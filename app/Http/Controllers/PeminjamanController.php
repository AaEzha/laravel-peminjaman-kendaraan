<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Pegawai;
use App\Models\Kendaraan;
use Yajra\Datatables\Datatables;

class PeminjamanController extends Controller
{
    function page()
    {
        if (session()->has('user')) {
            $kendaraan = Kendaraan::all()->where('is_used', false);
            $pegawai = Pegawai::all();
            return view('admin-peminjaman')
                ->with('kendaraan', $kendaraan)
                ->with('pegawai', $pegawai);
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll(Request $request)
    {
        $peminjaman = Peminjaman::with(['kendaraan', 'pegawai'])
            ->where([
                ['tanggal_dikembalikan', '=', null],
                ['denda', '=', null]
            ])
            ->get();
        if ($request->ajax()) {
            return (Datatables::of($peminjaman)->toJson());
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $peminjaman = new Peminjaman();
        $peminjaman->kendaraan = $request->kendaraan;
        $peminjaman->pegawai = $request->pegawai;
        $peminjaman->alasan = $request->alasan;
        $peminjaman->tanggal_kembali =  $request->tanggal_kembali;
        $peminjaman->tanggal_pinjam =  $request->tanggal_pinjam;
        $peminjaman->tanggal_dikembalikan =  null;
        $peminjaman->denda =  null;
        $peminjaman->save();

        $kendaraan = Kendaraan::find($request->kendaraan);
        $kendaraan->is_used = true;
        $kendaraan->update();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nip)
    {
        $peminjaman = Peminjaman::where('nip', $nip)->get();
        return $peminjaman;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $peminjaman = Peminjaman::find($id);
        $peminjaman->tanggal_dikembalikan =  date("Y-m-d");
        $interval = strtotime(date("Y-m-d")) - strtotime($peminjaman->tanggal_kembali);
        $denda = 0;
        if ($interval > 0) {
            $days = floor($interval / 60 / 60 / 24);
            $denda = $days * 100000;
        }
        $peminjaman->denda =  $denda;
        $peminjaman->save();

        $kendaraan = Kendaraan::find($peminjaman->kendaraan);
        $kendaraan->is_used = false;
        $kendaraan->update();
        return $interval;
    }

}
