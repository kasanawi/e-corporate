<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title><?php echo $title ?></title>
    <style type="text/css"> <?php echo $css ?> </style>
</head>
<style type="text/css">
    body
    {
          font-family: Arial, Helvetica, sans-serif;
    }
</style>
<body>
    <div class="float-left">
        <h3 class="m-1 font-weight-bold">PT ARGA BANGUN BANGSA</h3>
    </div>
    <div class="clearfix"></div>
    <hr class="hr">
    <div align="center">
        <h2 class="mb-0">PURCHASE ORDER</h2>
    </div> 
    <div class="w-25 p-0">
        <table class="table table-sm">
            <tbody>
                <tr>
                    <td style="font-size: 13px;">Menara 165 lt 24<br>
                        Jl. TB Simatupang Kav 1<br>
                        Cilandak Jakarta Selatan<br>
                        Phone (021) 29406969</td>
                </tr>
            </tbody>
        </table>
    </div>
 
    <div style="width: 30%;">
        <table class="table table-sm">
            <tbody>
                <tr>
                    <td class="font-weight-bold" width="80px">Nomor PSB : </td>
                    <td ></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Nomor PO :</td>
                    <td><?php echo $notrans ?></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">Tanggal PO :</td>
                    <td><?php echo formatdateslash($tanggal) ?></td>
                </tr>
            </tbody>
        </table>
    </div> 
        <p>NOMOR PO INI HARUS TERDAPAT PADA SHIPING PAPERS, DAN INVOICE :</p>
    <table class="table table-bordered">
        <tr>
            <td>Kita Stationary</td>
            <td>
                <b>PENGIRIMAN DITUJUKAN KEPADA :</b><br>
                Menara 165 Lt 24<br>
                Jl TB Simatupang Kav 1<br>
                Cilandak Jakarta Selatan<br>
            </td>
        </tr>
    </table>
    <div class="w-100">
        <table class="table table-sm table-border-bottom">
            <thead class="bg-light">
                <tr>
                    <th><?php echo lang('item') ?></th>
                    <th class="text-right"><?php echo lang('price') ?></th>
                    <th class="text-right"><?php echo lang('qty') ?></th>
                    <th class="text-right"><?php echo lang('subtotal') ?></th>
                    <th class="text-right"><?php echo lang('discount') ?></th>
                    <th class="text-right"><?php echo lang('ppn') ?></th>
                    <th class="text-right"><?php echo lang('total') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $grandtotal = 0; ?>
                <?php foreach ($pemesanandetail as $row): ?>
                    <?php $grandtotal = $row['total'] + $grandtotal ?>
                    <tr>
                        <td><?php echo $row['item'] ?></td>
                        <td class="text-right"><?php echo 'Rp. ' . number_format($row['harga']). ',00' ?></td>
                        <td class="text-right"><?php echo 'Rp. ' . number_format($row['jumlah']) ?></td>
                        <td class="text-right"><?php echo 'Rp. ' . number_format($row['subtotal']). ',00' ?></td>
                        <td class="text-right"><?php echo 'Rp. ' . number_format($row['diskon']) . ' %' ?></td>
                        <td class="text-right"><?php echo 'Rp. ' . number_format($row['ppn']). ',00' ?></td>
                        <td class="text-right"><?php echo 'Rp. ' . number_format($row['total']). ',00' ?></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div>
        <table class="table table-sm table-bordered">
            <tbody>
                <tr>
                    <td rowspan="4" colspan="3" width="70%">
                        <b>
                        1. Terdiri dari 3 lembar, Asli (Putih) untuk Vendor, Kuning <br>
                         Accounting, Merah Kepala divisi. <br>
                        2. Harap dilampirkan tanggal jatuh tempo pembayaran Invoice. <br>
                        3. Pembayaran dilakukan secara Transfer Rekening. <br>
                        4. Pengiriman barang beserta Invoice ditujukan pada : <br>
                         Kepada : ....... <br>
                        </b>
                        <br>
                        ESQ-Leadership Center
                        <br><br>
                        Menara 165 Lt 24, Jalan TB Simatupang KAV 1 Cilandak Jakarta selatan
                        Telp 021 29406969
                    </td>
                    <td><?php echo lang('subtotal') ?></td>
                    <td class="text-right font-weight-bold"><?php echo 'Rp. ' . number_format($subtotal) . ',00' ?></td>
                </tr>
                <tr>
                    <td><?php echo lang('discount') ?></td>
                    <td class="text-right font-weight-bold"><?php echo 'Rp. ' . number_format($diskon) ?> %</td>
                </tr>
                <tr>
                    <td><?php echo lang('ppn') ?></td>
                    <td class="text-right font-weight-bold"><?php echo 'Rp. ' . number_format($ppn)  . ',00'?></td>
                </tr>
                <tr>
                    <td><?php echo lang('total') ?></td>
                    <td class="text-right font-weight-bold"><?php echo 'Rp. ' . number_format($total)  . ',00'?></td>
                </tr>
                <tr>
                    <td>
                        <p class="mb-5">Membuat</p>
                        <p>Tgl : </p>
                    </td>
                    <td>
                        <p class="mb-5">Mengetahui</p>
                        <p>Tgl : </p>
                    </td>
                    <td colspan="3" rowspan="2">
                        Catatan
                    </td>
                </tr>
                <tr>
                    <td>Purchasing</td>
                    <td></td>
                </tr>
            </tbody>
        </table>    
    </div> 
</body>

</html>