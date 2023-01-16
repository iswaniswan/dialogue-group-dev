<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090408';

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
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'], 
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
        echo $this->mmaster->data($this->i_menu);
    }
   
    function getqcset(){
        header("Content-Type: application/json", true);
        $dfrom        = $this->input->post('dfrom');
        $dto          = $this->input->post('dto');

        $pisah1 = explode("-", $dfrom);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];
        $iperiode = $thn1.$bln1;
        if($bln1 == 1) {
          $bln_query = 12;
          $thn_query = $thn1-1;
        }else {
          $bln_query = $bln1-1;
          $thn_query = $thn1;
          if ($bln_query < 10){
            $bln_query = "0".$bln_query;
          }
        }
        $pisah1 = explode("-", $dto);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];

        $this->db->select("a* from f_mutasi_qcset($bln_query, $thn_query, '$dfrom','$dto', $bln1, $thn1)",false);
        $data = $this->db->get();

        $query   = $this->mmaster->getQCset($dfrom, $dto);

        $dataa = array(
            'data'    => $data->result_array(),
            'jmlitem' => $query->num_rows(),
            'qcset'   => $this->mmaster->getQCset($dfrom, $dto)->result_array(),
        );
        echo json_encode($dataa);
    }
}
/* End of file Cform.php */