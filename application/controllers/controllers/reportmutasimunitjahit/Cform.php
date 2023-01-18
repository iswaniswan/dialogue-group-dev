<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2080202';

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
        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_ago = new DateTime();
        $one_month_ago->sub($one_month);

        // Output the microseconds.

        $awal  = $d->format('01-m-Y');
        $blalu = $one_month_ago->format('m');
        $tlalu = $one_month_ago->format('Y');

        $akhir = $d->format('d-m-Y');
        $bnow  = $d->format('m');
        $tnow  = $d->format('Y');

        //echo $blalu."'".$tlalu."'".$awal."'".$akhir."'".$bnow."'".$tnow;

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            // 'blalu'         => $blalu,
            // 'tlalu'         => $tlalu,
            'dfrom'         => $awal,
            'dto'           => $akhir,
            'bnow'          => $bnow,
            'tnow'          => $tnow,
            'partner'       => $this->mmaster->getpartner(),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
       // $pbulan  = $this->input->post('pbulan');
       // $ptahun  = $this->input->post('ptahun');

       //  if($pbulan==''){
       //      $pbulan=$this->uri->segment(4);
       //  }
       //  if($ptahun==''){
       //      $ptahun=$this->uri->segment(5);
       //  }
       //  $datenow = "01-".$pbulan."-".$ptahun;
       //  $d = new DateTime($datenow);
       //  $one_month = new DateInterval('P1M');
       //  $one_month_ago = new DateTime($datenow);
       //  $one_month_ago->sub($one_month);

       //  $awal  = $d->format('01-m-Y');
       //  $akhir = $d->format('t-m-Y');

       //  $blalu = $one_month_ago->format('m');
       //  $tlalu = $one_month_ago->format('Y');

       //  $bnow  = $d->format('m');
       //  $tnow  = $d->format('Y');


        $dfrom=$this->uri->segment(4);
        $dto=$this->uri->segment(5);
        $partner=$this->uri->segment(6);
        
        // var_dump($dfrom, $dto, $partner);
        // die();

        $from = explode("-", $dfrom);
        $tgl= $from[0];
        $bln= $from[1];
        $thn= $from[2];

        $to = explode("-", $dto);
        $tgl2= $to[0];
        $bln2= $to[1];
        $thn2= $to[2];


        //$datenow = "01-".$pbulan."-".$ptahun;
        $d = new DateTime($dto);
        $one_month = new DateInterval('P1M');
        $one_month_ago = new DateTime($dfrom);
        $one_month_ago->sub($one_month);

        // $awal  = $d->format('01-m-Y');
        // $akhir = $d->format('d-m-Y');
        $blalu = $one_month_ago->format('m');
        $tlalu = $one_month_ago->format('Y');
        $bnow  = $d->format('m');
        $tnow  = $d->format('Y');


        $one_day = new DateInterval('P1D');
        $one_day_ago = new DateTime($dfrom);
        $one_day_ago->sub($one_day);

        $djangka = new DateTime($dfrom);
        $dawal  = $djangka->format('01-m-Y');
        $dlalu_j = $one_day_ago->format('d');
        $blalu_j = $one_day_ago->format('m');
        $tlalu_j = $one_day_ago->format('Y');

        $dakhir = $dlalu_j."-".$blalu_j."-".$tlalu_j;
        $partner2;
        if ($dawal == $dfrom) {
            $partner2 = "xx";
        } else {
            $partner2 = $partner;
        }

        
        //var_dump($blalu."'".$tlalu."'".$dfrom."'".$dto."'".$bnow."'".$tnow."'".$partner."'".$dawal."'".$dakhir."'".$partner2);
        //die();
            
        echo $this->mmaster->data($blalu,$tlalu,$dfrom,$dto,$bnow,$tnow,$partner, $dawal, $dakhir, $partner2);
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

        $this->db->select("* from f_mutasi_qcset($bln_query, $thn_query, '$dfrom','$dto', $bln1, $thn1)",false);
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