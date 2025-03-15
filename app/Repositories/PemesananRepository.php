<?php

namespace App\Repositories;

use App\Models\Pemesanan;
use App\Models\Payment;

class PemesananRepository
{
    public function listPemesanan()
    {
        try {
            $pemesananList = Pemesanan::with(['payment', 'menu'])->get();
            return [
                'id' => '1',
                'data' => $pemesananList,
                'statusCode' => 200
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => $th,
                'statusCode' => 401
            ];
        }
    }

    public function createPemesanan($dataRequest, $dataPayment, $midtransResponse)
    {
        try {
            $pemesanan = Pemesanan::create($dataRequest);
            $dataPayment['pemesanan_id'] = $pemesanan->id;

            Payment::create($dataPayment);

            return [
                'id' => '1',
                'data' => $midtransResponse,
                'statusCode' => 200
            ];
        } catch (\Exception $e) {
            return [
                'id' => '0',
                'data' => $e->getMessage(),
                'statusCode' => 500
            ];
        }
    }


    public function updatePemesanan($dataRequest)
    {
        try {
            $result = Pemesanan::where('id', $dataRequest['id'])->update($dataRequest);
            return [
                'id' => '1',
                'data' => $result,
                'statusCode' => 200
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => $th,
                'statusCode' => 401
            ];
        }
    }

    public function deletePemesanan($id)
    {
        try {
            $result = Pemesanan::where('id', $id)->delete();
            return [
                'id' => '1',
                'data' => $result,
                'statusCode' => 200
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => $th,
                'statusCode' => 401
            ];
        }
    }
}