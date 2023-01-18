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
    public $i_menu = '1050202';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->model($this->global['folder'].'/mmaster');
    }  

    public function index(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekuser($username, $idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea($iarea),
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function export(){
        $dfrom        = $this->uri->segment(4);
        $dto          = $this->uri->segment(5);
        $iarea        = $this->uri->segment(6);
        $earea        = $this->mmaster->area($iarea);        
        if ($iarea=='NA') {
            $area = 'Nasional';
        }else{
            $area = $earea;
        }
        $query        = $this->mmaster->getAll($dfrom, $dto, $iarea);
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
            foreach(range('A','H') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:H2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:H3");
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->global['title'])
            ->setCellValue('A2', "Periode : $dfrom sd $dto")
            ->setCellValue('A3', "Area : $area")
            ->setCellValue('A5', 'No')
            ->setCellValue('B5', 'Nama Toko')
            ->setCellValue('C5', 'No. Nota')
            ->setCellValue('D5', 'Tanggal Nota')
            ->setCellValue('E5', 'Nilai')
            ->setCellValue('F5', 'No. SJ')
            ->setCellValue('G5', 'Keterangan')
            ->setCellValue('H5', 'Check');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:H5');

            $kolom = 6;
            $nomor = 1;
            $nol   = 0;
            foreach($query->result() as $row) {
                if($row->v_nota_discount!=$row->v_nota_discounttotal){
                    $row->v_nota_discount = $row->v_nota_discount1+$row->v_nota_discount2+$row->v_nota_discount3+$row->v_nota_discount4;
                }
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $row->e_customer_name)
                ->setCellValue('C' . $kolom, trim(substr($row->i_nota,8,7)))
                ->setCellValue('D' . $kolom, Date::PHPToExcel($row->d_nota))
                ->setCellValue('E' . $kolom, $row->v_nota_netto)
                ->setCellValue('F' . $kolom, trim(substr($row->i_sj,8,6)))
                ->setCellValue('G' . $kolom, 'Nota Asli + SJ Asli');
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':H'.$kolom);
                $spreadsheet->getActiveSheet()
                ->getStyle('D'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
                $spreadsheet->getActiveSheet()
                ->getStyle('E'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                $kolom++;
                $nomor++;
            }
            $tgl = date("d")."-".date("m")."-".date("Y")."  Jam : ".date("H:i:s");
            $spreadsheet->getActiveSheet()->mergeCells('A'.$kolom.':H'.$kolom);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':H'.$kolom);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$kolom, 'Tgl Cetak : '.$tgl);
        }
        $this->Logger->write('TTD Nota Area '.$iarea.' Periode:'.$dfrom.' s/d '.$dto);
        $spreadsheet->getActiveSheet()->setTitle('TTD Nota');
        $nama_file = "TTD_Nota_".$dfrom."_".$dto."_".$iarea.".xls";
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
