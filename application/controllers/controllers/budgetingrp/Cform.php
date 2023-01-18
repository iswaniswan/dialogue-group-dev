<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '20200';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->level            = $this->session->i_level;

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'] . '/mmaster');
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

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => $this->global['title'],
            'dfrom'      => date('d-m-Y', strtotime($dfrom)),
            'dto'        => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    function data()
    {       
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data( $this->global['folder'],$this->i_menu, $dfrom, $dto);
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => "PP-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function tambahbudgeting()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => "PP-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title'].' Berdasarkan Budgeting');

        $this->load->view($this->global['folder'] . '/vformaddbudget', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function kelompok()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->kelompok($this->input->get('q'),$this->input->get('ibagian'));
            $filter[] = array(
                'id'   => 'all',  
                'text' => 'Semua Kategori',
            );
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_kode_kelompok,  
                    'text' => ucwords(strtolower($key->e_nama_kelompok)),
                );
            }          
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function jenis()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->jenis($this->input->get('q'),$this->input->get('ikategori'),$this->input->get('ibagian'));
            $filter[] = array(
                'id'   => 'all',  
                'text' => 'Semua Jenis',
            );
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_type_code,  
                    'text' => ucwords(strtolower($key->e_type_name)),
                );
            }          
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function material()
    {
        $filter = [];
        if ($this->input->get('q')!='' && $this->input->get('ibagian')!='') {
            $data = $this->mmaster->material(str_replace("'","",$this->input->get('q')),$this->input->get('ikategori'),$this->input->get('ijenis'),$this->input->get('ibagian'));
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_material,
                    'text' => $row->i_material.' - '.$row->e_material_name,
                );
            }
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    public function getmaterial()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->getmaterial($this->input->post('imaterial', TRUE))->result_array());
    }

    public function materialbudget()
    {
        $filter = [];
        if ($this->input->get('q')!='' && $this->input->get('ibagian')!='' && $this->input->get('dpp')!='') {
            $data = $this->mmaster->materialbudget(str_replace("'","",$this->input->get('q')),$this->input->get('ikategori'),$this->input->get('ijenis'),$this->input->get('ibagian'),$this->input->get('dpp'));
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->i_material,
                    'text' => $row->i_material.' - '.$row->e_material_name,
                );
            }
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }

    /** Rubah 2021-11-24 */
    public function getmaterialbudgetold()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->getmaterialbudget($this->input->post('imaterial', TRUE),$this->input->post('dpp', TRUE))->result_array());
    }

    
    public function budgeting()
    {
        $filter = [];
        $cari = str_replace("'","",$this->input->get('q'));
        $data = $this->mmaster->budgeting($cari);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->id,
                'text' => $row->i_document.' [ '. $row->periode .' ]',
            );
        }
        echo json_encode($filter);
    }
    
    public function getmaterialbudget()
    {
        header("Content-Type: application/json", true);
        $i_budgeting = $this->input->post('i_budgeting', TRUE);
        echo json_encode($this->mmaster->getmaterialbudget($i_budgeting)->result_array());
    }

    public function getmaterialbudget_edit()
    {
        header("Content-Type: application/json", true);
        $i_budgeting = $this->input->post('i_budgeting', TRUE);
        echo json_encode($this->mmaster->getmaterialbudget_edit($i_budgeting)->result_array());
    }
    
    public function getmaterialprice()
    {
        header("Content-Type: application/json", true);
        $i_supplier = $this->input->post('i_supplier', TRUE);
        $i_material = $this->input->post('i_material', TRUE);
        $d_document = $this->input->post('d_document', TRUE);
        echo json_encode($this->mmaster->getmaterialprice($i_supplier, $i_material, $d_document)->result_array());
    }

    public function supplier()
    {
        $filter = [];
        $data = $this->mmaster->supplier(str_replace("'","",$this->input->get('q')), $this->input->get('i_material'));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->id,
                'text' => $row->i_supplier.' - '.$row->e_supplier_name,
            );
        }
        echo json_encode($filter);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        //var_dump($_POST);
        $i_budgeting = $this->input->post('i_budgeting', true);
        $kode_budgeting = $this->db->query("SELECT i_document from tm_budgeting where id = '$i_budgeting'", false)->row()->i_document;
        // $ibagian = $this->input->post('ibagian', true);
        // $ipp     = $this->input->post('ipp', true);
        // $dpp     = date('Y-m-d', strtotime($this->input->post('dpp', true)));
        // $dbp     = date('Y-m-d', strtotime($this->input->post('dbp', true)));
        // $remark  = $this->input->post('remark', true);
        $jml     = $this->input->post('jml', true);
        if ($jml > 0) {
            $id  = $this->input->post('id[]', true);
            $i_material  = $this->input->post('imaterial[]', true);
            $i_kode      = $this->input->post('ikode[]', true);
            $i_satuan    = $this->input->post('isatuan[]', true);
            $n_quantity  = str_replace(',','',$this->input->post('nquantity[]', true));
            $e_remark    = $this->input->post('eremark[]', true);
            $harga       = str_replace(',','',$this->input->post('harga_adj[]', true));
            $harga_sup_ar       = str_replace(',','',$this->input->post('harga_sup[]', true));
            $supplier    = $this->input->post('isupplier[]', true);

            $f_ppn    = $this->input->post('f_ppn[]', true);
            $n_ppn    = $this->input->post('n_ppn[]', true);
            $n_min_order    = $this->input->post('n_min_order[]', true);

            
                    
                    

            //$cekdata     = $this->mmaster->cek_kode($ipp,$ibagian);
            //if ($cekdata->num_rows()>0) {
            //    $data = array(
            //        'sukses' => false,
            //    );
            //}else{
                $this->db->trans_begin();
                //$id = $this->mmaster->runningid();
                $this->mmaster->updateheader($i_budgeting);
                $no = 0;
                foreach ($i_material as $imaterial) {
                    $imaterial  = $imaterial;
                    $id_item      = $id[$no];
                    $ikode      = $i_kode[$no];
                    $isatuan    = $i_satuan[$no];
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];

                    $fppn    = $f_ppn[$no];
                    $nppn    = $n_ppn[$no];
                    $nminorder    = $n_min_order[$no];
                    if ( !empty($supplier[$no])) {
                        $isupplier  = $supplier[$no];
                    }else{
                        $isupplier  = null;
                    }
                    if ( !empty($harga[$no])) {
                        $harga_adj  = $harga[$no];
                    }else{
                        $harga_adj  = 0;
                    }

                    if ( !empty($harga_sup_ar[$no])) {
                        $harga_sup  = $harga_sup_ar[$no];
                    }else{
                        $harga_sup  = 0;
                    }



                    if ($isupplier!='null') {
                        $this->mmaster->updatedetail($id_item,$isupplier,$harga_adj,$harga_sup, $eremark,$fppn, $nppn, $nminorder, $nquantity);
                    }
                    // if (($imaterial != '' || $imaterial != null) && $nquantity > 0) {
                    //     if($budgeting=='t'){
                    //         if($harga_adj > 0){
                               
                    //     }else{
                    //         $isupplier = null;
                    //         $harga_adj = null;
                    //         $this->mmaster->insertdetail($id,$ipp,$imaterial,$ikode,$isatuan,$nquantity,$eremark,$isupplier,$harga_adj, $harga_sup);
                    //     }
                    // }
                    $no++;
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title']);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $kode_budgeting,
                        'id'     => $i_budgeting,
                    );
                }
            //}
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        echo json_encode($data);
        // $this->load->view('pesan2', $data);
    }

    public function changestatus()
    {

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        //var_dump($_POST);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status Rupiah ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->getmaterialbudget_edit($this->uri->segment(4))->result(),//$this->mmaster->datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'number'     => "PP-".date('ym')."-123456",
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }
    
    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

         $i_budgeting = $this->input->post('i_budgeting', true);
        $kode_budgeting = $this->db->query("SELECT i_document from tm_budgeting where id = '$i_budgeting'", false)->row()->i_document;
        // $ibagian = $this->input->post('ibagian', true);
        // $ipp     = $this->input->post('ipp', true);
        // $dpp     = date('Y-m-d', strtotime($this->input->post('dpp', true)));
        // $dbp     = date('Y-m-d', strtotime($this->input->post('dbp', true)));
        // $remark  = $this->input->post('remark', true);
        $jml     = $this->input->post('jml', true);
        if ($jml > 0) {
            $id  = $this->input->post('id[]', true);
            $i_material  = $this->input->post('imaterial[]', true);
            $i_kode      = $this->input->post('ikode[]', true);
            $i_satuan    = $this->input->post('isatuan[]', true);
            $n_quantity  = str_replace(',','',$this->input->post('nquantity[]', true));
            $e_remark    = $this->input->post('eremark[]', true);
            $harga       = str_replace(',','',$this->input->post('harga_adj[]', true));
            $harga_sup_ar       = str_replace(',','',$this->input->post('harga_sup[]', true));
            $supplier    = $this->input->post('isupplier[]', true);


            $f_ppn    = $this->input->post('f_ppn[]', true);
            $n_ppn    = $this->input->post('n_ppn[]', true);
            $n_min_order    = $this->input->post('n_min_order[]', true);

            //$cekdata     = $this->mmaster->cek_kode($ipp,$ibagian);
            //if ($cekdata->num_rows()>0) {
            //    $data = array(
            //        'sukses' => false,
            //    );
            //}else{
                $this->db->trans_begin();
                //$id = $this->mmaster->runningid();
                $this->mmaster->updateheader($i_budgeting);
                $no = 0;
                foreach ($i_material as $imaterial) {
                    $imaterial  = $imaterial;
                    $id_item      = $id[$no];
                    $ikode      = $i_kode[$no];
                    $isatuan    = $i_satuan[$no];
                    $nquantity  = $n_quantity[$no];
                    $eremark    = $e_remark[$no];
                    
                    $fppn    = $f_ppn[$no];
                    $nppn    = $n_ppn[$no];
                    $nminorder    = $n_min_order[$no];
                    if ( !empty($supplier[$no])) {
                        $isupplier  = $supplier[$no];
                    }else{
                        $isupplier  = null;
                    }
                    if ( !empty($harga[$no])) {
                        $harga_adj  = $harga[$no];
                    }else{
                        $harga_adj  = 0;
                    }

                    if ( !empty($harga_sup_ar[$no])) {
                        $harga_sup  = $harga_sup_ar[$no];
                    }else{
                        $harga_sup  = 0;
                    }



                    if ($isupplier!='null') {
                        $this->mmaster->updatedetail($id_item,$isupplier,$harga_adj,$harga_sup, $eremark,$fppn, $nppn, $nminorder, $nquantity);
                    }

                    $no++;
                }
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data ' . $this->global['title']);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $kode_budgeting,
                        'id'     => $i_budgeting,
                    );
                }
            //}
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        // $this->load->view('pesan2', $data);
        echo json_encode($data);
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->dataheader($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->getmaterialbudget_edit($this->uri->segment(4))->result(),//$this->mmaster->datadetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function cetak()
    {
        
        $data = array(
            'folder' => $this->global['folder'],
            'title'  => "Cetak ".$this->global['title'],
            'id'     => $this->uri->segment(4),
            'data'   => $this->mmaster->dataheader($this->uri->segment(4)),
            'detail' => $this->mmaster->datadetail($this->uri->segment(4)),
        );

        $this->Logger->write('Cetak '.$this->global['title'].' Id : '.$this->uri->segment(4));

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }

    public function export_excel()
    {
        $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post("id", true);
        if ($id == "") $id = $this->uri->segment(4);

        $query = $this->mmaster->getmaterialbudget_edit($id);

        if ($query->num_rows()>0) {

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
                        'bottom' => ['borderStyle' => Border::BORDER_THIN],
                        'right' => ['borderStyle' => Border::BORDER_THIN],
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
            foreach (range('A', 'L') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Forcast Periode : ');
            $spreadsheet->getActiveSheet()->setTitle('FC');
            $spreadsheet->getActiveSheet()->mergeCells("A1:L1");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A2', 'No')
                ->setCellValue('B2', 'Kode Barang')
                ->setCellValue('C2', 'Nama Barang')
                ->setCellValue('D2', 'Satuan Pembelian')
                ->setCellValue('E2', 'Jml Kebutuhan Real')
                ->setCellValue('F2', 'Supplier')
                ->setCellValue('G2', 'Jenis Harga')
                ->setCellValue('H2', 'Min Order')
                ->setCellValue('I2', 'Jml Adjustment')
                ->setCellValue('J2', 'Harga Supplier')
                ->setCellValue('K2', 'Sub Total')
                ->setCellValue('L2', 'Keterangan');


            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A2:L2');

            $kolom = 3;
            $nomor = 1;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, removeEmoji($row->i_material))
                    ->setCellValue('C' . $kolom, removeEmoji($row->e_material_name))
                    ->setCellValue('D' . $kolom, removeEmoji($row->e_satuan_name))
                    ->setCellValue('E' . $kolom, removeEmoji($row->n_quantity_old))
                    ->setCellValue('F' . $kolom, removeEmoji($row->kode_supplier . " - " . $row->nama_supplier))
                    ->setCellValue('G' . $kolom, $row->inex)
                    ->setCellValue('H' . $kolom, $row->n_min_order)
                    ->setCellValue('I' . $kolom, $row->n_adjusment)
                    ->setCellValue('J' . $kolom, $row->v_price)
                    ->setCellValue('K' . $kolom, $row->sub_total_edit)
                    ->setCellValue('L' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':L' . $kolom);

                $kolom++;
                $nomor++;
            }
            $writer = new Xls($spreadsheet);
            $nama_file = "Budgeting_" . $id . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }
}
/* End of file Cform.php */
