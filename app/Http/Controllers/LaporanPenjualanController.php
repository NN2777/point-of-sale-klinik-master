<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use PDF;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        // dd($tanggalAwal, $tanggalAkhir);

        return view('laporan.penjualan.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $penjualan = Penjualan::with('member')->orderBy('id_penjualan', 'desc')->whereBetween('tanggal', [$awal, $akhir])->get();

        // dd($pembelian);
        
        $data = array();
        $total_penjualan = 0;
        $total_harga = 0;
        $total_diskon = 0;
        $total_ppn = 0;
        
        // dd($penjualan);
        $no = 0;
        foreach ($penjualan as $jual) {
            $total_harga += $jual->total_harga;
            $total_penjualan += $jual->bayar;
            $total_diskon += $jual->diskon * ($jual->total_harga)/100;
            $total_ppn += $jual->ppn * ($jual->total_harga)/100;
            $row = array();
            $row['DT_RowIndex'] = ++$no;
            $row['tanggal'] = tanggal_indonesia($jual->tanggal, false);
            $row['member'] = $jual->member->nama ?? '';
            $row['total_harga'] =  'Rp.' . format_uang($jual->total_harga);
            $row['diskon'] = 'Rp.' . format_uang($jual->diskon * ($jual->total_harga)/100);
            $row['ppn'] = 'Rp.' . format_uang($jual->ppn * ($jual->total_harga)/100);
            $row['total_bayar'] = 'Rp.' . format_uang($jual->bayar);
            $row['status'] = $jual->status;
            $row['jatuh_tempo'] = $jual->jatuh_tempo;

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => 'Total',
            'member' => '',
            'total_harga' => 'Rp.' . format_uang($total_harga),
            'diskon' => 'Rp.' . format_uang($total_diskon),
            'ppn' => 'Rp.' . format_uang($total_ppn),
            'total_bayar' => 'Rp.' . format_uang($total_penjualan),
            'status' => '',
            'jatuh_tempo' => '',
        ];
        // dd($data);
        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }
    
    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.penjualan.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-' . date('Y-m-d-his') . '.pdf');
    }


    /* GET DATA + EXPORT DATA PENJUALAN TUNAI*/
    public function indexTunai(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        // dd($tanggalAwal, $tanggalAkhir);

        return view('laporan.penjualan.tunai', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getDataTunai($awal, $akhir)
    {
        $penjualan = Penjualan::where('status', 'tunai')->with('member')->orderBy('id_penjualan', 'desc')->whereBetween('tanggal', [$awal, $akhir])->get();

        // dd($pembelian);
        
        $data = array();
        $total_penjualan = 0;
        $total_harga = 0;
        $total_diskon = 0;
        $total_ppn = 0;
        
        // dd($penjualan);
        $no = 0;
        foreach ($penjualan as $jual) {
            $total_harga += $jual->total_harga;
            $total_penjualan += $jual->bayar;
            $total_diskon += $jual->diskon * ($jual->total_harga)/100;
            $total_ppn += $jual->ppn * ($jual->total_harga)/100;
            $row = array();
            $row['DT_RowIndex'] = ++$no;
            $row['tanggal'] = tanggal_indonesia($jual->tanggal, false);
            $row['member'] = $jual->member->nama ?? '';
            $row['total_harga'] =  'Rp.' . format_uang($jual->total_harga);
            $row['diskon'] = 'Rp.' . format_uang($jual->diskon * ($jual->total_harga)/100);
            $row['ppn'] = 'Rp.' . format_uang($jual->ppn * ($jual->total_harga)/100);
            $row['total_bayar'] = 'Rp.' . format_uang($jual->bayar);
            $row['status'] = $jual->status;
            $row['jatuh_tempo'] = $jual->jatuh_tempo;

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => 'Total',
            'member' => '',
            'total_harga' => 'Rp.' . format_uang($total_harga),
            'diskon' => 'Rp.' . format_uang($total_diskon),
            'ppn' => 'Rp.' . format_uang($total_ppn),
            'status' => '',
            'total_bayar' => 'Rp.' . format_uang($total_penjualan),
            'jatuh_tempo' => '',
        ];
        // dd($data);
        return $data;
    }

    public function dataTunai($awal, $akhir)
    {
        $data = $this->getDataTunai($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }
    
    public function exporTunaitPDF($awal, $akhir)
    {
        $data = $this->getDataTunai($awal, $akhir);
        $pdf  = PDF::loadView('laporan.penjualan.tunaipdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-' . date('Y-m-d-his') . '.pdf');
    }


    /* GET DATA + EXPORT DATA PENJUALAN KREDIT*/
    public function indexKredit(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        // dd($tanggalAwal, $tanggalAkhir);

        return view('laporan.penjualan.kredit', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getDataKredit($awal, $akhir)
    {
        $penjualan = Penjualan::where('status', 'kredit')->with('member')->orderBy('id_penjualan', 'desc')->whereBetween('tanggal', [$awal, $akhir])->get();

        // dd($pembelian);
        
        $data = array();
        $total_penjualan = 0;
        $total_harga = 0;
        $total_diskon = 0;
        $total_ppn = 0;
        
        // dd($penjualan);
        $no = 0;
        foreach ($penjualan as $jual) {
            $total_harga += $jual->total_harga;
            $total_penjualan += $jual->bayar;
            $total_diskon += $jual->diskon * ($jual->total_harga)/100;
            $total_ppn += $jual->ppn * ($jual->total_harga)/100;
            $row = array();
            $row['DT_RowIndex'] = ++$no;
            $row['tanggal'] = tanggal_indonesia($jual->tanggal, false);
            $row['member'] = $jual->member->nama ?? '';
            $row['total_harga'] =  'Rp.' . format_uang($jual->total_harga);
            $row['diskon'] = 'Rp.' . format_uang($jual->diskon * ($jual->total_harga)/100);
            $row['ppn'] = 'Rp.' . format_uang($jual->ppn * ($jual->total_harga)/100);
            $row['total_bayar'] = 'Rp.' . format_uang($jual->bayar);
            $row['status'] = $jual->status;
            $row['jatuh_tempo'] = $jual->jatuh_tempo;

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => 'Total',
            'member' => '',
            'total_harga' => 'Rp.' . format_uang($total_harga),
            'diskon' => 'Rp.' . format_uang($total_diskon),
            'ppn' => 'Rp.' . format_uang($total_ppn),
            'total_bayar' => 'Rp.' . format_uang($total_penjualan),
            'status' => '',
            'jatuh_tempo' => '',
        ];
        // dd($data);
        return $data;
    }

    public function dataKredit($awal, $akhir)
    {
        $data = $this->getDataKredit($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }
    
    public function exportKreditPDF($awal, $akhir)
    {
        $data = $this->getDataKredit($awal, $akhir);
        $pdf  = PDF::loadView('laporan.penjualan.kreditpdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-' . date('Y-m-d-his') . '.pdf');
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

        return view('laporan.penjualan.nota', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getDataNota($awal, $akhir)
    {
        $penjualan = Penjualan::orderBy('id_penjualan', 'desc')->whereBetween('tanggal', [$awal, $akhir])->get();

        $data = array();
        $total_penjualan = 0;

        foreach ($penjualan as $jual) {
            $total_penjualan += $jual->bayar;
            $row = array();
            $row['DT_RowIndex'] = "";
            $row['nama_obat'] = "";
            $row['quantity'] = "";
            $row['harga_satuan'] = "";
            $row['diskon_item'] = "";
            $row['total_bayar'] = "";

            $data[] = $row;

            $data[] = [
                'DT_RowIndex' => '',
                'nama_obat' => 'Kode Nota: ' . $jual->id_penjualan,
                'quantity' =>  'Tanggal: ' .$jual->tanggal,
                'harga_satuan' => '',
                'diskon_item' => '',
                'total_bayar' => '',
            ];

            $produk= PenjualanDetail::with('produk')->where('id_penjualan', $jual->id_penjualan)->get();
            $total_pembelian_nota = 0;
            $no = 0;
            foreach($produk as $barang){
                $total_pembelian_nota += $barang->subtotal - ($jual->diskon * ($jual->total_harga)) / 100 + ($beli->ppn * ($beli->total_harga) / 100);
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
                'total_bayar' => format_uang($jual->diskon * ($jual->total_harga) / 100),
            ];

            $data[] = [
                'DT_RowIndex' => '',
                'nama_obat' => '',
                'no_batch' => '',
                'quantity' => '',
                'harga_satuan' => '',
                'diskon_item' => 'PPN Nota Transaksi',
                'total_bayar' =>  format_uang($jual->ppn * ($jual->total_harga) / 100),
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
            'total_bayar' => format_uang($total_penjualan),
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

}
