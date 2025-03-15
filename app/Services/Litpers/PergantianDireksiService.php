<?php

namespace App\Services\Litpers;

use App\Repositories\Litpers\PergantianDireksiRepository;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class PergantianDireksiService
{
    private $pergantianDireksiRepository;

    public function __construct(PergantianDireksiRepository $pergantianDireksiRepository)
    {
        $this->pergantianDireksiRepository = $pergantianDireksiRepository;
    }

    public function listDataAllFormPergantianDireksiByMitra($id)
    {
        try {
            return $this->pergantianDireksiRepository->listDataAllFormPergantianDireksiByMitra($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataUnfinishedFormPergantianDireksiByMitra($id)
    {
        try {
            return $this->pergantianDireksiRepository->listDataUnfinishedFormPergantianDireksiByMitra($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataUnvalidatedFormPergantianDireksi()
    {
        try {
            return $this->pergantianDireksiRepository->listDataUnvalidatedFormPergantianDireksi();
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

    public function listDataFormSatuPergantianDireksi()
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormSatuPergantianDireksi();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->pergantianDireksiRepository->detailDataFormSatuPergantianDireksi($formSatu->id);

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

    public function listDataFormSatuPergantianDireksiByAdmin($id)
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormSatuPergantianDireksiByAdmin($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->pergantianDireksiRepository->detailDataFormSatuPergantianDireksi($formSatu->id);

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

    public function listDataFormSatuPergantianDireksiByMitra($id)
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormSatuPergantianDireksiByMitra($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $formSatu) {
                    // Fetch detailed data for each entry
                    $currentData = $this->pergantianDireksiRepository->detailDataFormSatuPergantianDireksi($formSatu->id);

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

    public function detailDataFormSatuPergantianDireksi($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->pergantianDireksiRepository->detailDataFormSatuPergantianDireksi($id);

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

    public function inputDataFormSatuPergantianDireksi(array $dataRequest, array $files)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->pergantianDireksiRepository->inputDataFormSatuPergantianDireksi(array_merge($dataRequest, $fileNames));
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormSatuPergantianDireksi(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormSatuPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->updateDataFormSatuPergantianDireksi($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormSatuPergantianDireksi($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormSatuPergantianDireksi($id);

            // Delete the associated files
            $this->deleteOldFiles($currentData['data']['surat_disadaau_diskonsau']); // assuming 'files' contains the file paths
            $this->deleteOldFiles($currentData['data']['skhpp_lama']); // assuming 'files' contains the file paths

            // Delete the record in the repository
            return $this->pergantianDireksiRepository->deleteDataFormSatuPergantianDireksi($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Dua

    public function listDataFormDuaPergantianDireksi()
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormDuaPergantianDireksi();

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

    public function listDataFormDuaPergantianDireksiByFormSatu($id)
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormDuaPergantianDireksiByFormSatu($id);

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

    public function detailDataFormDuaPergantianDireksi($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->pergantianDireksiRepository->detailDataFormDuaPergantianDireksi($id);

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

    public function inputDataFormDuaPergantianDireksi($dataRequest)
    {
        try {
            return $this->pergantianDireksiRepository->inputDataFormDuaPergantianDireksi($dataRequest);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPergantianDireksi($dataRequest, $id)
    {
        try {
            return $this->pergantianDireksiRepository->updateDataFormDuaPergantianDireksi($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormDuaPergantianDireksi($id)
    {
        try {
            return $this->pergantianDireksiRepository->deleteDataFormDuaPergantianDireksi($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Tiga

    public function listDataFormTigaPergantianDireksi()
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormTigaPergantianDireksi();

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

    public function listDataFormTigaPergantianDireksiByFormDua($id)
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormTigaPergantianDireksiByFormDua($id);

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

    public function detailDataFormTigaPergantianDireksi($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->pergantianDireksiRepository->detailDataFormTigaPergantianDireksi($id);

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

    public function inputDataFormTigaPergantianDireksiPartSatu(array $dataRequest, array $files)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->pergantianDireksiRepository->inputDataFormTigaPergantianDireksiPartSatu(array_merge($dataRequest, $fileNames));
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPergantianDireksiPartDua(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->pergantianDireksiRepository->inputDataFormTigaPergantianDireksiPartDua(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPergantianDireksiPartTiga(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->pergantianDireksiRepository->inputDataFormTigaPergantianDireksiPartTiga(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPergantianDireksiPartEmpat(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->pergantianDireksiRepository->inputDataFormTigaPergantianDireksiPartEmpat(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPergantianDireksiPartLima(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->pergantianDireksiRepository->inputDataFormTigaPergantianDireksiPartLima(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPergantianDireksiPartEnam(array $dataRequest, array $files, $id)
    {
        try {
            // Simpan file dan ambil nama file
            $fileNames = $this->saveFiles($files);

            // Masukkan data ke repositori
            return $this->pergantianDireksiRepository->inputDataFormTigaPergantianDireksiPartEnam(array_merge($dataRequest, $fileNames), $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPergantianDireksiPartSatu(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormTigaPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->updateDataFormTigaPergantianDireksiPartSatu($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPergantianDireksiPartDua(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormTigaPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->updateDataFormTigaPergantianDireksiPartDua($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPergantianDireksiPartTiga(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormTigaPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->updateDataFormTigaPergantianDireksiPartTiga($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPergantianDireksiPartEmpat(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormTigaPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->updateDataFormTigaPergantianDireksiPartEmpat($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPergantianDireksiPartLima(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormTigaPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->updateDataFormTigaPergantianDireksiPartLima($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPergantianDireksiPartEnam(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormTigaPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->updateDataFormTigaPergantianDireksiPartEnam($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormTigaPergantianDireksi($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormTigaPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->deleteDataFormTigaPergantianDireksi($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Empat

    public function listDataFormEmpatPergantianDireksi()
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormEmpatPergantianDireksi();

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

    public function listDataFormEmpatPergantianDireksiByFormTiga($id)
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormEmpatPergantianDireksiByFormTiga($id);

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

    public function detailDataFormEmpatPergantianDireksi($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->pergantianDireksiRepository->detailDataFormEmpatPergantianDireksi($id);

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

    public function inputDataFormEmpatPergantianDireksi($dataRequest)
    {
        try {
            return $this->pergantianDireksiRepository->inputDataFormEmpatPergantianDireksi($dataRequest);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormEmpatPergantianDireksi($dataRequest, $id)
    {
        try {
            return $this->pergantianDireksiRepository->updateDataFormEmpatPergantianDireksi($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormEmpatPergantianDireksi($id)
    {
        try {
            return $this->pergantianDireksiRepository->deleteDataFormEmpatPergantianDireksi($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Lima

    public function listDataFormLimaPergantianDireksi()
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormLimaPergantianDireksi();

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

    public function listDataFormLimaPergantianDireksiByFormEmpat($id)
    {
        try {
            // Get the data from the repository
            $response = $this->pergantianDireksiRepository->listDataFormLimaPergantianDireksiByFormEmpat($id);

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

    public function detailDataFormLimaPergantianDireksiByFormEmpat($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->pergantianDireksiRepository->detailDataFormLimaPergantianDireksiByFormEmpat($id);

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

    public function detailDataFormLimaPergantianDireksi($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->pergantianDireksiRepository->detailDataFormLimaPergantianDireksi($id);

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

    public function inputDataFormLimaPergantianDireksi(array $dataRequest, array $files)
    {
        try {
            if (!empty($files)) {
                // Simpan file dan ambil nama file
                $fileNames = $this->saveFiles($files);

                // Masukkan data ke repositori
                return $this->pergantianDireksiRepository->inputDataFormLimaPergantianDireksi(array_merge($dataRequest, $fileNames));
            } else {
                return $this->pergantianDireksiRepository->inputDataFormLimaPergantianDireksi($dataRequest);
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

    public function updateDataFormLimaPergantianDireksi(array $dataRequest, array $files, $id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormLimaPergantianDireksi($id);

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
            return $this->pergantianDireksiRepository->updateDataFormLimaPergantianDireksi($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormLimaPergantianDireksi($id)
    {
        try {
            // Get the current record data to retrieve the current file paths
            $currentData = $this->pergantianDireksiRepository->detailDataFormLimaPergantianDireksiByFormEmpat($id);

            $currentFiles = $currentData['data']['files'] ?? [];
            // Delete the associated files
            if ($currentData['data']['skhpp'] != null) {
                $this->deleteOldFiles($currentData['data']['skhpp']);
            }

            // Delete the record in the repository
            return $this->pergantianDireksiRepository->deleteDataFormLimaPergantianDireksi($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Dll

    public function verifyDataFormPergantianDireksi($dataRequest, $id)
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
                return $this->pergantianDireksiRepository->verifyFormSatuPergantianDireksi($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form2') {
                return $this->pergantianDireksiRepository->verifyFormDuaPergantianDireksi($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form3') {
                return $this->pergantianDireksiRepository->verifyFormTigaPergantianDireksi($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form4') {
                return $this->pergantianDireksiRepository->verifyFormEmpatPergantianDireksi($dataEncrypt, $id);
            } elseif ($dataRequest['jenis_form'] == 'form5') {
                return $this->pergantianDireksiRepository->verifyFormLimaPergantianDireksi($dataEncrypt, $id);
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
                $filePath = $file->store('litpers/pergantian_direksi', 'public');
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
