<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportLabaRugiNota implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function array(): array
    {
        return $this->invoices;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'No_Faktur',
            'Penjualan',
            'Diskon_Nota',
            'HPP',
            'Laba_Rugi'
        ];
    }
}
