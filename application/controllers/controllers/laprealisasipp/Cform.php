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
    public $i_menu = '2050217';

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

        $gudang    = $this->session->userdata('gudang');

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'gudang'    => $this->mmaster->bacagudang($gudang),
            'igudang'   => $gudang,
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vmainform', $data);
    }

    function data(){
        
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(5);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(6);
        }
        $gudang  = $this->input->post('gudang', TRUE);
        if ($gudang == '') {
            $gudang = $this->uri->segment(4);
        }
        //var_dump($gudang);
        echo $this->mmaster->data($dfrom,$dto,$gudang,$this->global['folder'],$this->i_menu);
    }

    public function detail(){

        $ipp    = $this->uri->segment('4');
        $iop    = $this->uri->segment('5');
        $from   = $this->uri->segment('6');
        $to     = $this->uri->segment('7');
        $gudang = $this->uri->segment('8');

        $tmp = explode('-', $from);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dfrom = $th.'-'.$bl.'-'.$hr;

        $tmp = explode('-', $to);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dto = $th.'-'.$bl.'-'.$hr;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'gudang'        => $gudang,
            'head'          => $this->mmaster->baca_header($ipp)->row(),
            'detail'        => $this->mmaster->baca_detail($ipp, $iop),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function export(){
            $dfrom        = $this->uri->segment(4);
            $dto          = $this->uri->segment(5);
            $gudang       = $this->uri->segment(6);
            
            $query        = $this->mmaster->getAll($dfrom, $dto, $gudang);
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
                foreach(range('A','M') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                $spreadsheet->getActiveSheet()->mergeCells("A1:M1");
                $spreadsheet->getActiveSheet()->mergeCells("A2:M2");
                //$spreadsheet->getActiveSheet()->mergeCells("A3:L3");
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', $this->global['title'])
                ->setCellValue('A2', "Periode : $dfrom sd $dto")
                //->setCellValue('A3', "Area : $area")
                ->setCellValue('A5', 'No')
                ->setCellValue('B5', 'No PP')
                ->setCellValue('C5', 'Tanggal PP')
                ->setCellValue('D5', 'No OP')
                ->setCellValue('E5', 'Tanggal OP')
                ->setCellValue('F5', 'Nama Supplier')
                ->setCellValue('G5', 'Kode Material')
                ->setCellValue('H5', 'Nama Material')
                ->setCellValue('I5', 'Satuan')
                ->setCellValue('J5', 'Qty PP')
                ->setCellValue('K5', 'Qty OP')
                ->setCellValue('L5', '% Realisasi')
                ->setCellValue('M5', 'Status');
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:M3');
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:M5');

                $kolom = 6;
                $nomor = 1;
                $nol   = 0;
                foreach($query->result() as $row) {
                    $persen = ($row->qtyop/$row->qtypp)*100;
                    $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->i_pp)
                    ->setCellValue('C' . $kolom, $row->d_pp)
                    ->setCellValue('D' . $kolom, $row->i_op)
                    ->setCellValue('E' . $kolom, $row->d_op)
                    ->setCellValue('F' . $kolom, $row->e_supplier_name)
                    ->setCellValue('G' . $kolom, $row->i_material)
                    ->setCellValue('H' . $kolom, $row->e_material_name)
                    ->setCellValue('I' . $kolom, $row->e_satuan)
                    ->setCellValue('J' . $kolom, $row->qtypp)
                    ->setCellValue('K' . $kolom, $row->qtyop)
                    ->setCellValue('L' . $kolom, $persen."%")
                    ->setCellValue('M' . $kolom, $row->status);

                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':M'.$kolom);
                    $kolom++;
                    $nomor++;
                }
                $tgl = date("d")."-".date("m")."-".date("Y")."  Jam : ".date("H:i:s");
                $spreadsheet->getActiveSheet()->mergeCells('A'.$kolom.':M'.$kolom);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':M'.$kolom);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$kolom, 'Tgl Cetak : '.$tgl);
            }
            $this->Logger->write('Realisasi PP Bahan Baku'.' Periode:'.$dfrom.' s/d '.$dto);
            $spreadsheet->getActiveSheet()->setTitle('Realisasi PP Bahan Baku');
            $nama_file = "Realisasi_PP_Bahan_Baku_".$dfrom."_".$dto.".xls";
            $writer = new Xls($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$nama_file.'');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }
/* End of file Cform.php */
