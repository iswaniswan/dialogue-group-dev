<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
/* use PhpOffice\PhpSpreadsheet\Style\Fill; */
use PhpOffice\PhpSpreadsheet\Style\Style;
/* use PhpOffice\PhpSpreadsheet\Style\Alignment; */
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Cform extends CI_Controller
{

    /*----------  Created By A  ----------*/

    public $global = array();
    public $i_menu = '2090413';

    public function __construct()
    {
        parent::__construct();

        /*----------  Cek Session Di Helper  ----------*/
        cek_session();

        /*----------  Cek Menu Di Helper  ----------*/
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /*----------  Deklarasi Session, Folder dan Nama / Judul Menu  ----------*/
        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->level            = $this->session->i_level;
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/
        $this->load->model($this->global['folder'] . '/mmaster');

        /*----------  Load Librabry  ----------*/
        $this->load->library('fungsi');
    }

    /*----------  DEFAULT CONTROLLERS  ----------*/

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    /*----------  DAFTAR DATA  ----------*/

    public function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->data($this->global['folder'], $this->i_menu, $dfrom, $dto);
    }

    /*----------  REDIRECT KE FORM TAMBAH  ----------*/
    public function create()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            // 'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => "UJ-" . date('ym') . "-123456",
        );
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }
    
    public function bagian()
    {
        $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->bagian($cari);
            if ($data->num_rows()>0) {
                $group   = [];
                $arr     = [];
                foreach ($data->result() as $key) {
                    $arr[] = $key->company_name;
                }
                $unique_data = array_unique($arr);
                foreach($unique_data as $val) {
                    $child  = [];
                    foreach ($data->result() as $row) {
                        if ($val==$row->company_name) {
                            $child[] = array(
                                'id' => $row->i_bagian.'|'.$row->id_company, 
                                'text' => $row->e_bagian_name, 
                                'title' => $row->company_name,
                            );
                        }
                    }
                    $filter[] = array(
                        'id' => 0,
                        'text' => strtoupper($val),
                        'children' => $child
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
            echo json_encode($filter);
    }

    /** GET DATA REFERENSI HEADER */
    public function get_referensi()
    {
        $filter = [];
        $cari = str_replace("'", "", $this->input->get('q'));
        $ibagian = $this->input->get('ibagian');
        $data = $this->mmaster->get_data_referensi($cari, $ibagian);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id,
                'text'  => $row->i_document . ' - [' . $row->periode . ']',
            );
        }
        echo json_encode($filter);
    }

    public function get_detail_referensi()
    {
        header("Content-Type: application/json", true);
        $id = $this->input->post('id');
        $ibagian = $this->input->post('ibagian');
        $query = $this->mmaster->get_detail_referensi($id, $ibagian);
        $fc_jahit = 0;
        $fc_jahit_sisa = 0;
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                $fc_jahit += $key->n_quantity;
                $fc_jahit_sisa += $key->n_quantity_sisa;
            }
        }
        $data = array(
            'detail'   => $query->result_array(),
            'n_quantity' => $fc_jahit,
            'n_quantity_sisa' => $fc_jahit_sisa,
        );
        echo json_encode($data);
    }

    /*----------  REDIRECT LIST REFERENSI  ----------*/

    /* public function indexx()
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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlistreferensi', $data);
    } */

    /*----------  DAFTAR DATA REFERENSI  ----------*/

    /* public function datareferensi()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->datareferensi($this->global['folder'],$this->i_menu,$dfrom,$dto);
    } */

    /*----------  PROSES DATA  ----------*/

    /* public function prosesdata() 
    {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'tahun'      => $this->uri->segment(7),
            'bulan'      => $this->uri->segment(8),
            'bagian'     => $this->mmaster->bagian()->result(),
            'datadetail' => $this->mmaster->datadetail($this->uri->segment(4))->result(),
            // 'datadetaill'=> $this->mmaster->datadetaill($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->datadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "UJ-".date('ym')."-123456",
        );
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    } */

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK KODE SUDAH ADA / BELUM  ----------*/

    public function cekkode()
    {
        if ($this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE))->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument          = $this->input->post('idocument', TRUE);
        $idreferensi        = $this->input->post('idforecast', TRUE);
        $iperiode           = $this->input->post('iperiode', TRUE);

        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     != "") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagiancompany     = explode('|', $this->input->post('ibagian', TRUE));
        $ibagian            = $ibagiancompany[0];
        $idcompanybagian    = $ibagiancompany[1];
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);

        if ($idocument != '' && $ddocument != '' && $ibagian != '' && $idreferensi != '' && $jml > 0) {
            /* $cekkode = $this->mmaster->cek_kode($idreferensi, $ibagian);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            } else {
            } */
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            /** Simpan Data Header */
            $this->mmaster->insertheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $iperiode, $eremark, $idcompanybagian);

            /** Simpan Data Detail Yang Barang Jadi */

            for ($i = 0; $i < $jml; $i++) {
                $idproduct    = $this->input->post('idproduct' . $i, FALSE);
                $n_uraian_jahit   = str_replace(",", "", $this->input->post('n_uraian_jahit' . $i, TRUE));
                if($n_uraian_jahit == '') {
                    $n_uraian_jahit = null;
                }
                /* $n_quantity   = str_replace(",","",$this->input->post('n_quantity'.$i, TRUE));
                $n_internal  = str_replace(",","",$this->input->post('n_internal'.$i, TRUE));
                $n_eksternal    = str_replace(",","",$this->input->post('n_eksternal'.$i, TRUE)); */
                $e_remark     = $this->input->post('e_remark' . $i, TRUE);


                // if ($n_fcjahit > 0 && ($idproduct!=null || $idproduct!='')) {
                    $this->mmaster->insertdetailbase($id, $idproduct, $n_uraian_jahit, $e_remark);
                // }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'   => $idocument,
                    'id'     => $id
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
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/

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
            // 'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4), $this->uri->segment(7), $this->uri->segment(8))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "UJ-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id                 = $this->input->post('id', TRUE);
        $idocumentold       = $this->input->post('idocumentold', TRUE);
        $ibagianold         = $this->input->post('ibagianold', TRUE);

        $idocument          = $this->input->post('idocument', TRUE);
        $idreferensi        = $this->input->post('idforecast', TRUE);
        $idreferensiold     = $this->input->post('idforecastold', TRUE);
        $iperiode           = $this->input->post('iperiode', TRUE);

        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     != "") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagiancompany     = explode('|', $this->input->post('ibagian', TRUE));
        $ibagian            = $ibagiancompany[0];
        $idcompanybagian    = $ibagiancompany[1];
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);


        if ($id != '' && $idocument != '' && $ddocument != '' && $ibagian != '' && $idreferensi != '' && $jml > 0) {
            $cekkode = $this->mmaster->cek_kode_edit($idreferensi, $ibagian, $idreferensiold, $ibagianold);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                /** Update Header */
                $this->mmaster->updateheader($id, $idocument, $ddocument, $ibagian, $idreferensi, $iperiode, $eremark, $idcompanybagian);

                /** Delete Item Sebelum Insert */
                $this->mmaster->delete($id);

                /** Simpan Data Detail Yang Barang Jadi */
                /* for ($i = 0; $i < $jml; $i++) {
                    $idproduct    = $this->input->post('idproduct'.$i, FALSE);
                    $n_quantity   = str_replace(",","",$this->input->post('n_quantity'.$i, TRUE));
                    $n_internal  = str_replace(",","",$this->input->post('n_internal'.$i, TRUE));
                    $n_eksternal    = str_replace(",","",$this->input->post('n_eksternal'.$i, TRUE));
                    $e_remark     = $this->input->post('e_remark'.$i, TRUE);
                    //if ($n_fcjahit > 0 && ($idproduct!=null || $idproduct!='')) {
                        $this->mmaster->insertdetailbase($id,$idproduct,$n_quantity,$n_internal,$n_eksternal,$e_remark);
                    //}
                } */

                for ($i = 0; $i < $jml; $i++) {
                    $idproduct    = $this->input->post('idproduct' . $i, FALSE);
                    $n_uraian_jahit   = str_replace(",", "", $this->input->post('n_uraian_jahit' . $i, TRUE));
                    /* $n_quantity   = str_replace(",","",$this->input->post('n_quantity'.$i, TRUE));
                    $n_internal  = str_replace(",","",$this->input->post('n_internal'.$i, TRUE));
                    $n_eksternal    = str_replace(",","",$this->input->post('n_eksternal'.$i, TRUE)); */
                    $e_remark     = $this->input->post('e_remark' . $i, TRUE);


                    //if ($n_fcjahit > 0 && ($idproduct!=null || $idproduct!='')) {
                    $this->mmaster->insertdetailbase($id, $idproduct, $n_uraian_jahit, $e_remark);
                    //}
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
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
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA MENU APPROVE  ----------*/

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
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            // 'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem_approve($this->uri->segment(4), $this->uri->segment(7), $this->uri->segment(8))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "UJ-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  MEMBUKA FORM DETAIL  ----------*/

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            // 'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem_approve($this->uri->segment(4), $this->uri->segment(7), $this->uri->segment(8))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "UJ-" . date('ym') . "-123456",
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

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
        }
    }

    /*----------  EXPORT EXCEL URAIAN JAHIT  ----------*/
    public function export_excel()
    {
        $data = check_role($this->i_menu, 6);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post("ibagian", true);
        $tahun      = $this->input->post("tahun", true);
        $bulan      = $this->input->post("bulan", true);

        $dfrom      = $this->input->post("dfrom", true);
        $dto        = $this->input->post("dto", true);

        $id         = $this->input->post("id", true);

        if ($ibagian == "") $ibagian = $this->uri->segment(4);
        if ($tahun == "") $tahun = $this->uri->segment(5);
        if ($bulan == "") $bulan = $this->uri->segment(6);

        if ($dfrom == "") $dfrom = $this->uri->segment(7);
        if ($dto == "") $dto = $this->uri->segment(8);

        if ($id == "") $id = $this->uri->segment(9);

        // $query = $this->mmaster->datadetail($this->company, $tahun . $bulan, $id);
        $qHeader = $this->mmaster->datadetail_header($id)->row();
        $query = $this->mmaster->datadetail_edit($id);

        if ($query) {

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
                        'top' => ['borderStyle' => Border::BORDER_THIN],
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
            foreach (range('A', 'I') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Uraian Jahit Periode : ' . $tahun . $bulan)
                ->setCellValue('A2', 'Bagian : ' . $qHeader->e_bagian_name)
                ->setCellValue('A3', 'Nomor Dokumen : ' . $qHeader->i_document)
                ->setCellValue('A4', 'Status Dokumen : ' . $qHeader->e_status_name)
                ->setCellValue('A5', 'Dokumen Referensi : ' . $qHeader->no_dokumen_ref . '- [' . $qHeader->tgl_dokumen_ref . ']');
            $spreadsheet->getActiveSheet()->setTitle('Uraian Jahit' . $tahun . $bulan);
            $spreadsheet->getActiveSheet()->mergeCells("A1:I1");
            $spreadsheet->getActiveSheet()->mergeCells("A2:I2");
            $spreadsheet->getActiveSheet()->mergeCells("A3:I3");
            $spreadsheet->getActiveSheet()->mergeCells("A4:I4");
            $spreadsheet->getActiveSheet()->mergeCells("A5:I5");
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A7', 'No')
                ->setCellValue('B7', 'Kode Barang')
                ->setCellValue('C7', 'Nama Barang')
                ->setCellValue('D7', 'Warna')
                ->setCellValue('E7', 'Brand')
                ->setCellValue('F7', 'Series')
                ->setCellValue('G7', 'Qty FC Jahit')
                ->setCellValue('H7', 'Qty Urai Jahit')
                ->setCellValue('I7', 'Keterangan');

            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, 'A7:I7');

            $kolom = 8;
            $nomor = 1;
            $totalQtyFC = 0;
            $totalQtyUrai = 0;
            foreach ($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $kolom, $nomor)
                    ->setCellValue('B' . $kolom, $row->i_product_wip)
                    ->setCellValue('C' . $kolom, $row->e_product_name)
                    ->setCellValue('D' . $kolom, $row->e_color_name)
                    ->setCellValue('E' . $kolom, $row->e_brand_name)
                    ->setCellValue('F' . $kolom, $row->e_style_name)
                    ->setCellValue('G' . $kolom, $row->quantity_forecast)
                    ->setCellValue('H' . $kolom, $row->quantity_urai)
                    ->setCellValue('I' . $kolom, $row->e_remark);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'A' . $kolom . ':I' . $kolom);
                $totalQtyFC += $row->quantity_forecast;
                $totalQtyUrai += $row->quantity_urai;
                $kolom++;
                $nomor++;
            }

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('G6', 'Total : ' . $totalQtyFC)
                ->setCellValue('H6', 'Total : ' . $totalQtyUrai);
            $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, 'G6:H6');
            $writer = new Xls($spreadsheet);
            $nama_file = "Uraian_Jahit_" . $tahun . $bulan . ".xls";
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $nama_file . '');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }
    }

    public function export()
    {
        /** Parameter */
        $id = $this->uri->segment(6);
        $ddocument = $this->uri->segment(7);
        $ibagian = urldecode($this->uri->segment(8));
        $dsplit = explode('-',$ddocument);
        $nama_file = "";
        /** End Parameter */

        /** Style And Create New Spreedsheet */
        $spreadsheet  = new Spreadsheet;
        $sharedTitle = new Style();
        $sharedStyle1 = new Style();
        $sharedStyle2 = new Style();
        $sharedStyle3 = new Style();
        /* $conditional3 = new Conditional(); */
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
            [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => TRUE
            ]
        );

        $sharedTitle->applyFromArray(
            [
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'size'   => 26
                ],
            ]
        );

        $sharedStyle1->applyFromArray(
            [
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'italic' => false,
                    'size'   => 14
                ],
            ]
        );

        $sharedStyle2->applyFromArray(
            [
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => false,
                    'italic' => false,
                    'size'   => 11
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
            ]

        );

        $sharedStyle3->applyFromArray(
            [
                'font' => [
                    'name'   => 'Arial',
                    'bold'   => true,
                    'italic' => false,
                    'size'   => 11,
                ],
                'borders' => [
                    'top'    => ['borderStyle' => Border::BORDER_THIN],
                    'bottom' => ['borderStyle' => Border::BORDER_THIN],
                    'left'   => ['borderStyle' => Border::BORDER_THIN],
                    'right'  => ['borderStyle' => Border::BORDER_THIN]
                ],
                'alignment' => [
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]
        );
        /** End Style */

        $abjad  = range('A', 'Z');
        $zero = 1;
        $satu = 2;
        $dua = 3;

        $validation = $spreadsheet->getActiveSheet()->getCell("AZ1")->getDataValidation();
        $validation->setType(DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Input is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Number Value allowed");

        /** Start Sheet */
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue("A$zero", $id)
            ->setCellValue("A$satu", strtoupper($this->session->e_company_name))
            ->setCellValue("A$dua", "FORMAT UPLOAD URAIAN JAHIT");
        $spreadsheet->getActiveSheet()->setTitle('Format Uraian Jahit');

        $validation = $spreadsheet->getActiveSheet()->getCell('K1')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
        $validation->setAllowBlank(true);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setErrorTitle('Input error');
        $validation->setError('Number is not allowed!');
        $validation->setPromptTitle('Allowed input');
        $validation->setPrompt("Only Value number allowed");

        $h = 4;
        $header = [
            '#',
            'ID BARANG',
            'KODE',
            'NAMA BARANG',
            'WARNA',
            'FC JAHIT',
            'QTY BELUM DI URAI',
            'QTY URAI',
            'KETERANGAN'
        ];
        for ($i = 0; $i < count($header); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($abjad[$i] . $h, $header[$i]);
            $spreadsheet->getActiveSheet()->getColumnDimension($abjad[$i])->setAutoSize(true);
        }

        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle3, "A" . $h . ":I" . $h);
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "A2:I2");
        $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle1, "A3:I3");
        $spreadsheet->getActiveSheet()->getStyle('A' . $h . ':I' . $h)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('CEE7FF');
        $spreadsheet->getActiveSheet()->freezePane($abjad[0] . ($h + 1));
        $spreadsheet->getActiveSheet()->mergeCells('A2:I2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:I3');
        $j = 4;
        $i = 0;
        $no = 1;
        $sql = $this->mmaster->get_detail_referensi($id, $ibagian);
        if ($sql->num_rows() > 0) {
            $group = "";
            foreach($sql->result() as $row) {
                if ($group != $row->e_type_name) {
                    // $spreadsheet->setActiveSheetIndex(0)->setCellValue("A". $j, "#");
                    $j = $j + 1;
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue("A". $j, $row->e_type_name);
                    $spreadsheet->getActiveSheet()->mergeCells('A'. $j .':I' . $j);
                    $spreadsheet->getActiveSheet()->getStyle('A' . $j . ':I' . $j)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('bbbbbb');
                    $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . $j . ":I". $j);
                    $no = 1;
                }
                $group = $row->e_type_name;
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("A". ($j + 1), $no);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("B". ($j + 1), $row->id_product_wip);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("C". ($j + 1), $row->i_product_wip);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("D". ($j + 1), $row->e_product_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("E". ($j + 1), $row->e_color_name);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("F". ($j + 1), $row->n_quantity);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("G". ($j + 1), $row->n_quantity_sisa);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("H". ($j + 1), "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("I". ($j + 1), "");
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("Y". ($j + 1), $row->grup);
                $spreadsheet->setActiveSheetIndex(0)->setCellValue("Z". ($j + 1), $row->e_type_name);
                $spreadsheet->getActiveSheet()->duplicateStyle($sharedStyle2, "A" . ($j + 1) . ":I". ($j + 1));
                $spreadsheet->getActiveSheet()->setDataValidation("H" . ($j + 1), $validation);
                $j++;
                $no++;
                $i++;
            }
        }
        /* $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setPassword('THEPASSWORD');
        $hrow = $spreadsheet->getActiveSheet(0)->getHighestDataRow('A'); */

        /* $spreadsheet->getActiveSheet()->getStyle("H6:H" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle("I6:I" . $hrow)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $spreadsheet->getActiveSheet()->getStyle("A4:I4")->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED); */

        /** End Sheet */

        $writer = new Xls($spreadsheet);
        $nama_file = "Format_Uraian_Jahit_Referensi_FC_$id.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $nama_file . '');
        header('Cache-Control: max-age=0');
        ob_end_clean();
        ob_start();
        $writer->save('php://output');
    }

    public function load()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        /* $ibagian = $this->input->post('ibagian', TRUE);
        $i_so = $this->input->post('i_so', TRUE);
        $ddocument = $this->input->post('ddocument', TRUE);
        $dfrom = $this->input->post('dfrom', TRUE);
        $dto = $this->input->post('dto', TRUE); */
        // $abjadBanyak = array();
        // foreach (excelColumnRange('A', 'ZZ') as $value) {
        //     array_push($abjadBanyak, $value);
        // }
        $cellMulaiTanggal = 7;
        $idforecast = $this->input->post('idforecast');
        $filename = $this->id_company . "_Uraian_Jahit_" . $idforecast . ".xls";
        $aray = array();
        $fc_jahit = 0;
        $fc_jahit_sisa = 0;
        $fc_jahit_urai = 0;

        $config = array(
            'upload_path'   => "./import/uraianjahit/",
            'allowed_types' => "xls",
            'file_name'     => $filename,
            'overwrite'     => true
        );

        $this->load->library('upload', $config);
        if ($this->upload->do_upload("userfile")) {
            $data = array('upload_data' => $this->upload->data());

            $inputFileName = "./import/uraianjahit/" . $filename;
            $spreadsheet   = IOFactory::load($inputFileName);
            $worksheet     = $spreadsheet->getActiveSheet();
            $sheet         = $spreadsheet->getSheet(0);
            $hrow          = $sheet->getHighestDataRow('A');
            $id_referensi  = $spreadsheet->getActiveSheet()->getCell('A1')->getValue();
            $data = [];
            $data2 = [];
            if ($id_referensi == (int)$idforecast) {
                for ($n = 5; $n <= $hrow; $n++) {
                    $id_product = strtoupper($spreadsheet->getActiveSheet()->getCell('B' . $n)->getValue());
                    if ($id_product != "") {
                        $aray[] = array(
                            'id'                => $id_product,
                            'i_product_wip'     => strtoupper($spreadsheet->getActiveSheet()->getCell('C' . $n)->getValue()),
                            'e_product_name' => strtoupper($spreadsheet->getActiveSheet()->getCell('D' . $n)->getValue()),
                            'e_color_name'      => strtoupper($spreadsheet->getActiveSheet()->getCell('E' . $n)->getValue()),
                            'n_quantity'        => strtoupper($spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue()),
                            'n_quantity_sisa'   => strtoupper($spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue()),
                            'n_quantity_urai'   => strtoupper($spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue()),
                            'keterangan'   => strtoupper($spreadsheet->getActiveSheet()->getCell('I' . $n)->getValue()),
                            'grup'   => strtoupper($spreadsheet->getActiveSheet()->getCell('Y' . $n)->getValue()),
                            'e_type_name'   => strtoupper($spreadsheet->getActiveSheet()->getCell('Z' . $n)->getValue()),
                            // 'e_remark'          => $e_remark,
                        );
                    }
                    $fc_jahit += (int) strtoupper($spreadsheet->getActiveSheet()->getCell('F' . $n)->getValue());
                    $fc_jahit_sisa += (int) strtoupper($spreadsheet->getActiveSheet()->getCell('G' . $n)->getValue());
                    $fc_jahit_urai += (int) strtoupper($spreadsheet->getActiveSheet()->getCell('H' . $n)->getValue());
                    // if($cellMulaiTanggal < 52) {
                    //     $cellMulaiTanggal++;
                    // }
                }
                // echo '<pre>' . var_export($aray, true) . '</pre>';
                // var_dump($data2);
                // usort($aray, function ($b, $a) {
                //     return $b['d_schedule'] <=> $a['d_schedule'];
                // });
                $param = array(
                    'folder'        => $this->global['folder'],
                    'title'         => "Tambah " . $this->global['title'],
                    'title_list'    => $this->global['title'],
                    'detail'    => $aray,
                    'status'        => 'berhasil',
                    'sama'          => true,
                    'n_quantity' => $fc_jahit,
                    'n_quantity_sisa' => $fc_jahit_sisa,
                    'n_quantity_urai' => $fc_jahit_urai
                );
            } else {
                $param = array(
                    'folder'        => $this->global['folder'],
                    'title'         => "Tambah " . $this->global['title'],
                    'title_list'    => $this->global['title'],
                    'datadetail'    => $aray,
                    'status'        => 'gagal',
                    'sama'          => false
                );
            }
            echo json_encode($param);
        } else {
            $param =  array(
                'status'        => 'gagal',
                'datadetail'    => $aray,
                'sama'          => false
            );
            echo json_encode($param);
        }
    }
}
/* End of file Cform.php */