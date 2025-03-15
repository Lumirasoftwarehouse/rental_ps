<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanFsc;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Crypt;

class BarcodeController extends Controller
{
    public function sendNotification(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        event(new UserRegistered($request->message));

        return response()->json(['status' => 'Notification sent successfully']);
    }

    public function fscPreview($encryptedIdAsli)
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
        // Ambil data berdasarkan ID 1
        $dataEncrypt = PengajuanFsc::find($decrypted);

        if (!$dataEncrypt) {
            return abort(404, 'Data not found');
        }

        try {
            $data = [
                'operator' => Crypt::decrypt($dataEncrypt->operator ?? ''),
                'jenis' => Crypt::decrypt($dataEncrypt->jenis ?? ''),
                'no_registrasi' => Crypt::decrypt($dataEncrypt->no_registrasi ?? ''),
                'rute_penerbangan' => Crypt::decrypt($dataEncrypt->rute_penerbangan ?? ''),
                'nama_kapten_pilot' => Crypt::decrypt($dataEncrypt->nama_kapten_pilot ?? ''),
            ];
        } catch (\Exception $e) {
            return abort(500, 'Failed to decrypt data');
        }

        // Kirim data ke view
        return view('fsc', compact('data'));
    }
}
