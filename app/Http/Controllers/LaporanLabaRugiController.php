<?php

namespace App\Http\Controllers;

use App\Exports\ExportLabaRugi;
use App\Exports\ExportLabaRugiNota;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;


class LaporanLabaRugiController extends Controller
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

        return view('laporan.labarugi.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));
            $tanggal = $awal;

            $total_penjualan = Penjualan::where('tanggal', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pembelian = Pembelian::where('tanggal', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaran = 0;

            $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
            $total_pendapatan += $pendapatan;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penjualan'] = format_uang($total_penjualan);
            $row['pembelian'] = format_uang($total_pembelian);
            $row['pendapatan'] = format_uang($pendapatan);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penjualan' => '',
            'pembelian' => 'Total Pendapatan',
            'pendapatan' => format_uang($total_pendapatan),
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }

    /*LABARUGI 2*/
    public function indexlabarugi(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        // dd($tanggalAwal, $tanggalAkhir);

        return view('laporan.labarugi.labarugi', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getDataLabaRugi($awal, $akhir)
    {
        $no = 1;
        $data = array();
        
        $total_keuntungan = 0;

        $penjualan = Penjualan::orderBy('tanggal', 'asc')->whereBetween('tanggal', [$awal, $akhir])->get();
        foreach ($penjualan as $jual) {
            $keuntungan = 0;
            $total_beli=0;
            $detail = PenjualanDetail::with('produk')->where('id_penjualan', $jual->id_penjualan)->get();

            foreach($detail as $barang){
                $total_beli += $barang->produk->harga_beli* $barang->jumlah;
            }
            $keuntungan = $jual->total_harga - $total_beli;
            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($jual->tanggal, false);
            $row['no_faktur'] = $jual->no_faktur;
            $row['penjualan'] = 'Rp. '. format_uang($jual->total_harga);
            $row['diskon_nota'] = $jual->diskon . '%';
            $row['hpp'] = 'Rp. '.format_uang($total_beli);
            $row['laba_rugi'] ='Rp. '.format_uang($keuntungan);

            $data[] = $row;

            $total_keuntungan += $keuntungan;
        }
       
        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'no_faktur' => '',
            'penjualan' => '',
            'diskon_nota' => '',
            'hpp' => 'Total Keuntungan',
            'laba_rugi' => 'Rp. '.format_uang($total_keuntungan),
        ];

        return $data;
    }

    public function dataLabaRugi($awal, $akhir)
    {
        $data = $this->getDataLabaRugi($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.labarugi.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }

    public function exportNotaPDF($awal, $akhir)
    {
        $data = $this->getDataLabaRugi($awal, $akhir);
        $pdf  = PDF::loadView('laporan.labarugi.labarugipdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-pernota'. date('Y-m-d-his') .'.pdf');
    }

    public function exportExcel($awal, $akhir){
        $data = $this->getData($awal, $akhir);
        $export = new ExportLabaRugi([$data]);

        return Excel::download($export, 'laba_rugi.xlsx');
    }

    public function exportNotaExcel($awal, $akhir){
        $data = $this->getDataLabaRugi($awal, $akhir);
        $export = new ExportLabaRugiNota([$data]);

        return Excel::download($export, 'laba_rugi_nota.xlsx');
    }
}
