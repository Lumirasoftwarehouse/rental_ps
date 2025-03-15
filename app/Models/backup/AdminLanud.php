<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLanud extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama_satuan",
        "lokasi_satuan",
        "nama_kepala_satuan",
        "nama_admin",
        "jabatan_admin",
        "hp_admin",
        "user_id"
    ];
}
