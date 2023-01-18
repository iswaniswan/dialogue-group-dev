<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021005';

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
        $iadj  = $this->uri->segment(4);
        $iarea = $this->uri->segment(5);
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
            'iarea'  => $iarea,
            'iadj'   => $iadj,
            'isi'    => $this->mmaster->baca($iadj,$iarea),
            'detail' => $this->mmaster->bacadetail($iadj,$iarea)
        );
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $user = $this->session->userdata('username');
        $iadj = $this->input->post('iadj', TRUE);
        $dadj = $this->input->post('dadj', TRUE);
        if($dadj!=''){
            $tmp=explode("-",$dadj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dadj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $iarea              = $this->input->post('iarea', TRUE);
        $istore             = $this->input->post('istore', TRUE);
        $istorelocation     = $this->input->post('istorelocation', TRUE);
        $istorelocationbin  = '00';
        $eremark            = $this->input->post('eremark', TRUE);
        $istockopname       = $this->input->post('istockopname', TRUE);
        $jml                = $this->input->post('jml', TRUE);
        if($dadj!='' && $istockopname!='' && $eremark!='' && $iarea!=''){
            $this->db->trans_begin();
            $this->mmaster->approve($iadj, $iarea, $user);
            $x = 0;
            for($i=1;$i<=$jml;$i++){
                $iproduct = $this->input->post('iproduct'.$i, TRUE);
                if (($iproduct!=''||$iproduct!=null)) {
                    $x++;
                    $iproductgrade    = $this->input->post('grade'.$i, TRUE);
                    $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                    $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                    $nquantity        = $this->input->post('nquantity'.$i, TRUE);
                    $eremark          = $this->input->post('eremark'.$i, TRUE);
                    
                    /*--------------------STOCK------------------*/
                    $trans = $this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw  = $itrans->n_quantity_awal;
                            $q_ak  = $itrans->n_quantity_akhir;
                            $q_in  = $itrans->n_quantity_in;
                            $q_out = $itrans->n_quantity_out;
                            break;
                        }
                    }else{
                        $trans = $this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                        if(isset($trans)){
                            foreach($trans as $itrans){
                                $q_aw =$itrans->n_quantity_stock;
                                $q_ak =$itrans->n_quantity_stock;
                                $q_in =0;
                                $q_out=0;
                                break;
                            }
                        }else{
                            $q_aw=0;
                            $q_ak=0;
                            $q_in=0;
                            $q_out=0;
                        }
                    }
                    $query  = $this->db->query("SELECT current_timestamp as c, to_char(current_timestamp,'yyyymm') as d");
                    $row = $query->row();
                    $emutasiperiode = $row->d;
                    if($nquantity>0){
                        $ibbm=$this->mmaster->runningnumberbbm($thbl,$iarea);
                        $this->mmaster->insertheaderbbm($ibbm,$iadj,$dadj,$iarea,$eremark);
                        $this->mmaster->insertdetailbbm($ibbm,$iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$nquantity,0,$eremark,$eproductname,$dadj,$x);
                        $this->mmaster->inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbm,$q_in,$q_out,$nquantity,$q_aw,$q_ak);
                        if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                            $this->mmaster->updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                        }else{
                            $this->mmaster->insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                        }
                        if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                            $this->mmaster->updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$q_ak);
                        }else{
                            $this->mmaster->inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nquantity);
                        }
                    }else{
                        $nquantity = $nquantity*-1;
                        $ibbk = $this->mmaster->runningnumberbbk($thbl,$iarea);
                        $this->mmaster->insertheaderbbk($ibbk,$iadj,$dadj,$iarea,$eremark);
                        $this->mmaster->insertdetailbbk($ibbk,$iadj,$iarea,$iproduct,$iproductmotif,$iproductgrade,$nquantity,0,$eremark,$eproductname,$dadj,$x);
                        $this->mmaster->inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ibbk,$q_in,$q_out,$nquantity,$q_aw,$q_ak);
                        if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode))
                        {
                            $this->mmaster->updatemutasibbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                        }else{
                            $this->mmaster->insertmutasibbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$emutasiperiode);
                        }
                        if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin))
                        {
                            $this->mmaster->updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nquantity,$q_ak);
                        }else{
                            $this->mmaster->inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nquantity);
                        }
                    }
                    /*-----------------END STOCK-----------------*/
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Adjustment No:'.$iadj.' Area'.$iarea);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iadj
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
