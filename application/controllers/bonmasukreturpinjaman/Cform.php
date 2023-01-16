<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050105';
   
    public function __construct(){
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
    

    public function index(){
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'title_list' => 'Tambah '.$this->global['title'],
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        echo $this->mmaster->data($this->i_menu);
    }

    public function proses(){
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'title_list' => 'List '.$this->global['title'],
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function tambah(){
        $dfrom = $this->input->post('dfrom');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }

        $dto = $this->input->post('dto');
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
       

        $tmp=explode('-',$dfrom);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $from=$dd.'-'.$mm.'-'.$yy;

        $tmp=explode('-',$dto);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $to=$dd.'-'.$mm.'-'.$yy; 

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'title_list' => 'Tambah '.$this->global['title'],
            'data'       => $this->mmaster->cek_datasj($from, $to)->result(),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
       
        $ibonm          = $this->input->post('ibonm', TRUE);
        $jml            = $this->input->post('jml', TRUE);
       
        $this->db->trans_begin(); 
          

        for($i=1;$i<=$jml;$i++){   
            if($this->input->post('cek'.$i)=='cek'){         
                $isj            = $this->input->post('isj'.$i, TRUE);
                $dbonm          = $this->input->post('dsj'.$i, TRUE);       
                if($dbonm!=''){
                    $tmp=explode("-",$dbonm);
                    $th=$tmp[0];
                    $bl=$tmp[1];
                    $hr=$tmp[2];
                    $dbonm=$th."-".$bl."-".$hr;
                    $thbl=$th.$bl;
                }
                $ipenerima      = $this->input->post('ipenerima'.$i, TRUE);        
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $icolor         = $this->input->post('icolor'.$i, TRUE); 
                $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $nquantity      = $this->input->post('qty'.$i, TRUE); 
                $eremark        = $this->input->post('eremark'.$i, TRUE); 
                $inoitem        = $i;

                $ibonm          = $this->mmaster->runningnumber($thbl);
                $this->mmaster->insertdetail($ibonm, $isj, $iproduct, $icolor, $eproductname, $nquantity, $eremark, $inoitem);
            }
        }

        $this->mmaster->insertheader($ibonm, $isj, $dbonm, $eremark, $ipenerima);
        $this->Logger->write('Simpan dan Update Data '.$this->global['title'].' Kode : '.$ibonm); 
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                    
                );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'      => $ibonm,
            );
        }
        $this->load->view('pesan', $data); 
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isj        = $this->input->post('isj', TRUE);
        
        $this->db->trans_begin();
        $data = $this->mmaster->cancelheader($isj);
        $data = $this->mmaster->cancelsemuadetail($isj);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Bon Masuk (Retur Pinjaman) '.$isj);
            echo json_encode($data);
        }
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibonm = $this->uri->segment('4');
    
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_dataheader($ibonm)->row(),
            'datadetail'        => $this->mmaster->cek_datadetail($ibonm)->result(),           
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
       
        $ibonm          = $this->input->post('ibonm', TRUE);
        $isj            = $this->input->post('isj', TRUE);
        $dbonm          = $this->input->post('dsj', TRUE);       
        $eremark        = $this->input->post('eremark', TRUE); 
        $jml            = $this->input->post('jml', TRUE);
       
        $this->db->trans_begin();          
        $this->mmaster->update($ibonm, $isj, $dbonm, $eremark);
        $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ibonm); 

        // for($i=1;$i<=$jml;$i++){                  
        //     $iproduct       = $this->input->post('iproduct'.$i, TRUE);
        //     $icolor         = $this->input->post('icolor'.$i, TRUE); 
        //     $eproductname   = $this->input->post('eproductname'.$i, TRUE);
        //     $nquantity      = $this->input->post('nquantity'.$i, TRUE); 
        //     $eremark        = $this->input->post('eremark'.$i, TRUE); 
        //     $inoitem        = $i;

        //     $this->mmaster->insertdetail($ibonm, $isj, $iproduct, $icolor, $eproductname, $nquantity, $eremark, $inoitem);
        // }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                    
                );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'      => $ibonm,
            );
        }
        $this->load->view('pesan', $data); 
    }
}
/* End of file Cform.php */