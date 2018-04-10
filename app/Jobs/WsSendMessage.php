<?php

namespace STS\Jobs; 
use Illuminate\Contracts\Queue\ShouldQueue;


class WsSendMessage extends Job implements ShouldQueue
{
    public $message;
    public function __construct ($to, $message) {
        $this->message = $message;
        $this->to = $to;
    }

    function handle () {
        // nothings to implements
    }
}
