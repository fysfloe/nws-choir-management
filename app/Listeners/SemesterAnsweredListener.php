<?php

namespace App\Listeners;

use App\Events\SemesterAnsweredEvent;
use App\Events\ProjectAnsweredEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SemesterAnsweredListener
{
    /**
     * Handle the event.
     *
     * @param  SemesterAnsweredEvent  $event
     * @return void
     */
    public function handle(SemesterAnsweredEvent $event)
    {
        $semester = $event->semester;
        $user = $event->user;

        if (count($semester->projects) > 0) {
            foreach ($semester->projects as $project) {
                if ($project->is_main) {
                    $project->participants()->syncWithoutDetaching([$user->id => ['accepted' => true, 'voice_id' => $user->voice ? $user->voice->id : null]]);
                
                    event(new ProjectAnsweredEvent($project, $user, true)); 
                }
            }
        }
    }
}
