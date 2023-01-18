<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1060107';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");
        
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
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);

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

    public function pindah(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $iperiode1	= $this->input->post('iperiode1', TRUE);
        $iarea	= $this->input->post('iarea', TRUE);
        $this->mmaster->pindah($iperiode1,$iarea);
        if ($this->db->trans_status() === FALSE){
          $this->db->trans_rollback();
        }else{
          $this->db->trans_commit();
          $this->Logger->write('Closing Kas Kecil Periode: '.$this->global['title'].' Kode : '.$iperiode1);
          
          $data = array(
              'sukses'    => true,
              'kode'      => "Sukses Closing Kas Kecil Periode : ".$iperiode1
          );
        }
        $this->load->view('pesan', $data);  
    }
  }
/* End of file Cform.php */
