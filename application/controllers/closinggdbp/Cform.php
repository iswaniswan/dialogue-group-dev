<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050419';

    public function __construct()
    {
        parent::__construct();

        /*----------  Cek Session Di Helper  ----------*/
        cek_session();

        /*----------  Cek Menu Di Helper  ----------*/
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        /*----------  Deklarasi Session, Folder dan Nama Menu  ----------*/        
        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->level            = $this->session->i_level;
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/        
        $this->load->model($this->global['folder'].'/mmaster');
    }

    /*----------  Default Controllers start ----------*/
    
    public function index()
    {
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'bagian'     => $this->mmaster->bagian()->result(),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    /*----------  Proses Closing Saldo Akhir ke Saldo Awal  ----------*/
    
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
