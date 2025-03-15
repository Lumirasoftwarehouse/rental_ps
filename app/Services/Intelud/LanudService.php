<?php

namespace App\Services\Intelud;

use App\Repositories\Intelud\LanudRepository;
use Illuminate\Support\Facades\Crypt;

class LanudService
{
    private $lanudRepository;

    public function __construct(LanudRepository $lanudRepository)
    {
        $this->lanudRepository = $lanudRepository;
    }

    public function listDataLanud()
    {
        try {
            // Get the data from the repository
            $response = $this->lanudRepository->listDataLanud();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $lanud) {
                    // Decrypt fields
                    $response['data'][$key]->nama_lanud = Crypt::decrypt($lanud->nama_lanud);
                    $response['data'][$key]->alamat = Crypt::decrypt($lanud->alamat);
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

    public function detailDataLanud($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->lanudRepository->detailDataLanud($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $lanud = $response['data'];

                // Decrypt encrypted fields in the 'lanud' data
                $response['data']->nama_lanud = Crypt::decrypt($lanud->nama_lanud);
                $response['data']->alamat = Crypt::decrypt($lanud->alamat);
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

    public function inputDataLanud($dataRequest)
    {
        try {
            $dataEncrypt = [
                'nama_lanud' => Crypt::encrypt($dataRequest['nama_lanud']),
                'alamat' => Crypt::encrypt($dataRequest['alamat']),
            ];
            return $this->lanudRepository->inputDataLanud($dataEncrypt);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataLanud($dataRequest, $id)
    {
        try {
            $dataEncrypt = [
                'nama_lanud' => Crypt::encrypt($dataRequest['nama_lanud']),
                'alamat' => Crypt::encrypt($dataRequest['alamat']),
            ];
            return $this->lanudRepository->updateDataLanud($dataEncrypt, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataLanud($id)
    {
        try {
            return $this->lanudRepository->deleteDataLanud($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
