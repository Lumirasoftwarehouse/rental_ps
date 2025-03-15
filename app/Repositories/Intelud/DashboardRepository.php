<?php

namespace App\Repositories\Intelud;

use App\Models\PengajuanFsc;
use App\Models\PengajuanNfa;
use App\Models\LaporanKaintel;
use App\Models\User;

class DashboardRepository
{
    private $pengajuanFscModel;
    private $pengajuanNfaModel;
    private $laporanKaintelModel;
    private $userModel;

    public function __construct(PengajuanFsc $pengajuanFscModel, PengajuanNfa $pengajuanNfaModel, LaporanKaintel $laporanKaintelModel, User $userModel)
    {
        $this->pengajuanFscModel = $pengajuanFscModel;
        $this->pengajuanNfaModel = $pengajuanNfaModel;
        $this->laporanKaintelModel = $laporanKaintelModel;
        $this->userModel = $userModel;
    }

    public function dashboardAdmin()
    {
        try {
            $totalPengajuanFsc = $this->pengajuanFscModel->count();
            $totalPengajuanNfa = $this->pengajuanNfaModel->count();
            $totalPengajuan = $totalPengajuanFsc + $totalPengajuanNfa;

            $pengajuanFscStatus1 = $this->pengajuanFscModel->where('status', '1')->count();
            $pengajuanNfaStatus1 = $this->pengajuanNfaModel->where('status', '1')->count();
            $totalPengajuanStatus1 = $pengajuanFscStatus1 + $pengajuanNfaStatus1;

            $totalLaporanKaintel = $this->laporanKaintelModel->count();

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    'total_pengajuan' => $totalPengajuan,
                    'total_pengajuan_disetujui' => $totalPengajuanStatus1,
                    'total_laporan_kaintel' => $totalLaporanKaintel,
                ],
                "message" => 'get data dashboard admin success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function dashboardPic()
    {
        try {
            $totalPengajuanFsc = $this->pengajuanFscModel->where('fk_pic_intelud_nfc_id', auth()->user()->id)->count();
            $totalPengajuanNfa = $this->pengajuanNfaModel->where('fk_pic_intelud_nfa_id', auth()->user()->id)->count();
            $totalPengajuan = $totalPengajuanFsc + $totalPengajuanNfa;

            $pengajuanFscStatus0 = $this->pengajuanFscModel->where('fk_pic_intelud_nfc_id', auth()->user()->id)->where('status', '0')->count();
            $pengajuanNfaStatus0 = $this->pengajuanNfaModel->where('fk_pic_intelud_nfa_id', auth()->user()->id)->where('status', '0')->count();
            $totalPengajuanStatus0 = $pengajuanFscStatus0 + $pengajuanNfaStatus0;

            $pengajuanFscStatus1 = $this->pengajuanFscModel->where('fk_pic_intelud_nfc_id', auth()->user()->id)->where('status', '1')->count();
            $pengajuanNfaStatus1 = $this->pengajuanNfaModel->where('fk_pic_intelud_nfa_id', auth()->user()->id)->where('status', '1')->count();
            $totalPengajuanStatus1 = $pengajuanFscStatus1 + $pengajuanNfaStatus1;

            $pengajuanFscStatus2 = $this->pengajuanFscModel->where('fk_pic_intelud_nfc_id', auth()->user()->id)->where('status', '2')->count();
            $pengajuanNfaStatus2 = $this->pengajuanNfaModel->where('fk_pic_intelud_nfa_id', auth()->user()->id)->where('status', '2')->count();
            $totalPengajuanStatus2 = $pengajuanFscStatus2 + $pengajuanNfaStatus2;

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    'total_pengajuan' => $totalPengajuan,
                    'total_pengajuan_proses' => $totalPengajuanStatus0,
                    'total_pengajuan_disetujui' => $totalPengajuanStatus1,
                    'total_pengajuan_ditolak' => $totalPengajuanStatus2,
                ],
                "message" => 'get data dashboard pic intelud success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function dashboardMitra()
    {
        try {
            $totalPengajuanFsc = $this->pengajuanFscModel->where('fk_pic_perusahaan_nfc_id', auth()->user()->id)->count();
            $totalPengajuanNfa = $this->pengajuanNfaModel->where('fk_pic_perusahaan_nfa_id', auth()->user()->id)->count();
            $totalPengajuan = $totalPengajuanFsc + $totalPengajuanNfa;

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    'total_pengajuan' => $totalPengajuan,
                    'total_pengajuan_fa' => $totalPengajuanFsc,
                    'total_pengajuan_nfa' => $totalPengajuanNfa,
                ],
                "message" => 'get data dashboard pic intelud success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function dashboardKaintel()
    {
        try {
            $totalPengajuanFsc = $this->pengajuanFscModel->count();
            $totalPengajuanNfa = $this->pengajuanNfaModel->count();
            $totalPengajuan = $totalPengajuanFsc + $totalPengajuanNfa;

            $totalPengajuanFscHarian = $this->pengajuanFscModel->whereDate('created_at', now())->count();
            $totalPengajuanNfaHarian = $this->pengajuanNfaModel->whereDate('created_at', now())->count();
            $totalPengajuanHarian = $totalPengajuanFscHarian + $totalPengajuanNfaHarian;

            $totalLaporanKaintel = $this->laporanKaintelModel->count();

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    'total_pengajuan_harian' => $totalPengajuanHarian,
                    'total_pengajuan' => $totalPengajuan,
                    'total_pengajuan_fa' => $totalPengajuanFsc,
                    'total_pengajuan_nfa' => $totalPengajuanNfa,
                    'total_laporan_kaintel' => $totalLaporanKaintel,
                ],
                "message" => 'get data dashboard pic intelud success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function cekLevelUser()
    {
        return User::where('id', auth()->user()->id)->value('level');
    }
}
