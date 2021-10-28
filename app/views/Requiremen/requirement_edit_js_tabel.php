<?php
/* table_detail.row.add([
                barang[index].value ,
                item + `<input type="hidden" class="form-control"  name="item2[]" id="item2${index}${no}" value="`+ barang[index].value +`">
				<input type="text" name="item[]" id="" value="${item}">`,
                `<input type="hidden" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="harga[]" id="harga${index}${no}">
				<input type="text" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="jumlah[]" id="jumlah${index}${no}">
				 <input type="hidden" name="sub_total[]" class="form-control" id="subtotal${index}${no}" readonly>
				 <input type="hidden" name="noAkun1[]" class="form-control" id="noAkun1${index}${no}" value="`+ idakun +`">
				 <input type="hidden" name="subtotal[]" id="subtotal_asli${index}${no}" readonly>
				 <input type="hidden" class="form-control" name="diskon[]" id="diskon${index}${no}">
				 <input type="hidden" name="total_pajak[]" id="total_pajak${index}${no}" >
                 <input type="hidden" name="biaya_pengiriman[]" id="biaya_pengiriman${index}${no}">
                 <input type="hidden" class="form-control" name="total[]" id="total${index}${no}">
				 <input type="hidden" name="subtotal[]" id="subtotal_asli${index}${no}" readonly>`,
				 noakun + `<input type="text" class="form-control"  name="noakun2[]" id="noakun2${index}${no}" value="`+ noakun +`">`,              
                sisapaguitem_tabel,
                `<a href="javascript:void(0)" class="edit_detail" id_barang="${barang[index].value}"><i class="fas fa-pencil-alt"></i></a>&nbsp;
                <a href="javascript:void(0)" class="delete_detail text-danger"><i class="fas fa-trash"></i></a>`
            ]).draw( false );
            
                    table_detail.row.add([
            data['itemid'],
            data['nama'] + `<input type="text" name="xo[]" class="form-control" id="xo${data['index']}${data['no']}" value="` + data['xo'] + `">`,           
            `<input type="text" class="form-control" onkeyup="sum('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" name="jumlah[]" id="jumlah${data['index']}${data['no']}" value="${data['jumlah']}">
			`,
            data['noakun'] + `<input type="text" name="noAkun1[]" class="form-control" id="noAkun1${data['index']}${data['no']}" value="` + data['noakun'] + `">
			<input type="hidden" name="noAkun2[]" class="form-control" id="noAkun2[]" value="` + data['noakun'] + `">`,
            sisapaguitem_tabel +`<input type="hidden" class="form-control" name="total[]" id="total${data['index']}${data['no']}" readonly value="${formatRupiah(data['total'], 'Rp. ')+',00'}">
			<input type="hidden" class="form-control" onkeyup="sum('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" name="harga[]" id="harga${data['index']}${data['no']}" value="0${formatRupiah(data['harga'], 'Rp. ')}">
			<input type="hidden" class="form-control" id="subtotal${data['index']}${data['no']}" readonly value="${formatRupiah(data['subtotal'], 'Rp. ')+',00'}"><input type="hidden" name="subtotal[]" id="subtotal_asli${data['index']}${data['no']}" readonly value="0${data['subtotal']}">
			<input type="hidden" name="total_pajak[]" id="total_pajak${data['index']}${data['no']}" onchange="sum_total('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" value="0${data['ppn']}">
			<input type="hidden" class="form-control" id="subtotal${data['index']}${data['no']}" readonly value="0${formatRupiah(data['subtotal'], 'Rp. ')+',00'}"><input type="hidden" name="subtotal[]" id="subtotal_asli${data['index']}${data['no']}" readonly value="${data['subtotal']}"><input type="hidden" class="form-control" onkeyup="sum_total('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" name="diskon[]" id="diskon${data['index']}${data['no']}" value="0${data['diskon']}">`,
            `<a href="javascript:void(0)" class="edit_detail" id_barang="${data['itemid']}"><i class="fas fa-pencil-alt"></i></a>&nbsp;
            <a href="javascript:void(0)" class="delete_detail text-danger"><i class="fas fa-trash"></i></a>`
        ]).draw( false );
*/
?>