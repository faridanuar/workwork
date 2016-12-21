<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Advert;

use App\Contracts\Search;

class PostingAdvert extends Event
{
    use SerializesModels;

    public $advert;
    public $search;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Advert $advert, Search $search)
    {
        $this->advert = $advert;
        $this->search = $search;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
