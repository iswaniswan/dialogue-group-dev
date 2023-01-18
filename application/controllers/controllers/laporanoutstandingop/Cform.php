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
    public $i_menu = '20203';

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

    public function index()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $supplier = $this->input->post('supplier');
        if($supplier== ''){
            $supplier  = $this->uri->segment(4);
            if($supplier== ''){
                $supplier = 'SP';
            }
        }

        $dfrom = $this->input->post('dfrom');
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(5);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto');
        if ($dto == '') {
            $dto = $this->uri->segment(6);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
            'supplier'      => $supplier,
            'ceksup'        => $this->mmaster->cek_supplier($dfrom,$dto)->result(),
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' '.$supplier.' Tanggal : '.$dfrom.' Sampai '.$dto);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    function data(){
        $supplier  = $this->input->post('supplier');
        if ($supplier=='') {
            $supplier = $this->uri->segment(4);
        }
        $dfrom  = $this->input->post('dfrom');
        if ($dfrom=='') {
            $dfrom = $this->uri->segment(5);
        }
        $dto  = $this->input->post('dto');
        if ($dto=='') {
            $dto = $this->uri->segment(6);
        }

        echo $this->mmaster->data($supplier,$dfrom,$dto,$this->i_menu,$this->global['folder']);
    }

    public function view()
    {

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'supplier'      => $this->uri->segment(7),
            'iop'           => $this->uri->segment(8),
            'data'          => $this->mmaster->cek_data($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' Supplier : '.$this->uri->segment(7).' No OP : '.$this->uri->segment(8));

        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function export()
    {

        $supplier = ($this->input->post('supplier',TRUE) != '' ? $this->input->post('supplier',TRUE) : $this->uri->segment(4));
        $dfrom    = ($this->input->post('dfrom',TRUE) != '' ? $this->input->post('dfrom',TRUE) : $this->uri->segment(5));
        $dto      = ($this->input->post('dto',TRUE) != '' ? $this->input->post('dto',TRUE) : $this->uri->segment(6));
        $query    = $this->mmaster->exportdata($supplier,$dfrom,$dto)->result();

        foreach ($query as $row) {

            $spreadsheet  = new Spreadsheet;
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $conditional3 = new Conditional();

            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray( 
              [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
              'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] ); 

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
            ->setName('Calibri')
            ->setSize(9);
            foreach(range('A','L') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                /*$conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);*/
            }
            if ($supplier=='SP') {
                $cupplier = 'Semua';
            }else{
                $cupplier = $row->e_supplier_name;
            }
            $spreadsheet->getActiveSheet()->mergeCells("A1:K1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:K2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:K3");
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', $this->global['title'])
            ->setCellValue('A2', "Supplier : $cupplier")
            ->setCellValue('A3', "Tanggal: $dfrom s/d $dto")
            ->setCellValue('A5', 'No')
            ->setCellValue('B5', 'No OP')
            ->setCellValue('C5', 'Tanggal OP')
            ->setCellValue('D5', 'Supplier')
            ->setCellValue('E5', 'Pembuat')
            ->setCellValue('F5', 'Kode Barang')
            ->setCellValue('G5', 'Nama Barang')
            ->setCellValue('H5', 'Jumlah OP')
            ->setCellValue('I5', 'Jumlah BTB')
            ->setCellValue('J5', '% OPBTB')
            ->setCellValue('K5', 'Sisa')
            ->setCellValue('L5', 'Harga OP');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:L1');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:L2');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:L3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:L5');

            $kolom = 6;
            $nomor = 1;
            $nol   = 0;
            $op    = 0;
            $btb   = 0;
            $sisa  = 0;
            foreach($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $row->i_op)
                ->setCellValue('C' . $kolom, Date::PHPToExcel($row->d_op))
                ->setCellValue('D' . $kolom, $row->e_supplier_name)
                ->setCellValue('E' . $kolom, $row->e_bagian_name)
                ->setCellValue('F' . $kolom, $row->i_material)
                ->setCellValue('G' . $kolom, $row->e_material_name)
                ->setCellValue('H' . $kolom, $row->op)
                ->setCellValue('I' . $kolom, $row->btb)
                ->setCellValue('J' . $kolom, $row->btb/$row->op)
                ->setCellValue('K' . $kolom, $row->sisa)
                ->setCellValue('L' . $kolom, $row->v_price); 
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':L'.$kolom);
                $spreadsheet->getActiveSheet()
                ->getStyle('C'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
                $spreadsheet->getActiveSheet()
                ->getStyle('J'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $spreadsheet->getActiveSheet()
                ->getStyle('L'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);

                $kolom++;
                $nomor++;

                $op += $row->op;
                $btb += $row->btb;
                $sisa += $row->sisa;
            }
            $spreadsheet->getActiveSheet()->mergeCells('A'.$kolom.':G'.$kolom);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':K'.$kolom);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $kolom, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('H' . $kolom, $op);
            $spreadsheet->getActiveSheet()->setCellValue('I' . $kolom, $btb);
            $spreadsheet->getActiveSheet()->setCellValue('J' . $kolom, $btb/$op);
            $spreadsheet->getActiveSheet()->setCellValue('K' . $kolom, $sisa);
            $spreadsheet->getActiveSheet()
                ->getStyle('J'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

            $writer = new Xls($spreadsheet);
            $nama_file = "Oustanding_OP_".$dfrom."_".$dto.".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$nama_file.'');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }
}
/* End of file Cform.php */
