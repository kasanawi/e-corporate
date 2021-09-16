
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
            <li class="breadcrumb-item"><a href="{site_url}Pemesanan_pembelian">Pemesanan Pembelian</a></li>
            <li class="breadcrumb-item active">{title}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{title}</h3>
        </div>
          <!-- /.card-header -->
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6"></div>
            <div class="col-md-6 text-right">
              <?php if ($status == '4'): ?>
                <h1 class="text-danger font-weight-bold text-uppercase"><?php echo lang('pending') ?></h1>
              <?php else: ?>
                <h1 class="text-success font-weight-bold text-uppercase"><?php echo lang('done') ?></h1>
              <?php endif ?>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <table class="table">
                <tbody>
                  <tr>
                    <td><?php echo lang('notrans') ?></td>
                    <td class="font-weight-bold">{notrans}</td>
                  </tr>
                  <tr>
                    <td><?php echo lang('date') ?></td>
                    <td class="font-weight-bold">{tanggal}</td>
                  </tr>
                  <tr>
                    <td><?php echo lang('supplier') ?></td>
                    <td class="font-weight-bold">
                        <select id="kontakid" class="form-control kontakid" name="kontakid" required></select>
                    </td>
                  </tr>
                  <tr>
                    <td><?php echo lang('warehouse') ?></td>
                    <td class="font-weight-bold"><?php echo $gudang['nama'] ?></td>
                  </tr>
                  <tr>
                    <td><?php echo lang('note') ?></td>
                    <td class="font-weight-bold">{catatan}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><?php echo lang('subtotal') ?></td>
                            <td class="text-right font-weight-bold"><?= number_format($subtotal,0,',','.'); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo lang('discount') ?></td>
                            <td class="text-right font-weight-bold"><?= $diskon; ?> %</td>
                        </tr>
                        <tr>
                            <td><?php echo lang('ppn') ?></td>
                            <td class="text-right font-weight-bold"><?= number_format($ppn,0,',','.'); ?></td>
                        </tr>
                        <tr class="bg-light">
                            <td><?php echo lang('total') ?></td>
                            <td class="text-right font-weight-bold"><?= number_format($total,0,',','.'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
          </div>
          <form id="form-detail">
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-xs table-striped table-borderless table-hover" id="table-detail">
                  <thead>
                    <tr class="table-active">
                      <th><?php echo lang('item') ?></th>
                      <th class=""><?php echo lang('price') ?></th>
                      <th class=""><?php echo lang('qty') ?></th>
                      <th class=""><?php echo lang('subtotal') ?></th>
                      <th class="text-right"><?php echo lang('discount') ?></th>
                      <th class="text-right">Pajak</th>
                      <th class="text-right">Biaya Pengiriman</th>
                      <th class="text-right">No Akun</th>
                      <th class="text-right"><?php echo lang('total') ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $grandtotal = 0; ?>
                    <?php foreach ($pemesanandetail as $row): ?>
                      <?php $grandtotal = $row['total'] + $grandtotal ?>
                      <tr>
                        <td>
                          <input type="hidden" name="row[]" value="<?= $row['id'] ?>">
                          <input type="hidden" name="item[]" value="<?= $row['itemid'] ?>">
                          <input type="hidden" name="akunno[]" value="<?= $row['akunno'] ?>">
                          <?php echo $row['item'] ?>
                        </td>
                        <td class="text-right">
                          <input type="text" name="harga[]" data-row="<?= $row['id'] ?>" class="form-control harga" value="<?= number_format($row['harga'],0,',','.'); ?>">
                        </td>
                        <td class="text-right">
                          <input type="text" name="jumlah[]" data-row="<?= $row['id'] ?>" class="form-control jumlah" value="<?= number_format($row['jumlah']) ?>">
                        </td>
                        <td class="text-right">
                          <input type="text" class="form-control subtotal" data-row="<?= $row['id'] ?>" name="subtotal[]" value="<?= number_format($row['subtotal'],0,',','.'); ?>" readonly>
                        </td>
                        <td class="text-right">
                          <div class="input-group">
                            <input type="number" name="diskon[]" data-row="<?= $row['id'] ?>" class="form-control diskon" value="<?= number_format($row['diskon']) ?>" style="width: 5rem;">
                            <div class="input-group-append p-1">%</div>
                          </div>
                        </td>
                        <td class="text-right">
                          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPajak<?= $row['id']; ?>" title="Detail Pajak">
                              <i class="fas fa-balance-scale"></i>
                          </button>
                          <?php
                            $uses = [];

                            foreach($pajak as $pjk) {
                              $uses[$pjk['id_pajak']] = $pjk;
                            }

                            $pajakVal = array_map(function($pjk) use($uses) {
                              $pajak = $uses[$pjk['idPajak']];
                              
                              return [
                                'id_pajak' => $pajak['id_pajak'],
                                'kode_pajak' => $pajak['kode_pajak'],
                                'nama_pajak' => $pajak['nama_pajak'],
                                'akun' => $pajak['akun'],
                                'persen' => $pajak['persen'],
                                'idakun' => $pajak['idakun'],
                                'akunno' => $pajak['akunno'],
                                'namaakun' => $pajak['namaakun'],
                                'nominal' => $pjk['nominal']
                              ];
                            }, $row['pajak']);
                          ?>
                          <input type="hidden" id="pajak<?= $row['id'] ?>" value='<?= json_encode($pajakVal) ?>' data-row="<?= $row['id'] ?>" name="pajak[]">
                          <div class="modal fade" id="modalPajak<?= $row['id']; ?>">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Pajak</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <form id="form_pajak" action="javascript:total_pajak('', '${no}')" enctype="multipart/form-data" method="POST">
                                  <div class="modal-body">
                                    <div class="d-flex justify-content-start">
                                      <button type="button" class="btn btn-sm btn-primary mb-2" data-toggle="modal" data-target="#modalPilihPajak<?= $row['id'] ?>">
                                        <i class="fas fa-plus"></i> Pilih pajak
                                      </button>
                                    </div>
                                    <div class="table-responsive">
                                      <table class="table table-xs table-striped table-borderless table-hover index_datatable" style="width:100%" id="pajak">
                                        <thead>
                                          <tr class="table-active">
                                            <th>Nama Pajak</th>
                                            <th>Kode Akun</th>
                                            <th>Nama Akun</th>
                                            <th>Nominal</th>
                                          </tr>
                                        </thead>
                                        <tbody class="table-list-pajak" data-row="<?= $row['id'] ?>">
                                          <?php
                                            if ($row['pajak']) {
                                              foreach ($row['pajak'] as $key) { ?>
                                                <tr>
                                                  <td><?= $key['nama_pajak']; ?></td>
                                                  <td><?= $key['akunno']; ?></td>
                                                  <td><?= $key['namaakun']; ?></td>
                                                  <td>
                                                    <?php 
                                                      switch ($key['pengurangan']) {
                                                        case '0':
                                                          echo number_format($key['nominal'],0,',','.');
                                                          break;
                                                        case '1':
                                                          echo '-' . number_format($key['nominal'],0,',','.');
                                                          break;
                                                        
                                                        default:
                                                          # code...
                                                          break;
                                                      }
                                                    ?>
                                                  </td>
                                                </tr>
                                              <?php }
                                            }
                                          ?>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          <div class="modal fade" class="modal-pilih-pajak" data-row="<?= $row['id'] ?>" id="modalPilihPajak<?= $row['id'] ?>">
                          <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Pilih Pajak</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-hover text-center">
                                      <thead>
                                        <tr>
                                          <th></th>
                                          <th>Kode Pajak</th>
                                          <th>Nama Pajak</th>
                                          <th>Kode Akun</th>
                                          <th>Nama Akun</th>
                                          <th>Tarif %</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php foreach($pajak as $pjk):
                                          $list = array_map(function($pjk1) {
                                            return $pjk1['idPajak'];
                                          }, $row['pajak']);
                                          $isExist = in_array($pjk['id_pajak'], $list);
                                          ?>
                                          <tr>
                                            <td>
                                              <input type="checkbox" class="check-pajak" data-row="<?= $row['id'] ?>" value='<?= json_encode($pjk) ?>' <?= ($isExist) ? 'checked' : '' ?>>
                                            </td>
                                            <td><?= $pjk['kode_pajak'] ?></td>
                                            <td><?= $pjk['nama_pajak'] ?></td>
                                            <td><?= $pjk['akunno'] ?></td>
                                            <td><?= $pjk['namaakun'] ?></td>
                                            <td><?= $pjk['persen'] ?></td>
                                          </tr>
                                        <?php endforeach; ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Oke</button>
                                </div>
                              </div>
                            </div>  
                          </div>
                        </td>
                        <td class="text-right">
                          <input type="hidden" id="ongkir<?= $row['id'] ?>" value="[]">
                          <input type="hidden" id="totalOngkir<?= $row['id'] ?>" value="0" name="ongkir[]">
                          <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalPilihOngkir<?= $row['id'] ?>">
                            <i class="fas fa-shipping-fast"></i>
                          </button>
                          <div class="modal fade modal-pilih-ongkir" id="modalPilihOngkir<?= $row['id'] ?>">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Biaya Pengiriman</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form class="form-ongkir" data-row="<?= $row['id'] ?>">
                                  <div class="modal-body">
                                    <div class="mb-3 mt-3 table-responsive">
                                      <div class="mt-3 mb-3">
                                        <select class="form-control pilih_akun select-ongkir" name="noakun" data-row="<?= $row['id'] ?>" multiple></select>
                                      </div>
                                      <table class="table table-bordered" style="width:100%" id="pengiriman">
                                        <thead class="{bg_header}">
                                          <tr>
                                            <th class="text-right">Kode Akun</th>
                                            <th class="text-right">Nama Akun</th>
                                            <th class="text-right">Nominal</th>
                                          </tr>
                                        </thead>
                                        <tbody class="table-ongkir" id="table-ongkir<?= $row['id'] ?>">
                                            
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
                          </div>
                        </td>
                        <td class="text-right"><?= $row['akunno']; ?></td>
                        <td class="text-right">
                          <input type="text" class="form-control total" data-row="<?= $row['id'] ?>" value="<?= number_format($row['total'],0,',','.'); ?>" name="total[]" readonly>
                        </td>
                      </tr>
                    <?php endforeach ?>
                  </tbody>
                    <tfoot>
                      <tr class="table-active">
                        <td class="font-weight-bold text-right" colspan="8"><?php echo lang('grand_total') ?></td>
                        <td class="font-weight-bold text-right" id="grandTotal"><?= number_format($grandtotal,0,',','.'); ?></td>
                      </tr>
                    </tfoot>
                </table>
              </div>
            </div>
          </div>
          </form>
          <form action="javascript:save()" id="form">
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label><?php echo lang('Uang Muka') ?>:</label>
                  <input type="hidden" value="<?= $this->uri->segment(3); ?>" name="idpemesanan">
                  <input class="form-control um" name="um" id="um" onkeyup="format('um'), hitungtum()" value="<?= $angsuran['uangmuka'] !== '' ? number_format($angsuran['uangmuka'],0,',','.') : "" ; ?>">
                </div>
                <div class="row mb-3">                            
                  <div class="col-md-6">
                    <div class="form-group">
                      <label><?php echo lang('Total Uang Muka + Term') ?>:</label>
                      <div class="alert alert-danger alert-dismissible" style="display:none" id="alertjumlah">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        Jumlah Total dan Jumlah Uang Muka tidak sama
                      </div>
                      <input type="hidden" name="id_angsuran" readonly value="<?= $angsuran['id']; ?>">
                      <input class="form-control tum" name="tum" readonly value="<?= number_format($angsuran['total'],0,',','.'); ?>">
                    </div>
                  </div> 
                  <div class="col-md-3">                       
                    <div class="form-group">
                      <label><?php echo lang('Jumlah Term') ?>:</label>
                      <input class="form-control jtem" name="jtem" readonly value="<?= $angsuran['jumlahterm'] !== '' ? number_format($angsuran['jumlahterm'],0,',','.') : "" ; ?>">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label><?php echo lang('note') ?>:</label>
                  <textarea class="form-control catatan" name="catatan" rows="6"></textarea>
                </div>                       
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label><?php echo lang('Term 1') ?>:</label>
                  <input type="text" class="form-control" name="a1" placeholder="Angsuran 1" id="a1" onkeyup="format('a1'), hitungterm(), hitungtum()" value="<?= $angsuran['a1'] !== '' ? number_format($angsuran['a1'],0,',','.') : "" ; ?>">
                </div>
                <div class="form-group">
                  <label><?php echo lang('Term 2') ?>:</label>
                  <input type="text" class="form-control" name="a2" placeholder="Angsuran 2" id="a2" onkeyup="format('a2'), hitungterm(), hitungtum()" value="<?= $angsuran['a2'] !== '' ? number_format($angsuran['a2'],0,',','.') : "" ; ?>">
                </div>
                <div class="form-group">
                  <label><?php echo lang('Term 3') ?>:</label>
                  <input type="text" class="form-control" name="a3" placeholder="Angsuran 3" id="a3" onkeyup="format('a3'), hitungterm(), hitungtum()" value="<?= $angsuran['a3'] !== '' ? number_format($angsuran['a3'],0,',','.') : "" ; ?>">
                </div>
                <div class="form-group">
                  <label><?php echo lang('Term 4') ?>:</label>
                  <input type="text" class="form-control" name="a4" placeholder="Angsuran 4" id="a4" onkeyup="format('a4'), hitungterm(), hitungtum()" value="<?= $angsuran['a4'] !== '' ? number_format($angsuran['a4'],0,',','.') : "" ; ?>">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label><?php echo lang('Term 5') ?>:</label>
                  <input type="text" class="form-control" name="a5" placeholder="Angsuran 5" id="a5" onkeyup="format('a5'), hitungterm(), hitungtum()" value="<?= $angsuran['a5'] !== '' ? number_format($angsuran['a5'],0,',','.') : "" ; ?>">
                </div>
                <div class="form-group">
                  <label><?php echo lang('Term 6') ?>:</label>
                  <input type="text" class="form-control" name="a6" placeholder="Angsuran 6" id="a6" onkeyup="format('a6'), hitungterm(), hitungtum()" value="<?= $angsuran['a6'] !== '' ? number_format($angsuran['a6'],0,',','.') : "" ; ?>">
                </div>
                <div class="form-group">
                  <label><?php echo lang('Term 7') ?>:</label>
                  <input type="text" class="form-control" name="a7" placeholder="Angsuran 7" id="a7" onkeyup="format('a7'), hitungterm(), hitungtum()" value="<?= $angsuran['a7'] !== '' ? number_format($angsuran['a7'],0,',','.') : "" ; ?>">
                </div>
                <div class="form-group">
                  <label><?php echo lang('Term 8') ?>:</label>
                  <input type="text" class="form-control" name="a8" placeholder="Angsuran 8" id="a8" onkeyup="format('a8'), hitungterm(), hitungtum()" value="<?= $angsuran['a8'] !== '' ? number_format($angsuran['a8'],0,',','.') : "" ; ?>">
                </div>
              </div>
            </div>
            <div class="text-left">
              <div class="btn-group">
                <a href="{site_url}requiremen" class="btn bg-danger"><?php echo lang('cancel') ?></a>
                <button type="submit" class="btn bg-success"><?php echo lang('save') ?></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

<script>
  // Datatable
  var table_detail = $('#table-detail').DataTable({
    sort: false,
    info: false,
    searching: false,
    paging: false,
    footerCallback: function(row, data, start, end, display) {
      var api = this.api(), data;

      var intVal  = function (i) {
        const row = $($.parseHTML(i)).data('row');
        return parseInt($(`.total[data-row=${row}]`).val().replace(/[^,\d]/g, '').toString());
      };
      
      total = api.column(8).data().reduce( function (a, b) {
        return intVal(b) + a;
      }, 0 );


      $('#grandTotal').text(formatRupiah(String(total)));
    }
  });

  // Fungsi generate subtotal
    function sum_subtotal(row) {
      const harga = parseInt($(`.harga[data-row=${row}]`).val().replace(/[^,\d]/g, '').toString());
      const jumlah = parseInt($(`.jumlah[data-row=${row}]`).val().replace(/[^,\d]/g, '').toString());
      const subtotal = harga * jumlah;

      $(`.subtotal[data-row=${row}]`).val(formatRupiah(String(subtotal)));
      return subtotal;
    }

    // Fungsi untuk mengembalikan diskon
    function sum_discount(row, total)
    {
      const diskon = parseInt($(`.diskon[data-row=${row}]`).val().replace(/[^,\d]/g, '').toString());
      return (total * diskon) / 100;
    }

    // Fungsi untuk menampilkan list pajak
    function sum_pajak(row, total)
    {
      const pajaks = JSON.parse($(`#pajak${row}`).val());
      let totalPajak = 0;

      $(`.table-list-pajak[data-row=${row}]`).html('');

      pajaks.forEach((pajak, index) => {
        let nominal = (total * pajak.persen) / 100;
        pajaks[0].nominal = nominal;
        
        if(isNaN(nominal)) nominal = 0;
        totalPajak += nominal;

        $(`.table-list-pajak[data-row=${row}]`).prepend(`
        <tr>
          <td>${pajak.nama_pajak}</td>
          <td>${pajak.akunno}</td>
          <td>${pajak.namaakun}</td>
          <td>${formatRupiah(String(nominal))}</td>
        </tr>
        `);
      });

      $(`#pajak${row}`).val(JSON.stringify(pajaks));
      return totalPajak;
    } 

    // Fungsi generate total
    function sum_total(row) {
      const subtotal = sum_subtotal(row);
      const diskon = sum_discount(row, subtotal);
      const ongkir = parseInt($(`#totalOngkir${row}`).val());

      let total = subtotal - diskon;

      const pajak = sum_pajak(row, total);
      total = total + pajak + ongkir;
      
      $(`.total[data-row=${row}]`).val(formatRupiah(String(total)));
      $(`.totalasli[data-row=${row}]`).val(formatRupiah(String(total)));
      sum_grandtotal();
      hitungtum();
    }

    function sum_grandtotal()
    {
      let grandTotal = 0;

      $('.total').map((i, e) => {
        const total = parseInt($(e).val().replace(/[^,\d]/g, '').toString())
        grandTotal += total;
      });

      $('#grandTotal').text(formatRupiah(String(grandTotal)));
    }

    // Fungsi untuk memanage pajak
    function manage_pajak(row, pajak, isRemove) {
      const pajaks = JSON.parse($(`#pajak${row}`).val());
      const total = parseInt($(`.total[data-row=${row}]`).val().replace(/[^,\d]/g, '').toString());

      pajak.nominal = total * parseInt(pajak.persen) / 100;

      if(isRemove) {
        const index = pajaks.findIndex(pjk => pjk.id_pajak == pajak.id_pajak);
        pajaks.splice(index, 1);
      } else {
        pajaks.push(pajak);
      }

      $(`#pajak${row}`).val(JSON.stringify(pajaks));
    }

    function manage_ongkir(row, akun) {
      const ongkirs = JSON.parse($(`#ongkir${row}`).val());
      const ongkir = {
        no: akun.akunno,
        nama: akun.namaakun
      }

      ongkirs.push(ongkir);
      $(`#ongkir${row}`).val(JSON.stringify(ongkirs));

      $(`#table-ongkir${row}`).append(`
        <tr>
          <td>${ongkir.no}</td>
          <td>${ongkir.nama}</td>
          <td>
            <input class="form-control input-ongkir float-right" type="text" style="width: 15rem;" data-row="${row}">
          </td>
        </tr>
      `);
    }

    /* ======== Batas area fungsi ============= */

  // Saat harga diubah
    $('.harga').on('keyup', function() {
      const row = $(this).data('row');
      sum_total(row);

      $(this).val(formatRupiah($(this).val()));
    });

    // Saat jumlah dirubah
    $('.jumlah').on('keyup', function() {
      const row = $(this).data('row');
      sum_total(row);

      $(this).val(formatRupiah($(this).val()));
    });

    // Saat diskon dirubah
    $('.diskon').on('keyup', function() {
      const row = $(this).data('row');
      sum_total(row);
    });

    // Saat checkbox pajak diklik
    $('.check-pajak').on('click', function() {
      const row = $(this).data('row');
      const val = $(this).val();
      const pajak = JSON.parse(val);
      const isRemove = $(this).is(':checked');

      manage_pajak(row, pajak, !isRemove);
      sum_total(row);
    });

    /* =========== ONGKIR SECTION ========
      ======================================
    */

    ajax_select({
      id: '.select-ongkir',
      url: "{site_url}pajak/select2_noakun"
    });

    $('.select-ongkir').on('change', function(e) {
      const ids = $(this).val();
      const row = $(this).data('row');

      $(`#ongkir${row}`).val('[]');
      $(`#table-ongkir${row}`).html('');
      $(`#totalOngkir${row}`).val('0');
      sum_total(row);

      ids.map(async (id) => {
        await $.ajax({
          url: '{site_url}noakun/get',
          method: 'post',
          dataType: 'json',
          data: {
            id: id
          },
          success: function(data) {
            manage_ongkir(row, data);
          }
        });
      });
    });

    $('.table-ongkir').on('keyup', '.input-ongkir', function(e) {
      const value = $(this).val().replace(/[^,\d]/g, '').toString();

      $(this).val(formatRupiah(value));
    });

    $('.form-ongkir').on('submit', function(e) {
      let total = 0;
      const row = $(this).data('row');
      e.preventDefault();

      $(`.input-ongkir[data-row=${row}`).map((i, el) => {
        const val = parseInt($(el).val().replace(/[^,\d]/g, '').toString());
        total += val;
      });

      $(`#totalOngkir${row}`).val(total);
      sum_total(row);

      $(`#modalPilihOngkir${row}`).modal('toggle');
    });


    /* ========================= */

    var base_url    = '{site_url}requiremen/';
    var kontak      = '<?= $kontakid; ?>'

    $(document).ready(function(){
        ajax_select({ 
            id          : '.kontakid', 
            url         : base_url + 'select2_kontak', 
            selected    : { 
                id  : kontak 
                } 
        });
    });

    $(document).on('select2:select','.kontakid',function(e){
        var kontakid    = e.params.data.id;
        var idpemesanan = '<?= $this->uri->segment(3); ?>';
        $.ajax({
            url: base_url + 'update_kontakid/' + idpemesanan,
            method: 'post',
            datatype: 'json',
            data: {
                kontakid: kontakid
            }
        })
    })

    function save() {
        const form = $('#form').serialize();
        const detail = $('#form-detail').serialize();
        const grandTotal = $('#grandTotal').text();

        $.ajax({
            url: base_url + 'tambah_angsuran',
            dataType: 'json',
            method: 'post',
            data: `${form}&${detail}&grandtotal=${grandTotal}`,
            beforeSend: function() {
                pageBlock();
            },
            afterSend: function() {
                unpageBlock();
            },
            success: function(data) {
                if(data.status == 'success') {
                    swal("Berhasil!", "Berhasil Mengupdate Data", "success");
                    redirect('{site_url}pemesanan_pembelian');
                } else {
                    swal("Gagal!", data.message, "error");
                }
            },
            error: function() {
                swal("Gagal!", "Internal Server Error", "error");
            }
        })
    }

    function format(id) {
      var angka   = $('#'+id).val()
      $('#'+id).val(formatRupiah(String(angka)));
    }

    function hitungtum() {
      var um      = parseInt($('#um').val().replace(/[Rp. ]/g, ''));
      var jtem    = parseInt($('.jtem').val().replace(/[Rp. ]/g, ''));
      const grandTotal = parseInt($('#grandTotal').text().replace(/[^,\d]/g, '').toString());
      if (isNaN(jtem)) {
          jtem = 0;
      }
      if (isNaN(um)) {
          um = 0;
      }
      tum = um + jtem;
      $('.tum').val(formatRupiah(String(tum)));

      if (tum !== grandTotal) {
          $('#alertjumlah').css('display', 'block');
      } else {
          $('#alertjumlah').css('display', 'none');
      }
    }

    function hitungterm() {
      var a1  = parseInt($('#a1').val().replace(/[Rp. ]/g, ''));
      var a2  = parseInt($('#a2').val().replace(/[Rp. ]/g, ''));
      var a3  = parseInt($('#a3').val().replace(/[Rp. ]/g, ''));
      var a4  = parseInt($('#a4').val().replace(/[Rp. ]/g, ''));
      var a5  = parseInt($('#a5').val().replace(/[Rp. ]/g, ''));
      var a6  = parseInt($('#a6').val().replace(/[Rp. ]/g, ''));
      var a7  = parseInt($('#a7').val().replace(/[Rp. ]/g, ''));
      var a8  = parseInt($('#a8').val().replace(/[Rp. ]/g, ''));
      if (isNaN(a1)) {
        a1 = 0;
      }
      if (isNaN(a2)) {
        a2 = 0;
      }
      if (isNaN(a3)) {
        a3 = 0;
      }
      if (isNaN(a4)) {
        a4 = 0;
      }
      if (isNaN(a5)) {
        a5 = 0;
      }
      if (isNaN(a6)) {
        a6 = 0;
      }
      if (isNaN(a7)) {
        a7 = 0;
      }
      if (isNaN(a8)) {
        a8 = 0;
      }
      jtem  = a1 + a2 + a3 + a4 + a5 + a6 + a7 + a8;
      $('.jtem').val(formatRupiah(String(jtem)));
    }
</script>