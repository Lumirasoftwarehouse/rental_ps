<?php

namespace App\Http\Controllers\Pamsut;

use Illuminate\Http\Request;
use App\Services\Pamsut\AdminLanudService;
use Illuminate\Support\Facades\Storage;

class AdminLanudController extends Controller
{
    private $adminLanudService;

    public function __construct(AdminLanudService $adminLanudService)
    {
        $this->adminLanudService = $adminLanudService;
    }

    public function listDataAdminLanud()
    {
        try {
            $result = $this->adminLanudService->listDataAdminLanud();
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

    public function detailDataAdminLanud($id)
    {
        try {

            $result = $this->adminLanudService->detailDataAdminLanud($id);
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

    public function inputDataAdminLanud(Request $request)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'nama_satuan' => 'required|string|max:255',
                'lokasi_satuan' => 'required|string|max:255',
                'nama_kepala_satuan' => 'required|string|max:255',
                'nama_admin' => 'required|string|max:255',
                'jabatan_admin' => 'required|string|max:255',
                'hp_admin' => 'required|string|max:255',
            ]);

            $result = $this->adminLanudService->inputDataAdminLanud($validateData);
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

    public function updateDataAdminLanud(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'nama_satuan' => 'required|string|max:255',
                'lokasi_satuan' => 'required|string|max:255',
                'nama_kepala_satuan' => 'required|string|max:255',
                'nama_admin' => 'required|string|max:255',
                'jabatan_admin' => 'required|string|max:255',
                'hp_admin' => 'required|string|max:255',
            ]);

            $result = $this->adminLanudService->updateDataAdminLanud($validateData, $id);
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

    public function deleteDataAdminLanud($id)
    {
        try {
            $result = $this->adminLanudService->deleteDataAdminLanud($id);
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
