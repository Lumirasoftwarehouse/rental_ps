<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

class UploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|file|max:2048', // Max 2MB untuk contoh
        ]);

        // Ambil file dari request
        $file = $request->file('file');

        // Baca konten file sebagai string
        $fileContent = file_get_contents($file);

        // Enkripsi konten file
        $encryptedContent = Crypt::encrypt($fileContent);

        // Simpan file yang sudah terenkripsi di storage (di folder 'uploads')
        $fileName = time() . '_' . $file->getClientOriginalName();
        Storage::put('uploads/' . $fileName, $encryptedContent);

        return response()->json(['message' => 'File berhasil diunggah dan terenkripsi!', 'file' => $fileName]);
    }

    public function previewFile($fileName)
    {
        // Cek apakah file ada di storage
        if (Storage::exists('uploads/' . $fileName)) {
            // Baca konten terenkripsi dari file
            $encryptedContent = Storage::get('uploads/' . $fileName);

            // Dekripsi konten file
            $decryptedContent = Crypt::decrypt($encryptedContent);

            // Kirim file yang sudah didekripsi sebagai respons untuk preview
            return response($decryptedContent)
                ->header('Content-Type', 'application/pdf') // Sesuaikan dengan tipe file
                ->header('Content-Disposition', 'inline; filename="'.$fileName.'"');
        }

        return response()->json(['message' => 'File tidak ditemukan!'], 404);
    }


    public function downloadFile($fileName)
    {
        // Cek apakah file ada di storage
        if (Storage::exists('uploads/' . $fileName)) {
            // Baca konten terenkripsi dari file
            $encryptedContent = Storage::get('uploads/' . $fileName);

            // Dekripsi konten file
            $decryptedContent = Crypt::decrypt($encryptedContent);

            // Kirim file ke pengguna (sebagai download)
            return response($decryptedContent)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="'.$fileName.'"');
        }

        return response()->json(['message' => 'File tidak ditemukan!'], 404);
    }
}
