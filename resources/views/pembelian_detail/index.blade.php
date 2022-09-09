@extends('layouts.master')

@section('title')
Transaksi Pembelian
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-pembelian tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
@parent
<li class="active">Transaksi Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <table>
                    <tr>
                        <td>Supplier</td>
                        <td>: {{ $supplier->nama }}</td>
                    </tr>
                    <tr>
                        <td>Telepon</td>
                        <td>: {{ $supplier->telepon }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ $supplier->alamat }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">

                <form class="form-produk">
                    @csrf
                    <div class="form-group row">
                        <label for="kode_produk" class="col-lg-2">Kode Produk</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $id_pembelian }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th width="15%">Jumlah</th>
                        <th width="15%">Diskon</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('pembelian.store') }}" class="form-pembelian" method="post">
                            @csrf
                            <input type="hidden" name="id_pembelian" value="{{ $id_pembelian }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">

                            <div class="form-group row">
                                <label for="no_faktur" class="col-lg-2 control-label">No Faktur</label>
                                <div class="col-lg-8">
                                    <input type="text" name="no_faktur" id="no_faktur" class="form-control" value="{{ $id_pembelian }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal" class="col-lg-2 control-label">Tanggal</label>
                                <div class="col-lg-8">
                                    <input type="text" name="tanggal" id="tanggal" class="form-control datepicker" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" value="{{ $diskon }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ppn" class="col-lg-2 control-label">PPN</label>
                                <div class="col-lg-8">
                                    <input type="number" name="ppn" id="ppn" class="form-control" value="{{ $ppn }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="carabayar" class="col-lg-2 control-label">Cara Pembayaran</label>
                                <div class="col-lg-8">
                                    <input type="radio" name="status" value="Tunai">Tunai
                                    <input type="radio" name="status" value="Kredit">Kredit
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="jatuh_tempo" class="col-lg-2 control-label">Jatuh Tempo</label>
                                <div class="col-lg-8">
                                <input type="text" name="jatuh_tempo" id="jatuh_tempo" class="form-control datepicker" required autofocus value="{{ date('Y-m-d') }}" style="border-radius: 0 !important;">
                                <span class="help-block with-errors"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian_detail.produk')
@endsection

@push('scripts')
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    let table, table2;
    let id_pembelian_detail = [];

    $(function() {
        $('body').addClass('sidebar-collapse');

        table = $('.table-pembelian').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('pembelian_detail.data', $id_pembelian) }}',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'kode_produk'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'harga_beli'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'diskon'
                    },
                    {
                        data: 'subtotal'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ],
                dom: 'Brt',
                bSort: false,
                paginate: false,
                initComplete: function () {
                    $('.quantity').each(function() {
                        let data = $(this).data('id');
                        id_pembelian_detail.push(data);
                    });                    
                    console.log(id_pembelian_detail);
                    $.each(id_pembelian_detail, function( index, value ) {  
                        console.log(value);          
                        $(document).on('input', `#quantity_${value}`, function() {
                            let id = $(`#quantity_${value}`).data('id');
                            let jumlah = parseInt($(`#quantity_${id}`).val());
                            let diskon = $(`#diskon_${id}`).val();

                            if (jumlah < 1) {
                                $(`#quantity_${id}`).val(1);
                                alert('Jumlah tidak boleh kurang dari 1');
                                return;
                            }
                            if (jumlah > 10000) {
                                $(`#quantity_${id}`).val(10000);
                                alert('Jumlah tidak boleh lebih dari 10000');
                                return;
                            }

                            $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                                    '_token': $('[name=csrf-token]').attr('content'),
                                    '_method': 'put',
                                    'jumlah': jumlah,
                                    'diskon': diskon
                                })
                                .done(response => {
                                    $(this).on('mouseout', function() {
                                        table.ajax.reload(() => loadForm($('#diskon').val()));
                                    });
                                })
                                .fail(errors => {
                                    alert('Tidak dapat menyimpan data');
                                    return;
                                });                                    
                        });

                        $(document).on('input', `#diskon_${value}`, function() {
                            let id = $(`#quantity_${value}`).data('id');
                            let jumlah = parseInt($(`#quantity_${id}`).val());
                            let diskon = $(`#diskon_${id}`).val();

                            $.post(`{{ url('/pembelian_detail') }}/${id}`, {
                                    '_token': $('[name=csrf-token]').attr('content'),
                                    '_method': 'put',
                                    'jumlah': jumlah,
                                    'diskon': diskon
                                })
                                .done(response => {
                                    $(this).on('mouseout', function() {
                                        table.ajax.reload(() => loadForm($('#diskon').val()));
                                    });
                                })
                                .fail(errors => {
                                    alert('Tidak dapat menyimpan data');
                                    return;
                                });
                        }); 
                    });  
                }
            })
            .on('draw.dt', function() {
                loadForm($('#diskon').val());
                setTimeout(() => {
                    $('#diterima').trigger('input');
                }, 300);                
            })
            // })
            // .on('draw.dt', function() {
            //     loadForm($('#diskon').val(), $('#ppn').val());
            // });

        table2 = $('.table-produk').DataTable();

        // $(document).on('input', '.quantity', function() {
        //     let id = $(this).data('id');
        //     let jumlah = parseInt($(this).val());

        //     if (jumlah < 1) {
        //         $(this).val(1);
        //         alert('Jumlah tidak boleh kurang dari 1');
        //         return;
        //     }
        //     if (jumlah > 10000) {
        //         $(this).val(10000);
        //         alert('Jumlah tidak boleh lebih dari 10000');
        //         return;
        //     }

        //     $.post(`{{ url('/pembelian_detail') }}/${id}`, {
        //             '_token': $('[name=csrf-token]').attr('content'),
        //             '_method': 'put',
        //             'jumlah': jumlah
        //         })
        //         .done(response => {
        //             $(this).on('mouseout', function() {
        //                 table.ajax.reload(() => loadForm($('#diskon').val(), $('#ppn').val()));
        //             });
        //         })
        //         .fail(errors => {
        //             alert('Tidak dapat menyimpan data');
        //             return;
        //         });
        // });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $(document).on('input', '#diskon', '#ppn', function() {
            if ($('#diskon').val() == "") {
                $('#diskon').val(0).select();
            }

            loadForm($('#diskon').val(), $('#ppn').val());

        });
        
        $('#ppn').on('input', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($('#diskon').val(), $(this).val());
        })

        // $(document).on('input', '#diskon', function () {
        //     if ($(this).val() == "") {
        //         $(this).val(0).select();
        //     }

        //     loadForm($(this).val());
        // });

        // $(document).on('input', '#ppn', function () {
        //     if ($(this).val() == "") {
        //         $(this).val(0).select();
        //     }

        //     loadForm($(this).val());
        // });

        $('.btn-simpan').on('click', function() {
            $('.form-pembelian').submit();
        });
    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        hideProduk();
        tambahProduk();
    }

    function tambahProduk() {
        $.post('{{ route('pembelian_detail.store') }}', $('.form-produk').serialize())
            .done(response => {
                $('#kode_produk').focus();
                table.ajax.reload(() => loadForm($('#diskon').val(), $('#ppn').val()));
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
                return;
            });
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val(), $('ppn').val()));
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function loadForm(diskon = 0, ppn = 0) {
        $('#total').val($('.total').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/pembelian_detail/loadform') }}/${diskon}/${ppn}/${$('.total').text()}`)
            .done(response => {
                $('#totalrp').val('Rp. ' + response.totalrp);
                $('#bayarrp').val('Rp. ' + response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Rp. ' + response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);
            })
            .fail(errors => {
                alert('Tidak dapat menampilkan data');
                return;
            })
    }
</script>
@endpush