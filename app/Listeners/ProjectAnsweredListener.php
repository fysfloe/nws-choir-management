<?php

namespace App\Listeners;

use App\Events\ProjectAnsweredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectAnsweredListener
{
    /**
     * Handle the event.
     *
     * @param  ProjectAnsweredEvent  $event
     * @return void
     */
    public function handle(ProjectAnsweredEvent $event)
    {
        $now = new \DateTime();

        foreach ($event->project->concerts as $concert) {
            if ($concert->getDateTime() > $now) {
                $concert->participants()->syncWithoutDetaching([$event->user->id => ['accepted' => $event->accepted]]);
            }
        }

        foreach ($event->project->rehearsals as $rehearsal) {
            if ($rehearsal->getDateTime() > $now) {
                $rehearsal->participants()->syncWithoutDetaching([$event->user->id => ['accepted' => $event->accepted]]);
            }
        }
    }
}
