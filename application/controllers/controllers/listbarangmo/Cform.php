<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070401';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
    	$igroup	      = $this->uri->segment(4); 
            
    	echo $this->mmaster->data($igroup);
    }
    
    public function view(){
    	$igroup         = $this->input->post('igroup');
        $egroupname     = $this->input->post('egroupname');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'igroup'     => $igroup,
            'egroupname' => $egroupname
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function bacagroup(){
        $iuser = $this->session->userdata('username');
        $filter = [];
        $query = $this->db->query("
                                SELECT
                                    b.*, 
                                    c.e_price_groupconame 
                                FROM 
                                    tr_spg a, 
                                    tr_customer_consigment b, 
                                    tr_price_groupco c
                                WHERE 
                                    a.i_spg ='$iuser' 
                                    AND b.i_customer = a.i_customer 
                                    AND b.i_price_groupco = c.i_price_groupco"
                                );
        if ($query->num_rows() > 0){
            $raw	= $query->row();
            $icust    = $raw->i_customer;
            $igroup   = $raw->i_price_groupco;
            $egroupname   = $raw->e_price_groupconame;
            $status   = 'group';
        }else{
            $status = 'all';
            $igroup = '';
            $icust  = '';
            $egroupname = '';
        }
            
		if($status=='all'){
            $query = $this->db->query("select * from tr_price_groupco",false);
        }else{
            $query = $this->db->query("select * from tr_price_groupco where i_price_groupco = '$igroup'",false);
        }
        foreach($query->result() as  $igroup){
                $filter[] = array(
                'id' => $igroup->i_price_groupco,  
                'text' => $igroup->i_price_groupco."-".$igroup->e_price_groupconame
            );
        }
        echo json_encode($filter);
    }

    function getgroup(){
        header("Content-Type: application/json", true);
        $igroup = $this->input->post('i_price_groupco');
        $this->db->select("*");
        $this->db->from("tr_price_groupco");
        $this->db->where("UPPER(i_price_groupco)", $igroup);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function export(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $igroup     = ($this->input->post('igroup',TRUE) != '' ? $this->input->post('igroup',TRUE) : $this->uri->segment(4));
        $egroupname = ($this->input->post('egroupname',TRUE) != '' ? $this->input->post('egroupname',TRUE) : $this->uri->segment(5));
        $query = $this->mmaster->bacabarang($igroup)->result();
        $spreadsheet = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $conditional3 = new Conditional();
        $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray( 
          [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] ); 

      $sharedStyle1->applyFromArray(
          [
          'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          ],
          'borders' => [
            'bottom' => ['borderStyle' => Border::BORDER_THIN],
            'right' => ['borderStyle' => Border::BORDER_THIN],
          ],
        ]
      );

        $sharedStyle2->applyFromArray(
        [
            'borders' => [
            'bottom' => ['borderStyle' => Border::BORDER_THIN],
            'right' => ['borderStyle' => Border::BORDER_THIN],
          ],
        ]
      );

        $sharedStyle3->applyFromArray(
            [
                'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ]
        );
        $spreadsheet->getDefaultStyle()
        ->getFont()
        ->setName('Calibri')
        ->setSize(9);
        foreach(range('A','F') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          $spreadsheet->getActiveSheet()->mergeCells("A1:F1");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Daftar Master Barang MO Group '.$egroupname)
                      ->setCellValue('A3', 'KODE BARANG')
                      ->setCellValue('B3', 'NAMA BARANG')
                      ->setCellValue('C3', 'KODE HARGA')
                      ->setCellValue('D3', 'GROUP')
                      ->setCellValue('E3', 'GRADE')
                      ->setCellValue('F3', 'HARGA');

          $kolom = 4;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $row->i_product)
                        ->setCellValue('B' . $kolom, $row->e_product_name)
                        ->setCellValue('C' . $kolom, $row->i_price_group)
                        ->setCellValue('D' . $kolom, $row->e_price_groupconame)
                        ->setCellValue('E' . $kolom, 'A')
                        ->setCellValue('F' . $kolom, $row->v_product_retail);
            $spreadsheet->getActiveSheet()
                        ->getStyle('F'.$kolom)
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "Master_Barang_MO".$egroupname.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

}

/* End of file Cform.php */
