<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pegawai;
use Yajra\Datatables\Datatables;

class PegawaiController extends Controller
{
    function page()
    {
        if (session()->has('user')) {
            return view('admin-pegawai');
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
        $pegawai = Pegawai::all();
        if ($request->ajax()) {
            return (Datatables::of($pegawai)->toJson());
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
        if (Pegawai::where('nip', $request->nip)->exists()) {
            return response()->json([
                "message" => "Nip already exist!"
            ], 404);
        } else {
            $pegawai = new Pegawai();
            $pegawai->nip = $request->nip;
            $pegawai->nama = $request->nama;
            $pegawai->phone =  $request->phone;
            $pegawai->jenis_kelamin =  $request->jenis_kelamin;
            $pegawai->save();
            return response()->json([
                "message" => "Data added!"
            ], 202);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $find = Pegawai::find($request->nip);
        $find->nama = $request->nama;
        $find->phone =  $request->phone;
        $find->jenis_kelamin =  $request->jenis_kelamin;
        $find->update();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nip)
    {
        $pegawai = Pegawai::where('nip', $nip)->get();
        return $pegawai;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Pegawai::where('nip', $id)->exists()) {
            $pegawai = Pegawai::find($id);
            $pegawai->delete();

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
