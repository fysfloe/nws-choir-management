<?php

namespace App\Exports;

use App\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

class ProjectUsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    use Exportable;

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
     */
    public function __construct(Project $project)
    {
        $this->project = $project;
        $this->concertsAndRehearsals = array_merge($this->project->rehearsals->all(), $this->project->concerts->all());

        usort($this->concertsAndRehearsals, function ($a, $b) {
            return $a->date > $b->date;
        });
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
        $headings = [
            '#',
            __('First Name'),
            __('Surname'),
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
            $user->email
        ];

        foreach ($this->concertsAndRehearsals as $concertOrRehearsal) {
            $mappedUser[] = $concertOrRehearsal->participants->contains($user) ? 'o' : 'x';
        }

        return $mappedUser;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:Z1')->getFont()->setBold(true);

                $conditions = [];
                $conditional1 = new Conditional();
                $conditional1->setConditionType(Conditional::CONDITION_CONTAINSTEXT);
                $conditional1->setOperatorType(Conditional::OPERATOR_EQUAL);
                $conditional1->addCondition('x');
                $conditional1->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_RED);

                $conditions[] = $conditional1;

                $event->sheet->getDelegate()->getStyle('E2:Z200')->setConditionalStyles($conditions);
            },
        ];
    }
}