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
    public $i_menu = '10806';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);

    }

    function dataarea(){
      $filter = [];
      if($this->input->get('q') != '') {
          $filter = [];
          $cari = strtoupper($this->input->get('q'));
          $company = $this->session->userdata('id_company');
          $username = $this->session->userdata('username');
          $this->db->select(" * from tr_area where (upper(i_area) like '%$cari%' or upper(e_area_name) like '%$cari%') and (i_area in ( select i_area from tm_user_area where i_user='$username') )",false);
          $data = $this->db->get();
          foreach($data->result() as  $area){
                  $filter[] = array(
                  'id' => $area->i_area,  
                  'text' => $area->i_area.'-'.$area->e_area_name
              );
          }
          echo json_encode($filter);
      } else {
          echo json_encode($filter);
      }
    }

   /* public function getaja(){
      $periode = ($this->input->post('iperiode',TRUE) != '' ? $this->input->post('iperiode',TRUE) : $this->uri->segment(4));
      echo $periode;
      $area = ($this->input->post('iarea',TRUE) != '' ? $this->input->post('iarea',TRUE) : $this->uri->segment(5));
      echo $area;
    }*/

    public function export(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $periode = ($this->input->post('iperiode',TRUE) != '' ? $this->input->post('iperiode',TRUE) : $this->uri->segment(4));
        $area = ($this->input->post('iarea',TRUE) != '' ? $this->input->post('iarea',TRUE) : $this->uri->segment(5));
        $a=substr($periode,2,2);
	      $b=substr($periode,4,2);
		    //$peri=mbulan($b)." - ".$a;
        if($area=='NA'){
          $no='FP-'.$a.$b.'-%';
        }else{
          $no='FP-'.$a.$b.'-'.$area.'%';
        }
        $query = $this->mmaster->getAll($no)->result();
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
        ->setSize(9);
        foreach(range('A','V') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
          $spreadsheet->getActiveSheet()->mergeCells("A1:V1");
          $spreadsheet->getActiveSheet()->mergeCells("A2:V2");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'LAPORAN PJNA')
                      ->setCellValue('A2', "Laporan PJNA Periode : $periode & Area : $area")
                      ->setCellValue('A5', 'KODETRAN,C,2')
                      ->setCellValue('B5', 'NODOK,C,7')
                      ->setCellValue('C5', 'NOFAKTUR,C,6')
                      ->setCellValue('D5', 'PENGGANTI,l')
                      ->setCellValue('E5', 'NOSERI,C,6')
                      ->setCellValue('F5', 'TGLDOK,D')
                      ->setCellValue('G5', 'TGLPJK,D')
                      ->setCellValue('H5', 'WILA,C,2')
                      ->setCellValue('I5', 'RAYON,C,2')
                      ->setCellValue('J5', 'NOLANG,C,3')
                      ->setCellValue('K5', 'KODESALES,C,2')
                      ->setCellValue('L5', 'GROSS,N,10,0')
                      ->setCellValue('M5', 'POTONG,N,10,0')
                      ->setCellValue('N5', 'DPP,N,10,0')
                      ->setCellValue('O5', 'PPN,N,10,0')
                      ->setCellValue('P5', 'NET,N,10,0')
                      ->setCellValue('Q5', 'TGLCETAK,D')
                      ->setCellValue('R5', 'JUMCETAK,N,6,0')
                      ->setCellValue('S5', 'TGLBUAT,D')
                      ->setCellValue('T5', 'JAMBUAT,C,8')
                      ->setCellValue('U5', 'TGLUBAH,D')
                      ->setCellValue('V5', 'JAMUBAH,C,8');

          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:V1');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:V2');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:V5');

          $kolom = 6;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
            if($row->d_nota!=''){
              $tmp=explode('-',$row->d_nota);
              $hr=$tmp[2];
              $bl=$tmp[1];
              $th=$tmp[0];
              $row->d_nota=$hr.'-'.$bl.'-'.$th;
            }
            if($row->d_pajak!=''){
              $tmp=explode('-',$row->d_pajak);
              $hr=$tmp[2];
              $bl=$tmp[1];
              $th=$tmp[0];
              $row->d_pajak=$hr.'-'.$bl.'-'.$th;
            }
            $row->v_nota_dpp=round($row->v_nota_netto/1.1);
            $row->v_nota_ppn=round($row->v_nota_netto/1.1*0.1);
            if($row->f_customer_pkp=='t'){
              $kodetran='04';
            }else{
              $kodetran='07';
            }
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $kodetran)
                        ->setCellValue('B' . $kolom, $row->i_nota)
                        ->setCellValue('C' . $kolom, $row->i_faktur_komersial)
                        ->setCellValue('D' . $kolom, $row->f_pajak_pengganti)
                        ->setCellValue('E' . $kolom, $row->i_seri_pajak)
                        ->setCellValue('F' . $kolom, $row->d_nota)
                        ->setCellValue('G' . $kolom, $row->d_pajak)
                        ->setCellValue('H' . $kolom, $row->i_area)
                        ->setCellValue('I' . $kolom, null)
                        ->setCellValue('J' . $kolom, substr($row->i_customer,2,3))
                        ->setCellValue('K' . $kolom, $row->i_salesman)
                        ->setCellValue('L' . $kolom, $row->v_nota_gross)
                        ->setCellValue('M' . $kolom, $row->v_nota_discounttotal)
                        ->setCellValue('N' . $kolom, $row->v_nota_dpp)
                        ->setCellValue('O' . $kolom, $row->v_nota_ppn)
                        ->setCellValue('P' . $kolom, $row->v_nota_netto)
                        ->setCellValue('Q' . $kolom, $row->d_pajak_print)
                        ->setCellValue('R' . $kolom, $row->n_pajak_print)
                        ->setCellValue('S' . $kolom, null)
                        ->setCellValue('T' . $kolom, null)
                        ->setCellValue('U' . $kolom, null)
                        ->setCellValue('V' . $kolom, null);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':V'.$kolom);
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
            $spreadsheet->getActiveSheet()
                        ->getStyle('P'.$kolom)
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "PJNA".$periode."_".$area.".xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename='.$nama_file.'');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
          // Proses file excel    
          /*header('Content-Type: application/vnd.ms-excel');    
          header('Content-Disposition: attachment; filename='.$nama_file.''); // Set nama file excel nya    
          header('Cache-Control: max-age=0');
          $writer = IOFactory::createWriter($spreadsheet, 'Excel5');
          $writer->save('php://output','w');*/
      }
}
/* End of file Cform.php */
