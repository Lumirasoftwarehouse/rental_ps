<?php

namespace App\Http\Controllers\Pamsut;

use Illuminate\Http\Request;
use App\Services\Pamsut\JenisKasusService;
use Illuminate\Support\Facades\Storage;

class JenisKasusController extends Controller
{
    private $jenisKasusService;

    public function __construct(JenisKasusService $jenisKasusService)
    {
        $this->jenisKasusService = $jenisKasusService;
    }

    public function listDataJenisKasus()
    {
        try {
            $result = $this->jenisKasusService->listDataJenisKasus();
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

    public function detailDataJenisKasus($id)
    {
        try {

            $result = $this->jenisKasusService->detailDataJenisKasus($id);
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

    public function inputDataJenisKasus(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jenis_kasus' => 'required|string|max:255',
            ]);

            $result = $this->jenisKasusService->inputDataJenisKasus($validateData);
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

    public function updateDataJenisKasus(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jenis_kasus' => 'required|string|max:255',
            ]);

            $result = $this->jenisKasusService->updateDataJenisKasus($validateData, $id);
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

    public function deleteDataJenisKasus($id)
    {
        try {
            $result = $this->jenisKasusService->deleteDataJenisKasus($id);
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
