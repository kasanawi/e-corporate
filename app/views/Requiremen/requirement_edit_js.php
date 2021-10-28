<script>

function get_detail(no, jenis) {
        switch (jenis) {
            case 'barang':
                var form            = $('#form2')[0];
                var formData        = new FormData(form);
                var barang          = $('.itemid :selected');        
                break;
            case 'jasa':
                var form            = $('#form_jasa')[0];
                var formData        = new FormData(form);
                var barang          = $('.id_jasa :selected');        
                break;
        
            default:
                break;
        }
        var jenis_pembelian = $('.jenis_pembelian').val();
		var itemid          = $(this).val();   
        for (let index = 0; index < barang.length; index++) {
			$.ajax({
                    url         : base_url + 'get_detail_item',
                    method      : 'post',
                    datatype    : 'json',
                    data        : {
                        itemid: itemid
                    },
                    success: function(data) {
                        var detail_barang   = '';
                        
                    }
                });
			// end get data
            var item    = barang[index].text;
            if(table_detail.hasValue(item)) {
                swal("Gagal!", "Item sudah ada", "error");
                return;
            }
            if (jenis == 'jasa') {
                noakun          = 0;
                sisapaguitem    = 0;
            } else {
				console.log('A912' + 'S912');
                noakun          = $('#noakun'+index).val() + +`123`;
                sisapaguitem    = $('#sisapaguitem'+index).val();
				console.log(index);
                $('#noakun'+index).remove();
                $('#sisapaguitem'+index).remove();
            }
            if (jenis == 'barang') {
                sisapaguitem_tabel  = `<input type="hidden" name="sisapaguitem[]" id="sisapaguitem_lama${index}${no}" value="${sisapaguitem}"><input type="text" class="form-control" id="sisapaguitem_baru${index}${no}" value="${formatRupiah(sisapaguitem, 'Rp.')+',00'}" readonly>`;
            } else {
                sisapaguitem_tabel  = ``;
            }
			console.log('N199');
            table_detail.row.add([
                barang[index].value,
                item,
                `<input type="text" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="harga[]" id="harga${index}${no}">
				<input type="text" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="jumlah[]" id="jumlah${index}${no}">`,
				 noakun,
                `<input type="hidden" class="form-control" id="subtotal${index}${no}" readonly>
				 <input type="hidden" name="subtotal[]" id="subtotal_asli${index}${no}" readonly>
				 <input type="hidden" class="form-control" onkeyup="sum_total('${index}${no}', '${no}', '${jenis}');" name="diskon[]" id="diskon${index}${no}">2<input type="hidden" name="total_pajak[]" id="total_pajak${index}${no}" onchange="sum_total('${index}${no}', '${no}', '${jenis}');">3
                <input type="hidden" name="biaya_pengiriman[]" id="biaya_pengiriman${index}${no}">4
                `,
               
                `<input type="hidden" class="form-control" name="total[]" id="total${index}${no}" readonly onchange="sum_total('${index}${no}', '${no}', '${jenis}');">`,
                sisapaguitem_tabel,
                `<a href="javascript:void(0)" class="edit_detail" id_barang="${barang[index].value}"><i class="fas fa-pencil-alt"></i></a>&nbsp;
                <a href="javascript:void(0)" class="delete_detail text-danger"><i class="fas fa-trash"></i></a>`
            ]).draw( false );
		}
		$('#form2').attr('action', 'javascript:get_detail('+no_baru+', "barang")');
        $('#form_jasa').attr('action', 'javascript:get_detail('+no_baru+', "jasa")');
	}

