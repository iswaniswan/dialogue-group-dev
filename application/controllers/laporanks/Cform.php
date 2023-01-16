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
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107031005';

    public function __construct(){
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
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function view(){
        $bulan      = $this->input->post('bulan',TRUE);
        $tahun      = $this->input->post('tahun',TRUE);
        
        if($bulan==''){
            $bulan = $this->uri->segment(4);
        }
        if($tahun==''){
            $tahun = $this->uri->segment(5);
        }

        $iperiode   = $tahun.$bulan;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iperiode'      => $iperiode,
            'isi'           => $this->mmaster->data($iperiode)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function export(){
        $iperiode        = $this->input->post('iperiode');
        
        if($iperiode == ''){
            $iperiode        = $this->uri->segment(4);
        }

        $periode=$iperiode;
        $a=substr($periode,0,4);
        $b=substr($periode,4,2);
        $eperiode= $this->fungsi->mbulan($b)." - ".$a;

        $query        = $this->mmaster->getAll($iperiode);

        $spreadsheet  = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        if ($query->num_rows()>0) {
            $sharedStyle1->applyFromArray(
                [
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'DFF1D0'],
                    ],
                    'font'=>[
                        'name'  => 'Arial',
                        'bold'  => true,
                        'italic'=> false,
                        'size'  => 10
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN]
                    ],
                ]
            );

            $sharedStyle2->applyFromArray(
                [
                    'font'=>[
                        'name'  => 'Arial',
                        'bold'  => false,
                        'italic'=> false,
                        'size'  => 10
                    ],
                    'borders' => [
                        'top'    => ['borderStyle' => Border::BORDER_THIN],
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'left'   => ['borderStyle' => Border::BORDER_THIN],
                        'right'  => ['borderStyle' => Border::BORDER_THIN]
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]
            );

            $sharedStyle3->applyFromArray(
                [
                    'font'=>[
                        'name'  => 'Times New Roman',
                        'bold'  => true,
                        'italic'=> false,
                        'size'  => 12
                    ],
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
            foreach(range('A','E') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->getActiveSheet()->mergeCells("A1:E1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:E2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:E3");
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->global['title'])
            ->setCellValue('A2', "Periode : $eperiode")
            ->setCellValue('A4', 'Kode Produk')
            ->setCellValue('B4', 'Nama Produk')
            ->setCellValue('C4', 'Motif')
            ->setCellValue('D4', 'Grade')
            ->setCellValue('E4', 'Total Jumlah');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:E3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A4:E5');

            $kolom = 5;
            foreach($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $row->ic_product)
                ->setCellValue('B' . $kolom, $row->ic_product_name)
                ->setCellValue('C' . $kolom, $row->ic_product_motif)
                ->setCellValue('D' . $kolom, $row->ic_product_grade)
                ->setCellValue('E' . $kolom, $row->ic_n_convertion);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':E'.$kolom);
                $kolom++;
            }
            $tgl = date("d")."-".date("m")."-".date("Y")."  Jam : ".date("H:i:s");
            $spreadsheet->getActiveSheet()->mergeCells('A'.$kolom.':E'.$kolom);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':E'.$kolom);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$kolom, 'Tgl Cetak : '.$tgl);
        }
        $this->Logger->write('Laporan Konversi Stock Periode:'.$iperiode);
        $spreadsheet->getActiveSheet()->setTitle('Lap.Konversi Stock');
        $nama_file = "Report_KS_".$iperiode.".xls";
        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        if(file_exists('export/00/'.$nama_file)){
            @chmod('export/00/'.$nama_file, 0777);
            @unlink('export/00/'.$nama_file);
        }
        $writer->save('export/00/'.$nama_file); 
        @chmod('export/00/'.$nama_file, 0777);
        $writer = new Xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}

/* End of file Cform.php */
