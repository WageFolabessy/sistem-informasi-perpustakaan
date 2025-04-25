<?php

namespace App\Listeners;

use App\Events\BookingCancelledByAdmin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\BookingCancelledNotification;

class SendBookingCancelledNotification
{
    public function __construct() {}

    public function handle(BookingCancelledByAdmin $event): void
    {
        $user = $event->booking->siteUser;
        if ($user) {
            $user->notify(new BookingCancelledNotification($event->booking));
        }
    }
}
