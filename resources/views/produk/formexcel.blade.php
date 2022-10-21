<div class="modal fade" id="modal-form-excel" tabindex="-1" role="dialog" aria-labelledby="modal-form-excel">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="file_produk" class="col-lg-2 col-lg-offset-1 control-label">Produk</label>
                        <div class="col-lg-6">
                            <input type="file" name="file_produk" id="file_produk" class="form-control" required autofocus>
                            <small class="w-100">nama_kategori, kode_produk, nama_produk, merk, harga_beli, harga_jual_1, harga_jual_2, harga_jual_3, harga_jual_4, stok, diskon</small>
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