<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021001';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iperiode'  => $this->mmaster->cekperiode(),
            'store'     => $this->mmaster->bacastore($username, $idcompany)->result()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getdetail(){
        header("Content-Type: application/json", true);
        $istore     = $this->input->post('istore', FALSE);
        $dstockopname  = $this->input->post('dso', FALSE);
        $istorelocation  = $this->input->post('istorelocation', FALSE);
        $tmp      = explode('-',$dstockopname);
        $th       = $tmp[2];
        $bl       = $tmp[1];
        $dt       = $tmp[0];
        $iperiode = $th.$bl;
        $query    = array(
            'detail' => $this->mmaster->getdetail($istore, $istorelocation, $iperiode)->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $istockopname       = '';
        $dstockopname       = $this->input->post('dstockopname');
        if($dstockopname!=''){
            $tmp=explode("-",$dstockopname);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dstockopname=$th."-".$bl."-".$hr;
            $thbl=substr($th,2,2).$bl;
        }
        $istore             = $this->input->post('istore');
        $iarea              = $this->input->post('iarea');
        $istorelocation     = $this->input->post('istorelocation');
        $istorelocationbin  = '00';
        $jml                = $this->input->post('jml');
        if ((isset($dstockopname) && $dstockopname != '') && (isset($istore) && $istore != '')  && (isset($istorelocation) && $istorelocation != '') && ($istockopname == '')){
            $this->db->trans_begin();
            $istockopname   = $this->mmaster->runningnumber($iarea,$thbl);
            $this->mmaster->insertheader($istockopname,$dstockopname,$istore,$istorelocation,$iarea);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i);
                $iproductgrade  = $this->input->post('iproductgrade'.$i);
                $iproductmotif  = $this->input->post('iproductmotif'.$i);
                $eproductname   = $this->input->post('eproductname'.$i);
                $nstockopname   = $this->input->post('nstockopname'.$i);
                $nstockopname   = str_replace(',','',$nstockopname);
                if (($iproduct!=''||$iproduct!=null) && $nstockopname > 0) {
                    $x++;
                    $this->mmaster->insertdetail($iproduct, $iproductgrade, $eproductname, $nstockopname,$istockopname, $istore, $istorelocation, $istorelocationbin,$iproductmotif,$dstockopname,$iarea,$x);
                    $emutasiperiode='20'.substr($istockopname,3,4);
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                        $this->mmaster->updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nstockopname,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nstockopname,$emutasiperiode);
                    }
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Stock Opname Area:'.$iarea.' No:'.$istockopname);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $istockopname
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
