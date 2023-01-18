<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10602';

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
        $query=$this->db->query("select i_periode from tm_periode ",false);
        $hasil = $query->row();
        $iperiode = $hasil->i_periode;
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'ijurnal'   => '',
            'isi'       => '',
            'detail'    => '',
            'jmlitem'   => '',
            'periode'   => $iperiode
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function dataarea(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $company = $this->session->userdata('id_company');
            $username = $this->session->userdata('username');
            $this->db->select(" * from tr_area where (upper(i_area) like '%$cari%' or upper(e_area_name) like '%$cari%') and (i_area in ( select i_area from tm_user_area where i_user='$username') )",false);
            $data = $this->db->get();
            foreach($data->result() as  $area){
                    $filter[] = array(
                    'id' => $area->i_area,  
                    'text' => $area->i_area.'-'.$area->e_area_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function datacoa(){
        $filter = [];
        $icustomer = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("select * from tr_coa where upper(i_coa) like '%$cari%' or upper(e_coa_name) like '%$cari%'",false);
            foreach($query->result() as  $coa){
                    $filter[] = array(
                    'id' => $coa->i_coa,  
                    'text' => $coa->i_coa.'-'.$coa->e_coa_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getcoa(){
        header("Content-Type: application/json", true);
        $icoa = $this->input->post('i_coa');
        $data=$this->db->query("select * from tr_coa where i_coa='$icoa'",false);
        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $djurnal 	= $this->input->post('djurnal', TRUE);
		if($djurnal!=''){
			$tmp=explode("-",$djurnal);
			$th=$tmp[2];
			$bl=$tmp[1];
			$hr=$tmp[0];
			$djurnal=$th."-".$bl."-".$hr;
            $thbl=substr($th,2,2).$bl;
            $iperiode=$th.$bl;
		}
        $ijurnal    = $this->input->post('ijurnal', TRUE);
		$iarea		= $this->input->post('iarea', TRUE);
		$vdebet		= $this->input->post('vdebet', TRUE);
		$vdebet		= str_replace(',','',$vdebet);
		$vkredit	= $this->input->post('vkredit', TRUE);
		$vkredit	= str_replace(',','',$vkredit);
		$jml		= $this->input->post('jml', TRUE);
		$edescription	= $this->input->post('edescription', TRUE);
		$fposting	='t';
		$fclose		='f';
        if(($iarea!='') && ($ijurnal!='') && ($djurnal!='') && ($vdebet!='0') && ($vkredit!='0')){
            if(($vdebet==$vkredit)){
                $this->db->trans_begin();
                for($i=1;$i<=$jml;$i++){
                    $icoa		= $this->input->post('icoa'.$i, TRUE);
					$ecoaname	= $this->input->post('ecoaname'.$i, TRUE);
					$vmdebet	= $this->input->post('vdebet'.$i, TRUE);
					$vmdebet	= str_replace(',','',$vmdebet);
					$vmkredit	= $this->input->post('vkredit'.$i, TRUE);
                    $vmkredit	= str_replace(',','',$vmkredit);
                    if($vmdebet>0)
						$fdebet='t';
					else
						$fdebet='f';
						$irefference=null;
						$drefference=null;
					$this->mmaster->insertdetail($ijurnal,$icoa,$ecoaname,$fdebet,$vmdebet,$vmkredit,$iarea,$irefference,$drefference);
                }
                if($vmkredit=='0'){
					$this->mmaster->inserttransitemdebet($icoa,$ijurnal,$ecoaname,'t','t',$iarea,$edescription,$vmdebet,$djurnal);
					$this->mmaster->updatesaldodebet($icoa,$iperiode,$vmdebet);
					$this->mmaster->insertgldebet($icoa,$ijurnal,$ecoaname,'t',$iarea,$vmdebet,$djurnal,$edescription);
				}elseif($vmdebet=='0'){
					$this->mmaster->inserttransitemkredit($icoa,$ijurnal,$ecoaname,'f','t',$iarea,$edescription,$vmkredit,$djurnal);
					$this->mmaster->updatesaldokredit($icoa,$iperiode,$vmkredit);
					$this->mmaster->insertglkredit($icoa,$ijurnal,$ecoaname,'f',$iarea,$vmkredit,$djurnal,$edescription);
                }
                $this->mmaster->insertheader($ijurnal,$iarea,$djurnal,$edescription,$vdebet,$vkredit,$fposting,$fclose);
                $this->mmaster->inserttransheader($ijurnal,$iarea,$edescription,$fclose,$djurnal);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Input Jurnal Umum :'.$this->global['title'].' Kode : '.$ijurnal);
                    $data = array(
                        'sukses'    => true,
                        'kode'      => 'Input Jurnal Umum '.$ijurnal
                    );
                }
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
