<?php

namespace App\Repositories\Intelud;

use App\Models\LaporanKaintel;

class LaporanKaintelRepository
{
    public function listLaporanKaintel()
    {
        $data = LaporanKaintel::get();
        return $data;
    }

    public function detailLaporanKaintel($id)
    {
        $laporan = LaporanKaintel::find($id);
        if ($laporan->jenis_pengajuan == '0') {
            $data = LaporanKaintel::join('pengajuan_fscs', 'laporan_kaintels.id_pengajuan', '=', 'pengajuan_fscs.id')
                ->where('laporan_kaintels.id', $id)
                ->select('laporan_kaintels.*', 'pengajuan_fscs.jenis', 'pengajuan_fscs.no_registrasi', 'pengajuan_fscs.rute_penerbangan', 'pengajuan_fscs.nama_kapten_pilot', 'pengajuan_fscs.awak_pesawat_lain')
                ->first();
        } else if ($laporan->jenis_pengajuan == '1') {
            $data = LaporanKaintel::join('pengajuan_nfas', 'laporan_kaintels.id_pengajuan', '=', 'pengajuan_nfas.id')
                ->where('laporan_kaintels.id', $id)
                ->select('laporan_kaintels.*', 'pengajuan_nfas.jenis', 'pengajuan_nfas.no_registrasi', 'pengajuan_nfas.rute_penerbangan', 'pengajuan_nfas.nama_kapten_pilot', 'pengajuan_nfas.awak_pesawat_lain')
                ->first();
        } else {
            $data = [];
        }
        return $data;
    }

    public function createLaporanKaintel($dataRequest)
    {
        try {
            LaporanKaintel::create($dataRequest);
            return [
                'id' => '1',
                'data' => 'berhasil menambahkan laporan kaintel'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'gagal menambahkan laporan kaintel: ' . $th
            ];
        }
    }

    public function createLaporanKhususKaintel($dataRequest)
    {
        try {
            LaporanKaintel::create($dataRequest);
            return [
                'id' => '1',
                'data' => 'berhasil menambahkan laporan kaintel'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'gagal menambahkan laporan kaintel: ' . $th
            ];
        }
    }

    public function updateLaporanKaintel($dataRequest, $id)
    {
        try {
            LaporanKaintel::where('id', $id)->update($dataRequest);
            return [
                'id' => '1',
                'data' => 'berhasil memperbarui laporan kaintel'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'gagal memperbarui laporan kaintel: ' . $th
            ];
        }
    }

    public function deleteLaporanKaintel($id)
    {
        try {
            $data = LaporanKaintel::find($id);
            if ($data) {
                $data->delete();
                return [
                    'id' => '1',
                    'data' => 'berhasil menghapus laporan kaintel'
                ];
            }
            return [
                'id' => '0',
                'data' => 'laporan kaintel tidak ditemukan'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'gagal menghapus laporan kaintel: '
            ];
        }
    }

    public function verifyLaporanKaintel($dataRequest, $id)
    {
        try {
            LaporanKaintel::where('id', $id)->update($dataRequest);
            return [
                'id' => '1',
                'data' => 'berhasil memverifikasi laporan kaintel'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'gagal memverifikasi laporan kaintel: ' . $th
            ];
        }
    }
}
