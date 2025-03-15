<?php

namespace App\Services\Pamsut;

use App\Repositories\Pamsut\CatpersRepository;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class CatpersService
{
    private $catpersRepository;

    public function __construct(CatpersRepository $catpersRepository)
    {
        $this->catpersRepository = $catpersRepository;
    }

    public function getFotoPersonilById($id)
    {
        return $this->catpersRepository->getFotoPersonilById($id);
    }

    public function getFotoKejadianById($id)
    {
        return $this->catpersRepository->getFotoKejadianById($id);
    }

    public function exportDataCatpers($dataRequest)
    {
        try {
            return $this->catpersRepository->exportDataCatpers($dataRequest);
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
            return $this->catpersRepository->exportDataCatpersByExcel($dataRequest);
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
            // Get the data from the repository
            $response = $this->catpersRepository->listDataCatpersByUser($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $catper) {
                    // Decrypt fields
                    $response['data'][$key]->nama_personil = Crypt::decrypt($catper->nama_personil);
                    // $response['data'][$key]->nrp_personil = Crypt::decrypt($catper->nrp_personil);
                    $response['data'][$key]->jabatan_personil = Crypt::decrypt($catper->jabatan_personil);
                    $response['data'][$key]->kronologi_singkat = Crypt::decrypt($catper->kronologi_singkat);
                    $response['data'][$key]->alasan_kejadian = Crypt::decrypt($catper->alasan_kejadian);
                    $response['data'][$key]->lokasi_kejadian = Crypt::decrypt($catper->lokasi_kejadian);
                    $response['data'][$key]->cara_kejadian = Crypt::decrypt($catper->cara_kejadian);

                    // Decrypt and retrieve 'foto_personil' if it exists
                    // if (!empty($catper->foto_personil)) {
                    //     $filePath = $catper->foto_personil;
                    //     if (Storage::disk('public')->exists($filePath)) {
                    //         $encryptedContent = Storage::disk('public')->get($filePath);
                    //         $decryptedContent = Crypt::decrypt($encryptedContent);
                    //         // Optionally return the decrypted content as a base64 encoded string
                    //         $response['data'][$key]->foto_personil = 'data:image/jpeg;base64,' . base64_encode($decryptedContent);
                    //     }
                    // }

                    // Fix the foto_kejadian response: already in an array in the API response
                    // $fotoKejadianList = [];
                    // if (!empty($catper->fotoKejadian)) {
                    //     foreach ($catper->fotoKejadian as $foto) {
                    //         if (Storage::disk('public')->exists($foto->foto_kejadian)) {
                    //             $encryptedContent = Storage::disk('public')->get($foto->foto_kejadian);
                    //             $decryptedContent = Crypt::decrypt($encryptedContent);
                    //             $fotoKejadianList[] = 'data:image/jpeg;base64,' . base64_encode($decryptedContent);
                    //         }
                    //     }
                    // }
                    // $response['data'][$key]->foto_kejadian = $fotoKejadianList;

                    // Fix the jenis_kasus response: needs to be an array of names as in the API
                    // $jenisKasusList = [];
                    // if ($catper->jenisKasus && $catper->jenisKasus->count() > 0) {
                    //     foreach ($catper->jenisKasus as $jenis) {
                    //         $jenisKasusList[] = [
                    //             'id' => $jenis->id,
                    //             'jenis_kasus' => $jenis->jenis_kasus
                    //         ];
                    //     }
                    // }
                    // $response['data'][$key]->jenis_kasus = $jenisKasusList;

                    // Optional: Decrypt additional fields if they exist
                    if (isset($catper->sanksi_hukum)) {
                        $response['data'][$key]->sanksi_hukum = Crypt::decrypt($catper->sanksi_hukum);
                    }
                }
            }

            // Return the modified response with decrypted data
            return $response;
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
            // Get the data from the repository
            $response = $this->catpersRepository->listDataCatpers();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $catper) {
                    // Decrypt fields
                    $response['data'][$key]->nama_personil = Crypt::decrypt($catper->nama_personil);
                    // $response['data'][$key]->nrp_personil = Crypt::decrypt($catper->nrp_personil);
                    $response['data'][$key]->jabatan_personil = Crypt::decrypt($catper->jabatan_personil);
                    $response['data'][$key]->kronologi_singkat = Crypt::decrypt($catper->kronologi_singkat);
                    $response['data'][$key]->alasan_kejadian = Crypt::decrypt($catper->alasan_kejadian);
                    $response['data'][$key]->lokasi_kejadian = Crypt::decrypt($catper->lokasi_kejadian);
                    $response['data'][$key]->cara_kejadian = Crypt::decrypt($catper->cara_kejadian);

                    // Decrypt and retrieve 'foto_personil' if it exists
                    // if (!empty($catper->foto_personil)) {
                    //     $filePath = $catper->foto_personil;
                    //     if (Storage::disk('public')->exists($filePath)) {
                    //         $encryptedContent = Storage::disk('public')->get($filePath);
                    //         $decryptedContent = Crypt::decrypt($encryptedContent);
                    //         // Optionally return the decrypted content as a base64 encoded string
                    //         $response['data'][$key]->foto_personil = 'data:image/jpeg;base64,' . base64_encode($decryptedContent);
                    //     }
                    // }

                    // Fix the foto_kejadian response: already in an array in the API response
                    // $fotoKejadianList = [];
                    // if (!empty($catper->fotoKejadian)) {
                    //     foreach ($catper->fotoKejadian as $foto) {
                    //         if (Storage::disk('public')->exists($foto->foto_kejadian)) {
                    //             $encryptedContent = Storage::disk('public')->get($foto->foto_kejadian);
                    //             $decryptedContent = Crypt::decrypt($encryptedContent);
                    //             $fotoKejadianList[] = 'data:image/jpeg;base64,' . base64_encode($decryptedContent);
                    //         }
                    //     }
                    // }
                    // $response['data'][$key]->foto_kejadian = $fotoKejadianList;

                    // Fix the jenis_kasus response: needs to be an array of names as in the API
                    // $jenisKasusList = [];
                    // if ($catper->jenisKasus && $catper->jenisKasus->count() > 0) {
                    //     foreach ($catper->jenisKasus as $jenis) {
                    //         $jenisKasusList[] = [
                    //             'id' => $jenis->id,
                    //             'jenis_kasus' => $jenis->jenis_kasus
                    //         ];
                    //     }
                    // }
                    // $response['data'][$key]->jenis_kasus = $jenisKasusList;

                    // Optional: Decrypt additional fields if they exist
                    if (isset($catper->sanksi_hukum)) {
                        $response['data'][$key]->sanksi_hukum = Crypt::decrypt($catper->sanksi_hukum);
                    }
                }
            }

            // Return the modified response with decrypted data
            return $response;
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
            // Fetch the detailed data from the repository
            $response = $this->catpersRepository->detailDataCatpers($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data']['catpers'])) {
                $catpers = $response['data']['catpers'];

                // Decrypt encrypted fields in the 'catpers' data
                $response['data']['catpers']->nama_personil = Crypt::decrypt($catpers->nama_personil);
                // $response['data']['catpers']->nrp_personil = Crypt::decrypt($catpers->nrp_personil);
                $response['data']['catpers']->jabatan_personil = Crypt::decrypt($catpers->jabatan_personil);
                $response['data']['catpers']->kronologi_singkat = Crypt::decrypt($catpers->kronologi_singkat);
                $response['data']['catpers']->alasan_kejadian = Crypt::decrypt($catpers->alasan_kejadian);
                $response['data']['catpers']->lokasi_kejadian = Crypt::decrypt($catpers->lokasi_kejadian);
                $response['data']['catpers']->cara_kejadian = Crypt::decrypt($catpers->cara_kejadian);

                // Decrypt and retrieve 'foto_personil' if it exists
                if (!empty($catpers->foto_personil)) {
                    $filePath = $catpers->foto_personil;
                    if (Storage::disk('public')->exists($filePath)) {
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);
                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['catpers']->foto_personil = 'data:image/jpeg;base64,' . base64_encode($decryptedContent);
                    }
                }

                // Decrypt and retrieve 'foto_kejadian' if it exists
                if (!empty($catpers->fotoKejadian)) {
                    foreach ($catpers->fotoKejadian as $index => $foto) {
                        $filePath = $foto->foto_kejadian;
                        if (Storage::disk('public')->exists($filePath)) {
                            $encryptedContent = Storage::disk('public')->get($filePath);
                            $decryptedContent = Crypt::decrypt($encryptedContent);
                            // Optionally return the decrypted content as a base64 encoded string
                            $catpers->fotoKejadian[$index]->foto_kejadian = 'data:image/jpeg;base64,' . base64_encode($decryptedContent);
                        } else {
                            // Handle cases where the file doesn't exist
                            $catpers->fotoKejadian[$index]->foto_kejadian = null;
                        }
                    }
                }

                // Optionally decrypt fields that may or may not exist (e.g., 'sanksi_hukum')
                if (isset($catpers->sanksi_hukum)) {
                    $response['data']['catpers']->sanksi_hukum = Crypt::decrypt($catpers->sanksi_hukum);
                }
            }

            // Decrypt the 'komentar' section if it exists
            if (!empty($response['data']['komentar'])) {
                foreach ($response['data']['komentar'] as $index => $komentar) {
                    // Only decrypt if 'isi_komentar' is not null
                    if (!empty($komentar->isi_komentar)) {
                        $response['data']['komentar'][$index]->isi_komentar = Crypt::decrypt($komentar->isi_komentar);
                    } else {
                        $response['data']['komentar'][$index]->isi_komentar = null; // Ensure it's explicitly set to null
                    }

                    // Only decrypt if 'name' is not null
                    // if (!empty($komentar->name)) {
                    //     $response['data']['komentar'][$index]->name = Crypt::decrypt($komentar->name);
                    // } else {
                    //     $response['data']['komentar'][$index]->name = null; // Ensure it's explicitly set to null
                    // }
                }
            }

            // Return the modified response with decrypted data
            return $response;
        } catch (\Exception $e) {
            // Handle exceptions and return an appropriate error response
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataCatpers($dataRequest)
    {
        try {
            // Encrypt the sensitive data
            $dataEncrypt = [
                'nama_personil' => Crypt::encrypt($dataRequest['nama_personil']),
                'nrp_personil' => $dataRequest['nrp_personil'],
                'jabatan_personil' => Crypt::encrypt($dataRequest['jabatan_personil']),
                'kronologi_singkat' => Crypt::encrypt($dataRequest['kronologi_singkat']),
                'tanggal' => $dataRequest['tanggal'],
                'alasan_kejadian' => Crypt::encrypt($dataRequest['alasan_kejadian']),
                'lokasi_kejadian' => Crypt::encrypt($dataRequest['lokasi_kejadian']),
                'cara_kejadian' => Crypt::encrypt($dataRequest['cara_kejadian']),
                'admin_lanud_id' => $dataRequest['admin_lanud_id'],
            ];

            // Save personnel photo if available
            if (isset($dataRequest['foto_personil'])) {
                $file = $dataRequest['foto_personil'];
                $filePath = $file->store('pamsut/foto_personil', 'public');
                $fileContent = file_get_contents($file->getRealPath());
                $encryptedContent = Crypt::encrypt($fileContent);
                Storage::disk('public')->put($filePath, $encryptedContent);
                $dataEncrypt['foto_personil'] = $filePath;
            }

            if (isset($dataRequest['sanksi_hukum'])) {
                $dataEncrypt['sanksi_hukum'] = Crypt::encrypt($dataRequest['sanksi_hukum']);
            }

            // Insert main data into the database
            $catpers = $this->catpersRepository->inputDataCatpers($dataEncrypt);

            // Handle multiple photos for the incident
            if (isset($dataRequest['foto_kejadian']) && is_array($dataRequest['foto_kejadian'])) {
                foreach ($dataRequest['foto_kejadian'] as $foto) {
                    $filePath = $foto->store('pamsut/foto_kejadian', 'public');
                    $fileContent = file_get_contents($foto->getRealPath());
                    $encryptedContent = Crypt::encrypt($fileContent);
                    Storage::disk('public')->put($filePath, $encryptedContent);

                    $this->catpersRepository->insertFotoKejadian([
                        'catpers_id' => $catpers['data']['id'],
                        'foto_kejadian' => $filePath,
                    ]);
                }
            }

            // Associate multiple case types
            if (isset($dataRequest['jenis_kasus_id']) && is_array($dataRequest['jenis_kasus_id'])) {
                foreach ($dataRequest['jenis_kasus_id'] as $jenisKasusId) {
                    $this->catpersRepository->insertJenisKasus([
                        'catpers_id' => $catpers['data']['id'],
                        'jenis_kasus_id' => $jenisKasusId,
                    ]);
                }
            }

            return [
                "id" => "1",
                "data" => $catpers['data'],
                "statusCode" => 200,
                "message" => 'Input data catpers success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 500,
                "message" => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }

    public function updateDataCatpers($dataRequest, $id)
    {
        try {
            $dataEncrypt = [
                'nama_personil' => Crypt::encrypt($dataRequest['nama_personil']),
                'nrp_personil' => $dataRequest['nrp_personil'],
                'jabatan_personil' => Crypt::encrypt($dataRequest['jabatan_personil']),
                'kronologi_singkat' => Crypt::encrypt($dataRequest['kronologi_singkat']),
                'alasan_kejadian' => Crypt::encrypt($dataRequest['alasan_kejadian']),
                'lokasi_kejadian' => Crypt::encrypt($dataRequest['lokasi_kejadian']),
                'cara_kejadian' => Crypt::encrypt($dataRequest['cara_kejadian']),
                'tanggal' => $dataRequest['tanggal'],
                'jenis_kasus_id' => $dataRequest['jenis_kasus_id'],
            ];

            // Handle 'foto_personil'
            if (isset($dataRequest['foto_personil'])) {
                $existingRecord = $this->catpersRepository->getFotoPersonilById($id);
                // Delete the old file if it exists
                if ($existingRecord) {
                    Storage::delete('public/' . $existingRecord);
                }

                $file = $dataRequest['foto_personil'];
                $filePath = $file->store('pamsut/foto_personil', 'public'); // Store the new file
                $fileContent = file_get_contents($file->getRealPath()); // Get file content
                $encryptedContent = Crypt::encrypt($fileContent); // Encrypt file content
                Storage::disk('public')->put($filePath, $encryptedContent); // Save encrypted content

                $dataEncrypt['foto_personil'] = $filePath; // Store the new file path in DB
            }

            if (isset($dataRequest['foto_kejadian']) && is_array($dataRequest['foto_kejadian'])) {
                // Retrieve old images associated with the record
                $existingFiles = $this->catpersRepository->getFotoKejadianById($id);

                // Delete each old image from storage
                if (!empty($existingFiles)) {
                    foreach ($existingFiles as $existingFile) {
                        if (Storage::exists('public/' . $existingFile)) {
                            Storage::delete('public/' . $existingFile);
                        }
                    }
                }

                foreach ($dataRequest['foto_kejadian'] as $file) {
                    $filePath = $file->store('pamsut/foto_kejadian', 'public');
                    $encryptedContent = Crypt::encrypt(file_get_contents($file->getRealPath()));
                    Storage::disk('public')->put($filePath, $encryptedContent);

                    $dataEncrypt['foto_kejadian'][] = $filePath; // Collect file paths
                }
            }

            if (isset($dataRequest['jenis_kasus_id']) && is_array($dataRequest['jenis_kasus_id'])) {
                $dataEncrypt['jenis_kasus_id'] = $dataRequest['jenis_kasus_id'];
            }

            if (isset($dataRequest['sanksi_hukum'])) {
                $dataEncrypt['sanksi_hukum'] = Crypt::encrypt($dataRequest['sanksi_hukum']);
            }
            return $this->catpersRepository->updateDataCatpers($dataEncrypt, $id);
        } catch (\Exception $e) {
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
            return $this->catpersRepository->deleteDataCatpers($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
