<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020901';

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

    public function getdetailitem(){
        header("Content-Type: application/json", true);        
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');
        $istore         = $this->input->post('istore', FALSE);
        $istorelocation = $this->input->post('istorelocation', FALSE);
        $dspmb          = $this->input->post('dspmb', FALSE);
        if ($dspmb!='') {
            $tmp        = explode('-',$dspmb);
            $th         = $tmp[2];
            $bl         = $tmp[1];
            $dt         = $tmp[0];
            $iperiode   = $th.$bl;
        }else{
            $iperiode   = date('Ym');
        }
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
        $query  = array(
            /*'jml'    => $this->mmaster->jmldetail($istore,$istorelocation,$iperiode),*/
            'detail' => $this->mmaster->bacamutasi($istore,$istorelocation,$iperiode,$fpaw,$fpak,$username,$idcompany)->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

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
        $iarea      = $this->input->post('iarea', TRUE);
        $eremark    = $this->input->post('eremark', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        $fop        = 'f';
        $nprint     = 0;
        if($dspmb!='' && $iarea!=''){
            $this->db->trans_begin();
            $ispmb  = $this->mmaster->runningnumber($thbl);
            $this->mmaster->insertheader($ispmb, $dspmb, $iarea, $fop, $nprint, $ispmbold, $eremark);
            for($i=1;$i<=$jml;$i++){
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade    = 'A';
                $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice       = $this->input->post('vproductmill'.$i, TRUE);
                $vunitprice       = str_replace(',','',$vunitprice);
                $norder           = $this->input->post('norder'.$i, TRUE);
                $nacc             = $this->input->post('nacc'.$i, TRUE);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                if($norder!='0' && $norder!=0 && $norder!=''){
                    $this->mmaster->insertdetail($ispmb,$iproduct,$iproductgrade,$eproductname,$norder,$nacc,$vunitprice,$iproductmotif,$eremark,$iarea,$i);
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input SPMB Area:'.$iarea.' No:'.$ispmb);
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
