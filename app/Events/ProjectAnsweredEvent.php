<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Project;
use App\User;

class ProjectAnsweredEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var User
     */
    public $user;
    /**
     * @var Project
     */
    public $project;
    /**
     * @var bool
     */
    public $accepted;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Project $project, User $user, bool $accepted)
    {
        $this->project = $project;
        $this->user = $user;
        $this->accepted = $accepted;
    }
}