function isi_tabel(data) {
        if (data['jenis'] == 'barang') {
            sisapaguitem_tabel  = `<input type="hidden" name="sisapaguitem[]" id="sisapaguitem_lama${data['index']}${data['no']}" value="${data['sisapaguitem_a']}"><input type="text" class="form-control" id="sisapaguitem_baru${data['index']}${data['no']}" value="${formatRupiah(String(data['sisapaguitem_b']), 'Rp.')+',00'}" readonly>`;
        } else {
            sisapaguitem_tabel  = ``;
        }
		console.log('U411');
         table_detail.row.add([
            data['itemid'],
            data['nama'],           
            `<input type="text" class="form-control" onkeyup="sum('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" name="jumlah[]" id="jumlah${data['index']}${data['no']}" value="${data['jumlah']}">
			<input type="hidden" class="form-control" name="total[]" id="total${data['index']}${data['no']}" readonly value="${formatRupiah(data['total'], 'Rp. ')+',00'}">`,
            data['noakun'],
            sisapaguitem_tabel,
            `<a href="javascript:void(0)" class="edit_detail" id_barang="${data['itemid']}"><i class="fas fa-pencil-alt"></i></a>&nbsp;
            <a href="javascript:void(0)" class="delete_detail text-danger"><i class="fas fa-trash"></i></a>`
        ]).draw( false );
        detail_array()
       
        $('#tambah_modal_pajak').append(modal_pajak);
        $('#tambah_modal_pilih_pajak').append(modal_pilih_pajak);
        $('#tambah_modal_pilih_pengiriman').append(modal_pengiriman);
        ajax_select({ 
            id          : `#noakun${data['index']}${data['no']}`, 
            url         : '{site_url}pajak/select2_noakun'
        });
        getListPajak(String(data['index']) + String(data['no']));
        total_total[data['no']] = data['total'];
        console.log(data['no'], data['total']);
        data['no']++;
        var no_baru = data['no'];
        $('#form2').attr('action', 'javascript:save_detail('+no_baru+', "barang")');
        $('#form_jasa').attr('action', 'javascript:save_detail('+no_baru+', "jasa")');
        total_semua();
    }
	    $(document).on('change','.jenis_pembelian',function(){
        if ($(this).val() == 'jasa') {
            $('.jenis_barang').attr("disabled", true);
            $('#rekanan').empty();
            $('#gudang').empty();
        } else if($(this).val() == 'barang') {
            $('.jenis_barang').attr("disabled", false);
            $('#rekanan').html(`
                <label><?php echo lang('rekanan') ?>:</label>
                <select class="form-control kontakid" name="kontakid" disabled></select>
            `);
            $('#gudang').html(`
                <label><?php echo lang('gudang') ?>:</label>
                <select class="form-control gudangid" name="gudangid"></select>
            `);
            ajax_select({ id: '.gudangid', url: base_url + 'select2_gudang', selected: { id: null } });
            $('#tombol_jasa').css('display', 'none')
        } else {
            $('.gudangid').attr("disabled", true);
            $('#tombol_jasa').css('display', 'block')
        }
    })

    $(document).on('click','.btn_add_detail',function(){
        $('#modal_add_detail').modal('show');
        $('.itemid').empty();
        var jenis_pembelian = $('.jenis_pembelian').val();
        switch (jenis_pembelian) {
            case 'barang':
                url = base_url + 'select2_item';
                break;
            case 'jasa':
                url = base_url + 'select2_item_jasa';
                break;
            default:
                url = base_url + 'select2_item';
                break;
        }
        $.ajax({
            url         : url,
            method      : 'post',
            datatype    : 'json',
            success: function(data) {
                isi = "";
                for (let index = 0; index < data.length; index++) {
                    isi += `<option value="${data[index].id}">${data[index].text}</option>`
                }
                $('.itemid').append(isi);
            }
        })
        $('.itemid').select2();
    })

    $('#tombol_jasa').click(function() {
        $('#modal_add_jasa').modal('show');
        $('.id_jasa').empty();
        var jenis_pembelian = $('.jenis_pembelian').val();
        $.ajax({
            url         : base_url + 'select2_item_jasa',
            method      : 'post',
            datatype    : 'json',
            success: function(data) {
                isi = "";
                for (let index = 0; index < data.length; index++) {
                    isi += `<option value="${data[index].id}">${data[index].text}</option>`
                }
                $('.id_jasa').append(isi);
            }
        })
        $('.id_jasa').select2();
    })

    $(document).on('change','.itemid',function(){
        var rowindex        = $('input[name=rowindex]').val();
        var itemid          = $(this).val();    
        var jenis_pembelian = $('.jenis_pembelian').val();
        if (jenis_pembelian == 'barang' || jenis_pembelian == 'barang_dan_jasa') {
            if(!rowindex) {
                if(itemid) {
                    $.ajax({
                        url         : base_url + 'get_detail_item',
                        method      : 'post',
                        datatype    : 'json',
                        data        : {
                            itemid: itemid
                        },
                        success: function(data) {
                            var detail_barang   = '';
                            for (let index = 0; index < data.length; index++) {
                                var hargabeli       = data[index].tarif;
                                var jumlahkasitem   = data[index].jumlah;
                                detail_barang       +=  `<input type="hidden" class="form-control" id="noakun`+index+`" name="noakun[]" required value="${data[index].koderekening}"><input type="hidden" class="form-control" id="sisapaguitem`+index+`" name="sisapaguitem[]" required value="${data[index].jumlah}">`
                            }
                            $('#detail_barang').html(detail_barang);
                        }
                    })
                }
            }
        }
    })
	
	    function save_detail(no, jenis) {
        switch (jenis) {
            case 'barang':
                var form            = $('#form2')[0];
                var formData        = new FormData(form);
                var barang          = $('.itemid :selected');        
                break;
            case 'jasa':
                var form            = $('#form_jasa')[0];
                var formData        = new FormData(form);
                var barang          = $('.id_jasa :selected');        
                break;
        
            default:
                break;
        }
        var jenis_pembelian = $('.jenis_pembelian').val();
        for (let index = 0; index < barang.length; index++) {
            var item    = barang[index].text;
            if(table_detail.hasValue(item)) {
                swal("Gagal!", "Item sudah ada", "error");
                return;
            }
            if (jenis == 'jasa') {
                noakun          = 0;
                sisapaguitem    = 0;
            } else {
                noakun          = $('#noakun'+index).val();
                sisapaguitem    = $('#sisapaguitem'+index).val();
                $('#noakun'+index).remove();
                $('#sisapaguitem'+index).remove();
            }
            if (jenis == 'barang') {
                sisapaguitem_tabel  = `<input type="hidden" name="sisapaguitem[]" id="sisapaguitem_lama${index}${no}" value="${sisapaguitem}"><input type="text" class="form-control" id="sisapaguitem_baru${index}${no}" value="${formatRupiah(sisapaguitem, 'Rp.')+',00'}" readonly>`;
            } else {
                sisapaguitem_tabel  = ``;
            }
			console.log('U412');
            table_detail.row.add([
                barang[index].value,
                item,
                `<input type="text" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="harga[]" id="harga${index}${no}">`,
                `<input type="text" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="jumlah[]" id="jumlah${index}${no}">`,
                `<input type="text" class="form-control" id="subtotal${index}${no}" readonly><input type="hidden" name="subtotal[]" id="subtotal_asli${index}${no}" readonly>`,
                `<input type="text" class="form-control" onkeyup="sum_total('${index}${no}', '${no}', '${jenis}');" name="diskon[]" id="diskon${index}${no}">`,
                `<input type="hidden" name="total_pajak[]" id="total_pajak${index}${no}" onchange="sum_total('${index}${no}', '${no}', '${jenis}');">
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_pajak${index}${no}" title="Tambah Pajak">
                    <i class="fas fa-balance-scale"></i>
                </button>`,
                `<input type="hidden" name="biaya_pengiriman[]" id="biaya_pengiriman${index}${no}">
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_pengiriman${index}${no}" title="Tambah Biaya Pengiriman">
                    <i class="fas fa-shipping-fast"></i>
                </button>`,
                noakun,
                `<input type="text" class="form-control" name="total[]" id="total${index}${no}" readonly onchange="sum_total('${index}${no}', '${no}', '${jenis}');">`,
                sisapaguitem_tabel,
                `<a href="javascript:void(0)" class="edit_detail" id_barang="${barang[index].value}"><i class="fas fa-pencil-alt"></i></a>&nbsp;
                <a href="javascript:void(0)" class="delete_detail text-danger"><i class="fas fa-trash"></i></a>`
            ]).draw( false );
            detail_array()

              
            $('#tambah_modal_pajak').append(modal_pajak);
            $('#tambah_modal_pilih_pajak').append(modal_pilih_pajak);
            $('#tambah_modal_pilih_pengiriman').append(modal_pengiriman);
            ajax_select({ 
                id          : `#noakun${index}${no}`, 
                url         : '{site_url}pajak/select2_noakun'
            });
            getListPajak(String(index) + String(no));
            total_total.push(no);
            no++;
            var no_baru = no;
        }
        $('#modal_add_detail').modal('hide');
        $('#modal_add_jasa').modal('hide');
        $('#form2').attr('action', 'javascript:save_detail('+no_baru+', "barang")');
        $('#form_jasa').attr('action', 'javascript:save_detail('+no_baru+', "jasa")');
    }
