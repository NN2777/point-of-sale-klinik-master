<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal">
            @csrf
            @method('post')
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <input type="hidden" name="id_pembelian" id="id_pembelian" class="form-control">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="no_faktur" class="col-lg-2 col-lg-offset-1 control-label">No Faktur</label>
                        <div class="col-lg-6">
                            <input type="text" name="no_faktur" id="no_faktur" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal" class="col-lg-2 col-lg-offset-1 control-label">Tanggal Bayar</label>
                        <div class="col-lg-6">
                            <input type="text" name="tanggal_bayar" id="tanggal_bayar" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bayar" class="col-lg-2 col-lg-offset-1 control-label">Bayar</label>
                        <div class="col-lg-6">
                            <input type="number" name="bayar" id="bayar" class="form-control">
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="dibayar" class="col-lg-2 col-lg-offset-1 control-label">Dibayar</label>
                        <div class="col-lg-6">
                            <input type="number" name="dibayar" id="dibayar" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>