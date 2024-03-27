<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PaymentDetails implements FromCollection, WithHeadings,ShouldAutoSize
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
{
    return $this->data->map(function ($item) {
        $tourType = '';
        switch ($item->tour_type) {
            case 1:
                $tourType = 'Tour';
                $tourName = $item->title ?? '';
                break;
            case 2:
                $tourType = 'Virtual Tour';
                $tourName = $item->virtual_title ?? '';
                break;
            case 3:
                $tourType = 'Photo Booth';
                $tourName = $item->photo_title ?? '';
                break;
            case 4:
                $tourType = 'Taxi Booking';
                $tourName = ''; // Assuming there's no specific tour name for Taxi Booking
                break;
            default:
                $tourType = 'Null';
                $tourName = '';
                break;
        }

        $status = '';
        switch ($item->status) {
            case 1:
                $status = 'Accepted';
                break;
            case 2:
                $status = 'Rejected';
                break;
            default:
                $status = 'Null';
                break;
        }

        return [
            'Booking ID' => $item->booking_id,
            'Transaction ID' => $item->transaction_id,
            'Tour Name' => $tourName,
            'User Name' => $item->user_name ?? '',
            'Payment Method' => 'Paypal',
            'Tour Type' => $tourType,
            'Amount' => $item->amount,
            'Status' => $status,
            'Date' =>  Carbon::parse($item->created_at)->format('m/d/Y'),
        ];
    });
}


    public function headings(): array
    {
        return [
            'Booking ID',
            'Transaction ID',
            'Tour Name',
            'User Name',
            'Payment Method',
            'Tour Type',
            'Amount',
            'Status',
            'Date',
            
        ];
    }
}
