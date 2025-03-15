<?php

namespace App\Http\Controllers\Intelud;

use Illuminate\Http\Request;
use App\Services\Intelud\LanudService;
use Illuminate\Support\Facades\Storage;

class LanudController extends Controller
{
    private $lanudService;

    public function __construct(LanudService $lanudService)
    {
        $this->lanudService = $lanudService;
    }

    public function listDataLanud()
    {
        try {
            $result = $this->lanudService->listDataLanud();
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

    public function detailDataLanud($id)
    {
        try {

            $result = $this->lanudService->detailDataLanud($id);
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

    public function inputDataLanud(Request $request)
    {
        try {
            $validateData = $request->validate([
                'nama_lanud' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
            ]);

            $result = $this->lanudService->inputDataLanud($validateData);
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

    public function updateDataLanud(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'nama_lanud' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
            ]);

            $result = $this->lanudService->updateDataLanud($validateData, $id);
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

    public function deleteDataLanud($id)
    {
        try {
            $result = $this->lanudService->deleteDataLanud($id);
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
