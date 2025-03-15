<?php

namespace App\Http\Controllers\Pamsut;

use Illuminate\Http\Request;
use App\Services\Pamsut\DashboardService;

class DashboardController extends Controller
{
    private $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function listDataDashboardAdminPamsut()
    {
        try {
            $result = $this->dashboardService->listDataDashboardAdminPamsut();
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

    // public function listDataDashboardAdminLitpers()
    // {
    //     try {
    //         $result = $this->dashboardService->listDataDashboardAdminLitpers();
    //         return response()->json(
    //             [
    //                 'id' => $result['id'],
    //                 'message' => $result['message'],
    //                 'data' => $result['data']
    //             ],
    //             $result['statusCode']
    //         );
    //     } catch (\Throwable $th) {
    //         return response()->json(
    //             [
    //                 'id' => '0',
    //                 'message' => $th->getMessage(),
    //                 'data' => []
    //             ],
    //             401
    //         );
    //     }
    // }

    public function listAvailableYearsAdminPamsut()
    {
        try {
            $result = $this->dashboardService->listAvailableYearsAdminPamsut();
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

    public function listLineChartDailyAdminPamsut()
    {
        try {
            $result = $this->dashboardService->listLineChartDailyAdminPamsut();
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

    public function listLineChartMonthlyAdminPamsut()
    {
        try {
            $result = $this->dashboardService->listLineChartMonthlyAdminPamsut();
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

    public function listLineChartYearlyAdminPamsut()
    {
        try {
            $result = $this->dashboardService->listLineChartYearlyAdminPamsut();
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

    public function listLineChartJenisKasusAdminPamsut(Request $request)
    {
        try {
            $selectedJenisKasus = $request->jenis_kasus;

            $result = $this->dashboardService->listLineChartJenisKasusAdminPamsut($selectedJenisKasus);

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

    public function listLineChartTriwulanAdminPamsut(Request $request)
    {
        try {
            $selectedTriwulan = $request->triwulan;
            $selectedTahun = $request->tahun;

            $result = $this->dashboardService->listLineChartTriwulanAdminPamsut($selectedTriwulan, $selectedTahun);

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

    public function listPieChartWeeklyAdminPamsut()
    {
        try {
            $result = $this->dashboardService->listPieChartWeeklyAdminPamsut();
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

    public function listPieChartMonthlyAdminPamsut()
    {
        try {
            $result = $this->dashboardService->listPieChartMonthlyAdminPamsut();
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

    public function listPieChartYearlyAdminPamsut()
    {
        try {
            $result = $this->dashboardService->listPieChartYearlyAdminPamsut();
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

    public function listPieChartJenisKasusAdminPamsut(Request $request)
    {
        try {
            $selectedJenisKasus = $request->jenis_kasus;

            $result = $this->dashboardService->listPieChartJenisKasusAdminPamsut($selectedJenisKasus);

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

    public function listPieChartTriwulanAdminPamsut(Request $request)
    {
        try {
            $selectedTriwulan = $request->triwulan;
            $selectedTahun = $request->tahun;

            $result = $this->dashboardService->listPieChartTriwulanAdminPamsut($selectedTriwulan, $selectedTahun);

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

    public function listAvailableYearsAdminLanud($id)
    {
        try {
            $result = $this->dashboardService->listAvailableYearsAdminLanud($id);
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

    public function listDataDashboardAdminLanud($id)
    {
        try {
            $result = $this->dashboardService->listDataDashboardAdminLanud($id);
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

    public function listLineChartDailyAdminLanud($id)
    {
        try {
            $result = $this->dashboardService->listLineChartDailyAdminLanud($id);
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

    public function listLineChartMonthlyAdminLanud($id)
    {
        try {
            $result = $this->dashboardService->listLineChartMonthlyAdminLanud($id);
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

    public function listLineChartYearlyAdminLanud($id)
    {
        try {
            $result = $this->dashboardService->listLineChartYearlyAdminLanud($id);
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

    public function listLineChartJenisKasusAdminLanud($id, Request $request)
    {
        try {
            $selectedJenisKasus = $request->jenis_kasus;

            $result = $this->dashboardService->listLineChartJenisKasusAdminLanud($id, $selectedJenisKasus);

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

    public function listLineChartTriwulanAdminLanud($id, Request $request)
    {
        try {
            $selectedTriwulan = $request->triwulan;
            $selectedTahun = $request->tahun;

            $result = $this->dashboardService->listLineChartTriwulanAdminLanud($id, $selectedTriwulan, $selectedTahun);

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

    public function listPieChartWeeklyAdminLanud($id)
    {
        try {
            $result = $this->dashboardService->listPieChartWeeklyAdminLanud($id);
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

    public function listPieChartMonthlyAdminLanud($id)
    {
        try {
            $result = $this->dashboardService->listPieChartMonthlyAdminLanud($id);
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

    public function listPieChartYearlyAdminLanud($id)
    {
        try {
            $result = $this->dashboardService->listPieChartYearlyAdminLanud($id);
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

    public function listPieChartJenisKasusAdminLanud($id, Request $request)
    {
        try {
            $selectedJenisKasus = $request->jenis_kasus;

            $result = $this->dashboardService->listPieChartJenisKasusAdminLanud($id, $selectedJenisKasus);

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

    public function listPieChartTriwulanAdminLanud($id, Request $request)
    {
        try {
            $selectedTriwulan = $request->triwulan;
            $selectedTahun = $request->tahun;

            $result = $this->dashboardService->listPieChartTriwulanAdminLanud($id, $selectedTriwulan, $selectedTahun);

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
}
