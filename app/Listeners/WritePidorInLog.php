<?php

namespace App\Listeners;

use App\Events\UserBecamePidor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class WritePidorInLog implements ShouldQueue
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
     * @param UserBecamePidor $event
     * @return void
     */
    public function handle(UserBecamePidor $event)
    {
        $user = $event->user;

        DB::table('pidor_logs')->insert(['user_id' => $user->id]);

        $user->calcPidorRate();
    }
}
