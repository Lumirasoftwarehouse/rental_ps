<?php

namespace App\Http\Controllers\Litpers;

use Illuminate\Http\Request;
use App\Services\Litpers\PicPerusahaanLitpersService;
use Illuminate\Support\Facades\Storage;

class PicPerusahaanLitpersController extends Controller
{
    private $picPerusahaanLitpersService;

    public function __construct(PicPerusahaanLitpersService $picPerusahaanLitpersService)
    {
        $this->picPerusahaanLitpersService = $picPerusahaanLitpersService;
    }

    public function listDataPicPerusahaanLitpers()
    {
        try {
            $result = $this->picPerusahaanLitpersService->listDataPicPerusahaanLitpers();
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

    public function detailDataPicPerusahaanLitpers($id)
    {
        try {

            $result = $this->picPerusahaanLitpersService->detailDataPicPerusahaanLitpers($id);
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

    public function inputDataPicPerusahaanLitpers(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nama_perusahaan' => 'required|string|max:255',
                'nama_pic' => 'required|string|max:255',
                'jabatan_pic' => 'required|string|max:255',
                'hp_pic' => 'required|string|max:255',
            ]);
            $validateData['user_id'] = '1';
            // $validateData['user_id'] = auth()->user()->id;

            $result = $this->picPerusahaanLitpersService->inputDataPicPerusahaanLitpers($validateData);
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

    public function updateDataPicPerusahaanLitpers(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'nama_perusahaan' => 'required|string|max:255',
                'nama_pic' => 'required|string|max:255',
                'jabatan_pic' => 'required|string|max:255',
                'hp_pic' => 'required|string|max:255',
            ]);

            $result = $this->picPerusahaanLitpersService->updateDataPicPerusahaanLitpers($validateData, $id);
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

    public function deleteDataPicPerusahaanLitpers($id)
    {
        try {
            $result = $this->picPerusahaanLitpersService->deleteDataPicPerusahaanLitpers($id);
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
