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
    /**
     * Menampilkan riwayat peminjaman (aktif dan lampau) untuk user yang login.
     */
    public function history(): View
    {
        $user = Auth::user(); // Ambil siswa yang sedang login (guard 'web')
        $today = Carbon::today();

        // Ambil Peminjaman Aktif (Dipinjam / Lewat Tempo)
        $activeBorrowings = Borrowing::where('site_user_id', $user->id)
            ->whereIn('status', [BorrowingStatus::Borrowed, BorrowingStatus::Overdue])
            ->with([
                'bookCopy:id,copy_code,book_id',
                'bookCopy.book:id,slug,title,cover_image' // Ambil data buku yg relevan
            ])
            ->orderBy('due_date', 'asc') // Urutkan berdasarkan jatuh tempo terdekat
            ->get()
            ->map(function ($borrowing) use ($today) { // Tambahkan flag overdue
                $borrowing->is_overdue = $borrowing->due_date && Carbon::parse($borrowing->due_date)->startOfDay()->lt($today);
                return $borrowing;
            });

        // Ambil Riwayat Peminjaman Lampau (Dikembalikan / Hilang) - dengan pagination
        $pastBorrowings = Borrowing::where('site_user_id', $user->id)
            ->whereIn('status', [BorrowingStatus::Returned, BorrowingStatus::Lost])
            ->with([
                'bookCopy:id,copy_code,book_id',
                'bookCopy.book:id,slug,title',
                'fine:id,amount,status' // Ambil info denda jika ada
            ])
            ->orderBy('return_date', 'desc') // Urutkan berdasarkan tanggal kembali terbaru
            ->paginate(10); // Misal 10 item per halaman

        return view('user.borrowings.history', compact(
            'activeBorrowings',
            'pastBorrowings'
        ));
    }
}
