<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021011';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']." Ubah Grade B"
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getbbm(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->getbbm($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_bbm,  
                    'text'  => $kuy->i_area." - ".$kuy->e_area_name." - ".$kuy->i_bbm." - ".$kuy->dbbm
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailitem(){
        header("Content-Type: application/json", true);        
        $ibbm           = $this->input->post('ibbm', FALSE);
        $query  = array(
            'detail' => $this->mmaster->bacadetail($ibbm)->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iicconvertion  = $this->input->post('iicconvertion', TRUE);
        $dicconvertion  = $this->input->post('dicconvertion', TRUE);        
        $th             = date('Y', strtotime($dicconvertion));
        $bl             = date('m', strtotime($dicconvertion));
        $thbl           = date('ym', strtotime($dicconvertion));
        $tehbl          = date('Ym', strtotime($dicconvertion));
        $dicconvertion  = date('Y-m-d', strtotime($dicconvertion));
        $drefference    = $this->input->post('drefference', TRUE);
        $drefference    = date('Y-m-d', strtotime($drefference));
        $iarea          = $this->input->post('iarea', TRUE);
        $irefference    = $this->input->post('irefference', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if(($iicconvertion=='') && ($dicconvertion!='') && ($irefference!='') && ($drefference!='') && (($jml!='')&&($jml!='0'))){
            $this->db->trans_begin();
            for($i=1;$i<=$jml;$i++){
                $iicconvertion      = $this->mmaster->runningnumber($thbl,$iarea);
                $ibbk               = $this->mmaster->runningnumberbbk($tehbl);
                $ibbm               = $this->mmaster->runningnumberbbm($tehbl);
                $dbbk               = $dicconvertion;
                $dbbm               = $dicconvertion;
                $istore             = 'AA';
                $istorelocation     = '01';
                $istorelocationbin  = '00';
                $eremark            = 'Konversi Stock';
                $ibbktype           = '04';
                $ibbmtype           = '03';
                $iarea              = '00';
                $iproduct           = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade      = 'A';
                $iproductmotif      = $this->input->post('iproductmotif'.$i, TRUE);
                $ficconvertion      = 't';
                $nicconvertion      = $this->input->post('nicconvertion'.$i, TRUE);
                $eproductname       = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice         = $this->input->post('vunitprice'.$i, TRUE);
                $vunitprice         = str_replace(',','',$vunitprice);
                $this->mmaster->insertheader($iicconvertion,$dicconvertion,$iproduct,$iproductgrade,$eproductname,$iproductmotif,$ficconvertion,$nicconvertion,$irefference,$drefference);
                $this->mmaster->insertbbkdetail($iproduct,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion,$vunitprice,$iicconvertion,$ibbk,$eremark,$ibbktype);
                $trans = $this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                if(isset($trans)){
                    foreach($trans as $itrans){
                        $q_aw =$itrans->n_quantity_awal;
                        $q_ak =$itrans->n_quantity_akhir;
                        $q_in =$itrans->n_quantity_in;
                        $q_out=$itrans->n_quantity_out;
                        break;
                    }
                }else{
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw  = $itrans->n_quantity_stock;
                            $q_ak  = $itrans->n_quantity_stock;
                            $q_in  = 0;
                            $q_out = 0;
                            break;
                        }
                    }else{
                        $q_aw   = 0;
                        $q_ak   = 0;
                        $q_in   = 0;
                        $q_out  = 0;
                    }
                }
                $this->mmaster->inserttransbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$iicconvertion,$q_in,$q_out,$nicconvertion,$q_aw,$q_ak);
                $emutasiperiode = $th.$bl;
                if($ibbktype=='05'){            
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                        $this->mmaster->updatemutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasibbk5($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }
                }else{
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                        $this->mmaster->updatemutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasibbkelse($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                    }
                }
                if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                    $this->mmaster->updateicbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$q_ak);
                }else{
                    $this->mmaster->inserticbbk($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nicconvertion);
                }
                $this->mmaster->insertbbkheader($iicconvertion,$dicconvertion,$ibbk,$dbbk,$ibbktype,$eremark,$iarea);
                $this->mmaster->insertbbmheader($iicconvertion,$dicconvertion,$ibbm,$dbbm,$ibbmtype,$eremark,$iarea);
                $iproduct2 = $iproduct;
                $this->mmaster->insertdetail($iicconvertion,$dicconvertion,$iproduct2,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion);
                $this->mmaster->insertbbmdetail($iproduct2,$iproductgrade,$eproductname,$iproductmotif,$nicconvertion,$vunitprice,$iicconvertion,$ibbm,$eremark,$ibbmtype);
                $trans=$this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                if(isset($trans)){
                    foreach($trans as $itrans){
                        $q_aw =$itrans->n_quantity_awal;
                        $q_ak =$itrans->n_quantity_akhir;
                        $q_in =$itrans->n_quantity_in;
                        $q_out=$itrans->n_quantity_out;
                        break;
                    }
                }else{
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw  = $itrans->n_quantity_stock;
                            $q_ak  = $itrans->n_quantity_stock;
                            $q_in  = 0;
                            $q_out = 0;
                            break;
                        }
                    }else{
                        $q_aw   = 0;
                        $q_ak   = 0;
                        $q_in   = 0;
                        $q_out  = 0;
                    }
                }
                $this->mmaster->inserttransbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$iicconvertion,$q_in,$q_out,$nicconvertion,$q_aw,$q_ak);
                $emutasiperiode = $th.$bl;
                if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                    $this->mmaster->updatemutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                }else{
                    $this->mmaster->insertmutasibbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$emutasiperiode);
                }
                if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                    $this->mmaster->updateicbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nicconvertion,$q_ak);
                }else{
                    $this->mmaster->inserticbbm($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nicconvertion);
                }

            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input Konversi Stock BBM No:'.$iicconvertion);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iicconvertion
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
