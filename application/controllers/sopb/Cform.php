<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021016';

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
        $idcompany  = strtoupper($this->session->userdata("id_company"));
        $ispg       = strtoupper($this->session->userdata("username"));
        $iarea      = $this->mmaster->cekuser($ispg, $idcompany); 
        $query      = $this->mmaster->customer($idcompany, $ispg);
        if($query->num_rows()>0){
            foreach($query->result() as $xx){
                $icustomer     = $xx->i_customer;
                $eareaname     = $xx->e_area_name;
                $espgname      = $xx->e_spg_name;
                $ecustomername = $xx->e_customer_name;
            }
        }else{
            $icustomer     = '';
            $eareaname     = '';
            $espgname      = '';
            $ecustomername = '';
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'iperiode'      => $this->mmaster->cekperiode(),
            'iarea'         => $iarea,  
            'ispg'          => $ispg,
            'icustomer'     => $icustomer,
            'eareaname'     => $eareaname,
            'espgname'      => $espgname,
            'ecustomername' => $ecustomername,
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function getdetailitem(){
        header("Content-Type: application/json", true);        
        $icustomer = strtoupper($this->input->post('icustomer', FALSE));
        $query  = array(
            'detail' => $this->mmaster->bacadetail($icustomer)->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $istockopname   = $this->input->post('istockopname');
        $dstockopname   = $this->input->post('dstockopname');
        $thbl           = date('ym', strtotime($dstockopname));
        $dstockopname   = date('Y-m-d', strtotime($dstockopname));
        $icustomer      = $this->input->post('icustomer');
        $iarea          = $this->input->post('iarea');
        $ispg           = $this->input->post('ispg');
        $jml            = $this->input->post('jml');
        var_dump($dstockopname, $icustomer, $iarea, $istockopname);
        if((isset($dstockopname) && $dstockopname != '') && (isset($icustomer) && $icustomer != '') && (isset($iarea) && $iarea != '') && ($istockopname == '')){
            $this->db->trans_begin();
            $istockopname = $this->mmaster->runningnumber($icustomer,$iarea,$thbl);
            $this->mmaster->insertheader($istockopname,$dstockopname,$icustomer,$iarea,$ispg);
            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i);
                $iproductgrade    = $this->input->post('iproductgrade'.$i);
                $iproductmotif    = $this->input->post('iproductmotif'.$i);
                $eproductname     = $this->input->post('eproductname'.$i);
                $nstockopname     = $this->input->post('nstockopname'.$i);
                $nstockopname     = str_replace(',','',$nstockopname);
                if($nstockopname>0){
                    $this->mmaster->insertdetail($iproduct, $iproductgrade, $eproductname, $nstockopname,$istockopname, $icustomer, $iproductmotif,$dstockopname,$iarea,$i);
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$icustomer);
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
                    $emutasiperiode='20'.substr($istockopname,3,4);
                    if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode)){
                        $this->mmaster->updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nstockopname,$emutasiperiode);
                    }else{
                        $this->mmaster->insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nstockopname,$emutasiperiode);
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
                $this->Logger->write('Input SO Konsinyasi No:'.$istockopname.' Pelanggan:'.$icustomer);
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
