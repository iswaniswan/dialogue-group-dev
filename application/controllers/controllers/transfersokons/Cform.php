<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021015';

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
        $istore    = $this->session->userdata('i_store');
        /*$istore    = 'PB';*/
        $ispg      = $this->session->userdata('username');
        /*$ispg      = 'S109';*/
        if($istore=='AA'){
            $istore='00';
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'file'      => directory_map("./import/so/".$istore, TRUE),
            'cust'      => $this->mmaster->bacacust($ispg)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function load(){
        $file      = $this->input->post('namafile', TRUE);
        $tgl       = $this->input->post('dstockopname', TRUE);
        $istore    = $this->session->userdata('i_store');
        /*$istore    = 'PB';*/
        $ispg      = $this->session->userdata('username');
        /*$ispg      = 'S109';*/
        if($istore=='AA'){
            $istore='00';
        }
        $inputFileName = './import/so/'.$istore.'/'.$file;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestRow();
        $aray = array();
        for ($n=2; $n<=$hrow; $n++){
            $aray[] = array(
                'KODEPROD'      => $spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue(),
                'NAMAPROD'      => $spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue(),
                'STOCKOPNAM'    => $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue(),
                'GRADE'         => $spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue(),
            );
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'file'      => directory_map("./import/so/".$istore, TRUE),
            'items'     => $aray,
            'filename'  => $file,
            'tgl'       => $tgl,
            'jml'       => $hrow,
            'iperiode'  => $this->mmaster->bacaperiode()
        );
        $this->load->view($this->global['folder'].'/vfile', $data);
    }

    public function transfer(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $file          = $this->input->post('namafile', TRUE);
        $iarea         = substr($file,2,2);
        $istore        = $this->session->userdata('i_store');
        /*$istore        = 'PB';*/
        $ispg          = $this->session->userdata('username');
        /*$ispg          = "S109";*/
        $icustomer     = $this->mmaster->getcustomer($ispg);
        $query         = $this->mmaster->getspg($icustomer);
        if($query->num_rows()>0){
            $tes  = $query->row();
            $ispg = $tes->i_spg;
        } 
        $dstockopname  = $this->input->post('tgl', TRUE);
        $thbl          = date('ym', strtotime($dstockopname));
        $dstockopname  = date('Y-m-d', strtotime($dstockopname));
        $inputFileName = './import/so/'.$istore.'/'.$file;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestRow();
        if ((isset($dstockopname) && $dstockopname != '')){
            $this->db->trans_begin();
            $istockopname  = $this->mmaster->runningnumber($icustomer,$thbl);
            $per           = '20'.substr($istockopname,3,4);
            $this->mmaster->insertheader($istockopname,$dstockopname,$icustomer,$iarea,$ispg);
            $inputFileName = './import/so/'.$istore.'/'.$file;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestRow();
            $aray = array();
            for ($n=2; $n<=$hrow; $n++){
                $iproductgrade = 'A';
                $cek_data = $this->mmaster->cek_data($istockopname,$icustomer,$spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue(),$iproductgrade);
                if($cek_data->num_rows() > 0){
                    echo "<script> swal('Barang ".$iproduct." Sudah Ada ! Tolong Cek Kembali SO Nya !') </script>";
                    die();
                }
                $eproductname = $this->mmaster->eproductname($spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue());
                $aray = array(
                    'i_sopb'              => $istockopname,
                    'd_sopb'              => $dstockopname,
                    'i_customer'          => $icustomer,
                    'i_product'           => $spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue(),
                    'i_product_grade'     => $iproductgrade,
                    'e_product_name'      => $eproductname,
                    'i_product_motif'     => '00',  
                    'n_sopb'              => $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue(),
                    'i_area'              => $iarea,
                    'e_mutasi_periode'    => $per,
                    'n_item_no'           => $n-1
                ); 

                if ($aray <> null){
                    $insert = $this->db->insert("tm_sopb_item",$aray);
                }
                $iproduct         = $spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue();
                $nstockopname     = $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue();
                $iproductgrade    = $spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue();
                $iproductmotif    =  '00';
                $trans = $this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$icustomer);
                if(isset($trans)){
                    foreach($trans as $itrans){
                        $q_aw  = $itrans->n_quantity_stock;
                        $q_ak  = $itrans->n_quantity_stock;
                        $q_in  = 0;
                        $q_out = 0;
                        break;
                    }
                }else{
                    $q_aw   = 0;
                    $q_ak   = 0;
                    $q_in   = 0;
                    $q_out  = 0;
                }
                $emutasiperiode='20'.substr($istockopname,3,4);
                if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$icustomer,$emutasiperiode)){
                    $this->mmaster->updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nstockopname,$emutasiperiode);
                }else{
                    $this->mmaster->insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$icustomer,$nstockopname,$emutasiperiode);           
                }

            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Transfer SO Konsinyasi Counter '.$iarea.' No:'.$istockopname);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $istockopname
                );
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
