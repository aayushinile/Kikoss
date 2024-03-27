<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class TaxiBookingExport implements FromCollection, WithHeadings,ShouldAutoSize
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
                'User Name' => $item->user_name,
                'Booked For' => $item->fullname,
                'Phone' => $item->mobile ?? '',
                'Booking ID' => $item->booking_id ?? '',
                'Booking Date' => Carbon::parse($item->booking_time)->format('m/d/Y'),
                'Pickup Location' =>  $item->pickup_location,
                'Drop Off Location' => $item->drop_location,
                'Travel Distance' => $item->distance.'KM',
                'Hotel Name' => $item->hotel_name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'User Name',
            'Booked For',
            'Phone',
            'Booking ID',
            'Booking Date',
            'Pickup Location',
            'Drop Off Location',
            'Travel Distance',
            'Hotel Name'
        ];
    }
}
