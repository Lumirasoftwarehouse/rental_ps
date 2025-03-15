<?php

namespace App\Http\Controllers\Litpers;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Services\Litpers\JadwalSurveiPerpanjanganService;
use Illuminate\Support\Facades\Storage;

class JadwalSurveiPerpanjanganController extends Controller
{
    private $jadwalSurveiPerpanjanganService;

    public function __construct(JadwalSurveiPerpanjanganService $jadwalSurveiPerpanjanganService)
    {
        $this->jadwalSurveiPerpanjanganService = $jadwalSurveiPerpanjanganService;
    }

    public function listDataJadwalSurveiPerpanjangan($id)
    {
        try {
            $result = $this->jadwalSurveiPerpanjanganService->listDataJadwalSurveiPerpanjangan($id);
            return response()->json(
                [
                    'id' => $result['id'],
                    'message' => $result['message'],
                    'data' => $result['data']
                ],
                $result['statusCode']
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'id' => '0',
                    'message' => $th->getMessage(),
                    'data' => []
                ],
                401
            );
        }
    }

    public function detailDataJadwalSurveiPerpanjangan($id)
    {
        try {

            $result = $this->jadwalSurveiPerpanjanganService->detailDataJadwalSurveiPerpanjangan($id);
            return response()->json(
                [
                    'id' => $result['id'],
                    'message' => $result['message'],
                    'data' => $result['data']
                ],
                $result['statusCode']
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'id' => '0',
                    'message' => $th->getMessage(),
                    'data' => []
                ],
                401
            );
        }
    }

    public function inputDataJadwalSurveiPerpanjangan(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nama_admin' => 'required|string|max:255',
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
                'alamat_survei' => 'required|string|max:500',
                'hp_admin' => 'required|string|max:255',
                'f_dua_perpanjangan_id' => 'required|exists:form_dua_perpanjangans,id',
            ]);
            // $validateData['admin_litpers_id'] = '1';
            $validateData['admin_litpers_id'] = auth()->user()->id;

            $result = $this->jadwalSurveiPerpanjanganService->inputDataJadwalSurveiPerpanjangan($validateData);
            // NotificationHelper::broadcastNotification('mitra_litpers', 'Jadwal survei form perpanjangan anda telah ditetapkan, silahkan dicek.');

            return response()->json(
                [
                    'id' => $result['id'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    public function updateDataJadwalSurveiPerpanjangan(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'nama_admin' => 'required|string|max:255',
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
                'alamat_survei' => 'required|string|max:500',
                'hp_admin' => 'required|string|max:255',
            ]);

            $result = $this->jadwalSurveiPerpanjanganService->updateDataJadwalSurveiPerpanjangan($validateData, $id);
            return response()->json(
                [
                    'id' => $result['id'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    public function deleteDataJadwalSurveiPerpanjangan($id)
    {
        try {
            $result = $this->jadwalSurveiPerpanjanganService->deleteDataJadwalSurveiPerpanjangan($id);
            return response()->json(
                [
                    'id' => $result['id'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'id' => '0',
                    'message' => $th->getMessage()
                ],
                401
            );
        }
    }
}
