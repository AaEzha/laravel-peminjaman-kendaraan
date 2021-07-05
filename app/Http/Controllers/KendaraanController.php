<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use Yajra\Datatables\Datatables;

class KendaraanController extends Controller
{

    function page()
    {
        if (session()->has('user')) {
            return view('admin-kendaraan');
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
        $kendaraan = Kendaraan::all();
        if ($request->ajax()) {
            return (Datatables::of($kendaraan)->toJson());
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
        if (Kendaraan::where('id',$request->id)->exists()) {
            $find = Kendaraan::find($request->id);
            $find->nama = $request->nama;
            $find->plat_nomor =  $request->plat_nomor;
            $find->update();
            return response()->json([
                "message" => "Data Updated"
            ], 202);
        } else {
            $kendaraan = new Kendaraan();
            $kendaraan->id = 0;
            $kendaraan->nama = $request->nama;
            $kendaraan->plat_nomor =  $request->plat_nomor;
            $kendaraan->save();
            return response()->json([
                "message" => "Data Added"
            ], 202);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kendaraan = Kendaraan::find($id);
        return $kendaraan;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Kendaraan::where('id', $id)->exists()) {
            $kendaraan = Kendaraan::find($id);
            $kendaraan->delete();

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
