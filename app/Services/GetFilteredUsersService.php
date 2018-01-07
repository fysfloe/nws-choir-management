<?php

namespace App\Services;

use App\User;

class GetFilteredUsersService {
    public function __construct()
    {
        //
    }

    public function handle($filters, $search, $sort = null, $dir)
    {
        if (!$sort) $sort = 'id';

        $voices = isset($filters['voices']) ? $filters['voices'] : null;
        $concerts = isset($filters['concerts']) ? $filters['concerts'] : null;

        $query = "SELECT users.* "
            . "FROM users ";

        $query .= " LEFT JOIN user_concert ON users.id = user_concert.user_id ";

        $query .= "WHERE users.deleted_at IS NULL AND (users.firstname LIKE '%$search%' OR users.surname LIKE '%$search%' OR users.email LIKE '%$search%') ";

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
        if ($concerts !== null && count($concerts) > 0) {
            $concerts = implode(',', $concerts);
            $query .= "AND user_concert.concert_id IN ($concerts) AND user_concert.accepted = 1 ";
        }

        $query .= "GROUP BY users.id ORDER BY $sort $dir";

        $users = User::fromQuery($query);

        return $users;
    }
}
