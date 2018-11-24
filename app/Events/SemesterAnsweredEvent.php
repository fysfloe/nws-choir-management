<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Semester;
use App\User;

class SemesterAnsweredEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var User
     */
    public $user;
    /**
     * @var Semester 
     */
    public $semester;
    /**
     * @var bool
     */
    public $accepted;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Semester $semester, User $user, bool $accepted)
    {
        $this->semester = $semester;
        $this->user = $user;
        $this->accepted = $accepted;
    }
}
