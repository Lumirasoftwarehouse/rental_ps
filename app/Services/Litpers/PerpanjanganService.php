<?php

namespace App\Services\Litpers;

use App\Repositories\Litpers\PerpanjanganRepository;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class PerpanjanganService
{
    private $perpanjanganRepository;

    public function __construct(PerpanjanganRepository $perpanjanganRepository)
    {
        $this->perpanjanganRepository = $perpanjanganRepository;
    }

    public function listDataAllFormPerpanjanganByMitra($id)
    {
        try {
            return $this->perpanjanganRepository->listDataAllFormPerpanjanganByMitra($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataUnfinishedFormPerpanjanganByMitra($id)
    {
        try {
            return $this->perpanjanganRepository->listDataUnfinishedFormPerpanjanganByMitra($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataUnvalidatedFormPerpanjangan()
    {
        try {
            return $this->perpanjanganRepository->listDataUnvalidatedFormPerpanjangan();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Satu

    public function listDataFormSatuPerpanjangan()
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormSatuPerpanjangan();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->perpanjanganRepository->detailDataFormSatuPerpanjangan($formSatu->id);

                    if (!empty($currentData['data'])) {
                        $currentDetails = $currentData['data'];

                        // Decrypt files in `files`
                        foreach ($currentDetails['files'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }


                        // Decrypt `catatan_revisi` if it exists
                        if (!empty($currentDetails['catatan_revisi'])) {
                            $currentDetails['catatan_revisi'] = Crypt::decrypt($currentDetails['catatan_revisi']);
                        }

                        // Replace original data with the decrypted details
                        $response['data'][$key] = $currentDetails;
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

    public function listDataFormSatuPerpanjanganByAdmin($id)
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormSatuPerpanjanganByAdmin($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->perpanjanganRepository->detailDataFormSatuPerpanjangan($formSatu->id);

                    if (!empty($currentData['data'])) {
                        $currentDetails = $currentData['data'];

                        // Decrypt files in `files`
                        foreach ($currentDetails['files'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }


                        // Decrypt `catatan_revisi` if it exists
                        if (!empty($currentDetails['catatan_revisi'])) {
                            $currentDetails['catatan_revisi'] = Crypt::decrypt($currentDetails['catatan_revisi']);
                        }

                        // Replace original data with the decrypted details
                        $response['data'][$key] = $currentDetails;
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

    public function listDataFormSatuPerpanjanganByMitra($id)
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormSatuPerpanjanganByMitra($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->perpanjanganRepository->detailDataFormSatuPerpanjangan($formSatu->id);

                    if (!empty($currentData['data'])) {
                        $currentDetails = $currentData['data'];

                        // Decrypt files in `files`
                        foreach ($currentDetails['files'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }


                        // Decrypt `catatan_revisi` if it exists
                        if (!empty($currentDetails['catatan_revisi'])) {
                            $currentDetails['catatan_revisi'] = Crypt::decrypt($currentDetails['catatan_revisi']);
                        }

                        // Replace original data with the decrypted details
                        $response['data'][$key] = $currentDetails;
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

    public function detailDataFormSatuPerpanjangan($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->perpanjanganRepository->detailDataFormSatuPerpanjangan($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formSatu = $response['data'];

                foreach ($formSatu['files'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files'][$key] = base64_encode($decryptedContent);
                    }
                }

                if (isset($formSatu['catatan_revisi'])) {
                    $response['data']['catatan_revisi'] = Crypt::decrypt($formSatu['catatan_revisi']);
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

    public function inputDataFormSatuPerpanjangan(array $dataRequest, array $files)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->perpanjanganRepository->inputDataFormSatuPerpanjangan(array_merge($dataRequest, $fileNames));
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormSatuPerpanjangan(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormSatuPerpanjangan($id);

            // Access the file paths inside the 'data' array
            $currentFiles = $currentData['data']['files'] ?? [];

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                // Delete old files
                $this->deleteOldFiles($currentData['data']['surat_disadaau_diskonsau']);
                $this->deleteOldFiles($currentData['data']['skhpp_lama']);

                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->perpanjanganRepository->updateDataFormSatuPerpanjangan($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormSatuPerpanjangan($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormSatuPerpanjangan($id);

            // Delete the associated files
            $this->deleteOldFiles($currentData['data']['surat_disadaau_diskonsau']); // assuming 'files' contains the file paths
            $this->deleteOldFiles($currentData['data']['skhpp_lama']); // assuming 'files' contains the file paths

            // Delete the record in the repository
            return $this->perpanjanganRepository->deleteDataFormSatuPerpanjangan($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Dua

    public function listDataFormDuaPerpanjangan()
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormDuaPerpanjangan();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formTiga) {
                    // Fetch detailed data for each entry
                    $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($formTiga->id);

                    if (!empty($currentData['data'])) {
                        $currentDetails = $currentData['data'];

                        // Decrypt files in `files_pt1`
                        foreach ($currentDetails['files_pt1'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt1'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt2'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt2'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt3'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt3'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt4'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt4'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt5'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt5'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt6'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt6'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }

                        // Decrypt `catatan_revisi` if it exists
                        if (!empty($currentDetails['catatan_revisi'])) {
                            $currentDetails['catatan_revisi'] = Crypt::decrypt($currentDetails['catatan_revisi']);
                        }

                        // Replace original data with the decrypted details
                        $response['data'][$key] = $currentDetails;
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

    public function listDataFormDuaPerpanjanganByFormSatu($id)
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormDuaPerpanjanganByFormSatu($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formTiga) {
                    // Fetch detailed data for each entry
                    $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($formTiga->id);

                    if (!empty($currentData['data'])) {
                        $currentDetails = $currentData['data'];

                        // Decrypt files in `files_pt1`
                        foreach ($currentDetails['files_pt1'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt1'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt2'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt2'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt3'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt3'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt4'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt4'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt5'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt5'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }
                        foreach ($currentDetails['files_pt6'] as $fileKey => $filePath) {
                            if ($filePath && Storage::disk('public')->exists($filePath)) {
                                $encryptedContent = Storage::disk('public')->get($filePath);
                                $decryptedContent = Crypt::decrypt($encryptedContent);
                                $currentDetails['files_pt6'][$fileKey] = base64_encode($decryptedContent);
                            }
                        }

                        // Decrypt `catatan_revisi` if it exists
                        if (!empty($currentDetails['catatan_revisi'])) {
                            $currentDetails['catatan_revisi'] = Crypt::decrypt($currentDetails['catatan_revisi']);
                        }

                        // Replace original data with the decrypted details
                        $response['data'][$key] = $currentDetails;
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

    public function detailDataFormDuaPerpanjangan($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->perpanjanganRepository->detailDataFormDuaPerpanjangan($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formDua = $response['data'];

                foreach ($formDua['files_pt1'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt1'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formDua['files_pt2'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt2'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formDua['files_pt3'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt3'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formDua['files_pt4'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt4'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formDua['files_pt5'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt5'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formDua['files_pt6'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt6'][$key] = base64_encode($decryptedContent);
                    }
                }

                if (isset($formDua['catatan_revisi'])) {
                    $response['data']['catatan_revisi'] = Crypt::decrypt($formDua['catatan_revisi']);
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

    public function inputDataFormDuaPerpanjanganPartSatu(array $dataRequest, array $files)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->perpanjanganRepository->inputDataFormDuaPerpanjanganPartSatu(array_merge($dataRequest, $fileNames));
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormDuaPerpanjanganPartDua(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->perpanjanganRepository->inputDataFormDuaPerpanjanganPartDua(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormDuaPerpanjanganPartTiga(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->perpanjanganRepository->inputDataFormDuaPerpanjanganPartTiga(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormDuaPerpanjanganPartEmpat(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->perpanjanganRepository->inputDataFormDuaPerpanjanganPartEmpat(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormDuaPerpanjanganPartLima(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->perpanjanganRepository->inputDataFormDuaPerpanjanganPartLima(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormDuaPerpanjanganPartEnam(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->perpanjanganRepository->inputDataFormDuaPerpanjanganPartEnam(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPerpanjanganPartSatu(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormDuaPerpanjangan($id);

            // Access the file paths inside the 'data' array
            $currentFiles = $currentData['data']['files_pt1'] ?? [];

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                // Delete old files
                $this->deleteOldFiles($currentData['data']['surat_permohonan_penerbitan']);
                $this->deleteOldFiles($currentData['data']['akte_pendirian_perusahaan']);
                $this->deleteOldFiles($currentData['data']['akte_perubahan_perusahaan']);
                $this->deleteOldFiles($currentData['data']['nomor_izin_berusaha']);
                $this->deleteOldFiles($currentData['data']['nomor_pokok_wajib_pajak']);

                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->perpanjanganRepository->updateDataFormDuaPerpanjanganPartSatu($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPerpanjanganPartDua(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormDuaPerpanjangan($id);

            // Access the file paths inside the 'data' array
            $currentFiles = $currentData['data']['files_pt2'] ?? [];

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                // Delete old files
                $this->deleteOldFiles($currentData['data']['surat_pengukuhan_pengusaha_kena_pajak']);
                $this->deleteOldFiles($currentData['data']['surat_pernyataan_sehat']);
                $this->deleteOldFiles($currentData['data']['referensi_bank']);
                $this->deleteOldFiles($currentData['data']['neraca_perusahaan_terakhir']);
                $this->deleteOldFiles($currentData['data']['rekening_koran_perusahaan']);

                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->perpanjanganRepository->updateDataFormDuaPerpanjanganPartDua($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPerpanjanganPartTiga(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormDuaPerpanjangan($id);

            // Access the file paths inside the 'data' array
            $currentFiles = $currentData['data']['files_pt3'] ?? [];

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                // Delete old files
                $this->deleteOldFiles($currentData['data']['cv_direktur_utama']);
                $this->deleteOldFiles($currentData['data']['ktp_jajaran_direksi']);
                $this->deleteOldFiles($currentData['data']['skck']);
                $this->deleteOldFiles($currentData['data']['company_profile']);
                $this->deleteOldFiles($currentData['data']['daftar_pengalaman_pekerjaan_di_tni_au']);

                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->perpanjanganRepository->updateDataFormDuaPerpanjanganPartTiga($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPerpanjanganPartEmpat(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormDuaPerpanjangan($id);

            // Access the file paths inside the 'data' array
            $currentFiles = $currentData['data']['files_pt4'] ?? [];

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                // Delete old files
                $this->deleteOldFiles($currentData['data']['daftar_peralatan_fasilitas_kantor']);
                $this->deleteOldFiles($currentData['data']['aset_perusahaan']);
                $this->deleteOldFiles($currentData['data']['surat_kemampuan_principle_agent']);
                $this->deleteOldFiles($currentData['data']['surat_loa_poa']);
                $this->deleteOldFiles($currentData['data']['supporting_letter_dari_vendor']);

                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->perpanjanganRepository->updateDataFormDuaPerpanjanganPartEmpat($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPerpanjanganPartLima(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormDuaPerpanjangan($id);

            // Access the file paths inside the 'data' array
            $currentFiles = $currentData['data']['files_pt5'] ?? [];

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                // Delete old files
                $this->deleteOldFiles($currentData['data']['foto_direktur_4_6']);
                $this->deleteOldFiles($currentData['data']['kepemilikan_kantor']);
                $this->deleteOldFiles($currentData['data']['struktur_organisasi']);
                $this->deleteOldFiles($currentData['data']['foto_perusahaan']);
                $this->deleteOldFiles($currentData['data']['gambar_rute_denah_kantor']);

                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->perpanjanganRepository->updateDataFormDuaPerpanjanganPartLima($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPerpanjanganPartEnam(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormDuaPerpanjangan($id);

            // Access the file paths inside the 'data' array
            $currentFiles = $currentData['data']['files_pt6'] ?? [];

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                // Delete old files
                $this->deleteOldFiles($currentData['data']['kk_direktur_utama']);
                $this->deleteOldFiles($currentData['data']['beranda_lpse']);
                $this->deleteOldFiles($currentData['data']['e_catalog']);

                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->perpanjanganRepository->updateDataFormDuaPerpanjanganPartEnam($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormDuaPerpanjangan($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormDuaPerpanjangan($id);

            // List of file fields to be deleted
            $fileFields = [
                'surat_permohonan_penerbitan',
                'akte_pendirian_perusahaan',
                'akte_perubahan_perusahaan',
                'nomor_izin_berusaha',
                'nomor_pokok_wajib_pajak',
                'surat_pengukuhan_pengusaha_kena_pajak',
                'surat_pernyataan_sehat',
                'referensi_bank',
                'neraca_perusahaan_terakhir',
                'rekening_koran_perusahaan',
                'cv_direktur_utama',
                'ktp_jajaran_direksi',
                'skck',
                'company_profile',
                'daftar_pengalaman_pekerjaan_di_tni_au',
                'daftar_peralatan_fasilitas_kantor',
                'aset_perusahaan',
                'surat_kemampuan_principle_agent',
                'surat_loa_poa',
                'supporting_letter_dari_vendor',
                'foto_direktur_4_6',
                'kepemilikan_kantor',
                'struktur_organisasi',
                'foto_perusahaan',
                'gambar_rute_denah_kantor',
                'kk_direktur_utama',
                'beranda_lpse',
                'e_catalog',
            ];

            // Iterate through file fields and delete files if the path is not null
            foreach ($fileFields as $field) {
                $filePath = $currentData['data'][$field] ?? null;
                if ($filePath) {
                    $this->deleteOldFiles($filePath);
                }
            }

            // Delete the record in the repository
            return $this->perpanjanganRepository->deleteDataFormDuaPerpanjangan($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Tiga

    public function listDataFormTigaPerpanjangan()
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormTigaPerpanjangan();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formEmpat) {
                    // Decrypt fields

                    if (isset($formEmpat->catatan_revisi)) {
                        $response['data'][$key]->catatan_revisi = Crypt::decrypt($formEmpat->catatan_revisi);
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

    public function listDataFormTigaPerpanjanganByFormDua($id)
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormTigaPerpanjanganByFormDua($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formEmpat) {
                    // Decrypt fields

                    if (isset($formEmpat->catatan_revisi)) {
                        $response['data'][$key]->catatan_revisi = Crypt::decrypt($formEmpat->catatan_revisi);
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

    public function detailDataFormTigaPerpanjangan($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->perpanjanganRepository->detailDataFormTigaPerpanjangan($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formEmpat = $response['data'];

                if (isset($formEmpat['catatan_revisi'])) {
                    $response['data']['catatan_revisi'] = Crypt::decrypt($formEmpat['catatan_revisi']);
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

    public function inputDataFormTigaPerpanjangan($dataRequest)
    {
        try {
            return $this->perpanjanganRepository->inputDataFormTigaPerpanjangan($dataRequest);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPerpanjangan($dataRequest, $id)
    {
        try {
            return $this->perpanjanganRepository->updateDataFormTigaPerpanjangan($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormTigaPerpanjangan($id)
    {
        try {
            return $this->perpanjanganRepository->deleteDataFormTigaPerpanjangan($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Empat

    public function listDataFormEmpatPerpanjangan()
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormEmpatPerpanjangan();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formLima) {
                    // Decrypt fields

                    // Decrypt and retrieve 'foto_personil' if it exists
                    if (!empty($formLima->skhpp)) {
                        $filePath = $formLima->skhpp;
                        if (Storage::disk('public')->exists($filePath)) {
                            $encryptedContent = Storage::disk('public')->get($filePath);
                            $decryptedContent = Crypt::decrypt($encryptedContent);
                            // Optionally return the decrypted content as a base64 encoded string
                            $response['data'][$key]->skhpp = base64_encode($decryptedContent);
                        }
                    }

                    if (isset($formLima->catatan_revisi)) {
                        $response['data'][$key]->catatan_revisi = Crypt::decrypt($formLima->catatan_revisi);
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

    public function listDataFormEmpatPerpanjanganByFormTiga($id)
    {
        try {
            // Get the data from the repository
            $response = $this->perpanjanganRepository->listDataFormEmpatPerpanjanganByFormTiga($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formLima) {
                    // Decrypt fields

                    // Decrypt and retrieve 'foto_personil' if it exists
                    if (!empty($formLima->skhpp)) {
                        $filePath = $formLima->skhpp;
                        if (Storage::disk('public')->exists($filePath)) {
                            $encryptedContent = Storage::disk('public')->get($filePath);
                            $decryptedContent = Crypt::decrypt($encryptedContent);
                            // Optionally return the decrypted content as a base64 encoded string
                            $response['data'][$key]->skhpp = base64_encode($decryptedContent);
                        }
                    }

                    if (isset($formLima->catatan_revisi)) {
                        $response['data'][$key]->catatan_revisi = Crypt::decrypt($formLima->catatan_revisi);
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

    public function detailDataFormEmpatPerpanjanganByFormTiga($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->perpanjanganRepository->detailDataFormEmpatPerpanjanganByFormTiga($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formEmpat = $response['data'];

                // Decrypt and retrieve 'foto_kejadian' if it exists
                if (!empty($formEmpat['skhpp'])) {
                    $filePath = $formEmpat['skhpp'];
                    if (Storage::disk('public')->exists($filePath)) {
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);
                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['skhpp'] = base64_encode($decryptedContent);
                    }
                }

                if (isset($formEmpat['catatan_revisi'])) {
                    $response['data']['catatan_revisi'] = Crypt::decrypt($formEmpat['catatan_revisi']);
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

    public function detailDataFormEmpatPerpanjangan($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->perpanjanganRepository->detailDataFormEmpatPerpanjangan($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formEmpat = $response['data'];

                // Decrypt and retrieve 'foto_kejadian' if it exists
                if (!empty($formEmpat['skhpp'])) {
                    $filePath = $formEmpat['skhpp'];
                    if (Storage::disk('public')->exists($filePath)) {
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);
                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['skhpp'] = base64_encode($decryptedContent);
                    }
                }

                if (isset($formEmpat['catatan_revisi'])) {
                    $response['data']['catatan_revisi'] = Crypt::decrypt($formEmpat['catatan_revisi']);
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

    public function inputDataFormEmpatPerpanjangan(array $dataRequest, array $files)
    {
        try {
            if (!empty($files)) {
                // Simpan file dan ambil nama file
                $fileNames = $this->saveFiles($files);

                // Masukkan data ke repositori
                return $this->perpanjanganRepository->inputDataFormEmpatPerpanjangan(array_merge($dataRequest, $fileNames));
            } else {
                return $this->perpanjanganRepository->inputDataFormEmpatPerpanjangan($dataRequest);
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

    public function updateDataFormEmpatPerpanjangan(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormEmpatPerpanjangan($id);

            // Access the file paths inside the 'data' array
            $currentFiles = $currentData['data']['files'] ?? [];

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                if ($currentData['data']['skhpp'] != null) {
                    // Delete old files
                    $this->deleteOldFiles($currentData['data']['skhpp']);
                }
                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->perpanjanganRepository->updateDataFormEmpatPerpanjangan($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormEmpatPerpanjangan($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->perpanjanganRepository->detailDataFormEmpatPerpanjanganByFormTiga($id);

            $currentFiles = $currentData['data']['files'] ?? [];
            // Delete the associated files
            if ($currentData['data']['skhpp'] != null) {
                $this->deleteOldFiles($currentData['data']['skhpp']);
            }

            // Delete the record in the repository
            return $this->perpanjanganRepository->deleteDataFormEmpatPerpanjangan($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Dll

    public function verifyDataFormPerpanjangan($dataRequest, $id)
    {
        try {
            $dataEncrypt = [
                'jenis_form' => $dataRequest['jenis_form'],
                'status' => $dataRequest['status'],
                'admin_litpers_id' => $dataRequest['admin_litpers_id'],
            ];
            if (isset($dataRequest['catatan_revisi'])) {
                $dataEncrypt['catatan_revisi'] = Crypt::encrypt($dataRequest['catatan_revisi']);
            }
            if ($dataRequest['jenis_form'] == 'form1') {
                return $this->perpanjanganRepository->verifyFormSatuPerpanjangan($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form2') {
                return $this->perpanjanganRepository->verifyFormDuaPerpanjangan($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form3') {
                return $this->perpanjanganRepository->verifyFormTigaPerpanjangan($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form4') {
                return $this->perpanjanganRepository->verifyFormEmpatPerpanjangan($dataEncrypt, $id);
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 401,
                    "message" => 'jenis form tidak sesuai'
                ];
            }
        } catch (\Throwable $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    private function saveFiles(array $files)
    {
        $fileNames = [];

        foreach ($files as $key => $file) {
            if ($file->isValid()) {
                $filePath = $file->store('litpers/perpanjangan', 'public');
                $fileContent = file_get_contents($file->getRealPath());
                $encryptedContent = Crypt::encrypt($fileContent);
                Storage::disk('public')->put($filePath, $encryptedContent);
                $fileNames[$key] = $filePath; // Menyimpan nama file
            }
        }

        return $fileNames;
    }

    private function deleteOldFiles($filePath)
    {
        // foreach ($filePaths as $filePath) {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        // }
    }
}
