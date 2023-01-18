<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller{
    public $global = array();
    public $i_menu = '2051001';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }   

    public function index(){

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "List ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'], 
        );      

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        echo $this->mmaster->data($this->i_menu);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj     = $this->uri->segment('4');
        $isumber = $this->uri->segment('5');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($isj, $isumber)->row(),
            'datadetail'        => $this->mmaster->cek_datadetail($isj, $isumber)->result(),           
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }
   
    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj         = $this->input->post('isj', TRUE);
        $dsj         = $this->input->post('dsj', TRUE);
        $dreceive    = $this->input->post('dreceive',TRUE);
        if($dreceive){
             $tmp   = explode('-', $dreceive);
             $day   = $tmp[0];
             $month = $tmp[1];
             $year  = $tmp[2];
             $yearmonth = $year.$month;
             $datereceive = $year.'-'.$month.'-'.$day;
        }
        $isumber     = $this->input->post('isumber', TRUE);
        $iunitjahit  = $this->input->post('iunitjahit', TRUE);
        $ijenis      = $this->input->post('ijenis', TRUE);
        $eremark     = $this->input->post('eremark', TRUE);
        $jml         = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);
        $this->mmaster->deleteheader($isj, $isumber);
        $this->mmaster->insertheader($isj, $dsj, $isumber, $iunitjahit, $ijenis, $eremark, $datereceive);
        $this->mmaster->updateheader($isj, $datereceive, $isumber);  

            for($i=1;$i<=$jml;$i++){
                $iproduct      = $this->input->post('iproduct'.$i, TRUE);
                $eproductname  = $this->input->post('eproductname'.$i, TRUE);
                $icolor        = $this->input->post('icolor'.$i, TRUE);
                $ecolorname    = $this->input->post('ecolorname'.$i, TRUE);
                $nquantity     = $this->input->post('nquantity'.$i, TRUE);
                $eremark       = $this->input->post('eremark'.$i, TRUE);
                $inoitem       = $i;    

                $this->mmaster->deletedetail($iproduct, $icolor, $isj, $isumber);
                $this->mmaster->insertdetail($iproduct, $eproductname, $icolor, $ecolorname, $nquantity, $eremark, $isj, $inoitem, $dsj, $isumber);
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $isj,
                );
        }
    $this->load->view('pesan', $data); 
    }
}
/* End of file Cform.php */