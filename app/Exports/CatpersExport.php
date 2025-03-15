<?php

namespace App\Exports;

use App\Models\Catpers;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class CatpersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $nrpList;

    public function __construct($nrpList = [])
    {
        $this->nrpList = is_array($nrpList) ? $nrpList : [$nrpList]; // Ensure it's an array
    }

    public function collection()
    {
        // Fetch all records matching the given NRP
        $catpersData = Catpers::whereIn('nrp_personil', $this->nrpList)->get();

        // Create a list of all NRPs that have records
        $foundNrp = $catpersData->pluck('nrp_personil')->toArray();

        // Find NRPs that were searched but have no records
        $missingNrp = array_diff($this->nrpList, $foundNrp);

        // Add missing NRPs with "Bersih" as their status
        foreach ($missingNrp as $nrp) {
            $catpersData->push((object) [
                'nama_personil' => '',
                'nrp_personil' => $nrp,
                'jabatan_personil' => '',
                'kronologi_singkat' => '',
                'sanksi_hukum' => '',
                'keterangan' => 'Bersih',
            ]);
        }

        return $catpersData;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama/NRP/Jabatan',
            'Kronologi Singkat',
            'Sanksi Hukum',
            'Keterangan'
        ];
    }

    public function map($catpers): array
    {
        static $count = 1;

        // Helper function to handle decryption
        $decrypt = function ($value, $default = 'N/A') {
            try {
                return Crypt::decrypt($value);
            } catch (\Exception $e) {
                return $default; // Return default if decryption fails
            }
        };

        // Decrypt fields with fallback values
        $nama = $decrypt($catpers->nama_personil, 'Nama tidak tercatat di catpers');
        $nrp = $catpers->nrp_personil;
        $jabatan = $decrypt($catpers->jabatan_personil, 'Jabatan tidak tercatat di catpers');
        $kronologi = $decrypt($catpers->kronologi_singkat);
        $sanksi = $decrypt($catpers->sanksi_hukum, 'Tidak ada sanksi');

        // If "Keterangan" is not already set, assume there's a record
        $keterangan = property_exists($catpers, 'keterangan') ? $catpers->keterangan : 'Ada catpers';

        return [
            $count++,
            "{$nama}/{$nrp}/{$jabatan}",
            $kronologi,
            $sanksi,
            $keterangan,
        ];
    }
}
