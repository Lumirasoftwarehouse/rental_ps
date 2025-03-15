<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MenuService;

use App\Models\Menu;
use App\Models\Pemesanan;
use Carbon\Carbon;

class MenuController extends Controller
{
    private $globalMenuService;

    public function __construct(MenuService $menuService)
    {
        $this->globalMenuService = $menuService;
    }

    public function getAvailableMenu(Request $request)
    {
        $tanggal = $request->input('tanggal');

        if (!$tanggal || !strtotime($tanggal)) {
            return response()->json(['error' => 'Tanggal tidak valid'], 400);
        }

        $tanggal = Carbon::parse($tanggal);

        // Ambil semua menu yang sedang disewa pada tanggal tertentu
        $menuDisewa = Pemesanan::where(function ($query) use ($tanggal) {
            $query->whereDate('tanggal_sewa', '<=', $tanggal)
                  ->whereRaw("DATE_ADD(tanggal_sewa, INTERVAL durasi DAY) >= ?", [$tanggal->format('Y-m-d')]);
        })->pluck('menu_id');

        // Ambil semua menu yang tidak sedang disewa
        $availableMenu = Menu::whereNotIn('id', $menuDisewa)->get();

        return response()->json($availableMenu);
    }


    public function listMenu()
    {
        try {
            $result = $this->globalMenuService->listMenu();
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ], $result['statusCode']);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => $th->getMessage()
            ], 500);  // Changed to 500 for internal server error
        }
    }

    public function createMenu(Request $request)
    {
        try {
            $validate = $request->validate([
                'name' => 'required',
                'jenis' => 'required',
                'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            $result = $this->globalMenuService->createMenu($validate);
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ], $result['statusCode']);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => $th->getMessage()
            ], 500);
        }
    }

    public function updateMenu(Request $request)
    {
        try {
            $validate = $request->validate([
                'id' => 'required',
                'name' => 'required',
                'jenis' => 'required',
                'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            $result = $this->globalMenuService->updateMenu($validate);
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ], $result['statusCode']);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteMenu($id)
    {
        try {
            $result = $this->globalMenuService->deleteMenu($id);
            return response()->json([
                'id' => $result['id'],
                'data' => $result['data']
            ], $result['statusCode']);
        } catch (\Throwable $th) {
            return response()->json([
                'id' => '0',
                'data' => $th->getMessage()
            ], 500);
        }
    }
}
