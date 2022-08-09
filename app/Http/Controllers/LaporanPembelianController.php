<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use PDF;

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
        $pembelian = Pembelian::orderBy('id_pembelian', 'desc')->whereBetween('created_at', [$awal, $akhir])->get();

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
            $row['tanggal'] = tanggal_indonesia($beli->created_at, false);
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
        $pembelian = Pembelian::where('status', 'tunai')->orderBy('id_pembelian', 'desc')->whereBetween('created_at', [$awal, $akhir])->get();

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
            $row['tanggal'] = tanggal_indonesia($beli->created_at, false);
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
        $pembelian = Pembelian::where('status', 'kredit')->orderBy('id_pembelian', 'desc')->whereBetween('created_at', [$awal, $akhir])->get();

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
            $row['tanggal'] = tanggal_indonesia($beli->created_at, false);
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
        $pembelian = Pembelian::orderBy('id_pembelian', 'desc')->whereBetween('created_at', [$awal, $akhir])->get();

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
            $row['tanggal'] = tanggal_indonesia($beli->created_at, false);
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

    public function dataNota($awal, $akhir)
    {
        $data = $this->getDataKredit($awal, $akhir);
        return datatables()
            ->of($data)
            ->make(true);
    }

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

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pembelian.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-' . date('Y-m-d-his') . '.pdf');
    }

    public function exportTunaiPDF($awal, $akhir)
    {
        $data = $this->getDataTunai($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pembelian.tunaipdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-' . date('Y-m-d-his') . '.pdf');
    }

    public function exportKreditPDF($awal, $akhir)
    {
        $data = $this->getDataKredit($awal, $akhir);
        $pdf  = PDF::loadView('laporan.pembelian.kreditpdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');

        return $pdf->stream('Laporan-pembelian-' . date('Y-m-d-his') . '.pdf');
    }
}
