<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Enum\BookingStatus;
use App\Notifications\BookingExpiringSoonNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBookingReminders extends Command
{
    protected $signature = 'app:send-booking-reminders';

    protected $description = 'Kirim notifikasi pengingat untuk booking yang akan segera kadaluarsa (misal H-1)';

    public function handle()
    {
        $this->info('Memulai pengecekan booking yang akan kadaluarsa...');

        $now = Carbon::now();
        $reminderThreshold = Carbon::now()->addDay();

        $expiringBookings = Booking::where('status', BookingStatus::Active)
            ->where('expiry_date', '>', $now)
            ->where('expiry_date', '<=', $reminderThreshold)
            ->with('siteUser')
            ->get();

        if ($expiringBookings->isEmpty()) {
            $this->info('Tidak ada booking aktif yang akan segera kadaluarsa.');
            return Command::SUCCESS;
        }

        $this->info("Mengirim {$expiringBookings->count()} notifikasi booking akan kadaluarsa...");
        $sentCount = 0;

        foreach ($expiringBookings as $booking) {
            try {
                if ($booking->siteUser && $booking->siteUser->is_active) {
                    $alreadySent = $booking->siteUser->notifications()
                        ->where('type', BookingExpiringSoonNotification::class)
                        ->where('created_at', '>=', Carbon::today())
                        ->exists();
                    if (!$alreadySent) {
                        $booking->siteUser->notify(new BookingExpiringSoonNotification($booking));
                        $this->line("- Notif booking expiring dikirim ke User ID: {$booking->siteUser->id} untuk Booking ID: {$booking->id}");
                        $sentCount++;
                    } else {
                        $this->line("- Notif booking expiring untuk User ID: {$booking->siteUser->id} (Booking ID: {$booking->id}) sudah dikirim hari ini.");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Failed sending BookingExpiringSoon notification for Booking ID {$booking->id}: " . $e->getMessage());
                $this->error("Gagal kirim notif BookingExpiringSoon untuk Booking ID {$booking->id}.");
            }
        }

        $this->info("Selesai. {$sentCount} notifikasi booking akan kadaluarsa berhasil dikirim.");
        return Command::SUCCESS;
    }
}
