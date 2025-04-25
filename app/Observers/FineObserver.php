<?php

namespace App\Observers;

use App\Models\Fine;
use App\Enum\FineStatus;
use App\Notifications\FineGeneratedNotification;
use Illuminate\Support\Facades\Log;

class FineObserver
{
    public function created(Fine $fine): void
    {
        if ($fine->status === FineStatus::Unpaid) {
            try {
                $user = $fine->borrowing?->siteUser;
                if ($user) {
                    $user->notify(new FineGeneratedNotification($fine));
                } else {
                    Log::warning("Cannot send FineGeneratedNotification: User not found for Fine ID {$fine->id} via Borrowing ID {$fine->borrowing_id}.");
                }
            } catch (\Exception $e) {
                Log::error("Error sending FineGeneratedNotification for Fine ID {$fine->id}: " . $e->getMessage());
            }
        }
    }
}
