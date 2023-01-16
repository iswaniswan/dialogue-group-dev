<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050604';

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

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'area'=> $this->mmaster->bacagudang(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    function getmaterial(){
        header("Content-Type: application/json", true);
        $imaterial = $this->input->post('i_material');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->where("UPPER(i_material)", $imaterial);
            $this->db->order_by('a.i_material', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function material(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_material, a.e_material_name ,b.i_satuan, b.e_satuan");
            $this->db->from("from tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->or_like("UPPER(i_color)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->nama,
        
                );
            }          
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $remark= $this->input->post('eremark', TRUE);
        $jml= $this->input->post('jml', TRUE); 
        $ppcancel = 'f';
        $query 	= $this->db->query("SELECT current_timestamp as c");
        
	   		$row   	= $query->row();
	    	$now	  = $row->c;

	    	$dpp = $this->input->post("dpp",true);
	    	if($dpp){
	    		 $tmp 	= explode('-', $dpp);
	    		 $day 	= $tmp[0];
	    		 $month = $tmp[1];
	    		 $year	= $tmp[2];
	    		 $yearmonth = $year.$month;
	    		//  $datepp = $year.'-'.$month.'-'.$day;
        }
            $this->db->trans_begin(); 
            $ipp = $this->mmaster->runningnumber($yearmonth);
            $this->mmaster->insertheader($ikodemaster,$ppcancel,$now,$datepp,$ipp,$remark);
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ipp);
            for($i=1;$i<=$jml;$i++){
                $imaterial= $this->input->post('imaterial'.$i, TRUE);
                $isatuan= $this->input->post('isatuan'.$i, TRUE);
                // echo $isatuan;
                // die; 
                $nquantity= $this->input->post('nquantity'.$i, TRUE); 
                $eremark= $this->input->post('eremark'.$i, TRUE);

                $vprice = '0';
                $fopcomplete = 'f';
                $this->mmaster->insertdetail($ipp, $imaterial ,$isatuan ,
                $nquantity ,$vprice ,$fopcomplete,$i);
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $ipp,
                );
        }
    $this->load->view('pesan', $data);      
    }
    function datamaterial(){
        $filter = [];
        $ikodemaster = $this->uri->segment(4);
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.*,b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            // $this->db->join("tr_master_gudang c","a.i_kode_master=b.i_store");
            $this->db->where('a.i_store', $ikodemaster);
            // $this->db->order_by('a.i_material', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $material){       
                    $filter[] = array(
                    'id' => $material->i_material,  
                    'text' => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  
    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ipp = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($ipp)->row(),
            'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
            $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
            $ipp 			        = $this->input->post('ipp', TRUE);
            $remark                 = $this->input->post('eremark', TRUE);
            $jml                    = $this->input->post('jml', TRUE);
            $ppcancel               = 'f';
            $query 	                = $this->db->query("SELECT current_timestamp as c");
	   		$row   	            = $query->row();
	    	$now	            = $row->c;
            $dpp                = $this->input->post("dpp",true);
            $this->db->trans_begin(); 
	    	$this->mmaster->updateheader($ipp, $ikodemaster, $remark, $dpp, $now);
        if ($ipp != '' && $ikodemaster != ''){
            $cekada = $this->mmaster->cek_dataheader($ipp);
            // echo $jml;
            //     die;
            if($cekada->num_rows() > 0){
            for($i=1;$i<=$jml;$i++){
                
                $imaterial= $this->input->post('imaterial'.$i, TRUE);
                $isatuan= $this->input->post('isatuan'.$i, TRUE); 
                $nquantity= $this->input->post('qty'.$i, TRUE); 
                $eremark= $this->input->post('eremark'.$i, TRUE);
                $vprice = '0';
                $fopcomplete = 'f';
                $cekdet = $this->mmaster->cekdatadetail($ipp, $imaterial);
                if($cekdet->num_rows() > 0){
                    $this->mmaster->updatedetail($nquantity,$ipp,$imaterial);
                }else{
                    $this->mmaster->insertdetail($ipp, $imaterial ,$isatuan ,
                                                $nquantity ,$vprice ,$fopcomplete,$i);
                }
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ipp);
            }
        }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ipp
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
}


    public function view(){

        $ipp = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($ipp)->row(),
            'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

        public function approve(){

            $data = check_role($this->i_menu, 1);
            if(!$data){
                redirect(base_url(),'refresh');
            }
    
            $i_bonk = $this->uri->segment('4');
    
            $data = array(
                'folder' => $this->global['folder'],
                'title' => "Edit ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'data' => $this->mmaster->cek_data($i_bonk)->row(),
                'data2' => $this->mmaster->cek_datadet($i_bonk)->result(),
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
        }
        public function approve2(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibonk 			= $this->input->post('ibonk', TRUE);
        $query 	= $this->db->query("SELECT current_timestamp as c");
	   		$row   	= $query->row();
            $now	  = $row->c;
            $this->db->trans_begin(); 
        $this->mmaster->approve($ibonk);
        if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ibonk
                );
            }
            $this->load->view('pesan', $data);  
        }

        public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $i_pp        = $this->input->post('i_pp', TRUE);
        
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($i_pp);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Cancel Permintaan Pembelian '.$i_pp);
                echo json_encode($data);
            }
        }
    }
/* End of file Cform.php */
