<?php

namespace App\Services;

use App\Rehearsal;
use App\Semester;
use App\User;

class GetFilteredUsersService {
    public function __construct()
    {
        //
    }

    public function handle($filters, $search, $sort = 'surname', $dir = 'ASC')
    {
        if (!$sort) $sort = 'surname';

        $voices = isset($filters['voices']) ? $filters['voices'] : null;
        $concerts = isset($filters['concerts']) ? $filters['concerts'] : null;

        $query = "SELECT users.* "
            . "FROM users ";

        $query .= " LEFT JOIN user_concert ON users.id = user_concert.user_id ";
        $query .= " LEFT JOIN voices as voice ON voice.id = users.voice_id ";

        $query .= "WHERE users.deleted_at IS NULL AND (users.firstname LIKE '%$search%' OR users.surname LIKE '%$search%' OR users.email LIKE '%$search%') ";

        $ageFrom = isset($filters['ageFrom']) ? $filters['ageFrom'] : null;
        if ($ageFrom) {
            $minDate = (new \DateTime("- $ageFrom years"))->format('Y-m-d');
            $query .= "AND users.birthdate < '$minDate' ";
        }

        $ageTo = isset($filters['ageTo']) ? $filters['ageTo'] : null;
        if ($ageTo) {
            $ageTo += 1;
            $maxDate = (new \DateTime("- $ageTo years"))->format('Y-m-d');
            $query .= "AND users.birthdate >= '$maxDate' ";
        }
        if ($voices !== null && count($voices) > 0) {
            $voices = implode(',', $voices);
            $query .= "AND users.voice_id IN ($voices) ";
        }
        if ($concerts !== null && count($concerts) > 0) {
            $concerts = implode(',', $concerts);
            $query .= "AND user_concert.concert_id IN ($concerts) AND user_concert.accepted = 1 ";
        }

        if ($sort === 'id') {
            $sort = 'users.id';
        }

        if ($sort === 'voice') {
            $sort = 'voice.rank';
        }

        $query .= "GROUP BY users.id ORDER BY $sort $dir";

        $users = User::fromQuery($query);

        return $users;
    }

    public function concertParticipants($concert, $filters, $search, $sort = 'surname', $dir = 'ASC')
    {
        if (!$sort) $sort = 'surname';

        $voices = isset($filters['voices']) ? $filters['voices'] : null;

        $query = "SELECT users.*, voices.name as voiceName, user_concert.confirmed as confirmed, user_concert.excused as excused "
            . "FROM users
            LEFT OUTER JOIN user_concert ON users.id = user_concert.user_id
            LEFT OUTER JOIN voices ON voices.id = user_concert.voice_id ";

        $query .= "WHERE users.deleted_at IS NULL
        AND users.non_singing = 0
        AND user_concert.concert_id = $concert->id
        AND user_concert.accepted = 1
        AND (users.firstname LIKE '%$search%' OR users.surname LIKE '%$search%' OR users.email LIKE '%$search%') ";

        $ageFrom = isset($filters['age-from']) ? $filters['age-from'] : null;
        if ($ageFrom) {
            $minDate = (new \DateTime("- $ageFrom years"))->format('Y-m-d');
            $query .= "AND users.birthdate < '$minDate' ";
        }

        $ageTo = isset($filters['age-to']) ? $filters['age-to'] : null;
        if ($ageTo) {
            $ageTo += 1;
            $maxDate = (new \DateTime("- $ageTo years"))->format('Y-m-d');
            $query .= "AND users.birthdate >= '$maxDate' ";
        }
        if ($voices !== null && count($voices) > 0) {
            $voices = implode(',', $voices);
            $query .= "AND voices.id IN ($voices) ";
        }

        $query .= "GROUP BY users.id, voices.id ORDER BY $sort $dir";

        $users = User::fromQuery($query);

        return $users;
    }

