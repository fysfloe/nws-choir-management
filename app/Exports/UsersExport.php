<?php

namespace App\Exports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    /**
     * @return Collection
     */
    public function collection()
    {
        return User::orderBy('surname')->where('deleted_at', '=', null)->get();
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
            __('Username'),
            __('Voice'),
            __('Email'),
            __('Phone'),
            __('Birthdate'),
            __('Street'),
            __('ZIP'),
            __('City')
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
            $user->username,
            $user->voice->name,
            $user->email,
            $user->phone,
            $user->birthdate,
            $user->street,
            $user->zip,
            $user->city
        ];

        return $mappedUser;
    }
}