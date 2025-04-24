<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Borrowing;
use App\Enum\BorrowingStatus;
use Illuminate\View\View;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function history(): View
    {
        $user = Auth::user(); 
        $today = Carbon::today();

        $activeBorrowings = Borrowing::where('site_user_id', $user->id)
            ->whereIn('status', [BorrowingStatus::Borrowed, BorrowingStatus::Overdue])
            ->with([
                'bookCopy:id,copy_code,book_id',
                'bookCopy.book:id,slug,title,cover_image'
            ])
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($borrowing) use ($today) {
                $borrowing->is_overdue = $borrowing->due_date && Carbon::parse($borrowing->due_date)->startOfDay()->lt($today);
                return $borrowing;
            });

        $pastBorrowings = Borrowing::where('site_user_id', $user->id)
            ->whereIn('status', [BorrowingStatus::Returned, BorrowingStatus::Lost])
            ->with([
                'bookCopy:id,copy_code,book_id',
                'bookCopy.book:id,slug,title',
                'fine:id,amount,status'
            ])
            ->orderBy('return_date', 'desc')
            ->paginate(10);

        return view('user.borrowings.history', compact(
            'activeBorrowings',
            'pastBorrowings'
        ));
    }
}
