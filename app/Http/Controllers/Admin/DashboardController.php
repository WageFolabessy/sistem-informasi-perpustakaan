<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\SiteUser;
use App\Models\Borrowing;
use App\Enum\BorrowingStatus;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBooks = Book::count();
        $totalCopies = BookCopy::count();
        $activeStudents = SiteUser::where('is_active', true)->count();
        $activeBorrowings = Borrowing::whereIn('status', [
            BorrowingStatus::Borrowed,
            BorrowingStatus::Overdue
        ])->count();

        $recentBorrowings = Borrowing::with([
            'siteUser:id,name',
            'bookCopy:id,copy_code,book_id',
            'bookCopy.book:id,title'
        ])
            ->latest('borrow_date')
            ->take(10)
            ->get();

        return view('admin.index', compact(
            'totalBooks',
            'totalCopies',
            'activeStudents',
            'activeBorrowings',
            'recentBorrowings'
        ));
    }
}
