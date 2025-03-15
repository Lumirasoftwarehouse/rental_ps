<?php

namespace App\Repositories\Pamsut;

use App\Models\AdminLanud;
use App\Models\Catpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardRepository
{
    private $catpersModel;

    public function __construct(Catpers $catpersModel)
    {
        $this->catpersModel = $catpersModel;
    }

    public function listDataDashboardAdminPamsut()
    {
        try {
            $jumlah_catpers = Catpers::count();
            $jumlah_satuan = AdminLanud::count();

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "jumlah_catpers" => $jumlah_catpers,
                    "jumlah_satuan" => $jumlah_satuan
                ],
                "message" => 'get data dashboard success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    // public function listDataDashboardAdminLitpers()
    // {
    //     try {
    //         $jumlah_catpers = Catpers::count();
    //         // $jumlah_satuan = AdminLanud::count();

    //         return [
    //             "id" => '1',
    //             "statusCode" => 200,
    //             "data" => [
    //                 "jumlah_catpers" => $jumlah_catpers,
    //                 // "jumlah_satuan" => $jumlah_satuan
    //             ],
    //             "message" => 'get data dashboard success'
    //         ];
    //     } catch (\Exception $e) {
    //         return [
    //             "id" => '0',
    //             "statusCode" => 401,
    //             "data" => [],
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function listAvailableYearsAdminPamsut()
    {
        try {
            $years = Catpers::selectRaw('YEAR(tanggal) as year')
                ->distinct()
                ->pluck('year');

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => $years,
                "message" => 'Get available years success',
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage(),
            ];
        }
    }

    public function listLineChartDailyAdminPamsut()
    {
        try {
            // Get the current date, start of the week (Monday) and end of the week (Sunday)
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
            $currentYear = Carbon::now()->year;

            // Fetch data for the current week, grouping by day and jenis_kasus name
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->whereBetween('catpers.tanggal', [$startOfWeek, $endOfWeek])
                ->selectRaw('DAYOFWEEK(catpers.tanggal) as day_of_week, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->groupBy('day_of_week', 'jenis_kasuses.jenis_kasus')
                ->orderBy('day_of_week')
                ->get();

            // Convert day_of_week to actual day names (Monday-Sunday)
            $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $formattedData = $data->map(function ($item) use ($dayNames) {
                return [
                    'day' => $dayNames[$item->day_of_week - 1], // DAYOFWEEK returns 1 for Sunday, so we map it manually
                    'jenis_kasus' => $item->jenis_kasus,
                    'count' => $item->count
                ];
            });

            if ($formattedData->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $formattedData,
                    "message" => 'Get data line chart daily success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'Data line chart daily not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listLineChartMonthlyAdminPamsut()
    {
        try {
            // Get the current year
            $year = Carbon::now()->year;

            // Fetch data for the current year, grouping by month and jenis_kasus name
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->whereYear('catpers.tanggal', $year)  // Filter by current year
                ->selectRaw('MONTH(catpers.tanggal) as month, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->groupBy('month', 'jenis_kasuses.jenis_kasus')
                ->orderBy('month')
                ->get();

            // Map numeric month to actual month names
            $monthNames = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December'
            ];

            $formattedData = $data->map(function ($item) use ($monthNames) {
                return [
                    'month' => $monthNames[$item->month], // Convert numeric month to month name
                    'jenis_kasus' => $item->jenis_kasus,
                    'count' => $item->count
                ];
            });

            if ($formattedData->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $formattedData,
                    "message" => 'Get data line chart monthly success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'Data line chart monthly not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listLineChartYearlyAdminPamsut()
    {
        try {
            // Get distinct years from the database
            $years = Catpers::selectRaw('YEAR(tanggal) as year')
                ->distinct()
                ->pluck('year');

            $currentYear = Carbon::now()->year;
            $nextYears = range($currentYear, $currentYear + 4); // Generate next 4 years

            // Determine the years to use
            if ($years->isEmpty() || ($years->count() === 1 && $years->first() === $currentYear)) {
                $years = collect($nextYears); // No years found or only current year, use next 5 years
            } else {
                $years = $years->sort()->unique(); // Sort and get unique years
            }

            // Fetch data for each year and jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->whereIn(DB::raw('YEAR(catpers.tanggal)'), $years) // Filter by years determined above
                ->selectRaw('YEAR(catpers.tanggal) as year, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->groupBy(DB::raw('YEAR(catpers.tanggal)'), 'jenis_kasuses.jenis_kasus')
                ->orderBy(DB::raw('YEAR(catpers.tanggal)'))
                ->get();

            // Prepare the response
            $formattedData = [];
            $jenisKasusList = $data->pluck('jenis_kasus')->unique(); // Get unique jenis_kasus

            foreach ($years as $year) {
                foreach ($jenisKasusList as $jenis_kasus) {
                    // Check if the data exists for the current year and jenis_kasus
                    $item = $data->firstWhere(function ($value) use ($year, $jenis_kasus) {
                        return $value->year == $year && $value->jenis_kasus == $jenis_kasus;
                    });

                    if ($item) {
                        // If data exists, use its count
                        $formattedData[] = [
                            'year' => $year,
                            'jenis_kasus' => $item->jenis_kasus,
                            'count' => $item->count,
                        ];
                    } else {
                        // If no data exists, set count to 0
                        $formattedData[] = [
                            'year' => $year,
                            'jenis_kasus' => $jenis_kasus,
                            'count' => 0,
                        ];
                    }
                }
            }

            return response()->json([
                "id" => '1',
                "statusCode" => 200,
                "data" => $formattedData,
                "message" => 'Get data line chart yearly success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listLineChartJenisKasusAdminPamsut($selectedJenisKasus)
    {
        try {
            // Get distinct years from the database
            $years = Catpers::selectRaw('YEAR(tanggal) as year')
                ->distinct()
                ->pluck('year');

            $currentYear = Carbon::now()->year;
            $nextYears = range($currentYear, $currentYear + 4); // Generate next 4 years

            // Determine the years to use
            if ($years->isEmpty() || ($years->count() === 1 && $years->first() === $currentYear)) {
                $years = collect($nextYears); // No years found or only current year, use next 5 years
            } else {
                $years = $years->sort()->unique(); // Sort and get unique years
            }

            // Fetch data for each year and jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('YEAR(tanggal) as year, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereIn(DB::raw('YEAR(tanggal)'), $years) // Filter by years determined above
                ->when($selectedJenisKasus, function ($query) use ($selectedJenisKasus) {
                    return $query->where('catpers_jenis_kasuses.jenis_kasus_id', $selectedJenisKasus); // Filter by selected jenis_kasus_id
                })
                ->groupBy(DB::raw('YEAR(tanggal)'), 'jenis_kasuses.jenis_kasus')
                ->orderBy(DB::raw('YEAR(tanggal)'))
                ->get();

            // Prepare the response
            $formattedData = [];
            $jenisKasusList = $data->pluck('jenis_kasus')->unique(); // Get unique jenis_kasus

            foreach ($years as $year) {
                foreach ($jenisKasusList as $jenis_kasus) {
                    // Check if the data exists for the current year and jenis_kasus
                    $item = $data->firstWhere(function ($value) use ($year, $jenis_kasus) {
                        return $value->year == $year && $value->jenis_kasus == $jenis_kasus;
                    });

                    if ($item) {
                        // If data exists, use its count
                        $formattedData[] = [
                            'year' => $year,
                            'jenis_kasus' => $item->jenis_kasus,
                            'count' => $item->count,
                        ];
                    } else {
                        // If no data exists, set count to 0
                        $formattedData[] = [
                            'year' => $year,
                            'jenis_kasus' => $jenis_kasus,
                            'count' => 0,
                        ];
                    }
                }
            }

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => $formattedData,
                "message" => 'Get data line chart jenis kasus success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartTriwulanAdminPamsut($selectedTriwulan, $selectedTahun)
    {
        try {
            $quarterMonths = [
                'triwulan_1' => [1, 2, 3],
                'triwulan_2' => [4, 5, 6],
                'triwulan_3' => [7, 8, 9],
                'triwulan_4' => [10, 11, 12],
            ];

            $months = $quarterMonths[$selectedTriwulan] ?? [];
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('MONTH(tanggal) as month, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereYear('tanggal', $selectedTahun)
                ->whereIn(DB::raw('MONTH(tanggal)'), $months)
                ->groupBy(DB::raw('MONTH(tanggal)'), 'jenis_kasuses.jenis_kasus')
                ->orderBy(DB::raw('MONTH(tanggal)'))
                ->get();

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => $data,
                "message" => 'Get line chart data success',
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage(),
            ];
        }
    }

    public function listPieChartWeeklyAdminPamsut()
    {
        try {
            // Get the current date, start of the week (Monday) and end of the week (Sunday)
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
            $currentYear = Carbon::now()->year;

            // Fetch data for the current week, grouping by jenis_kasus name
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])  // Filter by current week
                ->whereYear('tanggal', $currentYear)
                ->groupBy('jenis_kasuses.jenis_kasus') // Group by jenis_kasus
                ->orderBy('jenis_kasuses.jenis_kasus') // Optional: Order by jenis_kasus
                ->get();

            if ($data->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $data,
                    "message" => 'Get data pie chart weekly success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'Data pie chart weekly not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listPieChartMonthlyAdminPamsut()
    {
        try {
            // Get the current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // Fetch data for the current month, grouping by jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereMonth('tanggal', $currentMonth) // Filter by current month
                ->whereYear('tanggal', $currentYear) // Filter by current year
                ->groupBy('jenis_kasuses.jenis_kasus')
                ->orderBy('count', 'desc')
                ->get();

            if ($data->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $data,
                    "message" => 'Get monthly kasus data success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No data found for the current month'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listPieChartYearlyAdminPamsut()
    {
        try {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Fetch data for the current year, grouping by jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereYear('tanggal', $currentYear) // Filter by current year
                ->groupBy('jenis_kasuses.jenis_kasus')
                ->orderBy('count', 'desc')
                ->get();

            if ($data->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $data,
                    "message" => 'Get yearly kasus data success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No data found for the current year'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listPieChartJenisKasusAdminPamsut($selectedJenisKasus)
    {
        try {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Fetch data for the current year, grouping by jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereYear('tanggal', $currentYear) // Filter by current year
                ->when($selectedJenisKasus, function ($query) use ($selectedJenisKasus) {
                    return $query->where('catpers_jenis_kasuses.jenis_kasus_id', $selectedJenisKasus); // Filter by selected jenis_kasus_id
                })
                ->groupBy('jenis_kasuses.jenis_kasus')
                ->orderBy('count', 'desc')
                ->get();

            if ($data->isNotEmpty()) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $data,
                    "message" => 'Get pie chart jenis kasus success'
                ];
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No data found for the pie chart jenis kasus'
                ]);
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartTriwulanAdminPamsut($selectedTriwulan, $selectedTahun)
    {
        try {
            // Define the months for each triwulan
            $quarterMonths = [
                'triwulan_1' => [1, 2, 3],
                'triwulan_2' => [4, 5, 6],
                'triwulan_3' => [7, 8, 9],
                'triwulan_4' => [10, 11, 12],
            ];

            // Get the months corresponding to the selected triwulan
            $months = $quarterMonths[$selectedTriwulan] ?? [];

            // Query the data
            $data = DB::table('catpers')
                ->selectRaw('MONTH(tanggal) as month, COUNT(*) as count')
                ->whereYear('tanggal', $selectedTahun)
                ->whereIn(DB::raw('MONTH(tanggal)'), $months)
                ->groupBy(DB::raw('MONTH(tanggal)'))
                ->orderBy(DB::raw('MONTH(tanggal)'))
                ->get();

            // Format the data for better usability
            $formattedData = [];
            foreach ($months as $month) {
                $item = $data->firstWhere('month', $month);
                $formattedData[] = [
                    'month' => $month,
                    'count' => $item->count ?? 0,
                ];
            }

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => $formattedData,
                "message" => 'Get pie chart data success',
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage(),
            ];
        }
    }

    public function listAvailableYearsAdminLanud($id)
    {
        try {
            $years = Catpers::selectRaw('YEAR(tanggal) as year')
                ->where('admin_lanud_id', $id)
                ->distinct()
                ->pluck('year');

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => $years,
                "message" => 'Get available years success',
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage(),
            ];
        }
    }

    public function listDataDashboardAdminLanud($id)
    {
        try {
            $jumlah_catpers = Catpers::where('admin_lanud_id', $id)->count();
            // $jumlah_satuan = AdminLanud::count();

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "jumlah_catpers" => $jumlah_catpers,
                    // "jumlah_satuan" => $jumlah_satuan
                ],
                "message" => 'get data catpers success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartDailyAdminLanud($id)
    {
        try {
            // Get the current date, start of the week (Monday) and end of the week (Sunday)
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
            $currentYear = Carbon::now()->year;

            // Fetch data for the current week, grouping by day and jenis_kasus name
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('DAYOFWEEK(tanggal) as day_of_week, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])  // Filter by current week
                ->whereYear('tanggal', $currentYear)
                ->where('admin_lanud_id', $id)
                ->groupBy('day_of_week', 'jenis_kasuses.jenis_kasus')
                ->orderBy('day_of_week')
                ->get();

            // Convert day_of_week to actual day names (Monday-Sunday)
            $dayNames = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $formattedData = $data->map(function ($item) use ($dayNames) {
                return [
                    'day' => $dayNames[$item->day_of_week - 1], // DAYOFWEEK returns 1 for Sunday, so we map it manually
                    'jenis_kasus' => $item->jenis_kasus,
                    'count' => $item->count
                ];
            });

            if ($formattedData->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $formattedData,
                    "message" => 'Get data line chart daily success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'Data line chart daily not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listLineChartMonthlyAdminLanud($id)
    {
        try {
            // Get the current year
            $year = Carbon::now()->year;

            // Fetch data for the current year, grouping by month and jenis_kasus name
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('MONTH(tanggal) as month, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereYear('tanggal', $year)  // Filter by current year
                ->where('admin_lanud_id', $id)
                ->groupBy('month', 'jenis_kasuses.jenis_kasus')
                ->orderBy('month')
                ->get();

            // Map numeric month to actual month names
            $monthNames = [
                1 => 'January',
                2 => 'February',
                3 => 'March',
                4 => 'April',
                5 => 'May',
                6 => 'June',
                7 => 'July',
                8 => 'August',
                9 => 'September',
                10 => 'October',
                11 => 'November',
                12 => 'December'
            ];

            $formattedData = $data->map(function ($item) use ($monthNames) {
                return [
                    'month' => $monthNames[$item->month], // Convert numeric month to month name
                    'jenis_kasus' => $item->jenis_kasus,
                    'count' => $item->count
                ];
            });

            if ($formattedData->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $formattedData,
                    "message" => 'Get data line chart monthly success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'Data line chart monthly not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listLineChartYearlyAdminLanud($id)
    {
        try {
            // Get distinct years from the database
            $years = Catpers::selectRaw('YEAR(tanggal) as year')
                ->distinct()
                ->pluck('year');

            $currentYear = Carbon::now()->year;
            $nextYears = range($currentYear, $currentYear + 4); // Generate next 4 years

            // Determine the years to use
            if ($years->isEmpty() || ($years->count() === 1 && $years->first() === $currentYear)) {
                $years = collect($nextYears); // No years found or only current year, use next 5 years
            } else {
                $years = $years->sort()->unique(); // Sort and get unique years
            }

            // Fetch data for each year and jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('YEAR(tanggal) as year, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereIn(DB::raw('YEAR(tanggal)'), $years) // Filter by years determined above
                ->where('admin_lanud_id', $id)
                ->groupBy(DB::raw('YEAR(tanggal)'), 'jenis_kasuses.jenis_kasus')
                ->orderBy(DB::raw('YEAR(tanggal)'))
                ->get();

            // Prepare the response
            $formattedData = [];
            $jenisKasusList = $data->pluck('jenis_kasus')->unique(); // Get unique jenis_kasus

            foreach ($years as $year) {
                foreach ($jenisKasusList as $jenis_kasus) {
                    // Check if the data exists for the current year and jenis_kasus
                    $item = $data->firstWhere(function ($value) use ($year, $jenis_kasus) {
                        return $value->year == $year && $value->jenis_kasus == $jenis_kasus;
                    });

                    if ($item) {
                        // If data exists, use its count
                        $formattedData[] = [
                            'year' => $year,
                            'jenis_kasus' => $item->jenis_kasus,
                            'count' => $item->count,
                        ];
                    } else {
                        // If no data exists, set count to 0
                        $formattedData[] = [
                            'year' => $year,
                            'jenis_kasus' => $jenis_kasus,
                            'count' => 0,
                        ];
                    }
                }
            }

            return response()->json([
                "id" => '1',
                "statusCode" => 200,
                "data" => $formattedData,
                "message" => 'Get data line chart yearly success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listLineChartJenisKasusAdminLanud($id, $selectedJenisKasus)
    {
        try {
            // Get distinct years from the database
            $years = Catpers::selectRaw('YEAR(tanggal) as year')
                ->distinct()
                ->pluck('year');

            $currentYear = Carbon::now()->year;
            $nextYears = range($currentYear, $currentYear + 4); // Generate next 4 years

            // Determine the years to use
            if ($years->isEmpty() || ($years->count() === 1 && $years->first() === $currentYear)) {
                $years = collect($nextYears); // No years found or only current year, use next 5 years
            } else {
                $years = $years->sort()->unique(); // Sort and get unique years
            }

            // Fetch data for each year and jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('YEAR(tanggal) as year, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereIn(DB::raw('YEAR(tanggal)'), $years) // Filter by years determined above
                ->when($selectedJenisKasus, function ($query) use ($selectedJenisKasus) {
                    return $query->where('catpers_jenis_kasuses.jenis_kasus_id', $selectedJenisKasus); // Filter by selected jenis_kasus_id
                })
                ->where('admin_lanud_id', $id)
                ->groupBy(DB::raw('YEAR(tanggal)'), 'jenis_kasuses.jenis_kasus')
                ->orderBy(DB::raw('YEAR(tanggal)'))
                ->get();

            // Prepare the response
            $formattedData = [];
            $jenisKasusList = $data->pluck('jenis_kasus')->unique(); // Get unique jenis_kasus

            foreach ($years as $year) {
                foreach ($jenisKasusList as $jenis_kasus) {
                    // Check if the data exists for the current year and jenis_kasus
                    $item = $data->firstWhere(function ($value) use ($year, $jenis_kasus) {
                        return $value->year == $year && $value->jenis_kasus == $jenis_kasus;
                    });

                    if ($item) {
                        // If data exists, use its count
                        $formattedData[] = [
                            'year' => $year,
                            'jenis_kasus' => $item->jenis_kasus,
                            'count' => $item->count,
                        ];
                    } else {
                        // If no data exists, set count to 0
                        $formattedData[] = [
                            'year' => $year,
                            'jenis_kasus' => $jenis_kasus,
                            'count' => 0,
                        ];
                    }
                }
            }

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => $formattedData,
                "message" => 'Get data line chart jenis kasus success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartTriwulanAdminLanud($id, $selectedTriwulan, $selectedTahun)
    {
        try {
            $quarterMonths = [
                'triwulan_1' => [1, 2, 3],
                'triwulan_2' => [4, 5, 6],
                'triwulan_3' => [7, 8, 9],
                'triwulan_4' => [10, 11, 12],
            ];

            $months = $quarterMonths[$selectedTriwulan] ?? [];
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('MONTH(tanggal) as month, jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereYear('tanggal', $selectedTahun)
                ->where('admin_lanud_id', $id)
                ->whereIn(DB::raw('MONTH(tanggal)'), $months)
                ->groupBy(DB::raw('MONTH(tanggal)'), 'jenis_kasuses.jenis_kasus')
                ->orderBy(DB::raw('MONTH(tanggal)'))
                ->get();

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => $data,
                "message" => 'Get line chart data success',
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage(),
            ];
        }
    }

    public function listPieChartWeeklyAdminLanud($id)
    {
        try {
            // Get the current date, start of the week (Monday) and end of the week (Sunday)
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);
            $currentYear = Carbon::now()->year;

            // Fetch data for the current week, grouping by jenis_kasus name
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])  // Filter by current week
                ->whereYear('tanggal', $currentYear)
                ->where('admin_lanud_id', $id)
                ->groupBy('jenis_kasuses.jenis_kasus') // Group by jenis_kasus
                ->orderBy('jenis_kasuses.jenis_kasus') // Optional: Order by jenis_kasus
                ->get();

            if ($data->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $data,
                    "message" => 'Get data pie chart weekly success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'Data pie chart weekly not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listPieChartMonthlyAdminLanud($id)
    {
        try {
            // Get the current month and year
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            // Fetch data for the current month, grouping by jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereMonth('tanggal', $currentMonth) // Filter by current month
                ->whereYear('tanggal', $currentYear) // Filter by current year
                ->where('admin_lanud_id', $id)
                ->groupBy('jenis_kasuses.jenis_kasus')
                ->orderBy('count', 'desc')
                ->get();

            if ($data->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $data,
                    "message" => 'Get monthly kasus data success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No data found for the current month'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listPieChartYearlyAdminLanud($id)
    {
        try {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Fetch data for the current year, grouping by jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereYear('tanggal', $currentYear) // Filter by current year
                ->where('admin_lanud_id', $id)
                ->groupBy('jenis_kasuses.jenis_kasus')
                ->orderBy('count', 'desc')
                ->get();

            if ($data->isNotEmpty()) {
                return response()->json([
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $data,
                    "message" => 'Get yearly kasus data success'
                ]);
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No data found for the current year'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ]);
        }
    }

    public function listPieChartJenisKasusAdminLanud($id, $selectedJenisKasus)
    {
        try {
            // Get the current year
            $currentYear = Carbon::now()->year;

            // Fetch data for the current year, grouping by jenis_kasus
            $data = DB::table('catpers')
                ->join('catpers_jenis_kasuses', 'catpers.id', '=', 'catpers_jenis_kasuses.catpers_id')
                ->join('jenis_kasuses', 'catpers_jenis_kasuses.jenis_kasus_id', '=', 'jenis_kasuses.id')
                ->selectRaw('jenis_kasuses.jenis_kasus, COUNT(*) as count')
                ->whereYear('tanggal', $currentYear) // Filter by current year
                ->when($selectedJenisKasus, function ($query) use ($selectedJenisKasus) {
                    return $query->where('catpers_jenis_kasuses.jenis_kasus_id', $selectedJenisKasus); // Filter by selected jenis_kasus_id
                })
                ->where('admin_lanud_id', $id)
                ->groupBy('jenis_kasuses.jenis_kasus')
                ->orderBy('count', 'desc')
                ->get();

            if ($data->isNotEmpty()) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $data,
                    "message" => 'Get pie chart jenis kasus success'
                ];
            } else {
                return response()->json([
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No data found for the pie chart jenis kasus'
                ]);
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartTriwulanAdminLanud($id, $selectedTriwulan, $selectedTahun)
    {
        try {
            // Define the months for each triwulan
            $quarterMonths = [
                'triwulan_1' => [1, 2, 3],
                'triwulan_2' => [4, 5, 6],
                'triwulan_3' => [7, 8, 9],
                'triwulan_4' => [10, 11, 12],
            ];

            // Get the months corresponding to the selected triwulan
            $months = $quarterMonths[$selectedTriwulan] ?? [];

            // Query the data
            $data = DB::table('catpers')
                ->selectRaw('MONTH(tanggal) as month, COUNT(*) as count')
                ->whereYear('tanggal', $selectedTahun)
                ->where('admin_lanud_id', $id)
                ->whereIn(DB::raw('MONTH(tanggal)'), $months)
                ->groupBy(DB::raw('MONTH(tanggal)'))
                ->orderBy(DB::raw('MONTH(tanggal)'))
                ->get();

            // Format the data for better usability
            $formattedData = [];
            foreach ($months as $month) {
                $item = $data->firstWhere('month', $month);
                $formattedData[] = [
                    'month' => $month,
                    'count' => $item->count ?? 0,
                ];
            }

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => $formattedData,
                "message" => 'Get pie chart data success',
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage(),
            ];
        }
    }
}
