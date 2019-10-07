<?php

namespace App\Events;

use App\PublishedStory;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StoryPosted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $publishedStory;

    /**
     * Create a new event instance.
     *
     * @param PublishedStory $publishedStory
     */
    public function __construct(PublishedStory $publishedStory)
    {
        $this->publishedStory = $publishedStory;
    }
}
