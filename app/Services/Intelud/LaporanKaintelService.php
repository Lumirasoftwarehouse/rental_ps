<?php

namespace App\Services\Intelud;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Intelud\LaporanKaintelRepository;

class LaporanKaintelService
{
    private $laporanKaintelRepository;
    public function __construct(LaporanKaintelRepository $laporanKaintelRepository)
    {
        $this->laporanKaintelRepository = $laporanKaintelRepository;
    }

    public function listLaporanKaintel()
    {
        $encryptedData = $this->laporanKaintelRepository->listLaporanKaintel();

        $decryptedData = $encryptedData->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis_pelanggaran' => Crypt::decrypt($item->jenis_pelanggaran),
                'pelanggaran_lainnya' => Crypt::decrypt($item->pelanggaran_lainnya),
                'pelapor' => Crypt::decrypt($item->pelapor),
                'kontak_pelapor' => Crypt::decrypt($item->kontak_pelapor),
                'tanggal_pelanggaran' => Crypt::decrypt($item->tanggal_pelanggaran),
                'lokasi_pelanggaran' => Crypt::decrypt($item->lokasi_pelanggaran),
                'informasi_lainnya' => Crypt::decrypt($item->informasi_lainnya),
                'bukti_pelanggaran' => $item->bukti_pelanggaran, // Nama file yang disimpan
                'jenis_pengajuan' => $item->jenis_pengajuan,
                'status' => $item->status,
                'catatan' => $item['catatan'] ? Crypt::decrypt($item['catatan']) : null,
                'id_pengajuan' => $item->id_pengajuan,
                'created_at' => $item->created_at,
            ];
        });

        return $decryptedData;
    }


    public function detailLaporanKaintel($id)
    {
        try {
            $encryptedData = $this->laporanKaintelRepository->detailLaporanKaintel($id);
            if ($encryptedData['bukti_pelanggaran'] && Storage::disk('public')->exists($encryptedData['bukti_pelanggaran'])) {
                // Get the encrypted content and decrypt it
                $encryptedContent = Storage::disk('public')->get($encryptedData['bukti_pelanggaran']);
                $decryptedContent = Crypt::decrypt($encryptedContent);

                // Optionally return the decrypted content as a base64 encoded string
                $decryptedBuktiPelanggaran = base64_encode($decryptedContent);
            }

            $decryptedData = [
                'id' => $encryptedData->id,
                'jenis_pelanggaran' => Crypt::decrypt($encryptedData->jenis_pelanggaran),
                'pelanggaran_lainnya' => Crypt::decrypt($encryptedData->pelanggaran_lainnya),
                'pelapor' => Crypt::decrypt($encryptedData->pelapor),
                'kontak_pelapor' => Crypt::decrypt($encryptedData->kontak_pelapor),
                'jenis' => Crypt::decrypt($encryptedData->jenis),
                'no_registrasi' => Crypt::decrypt($encryptedData->no_registrasi),
                'rute_penerbangan' => Crypt::decrypt($encryptedData->rute_penerbangan),
                'nama_kapten_pilot' => Crypt::decrypt($encryptedData->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($encryptedData->awak_pesawat_lain),
                'tanggal_pelanggaran' => Crypt::decrypt($encryptedData->tanggal_pelanggaran),
                'lokasi_pelanggaran' => Crypt::decrypt($encryptedData->lokasi_pelanggaran),
                'informasi_lainnya' => Crypt::decrypt($encryptedData->informasi_lainnya),
                'bukti_pelanggaran' => $encryptedData->bukti_pelanggaran, // Nama file yang disimpan
                'jenis_pengajuan' => $encryptedData->jenis_pengajuan,
                'status' => $encryptedData->status,
                'catatan' => $encryptedData->catatan ? Crypt::decrypt($encryptedData->catatan) : null,
                'id_pengajuan' => $encryptedData->id_pengajuan,
                'created_at' => $encryptedData->created_at,
                'files' => [
                    'bukti_pelanggaran' => $decryptedBuktiPelanggaran
                ]
            ];

            return $decryptedData;
        } catch (\Throwable $th) {
            return $th;
        }
    }

    // Proses membuat laporan dengan enkripsi
    public function createLaporanKaintel($dataRequest)
    {
        // Proses upload dan enkripsi file
        $buktiPelanggaranFileName = null;

        if (isset($dataRequest['bukti_pelanggaran']) && $dataRequest['bukti_pelanggaran']->isValid()) {
            $file = $dataRequest['bukti_pelanggaran'];

            // Generate path penyimpanan dengan nama unik
            $filePath = $file->store('intelud/bukti_pelanggaran', 'public');

            // Baca konten file asli
            $fileContent = file_get_contents($file->getRealPath());

            // Enkripsi konten file
            $encryptedContent = Crypt::encrypt($fileContent);

            // Tulis ulang file dengan konten terenkripsi
            Storage::disk('public')->put($filePath, $encryptedContent);

            // Simpan nama file untuk database
            $buktiPelanggaranFileName = $filePath;
        }

        // Enkripsi data lainnya
        $dataEncrypt = [
            'jenis_pelanggaran' => Crypt::encrypt($dataRequest['jenis_pelanggaran']),
            'pelanggaran_lainnya' => Crypt::encrypt($dataRequest['pelanggaran_lainnya']),
            'pelapor' => Crypt::encrypt($dataRequest['pelapor']),
            'kontak_pelapor' => Crypt::encrypt($dataRequest['kontak_pelapor']),
            'tanggal_pelanggaran' => Crypt::encrypt($dataRequest['tanggal_pelanggaran']),
            'lokasi_pelanggaran' => Crypt::encrypt($dataRequest['lokasi_pelanggaran']),
            'informasi_lainnya' => Crypt::encrypt($dataRequest['informasi_lainnya']),
            'bukti_pelanggaran' => $buktiPelanggaranFileName, // Nama file yang disimpan
            'jenis_pengajuan' => $dataRequest['jenis_pengajuan'],
            'id_pengajuan' => $dataRequest['id_pengajuan'],
        ];

        // Simpan data terenkripsi ke repository
        return $this->laporanKaintelRepository->createLaporanKaintel($dataEncrypt);
    }

    public function createLaporanKhususKaintel($dataRequest)
    {
        // Proses upload dan enkripsi file
        $buktiPelanggaranFileName = null;

        if (isset($dataRequest['bukti_pelanggaran']) && $dataRequest['bukti_pelanggaran']->isValid()) {
            $file = $dataRequest['bukti_pelanggaran'];

            // Generate path penyimpanan dengan nama unik
            $filePath = $file->store('intelud/bukti_pelanggaran', 'public');

            // Baca konten file asli
            $fileContent = file_get_contents($file->getRealPath());

            // Enkripsi konten file
            $encryptedContent = Crypt::encrypt($fileContent);

            // Tulis ulang file dengan konten terenkripsi
            Storage::disk('public')->put($filePath, $encryptedContent);

            // Simpan nama file untuk database
            $buktiPelanggaranFileName = $filePath;
        }

        // Enkripsi data lainnya
        $dataEncrypt = [
            'jenis_pelanggaran' => Crypt::encrypt($dataRequest['jenis_pelanggaran']),
            'pelanggaran_lainnya' => Crypt::encrypt($dataRequest['pelanggaran_lainnya']),
            'pelapor' => Crypt::encrypt($dataRequest['pelapor']),
            'kontak_pelapor' => Crypt::encrypt($dataRequest['kontak_pelapor']),
            'tanggal_pelanggaran' => Crypt::encrypt($dataRequest['tanggal_pelanggaran']),
            'lokasi_pelanggaran' => Crypt::encrypt($dataRequest['lokasi_pelanggaran']),
            'informasi_lainnya' => Crypt::encrypt($dataRequest['informasi_lainnya']),
            'bukti_pelanggaran' => $buktiPelanggaranFileName,
        ];

        // Simpan data terenkripsi ke repository
        return $this->laporanKaintelRepository->createLaporanKhususKaintel($dataEncrypt);
    }

    // API untuk menampilkan preview file setelah dekripsi
    public function previewFile($fileName)
    {
        try {
            // Retrieve file content
            $filePath = 'public/' . $fileName; // Add 'public' prefix for storage path
            if (!Storage::exists($filePath)) {
                return response()->json(['error' => 'File not found'], 404);
            }

            $encryptedContent = Storage::get($filePath);

            // Decrypt content
            $decryptedContent = Crypt::decrypt($encryptedContent);

            return response($decryptedContent, 200, [
                'Content-Type' => mime_content_type(storage_path('app/' . $filePath)),
                'Content-Disposition' => 'inline; filename="' . basename($fileName) . '"'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to decrypt file: ' . $e->getMessage()], 500);
        }
    }

    public function updateLaporanKaintel($dataRequest, $id)
    {
        // Proses upload file jika ada file baru
        $buktiPelanggaranFileName = null;
        if (isset($dataRequest['bukti_pelanggaran']) && $dataRequest['bukti_pelanggaran']->isValid()) {
            $buktiPelanggaranFileName = $dataRequest['bukti_pelanggaran']->store('bukti_pelanggaran', 'public');
        }

        $dataEncrypt = [
            'jenis_pelanggaran' => Crypt::encrypt($dataRequest['jenis_pelanggaran']),
            'pelanggaran_lainnya' => Crypt::encrypt($dataRequest['pelanggaran_lainnya']),
            'pelapor' => Crypt::encrypt($dataRequest['pelapor']),
            'kontak_pelapor' => Crypt::encrypt($dataRequest['kontak_pelapor']),
            'tanggal_pelanggaran' => Crypt::encrypt($dataRequest['tanggal_pelanggaran']),
            'lokasi_pelanggaran' => Crypt::encrypt($dataRequest['lokasi_pelanggaran']),
            'informasi_lainnya' => Crypt::encrypt($dataRequest['informasi_lainnya']),
            'bukti_pelanggaran' => $buktiPelanggaranFileName ?: $dataRequest['existing_bukti_pelanggaran'], // Gunakan file lama jika tidak ada file baru
            'jenis_pengajuan' => $dataRequest['jenis_pengajuan'],
            'id_pengajuan' => $dataRequest['id_pengajuan'],
        ];

        return $this->laporanKaintelRepository->updateLaporanKaintel($dataEncrypt, $id);
    }


    public function deleteLaporanKaintel($id)
    {
        return $this->laporanKaintelRepository->deleteLaporanKaintel($id);
    }

    public function verifyLaporanKaintel($dataRequest, $id)
    {
        $dataEncrypt = [
            'status' => $dataRequest['status'],
        ];
        if (isset($dataRequest['catatan'])) {
            $dataEncrypt['catatan'] = Crypt::encrypt($dataRequest['catatan']);
        }
        return $this->laporanKaintelRepository->verifyLaporanKaintel($dataEncrypt, $id);
    }
}