$(document).on('select2:select','.pilih_akun',function(e){
        id  = $(this).attr('data-id');
        $.ajax({
            url         : '{site_url}noakun/get',
            method      : 'post',
            datatype    : 'json',
            data        : {
                id  : e.params.data.id
            },
            success: function(data) {
                var isi_tbody_pengiriman    = `
                    <tr>
                        <td>${data.akunno}</td>
                        <td>${data.namaakun}</td>
                        <td><input type="text" class="form-control pajak" id="nominal_pajak${id}" onkeyup="nominalPajak('${id}')" name="pengiriman"></td>
                    </tr>`;
                $('#isi_tbody_pengiriman'+id).append(isi_tbody_pengiriman);
            }
        })
    })

    function detail_array() {
        var arr = table_detail.data().toArray();
        $('#detail_array').val( JSON.stringify(arr) );
    }

    function sum(no, no1, jenis) {	
        var txtFirstNumberValue                     = document.getElementById('harga'+no).value.replace(/[^,\d]/g, '').toString();    
        document.getElementById('harga'+no).value   = formatRupiah(txtFirstNumberValue, 'Rp.');
		var txtSecondNumberValue                    = document.getElementById('jumlah'+no).value;
        var result                                  = parseInt(txtFirstNumberValue) * parseInt(txtSecondNumberValue);
        var pajak                 = document.getElementById('total_pajak'+no).value;
        if (!isNaN(parseInt(pajak))) {
            var result   = result + parseInt(pajak);    
        }
        if (isNaN(parseInt(txtFirstNumberValue))) {
            var result  = 0;    
        }
        if (isNaN(parseInt(txtSecondNumberValue))) {
            var result  = 0;    
        }
        if (jenis == 'barang') {
            var sisapaguitem            = document.getElementById('sisapaguitem_lama'+no).value;
            var sisapaguitem_baru       = String(parseInt(sisapaguitem) - result);
        }
		if (!isNaN(result)) {
			document.getElementById('subtotal'+no).value        = formatRupiah(String(result), 'Rp.')+',00';
            document.getElementById('subtotal_asli'+no).value   = result;
            document.getElementById('total'+no).value           = formatRupiah(String(result), 'Rp.')+',00';
		}
		else if(txtFirstNumberValue !=null && txtSecondNumberValue == null){
			document.getElementById('subtotal'+no).value = txtFirstNumberValue;
            document.getElementById('total'+no).value = txtFirstNumberValue;
		}else{
            document.getElementById('subtotal'+no).value        = formatRupiah(String(result), 'Rp.')+',00';
            document.getElementById('subtotal_asli'+no).value   = result;
            document.getElementById('total'+no).value           = formatRupiah(String(result), 'Rp.')+',00';
		}
        if (jenis == 'barang') {
            document.getElementById('sisapaguitem_baru'+no).value = formatRupiah(sisapaguitem_baru, 'Rp.')+',00';
        }
        total_total[no1] = [];
        total_total[no1].push(parseInt(result));
        total_semua();
	}

    function sum_total(no, no1, jenis) {	
		var subtotal            = document.getElementById('subtotal_asli'+no).value.replace(/[^,\d]/g, '').toString();
        if (isNaN(parseInt(subtotal))) {
            var subtotal   = 0;    
        }
		var diskon              = document.getElementById('diskon'+no).value;
        if (isNaN(parseInt(diskon))) {
            var subtotal_baru   = parseInt(subtotal);    
        } else {
            var subtotal_baru   = parseInt(subtotal) - (parseInt(diskon) * parseInt(subtotal)/100);
        }
        var pajak                 = document.getElementById('total_pajak'+no).value;
        if (isNaN(parseInt(pajak))) {
            var total   = parseInt(subtotal_baru);    
        } else {
            var total   = parseInt(subtotal_baru) + parseInt(pajak);
        }
        var pengiriman  = document.getElementById('biaya_pengiriman'+no).value;
        if (isNaN(parseInt(pengiriman))) {
            var total   = parseInt(total);    
        } else {
            var total   = parseInt(total) + parseInt(pengiriman);
        }
        if (jenis == 'barang') {
            var sisapaguitem            = document.getElementById('sisapaguitem_lama'+no).value;
            var sisapaguitem_baru       = String(parseInt(sisapaguitem) - total);
        }
        document.getElementById('total'+no).value = formatRupiah(String(total), 'Rp.')+',00';
        if (jenis == 'barang') {
            document.getElementById('sisapaguitem_baru'+no).value = formatRupiah(sisapaguitem_baru, 'Rp.')+',00';
        }
        total_total[no1]    = [];
        total_total[no1].push((parseInt(total)));
        total_semua();
    }
    
    function total_semua() {
        a   = 0;
        total_total.forEach(function callback(element, index, array) {
            // console.log(array);
            a   += parseInt(element);
        })
        // console.log('batas');
        $('#total_total').html(formatRupiah(String(a), 'Rp. ')+',00');
    }

    $('#table_detail tbody').on('click','.edit_detail',function(){
        var id              = $('.edit_detail').attr('id_barang'); 
        var tr              = table_detail.row($(this).parents('tr')).index();
        var rowindex        = table_detail.row($(this).parents('tr')).index();
        var jenis_pembelian = $('.jenis_pembelian').val();
        $('input[name=edit_rowindex]').val(rowindex);
        switch (jenis_pembelian) {
            case 'barang':
                url = base_url + 'select2_item';
                break;
            case 'jasa':
                url = base_url + 'select2_item_jasa';
                break;
            default:
                break;
        }
        ajax_select({ 
            id: '.edit_itemid', 
            url: url, 
            selected: { 
                id: id 
            } 
        });
        $('#modal_edit_detail').modal('show');
    })

    function edit_detail_barang(no) {
        var formData        = new FormData($('#form3')[0]);
        var rowindex        = formData.get('edit_rowindex');
        var barang          = $('.edit_itemid :selected');
        var jenis_pembelian = $('.jenis_pembelian').val();
        for (let index = 0; index < barang.length; index++) {
            var item    = barang[index].text;
            if(table_detail.hasValue(item)) {
                // NotifyError('Item sudah ada!');
                swal("Gagal!", "Item sudah ada", "error");
                return;
            }
            switch (jenis_pembelian) {
                case 'jasa':
                    noakun  = 0;
                    break;
                case 'barang':
                    noakun          = $('#noakun'+index).val();
                    sisapaguitem    = $('#sisapaguitem'+index).val();
                    break;
            
                default:
                    break;
            }
			console.log('U413');
            table_detail.row(rowindex).data([
                barang[index].value,
                item,
                `<input type="text" class="form-control" onkeyup="sum('${index}${rowindex}', '${rowindex}');" name="harga[]" id="harga${index}${rowindex}">`,
                `<input type="text" class="form-control" onkeyup="sum('${index}${rowindex}', '${rowindex}');" name="jumlah[]" id="jumlah${index}${rowindex}">`,
                `<input type="text" class="form-control" id="subtotal${index}${rowindex}" readonly><input type="hidden" name="subtotal[]" id="subtotal_asli${index}${rowindex}" readonly>`,
                `<input type="text" class="form-control" onkeyup="sum_total('${index}${rowindex}', '${rowindex}');" name="diskon[]" id="diskon${index}${rowindex}">`,
                `<input type="text" class="form-control" onkeyup="sum_total('${index}${rowindex}', '${rowindex}');" name="ppn[]" id="ppn${index}${rowindex}">`,
                noakun,
                `<input type="text" class="form-control" name="total[]" id="total${index}${rowindex}" readonly>`,
                `<input type="hidden" name="sisapaguitem[]" id="sisapaguitem_lama${index}${rowindex}" value="${sisapaguitem}"><input type="text" class="form-control" id="sisapaguitem_baru${index}${rowindex}" value="${formatRupiah(sisapaguitem, 'Rp.')+',00'}" readonly name="sisapaguitem_baru[]" >`,
                `<a href="javascript:void(0)" class="edit_detail" id_barang="${barang[index].value}"><i class="fas fa-pencil-alt"></i></a>&nbsp;
                <a href="javascript:void(0)" class="delete_detail text-danger" onclick="delete_detail('${no}')"><i class="fas fa-trash"></i></a>`
            ]).draw( false );
            detail_array()
            var checklist   = `<tr class="bg-light">
                                    <td style="width:5px;padding-right:0px;"><input type="checkbox" checked="checked"></td>
                                    <td>${item}</td>
                                </tr>`;
            rowindex++;
        }
        $('#modal_edit_detail').modal('hide')
    }

    $(document).on('change','.edit_itemid',function(){
        var rowindex = $('input[name=rowindex]').val();
        var itemid = $(this).val();   
        if(!rowindex) {
            if(itemid) {
                $.ajax({
                    url: base_url + 'get_detail_item',
                    method: 'post',
                    datatype: 'json',
                    data: {
                        itemid: itemid
                    },
                    success: function(data) {
                        var jenis_pembelian = $('.jenis_pembelian').val();
                        switch (jenis_pembelian) {
                            case 'barang':
                                for (let index = 0; index < data.length; index++) {
                                    var hargabeli       = data[index].tarif;
                                    var jumlahkasitem   = data[index].jumlah;
                                    detail_barang       =  `<input type="hidden" class="form-control" id="noakun`+index+`" name="noakun[]" required value="${data[index].koderekening}"><input type="hidden" class="form-control" id="sisapaguitem`+index+`" name="sisapaguitem[]" required value="${data[index].jumlah}">`
                                }
                                break;
                            case 'jasa':
                                
                                break;
                        
                            default:
                                break;
                        }
                        
                        $('#edit_detail_barang').append(detail_barang);
                    }
                })
            }
        }
    })

    $('#table_detail tbody').on('click','.delete_detail',function(){
        var rowindex    = table_detail.row($(this).parents('tr')).index();
        table_detail.row($(this).parents('tr')).remove().draw();
        detail_array();
        total_total.splice(rowindex, 1);
        total_semua();
    })

    function save() {
        var form        = $('#form1')[0];
        var formData    = new FormData(form);
        detail          = formData.get('detail_array');
        if(detail.length < 10) {
            NotifyError('Silahkan isi detail terlebih dulu!');
            return false;
        }
        $.ajax({
            url         : base_url + 'ganti/<?= $edit['id']; ?>',
            dataType    : 'json',
            method      : 'post',
            data        : formData,
            contentType : false,
            processData : false,
            beforeSend  : function() {
                pageBlock();
            },
            afterSend   : function() {
                unpageBlock();
            },
            success: function(data) {
                if(data.status == 'success') {
                    swal("Berhasil!", "Berhasil Menambah Data", "success");
                    redirect(base_url);
                } else {
                    swal("Gagal!", "Gagal Menambah Data", "error");
                }
            },
            error: function() {
                swal("Gagal!", "Internal Server Error", "error");
            }
        })
    }
</script>