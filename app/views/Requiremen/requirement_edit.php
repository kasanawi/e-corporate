<div class="content-wrapper">

    <!-- Content Header (Page header) Requirement edit-->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url(); ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('pembelian'); ?>">{title}</a></li>
                        <li class="breadcrumb-item active">{subtitle}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
     <section class="content">
        <div class="container-fluid">
            <div class="row">
            <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= $title; ?></h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="form1" action="javascript:save()">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('notrans') ?>:</label>
                                            <input type="text" class="form-control"readonly name="notrans" placeholder="AUTO" value="<?= $edit['notrans']; ?>">
                                        </div>
                                        <div class="form-group" id="rekanan">
                                            <label><?php echo lang('rekanan') ?>:</label>
                                            <select class="form-control kontakid" name="kontakid" disabled></select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('date') ?>:</label>
                                            <div class="input-group"> 
                                                <input type="date" class="form-control datepicker" name="tanggal" required value="<?= $edit['tanggal']; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group" id="gudang">
                                            <label><?php echo lang('gudang') ?>:</label>
                                            <select id="gudang" class="form-control gudangid" name="de"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><?php echo lang('Perusahaan') ?>:</label>
                                            <div class="input-group"> 
                                                <select id="perusahaan" class="form-control perusahaan" name="idperusahaan" required style="width: 100%;"></select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo lang('Departemen') ?>:</label>
                                            <div class="input-group"> 
                                            <select id="department" class="form-control department" name="iddepartemen" required></select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo lang('PIC') ?>:</label>
                                            <div class="input-group"> 
                                            <select id="pejabat" class="form-control pejabat" name="pejabat" required></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                    <div class="form-group">
                                            <label><?php echo lang('Jenis Pembelian') ?>:</label>
                                            <select class="form-control jenis_pembelian" name="jenis_pembelian" required>
                                                <option value="barang" <?= $edit['jenis_pembelian'] == 'barang' ? 'selected' : '' ; ?>>Barang</option>                                   
                                                <option value="jasa" <?= $edit['jenis_pembelian'] == 'jasa' ? 'selected' : '' ; ?>>Jasa</option>
                                                <option value="barang_dan_jasa" <?= $edit['jenis_pembelian'] == 'barang_dan_jasa' ? 'selected' : '' ; ?>>Barang dan Jasa</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo lang('Jenis Barang') ?>:</label>
                                            <select class="form-control jenis_barang" name="jenis_barang" required>
                                                <option value="barang_dagangan" <?= $edit['jenis_barang'] == 'barang_dagangan' ? 'selected' : '' ; ?>>Barang Dagangan</option>
                                                <option value="inventaris" <?= $edit['jenis_barang'] == 'inventaris' ? 'selected' : '' ; ?>>Inventaris</option>    
                                                <option value="barang_dan_jasa" <?= $edit['jenis_barang'] == 'barang_dan_jasa' ? 'selected' : '' ; ?>>Barang dan Jasa</option>                               
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo lang('Cara Pembayaran') ?>:</label>
                                            <select class="form-control cara_pembayaran" name="cara_pembayaran" required>
                                                <option value="cash" <?= $edit['cara_pembayaran'] == 'cash' ? 'selected' : '' ; ?>>Cash</option>
                                                <option value="credit" <?= $edit['cara_pembayaran'] == 'credit' ? 'selected' : '' ; ?>>Credit</option>                                   
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                                <div class="mb-3 mt-3 table-responsive">
                                    <div class="mt-3 mb-3">
                                        <div class="row">
                                            <div class="col-2">
                                                <button type="button" class="btn btn-sm btn-primary btn_add_detail"><i class="fas fa-plus"></i> <?php echo lang('add_new') ?></button>
                                            </div>
                                            <div class="col-10">
                                                <button type="button" class="btn btn-sm btn-primary" style="display:none;" id="tombol_jasa"><i class="fas fa-plus"></i> Tambah Jasa</button>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered" id="table_detail">
                                        <thead class="{bg_header}">
                                            <tr>
                                                <th>ID</th>
                                                <th><?php echo lang('item') ?></th>                                              
                                                <th class="text-right" style="width:50px;"><?php echo lang('qty') ?></th><th class="text-right" style="width:50px;"><?php echo lang('no akun') ?></th>
                                                <!--th class="text-right"><?php // echo lang('total') ?></th-->
                                                <th class="text-right"><?php echo lang('sisa pagu item') ?></th>
                                                <th class="text-center" style="width:50px;"><?php echo lang('action') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody> </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <th>ID</th>
                                                <th colspan="1">&nbsp;</th>
                                                <th class="text-right"><?php //echo lang('total') ?></th>
                                                <th class="text-center" id="total_total2"><?php // "Rp. " . number_format($edit['total'],2,',','.'); ?></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                    
                                        
                                        <div class="form-group">
                                            <!--label><?php echo lang('note') ?>:</label-->
                                            <input type="hidden" class="form-control um" name="um">
                                            <input type="hidden" class="form-control tum" name="tum">
                                            <input type="hidden" class="form-control jtem" name="jtem">
                                            <!--textarea class="form-control catatan" name="catatan" rows="6"></textarea-->
                                        </div>                       
                                    </div>
                                    <div class="col-md-3">
                                       
                                    </div>
                                    <div class="col-md-3">
                                        
                                    </div>
                                </div>
                                <input type="hidden" name="detail_array" id="detail_array">
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="text-left">
                                    <div class="btn-group">
                                        <a href="{site_url}requiremen" class="btn bg-danger"><?php echo lang('cancel') ?></a>
                                        <button type="submit" class="btn bg-success"><?php echo lang('save') ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        </div>
                    <!-- /.card -->
                    </div>
                <!--/.col (left) -->
            <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
     </section>
</div>


<div id="tambah_modal_pajak"></div>

<div id="tambah_modal_pilih_pajak"></div>

<div id="tambah_modal_pilih_pengiriman"></div>
<hr />
<pre id="tampil2"></pre>
<div id="tampil"></div>
<?php 

include "requirement_modal.php";
include "requirement_edit_js_data.php";
include "requirement_edit_js_tabel.php";

//print_r($edit);

?>
