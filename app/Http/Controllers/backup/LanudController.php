<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lanud;

class LanudController extends Controller
{
    public function listLanud()
    {
        $lanud = Lanud::all();
        return response()->json([
            'id' => '1',
            'data' => $lanud
        ]);
    }

    public function createLanud(Request $request)
    {
        try {
            $dataRequest = $request->validte([
                'nama_lanud' => 'required',
                'alamat' => 'required',
            ]);
            $lanud = Lanud::create(
                [
                    'nama_lanud' => $dataRequest['nama_lanud'],
                    'alamat' => $dataRequest['alamat'],
                ]
            );
            return response()->json([
                'id' => '1',
                'data' => 'Lanud berhasil dibuat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'Lanud gagal dibuat'
            ]);
        }
    }

    public function updateLanud(Request $request, $id)
    {
        try {
            $dataRequest = $request->validte([
                'nama_lanud' => 'required',
                'alamat' => 'required',
            ]);
            $lanud = Lanud::find($id);
            $lanud->update(
                [
                    'nama_lanud' => $dataRequest['nama_lanud'],
                    'alamat' => $dataRequest['alamat'],
                ]
            );
            return response()->json([
                'id' => '1',
                'data' => 'Lanud berhasil diupdate'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'Lanud gagal diupdate'
            ]);
        }
    }

    public function deleteLanud($id)
    {
        try {
            $lanud = Lanud::find($id);
            $lanud->delete();
            return response()->json([
                'id' => '1',
                'data' => 'Lanud berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'Lanud gagal dihapus'
            ]);
        }
    }
}
