<?php

namespace App\Services\Intelud;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Intelud\NonFlightApprovalRepository;

class NonFlightApprovalService
{
    private $nonFlightApprovalRepository;

    public function __construct(NonFlightApprovalRepository $nonFlightApprovalRepository)
    {
        $this->nonFlightApprovalRepository = $nonFlightApprovalRepository;
    }

    public function listPengajuanRejected()
    {
        $encryptedData = $this->nonFlightApprovalRepository->listPengajuanRejected();

        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'nama_perusahaan' => Crypt::decrypt($item->nama_perusahaan),
                'operator' => Crypt::decrypt($item->operator),
                'jenis' => Crypt::decrypt($item->jenis),
                'no_registrasi' => Crypt::decrypt($item->no_registrasi),

                'tanggal_terbang' => Crypt::decrypt($item->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($item->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($item->rute_penerbangan),
                'lanud' => Crypt::decrypt($item->lanud),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),

                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'catatan' => Crypt::decrypt($item->catatan),

                'fk_pic_perusahaan_nfa_id' => $item->fk_pic_perusahaan_nfa_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function listPengajuanApprovated()
    {
        $encryptedData = $this->nonFlightApprovalRepository->listPengajuanApprovated();

        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_perusahaan' => Crypt::decrypt($item->nama_perusahaan),
                'operator' => Crypt::decrypt($item->operator),
                'jenis' => Crypt::decrypt($item->jenis),
                'no_registrasi' => Crypt::decrypt($item->no_registrasi),

                'tanggal_terbang' => Crypt::decrypt($item->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($item->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($item->rute_penerbangan),
                'lanud' => Crypt::decrypt($item->lanud),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),

                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'catatan' => Crypt::decrypt($item->catatan),

                'fk_pic_perusahaan_nfa_id' => $item->fk_pic_perusahaan_nfa_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function postPengajuan(array $dataRequest)
    {
        $dataEncrypt = [
            'nama_perusahaan' => Crypt::encrypt($dataRequest['nama_perusahaan']),
            'operator' => Crypt::encrypt($dataRequest['operator']),
            'jenis' => Crypt::encrypt($dataRequest['jenis']),
            'no_registrasi' => Crypt::encrypt($dataRequest['no_registrasi']),

            'tanggal_terbang' => Crypt::encrypt($dataRequest['tanggal_terbang']),
            'tanggal_mendarat' => Crypt::encrypt($dataRequest['tanggal_mendarat']),
            'rute_penerbangan' => Crypt::encrypt($dataRequest['rute_penerbangan']),
            'lanud' => Crypt::encrypt($dataRequest['lanud']),
            'pendaratan_niaga' => Crypt::encrypt($dataRequest['pendaratan_niaga']),

            'nama_kapten_pilot' => Crypt::encrypt($dataRequest['nama_kapten_pilot']),
            'awak_pesawat_lain' => Crypt::encrypt($dataRequest['awak_pesawat_lain']),
            'penumpang_barang' => Crypt::encrypt($dataRequest['penumpang_barang']),
            'jumlah_kursi' => Crypt::encrypt($dataRequest['jumlah_kursi']),
            'catatan' => Crypt::encrypt($dataRequest['catatan']),

            'fk_pic_perusahaan_nfa_id' => $dataRequest['fk_pic_perusahaan_nfa_id'],
        ];
        return $this->nonFlightApprovalRepository->postPengajuan($dataEncrypt);
    }

    public function listPengajuanNfa()
    {
        $encryptedData = $this->nonFlightApprovalRepository->listPengajuanNfa();

        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis_pengajuan' => 'nfa',
                'nama_perusahaan' => Crypt::decrypt($item->nama_perusahaan),
                'operator' => Crypt::decrypt($item->operator),
                'jenis' => Crypt::decrypt($item->jenis),
                'no_registrasi' => Crypt::decrypt($item->no_registrasi),

                'tanggal_terbang' => Crypt::decrypt($item->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($item->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($item->rute_penerbangan),
                'lanud' => Crypt::decrypt($item->lanud),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),

                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'status' => $item->status,
                'catatan' => Crypt::decrypt($item->catatan),

                'fk_pic_perusahaan_nfa_id' => $item->fk_pic_perusahaan_nfa_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function listPengajuanNfaWhereNoPic()
    {
        $encryptedData = $this->nonFlightApprovalRepository->listPengajuanNfaWhereNoPic();

        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis_pengajuan' => 'nfa',
                'nama_perusahaan' => Crypt::decrypt($item->nama_perusahaan),
                'operator' => Crypt::decrypt($item->operator),
                'jenis' => Crypt::decrypt($item->jenis),
                'no_registrasi' => Crypt::decrypt($item->no_registrasi),

                'tanggal_terbang' => Crypt::decrypt($item->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($item->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($item->rute_penerbangan),
                'lanud' => Crypt::decrypt($item->lanud),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),

                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'status' => $item->status,
                'catatan' => Crypt::decrypt($item->catatan),

                'fk_pic_perusahaan_nfa_id' => $item->fk_pic_perusahaan_nfa_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function detailPengajuan($id)
    {
        $dataEncrypt = $this->nonFlightApprovalRepository->detailPengajuan($id);

        $dataDecrypt = [
            'id' => $dataEncrypt->id,
            'nama_pic' => Crypt::decrypt($dataEncrypt['nama_pic']),
            'hp_pic' => Crypt::decrypt($dataEncrypt['hp_pic']),
            'pic_intelud' => $dataEncrypt['pic_intelud'] ? Crypt::decrypt($dataEncrypt['pic_intelud']) : null,
            'nama_perusahaan' => Crypt::decrypt($dataEncrypt['nama_perusahaan']),
            'operator' => Crypt::decrypt($dataEncrypt['operator']),
            'jenis' => Crypt::decrypt($dataEncrypt['jenis']),
            'no_registrasi' => Crypt::decrypt($dataEncrypt['no_registrasi']),

            'tanggal_terbang' => Crypt::decrypt($dataEncrypt['tanggal_terbang']),
            'tanggal_mendarat' => Crypt::decrypt($dataEncrypt['tanggal_mendarat']),
            'rute_penerbangan' => Crypt::decrypt($dataEncrypt['rute_penerbangan']),
            'lanud' => Crypt::decrypt($dataEncrypt['lanud']),
            'pendaratan_niaga' => Crypt::decrypt($dataEncrypt['pendaratan_niaga']),

            'nama_kapten_pilot' => Crypt::decrypt($dataEncrypt['nama_kapten_pilot']),
            'awak_pesawat_lain' => Crypt::decrypt($dataEncrypt['awak_pesawat_lain']),
            'penumpang_barang' => Crypt::decrypt($dataEncrypt['penumpang_barang']),
            'jumlah_kursi' => Crypt::decrypt($dataEncrypt['jumlah_kursi']),
            'status' => $dataEncrypt['status'],
            'catatan' => Crypt::decrypt($dataEncrypt['catatan']),

            'fk_pic_perusahaan_nfa_id' => $dataEncrypt['fk_pic_perusahaan_nfa_id'],
            'created_at' => $dataEncrypt['created_at'],
        ];

        return $dataDecrypt;
    }

    public function listPengajuanNfaByPicIntelud()
    {
        // Mendapatkan data terenkripsi dari repository
        $encryptedData = $this->nonFlightApprovalRepository->listPengajuanNfaByPicIntelud();

        // Mendekripsi setiap data yang diperlukan
        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_perusahaan' => Crypt::decrypt($item->nama_perusahaan),
                'operator' => Crypt::decrypt($item->operator),
                'jenis' => Crypt::decrypt($item->jenis),
                'no_registrasi' => Crypt::decrypt($item->no_registrasi),

                'tanggal_terbang' => Crypt::decrypt($item->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($item->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($item->rute_penerbangan),
                'lanud' => Crypt::decrypt($item->lanud),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),

                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'status' => $item->status,
                'catatan' => Crypt::decrypt($item->catatan),

                'fk_pic_perusahaan_nfa_id' => $item->fk_pic_perusahaan_nfa_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
        // return $this->nonFlightApprovalRepository->listPengajuanNfaByIdPic();
    }

    public function listPengajuanNfaByIdPic()
    {
        // Mendapatkan data terenkripsi dari repository
        $encryptedData = $this->nonFlightApprovalRepository->listPengajuanNfaByIdPic();

        // Mendekripsi setiap data yang diperlukan
        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'nama_perusahaan' => Crypt::decrypt($item->nama_perusahaan),
                'operator' => Crypt::decrypt($item->operator),
                'jenis' => Crypt::decrypt($item->jenis),
                'no_registrasi' => Crypt::decrypt($item->no_registrasi),

                'tanggal_terbang' => Crypt::decrypt($item->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($item->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($item->rute_penerbangan),
                'lanud' => Crypt::decrypt($item->lanud),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),

                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'status' => $item->status,
                'catatan' => Crypt::decrypt($item->catatan),

                'fk_pic_perusahaan_nfa_id' => $item->fk_pic_perusahaan_nfa_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
        // return $this->nonFlightApprovalRepository->listPengajuanNfaByIdPic();
    }

    public function postFormEmpat(array $validatedData, array $files)
    {
        // Simpan file dan ambil nama file
        $fileNames = $this->saveFiles($files);

        // Masukkan data ke repositori
        return $this->nonFlightApprovalRepository->postFormEmpat(array_merge($validatedData, $fileNames));
    }

    private function saveFiles(array $files)
    {
        $fileNames = [];

        foreach ($files as $key => $file) {
            if ($file->isValid()) {
                // Get the original file name and its extension
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                // Generate a unique file name using hash and the original file name
                $hashedName = md5($originalName . time() . uniqid()) . '.' . $extension;

                // Read the file content
                $fileContent = file_get_contents($file->getRealPath());

                // Encrypt the file content
                $encryptedContent = Crypt::encryptString($fileContent);

                // Save the encrypted content as a new file in storage
                Storage::put('documents_nonFlightApproval/' . $hashedName, $encryptedContent, 'public');

                // Store the hashed name in the array
                $fileNames[$key] = $hashedName; // Store the hashed name
            }
        }

        return $fileNames;
    }

    public function sendToPic($dataRequest)
    {
        try {
            if ($this->cekLevelUser(1)) { // cek apakah dia benar-benar admin
                return $this->nonFlightApprovalRepository->sendToPic($dataRequest);
            }
            return [
                'id' => '0',
                'data' => 'Anda tidak memiliki hak akses'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'Terjadi kesalahan dengan data yang dikirimkan' . $th
            ];
        }
    }

    public function verifyForms($dataRequest, $id)
    {
        try {
            if ($dataRequest['jenis_form'] == 'form1') {
                return $this->nonFlightApprovalRepository->verifyFormSatu($dataRequest, $id);
            } elseif ($dataRequest['jenis_form'] == 'form2') {
                return $this->nonFlightApprovalRepository->verifyFormDua($dataRequest, $id);
            } elseif ($dataRequest['jenis_form'] == 'form3') {
                return $this->nonFlightApprovalRepository->verifyFormTiga($dataRequest, $id);
            } elseif ($dataRequest['jenis_form'] == 'formDocument') {
                return $this->nonFlightApprovalRepository->verifyDocumentNfa($dataRequest, $id);
            } else {
                return [
                    'id' => '0',
                    'data' => 'jenis form tidak sesuai'
                ];
            }
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'kesalahan dalam verifikasi form'
            ];
        }
    }

    private function cekLevelUser($levelTarget)
    {
        $levelPembanding = $this->nonFlightApprovalRepository->cekLevelUser();
        if ($levelTarget == $levelPembanding) {
            return true;
        }
        return false;
    }
}
