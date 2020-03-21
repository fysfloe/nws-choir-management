<?php

namespace App\Services;

use App\Rehearsal;
use App\Semester;
use App\User;
use App\Project;
use App\Concert;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class GetFilteredUsersService
{
    const DEFAULT_SORT = 'surname';
    const DEFAULT_SORT_DIR = 'ASC';

    public function handle(array $filters, $search = '', $sort = self::DEFAULT_SORT, $dir = self::DEFAULT_SORT_DIR)
    {
        $qb = $this->getUsersQueryBuilder()
            ->leftJoin('user_concert', 'users.id', '=', 'user_concert.user_id')
            ->leftJoin('voices', 'voices.id', '=', 'users.voice_id');

        $this->addSearch($qb, $search);
        $this->addFilters($qb, $filters);
        $this->addSort($qb, $sort, $dir);

        return $qb->get();
    }

    public function concertParticipants(Concert $concert, array $filters, $search = '', $sort = self::DEFAULT_SORT, $dir = self::DEFAULT_SORT_DIR)
    {
        $qb = $this->getUsersQueryBuilder()
            ->select('users.*', 'voices.name as voiceName', 'voices.id as voiceId', 'user_concert.confirmed as confirmed', 'user_concert.excused as excused')
            ->leftJoin('user_concert', 'users.id', '=', 'user_concert.user_id')
            ->leftJoin('voices', 'voices.id', '=', 'user_concert.voice_id')
            ->where('users.non_singing', false)
            ->where(function ($query) use ($concert) {
                $query->where('user_concert.concert_id', '=', $concert->id)
                    ->orWhereNull('user_concert.concert_id');
            });

        $this->addSearch($qb, $search);
        $this->addFilters($qb, $filters, 'user_concert');
        $this->addSort($qb, $sort, $dir);

        return $qb->get();
    }

    public function projectParticipants(Project $project, array $filters, $search = '', $sort = self::DEFAULT_SORT, $dir = self::DEFAULT_SORT_DIR)
    {
        $qb = $this->getUsersQueryBuilder()
            ->leftJoin('user_project', 'users.id', '=', 'user_project.user_id')
            ->leftJoin('voices', 'voices.id', '=', 'user_project.voice_id')
            ->where('users.non_singing', false)
            ->where(function ($query) use ($project) {
                $query->where('user_project.project_id', '=', $project->id)
                    ->orWhereNull('user_project.project_id');
            });

        $this->addSearch($qb, $search);
        $this->addFilters($qb, $filters, 'user_project');
        $this->addSort($qb, $sort, $dir);

        return $qb->get();
    }

    public function rehearsalParticipants(Rehearsal $rehearsal, array $filters, $search = '', $sort = self::DEFAULT_SORT, $dir = self::DEFAULT_SORT_DIR)
    {
        $qb = $this->getUsersQueryBuilder()
            ->select('users.*', 'voices.name as voiceName', 'voices.id as voiceId', 'user_rehearsal.confirmed as confirmed', 'user_rehearsal.excused as excused')
            ->leftJoin('user_project', function ($join) use ($rehearsal) {
                $join->on('users.id', '=', 'user_project.user_id')
                    ->where('user_project.project_id', '=', $rehearsal->project->id);
            })
            ->leftJoin('user_rehearsal', 'users.id', '=', 'user_rehearsal.user_id')
            ->leftJoin('voices', 'voices.id', '=', 'users.voice_id')
            ->where('users.non_singing', false)
            ->where(function ($query) use ($rehearsal) {
                $query->where('user_rehearsal.rehearsal_id', '=', $rehearsal->id)
                    ->orWhereNull('user_rehearsal.rehearsal_id');
            });

        $this->addSearch($qb, $search);
        $this->addFilters($qb, $filters, 'user_rehearsal', 'user_project');
        $this->addSort($qb, $sort, $dir);

        return $qb->get();
    }

    public function semesterParticipants(Semester $semester, array $filters, $search = '', $sort = self::DEFAULT_SORT, $dir = self::DEFAULT_SORT_DIR)
    {
        $qb = $this->getUsersQueryBuilder()
            ->leftJoin('user_semester', 'users.id', '=', 'user_semester.user_id')
            ->leftJoin('voices', 'voices.id', '=', 'users.voice_id')
            ->where('users.non_singing', false)
            ->where(function ($query) use ($semester) {
                $query->where('user_semester.semester_id', '=', $semester->id)
                ->orWhereNull('user_semester.semester_id');
            });

        $this->addSearch($qb, $search);
        $this->addFilters($qb, $filters, 'user_semester');
        $this->addSort($qb, $sort, $dir);

        return $qb->get();
    }

    private function getUsersQueryBuilder(): Builder
    {
        return User::select('users.*', 'voices.name as voiceName', 'voices.id as voiceId')
            ->distinct()
            ->whereNull('users.deleted_at')
            ->groupBy('users.id');
    }

    /**
     * @param  Builder $query
     * @param  array  $filters
     * @param  string $relationTableAlias
     * @return Builder
     */
    private function addFilters(
        Builder $qb,
        array $filters,
        string $relationTableAlias = null,
        string $acceptedWhenNotAnsweredAlias = null
    ): Builder {
        if (null !== $relationTableAlias) {
            if (isset($filters['accepted'])) {
                if ($filters['accepted'] === 'not answered') {
                    $qb->where(function ($query) use ($relationTableAlias, $acceptedWhenNotAnsweredAlias) {
                        $query->whereNull("$relationTableAlias.accepted");

                        if (null !== $acceptedWhenNotAnsweredAlias) {
                            $query->where("$acceptedWhenNotAnsweredAlias.accepted", true);
                        }
                    });
                } else {
                    $qb->where("$relationTableAlias.accepted", $filters['accepted']);
                }
            } else {
                $qb->where("$relationTableAlias.accepted", true);
            }
        }

        $ageFrom = isset($filters['age-from']) ? $filters['age-from'] : null;

        if ($ageFrom) {
            $minDate = (new \DateTime("- $ageFrom years"))->format('Y-m-d');
            $qb->where('users.birthdate', '<', $minDate);
        }

        $ageTo = isset($filters['age-to']) ? $filters['age-to'] : null;

        if ($ageTo) {
            $ageTo += 1;
            $maxDate = (new \DateTime("- $ageTo years"))->format('Y-m-d');
            $qb->where('users.birthdate', '>=', $maxDate);
        }

        $voices = isset($filters['voices']) ? $filters['voices'] : null;

        if ($voices !== null && count($voices) > 0) {
            $qb->whereIn('voices.id', $voices);
        }

        return $qb;
    }

    /**
     * @param  string $query
     * @param  string $sort
     * @param  string $sortDir
     * @return string
     */
    private function addSort(Builder $qb, $sort = self::DEFAULT_SORT, $sortDir = self::DEFAULT_SORT_DIR): Builder
    {
        if (!$sort) {
            $sort = 'surname';
        }

        if ($sort === 'voice') {
            $sort = 'voices.rank';
        }

        $qb->orderBy($sort, $sortDir);

        return $qb;
    }

    /**
     * @param  Builder $query
     * @param  string $search
     * @return Builder
     */
    private function addSearch(Builder $qb, $search = ''): Builder
    {
        $qb->where(function ($query) use ($search) {
            $query->where('users.firstname', 'like', "%$search%")
                ->orWhere('users.surname', 'like', "%$search%")
                ->orWhere('users.email', 'like', "%$search%");

        });
        return $qb;
    }
}
