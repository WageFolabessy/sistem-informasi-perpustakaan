<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Enum\BookingStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// Import notifikasi jika ingin mengirim notif saat expired (opsional)
// use App\Notifications\BookingExpiredNotification;

class ProcessExpiredBookings extends Command
{
    protected $signature = 'app:process-expired-bookings';
    protected $description = 'Update status booking yang sudah melewati tanggal kadaluarsa';

    public function handle()
    {
        $this->info('Memulai proses update booking kadaluarsa...');
        $now = Carbon::now();
        $expiredBookings = Booking::where('status', BookingStatus::Active)
            ->where('expiry_date', '<', $now)
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('Tidak ada booking aktif yang kadaluarsa.');
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$expiredBookings->count()} booking kadaluarsa.");
        $processedCount = 0;

        foreach ($expiredBookings as $booking) {
            DB::beginTransaction();
            try {
                $booking->status = BookingStatus::Expired;
                $booking->notes = trim(($booking->notes ? $booking->notes . "\n\n---\n\n" : '') . "Otomatis diubah ke Expired pada " . $now->isoFormat('D MMM<y_bin_46>, HH:mm'));
                $booking->save();


                DB::commit();
                $processedCount++;
                $this->line("- Booking ID {$booking->id} diupdate ke Expired.");

                // if($booking->siteUser) {
                //     $booking->siteUser->notify(new BookingExpiredNotification($booking));
                // }

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Failed to expire Booking ID {$booking->id}: " . $e->getMessage());
                $this->error("Gagal memproses Booking ID {$booking->id}.");
            }
        }

        $this->info("Selesai. {$processedCount} booking berhasil diupdate.");
        return Command::SUCCESS;
    }
}
