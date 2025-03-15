<?php

namespace App\Http\Controllers\Litpers;

use Illuminate\Http\Request;
use App\Services\Litpers\JenisSkhppService;
use App\Services\Litpers\PenerbitanBaruService;
use App\Services\Litpers\PergantianDireksiService;
use App\Services\Litpers\PerpanjanganService;
use Illuminate\Support\Facades\Storage;

class JenisSkhppController extends Controller
{
    private $jenisSkhppService;
    private $penerbitanBaruService;
    private $pergantianDireksiService;
    private $perpanjanganService;

    public function __construct(JenisSkhppService $jenisSkhppService, PenerbitanBaruService $penerbitanBaruService, PergantianDireksiService $pergantianDireksiService, PerpanjanganService $perpanjanganService)
    {
        $this->jenisSkhppService = $jenisSkhppService;
        $this->penerbitanBaruService = $penerbitanBaruService;
        $this->pergantianDireksiService = $pergantianDireksiService;
        $this->perpanjanganService = $perpanjanganService;
    }

    public function jumlahDataSkhpp()
    {
        try {
            $result = $this->jenisSkhppService->jumlahDataSkhpp();
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

    public function listDataAllSkhpp()
    {
        try {
            $result = $this->jenisSkhppService->listDataAllSkhpp();
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

    public function listDataAllSkhppByMitra($id)
    {
        try {
            $result = $this->jenisSkhppService->listDataAllSkhppByMitra($id);
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

    public function deleteDataSkhpp(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jenis_skhpp' => 'required|string|max:255',
                'jenis_form' => 'required|string|max:255',
            ]);

            if ($validateData['jenis_skhpp'] == 'penerbitan' && $validateData['jenis_form'] == '1') {
                $result = $this->penerbitanBaruService->deleteDataFormSatuPenerbitanBaru($id);
            } elseif ($validateData['jenis_skhpp'] == 'penerbitan' && $validateData['jenis_form'] == '2') {
                $result = $this->penerbitanBaruService->deleteDataFormDuaPenerbitanBaru($id);
            } elseif ($validateData['jenis_skhpp'] == 'penerbitan' && $validateData['jenis_form'] == '3') {
                $result = $this->penerbitanBaruService->deleteDataFormTigaPenerbitanBaru($id);
            } elseif ($validateData['jenis_skhpp'] == 'penerbitan' && $validateData['jenis_form'] == '4') {
                $result = $this->penerbitanBaruService->deleteDataFormEmpatPenerbitanBaru($id);
            } elseif ($validateData['jenis_skhpp'] == 'penerbitan' && $validateData['jenis_form'] == '5') {
                $result = $this->penerbitanBaruService->deleteDataFormLimaPenerbitanBaru($id);
            } elseif ($validateData['jenis_skhpp'] == 'pergantian' && $validateData['jenis_form'] == '1') {
                $result = $this->pergantianDireksiService->deleteDataFormSatuPergantianDireksi($id);
            } elseif ($validateData['jenis_skhpp'] == 'pergantian' && $validateData['jenis_form'] == '2') {
                $result = $this->pergantianDireksiService->deleteDataFormDuaPergantianDireksi($id);
            } elseif ($validateData['jenis_skhpp'] == 'pergantian' && $validateData['jenis_form'] == '3') {
                $result = $this->pergantianDireksiService->deleteDataFormTigaPergantianDireksi($id);
            } elseif ($validateData['jenis_skhpp'] == 'pergantian' && $validateData['jenis_form'] == '4') {
                $result = $this->pergantianDireksiService->deleteDataFormEmpatPergantianDireksi($id);
            } elseif ($validateData['jenis_skhpp'] == 'pergantian' && $validateData['jenis_form'] == '5') {
                $result = $this->pergantianDireksiService->deleteDataFormLimaPergantianDireksi($id);
            } elseif ($validateData['jenis_skhpp'] == 'perpanjangan' && $validateData['jenis_form'] == '1') {
                $result = $this->perpanjanganService->deleteDataFormSatuPerpanjangan($id);
            } elseif ($validateData['jenis_skhpp'] == 'perpanjangan' && $validateData['jenis_form'] == '2') {
                $result = $this->perpanjanganService->deleteDataFormDuaPerpanjangan($id);
            } elseif ($validateData['jenis_skhpp'] == 'perpanjangan' && $validateData['jenis_form'] == '3') {
                $result = $this->perpanjanganService->deleteDataFormTigaPerpanjangan($id);
            } elseif ($validateData['jenis_skhpp'] == 'perpanjangan' && $validateData['jenis_form'] == '4') {
                $result = $this->perpanjanganService->deleteDataFormEmpatPerpanjangan($id);
            } else {
                return response()->json(
                    [
                        'id' => '0',
                        'message' => 'jenis skhpp dan jenis form tidak sesuai'
                    ],
                    401
                );
            }
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

    public function listDataJenisSkhpp()
    {
        try {
            $result = $this->jenisSkhppService->listDataJenisSkhpp();
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

    public function detailDataJenisSkhpp($id)
    {
        try {

            $result = $this->jenisSkhppService->detailDataJenisSkhpp($id);
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

    public function inputDataJenisSkhpp(Request $request)
    {
        try {
            $validateData = $request->validate([
                'jenis_skhpp' => 'required|string|max:255',
            ]);

            $result = $this->jenisSkhppService->inputDataJenisSkhpp($validateData);
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

    public function updateDataJenisSkhpp(Request $request, $id)
    {
        try {
            $validateData = $request->validate([
                'jenis_skhpp' => 'required|string|max:255',
            ]);

            $result = $this->jenisSkhppService->updateDataJenisSkhpp($validateData, $id);
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

    public function deleteDataJenisSkhpp($id)
    {
        try {
            $result = $this->jenisSkhppService->deleteDataJenisSkhpp($id);
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
}
