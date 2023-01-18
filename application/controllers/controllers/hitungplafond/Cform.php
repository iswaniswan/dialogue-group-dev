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
    public $i_menu = '10515';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'bulan'     => date('m'),
            'tahun'     => date('Y')

        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformawal', $data);

    }

    function data(){
        $periode    = $this->uri->segment('4');
        $count      = $this->mmaster->total($periode);
        $total      = $count->num_rows();
            
    	echo $this->mmaster->data($periode,$this->global['folder'], $total);
    }

    function proses(){
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');
        $bulanawal      = $this->input->post('iperiodeblawal', TRUE);
        $tahunawal      = $this->input->post('iperiodethawal', TRUE);
        $bulanakhir     = $this->input->post('iperiodeblakhir', TRUE);
        $tahunakhir     = $this->input->post('iperiodethakhir', TRUE);
        $iperiodeawal    = $tahunawal.$bulanawal;
        $iperiodeakhir   = $tahunakhir.$bulanakhir;
        if($iperiodeawal==''){
            $dfrom=$this->uri->segment(4);
        }
        if($iperiodeakhir==''){
            $dto=$this->uri->segment(5);
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'iperiodeawal'   => $iperiodeawal,
            'iperiodeakhir'  => $iperiodeakhir,
            'isi'           => $this->mmaster->bacaperiode($iperiodeawal,$iperiodeakhir)
        );
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function export(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iperiodeawal     = ($this->input->post('iperiodeawal',TRUE) != '' ? $this->input->post('iperiodeawal',TRUE) : $this->uri->segment(4));
        $iperiodeakhir   = ($this->input->post('iperiodeakhir',TRUE) != '' ? $this->input->post('iperiodeakhir',TRUE) : $this->uri->segment(5));
        $a=substr($iperiodeawal,2,2);
        $b=substr($iperiodeawal,4,2);
        $a=substr($iperiodeakhir,2,2);
	    $b=substr($iperiodeakhir,4,2);
        $query = $this->mmaster->bacano($iperiodeawal,$iperiodeakhir)->result();
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
        foreach(range('A','P') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'e_area_name')
                      ->setCellValue('B1', 'i_customer_groupbayar')
                      ->setCellValue('C1', 'e_customer_name')
                      ->setCellValue('D1', 'e_periode_awal')
                      ->setCellValue('E1', 'e_periode_akhir')
                      ->setCellValue('F1', 'i_kategori')
                      ->setCellValue('G1', 'e_kategori')
                      ->setCellValue('H1', 'n_rata_telat')
                      ->setCellValue('I1', 'i_index')
                      ->setCellValue('J1', 'v_total_penjualan')
                      ->setCellValue('K1', 'v_max_penjualan')
                      ->setCellValue('L1', 'v_rata_penjualan')
                      ->setCellValue('M1', 'v_plafond')
                      ->setCellValue('N1', 'v_plafond_before')
                      ->setCellValue('O1', 'v_plafond_acc')
                      ->setCellValue('P1', 'TOP');

          $kolom = 2;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $row->e_area_name)
                        ->setCellValue('B' . $kolom, $row->i_customer_groupbayar)
                        ->setCellValue('C' . $kolom, $row->e_customer_name)
                        ->setCellValue('D' . $kolom, $row->e_periode_awal)
                        ->setCellValue('E' . $kolom, $row->e_periode_akhir)
                        ->setCellValue('F' . $kolom, $row->i_kategori)
                        ->setCellValue('G' . $kolom, $row->e_kategori)
                        ->setCellValue('H' . $kolom, $row->n_rata_telat)
                        ->setCellValue('I' . $kolom, $row->i_index)
                        ->setCellValue('J' . $kolom, $row->v_total_penjualan)
                        ->setCellValue('K' . $kolom, $row->v_max_penjualan)
                        ->setCellValue('L' . $kolom, $row->v_rata_penjualan)
                        ->setCellValue('M' . $kolom, $row->v_plafond)
                        ->setCellValue('N' . $kolom, $row->v_plafond_before)
                        ->setCellValue('O' . $kolom, $row->v_plafond_acc)
                        ->setCellValue('P' . $kolom, $row->n_customer_toplength);
            $kolom++;
            $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "hitungplafond".$iperiodeawal."_".$iperiodeakhir.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
/* End of file Cform.php */
