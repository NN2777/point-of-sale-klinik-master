@extends('layouts.master')

@section('title')
    Daftar Pembelian
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembelian</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Transaksi Baru</button>
                @empty(! session('id_pembelian'))
                <a href="{{ route('pembelian_detail.index') }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-pencil"></i> Transaksi Aktif</a>
                @endempty
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>No Faktur</th>
                        <th>Supplier</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Diskon</th>
                        <th>PPN</th>
                        <th>Status</th>
                        <th>Jatuh Tempo</th>
                        <th>Total Bayar</th>
                        <th>Status Bayar</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('pembelian.supplier')
@includeIf('pembelian.detail')
@includeIf('pembelian.form')
@endsection

@push('scripts')
<script>
    let table, table1, table2;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembelian.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'no_faktur'},
                {data: 'supplier'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'diskon'},
                {data: 'ppn'},
                {data: 'status'},
                {data: 'jatuh_tempo'},
                {data: 'bayar'},
                {data: 'status2'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('.table-supplier').DataTable();

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_beli'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        })

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });
    });

    function addFormBayar(url, id) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Lakukan Pembayaran');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('get');
        $('#modal-form [name=bayar]').focus();

        $.get(`{{ url('/pembayaran/loadform') }}/${id}`)
            .done((response) => {
                $('#modal-form [name=no_faktur]').val(response.no_faktur);
                $('#modal-form [name=id_pembelian]').val(response.id_pembelian);
                $('#modal-form [name=tanggal_bayar]').val(response.tanggal);
                $('#modal-form [name=bayar]').val(response.bayar);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    function addForm() {
        $('#modal-supplier').modal('show');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }
</script>
@endpush