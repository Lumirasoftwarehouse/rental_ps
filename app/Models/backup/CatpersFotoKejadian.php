<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatpersFotoKejadian extends Model
{
    use HasFactory;

    protected $fillable = ['foto_kejadian', 'catpers_id'];

    public function catpers()
    {
        return $this->belongsTo(Catpers::class);
    }
}
