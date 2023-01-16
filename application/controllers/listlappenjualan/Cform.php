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
    public $i_menu = '10804';

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
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'bulan'     => date('m'),
            'tahun'     => date('Y')

        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);

    }

    function data(){
    	$dfrom = $this->uri->segment('4');
        $dto = $this->uri->segment('5');
        
        $tmp=explode('-',$dfrom);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $from=$yy.'-'.$mm.'-'.$dd;

        $tmp=explode('-',$dto);
        $dd=$tmp[0];
        $mm=$tmp[1];
        $yy=$tmp[2];
        $to=$yy.'-'.$mm.'-'.$dd;
            
    	echo $this->mmaster->data($from,$to);
    }

    public function view(){
    	$dfrom = $this->input->post('dfrom');
    	$dto   = $this->input->post('dto');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function proses(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $bulan      = $this->input->post('iperiodebl', TRUE);
        $tahun      = $this->input->post('iperiodeth', TRUE);
        $periode    = $tahun.$bulan;
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'periode'  => $periode
        );
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function approve(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $jml = $this->input->post('jml', TRUE);
        $ifkom= $this->input->post('ifakturkomersial', TRUE);
        $adatmp=true;
        if($ifkom!=''){
            $this->db->trans_begin();
			for($i=1;$i<=$jml;$i++){
				$cek=$this->input->post('chk'.$i, TRUE);
				if($cek=='on'){
                    $dbbk = $this->input->post('tanggal'.$i, TRUE);
                    if($dbbk!=''){
			            $tmp=explode("-",$dbbk);
			            $th=$tmp[0];
			            $bl=$tmp[1];
			            $hr=$tmp[2];
			            $dbbk=$th."-".$bl."-".$hr;
                        $thbl=$th.$bl;
                        $tbl=substr($th,2,2).$bl;

			        }
                    $tmp="FK-".$tbl."-";
                    $ibbk = $this->input->post('bbk'.$i, TRUE);
                    $ifakturkomersial	= $tmp.$this->input->post('ifakturkomersial', TRUE);
                    $nilai = $this->input->post('nilai'.$i, TRUE);
                    $nilai = str_replace(",","",$nilai);
                    $ada=$this->mmaster->cekfaktur($ifakturkomersial);
                    if(!$ada || $adatmp == false){
                        $this->mmaster->insertfkom($ifakturkomersial,$ibbk);
                      $adatmp=false;
                    }
                }
                
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ifkom);
                
                $data = array(
                    'sukses'    => true,
                    'kode'      => $tmp.$ifkom
                );
            }
        }
        
        $this->load->view('pesan', $data);
    }

    public function export(){
        $dfrom = $this->uri->segment(4);
        $dto   = $this->uri->segment(5);
        $query = $this->mmaster->getAll($dfrom, $dto)->result();
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
        ->setName('Arial')
        ->setSize(10);
        foreach(range('A','N') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
    }
          /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
          $spreadsheet->getActiveSheet()->mergeCells("A1:N1");
          $spreadsheet->getActiveSheet()->mergeCells("A2:N2");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'LAPORAN BUKU PENJUALAN')
                      ->setCellValue('A2', "Laporan Penjualan Periode : $dfrom sd $dto")
                      ->setCellValue('A5', 'No')
                      ->setCellValue('B5', 'Tanggal Nota')
                      ->setCellValue('C5', 'Kode Sales')
                      ->setCellValue('D5', 'Kode Lang')
                      ->setCellValue('E5', 'No Nota')
                      ->setCellValue('F5', 'Nilai Kotor')
                      ->setCellValue('G5', 'Potongan')
                      ->setCellValue('H5', 'PPN')
                      ->setCellValue('I5', 'DPP')
                      ->setCellValue('J5', 'No Faktur')
                      ->setCellValue('K5', 'Status')
                      ->setCellValue('L5', 'Jatuh Tempo')
                      ->setCellValue('M5', 'Nilai Bersih')
                      ->setCellValue('N5', 'PKP');

          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:N1');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:N2');
          $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:N5');

        $kolom = 6;
        $nomor = 1;
        $nol = 0;
        foreach($query as $row) {
            if($row->d_nota!=''){
                $tmp=explode('-',$row->d_nota);
                $tgl=$tmp[2];
                $bln=$tmp[1];
                $thn=$tmp[0];
                $row->d_nota=$tgl.'-'.$bln.'-'.$thn;
            }
            if($row->d_jatuh_tempo!=''){
                      $tmp=explode('-',$row->d_jatuh_tempo);
                      $tgl=$tmp[2];
                      $bln=$tmp[1];
                      $thn=$tmp[0];
                      $row->d_jatuh_tempo=$tgl.'-'.$bln.'-'.$thn;
            }
            if($row->f_customer_pkp=='t')
            {
              $status='Ya';
            }else{
              $status='Tidak';
            }
            $dpp=(round($row->v_nota_netto))/1.1;
            $vppn=(round($dpp))*0.1;
            if($row->f_nota_cancel == 't'){
                $vgross = 0;
                $vdiscount = 0;
                $vppn = 0;
                $dpp = 0;
                $vnett0=0;
            }else{
              $vgross = $row->v_nota_gross;
              $vdiscount = $row->v_nota_discounttotal;
              $vppn = $vppn;
              $dpp = $dpp;
              $vnetto = $row->v_nota_netto;
            }
                $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $kolom, $nomor)
                            ->setCellValue('B' . $kolom, $row->d_nota)
                            ->setCellValue('C' . $kolom, $row->i_salesman)
                            ->setCellValue('D' . $kolom, $row->i_customer)
                            ->setCellValue('E' . $kolom, $row->i_nota)
                            ->setCellValue('F' . $kolom, $vgross)
                            ->setCellValue('G' . $kolom, $vdiscount)
                            ->setCellValue('H' . $kolom, $vppn)
                            ->setCellValue('I' . $kolom, $dpp)
                            ->setCellValue('J' . $kolom, $row->i_faktur_komersial)
                            ->setCellValue('K' . $kolom, $status)
                            ->setCellValue('L' . $kolom, $row->d_jatuh_tempo)
                            ->setCellValue('M' . $kolom, $row->v_nota_netto)
                            ->setCellValue('N' . $kolom, $row->f_customer_pkp);
                            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':N'.$kolom);
                            $spreadsheet->getActiveSheet()
                            ->getStyle('M'.$kolom)
                            ->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                            $spreadsheet->getActiveSheet()
                            ->getStyle('F'.$kolom)
                            ->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                            $spreadsheet->getActiveSheet()
                            ->getStyle('G'.$kolom)
                            ->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                            $spreadsheet->getActiveSheet()
                            ->getStyle('H'.$kolom)
                            ->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                            $spreadsheet->getActiveSheet()
                            ->getStyle('I'.$kolom)
                            ->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);


               $kolom++;
               $nomor++;

          }

          $writer = new Xls($spreadsheet);

          $nama_file = "Buku_Penjualan".$dfrom."_".$dto.".xls";
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
