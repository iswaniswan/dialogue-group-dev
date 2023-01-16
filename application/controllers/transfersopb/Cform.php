<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1021014';

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
        $istore  = $this->session->userdata('i_store');
        /*$istore  = 'PB';*/
        if($istore=='AA'){
            $istore='00';
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'file'      => directory_map("./import/so/".$istore, TRUE)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function load(){
        $file   = $this->input->post('namafile', TRUE);
        $tgl    = $this->input->post('dstockopname', TRUE);
        $istore = $this->session->userdata('i_store');
        /*$istore = 'PB';*/
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
            'jml'       => $hrow
        );
        $this->load->view($this->global['folder'].'/vfile', $data);
    }

    public function transfer(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $file   = $this->input->post('namafile', TRUE);
        $istorelocationbin = '00';
        $iarea  = substr($file,2,2);
        $istore = $this->session->userdata('i_store');
        /*$istore = 'PB';*/
        if ($istore == 'AA'){
            $istorelocation = '01';
        }else{
            $istorelocation = 'PB';
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
            $istockopname  = $this->mmaster->runningnumber($iarea,$thbl);
            $per           = '20'.substr($istockopname,3,4);
            $this->mmaster->insertheader($istockopname,$dstockopname,$istore,$istorelocation,$iarea);
            $inputFileName = './import/so/'.$istore.'/'.$file;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestRow();
            $aray = array();
            for ($n=2; $n<=$hrow; $n++){
                $eproductname = $this->mmaster->eproductname($spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue());
                $aray = array(
                    'i_stockopname'       => $istockopname,
                    'd_stockopname'       => $dstockopname,
                    'i_store'             => $istore,
                    'i_store_location'    => $istorelocation,
                    'i_store_locationbin' => $istorelocationbin,
                    'i_product'           => $spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue(),
                    'e_product_name'      => $eproductname,
                    'n_stockopname'       => $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue(),
                    'i_product_grade'     => 'A',
                    'i_product_motif'     => '00',  
                    'i_area'              => $iarea,
                    'e_mutasi_periode'    => $per,
                    'n_item_no'           => $n-1
                ); 

                if ($aray <> null){
                    $insert = $this->db->insert("tm_stockopname_item",$aray);
                }
                $iproduct         = $spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue();
                $nstockopname     = $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue();
                $iproductgrade    = $spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue();
                $iproductmotif    =  '00';
                $trans = $this->mmaster->lasttrans($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                if(isset($trans)){
                    foreach($trans as $itrans){
                        $q_aw =$itrans->n_quantity_awal;
                        $q_ak =$itrans->n_quantity_akhir;
                        $q_in =$itrans->n_quantity_in;
                        $q_out=$itrans->n_quantity_out;
                        break;
                    }
                }else{
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw =$itrans->n_quantity_stock;
                            $q_ak =$itrans->n_quantity_stock;
                            $q_in =0;
                            $q_out=0;
                            break;
                        }
                    }else{
                        $q_aw=0;
                        $q_ak=0;
                        $q_in=0;
                        $q_out=0;
                    }
                }
                $emutasiperiode='20'.substr($istockopname,3,4);
                if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                    $this->mmaster->updatemutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nstockopname,$emutasiperiode);
                }else{
                    $this->mmaster->insertmutasi4x($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$nstockopname,$emutasiperiode);
                }

            }
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Transfer SO Gudang PB Area '.$iarea.' No:'.$istockopname);
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
