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
    public $i_menu = '107020410';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $iarea     = $this->mmaster->cekarea($username, $idcompany);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'iarea'  => $iarea,
            'area'   => $this->mmaster->bacaarea($username, $idcompany, $iarea)
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);

    }

    public function export(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $dfrom = ($this->input->post('dfrom',TRUE) != '' ? $this->input->post('dfrom',TRUE) : $this->uri->segment(4));
        $dto = ($this->input->post('dto',TRUE) != '' ? $this->input->post('dto',TRUE) : $this->uri->segment(5));
        $iarea = ($this->input->post('iarea',TRUE) != '' ? $this->input->post('iarea',TRUE) : $this->uri->segment(6));
		    if($dfrom!=''){
          $tmp=explode("-",$dfrom);
          $th=$tmp[2];
          $bl=$tmp[1];
          $hr=$tmp[0];
          $dfrom =$hr."-".$bl."-".$th;
          $thdfromkurang=$th-1;
          $dfromsebelumnya =$hr."-".$bl."-".$thdfromkurang;
          $thskrng=$th;
        }
  
        if($dto!=''){
          $tmp=explode("-",$dto);
          $th=$tmp[2];
          $bl=$tmp[1];
          $hr=$tmp[0];
          $dto =$hr."-".$bl."-".$th;
          $thdtokurang=$th-1;
          $dtosebelumnya =$hr."-".$bl."-".$thdtokurang;
          $thnsebelumnya=$thdtokurang;
        }
        $data = array(
          'dfrom'           => $dfrom,
          'dto'             => $dto,
          'thskrng'         => $thskrng,
          'thnsebelumnya'   => $thnsebelumnya,
          'dfromsebelumnya' => $dfromsebelumnya,
          'dtosebelumnya'   => $dtosebelumnya
        );

        /****TARIK DATA****/
          $this->db->query(" DELETE FROM tt_trenditemtoko ");
          $this->db->query(" ALTER SEQUENCE tt_trenditemtoko_id_seq RESTART WITH 1 ");
          $this->db->query(" 	INSERT INTO tt_trenditemtoko (i_product, e_product_name, i_area, e_area_name, 
					e_customer_name, i_customer, e_customer_classname, e_product_categoryname, vnota, 
					qnota, oa, prevvnota, prevqnota, prevoa)
					SELECT * FROM f_sales_report_trenditemtoko('$dfrom','$dto','$dfromsebelumnya','$dtosebelumnya') ");
        /******************/
        $query = $this->mmaster->baca($iarea)->result();
        $spreadsheet = new Spreadsheet;
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        $conditional3 = new Conditional();
        $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray( 
          [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE ] ); 

      $sharedStyle1->applyFromArray(
          [
          'alignment' => [
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          ],
          'borders' => [
            'bottom' => ['borderStyle' => Border::BORDER_THIN],
            'right' => ['borderStyle' => Border::BORDER_THIN],
          ],
        ]
      );

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
        ->setSize(5);
        foreach(range('A','P') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Area')
                      ->setCellValue('B1', 'Kode Pelanggan')
                      ->setCellValue('C1', 'Pelanggan')
                      ->setCellValue('D1', 'Klasifikasi')
                      ->setCellValue('E1', 'Kd Produk')
                      ->setCellValue('F1', 'Produk')
                      ->setCellValue('G1', 'OA'.$thnsebelumnya)
                      ->setCellValue('H1', 'OA'.$thskrng)
                      ->setCellValue('I1', '%')
                      ->setCellValue('J1', 'Sales QTY(Unit)'.$thnsebelumnya)
                      ->setCellValue('K1', 'Sales QTY(Unit)'.$thskrng)
                      ->setCellValue('L1', '%')
                      ->setCellValue('M1', 'NET SALES(Rp.)'.$thnsebelumnya)
                      ->setCellValue('N1', 'NET SALES(Rp.)'.$thskrng)
                      ->setCellValue('O1', '%')
                      ->setCellValue('P1', '% CTR NET SALES(RP.)');

          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:P1');

          $i=7;
				  $j=7;
				  $xarea='';
				  $saldo=0;
				  $grwoa=0;
				  $coba=0;
          $totnota=0;
          $kolom = 2;
          $nomor = 1;
          $nol = 0;
          foreach($query as $rew){
            $coba+=$rew->vnota;
          }
          $totnota=$coba;
          foreach($query as $row) {
            if(($row->vnota==0) && ($row->qnota==0)){
                $totvnota=0;
                $totqnota=0;
                $totvnota=$totvnota+0;
                $totqnota=$totqnota+0;
            }else{
                $totvnota=0;
                $totqnota=0;
                $totvnota=$totvnota+$row->vnota;
                $totqnota=$totqnota+$row->qnota;
            }
            $grwqty=0;
            $grwrp=0;
                
            $totrp=0;
            $ctrrp=0;
            $totprevoa=0;
            $totoa=0;
            $totprevqnota=0;
            $totprevvnota=0;
            $totctrrp=0;
            $totgrwoa=0;
            $totgrwqty=0;
            $totgrwrp=0;
            $totpersenvnota=0;
            $ctrrp=0;
        
            $totrp=$totnota;
            if($totvnota==0){
              $persenvnota=0;
            }else{
              $persenvnota=($row->vnota/$totvnota)*100;
            }
            $totpersenvnota=$totpersenvnota+$persenvnota;
  
            if ($row->prevoa == 0) {
                $grwoa = 0;
            } else { //jika pembagi tidak 0
                $grwoa = (($row->oa-$row->prevoa)/$row->prevoa)*100;
            }
  
            if ($row->prevqnota == 0) {
                $grwqty = 0;
            } else { //jika pembagi tidak 0
                $grwqty = (($row->qnota-$row->prevqnota)/$row->prevqnota)*100;
            }
  
            if ($row->prevvnota == 0) {
                $grwrp = 0;
            } else { //jika pembagi tidak 0
                $grwrp = (($row->vnota-$row->prevvnota)/$row->prevvnota)*100;
            }
  
            if(($row->prevoa!=0)&&($row->oa!=0)&&($row->prevqnota!=0)&&($row->prevvnota!=0)&&($row->vnota!=0)){	
              
              $ctrrp= ($row->vnota/$totrp)*100;
              $totprevoa=$totprevoa+$row->prevoa;
              $totoa=$totoa+$row->oa;
              $totprevqnota=$totprevqnota+$row->prevqnota;
              $totprevvnota=$totprevvnota+$row->prevvnota;
            
            }else{
  
              $totprevoa=$totprevoa+0;
              $totoa=$totoa+0;
              $totprevqnota=$totprevqnota+0;
              $totprevvnota=$totprevvnota+0;
  
            }
              $totctrrp=$totctrrp+$ctrrp;
  
            //===============PROSESII==================//
  
            if ($totprevoa == 0) {
                $totgrwoa = 0;
            } else { //jika pembagi tidak 0
                $totgrwoa = (($totoa-$totprevoa)/$totprevoa)*100;
            }
  
            if ($totprevqnota == 0) {
                $totgrwqty = 0;
            } else { //jika pembagi tidak 0
                $totgrwqty = (($totqnota-$totprevqnota)/$totprevqnota)*100;
            }
  
            if ($totprevvnota == 0) {
                $totgrwrp = 0;
            } else { //jika pembagi tidak 0
                $totgrwrp = (($totvnota-$totprevvnota)/$totprevvnota)*100;
            }

            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $row->i_area.'-'.$row->e_area_name)
                        ->setCellValue('B' . $kolom, $row->i_customer)
                        ->setCellValue('C' . $kolom, $row->e_customer_name)
                        ->setCellValue('D' . $kolom, $row->e_customer_classname)
                        ->setCellValue('E' . $kolom, $row->i_product)
                        ->setCellValue('F' . $kolom, $row->e_product_name)
                        ->setCellValue('G' . $kolom, $row->prevoa)
                        ->setCellValue('H' . $kolom, $row->oa)
                        ->setCellValue('I' . $kolom, $grwoa. "%")
                        ->setCellValue('J' . $kolom, $row->prevqnota)
                        ->setCellValue('K' . $kolom, $row->qnota)
                        ->setCellValue('L' . $kolom, $grwqty. "%")
                        ->setCellValue('M' . $kolom, $row->prevvnota)
                        ->setCellValue('N' . $kolom, $row->vnota)
                        ->setCellValue('O' . $kolom, $grwrp."%")
                        ->setCellValue('P' . $kolom, $ctrrp. "%");
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':P'.$kolom);
            $spreadsheet->getActiveSheet()
                        ->getStyle('L'.$kolom)
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()
                        ->getStyle('M'.$kolom)
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()
                        ->getStyle('N'.$kolom)
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            $spreadsheet->getActiveSheet()
                        ->getStyle('O'.$kolom)
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "sales_report_by_trend_toko_per_item".$dfrom."_".$dto.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
      }
}
/* End of file Cform.php */
