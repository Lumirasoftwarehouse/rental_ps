<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatpersJenisKasus extends Model
{
    use HasFactory;

    protected $fillable = ['catpers_id', 'jenis_kasus_id'];

    public function catpers()
    {
        return $this->belongsToMany(Catpers::class, 'catpers_jenis_kasus');
    }
}
