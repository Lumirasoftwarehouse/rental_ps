<?php

namespace App\Services\Intelud;

use App\Repositories\Intelud\KaintelRepository;
use Illuminate\Support\Facades\Crypt;

class KaintelService
{
    private $kaintelRepository;

    public function __construct(KaintelRepository $kaintelRepository)
    {
        $this->kaintelRepository = $kaintelRepository;
    }

    public function listDataKaintel()
    {
        try {
            // Get the data from the repository
            $response = $this->kaintelRepository->listDataKaintel();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $kaintel) {
                    // Decrypt fields
                    $response['data'][$key]->nama_kaintel = Crypt::decrypt($kaintel->nama_kaintel);
                    $response['data'][$key]->alamat_kaintel = Crypt::decrypt($kaintel->alamat_kaintel);
                    $response['data'][$key]->hp = Crypt::decrypt($kaintel->hp);
                    $response['data'][$key]->nama_lanud = Crypt::decrypt($kaintel->nama_lanud);
                    $response['data'][$key]->alamat_lanud = Crypt::decrypt($kaintel->alamat_lanud);
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

    public function detailDataKaintel($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->kaintelRepository->detailDataKaintel($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $kaintel = $response['data'];

                // Decrypt encrypted fields in the 'kaintel' data
                $response['data']->nama_kaintel = Crypt::decrypt($kaintel->nama_kaintel);
                $response['data']->alamat_kaintel = Crypt::decrypt($kaintel->alamat_kaintel);
                $response['data']->hp = Crypt::decrypt($kaintel->hp);
                $response['data']->nama_lanud = Crypt::decrypt($kaintel->nama_lanud);
                $response['data']->alamat_lanud = Crypt::decrypt($kaintel->alamat_lanud);
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

    public function inputDataKaintel($dataRequest)
    {
        try {
            $dataEncrypt = [
                'name' => $dataRequest['name'],
                'email' => $dataRequest['email'],
                'password' => $dataRequest['password'],
                'nama_kaintel' => Crypt::encrypt($dataRequest['nama_kaintel']),
                'alamat' => Crypt::encrypt($dataRequest['alamat']),
                'hp' => Crypt::encrypt($dataRequest['hp']),
                'lanud_id' => $dataRequest['lanud_id'],
            ];
            return $this->kaintelRepository->inputDataKaintel($dataEncrypt);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataKaintel($dataRequest, $id)
    {
        try {
            $dataEncrypt = [
                'name' => $dataRequest['name'],
                'email' => $dataRequest['email'],
                'password' => $dataRequest['password'],
                'nama_kaintel' => Crypt::encrypt($dataRequest['nama_kaintel']),
                'alamat' => Crypt::encrypt($dataRequest['alamat']),
                'hp' => Crypt::encrypt($dataRequest['hp']),
                'lanud_id' => $dataRequest['lanud_id'],
            ];
            return $this->kaintelRepository->updateDataKaintel($dataEncrypt, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataKaintel($id)
    {
        try {
            return $this->kaintelRepository->deleteDataKaintel($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
