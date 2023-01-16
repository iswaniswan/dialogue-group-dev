<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10518';

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
        $this->load->helper('file');
        $this->load->helper('directory');
        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'file'      => directory_map("./import/plafond/", TRUE)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function load(){
        $file   = $this->input->post('namafile', TRUE);
        $inputFileName = './import/plafond/'.$file;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestRow();
        $aray = array();
        for ($n=2; $n<=$hrow; $n++){
            $aray[] = array(
                'e_area_name'           => $spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue(),
                'i_customer_groupbayar' => $spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue(),
                'e_customer_name'       => $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue(),
                'e_periode_awal'        => $spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue(),
                'e_periode_akhir'       => $spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue(),
                'i_kategori'            => $spreadsheet->getActiveSheet()->getCell('F'.$n)->getCalculatedValue(),
                'e_kategori'            => $spreadsheet->getActiveSheet()->getCell('G'.$n)->getCalculatedValue(),
                'n_rata_telat'          => $spreadsheet->getActiveSheet()->getCell('H'.$n)->getCalculatedValue(),
                'i_index'               => $spreadsheet->getActiveSheet()->getCell('I'.$n)->getCalculatedValue(),
                'v_total_penjualan'     => $spreadsheet->getActiveSheet()->getCell('J'.$n)->getCalculatedValue(),
                'v_max_penjualan'       => $spreadsheet->getActiveSheet()->getCell('K'.$n)->getCalculatedValue(),
                'v_rata_penjualan'      => $spreadsheet->getActiveSheet()->getCell('L'.$n)->getCalculatedValue(),
                'v_plafond'             => $spreadsheet->getActiveSheet()->getCell('M'.$n)->getCalculatedValue(),
                'v_plafond_before'      => $spreadsheet->getActiveSheet()->getCell('N'.$n)->getCalculatedValue(),
                'v_plafond_acc'         => $spreadsheet->getActiveSheet()->getCell('O'.$n)->getCalculatedValue(),
                'TOP'                   => $spreadsheet->getActiveSheet()->getCell('P'.$n)->getCalculatedValue(),
            );
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'file'      => directory_map("./import/plafond/", TRUE),
            'items'     => $aray,
            'filename'  => $file,
            'jml'       => $hrow
        );
        $this->load->view($this->global['folder'].'/vfile', $data);
    }

    public function transfer(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $jml          = $this->input->post('jml', TRUE);
	    $iperiodeawal  = $this->input->post('iperiodeawal',TRUE);
	    $iperiodeakhir = $this->input->post('iperiodeakhir',TRUE);
        $file   = $this->input->post('namafile', TRUE);
        if($jml==''){
            $jml	= $this->uri->segment(4);
        }
        if($iperiodeawal==''){
            $iperiodeawal	= $this->uri->segment(5);
        }
        if($iperiodeakhir==''){
            $iperiodeakhir	= $this->uri->segment(6);
        }
        if($file==''){
            $file	= $this->uri->segment(7);
        }
        $per = substr($file,4,6);
        $inputFileName = './import/plafond/'.$file;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestRow();
        if ((isset($file) && $file != '')){
            $this->db->trans_begin();
            $inputFileName = './import/plafond/'.$file;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestRow();
            $aray = array();
            for ($n=2; $n<=$hrow; $n++){
               // $eproductname = $this->mmaster->eproductname($spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue());
                $icust               = $spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue();
                $iperiodeawal        = $spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue();
                $iperiodeakhir       = $spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue();
                $plafonacc           = $spreadsheet->getActiveSheet()->getCell('O'.$n)->getCalculatedValue();

                $this->mmaster->updateplafond($icust,$iperiodeawal,$iperiodeakhir,$plafonacc);
                $this->mmaster->updategroupbayar($icust,$plafonacc);
                $query = $this->db->query("select * from tr_customer_groupbayar where i_customer_groupbayar = '$icust'");

                if ($query->num_rows() > 0){
                    $raw	= $query->row();
                    $icust_bayar    = $raw->i_customer_groupbayar;
                    $icust          = $raw->i_customer;
                    $this->db->query("update tr_customer_groupar set v_flapond ='$plafonacc' where i_customer='$icust'");
                }
            }
        }
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false
            );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Transfer Plafond Periode : '.$iperiodeawal.' sd '.$iperiodeakhir);
            $data = array(
                'sukses'    => true,
                'kode'      => 'Transfer Plafond Periode : '.$iperiodeawal.' sd '.$iperiodeakhir
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
