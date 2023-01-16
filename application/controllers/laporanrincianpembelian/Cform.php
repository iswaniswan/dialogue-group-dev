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
    public $i_menu = '20205';

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

        $isupplier = $this->input->post('isupplier', TRUE);
        if($isupplier == ''){
            $isupplier = $this->uri->segment(4);
            if($isupplier == ''){
                $isupplier = 'ALL';
            }
        }
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(5);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(6);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }
        
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
            'isupplier'  => $isupplier,
            'supplier'   => $this->mmaster->bacasupplier(),
            'esupplier'  => $this->mmaster->getesupplier($isupplier),
            'isi'        => $this->mmaster->bacaexport($isupplier,$dfrom,$dto)
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vmainform', $data);
    }

    public function data(){
        $supplier  = $this->input->post('isupplier');
        $dfrom  = $this->input->post('dfrom');
        $dto  = $this->input->post('dto');

        if($supplier==''){
            $supplier=$this->uri->segment(4);
        }
        if($dfrom==''){
            $dfrom=$this->uri->segment(5);
        }
        if($dto==''){
            $dto=$this->uri->segment(6);
        }
		echo $this->mmaster->data($supplier,$dfrom,$dto);
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
            foreach(range('A','S') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                /*$conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);*/
            }
            if ($supplier=='ALL') {
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
            ->setCellValue('B5', 'Tgl SJ')
            ->setCellValue('C5', 'No SJ')
            ->setCellValue('D5', 'No Faktur')
            ->setCellValue('E5', 'Kode Supplier')
            ->setCellValue('F5', 'Nama Supplier')
            ->setCellValue('G5', 'Kode Barang')
            ->setCellValue('H5', 'Nama Barang')
            ->setCellValue('I5', 'No Perkiraan')
            ->setCellValue('J5', 'Nama Perkiraan')
            ->setCellValue('K5', 'Status Pajak')
            ->setCellValue('L5', 'Satuan')
            ->setCellValue('M5', 'Qty')
            ->setCellValue('N5', 'Harga')
            ->setCellValue('O5', 'Diskon %')
            ->setCellValue('P5', 'Total')
            ->setCellValue('Q5', 'DPP')
            ->setCellValue('R5', 'PPN')
            ->setCellValue('S5', 'Update By');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:S1');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:S2');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:S3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:S5');

            $kolom = 6;
            $nomor = 1;
            $nol   = 0;
            $total    = 0;
            $dpp   = 0;
            $ppn  = 0;

            foreach($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, Date::PHPToExcel($row->d_sj_supplier))
                ->setCellValue('C' . $kolom, $row->i_sj_supplier)
                ->setCellValue('D' . $kolom, $row->i_faktur_supplier)
                ->setCellValue('E' . $kolom, $row->i_supplier)
                ->setCellValue('F' . $kolom, $row->e_supplier_name)
                ->setCellValue('G' . $kolom, $row->i_material)
                ->setCellValue('H' . $kolom, $row->e_material_name)
                ->setCellValue('I' . $kolom, $row->noperkiraan)
                ->setCellValue('J' . $kolom, $row->namaperkiraan)
                ->setCellValue('K' . $kolom, $row->status_pajak)
                ->setCellValue('L' . $kolom, $row->e_satuan_name)
                ->setCellValue('M' . $kolom, $row->n_quantity)
                ->setCellValue('N' . $kolom, $row->v_price)
                ->setCellValue('O' . $kolom, $row->n_diskon)
                ->setCellValue('P' . $kolom, $row->total)
                ->setCellValue('Q' . $kolom, $row->dpp)
                ->setCellValue('R' . $kolom, $row->ppn)
                ->setCellValue('S' . $kolom, ''); 
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':S'.$kolom);
                $spreadsheet->getActiveSheet()
                ->getStyle('B'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
                $spreadsheet->getActiveSheet()
                ->getStyle('P'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                $spreadsheet->getActiveSheet()
                ->getStyle('Q'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                $spreadsheet->getActiveSheet()
                ->getStyle('R'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);

                $kolom++;
                $nomor++;

                $total += $row->total;
                $dpp += $row->dpp;
                $ppn += $row->ppn;
            }
            $spreadsheet->getActiveSheet()->mergeCells('A'.$kolom.':O'.$kolom);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':S'.$kolom);
            $spreadsheet->getActiveSheet()->setCellValue('A' . $kolom, 'TOTAL');
            $spreadsheet->getActiveSheet()->setCellValue('P' . $kolom, $total);
            $spreadsheet->getActiveSheet()->setCellValue('Q' . $kolom, $dpp);
            $spreadsheet->getActiveSheet()->setCellValue('R' . $kolom, $ppn);

            $spreadsheet->getActiveSheet()
                ->getStyle('P'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()
                ->getStyle('Q'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()
                ->getStyle('R'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);

            $writer = new Xls($spreadsheet);
            $nama_file = "Laporan_Buku_Pembelian_".$dfrom."_".$dto.".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$nama_file.'');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

    // public function view(){

    //     $data = check_role($this->i_menu, 2);
    //     if(!$data){
    //         redirect(base_url(),'refresh');
    //     }
    //     $supplier  = $this->input->post('isupplier');
    //     $from  = $this->input->post('dfrom');
    //     $to  = $this->input->post('dto');

    //     $tmp = explode('-', $from);
    //     $hr = $tmp[0];
    //     $bl = $tmp[1];
    //     $th = $tmp[2];
    //     $dfrom = $th.'-'.$bl.'-'.$hr;

    //     $tmp = explode('-', $to);
    //     $hr = $tmp[0];
    //     $bl = $tmp[1];
    //     $th = $tmp[2];
    //     $dto = $th.'-'.$bl.'-'.$hr;

    //     $data = array(
    //         'folder'        => $this->global['folder'],
    //         'title'         => "View ".$this->global['title'],
    //         'title_list'    => 'List '.$this->global['title'],
    //         'dfrom'         => $dfrom,
    //         'dto'           => $dto,
    //         'supplier'      => $supplier,
    //         'isi'           => $this->mmaster->bacaexport($supplier,$dfrom,$dto),
    //     );

    //     $this->Logger->write('Membuka Data '.$this->global['title'].' '.$supplier.' Tanggal : '.$from.' Sampai '.$to);

    //     $this->load->view($this->global['folder'].'/vformview', $data);
    // }
}
/* End of file Cform.php */
