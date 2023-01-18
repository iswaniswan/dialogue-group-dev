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
    public $i_menu = '2040112';
    
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
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');

        $bulan          = $this->input->post('bulan');
        $tahun          = $this->input->post('tahun');
        $periode        = $tahun.$bulan;
        $isupplier      = $this->input->post('isupplier');
        $itypemakloon   = $this->input->post('itypemakloon');
        $ifaktur        = $this->input->post('inota'); 

        if($bulan == ''){
            $bulan = $this->uri->segment(4);
        }
        if($tahun == ''){
            $tahun = $this->uri->segment(5);
        }
        if($isupplier == ''){
            $isupplier = $this->uri->segment(6);
        }
        if($itypemakloon == ''){
            $itypemakloon = $this->uri->segment(7);
        }
        if($ifaktur == ''){
            $ifaktur = $this->uri->segment(8);
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'bulan'             => $bulan,
            'tahun'             => '',
            'periode'           => $periode,
            'ifaktur'           => '',
            'itypemakloon'      => '',
            'isupplier'         => '',
            'namabulan'         => '',
            'typemakloon'       => $this->mmaster->typemakloon(),
            'getmakloon'        => $this->mmaster->getmakloon($itypemakloon)->row(),
            'getsuppliername'   => $this->mmaster->getsuppliername($isupplier)->row(),
            'getfaktur'         => $this->mmaster->getnofaktur($periode, $ifaktur)->row(),
            'total'             => $this->mmaster->bacaheader($bulan, $tahun, $itypemakloon, $isupplier, $ifaktur)->row()
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);

    }

    public function data(){
        $bulan          = $this->input->post('bulan');
        $tahun          = $this->input->post('tahun');
        $periode        = $tahun.$bulan;
        $isupplier      = $this->input->post('isupplier');
        $itypemakloon   = $this->input->post('itypemakloon');
        $ifaktur        = $this->input->post('ifaktur');

        if($bulan == ''){
            $bulan = $this->uri->segment(4);
        }
        if($tahun == ''){
            $tahun = $this->uri->segment(5);
        }
        if($isupplier == ''){
            $isupplier = $this->uri->segment(6);
        }
        if($itypemakloon == ''){
            $itypemakloon = $this->uri->segment(7);
        }
        if($ifaktur == ''){
            $ifaktur = $this->uri->segment(8);
        }

        echo $this->mmaster->data($bulan, $tahun, $isupplier, $ifaktur, $this->i_menu, $this->global['folder']);
    }

    public function typemakloon(){
        header("Content-Type: application/json", true);
        $this->mmaster->typemakloon();
    }

    public function getsupplier(){
        $id = $this->input->post('id');
        $query = $this->mmaster->getsupplier($id);
        if($query->num_rows()>0) {
            $c         = "";
            $supplier  = $query->result();
            foreach($supplier as $row) {
                $c.="<option value=".$row->i_supplier." >".$row->i_supplier." - ".$row->e_supplier_name."</option>";
            }
            $kop  = "<option value=\"ALL\">Semua Supplier".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Supplier Tidak Ada</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getsuppliername(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $data  = $this->mmaster->getsuppliername($kode);
        echo json_encode($data->result_array());  
    }

    public function getfaktur(){
        $isupplier  = $this->input->post('id');
        $periode    = $this->input->post('periode');
        $query      = $this->mmaster->getfaktur($periode, $isupplier);
        if($query->num_rows()>0) {
            $c         = "";
            $faktur  = $query->result();
            foreach($faktur as $row) {
                $c.="<option value=".$row->no_faktur." >".$row->no_faktur." - ".$row->nama_supplier."</option>";
            }
            $kop  = "<option value=\"ALL\">Semua Faktur".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Ada Faktur</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function view(){
        $bulan          = $this->input->post('bulan');
        $tahun          = $this->input->post('tahun');
        $periode        = $tahun.$bulan;
        $isupplier      = $this->input->post('isupplier');
        $itypemakloon   = $this->input->post('itypemakloon');
        $ifaktur        = $this->input->post('inota');

        if($bulan == ''){
            $bulan = $this->uri->segment(4);
        }
        if($tahun == ''){
            $tahun = $this->uri->segment(5);
        }
        if($isupplier == ''){
            $isupplier = $this->uri->segment(6);
        }
        if($itypemakloon == ''){
            $itypemakloon = $this->uri->segment(7);
        }
        if($ifaktur == ''){
            $ifaktur = $this->uri->segment(8);
        }

        $namabulan      = mbulan($bulan);
        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'namabulan'         => $namabulan,
            'bulan'             => $bulan,
            'tahun'             => $tahun,
            'periode'           => $periode,
            'isupplier'         => $isupplier,
            'itypemakloon'      => $itypemakloon,
            'ifaktur'           => $ifaktur,
            'typemakloon'       => $this->mmaster->typemakloon(),
            'getmakloon'        => $this->mmaster->getmakloon($itypemakloon)->row(),
            'getsuppliername'   => $this->mmaster->getsuppliername($isupplier)->row(),
            'getfaktur'         => $this->mmaster->getnofaktur($periode, $ifaktur)->row(),
            'total'             => $this->mmaster->bacaheader($bulan, $tahun, $itypemakloon, $isupplier, $ifaktur)->row()
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function export(){

        $bulan          = $this->uri->segment(4);
        $tahun          = $this->uri->segment(5);
        $isupplier      = $this->uri->segment(6);
        $itypemakloon   = $this->uri->segment(7);
        $ifaktur        = $this->uri->segment(8);
        $periode        = $tahun.$bulan;
        
        $query = $this->mmaster->bacaexport($bulan, $tahun, $itypemakloon, $isupplier, $ifaktur)->result();
        $query2 = $this->mmaster->bacaheader($bulan, $tahun, $itypemakloon, $isupplier, $ifaktur)->result();
        $totgros = 0;
        $totnet  = 0;
        $totdis  = 0;
        $totdpp  = 0;
        $totppn  = 0;
        foreach($query2 as $row){
            $totgros = $row->total_gross;
            $totdis  = $row->total_discount;
            $totnet  = $row->total_netto;
            $totdpp  = $row->total_dpp;
            $totppn  = $row->total_ppn;
        }
        
        $spreadsheet  = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $conditional3 = new Conditional();

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
        foreach(range('A','K') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          $spreadsheet->getActiveSheet()->mergeCells("A1:J1");
          $spreadsheet->getActiveSheet()->mergeCells("A2:J2");
          $spreadsheet->getActiveSheet()->mergeCells("A3:J3");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Laporan Faktur Jasa Makloon')
                      ->setCellValue('A2', 'Periode : '.$periode)
                      ->setCellValue('A4', 'Total Gross : '.$totgros)
                      ->setCellValue('A5', 'Total Diskon : '.$totdis)
                      ->setCellValue('A6', 'Total Netto : '.$totnet)
                      ->setCellValue('A7', 'PPN : '.$totdpp)
                      ->setCellValue('A8', 'DPP : '.$totppn)
                      ->setCellValue('A10', 'No Faktur')
                      ->setCellValue('B10', 'Tanggal Faktur')
                      ->setCellValue('C10', 'Supplier')
                      ->setCellValue('D10', 'Tipe Makloon')
                      ->setCellValue('E10', 'Kode Barang')
                      ->setCellValue('F10', 'Nama Barang')
                      ->setCellValue('G10', 'Warna')
                      ->setCellValue('H10', 'Jumlah')
                      ->setCellValue('I10', 'Harga')
                      ->setCellValue('J10', 'Total');
                      $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:J1');
                      $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:J2');
                      $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A10:J10');
        $kolom = 11;
        $nomor = 1;
        $nol = 0;
        foreach($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $kolom, $row->no_faktur)
                            ->setCellValue('B' . $kolom, $row->tgl_faktur)
                            ->setCellValue('C' . $kolom, $row->nama_supplier)
                            ->setCellValue('D' . $kolom, $row->nama_makloon)
                            ->setCellValue('E' . $kolom, $row->kode_barang)
                            ->setCellValue('F' . $kolom, $row->nama_barang)
                            ->setCellValue('G' . $kolom, $row->warna)
                            ->setCellValue('H' . $kolom, $row->jumlah_barang)
                            ->setCellValue('I' . $kolom, $row->harga_barang)
                            ->setCellValue('J' . $kolom, $row->total_barang);
               $kolom++;
               $nomor++;

          }

          $writer = new Xls($spreadsheet);

          $nama_file = "laporan_faktur_jasamakloon_".$periode.".xls";
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename='.$nama_file.'');
          header('Cache-Control: max-age=0');

          $writer->save('php://output');
    }

}
/* End of file Cform.php */
