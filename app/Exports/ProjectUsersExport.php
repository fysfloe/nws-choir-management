<?php

namespace App\Exports;

use App\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectUsersExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    /**
     * @var Project
     */
    private $project;

    /**
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
     * @return Collection
     */
    public function collection()
    {


        return $this->project->participants;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [__('Name')];
    }

    /**
     * @param mixed $user
     *
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->firstname,
            $user->surname
        ];
    }
}