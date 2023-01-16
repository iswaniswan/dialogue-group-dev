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
    public $i_menu = '107030212';

    public function __construct(){
        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
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
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Info ".$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 
        echo $this->mmaster->data($dfrom,$dto,$this->global['folder']);
    }
    
    public function view(){
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        if($this->uri->segment(4)!=''){
            $isjp	= $this->uri->segment(4);
            $dfrom= $this->uri->segment(5);
            $dto 	= $this->uri->segment(6);
            $query 	= $this->db->query("select * from tm_sjp_item where i_sjp = '$isjp'");
            $data = array(
                'folder'         => $this->global['folder'],
                'title'          => "Edit ".$this->global['title'],
                'title_list'     => 'List '.$this->global['title'],
                'jmlitem'        => $query->num_rows(),
                'isjp'           => $isjp,
                'dfrom'          => $dfrom,
                'dto'            => $dto,
                'isi'            => $this->mmaster->baca($isjp)->row(),
                'detail'         => $this->mmaster->bacadetail($isjp)->result()
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformedit', $data);
        }
    }

    public function export(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $dfrom = ($this->input->post('xdfrom',TRUE) != '' ? $this->input->post('xdfrom',TRUE) : $this->uri->segment(4));
        $dto = ($this->input->post('xdto',TRUE) != '' ? $this->input->post('xdto',TRUE) : $this->uri->segment(5));
        $query = $this->mmaster->cekdata($dfrom, $dto);
        foreach ($query as $row) {
            $spreadsheet = new Spreadsheet;
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $conditional3 = new Conditional();
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray( 
              [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] 
            ); 

            $sharedStyle1->applyFromArray([
                'alignment' => [
                  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                  'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                  'bottom' => ['borderStyle' => Border::BORDER_THIN],
                  'right' => ['borderStyle' => Border::BORDER_THIN],
                ],
            ]);

        $sharedStyle2->applyFromArray(
            [/*'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFFFFF00'],
                ],*/
                /*'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],*/
                'borders' => [
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
        ->setName('Calibri')
        ->setSize(9);
        foreach(range('A','K') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
            $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A1', 'Daftar SJP Deliver vs Receive')
                        ->setCellValue('A2', NmPerusahaan)
                        ->setCellValue('A3', "Dari Tanggal : $dfrom Sampai Tanggal : $dto")
                        ->setCellValue('A5', 'Area')
                        ->setCellValue('B5', 'No SJP')
                        ->setCellValue('C5', 'Tgl SJP')
                        ->setCellValue('D5', 'Tgl Terima')
                        ->setCellValue('E5', 'SPMB')
                        ->setCellValue('F5', 'Konsinyasi')
                        ->setCellValue('G5', 'Kode')
                        ->setCellValue('H5', 'Nama')
                        ->setCellValue('I5', 'Jml Kirim')
                        ->setCellValue('J5', 'Jml Terima')
                        ->setCellValue('K5', 'Harga');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:K1');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:K2');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:K3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:K5');
            $tmp=explode('-',$row->d_sjp);
            $tgl=$tmp[2];
            $bln=$tmp[1];
            $thn=$tmp[0];
            $row->d_sjp=$tgl.'-'.$bln.'-'.$thn;

            if($row->d_sjp_receive!=''){
               $tm	= explode('-',$row->d_sjp_receive);
                     $tgl	= $tm[2];
                     $bln	= $tm[1];
                     $thn	= $tm[0];
                     $row->d_sjp_receive=$tgl.'-'.$bln.'-'.$thn;
             }
         
             if($row->f_spmb_consigment == 't'){
               $kons='ya';
             }else{
               $kons='tidak';
             }
          $kolom = 6;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, '('.$row->i_area.') '.$row->e_area_name)
                        ->setCellValue('B' . $kolom, $row->i_sjp)
                        ->setCellValue('C' . $kolom, $row->d_sjp)
                        ->setCellValue('D' . $kolom, $row->d_sjp_receive)
                        ->setCellValue('E' . $kolom, $row->i_spmb)
                        ->setCellValue('F' . $kolom, $kons)
                        ->setCellValue('G' . $kolom, $row->i_product)
                        ->setCellValue('H' . $kolom, $row->e_product_name)
                        ->setCellValue('I' . $kolom, $row->n_quantity_deliver)
                        ->setCellValue('J' . $kolom, $row->n_quantity_receive)
                        ->setCellValue('K' . $kolom, $row->v_unit_price);
                        
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':K'.$kolom);
           
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "SJPDelivervsReceive".$dfrom."_".$dto.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        }
      }

}

/* End of file Cform.php */
