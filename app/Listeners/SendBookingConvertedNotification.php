<?php

namespace App\Listeners;

use App\Events\BookingConvertedToLoan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\BookingConvertedNotification;

class SendBookingConvertedNotification
{
    public function __construct() {}

    public function handle(BookingConvertedToLoan $event): void
    {
        $user = $event->booking->siteUser;
        if ($user) {
            $user->notify(new BookingConvertedNotification($event->booking));
        }
    }
}
