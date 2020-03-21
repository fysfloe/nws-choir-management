<?php

namespace App\Traits;

use App\User;
use App\Interfaces\AttendanceAware;

trait AttendanceTrait
{
    protected function doConfirm(AttendanceAware $attendanceAware, User $user): void
    {
        $participant = $attendanceAware->participants->find($user);
        if (!$participant || !$participant->pivot->confirmed) {
            $attendanceAware->promises()->syncWithoutDetaching([$user->id => ['accepted' => true, 'confirmed' => true, 'excused' => false]]);
        } else {
            $attendanceAware->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }
    }

    protected function doExcuse(AttendanceAware $attendanceAware, User $user): void
    {
        $participant = $attendanceAware->participants->find($user);
        if (!$participant || !$participant->pivot->excused) {
            $attendanceAware->promises()->syncWithoutDetaching([$user->id => ['accepted' => true, 'confirmed' => false, 'excused' => true]]);
        } else {
            $attendanceAware->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }
    }

    protected function doSetUnexcused(AttendanceAware $attendanceAware, User $user): void
    {
        $participant = $attendanceAware->participants->find($user);
        if (!$participant || !$participant->pivot->excused) {
            $attendanceAware->promises()->syncWithoutDetaching([$user->id => ['accepted' => true, 'confirmed' => false, 'excused' => false]]);
        } else {
            $attendanceAware->promises()->syncWithoutDetaching([$user->id => ['confirmed' => null, 'excused' => null]]);
        }
    }
}
