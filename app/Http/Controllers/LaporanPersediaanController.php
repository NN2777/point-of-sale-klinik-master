<?php

namespace App\Http\Controllers;

use App\Exports\ExportPersediaan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class LaporanPersediaanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = date('Y-m-d');

        return view('laporan.persediaan.index', compact('tanggal'));
    }

    public function getData()
    {
        $produk = Produk::orderBy('id_produk', 'desc')->get();

        // dd($pembelian);
        $total_nilai_persediaan = 0;
        $total_persediaan = 0;
        $data = array();
        
        // dd($penjualan);
        $no = 0;
        foreach ($produk as $barang) {
            // $stok = $barang['stok'] <= 0 ? 0 : $barang->stok;
            $stok = $barang->stok;
            $total_persediaan += $barang->harga_beli;
            $total_nilai_persediaan += $barang->harga_beli * $stok;
            $row = array();
            $row['DT_RowIndex'] = ++$no;
            $row['kode'] = $barang->kode_produk;
            $row['nama_obat'] = $barang->nama_produk;
            $row['stok'] =  $stok;
            $row['harga_pokok'] = 'Rp. ' . format_uang($barang->harga_beli);
            $row['nilai_persediaan'] = 'Rp. ' . format_uang($barang->harga_beli * $stok);
            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'kode' => '',
            'nama_obat' => '',
            'stok' => 'Total',
            'harga_pokok' => 'Rp. ' . format_uang($total_persediaan),
            'nilai_persediaan' => 'Rp. ' . format_uang($total_nilai_persediaan),
        ];
        // dd($data);
        return $data;
    }

    public function data()
    {
        $data = $this->getData();

        return datatables()
            ->of($data)
            ->make(true);
    }
    
    public function exportPDF($tanggal)
    {   
        $data = $this->getData();
        $pdf  = PDF::loadView('laporan.persediaan.pdf', compact('tanggal', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-' . date('Y-m-d-his') . '.pdf');
    }

    public function exportExcel(){
        $data = $this->getData();
        $export = new ExportPersediaan([$data]);

        return Excel::download($export, 'persediaan.xlsx');
    }
}
