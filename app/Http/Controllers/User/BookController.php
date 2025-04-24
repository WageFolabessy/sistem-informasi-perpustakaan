<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Enum\BookCopyStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

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
        $availableCopies = $book->copies->where('status', BookCopyStatus::Available)->count();

        return view('user.books.show', compact('book', 'totalCopies', 'availableCopies'));
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
