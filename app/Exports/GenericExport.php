<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class GenericExport implements FromCollection, WithHeadings
{
    protected $slug;

    public function __construct($slug)
    {
        $this->slug = $slug;
    }

    public function collection()
    {
        $dataType = DB::table('data_types')->where('slug', $this->slug)->first();
        if ($dataType) {
            return DB::table($dataType->name)->get();
        }
        return collect();
    }

    public function headings(): array
    {
        $dataType = DB::table('data_types')->where('slug', $this->slug)->first();
        if ($dataType) {
            // Obtener los nombres de las columnas de la tabla
            $columns = DB::getSchemaBuilder()->getColumnListing($dataType->name);
            return $columns;
        }
        return [];
    }
}
