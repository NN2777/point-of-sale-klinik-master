<div class="modal fade" id="modal-dokter" tabindex="-1" role="dialog" aria-labelledby="modal-dokter">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Dokter</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-dokter">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($dokter as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->telepon }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihDokter('{{ $item->id_dokter }}', '{{ $item->kode_dokter }}')">
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