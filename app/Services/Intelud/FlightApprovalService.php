<?php

namespace App\Services\Intelud;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Intelud\FlightApprovalRepository;

class FlightApprovalService
{
    private $flightApprovalRepository;

    public function __construct(FlightApprovalRepository $flightApprovalRepository)
    {
        $this->flightApprovalRepository = $flightApprovalRepository;
    }

    public function listPengajuanApprovated()
    {
        $encryptedData = $this->flightApprovalRepository->listPengajuanApprovated();

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
                'pendaratan_teknik' => Crypt::decrypt($item->pendaratan_teknik),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),
                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'fa' => Crypt::decrypt($item->fa),
                'catatan' => Crypt::decrypt($item->catatan),
                'fk_pic_perusahaan_nfc_id' => $item->fk_pic_perusahaan_nfc_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function listPengajuanRejected()
    {
        $encryptedData = $this->flightApprovalRepository->listPengajuanRejected();

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
                'pendaratan_teknik' => Crypt::decrypt($item->pendaratan_teknik),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),
                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'fa' => Crypt::decrypt($item->fa),
                'catatan' => Crypt::decrypt($item->catatan),
                'fk_pic_perusahaan_nfc_id' => $item->fk_pic_perusahaan_nfc_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }



    public function listPengajuan()
    {
        $encryptedData = $this->flightApprovalRepository->listPengajuan();

        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis_pengajuan' => 'fa',
                'nama_perusahaan' => Crypt::decrypt($item->nama_perusahaan),
                'operator' => Crypt::decrypt($item->operator),
                'jenis' => Crypt::decrypt($item->jenis),
                'no_registrasi' => Crypt::decrypt($item->no_registrasi),
                'tanggal_terbang' => Crypt::decrypt($item->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($item->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($item->rute_penerbangan),
                'lanud' => Crypt::decrypt($item->lanud),
                'pendaratan_teknik' => Crypt::decrypt($item->pendaratan_teknik),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),
                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'fa' => Crypt::decrypt($item->fa),
                'status' => $item->status,
                'catatan' => Crypt::decrypt($item->catatan),
                'fk_pic_perusahaan_nfc_id' => $item->fk_pic_perusahaan_nfc_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function listPengajuanWhereNoPic()
    {
        $encryptedData = $this->flightApprovalRepository->listPengajuanWhereNoPic();

        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis_pengajuan' => 'fa',
                'nama_perusahaan' => Crypt::decrypt($item->nama_perusahaan),
                'operator' => Crypt::decrypt($item->operator),
                'jenis' => Crypt::decrypt($item->jenis),
                'no_registrasi' => Crypt::decrypt($item->no_registrasi),
                'tanggal_terbang' => Crypt::decrypt($item->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($item->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($item->rute_penerbangan),
                'lanud' => Crypt::decrypt($item->lanud),
                'pendaratan_teknik' => Crypt::decrypt($item->pendaratan_teknik),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),
                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'fa' => Crypt::decrypt($item->fa),
                'status' => $item->status,
                'catatan' => Crypt::decrypt($item->catatan),
                'fk_pic_perusahaan_nfc_id' => $item->fk_pic_perusahaan_nfc_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function listPengajuanFaByPicIntelud()
    {
        // Mendapatkan data terenkripsi dari repository
        $encryptedData = $this->flightApprovalRepository->listPengajuanFaByPicIntelud();

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
                'pendaratan_teknik' => Crypt::decrypt($item->pendaratan_teknik),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),
                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'fa' => Crypt::decrypt($item->fa),
                'status' => $item->status,
                'catatan' => Crypt::decrypt($item->catatan),
                'fk_pic_perusahaan_nfc_id' => $item->fk_pic_perusahaan_nfc_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function listPengajuanFaByIdPic()
    {
        // Mendapatkan data terenkripsi dari repository
        $encryptedData = $this->flightApprovalRepository->listPengajuanFaByIdPic();

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
                'pendaratan_teknik' => Crypt::decrypt($item->pendaratan_teknik),
                'pendaratan_niaga' => Crypt::decrypt($item->pendaratan_niaga),
                'nama_kapten_pilot' => Crypt::decrypt($item->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($item->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($item->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($item->jumlah_kursi),
                'fa' => Crypt::decrypt($item->fa),
                'status' => $item->status,
                'catatan' => Crypt::decrypt($item->catatan),
                'fk_pic_perusahaan_nfc_id' => $item->fk_pic_perusahaan_nfc_id,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }

    public function detailPengajuan($id)
    {
        $dataEncrypt = $this->flightApprovalRepository->detailPengajuan($id);

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
            'pendaratan_teknik' => Crypt::decrypt($dataEncrypt['pendaratan_teknik']),
            'pendaratan_niaga' => Crypt::decrypt($dataEncrypt['pendaratan_niaga']),
            'nama_kapten_pilot' => Crypt::decrypt($dataEncrypt['nama_kapten_pilot']),
            'awak_pesawat_lain' => Crypt::decrypt($dataEncrypt['awak_pesawat_lain']),
            'penumpang_barang' => Crypt::decrypt($dataEncrypt['penumpang_barang']),
            'jumlah_kursi' => Crypt::decrypt($dataEncrypt['jumlah_kursi']),
            'fa' => Crypt::decrypt($dataEncrypt['fa']),
            'status' => $dataEncrypt['status'],
            'catatan' => Crypt::decrypt($dataEncrypt['catatan']),
            'status' => $dataEncrypt['status'],
            'fk_pic_perusahaan_nfc_id' => $dataEncrypt['fk_pic_perusahaan_nfc_id'],
            'created_at' => $dataEncrypt['created_at'],
        ];

        return $dataDecrypt;
    }


    public function detailDocumentPengajuan($id)
    {
        // Ambil nama file dari repository untuk setiap dokumen terkait pengajuan
        $fileData = $this->flightApprovalRepository->detailDocumentPengajuan($id);

        // Pastikan data file yang diambil valid
        if (!$fileData || empty($fileData)) {
            return [
                'id' => '0',
                'data' => 'No files found for this submission.'
            ];
        }

        // Array untuk menyimpan konten file yang sudah didekripsi
        $decryptedFiles = [];

        // Loop melalui semua kunci file yang ada
        $fileKeys = [
            'sertifikat_operator',
            'flight_approval',
            'sertifikat_kelaludaraan',
            'sertifikat_pendaftaran',
            'izin_usaha',
            'permohonan_lanud_khusus',
            'fc_crew',
            'sertifikat_vaksin',
            'rapid_antigen',
        ];

        foreach ($fileKeys as $key) {
            // Pastikan kunci dokumen tersedia dalam data
            if (isset($fileData[$key]) && $fileData[$key]) {
                // Tentukan lokasi file di storage
                $filePath = 'documents_flightApproval/' . $fileData[$key];

                if (Storage::exists($filePath)) {
                    // Ambil konten file terenkripsi
                    $encryptedContent = Storage::get($filePath);

                    try {
                        // Dekripsi konten file
                        $decryptedContent = Crypt::decryptString($encryptedContent);

                        // Masukkan konten yang didekripsi ke dalam array dengan kunci yang relevan
                        $decryptedFiles[$key] = [
                            'content' => base64_encode($decryptedContent), // Gunakan base64_encode untuk data biner
                            'file_name' => $fileData[$key],
                        ];
                    } catch (\Exception $e) {
                        // Tangani kesalahan jika dekripsi gagal
                        $decryptedFiles[$key] = ['error' => 'Failed to decrypt file.'];
                    }
                } else {
                    // Jika file tidak ditemukan
                    $decryptedFiles[$key] = ['error' => 'File not found in storage.'];
                }
            } else {
                // Jika tidak ada file untuk kunci ini
                $decryptedFiles[$key] = ['error' => 'No file provided for this key.'];
            }
        }
        // Kembalikan hasil dalam format JSON
        return $decryptedFiles;
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
            'pendaratan_teknik' => Crypt::encrypt($dataRequest['pendaratan_teknik']),
            'pendaratan_niaga' => Crypt::encrypt($dataRequest['pendaratan_niaga']),
            'nama_kapten_pilot' => Crypt::encrypt($dataRequest['nama_kapten_pilot']),
            'awak_pesawat_lain' => Crypt::encrypt($dataRequest['awak_pesawat_lain']),
            'penumpang_barang' => Crypt::encrypt($dataRequest['penumpang_barang']),
            'jumlah_kursi' => Crypt::encrypt($dataRequest['jumlah_kursi']),
            'fa' => Crypt::encrypt($dataRequest['fa']),
            'catatan' => Crypt::encrypt($dataRequest['catatan']),
            'fk_pic_perusahaan_nfc_id' => $dataRequest['fk_pic_perusahaan_nfc_id'],
        ];
        return $this->flightApprovalRepository->postPengajuan($dataEncrypt);
    }

    public function postFormEmpat(array $validatedData, array $files)
    {
        // Simpan file dan ambil nama file yang disimpan
        $fileNames = $this->saveFiles($files);

        // Gabungkan data validasi dan nama file yang disimpan
        return $this->flightApprovalRepository->postFormEmpat(array_merge($validatedData, $fileNames));
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
                Storage::put('documents_flightApproval/' . $hashedName, $encryptedContent, 'public');

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
                return $this->flightApprovalRepository->sendToPic($dataRequest);
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
                return $this->flightApprovalRepository->verifyFormSatu($dataRequest, $id);
            } elseif ($dataRequest['jenis_form'] == 'form2') {
                return $this->flightApprovalRepository->verifyFormDua($dataRequest, $id);
            } elseif ($dataRequest['jenis_form'] == 'form3') {
                return $this->flightApprovalRepository->verifyFormTiga($dataRequest, $id);
            } elseif ($dataRequest['jenis_form'] == 'formDocument') {
                return $this->flightApprovalRepository->verifyDocumentFsc($dataRequest, $id);
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
        $levelPembanding = $this->flightApprovalRepository->cekLevelUser();
        if ($levelTarget == $levelPembanding) {
            return true;
        }
        return false;
    }

    public function rejectPengajuan($dataRequest, $id)
    {
        return $this->flightApprovalRepository->rejectPengajuan($dataRequest, $id);
    }
}
