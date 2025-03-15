<?php

namespace App\Repositories\Pamsut;

use App\Models\AdminLanud;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminLanudRepository
{
    private $adminLanudModel;

    public function __construct(AdminLanud $adminLanudModel)
    {
        $this->adminLanudModel = $adminLanudModel;
    }

    public function listDataAdminLanud()
    {
        try {
            $dataAdminLanud = User::join('admin_lanuds', 'users.id', '=', 'admin_lanuds.user_id')
                ->select('users.*', 'admin_lanuds.nama_satuan', 'admin_lanuds.lokasi_satuan', 'admin_lanuds.nama_kepala_satuan', 'admin_lanuds.nama_admin', 'admin_lanuds.jabatan_admin', 'admin_lanuds.hp_admin')
                ->get();
            if ($dataAdminLanud) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataAdminLanud,
                    "message" => 'get data admin lanud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data admin lanud not found'
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

    public function detailDataAdminLanud($id)
    {
        try {
            $dataAdminLanud = User::join('admin_lanuds', 'users.id', '=', 'admin_lanuds.user_id')
                ->select('users.*', 'admin_lanuds.nama_satuan', 'admin_lanuds.lokasi_satuan', 'admin_lanuds.nama_kepala_satuan', 'admin_lanuds.nama_admin', 'admin_lanuds.jabatan_admin', 'admin_lanuds.hp_admin')
                ->find($id);
            if ($dataAdminLanud) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataAdminLanud,
                    "message" => 'get detail data admin lanud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data admin lanud not found'
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

    public function inputDataAdminLanud($dataRequest)
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $dataRequest['name'];
            $user->email = $dataRequest['email'];
            $user->password = bcrypt($dataRequest['password']);
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            $adminLanud = new AdminLanud();
            $adminLanud->nama_satuan = $dataRequest['nama_satuan'];
            $adminLanud->lokasi_satuan = $dataRequest['lokasi_satuan'];
            $adminLanud->nama_kepala_satuan = $dataRequest['nama_kepala_satuan'];
            $adminLanud->nama_admin = $dataRequest['nama_admin'];
            $adminLanud->jabatan_admin = $dataRequest['jabatan_admin'];
            $adminLanud->hp_admin = $dataRequest['hp_admin'];
            $adminLanud->user_id = $user->id;
            $adminLanud->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data admin lanud success'
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

    public function updateDataAdminLanud($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataUser = User::find($id);
            $dataUser->name = $dataRequest['name'];
            $dataUser->email = $dataRequest['email'];
            $dataUser->password = bcrypt($dataRequest['password']);
            $dataUser->save();

            $idAdminLanud = $this->adminLanudModel->where('user_id', $dataUser->id)->first();
            $dataAdminLanud = $this->adminLanudModel->find($idAdminLanud->id);
            $dataAdminLanud->nama_satuan = $dataRequest['nama_satuan'];
            $dataAdminLanud->lokasi_satuan = $dataRequest['lokasi_satuan'];
            $dataAdminLanud->nama_kepala_satuan = $dataRequest['nama_kepala_satuan'];
            $dataAdminLanud->nama_admin = $dataRequest['nama_admin'];
            $dataAdminLanud->jabatan_admin = $dataRequest['jabatan_admin'];
            $dataAdminLanud->hp_admin = $dataRequest['hp_admin'];
            $dataAdminLanud->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data admin lanud success'
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

    public function deleteDataAdminLanud($id)
    {
        try {
            $user = User::find($id);
            $idAdminLanud = $this->adminLanudModel->where('user_id', $user->id)->first();
            $adminLanud = $this->adminLanudModel->find($idAdminLanud->id);
            if ($adminLanud) {
                // Delete user
                User::where('id', $adminLanud->user_id)->delete();
                $adminLanud->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data admin lanud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data admin lanud tidak ditemukan'
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
