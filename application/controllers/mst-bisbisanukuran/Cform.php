<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010206';
   
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
		echo $this->mmaster->data($this->i_menu, $this->global['folder']);
    }
    
    public function status(){
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

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'bisbisan_ukuran'   => $this->mmaster->get_ukuran()->result() ,
            'material'          => $this->mmaster->bacamaterial()
                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikodeukuran      = $this->input->post('ikodeukuran', TRUE);
        $enamaukuran      = $this->input->post('enamaukuran', TRUE); 
        $imaterial        = $this->input->post('imaterial', TRUE);
        $dbuat            = $this->input->post('dbuat', TRUE);
        $dberlaku         = $this->input->post('dberlaku', TRUE);
        $eremark          = $this->input->post('eremark', TRUE); 
        
        if($dbuat!=''){
            $tmp=explode("-",$dbuat);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dbuat=$th."-".$bl."-".$hr;
        }

        if($dberlaku!=''){
            $tmp=explode("-",$dberlaku);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dberlaku=$th."-".$bl."-".$hr;
        }
        
        if ($ikodeukuran != '' && $enamaukuran != '' && $imaterial != '' && $dbuat != '' && $dberlaku != ''){
                $cekada = $this->mmaster->cek_data($ikodeukuran);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikodeukuran);
                    $this->mmaster->insert($ikodeukuran, $enamaukuran, $imaterial, $dbuat, $dberlaku, $eremark);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ikodeukuran
                    );
                }
        }else{
                $data = array(
                    'sukses' => false,
                );
        }
        $this->load->view('pesan', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $igroupbrg  = $this->uri->segment(5);
        $ikategori  = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'            => $this->mmaster->cek_data_detail($id, $idcompany),
            'bisbisan'          => $this->mmaster->cek_data_detail_bisbisan($id, $idcompany),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function get_jenis_potong(){
        $filter = [];
        //$idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $this->input->get('q'));
        $data      = $this->mmaster->get_jenis_potong($cari);
        if ($data->num_rows()>0) {
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'   => $row->id,  
                    'text' => $row->e_jenis_potong,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

        /** Get Jenis Potong Detail */
    public function get_jenis_potong_detail()
    {
        header("Content-Type: application/json", true);
        $id = $this->input->post('id', TRUE);
        $query  = array(
            'data' => $this->mmaster->get_jenis_potong_detail($id)->row()
        );
        echo json_encode($query);
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ikodeukuran = $this->input->post('i_kodeukuran', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ikodeukuran);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Ukuran Bis-bisan ' . $ikodeukuran);
            echo json_encode($data);
        }
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id                 = $this->input->post('id', TRUE);
        $ikodebrg           = $this->input->post('ikodebrg', TRUE); 
        // $ikelompok          = $this->input->post('ikategori', TRUE);
        // $ijenisbrg          = $this->input->post('ijenisbrg', TRUE);         
        // $isatuan            = $this->input->post('isatuan', TRUE); 
        $enamabrg           = $this->input->post('enamabrg', TRUE); 
        // $edeskripsi         = $this->input->post('edeskripsi', TRUE);
        // $isupplier          = $this->input->post('isupplier', TRUE); 
        // $igroupbrg          = $this->input->post('igroupbrg', TRUE);       
        // $npanjang           = $this->input->post('npanjang', TRUE);      
        // $nlebar             = $this->input->post('nlebar', TRUE);      
        // $ntinggi            = $this->input->post('ntinggi', TRUE);      
        // $nberat             = $this->input->post('nberat', TRUE); 
        // $isatuanberat       = $this->input->post('isatuanberat', TRUE); 
        // $isatuanukuran      = $this->input->post('isatuanukuran', TRUE);   
        // $ibrand             = $this->input->post('ibrand', TRUE);
        // $istyle             = $this->input->post('istyle', TRUE);  
        // $istatusproduksi    = $this->input->post('istatusproduksi', TRUE);  
        // $idivisi           = $this->input->post('idivisi', TRUE);
        // $dregister          = $this->input->post('dregister', TRUE);  
        // if($dregister){
        //          $tmp   = explode('-', $dregister);
        //          $day   = $tmp[0];
        //          $month = $tmp[1];
        //          $year  = $tmp[2];
        //          $dateregister = $year.'-'.$month.'-'.$day;
        // }
        // if($npanjang == '' || $nlebar == '' ||  $ntinggi == '' ||  $nberat == ''){
        //     $npanjang       = 0;      
        //     $nlebar         = 0;   
        //     $ntinggi        = 0;      
        //     $nberat         = 0;
        // } 
        
        // if($istatusproduksi == ''){
        //     $istatusproduksi = null;
        // }

        // if($ibrand == ''){
        //     $ibrand = null;
        // }
        
        // if($istyle == ''){
        //     $istyle = null;
        // }

        // if($isupplier == ''){
        //     $isupplier = null;
        // }

        if ($id != ''){  
            $this->db->trans_begin();           
            $this->mmaster->update($id, $ikodebrg, $enamabrg);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ikodebrg);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikodebrg
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

        $id         = $this->uri->segment(4);
        $ikodeukuran    = $this->uri->segment('4');
        $idcompany      = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($ikodeukuran,$idcompany)->row(),
            'detail'            => $this->mmaster->cek_data_detail($id, $idcompany),
            'bisbisan'          => $this->mmaster->cek_data_detail_bisbisan($id, $idcompany),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */