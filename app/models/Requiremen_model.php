<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Requiremen_model extends CI_Model {

	public function save($id) {
		if ($id == null) {
			$id_pemesanan	= uniqid('PEMESANAN');
		} else {
			$id_pemesanan	= $id;
		}
		$total				= 0;
		$pajak				= 0;
		$diskon				= 0;
		$subtotal			= 0;
		$biayapengiriman	= 0;
		$log ='';
		if(!$this->input->post('is_create')) {
			foreach ($this->input->post('total') as $key) {
				$total	+= (integer) preg_replace("/(Rp. |,00|[^0-9])/", "", $key);
			}
			foreach ($this->input->post('total_pajak') as $key) {
				$pajak	+= (integer) preg_replace("/(Rp. |,00|[^0-9])/", "", $key);
			}
			foreach ($this->input->post('diskon') as $key) {
				$diskon	+= (integer) preg_replace("/(Rp. |,00|[^0-9])/", "", $key);
			}
			foreach ($this->input->post('subtotal') as $key) {
				$subtotal	+= (integer) preg_replace("/(Rp. |,00|[^0-9])/", "", $key);
			}
			foreach ($this->input->post('biayapengiriman') as $key) {
				$subtotal	+= (integer) preg_replace("/(Rp. |,00|[^0-9])/", "", $key);
			}
		}
		$log .= $id_pemesanan;
		$jenis_pembelian  = $this->input->post('jenis_pembelian');
		$jenis_barang 		= $this->input->post('jenis_barang');
		$noakun1 			    = $this->input->post('noAkun1');
		$noakun 			    = $this->input->post('noakun');
		$harga				    = $this->input->post('harga');
		$tanggal	        = $this->input->post('tanggal');
		$item             = $this->input->post('item');
    
    $this->load->helper('penomoran');
    $penomoran  = penomoran('permintaanPembelian', $this->input->post('idperusahaan'), $this->input->post('dept'));

		if ($id == null) {
			$insertHead	= $this->db->insert('tpemesanan', [
				'id'              		=> $id_pemesanan,
				'nomor'           		=> $penomoran['nomor'],
				'notrans'			    => $penomoran['notrans'],
				'tanggal'			    => $this->input->post('tanggal'),
				'kontakid'			  	=> $this->input->post('kontakid'),
				'gudangid'			  	=> $this->input->post('gudangid'),
				'idperusahaan'			=> $this->input->post('idperusahaan'),
				'departemen'		  	=> $this->input->post('dept'),
				'pejabat'			    => $this->input->post('pejabat'),
				'jenis_pembelian' 		=> $this->input->post('jenis_pembelian'),
				'jenis_barang'			=> $this->input->post('jenis_barang'),
				'cara_pembayaran'		=> $this->input->post('cara_pembayaran') ?? 'cash',
				'project'				=> $this->input->post('project'),
				'catatan'			    => $this->input->post('catatan'),
				'tipe'				    => '2',
				'status'			    => '4',
				'cby'				      => get_user('username'),
				'cdate'				    => date('Y-m-d H:i:s'),
				'total'				    => $total,
				'ppn'				      => $pajak,
				'diskon'			    => $diskon,
				'subtotal'			  	=> $subtotal,
				'biayapengiriman'		=> $biayapengiriman
			]);
		} else {
			$data_ubah = array(
				'tanggal'			=> $this->input->post('tanggal'),
				'kontakid'			=> $this->input->post('kontakid'),
				'gudangid'			=> $this->input->post('gudangid'),
				'idperusahaan'		=> $this->input->post('idperusahaan'),
				'departemen'		=> $this->input->post('dept'),
				'pejabat'			=> $this->input->post('pejabat'),
				'jenis_pembelian'	=> $this->input->post('jenis_pembelian'),
				'jenis_barang'		=> $this->input->post('jenis_barang'),
				'cara_pembayaran'	=> $this->input->post('cara_pembayaran'),
				'catatan'			=> $this->input->post('catatan'),
				'tipe'				=> '2',
				'status'			=> '4',
				'cby'				=> get_user('username'),
				'cdate'				=> date('Y-m-d H:i:s'),
				'total'				=> $total,
				'ppn'				=> $pajak,
				'diskon'			=> $diskon,
				'subtotal'			=> $subtotal,
				'biayapengiriman'	=> $biayapengiriman
				);
			$this->db->where('id', $id_pemesanan);
			$insertHead	= $this->db->update('tpemesanan',$data_ubah); 
			$log .= $this->db->last_query();
			//echo "console.log('ANG')";
		}
		if($insertHead) {
			$this->db->where('idpemesanan', $id);
			$this->db->delete('tpemesanandetail');
			$detail_array = $this->input->post('detail_array');
			$detail_array = json_decode($detail_array);
			$no	= 0;
			foreach($detail_array as $row => $value) {
				$idPemesananDetail	= uniqid('PEM-DET');
				$this->db->insert('tpemesanandetail', [
					'id'			=> $idPemesananDetail,
					'idpemesanan'	=> $id_pemesanan,
					'itemid'		=> $value[0],
					'harga'			=> preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('harga')[$no]),
					'jumlah'		=> $this->input->post('jumlah')[$no],
					'status'		=> '4',
					'diskon'		=> $this->input->post('diskon')[$no],
					'ppn'			=> $this->input->post('total_pajak')[$no],
					'akunno'		=>  $this->input->post('noAkun1')[$no],
					'subtotal'		=> $this->input->post('subtotal')[$no],
					'total'			=> preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('total')[$no]),
					'jumlahsisa'	=> $this->input->post('jumlah')[$no],
					'biayapengiriman'	=> $this->input->post('biayapengiriman')[$no] ?? '0',
				]);
				$idPajak			= explode(',', $this->input->post('idPajak')[$no]);
				$nominal			= explode(',', $this->input->post('pajak')[$no]);
				$pengurangan		= explode(',', $this->input->post('pengurangan')[$no]);
				for ($i=0; $i < count($idPajak); $i++) { 
					$this->db->insert('pajakPembelian', [
						'idPemesananDetail'	=> $idPemesananDetail,
						'idPajak'			=> $idPajak[$i],
						'nominal'			=> preg_replace("/(Rp. |,00|[^0-9])/", "", $nominal[$i]),
						'pengurangan'		=> $pengurangan[$i]
					]);
				}
				$no++;
			}
			$angsuran['id']				= uniqid('PEM-ANG');
			$angsuran['idpemesanan']	= $id_pemesanan;
			if ($this->input->post('tum') !== '') {
				$angsuran['uangmuka']   = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('um'));
				$angsuran['jumlahterm']	= preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('jtem'));
				$angsuran['total']		  = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('tum'));
				$angsuran['a1']			    = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('a1'));
				$angsuran['a2']			    = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('a2'));
				$angsuran['a3']			    = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('a3'));
				$angsuran['a4']			    = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('a4'));
				$angsuran['a5']			    = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('a5'));
				$angsuran['a6']			    = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('a6'));
				$angsuran['a7']			    = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('a7'));
				$angsuran['a8']			    = preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('a8'));
			}
			$this->db->insert('tpemesananangsuran', $angsuran);
			$this->db->insert('tPenerimaan', [
				'pemesanan'	=> $id_pemesanan,
				'total'		=> 0,
				'tipe'		=> 1
			]);

		}

		if($jenis_pembelian == 'barang' && $jenis_barang == 'inventaris'){
			$get_last_inventaris = $this->db->select('*')->from('tinventaris')->order_by('id_inventaris', 'DESC')->get()->row_array();
			$no_reg = $get_last_inventaris['no_register'];
			if(empty($get_last_inventaris)){
				$no = 1;
			} else {
				$no = intval($no_reg) + 1;
			}

			$pch_tgl = explode('-', $tanggal);

			$jml_item = count($harga);
			for($i=0; $i<$jml_item; $i++){
				$no_register = $no+$i;
				// $pch_akun = explode('.', $noakun[$i]);
				// $jenis_akun = $pch_akun[0].'.'.$pch_akun[1].'.'.$pch_akun[2];
				$get_item = $this->db->get_where('mitem', ['nama' => $item[$i]])->row_array();
				$nama_barang = $get_item['nama'];
				$kode_barang = $get_item['kode'];

				$insert_inventaris = [
					'no_register'	=> $no_register,
					'id_pemesanan'	=> $id_pemesanan,
					'idperusahaan'	=> $this->input->post('idperusahaan'),
					'jenis_akun'	=> $noakun1[$i],
					'kode_barang' 	=> $kode_barang,
					'nama_barang'	=> $nama_barang,
					'tahun_perolehan'=> $pch_tgl[0],
					'nominal_asset'=> $harga[$i],
				];

				$a = $this->db->insert('tinventaris', $insert_inventaris);
			}
		}
		$data['pesan'] = $log;
		$data['status'] = 'success';
		$data['message'] = lang('update_success_message').$log;
		return $this->output->set_content_type('application/json')->set_output(json_encode($data));
		
	}

	public function tambah_angsuran() {
		$id = $this->input->post('idpemesanan', true);
		$pemesanan = $this->db->get_where('tpemesanan', [
			'id' => $id
		])->row_array();

		if($this->input->post('tum') == $this->input->post('grandtotal')) {
			$ongkir = array_reduce($this->input->post('ongkir'), function($curr, $val) {
				return $curr + $this->parseInt($val);
			}, 0);

			$subtotal = array_reduce($this->input->post('subtotal'), function($curr, $val) {
				return $curr + $this->parseInt($val);
			}, 0);

			$pemesanan = [
				'catatan' => $this->input->post('catatan'),
				'biayapengiriman' => $ongkir,
				'diskon' => '0',
				'subtotal' => $subtotal,
				'total' => $this->parseInt($this->input->post('grandtotal')),
				'diskon' => $this->getTotalPercentage(),
				'kontakid' => $this->input->post('kontakid'),
				'cara_pembayaran' => $this->input->post('cara_pembayaran'),
				'status' => 6
			];
			
			$this->db->update('tpemesanan', $pemesanan, ['id' => $id]);
			$this->db->delete('tpemesanandetail', [
				'idpemesanan' => $id
			]);

			$i = 0;
			foreach($this->input->post('item') as $itemid) {
				$idDetail = uniqid('PEM-DET');
				$harga = $this->input->post('harga');
				$jumlah = $this->input->post('jumlah');
				$subtotal = $this->input->post('subtotal');
				$diskon = $this->input->post('diskon');
				$ongkir = $this->input->post('ongkir');
				$total = $this->input->post('total');
				$akunno = $this->input->post('akunno');

				$detail = [
					'id' => $idDetail,
					'idpemesanan' => $id,
					'itemid' => $itemid,
					'harga' => $this->parseInt($harga[$i]),
					'jumlah' => $this->parseInt($jumlah[$i]),
					'subtotal' => $this->parseInt($subtotal[$i]),
					'diskon' => $this->parseInt($diskon[$i]),
					'biayapengiriman' => $this->parseInt($ongkir[$i]),
					'total' => $this->parseInt($total[$i]),
					'akunno' => $akunno[$i],
					'status' => 4,
					'jumlahsisa'	=> $this->parseInt($jumlah[$i]),
				];

				$this->db->insert('tpemesanandetail', $detail);
				$this->db->delete('pajakPembelian', [
					'idPemesananDetail' => $this->input->post('row')[$i]
				]);

				$pajak = json_decode($this->input->post('pajak')[$i]);

				foreach($pajak as $pjk) {
					$dataPajak = [
						'idPemesananDetail' => $idDetail,
						'idPajak' => $pjk->id_pajak,
						'nominal' => $this->parseInt($pjk->nominal),
						'pengurangan' => '0'
					];

					$this->db->insert('pajakPembelian', $dataPajak);
				}

				$i++;
			}

			$this->db->update('tpemesananangsuran', [
				'uangmuka'		=> $this->parseInt($this->input->post('um')),
				'jumlahterm'	=> $this->parseInt($this->input->post('jtem')),
				'total'			=> $this->parseInt($this->input->post('tum')),
				'a1'			=> $this->parseInt($this->input->post('a1')),
				'a2'			=> $this->parseInt($this->input->post('a2')),
				'a3'			=> $this->parseInt($this->input->post('a3')),
				'a4'			=> $this->parseInt($this->input->post('a4')),
				'a5'			=> $this->parseInt($this->input->post('a5')),
				'a6'			=> $this->parseInt($this->input->post('a6')),
				'a7'			=> $this->parseInt($this->input->post('a7')),
				'a8'			=> $this->parseInt($this->input->post('a8')),
			]);
			
			$data['status'] = 'success';
			$data['message'] = lang('update_success_message');
			return $this->output->set_content_type('application/json')->set_output(json_encode($data));
		} else {
			$data['status']		= 'gagal';
			$data['message']	= 'Jumlah Total dan Jumlah Uang Muka tidak sama'.$this->input->post('tum').'/'.$this->input->post('grandtotal');
			return $this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}

	public function requiremendetail($idpemesanan) {
		$this->db->select('trequiremendetail.*, mitem.nama as item');
		$this->db->join('mitem', 'trequiremendetail.itemid = mitem.id', 'left');
		$this->db->where('trequiremendetail.idpemesanan', $idpemesanan);
		$get = $this->db->get('trequiremendetail');
		return $get->result_array();
	}

	public function delete() {
		$id = $this->uri->segment(3);
		// $this->db->set('stdel','1');
		$this->db->where('id', $id);
		$update = $this->db->delete('tpemesanan');
		if($update) {
			$data['status'] = 'success';
			$data['message'] = lang('delete_success_message');
		} else {
			$data['status'] = 'error';
			$data['message'] = lang('delete_error_message');
		}
		return $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function get_detail_item() {
		$itemid = $this->input->post('itemid');
		$data	= [];
		if(is_array($itemid)) {
			for ($i=0; $i < count($itemid); $i++) {
				$this->db->select('mnoakun.akunno, tanggaranbelanjadetail.jumlah, mnoakun.idakun');
				$this->db->join('mnoakun', 'tanggaranbelanjadetail.koderekening = mnoakun.idakun');
				//$this->db->where('tanggaranbelanjadetail.id', $itemid[$i]);
				$data[$i] = $this->db->get('tanggaranbelanjadetail')->row_array();
			}
		} else {
			$this->db->select('mnoakun.akunno, tanggaranbelanjadetail.jumlah');
				$this->db->join('mnoakun', 'tanggaranbelanjadetail.koderekening = mnoakun.idakun');
			$this->db->where('tanggaranbelanjadetail.id', $itemid[0]);
			$data[0] = $this->db->get('tanggaranbelanjadetail')->row_array();
		}
		//$data['selamet']= 'A';
		//$data[0] = $this->db->last_query();
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function get_stok_item() {
		$itemid = $this->input->post('itemid', TRUE);
		$gudangid = $this->input->post('gudangid', TRUE);
		if($itemid && $gudangid) {
			$this->db->select_sum('sisa','stok');
			$this->db->where('itemid', $itemid);
			$this->db->where('gudangid', $gudangid);
			$data = $this->db->get('tstokmasuk', 1)->row_array();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}
	public function detail_item() {
		$id = $this->input->post('itemid');
		if($id) {
			$data['status'] = 'success';
			$data['data'] = $this->db->get_where('tanggaranbelanja', array('id' => $id))->row_array();
		} else {
			$data['status'] = 'error';
			$data['message'] = lang('bad_request');
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
	public function pemesanandetail($idpemesanan) {
		$this->db->select('tpemesanandetail.*, mitem.nama as item, mnoakun.idakun, mnoakun.akunno, mnoakun.namaakun');
		$this->db->join('tanggaranbelanjadetail', 'tpemesanandetail.itemid = tanggaranbelanjadetail.id', 'left');
		$this->db->join('mnoakun', 'tanggaranbelanjadetail.koderekening = mnoakun.idakun');
		$this->db->join('mitem', 'tanggaranbelanjadetail.uraian = mitem.id', 'left');
		$this->db->where('tpemesanandetail.idpemesanan', $idpemesanan);
		$data	= $this->db->get('tpemesanandetail')->result_array();
		for ($i=0; $i < count($data); $i++) { 
			$this->db->select('mpajak.nama_pajak, mnoakun.akunno, mnoakun.namaakun, pajakPembelian.nominal, pajakPembelian.pengurangan');
			$this->db->join('mpajak', 'pajakPembelian.idPajak = mpajak.id_pajak');
			$this->db->join('mnoakun', 'mpajak.akun = mnoakun.idakun');
			$data[$i]['pajak']	= $this->db->get_where('pajakPembelian', [
				'idPemesananDetail'	=> $data[$i]['id']
			])->result_array();
		}
		return $data;
	}

	public function get($id)
	{
		$this->db->select('tpemesanan.*, project.noEvent');
		$this->db->join('project', 'tpemesanan.project = project.idProject', 'left');
		$data			= $this->db->get_where('tpemesanan', [
			'id'	=> $id
		])->row_array();
		$this->db->select('tpemesanandetail.*, mnoakun.akunno AS akundet'); 
		$this->db->join('mnoakun', 'tpemesanandetail.akunno = mnoakun.idakun', 'left');
		//$this->db->from('tpemesanandetail');
		//$data['detail'] = $this->db->get()->result_array();
		
		$data['detail']	= $this->db->get_where('tpemesanandetail', [
			'tpemesanandetail.idpemesanan'	=> $id
		])->result_array();
		
		return $data;
	}

	private function parseInt($str)
	{
		return intval(str_replace('.', '', $str));
	}

	private function getTotalPercentage()
	{
		$i = 0;
		$totalAsli = 0;
		$total = 0;
		$harga = $this->input->post('harga');
		$jumlah = $this->input->post('jumlah');
		$diskon = $this->input->post('diskon');

		foreach($this->input->post('item') as $item) {
			$sum = $this->parseInt($harga[$i]) * $this->parseInt($jumlah[$i]);
			$totalAsli += $sum;

			if($diskon[$i] != '0') {
				$total += $sum * $this->parseInt($diskon[$i]) / 100;
			} else {
				$total += $sum;
			}

			$i++;
		}

		if($total == 0 && $totalAsli == 0) return 0;

		return 100 - (($total / $totalAsli) * 100);
	}
	
	//uodate
	public function ganti($id_pemesanan) {
		$total				= 0;
		$pajak				= 0;
		$diskon				= 0;
		$subtotal			= 0;
		$biayapengiriman	= 0;
		$log ='';
		
		$log .= $id_pemesanan;
		$jenis_pembelian  		= $this->input->post('jenis_pembelian');
		$jenis_barang 			= $this->input->post('jenis_barang');
		$noakun1 			    = $this->input->post('noAkun1');
		$noakun 			    = $this->input->post('noakun');
		$harga				    = $this->input->post('harga');
		$tanggal	        	= $this->input->post('tanggal');
		$item             		= $this->input->post('item');
		$detail_array 			= $this->input->post('detail_array');
		$detail_array 			= json_decode($detail_array);
		$no	= 0;
		$this->load->helper('penomoran');
		$penomoran  = penomoran('permintaanPembelian', $this->input->post('idperusahaan'), $this->input->post('dept'));
		//echo json_encode('Enko');
			$log ='';//ubah tpemesanan	 
			$data = array (
				'tanggal'			=> $this->input->post('tanggal'),
				'kontakid'			=> $this->input->post('kontakid'),
				'gudangid'			=> $this->input->post('gudangid'),
				'idperusahaan'		=> $this->input->post('idperusahaan'),
				'departemen'		=> $this->input->post('iddepartemen'),
				'pejabat'			=> $this->input->post('pejabat'),
				'jenis_pembelian'	=> $this->input->post('jenis_pembelian'),
				'jenis_barang'		=> $this->input->post('jenis_barang'),
				'cara_pembayaran'	=> $this->input->post('cara_pembayaran'),
				'catatan'			=> $this->input->post('catatan'),
				'tipe'				=> '2',
				'status'			=> '4',
				'cby'				=> get_user('username'),
				'cdate'				=> date('Y-m-d H:i:s')
				//'total'				=> 12,//$total,
				//'ppn'				=> 12,//$pajak,
				//'diskon'			=> 12,//$diskon,
				//'subtotal'			=> 12,//$subtotal,
				//'biayapengiriman'	=> 12,//$biayapengiriman);
			);
			$this->db->where('id', $id_pemesanan);
			  $insertHead	= $this->db->update('tpemesanan', $data); 
			//$log = $this->db->last_query();
			//ubah tpemesanandetail			
			$data_item = array(
				'item'			   => $item,
 				'jenis_pembelian'  => $jenis_pembelian,
				
			);
			//hapus daftar barang lama
			$this->db->where('idpemesanan', $id_pemesanan);
			$this->db->delete('tpemesanandetail');
			
			foreach($detail_array as $row => $value) {
				$idPemesananDetail	= uniqid('PEM-DET');
				$data_detail[$no] = array(
					'id'			=> $idPemesananDetail,
					'idpemesanan'	=> $id_pemesanan,
					'itemid'		=> $value[0],
					'harga'			=> preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('harga')[$no]),
					'jumlah'		=> $this->input->post('jumlah')[$no],
					'status'		=> '4',
					'diskon'		=> $this->input->post('diskon')[$no],
					'ppn'			=> $this->input->post('total_pajak')[$no],
					'akunno'		=>  $this->input->post('noAkun1')[$no],
					'subtotal'		=> $this->input->post('subtotal')[$no],
					'total'			=> preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('total')[$no]),
					'jumlahsisa'	=> $this->input->post('jumlah')[$no],
					'biayapengiriman'	=> $this->input->post('biayapengiriman')[$no] ?? '0',
				);
				
				//$this->db->where('idpemesanan',$id_pemesanan);
				//$this->db->where('id',$this->input->post('xo')[$no]);
				
				$log .= ''.$this->db->last_query().';';
				$this->db->replace('tpemesanandetail',$data_detail[$no]);
				
				$idPajak			= explode(',', $this->input->post('idPajak')[$no]);
				$nominal			= explode(',', $this->input->post('pajak')[$no]);
				$pengurangan		= explode(',', $this->input->post('pengurangan')[$no]);
				for ($i=0; $i < count($idPajak); $i++) { 
					$this->db->insert('pajakPembelian', [
						'idPemesananDetail'	=> $idPemesananDetail,
						'idPajak'			=> $idPajak[$i],
						'nominal'			=> preg_replace("/(Rp. |,00|[^0-9])/", "", $nominal[$i]),
						'pengurangan'		=> $pengurangan[$i]
					]);
				}
				$no ++;
			}
		$data['pesan'] = $log.';';//.json_encode($data_detail)
		$data['status'] = 'success';
		$data['message'] = lang('update_success_message').$log;
		//echo json_encode($data['status'y;
		return $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
	
	function ganti2($id_pemesanan)
	{
		//cek array yg dikirim
		$no = 0;
		$detail_array 			= $this->input->post('detail_array');
		$detail_array 			= json_decode($detail_array);
		foreach($detail_array as $row => $value) {
			$idPemesananDetail	= uniqid('PEM-DET');
			$data_detail[$no] = array(
					'id'			=> $idPemesananDetail,
					'idpemesanan'	=> $id_pemesanan,
					'itemid'		=> $value[0],
					'harga'			=> preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('harga')[$no]),
					'jumlah'		=> $this->input->post('jumlah')[$no],
					'status'		=> '4',
					'diskon'		=> $this->input->post('diskon')[$no],
					'ppn'			=> $this->input->post('total_pajak')[$no],
					'akunno'		=>  $this->input->post('noAkun1')[$no],
					'subtotal'		=> $this->input->post('subtotal')[$no],
					'total'			=> preg_replace("/(Rp. |,00|[^0-9])/", "", $this->input->post('total')[$no]),
					'jumlahsisa'	=> $this->input->post('jumlah')[$no],
					'biayapengiriman'	=> $this->input->post('biayapengiriman')[$no] ?? '0',
			);
			$no ++;
		};
		$data['pesan'] = $data_detail;//$this->input->post();
		$data['status'] = 'success';
		$data['message'] = lang('update_success_message');
		return $this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
	
}

