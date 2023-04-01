<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2050113';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->departement  = $this->session->i_departement;
        $this->company      = $this->session->id_company;
        $this->level        = $this->session->i_level;
        $this->username     = $this->session->username;
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'] . '/mmaster');
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

    /*----------  LIST DATA SJ  ----------*/

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

        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    /*----------  REDIRECT KE FORM TAMBAH  ----------*/

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SJ-" . date('ym') . "-000001",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK NO DOKUMENT SUDAH ADA  ----------*/

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  CARI AREA BERDASARKAN JENIS SPB  ----------*/

    public function area()
    {
        $filter = [];
        $data   = $this->mmaster->area(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => $key->i_area . ' - ' . $key->e_area,
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

    /*----------  CARI CUSTOMER BERDASARKAN JENIS SPB DAN AREA  ----------*/

    public function customer()
    {
        $filter = [];
        $iarea  = $this->input->get('iarea', TRUE);
        if (($iarea != '' || $iarea != null)) {
            $data   = $this->mmaster->customer(str_replace("'", "", $this->input->get('q')), $iarea);
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $key) {
                    $filter[] = array(
                        'id'   => $key->id,
                        'text' => '(' . $key->i_customer . ') - ' . $key->e_customer_name,
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Area Harus Dipilih!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  CARI REFERENSI BERDASARKAN JENIS SPB DAN CUSTOMER  ----------*/

    public function referensi()
    {
        $filter      = [];
        $icustomer   = $this->input->get('icustomer', TRUE);
        $iarea       = $this->input->get('iarea', TRUE);
        if (($icustomer != '' || $icustomer != null) && ($iarea != '' || $iarea != null)) {
            $data   = $this->mmaster->referensi(str_replace("'", "", $this->input->get('q')), $icustomer, $iarea);
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $key) {
                    $filter[] = array(
                        'id'   => $key->id,
                        'text' => $key->i_document,
                    );
                }
            } else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Area dan Pelanggan Harus Dipilih!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL REFERENSI  ----------*/

    public function getdetailrefeks()
    {
        header("Content-Type: application/json", true);
        $id     = $this->input->post('id');
        $d_document     = $this->input->post('d_document');
        $i_customer     = $this->input->post('i_customer');
        $query  = array(
            'head'   => $this->mmaster->getdetailrefeks($id,$i_customer)->row(),
            'detail' => $this->mmaster->getdetailrefeks($id,$i_customer,$d_document)->result_array()
        );
        echo json_encode($query);
    }

    /*----------  SIMPAN DATA SJ  ----------*/

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian          = $this->input->post("ibagian", TRUE);
        $isj              = $this->input->post("isj", TRUE);
        $ddocument        = $this->input->post("ddocument", TRUE);
        if ($ddocument) {
            $datedocument = date('Y-m-d', strtotime($ddocument));
        }
        $idharga          = $this->input->post('idharga', TRUE);
        $idarea           = $this->input->post('iarea', TRUE);
        $icustomer        = $this->input->post('icustomer', TRUE);
        $ecustomer        = $this->input->post('ecustomer', TRUE);
        $ncustop          = $this->input->post('ncustop', TRUE);
        $ireferensi       = $this->input->post('ireferensi', TRUE);
        $eremark          = $this->input->post('eremark', TRUE);
        $jml              = $this->input->post('jml', TRUE);
        if ($idharga != '' && $ibagian != '' && $icustomer != '' && $jml > 0) {
            $cekkode = $this->mmaster->cek_kode($isj, $ibagian);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $isj,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $isj, $datedocument, $ibagian, $ireferensi, $icustomer, $ecustomer, $ncustop, $idarea, $idharga, $eremark);
                $no = 0;
                foreach ($this->input->post('idproduct[]', TRUE) as $iproduct) {
                    $nquantity     = str_replace(',', '', $this->input->post('nquantity[]', TRUE))[$no];
                    $vprice        = str_replace(',', '', $this->input->post('vprice[]', TRUE))[$no];
                    $ndiskon1      = str_replace(',', '', $this->input->post('ndiskon1[]', TRUE))[$no];
                    $ndiskon2      = str_replace(',', '', $this->input->post('ndiskon2[]', TRUE))[$no];
                    $ndiskon3      = str_replace(',', '', $this->input->post('ndiskon3[]', TRUE))[$no];
                    $vdiskonplus   = str_replace(',', '', $this->input->post('vdiskonplus[]', TRUE))[$no];
                    $edesc         = $this->input->post('edesc[]', TRUE)[$no];
                    if (/* $nquantity > 0 &&  */($iproduct != null || $iproduct != '')) {
                        $jumlah         = $nquantity * $vprice;
                        $vdiskon1       = $jumlah * ($ndiskon1 / 100);
                        $vdiskon2       = ($jumlah - $vdiskon1) * ($ndiskon2 / 100);
                        $vdiskon3       = ($jumlah - $vdiskon1 - $vdiskon2) * ($ndiskon3 / 100);
                        $vdiskontotal   = $vdiskon1 + $vdiskon2 + $vdiskon3 + $vdiskonplus;
                        $vtotal         = $jumlah - $vdiskontotal;
                        $this->mmaster->insertdetail($id, $ireferensi, $iproduct, $nquantity, $vprice, $ndiskon1, $ndiskon2, $ndiskon3, $vdiskon1, $vdiskon2, $vdiskon3, $vdiskonplus, $vdiskontotal, $vtotal, $edesc);
                    }
                    $no++;
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $isj,
                        'id'     => $id
                    );
                } else {
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $isj,
                        'id'     => $id
                    );
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
                }
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => 'Gagal',
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  REDIRECT KE FORM EDIT ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $d_document = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'number'        => "BBK-" . date('ym') . "-000001",
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($id)->row(),
            'datadetail'    => $this->mmaster->baca_detail($id,$d_document)->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    /*----------  UPDATE DATA SJ  ----------*/

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id               = $this->input->post("id", TRUE);
        $ibagian          = $this->input->post("ibagian", TRUE);
        $ibagianold       = $this->input->post("xbagian", TRUE);
        $isj              = $this->input->post("isj", TRUE);
        $isjold           = $this->input->post("isjold", TRUE);
        $ddocument        = $this->input->post("ddocument", TRUE);
        if ($ddocument) {
            $datedocument = date('Y-m-d', strtotime($ddocument));
        }
        $idharga          = $this->input->post('idharga', TRUE);
        $idarea           = $this->input->post('iarea', TRUE);
        $icustomer        = $this->input->post('icustomer', TRUE);
        $ecustomer        = $this->input->post('ecustomer', TRUE);
        $ncustop          = $this->input->post('ncustop', TRUE);
        $ireferensi       = $this->input->post('ireferensi', TRUE);
        $eremark          = $this->input->post('eremark', TRUE);
        $jml              = $this->input->post('jml', TRUE);
        if ($id != '' && $idharga != '' && $ibagian != '' && $icustomer != '' && $jml > 0) {
            $cekkode = $this->mmaster->cek_kode_edit($isj, $ibagian, $isjold, $ibagianold);
            if ($cekkode->num_rows() > 0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $isj,
                    'id'     => null
                );
            } else {
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $isj, $datedocument, $ibagian, $ireferensi, $icustomer, $ecustomer, $ncustop, $idarea, $idharga, $eremark);
                $this->mmaster->deletedetail($id);
                $no = 0;
                foreach ($this->input->post('idproduct[]', TRUE) as $iproduct) {
                    $nquantity     = str_replace(',', '', $this->input->post('nquantity[]', TRUE))[$no];
                    $vprice        = str_replace(',', '', $this->input->post('vprice[]', TRUE))[$no];
                    $ndiskon1      = str_replace(',', '', $this->input->post('ndiskon1[]', TRUE))[$no];
                    $ndiskon2      = str_replace(',', '', $this->input->post('ndiskon2[]', TRUE))[$no];
                    $ndiskon3      = str_replace(',', '', $this->input->post('ndiskon3[]', TRUE))[$no];
                    $vdiskonplus   = str_replace(',', '', $this->input->post('vdiskonplus[]', TRUE))[$no];
                    $edesc         = $this->input->post('edesc[]', TRUE)[$no];
                    if (/* $nquantity > 0 &&  */($iproduct != null || $iproduct != '')) {
                        $jumlah         = $nquantity * $vprice;
                        $vdiskon1       = $jumlah * ($ndiskon1 / 100);
                        $vdiskon2       = ($jumlah - $vdiskon1) * ($ndiskon2 / 100);
                        $vdiskon3       = ($jumlah - $vdiskon1 - $vdiskon2) * ($ndiskon3 / 100);
                        $vdiskontotal   = $vdiskon1 + $vdiskon2 + $vdiskon3 + $vdiskonplus;
                        $vtotal         = $jumlah - $vdiskontotal;
                        $this->mmaster->insertdetail($id, $ireferensi, $iproduct, $nquantity, $vprice, $ndiskon1, $ndiskon2, $ndiskon3, $vdiskon1, $vdiskon2, $vdiskon3, $vdiskonplus, $vdiskontotal, $vtotal, $edesc);
                    }
                    $no++;
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $isj,
                        'id'     => $id
                    );
                } else {
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $isj,
                        'id'     => $id
                    );
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
                }
            }
        } else {
            $data = array(
                'sukses' => false,
                'kode'   => 'Gagal',
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  REDIRECT KE FORM DETAIL ----------*/

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }


        $id         = $this->uri->segment(4);
        $d_document = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        // $idtypespb  = $this->uri->segment(8);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Detail " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'data'          => $this->mmaster->baca_header($id)->row(),
            'datadetail'    => $this->mmaster->baca_detail($id, $d_document)->result(),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  REDIRECT KE FORM APPROVE ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $d_document = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);
        // $idtypespb  = $this->uri->segment(8);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->baca_header($id)->row(),
            'datadetail'    => $this->mmaster->baca_detail($id, $d_document)->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
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

    /*----------  SIMPAN SPB TURUNAN  ----------*/

    public function insertspbnew()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        // var_dump($_POST);
        // die;

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagianreff', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp = explode('-', $ddocument);
            $day = $tmp[0];
            $month = $tmp[1];
            $year = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $ijenis         = $this->input->post('ijenis', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $icustomer      = $this->input->post('icustomer', TRUE);
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $dreferensi     = $this->input->post('dreferensi', TRUE);
        if ($dreferensi) {
            $datereferensi = date('Y-m-d', strtotime($dreferensi));
        }
        $ndiskontotal   = $this->input->post('ndiskontotal', TRUE);
        $nkotor         = $this->input->post('nkotor', TRUE);
        $nbersih        = $this->input->post('nbersih', TRUE);
        $vdpp           = $this->input->post('vdpp', TRUE);
        $vppn           = $this->input->post('vppn', TRUE);

        $nkotorold      = $this->input->post('nkotorold', TRUE);
        $nbersihold     = $this->input->post('nbersihold', TRUE);
        $vdppold        = $this->input->post('vdppold', TRUE);
        $vppnold        = $this->input->post('vppnold', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $idkodeharga    = $this->input->post('idkodeharga', TRUE);
        $ejenisspb      = $this->input->post('ejenisspb', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $i_product      = $this->input->post('idproduct[]', TRUE);
        $n_sisa         = str_replace(',', '', $this->input->post('sisa[]', TRUE));
        $n_sisab        = str_replace(',', '', $this->input->post('sisab[]', TRUE));
        $n_quantity     = str_replace(',', '', $this->input->post('nquantity[]', TRUE));
        $v_price        = $this->input->post('vprice[]', TRUE);
        $_1_ndiskon     = $this->input->post('1ndiskon[]', TRUE);
        $_2_ndiskon     = $this->input->post('2ndiskon[]', TRUE);
        $_3_ndiskon     = $this->input->post('3ndiskon[]', TRUE);
        $_1_vdiskon     = $this->input->post('1vdiskon[]', TRUE);
        $_2_vdiskon     = $this->input->post('2vdiskon[]', TRUE);
        $_3_vdiskon     = $this->input->post('3vdiskon[]', TRUE);
        $v_diskonadd    = $this->input->post('vdiskonadd[]', TRUE);
        $vt_diskon      = $this->input->post('vtdiskon[]', TRUE);
        $v_total        = $this->input->post('vtotal[]', TRUE);
        $v_totalbersih  = $this->input->post('vtotalbersih[]', TRUE);

        $v_totalold        = $this->input->post('vtotalold[]', TRUE);
        $v_totalbersihold  = $this->input->post('vtotalbersihold[]', TRUE);
        $e_desc            = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin();
        $idbaru     = $this->mmaster->runningidspb($ijenis);
        $idocument  = $this->mmaster->runningnumberspb(date('ym', strtotime($this->input->post('ddocument', TRUE))), date('Y', strtotime($this->input->post('ddocument', TRUE))), $this->input->post('ibagianreff', TRUE), $ijenis);

        $data  = $this->mmaster->insertspbnew($idbaru, $ibagian, $idocument, $datedocument, $iarea, $icustomer, $ireferensi, $datereferensi, $ndiskontotal, $nkotor, $nbersih, $vdpp, $vppn, $eremark, $ijenis, $idkodeharga, $ejenisspb);
        $data2 = '';
        $data3 = $this->mmaster->updateheaderspbold($ireferensi, $datereferensi, $nkotorold, $nbersihold, $vdppold, $vppnold, $ijenis);
        $data5 = $this->mmaster->updatestatus($id);

        $no = 0;
        foreach ($i_product as $iproduct) {
            $iproduct           = $iproduct;
            $nsisa              = $n_sisa[$no];
            $nsisab             = $n_sisab[$no];
            $nquantity          = $n_quantity[$no];
            $vprice             = $v_price[$no];
            $_1ndiskon          = $_1_ndiskon[$no];
            $_2ndiskon          = $_2_ndiskon[$no];
            $_3ndiskon          = $_3_ndiskon[$no];
            $_1vdiskon          = $_1_vdiskon[$no];
            $_2vdiskon          = $_2_vdiskon[$no];
            $_3vdiskon          = $_3_vdiskon[$no];
            $vdiskonadd         = $v_diskonadd[$no];
            $vtdiskon           = $vt_diskon[$no];
            $vtotal             = $v_total[$no];
            $vtotalbersih       = $v_totalbersih[$no];

            $vtotalold          = $v_totalold[$no];
            $vtotalbersihold    = $v_totalbersihold[$no];
            $edesc              = $e_desc[$no];

            if ($nsisab > 0) {
                $data2 = $this->mmaster->insertdetailspb($idbaru, $iproduct, $nsisa, $vprice, $_1ndiskon, $_2ndiskon, $_3ndiskon, $_1vdiskon, $_2vdiskon, $_3vdiskon, $vdiskonadd, $vtdiskon, $vtotal, $vtotalbersih, $edesc, $nsisab, $ijenis);
            }
            $data4 = $this->mmaster->updatedetailspbold($ireferensi, $iproduct, $nquantity, $vtotalold, $vtotalbersihold, $ijenis);
            $no++;
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Insert SPB Turunan Baru' . $ireferensi);
            echo json_encode($data, $data2);
        }
    }

    /*----------  CETAK SJ HARGA DAN NON HARGA  ----------*/
    /*
    * Jika type = 'y' maka cetak yang harga;
    * Jika type = 't' maka cetak yang non harga;
    */

    public function cetak()
    {
        $id         = $this->uri->segment(4);
        $type       = $this->uri->segment(5);
        $jenisspb   = $this->uri->segment(6);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Cetak " . $this->global['title'],
            'id'            => $id,
            'data'          => $this->mmaster->baca_header($id, $jenisspb),
            'datadetail'    => $this->mmaster->baca_detail($id, $jenisspb),
        );

        if ($type == 'y') {
            $this->Logger->write('Cetak ' . $this->global['title'] . ' + Harga, ID : ' . $id);
            $this->load->view($this->global['folder'] . '/vformprintharga', $data);
        } else {
            $this->Logger->write('Cetak ' . $this->global['title'] . ' Non Harga, ID : ' . $id);
            $this->load->view($this->global['folder'] . '/vformprintnonharga', $data);
        }
    }

    /*----------  UPDATE STATUS PRINT  ----------*/

    public function updateprint()
    {

        $id = $this->input->post('id', true);
        $this->db->trans_begin();
        $this->mmaster->updateprint($id);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo 'fail';
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Print ' . $this->global['folder'] . ' Id : ' . $id);
            echo $id;
        }
    }
}
/* End of file Cform.php */