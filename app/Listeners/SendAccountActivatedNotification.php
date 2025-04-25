<?php

namespace App\Listeners;

use App\Events\UserAccountActivated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\AccountActivatedNotification;

class SendAccountActivatedNotification
{
    public function __construct() {}

    public function handle(UserAccountActivated $event): void
    {
        $event->user->notify(new AccountActivatedNotification());
    }
}
