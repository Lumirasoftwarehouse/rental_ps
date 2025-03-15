<?php

namespace App\Http\Controllers\Intelud;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Services\Intelud\LaporanKaintelService;

class LaporanKaintelController extends Controller
{
    private $laporanKaintelService;

    public function __construct(LaporanKaintelService $laporanKaintelService)
    {
        $this->laporanKaintelService = $laporanKaintelService;
    }

    public function listLaporanKaintel()
    {
        try {
            $dataLaporan = $this->laporanKaintelService->listLaporanKaintel();

            return response()->json([
                'id' => '1',
                'data' => $dataLaporan
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan saat mengambil data laporan' . $th
            ]);
        }
    }

    public function detailLaporanKaintel($id)
    {
        try {
            $dataLaporan = $this->laporanKaintelService->detailLaporanKaintel($id);

            return response()->json([
                'id' => '1',
                'data' => $dataLaporan
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan saat mengambil data laporan' . $th
            ]);
        }
    }

    public function createLaporanKaintel(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jenis_pelanggaran' => 'required',
                'pelanggaran_lainnya' => 'required',
                'pelapor' => 'required',
                'kontak_pelapor' => 'required',
                'tanggal_pelanggaran' => 'required',
                'lokasi_pelanggaran' => 'required',
                'informasi_lainnya' => 'required',
                'bukti_pelanggaran' => 'required',
                'jenis_pengajuan' => 'required',
                'id_pengajuan' => 'required',
            ]);

            $result = $this->laporanKaintelService->createLaporanKaintel($validateData);

            NotificationHelper::broadcastNotification('admin_intelud', 'Laporan Kaintel baru telah ditambahkan.');
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan saat membuat laporan' . $th
            ]);
        }
    }

    public function createLaporanKhususKaintel(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jenis_pelanggaran' => 'required',
                'pelanggaran_lainnya' => 'required',
                'pelapor' => 'required',
                'kontak_pelapor' => 'required',
                'tanggal_pelanggaran' => 'required',
                'lokasi_pelanggaran' => 'required',
                'informasi_lainnya' => 'required',
                'bukti_pelanggaran' => 'required',
            ]);

            $result = $this->laporanKaintelService->createLaporanKhususKaintel($validateData);

            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan saat membuat laporan' . $th
            ]);
        }
    }

    public function updateLaporanKaintel(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jenis_pelanggaran' => 'required',
                'pelanggaran_lainnya' => 'required',
                'pelapor' => 'required',
                'kontak_pelapor' => 'required',
                'tanggal_pelanggaran' => 'required',
                'lokasi_pelanggaran' => 'required',
                'informasi_lainnya' => 'required',
                'bukti_pelanggaran' => 'required',
                'jenis_pengajuan' => 'required',
                'id_pengajuan' => 'required',
            ]);

            $result = $this->laporanKaintelService->updateLaporanKaintel($validateData, $id);

            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan saat mengupdate laporan'
            ]);
        }
    }

    public function deleteLaporanKaintel($id)
    {
        try {
            $result = $this->laporanKaintelService->deleteLaporanKaintel($id);
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan saat menghapus laporan'
            ]);
        }
    }

    public function verifyLaporanKaintel(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'status' => 'required',
                'catatan' => 'nullable|string'
            ]);

            $result = $this->laporanKaintelService->verifyLaporanKaintel($validateData, $id);

            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan saat mengupdate laporan'
            ]);
        }
    }
}
