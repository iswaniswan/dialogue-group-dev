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
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20701';

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
        

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'customer'      => $this->mmaster->bacacustomer()
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function getbarang(){
        $ikodemaster = $this->input->post('ikodemaster');
        $query = $this->mmaster->getbarang($ikodemaster);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_product_motif." >".$row->i_product_motif."-".$row->e_product_basename."</option>";
            }
            $kop  = "<option value=\"BRG\" selected>  Semua Barang  ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

     public function load(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom      = $this->input->post('dfrom', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonthfrom = $year.$month;
            $dfrom = $year.'-'.$month.'-'.$day;
        }

        $filename = "Forecast_Distributor_".$yearmonthfrom."_".$icustomer.".xls";
        


        $config = array(
            'upload_path'   => "./import/fcdistributor",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );
    

        $this->load->library('upload',$config); 
         
        if($this->upload->do_upload("userfile")){
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File Forecast Distributor Periode : '.$yearmonthfrom.'_'.$icustomer);
            if ($dfrom =="" || $dfrom == "null" || $dfrom == null || $icustomer == "" || $icustomer == null || $icustomer == "null") {
                $param =  array(
                    'dfrom'  => $dfrom,
                    'icustomer' => $icustomer,
                    'status' => 'gagal'
                );
            } else {
                $param =  array(
                    'dfrom'  => $dfrom,
                    'icustomer' => $icustomer,
                    'status' => 'berhasil'
                );
            }
            echo json_encode($param);
        }
        else{
            $param =  array(
                'status' => 'gagal'
            );
            echo json_encode($param);
        }
    }

    public function views(){
        $dfrom      = $this->input->post('dfrom', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);

        if($dfrom == ''){
            $dfrom = $this->uri->segment(4);
        }
        
        
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonthfrom = $year.$month;
            $dfrom = $year.'-'.$month.'-'.$day;
        }

        if($icustomer == ''){
            $icustomer = $this->uri->segment(5);
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $dfrom,
            'icustomer'     => $icustomer,
            'yearmonthfrom' => $yearmonthfrom,
            'fc'            => $this->mmaster->bacafc($dfrom, $icustomer)->row(),
            'isi'           => $this->mmaster->cek_dataheader($yearmonthfrom, $icustomer)->row(),
            'detail'        => $this->mmaster->cek_datadetail($yearmonthfrom)->result()
            
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom = $this->input->post("dfrom",true);
        if($dfrom == ''){
            $dfrom = $this->uri->segment(4);
        }

        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dfrom = $year.'-'.$month.'-'.$day;
        }

        $icustomer = $this->input->post("icustomer",true);
        if($icustomer == ''){
            $icustomer = $this->uri->segment(5);
        }

        $query = $this->mmaster->cek_datadetail($yearmonth);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $dfrom,
            'icustomer'     => $icustomer,
            'yearmonthfrom' => $yearmonth,
            'jmlitem'       => $query->num_rows(),
            'fc'            => $this->mmaster->bacafc($dfrom, $icustomer)->row(),
            'barang'        => $this->mmaster->getbarang()->row(),
            'isi'           => $this->mmaster->cek_dataheader($yearmonth, $icustomer)->row(),
            'detail'        => $this->mmaster->cek_datadetail($yearmonth)->result()
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function loadview(){
        
        $dfrom          = $this->uri->segment(4);
        $icustomer      = $this->uri->segment(5);
        $query = $this->db->query("SELECT e_customer_name FROM tr_customer WHERE i_customer='$icustomer'", FALSE)->result();
        foreach($query as $row){
            $ecustomer =  $row->e_customer_name;
        }
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[2];
            $month = $tmp[1];
            $year  = $tmp[0];
            $yearmonth = $year.$month;
            $dfrom = $year.'-'.$month.'-'.$day;
        }
        $filename       = "Forecast_Distributor_".$yearmonth."_".$icustomer.".xls";
        $inputFileName  = './import/fcdistributor/'.$filename;
        $spreadsheet    = IOFactory::load($inputFileName);
        $worksheet      = $spreadsheet->getActiveSheet();
        $sheet          = $spreadsheet->getSheet(0);
        $hrow           = $sheet->getHighestDataRow('A');
        $aray           = array();
        for ($n=6; $n<=$hrow; $n++){
            $iproduct       = $spreadsheet->getActiveSheet()->getCell('A'.$n)->getValue();
            $eproductname   = $spreadsheet->getActiveSheet()->getCell('B'.$n)->getValue();
            $icolor         = $spreadsheet->getActiveSheet()->getCell('C'.$n)->getValue();
            $nquantity      = $spreadsheet->getActiveSheet()->getCell('D'.$n)->getValue();
            $aray[] = array( 
                'iproduct'       => $iproduct,
                'eproductname'   => $eproductname,
                'icolor'         => $icolor,
                'nquantity'      => $nquantity
            );
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Input ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $dfrom,
            'yearmonth'     => $yearmonth,
            'icustomer'     => $icustomer,
            'ecustomer'     => $ecustomer,
            'data2'         => $aray
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function dataproduct(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->bacaproduct($cari);
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_product_motif,
                    'name' => $row->e_product_basename,
                    'text' => $row->i_product_motif.' - '.$row->e_product_basename.' - '.$row->i_color,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailfc(){
        header("Content-Type: application/json", true);
        $ifc     = $this->input->post('ifc', FALSE);
        $dfrom   = $this->input->post('dfrom', FALSE);
        $query  = array(
            'detail' => $this->mmaster->bacadetailfc($ifc,$dfrom)->result_array()
        );
        echo json_encode($query);  
    }

    public function getproduct(){
        header("Content-Type: application/json", true);
        
        $iproduct   = $this->input->post('iproduct');
        $this->db->select("*");
        $this->db->from("tr_product_base");
        $this->db->where("i_product_motif", $iproduct);
        $this->db->order_by('i_product_motif', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $dfrom      = $this->input->post('dfrom', TRUE);
        $isubbagian = $this->session->userdata('i_lokasi'); 
        $icustomer  = $this->input->post('icustomer', TRUE);
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dfrom   = $year.'-'.$month.'-'.$day;
        }
        $jml   = $this->input->post('jml', TRUE); 
        $ifc   = '';
        $this->db->trans_begin();
        $data = $this->mmaster->cekfc($yearmonth, $icustomer);
        if ($data->num_rows() > 0){
            foreach($data->result() as $row){
                $ifc=$row->i_fc;
            }
            $this->mmaster->updateheaderfc($ifc, $dfrom, $yearmonth, $icustomer);
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ifc);
        } else {
            $ifc = $this->mmaster->runningnumber($yearmonth,$isubbagian);
           
            $this->mmaster->insertheader($ifc, $dfrom, $yearmonth, $icustomer);
            
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ifc);
        }  
        $this->mmaster->deletedetail($ifc);
        for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);          
                $icolor         = $this->input->post('icolor'.$i, TRUE);
                $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                $nitemno        = $i;
                $this->mmaster->insertdetail($ifc, $iproduct, $icolor, $nquantity, $nitemno);
                
        }
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'      => $ifc,
            );
        }
        $this->load->view('pesan', $data); 
    }

    public function deletedetailinput(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ifc			= $this->input->post('ifc', TRUE);
		$iproduct		= $this->input->post('iproduct', TRUE);

        $this->db->trans_begin();
        $this->mmaster->deletedetailinput($iproduct);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete Item Forecast Distributor '.$iproduct);
            echo json_encode($data);
        }
    }

    public function export(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dfrom      = ($this->input->post('dfrom',TRUE) != '' ? $this->input->post('dfrom',TRUE) : $this->uri->segment(4));
        $icustomer  = ($this->input->post('icustomer',TRUE) != '' ? $this->input->post('icustomer',TRUE) : $this->uri->segment(5));
        $query = $this->db->query("SELECT e_customer_name FROM tr_customer WHERE i_customer='$icustomer'",FALSE)->result();
        foreach($query as $row){
            $ecustomer = $row->e_customer_name;
        }
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dfrom = $year.'-'.$month.'-'.$day;
        }
        $query  = $this->mmaster->cek_datadetail($yearmonth, $icustomer)->result();

        foreach ($query as $row) {
            $spreadsheet  = new Spreadsheet;
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $conditional3 = new Conditional();
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray( 
                [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE 
                ]
            ); 

            $sharedStyle1->applyFromArray(
                ['alignment' => [
                  'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                  'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                  'bottom' => ['borderStyle' => Border::BORDER_THIN],
                  'right' => ['borderStyle' => Border::BORDER_THIN],
                ],]
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
            foreach(range('A','G') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            }
            $spreadsheet->getActiveSheet()->mergeCells("A1:D1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:D2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:D3");
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A1', "Forecast Distributor")
                        ->setCellValue('A2', "Periode Forecast : $yearmonth")
                        ->setCellValue('A3', "Customer : $icustomer - $ecustomer")
                        ->setCellValue('A5', 'Kode_Barang')
                        ->setCellValue('B5', 'Nama_Barang')
                        ->setCellValue('C5', 'Warna')
                        ->setCellValue('D5', 'Jumlah_Forecast');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:D1');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:D2');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A3:D3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A4:D4');
            $kolom = 6;
            $nomor = 1;
            $nol = 0;
            foreach($query as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $kolom, $row->i_product)
                            ->setCellValue('B' . $kolom, $row->e_product_basename)
                            ->setCellValue('C' . $kolom, $row->i_color)
                            ->setCellValue('D' . $kolom, $row->jumlah);      
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':D'.$kolom);
            
                $kolom++;
                $nomor++;
            }
            $writer = new Xls($spreadsheet);
            $nama_file = "Forecast_Distributor_".$yearmonth."_".$icustomer.".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$nama_file.'');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }   
        $ifc        = $this->input->post('ifc', TRUE);
        $dfrom      = $this->input->post('dfrom', TRUE);
        $icustomer  = $this->input->post('icustomer', TRUE);
        if($dfrom){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $dfrom   = $year.'-'.$month.'-'.$day;
        }
        $jml   = $this->input->post('jml', TRUE); 

        //ITEM
        $i_product       = $this->input->post('iproduct[]',TRUE);
        $e_product       = $this->input->post('eproductname[]',TRUE);
        $i_color         = $this->input->post('icolor[]',TRUE);
        $n_quantity      = $this->input->post('nquantity[]',TRUE);

        $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ifc);
        $this->mmaster->updateheader($ifc, $icustomer);
        $this->mmaster->deletedetail($ifc);
        $no=0;
        foreach ($i_product as $iproduct) {     
            $iproduct   = $iproduct;
            $icolor     = $i_color[$no];
            $nquantity  = $n_quantity[$no];
            $nitemno    = $no;
            $this->mmaster->insertdetail($ifc, $iproduct, $icolor, $nquantity, $nitemno);
            $no++;
        }
    
         if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                    
                );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses'    => true,
                'kode'      => $ifc,
            );
        }
        $this->load->view('pesan', $data); 
    }
}
/* End of file Cform.php */