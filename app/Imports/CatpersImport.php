<?php

namespace App\Imports;

use App\Models\Catpers;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class CatpersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        return $rows->pluck('nrp_personil')->flatten()->toArray();
    }
}
