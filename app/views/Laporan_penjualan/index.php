<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{title}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{title}</li>
            </ol>
          </div>
        </div>
        <div class="header-elements d-none">
            <div class="d-flex justify-content-center">
                <div class="btn-group">
					<?php $currentURL = current_url(); ?>
					<?php $params = $_SERVER['QUERY_STRING']; ?>
					<?php $fullURL = $currentURL . '/printpdf?' . $params; ?>
					<?php $fullURLChange = $fullURL ?>
					<?php if ($this->uri->segment(2)): ?>
						<?php $fullURL = $currentURL . '?' . $params; ?>
						<?php $fullURLChange = str_replace('index', 'printpdf', $fullURL) ?>
					<?php endif ?>
					<a href="<?php echo $fullURLChange ?>" target="_blank" class="btn btn-warning"><?php echo lang('print') ?></a>
				</div>
            </div>
        </div>
        <div class="m-3">
            <form target="_blank" action="{site_url}laporan_penjualan/index" id="form1" method="get">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label><?php echo lang('start_date') ?>:</label>
							<input type="date" class="form-control datepicker" name="tanggalawal" required value="{tanggalawal}">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label><?php echo lang('end_date') ?>:</label>
							<input type="date" class="form-control datepicker" name="tanggalakhir" required value="{tanggalakhir}">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label><?php echo lang('contact') ?>:</label>
							<select type="text" class="form-control kontakid" name="kontakid"></select>
						</div>
					</div>
					<!-- <div class="col-md-3">
						<div class="form-group">
							<label><?php echo lang('warehouse') ?>:</label>
							<select type="text" class="form-control gudangid" name="gudangid"></select>
						</div>
					</div> -->
				</div>
				<!-- <div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label><?php echo lang('status') ?>:</label>
							<select type="text" class="form-control status" name="status"></select>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label><?php echo lang('item') ?>:</label>
							<select type="text" class="form-control itemid" name="itemid"></select>
						</div>
					</div>
				</div> -->
				<div class="row">
					<div class="col-md-3">
						<div class="text-right">
							<button type="submit" class="btn-block btn bg-success"><?php echo lang('search') ?></button>
						</div>
						<div class="row mt-2">
							<div class="col-md-6">
								<button class="btn-block btn bg-danger"><i class="fas fa-file-pdf" aria-hidden="true"></i> <?php echo lang('pdf') ?></button>
							</div>
							<div class="col-md-6">
								<button class="btn-block btn bg-success"><i class="fas fa-file-excel" aria-hidden="true"></i> <?php echo lang('xls') ?></button>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						
					</div>
				</div>
			</form>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">         
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-bordered">
						<tbody>
							<?php if ($itemid): ?>
								<?php if ($get_faktur_penjualan): ?>
									<?php $grandtotalpenjualan = 0 ?>
									<?php foreach ($get_faktur_penjualan as $row): ?>
										<tr class="{bg_header}">
											<td colspan="8" class="font-weight-bold">
												Invoice : <?php echo $row['nofaktur'] ?>  | 
												Tanggal : <?php echo formatdateslash($row['tanggal']) ?> | 
												Kepada : <?php echo $row['kontak'] ?>
											</td>
										</tr>
										<tr class="bg-light">
											<td class="text-left"><?php echo lang('item') ?></td>
											<td class="text-left"><?php echo lang('unit') ?></td>
											<td class="text-right"><?php echo lang('price') ?></td>
											<td class="text-right"><?php echo lang('qty') ?></td>
											<td class="text-right"><?php echo lang('subtotal') ?></td>
											<td class="text-right"><?php echo lang('discount') ?></td>
											<td class="text-right"><?php echo lang('ppn') ?></td>
											<td class="text-right"><?php echo lang('total') ?></td>
										</tr>
										<?php $totaldetailpenjualan = 0 ?>
										<?php foreach ($this->model->get_faktur_penjualan_detail($row['id']) as $det): ?>
											<?php if ($itemid == $det['itemid']): ?>
												<?php $totaldetailpenjualan = $totaldetailpenjualan + $det['total'] ?>
												<tr>
													<td> <?php echo $det['item'] ?> </td>
													<td> <?php echo $det['satuan'] ?> </td>
													<td class="text-right"> <?php echo number_format($det['harga']) ?> </td>
													<td class="text-right"> <?php echo number_format($det['jumlah']) ?> </td>
													<td class="text-right"> <?php echo number_format($det['subtotal']) ?> </td>
													<td class="text-right"> <?php echo number_format($det['diskon']) ?>% </td>
													<td class="text-right"> <?php echo number_format($det['ppn']) ?>% </td>
													<td class="text-right"> <?php echo number_format($det['total']) ?> </td>
												</tr>
											<?php endif ?>
										<?php endforeach ?>

										<?php $grandtotalpenjualan = $grandtotalpenjualan + $totaldetailpenjualan ?>
										<tr class="bg-light">
											<td colspan="7" class="text-right font-weight-semibold"><?php echo lang('total') ?></td>
											<td class="text-right font-weight-semibold bg-warning"><?php echo number_format($totaldetailpenjualan) ?></td>
										</tr>
									<?php endforeach ?>
								<?php else: ?>
									<tr>
										<td colspan="8"><?php echo lang('data_not_found') ?></td>
									</tr>
								<?php endif ?>
							<?php else: ?>
								<?php if ($get_faktur_penjualan): ?>
									<?php $grandtotalpenjualan = 0 ?>
									<?php foreach ($get_faktur_penjualan as $row): ?>
										<tr class="{bg_header}">
											<td colspan="8" class="font-weight-bold">
												Invoice : <?php echo $row['nofaktur'] ?>  | 
												Tanggal : <?php echo formatdateslash($row['tanggal']) ?> | 
												Kepada : <?php echo $row['kontak'] ?>
											</td>
										</tr>
										<tr class="bg-light">
											<td class="text-left"><?php echo lang('item') ?></td>
											<td class="text-left"><?php echo lang('unit') ?></td>
											<td class="text-right"><?php echo lang('price') ?></td>
											<td class="text-right"><?php echo lang('qty') ?></td>
											<td class="text-right"><?php echo lang('subtotal') ?></td>
											<td class="text-right"><?php echo lang('discount') ?></td>
											<td class="text-right"><?php echo lang('ppn') ?></td>
											<td class="text-right"><?php echo lang('total') ?></td>
										</tr>
										<?php $totaldetailpenjualan = 0 ?>
										<?php foreach ($this->model->get_faktur_penjualan_detail($row['id']) as $det): ?>
											<?php $totaldetailpenjualan = $totaldetailpenjualan + $det['total'] ?>
											<tr>
												<td> <?php echo $det['item'] ?> </td>
												<td> <?php echo $det['satuan'] ?> </td>
												<td class="text-right"> <?php echo number_format($det['harga']) ?> </td>
												<td class="text-right"> <?php echo number_format($det['jumlah']) ?> </td>
												<td class="text-right"> <?php echo number_format($det['subtotal']) ?> </td>
												<td class="text-right"> <?php echo number_format($det['diskon']) ?>% </td>
												<td class="text-right"> <?php echo number_format($det['ppn']) ?>% </td>
												<td class="text-right"> <?php echo number_format($det['total']) ?> </td>
											</tr>
										<?php endforeach ?>

										<?php $grandtotalpenjualan = $grandtotalpenjualan + $totaldetailpenjualan ?>
										<tr class="bg-light">
											<td colspan="7" class="text-right font-weight-semibold"><?php echo lang('total') ?></td>
											<td class="text-right font-weight-semibold"><?php echo number_format($totaldetailpenjualan) ?></td>
										</tr>
									<?php endforeach ?>
								<?php else: ?>
									<tr>
										<td colspan="8"><?php echo lang('data_not_found') ?></td>
									</tr>
								<?php endif ?>
							<?php endif ?>
						</tbody>
						<?php if ($get_faktur_penjualan): ?>
							<tfoot>
								<tr class="bg-success">
									<td colspan="7" class="text-right font-weight-semibold"><?php echo lang('grand_total') ?></td>
									<td class="text-right font-weight-semibold bg-success"><?php echo number_format($grandtotalpenjualan) ?></td>
								</tr>
							</tfoot>
						<?php endif ?>
					</table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<script src="{assets_path}global/js/plugins/notifications/pnotify.min.js"></script>
<script src="{assets_path}global/js/plugins/forms/selects/select2.full.min.js"></script>
<script type="text/javascript">
	var base_url = '{site_url}laporan_penjualan/';

    $(document).ready(function(){
	    $('.status').select2({
	        placeholder: 'Select an Option',
	        data: [
	            {id: '', text: 'Semua'},
	            {id: '2', text: 'Proses'},
	            {id: '3', text: 'Selesai'},
	        ]
	    }).val('{status}').trigger('change');
        ajax_select({ id: '.kontakid', url: base_url + 'select2_kontakid', selected: { id: '{kontakid}' } });
        ajax_select({ id: '.gudangid', url: base_url + 'select2_gudangid', selected: { id: '{gudangid}' } });
        ajax_select({ id: '.itemid', url: base_url + 'select2_itemid', selected: { id: '{itemid}' } });
    })
</script>