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
    public $i_menu = '10809';

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
        $dfrom = ($this->input->post('dfrom',TRUE) != '' ? $this->input->post('dfrom',TRUE) : $this->uri->segment(4));
        $dto = ($this->input->post('dto',TRUE) != '' ? $this->input->post('dto',TRUE) : $this->uri->segment(5));
        $area = ($this->input->post('iarea',TRUE) != '' ? $this->input->post('iarea',TRUE) : $this->uri->segment(6));
        $query = $this->mmaster->getAll($dfrom,$dto,$area)->result();
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
        foreach(range('A','H') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
          $spreadsheet->getActiveSheet()->mergeCells("A1:H1");
          $spreadsheet->getActiveSheet()->mergeCells("A2:H2");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'LAPORAN TANDA TERIMA PAJAK')
                      ->setCellValue('A2', "Laporan Tanda Terima Pajak Tanggal : $dfrom sd $dto & Area : $area")
                      ->setCellValue('A5', 'No')
                      ->setCellValue('B5', 'NAMALANG')
                      ->setCellValue('C5', 'NAMAPKP')
                      ->setCellValue('D5', 'NODOK')
                      ->setCellValue('E5', 'NOSERI')
                      ->setCellValue('F5', 'PPN')
                      ->setCellValue('G5', 'TGLPJK')
                      ->setCellValue('H5', 'CHECK');

          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H1');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:H2');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:H5');

          $kolom = 6;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
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
  
            if($row->v_nota_discount!=$row->v_nota_discounttotal){
              $row->v_nota_discount=$row->v_nota_discount1+$row->v_nota_discount2+$row->v_nota_discount3+$row->v_nota_discount4;
            }
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $kolom, $nomor)
                        ->setCellValue('B' . $kolom, $row->e_customer_name)
                        ->setCellValue('C' . $kolom, $row->e_customer_pkpname)
                        ->setCellValue('D' . $kolom, substr($row->i_nota,8,7))
                        ->setCellValue('E' . $kolom, $row->i_seri_pajak)
                        ->setCellValue('F' . $kolom, $row->v_nota_ppn)
                        ->setCellValue('G' . $kolom, $row->d_pajak);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':H'.$kolom);
            $spreadsheet->getActiveSheet()
                        ->getStyle('F'.$kolom)
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "TandaTerimaPajak".$dfrom."_".$dto."_".$area.".xls";
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
