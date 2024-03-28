<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class ManageBookingExport implements FromCollection, WithHeadings,ShouldAutoSize
{
    protected $data_exp;

    public function __construct(Collection $data_exp)
    {
        $this->data_exp = $data_exp;
    }

    public function collection()
{
    return $this->data_exp->map(function ($item) {
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
            'Name' => $item->user_name ?? '',
            'Tour Name' => $item->Tour->name ?? '',
            'Duration' => $item->Tour->duration ?? '',
            'Amount' => $item->total_amount,
            'Tour Book Date' =>  Carbon::parse($item->booking_date)->format('m/d/Y'),
            'Person' => $item->Tour['total_people'] ?? 0,
            'Payment Mode' => 'Paypal',
            'Status' => $status,
        ];
    });
}


    public function headings(): array
    {
        return [
            'Booking ID',
            'Transaction ID',
            'Name',
            'Tour Name',
            'Duration',
            'Amount',
            'Tour Book Date',
            'Person',
            'Payment Mode',
            'Status'
            
        ];
    }
}
