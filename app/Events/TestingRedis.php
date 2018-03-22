<?php

namespace STS\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestingRedis extends Event implements ShouldBroadcast
{
    use SerializesModels; 
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['user-notify'];
    }

    public function broadcastAs()
    {
        return 'new-message';
    }
}
