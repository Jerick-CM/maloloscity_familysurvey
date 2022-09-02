<?php

namespace App\Listeners;

use App\Events\UserLogsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Logs;
class UserLogsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserLogsEvent  $event
     * @return void
     */
    public function handle(UserLogsEvent $event)
    {
        Logs::create([
            'user_id' => $event->user_id,
            'type' => $event->type,
            'meta' => $event->meta,
        ]);
    }
}
