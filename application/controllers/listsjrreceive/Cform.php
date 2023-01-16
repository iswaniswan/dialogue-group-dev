<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107030204';

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
            'i_area'    => $this->mmaster->cekarea(),
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
        $xarea = $this->mmaster->cekarea();
        echo $this->mmaster->data($dfrom,$dto,$iarea,$this->global['folder'],$this->i_menu,$xarea);
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

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isjr   = $this->input->post('isjr');
        $iarea  = $this->input->post('iarea');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($isjr, $iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Menghapus Data SJ Retur Receive '.$iarea.' No:'.$isjr);
            echo json_encode($data);
        }
    }

    public function edit(){
        /*$data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }*/
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $isjr   = $this->uri->segment(4);
            $iarea  = $this->uri->segment(5);
            $dfrom  = $this->uri->segment(6);
            $dto    = $this->uri->segment(7);
            $xarea  = $this->uri->segment(8);
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'isjr'          => $isjr,
                'iarea'         => $iarea,
                'xarea'         => $xarea,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'i_level'       => $this->session->userdata('i_level'),
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($isjr,$iarea),
                'detail'        => $this->mmaster->bacadetail($isjr,$iarea),
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function product(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->product($cari);
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
        $iproduct = $this->input->post('iproduct', FALSE);
        $data     = $this->mmaster->detailproduct($iproduct);
        echo json_encode($data->result_array());  
    } 

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj            = $this->input->post('isj', TRUE);
        $dsjreceive     = $this->input->post('dsjreceive', TRUE);         
        if($dsjreceive!=''){   
            $tmp=explode("-",$dsjreceive);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dsjreceive=$th."-".$bl."-".$hr;
            $thbl       = substr($th,2,2).$bl;
            $tmpsj      = explode("-",$isj);
            $firstsj    = $tmpsj[0];
            $lastsj     = $tmpsj[2];
            $newsj      = $firstsj."-".$thbl."-".$lastsj;
        }
        $dsj            = $this->input->post('dsj', TRUE);         
        if($dsj!=''){   
            $dsj        = date('Y-m-d', strtotime($dsj));
        }
        $iarea          = $this->input->post('iarea', TRUE);
        $isjold         = $this->input->post('isjold', TRUE);
        $vspbnetto      = $this->input->post('vsj', TRUE);
        $vspbnetto      = str_replace(',','',$vspbnetto);
        $vsjrec         = $this->input->post('vsjrec', TRUE);
        $vsjrec         = str_replace(',','',$vsjrec);
        $jml            = $this->input->post('jml', TRUE);
        $gaono          = true;
        for($i=1;$i<=$jml;$i++){
            $cek=$this->input->post('chk'.$i, TRUE);
            if($cek=='on'){
                $gaono=false;
            }
            if(!$gaono) break;
        }
        if(!$gaono){
            $this->db->trans_begin();
            $istore               = 'AA';
            $istorelocation       = '01';
            $istorelocationbin    = '00';
            $istore2              = $this->input->post('istore', TRUE);
            $istorelocation2      = $this->input->post('istorelocation', TRUE);
            $istorelocationbin2   = $this->input->post('istorelocationbin', TRUE);
            $this->mmaster->updatesjheader($isj,$iarea,$isjold,$dsjreceive,$vspbnetto,$vsjrec);
            for($i=1;$i<=$jml;$i++){
                $cek=$this->input->post('chk'.$i, TRUE);
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade  = 'A';
                $iproductmotif  = $this->input->post('motif'.$i, TRUE);
                $nretur         = $this->input->post('nretur'.$i, TRUE);
                $nretur         = str_replace(',','',$nretur);
                $nreceive       = $this->input->post('nreceive'.$i, TRUE);
                $nreceive       = str_replace(',','',$nreceive);
                $nasal          = $this->input->post('nasal'.$i, TRUE);
                $nasal          = str_replace(',','',$nasal);
                if($nretur==''){
                    $nretur=$nreceive;
                }
                if($nasal==''){
                    $nasal=0;
                }
                $this->mmaster->deletesjdetail( $isj, $iarea, $iproduct, $iproductgrade, $iproductmotif);
                $th=substr($dsjreceive,0,4);
                $bl=substr($dsjreceive,5,2);
                $emutasiperiode=$th.$bl;
                $tra=$this->mmaster->deletetrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$isj);
                $this->mmaster->updatemutasi01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nasal,$emutasiperiode);
                $this->mmaster->updateic01($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nasal);
                if($cek=='on'){
                    $eproductname   = $this->input->post('eproductname'.$i, TRUE);
                    $vunitprice     = $this->input->post('vproductmill'.$i, TRUE);
                    $vunitprice     = str_replace(',','',$vunitprice);
                    $eremark        = $this->input->post('eremark'.$i, TRUE);
                    if($eremark==''){
                        $eremark=null;
                    }
                    if($nreceive>0){
                        $this->mmaster->insertsjdetail($iproduct,$iproductgrade,$iproductmotif,$eproductname,$nreceive,$nretur,$vunitprice,$isj,$dsj,$iarea, $istore2,$istorelocation2,$istorelocationbin2,$eremark,$i);                      
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
                        $this->mmaster->inserttrans1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$isj,$q_in,$q_out,$nreceive,$q_aw,$q_ak,$tra);
                        $ada=$this->mmaster->cekmutasi2($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode);
                        if($ada=='ada'){
                            $this->mmaster->updatemutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$emutasiperiode);
                        }else{
                            $this->mmaster->insertmutasi1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$emutasiperiode);
                        }
                        if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                            $this->mmaster->updateic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nreceive,$q_ak);
                        }else{
                            $this->mmaster->insertic1($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$nreceive);
                        }
                    }
                }
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update SJ Retur Receive Area : '.$iarea.' No:'.$isj);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isj
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
