<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '205020102';

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
            'kodemaster'=> $this->mmaster->bacagudang(),
            'kodejenis'=> $this->mmaster->bacajenis(),
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
        
        $dbonm = $this->input->post("dbonm",true);
        $tmp = explode('-', $this->input->post('dbonm'));
		$hr = $tmp[0];
		$bl = $tmp[1];
		$th = substr($tmp[2],2,2);
        $dbonm = $tmp[2].'-'.$bl.'-'.$hr;
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $ibonmanual 			= $this->input->post('ibonmanual', TRUE);
        $ikodejenis 			= $this->input->post('ikodejenis', TRUE);
        $remark                 = $this->input->post('eremark', TRUE);
        $nobonm                 = $this->mmaster->runningnumberbonm($th,$bl);
        $jml                    = $this->input->post('jml', TRUE); 
        $bonmcancel               = 'f';
        $query 	    = $this->db->query("SELECT current_timestamp as c");
	   	$row   	    = $query->row();
        $now	    = $row->c;
        $this->db->trans_begin();
        $this->mmaster->insertheader($dbonm, $ikodemaster, $ibonmanual, $ikodejenis, $remark, $nobonm, $bonmcancel, $now);
            for($i=1;$i<=$jml;$i++){
                $imaterial      = $this->input->post('imaterial'.$i, TRUE);
                $ematerialname  = $this->input->post('ematerialname'.$i, TRUE);
                $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                $nquantitykonv  = $this->input->post('nquantitykonv'.$i, TRUE);
                $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
                $esatuankonv    = $this->input->post('esatuankonv'.$i, TRUE);
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $this->mmaster->insertdetail($nobonm, $imaterial, $ematerialname, $nquantity, $nquantitykonv, $isatuan, $esatuankonv, $edesc, $now, $i);
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nobonm);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nobonm,
                );
        }
    $this->load->view('pesan', $data);      
    }
    function datamaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.*,b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
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

        $i_bonm = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($i_bonm)->row(),
            'data2' => $this->mmaster->cek_datadet($i_bonm)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dbonm = $this->input->post("dbonm",true);
        // $tmp = explode('-', $this->input->post('dbonm'));
		// $hr = $tmp[0];
		// $bl = $tmp[1];
		// $th = substr($tmp[2],2,2);
        // $dbonm = $tmp[2].'-'.$bl.'-'.$hr;
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $ibonmanual 			= $this->input->post('ibonmanual', TRUE);
        $ikodejenis 			= $this->input->post('ikodejenis', TRUE);
        $remark                 = $this->input->post('eremark', TRUE);
        $nobonm                 = $this->input->post('nobonm', TRUE);
        $jml                    = $this->input->post('jml', TRUE); 
        $bonmcancel               = 'f';
        $query 	    = $this->db->query("SELECT current_timestamp as c");
	   	$row   	    = $query->row();
        $now	    = $row->c;
        $this->db->trans_begin();
        if ($nobonm != '' && $ikodemaster != ''){
            $cekada = $this->mmaster->cek_dataheader($nobonm);
            $this->mmaster->updateheader($nobonm, $dbonm, $ibonmanual, $remark, $now);
            if($cekada->num_rows() > 0){
            for($i=1;$i<=$jml;$i++){
                
                $imaterial      = $this->input->post('imaterial'.$i, TRUE);
                $ematerialname  = $this->input->post('ematerialname'.$i, TRUE);
                $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                $nquantitykonv  = $this->input->post('nquantitykonv'.$i, TRUE);
                $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
                $esatuankonv    = $this->input->post('esatuankonv'.$i, TRUE);
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $cekdet = $this->mmaster->cekdatadetail($nobonm, $imaterial);
                if($cekdet->num_rows() > 0){
                    $this->mmaster->updatedetail($nquantity,$nquantitykonv,$nobonm, $imaterial);
                }else{
                    $this->mmaster->insertdetail($nobonm, $imaterial, $ematerialname, $nquantity, 
                    $nquantitykonv, $isatuan, $esatuankonv, $edesc, $now, $i);
                }
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$nobonm);
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
                    'kode'      => $nobonm,
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

        // public function approve(){
        // $data = check_role($this->i_menu, 3);
        // if(!$data){
        //     redirect(base_url(),'refresh');
        // }
        
        //     $ipp= $this->input->post('ipp', TRUE);
        //     $data = array(
        //         'folder' => $this->global['folder'],
        //         'title' => "View ".$this->global['title'],
        //         'title_list' => 'List '.$this->global['title'],
        //         'data' => $this->mmaster->cek_data($ipp)->row(),
        //         'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        //     );
        
        //     $this->Logger->write('Membuka Menu Approve PP'.$this->global['title']);
        
        //     $this->load->view($this->global['folder'].'/vformapprove', $data);
        // }

        public function approve(){

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
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
        }
        public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ipp 			= $this->input->post('ipp', TRUE);
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $query 	= $this->db->query("SELECT current_timestamp as c");
	   		$row   	= $query->row();
            $now	  = $row->c;
            $this->db->trans_begin(); 
        $this->mmaster->approve($ipp, $now);
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
            $this->load->view('pesan', $data);  
        }
    }
/* End of file Cform.php */
