<?php

namespace App\Exports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    // Constructor untuk menerima data yang akan diekspor
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Mengembalikan koleksi data.
     */
    public function collection()
    {
        return $this->data;
    }

    /**
     * Menambahkan heading di file Excel.
     */
    public function headings(): array
    {
        return [
            'ID',
            'UID',
            'pH',
            'COD',
            'TSS',
            'NH3-N',
            'Debit',
            'Created At',
        ];
    }

    /**
     * Mapping data untuk setiap baris.
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->uid,
            $row->pH,
            $row->cod,
            $row->tss,
            $row->nh3n,
            $row->debit,
            $row->created_at,
        ];
    }
}
