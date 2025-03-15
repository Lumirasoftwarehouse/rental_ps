<?php

namespace App\Services\Litpers;

use App\Repositories\Litpers\PenerbitanBaruRepository;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PenerbitanBaruService
{
    private $penerbitanBaruRepository;

    public function __construct(PenerbitanBaruRepository $penerbitanBaruRepository)
    {
        $this->penerbitanBaruRepository = $penerbitanBaruRepository;
    }

    public function listDataAllFormPenerbitanBaruByMitra($id)
    {
        try {
            return $this->penerbitanBaruRepository->listDataAllFormPenerbitanBaruByMitra($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataUnfinishedFormPenerbitanBaruByMitra($id)
    {
        try {
            return $this->penerbitanBaruRepository->listDataUnfinishedFormPenerbitanBaruByMitra($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataUnvalidatedFormPenerbitanBaru()
    {
        try {
            return $this->penerbitanBaruRepository->listDataUnvalidatedFormPenerbitanBaru();
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

    public function listDataFormSatuPenerbitanBaru()
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormSatuPenerbitanBaru();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->penerbitanBaruRepository->detailDataFormSatuPenerbitanBaru($formSatu->id);

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

    public function listDataFormSatuPenerbitanBaruByAdmin($id)
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormSatuPenerbitanBaruByAdmin($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->penerbitanBaruRepository->detailDataFormSatuPenerbitanBaru($formSatu->id);

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

    public function listDataFormSatuPenerbitanBaruByMitra($id)
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormSatuPenerbitanBaruByMitra($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->penerbitanBaruRepository->detailDataFormSatuPenerbitanBaru($formSatu->id);

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

    public function detailDataFormSatuPenerbitanBaru($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->penerbitanBaruRepository->detailDataFormSatuPenerbitanBaru($id);

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

    public function inputDataFormSatuPenerbitanBaru(array $dataRequest, array $files)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->penerbitanBaruRepository->inputDataFormSatuPenerbitanBaru(array_merge($dataRequest, $fileNames));
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormSatuPenerbitanBaru(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormSatuPenerbitanBaru($id);

            // If there are files, delete the old ones and store the new files
            if (!empty($files)) {
                // Delete old files
                $this->deleteOldFiles($currentData['data']['surat_disadaau_diskonsau']);

                // Save new files and merge with data request
                $fileNames = $this->saveFiles($files);
                $dataRequest = array_merge($dataRequest, $fileNames);
            }

            // Update the data in the repository
            return $this->penerbitanBaruRepository->updateDataFormSatuPenerbitanBaru($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormSatuPenerbitanBaru($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormSatuPenerbitanBaru($id);

            // Delete the associated files
            $this->deleteOldFiles($currentData['data']['surat_disadaau_diskonsau']); // assuming 'files' contains the file paths

            // Delete the record in the repository
            return $this->penerbitanBaruRepository->deleteDataFormSatuPenerbitanBaru($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Dua

    public function listDataFormDuaPenerbitanBaru()
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormDuaPenerbitanBaru();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formDua) {
                    // Decrypt fields

                    if (isset($formDua->catatan_revisi)) {
                        $response['data'][$key]->catatan_revisi = Crypt::decrypt($formDua->catatan_revisi);
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

    public function listDataFormDuaPenerbitanBaruByFormSatu($id)
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormDuaPenerbitanBaruByFormSatu($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formDua) {
                    // Decrypt fields

                    if (isset($formDua->catatan_revisi)) {
                        $response['data'][$key]->catatan_revisi = Crypt::decrypt($formDua->catatan_revisi);
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

    public function detailDataFormDuaPenerbitanBaru($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->penerbitanBaruRepository->detailDataFormDuaPenerbitanBaru($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formDua = $response['data'];

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

    public function inputDataFormDuaPenerbitanBaru($dataRequest)
    {
        try {
            return $this->penerbitanBaruRepository->inputDataFormDuaPenerbitanBaru($dataRequest);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPenerbitanBaru($dataRequest, $id)
    {
        try {
            return $this->penerbitanBaruRepository->updateDataFormDuaPenerbitanBaru($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormDuaPenerbitanBaru($id)
    {
        try {
            return $this->penerbitanBaruRepository->deleteDataFormDuaPenerbitanBaru($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Tiga

    public function listDataFormTigaPenerbitanBaru()
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormTigaPenerbitanBaru();

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

    public function listDataFormTigaPenerbitanBaruByFormDua($id)
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormTigaPenerbitanBaruByFormDua($id);

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

    public function detailDataFormTigaPenerbitanBaru($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formTiga = $response['data'];

                foreach ($formTiga['files_pt1'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt1'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formTiga['files_pt2'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt2'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formTiga['files_pt3'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt3'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formTiga['files_pt4'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt4'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formTiga['files_pt5'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt5'][$key] = base64_encode($decryptedContent);
                    }
                }
                foreach ($formTiga['files_pt6'] as $key => $filePath) {
                    if ($filePath && Storage::disk('public')->exists($filePath)) {
                        // Get the encrypted content and decrypt it
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);

                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['files_pt6'][$key] = base64_encode($decryptedContent);
                    }
                }

                if (isset($formTiga['catatan_revisi'])) {
                    $response['data']['catatan_revisi'] = Crypt::decrypt($formTiga['catatan_revisi']);
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

    public function inputDataFormTigaPenerbitanBaruPartSatu(array $dataRequest, array $files)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->penerbitanBaruRepository->inputDataFormTigaPenerbitanBaruPartSatu(array_merge($dataRequest, $fileNames));
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartDua(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->penerbitanBaruRepository->inputDataFormTigaPenerbitanBaruPartDua(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartTiga(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->penerbitanBaruRepository->inputDataFormTigaPenerbitanBaruPartTiga(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartEmpat(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->penerbitanBaruRepository->inputDataFormTigaPenerbitanBaruPartEmpat(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartLima(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->penerbitanBaruRepository->inputDataFormTigaPenerbitanBaruPartLima(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartEnam(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->penerbitanBaruRepository->inputDataFormTigaPenerbitanBaruPartEnam(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartSatu(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($id);


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
            return $this->penerbitanBaruRepository->updateDataFormTigaPenerbitanBaruPartSatu($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartDua(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($id);

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
            return $this->penerbitanBaruRepository->updateDataFormTigaPenerbitanBaruPartDua($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartTiga(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($id);

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
            return $this->penerbitanBaruRepository->updateDataFormTigaPenerbitanBaruPartTiga($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartEmpat(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($id);

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
            return $this->penerbitanBaruRepository->updateDataFormTigaPenerbitanBaruPartEmpat($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartLima(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($id);

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
            return $this->penerbitanBaruRepository->updateDataFormTigaPenerbitanBaruPartLima($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartEnam(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($id);

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
            return $this->penerbitanBaruRepository->updateDataFormTigaPenerbitanBaruPartEnam($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormTigaPenerbitanBaru($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormTigaPenerbitanBaru($id);

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
            return $this->penerbitanBaruRepository->deleteDataFormTigaPenerbitanBaru($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Empat

    public function listDataFormEmpatPenerbitanBaru()
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormEmpatPenerbitanBaru();

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

    public function listDataFormEmpatPenerbitanBaruByFormTiga($id)
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormEmpatPenerbitanBaruByFormTiga($id);

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

    public function detailDataFormEmpatPenerbitanBaru($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->penerbitanBaruRepository->detailDataFormEmpatPenerbitanBaru($id);

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

    public function inputDataFormEmpatPenerbitanBaru($dataRequest)
    {
        try {
            return $this->penerbitanBaruRepository->inputDataFormEmpatPenerbitanBaru($dataRequest);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormEmpatPenerbitanBaru($dataRequest, $id)
    {
        try {
            return $this->penerbitanBaruRepository->updateDataFormEmpatPenerbitanBaru($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormEmpatPenerbitanBaru($id)
    {
        try {
            return $this->penerbitanBaruRepository->deleteDataFormEmpatPenerbitanBaru($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Lima

    public function listDataFormLimaPenerbitanBaru()
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormLimaPenerbitanBaru();

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

    public function listDataFormLimaPenerbitanBaruByFormEmpat($id)
    {
        try {
            // Get the data from the repository
            $response = $this->penerbitanBaruRepository->listDataFormLimaPenerbitanBaruByFormEmpat($id);

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

    public function detailDataFormLimaPenerbitanBaruByFormEmpat($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->penerbitanBaruRepository->detailDataFormLimaPenerbitanBaruByFormEmpat($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formLima = $response['data'];

                // Decrypt and retrieve 'foto_kejadian' if it exists
                if (!empty($formLima['skhpp'])) {
                    $filePath = $formLima['skhpp'];
                    if (Storage::disk('public')->exists($filePath)) {
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);
                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['skhpp'] = base64_encode($decryptedContent);
                    }
                }

                if (isset($formLima['catatan_revisi'])) {
                    $response['data']['catatan_revisi'] = Crypt::decrypt($formLima['catatan_revisi']);
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

    public function detailDataFormLimaPenerbitanBaru($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->penerbitanBaruRepository->detailDataFormLimaPenerbitanBaru($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $formLima = $response['data'];

                // Decrypt and retrieve 'foto_kejadian' if it exists
                if (!empty($formLima['skhpp'])) {
                    $filePath = $formLima['skhpp'];
                    if (Storage::disk('public')->exists($filePath)) {
                        $encryptedContent = Storage::disk('public')->get($filePath);
                        $decryptedContent = Crypt::decrypt($encryptedContent);
                        // Optionally return the decrypted content as a base64 encoded string
                        $response['data']['skhpp'] = base64_encode($decryptedContent);
                    }
                }

                if (isset($formLima['catatan_revisi'])) {
                    $response['data']['catatan_revisi'] = Crypt::decrypt($formLima['catatan_revisi']);
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

    public function inputDataFormLimaPenerbitanBaru(array $dataRequest, array $files)
    {
        try {
            if (!empty($files)) {
                // Simpan file dan ambil nama file
                $fileNames = $this->saveFiles($files);

                // Masukkan data ke repositori
                return $this->penerbitanBaruRepository->inputDataFormLimaPenerbitanBaru(array_merge($dataRequest, $fileNames));
            } else {
                return $this->penerbitanBaruRepository->inputDataFormLimaPenerbitanBaru($dataRequest);
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

    public function updateDataFormLimaPenerbitanBaru(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormLimaPenerbitanBaru($id);

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
            return $this->penerbitanBaruRepository->updateDataFormLimaPenerbitanBaru($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormLimaPenerbitanBaru($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->penerbitanBaruRepository->detailDataFormLimaPenerbitanBaruByFormEmpat($id);

            // Delete the associated files
            if ($currentData['data']['skhpp'] != null) {
                $this->deleteOldFiles($currentData['data']['skhpp']);
            }

            // Delete the record in the repository
            return $this->penerbitanBaruRepository->deleteDataFormLimaPenerbitanBaru($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Dll

    public function verifyDataFormPenerbitanBaru($dataRequest, $id)
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
                return $this->penerbitanBaruRepository->verifyFormSatuPenerbitanBaru($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form2') {
                return $this->penerbitanBaruRepository->verifyFormDuaPenerbitanBaru($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form3') {
                return $this->penerbitanBaruRepository->verifyFormTigaPenerbitanBaru($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form4') {
                return $this->penerbitanBaruRepository->verifyFormEmpatPenerbitanBaru($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form5') {
                return $this->penerbitanBaruRepository->verifyFormLimaPenerbitanBaru($dataEncrypt, $id);
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

    private function decryptString($string)
    {
        // Check if the string is not null before decrypting
        if ($string) {
            try {
                // Decrypt the string and return it
                return Crypt::decrypt($string);
            } catch (\Exception $e) {
                // Return null if decryption fails
                return null;
            }
        }

        // Return null if the string is null
        return null;
    }

    private function decryptFile($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            // Get the encrypted content
            $encryptedContent = Storage::disk('public')->get($filePath);

            // Decrypt the content
            try {
                $decryptedContent = Crypt::decrypt($encryptedContent);
                // Optionally return the decrypted content as a base64 encoded string
                return base64_encode($decryptedContent);
            } catch (\Exception $e) {
                // Return null or handle errors as needed
                return null;
            }
        }
        return null;
    }

    private function saveFiles(array $files)
    {
        $fileNames = [];

        foreach ($files as $key => $file) {
            if ($file->isValid()) {
                $filePath = $file->store('litpers/penerbitan_baru', 'public');
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
