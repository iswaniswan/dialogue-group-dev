<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040113';

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
        
        $dfrom = $this->input->post('dfrom');
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            // if ($dfrom == '') {
            //     $dto    = date('d-m-Y');
            //     $dfrom  = date('d-m-Y', strtotime('-1 month', strtotime($dto)));
            // }
        }
        $dto = $this->input->post('dto');
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            // if ($dto == '') {
            //     $dto = date('d-m-Y');
            // }
        }
        $status = $this->input->post('status');
        if ($status == '') {
            $status = $this->uri->segment(6);
        }

        /* AKTIFKAN KALAU MAU PERIODE */
        // $bulan = $this->input->post('bulan');
        // if ($bulan == '') {
        //     $bulan = $this->uri->segment(4);
        // }

        // $tahun = $this->input->post('tahun');
        // if ($tahun == '') {
        //     $tahun = $this->uri->segment(5);
        // } 
        /* ******************************* */

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            #'bulan'     => $bulan,
            #'tahun'     => $tahun,
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'status'    => $status,
        );
        
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    // public function view()
    // {
    //     $icustomer  = $this->input->post('icustomer', true);
    //     $dfrom      = $this->input->post('dfrom', true);
    //     $dto        = $this->input->post('dto', true);
        
    //     $data = array(
    //         'folder'    => $this->global['folder'],
    //         'title'     => $this->global['title'],
    //         'icustomer' => $icustomer,
    //         'dfrom'     => $dfrom,
    //         'dto'       => $dto,
    //     );
    //     $this->Logger->write('Membuka Menu ' . $this->global['title']) . ' Tanggal : ' . $dfrom . ' S/d : ' . $dto;
    //     $this->load->view($this->global['folder'] . '/vformlist', $data);
    // }

    public function data()
    {
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        $status     = $this->input->post('status');
        
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($status==''){
            $status=$this->uri->segment(6);
        }
        
        echo $this->mmaster->data($dfrom,$dto,$status,$this->global['folder'],$this->i_menu);
    }

    public function view() {
        // $data = check_role($this->i_menu, 3);
        // if (!$data) {
        //     redirect(base_url(), 'refresh');
        // }
        
        $ipembayaran    = $this->uri->segment(4);
        $partner        = $this->uri->segment(5);
        $dfrom          = $this->uri->segment(6);
        $dto            = $this->uri->segment(7);
        $status         = $this->uri->segment(8);
        
        // echo "Masuk";
        // die;
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'partner'       => $this->mmaster->partner(),
            'jenis'         => $this->mmaster->jenis(),
            'data'          => $this->mmaster->baca($ipembayaran,$partner),
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'status'        => $status,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function getdetailedit(){
        header("Content-Type: application/json", true);
        $partner  = $this->input->post('partner', FALSE);
        $jenis    = $this->input->post('jenis', FALSE);
        $ipembayaran   = $this->input->post('i_pembayaran', FALSE);

 
        $query  = array(
            'detail' => $this->mmaster->getdetailedit($partner, $jenis, $ipembayaran)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }
}
/* End of file Cform.php */
