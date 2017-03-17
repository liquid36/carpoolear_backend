<?php

namespace STS\Events\Friend;

use Illuminate\Queue\SerializesModels;
use STS\Events\Event;

class Request extends Event
{
    use SerializesModels;

    protected $from;
    protected $to;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
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
