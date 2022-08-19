<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\Member;
use App\Models\Dokter;
use Illuminate\Http\Request;
use PDF;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $penjualan = Penjualan::with('member','dokter')->orderBy('id_penjualan', 'desc')->get();
        // dd($penjualan);

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'Rp. '. format_uang($penjualan->bayar);
            })
            ->addColumn('no_faktur', function ($penjualan) {
                $faktur = $penjualan->no_faktur ?? '';
                return '<span class="label label-primary">'. $faktur .'</spa>';
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->tanggal, false);
            })
            ->addColumn('kode_member', function ($penjualan) {
                $member = $penjualan->member->nama ?? '';
                return '<span class="label label-success">'. $member .'</spa>';
            })
            ->addColumn('kode_dokter', function ($penjualan) {
                $dokter = $penjualan->dokter->nama ?? '';
                return '<span class="label label-success">'. $dokter .'</spa>';
            })
            ->editColumn('diskon', function ($penjualan) {
                return $penjualan->diskon . '%';
            })
            ->editColumn('ppn', function ($penjualan) {
                return $penjualan->ppn . '%';
            })
            ->editColumn('status', function ($penjualan) {
                return $penjualan->status;
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
                <div class="btn-group">
                    <a href="'. route('penjualan.edit', $penjualan->id_penjualan) .'" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></a>
                    <button onclick="showDetail(`'. route('penjualan.show', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                    <button onclick="deleteData(`'. route('penjualan.destroy', $penjualan->id_penjualan) .'`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['aksi', 'kode_member','kode_dokter','no_faktur'])
            ->make(true);
    }

    public function create()
    {
        $penjualan = new Penjualan();
        $penjualan->id_member = null;
        $penjualan->id_dokter = null;
        $penjualan->no_faktur = $penjualan->id_penjualan;
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->diskon = 0;
        $penjualan->ppn = 0;
        $penjualan->status = "";
        $penjualan->jatuh_tempo = date('Y-m-d');
        $penjualan->tanggal = date('Y-m-d');
        $penjualan->bayar = 0;
        $penjualan->diterima = 0;
        $penjualan->id_user = auth()->id();
        $penjualan->save();

        session(['id_penjualan' => $penjualan->id_penjualan]);
        return redirect()->route('transaksi.index');
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->id_member = $request->id_member;
        $penjualan->id_dokter = $request->id_dokter;
        $penjualan->no_faktur = $request->no_faktur;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->ppn = $request->ppn;
        $penjualan->status = $request->status;
        $penjualan->jatuh_tempo = $request->jatuh_tempo;
        $penjualan->tanggal = $request->tanggal;
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->no_faktur = $request->no_faktur;
            $item->update();

            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
        }


        return redirect()->route('transaksi.selesai');
    }

    public function edit($id)
    {   
        $produk = Produk::orderBy('nama_produk')->get();
        $member = Member::orderBy('nama')->get();
        $dokter = Dokter::orderBy('nama')->get();
        
        session(['id_penjualan' => $id]);
        $id_penjualan = session('id_penjualan');
        $penjualan = Penjualan::find($id);
        // dd($id_penjualan);
        $diskon = Penjualan::find($id)->diskon ?? 0;
        $ppn = Penjualan::find($id)->ppn ?? 0;
        $memberSelected = $penjualan->member ?? new Member();
        $dokterSelected = $penjualan->dokter ?? new Dokter();
        return view('penjualan_detail.edit', compact('produk', 'member', 'dokter', 'diskon', 'ppn', 'id_penjualan', 'penjualan', 'memberSelected', 'dokterSelected'));
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">'. $detail->produk->kode_produk .'</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. '. format_uang($detail->harga_jual);
            })
            // ->addColumn('harga_jual_1', function ($detail) {
            //     return 'Rp. '. format_uang($detail->harga_jual_1);
            // })
            // ->addColumn('harga_jual_2', function ($detail) {
            //     return 'Rp. '. format_uang($detail->harga_jual_2);
            // })
            // ->addColumn('harga_jual_3', function ($detail) {
            //     return 'Rp. '. format_uang($detail->harga_jual_3);
            // })
            // ->addColumn('harga_jual_4', function ($detail) {
            //     return 'Rp. '. format_uang($detail->harga_jual_4);
            // })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. '. format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('penjualan.selesai', compact('setting'));
    }

    public function notaKecil()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();
        
        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (! $penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0,0,609,440, 'potrait');
        return $pdf->stream('Transaksi-'. date('Y-m-d-his') .'.pdf');
    }
}
