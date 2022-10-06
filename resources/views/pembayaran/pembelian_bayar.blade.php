<div class="modal fade" id="modal-bayar" tabindex="-1" role="dialog" aria-labelledby="modal-bayar">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Transaksi Pembelian</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-bayar">
                    <thead>
                        <th width="5%">No</th>
                        <th>No Faktur</th>
                        <th>Tanggal</th>
                        <th>Bayar</th>
                        <th>Status Bayar</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($pembelian as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->no_faktur }}</td>
                                <td>{{ $item->tanggal }}</td>
                                <td>Rp. {{ $item->bayar }}</td>
                                <td>{{ $item->status2 }}</td>
                                <td>
                                    <a href="{{ route('pembayaran.create', $item->id_pembelian) }}" class="btn btn-primary btn-xs btn-flat">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>