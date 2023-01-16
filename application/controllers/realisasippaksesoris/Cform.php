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
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050316';

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
        

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'gudang'    => $this->mmaster->bacagudang()
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vmainform', $data);
    }

    public function view(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $from  = $this->input->post('dfrom');
        if($from == ''){
            $from = $this->uri->segment(4);
        }
        $to  = $this->input->post('dto');
        if($to == ''){
            $to = $this->uri->segment(5);
        }
        $gudang  = $this->input->post('gudang');
        if($gudang == ''){
            $gudang = $this->uri->segment(6);
        }

        $tmp = explode('-', $from);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dfrom = $th.'-'.$bl.'-'.$hr;

        $tmp = explode('-', $to);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dto = $th.'-'.$bl.'-'.$hr;

        //var_dump($supplier, $from, $to, $dfrom, $dto);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'gudang'        => $gudang
            //'total'         => $this->mmaster->total($supplier,$dfrom,$dto)->row()
        );

        $this->Logger->write('Membuka Data Realisasi PP Aksesoris Gudang '.$this->global['title'].' '.$gudang.' Tanggal : '.$from.' Sampai '.$to);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    function data(){
        $gudang  = $this->input->post('gudang');
        $dfrom  = $this->input->post('dfrom');
        $dto  = $this->input->post('dto');

        // if($supplier==''){
        //     $supplier=$this->uri->segment(4);
        // }
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if($gudang==''){
            $gudang=$this->uri->segment(6);
        }
		echo $this->mmaster->data($dfrom,$dto,$gudang,$this->global['folder'],$this->i_menu);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'kodemaster'=> $this->mmaster->bacagudang(),
            'jnskeluar'=> $this->mmaster->bacajenis(),
            'tujuan'=> $this->mmaster->bacatujuan(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function gettujuankirim(){
        $itujuan = $this->input->post('itujuan');
        if($itujuan == "UJ"){
        $query = $this->mmaster->gettujuanUJ();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->itujuank." >".$row->etujuank."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Tujuan Kirim -- ".$c."</option>";
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
        }else if ($itujuan == "UP"){
            $query = $this->mmaster->gettujuanUP();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->itujuank." >".$row->etujuank."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Tujuan Kirim -- ".$c."</option>";
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
        }else if(($itujuan == "CT")){
            $query = $this->mmaster->gettujuanCT();
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->itujuank." >".$row->etujuank."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih Tujuan Kirim -- ".$c."</option>";
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
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
                echo json_encode(array(
                    'kop'    => $kop,
                    'kosong' => 'kopong'
                ));
        }
    }

    function getmaterial(){
        header("Content-Type: application/json", true);
        $imaterial = $this->input->post('i_material');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->where("UPPER(i_material)", $imaterial);
            $this->db->order_by('a.i_material', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function material(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_material, a.e_material_name ,b.i_satuan, b.e_satuan");
            $this->db->from("from tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->or_like("UPPER(i_color)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->nama,
        
                );
            }          
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dbonm = $this->input->post("dbonk",true);
        $tmp = explode('-', $this->input->post('dbonk'));
		$hr = $tmp[0];
		$bl = $tmp[1];
		$th = substr($tmp[2],2,2);
        $dbonk = $tmp[2].'-'.$bl.'-'.$hr;
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $jnskeluar 			= $this->input->post('jnskeluar', TRUE);
        $itujuan 			= $this->input->post('itujuan', TRUE);
        $itujuankirim                 = $this->input->post('itujuankirim', TRUE);
        $remark                 = $this->input->post('eremark', TRUE);
        $nobonk                 = $this->mmaster->runningnumberbonk($th,$bl,$ikodemaster);
        $jml                    = $this->input->post('jml', TRUE); 
        $bonkcancel               = 'f';
        $query 	    = $this->db->query("SELECT current_timestamp as c");
	   	$row   	    = $query->row();
        $now	    = $row->c;
        $this->db->trans_begin();
        $this->mmaster->insertheader($dbonk, $ikodemaster, $itujuan, $jnskeluar, $remark, $nobonk, $bonkcancel, $now, $itujuankirim);
            for($i=1;$i<=$jml;$i++){
                $imaterial      = $this->input->post('imaterial'.$i, TRUE);
                // $ematerialname  = $this->input->post('ematerialname'.$i, TRUE);
                $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                $nquantitykonv  = $this->input->post('nquantitykonv'.$i, TRUE);
                $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
                $esatuankonv    = $this->input->post('esatuankonv'.$i, TRUE);
                $fkonv    = $this->input->post('fkonv'.$i, TRUE);
                    if($esatuankonv == '0'){
                        $fkonv = 'f';
                    }else{
                        $fkonv = 't';
                    }
                $bisbisan       = $this->input->post('bisbisan'.$i, TRUE);
                    if($bisbisan == 'on'){
                        $bisbisan = 't';
                    }else{
                        $bisbisan = 'f';
                    }
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $this->mmaster->insertdetail($nobonk, $imaterial, $nquantity, $nquantitykonv, $isatuan, $esatuankonv, $edesc, $bisbisan, $ikodemaster,  $now, $i, $fkonv);
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nobonk);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nobonk,
                );
        }
    $this->load->view('pesan', $data);      
    }
    function datamaterial(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.*,b.e_satuan");
            $this->db->from("tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            // $this->db->order_by('a.i_material', 'ASC');
            $data = $this->db->get();
            foreach($data->result() as  $material){       
                    $filter[] = array(
                    'id' => $material->i_material,  
                    'text' => $material->i_material.' - '.$material->e_material_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  
    // public function view(){

    //     $data = check_role($this->i_menu, 2);
    //     if(!$data){
    //         redirect(base_url(),'refresh');
    //     }

    //     $dfrom  = $this->input->post("dfrom",true);
    //     $dto    = $this->input->post("dto",true);
    //     $enamamaster    = $this->input->post("ikodemaster",true);
    //     $jnsbarang    = $this->input->post("jnsbarang",true);
    //     $ikelompok    = $this->input->post("ikelompok",true);

    //     $data = array(
    //         'folder'        => $this->global['folder'],
    //         'title'         => "Edit ".$this->global['title'],
    //         'title_list'    => 'List '.$this->global['title'],
    //         'enamamaster'        => $enamamaster,
    //         'dfrom'         => $dfrom,
    //         'dto'           => $dto,
    //         //if($jnsbarang != "" and )
    //         'data2'         => $this->mmaster->cek_datadet($dfrom, $dto)->result(),
    //     );
    //     $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

    //     $this->load->view($this->global['folder'].'/vformedit', $data);
    // }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dbonk = $this->input->post("dbonk",true);
        // $tmp = explode('-', $this->input->post('dbonm'));
		// $hr = $tmp[0];
		// $bl = $tmp[1];
		// $th = substr($tmp[2],2,2);
        // $dbonm = $tmp[2].'-'.$bl.'-'.$hr;
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $remark                 = $this->input->post('eremark', TRUE);
        $nobonk                 = $this->input->post('nobonk', TRUE);
        $jml                    = $this->input->post('jml', TRUE); 
        $bonmcancel               = 'f';
        $query 	    = $this->db->query("SELECT current_timestamp as c");
	   	$row   	    = $query->row();
        $now	    = $row->c;
        $this->db->trans_begin();
        if ($nobonk != '' && $ikodemaster != ''){
            $cekada = $this->mmaster->cek_dataheader($nobonk);
            $this->mmaster->updateheader($nobonk, $dbonk, $remark, $now);
            if($cekada->num_rows() > 0){
            for($i=1;$i<=$jml;$i++){
                
                $imaterial      = $this->input->post('imaterial'.$i, TRUE);
                $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                $nquantitykonv  = $this->input->post('nquantitykonv'.$i, TRUE);
                $isatuan        = $this->input->post('isatuan'.$i, TRUE); 
                $esatuankonv    = $this->input->post('esatuankonv'.$i, TRUE);
                $fkonv    = $this->input->post('fkonv'.$i, TRUE);
                    if($esatuankonv == '0'){
                        $fkonv = 'f';
                    }else{
                        $fkonv = 't';
                    }
                $cekdet = $this->mmaster->cekdatadetail($nobonk, $imaterial);
                if($cekdet->num_rows() > 0){
                    $this->mmaster->updatedetail($nquantity,$nquantitykonv,$nobonk, $imaterial);
                }else{
                    $this->mmaster->insertdetail($nobonk, $imaterial, $nquantity, $nquantitykonv, $isatuan, $esatuankonv,
                    $ikodemaster,  $now, $i, $fkonv);
                }
                
            }
            $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$nobonk);
        }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $nobonk,
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
}


    public function detail(){

        $ipp    = $this->uri->segment('4');
        $from   = $this->uri->segment('5');
        $to     = $this->uri->segment('6');
        $gudang = $this->uri->segment('7');

        $tmp = explode('-', $from);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dfrom = $th.'-'.$bl.'-'.$hr;

        $tmp = explode('-', $to);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = $tmp[2];
        $dto = $th.'-'.$bl.'-'.$hr;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'gudang'        => $gudang,
            'head'          => $this->mmaster->baca_header($ipp)->row(),
            'detail'        => $this->mmaster->baca_detail($ipp),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

        // public function approve(){
        // $data = check_role($this->i_menu, 3);
        // if(!$data){
        //     redirect(base_url(),'refresh');
        // }
        
        //     $ipp= $this->input->post('ipp', TRUE);
        //     $data = array(
        //         'folder' => $this->global['folder'],
        //         'title' => "View ".$this->global['title'],
        //         'title_list' => 'List '.$this->global['title'],
        //         'data' => $this->mmaster->cek_data($ipp)->row(),
        //         'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        //     );
        
        //     $this->Logger->write('Membuka Menu Approve PP'.$this->global['title']);
        
        //     $this->load->view($this->global['folder'].'/vformapprove', $data);
        // }

        public function approve(){

            $data = check_role($this->i_menu, 3);
            if(!$data){
                redirect(base_url(),'refresh');
            }
    
            $ipp = $this->uri->segment('4');
    
            $data = array(
                'folder' => $this->global['folder'],
                'title' => "Edit ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'data' => $this->mmaster->cek_data($ipp)->row(),
                'data2' => $this->mmaster->cek_datadet($ipp)->result(),
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
        }
        public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ipp 			= $this->input->post('ipp', TRUE);
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $query 	= $this->db->query("SELECT current_timestamp as c");
	   		$row   	= $query->row();
            $now	  = $row->c;
            $this->db->trans_begin(); 
        $this->mmaster->approve($ipp, $now);
        if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ipp
                );
            }
            $this->load->view('pesan', $data);  
        }

        public function export(){
            $dfrom        = $this->uri->segment(4);
            $dto          = $this->uri->segment(5);
            $gudang       = $this->uri->segment(6);
            
            $query        = $this->mmaster->getAll($dfrom, $dto, $gudang);
            $spreadsheet  = new Spreadsheet;
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            if ($query->num_rows()>0) {
                $sharedStyle1->applyFromArray(
                    [
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'color' => ['rgb' => 'DFF1D0'],
                        ],
                        'font'=>[
                            'name'  => 'Arial',
                            'bold'  => true,
                            'italic'=> false,
                            'size'  => 10
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        ],
                        'borders' => [
                            'top'    => ['borderStyle' => Border::BORDER_THIN],
                            'bottom' => ['borderStyle' => Border::BORDER_THIN],
                            'left'   => ['borderStyle' => Border::BORDER_THIN],
                            'right'  => ['borderStyle' => Border::BORDER_THIN]
                        ],
                    ]
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
                        'font'=>[
                            'name'  => 'Times New Roman',
                            'bold'  => true,
                            'italic'=> false,
                            'size'  => 12
                        ],
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
                foreach(range('A','M') as $columnID) {
                    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                }
                $spreadsheet->getActiveSheet()->mergeCells("A1:M1");
                $spreadsheet->getActiveSheet()->mergeCells("A2:M2");
                //$spreadsheet->getActiveSheet()->mergeCells("A3:L3");
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', $this->global['title'])
                ->setCellValue('A2', "Periode : $dfrom sd $dto")
                //->setCellValue('A3', "Area : $area")
                ->setCellValue('A5', 'No')
                ->setCellValue('B5', 'No PP')
                ->setCellValue('C5', 'Tanggal PP')
                ->setCellValue('D5', 'No OP')
                ->setCellValue('E5', 'Tanggal OP')
                ->setCellValue('F5', 'Nama Supplier')
                ->setCellValue('G5', 'Kode Material')
                ->setCellValue('H5', 'Nama Material')
                ->setCellValue('I5', 'Satuan')
                ->setCellValue('J5', 'Qty PP')
                ->setCellValue('K5', 'Qty OP')
                ->setCellValue('L5', '% Realisasi')
                ->setCellValue('M5', 'Status');
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:M3');
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A5:M5');

                $kolom = 6;
                $nomor = 1;
                $nol   = 0;
                foreach($query->result() as $row) {
                    $persen = ($row->qtyop/$row->qtypp)*100;
                    $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->i_pp)
                    ->setCellValue('C' . $kolom, $row->d_pp)
                    ->setCellValue('D' . $kolom, $row->i_op)
                    ->setCellValue('E' . $kolom, $row->d_op)
                    ->setCellValue('F' . $kolom, $row->e_supplier_name)
                    ->setCellValue('G' . $kolom, $row->i_material)
                    ->setCellValue('H' . $kolom, $row->e_material_name)
                    ->setCellValue('I' . $kolom, $row->e_satuan)
                    ->setCellValue('J' . $kolom, $row->qtypp)
                    ->setCellValue('K' . $kolom, $row->qtyop)
                    ->setCellValue('L' . $kolom, $persen."%")
                    ->setCellValue('M' . $kolom, $row->status);

                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':M'.$kolom);
                    // $spreadsheet->getActiveSheet()
                    // ->getStyle('D'.$kolom)
                    // ->getNumberFormat()
                    // ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD1);
                    // $spreadsheet->getActiveSheet()
                    // ->getStyle('E'.$kolom)
                    // ->getNumberFormat()
                    // ->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
                    $kolom++;
                    $nomor++;
                }
                $tgl = date("d")."-".date("m")."-".date("Y")."  Jam : ".date("H:i:s");
                $spreadsheet->getActiveSheet()->mergeCells('A'.$kolom.':M'.$kolom);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A'.$kolom.':M'.$kolom);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$kolom, 'Tgl Cetak : '.$tgl);
            }
            $this->Logger->write('Realisasi PP Aksesoris'.' Periode:'.$dfrom.' s/d '.$dto);
            $spreadsheet->getActiveSheet()->setTitle('Realisasi PP Aksesoris');
            $nama_file = "Realisasi_PP_Aks_".$dfrom."_".$dto.".xls";
            // $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            // if(file_exists('export/00/'.$nama_file)){
            //     @chmod('export/00/'.$nama_file, 0777);
            //     @unlink('export/00/'.$nama_file);
            // }
            // $writer->save('export/00/'.$nama_file); 
            // @chmod('export/00/'.$nama_file, 0777);
            $writer = new Xls($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='.$nama_file.'');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
    }
/* End of file Cform.php */
