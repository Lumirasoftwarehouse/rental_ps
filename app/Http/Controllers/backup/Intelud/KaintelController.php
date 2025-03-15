<?php

namespace App\Http\Controllers\Intelud;

use Illuminate\Http\Request;
use App\Services\Intelud\KaintelService;
use Illuminate\Support\Facades\Storage;

class KaintelController extends Controller
{
    private $kaintelService;

    public function __construct(KaintelService $kaintelService)
    {
        $this->kaintelService = $kaintelService;
    }

    public function listDataKaintel()
    {
        try {
            $result = $this->kaintelService->listDataKaintel();
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

    public function detailDataKaintel($id)
    {
        try {

            $result = $this->kaintelService->detailDataKaintel($id);
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

    public function inputDataKaintel(Request $request)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'nama_kaintel' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'hp' => 'required|string|max:255',
                'lanud_id' => 'required|exists:lanuds,id',
            ]);

            $result = $this->kaintelService->inputDataKaintel($validateData);
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

    public function updateDataKaintel(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'nama_kaintel' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'hp' => 'required|string|max:255',
                'lanud_id' => 'required|exists:lanuds,id',
            ]);

            $result = $this->kaintelService->updateDataKaintel($validateData, $id);
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

    public function deleteDataKaintel($id)
    {
        try {
            $result = $this->kaintelService->deleteDataKaintel($id);
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
