<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class VirtualTourExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Booking ID' => $item->booking_id,
                'Transaction ID' => $item->transaction_id,
                'User Name' => $item->user_name ?? '',
                'Tour Name' => $item->VirtualTour->name ?? '',
                'Total Amount' => $item->total_amount,
                'Booking Date' =>  Carbon::parse($item->booking_date)->format('m/d/Y'),
                'Payment Method' => 'Paypal'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Booking ID',
            'Transaction ID',
            'User Name',
            'Tour Name',
            'Total Amount',
            'Booking Date',
            'Payment Method'
        ];
    }
}
