<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
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

}
