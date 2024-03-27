<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class TourCallBackExport implements FromCollection, WithHeadings,ShouldAutoSize
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
                'Name' => $item->name,
                'Tour Name' => $item->TourName->name ?? '',
                'Phone' => $item->mobile ?? '',
                'Duration' => $item->TourName->duration ?? ''.'Hrs',
                'Tour Book Date' => Carbon::parse($item->preferred_time)->format('m/d/Y'),
                'Note' =>  $item->note,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Tour Name',
            'Phone',
            'Duration',
            'Tour Book Date',
            'Note',
        ];
    }
}
