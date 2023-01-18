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
    public $i_menu = '1070504';

    public function __construct(){
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
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function view(){
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        if($dfrom=='') {
            $dfrom=$this->uri->segment(4);
        }
        if($dto=='') {
            $dto=$this->uri->segment(5);
        }
        $iarea='00';
        $dfrom1 = substr($dfrom, 6, 4).substr($dfrom, 3,2 );
		$dto1 = substr($dto, 6, 4).substr($dto, 3,2 );
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'dfrom1'        => $dfrom1,
            'dto1'          => $dto1,
            'iarea'          => $iarea
        );
        $this->Logger->write('Membuka Menu Lihat Saldo Awal Forecast '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        // $dfrom      = $this->input->post('dfrom');
        // var_dump($dfrom);
        // $dto        = $this->input->post('dto');
        // var_dump($dto);
        $iarea      ='00';
        $dfrom=$this->uri->segment(4);
        $dto=$this->uri->segment(5);
        $dfrom1     = substr($dfrom, 6, 4).substr($dfrom, 3,2 );
        $dto1       = substr($dto, 6, 4).substr($dto, 3,2 );
        echo $this->mmaster->data($this->global['folder'],$dfrom1,$dto1,$dfrom,$dto,$iarea);
    } 

    public function edit(){
        $e_periode = $this->uri->segment(4);
        $dfrom     = $this->uri->segment(5);
        $dto       = $this->uri->segment(6);
        $iarea     = $this->uri->segment(7);
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'e_periode' => $e_periode,
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'iarea'     => $iarea,
            'isi'       => $this->mmaster->bacadetailsaldo($e_periode)->result()
        );
        $this->Logger->write('Input '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function getproduct(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter     = [];
            $cari       = strtoupper($this->input->get('q'));
            $data       = $this->mmaster->getproduct($cari);
            foreach($data->result() as $kuy){
                $filter[] = array(
                    'id'    => $kuy->i_product,  
                    'text'  => $kuy->i_product." - ".$kuy->e_product_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailproduct(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
        $data  = $this->mmaster->getdetailproduct($iproduct);
        echo json_encode($data->result_array());  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
       // $no = $_POST['baris'];
        $jml       = $this->input->post('jml', TRUE);
        ///var_dump($jml);
        $e_periode = $this->input->post('e_periode');   
        $this->db->trans_begin();
        $this->mmaster->delete($e_periode);
        for($i=1;$i<=$jml;$i++){
            $baris          = $this->input->post('baris'.$i, TRUE);
   	    	$e_periode		= $this->input->post('eperiode'.$i, TRUE);
   	    	$i_product 		= $this->input->post('iproduct'.$i, TRUE);
   	    	$n_saldo_awal	= $this->input->post('n_saldo_awal'.$i, TRUE);
            $n_sisa 		= $this->input->post('n_sisa'.$i, TRUE);
            $iproductgrade  ='A';
            $iproductmotif  = $this->input->post('iproductmotif'.$i, TRUE);
   	    	if($i_product != '' || $i_product != null){
                $this->mmaster->insertdetail($e_periode,$i_product,$iproductgrade,$iproductmotif,$n_saldo_awal,$n_sisa);
            } 
           // $i++;
        }
        //$this->mmaster->updatedetail($e_periode, $i_product, $n_saldo_awal, $n_sisa);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Update Data Saldo Awal Forecast : '.$e_periode);
            $data = array(
                'sukses'    => true,
                'kode'      => $e_periode
            );
        }
        $this->load->view('pesan', $data);
    }

    public function export(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $dfrom = ($this->input->post('xdfrom',TRUE) != '' ? $this->input->post('xdfrom',TRUE) : $this->uri->segment(4));
        $dto = ($this->input->post('xdto',TRUE) != '' ? $this->input->post('xdto',TRUE) : $this->uri->segment(5));
        $iarea = ($this->input->post('xiarea',TRUE) != '' ? $this->input->post('xiarea',TRUE) : $this->uri->segment(6));

        $query = $this->mmaster->cekdata($dfrom, $dto, $iarea)->result();
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
                        ->setCellValue('A1', 'Daftar SPB')
                        ->setCellValue('A2', "PT Dialogue Garmindo Utama")
                        ->setCellValue('A3', "Dari Tanggal : $dfrom Sampai Tanggal : $dto")
                        ->setCellValue('A5', 'No')
                        ->setCellValue('B5', 'Tanggal')
                        ->setCellValue('C5', 'Sales')
                        ->setCellValue('D5', 'Lang')
                        ->setCellValue('E5', 'Area')
                        ->setCellValue('F5', 'Kotor')
                        ->setCellValue('G5', 'Discount')
                        ->setCellValue('H5', 'Bersih')
                        ->setCellValue('I5', 'Status')
                        ->setCellValue('J5', 'Daerah')
                        ->setCellValue('K5', 'Jenis');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:K1');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:K2');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:K3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:K5');

            if(($row->f_spb_cancel == 't') ){
                    $status='Batal';
                }elseif(
                         ($row->i_approve1 == null) && ($row->i_notapprove == null)
                ){
                    $status='Sales';
                }elseif(
                        ($row->i_approve1 == null) && ($row->i_notapprove != null)
                ){
                    $status='Reject (sls)';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 == null) &
                        ($row->i_notapprove == null)
                ){
                    $status='Keuangan';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 == null) && 
                        ($row->i_notapprove != null)
                ){
                    $status='Reject (ar)';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                          ($row->i_store == null)
                ){
                    $status='Gudang';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                          ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
                        ($row->f_spb_siapnotagudang == 'f') && ($row->f_spb_op == 'f')
                ){
                    $status='Pemenuhan SPB';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                          ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
                        ($row->f_spb_siapnotagudang == 'f') && ($row->f_spb_op == 't') && ($row->f_spb_opclose == 'f')
                ){
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
                        ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 'f')
                ){
                    $status='Siap SJ (sales)';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                          ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') &&
                        ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj == null)
                ){
   #			 	$status='Siap SJ (gudang)';
                    $status='Siap SJ';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                          ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
                        ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj == null)
                ){
                    $status='Siap SJ';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_dkb == null) && 
                          ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
                        ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj != null)
                       ){
                    $status='Siap DKB';
            }elseif(
                         ($row->i_approve1 != null) && ($row->i_approve2 != null) && ($row->i_dkb != null) && 
                           ($row->i_store != null) && ($row->i_nota == null) && ($row->f_spb_stockdaerah == 'f') && 
                         ($row->f_spb_siapnotagudang == 't') && ($row->f_spb_siapnotasales == 't') && ($row->i_sj != null)
            ){
                     $status='Siap Nota';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                          ($row->i_store != null) && ($row->i_nota == null) && 
                        ($row->f_spb_stockdaerah == 't') && ($row->i_sj == null)
                ){
                    $status='Siap SJ';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                          ($row->i_store != null) && ($row->i_nota == null) && ($row->i_dkb == null) && 
                        ($row->f_spb_stockdaerah == 't') && ($row->i_sj != null)
                ){
                    $status='Siap DKB';
                }elseif(
                        ($row->i_approve1 != null) && ($row->i_approve2 != null) && 
                          ($row->i_store != null) && ($row->i_nota == null) && ($row->i_dkb != null) && 
                        ($row->f_spb_stockdaerah == 't') && ($row->i_sj != null)
                ){
                    $status='Siap Nota';
                }elseif(
                        ($row->i_approve1 != null) && 
                          ($row->i_approve2 != null) &&
                           ($row->i_store != null) && 
                        ($row->i_nota != null) 
                ){
                    $status='Sudah dinotakan';			  
                }elseif(($row->i_nota != null)){
                    $status='Sudah dinotakan';
            }else{
                    $status='Unknown';		
            }
           $bersih	= $row->v_spb-$row->v_spb_discounttotal;
 #          $bersih	= number_format($row->v_spb-$row->v_spb_discounttotal);
 #			$row->v_spb	= number_format($row->v_spb);
 #   		$row->v_spb_discounttotal	= number_format($row->v_spb_discounttotal);  
           if($row->f_spb_stockdaerah=='t'){
             $daerah='Ya';
           }else{
             $daerah='Tidak';
           }

           if($row->i_product_group=='00') {
             $jenis='Home';
           } elseif($row->i_product_group=='01') {
             $jenis='Bd';
           } else {
             $jenis='NB';
           }

          $kolom = 6;
          $nomor = 1;
          $nol = 0;
          foreach($query as $row) {
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
                        ->setCellValue('K' . $kolom, $jenis);
                        
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':K'.$kolom);
           
                 $kolom++;
                 $nomor++;
        }
        $writer = new Xls($spreadsheet);
        $nama_file = "SaldoAwalForecast".$dfrom."_".$dto.".xls";
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

}
/* End of file Cform.php */
