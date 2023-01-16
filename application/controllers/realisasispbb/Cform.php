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
    public $i_menu = '2090106';

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    public function detail()
    {
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data   = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'title'     => "Detail ".$this->global['title'],
            'title_list'=> 'List '.$this->global['title'], 
            'id'        => $this->uri->segment(7),
            'dfrom'     => $this->uri->segment(5),
            'dto'       => $this->uri->segment(6),
            'data'      => $this->mmaster->detail($this->uri->segment(4),$this->uri->segment(5),$this->uri->segment(6)),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformdetail', $data);
    }

    public function export()
    {
        $spreadsheet  = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        if ($this->mmaster->export($this->uri->segment(4),$this->uri->segment(5))->num_rows()>0) {            
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
            foreach(range('A','K') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->getActiveSheet()->mergeCells("A2:K2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:K3");
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A2', $this->global['title'])
            ->setCellValue('A3', "Periode : ".$this->uri->segment(4)." s/d ".$this->uri->segment(4))
            ->setCellValue('A5', 'No')
            ->setCellValue('B5', 'Kode WIP')
            ->setCellValue('C5', 'Nama Barang WIP')
            ->setCellValue('D5', 'Kode Material')
            ->setCellValue('E5', 'Nama Barang Material')
            ->setCellValue('F5', 'Satuan')
            ->setCellValue('G5', 'No. Dokumen')
            ->setCellValue('H5', 'Permintaan')
            ->setCellValue('I5', 'Pemenuhan')
            ->setCellValue('J5', 'Jml Gelar')
            ->setCellValue('K5', '% Pemenuhan');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:K3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:K5');

            $kolom = 6;
            $nomor = 1;
            $nol   = 0;
            foreach($this->mmaster->export($this->uri->segment(4),$this->uri->segment(5))->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $nomor)
                ->setCellValue('B' . $kolom, $row->i_product)
                ->setCellValue('C' . $kolom, $row->e_product_name)
                ->setCellValue('D' . $kolom, $row->i_material)
                ->setCellValue('E' . $kolom, $row->e_material_name)
                ->setCellValue('F' . $kolom, $row->e_satuan)
                ->setCellValue('G' . $kolom, $row->i_spbb)
                ->setCellValue('H' . $kolom, $row->permintaan)
                ->setCellValue('I' . $kolom, $row->pemenuhan)
                ->setCellValue('J' . $kolom, $row->jumlah_gelar)
                ->setCellValue('K' . $kolom, $row->persentase)
                ;
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':K'.$kolom);
                $spreadsheet->getActiveSheet()
                ->getStyle('K'.$kolom)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
                $kolom++;
                $nomor++;
            }
            $spreadsheet->getActiveSheet()->mergeCells('A'.$kolom.':G'.$kolom);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A'.$kolom.':G'.$kolom);
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A'.$kolom, 'TOTAL')
            ->setCellValue('H'.$kolom, '=SUM(H6:H'.($kolom-1).')')
            ->setCellValue('I'.$kolom, '=SUM(I6:I'.($kolom-1).')')
            ->setCellValue('J'.$kolom, '=SUM(J6:J'.($kolom-1).')')
            ->setCellValue('K'.$kolom, '=I'.$kolom.'/H'.$kolom)
            ;
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'H'.$kolom.':K'.$kolom);
            $spreadsheet->getActiveSheet()
            ->getStyle('K'.$kolom)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);
        }
        $this->Logger->write('Export '.$this->global['title'].' Periode : '.$this->uri->segment(4).' s/d '.$this->uri->segment(5));
        $spreadsheet->getActiveSheet()->setTitle($this->global['title']);
        $nama_file = str_replace(" ", "_", $this->global['title'])."_".date('Ymd',strtotime($this->uri->segment(4)))."_sd_".date('Ymd',strtotime($this->uri->segment(5))).".xls";
        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer = new Xls($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
/* End of file Cform.php */
