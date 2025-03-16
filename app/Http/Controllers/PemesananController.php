<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PemesananService;

class PemesananController extends Controller
{
    private $gobalPemesananService;

    public function __construct(PemesananService $pemesananService)
    {
        $this->globalPemesananService = $pemesananService;
    }

    public function listPemesanan()
    {
        try {
            $result = $this->globalPemesananService->listPemesanan();
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ], $result['statusCode']);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => $th->getMessage()
            ], 500);
        }
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

            // Cek apakah pemesanan dilakukan pada weekend
            $tanggalSewa = \Carbon\Carbon::parse($dataRequest['tanggal_sewa']);
            $isWeekend = $tanggalSewa->isWeekend();

            // Tambahkan biaya tambahan jika weekend
            $extraCharge = $isWeekend ? 50000 : 0;

            $orderId = Str::uuid();

            $totalAmount = ($hargaPerDurasi * $dataRequest['durasi']) + $extraCharge;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalAmount,
                ],
                'item_details' => [
                    [
                        'price' => $hargaPerDurasi,
                        'quantity' => $dataRequest['durasi'],
                        'name' => $dataMenu->jenis
                    ],
                    [
                        'price' => $extraCharge,
                        'quantity' => 1,
                        'name' => 'Weekend Charge'
                    ]
                ],
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
                'price' => $totalAmount,
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



    public function updatePemesanan(Request $request)
    {
        try {
            $dataValidate = $request->validate([
                'tanggal_sewa' => 'required',
                'durasi' => 'required',
                'menu_id' => 'required',
                'user_id' => 'required',
                'id' => 'required'
            ]);

            $result = $this->globalPemesananService->updatePemesanan($dataValidate);
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ], $result['statusCode']);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => $th->getMessage()
            ], 500);
        }
    }

    public function deletePemesanan($id)
    {
        try {
            $result = $this->globalPemesananService->deletePemesanan($id);
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ], $result['statusCode']);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => $th->getMessage()
            ], 500);
        }
    }
}
