<?php

namespace App\Http\Controllers\Litpers;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Services\Litpers\JadwalSurveiPenerbitanBaruService;
use Illuminate\Support\Facades\Storage;

class JadwalSurveiPenerbitanBaruController extends Controller
{
    private $jadwalSurveiPenerbitanBaruService;

    public function __construct(JadwalSurveiPenerbitanBaruService $jadwalSurveiPenerbitanBaruService)
    {
        $this->jadwalSurveiPenerbitanBaruService = $jadwalSurveiPenerbitanBaruService;
    }

    public function listDataJadwalSurveiPenerbitanBaru($id)
    {
        try {
            $result = $this->jadwalSurveiPenerbitanBaruService->listDataJadwalSurveiPenerbitanBaru($id);
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

    public function detailDataJadwalSurveiPenerbitanBaru($id)
    {
        try {

            $result = $this->jadwalSurveiPenerbitanBaruService->detailDataJadwalSurveiPenerbitanBaru($id);
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

    public function inputDataJadwalSurveiPenerbitanBaru(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nama_admin' => 'required|string|max:255',
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
                'alamat_survei' => 'required|string|max:500',
                'hp_admin' => 'required|string|max:255',
                'f_tiga_penerbitan_id' => 'required|exists:form_tiga_penerbitan_barus,id',
            ]);
            // $validateData['admin_litpers_id'] = '1';
            $validateData['admin_litpers_id'] = auth()->user()->id;

            $result = $this->jadwalSurveiPenerbitanBaruService->inputDataJadwalSurveiPenerbitanBaru($validateData);
            // NotificationHelper::broadcastNotification('mitra_litpers', 'Jadwal survei form penerbitan baru anda telah ditetapkan, silahkan dicek.');

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

    public function updateDataJadwalSurveiPenerbitanBaru(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'nama_admin' => 'required|string|max:255',
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date',
                'alamat_survei' => 'required|string|max:500',
                'hp_admin' => 'required|string|max:255',
            ]);

            $result = $this->jadwalSurveiPenerbitanBaruService->updateDataJadwalSurveiPenerbitanBaru($validateData, $id);
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

    public function deleteDataJadwalSurveiPenerbitanBaru($id)
    {
        try {
            $result = $this->jadwalSurveiPenerbitanBaruService->deleteDataJadwalSurveiPenerbitanBaru($id);
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
