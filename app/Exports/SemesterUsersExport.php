<?php

namespace App\Exports;

use App\Rehearsal;
use App\Exports\ParticipantsExport;
use App\Semester;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SemesterUsersExport extends ParticipantsExport
{
    /**
     * @var Semester
     */
    private $semester;


    /**
     * @param Semester $semester
     * @param Collection $users
     */
    public function __construct(Semester $semester, Collection $users)
    {
        parent::__construct($users);
        $this->semester = $semester;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [
            '#',
            __('First Name'),
            __('Surname'),
            __('Email'),
            __('Voice')
        ];

        foreach ($this->semester->projects as $project) {
            $headings[] = $project->title;
        }

        return $headings;
    }

    /**
     * @param mixed $user
     *
     * @return array
     */
    public function map($user): array
    {
        $mappedUser = [
            $user->id,
            $user->firstname,
            $user->surname,
            $user->email,
            $user->voice->name
        ];

        foreach ($this->semester->projects as $project) {
            $participant = $project->participants->find($user);
            $accepted = '?';

            if ($participant) {
                if ($participant->pivot->accepted) {
                    $accepted = 'o';
                } else {
                    $accepted = 'x';
                }
            }

            $mappedUser[] = $accepted;
        }

        return $mappedUser;
    }
}
