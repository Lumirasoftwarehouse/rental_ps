<?php

namespace App\Http\Controllers\Intelud;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Services\Intelud\NonFlightApprovalService;

class NonFlightApprovalController extends Controller
{
    private $nonFlightApprovalService;

    public function __construct(NonFlightApprovalService $nonFlightApprovalService)
    {
        $this->nonFlightApprovalService = $nonFlightApprovalService;
    }

    public function postPengajuan(Request $request) // Post pengajuan
    {
        $validatedData = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'operator' => 'required|string|max:100',
            'jenis' => 'required|string|max:50',
            'no_registrasi' => 'required',

            'tanggal_terbang' => 'required',
            'tanggal_mendarat' => 'required',
            'rute_penerbangan' => 'required|string|max:255',
            'lanud' => 'required|string|max:100',
            'pendaratan_niaga' => 'required',

            'nama_kapten_pilot' => 'required|string|max:255',
            'awak_pesawat_lain' => 'required',
            'penumpang_barang' => 'required',
            'jumlah_kursi' => 'required',
            'catatan' => 'nullable|string|max:500',

            'fk_pic_perusahaan_nfa_id' => 'required',
        ]);

        return $this->handleRequest(function () use ($validatedData) {
            $dataResponse = $this->nonFlightApprovalService->postPengajuan($validatedData);

            NotificationHelper::broadcastNotification('admin_intelud', 'Pengajuan Non Flight Approval baru telah ditambahkan.');
            return response()->json([
                'id' => $dataResponse['id'],
                'data' => $dataResponse['data'],
            ]);
        });
    }

    public function listPengajuanNfaByIdPic()
    {
        try {
            return $this->handleRequest(function () {
                $dataFa = $this->nonFlightApprovalService->listPengajuanNfaByIdPic();

                return response()->json([
                    'id' => '1',
                    'data' => $dataFa,
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil list pengajuan',
            ], 401);
        }
    }

    public function detailPengajuan($id)
    {
        try {
            return $this->handleRequest(function () use ($id) {
                $data = $this->nonFlightApprovalService->detailPengajuan($id);
                return response()->json([
                    'id' => '1',
                    'data' => $data,
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil detail pengajuan',
            ], 401);
        }
    }

    public function postDocument(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'sertifikat_operator' => 'required|file|mimes:pdf|max:2048',
                'sertifikat_kelaludaraan' => 'required|file|mimes:pdf|max:2048',
                'sertifikat_pendaftaran' => 'required|file|mimes:pdf|max:2048',
                'izin_usaha' => 'required|file|mimes:pdf|max:2048',
                'permohonan_lanud_khusus' => 'required|file|mimes:pdf|max:2048',
                'sc_spam' => 'required|file|mimes:pdf|max:2048',
                'lain_lain' => 'required|file|mimes:pdf|max:2048',
                'rapid_antigen' => 'required|file|mimes:pdf|max:2048',
                'pengajuan_nfa_id' => 'required',
            ]);
            $dataResponse = $this->nonFlightApprovalService->postFormEmpat($validatedData, $request->allFiles());

            return response()->json([
                'id' => $dataResponse['id'],
                'data' => $dataResponse['data'],
            ]);
            // return $this->handleRequest(function () use ($validatedData, $request) {

            // });
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'id' => '0',
                'data' => 'error :' . $th->getMessage(),
            ]);
        }
    }

    public function sendToPic(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_pengajuan' => 'required',
                'id_pic' => 'required',
            ]);
            return $this->handleRequest(function () use ($validatedData) {
                $data = $this->nonFlightApprovalService->sendToPic($validatedData);
                NotificationHelper::broadcastNotification('pic_intelud', 'Pengajuan Non Flight Approval baru telah dikirimkan kepada anda.', $validatedData['id_pic']);
                return response()->json([
                    'id' => $data['id'],
                    'data' => $data['data'],
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil detail pengajuan',
            ], 401);
        }
    }

    public function verifyForms(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'jenis_form' => 'required',
                'statusVerify' => 'required',
                'catatan_revisi' => 'required'
            ]);

            $response = $this->nonFlightApprovalService->verifyForms($validatedData, $id);

            return response()->json([
                'id' => $response['id'],
                'data' => $response['data']
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'data tidak valid!!',
            ]);
        }
    }

    private function handleRequest(callable $callback)
    {
        try {
            return $callback();
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'Request gagal: ' . $th->getMessage(),
            ], 400); // Menggunakan 400 Bad Request
        }
    }
}
