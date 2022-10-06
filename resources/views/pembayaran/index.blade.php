@extends('layouts.master')

@section('title')
    Daftar pembayaran
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Pembayaran</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-pembelian">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>No Faktur</th>
                        <th>Total Harga</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@includeIf('pembayaran.pembelian_bayar')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function () {
        table = $('.table-pembelian').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('pembayaran.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'no_faktur'},
                {data: 'bayar'},
                {data: 'dibayar'},
                {data: 'status'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });
        $('.table-bayar').DataTable();
    });

    function addForm() {
        $('#modal-bayar').modal('show');
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