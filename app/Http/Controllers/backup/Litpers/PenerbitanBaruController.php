<?php

namespace App\Http\Controllers\Litpers;

use App\Helpers\NotificationHelper;
use App\Models\FormDuaPenerbitanBaru;
use App\Models\FormEmpatPenerbitanBaru;
use App\Models\FormSatuPenerbitanBaru;
use App\Models\FormTigaPenerbitanBaru;
use Illuminate\Http\Request;
use App\Services\Litpers\PenerbitanBaruService;
use Illuminate\Support\Facades\Storage;

class PenerbitanBaruController extends Controller
{
    private $penerbitanBaruService;

    public function __construct(PenerbitanBaruService $penerbitanBaruService)
    {
        $this->penerbitanBaruService = $penerbitanBaruService;
    }

    public function listDataAllFormPenerbitanBaruByMitra($id)
    {
        try {
            $result = $this->penerbitanBaruService->listDataAllFormPenerbitanBaruByMitra($id);
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

    public function listDataUnfinishedFormPenerbitanBaruByMitra($id)
    {
        try {
            $result = $this->penerbitanBaruService->listDataUnfinishedFormPenerbitanBaruByMitra($id);
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

    public function listDataUnvalidatedFormPenerbitanBaru()
    {
        try {
            $result = $this->penerbitanBaruService->listDataUnvalidatedFormPenerbitanBaru();
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

    public function listDataFormSatuPenerbitanBaru()
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormSatuPenerbitanBaru();
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

    public function listDataFormSatuPenerbitanBaruByAdmin($id)
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormSatuPenerbitanBaruByAdmin($id);
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

    public function listDataFormSatuPenerbitanBaruByMitra($id)
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormSatuPenerbitanBaruByMitra($id);
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

    public function detailDataFormSatuPenerbitanBaru($id)
    {
        try {

            $result = $this->penerbitanBaruService->detailDataFormSatuPenerbitanBaru($id);
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

    public function inputDataFormSatuPenerbitanBaru(Request $request)
    {
        try {
            $validateData = $request->validate([
                'surat_disadaau_diskonsau' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
                'jenis_skhpp_id' => 'required|exists:jenis_skhpps,id'
            ]);
            // $validateData['pic_perusahaan_litpers_id'] = '8';
            $validateData['pic_perusahaan_litpers_id'] = auth()->user()->id;

            $result = $this->penerbitanBaruService->inputDataFormSatuPenerbitanBaru($validateData, $request->allFiles());
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form satu penerbitan baru telah ditambahkan.');

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

    public function updateDataFormSatuPenerbitanBaru(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_disadaau_diskonsau' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
                'jenis_skhpp_id' => 'required|exists:jenis_skhpps,id'
            ]);

            $result = $this->penerbitanBaruService->updateDataFormSatuPenerbitanBaru($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormSatuPenerbitanBaru($id)
    {
        try {
            $result = $this->penerbitanBaruService->deleteDataFormSatuPenerbitanBaru($id);
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

    public function listDataFormDuaPenerbitanBaru()
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormDuaPenerbitanBaru();
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

    public function listDataFormDuaPenerbitanBaruByFormSatu($id)
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormDuaPenerbitanBaruByFormSatu($id);
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

    public function detailDataFormDuaPenerbitanBaru($id)
    {
        try {

            $result = $this->penerbitanBaruService->detailDataFormDuaPenerbitanBaru($id);
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

    public function inputDataFormDuaPenerbitanBaru(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jadwal_dip' => 'required|date',
                'status' => 'required|in:0,1,2',
                'form_satu_penerbitan_baru_id' => 'required|exists:form_satu_penerbitan_barus,id'
            ]);

            $result = $this->penerbitanBaruService->inputDataFormDuaPenerbitanBaru($validateData);
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form dua penerbitan baru telah ditambahkan.');

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

    public function updateDataFormDuaPenerbitanBaru(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jadwal_dip' => 'required|date',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->penerbitanBaruService->updateDataFormDuaPenerbitanBaru($validateData, $id);
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

    public function deleteDataFormDuaPenerbitanBaru($id)
    {
        try {
            $result = $this->penerbitanBaruService->deleteDataFormDuaPenerbitanBaru($id);
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

    public function listDataFormTigaPenerbitanBaru()
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormTigaPenerbitanBaru();
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

    public function listDataFormTigaPenerbitanBaruByFormDua($id)
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormTigaPenerbitanBaruByFormDua($id);
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

    public function detailDataFormTigaPenerbitanBaru($id)
    {
        try {

            $result = $this->penerbitanBaruService->detailDataFormTigaPenerbitanBaru($id);
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

    public function inputDataFormTigaPenerbitanBaruPartSatu(Request $request)
    {
        try {
            $validateData = $request->validate([
                'surat_permohonan_penerbitan' => 'required|file|mimes:pdf|max:2048',
                'akte_pendirian_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'akte_perubahan_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'nomor_izin_berusaha' => 'required|file|mimes:pdf|max:2048',
                'nomor_pokok_wajib_pajak' => 'required|file|mimes:pdf|max:2048',
                'status' => 'required|in:0,1,2',
                'form_dua_penerbitan_baru_id' => 'required|exists:form_dua_penerbitan_barus,id'
            ]);

            $result = $this->penerbitanBaruService->inputDataFormTigaPenerbitanBaruPartSatu($validateData, $request->allFiles());
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

    public function inputDataFormTigaPenerbitanBaruPartDua(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_pengukuhan_pengusaha_kena_pajak' => 'required|file|mimes:pdf|max:2048',
                'surat_pernyataan_sehat' => 'required|file|mimes:pdf|max:2048',
                'referensi_bank' => 'required|file|mimes:pdf|max:2048',
                'neraca_perusahaan_terakhir' => 'required|file|mimes:pdf|max:2048',
                'rekening_koran_perusahaan' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->inputDataFormTigaPenerbitanBaruPartDua($validateData, $request->allFiles(), $id);
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

    public function inputDataFormTigaPenerbitanBaruPartTiga(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'cv_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'ktp_jajaran_direksi' => 'required|file|mimes:pdf|max:2048',
                'skck' => 'required|file|mimes:pdf|max:2048',
                'company_profile' => 'required|file|mimes:pdf|max:2048',
                'daftar_pengalaman_pekerjaan_di_tni_au' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->inputDataFormTigaPenerbitanBaruPartTiga($validateData, $request->allFiles(), $id);
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

    public function inputDataFormTigaPenerbitanBaruPartEmpat(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'daftar_peralatan_fasilitas_kantor' => 'required|file|mimes:pdf|max:2048',
                'aset_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'surat_kemampuan_principle_agent' => 'required|file|mimes:pdf|max:2048',
                'surat_loa_poa' => 'required|file|mimes:pdf|max:2048',
                'supporting_letter_dari_vendor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->inputDataFormTigaPenerbitanBaruPartEmpat($validateData, $request->allFiles(), $id);
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

    public function inputDataFormTigaPenerbitanBaruPartLima(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'foto_direktur_4_6' => 'required|file|mimes:pdf|max:2048',
                'kepemilikan_kantor' => 'required|file|mimes:pdf|max:2048',
                'struktur_organisasi' => 'required|file|mimes:pdf|max:2048',
                'foto_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'gambar_rute_denah_kantor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->inputDataFormTigaPenerbitanBaruPartLima($validateData, $request->allFiles(), $id);
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

    public function inputDataFormTigaPenerbitanBaruPartEnam(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'kk_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'beranda_lpse' => 'required|file|mimes:pdf|max:2048',
                'e_catalog' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->inputDataFormTigaPenerbitanBaruPartEnam($validateData, $request->allFiles(), $id);
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form tiga penerbitan baru telah ditambahkan.');
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

    public function updateDataFormTigaPenerbitanBaruPartSatu(Request $request, $id)
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

            $result = $this->penerbitanBaruService->updateDataFormTigaPenerbitanBaruPartSatu($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPenerbitanBaruPartDua(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'surat_pengukuhan_pengusaha_kena_pajak' => 'required|file|mimes:pdf|max:2048',
                'surat_pernyataan_sehat' => 'required|file|mimes:pdf|max:2048',
                'referensi_bank' => 'required|file|mimes:pdf|max:2048',
                'neraca_perusahaan_terakhir' => 'required|file|mimes:pdf|max:2048',
                'rekening_koran_perusahaan' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->updateDataFormTigaPenerbitanBaruPartDua($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPenerbitanBaruPartTiga(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'cv_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'ktp_jajaran_direksi' => 'required|file|mimes:pdf|max:2048',
                'skck' => 'required|file|mimes:pdf|max:2048',
                'company_profile' => 'required|file|mimes:pdf|max:2048',
                'daftar_pengalaman_pekerjaan_di_tni_au' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->updateDataFormTigaPenerbitanBaruPartTiga($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPenerbitanBaruPartEmpat(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'daftar_peralatan_fasilitas_kantor' => 'required|file|mimes:pdf|max:2048',
                'aset_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'surat_kemampuan_principle_agent' => 'required|file|mimes:pdf|max:2048',
                'surat_loa_poa' => 'required|file|mimes:pdf|max:2048',
                'supporting_letter_dari_vendor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->updateDataFormTigaPenerbitanBaruPartEmpat($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPenerbitanBaruPartLima(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'foto_direktur_4_6' => 'required|file|mimes:pdf|max:2048',
                'kepemilikan_kantor' => 'required|file|mimes:pdf|max:2048',
                'struktur_organisasi' => 'required|file|mimes:pdf|max:2048',
                'foto_perusahaan' => 'required|file|mimes:pdf|max:2048',
                'gambar_rute_denah_kantor' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->updateDataFormTigaPenerbitanBaruPartLima($validateData, $request->allFiles(), $id);
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

    public function updateDataFormTigaPenerbitanBaruPartEnam(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'kk_direktur_utama' => 'required|file|mimes:pdf|max:2048',
                'beranda_lpse' => 'required|file|mimes:pdf|max:2048',
                'e_catalog' => 'required|file|mimes:pdf|max:2048',
            ]);

            $result = $this->penerbitanBaruService->updateDataFormTigaPenerbitanBaruPartEnam($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormTigaPenerbitanBaru($id)
    {
        try {
            $result = $this->penerbitanBaruService->deleteDataFormTigaPenerbitanBaru($id);
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

    public function listDataFormEmpatPenerbitanBaru()
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormEmpatPenerbitanBaru();
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

    public function listDataFormEmpatPenerbitanBaruByFormTiga($id)
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormEmpatPenerbitanBaruByFormTiga($id);
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

    public function detailDataFormEmpatPenerbitanBaru($id)
    {
        try {

            $result = $this->penerbitanBaruService->detailDataFormEmpatPenerbitanBaru($id);
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

    public function inputDataFormEmpatPenerbitanBaru(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jadwal_survei' => 'required|date',
                'status' => 'required|in:0,1,2',
                'form_tiga_penerbitan_baru_id' => 'required|exists:form_tiga_penerbitan_barus,id'
            ]);

            $result = $this->penerbitanBaruService->inputDataFormEmpatPenerbitanBaru($validateData);
            NotificationHelper::broadcastNotification('admin_litpers', 'Data form empat penerbitan baru telah ditambahkan.');
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

    public function updateDataFormEmpatPenerbitanBaru(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jadwal_survei' => 'required|date',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->penerbitanBaruService->updateDataFormEmpatPenerbitanBaru($validateData, $id);
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

    public function deleteDataFormEmpatPenerbitanBaru($id)
    {
        try {
            $result = $this->penerbitanBaruService->deleteDataFormEmpatPenerbitanBaru($id);
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

    public function listDataFormLimaPenerbitanBaru()
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormLimaPenerbitanBaru();
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

    public function listDataFormLimaPenerbitanBaruByFormEmpat($id)
    {
        try {
            $result = $this->penerbitanBaruService->listDataFormLimaPenerbitanBaruByFormEmpat($id);
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

    public function detailDataFormLimaPenerbitanBaruByFormEmpat($id)
    {
        try {

            $result = $this->penerbitanBaruService->detailDataFormLimaPenerbitanBaruByFormEmpat($id);
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

    public function detailDataFormLimaPenerbitanBaru($id)
    {
        try {

            $result = $this->penerbitanBaruService->detailDataFormLimaPenerbitanBaru($id);
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

    public function inputDataFormLimaPenerbitanBaru(Request $request)
    {
        try {
            $validateData = $request->validate([
                'skhpp' => 'nullable|file|mimes:pdf|max:2048',
                'tanggal_awal_berlaku' => 'required|date',
                'tanggal_akhir_berlaku' => 'required|date',
                'status' => 'required|in:0,1,2',
                'form_empat_penerbitan_baru_id' => 'required|exists:form_empat_penerbitan_barus,id'
            ]);

            $result = $this->penerbitanBaruService->inputDataFormLimaPenerbitanBaru($validateData, $request->allFiles());

            $form = FormEmpatPenerbitanBaru::with('formTiga.formDua.formSatu')
                ->find($request->form_empat_penerbitan_baru_id);
            $userId = $form->formTiga->formDua->formSatu->pic_perusahaan_litpers_id;
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

    public function updateDataFormLimaPenerbitanBaru(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'skhpp' => 'nullable|file|mimes:pdf|max:2048',
                'tanggal_awal_berlaku' => 'required|date',
                'tanggal_akhir_berlaku' => 'required|date',
                'status' => 'required|in:0,1,2',
            ]);

            $result = $this->penerbitanBaruService->updateDataFormLimaPenerbitanBaru($validateData, $request->allFiles(), $id);
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

    public function deleteDataFormLimaPenerbitanBaru($id)
    {
        try {
            $result = $this->penerbitanBaruService->deleteDataFormLimaPenerbitanBaru($id);
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

    public function verifyDataFormPenerbitanBaru(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jenis_form' => 'required',
                'status' => 'required|in:0,1,2',
                'catatan_revisi' => 'nullable|string|max:500'
            ]);
            // $validateData['admin_litpers_id'] = '1';
            $validateData['admin_litpers_id'] = auth()->user()->id;

            $result = $this->penerbitanBaruService->verifyDataFormPenerbitanBaru($validateData, $id);
            if ($validateData['jenis_form'] == 'form1') {
                $form = FormSatuPenerbitanBaru::find($id);
                $userId = $form->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 1 penerbitan baru anda telah diverifikasi, silahkan dicek.', $userId);
            } elseif ($validateData['jenis_form'] == 'form2') {
                $form = FormDuaPenerbitanBaru::with('formSatu')->find($id);
                $userId = $form->formSatu->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 2 penerbitan baru anda telah diverifikasi, silahkan dicek.', $userId);
            } elseif ($validateData['jenis_form'] == 'form3') {
                $form = FormTigaPenerbitanBaru::with('formDua.formSatu')->find($id);
                $userId = $form->formDua->formSatu->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 3 penerbitan baru anda telah diverifikasi, silahkan dicek.', $userId);
            } elseif ($validateData['jenis_form'] == 'form4') {
                // Adjust the relationships to access the userId from the correct form level
                $form = FormEmpatPenerbitanBaru::with('formTiga.formDua.formSatu')
                    ->find($id);
                $userId = $form->formTiga->formDua->formSatu->pic_perusahaan_litpers_id;
                NotificationHelper::broadcastNotification('mitra_litpers', 'Data form 4 penerbitan baru anda telah diverifikasi, silahkan dicek.', $userId);
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
