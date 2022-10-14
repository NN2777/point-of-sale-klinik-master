@extends('layouts.master')


@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
@parent
<li class="active">Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h4>Jenis Laporan</h4>
            </div>
            <div class="box-body">
                <ul class="list-group">
                    <li class="list-group-item active">Laporan Pembelian</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-pembelian-tunai.index') }}">Laporan Pembelian Tunai</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-pembelian-kredit.index') }}">Laporan Pembelian Kredit</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-pembelian.index') }}">Laporan Pembelian Total</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-pembelian-nota.index') }}">Laporan Pembelian Nota</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-pembelian-item.index') }}">Laporan Pembelian Per Item</a></li>
                    <li class="list-group-item active">Laporan Penjualan</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-penjualan-tunai.index') }}">Laporan Penjualan Tunai</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-penjualan-kredit.index') }}">Laporan Penjualan Kredit</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-penjualan.index') }}">Laporan Penjualan Total</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-penjualan-nota.index') }}">Laporan Penjualan Nota</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-penjualan-item.index') }}">Laporan Penjualan Per Item</a></li>
                    <li class="list-group-item active">Laporan Persediaan</a></li>
                    <li class="list-group-item"><a href="{{ route('laporan-persediaan.index') }}">Laporan Persediaan</a></li>
                    <li class="list-group-item active">Laporan Laba Rugi</li>
                    <li class="list-group-item"><a href="{{ route('labarugi.index') }}">Laporan Laba Rugi</a></li>
                </ul>
            </div>
            <!-- <div class="box-body">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Accordion Item #1
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Accordion Item #2
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Accordion Item #3
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
            </div>
        </div> 
    </div> -->
        </div>
    </div>
    @endsection