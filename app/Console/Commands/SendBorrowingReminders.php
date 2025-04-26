<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrowing;
use App\Enum\BorrowingStatus;
use App\Notifications\BorrowingDueSoonNotification;
use App\Notifications\BorrowingOverdueNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\SiteUser;

class SendBorrowingReminders extends Command
{
    protected $signature = 'app:send-borrowing-reminders';
    protected $description = 'Send due soon/overdue notifications and update overdue status';

    public function handle()
    {
        $this->info('Memulai pengecekan peminjaman...');
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $dueSoonBorrowings = Borrowing::where('status', BorrowingStatus::Borrowed)
            ->whereDate('due_date', $tomorrow)
            ->whereHas('siteUser', fn($q) => $q->where('is_active', true))
            ->with('siteUser')
            ->get();

        if ($dueSoonBorrowings->isNotEmpty()) {
            $this->info("Mengirim {$dueSoonBorrowings->count()} notifikasi jatuh tempo besok...");
            foreach ($dueSoonBorrowings as $borrowing) {
                try {
                    $borrowing->siteUser->notify(new BorrowingDueSoonNotification($borrowing));
                    $this->line("- Notif jatuh tempo dikirim ke User ID: {$borrowing->siteUser->id} untuk Borrowing ID: {$borrowing->id}");
                } catch (\Exception $e) {
                    Log::error("Failed sending DueSoon notification for Borrowing ID {$borrowing->id}: " . $e->getMessage());
                    $this->error("Gagal kirim notif DueSoon untuk Borrowing ID {$borrowing->id}.");
                }
            }
        } else {
            $this->info("Tidak ada peminjaman yang jatuh tempo besok.");
        }


        $newlyOverdueBorrowings = Borrowing::where('status', BorrowingStatus::Borrowed)
            ->whereDate('due_date', '<', $today)
            ->whereHas('siteUser', fn($q) => $q->where('is_active', true))
            ->with('siteUser')
            ->get();

        if ($newlyOverdueBorrowings->isNotEmpty()) {
            $this->info("Menemukan {$newlyOverdueBorrowings->count()} peminjaman yang baru lewat tempo. Mengupdate status & mengirim notif...");
            foreach ($newlyOverdueBorrowings as $borrowing) {
                DB::beginTransaction();
                try {
                    $borrowing->status = BorrowingStatus::Overdue;
                    $borrowing->save();

                    $borrowing->siteUser->notify(new BorrowingOverdueNotification($borrowing));
                    $this->line("- Status Borrowing ID {$borrowing->id} diupdate ke Overdue & notif dikirim ke User ID: {$borrowing->siteUser->id}");

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error("Failed updating status/sending Overdue notification for Borrowing ID {$borrowing->id}: " . $e->getMessage());
                    $this->error("Gagal proses Overdue untuk Borrowing ID {$borrowing->id}.");
                }
            }
        } else {
            $this->info("Tidak ada peminjaman Borrowed yang perlu diupdate ke Overdue.");
        }

        $this->info('Pengecekan peminjaman selesai.');
        return Command::SUCCESS;
    }
}
