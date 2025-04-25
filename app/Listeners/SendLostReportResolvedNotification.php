<?php

namespace App\Listeners;

use App\Events\LostReportResolved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\LostReportResolvedNotification;

class SendLostReportResolvedNotification
{
    public function __construct() {}

    public function handle(LostReportResolved $event): void
    {
        $user = $event->lostReport->reporter;
        if ($user) {
            $user->notify(new LostReportResolvedNotification($event->lostReport));
        }
    }
}
