<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070313';

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
        $id     = $this->input->post('id');
        $iarea  = $this->input->post('iarea');
        $tahun  = $this->input->post('tahun');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($id, $iarea, $tahun);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus TTB Retur Area '.$iarea.' No:'.$id);
            echo json_encode($data);
        }
    }

    public function customer(){
        $filter = [];
        if ($this->input->get('iarea')!='') {
            $data   = $this->mmaster->bacacustomer(strtoupper($this->input->get('q')),$this->input->get('iarea', TRUE));
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_customer,  
                    'text'  => $row->e_customer_name
                );
            }
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    public function detailcustomer(){
        header("Content-Type: application/json", true);
        $data     = $this->mmaster->detailcustomer($this->input->post('icustomer', TRUE),$this->input->post('iarea', TRUE));
        echo json_encode($data->result_array());  
    } 

    public function salesman(){
        $filter = [];
        if ($this->input->get('iarea')!='' && $this->input->get('dttb')!='') {
            $dttb   = $this->input->get('dttb');
            $per    = "";
            if($dttb!=''){
                $tmp = explode('-',$dttb);
                $yy  = $tmp[2];
                $bl  = $tmp[1];
                $per = $yy.$bl;
            }
            $data   = $this->mmaster->bacasalesman(strtoupper($this->input->get('q')),$this->input->get('iarea', TRUE),$per);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_salesman,  
                    'text'  => $row->e_salesman_name
                );
            }
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    public function alasan(){
        $filter = [];
        $cari   = strtoupper($this->input->get('q'));
        $data   = $this->mmaster->bacaalasan($cari);
        foreach($data->result() as $row){
            $filter[] = array(
                'id'    => $row->i_alasan_retur,  
                'text'  => $row->e_alasan_returname
            );
        }
        echo json_encode($filter);
    }

    public function edit(){
        /*$data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }*/
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $id     = $this->uri->segment(4);
            $iarea  = $this->uri->segment(5);
            $dfrom  = $this->uri->segment(6);
            $dto    = $this->uri->segment(7);
            $xarea  = $this->uri->segment(8);
            $tahun  = $this->uri->segment(9);
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'id'            => $id,
                'iarea'         => $iarea,
                'xarea'         => $xarea,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'tahun'         => $tahun,
                'i_level'       => $this->session->userdata('i_level'),
                'i_menu'        => $this->i_menu,
                'isi'           => $this->mmaster->baca($id,$iarea,$tahun),
                'detail'        => $this->mmaster->bacadetail($id,$iarea,$tahun),
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function product(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('icustomer') != '' && $this->input->get('ipricegroup') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $data    = $this->mmaster->product($cari,$this->input->get('icustomer'),$this->input->get('ipricegroup'));
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
        $data     = $this->mmaster->detailproduct($iproduct,$this->input->post('icustomer', TRUE),$this->input->post('ipricegroup', TRUE));
        echo json_encode($data->result_array());  
    } 

    public function deleteitem(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct           = $this->input->post('product', TRUE);
        $iproductgrade      = 'A';
        $iproductmotif      = $this->input->post('motif', TRUE);
        $iarea              = $this->input->post('iarea', TRUE);
        $ittb               = $this->input->post('ittb', TRUE);
        $tahun              = $this->input->post('nttbyear', TRUE);
        $nttbdiscount1      = $this->input->post('nttbdiscount1', TRUE);
        $nttbdiscount2      = $this->input->post('nttbdiscount2', TRUE);
        $nttbdiscount3      = $this->input->post('nttbdiscount3', TRUE);
        $vttbdiscount1      = $this->input->post('vttbdiscount1', TRUE);
        $vttbdiscount1      = str_replace(',','',$vttbdiscount1);
        $vttbdiscount2      = $this->input->post('vttbdiscount2', TRUE);
        $vttbdiscount2      = str_replace(',','',$vttbdiscount2);
        $vttbdiscount3      = $this->input->post('vttbdiscount3', TRUE);
        $vttbdiscount3      = str_replace(',','',$vttbdiscount3);
        $vttbdiscounttotal  = $this->input->post('vttbdiscounttotal', TRUE);
        $vttbdiscounttotal  = str_replace(',','',$vttbdiscounttotal);
        $vttbnetto          = $this->input->post('vttbnetto', TRUE);
        $vttbnetto          = str_replace(',','',$vttbnetto);
        $vttbgross          = $this->input->post('vttbgross', TRUE);
        $vttbgross          = str_replace(',','',$vttbgross);
        $jml                = $this->input->post('jml', TRUE);
        $dttb               = $this->input->post('dttb', TRUE);
        $icustomer          = $this->input->post('cust', TRUE);
        $drom               = $this->input->post('dfrom', TRUE);
        $dto                = $this->input->post('dto', TRUE);
        $dreceive1          = $this->input->post('dreceive1', TRUE);
        if($dreceive1!=''){
            $tmp=explode("-",$dreceive1);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dreceive1=$th."-".$bl."-".$hr;
        }else{
            $dreceive1=null;
        }
        $ettbremark         = $this->input->post('ettbremark', TRUE);
        if($ettbremark==''){
            $ettbremark=null;
        }
        $ecustomerpkpnpwp   = $this->input->post('ecustomerpkpnpwp', TRUE);
        if($ecustomerpkpnpwp==''){
            $fttbpkp = 'f';
        }else{
            $fttbpkp = 't';
        }
        $ibbm = $this->input->post('ibbm', TRUE);
        $this->db->trans_begin();
        $this->mmaster->updateheaderdetail($ittb,$iarea,$tahun,$dttb,$dreceive1,$ettbremark,$nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,$vttbgross,$icustomer,$ibbm);
        $data = $this->mmaster->deletedetail($iarea, $ittb, $iproduct, $iproductgrade, $iproductmotif, $tahun);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus Detail TTB Retur Area '.$iarea.' No:'.$ittb);
            echo json_encode($data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iarea              = $this->input->post('iarea', TRUE);
        $ipricegroup        = $this->input->post('ipricegroup',TRUE);
        $inota              = $this->input->post('inota',TRUE);
        $nttbdiscount1      = $this->input->post('nttbdiscount1',TRUE);
        $nttbdiscount2      = $this->input->post('nttbdiscount2',TRUE);
        $nttbdiscount3      = $this->input->post('nttbdiscount3',TRUE);
        $vttbdiscount1      = $this->input->post('vttbdiscount1',TRUE);
        $vttbdiscount2      = $this->input->post('vttbdiscount2',TRUE);
        $vttbdiscount3      = $this->input->post('vttbdiscount3',TRUE);
        $vttbdiscounttotal  = $this->input->post('vttbdiscounttotal',TRUE);
        $vttbdiscount1      = str_replace(',','',$vttbdiscount1);
        $vttbdiscount2      = str_replace(',','',$vttbdiscount2);
        $vttbdiscount3      = str_replace(',','',$vttbdiscount3);
        $vttbdiscounttotal  = str_replace(',','',$vttbdiscounttotal);
        $vttbnetto          = $this->input->post('vttbnetto',TRUE);
        $vttbnetto          = str_replace(',','',$vttbnetto);
        $vttbgross          = $this->input->post('vttbgross',TRUE);
        $vttbgross          = str_replace(',','',$vttbgross);
        $jml                = $this->input->post('jml', TRUE);
        $ittb               = $this->input->post('ittb', TRUE);
        $dttb               = $this->input->post('dttb', TRUE);
        $isalesman          = $this->input->post('isalesmanx',TRUE);
        $ialasanretur       = $this->input->post('ialasanretur', TRUE);
        if($dttb!=''){
            $tmp=explode("-",$dttb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dttb=$th."-".$bl."-".$hr;
        }
        $xtahun   = $this->input->post('nttbyear', TRUE);
        $tahun = $th;
        $dreceive1  = $this->input->post('dreceive1', TRUE);
        if($dreceive1!=''){
            $tmp=explode("-",$dreceive1);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dreceive1=$th."-".$bl."-".$hr;
        }else{
            $dreceive1=null;
        }
        $ettbremark    = $this->input->post('eremark', TRUE);
        if($ettbremark==''){
            $ettbremark=null;
        }
        $ibbm               = $this->input->post('ibbm', TRUE);
        $dbbm               = $dttb;
        $icustomer          = $this->input->post('icustomer', TRUE);
        $istore             = 'AA';
        $istorelocation     = '01';
        $istorelocationbin  = '00';
        $eremark            = 'TTB Retur';
        $ibbktype           = '01';
        $ibbmtype           = '05';
        if(($dttb!='') && ($ittb!='') && ($iarea!='') && ($icustomer!='') && ($ialasanretur!='') && ($jml!='') && ($jml!='0')){
            $this->db->trans_begin();
            $this->mmaster->updateheader( $ittb,$iarea,$tahun,$xtahun,$dttb,$dreceive1,$ettbremark,$nttbdiscount1,$nttbdiscount2,$nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$vttbdiscounttotal,$vttbnetto,$vttbgross,$icustomer,$ibbm,$isalesman,$ialasanretur,$ipricegroup,$inota);
            for($i=1;$i<=$jml;$i++){                
                $iproduct             = $this->input->post('iproduct'.$i, TRUE);
                $iproductgrade        = 'A';
                $iproductmotif        = $this->input->post('motif'.$i, TRUE);
                $eproductname         = $this->input->post('eproductname'.$i, TRUE);
                $vunitprice           = $this->input->post('vunitprice'.$i, TRUE);
                $vunitprice           = str_replace(',','',$vunitprice);
                $ndeliver             = $this->input->post('ndeliver'.$i, TRUE);
                $nquantity            = $this->input->post('nquantity'.$i, TRUE);
                $ettbremark           = $this->input->post('eremark'.$i, TRUE);
                if($ettbremark==''){
                    $ettbremark=null;

                }
                $this->mmaster->updatedetail($iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$nquantity,$vunitprice,$ettbremark,$tahun,$xtahun,$ndeliver,$i);
                $this->mmaster->updatebbm($ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,$vunitprice);            
            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update TTB Retur Area '.$iarea.' No:'.$ittb);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ittb
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
