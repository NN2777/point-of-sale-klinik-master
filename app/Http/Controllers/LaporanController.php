<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        return view ('laporan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function getData($awal, $akhir)
    // {
    //     $no = 1;
    //     $data = array();
    //     $pendapatan = 0;
    //     $total_pendapatan = 0;

    //     while (strtotime($awal) <= strtotime($akhir)) {
    //         $tanggal = $awal;
    //         $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

    //         $total_penjualan = Penjualan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
    //         $total_pembelian = Pembelian::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
    //         $total_pengeluaran = Pengeluaran::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');

    //         $pendapatan = $total_penjualan - $total_pembelian - $total_pengeluaran;
    //         $total_pendapatan += $pendapatan;

    //         $row = array();
    //         $row['DT_RowIndex'] = $no++;
    //         $row['tanggal'] = tanggal_indonesia($tanggal, false);
    //         $row['penjualan'] = format_uang($total_penjualan);
    //         $row['pembelian'] = format_uang($total_pembelian);
    //         $row['pengeluaran'] = format_uang($total_pengeluaran);
    //         $row['pendapatan'] = format_uang($pendapatan);

    //         $data[] = $row;
    //     }

    //     $data[] = [
    //         'DT_RowIndex' => '',
    //         'tanggal' => '',
    //         'penjualan' => '',
    //         'pembelian' => '',
    //         'pengeluaran' => 'Total Pendapatan',
    //         'pendapatan' => format_uang($total_pendapatan),
    //     ];

    //     return $data;
    // }

    // public function data($awal, $akhir)
    // {
    //     $data = $this->getData($awal, $akhir);

    //     return datatables()
    //         ->of($data)
    //         ->make(true);
    // }

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
