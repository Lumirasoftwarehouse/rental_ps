<?php

namespace App\Http\Controllers\Pamsut;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Services\Pamsut\CatpersService;
use Illuminate\Support\Facades\Storage;

class CatpersController extends Controller
{
    private $catpersService;

    public function __construct(CatpersService $catpersService)
    {
        $this->catpersService = $catpersService;
    }

    public function exportDataCatpers(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nrp_personil' => 'required',
            ]);
            $result = $this->catpersService->exportDataCatpers($validateData);
            return $result;
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

    public function exportDataCatpersByExcel(Request $request)
    {
        try {
            $validateData = $request->validate([
                'file' => 'required|file|mimes:xlsx,xlsm,xlsb,xltx',
            ]);
            $result = $this->catpersService->exportDataCatpersByExcel($validateData);
            return $result;
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

    public function listDataCatpersByUser($id)
    {
        try {
            $result = $this->catpersService->listDataCatpersByUser($id);
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

    public function listDataCatpers()
    {
        try {
            $result = $this->catpersService->listDataCatpers();
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

    public function detailDataCatpers($id)
    {
        try {

            $result = $this->catpersService->detailDataCatpers($id);
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

    public function inputDataCatpers(Request $request)
    {
        // Validate input data
        $validateData = $request->validate([
            'foto_personil' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'nama_personil' => 'required|string|max:255',
            'nrp_personil' => 'required|string|max:255|unique:catpers',
            'jabatan_personil' => 'required|string|max:255',
            'kronologi_singkat' => 'required|string|max:500',
            'tanggal' => 'required|date',
            'alasan_kejadian' => 'required|string|max:500',
            'lokasi_kejadian' => 'required|string|max:255',
            'cara_kejadian' => 'required|string|max:500',
            'sanksi_hukum' => 'nullable|string|max:255',
            'foto_kejadian.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Multiple images
            'jenis_kasus_id' => 'required|array', // Multiple jenis kasus
            'jenis_kasus_id.*' => 'exists:jenis_kasuses,id',
        ]);
        // $validateData['admin_lanud_id'] = '1';
        $validateData['admin_lanud_id'] = auth()->user()->id;

        try {
            // Call the service method to process the data
            $result = $this->catpersService->inputDataCatpers($validateData);

            NotificationHelper::broadcastNotification('admin_pamsut', 'Data catpers baru dengan NRP ' . $result['data']['nrp_personil'] . ' telah ditambahkan.');

            return response()->json([
                'message' => $result['message'],
                'data' => $result
            ], $result['statusCode']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateDataCatpers(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'foto_personil' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'nama_personil' => 'required|string|max:255',
                'nrp_personil' => 'required|string|max:255|unique:catpers,nrp_personil,' . $id,
                'jabatan_personil' => 'required|string|max:255',
                'kronologi_singkat' => 'required|string|max:500',
                'tanggal' => 'required|date',
                'alasan_kejadian' => 'required|string|max:500',
                'lokasi_kejadian' => 'required|string|max:255',
                'cara_kejadian' => 'required|string|max:500',
                'sanksi_hukum' => 'nullable|string|max:255',
                'foto_kejadian.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Multiple images
                'jenis_kasus_id' => 'required|array', // Multiple jenis kasus
                'jenis_kasus_id.*' => 'exists:jenis_kasuses,id',
            ]);

            $result = $this->catpersService->updateDataCatpers($validateData, $id);
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

    public function deleteDataCatpers($id)
    {
        try {
            $result = $this->catpersService->deleteDataCatpers($id);
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
