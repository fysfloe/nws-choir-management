<?php

namespace App\Exports;

use App\Rehearsal;
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

abstract class ParticipantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    use Exportable;

    /**
     * @var Collection
     */
    protected $users;

    /**
     * @param Collection $users
     */
    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->users;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:Z1')->getFont()->setBold(true);

                $conditional1 = new Conditional();
                $conditional1->setConditionType(Conditional::CONDITION_CONTAINSTEXT);
                $conditional1->setOperatorType(Conditional::OPERATOR_BEGINSWITH);
                $conditional1->setText('x');
                $conditional1->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                $conditional1->getStyle()->getFill()->getEndColor()->setRGB('F5C6CB');

                $conditional2 = new Conditional();
                $conditional2->setConditionType(Conditional::CONDITION_CONTAINSTEXT);
                $conditional2->setOperatorType(Conditional::OPERATOR_BEGINSWITH);
                $conditional2->setText('o');
                $conditional2->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                $conditional2->getStyle()->getFill()->getEndColor()->setRGB('C3E6CB');

                $conditional3 = new Conditional();
                $conditional3->setConditionType(Conditional::CONDITION_CONTAINSTEXT);
                $conditional3->setOperatorType(Conditional::OPERATOR_BEGINSWITH);
                $conditional3->setText('e');
                $conditional3->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
                $conditional3->getStyle()->getFill()->getEndColor()->setRGB('FFEEBA');

                $conditions = [$conditional1, $conditional2, $conditional3];

                $event->sheet->getDelegate()->getStyle('E2:Z200')->setConditionalStyles($conditions);
            },
        ];
    }
}
