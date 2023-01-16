<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070301';

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
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'bulan'     => date('m'),
            'tahun'     => date('Y')

        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformawal', $data);

    }

    function proses(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $bulan      = $this->input->post('iperiodebl', TRUE);
        $tahun      = $this->input->post('iperiodeth', TRUE);
        $periode    = $tahun.$bulan;
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'periode'   => $periode,
            'isi'       => $this->mmaster->bacastock($periode)->result()
        );
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function approve(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $jml = $this->input->post('jml', TRUE);
        $ifkom= $this->input->post('ifakturkomersial', TRUE);
        $adatmp=true;
        if($ifkom!=''){
            $this->db->trans_begin();
			for($i=1;$i<=$jml;$i++){
				$cek=$this->input->post('chk'.$i, TRUE);
				if($cek=='on'){
                    $dbbk = $this->input->post('tanggal'.$i, TRUE);
                    if($dbbk!=''){
			            $tmp=explode("-",$dbbk);
			            $th=$tmp[0];
			            $bl=$tmp[1];
			            $hr=$tmp[2];
			            $dbbk=$th."-".$bl."-".$hr;
                        $thbl=$th.$bl;
                        $tbl=substr($th,2,2).$bl;

			        }
                    $tmp="FK-".$tbl."-";
                    $ibbk = $this->input->post('bbk'.$i, TRUE);
                    $ifakturkomersial	= $tmp.$this->input->post('ifakturkomersial', TRUE);
                    $nilai = $this->input->post('nilai'.$i, TRUE);
                    $nilai = str_replace(",","",$nilai);
                    $ada=$this->mmaster->cekfaktur($ifakturkomersial);
                    if(!$ada || $adatmp == false){
                        $this->mmaster->insertfkom($ifakturkomersial,$ibbk);
                      $adatmp=false;
                    }
                }
                
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ifkom);
                
                $data = array(
                    'sukses'    => true,
                    'kode'      => $tmp.$ifkom
                );
            }
        }
        
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
