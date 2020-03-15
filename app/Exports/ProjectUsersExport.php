<?php

namespace App\Exports;

use App\Project;
use App\Exports\ParticipantsExport;
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

class ProjectUsersExport extends ParticipantsExport
{
    /**
     * @var Project
     */
    private $project;
    /**
     * @var array
     */
    private $concertsAndRehearsals;

    /**
     * @param Project $project
     * @param Collection $users
     */
    public function __construct(Project $project, Collection $users)
    {
        parent::__construct($users);
        $this->project = $project;
        $this->concertsAndRehearsals = array_merge($this->project->rehearsals->all(), $this->project->concerts->all());

        usort($this->concertsAndRehearsals, function ($a, $b) {
            return $a->date > $b->date;
        });
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
            __('Voice'),
            __('Email')
        ];

        foreach ($this->concertsAndRehearsals as $concertOrRehearsal) {
            $headings[] = $concertOrRehearsal->spreadSheetHeading();
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
            $user->voiceName,
            $user->email
        ];

        foreach ($this->concertsAndRehearsals as $concertOrRehearsal) {
            $participant = $concertOrRehearsal->participants->find($user);
            $accepted = '?';

            if ($participant) {
                if ($participant->pivot->accepted && $participant->pivot->confirmed) {
                    $accepted = 'o';
                } else if ($participant->pivot->excused) {
                    $accepted = 'e';
                } else {
                    $accepted = 'x';
                }
            }

            $mappedUser[] = $accepted;
        }

        return $mappedUser;
    }
}
