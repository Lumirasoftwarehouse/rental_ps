<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarCatpers extends Model
{
    use HasFactory;

    protected $fillable = [
        "isi_komentar",
        "catpers_id",
        "user_id"
    ];
}
