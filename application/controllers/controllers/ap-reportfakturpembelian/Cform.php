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
    public $i_menu = '2040111';
    
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
        $bulan = $this->input->post('bulan');
        if($bulan == ''){   
            $bulan = $this->uri->segment('4');
        }
        
        $tahun = $this->input->post('tahun');
        if($tahun == ''){
            $tahun = $this->uri->segment('5');
        }

        $isupplier  = $this->input->post('isupplier');
        #$isupplierx = $this->input->post('isupplierx');
        if($isupplier == ''){
         #   $isupplier = '';
        #}else{
            $isupplier = $this->uri->segment('6');
        }

        if($bulan != ''){
            $nmbulan = $this->fungsi->mbulan($bulan);
        }else{
            $nmbulan = '';
            #$isupplier = '';
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'bulan'         => $bulan,
            'tahun'         => $tahun,
            'isupplier'     => $isupplier,
            'periode'       => '',
            'namabulan'     => $nmbulan,
            'supplier'      => $this->mmaster->get_supplier()->result(),
            #'supplier'      => '',
        );
        
        $this->Logger->write('Membuka Menuu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
        $bulan          = $this->input->post('bulan');
        $tahun          = $this->input->post('tahun');
        $isupplier      = $this->input->post('isupplier');

        if($bulan == ''){
            $bulan = $this->uri->segment(4);
        }
        if($tahun == ''){
            $tahun = $this->uri->segment(5);
        }
        if($isupplier == ''){
            $isupplier = $this->uri->segment(6);
        }
/*=======*/

        $namabulan  = $this->fungsi->mbulan($bulan);
        #echo $this->mmaster->data($bulan, $tahun, $isupplier, $ifaktur, $this->i_menu, $this->global['folder']);
        echo $this->mmaster->data($bulan, $tahun, $isupplier, $this->i_menu, $this->global['folder']);
    }

    // public function typemakloon(){
    //     header("Content-Type: application/json", true);
    //     $this->mmaster->typemakloon();
    // }

    public function view(){

        $inota          = $this->uri->segment('4');
        $isupplier      = $this->uri->segment('5');
        $ipaymenttype   = $this->uri->segment('6');
        $bulan          = $this->uri->segment('7');
        $tahun          = $this->uri->segment('8');
        $isupplierx     = $this->uri->segment('9');

        //var_dump($bulan."/".$tahun."/".$isupplier);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'bulan'      => $bulan,
            'tahun'      => $tahun,
            'isupplier'  => $isupplier,
            'isupplierx' => $isupplierx,
            'data'       => $this->mmaster->cek_data($inota)->row(),
            'data1'      => $this->mmaster->get_itemm($inota,$isupplier)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function view2(){
        $bulan          = $this->input->post('bulan');
        $tahun          = $this->input->post('tahun');
        $periode        = $tahun.$bulan;
        $isupplier      = $this->input->post('isupplier');

        if($bulan == ''){
            $bulan = $this->uri->segment(4);
        }
        if($tahun == ''){
            $tahun = $this->uri->segment(5);
        }
        if($isupplier == ''){
            $isupplier = $this->uri->segment(6);
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
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function export(){

        $bulan          = $this->uri->segment(4);
        $tahun          = $this->uri->segment(5);
        $isupplier      = $this->uri->segment(6);

        $periode        = $tahun.$bulan;
        
        $query = $this->mmaster->bacaexport($bulan, $tahun, $isupplier)->result();
        
        $spreadsheet  = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $conditional3 = new Conditional();

        $sharedStyle1->applyFromArray(
            [
              'borders' => 
              [
                'bottom'        => ['borderStyle' => Border::BORDER_THIN],
                'right'         => ['borderStyle' => Border::BORDER_THIN],
                'setAutoSize'   => [true],
                #'setSize'       => [9],
                #'setName'       => ['Arial'],
              ],
            ]
          );

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

        foreach(range('A','O') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }

            $spreadsheet->getActiveSheet()->mergeCells("A1:O1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:O2");
            //$spreadsheet->getActiveSheet()->mergeCells("A3:O3");
            $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Laporan Faktur Pembelian')
                      ->setCellValue('A2', 'Periode : '.$this->fungsi->mbulan($bulan)." ".$tahun)
                      ->setCellValue('A3', 'No Faktur')
                      ->setCellValue('B3', 'Tanggal Faktur')
                      ->setCellValue('C3', 'Keterangan')
                      ->setCellValue('D3', 'No BTB')
                      ->setCellValue('E3', 'Supplier')
                      ->setCellValue('F3', 'Kode Material')
                      ->setCellValue('G3', 'Nama Material')
                      ->setCellValue('H3', 'Qty Eks')
                      ->setCellValue('I3', 'Satuan')
                      ->setCellValue('J3', 'Qty In')
                      ->setCellValue('K3', 'Satuan')
                      ->setCellValue('L3', 'Harga')
                      ->setCellValue('M3', 'DPP')
                      ->setCellValue('N3', 'PPN')
                      ->setCellValue('O3', 'Total');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:O1');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:O2');
            #$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A3:O3');
        $kolom = 4;
        $nomor = 1;
        $nol = 0;
        foreach($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $kolom, $row->i_nota)
                            ->setCellValue('B' . $kolom, $row->d_nota)
                            ->setCellValue('C' . $kolom, $row->e_remark)
                            ->setCellValue('D' . $kolom, $row->i_btb)
                            ->setCellValue('E' . $kolom, $row->e_supplier_name)
                            ->setCellValue('F' . $kolom, $row->i_material)
                            ->setCellValue('G' . $kolom, $row->e_material_name)
                            ->setCellValue('H' . $kolom, $row->n_qty_eks)
                            ->setCellValue('I' . $kolom, $row->satuaneks)
                            ->setCellValue('J' . $kolom, $row->qtyin)
                            ->setCellValue('K' . $kolom, $row->e_satuan)
                            ->setCellValue('L' . $kolom, $row->v_price)
                            ->setCellValue('M' . $kolom, $row->v_dpp)
                            ->setCellValue('N' . $kolom, $row->v_ppn)
                            ->setCellValue('O' . $kolom, $row->v_total);
               $kolom++;
               $nomor++;

          }

          $writer = new Xls($spreadsheet);

          $nama_file = "laporan_faktur_pembelian_".$periode.".xls";
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename='.$nama_file.'');
          header('Cache-Control: max-age=0');

          $writer->save('php://output');
    }

}
/* End of file Cform.php */
