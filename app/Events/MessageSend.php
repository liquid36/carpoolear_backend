<?php

namespace STS\Events;

use Illuminate\Queue\SerializesModels;
use STS\Transformers\MessageTransformer;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSend extends Event
{
    use SerializesModels;

    public $from;

    public $to;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($from, $to, $message)
    {
        $this->from = $from;
        $this->to = $to;
        $this->message = $message;
    }


}
