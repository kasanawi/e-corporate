<div id="modal_add_detail" class="modal fade">
    <div class="modal-dialog modal-lg">
        <form action="javascript:save_detail(0, 'barang')" id="form2">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">C910<?php echo lang('add_new') ?></h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="rowindex">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo lang('item') ?>:</label>
                                <select class="form-control itemid" name="itemid[]" required style="width:100%" multiple>
                                </select>
                            </div>
                        </div>
                        <table class="table">
                            <tbody id='list_barang'>

                            </tbody>
                        </table>
                        <div id="detail_barang"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('cancel') ?></button>
                        <button type="submit" class="btn btn-success"><?php echo lang('save') ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal_add_jasa" class="modal fade">
    <div class="modal-dialog modal-lg">
        <form action="javascript:save_detail(0, 'jasa')" id="form_jasa">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jasa</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="rowindex">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Jasa :</label>
                                <select class="form-control id_jasa" name="id_jasa[]" required style="width:100%" multiple>
                                </select>
                            </div>
                        </div>
                        <table class="table">
                            <tbody id='list_jasa'>

                            </tbody>
                        </table>
                        <div id="detail_jasa"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('cancel') ?></button>
                        <button type="submit" class="btn btn-success"><?php echo lang('save') ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="modal_edit_detail" class="modal fade">
    <div class="modal-dialog">
        <form action="javascript:edit_detail_barang()" id="form3" enctype="multipart/form-data" method="POST">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title">Edit Detail C910</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="edit_rowindex">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo lang('item') ?>:</label>
                                <select class="form-control edit_itemid" name="edit_itemid[]" required style="width:100%">
                                </select>
                            </div>
                        </div>
                        <table class="table">
                            <tbody id='edit_list_barang'>

                            </tbody>
                        </table>
                        <div id="edit_detail_barang"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('cancel') ?></button>
                        <button type="submit" class="btn btn-success">Edit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script> 
 var modal_pajak = `<div class="modal fade" id="modal_pajak${data['index']}${data['no']}">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Pajak</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="form_pajak${data['index']}${data['no']}" action="javascript:total_pajak('${data['index']}${data['no']}', '${data['no']}')" enctype="multipart/form-data" method="POST">
                                <div class="modal-body">
                                    <div class="mb-3 mt-3 table-responsive">
                                        <div class="mt-3 mb-3">
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_pilih_pajak${data['index']}${data['no']}" id="pilih_pajak">
                                                <i class="fas fa-plus"></i>Pilih Pajak
                                            </button>
                                        </div>
                                        <table class="table table-bordered" style="width:100%" id="pajak">
                                            <thead class="{bg_header}">
                                                <tr>
                                                    <th class="text-right">Nama Pajak</th>
                                                    <th class="text-right">Kode Akun</th>
                                                    <th class="text-right">Nama Akun</th>
                                                    <th class="text-right">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="isi_tbody_pajak${data['index']}${data['no']}">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>`;

var modal_pilih_pajak   = `<div class="modal fade" id="modal_pilih_pajak${data['index']}${data['no']}">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Pilih Pajak</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table">
                                                    <thead class="{bg_header}">
                                                        <tr>
                                                            <th>&nbsp;</th>
                                                            <th>Kode Pajak</th>
                                                            <th>Nama Pajak</th>
                                                            <th>Kode Akun</th>
                                                            <th>Nama Akun</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id='list_pajak${data['index']}${data['no']}'>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Oke</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
var modal_pengiriman = `
                <div class="modal fade" id="modal_pengiriman${data['index']}${data['no']}">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Biaya Pengiriman</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="form_pengiriman${data['index']}${data['no']}" action="javascript:total_pengiriman('${data['index']}${data['no']}', '${data['no']}')" enctype="multipart/form-data" method="POST">
                            <div class="modal-body">
                                <div class="mb-3 mt-3 table-responsive">
                                    <div class="mt-3 mb-3">
                                        <select class="form-control pilih_akun" name="noakun" required id="noakun${data['index']}${data['no']}" multiple data-id="${data['index']}${data['no']}" onselect="pilih_pengiriman(e)"></select>
                                    </div>
                                    <table class="table table-bordered" style="width:100%" id="pengiriman">
                                        <thead class="{bg_header}">
                                            <tr>
                                                <th class="text-right">Kode Akun</th>
                                                <th class="text-right">Nama Akun</th>
                                                <th class="text-right">Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody id="isi_tbody_pengiriman${data['index']}${data['no']}">
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>`;
</script>
