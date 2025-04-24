<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Enum\BookCopyStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Enum\BorrowingStatus;
use App\Enum\BookingStatus;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $searchQuery = $request->input('search');
        $categoryFilter = $request->input('category');

        $booksQuery = Book::with(['author:id,name', 'category:id,name'])
            ->select(['id', 'title', 'slug', 'author_id', 'category_id', 'cover_image', 'synopsis'])
            ->orderBy('title', 'asc');

        if ($searchQuery) {
            $booksQuery->where(function ($query) use ($searchQuery) {
                $query->where('title', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('isbn', 'LIKE', "%{$searchQuery}%")
                    ->orWhereHas('author', function ($qAuthor) use ($searchQuery) {
                        $qAuthor->where('name', 'LIKE', "%{$searchQuery}%");
                    })
                    ->orWhereHas('publisher', function ($qPublisher) use ($searchQuery) {
                        $qPublisher->where('name', 'LIKE', "%{$searchQuery}%");
                    });
                // ->orWhere('synopsis', 'LIKE', "%{$searchQuery}%");
            });
        }

        if ($categoryFilter) {
            $booksQuery->where('category_id', $categoryFilter);
        }

        $books = $booksQuery->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('user.books.index', compact('books', 'categories', 'searchQuery', 'categoryFilter'));
    }

    public function show(Book $book): View
    {
        $book->load(['author', 'publisher', 'category', 'copies']);

        $totalCopies = $book->copies->count();
        $availableCopiesCount = $book->copies->where('status', BookCopyStatus::Available)->count();

        $userStatus = null;
        $statusDetails = null;
        $user = Auth::user();

        if ($user && $user->is_active) {
            $activeLoan = $user->borrowings()
                ->whereIn('status', [BorrowingStatus::Borrowed, BorrowingStatus::Overdue])
                ->whereHas('bookCopy', fn($q) => $q->where('book_id', $book->id))
                ->first(['id', 'due_date']);
            if ($activeLoan) {
                $userStatus = 'borrowing';
                $statusDetails = $activeLoan->due_date;
            } else {
                $activeBooking = $user->bookings()
                    ->where('book_id', $book->id)
                    ->where('status', BookingStatus::Active)
                    ->first(['id', 'expiry_date']);
                if ($activeBooking) {
                    $userStatus = 'booked';
                    $statusDetails = $activeBooking->expiry_date;
                } else {
                    if ($totalCopies <= 0) {
                        $userStatus = 'unavailable';
                        $statusDetails = 'Tidak ada eksemplar terdaftar untuk buku ini.';
                    } elseif ($availableCopiesCount <= 0) {
                        $userStatus = 'unavailable';
                        $statusDetails = 'Stok semua eksemplar sedang dipinjam atau dibooking.';
                    } else {
                        $maxBookings = (int) setting('max_active_bookings', 2);
                        $currentActiveBookings = $user->bookings()->where('status', BookingStatus::Active)->count();
                        if ($currentActiveBookings >= $maxBookings) {
                            $userStatus = 'limit_reached';
                            $statusDetails = $maxBookings;
                        } else {
                            $userStatus = 'can_book';
                        }
                    }
                }
            }
        } elseif ($user && !$user->is_active) {
            $userStatus = 'inactive';
        } else {
            $userStatus = 'guest';
        }

        return view('user.books.show', compact(
            'book',
            'totalCopies',
            'availableCopiesCount',
            'userStatus',
            'statusDetails'
        ));
    }

    public function searchApi(Request $request): JsonResponse
    {
        $searchQuery = $request->input('search');

        $booksQuery = Book::with(['author:id,name', 'category:id,name'])
            ->select(['id', 'title', 'slug', 'author_id', 'category_id', 'cover_image', 'synopsis'])
            ->orderBy('title', 'asc');

        if ($searchQuery) {
            $booksQuery->where(function ($query) use ($searchQuery) {
                $query->where('title', 'LIKE', "%{$searchQuery}%")
                    ->orWhere('isbn', 'LIKE', "%{$searchQuery}%")
                    ->orWhereHas('author', function ($qAuthor) use ($searchQuery) {
                        $qAuthor->where('name', 'LIKE', "%{$searchQuery}%");
                    })
                    ->orWhereHas('publisher', function ($qPublisher) use ($searchQuery) {
                        $qPublisher->where('name', 'LIKE', "%{$searchQuery}%");
                    });
            });
        } else {
            return response()->json(['html' => '<div class="col-12 text-center text-muted">Masukkan kata kunci pencarian.</div>']);
        }

        $books = $booksQuery->take(12)->get();

        $html = view('user.books._book_list', compact('books'))->render();

        return response()->json(['html' => $html]);
    }
}
