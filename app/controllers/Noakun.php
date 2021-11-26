<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Noakun extends User_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Noakun_model','model');
	}

	public function index() {
		$data['title']    = lang('account_number');
		$data['subtitle'] = lang('list');
		$data['content']  = 'Noakun/index';
		$data             = array_merge($data,path_info());
		$this->parser->parse('template',$data);
	}

	public function index_datatable($no = null) {
		$this->load->library('Datatables');
		$this->datatables->where('mnoakun.stdel', '0');
		if ($no) {
			$this->datatables->like('mnoakun.akunno', '1', 'after');
			$this->datatables->or_like('mnoakun.akunno', '2', 'after');
			$this->datatables->or_like('mnoakun.akunno', '3', 'after');
		}
		$this->datatables->from('mnoakun');
		return print_r($this->datatables->generate());
	}

	public function tambah() {
		$data['title']    = lang('account_number');
		$data['subtitle'] = lang('add_new');
		$data['content']  = 'Noakun/tambah';
		$data             = array_merge($data,path_info());
		$this->parser->parse('default',$data);
	}

	public function edit($id = null) {
		if($id) {
			$data = get_by_id('idakun', $id, 'mnoakun');
			if($data) {
				$data['title']		= lang('account_number');
				$data['subtitle']	= lang('edit');
				$data['content']	= 'Noakun/edit';
				$data				      = array_merge($data,path_info());
				$this->parser->parse('template',$data);
			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}

	public function save() {
		$this->model->save();
	}

	public function delete() {
		$this->model->delete();
	}


	public function detail($id) {
		if($id) {
			$data = get_by_id('noakun',$id,'mnoakun');
			if($data) {
				$tanggalawal = $this->input->get('tanggalawal');
				$tanggalakhir = $this->input->get('tanggalakhir');
				$per_page = $this->input->get('per_page');
				if (!$per_page) $per_page = 0;

				$base_url = site_url('noakun/detail/'.$id);
				if($tanggalawal && $tanggalakhir) {
					$data['tanggalawal'] = $tanggalawal;
					$data['tanggalakhir'] = $tanggalakhir;
					$base_url = site_url('noakun/detail/'.$id.'?tanggalawal='.$tanggalawal.'&tanggalakhir='.$tanggalakhir);
				} else {
					$data['tanggalawal'] = date('Y-m-01');
					$data['tanggalakhir'] = date('Y-m-t');
					$base_url = site_url('noakun/detail/'.$id.'?tanggalawal='.$data['tanggalawal'].'&tanggalakhir='.$data['tanggalakhir']);
				}
				$this->load->library('pagination');		
				$config['base_url'] = $base_url;
				$config['total_rows'] = $this->model->get_jurnal_detail_count($id, $data['tanggalawal'], $data['tanggalakhir']);
				$config['per_page'] = $this->model->get_jurnal_detail_count($id, $data['tanggalawal'], $data['tanggalakhir']);
				$config['full_tag_open'] = '<ul class="pagination">';
				$config['full_tag_close'] = '</ul>';
				$config['first_link'] = 'First';
				$config['first_tag_open'] = '<li class="page-link">';
				$config['first_tag_close'] = '</li>';
				$config['last_link'] = 'Last';
				$config['last_tag_open'] = '<li class="page-link">';
				$config['last_tag_close'] = '</li>';
				$config['next_link'] = '&gt;';
				$config['next_tag_open'] = '<li class="page-link">';
				$config['next_tag_close'] = '</li>';
				$config['prev_link'] = '&lt;';
				$config['prev_tag_open'] = '<li class="page-link">';
				$config['prev_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="page-link bg-info">';
				$config['cur_tag_close'] = '</li>';
				$config['num_tag_open'] = '<li class="page-link">';
				$config['num_tag_close'] = '</li>';
				$config['page_query_string'] = TRUE;
				
				$this->pagination->initialize($config);

				$data['pagination'] = $this->pagination->create_links();
				$data['get_noakun'] = $this->model->get_jurnal_detail($per_page, $config['per_page'], $id, $data['tanggalawal'], $data['tanggalakhir']);

				$data['title'] = lang('acount_number');
				$data['subtitle'] = lang('list');
				$data['content'] = 'Noakun/detail';
				$data = array_merge($data,path_info());
				$this->parser->parse('default',$data);

			} else {
				show_404();
			}
		} else {
			show_404();
		}
	}

	// additional
	public function select2_noakunheader($id = null, $akunno=null) {
		$term = $this->input->get('q');
		if($akunno) {
			$this->db->select('mnoakun.akunno as id, concat(mnoakun.akunno, " - ",mnoakun.namaakun) as text');
			$this->db->where('mnoakun.stheader', '1');
			$data = $this->db->where('akunno', $akunno)->get('mnoakun')->row_array();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		} else {
			$this->db->select('mnoakun.akunno as id, concat(mnoakun.akunno, " - ",mnoakun.namaakun) as text');
			$this->db->where('mnoakun.stdel', '0');
			$this->db->where('mnoakun.stheader', '1');
			$this->db->limit(100);
			if($term) $this->db->like('namaakun', $term);
			$data = $this->db->get('mnoakun')->result_array();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}
	
	public function select2_akunno($id = null, $akunno=null) {
		$term = $this->input->get('q');
		if($akunno) {
			$this->db->select('mnoakun.akunno as id, concat(mnoakun.akunno, " - ",mnoakun.namaakun) as text');
			$this->db->where('mnoakun.stheader', '1');
			$data = $this->db->where('akunno', $akunno)->get('mnoakun')->row_array();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		} else {
			$this->db->select('mnoakun.akunno as id, concat(mnoakun.akunno, " - ",mnoakun.namaakun) as text');
			$this->db->where('mnoakun.stdel', '0');
			$this->db->where('mnoakun.stheader', '1');
			$this->db->limit(100);
			if($term) $this->db->like('namaakun', $term);
			$data = $this->db->get('mnoakun')->result_array();
			$this->output->set_content_type('application/json')->set_output(json_encode($data));
		}
	}

	public function import_data()
	{
		$data           = $this->model->upload_file();
		include APPPATH.'third_party/PHPExcel.php';        
		$excelreader    = new PHPExcel_Reader_Excel2007();    
		$loadexcel      = $excelreader->load('uploads/excel/'.$data['file']);  // Load file yang telah diupload ke folder excel    
		$sheet          = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		$data           = array();        
		$numrow         = 0;    
		foreach($sheet as $row){      
			if($numrow > 1){
				if ($row['B'] == null) {
					if ($row['C'] == null) {
						if ($row['D'] == null) {
							$akunno	= $row['E'];	
						} else {
							$akunno	= $row['D'];
						}
					} else {
						$akunno	= $row['C'];
					}
				} else {
					$akunno	= $row['B'];
				}
				array_push($data, array(     
					'akunno'        => $akunno,
					'namaakun'      => $row['I'],
					'kategoriakun'	=> $row['M'],
					'saldo'         => $row['Q']
				));      
			}            
			$numrow++; // Tambah 1 setiap kali looping    
		}    // Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model    
		$this->model->insert_multiple($data);
		$hasil['status']	= 'success';
		$this->output->set_content_type('application/json')->set_output(json_encode($hasil));
	}

	public function get()
	{
		$id	= $this->input->post('id');
		$this->output->set_content_type('application/json')->set_output(json_encode($this->model->get($id)));
	}

	public function select2_noakun($id1 = null, $id2 = null)
	{
		$term = $this->input->get('q');
		$this->db->select('idakun as id, concat(mnoakun.akunno, " - ",mnoakun.namaakun) as text');
		if ($id1 !== null) {
			if ($id1 == 145) {
				if ($id2 !== null) {
					$this->db->where('idakun', $id2);
					$data = $this->db->get('mnoakun')->row_array();
				} else {
					$this->db->like('akunno', '1', 'after');
					$this->db->or_like('akunno', '4', 'after');
					$this->db->or_like('akunno', '5', 'after');
					if ($term) {
						$this->db->or_like('akunno', $term);
						$this->db->or_like('namaakun', $term);
					}
					$data = $this->db->get('mnoakun')->result_array();
				}
			} else {
				$this->db->where('idakun', $id1);
				$data = $this->db->get('mnoakun')->row_array();
			}
		} else {
			if ($term) {
				$this->db->like('akunno', $term);
				$this->db->or_like('namaakun', $term);
			}
			$data = $this->db->get('mnoakun')->result_array();
		}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function select2NoAkunBarang()
	{
		$term = $this->input->get('q');
		$this->db->select('idakun as id, concat(mnoakun.akunno, " - ",mnoakun.namaakun) as text');
		if ($term) {
			$this->db->like('akunno', $term);
			$this->db->or_like('namaakun', $term);
		} else {
			$this->db->or_like('akunno', '1', 'after');
			$this->db->or_like('akunno', '5', 'after');
			$this->db->or_like('akunno', '6', 'after');
		}
		$data = $this->db->get('mnoakun')->result_array();
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function select2_noakunbeli($id = null) {
		$data	= $this->model->select2NoAkunBeli($id);
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function select2_pendapatan()
	{
		$data	= $this->model->select2_pendapatan();
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function select2_hpp()
	{
		$id = $this->input->get('q');
		$data	= $this->model->select2_hpp($id);
		//$data['pesan'] = $this->db->last_query();
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function getPendapatan()
	{
		return $this->model->getPendapatan();
	}

	public function getHPP()
	{
		return $this->model->getHPP();
	}

	public function selectInventaris()
	{
		$term	= $this->input->get('q');
		$data	= $this->model->selectInventaris($term);
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function jenisAset()
	{
    $term	= $this->input->get('term');
		$data	= $this->model->jenisAset($term);
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
}