    public function projectParticipants($project, $filters, $search = '', $sort = 'surname', $dir = 'ASC')
    {
        if (!$sort) $sort = 'surname';

        $voices = isset($filters['voices']) ? $filters['voices'] : null;

        $query = "SELECT users.*, voices.name as voiceName, voices.id as voiceId "
            . "FROM users
            LEFT OUTER JOIN user_project ON users.id = user_project.user_id
            LEFT OUTER JOIN voices ON voices.id = user_project.voice_id ";

        $query .= "WHERE users.deleted_at IS NULL
        AND users.non_singing = 0
        AND (user_project.project_id = $project->id OR user_project.project_id IS NULL) 
        AND (users.firstname LIKE '%$search%' OR users.surname LIKE '%$search%' OR users.email LIKE '%$search%') ";

        if (isset($filters['accepted'])) {
            if ($filters['accepted'] === 'not answered') {
                $query .= "AND user_project.accepted IS NULL ";
            } else {
                $query .= "AND user_project.accepted = " . $filters['accepted'] . " ";
            }
        } else {
            $query .= "AND user_project.accepted = 1 ";
        }
        
        $ageFrom = isset($filters['age-from']) ? $filters['age-from'] : null;
        if ($ageFrom) {
            $minDate = (new \DateTime("- $ageFrom years"))->format('Y-m-d');
            $query .= "AND users.birthdate < '$minDate' ";
        }

        $ageTo = isset($filters['age-to']) ? $filters['age-to'] : null;
        if ($ageTo) {
            $ageTo += 1;
            $maxDate = (new \DateTime("- $ageTo years"))->format('Y-m-d');
            $query .= "AND users.birthdate >= '$maxDate' ";
        }
        if ($voices !== null && count($voices) > 0) {
            $voices = implode(',', $voices);
            $query .= "AND voices.id IN ($voices) ";
        }

        if ($sort === 'voice') {
            $sort = 'voices.rank';
        }

        $query .= "GROUP BY users.id, voices.id ORDER BY $sort $dir";

        $users = User::fromQuery($query);

        return $users;
    }

    public function rehearsalParticipants(Rehearsal $rehearsal, $filters, $search, $sort = 'surname', $dir = 'ASC')
    {
        if (!$sort) $sort = 'surname';

        $voices = isset($filters['voices']) ? $filters['voices'] : null;

        $query = "SELECT users.*, user_rehearsal.confirmed as confirmed, user_rehearsal.excused as excused"
            . " FROM users 
            LEFT OUTER JOIN user_project ON users.id = user_project.user_id 
            LEFT OUTER JOIN user_rehearsal ON users.id = user_rehearsal.user_id";
        
        $query .= " LEFT JOIN voices as voice ON voice.id = users.voice_id ";

        $query .= "WHERE users.deleted_at IS NULL
        AND users.non_singing = 0
        AND user_rehearsal.rehearsal_id = $rehearsal->id
        AND user_rehearsal.accepted = 1
        AND (users.firstname LIKE '%$search%' OR users.surname LIKE '%$search%' OR users.email LIKE '%$search%') ";

        $ageFrom = isset($filters['age-from']) ? $filters['age-from'] : null;
        if ($ageFrom) {
            $minDate = (new \DateTime("- $ageFrom years"))->format('Y-m-d');
            $query .= "AND users.birthdate < '$minDate' ";
        }

        $ageTo = isset($filters['age-to']) ? $filters['age-to'] : null;
        if ($ageTo) {
            $ageTo += 1;
            $maxDate = (new \DateTime("- $ageTo years"))->format('Y-m-d');
            $query .= "AND users.birthdate >= '$maxDate' ";
        }
        if ($voices !== null && count($voices) > 0) {
            $voices = implode(',', $voices);
            $query .= "AND voice.id IN ($voices) ";
        }

        if ($sort === 'voice') {
            $sort = 'voice.rank';
        }

        $query .= "GROUP BY users.id ORDER BY $sort $dir";

        $users = User::fromQuery($query);

        return $users;
    }

    public function semesterParticipants(Semester $semester, $filters, $search, $sort = 'surname', $dir = 'ASC')
    {
        if (!$sort) $sort = 'surname';

        $voices = isset($filters['voices']) ? $filters['voices'] : null;

        $query = "SELECT users.* "
            . "FROM users
            LEFT OUTER JOIN user_semester ON users.id = user_semester.user_id ";
        
        $query .= " LEFT JOIN voices as voice ON voice.id = users.voice_id ";

        $query .= "WHERE users.deleted_at IS NULL
        AND user_semester.semester_id = $semester->id
        AND user_semester.accepted = 1
        AND (users.firstname LIKE '%$search%' OR users.surname LIKE '%$search%' OR users.email LIKE '%$search%') ";

        $ageFrom = isset($filters['age-from']) ? $filters['age-from'] : null;        
        if ($ageFrom) {
            $minDate = (new \DateTime("- $ageFrom years"))->format('Y-m-d');
            $query .= "AND users.birthdate < '$minDate' ";
        }

        $ageTo = isset($filters['age-to']) ? $filters['age-to'] : null;       
        if ($ageTo) {
            $ageTo += 1;
            $maxDate = (new \DateTime("- $ageTo years"))->format('Y-m-d');
            $query .= "AND users.birthdate >= '$maxDate' ";
        }
        if ($voices !== null && count($voices) > 0) {
            $voices = implode(',', $voices);
            $query .= "AND users.voice_id IN ($voices) ";
        }

        $query .= "GROUP BY users.id ORDER BY $sort $dir";

        $users = User::fromQuery($query);

        return $users;
    }
}
