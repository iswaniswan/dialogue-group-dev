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
    public $i_menu = '107020102';

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
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
        $area = $this->uri->segment('4');
            
    	echo $this->mmaster->data($area, $this->i_menu);
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
    
    public function editpromo(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ispb = $this->uri->segment(4);
            $iarea= $this->uri->segment(5);
            $ipromo= $this->uri->segment(6);
            $query1 = $this->db->query("select * from tm_spb_item where i_spb = '$ispb' and i_area='$iarea'");
            $this->db->select(" * from tm_spb where i_spb = '$ispb' and i_area='$iarea'");
			$query = $this->db->get();
			foreach($query->result() as $row){
			  $pesan=$row->e_notapprove;
			  $status=$row->i_notapprove;
			}
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'jmlitem'       => $query1->num_rows(),
                'ispb'          => $ispb,
                'status'        => $status,
                'pesan'         => $pesan,
                'ipromo'        => $ipromo,
                'isi'           => $this->mmaster->bacapromo($ispb,$iarea)->row(),
                'detail'        => $this->mmaster->bacadetailpromo($ispb,$iarea)->result()
            );   
        }
        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformeditpromo', $data);
    }

    public function editspb(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $ispb = $this->uri->segment(4);
            $iarea= $this->uri->segment(5);
            $ipricegroup   = $this->uri->segment(6);
            $query = $this->db->query("select * from tm_spb_item where i_spb = '$ispb' and i_area='$iarea'");
            
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'jmlitem'       => $query->num_rows(),
                'ispb'          => $ispb,
                'isi'           => $this->mmaster->baca($ispb,$iarea),
                'detail'        => $this->mmaster->bacadetail($ispb,$iarea,$ipricegroup)
            );   

            $qnilaispb  = $this->mmaster->bacadetailnilaispb($ispb,$iarea,$ipricegroup);
            if($qnilaispb->num_rows()>0){
                $row_nilaispb  = $qnilaispb->row();
                $data['nilaispb'] = $row_nilaispb->nilaispb;
            }else{
                $data['nilaispb'] = 0;
            }
            $qnilaiorderspb   = $this->mmaster->bacadetailnilaiorderspb($ispb,$iarea,$ipricegroup);
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

        $this->load->view($this->global['folder'].'/vformeditspb', $data);
    }

    public function view(){
    	$area	= $this->input->post('iarea');
        
        if($area==''){
            $area=$this->uri->segment(4);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'area'          => $area
            
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function export(){
        $area = $this->uri->segment(4);
        $query = $this->mmaster->getdata($area)->result();
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
                      ->setCellValue('A1', 'Daftar SPB Pending')
                      ->setCellValue('A3', 'No')
                      ->setCellValue('B3', 'Tanggal')
                      ->setCellValue('C3', 'Sales')
                      ->setCellValue('D3', 'Lang')
                      ->setCellValue('E3', 'Area')
                      ->setCellValue('F3', 'Kotor')
                      ->setCellValue('G3', 'Discount')
                      ->setCellValue('H3', 'Bersih')
                      ->setCellValue('I3', 'Status')
                      ->setCellValue('J3', 'Daerah')
                      ->setCellValue('K3', 'Jenis');
                      $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:K1');
                      $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:K3');
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
                            ->setCellValue('J' . $kolom, $daerah)
                            ->setCellValue('K' . $kolom, $row->e_product_groupname_short);
                            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':K'.$kolom);
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

          $nama_file = "LaporanSPBPending-Area:".$area.".xls";
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename='.$nama_file.'');
          header('Cache-Control: max-age=0');

          $writer->save('php://output');
    }
}

/* End of file Cform.php */
