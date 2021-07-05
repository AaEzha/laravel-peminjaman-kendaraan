<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $table = 'peminjaman';
    public $timestamps = false;
    protected $guarded = [];


    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan', 'id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class,'pegawai', 'nip');
    }
}
