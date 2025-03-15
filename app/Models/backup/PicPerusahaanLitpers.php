<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicPerusahaanLitpers extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama_perusahaan",
        "nama_pic",
        "jabatan_pic",
        "hp_pic",
        "user_id"
    ];
}
