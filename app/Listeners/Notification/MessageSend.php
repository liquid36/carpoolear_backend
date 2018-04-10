<?php

namespace STS\Listeners\Notification;

use STS\Events\MessageSend as SendEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use STS\Notifications\NewMessageNotification;
use STS\Transformers\MessageTransformer;

class MessageSend implements ShouldQueue
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
     * @param  SendEvent  $event
     * @return void
     */
    public function handle(SendEvent $event)
    {
        $from = $event->from;
        $to = $event->to;
        $message = $event->message;
        $notification = new NewMessageNotification();
        $notification->setAttribute('from', $from);
        $notification->setAttribute('messages', $message);
        $notification->notify($to);

        $tr = new MessageTransformer($to);
        $payload = $tr->transform($message);
        $job = (new \STS\Jobs\WsSendMessage($to->id, $payload))->onConnection('redis');
        dispatch($job);

    }
}
