<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Yajra\Datatables\Datatables;

class RiwayatPeminjamanController extends Controller
{
    function page()
    {
        if (session()->has('user')) {
            return view('admin-riwayat');
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
                ['tanggal_dikembalikan', '!=', null],
                ['denda', '!=', null]
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
        if (Peminjaman::find($request->nip)->exists()) {
            $find = Peminjaman::find($request->nip);
            $find->nama = $request->nama;
            $find->phone =  $request->phone;
            $find->jenis_kelamin =  $request->jenis_kelamin;
            $find->update();
        } else {
            $peminjaman = new Peminjaman();
            $peminjaman->nip = $request->nip;
            $peminjaman->nama = $request->nama;
            $peminjaman->phone =  $request->phone;
            $peminjaman->jenis_kelamin =  $request->jenis_kelamin;
            $peminjaman->save();
        }
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Peminjaman::where('nip', $id)->exists()) {
            $peminjaman = Peminjaman::find($id);
            $peminjaman->delete();

            return response()->json([
                "message" => "records deleted"
            ], 202);
        } else {
            return response()->json([
                "message" => "not found"
            ], 404);
        }
    }
}
