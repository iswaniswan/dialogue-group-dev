<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090211';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->company          = $this->session->id_company;
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
            'bagian'        => $this->mmaster->bagian()->result(),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }
    
    public function closing()
    {
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE);
        $da         = new DateTime($this->input->post('tahun', TRUE).'-'.$this->input->post('bulan', TRUE).'-01');
        $dt         = new DateTime(date('Y-m-01'));
        $awal       = $da->format('Ym'); //202101
        $akhir      = $dt->format('Ym'); //202103

        $date       = date("Y-m-01");
        $timeStart  = strtotime($this->input->post('tahun', TRUE).'-'.$this->input->post('bulan', TRUE).'-01');
        $timeEnd    = strtotime("$date");
        /*Menambah bulan ini + semua bulan pada tahun sebelumnya*/
        $numBulan   = 1 + (date("Y",$timeEnd)-date("Y",$timeStart))*12;
        /*menghitung selisih bulan*/
        $numBulan   += date("m",$timeEnd)-date("m",$timeStart);

        for ($i = 0; $i < $numBulan; $i++) {
            $x  = 1;
            $pawal      = $awal;
            $interval   = new DateInterval('P'.$x.'M');
            $da->add($interval);
            $pakhir     = $da->format( 'Ym' );
            $this->mmaster->closingsaldo($ibagian,$pawal,$pakhir);
            $x++;
            $awal = $pakhir;
        }
        if ($numBulan < 0) {
            echo json_encode(0);
        } else {
            $this->Logger->write($this->global['title'].' Periode : '.$this->input->post('tahun', TRUE).$this->input->post('bulan', TRUE).' Bagian : '.$ibagian);
            echo json_encode(1);
        }
    }
}
/* End of file Cform.php */
