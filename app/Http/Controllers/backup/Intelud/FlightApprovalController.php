<?php

namespace App\Http\Controllers\Intelud;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Services\Intelud\FlightApprovalService;
use App\Services\Intelud\NonFlightApprovalService;


class FlightApprovalController extends Controller
{
    private $flightApprovalService;
    private $nonFlightApprovalService;

    public function __construct(FlightApprovalService $flightApprovalService, NonFlightApprovalService $nonFlightApprovalService)
    {
        $this->flightApprovalService = $flightApprovalService;
        $this->nonFlightApprovalService = $nonFlightApprovalService;
    }

    // ngecek list pengajuan yang belum selesai
    // trigernya ambil semua form dan cari apakah ada form yang tidak lengkap?

    public function listPengajuanApprovated()
    {
        try {
            return $this->handleRequest(function () {
                $faApprovated = $this->flightApprovalService->listPengajuanApprovated();
                $nfaApprovated = $this->nonFlightApprovalService->listPengajuanApprovated();
                return response()->json([
                    'id' => '1',
                    'dataFa' =>  $faApprovated,
                    'dataNfa' => $nfaApprovated
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil list pengajuan',
            ], 401);
        }
    }

    public function listPengajuanRejected()
    {
        try {
            return $this->handleRequest(function () {
                $faRejected = $this->flightApprovalService->listPengajuanRejected();
                $nfaRejected = $this->nonFlightApprovalService->listPengajuanRejected();
                return response()->json([
                    'id' => '1',
                    'dataFa' =>  $faRejected,
                    'dataNfa' => $nfaRejected
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil list pengajuan',
            ], 401);
        }
    }

    public function listPengajuanByPicIntelud()
    {
        try {
            return $this->handleRequest(function () {
                $dataFa = $this->flightApprovalService->listPengajuanFaByPicIntelud();
                $dataNfa = $this->nonFlightApprovalService->listPengajuanNfaByPicIntelud();

                return response()->json([
                    'id' => '1',
                    'data' => [
                        'flight-approval' => $dataFa,
                        'non-flight-approval' => $dataNfa
                    ],
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil list pengajuan',
            ], 401);
        }
    }

    public function listPengajuanByIdPic()
    {
        try {
            return $this->handleRequest(function () {
                $dataFa = $this->flightApprovalService->listPengajuanFaByIdPic();
                $dataNfa = $this->nonFlightApprovalService->listPengajuanNfaByIdPic();

                return response()->json([
                    'id' => '1',
                    'data' => [
                        'flight-approval' => $dataFa,
                        'non-flight-approval' => $dataNfa
                    ],
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil list pengajuan',
            ], 401);
        }
    }

    public function listPengajuan()
    {
        try {
            return $this->handleRequest(function () {
                $dataFa = $this->flightApprovalService->listPengajuan();
                $dataNfa = $this->nonFlightApprovalService->listPengajuanNfa();
                return response()->json([
                    'id' => '1',
                    'data' => [
                        'flight-approval' => $dataFa,
                        'non-flight-approval' => $dataNfa
                    ],
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil list pengajuan',
            ], 401);
        }
    }

    public function listPengajuanWhereNoPic()
    {
        try {
            return $this->handleRequest(function () {
                $dataFa = $this->flightApprovalService->listPengajuanWhereNoPic();
                $dataNfa = $this->nonFlightApprovalService->listPengajuanNfaWhereNoPic();
                return response()->json([
                    'id' => '1',
                    'data' => [
                        'flight-approval' => $dataFa,
                        'non-flight-approval' => $dataNfa
                    ],
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
                $data = $this->flightApprovalService->detailPengajuan($id);
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

    public function detailDocumentPengajuan($id)
    {
        try {
            return $this->handleRequest(function () use ($id) {
                $data = $this->flightApprovalService->detailDocumentPengajuan($id);
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
            'pendaratan_teknik' => 'required',
            'pendaratan_niaga' => 'required',
            'nama_kapten_pilot' => 'required|string|max:255',
            'awak_pesawat_lain' => 'required',
            'penumpang_barang' => 'required',
            'jumlah_kursi' => 'required',
            'fa' => 'required|string|max:100',
            'catatan' => 'nullable|string|max:500',
            'fk_pic_perusahaan_nfc_id' => 'required',

        ]);

        NotificationHelper::broadcastNotification('admin_intelud', 'Pengajuan Flight Approval baru telah ditambahkan.');
        return $this->handleRequest(function () use ($validatedData) {
            $dataResponse = $this->flightApprovalService->postPengajuan($validatedData);

            return response()->json([
                'id' => $dataResponse['id'],
                'data' => $dataResponse['data'],
            ]);
        });
    }


    public function postDocument(Request $request)
    {
        $validatedData = $request->validate([
            'sertifikat_operator' => 'required|file|mimes:pdf|max:2048',
            'flight_approval' => 'required|file|mimes:pdf|max:2048',
            'sertifikat_kelaludaraan' => 'required|file|mimes:pdf|max:2048',
            'sertifikat_pendaftaran' => 'required|file|mimes:pdf|max:2048',
            'izin_usaha' => 'required|file|mimes:pdf|max:2048',
            'permohonan_lanud_khusus' => 'required|file|mimes:pdf|max:2048',
            'fc_crew' => 'required|file|mimes:pdf|max:2048',
            'sertifikat_vaksin' => 'required|file|mimes:pdf|max:2048',
            'rapid_antigen' => 'required|file|mimes:pdf|max:2048',
            'pengajuan_fsc_id' => 'required',
            // 'form_tiga_fsc_id' => 'required|exists:form_tiga_fscs,id',
        ]);

        return $this->handleRequest(function () use ($validatedData, $request) {
            $dataResponse = $this->flightApprovalService->postFormEmpat($validatedData, $request->allFiles());

            return response()->json([
                'id' => $dataResponse['id'],
                'data' => $dataResponse['data'],
            ]);
        });
        // return response()->json(['data' => 'test']);
    }

    public function sendToPic(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_pengajuan' => 'required',
                'id_pic' => 'required',
            ]);
            return $this->handleRequest(function () use ($validatedData) {
                $data = $this->flightApprovalService->sendToPic($validatedData);
                NotificationHelper::broadcastNotification('pic_intelud', 'Pengajuan Flight Approval baru telah dikirimkan kepada anda.', $validatedData['id_pic']);
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

            $response = $this->flightApprovalService->verifyForms($validatedData, $id);

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

    public function rejectPengajuan(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'jenis_pengajuan' => 'required',
            ]);
            return $this->handleRequest(function () use ($validatedData, $id) {
                $data = $this->flightApprovalService->rejectPengajuan($validatedData, $id);
                return response()->json([
                    'id' => $data['id'],
                    'data' => $data['data'],
                ], 200);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mereject pengajuan',
            ], 401);
        }
    }
}
