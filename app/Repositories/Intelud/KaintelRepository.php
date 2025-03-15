<?php

namespace App\Repositories\Intelud;

use App\Models\KaintelLanud;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KaintelRepository
{
    private $kaintelModel;

    public function __construct(KaintelLanud $kaintelModel)
    {
        $this->kaintelModel = $kaintelModel;
    }

    public function listDataKaintel()
    {
        try {
            $dataKaintel = User::join('kaintel_lanuds', 'users.id', '=', 'kaintel_lanuds.user_id')
                ->join('lanuds', 'kaintel_lanuds.lanud_id', '=', 'lanuds.id')
                ->select('users.*', 'kaintel_lanuds.nama_kaintel', 'kaintel_lanuds.alamat AS alamat_kaintel', 'kaintel_lanuds.hp', 'lanuds.nama_lanud', 'lanuds.alamat AS alamat_lanud')
                ->get();
            if ($dataKaintel) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataKaintel,
                    "message" => 'get data kaintel success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data kaintel not found'
                ];
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

    public function detailDataKaintel($id)
    {
        try {
            $dataKaintel = User::join('kaintel_lanuds', 'users.id', '=', 'kaintel_lanuds.user_id')
                ->join('lanuds', 'kaintel_lanuds.lanud_id', '=', 'lanuds.id')
                ->select('users.*', 'kaintel_lanuds.nama_kaintel', 'kaintel_lanuds.alamat AS alamat_kaintel', 'kaintel_lanuds.hp', 'lanuds.nama_lanud', 'lanuds.alamat AS alamat_lanud')
                ->find($id);
            if ($dataKaintel) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataKaintel,
                    "message" => 'get detail data kaintel success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data kaintel not found'
                ];
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

    public function inputDataKaintel($dataRequest)
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $dataRequest['name'];
            $user->email = $dataRequest['email'];
            $user->password = bcrypt($dataRequest['password']);
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            $kaintel = new KaintelLanud();
            $kaintel->nama_kaintel = $dataRequest['nama_kaintel'];
            $kaintel->alamat = $dataRequest['alamat'];
            $kaintel->hp = $dataRequest['hp'];
            $kaintel->lanud_id = $dataRequest['lanud_id'];
            $kaintel->user_id = $user->id;
            $kaintel->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data kaintel success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataKaintel($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataUser = User::find($id);
            $dataUser->name = $dataRequest['name'];
            $dataUser->email = $dataRequest['email'];
            $dataUser->password = bcrypt($dataRequest['password']);
            $dataUser->save();

            $idKaintel = $this->kaintelModel->where('user_id', $dataUser->id)->first();
            $dataKaintel = $this->kaintelModel->find($idKaintel->id);
            $dataKaintel->nama_kaintel = $dataRequest['nama_kaintel'];
            $dataKaintel->alamat = $dataRequest['alamat'];
            $dataKaintel->hp = $dataRequest['hp'];
            $dataKaintel->lanud_id = $dataRequest['lanud_id'];
            $dataKaintel->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data kaintel success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataKaintel($id)
    {
        try {
            $user = User::find($id);
            $idKaintel = $this->kaintelModel->where('user_id', $user->id)->first();
            $kaintel = $this->kaintelModel->find($idKaintel->id);
            if ($kaintel) {
                // Delete user
                User::where('id', $kaintel->user_id)->delete();
                $kaintel->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data kaintel success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data kaintel tidak ditemukan'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
