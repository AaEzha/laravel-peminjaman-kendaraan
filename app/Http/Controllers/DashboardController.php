<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pegawai;
use App\Models\Kendaraan;
use App\Models\Admin;
use Cookie;

class DashboardController extends Controller
{
    function page(Request $request){
        if (session()->has('user')) {
            $kendaraan = Kendaraan::all()->count();
            $pegawai = Pegawai::all()->count();
            $peminjaman = Peminjaman::all()->count();
            $admin = Admin::all()->count();
            return view('dashboard')
                ->with('kendaraan', $kendaraan)
                ->with('peminjaman', $peminjaman)
                ->with('admin', $admin)
                ->with('pegawai', $pegawai);
        } else {
            return redirect()->route('login');
        }
    }
}
