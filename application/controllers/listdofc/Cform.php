<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070513';
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
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea($username, $idcompany)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $dfrom     = $this->input->post('dfrom', TRUE);
        if ($dfrom =='') {
            $dfrom = $this->uri->segment(4);
        }
        $dto       = $this->input->post('dto', TRUE);
        if ($dto   =='') {
            $dto   = $this->uri->segment(5);
        }
        $iarea  = $this->input->post('iarea');
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $cekdepartemen = $this->mmaster->cekdepartemen($username,$idcompany);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $cekdepartemen, $this->global['folder'], $this->global['title'],$this->i_menu);
    }

    public function edit(){
        $ido        = $this->uri->segment(4);
        $isupplier  = $this->uri->segment(5);
        $iarea       = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $iperiode   = $this->mmaster->bacaperiode();
        $query      = $this->db->query("select * from tm_dofc_item where i_do = '$ido'");
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' =>'List '.$this->global['title'],
            'iperiode'   => $iperiode,
            'ido'        => $ido,
            'isupplier'   => $isupplier,
            'jmlitem'    => $query->num_rows(),
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'iarea'      => $iarea,
            'periodeskrg'=> $iperiode,
            'i_menu'     => $this->i_menu,
            'isi'        => $this->mmaster->bacado($ido,$isupplier)->row(),
            'detail'     => $this->mmaster->bacadetaildo($ido,$isupplier)->result()
        );
       
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function product(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iop') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->product($cari,$this->input->get('iop'));
            foreach($data->result() as  $product){
                $filter[] = array(
                    'id'    => $product->i_product,  
                    'text'  => $product->i_product.' - '.$product->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function detailproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct', TRUE);
        $iop      = $this->input->post('iop', TRUE);
        $data     = $this->mmaster->detailproduct($iproduct,$this->input->post('iop', TRUE));
        echo json_encode($data->result_array());  
    } 

    public function deleteitem(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ido           = $this->input->post('ido', TRUE);
        $isupplier     = $this->input->post('isupplier', TRUE);
        $iproduct      = $this->input->post('iproduct', TRUE);
        $iproductgrade = $this->input->post('iproductgrade', TRUE);
        $iproductmotif = $this->input->post('iproductmotif', TRUE);
        $vdogross      = $this->input->post('vdogross',TRUE);
        $iop           = $this->input->post('iop', TRUE);
        $iarea         = $this->input->post('iarea',TRUE);
        $ddo           = $this->input->post('ddo', TRUE);
        if($ddo!=''){
            $tmp=explode("-",$ddo);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $ddo=$th."-".$bl."-".$hr;
            $tahun = substr($ddo,0,4);
        }else{
            $tahun = date('Y');
        }
        $this->db->trans_begin();
        $data = $this->mmaster->deletedetail($iproduct,$iproductgrade,$ido,$isupplier,$iproductmotif,$tahun,$ido);
        if ($data==true) {
            $this->mmaster->uphead($ido,$isupplier,$iop,$iarea,$ddo,$vdogross);
        }
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete Item DO FC Supplier : '.$isupplier.' No : '.$ido);
            echo json_encode($data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $idoold  = $this->input->post('idoold', TRUE);
        $ido     = $this->input->post('ido', TRUE);
        $ido     = trim($ido);
        $ddo     = $this->input->post('ddo', TRUE);
        if($ddo!=''){
           $tmp=explode("-",$ddo);
           $th=$tmp[2];
           $bl=$tmp[1];
           $hr=$tmp[0];
           $ddo=$th."-".$bl."-".$hr;
           $tahun=$th;
        }
        
        $isupplier          = $this->input->post('isupplier', TRUE);
        $iarea              = $this->input->post('iarea', TRUE);
        $iop                = $this->input->post('iop', TRUE);
        $vdogross           = $this->input->post('vdogross',TRUE);
        $vdogross           = str_replace(',','',$vdogross);
        $jml                = $this->input->post('jml', TRUE);
        $istore             = 'AA';
        $istorelocation     = '01';
        $istorelocationbin  = '00';
        $eremark            = 'DO Manual';
        if(($ido!='')
            && ($isupplier!='')
            && (($vdogross!='') || ($vdogross!='0'))
            && ($iop!='')
            && ($ddo!='')){
            $this->db->trans_begin();
            
            $this->mmaster->updateheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross,$idoold);
            for($i=1;$i<=$jml;$i++){
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade    = 'A';
                $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $vproductmill     = $this->input->post('vproductmill'.$i, TRUE);
                $vproductmill     = str_replace(',','',$vproductmill);
                $ndeliver         = $this->input->post('ndeliver'.$i, TRUE);
                $ndeliver         = str_replace(',','',$ndeliver);
                $ndeliverhidden   = $this->input->post('ndeliverhidden'.$i, TRUE);
                $ndeliverhidden   = str_replace(',','',$ndeliverhidden);
                $ntmp             = $this->input->post('ntmp'.$i, TRUE);
                $ntmp             = str_replace(',','',$ntmp);
                $eremark          = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->deletedetail($iproduct, $iproductgrade, $ido, $isupplier, $iproductmotif,$tahun,$idoold);
 
                $th=substr($ddo,0,4);
                $bl=substr($ddo,5,2);
                $emutasiperiode=$th.$bl;
                $tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ido,$ntmp,$eproductname);
                if( ($ntmp!='') && ($ntmp!=0) ){
                    $this->mmaster->updatemutasi04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp,$emutasiperiode);
                    $this->mmaster->updateic04($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ntmp);
                }
                $this->mmaster->insertdetail($iop,$ido,$isupplier,$iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vproductmill,$ddo,$eremark,$i,$idoold);
                $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                if(isset($trans)){
                    foreach($trans as $itrans){
                        $q_aw =$itrans->n_quantity_stock;
                        $q_ak =$itrans->n_quantity_stock;
                        $q_in =0;
                        $q_out=0;
                        break;
                    }
                }else{
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
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
                $this->mmaster->inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
                $th=substr($ddo,0,4);
                $bl=substr($ddo,5,2);
                $emutasiperiode=$th.$bl;
                if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                  $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode);
                }else{
                  $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode);
                }
                if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                  $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$q_ak);
                }else{
                  $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver);
                }
                $this->mmaster->updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$ndeliverhidden,$ntmp);
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data DO Forecast : '.$this->global['title'].' Kode : '.$ido);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ido
                );
            }
        }
        $this->load->view('pesan', $data);  
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ido	    = $this->input->post('ido');
        $iop	    = $this->input->post('iop');
        $isupplier	= $this->input->post('isupplier');
        $this->db->trans_begin();
        $this->mmaster->delete($ido,$iop,$isupplier);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel DO Forecast Supplier : '.$isupplier.' No:'.$ido);
            echo json_encode($data);
        }
    }

}
/* End of file Cform.php */
