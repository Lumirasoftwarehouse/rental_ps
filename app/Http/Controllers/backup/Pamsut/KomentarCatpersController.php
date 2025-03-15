<?php

namespace App\Http\Controllers\Pamsut;

use Illuminate\Http\Request;
use App\Services\Pamsut\KomentarCatpersService;

class KomentarCatpersController extends Controller
{
    private $komentarCatpersService;

    public function __construct(KomentarCatpersService $komentarCatpersService)
    {
        $this->komentarCatpersService = $komentarCatpersService;
    }

    public function listDataKomentarCatpersByCatpers($id)
    {
        try {
            $result = $this->komentarCatpersService->listDataKomentarCatpersByCatpers($id);
            return response()->json(
                [
                    "id" => $result['id'],
                    'message' => $result['message'],
                    'data' => $result['data']
                ],
                $result['statusCode']
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "id" => '0',
                    'message' => $th->getMessage(),
                    'data' => []
                ],
                401
            );
        }
    }

    public function detailDataKomentarCatpers($id)
    {
        try {
            $result = $this->komentarCatpersService->detailDataKomentarCatpers($id);
            return response()->json(
                [
                    "id" => $result['id'],
                    'message' => $result['message'],
                    'data' => $result['data']
                ],
                $result['statusCode']
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "id" => '0',
                    'message' => $th->getMessage(),
                    'data' => []
                ],
                401
            );
        }
    }

    public function inputDataKomentarCatpers(Request $request)
    {
        try {
            $validateData = $request->validate([
                'isi_komentar' => 'required|string|max:500',
                'catpers_id' => 'required|exists:catpers,id',
            ]);
            // $validateData['user_id'] = '1';
            $validateData['user_id'] = auth()->user()->id;

            $result = $this->komentarCatpersService->inputDataKomentarCatpers($validateData);
            return response()->json(
                [
                    "id" => $result['id'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    public function updateDataKomentarCatpers(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'isi_komentar' => 'required|string|max:500',
            ]);

            $result = $this->komentarCatpersService->updateDataKomentarCatpers($validateData, $id);
            return response()->json(
                [
                    "id" => $result['id'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    public function deleteDataKomentarCatpers($id)
    {
        try {
            $result = $this->komentarCatpersService->deleteDataKomentarCatpers($id);
            return response()->json(
                [
                    "id" => $result['id'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "id" => '0',
                    'message' => $th->getMessage()
                ],
                401
            );
        }
    }
}
