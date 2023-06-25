<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendVerificationCode
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $email;
    public $verificationCode;

    /**
     * Create a new event instance.
     *
     * @param string $email
     * @param string $verificationCode
     * @return void
     */
    public function __construct($email, $verificationCode)
    {
        $this->email = $email;
        $this->verificationCode = $verificationCode;
        // dd($email, $verificationCode);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
