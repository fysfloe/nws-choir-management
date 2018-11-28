<?php

namespace App\Services;

use App\Project;

class GetFilteredProjectsService {
    public function handle($user, $search, $sort = 'title', $dir = 'ASC')
    {
        if (!$sort) $sort = 'id';

        $query = "SELECT projects.*, user_project.accepted AS accepted "
            . "FROM projects "
            . "LEFT JOIN user_project ON projects.id = user_project.project_id AND user_project.user_id = " . $user->id . " ";

        $query .= "WHERE projects.deleted_at IS NULL AND projects.title LIKE '%$search%' ";
        $query .= "GROUP BY projects.id ORDER BY projects.$sort $dir";

        $projects = Project::fromQuery($query);

        return $projects;
    }
}
