<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020905';

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
        $this->load->library('fungsi');
        /*require_once("php/fungsi.php");*/
    }  

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);

    }

    public function data(){
        echo $this->mmaster->data($this->global['folder']);
    }

    public function edit(){
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ispmb      = $this->uri->segment(4);
        $iarea      = $this->uri->segment(5);
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'isi'               => $this->mmaster->baca($ispmb,$iarea),
            'detail'            => $this->mmaster->bacadetail($ispmb)
        );
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea           = $this->input->post('iarea', TRUE);
        $ispmb           = $this->input->post('ispmb', TRUE);
        $istore          = 'AA';
        $istorelocation  = '01';
        $fspmbclose      = 'f';
        $fspmbcancel     = 'f';
        $jml             = $this->input->post('jml', TRUE);
        if($ispmb!=''){
            $this->db->trans_begin();
            $this->mmaster->updateheader($ispmb,$istore,$istorelocation,$fspmbcancel,$fspmbclose);
            $langsung = true;
            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade  = 'A';
                $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice     = $this->input->post('vproductmill'.$i, TRUE);
                $vunitprice     = str_replace(',','',$vunitprice);
                $nacc           = $this->input->post('nacc'.$i, TRUE);
                $nacc           = str_replace(',','',$nacc);
                $norder         = $this->input->post('norder'.$i, TRUE);
                $norder         = str_replace(',','',$norder);
                $nstock         = $this->input->post('nstock'.$i, TRUE);
                $nstock         = str_replace(',','',$nstock);
                $nqtystock      = $this->input->post('nqtystock'.$i, TRUE);
                $nqtystock      = str_replace(',','',$nqtystock);
                $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                $eremark        = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->updatedetail($ispmb,$iproduct,$iproductgrade,$iproductmotif,$nstock,$iarea);
                if($nstock < $nacc){
                    $langsung=false;
                }
            }
            if($langsung==true){
                $this->mmaster->langsungclose($ispmb);
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Realisasi SPMB Area:'.$iarea.' No:'.$ispmb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispmb
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
