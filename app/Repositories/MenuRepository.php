<?php
namespace App\Repositories;

use App\Models\Menu;

class MenuRepository
{
    public function listMenu()
    {
        try {
            $result = Menu::all();
            return [
                'id' => '1',
                'data' => $result,
                'statusCode' => 200
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => $th,
                'statusCode' => 401
            ];
        }
    }

    public function cekMenu($id)
    {
        try {
            $result = Menu::find($id);
            return $result;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function createMenu($dataRequest)
    {
        try {
            $result = Menu::create([
                'name' => $dataRequest['name'],
                'jenis' => $dataRequest['jenis'],
                'image' => $dataRequest['path'],  // Path for image
            ]);
            return [
                'id' => '1',
                'data' => $result,
                'statusCode' => 200
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => $th,
                'statusCode' => 401
            ];
        }
    }

    public function updateMenu($dataRequest)
    {
        try {
            $result = Menu::where('id', $dataRequest['id'])->update([
                'name' => $dataRequest['name'],
                'jenis' => $dataRequest['jenis'],
                'image' => $dataRequest['path'],  // Path for image
            ]);
            return [
                'id' => '1',
                'data' => $result,
                'statusCode' => 200
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => $th,
                'statusCode' => 401
            ];
        }
    }

    public function deleteMenu($dataRequest)
    {
        try {
            $result = Menu::where('id', $dataRequest)->delete();
            return [
                'id' => '1',
                'data' => $result,
                'statusCode' => 200
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => $th,
                'statusCode' => 401
            ];
        }
    }
}
