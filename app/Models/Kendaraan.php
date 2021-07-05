<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $table = 'kendaraan';
    public $timestamps = false;

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class,'id', 'pegawai');
    }
}
