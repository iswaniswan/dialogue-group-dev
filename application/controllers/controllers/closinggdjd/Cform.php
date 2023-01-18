<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20110';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->company = $this->session->id_company;
        
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }  

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

    public function closing()
    {
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian = $this->input->post('ibagian', TRUE);
        if (strlen($ibagian) <= 0) {
            $ibagian = $this->uri->segment(4);
        }
        $tahun = $this->input->post('tahun', TRUE);
        $bulan = $this->input->post('bulan', TRUE);
        if (strlen($tahun) > 0 && strlen($bulan) > 0) {
            $da = new DateTime($tahun.'-'.$bulan.'-01');
        }else{
            if (strlen($this->uri->segment(5)) <= 0) {
                $da = new DateTime(date('Y-m-01'));
                $tahun = date('Y');
                $bulan = date('m');
            }else{
                $da = new DateTime($this->uri->segment(5));
                $tahun = date('Y', strtotime($this->uri->segment(5)));
                $bulan = date('m', strtotime($this->uri->segment(5)));
            }
        }
        $id_company = $this->session->userdata('id_company');
        $dt = new DateTime(date('Y-m-01'));
        $awal = $da->format('Ym');
        $akhir = $dt->format('Ym');

        $date       = date("Y-m-01");
        $timeStart  = strtotime($tahun.'-'.$bulan.'-01');
        $timeEnd    = strtotime("$date");
        /*Menambah bulan ini + semua bulan pada tahun sebelumnya*/
        $numBulan   = 1 + (date("Y",$timeEnd)-date("Y",$timeStart))*12;
        /*menghitung selisih bulan*/
        $numBulan   += date("m",$timeEnd)-date("m",$timeStart);
        
        $number = $akhir-$awal;

        for ($i=0; $i < $numBulan; $i++) {
            $x= 1;
            $pawal = $awal;
            $interval = new DateInterval('P'.$x.'M');
            $da->add($interval);
            $pakhir = $da->format( 'Ym' );
            if ($ibagian=='all') {
                $query = $this->mmaster->bagian();
                if ($query->num_rows()>0) {
                    foreach ($query->result() as $key) {
                        $this->mmaster->closingpembelianbarangjadi($id_company, $key->i_bagian, $pawal, $pakhir);
                    }
                }
            }else{
                $this->mmaster->closingpembelianbarangjadi($id_company, $ibagian, $pawal, $pakhir);
            }                
            $x++;
            $awal = $pakhir;
        }

        if ($numBulan < 0) {
            echo json_encode(0);
        } else {
            echo json_encode(1);
        }
    }
}
/* End of file Cform.php */
