<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107011801';
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
            'iarea'     => $this->mmaster->bacaarea($idcompany)
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
        $iarea     = $this->input->post('iarea', TRUE);
        if ($iarea =='') {
            $iarea = $this->uri->segment(6);
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
        //$idcompany  = $this->session->userdata('id_company');
        //$username   = $this->session->userdata('username');
        //$iperiode   = $this->mmaster->cekperiode();
        //$status     = $this->mmaster->cekstatus($idcompany,$username);
        echo $this->mmaster->data($dfrom, $dto, $iarea, $this->global['folder']);
    }

    public function edit(){
        $ikum       = $this->uri->segment(4);
        $nkumyear   = $this->uri->segment(5);
        $iarea      = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        $ikum       = str_replace('|','/',$this->uri->segment(4));
        $ikum       = str_replace('%20',' ',$ikum);
        $idcompany  = $this->session->userdata('id_company');
        $username   = $this->session->userdata('username');
        $pst        = $this->mmaster->bacaareauser($idcompany,$username);
    
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' =>'List '.$this->global['title'],
            'ikum'       => $ikum,
            'iarea'      => $iarea,
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'nkumyear'   => $nkumyear,
            'pst'        => $pst,
            //'cust'       => $this->mmaster->bacacustomer($iarea),
            'area'       => $this->mmaster->bacaarea($idcompany),
            'isi'        => $this->mmaster->baca($iarea,$ikum,$nkumyear)
        );
       
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ikum 	= $this->input->post('ikum', TRUE);
		$dkum	= $this->input->post('dkum', TRUE);
		if($dkum!=''){
			$tmp=explode("-",$dkum);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$dkum=$th."-".$bl."-".$hr;
			$tahun=$th;
		}
		$iareaasal	  = $this->input->post('iareaasal', TRUE);
		$icustomer		= $this->input->post('icustomer', TRUE);
		$icustomergroupar	= $this->input->post('icustomergroupar', TRUE);
		$ecustomername		= $this->input->post('ecustomername', TRUE);
        if (($ikum != '') && ($iareaasal!='') && ($tahun!='')){
            $this->db->trans_begin();
            $this->mmaster->update($ikum,$tahun,$icustomer,$icustomergroupar,$iareaasal);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update KU Masuk (Cabang):'.$iareaasal.' No:'.$ikum);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ikum
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
