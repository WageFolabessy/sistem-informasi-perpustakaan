<?php

namespace App\Exports;

use App\Models\BookCopy;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProcurementReportExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return BookCopy::all();
    }
}
