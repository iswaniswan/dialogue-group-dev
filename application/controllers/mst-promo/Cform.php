<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010306';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->id_company = $this->session->id_company;
        $this->username = $this->session->username;
        $this->i_level = $this->session->i_level;
        $this->i_departement = $this->session->i_departement;
        $this->folder = $this->global['folder'];

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
            
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    public function status()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }

    /** Get Data Type */
	public function get_type()
	{
		$filter = [];
		$data = $this->mmaster->get_type(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->id_promo_type,
				'text' => $row->e_promo_type_name,
			);
		}
		echo json_encode($filter);
	}

    /** Get Validasi */
	public function get_valid()
	{
		header("Content-Type: application/json", true);
		$i_promo_type = $this->input->post('id_promo_type', TRUE);
		$query  = array(
			'valid' => $this->mmaster->get_valid($i_promo_type)->result()
		);
		echo json_encode($query);
	}

	/** Get Data Group */
	public function get_group()
	{
		$filter = [];
		$data = $this->mmaster->get_group(str_replace("'", "", $this->input->get('q')));
		foreach ($data->result() as $row) {
			$filter[] = array(
				'id'   => $row->id,
				'text' => $row->i_harga.' - '.$row->e_harga,
			);
		}
		echo json_encode($filter);
	}

    public function tambah(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'promo'     => $this->mmaster->bacajenis(),
            'group'     => $this->mmaster->bacagroup()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);

    }

    public function getgroup(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('ipromotype') !='') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $ipromotype = strtoupper($this->input->get('ipromotype', FALSE));
            $data       = $this->mmaster->getgroup($cari,$ipromotype);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_price_group,  
                    'text'  => $kuy->i_price_group
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function product(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->product($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->kode,  
                    'text'  => $kuy->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function get_detail_product(){
        /* header("Content-Type: application/json", true);
        $iproduct = strtoupper($this->input->post('i_product', FALSE));
        $data = $this->mmaster->getproduct($iproduct);
        echo json_encode($data->result_array());   */
        header("Content-Type: application/json", true);
		$i_product = $this->input->post('i_product', TRUE);
		$i_price_group = $this->input->post('i_price_group', TRUE);
		$query  = array(
			'detail' => $this->mmaster->get_detail_product($i_product)->result_array()
		);
		echo json_encode($query);
    }

    public function get_customer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->customer($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->id,  
                    'text'  => $kuy->i_customer." - ".$kuy->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function get_detail_customer(){
        /* header("Content-Type: application/json", true);
        $icustomer = strtoupper($this->input->post('i_customer', FALSE));
        $data = $this->mmaster->getcustomer($icustomer);
        echo json_encode($data->result_array());   */
        header("Content-Type: application/json", true);
		$i_customer = $this->input->post('i_customer', TRUE);
		$query  = array(
			'detail' => $this->mmaster->get_detail_customer($i_customer)->result_array()
		);
		echo json_encode($query);
    }

    public function get_area(){
        $filter = [];
        $filter  = [];
        $cari    = strtoupper($this->input->get('q'));
        $data    = $this->mmaster->get_area($cari);
        foreach($data->result() as $kuy){
            $filter[] = array(
                'id'    => $kuy->i_area,  
                'text'  => $kuy->kode." - ".$kuy->e_area_name
            );
        }
        echo json_encode($filter);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->form_validation->set_rules('d_promo', 'd_promo', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('id_promo_type', 'id_promo_type', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_promo_name', 'e_promo_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_promo_start', 'd_promo_start', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('d_promo_finish', 'd_promo_finish', 'trim|required|min_length[0]');
        if ($this->form_validation->run() == true) {
            $this->db->trans_begin();
            $ipromo =$this->mmaster->runningnumber();
            $this->mmaster->save($ipromo);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Promo No:'.$ipromo);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ipromo
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        echo json_encode($data);
        /* $this->load->view('pesan', $data); */
    }
    
    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->get_data($id)->row(),
            'detail'     => $this->mmaster->get_data_detail($id),    
            'customer' 	 => $this->mmaster->get_data_customer($id),
			'area' 		 => $this->mmaster->get_data_area($id),   
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }
    
    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->get_data($id)->row(),
            'detail'     => $this->mmaster->get_data_detail($id),    
            'customer' 	 => $this->mmaster->get_data_customer($id),
			'area' 		 => $this->mmaster->get_data_area($id),   
        );

        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_promo_code = $this->input->post('i_promo_code');
        $this->form_validation->set_rules('id', 'id', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('d_promo', 'd_promo', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('id_promo_type', 'id_promo_type', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('e_promo_name', 'e_promo_name', 'trim|required|min_length[0]');
		$this->form_validation->set_rules('d_promo_start', 'd_promo_start', 'trim|required|min_length[0]');
        $this->form_validation->set_rules('d_promo_finish', 'd_promo_finish', 'trim|required|min_length[0]');
        if ($this->form_validation->run() == true) {
            $this->db->trans_begin();
            $this->mmaster->update();
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Promo No:'.$i_promo_code);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $i_promo_code
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        echo json_encode($data);
        /* $this->load->view('pesan', $data); */
    }
}
/* End of file Cform.php */
