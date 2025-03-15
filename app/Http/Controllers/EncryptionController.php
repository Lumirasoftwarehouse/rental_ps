<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class EncryptionController extends Controller
{
    /**
     * API untuk mengenkripsi data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function encryptData(Request $request)
    {
        // Validasi input
        $request->validate([
            'data' => 'required|string',
        ]);

        try {
            // Enkripsi data dari input
            $encrypted = Crypt::encrypt($request->input('data'));

            // Return hasil enkripsi
            return response()->json([
                'status' => 'success',
                'encrypted_data' => $encrypted,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Encrypt Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error encrypting data',
            ], 500);
        }
    }

    /**
     * API untuk mendekripsi data.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function decryptData(Request $request)
    {
        // Validasi input
        $request->validate([
            'encrypted_data' => 'required|string',
        ]);

        try {
            // Dekripsi data dari input
            $decrypted = Crypt::decrypt($request->input('encrypted_data'));

            // Return hasil dekripsi
            return response()->json([
                'status' => 'success',
                'decrypted_data' => $decrypted,
            ], 200);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            Log::error('Decrypt Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Error decrypting data. Invalid encrypted data.',
            ], 400);
        }
    }
}
