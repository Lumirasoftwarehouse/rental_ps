<?php

namespace App\Http\Controllers\Litpers;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Services\Litpers\JadwalSurveiPergantianDireksiService;
use Illuminate\Support\Facades\Storage;

class JadwalSurveiPergantianDireksiController extends Controller
{
    private $jadwalSurveiPergantianDireksiService;

    public function __construct(JadwalSurveiPergantianDireksiService $jadwalSurveiPergantianDireksiService)
    {
        $this->jadwalSurveiPergantianDireksiService = $jadwalSurveiPergantianDireksiService;
    }

    public function listDataJadwalSurveiPergantianDireksi($id)
    {
        try {
            $result = $this->jadwalSurveiPergantianDireksiService->listDataJadwalSurveiPergantianDireksi($id);
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

    public function detailDataJadwalSurveiPergantianDireksi($id)
    {
        try {

            $result = $this->jadwalSurveiPergantianDireksiService->detailDataJadwalSurveiPergantianDireksi($id);
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

    public function inputDataJadwalSurveiPergantianDireksi(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nama_admin' => 'required|string|max:255',
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
                'alamat_survei' => 'required|string|max:500',
                'hp_admin' => 'required|string|max:255',
                'f_tiga_pergantian_id' => 'required|exists:form_tiga_pergantian_direksis,id',
            ]);
            // $validateData['admin_litpers_id'] = '1';
            $validateData['admin_litpers_id'] = auth()->user()->id;

            $result = $this->jadwalSurveiPergantianDireksiService->inputDataJadwalSurveiPergantianDireksi($validateData);
            // NotificationHelper::broadcastNotification('mitra_litpers', 'Jadwal survei form pergantian direksi anda telah ditetapkan, silahkan dicek.');

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

    public function updateDataJadwalSurveiPergantianDireksi(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'nama_admin' => 'required|string|max:255',
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
                'alamat_survei' => 'required|string|max:500',
                'hp_admin' => 'required|string|max:255',
            ]);

            $result = $this->jadwalSurveiPergantianDireksiService->updateDataJadwalSurveiPergantianDireksi($validateData, $id);
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

    public function deleteDataJadwalSurveiPergantianDireksi($id)
    {
        try {
            $result = $this->jadwalSurveiPergantianDireksiService->deleteDataJadwalSurveiPergantianDireksi($id);
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
