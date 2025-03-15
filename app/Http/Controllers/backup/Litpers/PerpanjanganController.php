<?php

namespace App\Http\Controllers\Litpers;

use App\Helpers\NotificationHelper;
use App\Models\FormDuaPerpanjangan;
use App\Models\FormSatuPerpanjangan;
use App\Models\FormTigaPerpanjangan;
use Illuminate\Http\Request;
use App\Services\Litpers\PerpanjanganService;
use Illuminate\Support\Facades\Storage;

class PerpanjanganController extends Controller
{
    private $perpanjanganService;

    public function __construct(PerpanjanganService $perpanjanganService)
    {
        $this->perpanjanganService = $perpanjanganService;
    }

    public function listDataAllFormPerpanjanganByMitra($id)
    {
        try {
            $result = $this->perpanjanganService->listDataAllFormPerpanjanganByMitra($id);
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

    public function listDataUnfinishedFormPerpanjanganByMitra($id)
    {
        try {
            $result = $this->perpanjanganService->listDataUnfinishedFormPerpanjanganByMitra($id);
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

    public function listDataUnvalidatedFormPerpanjangan()
    {
        try {
            $result = $this->perpanjanganService->listDataUnvalidatedFormPerpanjangan();
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

    // Form Satu

    public function listDataFormSatuPerpanjangan()
    {
        try {
            $result = $this->perpanjanganService->listDataFormSatuPerpanjangan();
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

    public function listDataFormSatuPerpanjanganByAdmin($id)
    {
        try {
            $result = $this->perpanjanganService->listDataFormSatuPerpanjanganByAdmin($id);
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

    public function listDataFormSatuPerpanjanganByMitra($id)
    {
        try {
            $result = $this->perpanjanganService->listDataFormSatuPerpanjanganByMitra($id);
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

    public function detailDataFormSatuPerpanjangan($id)
    {
        try {

            $result = $this->perpanjanganService->detailDataFormSatuPerpanjangan($id);
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

    public function inputDataFormSatuPerpanjangan(Request $request)
    {
        try {
            $validateData = $request->validate([
                'surat_disadaau_diskonsau' => 'required|file|mimes:pdf|max:2048',
                'skhpp_lama' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
                'jenis_skhpp_id' => 'required|exists:jenis_skhpps,id'
            ]);
            // $validateData['pic_perusahaan_litpers_id'] = '8';
            $validateData['pic_perusahaan_litpers_id'] = auth()->user()->id;

            $result = $this->perpanjanganService->inputDataFormSatuPerpanjangan($validateData, $request->allFiles());
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form satu perpanjangan telah ditambahkan.');

            return response()->json(
                [
                    'id' => $result['id'],
                    'data' => $result['data'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    public function updateDataFormSatuPerpanjangan(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_disadaau_diskonsau' => 'required|file|mimes:pdf|max:2048',
                'skhpp_lama' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
                'jenis_skhpp_id' => 'required|exists:jenis_skhpps,id'
            ]);

            $result = $this->perpanjanganService->updateDataFormSatuPerpanjangan($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormSatuPerpanjangan($id)
    {
        try {
            $result = $this->perpanjanganService->deleteDataFormSatuPerpanjangan($id);
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

    // Form Dua

    public function listDataFormDuaPerpanjangan()
    {
        try {
            $result = $this->perpanjanganService->listDataFormDuaPerpanjangan();
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

    public function listDataFormDuaPerpanjanganByFormSatu($id)
    {
        try {
            $result = $this->perpanjanganService->listDataFormDuaPerpanjanganByFormSatu($id);
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

    public function detailDataFormDuaPerpanjangan($id)
    {
        try {

            $result = $this->perpanjanganService->detailDataFormDuaPerpanjangan($id);
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

    public function inputDataFormDuaPerpanjanganPartSatu(Request $request)
    {
        try {
            $validateData = $request->validate([
                'surat_permohonan_penerbitan' => 'required|file|mimes:pdf|max:2048',
                'akte_pendirian_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'akte_perubahan_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'nomor_izin_berusaha' => 'required|file|mimes:pdf|max:2048',
                'nomor_pokok_wajib_pajak' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
                'form_satu_perpanjangan_id' => 'required|exists:form_satu_perpanjangans,id'
            ]);

            $result = $this->perpanjanganService->inputDataFormDuaPerpanjanganPartSatu($validateData, $request->allFiles());
            return response()->json(
                [
                    'id' => $result['id'],
                    'data' => $result['data'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    public function inputDataFormDuaPerpanjanganPartDua(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_pengukuhan_pengusaha_kena_pajak' => 'required|file|mimes:pdf|max:2048',
                'surat_pernyataan_sehat' => 'required|file|mimes:pdf|max:2048',
                'referensi_bank' => 'required|file|mimes:pdf|max:2048',
                'neraca_perusahaan_terakhir' => 'required|file|mimes:pdf|max:2048',
                'rekening_koran_perusahaan' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->inputDataFormDuaPerpanjanganPartDua($validateData, $request->allFiles(), $id);
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

    public function inputDataFormDuaPerpanjanganPartTiga(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'cv_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'ktp_jajaran_direksi' => 'required|file|mimes:pdf|max:2048',
                'skck' => 'required|file|mimes:pdf|max:2048',
                'company_profile' => 'required|file|mimes:pdf|max:2048',
                'daftar_pengalaman_pekerjaan_di_tni_au' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->inputDataFormDuaPerpanjanganPartTiga($validateData, $request->allFiles(), $id);
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

    public function inputDataFormDuaPerpanjanganPartEmpat(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'daftar_peralatan_fasilitas_kantor' => 'required|file|mimes:pdf|max:2048',
                'aset_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'surat_kemampuan_principle_agent' => 'required|file|mimes:pdf|max:2048',
                'surat_loa_poa' => 'required|file|mimes:pdf|max:2048',
                'supporting_letter_dari_vendor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->inputDataFormDuaPerpanjanganPartEmpat($validateData, $request->allFiles(), $id);
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

    public function inputDataFormDuaPerpanjanganPartLima(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'foto_direktur_4_6' => 'required|file|mimes:pdf|max:2048',
                'kepemilikan_kantor' => 'required|file|mimes:pdf|max:2048',
                'struktur_organisasi' => 'required|file|mimes:pdf|max:2048',
                'foto_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'gambar_rute_denah_kantor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->inputDataFormDuaPerpanjanganPartLima($validateData, $request->allFiles(), $id);
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

    public function inputDataFormDuaPerpanjanganPartEnam(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'kk_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'beranda_lpse' => 'required|file|mimes:pdf|max:2048',
                'e_catalog' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->inputDataFormDuaPerpanjanganPartEnam($validateData, $request->allFiles(), $id);
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form dua perpanjangan telah ditambahkan.');

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

    public function updateDataFormDuaPerpanjanganPartSatu(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_permohonan_penerbitan' => 'required|file|mimes:pdf|max:2048',
                'akte_pendirian_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'akte_perubahan_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'nomor_izin_berusaha' => 'required|file|mimes:pdf|max:2048',
                'nomor_pokok_wajib_pajak' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->perpanjanganService->updateDataFormDuaPerpanjanganPartSatu($validateData, $request->allFiles(), $id);
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

    public function updateDataFormDuaPerpanjanganPartDua(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_pengukuhan_pengusaha_kena_pajak' => 'required|file|mimes:pdf|max:2048',
                'surat_pernyataan_sehat' => 'required|file|mimes:pdf|max:2048',
                'referensi_bank' => 'required|file|mimes:pdf|max:2048',
                'neraca_perusahaan_terakhir' => 'required|file|mimes:pdf|max:2048',
                'rekening_koran_perusahaan' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->updateDataFormDuaPerpanjanganPartDua($validateData, $request->allFiles(), $id);
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

    public function updateDataFormDuaPerpanjanganPartTiga(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'cv_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'ktp_jajaran_direksi' => 'required|file|mimes:pdf|max:2048',
                'skck' => 'required|file|mimes:pdf|max:2048',
                'company_profile' => 'required|file|mimes:pdf|max:2048',
                'daftar_pengalaman_pekerjaan_di_tni_au' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->updateDataFormDuaPerpanjanganPartTiga($validateData, $request->allFiles(), $id);
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

    public function updateDataFormDuaPerpanjanganPartEmpat(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'daftar_peralatan_fasilitas_kantor' => 'required|file|mimes:pdf|max:2048',
                'aset_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'surat_kemampuan_principle_agent' => 'required|file|mimes:pdf|max:2048',
                'surat_loa_poa' => 'required|file|mimes:pdf|max:2048',
                'supporting_letter_dari_vendor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->updateDataFormDuaPerpanjanganPartEmpat($validateData, $request->allFiles(), $id);
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

    public function updateDataFormDuaPerpanjanganPartLima(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'foto_direktur_4_6' => 'required|file|mimes:pdf|max:2048',
                'kepemilikan_kantor' => 'required|file|mimes:pdf|max:2048',
                'struktur_organisasi' => 'required|file|mimes:pdf|max:2048',
                'foto_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'gambar_rute_denah_kantor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->updateDataFormDuaPerpanjanganPartLima($validateData, $request->allFiles(), $id);
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

    public function updateDataFormDuaPerpanjanganPartEnam(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'kk_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'beranda_lpse' => 'required|file|mimes:pdf|max:2048',
                'e_catalog' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->perpanjanganService->updateDataFormDuaPerpanjanganPartEnam($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormDuaPerpanjangan($id)
    {
        try {
            $result = $this->perpanjanganService->deleteDataFormDuaPerpanjangan($id);
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

    // Form Tiga

    public function listDataFormTigaPerpanjangan()
    {
        try {
            $result = $this->perpanjanganService->listDataFormTigaPerpanjangan();
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

    public function listDataFormTigaPerpanjanganByFormDua($id)
    {
        try {
            $result = $this->perpanjanganService->listDataFormTigaPerpanjanganByFormDua($id);
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

    public function detailDataFormTigaPerpanjangan($id)
    {
        try {

            $result = $this->perpanjanganService->detailDataFormTigaPerpanjangan($id);
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

    public function inputDataFormTigaPerpanjangan(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jadwal_survei' => 'required|date',
                'status' => 'required|in:0,1,2',
                'form_dua_perpanjangan_id' => 'required|exists:form_dua_perpanjangans,id'
            ]);

            $result = $this->perpanjanganService->inputDataFormTigaPerpanjangan($validateData);
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form tiga perpanjangan telah ditambahkan.');
            return response()->json(
                [
                    'id' => $result['id'],
                    'data' => $result['data'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    public function updateDataFormTigaPerpanjangan(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jadwal_survei' => 'required|date',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->perpanjanganService->updateDataFormTigaPerpanjangan($validateData, $id);
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

    public function deleteDataFormTigaPerpanjangan($id)
    {
        try {
            $result = $this->perpanjanganService->deleteDataFormTigaPerpanjangan($id);
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

    // Form Empat

    public function listDataFormEmpatPerpanjangan()
    {
        try {
            $result = $this->perpanjanganService->listDataFormEmpatPerpanjangan();
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

    public function listDataFormEmpatPerpanjanganByFormTiga($id)
    {
        try {
            $result = $this->perpanjanganService->listDataFormEmpatPerpanjanganByFormTiga($id);
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

    public function detailDataFormEmpatPerpanjanganByFormTiga($id)
    {
        try {

            $result = $this->perpanjanganService->detailDataFormEmpatPerpanjanganByFormTiga($id);
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

    public function detailDataFormEmpatPerpanjangan($id)
    {
        try {

            $result = $this->perpanjanganService->detailDataFormEmpatPerpanjangan($id);
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

    public function inputDataFormEmpatPerpanjangan(Request $request)
    {
        try {
            $validateData = $request->validate([
                'skhpp' => 'nullable|file|mimes:pdf|max:2048',
                'tanggal_awal_berlaku' => 'required|date',
                'tanggal_akhir_berlaku' => 'required|date',
                'status' => 'required|in:0,1,2',
                'form_tiga_perpanjangan_id' => 'required|exists:form_tiga_perpanjangans,id'
            ]);

            $result = $this->perpanjanganService->inputDataFormEmpatPerpanjangan($validateData, $request->allFiles());
            $form = FormTigaPerpanjangan::with('formDua.formSatu')
                ->find($request->form_tiga_perpanjangan_id);
            $userId = $form->formDua->formSatu->pic_perusahaan_litpers_id;
            NotificationHelper::broadcastNotification('mitra_litpers', 'SKHPP penerbitan baru anda telah diterbitkan.', $userId);

            return response()->json(
                [
                    'id' => $result['id'],
                    'data' => $result['data'],
                    'message' => $result['message']
                ],
                $result['statusCode']
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }
    }

    public function updateDataFormEmpatPerpanjangan(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'skhpp' => 'nullable|file|mimes:pdf|max:2048',
                'tanggal_awal_berlaku' => 'required|date',
                'tanggal_akhir_berlaku' => 'required|date',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->perpanjanganService->updateDataFormEmpatPerpanjangan($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormEmpatPerpanjangan($id)
    {
        try {
            $result = $this->perpanjanganService->deleteDataFormEmpatPerpanjangan($id);
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

    // Dll

    public function verifyDataFormPerpanjangan(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jenis_form' => 'required',
                'status' => 'required|in:0,1,2',
                'catatan_revisi' => 'nullable|string|max:500'
            ]);
            $validateData['admin_litpers_id'] = '1';
            // $validateData['admin_litpers_id'] = auth()->user()->id;

            $result = $this->perpanjanganService->verifyDataFormPerpanjangan($validateData, $id);

            if ($validateData['jenis_form'] == 'form1') {
                $form = FormSatuPerpanjangan::find($id);
                $userId = $form->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 1 perpanjangan anda telah diverifikasi, silahkan dicek.', $userId);
            } elseif ($validateData['jenis_form'] == 'form2') {
                $form = FormDuaPerpanjangan::with('formSatu')->find($id);
                $userId = $form->formSatu->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 2 perpanjangan anda telah diverifikasi, silahkan dicek.', $userId);
            } elseif ($validateData['jenis_form'] == 'form3') {
                $form = FormTigaPerpanjangan::with('formDua.formSatu')->find($id);
                $userId = $form->formDua->formSatu->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 3 perpanjangan anda telah diverifikasi, silahkan dicek.', $userId);
            }

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
}
