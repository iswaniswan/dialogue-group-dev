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
    public $i_menu = '1070412';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'      => $this->mmaster->bacaarea()
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
        $dfrom=$this->uri->segment(4);
        $dto=$this->uri->segment(5);
        $area=$this->uri->segment(6);
            
    	echo $this->mmaster->data($dfrom,$dto,$area,  $this->global['folder'], $this->i_menu);
    }
    
    public function view(){
    	$area	= $this->input->post('iarea');
        $dfrom	= $this->input->post('dfrom');
        $dto	= $this->input->post('dto');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($area==''){
            $area=$this->uri->segment(6);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'area'          => $area
            
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ispb           = $this->uri->segment(4);
            $iarea          = $this->uri->segment(5);
            $dfrom          = $this->uri->segment(6);
            $dto            = $this->uri->segment(7);
            $ipricegroup    = $this->uri->segment(8);
            $query          = $this->db->query("select * from tm_spbkonsinyasi_item where i_spb = '$ispb' and i_area='$iarea'");
            
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'jmlitem'       => $query->num_rows(),
                'ispb'          => $ispb,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'iarea'         => $iarea,
                'ipricegroup'   => $ipricegroup,
                'iperiode'      => 'edit',
                'isi'           => $this->mmaster->baca($ispb,$iarea)->row(),
                'detail'        => $this->mmaster->bacadetail($ispb,$iarea,$ipricegroup)->result()
            );   

            $qnilaispb  = $this->mmaster->bacadetailnilaispb($ispb,$iarea);
            if($qnilaispb->num_rows()>0){
                $row_nilaispb  = $qnilaispb->row();
                $data['nilaispb'] = $row_nilaispb->nilaispb;
            }else{
                $data['nilaispb'] = 0;
            }
            $qnilaiorderspb   = $this->mmaster->bacadetailnilaiorderspb($ispb,$iarea);
            if($qnilaiorderspb->num_rows()>0){
               $row_nilaiorderspb   = $qnilaiorderspb->row();
               $data['nilaiorderspb']  = $row_nilaiorderspb->nilaiorderspb;
            }else{
               $data['nilaiorderspb']  = 0;
            }
            $qeket   = $this->db->query(" SELECT e_remark1 as keterangan from tm_spb where i_spb ='$ispb' and i_area='$iarea' ");
            if($qeket->num_rows()>0){
               $row_eket   = $qeket->row();
               $data['keterangan']  = $row_eket->keterangan;
            }
        }

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $ispb               = $this->input->post('ispb', TRUE);
        $iarea              = $this->input->post('iarea', TRUE);
        $nspbdiscount1      = $this->input->post('ncustomerdiscount1',TRUE);
        $vspbdiscount1      = $this->input->post('vcustomerdiscount1',TRUE);
        $vspbdiscounttotal  = $this->input->post('vspbdiscounttotal',TRUE);
        $vspb               = $this->input->post('vspb',TRUE);
        $nspbdiscount1      = str_replace(',','',$nspbdiscount1);
        $vspbdiscount1      = str_replace(',','',$vspbdiscount1);
        $vspbdiscounttotal  = str_replace(',','',$vspbdiscounttotal);
        $vspb               = str_replace(',','',$vspb);
        if(($iarea!='') && ($ispb!='')){
            $benar="false";
            $this->mmaster->updateheader($ispb, $iarea, $nspbdiscount1, $vspbdiscount1, $vspbdiscounttotal, $vspb);
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ispb);
            
            $data = array(
                'sukses'    => true,
                'kode'      => $ispb
            );
        }
        $this->load->view('pesan', $data);
    } 

    public function export(){
        $dfrom = $this->uri->segment(4);
        $dto   = $this->uri->segment(5);
        $iarea = $this->uri->segment(6);
        
        $query = $this->mmaster->getdata($iarea,$dfrom,$dto)->result();
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

          [
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
        foreach(range('A','K') as $columnID) {
          $spreadsheet->getActiveSheet()
          ->getColumnDimension($columnID)
          ->setAutoSize(true);
          $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        }
          $spreadsheet->getActiveSheet()->mergeCells("A1:K1");
          $spreadsheet->setActiveSheetIndex(0)
                      ->setCellValue('A1', 'Daftar SPB')
                      ->setCellValue('A3', 'No')
                      ->setCellValue('B3', 'Tanggal')
                      ->setCellValue('C3', 'Sales')
                      ->setCellValue('D3', 'Lang')
                      ->setCellValue('E3', 'Area')
                      ->setCellValue('F3', 'Kotor')
                      ->setCellValue('G3', 'Discount')
                      ->setCellValue('H3', 'Bersih')
                      ->setCellValue('I3', 'Status')
                      ->setCellValue('J3', 'Daerah');
                      $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:J1');
                      $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:J3');
        $kolom = 4;
        $nomor = 1;
        $nol = 0;
        foreach($query as $row) {
            if(($row->f_spb_cancel == 't') ){
                $status='Batal';
            }elseif(($row->i_approve1 == null) && ($row->i_notapprove == null)){
                $status='Sales';
            }elseif(($row->i_approve1 == null) && ($row->i_notapprove != null)){
                $status='Reject (sls)';
            }elseif(($row->i_approve1 != null) && ($row->i_approve2 == null) && ($row->i_notapprove == null)){
                $status='Keuangan';
            }elseif(($row->i_approve1 != null) && ($row->i_approve2 == null) && ($row->i_notapprove != null)){
                $status='Reject (ar)';
            }elseif(($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_store == null)){
                $status='Gudang';
            }elseif(($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && ($row->f_spb_siapnotagudang == 'f') && ($row->f_spb_op == 'f')){
                $status='Pemenuhan SPB';
            }elseif(($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && ($row->f_spb_siapnotagudang == 'f') && ($row->f_spb_op == 't') && ($row->f_spb_opclose == 'f')){
                $status='Proses OP';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
                    ($row->f_spb_siapnotagudang == 'f') && ($row->f_spb_siapnotasales == 'f') && ($row->f_spb_opclose == 't')
                   ){
                $status='OP Close';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
                    ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 'f')){
                $status='Siap SJ (sales)';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
                    ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj == null)){
                $status='Siap SJ';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
                    ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj == null)){
                $status='Siap SJ';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_dkb == null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
                    ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj != null)){
                $status='Siap DKB';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_dkb != null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
                    ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj != null)){
                $status='Siap Nota';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && 
                    ($row->f_spb_stockdaerah == 't') && ($row->i_sj == null)){
                $status='Siap SJ';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && ($row->i_dkb == null) && 
                    ($row->f_spb_stockdaerah == 't') && ($row->i_sj != null)){
                $status='Siap DKB';
            }elseif(
                    ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                    ($row->i_store != null) && ($row->i_nota == null) && ($row->i_dkb != null) && 
                    ($row->f_spb_stockdaerah == 't') && ($row->i_sj != null)){
                $status='Siap Nota';
            }else{
                $status='Unknown';		
            }

            $bersih	= $row->v_spb-$row->v_spb_discounttotal;  
            if($row->f_spb_stockdaerah=='t')
            {
              $daerah='Ya';
            }else{
              $daerah='Tidak';
            }
                $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $kolom, $row->i_spb)
                            ->setCellValue('B' . $kolom, $row->d_spb)
                            ->setCellValue('C' . $kolom, $row->i_salesman)
                            ->setCellValue('D' . $kolom, '('.$row->i_customer.') '.$row->e_customer_name)
                            ->setCellValue('E' . $kolom, "'".$row->i_area)
                            ->setCellValue('F' . $kolom, $row->v_spb)
                            ->setCellValue('G' . $kolom, $row->v_spb_discounttotal)
                            ->setCellValue('H' . $kolom, $bersih)
                            ->setCellValue('I' . $kolom, $status)
                            ->setCellValue('J' . $kolom, $daerah);
                            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':J'.$kolom);
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
               $kolom++;
               $nomor++;

          }

          $writer = new Xls($spreadsheet);

          $nama_file = "LaporanSPBKonsinyasi-Area:".$iarea.".xls";
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename='.$nama_file.'');
          header('Cache-Control: max-age=0');

          $writer->save('php://output');
    }
}

/* End of file Cform.php */
