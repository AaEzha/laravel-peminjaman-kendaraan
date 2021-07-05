<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';
    protected  $primaryKey = 'nip';
    public $timestamps = false;
    public $incrementing = false;

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class,'nip', 'pegawai');
    }
}
