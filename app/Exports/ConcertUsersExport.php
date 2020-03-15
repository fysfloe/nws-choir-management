<?php

namespace App\Exports;

use App\Rehearsal;
use App\Exports\ParticipantsExport;
use App\Concert;
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

class ConcertUsersExport extends ParticipantsExport
{
    /**
     * @var Concert
     */
    private $concert;

    /**
     * @param Concert $concert
     * @param Collection $users
     */
    public function __construct(Concert $concert, Collection $users)
    {
        parent::__construct($users);
        $this->concert = $concert;
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
            __('Voice'),
            __('Answer')
        ];

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

        $participant = $this->concert->participants->find($user);

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

        return $mappedUser;
    }
}
