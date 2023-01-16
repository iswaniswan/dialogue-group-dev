<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20701';

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
        require ('php/fungsi.php');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_customer = $this->uri->segment('4');
        $i_periode  = $this->uri->segment('5');
        $i_product  = $this->uri->segment('6');
        $i_color    = $this->uri->segment('7');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($i_customer, $i_periode, $i_product, $i_color)->row(),
            // 'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function tambah(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'folderx'   => "uploadforecast",
            'title'     => $this->global['title'],
            'distributor'  => $this->mmaster->bacadistributor()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);

    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
            $periode 			= $this->input->post('periode', TRUE);
            $icustomer 			= $this->input->post('icustomer', TRUE);
            $icolor             = $this->input->post('icolor', TRUE);
            $iproduct           = $this->input->post('iproduct', TRUE);
            $qty           = $this->input->post('qty', TRUE);
            $query 	            = $this->db->query("SELECT current_timestamp as c");
	   		$row   	            = $query->row();
	    	$now	            = $row->c;
            $dpp                = $this->input->post("dpp",true);
            $this->db->trans_begin(); 
	    	$this->mmaster->updateheader($periode, $icustomer, $icolor, $iproduct, $qty);
        // if ($ipp != '' && $ikodemaster != ''){
        //     $cekada = $this->mmaster->cek_dataheader($ipp);
            // echo $jml;
            //     die;
            // if($cekada->num_rows() > 0){
        
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproduct
                );
            }
        // }else{
        //     $data = array(
        //         'sukses' => false,
        //     );
        // }
        $this->load->view('pesan', $data);  
    }


    public function load(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        // $istore = $this->session->userdata('i_store');
        // if($istore=='AA'){
        //     $istore='00';
        // }
        $customer   = $this->input->post('customer', TRUE);
        $tahun    = $this->input->post('tahun', TRUE);
        $bulan    = $this->input->post('bulan', TRUE);
        $filename = $customer."-".$tahun.$bulan.".xls";
        //var_dump($customer,$bulan,$tahun);
        $config = array(
            'upload_path'   => "./import/forecast/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );
        $this->load->library('upload',$config);
        if($this->upload->do_upload("userfile")){
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File Forecast Periode : '.$tahun.$bulan.', Customer : '.$customer);
            //echo 'berhasil';
          
            $param =  array(
                'tahun' => $tahun, 
                'bulan' => $bulan, 
                'customer' => $customer,
                'status' => 'berhasil'
            );
            echo json_encode($param);
        }else{
            $param =  array(
                'status' => 'berhasil'
            );
            echo json_encode($param);
            //echo 'gagal';
        }
    }

    public function loadview(){
        
        $tahun    = $this->uri->segment(4);
        $bulan    = $this->uri->segment(5);
        $customer   = $this->uri->segment(6);
        $filename = $customer."-".$tahun.$bulan.".xls";
        $e_bulan =mbulan($bulan);

        //var_dump($filename);
        $inputFileName = './import/forecast/'.$filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('B');
        $aray = array();
        for ($n=2; $n<=$hrow; $n++){
            $aray[] = array(
                'NO'      => $spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue(),
                'KODEPROD'      => $spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue(),
                'COLOR'      => $spreadsheet->getActiveSheet()->getCell('C'.$n)->getValue(),
                'NAMAPROD'    => $spreadsheet->getActiveSheet()->getCell('D'.$n)->getCalculatedValue(),
                'JUMLAH'         => $spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue(),
            );
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'file'      => directory_map("./import/forecast/", TRUE),
            'items'     => $aray,
            'filename'  => $filename,
            'customer'       => $this->mmaster->bacadistributorbyid($customer),
            'tahun'       => $tahun,
            'bulan'       => $bulan,
            'jml'       => $hrow,
            'ebulan'       => $e_bulan
        );
        $this->load->view($this->global['folder'].'/vfile', $data);
    }

    public function transfer(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $customer   = $this->input->post('customer', TRUE);
        $periode    = $this->input->post('periode', TRUE);
        $filename = $customer."-".$periode.".xls";
      
        
        if ((isset($customer) && $customer != '') && (isset($periode) && $periode != '')){
            $this->db->trans_begin();
            // $istockopname  = $this->mmaster->runningnumber($iarea,$thbl);
            // $this->mmaster->deleteforecast($customer,$periode);
            
            $inputFileName = './import/forecast/'.$filename;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestDataRow('B');
            $aray = array();
            for ($n=2; $n<=$hrow; $n++){
                $iprod = $spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue();
                $icolor = $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue();
                $query2 = $this->mmaster->cekforecast($customer,$periode,$iprod, $icolor);
                if ($query2 -> num_rows() > 0){
                    // $iproduct   = $spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue();
                    // $icolor     = $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue();
                    // $qty        = $spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue();
                    $aray = array(
                        'i_customer'    => $customer,
                        'periode'       => $periode,
                        'i_product'     => $spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue(),
                        'i_color'       => $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue(),
                        'n_quantity'    => $spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue(),
                        'n_item_no'     => $n-1
                    ); 
                    // alert("Product Sudah Ada!!!");
                    $this->mmaster->updatetransfer($periode, $customer, $icolor, $iprod);
                }else{
                //$eproductname = $this->mmaster->eproductname($spreadsheet->getActiveSheet()->getCell('A'.$n)->getCalculatedValue());
                    $aray = array(
                        'i_customer'    => $customer,
                        'periode'       => $periode,
                        'i_product'     => $spreadsheet->getActiveSheet()->getCell('B'.$n)->getCalculatedValue(),
                        'i_color'       => $spreadsheet->getActiveSheet()->getCell('C'.$n)->getCalculatedValue(),
                        'n_quantity'    => $spreadsheet->getActiveSheet()->getCell('E'.$n)->getCalculatedValue(),
                        'n_item_no'     => $n-1
                    ); 

                    if ($aray <> null){
                        $this->db->insert("tm_forecast",$aray);
                    }else{

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
                $this->Logger->write('Transfer Forecast Periode '.$periode.' Customer :'.$customer);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $customer." - ".$periode
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
