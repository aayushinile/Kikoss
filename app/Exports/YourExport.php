<?php

namespace App\Exports;

use App\Models\TourBooking;
use Maatwebsite\Excel\Concerns\FromCollection;

class YourExport implements implements FromCollection, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            '#',
            'Tour Name',
            'duration',
            'Tour Book Date',
            'Amount Paid',
            'Person',
            'Amount Recieved',
            'Payment Made Via',
            'Status'

        ];
    }

    public function collection()
    {
        $certifcates = TourBooking::get();
        foreach ($certifcates as $i => $cert) {
           
        }
        return $certifcates;
    }
}

