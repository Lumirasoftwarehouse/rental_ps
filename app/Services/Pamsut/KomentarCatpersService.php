<?php

namespace App\Services\Pamsut;

use App\Repositories\Pamsut\KomentarCatpersRepository;
use Illuminate\Support\Facades\Crypt;

class KomentarCatpersService
{
    private $komentarCatpersRepository;

    public function __construct(KomentarCatpersRepository $komentarCatpersRepository)
    {
        $this->komentarCatpersRepository = $komentarCatpersRepository;
    }

    public function listDataKomentarCatpersByCatpers($id)
    {
        try {
            // Get the data from the repository
            $response = $this->komentarCatpersRepository->listDataKomentarCatpersByCatpers($id);

            // Check if the data is available and decrypt the necessary fields
            if (!empty($response['data'])) {
                foreach ($response['data'] as $key => $komentar) {
                    // Decrypt fields
                    $response['data'][$key]->isi_komentar = Crypt::decrypt($komentar->isi_komentar);
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

    public function detailDataKomentarCatpers($id)
    {
        try {
            // Fetch the detailed data from the repository
            $response = $this->komentarCatpersRepository->detailDataKomentarCatpers($id);

            // Check if the data exists and decrypt the necessary fields
            if (!empty($response['data'])) {
                $catpers = $response['data'];

                // Decrypt encrypted fields in the 'catpers' data
                $response['data']->isi_komentar = Crypt::decrypt($catpers->isi_komentar);
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

    public function inputDataKomentarCatpers($dataRequest)
    {
        try {
            $dataEncrypt = [
                'isi_komentar' => Crypt::encrypt($dataRequest['isi_komentar']),
                'catpers_id' => $dataRequest['catpers_id'],
                'user_id' => $dataRequest['user_id'],
            ];
            return $this->komentarCatpersRepository->inputDataKomentarCatpers($dataEncrypt);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataKomentarCatpers($dataRequest, $id)
    {
        try {
            $dataEncrypt = [
                'isi_komentar' => Crypt::encrypt($dataRequest['isi_komentar']),
            ];
            return $this->komentarCatpersRepository->updateDataKomentarCatpers($dataEncrypt, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataKomentarCatpers($id)
    {
        try {
            return $this->komentarCatpersRepository->deleteDataKomentarCatpers($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
