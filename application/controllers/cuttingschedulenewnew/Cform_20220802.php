<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
/* use PhpOffice\PhpSpreadsheet\Style\Fill; */
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment; */
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090101';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index()
    {

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

        $periode = $this->mmaster->getlateperiode();

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
            'periode'   => $periode
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

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
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }
    
    public function status()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        /* $iproductcolor = explode('|', $id);
        $iproduct = $iproductcolor[0];
        $icolor   = $iproductcolor[1]; */
        /*$id       = $iproductcolor[2];*/
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status(/* $iproduct, $icolor, */$id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' ID : '.$id);
                echo json_encode($data);
            }
        }
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $format = $this->mmaster->getformat();
        if($format->num_rows() < 1){
            $no = 0;
        }
        else{
            $format = $format->row();
            $no  = substr($format->i_document,8);    
        }
        $no = (int)$no + 1;
        $num = sprintf("%04d", $no);
        $str = "SC-".date("ym")."-".$num;

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'format'     => $str,
            'bagian'     => $this->mmaster->bagian()->result(),
            'periode'    => $this->mmaster->getperiode()->result()
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function productwip()
    {
        $filter = [];
        $data   = $this->mmaster->productwip(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach($data->result() as  $row){
                $filter[] = array(
                    'id'   => $row->id_product_wip.'|'.$row->i_color,  
                    'text' => $row->i_product_wip.' - '.$row->e_product_wipname.' - ['.$row->e_color_name.']',
                    'progress' => $row->progress,
                    'cutting' => $row->n_fc_cutting,
                    'perhitungan' => $row->n_fc_perhitungan,
                    'kondisi' => $row->n_kondisi_stock,
                    'persiapan' => 0,
                    
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function getbagian()
    {
        $filter = [];
        $data   = $this->mmaster->bagianpembuat(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach($data->result() as  $row){
                $filter[] = array(
                    'id'   => $row->i_bagian,  
                    'text' => $row->e_bagian_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function getkategori()
    {
        $filter = [];
        $data   = $this->mmaster->getkategori(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach($data->result() as  $row){
                $filter[] = array(
                    'id'   => $row->id,  
                    'text' => $row->e_nama_kategori,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function getunit()
    {
        $filter      = [];
        $cari        = str_replace("'", "", $this->input->get('q'));
        $kategori    = $this->input->get('kategori');
        $data   = $this->mmaster->getunit($cari,$kategori);
        if ($data->num_rows()>0) {
            foreach($data->result() as  $row){
                $filter[] = array(
                    'id'   => $row->id,  
                    'text' => $row->e_nama_unit,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }


    public function productwipref()
    {
        $filter         = [];
        $cari           = str_replace("'", "", $this->input->get('q'));
        $i_product_wip  = explode('|', $this->input->get('i_product_wip', TRUE))[0];
        $i_color        = explode('|', $this->input->get('i_product_wip', TRUE))[1];
        $data           = $this->mmaster->productwipref($cari,$i_product_wip, $i_color);
        if($i_product_wip != ''){
            if ($data->num_rows()>0) {
                foreach($data->result() as  $row){
                    $filter[] = array(
                        'id'   => $row->i_product_wip.'|'.$row->i_color,  
                        'text' => $row->i_product_wip.' - '.$row->e_product_wipname.' - ['.$row->e_color_name.']',
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data",
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Pilih Product WIP",
            );
        }
        echo json_encode($filter);
    }

    public function getdataitem()
    {
        header("Content-Type: application/json", true);
        $idreff    = $this->input->post('idreff');
        // $ipengirim = $this->input->post('ipengirim');
        $jml = $this->mmaster->getdataitem($idreff);
        $data = array(
            'jmlitem'    => $jml->num_rows(),
            'dataitem'   => $this->mmaster->getdataitem($idreff)->result_array()
        );
        echo json_encode($data);
    }

    public function get_bisbisan()
    {
        $filter         = [];
        $cari           = str_replace("'", "", $this->input->get('q'));
        $i_material     = $this->input->get('i_material', false);
        $data           = $this->mmaster->get_bisbisan($cari,$i_material);
        if($i_material != ''){
            if ($data->num_rows()>0) {
                foreach($data->result() as  $row){
                    $filter[] = array(
                        'id'   => $row->id,
                        'text' => $row->n_bisbisan.' - '.$row->e_jenis_potong,
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data",
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Pilih Product Terlebih Dahulu",
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL REFERENSI  ----------*/
    
    public function getdetailref()
    {
        header("Content-Type: application/json", true);
        $i_product_wip  = explode('|', $this->input->post('i_product_wip', TRUE))[0];
        $i_color        = explode('|', $this->input->post('i_product_wip', TRUE))[1];
        $query  = array(
            'detail' => $this->mmaster->getdetailref($i_product_wip, $i_color)->result_array(),
        );
        echo json_encode($query);  
    }

    /*----------  GET DETAIL MATERIAL  ----------*/
    
    public function getdetailmaterial()
    {
        header("Content-Type: application/json", true);
        $i_material  = $this->input->post('i_material', TRUE);
        $query  = array(
            'detail' => $this->mmaster->getdetailmaterial($i_material)->result_array(),
        );
        echo json_encode($query);  
    }

    public function material()
    {
        $filter = [];
        $data   = $this->mmaster->material(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach($data->result() as  $row){
                $filter[] = array(
                    'id'   => $row->i_material,  
                    'text' => $row->i_material.' - '.$row->e_material_name.' - '.$row->e_satuan_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }
    

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idocument 	    = $this->input->post('idocument', TRUE);
        $ddocument 	    = $this->input->post('ddocument', TRUE);
        $tmp   = explode('-', $ddocument);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year . $month;
        $ddocument = $year . '-' . $month . '-' . $day;
        $ibagian 	    = $this->input->post('ibagian', TRUE);
        $iperiode       = $this->input->post('ireff', TRUE);
        $keterangan	    = $this->input->post('keterangan', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        if ($jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->insertheader($idocument, $ddocument, $ibagian, $keterangan, $iperiode);

            for ($i=0; $i < $jml; $i++) { 
                $tanggal     = $this->input->post('tanggal'.$i, TRUE);
                $tmp         = explode('-', $tanggal);
                $day         = $tmp[0];
                $month       = $tmp[1];
                $year        = $tmp[2];
                $yearmonth   = $year . $month;
                $tanggal     = $year . '-' . $month . '-' . $day;
                $ibarang     = $this->input->post('ibarang'.$i, TRUE);
                $progress    = $this->input->post('progress'.$i, TRUE);
                $nfccutting  = $this->input->post('nfccutting'.$i, TRUE);
                $nfcproduksi = $this->input->post('nfcproduksi'.$i, TRUE);
                $nkondisi    = $this->input->post('nkondisi'.$i, TRUE);
                $eremark     = $this->input->post('eremark'.$i, TRUE);
                $this->mmaster->insertdetail($idocument, $tanggal, $ibarang, $progress, $nfccutting, $nfcproduksi, $nkondisi, $eremark);
            }
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $idocument
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode Dokumen : '.$idocument);
            }
        }else{
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
            );
        }
        $this->load->view('pesan', $data);      
    }
    
    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $this->uri->segment(4),
            'bagian'    => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->detail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idocument 	    = $this->input->post('idocument', TRUE);
        $ddocument 	    = $this->input->post('ddocument', TRUE);
        $tmp   = explode('-', $ddocument);
        $day   = $tmp[0];
        $month = $tmp[1];
        $year  = $tmp[2];
        $yearmonth = $year . $month;
        $ddocument = $year . '-' . $month . '-' . $day;
        $ibagian 	    = $this->input->post('ibagian', TRUE);
        $keterangan	    = $this->input->post('keterangan', TRUE);
        $tanggal     = $this->input->post('tanggal', TRUE);
        $ibarang     = $this->input->post('ibarang', TRUE);
        $progress    = $this->input->post('progress', TRUE);
        $nfccutting  = $this->input->post('nfccutting', TRUE);
        $nfcproduksi = $this->input->post('nfcproduksi', TRUE);
        $nkondisi    = $this->input->post('nkondisi', TRUE);
        $npersiapan  = $this->input->post('npersiapan', TRUE);
        $eremark     = $this->input->post('eremark', TRUE);
        $count       = count($tanggal);
        if ($count > 0) { 
            $this->db->trans_begin();
            $this->mmaster->updateheader($idocument, $ddocument, $ibagian, $keterangan);
            $this->mmaster->deletedetail($idocument);
            for ($i=0; $i < $count; $i++) { 
                $itanggal     = $tanggal[$i];
                $itmp         = explode('-', $itanggal);
                $iday         = $itmp[0];
                $imonth       = $itmp[1];
                $iyear        = $itmp[2];
                $iyearmonth   = $iyear . $imonth;
                $itanggal     = $iyear . '-' . $imonth . '-' . $iday;
                $iibarang     = $ibarang[$i];
                $iprogress   = $progress[$i];
                $infccutting  = $nfccutting[$i];
                $infcproduksi = $nfcproduksi[$i];
                $inkondisi    = $nkondisi[$i];
                $ieremark     = $eremark[$i];
                $this->mmaster->updatedetail($idocument, $itanggal, $iibarang, $iprogress, $infccutting, $infcproduksi, $inkondisi, $ieremark);
            }
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $idocument
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode Dokumen : '.$idocument);
            }
        }else{
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
            );
        }
        $this->load->view('pesan', $data);       
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->uri->segment(4);
        $status = $this->mmaster->cekstatus($id);
        if($status == '2'){
            $detail = $this->mmaster->detail($id)->result();
        }
        else{
            $detail = $this->mmaster->detailurut($id)->result();
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $id,
            'data'       => $this->mmaster->dataheader($id)->row(),
            'detail'     => $detail,
        );

        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    // public function upload()
    // {

    //     $data = check_role($this->i_menu, 1);
    //     if (!$data) {
    //         redirect(base_url(), 'refresh');
    //     }

    //     $data = array(
    //         'folder'     => $this->global['folder'],
    //         'title'      => "Upload " . $this->global['title'],
    //         'title_list' => 'List ' . $this->global['title'],
    //         'dfrom'      => $this->uri->segment(4),
    //         'dto'        => $this->uri->segment(5),
    //     );

    //     $this->Logger->write('Membuka Menu Upload ' . $this->global['title']);

    //     $this->load->view($this->global['folder'] . '/vformupload', $data);
    // }

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);

            if($estatus = "Final Approve"){
                $cek = $this->mmaster->getidocument($id);
                $num = $this->mmaster->cekdetail($cek)->num_rows();
                $query = $this->mmaster->cekdetail($cek)->result();
                $urut = 1;
                if($num > 0){
                    foreach($query as $row){
                        
                        $this->mmaster->updatenourut($row->id, $urut);
                        $urut++;
                    }
                }

            }
        }
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $idocument  = $this->uri->segment(5);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(6),
            'dto'        => $this->uri->segment(7),
            'data'       => $this->mmaster->cekdataheader($id)->row(),
            'detail'     => $this->mmaster->cekdetail($idocument)->result()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function act_upload()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $filename     = $_FILES['userfile']['name'];
        $tmp_file     = $_FILES['userfile']['tmp_name'];
        if (!empty($filename)) {
            $filename = str_replace(' ', '_', $filename);
            $exsten      = explode('.', $filename)[1];

            if ($tmp_file != "") {
                $kop = "./import/ipcutting/" . $filename;
                $pattern = "/^.*\.(" . $exsten . ")$/i";
                if (preg_match_all($pattern, $kop) >= 1) {
                    if (move_uploaded_file($tmp_file, $kop)) {
                        @chmod("./import/ipcutting/" . $filename, 0777);
                        $ibonm        = $this->input->post('idocument', TRUE);
                        $ikodemaster  = $this->input->post('ibagian', TRUE);
                        $dbonm        = $this->input->post('ddocument', TRUE);
                        if ($dbonm) {
                            $dbonm = date('Y-m-d', strtotime($dbonm));
                        }
                        $ireff        = $this->input->post('ireff', TRUE);
                        $eremark      = $this->input->post('eremark', TRUE);
                        if ($ibonm != ''  && $dbonm != '' && $ikodemaster != '' && $ireff != '') {
                            $cekkode = $this->mmaster->cek_kode($ibonm, $ikodemaster);
                            if ($cekkode->num_rows() > 0) {
                                $data = array(
                                    'sukses' => false,
                                    'kode' => ''
                                );
                            } else {
                                $this->db->trans_begin();
                                $id = $this->mmaster->runningid();
                                $this->mmaster->insertheader($id, $ibonm, $dbonm, $ikodemaster, $ireff, $eremark);
                                $inputFileName = './import/ipcutting/' . $filename;
                                $spreadsheet   = IOFactory::load($inputFileName);
                                $sheet         = $spreadsheet->getSheet(0);
                                $hrow          = $sheet->getHighestDataRow('B');
                                for ($rows = 3; $rows <= $hrow; $rows++) {
                                    $id_fccutting_item = $spreadsheet->getActiveSheet()->getCell('B' . $rows)->getValue();
                                    $id_product_base = $spreadsheet->getActiveSheet()->getCell('C' . $rows)->getValue();
                                    $n_qty_wip = $spreadsheet->getActiveSheet()->getCell('G' . $rows)->getValue();
                                    $id_material = $spreadsheet->getActiveSheet()->getCell('H' . $rows)->getValue();
                                    $e_bagian = $spreadsheet->getActiveSheet()->getCell('M' . $rows)->getValue();
                                    $n_gelar = $spreadsheet->getActiveSheet()->getCell('N' . $rows)->getValue();
                                    $n_set = $spreadsheet->getActiveSheet()->getCell('O' . $rows)->getValue();
                                    $n_panjang_gelar = $spreadsheet->getActiveSheet()->getCell('P' . $rows)->getValue();
                                    $n_panjang_kain = $spreadsheet->getActiveSheet()->getCell('Q' . $rows)->getValue();
                                    $f_auto_cutter = $spreadsheet->getActiveSheet()->getCell('R' . $rows)->getValue();
                                    $edesc = $spreadsheet->getActiveSheet()->getCell('W' . $rows)->getValue();
                                    if ($id_material != "") {
                                        $this->mmaster->insertdetail($id, $ireff, $id_product_base, $id_material, $id_fccutting_item, $e_bagian, $n_gelar, $n_set, $n_panjang_gelar, $n_panjang_kain, $f_auto_cutter, $edesc, $n_qty_wip);
                                    }
                                }
                                if ($this->db->trans_status() === FALSE) {
                                    $this->db->trans_rollback();
                                    $data = array(
                                        'sukses' => false,
                                        'kode' => ''
                                    );
                                } else {
                                    $this->db->trans_commit();
                                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonm);
                                    $data = array(
                                        'sukses' => true,
                                        'kode'   => $ibonm,
                                        'id'     => $id,
                                    );
                                }
                            }
                        } else {
                            $data = array(
                                'sukses' => false,
                                'kode' => ''
                            );
                        }
                    } else {
                        $data =  array(
                            'sukses'     => false,
                            'kode' => ''
                        );
                    }
                } else {
                    $data =  array(
                        'sukses'     => false,
                        'kode' => ''
                    );
                }
            } else {
                $data =  array(
                    'sukses'     => false,
                    'kode' => ''
                );
            }
        }
        /* $this->load->view('pesan2', $data); */
        echo json_encode($data);
    }

    public function download()
    {
        /* $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        } */

        $id         = $this->uri->segment(4);
        $idocument  = $this->uri->segment(5);

        $header     = $this->mmaster->cekdataheader($id)->row();
        $query      = $this->mmaster->cekdetail($idocument);
        if ($query->num_rows() > 0) {

            $spreadsheet = new Spreadsheet;
            $title = new Style();
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
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
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

            $title->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => true,
                        'italic' => false,
                        'size'  => 12
                    ],
                ]
            );

            $sharedStyle2->applyFromArray(
                [
                    'font' => [
                        'name'  => 'Calibri',
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
                    'font' => [
                        'name'  => 'Calibri',
                        'bold'  => false,
                        'italic' => false,
                        'size'  => 10
                    ],
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
            foreach (range('A', 'X') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Schedule Jahit');
            $spreadsheet->getActiveSheet()->duplicateStyle($title, 'A1');
            $spreadsheet->getActiveSheet()->setTitle('Schedule Jahit');
            $spreadsheet->getActiveSheet()->mergeCells("A1:F1");
            $spreadsheet->setActiveSheetIndex(0)
                
                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'Nama Barang')
                ->setCellValue('C2', 'Tanggal Pengerjaan')
                ->setCellValue('D2', 'Progress')
                ->setCellValue('E2', 'FC Cutting')
                // ->setCellValue('F2', 'FC Produksi')
                // ->setCellValue('G2', 'Kondisi Stock Persiapan Cutting')
                ->setCellValue('F2', 'Urutan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:F2');
            $sheet = $spreadsheet->getActiveSheet();
            $validation = $sheet->getCell("P3")->getDataValidation();
            $validation
                ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                ->setFormula1('"TRUE,FALSE"')
                ->setAllowBlank(false)
                ->setShowDropDown(true)
                ->setShowInputMessage(true)
                ->setPromptTitle("Note")
                ->setPrompt("Must select one from the drop down options.")
                ->setShowErrorMessage(true)
                ->setErrorStyle(
                    \PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP
                )
                ->setErrorTitle("Invalid option")
                ->setError("Select one from the drop down list.");

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {
                /* $f_auto_cutter = ($row->f_auto_cutter == 't') ? 'Auto Cutter' : 'Manual'; */
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->i_product_wip.'-'.$row->e_product_wipname.'-'.$row->e_color_name)
                    ->setCellValue('C' . $kolom, $row->d_schedule)
                    ->setCellValue('D' . $kolom, $row->e_progress)
                    ->setCellValue('E' . $kolom, $row->n_fc_cutting)
                    // ->setCellValue('F' . $kolom, $row->n_fc_perhitungan)
                    // ->setCellValue('G' . $kolom, $row->n_kondisi_stock)
                    ->setCellValue('F' . $kolom, $row->n_urut_stock);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':F' . $kolom);
                $kolom++;
                $nomor++;
            }
            $sheet->setDataValidation("P3:P" . $kolom, $validation);
            $writer = new Xls($spreadsheet);
            $nama_file = "Jadwal_jahit.xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        } else {
            echo "<center><h1>Tidak Ada Data :)</h1></center>";
        }
    }
    
}
/* End of file Cform.php */
