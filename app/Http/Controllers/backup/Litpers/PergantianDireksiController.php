<?php

namespace App\Http\Controllers\Litpers;

use App\Helpers\NotificationHelper;
use App\Models\FormDuaPergantianDireksi;
use App\Models\FormEmpatPergantianDireksi;
use App\Models\FormSatuPergantianDireksi;
use App\Models\FormTigaPergantianDireksi;
use Illuminate\Http\Request;
use App\Services\Litpers\PergantianDireksiService;
use Illuminate\Support\Facades\Storage;

class PergantianDireksiController extends Controller
{
    private $pergantianDireksiService;

    public function __construct(PergantianDireksiService $pergantianDireksiService)
    {
        $this->pergantianDireksiService = $pergantianDireksiService;
    }

    public function listDataAllFormPergantianDireksiByMitra($id)
    {
        try {
            $result = $this->pergantianDireksiService->listDataAllFormPergantianDireksiByMitra($id);
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

    public function listDataUnfinishedFormPergantianDireksiByMitra($id)
    {
        try {
            $result = $this->pergantianDireksiService->listDataUnfinishedFormPergantianDireksiByMitra($id);
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

    public function listDataUnvalidatedFormPergantianDireksi()
    {
        try {
            $result = $this->pergantianDireksiService->listDataUnvalidatedFormPergantianDireksi();
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

    public function listDataFormSatuPergantianDireksi()
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormSatuPergantianDireksi();
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

    public function listDataFormSatuPergantianDireksiByAdmin($id)
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormSatuPergantianDireksiByAdmin($id);
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

    public function listDataFormSatuPergantianDireksiByMitra($id)
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormSatuPergantianDireksiByMitra($id);
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

    public function detailDataFormSatuPergantianDireksi($id)
    {
        try {

            $result = $this->pergantianDireksiService->detailDataFormSatuPergantianDireksi($id);
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

    public function inputDataFormSatuPergantianDireksi(Request $request)
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

            $result = $this->pergantianDireksiService->inputDataFormSatuPergantianDireksi($validateData, $request->allFiles());
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form satu pergantian direksi telah ditambahkan.');

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

    public function updateDataFormSatuPergantianDireksi(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_disadaau_diskonsau' => 'required|file|mimes:pdf|max:2048',
                'skhpp_lama' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
                'jenis_skhpp_id' => 'required|exists:jenis_skhpps,id'
            ]);

            $result = $this->pergantianDireksiService->updateDataFormSatuPergantianDireksi($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormSatuPergantianDireksi($id)
    {
        try {
            $result = $this->pergantianDireksiService->deleteDataFormSatuPergantianDireksi($id);
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

    public function listDataFormDuaPergantianDireksi()
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormDuaPergantianDireksi();
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

    public function listDataFormDuaPergantianDireksiByFormSatu($id)
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormDuaPergantianDireksiByFormSatu($id);
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

    public function detailDataFormDuaPergantianDireksi($id)
    {
        try {

            $result = $this->pergantianDireksiService->detailDataFormDuaPergantianDireksi($id);
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

    public function inputDataFormDuaPergantianDireksi(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jadwal_dip' => 'required|date',
                'status' => 'required|in:0,1,2',
                'form_satu_pergantian_id' => 'required|exists:form_satu_pergantian_direksis,id'
            ]);

            $result = $this->pergantianDireksiService->inputDataFormDuaPergantianDireksi($validateData);
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form dua pergantian direksi telah ditambahkan.');

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

    public function updateDataFormDuaPergantianDireksi(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jadwal_dip' => 'required|date',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->pergantianDireksiService->updateDataFormDuaPergantianDireksi($validateData, $id);
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

    public function deleteDataFormDuaPergantianDireksi($id)
    {
        try {
            $result = $this->pergantianDireksiService->deleteDataFormDuaPergantianDireksi($id);
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

    public function listDataFormTigaPergantianDireksi()
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormTigaPergantianDireksi();
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

    public function listDataFormTigaPergantianDireksiByFormDua($id)
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormTigaPergantianDireksiByFormDua($id);
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

    public function detailDataFormTigaPergantianDireksi($id)
    {
        try {

            $result = $this->pergantianDireksiService->detailDataFormTigaPergantianDireksi($id);
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

    public function inputDataFormTigaPergantianDireksiPartSatu(Request $request)
    {
        try {
            $validateData = $request->validate([
                'surat_permohonan_penerbitan' => 'required|file|mimes:pdf|max:2048',
                'akte_pendirian_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'akte_perubahan_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'nomor_izin_berusaha' => 'required|file|mimes:pdf|max:2048',
                'nomor_pokok_wajib_pajak' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
                'form_dua_pergantian_id' => 'required|exists:form_dua_pergantian_direksis,id'
            ]);

            $result = $this->pergantianDireksiService->inputDataFormTigaPergantianDireksiPartSatu($validateData, $request->allFiles());
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

    public function inputDataFormTigaPergantianDireksiPartDua(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_pengukuhan_pengusaha_kena_pajak' => 'required|file|mimes:pdf|max:2048',
                'surat_pernyataan_sehat' => 'required|file|mimes:pdf|max:2048',
                'referensi_bank' => 'required|file|mimes:pdf|max:2048',
                'neraca_perusahaan_terakhir' => 'required|file|mimes:pdf|max:2048',
                'rekening_koran_perusahaan' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->inputDataFormTigaPergantianDireksiPartDua($validateData, $request->allFiles(), $id);
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

    public function inputDataFormTigaPergantianDireksiPartTiga(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'cv_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'ktp_jajaran_direksi' => 'required|file|mimes:pdf|max:2048',
                'skck' => 'required|file|mimes:pdf|max:2048',
                'company_profile' => 'required|file|mimes:pdf|max:2048',
                'daftar_pengalaman_pekerjaan_di_tni_au' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->inputDataFormTigaPergantianDireksiPartTiga($validateData, $request->allFiles(), $id);
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

    public function inputDataFormTigaPergantianDireksiPartEmpat(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'daftar_peralatan_fasilitas_kantor' => 'required|file|mimes:pdf|max:2048',
                'aset_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'surat_kemampuan_principle_agent' => 'required|file|mimes:pdf|max:2048',
                'surat_loa_poa' => 'required|file|mimes:pdf|max:2048',
                'supporting_letter_dari_vendor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->inputDataFormTigaPergantianDireksiPartEmpat($validateData, $request->allFiles(), $id);
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

    public function inputDataFormTigaPergantianDireksiPartLima(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'foto_direktur_4_6' => 'required|file|mimes:pdf|max:2048',
                'kepemilikan_kantor' => 'required|file|mimes:pdf|max:2048',
                'struktur_organisasi' => 'required|file|mimes:pdf|max:2048',
                'foto_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'gambar_rute_denah_kantor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->inputDataFormTigaPergantianDireksiPartLima($validateData, $request->allFiles(), $id);
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

    public function inputDataFormTigaPergantianDireksiPartEnam(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'kk_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'beranda_lpse' => 'required|file|mimes:pdf|max:2048',
                'e_catalog' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->inputDataFormTigaPergantianDireksiPartEnam($validateData, $request->allFiles(), $id);
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form tiga pergantian direksi telah ditambahkan.');

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

    public function updateDataFormTigaPergantianDireksiPartSatu(Request $request, $id)
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

            $result = $this->pergantianDireksiService->updateDataFormTigaPergantianDireksiPartSatu($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPergantianDireksiPartDua(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_pengukuhan_pengusaha_kena_pajak' => 'required|file|mimes:pdf|max:2048',
                'surat_pernyataan_sehat' => 'required|file|mimes:pdf|max:2048',
                'referensi_bank' => 'required|file|mimes:pdf|max:2048',
                'neraca_perusahaan_terakhir' => 'required|file|mimes:pdf|max:2048',
                'rekening_koran_perusahaan' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->updateDataFormTigaPergantianDireksiPartDua($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPergantianDireksiPartTiga(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'cv_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'ktp_jajaran_direksi' => 'required|file|mimes:pdf|max:2048',
                'skck' => 'required|file|mimes:pdf|max:2048',
                'company_profile' => 'required|file|mimes:pdf|max:2048',
                'daftar_pengalaman_pekerjaan_di_tni_au' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->updateDataFormTigaPergantianDireksiPartTiga($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPergantianDireksiPartEmpat(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'daftar_peralatan_fasilitas_kantor' => 'required|file|mimes:pdf|max:2048',
                'aset_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'surat_kemampuan_principle_agent' => 'required|file|mimes:pdf|max:2048',
                'surat_loa_poa' => 'required|file|mimes:pdf|max:2048',
                'supporting_letter_dari_vendor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->updateDataFormTigaPergantianDireksiPartEmpat($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPergantianDireksiPartLima(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'foto_direktur_4_6' => 'required|file|mimes:pdf|max:2048',
                'kepemilikan_kantor' => 'required|file|mimes:pdf|max:2048',
                'struktur_organisasi' => 'required|file|mimes:pdf|max:2048',
                'foto_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'gambar_rute_denah_kantor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->updateDataFormTigaPergantianDireksiPartLima($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPergantianDireksiPartEnam(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'kk_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'beranda_lpse' => 'required|file|mimes:pdf|max:2048',
                'e_catalog' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->pergantianDireksiService->updateDataFormTigaPergantianDireksiPartEnam($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormTigaPergantianDireksi($id)
    {
        try {
            $result = $this->pergantianDireksiService->deleteDataFormTigaPergantianDireksi($id);
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

    public function listDataFormEmpatPergantianDireksi()
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormEmpatPergantianDireksi();
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

    public function listDataFormEmpatPergantianDireksiByFormTiga($id)
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormEmpatPergantianDireksiByFormTiga($id);
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

    public function detailDataFormEmpatPergantianDireksi($id)
    {
        try {

            $result = $this->pergantianDireksiService->detailDataFormEmpatPergantianDireksi($id);
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

    public function inputDataFormEmpatPergantianDireksi(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jadwal_survei' => 'required|date',
                'status' => 'required|in:0,1,2',
                'form_tiga_pergantian_id' => 'required|exists:form_tiga_pergantian_direksis,id'
            ]);

            $result = $this->pergantianDireksiService->inputDataFormEmpatPergantianDireksi($validateData);
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form empat pergantian direksi telah ditambahkan.');

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

    public function updateDataFormEmpatPergantianDireksi(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jadwal_survei' => 'required|date',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->pergantianDireksiService->updateDataFormEmpatPergantianDireksi($validateData, $id);
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

    public function deleteDataFormEmpatPergantianDireksi($id)
    {
        try {
            $result = $this->pergantianDireksiService->deleteDataFormEmpatPergantianDireksi($id);
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

    // Form Lima

    public function listDataFormLimaPergantianDireksi()
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormLimaPergantianDireksi();
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

    public function listDataFormLimaPergantianDireksiByFormEmpat($id)
    {
        try {
            $result = $this->pergantianDireksiService->listDataFormLimaPergantianDireksiByFormEmpat($id);
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

    public function detailDataFormLimaPergantianDireksiByFormEmpat($id)
    {
        try {

            $result = $this->pergantianDireksiService->detailDataFormLimaPergantianDireksiByFormEmpat($id);
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

    public function detailDataFormLimaPergantianDireksi($id)
    {
        try {

            $result = $this->pergantianDireksiService->detailDataFormLimaPergantianDireksi($id);
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

    public function inputDataFormLimaPergantianDireksi(Request $request)
    {
        try {
            $validateData = $request->validate([
                'skhpp' => 'nullable|file|mimes:pdf|max:2048',
                'tanggal_awal_berlaku' => 'required|date',
                'tanggal_akhir_berlaku' => 'required|date',
                'status' => 'required|in:0,1,2',
                'form_empat_pergantian_id' => 'required|exists:form_empat_pergantian_direksis,id'
            ]);

            $result = $this->pergantianDireksiService->inputDataFormLimaPergantianDireksi($validateData, $request->allFiles());

            $form = FormEmpatPergantianDireksi::with('formTiga.formDua.formSatu')
                ->find($request->form_empat_pergantian_id);
            $userId = $form->formTiga->formDua->formSatu->pic_perusahaan_litpers_id;
            NotificationHelper::broadcastNotification('mitra_litpers', 'SKHPP pergantian direksi anda telah diterbitkan.', $userId);

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

    public function updateDataFormLimaPergantianDireksi(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'skhpp' => 'nullable|file|mimes:pdf|max:2048',
                'tanggal_awal_berlaku' => 'required|date',
                'tanggal_akhir_berlaku' => 'required|date',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->pergantianDireksiService->updateDataFormLimaPergantianDireksi($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormLimaPergantianDireksi($id)
    {
        try {
            $result = $this->pergantianDireksiService->deleteDataFormLimaPergantianDireksi($id);
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

    public function verifyDataFormPergantianDireksi(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jenis_form' => 'required',
                'status' => 'required|in:0,1,2',
                'catatan_revisi' => 'nullable|string|max:500'
            ]);
            // $validateData['admin_litpers_id'] = '1';
            $validateData['admin_litpers_id'] = auth()->user()->id;

            $result = $this->pergantianDireksiService->verifyDataFormPergantianDireksi($validateData, $id);

            if ($validateData['jenis_form'] == 'form1') {
                $form = FormSatuPergantianDireksi::find($id);
                $userId = $form->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 1 pergantian direksi anda telah diverifikasi, silahkan dicek.', $userId);
            } elseif ($validateData['jenis_form'] == 'form2') {
                $form = FormDuaPergantianDireksi::with('formSatu')->find($id);
                $userId = $form->formSatu->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 2 pergantian direksi anda telah diverifikasi, silahkan dicek.', $userId);
            } elseif ($validateData['jenis_form'] == 'form3') {
                $form = FormTigaPergantianDireksi::with('formDua.formSatu')->find($id);
                $userId = $form->formDua->formSatu->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 3 pergantian direksi anda telah diverifikasi, silahkan dicek.', $userId);
            } elseif ($validateData['jenis_form'] == 'form4') {
                // Adjust the relationships to access the userId from the correct form level
                $form = FormEmpatPergantianDireksi::with('formTiga.formDua.formSatu')
                    ->find($id);
                $userId = $form->formTiga->formDua->formSatu->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 4 pergantian direksi anda telah diverifikasi, silahkan dicek.', $userId);
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
