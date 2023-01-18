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
    public $i_menu = '2050218';

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
        

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
       // $supplier  = $this->input->post('isupplier');
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            //'supplier'      => $this->mmaster->bacasupplier(),
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'isi'           => $this->mmaster->bacaexport($dfrom,$dto),
            //'total'         => $this->mmaster->total($supplier,$dfrom,$dto)->row()
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    function data(){
        $supplier  = $this->input->post('isupplier');

        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
		echo $this->mmaster->data($dfrom,$dto);
    }

    public function export(){
        $dfrom        = $this->uri->segment(4);
        $dto          = $this->uri->segment(5);
        $query        = $this->mmaster->getAll($dfrom, $dto);
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
            $spreadsheet->getActiveSheet()->mergeCells("A1:L1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:L2");
            //$spreadsheet->getActiveSheet()->mergeCells("A3:L3");
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->global['title'])
            ->setCellValue('A2', "Periode : $dfrom sd $dto")
            ->setCellValue('A4', 'No')
            ->setCellValue('B4', 'No Schedule')
            ->setCellValue('C4', 'No Bon K')
            ->setCellValue('D4', 'Kode Produk')
            ->setCellValue('E4', 'Nama Produk')
            ->setCellValue('F4', 'Kode Material')
            ->setCellValue('G4', 'Nama Material')
            ->setCellValue('H4', 'Warna')
            ->setCellValue('I4', 'Qty Schedule')
            ->setCellValue('J4', 'Qty Pemenuhan')
            ->setCellValue('K4', 'Selisih')
            ->setCellValue('L4', 'Status Pemenuhan');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:L3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A4:L4');

            //$kolom = 6;
            $kolom = 5;
            $nomor = 1;
            $nol   = 0;
            foreach($query->result() as $row) {
                // if($row->v_nota_discount!=$row->v_nota_discounttotal){
                //     $row->v_nota_discount = $row->v_nota_discount1+$row->v_nota_discount2+$row->v_nota_discount3+$row->v_nota_discount4;
                // }
                $spreadsheet->setActiveSheetIndex(0)
                // ->setCellValue('A' . $kolom, $nomor)
                // ->setCellValue('B' . $kolom, $row->e_customer_name)
                // ->setCellValue('C' . $kolom, trim(substr($row->i_nota,8,7)))
                // ->setCellValue('D' . $kolom, Date::PHPToExcel($row->d_nota))
                // ->setCellValue('E' . $kolom, $row->v_nota_netto)
                // ->setCellValue('F' . $kolom, trim(substr($row->i_sj,8,6)))
                // ->setCellValue('G' . $kolom, 'Nota Asli + SJ Asli');
                // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':H'.$kolom);
                // $spreadsheet->getActiveSheet()
                // ->getStyle('D'.$kolom)
                // ->getNumberFormat()
                // ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD1);
                // $spreadsheet->getActiveSheet()
                // ->getStyle('E'.$kolom)
                // ->getNumberFormat()
                // ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);

                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $row->i_schedule)
                ->setCellValue('C' . $kolom, $row->i_bonk)
                ->setCellValue('D' . $kolom, $row->i_product)
                ->setCellValue('E' . $kolom, $row->e_product_name)
                ->setCellValue('F' . $kolom, $row->i_material)
                ->setCellValue('G' . $kolom, $row->e_material_name)
                ->setCellValue('H' . $kolom, $row->e_color_name)
                ->setCellValue('I' . $kolom, $row->n_quantity)
                ->setCellValue('J' . $kolom, $row->n_pemenuhan)
                ->setCellValue('K' . $kolom, $row->selisih)
                ->setCellValue('L' . $kolom, $row->status);
                $kolom++;
                $nomor++;
            }
            $tgl = date("d")."-".date("m")."-".date("Y")."  Jam : ".date("H:i:s");
            $spreadsheet->getActiveSheet()->mergeCells('A'.$kolom.':H'.$kolom);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':H'.$kolom);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$kolom, 'Tgl Cetak : '.$tgl);
        }
        //$this->Logger->write('TTD Nota Area '.$iarea.' Periode:'.$dfrom.' s/d '.$dto);
        $this->Logger->write('Report Outstanding SPBB Periode:'.$dfrom.' s/d '.$dto);
        $spreadsheet->getActiveSheet()->setTitle('TTD Nota');
        //$nama_file = "TTD_Nota_".$dfrom."_".$dto."_".$iarea.".xls";
        $nama_file = "Report_Out_SPBB".$dfrom."_".$dto.".xls";
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