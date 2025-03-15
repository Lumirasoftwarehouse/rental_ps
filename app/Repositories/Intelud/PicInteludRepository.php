<?php

namespace App\Repositories\Intelud;

use App\Models\PicInteludFsc;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PicInteludRepository
{
    private $picInteludModel;

    public function __construct(PicInteludFsc $picInteludModel)
    {
        $this->picInteludModel = $picInteludModel;
    }

    public function jumlahPengajuanByPic()
    {
        try {
            $jumlah = DB::table('pengajuan_fscs')
            ->where('fk_pic_intelud_nfc_id', auth()->user()->id)
            ->count();
            return [
                "id" => '1',
                "data" => $jumlah
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "data" => $e->getMessage()
            ];
        }
    }

    public function listDataPicIntelud()
    {
        try {
            $dataPicIntelud = User::join('pic_intelud_fscs', 'users.id', '=', 'pic_intelud_fscs.user_id')
                ->select('users.*', 'pic_intelud_fscs.nama_pic', 'pic_intelud_fscs.jabatan_pic', 'pic_intelud_fscs.hp_pic')
                ->get();
            if ($dataPicIntelud) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataPicIntelud,
                    "message" => 'get data pic intelud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data pic intelud not found'
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

    public function detailDataPicIntelud($id)
    {
        try {
            $dataPicIntelud = User::join('pic_intelud_fscs', 'users.id', '=', 'pic_intelud_fscs.user_id')
                ->select('users.*', 'pic_intelud_fscs.nama_pic', 'pic_intelud_fscs.jabatan_pic', 'pic_intelud_fscs.hp_pic')
                ->find($id);
            if ($dataPicIntelud) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataPicIntelud,
                    "message" => 'get detail data pic intelud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data pic intelud not found'
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

    public function inputDataPicIntelud($dataRequest)
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $dataRequest['name'];
            $user->email = $dataRequest['email'];
            $user->password = bcrypt($dataRequest['password']);
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->save();

            $picIntelud = new PicInteludFsc();
            $picIntelud->nama_pic = $dataRequest['nama_pic'];
            $picIntelud->jabatan_pic = $dataRequest['jabatan_pic'];
            $picIntelud->hp_pic = $dataRequest['hp_pic'];
            $picIntelud->user_id = $user->id;
            $picIntelud->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data pic intelud success'
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

    public function updateDataPicIntelud($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataUser = User::find($id);
            $dataUser->name = $dataRequest['name'];
            $dataUser->email = $dataRequest['email'];
            $dataUser->password = bcrypt($dataRequest['password']);
            $dataUser->save();

            $idPicIntelud = $this->picInteludModel->where('user_id', $dataUser->id)->first();
            $dataPicIntelud = $this->picInteludModel->find($idPicIntelud->id);
            $dataPicIntelud->nama_pic = $dataRequest['nama_pic'];
            $dataPicIntelud->jabatan_pic = $dataRequest['jabatan_pic'];
            $dataPicIntelud->hp_pic = $dataRequest['hp_pic'];
            $dataPicIntelud->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data pic intelud success'
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

    public function deleteDataPicIntelud($id)
    {
        try {
            $user = User::find($id);
            $idPicIntelud = $this->picInteludModel->where('user_id', $user->id)->first();
            $picIntelud = $this->picInteludModel->find($idPicIntelud->id);
            if ($picIntelud) {
                // Delete user
                User::where('id', $picIntelud->user_id)->delete();
                $picIntelud->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data pic intelud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data pic intelud tidak ditemukan'
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
