<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminInteludFsc extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama_admin",
        "jabatan_admin",
        "hp_admin",
        "user_id"
    ];
}
