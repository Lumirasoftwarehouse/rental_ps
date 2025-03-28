<?php

namespace App\Services;

use App\Repositories\PemesananRepository;
use App\Repositories\MenuRepository;
USE Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class PemesananService
{
    private $globalPemesananRepository;
    private $globalMenuRepository;

    public function __construct(PemesananRepository $pemesananRepository, MenuRepository $menuRepository)
    {
        $this->globalPemesananRepository = $pemesananRepository;
        $this->globalMenuRepository = $menuRepository;
    }

    public function listPemesanan()
    {
        return $this->globalPemesananRepository->listPemesanan();
    }

    public function createPemesanan($dataRequest)
    {
        try {
            $dataRequest['user_id'] = auth()->user()->id;
            $dataMenu = $this->globalMenuRepository->cekMenu($dataRequest['menu_id']);
    
            $hargaPerDurasi = $dataMenu->jenis === 'PS 5' ? 40000 : ($dataMenu->jenis === 'PS 4' ? 30000 : null);
    
            if (!$hargaPerDurasi) {
                return [
                    'id' => '0',
                    'data' => 'Menu tidak tersedia',
                    'statusCode' => 400
                ];
            }
    
            $tanggalMulai = \Carbon\Carbon::parse($dataRequest['tanggal_sewa']);
            $durasi = $dataRequest['durasi'];
    
            $totalHarga = 0;
            $detailItems = [];
    
            // Loop untuk menghitung harga per hari
            for ($i = 0; $i < $durasi; $i++) {
                $tanggalSewa = $tanggalMulai->copy()->addDays($i);
                $isWeekend = $tanggalSewa->isWeekend();
    
                $hargaHarian = $isWeekend ? ($hargaPerDurasi + 50000) : $hargaPerDurasi;
                $totalHarga += $hargaHarian;
    
                $detailItems[] = [
                    'price' => $hargaHarian,
                    'quantity' => 1,
                    'name' => $isWeekend ? 'Harga Weekend' : 'Harga Normal',
                    'date' => $tanggalSewa->format('Y-m-d')
                ];
            }
    
            $orderId = Str::uuid();
    
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalHarga,
                ],
                'item_details' => $detailItems,
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email
                ],
                'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'bri_va']
            ];
    
            $response = $this->sendToMidtrans($params);
    
            $dataPayment = [
                'order_id' => $orderId,
                'status' => 'pending',
                'customer_first_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'price' => $totalHarga,
                'item_name' => $dataMenu->jenis,
                'checkout_link' => $response->redirect_url ?? null,
                'pemesanan_id' => null
            ];
    
            return $this->globalPemesananRepository->createPemesanan($dataRequest, $dataPayment, $response);
    
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => $th->getMessage(),
                'statusCode' => 400
            ];
        }
    }
    
    // public function createPemesanan($dataRequest)
    // {
    //     try {
    //         $dataRequest['user_id'] = auth()->user()->id;
    //         $dataMenu = $this->globalMenuRepository->cekMenu($dataRequest['menu_id']);
    
    //         $hargaPerDurasi = $dataMenu->jenis === 'PS 5' ? 40000 : ($dataMenu->jenis === 'PS 4' ? 30000 : null);
    
    //         if (!$hargaPerDurasi) {
    //             return [
    //                 'id' => '0',
    //                 'data' => 'Menu tidak tersedia',
    //                 'statusCode' => 400
    //             ];
    //         }
    
    //         // Cek apakah pemesanan dilakukan pada weekend
    //         $tanggalSewa = \Carbon\Carbon::parse($dataRequest['tanggal_sewa']);
    //         $isWeekend = $tanggalSewa->isWeekend();
    
    //         // Tambahkan biaya tambahan jika weekend
    //         $extraCharge = $isWeekend ? 50000 : 0;
    
    //         $orderId = Str::uuid();
    
    //         $totalAmount = ($hargaPerDurasi * $dataRequest['durasi']) + $extraCharge;
    
    //         $params = [
    //             'transaction_details' => [
    //                 'order_id' => $orderId,
    //                 'gross_amount' => $totalAmount,
    //             ],
    //             'item_details' => [
    //                 [
    //                     'price' => $hargaPerDurasi,
    //                     'quantity' => $dataRequest['durasi'],
    //                     'name' => $dataMenu->jenis
    //                 ],
    //                 [
    //                     'price' => $extraCharge,
    //                     'quantity' => 1,
    //                     'name' => 'Weekend Charge'
    //                 ]
    //             ],
    //             'customer_details' => [
    //                 'first_name' => auth()->user()->name,
    //                 'email' => auth()->user()->email
    //             ],
    //             'enabled_payments' => ['credit_card', 'bca_va', 'bni_va', 'bri_va']
    //         ];
    
    //         $response = $this->sendToMidtrans($params);
    
    //         $dataPayment = [
    //             'order_id' => $orderId,
    //             'status' => 'pending',
    //             'customer_first_name' => auth()->user()->name,
    //             'customer_email' => auth()->user()->email,
    //             'price' => $totalAmount,
    //             'item_name' => $dataMenu->jenis,
    //             'checkout_link' => $response->redirect_url ?? null,
    //             'pemesanan_id' => null
    //         ];
    
    //         return $this->globalPemesananRepository->createPemesanan($dataRequest, $dataPayment, $response);
    
    //     } catch (\Throwable $th) {
    //         return [
    //             'id' => '0',
    //             'data' => $th->getMessage(),
    //             'statusCode' => 400
    //         ];
    //     }
    // }

    private function sendToMidtrans($params)
    {
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY') . ':');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Basic $auth",
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

        return json_decode($response->body());
    }


    public function updatePemesanan($dataRequest)
    {
        return $this->globalPemesananRepository->updatePemesanan($dataRequest);
    }

    public function deletePemesanan($id)
    {
        return $this->globalPemesananRepository->deletePemesanan($id);
    }

}