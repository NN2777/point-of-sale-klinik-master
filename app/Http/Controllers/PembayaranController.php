<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pembelian = Pembelian::orderBy('tanggal')->get();
        return view('pembayaran.index', compact('pembelian'));
    }

    public function data()
    {
        $pembayaran = Pembayaran::with('pembelian')->orderBy('id_pembayaran', 'desc')->get();
        // dd($penjualan);

        return datatables()
            ->of($pembayaran)
            ->addIndexColumn()
            ->addColumn('no_faktur', function ($pembayaran) {
                $faktur = $pembayaran->pembelian->no_faktur ?? '';
                return '<span class="label label-primary">'. $faktur .'</spa>';
            })
            ->addColumn('dibayar', function ($pembayaran) {
                return 'Rp. '. format_uang($pembayaran->dibayar);
            })
            ->addColumn('bayar', function ($pembayaran) {
                return 'Rp. '. format_uang($pembayaran->bayar);
            })
            ->addColumn('total_bayar', function ($pembayaran) {
                return 'Rp. '. format_uang($pembayaran->total_bayar);
            })
            ->addColumn('status', function ($pembayaran) {
                $status = $pembayaran->status2 ?? '';
                if($status=='Lunas'){
                    return '<span class="label label-success">'. $status .'</spa>';
                }else {
                    return '<span class="label label-danger">'. $status .'</spa>';
                }
            })
            ->addColumn('tanggal', function ($pembayaran) {
                return tanggal_indonesia($pembayaran->tanggal_bayar, false);
            })
            ->addColumn('aksi', function ($pembayaran) {
                return '
                <div class="btn-group">
                    <button onclick="deleteData(`'. route('pembayaran.destroy', $pembayaran->id_pembayaran) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'no_faktur', 'status'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $pembayaran = new Pembayaran();
        // $pembayaran->id_pembelian = null;
        // $pembayaran->bayar = 0;
        // $pembayaran->dibayar = 0;
        // $pembayaran->tanggal_bayar = date('Y-m-d');
        // $pembayaran->save();
        
        // return redirect()->route('pembayaran.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $pembelian = Pembelian::findOrFail($id);
        $pembayaran = new Pembayaran();
        $pembayarantotal = Pembayaran::where('id_pembelian', $pembelian->id_pembelian)->get();
        $pembayaran->id_pembelian = $pembelian->id_pembelian;
        $pembayaran->no_faktur = $request->no_faktur;
        $pembayaran->bayar = $request->bayar;
        $pembayaran->dibayar = $request->dibayar;
        $pembayaran->tanggal_bayar = $request->tanggal_bayar;
        $total = $pembayaran->dibayar;
        foreach ($pembayarantotal as $pay) {
            $total += $pay->dibayar;
        }        
        $pembayaran->total_bayar = $total;
        if($pembayaran->total_bayar >= $pembelian->bayar){
            $pembelian->status2 = 'Lunas';
            $pembelian->update();
        }
        $pembayaran->status2 = $pembelian->status2;
        $pembayaran->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::find($id);
        $pembayaran->nama_ = $request->nama_kategori;
        $pembayaran->update();

        return response()->json('Data berhasil disimpan', 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        if($pembayaran->id_pembelian!=null){
            $pembelian = Pembelian::findOrFail($pembayaran->id_pembelian);
            $pembayarantotal = Pembayaran::where('id_pembelian', $pembayaran->id_pembelian)->get();

            $total = 0;
            foreach ($pembayarantotal as $pay) {
                $total += $pay->dibayar;
            }       
            $total = $total - $pembayaran->bayar;
            
            if($total < $pembayaran->bayar){
                $pembelian->status2 = 'Belum Lunas';
                $pembelian->update();
            }
            $pembayaran->delete();

        } else{
            $pembayaran->delete();
        }
    }

    public function loadForm($id)
    {
        $pembelian = Pembelian::find($id);
        $data  = [
            'id_pembelian' => $pembelian->id_pembelian,
            'no_faktur' => $pembelian->no_faktur,
            'tanggal' => date('Y-m-d'),
            'bayar' => $pembelian->bayar,
            'dibayar' => 0,
        ];
        
        // dd($diskon, $ppn, $total, $bayar);
        return response()->json($data);
    }
}
