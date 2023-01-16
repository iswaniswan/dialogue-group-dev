<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2051201';

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
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'kodemaster' => $this->mmaster->bacagudang()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function datamaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.*,b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->where("i_store", 'G02');
            $this->db->like("UPPER(i_material)", $cari);
            $this->db->or_like("UPPER(e_material_name)", $cari);
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
        
        $dbonk          = $this->input->post("dbonk",true);
        if($dbonk!=''){
            $tmp=explode("-",$dbonk);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $datebonk=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $itujuankirim   = $this->input->post('itujuankirim', TRUE);
        $eremark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE); 
        $ibonk          = $this->mmaster->runningnumber($thbl);

        $this->db->trans_begin();
        $this->mmaster->insertheader($datebonk, $itujuankirim, $eremark, $ibonk);

        for($i=1;$i<=$jml;$i++){
            $imaterial      = $this->input->post('imaterial'.$i, TRUE);
            $nquantity      = $this->input->post('nquantity'.$i, TRUE);
            $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
            $inoitem        = $i;

            $this->mmaster->insertdetail($ibonk, $imaterial, $nquantity, $isatuan, $inoitem);
        }
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonk);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonk);
                $data = array(
                    'sukses' => true,
                    'kode'      => $ibonk,
                );
        }
    $this->load->view('pesan', $data);      
    }
    
    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibonk = $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_data($ibonk)->row(),
            'datadetail' => $this->mmaster->cekdatadetail($ibonk)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ibonk          = $this->input->post("ibonk",true);
        $dbonk          = $this->input->post("dbonk",true);
       
        $itujuankirim   = $this->input->post('itujuankirim', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE); 
        
        $this->db->trans_begin();
        $this->mmaster->updateheader($dbonk, $itujuankirim, $eremark, $ibonk);
         
        for($i=1;$i<=$jml;$i++){
            $imaterial      = $this->input->post('imaterial'.$i, TRUE);
            $nquantity      = $this->input->post('nquantity'.$i, TRUE);
            $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
            $inoitem        = $i;

            $this->mmaster->deletedetail($ibonk, $imaterial);
            $this->mmaster->insertdetail($ibonk, $imaterial, $nquantity, $isatuan, $inoitem);
        }
        $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ibonk);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ibonk);
                $data = array(
                    'sukses' => true,
                    'kode'      => $ibonk,
                );
        }
    $this->load->view('pesan', $data);      
    }
}
/* End of file Cform.php */
