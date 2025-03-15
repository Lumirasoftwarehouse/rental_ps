<?php

namespace App\Services\Litpers;

use App\Repositories\Litpers\PicPerusahaanLitpersRepository;
use Illuminate\Support\Facades\Crypt;

class PicPerusahaanLitpersService
{
    private $picPerusahaanLitpersRepository;

    public function __construct(PicPerusahaanLitpersRepository $picPerusahaanLitpersRepository)
    {
        $this->picPerusahaanLitpersRepository = $picPerusahaanLitpersRepository;
    }

    public function listDataPicPerusahaanLitpers()
    {
        try {
            $response = $this->picPerusahaanLitpersRepository->listDataPicPerusahaanLitpers();

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $mitraLitpers) {
                    // Decrypt fields
                    $response['data'][$key]->nama_perusahaan = Crypt::decrypt($mitraLitpers->nama_perusahaan);
                    $response['data'][$key]->nama_pic = Crypt::decrypt($mitraLitpers->nama_pic);
                    $response['data'][$key]->jabatan_pic = Crypt::decrypt($mitraLitpers->jabatan_pic);
                    $response['data'][$key]->hp_pic = Crypt::decrypt($mitraLitpers->hp_pic);
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

    public function detailDataPicPerusahaanLitpers($id)
    {
        try {
            $response = $this->picPerusahaanLitpersRepository->detailDataPicPerusahaanLitpers($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $mitraLitpers = $response['data'];

                // Decrypt encrypted fields in the 'mitraLitpers' data
                $response['data']->nama_perusahaan = Crypt::decrypt($mitraLitpers->nama_perusahaan);
                $response['data']->nama_pic = Crypt::decrypt($mitraLitpers->nama_pic);
                $response['data']->jabatan_pic = Crypt::decrypt($mitraLitpers->jabatan_pic);
                $response['data']->hp_pic = Crypt::decrypt($mitraLitpers->hp_pic);
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

    public function inputDataPicPerusahaanLitpers($dataRequest)
    {
        try {
            $dataEncrypt = [
                'name' => $dataRequest['name'],
                'email' => $dataRequest['email'],
                'password' => $dataRequest['password'],
                'nama_perusahaan' => Crypt::encrypt($dataRequest['nama_perusahaan']),
                'nama_pic' => Crypt::encrypt($dataRequest['nama_pic']),
                'jabatan_pic' => Crypt::encrypt($dataRequest['jabatan_pic']),
                'hp_pic' => Crypt::encrypt($dataRequest['hp_pic']),
            ];
            return $this->picPerusahaanLitpersRepository->inputDataPicPerusahaanLitpers($dataEncrypt);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataPicPerusahaanLitpers($dataRequest, $id)
    {
        try {
            $dataEncrypt = [
                'name' => $dataRequest['name'],
                'email' => $dataRequest['email'],
                'password' => $dataRequest['password'],
                'nama_perusahaan' => Crypt::encrypt($dataRequest['nama_perusahaan']),
                'nama_pic' => Crypt::encrypt($dataRequest['nama_pic']),
                'jabatan_pic' => Crypt::encrypt($dataRequest['jabatan_pic']),
                'hp_pic' => Crypt::encrypt($dataRequest['hp_pic']),
            ];
            return $this->picPerusahaanLitpersRepository->updateDataPicPerusahaanLitpers($dataEncrypt, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataPicPerusahaanLitpers($id)
    {
        try {
            return $this->picPerusahaanLitpersRepository->deleteDataPicPerusahaanLitpers($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
