<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Enum\BookingStatus;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Requests\User\StoreUserBookingRequest;
use App\Models\Book;

class BookingController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $activeBookings = Booking::where('site_user_id', $user->id)
            ->where('status', BookingStatus::Active)
            ->with('book:id,slug,title,cover_image')
            ->orderBy('expiry_date', 'asc')
            ->get()
            ->map(function ($booking) use ($today, $tomorrow) {
                $expiryDate = $booking->expiry_date ? Carbon::parse($booking->expiry_date)->endOfDay() : null;

                $booking->is_expired = $expiryDate && $expiryDate->lt($today);
                $booking->is_expiring_soon = !$booking->is_expired && $expiryDate && $expiryDate->lt($tomorrow->copy()->addDay());

                return $booking;
            });

        $pastBookings = Booking::where('site_user_id', $user->id)
            ->whereIn('status', [
                BookingStatus::Expired,
                BookingStatus::ConvertedToLoan,
                BookingStatus::Cancelled
            ])
            ->with('book:id,slug,title')
            ->latest('updated_at')
            ->paginate(10, ['*'], 'riwayat_page');

        return view('user.bookings.index', compact(
            'activeBookings',
            'pastBookings'
        ));
    }

    public function store(StoreUserBookingRequest $request, Book $book): RedirectResponse
    {
        $user = Auth::user();

        $bookingExpiryDays = (int) setting('booking_expiry_days', 2);
        $now = Carbon::now();
        $expiryDate = $now->copy()->addDays($bookingExpiryDays)->endOfDay();

        DB::beginTransaction();
        try {
            Booking::create([
                'site_user_id' => $user->id,
                'book_id' => $book->id,
                'booking_date' => $now,
                'expiry_date' => $expiryDate,
                'status' => BookingStatus::Active,
                'notes' => 'Booking dibuat oleh pengguna.',
            ]);

            DB::commit();

            return redirect()->route('user.bookings.index')
                ->with('success', 'Booking untuk buku "' . $book->title . '" berhasil dicatat! Segera ambil buku sebelum batas waktu pengambilan: ' . $expiryDate->isoFormat('dddd, D MMMM YYYY HH:mm') . '.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating user booking for book ID: {$book->id}, User ID: {$user->id} - " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Maaf, terjadi kesalahan saat mencoba membuat booking. Silakan coba lagi.');
        }
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        if (Auth::id() !== $booking->site_user_id || $booking->status !== BookingStatus::Active) {
            return redirect()->route('user.bookings.index')
                ->with('error', 'Anda tidak dapat membatalkan booking ini.');
        }

        DB::beginTransaction();
        try {
            $booking->status = BookingStatus::Cancelled;
            $cancelNote = "Dibatalkan oleh pengguna pada " . now()->isoFormat('D MMM YYYY, HH:mm');
            $booking->notes = trim(($booking->notes ? $booking->notes . "\n\n---\n\n" : '') . $cancelNote);
            $booking->save();

            DB::commit();
            return redirect()->route('user.bookings.index')
                ->with('success', 'Booking untuk buku "' . $booking->book?->title . '" berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error User cancelling Booking ID: {$booking->id} - " . $e->getMessage());
            return redirect()->route('user.bookings.index')
                ->with('error', 'Gagal membatalkan booking: Terjadi kesalahan sistem.');
        }
    }
}
