<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PengajuanFsc;
use App\Models\PengajuanNfa;
use App\Events\UserRegistered;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PdfController extends Controller
{
    public function previewSuratIntelud($id)
    {
        $dataPengajuanFsc = PengajuanFsc::find($id);

        // Dekripsi data dari database
        $data = [
            'nama_perusahaan' => Crypt::decrypt($dataPengajuanFsc->nama_perusahaan),
            'operator' => Crypt::decrypt($dataPengajuanFsc->operator),
            'jenis' => Crypt::decrypt($dataPengajuanFsc->jenis),
            'no_registrasi' => Crypt::decrypt($dataPengajuanFsc->no_registrasi),
            'tanggal_terbang' => Crypt::decrypt($dataPengajuanFsc->tanggal_terbang),
            'tanggal_mendarat' => Crypt::decrypt($dataPengajuanFsc->tanggal_mendarat),
            'rute_penerbangan' => Crypt::decrypt($dataPengajuanFsc->rute_penerbangan),
            'lanud' => Crypt::decrypt($dataPengajuanFsc->lanud),
            'pendaratan_teknik' => Crypt::decrypt($dataPengajuanFsc->pendaratan_teknik),
            'pendaratan_niaga' => Crypt::decrypt($dataPengajuanFsc->pendaratan_niaga),
            'nama_kapten_pilot' => Crypt::decrypt($dataPengajuanFsc->nama_kapten_pilot),
            'awak_pesawat_lain' => Crypt::decrypt($dataPengajuanFsc->awak_pesawat_lain),
            'penumpang_barang' => Crypt::decrypt($dataPengajuanFsc->penumpang_barang),
            'jumlah_kursi' => Crypt::decrypt($dataPengajuanFsc->jumlah_kursi),
            'fa' => Crypt::decrypt($dataPengajuanFsc->fa),
            'catatan' => Crypt::decrypt($dataPengajuanFsc->catatan),
        ];

        // Generate PDF dari template
        $pdf = Pdf::loadView('pdf.surat-template', $data)
            ->setPaper('a4', 'portrait');

        // Simpan PDF ke dalam memori
        $output = $pdf->output();

        // Konversi file PDF menjadi string base64
        $base64Pdf = base64_encode($output);

        // Kirimkan base64 sebagai respons JSON
        return response()->json([
            'file_name' => $data['tanggal_terbang'] . '-' . $data['nama_perusahaan'] . '.pdf',
            'base64_pdf' => $base64Pdf,
        ]);
    }

    public function multipelApproveAndRejectPengajuanFa(Request $request)
    {
        try {
            // Validasi input
            $validateData = $request->validate([
                'pengajuan_ids' => 'required|array|min:1',
                'status' => 'required|in:0,1,2',
            ]);

            $jumlahPengajuanBerhasil = 0;
            $jumlahPengajuanGagal = 0;

            foreach ($request->pengajuan_ids as $pengajuanId) {
                // Cari pengajuan berdasarkan ID
                $pengajuanFsc = PengajuanFsc::find($pengajuanId);

                if ($pengajuanFsc) {
                    // Ubah status pengajuan
                    $pengajuanFsc->status = $request->status;
                    $pengajuanFsc->save();
                    $jumlahPengajuanBerhasil++;
                    // Notifikasi
                    NotificationHelper::broadcastNotification('mitra_intelud', 'Pengajuan Flight Approval anda telah diverifikasi, silahkan dicek.', $pengajuanFsc->fk_pic_perusahaan_nfc_id);
                } else {
                    $jumlahPengajuanGagal++;
                }
            }

            return response()->json([
                'id' => '1',
                'data' => [
                    'success' => $jumlahPengajuanBerhasil . ' pengajuan FA berhasil di ' . ($pengajuanFsc->status == '1' ? 'Approve' : 'Reject'),
                    'failed' => $jumlahPengajuanGagal . ' pengajuan tidak ditemukan.',
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'Terjadi kesalahan saat menyetujui pengajuan.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }


    public function multipelApproveAndRejectPengajuanNfa(Request $request)
    {
        try {
            // Validasi input
            $validateData = $request->validate([
                'pengajuan_ids' => 'required|array|min:1',
                'status' => 'required|in:0,1,2',
            ]);

            $jumlahPengajuanBerhasil = 0;
            $jumlahPengajuanGagal = 0;

            foreach ($request->pengajuan_ids as $pengajuanId) {
                // Cari pengajuan berdasarkan ID
                $pengajuanNfa = PengajuanNfa::find($pengajuanId);

                if ($pengajuanNfa) {
                    // Ubah status pengajuan
                    $pengajuanNfa->status = $request->status;
                    $pengajuanNfa->save();
                    $jumlahPengajuanBerhasil++;
                    // Notifikasi
                    NotificationHelper::broadcastNotification('mitra_intelud', 'Pengajuan Non Flight Approval anda telah diverifikasi, silahkan dicek.', $pengajuanNfa->fk_pic_perusahaan_nfa_id);
                } else {
                    $jumlahPengajuanGagal++;
                }
            }

            return response()->json([
                'id' => '1',
                'data' => [
                    'success' => $jumlahPengajuanBerhasil . ' pengajuan NFA berhasil di ' . ($pengajuanNfa->status == '1' ? 'Approve' : 'Reject'),
                    'failed' => $jumlahPengajuanGagal . ' pengajuan tidak ditemukan.',
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => 'Terjadi kesalahan saat menyetujui pengajuan.',
                'error' => $th->getMessage(),
            ], 400);
        }
    }

    public function generateSuratPdf($encryptedIdAsli)
    {
        $encryptedId = str_replace(['-', '_'], ['+', '/'], $encryptedIdAsli);
        $encryptedId = base64_decode($encryptedId); // Decode dari Base64

        // Membuat kunci dengan panjang 32 karakter
        $key = str_pad('thisisaverysecretkey', 32, ' '); // Padding key untuk mencapai panjang 32 karakter
        $iv = '1234567890123456'; // IV yang sama dengan yang digunakan di Flutter

        // Dekripsi menggunakan AES-256-CBC
        $decrypted = openssl_decrypt($encryptedId, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            return response()->json(['error' => 'Decryption failed']);
        }

        $dataPengajuanFsc = PengajuanFsc::find($decrypted);

        $data = [
            'encryptedId' => $encryptedIdAsli,
            'nama_perusahaan' => Crypt::decrypt($dataPengajuanFsc->nama_perusahaan),
            'operator' => Crypt::decrypt($dataPengajuanFsc->operator),
            'jenis' => Crypt::decrypt($dataPengajuanFsc->jenis),
            'no_registrasi' => Crypt::decrypt($dataPengajuanFsc->no_registrasi),

            'tanggal_terbang' => Crypt::decrypt($dataPengajuanFsc->tanggal_terbang),
            'tanggal_mendarat' => Crypt::decrypt($dataPengajuanFsc->tanggal_mendarat),
            'rute_penerbangan' => Crypt::decrypt($dataPengajuanFsc->rute_penerbangan),
            'lanud' => Crypt::decrypt($dataPengajuanFsc->lanud),
            'pendaratan_teknik' => Crypt::decrypt($dataPengajuanFsc->pendaratan_teknik),
            'pendaratan_niaga' => Crypt::decrypt($dataPengajuanFsc->pendaratan_niaga),

            'nama_kapten_pilot' => Crypt::decrypt($dataPengajuanFsc->nama_kapten_pilot),
            'awak_pesawat_lain' => Crypt::decrypt($dataPengajuanFsc->awak_pesawat_lain),
            'penumpang_barang' => Crypt::decrypt($dataPengajuanFsc->penumpang_barang),
            'jumlah_kursi' => Crypt::decrypt($dataPengajuanFsc->jumlah_kursi),
            'fa' => Crypt::decrypt($dataPengajuanFsc->fa),
            'catatan' => Crypt::decrypt($dataPengajuanFsc->catatan),
        ];

        // Load view dan kirim data
        $pdf = Pdf::loadView('pdf.surat-template', $data)
            ->setPaper('a4', 'portrait'); // Mengatur ukuran kertas menjadi A4 dan orientasi portrait
        $file_name = $data['tanggal_terbang'] . '-' . $data['nama_perusahaan'] . '.pdf';
        // Generate file PDF untuk di-download
        // return $pdf->download($file_name);
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $file_name, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $file_name . '"'
        ]);
    }

    public function generateSuratDuaPdf($id)
    {
        $dataPengajuanFsc = PengajuanFsc::find($id);

        $data = [
            'nama_perusahaan' => Crypt::decrypt($dataPengajuanFsc->nama_perusahaan),
            'operator' => Crypt::decrypt($dataPengajuanFsc->operator),
            'jenis' => Crypt::decrypt($dataPengajuanFsc->jenis),
            'no_registrasi' => Crypt::decrypt($dataPengajuanFsc->no_registrasi),

            'tanggal_terbang' => Crypt::decrypt($dataPengajuanFsc->tanggal_terbang),
            'tanggal_mendarat' => Crypt::decrypt($dataPengajuanFsc->tanggal_mendarat),
            'rute_penerbangan' => Crypt::decrypt($dataPengajuanFsc->rute_penerbangan),
            'lanud' => Crypt::decrypt($dataPengajuanFsc->lanud),
            'pendaratan_teknik' => Crypt::decrypt($dataPengajuanFsc->pendaratan_teknik),
            'pendaratan_niaga' => Crypt::decrypt($dataPengajuanFsc->pendaratan_niaga),

            'nama_kapten_pilot' => Crypt::decrypt($dataPengajuanFsc->nama_kapten_pilot),
            'awak_pesawat_lain' => Crypt::decrypt($dataPengajuanFsc->awak_pesawat_lain),
            'penumpang_barang' => Crypt::decrypt($dataPengajuanFsc->penumpang_barang),
            'jumlah_kursi' => Crypt::decrypt($dataPengajuanFsc->jumlah_kursi),
            'fa' => Crypt::decrypt($dataPengajuanFsc->fa),
            'catatan' => Crypt::decrypt($dataPengajuanFsc->catatan),
        ];

        // Load view dan kirim data
        $pdf = Pdf::loadView('pdf.surat-dua-template', $data)
            ->setPaper('folio', 'portrait'); // Mengatur ukuran kertas menjadi A4 dan orientasi portrait

        $file_name = $data['tanggal_terbang'] . '-' . $data['nama_perusahaan'] . '.pdf';
        // Generate file PDF untuk di-download
        return $pdf->download('surat.pdf');
    }

    public function multipelApprovePengajuan(Request $request)
    {
        $pengajuanIds = $request->input('pengajuan_ids');
    }

    public function approveAndGenerateSuratFa($id)
    {
        try {
            // Log: Mulai proses
            Log::info("Memulai proses generateSurat untuk ID: $id");

            // Ambil data pengajuan berdasarkan ID
            $dataPengajuanFsc = PengajuanFsc::findOrFail($id);
            Log::info("Data pengajuan berhasil ditemukan untuk ID: $id");

            // Dekripsi data pengajuan
            $data = [
                'nama_perusahaan' => Crypt::decrypt($dataPengajuanFsc->nama_perusahaan),
                'operator' => Crypt::decrypt($dataPengajuanFsc->operator),
                'jenis' => Crypt::decrypt($dataPengajuanFsc->jenis),
                'no_registrasi' => Crypt::decrypt($dataPengajuanFsc->no_registrasi),
                'tanggal_terbang' => Crypt::decrypt($dataPengajuanFsc->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($dataPengajuanFsc->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($dataPengajuanFsc->rute_penerbangan),
                'lanud' => Crypt::decrypt($dataPengajuanFsc->lanud),
                'pendaratan_teknik' => Crypt::decrypt($dataPengajuanFsc->pendaratan_teknik),
                'pendaratan_niaga' => Crypt::decrypt($dataPengajuanFsc->pendaratan_niaga),
                'nama_kapten_pilot' => Crypt::decrypt($dataPengajuanFsc->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($dataPengajuanFsc->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($dataPengajuanFsc->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($dataPengajuanFsc->jumlah_kursi),
                'fa' => Crypt::decrypt($dataPengajuanFsc->fa),
                'catatan' => Crypt::decrypt($dataPengajuanFsc->catatan),
            ];
            Log::info("Data pengajuan berhasil didecrypt untuk ID: $id");

            // Generate PDF menggunakan view
            $pdf = Pdf::loadView('pdf.surat-template', $data)
                ->setPaper('a4', 'portrait');
            Log::info("PDF berhasil di-generate untuk ID: $id");

            // Format nama file dengan mengganti spasi dan karakter khusus
            $fileName = str_replace([' ', ':'], '_', $data['tanggal_terbang']) . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $data['nama_perusahaan']) . '.pdf';

            // Pastikan direktori tujuan ada
            $directoryPath = storage_path('app/public/generated_pdfs');
            if (!file_exists($directoryPath)) {
                Log::info("Direktori $directoryPath tidak ditemukan, membuat direktori...");
                mkdir($directoryPath, 0755, true); // Buat direktori jika belum ada
                Log::info("Direktori $directoryPath berhasil dibuat.");
            }

            // Cek apakah file sudah ada, jika ya, hapus file lama
            $filePath = $directoryPath . '/' . $fileName;
            if (file_exists($filePath)) {
                Log::info("File sudah ada, menghapus file lama...");
                unlink($filePath);
                Log::info("File lama berhasil dihapus.");
            }

            // Simpan file PDF ke storage
            $pdf->save($filePath);
            Log::info("PDF berhasil disimpan di path: $filePath");

            // Buat link untuk diakses dari storage
            $downloadLink = asset('storage/generated_pdfs/' . $fileName);

            Log::info("Proses selesai untuk ID: $id, file dapat diakses di: $downloadLink");

            $dataPengajuanFsc->file = $fileName;
            $dataPengajuanFsc->status = '1';
            $dataPengajuanFsc->save();

            // Response API yang rapi
            return response()->json([
                'id' => '1',
                'data' => 'PDF berhasil dibuat dan disimpan.',
            ], 200);  // Send the response explicitly

        } catch (\Exception $e) {
            // Handle error
            Log::error("Error saat membuat PDF untuk ID: $id - " . $e->getMessage());

            // Response error API yang lebih informatif
            return response()->json([
                'id' => '0',
                'data' => 'Terjadi kesalahan saat membuat PDF.',
                'error' => $e->getMessage(),
            ], 500)->send();  // Send the response explicitly
        }
    }

    public function approveAndGenerateSuratNfa($id)
    {
        try {
            // Log: Mulai proses
            Log::info("Memulai proses generateSurat untuk ID: $id");

            // Ambil data pengajuan berdasarkan ID
            $dataPengajuanNfa = PengajuanNfa::findOrFail($id);
            Log::info("Data pengajuan berhasil ditemukan untuk ID: $id");

            // Dekripsi data pengajuan
            $data = [
                'nama_perusahaan' => Crypt::decrypt($dataPengajuanNfa->nama_perusahaan),
                'operator' => Crypt::decrypt($dataPengajuanNfa->operator),
                'jenis' => Crypt::decrypt($dataPengajuanNfa->jenis),
                'no_registrasi' => Crypt::decrypt($dataPengajuanNfa->no_registrasi),
                'tanggal_terbang' => Crypt::decrypt($dataPengajuanNfa->tanggal_terbang),
                'tanggal_mendarat' => Crypt::decrypt($dataPengajuanNfa->tanggal_mendarat),
                'rute_penerbangan' => Crypt::decrypt($dataPengajuanNfa->rute_penerbangan),
                'lanud' => Crypt::decrypt($dataPengajuanNfa->lanud),
                'pendaratan_teknik' => Crypt::decrypt($dataPengajuanNfa->pendaratan_teknik),
                'pendaratan_niaga' => Crypt::decrypt($dataPengajuanNfa->pendaratan_niaga),
                'nama_kapten_pilot' => Crypt::decrypt($dataPengajuanNfa->nama_kapten_pilot),
                'awak_pesawat_lain' => Crypt::decrypt($dataPengajuanNfa->awak_pesawat_lain),
                'penumpang_barang' => Crypt::decrypt($dataPengajuanNfa->penumpang_barang),
                'jumlah_kursi' => Crypt::decrypt($dataPengajuanNfa->jumlah_kursi),
                'fa' => Crypt::decrypt($dataPengajuanNfa->fa),
                'catatan' => Crypt::decrypt($dataPengajuanNfa->catatan),
            ];
            Log::info("Data pengajuan berhasil didecrypt untuk ID: $id");

            // Generate PDF menggunakan view
            $pdf = Pdf::loadView('pdf.surat-template', $data)
                ->setPaper('a4', 'portrait');
            Log::info("PDF berhasil di-generate untuk ID: $id");

            // Format nama file dengan mengganti spasi dan karakter khusus
            $fileName = str_replace([' ', ':'], '_', $data['tanggal_terbang']) . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $data['nama_perusahaan']) . '.pdf';

            // Pastikan direktori tujuan ada
            $directoryPath = storage_path('app/public/generated_pdfs');
            if (!file_exists($directoryPath)) {
                Log::info("Direktori $directoryPath tidak ditemukan, membuat direktori...");
                mkdir($directoryPath, 0755, true); // Buat direktori jika belum ada
                Log::info("Direktori $directoryPath berhasil dibuat.");
            }

            // Cek apakah file sudah ada, jika ya, hapus file lama
            $filePath = $directoryPath . '/' . $fileName;
            if (file_exists($filePath)) {
                Log::info("File sudah ada, menghapus file lama...");
                unlink($filePath);
                Log::info("File lama berhasil dihapus.");
            }

            // Simpan file PDF ke storage
            $pdf->save($filePath);
            Log::info("PDF berhasil disimpan di path: $filePath");

            // Buat link untuk diakses dari storage
            $downloadLink = asset('storage/generated_pdfs/' . $fileName);

            Log::info("Proses selesai untuk ID: $id, file dapat diakses di: $downloadLink");

            $dataPengajuanNfa->file = $fileName;
            $dataPengajuanNfa->status = '1';
            $dataPengajuanNfa->save();

            // Response API yang rapi
            return response()->json([
                'id' => '1',
                'data' => 'PDF berhasil dibuat dan disimpan.',
            ], 200);  // Send the response explicitly

        } catch (\Exception $e) {
            // Handle error
            Log::error("Error saat membuat PDF untuk ID: $id - " . $e->getMessage());

            // Response error API yang lebih informatif
            return response()->json([
                'id' => '0',
                'data' => 'Terjadi kesalahan saat membuat PDF.',
                'error' => $e->getMessage(),
            ], 500)->send();  // Send the response explicitly
        }
    }

    public function previewFile($id)
    {
        try {
            // Log: Mulai proses
            Log::info("Memulai proses previewFile untuk ID: $id");

            // Ambil data pengajuan berdasarkan ID
            $dataPengajuanFsc = PengajuanFsc::findOrFail($id);
            Log::info("Data pengajuan berhasil ditemukan untuk ID: $id");

            // Cek apakah file sudah ada
            $fileName = $dataPengajuanFsc->file;
            if (!$fileName || !Storage::exists('public/generated_pdfs/' . $fileName)) {
                Log::error("File tidak ditemukan untuk ID: $id");
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan.',
                ], 404);
            }

            // Generate URL file untuk preview
            $fileUrl = asset('storage/generated_pdfs/' . $fileName);

            Log::info("File berhasil ditemukan, dapat diakses di: $fileUrl");

            // Response API untuk file preview
            return response()->json([
                'success' => true,
                'message' => 'File berhasil ditemukan.',
                'data' => [
                    'file_name' => $fileName,
                    'file_url' => $fileUrl,
                ]
            ], 200);
        } catch (\Exception $e) {
            // Handle error
            Log::error("Error saat preview file untuk ID: $id - " . $e->getMessage());

            // Response error API yang lebih informatif
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat preview file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function sendNotification(Request $request)
    // {
    //     $message = $request->input('message');
    //     event(new \App\Events\NewNotification($message));

    //     return response()->json(['success' => true, 'message' => 'Notification sent']);
    // }

    // public function sendNotification(Request $request)
    // {
    //     $request->validate([
    //         'userId' => 'required',
    //         'message' => 'required',
    //     ]);

    //     // event(new UserRegistered($request->message));
    //     NotificationHelper::broadcastNotification('mitra_litpers', $request->message, $request->userId);

    //     return response()->json(['status' => 'Notification sent successfully']);
    // }
}
