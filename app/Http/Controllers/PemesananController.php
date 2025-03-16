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

    public function createPemesanan(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal_sewa' => 'required',
            'durasi' => 'required|integer|min:1',
            'menu_id' => 'required|exists:menus,id',
        ]);

        $result = $this->globalPemesananService->createPemesanan($validatedData);

        return response()->json([
            'id' => $result['id'],
            'data' => $result['data']
        ], $result['statusCode']);
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
