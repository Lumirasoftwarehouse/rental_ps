<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\AdminInteludFsc;
use App\Models\AdminLanud;
use App\Models\AdminLitpers;
use App\Models\AdminPamsut;
use App\Models\PicInteludFsc;
use App\Models\PicPerusahaanFsc;
use App\Models\PicPerusahaanLitpers;
use App\Models\DocumentUser;
use App\Models\KaintelLanud;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class Google2FAController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a secret and barcode for Google Authenticator
     */
    public function generateSecret($email)
    {
        // Generate a new secret
        $secret = $this->google2fa->generateSecretKey();
        $google2fa = app('pragmarx.google2fa');
        // Generate the QR code URL
        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'), // Application name
            $email,
            $secret
        );

        $user = User::where('email', $email)->first();
        $user->google2fa_secret = $secret;
        $user->save();

        return [
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
        ];
    }

    public function generate(Request $request)
    {
        // Generate a new secret
        $secret = $this->google2fa->generateSecretKey();
        $google2fa = app('pragmarx.google2fa');
        // Generate the QR code URL
        $qrCodeUrl = $google2fa->getQRCodeInline(
            config('app.name'), // Application name
            'test@test.com',
            $secret
        );
        return [
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
        ];
    }

    /**
     * Verify the code from Google Authenticator
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            // 'secret' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        $secret = auth()->user()->google2fa_secret;
        $code = $request->input('code');

        // Verify the code
        $isValid = $this->google2fa->verifyKey($secret, $code);

        if ($isValid) {
            $user = User::where('email', auth()->user()->email)->first();
            $user->google2fa_expired_at = now()->addMinutes(60);
            $user->save();
            $customClaims = [
                'id' => $user->id,
                'name' => $user->name,
                // 'role' => $this->checkRole($user->id),
                // "documents_mitra_intelud" => $this->checkDocumentMitraIntelud($user->id),
            ];

            // Create the token with claims
            $tokenWithClaims = JWTAuth::claims($customClaims)->fromUser($user);
            return Response::json([
                'id' => '1',
                'access_token' => $tokenWithClaims,
                'sub' => auth()->user()->id,
                'name' => auth()->user()->name,
                'status' => auth()->user()->status,
                'data' => 'The code is valid.',
                'role' => $this->checkRole(auth()->user()->id)
            ]);
        }

        return Response::json([
            'id' => '0',
            'data' => 'Invalid code.',
            'role' => $this->checkRole(auth()->user()->id)
        ], 422);
    }

    private function checkRole($idUser)
    {
        if (AdminInteludFsc::where('user_id', $idUser)->exists() && User::where('level', '1')->exists()) {
            return "admin_intelud";
        } elseif (PicInteludFsc::where('user_id', $idUser)->exists() && User::where('level', '2')->exists()) {
            return "pic_intelud";
        } elseif (PicPerusahaanFsc::where('user_id', $idUser)->exists() && User::where('level', '4')->exists()) {
            return "mitra_intelud";
        } elseif (KaintelLanud::where('user_id', $idUser)->exists() && User::where('level', '3')->exists()) {
            return "kaintelud_lanud";
        } elseif (AdminPamsut::where('user_id', $idUser)->exists() && User::where('level', '2')->exists()) {
            return "admin_pamsut";
        } elseif (AdminLanud::where('user_id', $idUser)->exists() && User::where('level', '3')->exists()) {
            return "admin_lanud";
        } elseif (AdminLitpers::where('user_id', $idUser)->exists() && User::where('level', '1')->exists()) {
            return "admin_litpers";
        } elseif (PicPerusahaanLitpers::where('user_id', $idUser)->exists() && User::where('level', '4')->exists()) {
            return "mitra_litpers";
        } elseif (User::where('id', $idUser)->where('level', '0')->exists()) {
            return "superadmin";
        } else {
            return "user";
        }
    }
}
