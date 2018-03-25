<?php

namespace STS\Events;

use Illuminate\Queue\SerializesModels;
use STS\Transformers\MessageTransformer;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSend extends Event implements ShouldBroadcast
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

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user-' . $this->to->id];
    }

    public function broadcastWith()
    {
        $tr = new MessageTransformer($this->to);
        $payload = $tr->transform($this->message);
        return $payload;
    }

    public function broadcastAs()
    {
        return 'new-message';
    }

}
