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
    public $i_menu = '2040109';

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
            'tahun'     => date('Y'),
            'supplier'  => $this->mmaster->bacasupplier()

        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
      $dfrom     = $this->input->post('dfrom');
      $dto       = $this->input->post('dto');
      $isupplier = $this->input->post('isupplier');
      $esuppliername  = $this->input->post('esuppliername');
      if($dfrom == ''){
        $dfrom = $this->uri->segment(4);
      }
      if($dto == ''){
        $dto = $this->uri->segment(5);
      }
      if($isupplier == ''){
        $isupplier = $this->uri->segment(6);
      }     
    	echo $this->mmaster->data($dfrom,$dto,$isupplier,$this->global['folder'],$this->i_menu);
    }

    public function view(){
      $dfrom          = $this->input->post('dfrom');
      $dto            = $this->input->post('dto');
      $isupplier      = $this->input->post('isupplier');
      $esuppliername  = $this->input->post('esuppliername');
      if($dfrom == ''){
        $dfrom = $this->uri->segment(4);
      }
      if($dto == ''){
        $dto = $this->uri->segment(5);
      }
      if($isupplier == ''){
        $isupplier = $this->uri->segment(6);
      }     

      $data = array(
          'folder'        => $this->global['folder'],
          'title'         => "View ".$this->global['title'],
          'title_list'    => 'List '.$this->global['title'],
          'dfrom'         => $dfrom,
          'dto'           => $dto,
          'isupplier'     => $isupplier,
          'esuppliername' => $esuppliername,
          'supplier'      => $this->mmaster->bacasupplier()
      );
      $this->Logger->write('Membuka Menu View '.$this->global['title']);
      $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function supplier(){
      header("Content-Type: application/json", true);
      $kode = $this->input->post('kode');
      $data  = $this->mmaster->supplier($kode);
      echo json_encode($data->result_array());  
    }

    public function detail(){
      $data = check_role($this->i_menu, 3);
      if(!$data){
          redirect(base_url(),'refresh');
      }

      if($this->uri->segment(4)!=''){
        $ifaktur    = $this->uri->segment(4);
        $isupplier  = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto 	      = $this->uri->segment(7);
    
        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Edit ".$this->global['title'],
            'title_list'     => 'List '.$this->global['title'],
            'ifaktur'        => $ifaktur,
            'dfrom'          => $dfrom,
            'dto'            => $dto,
            'isupplier'      => $isupplier,
            'head'           => $this->mmaster->getdetail($ifaktur,$isupplier)->row(),
            'detail'         => $this->mmaster->getdetail($ifaktur,$isupplier)->result()
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
      }
    }

    public function export(){
        $dfrom = $this->uri->segment(4);
        $dto   = $this->uri->segment(5);
        
        $query = $this->mmaster->getAll($dfrom, $dto)->result();
        $spreadsheet = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $conditional3 = new Conditional();
      //   $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray( 
      //     [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
      //     'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] ); 

      //   $sharedStyle1->applyFromArray(
      //     [
      //     'alignment' => [
      //       'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      //       'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
      //     ],
      //     'borders' => [
      //       'bottom' => ['borderStyle' => Border::BORDER_THIN],
      //       'right' => ['borderStyle' => Border::BORDER_THIN],
      //     ],
      //   ]
      // );

        $sharedStyle2->applyFromArray(

          [
            'borders' => 
            [
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
        ->setName('Arial')
        ->setSize(9);
        foreach(range('A','N') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
    }
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'FM')
                      ->setCellValue('B1', 'KD_JENIS_TRANSAKSI')
                      ->setCellValue('C1', 'FG_PENGGANTI')
                      ->setCellValue('D1', 'NOMOR_FAKTUR')
                      ->setCellValue('E1', 'MASA_PAJAK')
                      ->setCellValue('F1', 'TAHUN_PAJAK')
                      ->setCellValue('G1', 'TANGGAL_FAKTUR')
                      ->setCellValue('H1', 'NPWP')
                      ->setCellValue('I1', 'NAMA')
                      ->setCellValue('J1', 'ALAMAT_LENGKAP')
                      ->setCellValue('K1', 'JUMLAH_DPP')
                      ->setCellValue('L1', 'JUMLAH_PPN')
                      ->setCellValue('M1', 'JUMLAH_PPNBM')
                      ->setCellValue('N1', 'IS_CREDITABLE');
        //  $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A1:N1');

        $kolom = 2;
        $nomor = 1;
        $nol = 0;
        foreach($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $kolom, 'FM')
                            ->setCellValue('B' . $kolom, '01')
                            ->setCellValue('C' . $kolom, '0')
                            ->setCellValue('D' . $kolom, $row->i_pajak)
                            ->setCellValue('E' . $kolom, $row->masa_pajak)
                            ->setCellValue('F' . $kolom, $row->tahun_pajak)
                            ->setCellValue('G' . $kolom, $row->tgl_pajak)
                            ->setCellValue('H' . $kolom, $row->i_supplier_npwp)
                            ->setCellValue('I' . $kolom, $row->e_supplier_name)
                            ->setCellValue('J' . $kolom, $row->e_supplier_address)
                            ->setCellValue('K' . $kolom, $row->dpp)
                            ->setCellValue('L' . $kolom, $row->ppn)
                            ->setCellValue('M' . $kolom, '0')
                            ->setCellValue('N' . $kolom, '1');
                           // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':L'.$kolom);
               $kolom++;
               $nomor++;

          }

          $writer = new Xls($spreadsheet);

          $nama_file = "E_Faktur_Pembelian_".$dfrom."_".$dto.".xls";
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename='.$nama_file.'');
          header('Cache-Control: max-age=0');

          $writer->save('php://output');
    }
}
/* End of file Cform.php */
