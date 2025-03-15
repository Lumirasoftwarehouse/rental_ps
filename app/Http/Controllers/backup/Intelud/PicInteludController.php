<?php

namespace App\Http\Controllers\Intelud;

use Illuminate\Http\Request;
use App\Services\Intelud\PicInteludService;
use Illuminate\Support\Facades\Storage;

class PicInteludController extends Controller
{
    private $picInteludService;

    public function __construct(PicInteludService $picInteludService)
    {
        $this->picInteludService = $picInteludService;
    }

    public function jumlahPengajuanByPic()
    {
        try {
            $result = $this->picInteludService->jumlahPengajuanByPic();
            return response()->json(
                [
                    'id' => $result['id'],
                    'data' => $result['data']
                ],
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'id' => '0',
                    'data' => $th->getMessage(),
                ],
                401
            );
        }
    }

    public function listDataPicIntelud()
    {
        try {
            $result = $this->picInteludService->listDataPicIntelud();
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

    public function detailDataPicIntelud($id)
    {
        try {

            $result = $this->picInteludService->detailDataPicIntelud($id);
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

    public function inputDataPicIntelud(Request $request)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'nama_pic' => 'required|string|max:255',
                'jabatan_pic' => 'required|string|max:255',
                'hp_pic' => 'required|string|max:255',
            ]);

            $result = $this->picInteludService->inputDataPicIntelud($validateData);
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

    public function updateDataPicIntelud(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'nama_pic' => 'required|string|max:255',
                'jabatan_pic' => 'required|string|max:255',
                'hp_pic' => 'required|string|max:255',
            ]);

            $result = $this->picInteludService->updateDataPicIntelud($validateData, $id);
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

    public function deleteDataPicIntelud($id)
    {
        try {
            $result = $this->picInteludService->deleteDataPicIntelud($id);
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
