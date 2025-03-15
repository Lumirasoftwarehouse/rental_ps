<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_sewa',
        'durasi',
        'menu_id',
        'user_id',
    ];

    public function payment()
    {
        return $this->hasOne(Payment::class, 'pemesanan_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
