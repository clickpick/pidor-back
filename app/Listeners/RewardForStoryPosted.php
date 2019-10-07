<?php

namespace App\Listeners;

use App\Events\StoryPosted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RewardForStoryPosted
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
     * @param StoryPosted $event
     * @return void
     */
    public function handle(StoryPosted $event)
    {
        $publishedStory = $event->publishedStory;

        $publishedStory->payReward();
    }
}
