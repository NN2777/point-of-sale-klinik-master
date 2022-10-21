<?php

namespace App\Http\Controllers;

use App\Exports\ExportHutang;
use App\Exports\ExportPembelian;
use App\Exports\ExportNota;
use App\Exports\ExportPerItem;
use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class LaporanPembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.pembelian.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $pembelian = Pembelian::orderBy('id_pembelian', 'desc')->whereBetween('tanggal', [$awal, $akhir])->get();

        $data = array();
        $total_pembelian = 0;
        $total_harga = 0;
        $total_diskon = 0;
        $total_ppn = 0;

        $no = 0;
        foreach ($pembelian as $beli) {
            $total_pembelian += $beli->bayar;
            $total_harga += $beli->total_harga;
            $total_diskon += $beli->diskon * ($beli->total_harga) / 100;
            $total_ppn += $beli->ppn * ($beli->total_harga) / 100;
            $row = array();
            $row['DT_RowIndex'] = ++$no;
            $row['no_faktur'] = $beli->no_faktur;
            $row['tanggal'] = tanggal_indonesia($beli->tanggal, false);
            $row['supplier'] = $beli->supplier->nama;
            $row['total_harga'] =  'Rp.' . format_uang($beli->total_harga);
            $row['diskon'] = 'Rp.' . format_uang($beli->diskon * ($beli->total_harga) / 100);
            $row['ppn'] = 'Rp.' . format_uang($beli->ppn * ($beli->total_harga) / 100);
            $row['total_bayar'] = 'Rp.' . format_uang($beli->bayar);
            $row['status'] = $beli->status;
            $row['jatuh_tempo'] = $beli->jatuh_tempo;

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'no_faktur' => '',
            'tanggal' => '',
            'supplier' => 'Total',
            'total_harga' => 'Rp.' . format_uang($total_harga),
            'diskon' => 'Rp.' . format_uang($total_diskon),
            'ppn' => 'Rp.' . format_uang($total_ppn),
            'total_bayar' => 'Rp.' . format_uang($total_pembelian),
            'status' => '',
            'jatuh_tempo' => '',
        ];
        // dd($pembelian);
        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
        // return datatables()
        //     ->of($pembelian)
        //     ->addIndexColumn()

        //     ->addColumn('total_harga', function ($pembelian) {
        //         return 'Rp. ' . format_uang($pembelian->total_harga);
        //     })
        //     ->addColumn('bayar', function ($pembelian) {
        //         return 'Rp. ' . format_uang($pembelian->bayar);
        //     })
        //     ->addColumn('tanggal', function ($pembelian) {
        //         return tanggal_indonesia($pembelian->created_at, false);
        //     })
        //     ->addColumn('supplier', function ($pembelian) {
        //         return $pembelian->supplier->nama;
        //     })
        //     ->editColumn('diskon', function ($pembelian) {
        //         return $pembelian->diskon . '%';
        //     })
        //     ->editColumn('ppn', function ($pembelian) {
        //         return $pembelian->ppn . '%';
        //     })
        //     ->editColumn('status', function ($pembelian) {
        //         return $pembelian->status;
        //     })
        //     ->editColumn('jatuh_tempo', function ($pembelian) {
        //         return $pembelian->jatuh_tempo ;
        //     })
        //     ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /** =================ngambil data tunai + return data tunai + export data tunai====================== */
    public function indexTunai(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.pembelian.tunai', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getDataTunai($awal, $akhir)
    {
        $pembelian = Pembelian::where('status', 'tunai')->orderBy('id_pembelian', 'desc')->whereBetween('tanggal', [$awal, $akhir])->get();

        $data = array();
        $total_pembelian = 0;
        $total_harga = 0;
        $total_diskon = 0;
        $total_ppn = 0;

        $no = 0;
        foreach ($pembelian as $beli) {
            $total_pembelian += $beli->bayar;
            $total_harga += $beli->total_harga;
            $total_diskon += $beli->diskon * ($beli->total_harga) / 100;
            $total_ppn += $beli->ppn * ($beli->total_harga) / 100;
            $row = array();
            $row['DT_RowIndex'] = ++$no;
            $row['no_faktur'] = $beli->no_faktur;
            $row['tanggal'] = tanggal_indonesia($beli->tanggal, false);
            $row['supplier'] = $beli->supplier->nama;
            $row['total_harga'] =  'Rp.' . format_uang($beli->total_harga);
            $row['diskon'] = 'Rp.' . format_uang($beli->diskon * ($beli->total_harga) / 100);
            $row['ppn'] = 'Rp.' . format_uang($beli->ppn * ($beli->total_harga) / 100);
            $row['total_bayar'] = 'Rp.' . format_uang($beli->bayar);
            $row['status'] = $beli->status;
            $row['jatuh_tempo'] = $beli->jatuh_tempo;

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'no_faktur' => '',
            'tanggal' => '',
            'supplier' => 'Total',
            'total_harga' => 'Rp.' . format_uang($total_harga),
            'diskon' => 'Rp.' . format_uang($total_diskon),
            'ppn' => 'Rp.' . format_uang($total_ppn),
            'total_bayar' => 'Rp.' . format_uang($total_pembelian),
            'status' => '',
            'jatuh_tempo' => '',
        ];
        // dd($pembelian);
        return $data;
    }

    public function dataTunai($awal, $akhir)
    {
        $data = $this->getDataTunai($awal, $akhir);
        return datatables()
            ->of($data)
            ->make(true);
    }


    /** =================ngambil data kredit + return data kredit + export data kredit====================== */
    public function indexKredit(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.pembelian.kredit', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getDataKredit($awal, $akhir)
    {
        $pembelian = Pembelian::where('status', 'kredit')->orderBy('id_pembelian', 'desc')->whereBetween('tanggal', [$awal, $akhir])->get();

        $data = array();
        $total_pembelian = 0;
        $total_harga = 0;
        $total_diskon = 0;
        $total_ppn = 0;

        $no = 0;
        foreach ($pembelian as $beli) {
            $total_pembelian += $beli->bayar;
            $total_harga += $beli->total_harga;
            $total_diskon += $beli->diskon * ($beli->total_harga) / 100;
            $total_ppn += $beli->ppn * ($beli->total_harga) / 100;
            $row = array();
            $row['DT_RowIndex'] = ++$no;
            $row['no_faktur'] = $beli->no_faktur;
            $row['tanggal'] = tanggal_indonesia($beli->tanggal, false);
            $row['supplier'] = $beli->supplier->nama;
            $row['total_harga'] =  'Rp.' . format_uang($beli->total_harga);
            $row['diskon'] = 'Rp.' . format_uang($beli->diskon * ($beli->total_harga) / 100);
            $row['ppn'] = 'Rp.' . format_uang($beli->ppn * ($beli->total_harga) / 100);
            $row['total_bayar'] = 'Rp.' . format_uang($beli->bayar);
            $row['status'] = $beli->status;
            $row['jatuh_tempo'] = $beli->jatuh_tempo;

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'no_faktur' => '',
            'tanggal' => '',
            'supplier' => 'Total',
            'total_harga' => 'Rp.' . format_uang($total_harga),
            'diskon' => 'Rp.' . format_uang($total_diskon),
            'ppn' => 'Rp.' . format_uang($total_ppn),
            'total_bayar' => 'Rp.' . format_uang($total_pembelian),
            'status' => '',
            'jatuh_tempo' => '',
        ];
        // dd($pembelian);
        return $data;
    }

    public function dataKredit($awal, $akhir)
    {
        $data = $this->getDataKredit($awal, $akhir);
        return datatables()
            ->of($data)
            ->make(true);
    }

    /** =================ngambil data tunai + return data tunai + export data tunai====================== */
    public function indexNota(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.pembelian.nota', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getDataNota($awal, $akhir)
    {
        $pembelian = Pembelian::orderBy('id_pembelian', 'desc')->whereBetween('tanggal', [$awal, $akhir])->get();

        $data = array();
        $total_pembelian = 0;

        foreach ($pembelian as $beli) {
            $total_pembelian += $beli->bayar;
            $row = array();
            $row['DT_RowIndex'] = "";
            $row['nama_obat'] = "";
            $row['no_batch'] = "";
            $row['quantity'] = "";
            $row['harga_satuan'] = "";
            $row['diskon_item'] = "";
            $row['total_bayar'] = "";

            $data[] = $row;

            $data[] = [
                'DT_RowIndex' => '',
                'nama_obat' => 'Kode Nota: ' . $beli->no_faktur,
                'no_batch' =>  'Tanggal: ' .$beli->tanggal,
                'quantity' => '',
                'harga_satuan' => '',
                'diskon_item' => '',
                'total_bayar' => '',
            ];

            $produk= PembelianDetail::with('produk')->where('id_pembelian', $beli->id_pembelian)->get();
            $total_pembelian_nota = 0;
            $no = 0;
            foreach($produk as $barang){
                $total_pembelian_nota += $barang->subtotal - ($beli->diskon * ($beli->total_harga)) / 100 + ($beli->ppn * ($beli->total_harga) / 100);
                $data[] = [
                    'DT_RowIndex' => ++$no,
                    'nama_obat' => $barang->produk->nama_produk,
                    'no_batch' => '',
                    'quantity' => $barang->jumlah,
                    'harga_satuan' => format_uang($barang->harga_beli),
                    'diskon_item' => $barang->produk->diskon,
                    'total_bayar' => format_uang($barang->harga_beli * $barang->jumlah),
                ];
            }

            $data[] = [
                'DT_RowIndex' => '',
                'nama_obat' => '',
                'no_batch' => '',
                'quantity' => '',
                'harga_satuan' => '',
                'diskon_item' => 'Diskon Nota Transaksi',
                'total_bayar' => format_uang($beli->diskon * ($beli->total_harga) / 100),
            ];

            $data[] = [
                'DT_RowIndex' => '',
                'nama_obat' => '',
                'no_batch' => '',
                'quantity' => '',
                'harga_satuan' => '',
                'diskon_item' => 'PPN Nota Transaksi',
                'total_bayar' =>  format_uang($beli->ppn * ($beli->total_harga) / 100),
            ];

            $data[] = [
                'DT_RowIndex' => '',
                'nama_obat' => '',
                'no_batch' => '',
                'quantity' => '',
                'harga_satuan' => '',
                'diskon_item' => 'Subtotal Nota',
                'total_bayar' => format_uang($total_pembelian_nota),
            ];
            // dd($produk);
        }

        $data[] = [
            'DT_RowIndex' => '',
            'nama_obat' => '',
            'no_batch' => '',
            'quantity' => '',
            'harga_satuan' => '',
            'diskon_item' => 'Grand Total',
            'total_bayar' => format_uang($total_pembelian),
        ];
        return $data;
    }

    public function dataNota($awal, $akhir)
    {
        $data = $this->getDataNota($awal, $akhir);
        return datatables()
            ->of($data)
            ->make(true);
    }

    /* ================================================== INDEX ITEM ===================== */
    public function indexItem(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');
        $getitem = Produk::first();
        $item = $getitem->nama_produk;
        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir && $request->has('item') && $request->item) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $item = $request->item;
        }

        return view('laporan.pembelian.item', compact('tanggalAwal', 'tanggalAkhir', 'item'));
    }

    public function getDataPembelianPerItem($awal, $akhir, $item){ 

        $produk = Produk::where('nama_produk', $item)->first();
        // $pembelian = PembelianDetail::with('pembelian', function($query) use ($awal, $akhir) {
        //     $query->whereBetween('tanggal', [$awal, $akhir]);
        // })->get();
        // dd($pembelian);
        $detail = PembelianDetail::with('pembelian.supplier')->whereBetween('tanggal',[$awal, $akhir])->where('id_produk', $produk->id_produk)->get();
        $data = array();
        $no = 0;
        $jumlah = 0;
        $total_pembelian_item = 0;
        foreach ($detail as $dt) {
            $jumlah += $dt->jumlah;
            $total_pembelian_item += $dt->subtotal;
            $row = array();
            $row['DT_RowIndex'] = ++$no;
            $row['no_faktur'] = $dt->no_faktur;
            $row['tanggal'] = $dt->tanggal;
            $row['supplier'] = $dt->pembelian->supplier['nama'] ?? '';
            $row['jumlah'] = $dt->jumlah;
            $row['harga_beli'] = $dt->harga_beli;
            $row['diskon'] = $dt->diskon ?? 0;
            $row['harga_total'] = $dt->subtotal;

            $data[] = $row;

        }

        $data[] = [
            'DT_RowIndex' => '',
            'no_faktur' => '',
            'tanggal' => '',
            'supplier' => 'Jumlah',
            'jumlah' => $jumlah,
            'harga_beli' => '',
            'diskon' => 'Harga Total',
            'harga_total' => format_uang($total_pembelian_item),
        ];
        // dd($pembelian);
        return $data;
    }

    public function dataItem($awal, $akhir, $item)
    {
        $data = $this->getDataPembelianPerItem($awal, $akhir, $item);
        return datatables()
            ->of($data)
            ->make(true);
    }

    /* ====================================================INDEX HUTANG=====================================*/
    public function indexHutang(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporan.hutang.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getDataHutang($awal, $akhir)
    {   
        $supplier = Supplier::orderBy('id_supplier', 'desc')->get();

        $data = array();
        $total_grand_hutang = 0;

        foreach ($supplier as $beli) {
            $data[] = [
                'DT_RowIndex' => '',
                'no_nota' => 'NAMA   : ' . $beli->nama,
                'tanggal_pembelian' =>  'ALAMAT : ' . $beli->alamat,
                'tanggal_jatuh_tempo' => '',
                'saldo_hutang' => '',
            ];

            $pembelian = Pembelian::orderBy('tanggal', 'asc')->where('id_supplier', $beli->id_supplier)->whereBetween('tanggal', [$awal, $akhir])->get();
            $total_pembelian_supplier = 0;
            $no = 0;
            foreach($pembelian as $barang){
                $total_pembelian_supplier += $barang->bayar;
                $data[] = [
                    'DT_RowIndex' => ++$no,
                    'no_nota' => $barang->no_faktur,
                    'tanggal_pembelian' => $barang->tanggal,
                    'tanggal_jatuh_tempo' => $barang->jatuh_tempo,
                    'saldo_hutang' => 'Rp. '.format_uang($barang->bayar),
                ];
            }
            $total_grand_hutang += $total_pembelian_supplier;

            $data[] = [
                'DT_RowIndex' => '',
                'no_nota' => '',
                'tanggal_pembelian' => '',  
                'tanggal_jatuh_tempo' => 'Total Hutang',
                'saldo_hutang' => 'Rp. '.format_uang($total_pembelian_supplier),
            ];

            $data[] = [
                'DT_RowIndex' => '',
                'no_nota' =>'' ,
                'tanggal_pembelian' =>  '',
                'tanggal_jatuh_tempo' => '',
                'saldo_hutang' => '',
            ];
            // dd($produk);
        }

        $data[] = [
            'DT_RowIndex' => '',
            'no_nota' => '',
            'tanggal_pembelian' => '',  
            'tanggal_jatuh_tempo' => 'Grand Total',
            'saldo_hutang' => 'Rp. '.format_uang($total_grand_hutang),
        ];

        return $data;
    }

    public function dataHutang($awal, $akhir)
    {
        $data = $this->getDataHutang($awal, $akhir);
        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportNotaPDF($awal, $akhir)
    {
        $data = $this->getDataNota($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pembelian.notapdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-nota' . date('Y-m-d-his') . '.pdf');
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pembelian.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-total' . date('Y-m-d-his') . '.pdf');
    }

    public function exportTunaiPDF($awal, $akhir)
    {
        $data = $this->getDataTunai($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pembelian.tunaipdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-tunai' . date('Y-m-d-his') . '.pdf');
    }

    public function exportKreditPDF($awal, $akhir)
    {
        $data = $this->getDataKredit($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pembelian.kreditpdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-kredit' . date('Y-m-d-his') . '.pdf');
    }

    public function exportItemPDF($awal, $akhir, $item)
    {
        $data = $this->getDataPembelianPerItem($awal, $akhir, $item);
        $pdf  = PDF::loadView('laporan.pembelian.itempdf', compact('awal', 'akhir', 'data', 'item'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-' . $item . '.pdf');
    }

    public function exportHutangPDF($awal, $akhir)
    {
        $data = $this->getDataHutang($awal, $akhir);
        $pdf  = PDF::loadView('laporan.hutang.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-hutang-' . date('Y-m-d-his') . '.pdf');
    }

    public function exportExcel($awal, $akhir){
        $data = $this->getData($awal, $akhir);
        $export = new ExportPembelian([$data]);

        return Excel::download($export, 'pembelian_total.xlsx');
    }

    public function exportTunaiExcel($awal, $akhir){
        $data = $this->getDataTunai($awal, $akhir);
        $export = new ExportPembelian([$data]);

        return Excel::download($export, 'pembelian_tunai.xlsx');
    }

    public function exportKreditExcel($awal, $akhir){
        $data = $this->getDataKredit($awal, $akhir);
        $export = new ExportPembelian([$data]);

        return Excel::download($export, 'pembelian_kredit.xlsx');
    }

    public function exportNotaExcel($awal, $akhir){
        $data = $this->getDataNota($awal, $akhir);
        $export = new ExportNota([$data]);

        return Excel::download($export, 'pembelian_nota.xlsx');
    }

    public function exportItemExcel($awal, $akhir, $item){
        $data = $this->getDataPembelianPerItem($awal, $akhir, $item);
        $export = new ExportPerItem([$data]);

        return Excel::download($export, 'pembelian_total_' .$item. '.xlsx');
    }

    public function exportHutangExcel($awal, $akhir){
        $data = $this->getDataHutang($awal, $akhir);
        $export = new ExportHutang([$data]);

        return Excel::download($export, 'pembelian_hutang.xlsx');
    }


}
