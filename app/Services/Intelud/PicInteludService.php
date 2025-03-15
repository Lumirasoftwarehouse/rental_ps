<?php

namespace App\Services\Intelud;

use App\Repositories\Intelud\PicInteludRepository;
use Illuminate\Support\Facades\Crypt;

class PicInteludService
{
    private $picInteludRepository;

    public function __construct(PicInteludRepository $picInteludRepository)
    {
        $this->picInteludRepository = $picInteludRepository;
    }

    public function jumlahPengajuanByPic()
    {
        try {
            return $this->picInteludRepository->jumlahPengajuanByPic();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "data" => $e->getMessage()
            ];
        }
    }

    public function listDataPicIntelud()
    {
        try {
            // Get the data from the repository
            $response = $this->picInteludRepository->listDataPicIntelud();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $picIntelud) {
                    // Decrypt fields
                    $response['data'][$key]->nama_pic = Crypt::decrypt($picIntelud->nama_pic);
                    $response['data'][$key]->jabatan_pic = Crypt::decrypt($picIntelud->jabatan_pic);
                    $response['data'][$key]->hp_pic = Crypt::decrypt($picIntelud->hp_pic);
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

    public function detailDataPicIntelud($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->picInteludRepository->detailDataPicIntelud($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $picIntelud = $response['data'];

                // Decrypt encrypted fields in the 'picIntelud' data
                $response['data']->nama_pic = Crypt::decrypt($picIntelud->nama_pic);
                $response['data']->jabatan_pic = Crypt::decrypt($picIntelud->jabatan_pic);
                $response['data']->hp_pic = Crypt::decrypt($picIntelud->hp_pic);
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

    public function inputDataPicIntelud($dataRequest)
    {
        try {
            $dataEncrypt = [
                'name' => $dataRequest['name'],
                'email' => $dataRequest['email'],
                'password' => $dataRequest['password'],
                'nama_pic' => Crypt::encrypt($dataRequest['nama_pic']),
                'jabatan_pic' => Crypt::encrypt($dataRequest['jabatan_pic']),
                'hp_pic' => Crypt::encrypt($dataRequest['hp_pic']),
            ];
            return $this->picInteludRepository->inputDataPicIntelud($dataEncrypt);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataPicIntelud($dataRequest, $id)
    {
        try {
            $dataEncrypt = [
                'name' => $dataRequest['name'],
                'email' => $dataRequest['email'],
                'password' => $dataRequest['password'],
                'nama_pic' => Crypt::encrypt($dataRequest['nama_pic']),
                'jabatan_pic' => Crypt::encrypt($dataRequest['jabatan_pic']),
                'hp_pic' => Crypt::encrypt($dataRequest['hp_pic']),
            ];
            return $this->picInteludRepository->updateDataPicIntelud($dataEncrypt, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataPicIntelud($id)
    {
        try {
            return $this->picInteludRepository->deleteDataPicIntelud($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
