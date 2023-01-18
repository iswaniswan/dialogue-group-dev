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
    public $i_menu = '2090205';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        require_once("php/fungsi.php");
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        

        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    public function index(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function index2()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SO-".date('ym')."-123456"
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function tambah(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post("ibagian",true);
        $ddocument  = $this->input->post("ddocument",true);
        $idocument  = $this->input->post("idocument",true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($ddocument == "") $ddocument = $this->uri->segment(5);
        if ($idocument == "") $idocument = $this->uri->segment(6);
        $dfrom      = $this->input->post("dfrom",true);
        $dto        = $this->input->post("dto",true);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'ddocument'     => $ddocument,
            'idocument'     => $idocument,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->session->userdata('id_company'))->row(),
            'datadetail'    => $this->mmaster->datadetail($this->session->userdata('id_company'),$ddocument, $ibagian)->result_array(),
            'grade'         => $this->mmaster->datagrade()->result_array(),
        );
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    /*----------  CARI BARANG  ----------*/
    public function barang() {
        $filter = [];
        $data = $this->mmaster->barang(str_replace("'","",$this->input->get('q')),$this->input->get('ibagian'), $this->input->get('ddocument') );
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id_product_wip,
                    'text' => $row->i_product_wip.' - '.$row->e_product_wipname.' - '.$row->e_color_name
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function detailbarang() {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detailbarang($this->input->post('id',TRUE),$this->input->post('ibagian',TRUE),$this->input->post('ddocument',TRUE))->result_array()
        );
        echo json_encode($query);  
    } 

    /*----------  CARI GRADE  ----------*/
    public function cargrade() {
        $filter = [];
        $data = $this->mmaster->cargrade(str_replace("'","",$this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    // 'id'   => $row->id,
                    'id'   => $row->i_grade,
                    'text' => $row->i_grade.' - '.$row->e_grade_name
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }


    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE); 
        $idocument  = $this->input->post('idocument', TRUE);
        
        $eremarkh  = $this->input->post('eremarkh', TRUE);
        $idcompany = $this->session->userdata('id_company');
        $jml        = $this->input->post('jml', TRUE);

        $format     = DateTime::createFromFormat('d-m-Y', $this->input->post('ddocument', TRUE));
        $iperiode   = $format->format('Ym');
        $ddocument  = $format->format('Y-m-d');
        $this->db->trans_begin();
        $id = $this->mmaster->runningid();
        
        $this->mmaster->simpan($id,$idcompany,$ibagian,$idocument,$ddocument,$iperiode, $eremarkh);
        //$this->mmaster->hapusdetail($idcompany, $id);
        $count = count($this->input->post("idpanel[]", TRUE));
        for ($i = 0; $i < $count; $i++) {
            $idpanel    = $this->input->post('idpanel[]', TRUE)[$i];
            $qty        = str_replace(",","",$this->input->post('nquantity[]', TRUE)[$i]);
            $eremark    = $this->input->post('eremark[]', TRUE)[$i];
            
            $this->mmaster->simpandetail($idcompany, $id, $idpanel, $qty, $eremark);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'kode' => "",
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idocument,
                'id'     => $id
            );
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
        }

        $this->load->view('pesan2', $data); 
    }


    public function changestatus(){
        $id         = $this->input->post('id', true);
        $istatus    = $this->input->post('istatus', true);
        $estatus    = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);
       
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE); 
        $idocument  = $this->input->post('idocument', TRUE);        
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $idcompany  = $this->session->userdata('id_company');
        $jml        = $this->input->post('jml', TRUE);

        $format     = DateTime::createFromFormat('d-m-Y', $this->input->post('ddocument', TRUE));
        $iperiode   = $format->format('Ym');
        $ddocument  = $format->format('Y-m-d');
        $this->db->trans_begin();
        $id = $this->input->post('id', TRUE);
        
        $this->mmaster->updateheader($id, $eremarkh);
        $this->mmaster->hapusdetail($id);
        $count = count($this->input->post("idpanel[]", TRUE));
        for ($i = 0; $i < $count; $i++) {
            $idpanel    = $this->input->post('idpanel[]', TRUE)[$i];
            $qty        = str_replace(",","",$this->input->post('nquantity[]', TRUE)[$i]);
            $eremark    = $this->input->post('eremark[]', TRUE)[$i];
            
            $this->mmaster->simpandetail($idcompany, $id, $idpanel, $qty, $eremark);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
                'kode' => "",
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $idocument,
                'id'     => $id
            );
            $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
        }

        $this->load->view('pesan2', $data); 
    }
     

    public function approval(){
        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);
       

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'id'            => $this->uri->segment(4),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

     public function view(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id     = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'id'            => $this->uri->segment(4),
            'datahead'      => $this->mmaster->dataheader_edit($id)->row(),
            'datadetail'    => $this->mmaster->datadetail_edit($id)->result_array(),
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function export()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $query_panel = $this->mmaster->data_export_panel();

       if ($query_panel->num_rows() > 0) {
            $spreadsheet = new Spreadsheet;
            $sharedStyle1 = new Style();
            $sharedStyle2 = new Style();
            $sharedStyle3 = new Style();
            $conditional3 = new Conditional();
            $spreadsheet->getActiveSheet()->getStyle('B2')->getAlignment()->applyFromArray(
                [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
                ]
            );

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
                    'font' => [
                        'name'  => 'Arial',
                        'bold'  => false,
                        'italic' => false,
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
            // foreach (range('A', 'S') as $columnID) {
            //     $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            //     $conditional3->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
            // }
            /*$spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'C5:R95');*/
            // $spreadsheet->getActiveSheet()->mergeCells("A1:G1");
            // $spreadsheet->getActiveSheet()->mergeCells("A2:G2");
            // $spreadsheet->getActiveSheet()->mergeCells("A3:G3");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", "ID PANEL (System)")
                ->setCellValue("B1", "Kode Produk")
                ->setCellValue("C1", "Nama Produk")
                ->setCellValue("D1", "Warna")
                ->setCellValue("E1", "Series")
                ->setCellValue("F1", "Kode Material")
                ->setCellValue("G1", "Nama Material")
                ->setCellValue("H1", "Kode Panel")
                ->setCellValue("I1", "Bagian")
                ->setCellValue("J1", "Qty Penyusun")
                ->setCellValue("K1", "Panjang (CM)")
                ->setCellValue("L1", "Lebar (CM)")
                ->setCellValue("M1", "Panjang Gelaran (CM)")
                ->setCellValue("N1", "Lebar Gelaran (CM)")
                ->setCellValue("O1", "Hasil Gelaran (Set)")
                ->setCellValue("P1", "Efficiency Marker")
                ->setCellValue("Q1", "Print")
                ->setCellValue("R1", "Bordir")
                ->setCellValue("S1", "QTY SO");

            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A1:H1');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A2:H2');
            // $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, 'A3:H3');
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A1:S1');

            $kolom = 2;
            $nomor = 1;
            foreach ($query_panel->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A" . $kolom, $row->id_panel)
                    ->setCellValue("B" . $kolom, $row->i_product_wip)
                    ->setCellValue("C" . $kolom, $row->e_product_wipname)
                    ->setCellValue("D" . $kolom, $row->e_color_name)
                    ->setCellValue("E" . $kolom, $row->e_series_name)
                    ->setCellValue("F" . $kolom, $row->i_material)
                    ->setCellValue("G" . $kolom, $row->e_material_name)
                    ->setCellValue("H" . $kolom, $row->i_panel)
                    ->setCellValue("I" . $kolom, $row->bagian)
                    ->setCellValue("J" . $kolom, $row->n_qty_penyusun)
                    ->setCellValue("K" . $kolom, $row->n_panjang_cm)
                    ->setCellValue("L" . $kolom, $row->n_lebar_cm)
                    ->setCellValue("M" . $kolom, $row->n_panjang_gelar)
                    ->setCellValue("N" . $kolom, $row->n_lebar_gelar)
                    ->setCellValue("O" . $kolom, $row->n_hasil_gelar)
                    ->setCellValue("P" . $kolom, $row->n_efficiency)
                    ->setCellValue("Q" . $kolom, $row->print)
                    ->setCellValue("R" . $kolom, $row->bordir)
                    ->setCellValue("S" . $kolom, 0);

                //$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);          
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':S' . $kolom);

                $kolom++;
                $nomor++;
            }

            $writer = new Xls($spreadsheet);
            $nama_file = "SO_PANEL" . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }


    public function load() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian  = $this->input->post('ibagian', TRUE);
        $i_so  = $this->input->post('i_so', TRUE);
        $ddocument       = $this->input->post('ddocument', TRUE);
        $dfrom       = $this->input->post('dfrom', TRUE);
        $dto       = $this->input->post('dto', TRUE);
        $filename = $this->id_company. "_SO_" .$ddocument.".xls";

        $config = array(
            'upload_path'   => "./import/soproduksi/qcset/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());
            $this->Logger->write('Upload File SO Pengesettan : ' . $i_so);

            $param =  array(
                'ibagian'   => $ibagian,
                'i_so'      => $i_so,
                'ddocument' => $ddocument,
                'dfrom'     => $dfrom,
                'dto'       => $dto,
                'status'    => 'berhasil'
            );

            echo json_encode($param);
        } else {
            $param =  array(
                'status' => 'gagal'
            );
            echo json_encode($param);
            //echo 'gagal';
        }
    }

     public function loadview()
    {

        $ibagian    = $this->uri->segment(4);
        $i_so       = $this->uri->segment(5);
        $ddocument  = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);

        $filename = $this->id_company. "_SO_" .$ddocument.".xls";


        $inputFileName = "./import/soproduksi/qcset/" . $filename;
        $spreadsheet   = IOFactory::load($inputFileName);
        $worksheet     = $spreadsheet->getActiveSheet();
        $sheet         = $spreadsheet->getSheet(0);
        $hrow          = $sheet->getHighestDataRow('A');

        $aray = array();
        $aray_notfound = array();
        for ($n = 2; $n <= $hrow; $n++) {
            $id_panel = strtoupper($spreadsheet->getActiveSheet()->getCell('A' . $n)->getValue());
            $qty = $spreadsheet->getActiveSheet()->getCell('S' . $n)->getCalculatedValue();

            if ($qty > 0 && $id_panel != "") {
                $cek_panel = $this->mmaster->cek_panel($id_panel);
                if ($cek_panel->num_rows() > 0) {
                    foreach ($cek_panel->result() as $row) {
                        $aray[] = array(
                            'id_panel_item'     => $row->id_panel_item,
                            'id_company'        => $this->id_company,
                            'id_product_wip'    => $row->id_product_wip,
                            'i_panel'           => $row->i_panel,
                            'bagian'            => $row->bagian,
                            'i_product_wip'     => $row->i_product_wip,
                            'e_product_wipname' => $row->e_product_wipname,
                            'e_color_name'      => $row->e_color_name,
                            'i_material'        => $row->i_material,
                            'id'                => $row->id,
                            'e_material_name'   => $row->e_material_name,
                            'e_satuan_name'     => $row->e_satuan_name,
                            'n_quantity'        => $qty,
                            'e_remark'          => '',
                        );
                    }
                }
            } 

            // else if ($qty > 0) {
            //     $aray_notfound[] = array(
            //         'id_product_base'       => null,
            //         'i_product_base'        => $i_product,
            //         'e_product_basename'    => $spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue(),
            //         'e_nama_divisi'         => $spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue(),
            //         'e_class_name'          => $spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue(),
            //         'v_harga'               => $v_harga,
            //         'n_rata2'               => $rata2,
            //         'n_quantity'            => $qty,
            //         'n_quantity_sisa'       => $qty,
            //         'e_remark'              => $e_remark,
            //         'e_color_name'          => "",
            //     );
            // }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => $this->global['title'],
            'ddocument'     => $ddocument,
            'idocument'     => $i_so,
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'bagian'        => $this->mmaster->get_bagian($ibagian, $this->id_company)->row(),
            'datadetail'    => $aray,
            'grade'         => $this->mmaster->datagrade()->result_array(),
        );
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

}
/* End of file Cform.php */