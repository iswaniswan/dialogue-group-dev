<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070514';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function dataarea(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->bacaarea($cari);
        foreach($data->result() as $row){
            $filter[] = array(
                'id'    => $row->i_area,  
                'text'  => $row->e_area_name
            );
        }
        echo json_encode($filter);
    }
    
    public function view(){
    	$area	= $this->input->post('iarea');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        }
        if($area==''){
            $area=$this->uri->segment(4);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $area,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $iarea  = $this->input->post('iarea');
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($iarea==''){
            $iarea=$this->uri->segment(6);
        }
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id         = $this->input->post('id');
        $isupplier  = $this->input->post('isupplier');
        $iop        = $this->input->post('iop');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($id, $isupplier, $iop);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus DO Supplier : '.$isupplier.' No : '.$id);
            echo json_encode($data);
        }
    }

    public function edit(){
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $id         = $this->uri->segment(4);
            $isupplier  = $this->uri->segment(5);
            $dfrom      = $this->uri->segment(6);
            $dto        = $this->uri->segment(7);
            $iarea      = $this->uri->segment(8);
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Detail ".$this->global['title'],
                'id'            => $id,
                'isupplier'     => $isupplier,
                'iarea'         => $iarea,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($id,$isupplier),
                'detail'        => $this->mmaster->bacadetail($id,$isupplier),
            );   
        }        

        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function product(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iop') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->product($cari,$this->input->get('iop'));
            foreach($data->result() as  $product){
                $filter[] = array(
                    'id'    => $product->kode,  
                    'text'  => $product->kode.' - '.$product->nama
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
            $this->mmaster->updatehead($ido,$isupplier);
        }
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete Item DO Supplier : '.$isupplier.' No : '.$ido);
            echo json_encode($data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idoold  = $this->input->post('idoold', TRUE);
        $ido  = $this->input->post('ido', TRUE);
        $ido  = trim($ido);
        $ddo  = $this->input->post('ddo', TRUE);
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
        if(($ido!='') && ($isupplier!='') && (($vdogross!='') || ($vdogross!='0')) && ($iop!='') && ($ddo!='')){
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
                if(($ntmp!='') && ($ntmp!=0)){
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
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update DO No : '.$ido.' Supplier : '.$isupplier);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ido
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
