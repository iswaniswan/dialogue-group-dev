<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020902';

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

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekuser($username, $idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'store'     => $this->mmaster->bacastore($iarea, $username, $idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->getproduct($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_product,  
                    'text'  => $kuy->i_product." - ".$kuy->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailproduct(){
        header("Content-Type: application/json", true);        
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');
        $tgl    = date('d-m-Y');
        $tmp    = explode("-",$tgl);
        $thak   = substr($tmp[2],2,2);
        $blak   = $tmp[1];
        $blaw   = $tmp[1];
        $thaw=$thak;
        for($z=1;$z<=3;$z++){
            settype($blaw,'integer');
            $blaw=$blaw-1;
            if($blaw==0){
                $blaw=12;
                $thaw=$thaw-1;
            }
        }
        settype($blaw,'string');
        if(strlen($blaw)==1){
            $blaw='0'.$blaw;
        }
        $peraw=$thaw.$blaw;
        $perak=$thak.$blak;
        $fpaw ='FP-'.$peraw;
        $fpak ='FP-'.$perak;
        $iproduct = $this->input->post('iproduct');
        $data = $this->mmaster->getdetailproduct($iproduct,$fpaw,$fpak,$username,$idcompany);      
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }


        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ispmb      = $this->input->post('ispmb', TRUE);
        $ispmbold   = $this->input->post('ispmbold', TRUE);
        $dspmb      = $this->input->post('dspmb', TRUE);
        if($dspmb!=''){
            $tmp=explode("-",$dspmb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dspmb=$th."-".$bl."-".$hr;
            $thbl=substr($th,2,2).$bl;
        }
        $iarea      = $this->mmaster->getuserarea($username, $idcompany);
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        $fop        = 'f';
        $nprint     = 0;
        if($dspmb!='' && $iarea!=''){
            $this->db->trans_begin();
            $ispmb  = $this->mmaster->runningnumber($thbl);
            $this->mmaster->insertheader($username,$ispmb, $dspmb, $iarea, $fop, $nprint, $ispmbold, $eremark);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct  = $this->input->post('iproduct'.$i, TRUE);
                $norder    = $this->input->post('norder'.$i, TRUE);
                if (($iproduct!=''||$iproduct!=null)&&($norder!=''||$norder!=0)) {
                    $x++;
                    $iproductgrade  = 'A';
                    $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                    $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice     = $this->input->post('vproductmill'.$i, TRUE);
                    $vunitprice     = str_replace(',','',$vunitprice);
                    $nacc           = $this->input->post('nacc'.$i, TRUE);
                    $eremark        = $this->input->post('eremark'.$i, TRUE); 
                    $this->mmaster->insertdetail( $ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$nacc,$vunitprice,$iproductmotif,$eremark,$iarea,$x);              
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input SPMB BBM AP No:'.$ispmb.' Area:'.$iarea);
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
