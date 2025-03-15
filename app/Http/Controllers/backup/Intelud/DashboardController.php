<?php

namespace App\Http\Controllers\Intelud;

use App\Helpers\NotificationHelper;
use Illuminate\Http\Request;
use App\Services\Intelud\DashboardService;


class DashboardController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboardAdmin()
    {
        try {
            return $this->handleRequest(function () {
                $result = $this->dashboardService->dashboardAdmin();
                return response()->json(
                    [
                        'id' => $result['id'],
                        'message' => $result['message'],
                        'data' => $result['data']
                    ],
                    $result['statusCode']
                );
            });
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

    public function dashboardPic()
    {
        try {
            return $this->handleRequest(function () {
                $result = $this->dashboardService->dashboardPic();
                return response()->json(
                    [
                        'id' => $result['id'],
                        'message' => $result['message'],
                        'data' => $result['data']
                    ],
                    $result['statusCode']
                );
            });
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

    public function dashboardMitra()
    {
        try {
            return $this->handleRequest(function () {
                $result = $this->dashboardService->dashboardMitra();
                return response()->json(
                    [
                        'id' => $result['id'],
                        'message' => $result['message'],
                        'data' => $result['data']
                    ],
                    $result['statusCode']
                );
            });
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

    public function dashboardKaintel()
    {
        try {
            return $this->handleRequest(function () {
                $result = $this->dashboardService->dashboardKaintel();
                return response()->json(
                    [
                        'id' => $result['id'],
                        'message' => $result['message'],
                        'data' => $result['data']
                    ],
                    $result['statusCode']
                );
            });
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

    private function handleRequest(callable $callback)
    {
        try {
            return $callback();
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'message' => 'Request gagal: ' . $th->getMessage(),
                'data' => []
            ], 400); // Menggunakan 400 Bad Request
        }
    }
}
