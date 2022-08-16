<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;

class DokterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dokter.index');
    }

    public function data()
    {
        $dokter = Dokter::orderBy('kode_dokter')->get();

        return datatables()
            ->of($dokter)
            ->addIndexColumn()
            ->addColumn('select_all', function ($produk) {
                return '
                    <input type="checkbox" name="id_dokter[]" value="'. $produk->id_dokter .'">
                ';
            })
            ->addColumn('kode_dokter', function ($dokter) {
                return '<span class="label label-success">'. $dokter->kode_dokter .'<span>';
            })
            ->addColumn('aksi', function ($dokter) {
                return '
                <div class="btn-group">
                    <button type="button" onclick="editForm(`'. route('dokter.update', $dokter->id_dokter) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteData(`'. route('dokter.destroy', $dokter->id_dokter) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'select_all', 'kode_dokter'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dokter = Dokter::latest()->first() ?? new Dokter();
        $kode_dokter = (int) $dokter->kode_dokter +1;

        $dokter = new Dokter();
        $dokter->kode_dokter = tambah_nol_didepan($kode_dokter, 5);
        $dokter->nama = $request->nama;
        $dokter->telepon = $request->telepon;
        $dokter->alamat = $request->alamat;
        $dokter->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dokter = Dokter::find($id);

        return response()->json($dokter);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $dokter = Dokter::find($id)->update($request->all());

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dokter = Dokter::find($id);
        $dokter->delete();

        return response(null, 204);
    }

    public function cetakDokter(Request $request)
    {
        $datadokter = collect(array());
        foreach ($request->id_dokter as $id) {
            $dokter = Dokter::find($id);
            $datadokter[] = $dokter;
        }

        $datadokter = $datadokter->chunk(2);
        $setting    = Setting::first();

        $no  = 1;
        $pdf = PDF::loadView('dokter.cetak', compact('datadokter', 'no', 'setting'));
        $pdf->setPaper(array(0, 0, 566.93, 850.39), 'potrait');
        return $pdf->stream('dokter.pdf');
    }
}
