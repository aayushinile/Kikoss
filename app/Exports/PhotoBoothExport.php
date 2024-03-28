<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PhotoBoothExport implements FromCollection, WithHeadings,ShouldAutoSize
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
                'Name' => $item->Users->fullname ?? '' ,
                'Tour Name' => $item->booth->title ?? '',
                'Booking Id' => $item->booking_id ?? '',
                'Amount Paid' => $item->total_amount ?? '',
                'Amount Recieved Date' => Carbon::parse($item->booking_date)->format('m/d/Y'),
                'Media Purchase' =>  $item->image_count.'images'.'/'.$item->video_count.'videos',
                'Payment Made Via' => 'Paypal',
                'Transaction ID' =>$item->transaction_id ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Tour Name',
            'Booking ID',
            'Amount Paid',
            'Amount Recieved Date',
            'Media Purchase',
            'Payment Made Via',
            'Transaction ID'
        ];
    }
}
