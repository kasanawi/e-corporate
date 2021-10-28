<script>
    var base_url        = '{site_url}requiremen/';
    var total_total     = [];
	console.log(base_url + 'JIX41');
    function addPajak(elem, no, id) {
		const kode_pajak 	= $(elem).attr('kode_pajak');
        const nama_pajak 	= $(elem).attr('nama_pajak');
        const kode_akun 	= $(elem).attr('kode_akun');
        const nama_akun 	= $(elem).attr('nama_akun');
		const stat			= $(elem).is(":checked");
        const table			= $('#isi_tbody_pajak'+id);
		// var no1				= 0;		
		if (stat) {
			html = `<tr no="${no}">
                        <td>${kode_pajak}</td>
                        <td>${kode_akun}</td>
                        <td>${nama_akun}</td>
                        <td><input type="text" class="form-control pajak" id="nominal_pajak${no}${id}" onkeyup="nominalPajak('${no}${id}')" name="pajak"></td>
                    </tr>`;
			table.append(html);
		} else {
            // alert('a');
			$(`tr[no="${no}"]`).remove();
		}
	}

    function total_pajak(id, no) {
        var formData    = new FormData($('#form_pajak'+id)[0]);
        var pajak       = formData.getAll('pajak');
        var pajak_baru  = 0;
        pajak.forEach(p => {
            pajak_baru  += parseInt(p.replace(/[Rp.]/g, ''));
        });
        $('#total_pajak'+id).val(pajak_baru);
        $('#modal_pajak'+id).modal('hide');
        sum_total(id, no)
    }

    function total_pengiriman(id, no) {
        var formData            = new FormData($('#form_pengiriman'+id)[0]);
        var pengiriman          = formData.getAll('pengiriman');
        var biaya_pengiriman    = 0;
        pengiriman.forEach(p => {
            biaya_pengiriman  += parseInt(p.replace(/[Rp.]/g, ''));
        });
        $('#biaya_pengiriman'+id).val(biaya_pengiriman);
        $('#modal_pengiriman'+id).modal('hide');
        sum_total(id, no)
    }

    function nominalPajak(no) {
        var nilai   = $('#nominal_pajak' + no).val();
        var nilai1  = nilai.replace(/[^,\d]/g, '').toString();
        $('#nominal_pajak' + no).val(formatRupiah(String(nilai), 'Rp. '));
    }

    function getListPajak(id) {
		var table = $('#list_pajak'+id);
		$.ajax({
			type    : "get",
			url     : '{site_url}pajak/get',
			success : function(response) {
				for (let i = 0; i < response.length; i++) {
					const element = response[i];
					if (i < 0) {
						const html = `
							<tr class="bg-light">
								<td><input type="checkbox" name="" id=""  disabled></td>
								<td>${element.kode_pajak}</td>
								<td>${element.nama_pajak}</td>
                                <td>${element.akunno}</td>
                                <td>${element.namaakun}</td>
							</tr>
						`;
						table.append(html);
					} else {
                        const html  = `
							<tr>
								<td><input type="checkbox" name="" kode_pajak="${element.kode_pajak}" nama_pajak="${element.nama_pajak}" kode_akun="${element.akunno}" nama_akun="${element.namaakun}"id="" onchange="addPajak(this, `+i+`, '`+id+`')"></td>
								<td>${element.kode_pajak}</td>
								<td>${element.nama_pajak}</td>
                                <td>${element.akunno}</td>
                                <td>${element.namaakun}</td>
							</tr>
						`;
						table.append(html);
					}
                }
			}
		});
	}

    $.fn.dataTable.Api.register( 'hasValue()' , function(value) {
        return this .data() .toArray() .toString() .toLowerCase() .split(',') .indexOf(value.toString().toLowerCase())>-1
    })
    var table_detail = $('#table_detail').DataTable({
        sort: false,
        info: false,
        searching: false,
        paging: false,
        columnDefs: [
            {targets: [0], visible: false},
            {targets: [2,3,4,5], className: 'text-right'}
        ]
    })

    $(document).ready(function(){
        ajax_select({ 
            id          : '.gudang', 
            url         : base_url + 'select2_gudang', 
            selected    : { 
                id  : '<?= $edit['gudangid']; ?>' 
            } 
        });
		ajax_select({ 
            id          : '#gudangid', 
            url         : base_url + 'select2_gudang', 
            selected    : { 
                id  : '<?= $edit['gudangid']; ?>' 
            } 
        });
        ajax_select({
            id          : '#perusahaan',	
            url         : base_url + 'select2_mperusahaan', 
            selected    : { 
                id  : <?= $edit['idperusahaan']; ?> 
            } 
        });			
		 ajax_select({
            id          : '#department',	
            url         : base_url + 'select2_mdepartemen/' + <?= $edit['idperusahaan']; ?>, 
            selected    : { 
                id  : 5<?= $edit['departemen']; ?>
			} 
        });		
		$('#perusahaan').change(function(e) {
			var perusahaanId = $('#perusahaan').children('option:selected').val();
			var num = perusahaanId.toString().padStart(3, "0")
			$('#corpCode').val(num);
            ajax_select({
				id: '#department',
                url: base_url + 'select2_mdepartemen/' + perusahaanId,
                selected    : {
                    id    : '<?= $edit['departemen']; ?>'
                }
			});
		})

		$('#department').change(function(e) {
			var deptName = $('#department').children('option:selected').text();
			var deptId = $('#department').children('option:selected').val()
			var num = deptId.toString().padStart(3, "0")
			$('#deptCode').val(num);
			ajax_select({
				id          : '#pejabat',
                url         : base_url + 'select2_mdepartemen_pejabat/' + deptName,
                selected    : {
                    id    : '<?= $edit['pejabat']; ?>'
                }
			});
		})

        getListPajak();

        if ('<?= $edit['jenis_pembelian']; ?>' == 'jasa') {
            $('.jenis_barang').attr("disabled", true);
            $('#rekanan').empty();
            $('#gudang').empty();
        } else if('<?= $edit['jenis_pembelian']; ?>' == 'barang') {
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
        no  = 0; 
        <?php
            for ($i=0; $i < count($edit['detail']); $i++) {
                if (substr($edit['detail'][$i]['itemid'], 0, 3) == 'ABD') {
					$this->db->select('*, mitem.nama');
					$this->db->from('tanggaranbelanjadetail');
					$this->db->join('mitem', 'tanggaranbelanjadetail.uraian = mitem.id');
					$this->db->where('tanggaranbelanjadetail.id', $edit['detail'][$i]['itemid']);
					$barang = $this->db->get();
					$barang = $barang->row_array();
                    ?>
                    nama            = '<?= $barang['nama']; ?>';
                    sisapaguitem    = '<?= $barang['jumlah']; ?>';
                    jenis           = 'barang';
                <?php } else {
                    $barang = $this->db->get_where('mjasa', [
                        'id'    => $edit['detail'][$i]['itemid']
                    ])->row_array(); ?>
                    nama            = '<?= $barang['nama']; ?>';
                    sisapaguitem    = 0;
                    jenis           = 'jasa';
                <?php } ?>
                data                    = [];
				data['xo']				= '<?= $edit['detail'][$i]['id']; ?>';
                data['itemid']          = '<?= $edit['detail'][$i]['itemid']; ?>';
                data['nama']            = nama;
                data['index']           = '<?= $i; ?>';
                data['no']              = no;
                data['jenis']           = jenis;
                data['harga']           = '<?= $edit['detail'][$i]['harga']; ?>';
                data['jumlah']          = '<?= $edit['detail'][$i]['jumlah']; ?>';
                data['diskon']          = '<?= $edit['detail'][$i]['diskon']; ?>';
                data['ppn']             = '<?= $edit['detail'][$i]['ppn']; ?>';
                data['noakun']          = '<?= $edit['detail'][$i]['akunno']; ?>';
				data['akundet']          = '<?= $edit['detail'][$i]['akundet']; ?>';
                data['total']           = '<?= $edit['detail'][$i]['total']; ?>';
                data['subtotal']        = '<?= $edit['detail'][$i]['subtotal']; ?>';
                data['sisapaguitem_a']  = sisapaguitem;
                data['sisapaguitem_b']  = parseInt(sisapaguitem) - parseInt(data['total']);
                isi_tabel(data);
                total_total.push(no);
                no++;
                var no_baru = no;
        <?php } ?>
    })
	function isi_tabel(data) {
        if (data['jenis'] == 'barang') {
            sisapaguitem_tabel  = `<input type="hidden" name="sisapaguitem[]" id="sisapaguitem_lama${data['index']}${data['no']}" value="${data['sisapaguitem_a']}"><input type="text" class="form-control" id="sisapaguitem_baru${data['index']}${data['no']}" value="${formatRupiah(String(data['sisapaguitem_b']), 'Rp.')+',00'}" readonly>`;
        } else {
            sisapaguitem_tabel  = ``;
        }
       console.log('N199' + data['jenis']);
        table_detail.row.add([
            data['itemid'],
            data['nama'] + `<input type="hidden" name="xo[]" class="form-control" id="xo${data['index']}${data['no']}" value="` + data['xo'] + `">`,           
            `<input type="text" class="form-control" onkeyup="sum('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" name="jumlah[]" id="jumlah${data['index']}${data['no']}" value="${data['jumlah']}">
			`,
            data['akundet'] + `<input type="hidden" name="noAkun1[]" class="form-control" id="noAkun1${data['index']}${data['no']}" value="` + data['noakun'] + `">
			<input type="hidden" name="noAkun2[]" class="form-control" id="noAkun2[]" value="` + data['noakun'] + `">`,
            sisapaguitem_tabel +`<input type="hidden" class="form-control" name="total[]" id="total${data['index']}${data['no']}" readonly value="${formatRupiah(data['total'], 'Rp. ')+',00'}">
			<input type="hidden" class="form-control" onkeyup="sum('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" name="harga[]" id="harga${data['index']}${data['no']}" value="0${formatRupiah(data['harga'], 'Rp. ')}">
			<input type="hidden" class="form-control" id="subtotal${data['index']}${data['no']}" readonly value="${formatRupiah(data['subtotal'], 'Rp. ')+',00'}"><input type="hidden" name="subtotal[]" id="subtotal_asli${data['index']}${data['no']}" readonly value="0${data['subtotal']}">
			<input type="hidden" name="total_pajak[]" id="total_pajak${data['index']}${data['no']}" onchange="sum_total('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" value="0${data['ppn']}">
			<input type="hidden" class="form-control" id="subtotal${data['index']}${data['no']}" readonly value="0${formatRupiah(data['subtotal'], 'Rp. ')+',00'}"><input type="hidden" name="subtotal[]" id="subtotal_asli${data['index']}${data['no']}" readonly value="${data['subtotal']}"><input type="hidden" class="form-control" onkeyup="sum_total('${data['index']}${data['no']}', '${data['no']}', '${data['jenis']}');" name="diskon[]" id="diskon${data['index']}${data['no']}" value="0${data['diskon']}">`,
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
        total_total[data['no']] = '';//data['total'];
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
				console.log('N913');
								str = JSON.stringify(data);
								str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
								//console.log(str);
                for (let index = 0; index < data.length; index++) {
                    isi += `<option value="${data[index].itemid}">${data[index].text}</option>`
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
			console.log('E192');
			var rowindex        = $('input[name=rowindex]').val();
			var itemid          = $(this).val();    
			var jenis_pembelian = $('.jenis_pembelian').val();
			//section get_detail_item barang C910
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
								str = JSON.stringify(data);
								str = JSON.stringify(data, null, 4); // (Optional) beautiful indented output.
								console.log('=================================')
								console.log(str)
								console.log(base_url + 'get_detail_item');
								for (let index = 0; index < data.length; index++) {
									console.log('data:' + index + '=' + data[index]);
									var hargabeli       = data[index].tarif;
									var jumlahkasitem   = data[index].jumlah;
									detail_barang       +=  `
									<input type="hidden" class="form-control" id="noakun`+index+`" name="noakun[]" required value="` + data[index].akunno + `" ><input type="hidden" class="form-control" id="idakun`+index+`" name="idakun[]" required value="` + data[index].idakun + `" >
									<input type="hidden" class="form-control" id="sisapaguitem`+index+`" name="sisapaguitem[]" required value="` + data[index].jumlah + `">`
								}
								$('#detail_barang').html(detail_barang);
							}
						})
					}
				}
			}
		});
	
	    function save_detail(no, jenis) {
		console.log('A914' + jenis);
        switch (jenis) {
            case 'barang':
                var form            = $('#form2')[0];
                var formData        = new FormData(form);
                var barang          = $('.itemid :selected');        
                break;
            case 'jasa':
                var form            = $('#form2')[0];
                var formData        = new FormData(form);
                var barang          = $('.itemid :selected');   
                break;
        
            default:
                break;
        }
						//str = JSON.stringify(barang);
						//str = JSON.stringify(barang, null, 4);
		console.log(barang);
		//document.getElementById("tampil").innerHTML  = JSON.stringify(barang);
		
        var jenis_pembelian = $('.jenis_pembelian').val();
        for (let index = 0; index < barang.length; index++) {
            var item    = barang[index].text;
			console.log('K192' + barang[index].text);
			//document.getElementById("tampil2").innerHTML  = JSON.stringify(barang[index], null, 4);
            if(table_detail.hasValue(item)) {
                Swal("Gagal!", "Item sudah ada", "error");
                return;
            }
            if (jenis == 'jasa') {
                noakun          = 0;
				idakun          = 0;
                sisapaguitem    = 0;
            } else {
                console.log('A912');
                noakun          = $('#noakun'+index).val();
				idakun          = $('#idakun'+index).val();
                sisapaguitem    = $('#sisapaguitem'+index).val();
                $('#noakun'+index).remove();
                $('#sisapaguitem'+index).remove();
            }
            if (jenis == 'barang') {
                sisapaguitem_tabel  = `<input type="hidden" name="sisapaguitem[]" id="sisapaguitem_lama${index}${no}" value="${sisapaguitem}"><input type="text" class="form-control" id="sisapaguitem_baru${index}${no}" value="${formatRupiah(sisapaguitem, 'Rp.')+',00'}" readonly>`;
            } else { 
                sisapaguitem_tabel  = ``;
            }
			console.log('A914' + 'jenis:' + jenis + ' kode barang:'+ barang[index].value);
           	 table_detail.row.add([
                barang[index].value,
                `<input type="hidden" name="item[]" id="" value="${item}">` + item,
                `<input type="text" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="jumlah[]" id="jumlah${index}${no}">`,
                `<input type="hidden" name="noAkun1[]" id="idakun${index}${no}" value="${idakun}"><input type="hidden" name="noakun[]" id="" value="${noakun}">` + noakun,
                `<input type="hidden" name="sisapaguitem[]" id="sisapaguitem_lama${index}${no}" value="${sisapaguitem}">
				<input type="text" class="form-control" id="sisapaguitem_baru${index}${no}" value="${formatRupiah(String(sisapaguitem))+',00'}" readonly>`,
                `<a href="javascript:void(0)" class="edit_detail" id_barang="${barang[index].value}"><i class="fas fa-pencil-alt"></i></a>&nbsp;
                <a href="javascript:void(0)" class="delete_detail text-danger"><i class="fas fa-trash"></i></a>
				<input type="hidden" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="harga[]" id="harga${index}${no}">
				<input type="text" class="form-control" onkeyup="sum('${index}${no}', '${no}', '${jenis}');" name="jumlah[]" id="jumlah${index}${no}">
				 <input type="hidden" name="sub_total[]" class="form-control" id="subtotal${index}${no}" readonly>
				 <input type="hidden" name="noAkun1[]" class="form-control" id="noAkun1${index}${no}" value="`+ idakun +`">
				 <input type="hidden" name="subtotal[]" id="subtotal_asli${index}${no}" readonly>
				 <input type="hidden" class="form-control" name="diskon[]" id="diskon${index}${no}">
				 <input type="hidden" name="total_pajak[]" id="total_pajak${index}${no}" >
                 <input type="hidden" name="biaya_pengiriman[]" id="biaya_pengiriman${index}${no}">
                 <input type="hidden" class="form-control" name="total[]" id="total${index}${no}">
				 <input type="hidden" name="subtotal[]" id="subtotal_asli${index}${no}" readonly>`
            ]).draw( false );
            detail_array();
			
              
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
            table_detail.row(rowindex).data([
                barang[index].value,
                item + `<input type="text" name="xo[]" class="form-control" id="xo${index}${rowindex}" >`,
                `<input type="text" class="form-control" onkeyup="sum('${index}${rowindex}', '${rowindex}');" name="jumlah[]" id="jumlah${index}${rowindex}">`,
                `<input type="text" class="form-control" id="subtotal${index}${rowindex}" readonly><input type="hidden" name="subtotal[]" id="subtotal_asli${index}${rowindex}" readonly>`,
                `<input type="text" class="form-control" onkeyup="sum_total('${index}${rowindex}', '${rowindex}');" name="diskon[]" id="diskon${index}${rowindex}">`,
                `<input type="text" class="form-control" onkeyup="sum_total('${index}${rowindex}', '${rowindex}');" name="ppn[]" id="ppn${index}${rowindex}"><input type="hidden" class="form-control" onkeyup="sum('${index}${rowindex}', '${rowindex}');" name="harga[]" id="harga${index}${rowindex}">`,
                noakun + `<input type="text" name="noakun2[]" class="form-control" id="noakun${index}${rowindex}" >`,
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
            url         : base_url + 'ganti2/<?= $edit['id']; ?>',
            dataType    : 'json',
            method      : 'post',
            data        : formData,
            contentType : false,
            processData : false,
            beforeSend  : function() {
                pageBlock();
				//alert(JSON.stringify(detail, null, 4));
				
            },
            afterSend   : function(data) {
                unpageBlock();
				//alert(JSON.stringify(data, null, 4));
            },
            success: function(data) {
                if(data.status == 'success') {
                    swal("Berhasil!", "Berhasil Menambah Data", "success");
                    //redirect(base_url);
					console.log(base_url + 'ganti2/<?= $edit['id']; ?>');
					console.log(data.pesan);
                } else {
                    swal("Gagal!", "Gagal Menambah Data", "error");
                }
            },
            error: function(textStatus) {
                swal("Gagal!", "Internal Server Error", "error"+textStatus);
				console.log(base_url + 'ganti2/<?= $edit['id']; ?>');//console.log(textStatus);
            }
        })
    }
</script>