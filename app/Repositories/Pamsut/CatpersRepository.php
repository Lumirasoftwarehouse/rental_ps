<?php

namespace App\Repositories\Pamsut;

use App\Exports\CatpersExport;
use App\Imports\CatpersImport;
use App\Models\Catpers;
use App\Models\CatpersFotoKejadian;
use App\Models\CatpersJenisKasus;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class CatpersRepository
{
    private $catpersModel;

    public function __construct(Catpers $catpersModel)
    {
        $this->catpersModel = $catpersModel;
    }

    public function getFotoPersonilById($id)
    {
        $catpers = $this->catpersModel->find($id);

        if ($catpers) {
            return $catpers->foto_personil;
        }

        return null;
    }

    public function getFotoKejadianById($id)
    {
        $catpers = CatpersFotoKejadian::where('catpers_id', $id)->pluck('foto_kejadian')->toArray();

        if ($catpers) {
            return $catpers;
        }

        return null;
    }

    public function exportDataCatpers($dataRequest)
    {
        try {
            $nrpList = $dataRequest['nrp_personil'];
            if (!empty($nrpList)) {
                return Excel::download(new CatpersExport($nrpList), 'catpers.xlsx');
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data catpers not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function exportDataCatpersByExcel($dataRequest)
    {
        try {
            $file = $dataRequest['file'];
            // Get the data from the Excel file
            $nrpList = Excel::toArray(new CatpersImport, $file)[0];

            // Extract only the 'nrp_personil' column from each row and filter out null or empty values
            $nrpList = collect($nrpList)
                ->pluck('nrp_personil') // Extract the nrp_personil column
                ->filter() // Remove null or empty values
                ->values() // Reindex the array
                ->toArray();
            if (!empty($nrpList)) {
                return Excel::download(new CatpersExport($nrpList), 'catpers.xlsx');
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data catpers not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataCatpersByUser($id)
    {
        try {
            $dataCatpers = $this->catpersModel
                ->with(['jenisKasus', 'fotoKejadian']) // Assuming relationships are defined in the model
                ->join('users', 'catpers.admin_lanud_id', '=', 'users.id')
                ->join('admin_lanuds', 'users.id', '=', 'admin_lanuds.user_id')
                ->select('catpers.*', 'admin_lanuds.nama_satuan')
                ->where('admin_lanud_id', $id)
                ->get();
            if ($dataCatpers) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataCatpers,
                    "message" => 'get data catpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data catpers not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataCatpers()
    {
        try {
            $dataCatpers = $this->catpersModel
                ->with(['jenisKasus', 'fotoKejadian']) // Assuming relationships are defined in the model
                ->join('users', 'catpers.admin_lanud_id', '=', 'users.id')
                ->join('admin_lanuds', 'users.id', '=', 'admin_lanuds.user_id')
                ->select('catpers.*', 'admin_lanuds.nama_satuan')
                ->get();
            if ($dataCatpers) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataCatpers,
                    "message" => 'get data catpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data catpers not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataCatpers($id)
    {
        try {
            $dataCatpers = $this->catpersModel
                ->with(['jenisKasus', 'fotoKejadian'])
                ->find($id);
            $dataKomentar = $this->catpersModel
                ->leftJoin('komentar_catpers', 'catpers.id', '=', 'komentar_catpers.catpers_id')
                ->leftJoin('users', 'komentar_catpers.user_id', '=', 'users.id')
                ->select('komentar_catpers.isi_komentar', 'users.name')
                ->where('catpers.id', $id)
                ->get();
            $dataLanud = $this->catpersModel
                ->join('users', 'catpers.admin_lanud_id', '=', 'users.id')
                ->join('admin_lanuds', 'users.id', '=', 'admin_lanuds.user_id')
                ->select('admin_lanuds.nama_satuan', 'admin_lanuds.lokasi_satuan', 'admin_lanuds.nama_kepala_satuan')
                ->find($id);
            if ($dataCatpers) {

                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        "catpers" => $dataCatpers,
                        "komentar" => $dataKomentar,
                        "lanud" => $dataLanud,
                    ],
                    "message" => 'get detail data catpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data catpers not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    // public function inputDataCatpers($dataRequest)
    // {
    //     try {
    //         $result = $this->catpersModel->insert($dataRequest);
    //         return [
    //             "id" => '1',
    //             "statusCode" => 200,
    //             "message" => 'input data catpers success'
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             "id" => '0',
    //             "statusCode" => 401,
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function inputDataCatpers($dataRequest)
    {
        try {
            // Create new Catpers record in the database
            $catpers = $this->catpersModel->create($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $catpers->id,
                    "nrp_personil" => $catpers->nrp_personil
                ],
                "message" => 'input data catpers success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function insertFotoKejadian($dataRequest)
    {
        try {
            // Insert photo incident record into the database
            CatpersFotoKejadian::create($dataRequest);
            // return [
            //     "id" => '1',
            //     "statusCode" => 200,
            //     "message" => 'input data catpers success'
            // ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function insertJenisKasus($dataRequest)
    {
        try {
            // Insert case type record into the database
            CatpersJenisKasus::create($dataRequest);
            // return [
            //     "id" => '1',
            //     "statusCode" => 200,
            //     "message" => 'input data catpers success'
            // ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }


    public function updateDataCatpers($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataCatpers = $this->catpersModel->find($id);
            if (isset($dataRequest['foto_personil'])) {
                $dataCatpers->foto_personil = $dataRequest['foto_personil'];
            }
            if (isset($dataRequest['sanksi_hukum'])) {
                $dataCatpers->sanksi_hukum = $dataRequest['sanksi_hukum'];
            }
            // if (isset($dataRequest['foto_kejadian'])) {
            //     $dataCatpers->foto_kejadian = $dataRequest['foto_kejadian'];
            // }
            $dataCatpers->nama_personil = $dataRequest['nama_personil'];
            $dataCatpers->nrp_personil = $dataRequest['nrp_personil'];
            $dataCatpers->jabatan_personil = $dataRequest['jabatan_personil'];
            $dataCatpers->kronologi_singkat = $dataRequest['kronologi_singkat'];
            $dataCatpers->tanggal = $dataRequest['tanggal'];
            $dataCatpers->alasan_kejadian = $dataRequest['alasan_kejadian'];
            $dataCatpers->lokasi_kejadian = $dataRequest['lokasi_kejadian'];
            $dataCatpers->cara_kejadian = $dataRequest['cara_kejadian'];
            // $dataCatpers->jenis_kasus_id = $dataRequest['jenis_kasus_id'];
            $dataCatpers->save();

            if (isset($dataRequest['foto_kejadian']) && is_array($dataRequest['foto_kejadian'])) {
                CatpersFotoKejadian::where('catpers_id', $id)->delete(); // Delete old entries
                foreach ($dataRequest['foto_kejadian'] as $foto) {
                    CatpersFotoKejadian::create([
                        'catpers_id' => $id,
                        'foto_kejadian' => $foto,
                    ]);
                }
            }

            if (isset($dataRequest['jenis_kasus_id']) && is_array($dataRequest['jenis_kasus_id'])) {
                CatpersJenisKasus::where('catpers_id', $id)->delete(); // Delete old associations
                foreach ($dataRequest['jenis_kasus_id'] as $jenisKasusId) {
                    CatpersJenisKasus::create([
                        'catpers_id' => $id,
                        'jenis_kasus_id' => $jenisKasusId,
                    ]);
                }
            }

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data catpers success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataCatpers($id)
    {
        try {
            $catpers = $this->catpersModel->find($id);
            $catpersFotoKejadian = $this->getFotoKejadianById($id);
            if ($catpers) {
                // Check if either foto_personil or foto_kejadian is not null and delete them accordingly
                $deletedFotoPersonil = false;
                $deletedFotoKejadian = false;

                if ($catpers->foto_personil) {
                    $deletedFotoPersonil = Storage::disk('public')->delete($catpers->foto_personil);
                }

                // Delete foto_kejadian if it exists
                if (!empty($catpersFotoKejadian)) {
                    $fotoKejadianFiles = is_array($catpersFotoKejadian) ? $catpersFotoKejadian : json_decode($catpersFotoKejadian, true);

                    foreach ($fotoKejadianFiles as $filePath) {
                        $fileDeleted = Storage::disk('public')->delete($filePath);
                        if (!$fileDeleted) {
                            $deletedFotoKejadian = false; // Mark as failed if any file fails to delete
                        }
                    }
                }

                // If at least one of the files is deleted or if none exists, delete the data
                if ($deletedFotoPersonil || $deletedFotoKejadian || (!$catpers->foto_personil && !$catpersFotoKejadian)) {
                    $catpers->delete();
                    return [
                        "id" => '1',
                        "statusCode" => 200,
                        "message" => 'delete data catpers success'
                    ];
                } else {
                    return [
                        "id" => '0',
                        "statusCode" => 500,
                        "message" => 'failed to delete files'
                    ];
                }
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data catpers tidak ditemukan'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
