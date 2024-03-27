<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class UsersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $users;

    public function __construct(Collection $users)
    {
        $this->users = $users;
    }

    public function collection()
    {
        return $this->users->map(function ($user) {
            return [
                'Full Name' => $user->fullname,
                'Email' => $user->email,
                'Mobile' => $user->mobile,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Email',
            'Mobile',
        ];
    }
}
