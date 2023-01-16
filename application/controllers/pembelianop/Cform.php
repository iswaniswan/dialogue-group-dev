<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '20202';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

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

        $iop = $this->uri->segment('4');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

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

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dfrom = $this->input->post('dfrom');
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto');
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        if ($dfrom) {
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $dfrom1 = $year . '-' . $month . '-' . $day;
        }
        if ($dto) {
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $dto1 = $year . '-' . $month . '-' . $day;
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom1,
            'dto'        => $dto1,
            'dfrom1'     => $dfrom,
            'dto1'       => $dto
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function data_pp()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data_pp($this->global['folder'], $dfrom, $dto);
    }

    public function proses1()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isupplier = $this->input->post('isupplier');
        $id_pp_item = $this->input->post('id_pp_item');
        $jenis = $this->mmaster->cek_sup($isupplier)->row()->jenis_pembelian;

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'isupplier'     => $isupplier,
            'dfrom'         => $this->input->post('dfrom'),
            'dto'           => $this->input->post('dto'),
            'datasup'       => $this->mmaster->cek_sup($isupplier)->row(),
            'data'          => $this->mmaster->get_head($id_pp_item, $isupplier)->row(),
            'data2'         => $this->mmaster->get_harga1($id_pp_item, $isupplier, $jenis)->result(),
            'datatotal'     => $this->mmaster->get_total($id_pp_item, $isupplier, $jenis)->result(),
            'bagian'        => $this->mmaster->bagian()->result(),
            'jenis'         => $jenis,
            'number'        => $this->mmaster->runningnumber(date('ym'), date('Y'))
        );

        $this->Logger->write('Membuka Menu Input Item ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vforminput', $data);
    }

    public function importancestatus()
    {
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->importancestatus($cari);
        foreach ($data->result() as $key) {
            $filter[] = array(
                'id'   => $key->i_status_op,
                'text' => $key->e_status_op
            );
        }
        echo json_encode($filter);
    }

    public function get_supplier()
    {
        $filter = [];
        $cari = str_replace("'", "", $this->input->get('q'));
        $data = $this->db->query("SELECT
                DISTINCT 
                b.id,
                b.i_supplier,
                b.e_supplier_name
            FROM
                tr_supplier_materialprice a
            INNER JOIN tr_supplier b ON
                (b.i_supplier = a.i_supplier
                    AND a.id_company = b.id_company)
            WHERE
                a.f_status = 't'
                AND b.f_status = 't'
                AND b.i_supplier ILIKE '%$cari%'
                AND b.e_supplier_name ILIKE '%$cari%'
                AND a.id_company = '$this->id_company'
            ORDER BY
                b.e_supplier_name ASC");
        foreach ($data->result() as $key) {
            $filter[] = array(
                'id'   => $key->i_supplier,
                'text' => $key->i_supplier . ' - ' . $key->e_supplier_name
            );
        }
        echo json_encode($filter);
    }

    public function getmaterialprice()
    {
        header("Content-Type: application/json", true);
        $i_supplier = $this->input->post('i_supplier', TRUE);
        $i_material = $this->input->post('i_material', TRUE);
        $d_document = $this->input->post('d_document', TRUE);
        echo json_encode($this->mmaster->getmaterialprice($i_supplier, $i_material, $d_document)->result_array());
    }

    /*public function paymenttype()
    {
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->paymenttype($cari);
        foreach ($data->result() as $key) {
            $filter[] = array(
                'id'   => $key->i_payment_type,
                'text' => $key->e_payment_type_name
            );
        }
        echo json_encode($filter);
    }*/

    /*public function sup()
    {
        $filter = [];
        $ipp = $this->input->post('ipp');
        $data = $this->mmaster->getsup($ipp);

        foreach ($data->result() as $ikode) {
            $filter[] = array(
                'id'   => $ikode->i_supplier,
                'text' => $ikode->e_supplier_name,
            );
        }
        echo json_encode($filter);
    }*/

    public function getsup()
    {
        $idmaterial = $this->input->post('idmaterial');
        $query = $this->mmaster->getsup($idmaterial);
        if ($query->num_rows() > 0) {
            $c = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .= "<option value=" . $row->i_supplier . " >" . $row->i_supplier . " - " . $row->e_supplier_name . " (" . strtoupper($row->jenis_pembelian) . ")</option>";
            }
            $kop = "<option value=\"\"> -- Pilih Supplier -- " . $c . "</option>";
            echo json_encode(
                array(
                    'kop' => $kop
                )
            );
        } else {
            $kop = "<option value=\"\">Data Kosong</option>";
            echo json_encode(
                array(
                    'kop'    => $kop,
                    'kosong' => 'kopong'
                )
            );
        }
    }

    /*function getop()
    {
        header("Content-Type: application/json", true);
        $dfrom     = $this->input->post('dfrom');
        $dto       = $this->input->post('dto');
        $isupplier = $this->input->post('isupplier');
        $igudang   = $this->input->post('igudang');
        $ipp       = $this->input->post('ipp');
        $query   = $this->mmaster->get_pp_item($dfrom, $dto, $igudang, $isupplier, $ipp);

        $dataa = array(
            'jmlitem' => $query->num_rows(),
            'brgop'   => $this->mmaster->get_pp_item($dfrom, $dto, $igudang, $isupplier, $ipp)->result_array(),
        );
        echo json_encode($dataa);
    }*/

    /*public function supplier()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_supplier");
            $this->db->like("UPPER(i_supplier)", $cari);
            $this->db->or_like("UPPER(e_supplier_name)", $cari);
            $data = $this->db->get();
            foreach ($data->result() as $icolor) {
                $filter[] = array(
                    'id'   => $icolor->i_supplier,
                    'text' => $icolor->i_supplier . '-' . $icolor->e_supplier_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))));
        }
        echo json_encode($number);
    }

    /*public function ipp()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("select * from tm_pp where (i_pp like '%$cari%')");
            foreach ($data->result() as $pp) {
                $filter[] = array(
                    'id'   => $pp->i_pp,
                    'text' => $pp->i_pp,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }*/

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post('ibagian', true);
        $iop            = $this->input->post('iop', TRUE);
        $isupplier      = $this->input->post('isupplier', TRUE);
        $esuppliername  = $this->input->post('esuppliername', TRUE);
        // $ipp            = $this->input->post('ipp', TRUE);
        $dop = $this->input->post('dop', TRUE);
        // $igudang        = $this->input->post('igudang', TRUE);
        if ($dop) {
            $tmp   = explode('-', $dop);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $thbl = $year . $month;
            $dateop = $year . '-' . $month . '-' . $day;
        }
        $ddeliv = $this->input->post('dbp', TRUE);
        if ($ddeliv) {
            $tmp   = explode('-', $ddeliv);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datedeliv = $year . '-' . $month . '-' . $day;
        }
        $importantstatus = $this->input->post('importantstatus', TRUE);
        $eremark        = $this->input->post('eremarkh', TRUE);
        $fppn           = $this->input->post('fppn', TRUE);
        if ($fppn == 't') {
            $itypepajak = 'I';
        } else if ($fppn == 'f') {
            $itypepajak = 'E';
        }
        $ndiskon        = $this->input->post('ndiskon', TRUE);
        $fpkp           = $this->input->post('fpkp', TRUE);
        $ntop           = $this->input->post('ntop', TRUE);
        $jenis          = $this->input->post('jenis', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $iop);
        $id = $this->mmaster->runningid();
        $this->mmaster->insertheader(
            $id,
            $iop,
            $dateop,
            $ibagian,
            $isupplier,
            $ntop,
            $ndiskon,
            $itypepajak,
            $importantstatus,
            $datedeliv,
            $eremark,
            $fpkp,
            $esuppliername,
            $jenis
        );
        for ($i = 1; $i <= $jml; $i++) {
            $ipp            = $this->input->post('ipp' . $i, TRUE);
            $idpp           = $this->input->post('idpp' . $i, TRUE);
            $imaterial      = $this->input->post('imaterial' . $i, TRUE);
            $imaterialsupplier      = $this->input->post('imaterialsupplier' . $i, TRUE);
            $nquantity      = $this->input->post('nquantity' . $i, TRUE);
            $isatuan        = $this->input->post('isatuan' . $i, TRUE);
            $nsisa          = $nquantity;
            $vprice         = str_replace(",", "", $this->input->post('vprice' . $i, TRUE));
            $vpriceppn      = str_replace(",", "", $this->input->post('vpriceppn' . $i, TRUE));
            $ppn            = str_replace(",", "", $this->input->post('ppn' . $i, TRUE));

            $vpricedefault = str_replace(",", "", $this->input->post('vpricedefault' . $i, TRUE));
            $minorder      = $this->input->post('minorder' . $i, TRUE);

            $remark         = $this->input->post('eremark' . $i, TRUE);
            $this->mmaster->insertdetail($id, $idpp, $imaterial, $nquantity, $nsisa, $vprice, $remark, $vpricedefault, $minorder, $isatuan, $vpriceppn, $ppn, $imaterialsupplier);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $iop,
                'id'     => $id
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idop  = $this->uri->segment(4);
        $iop   = $this->uri->segment(5);
        $idpp  = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->cek_data($idop)->row(),
            'data2'      => $this->mmaster->get_item($idpp, $idop, $idcompany)->result(),
            'datatotal'     => $this->mmaster->get_item_total($idop, $idcompany)->result(),
            'dfrom'      => $this->uri->segment(7),
            'dto'        => $this->uri->segment(8),
            'jenis'      => strtolower($this->uri->segment(9)),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => $this->mmaster->runningnumber(date('ym'), date('Y'))
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

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $iop            = $this->input->post('iop', TRUE);
        $isupplier      = $this->input->post('isupplier', TRUE);
        $esuppliername  = $this->input->post('esuppliername', TRUE);
        $ipp            = $this->input->post('ipp', TRUE);
        $dop            = $this->input->post('dop', TRUE);
        if ($dop) {
            $tmp   = explode('-', $dop);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $thbl = $year . $month;
            $dateop = $year . '-' . $month . '-' . $day;
        }
        $ddeliv = $this->input->post('dbp', TRUE);
        if ($ddeliv) {
            $tmp   = explode('-', $ddeliv);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datedeliv = $year . '-' . $month . '-' . $day;
        }
        $istatus = $this->input->post('istatus');
        if ($istatus != '6') {
            $importantstatus = $this->input->post('importantstatus', TRUE);
        } else if ($istatus == '6') {
            $importantstatus = $this->input->post('importantstatusharga', TRUE);
        }
        $eremark        = $this->input->post('eremarkh', TRUE);
        $fppn           = $this->input->post('fppn', TRUE);
        if ($fppn == 't') {
            $itypepajak = 'I';
        } else if ($fppn == 'f') {
            $itypepajak = 'E';
        }
        $ndiskon        = $this->input->post('ndiskon', TRUE);
        $fpkp           = $this->input->post('fpkp', TRUE);
        $ntop           = $this->input->post('ntop', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $iop);
        $this->mmaster->update(
            $id,
            $iop,
            $dateop,
            $ibagian,
            $isupplier,
            $ntop,
            $ndiskon,
            $itypepajak,
            $importantstatus,
            $datedeliv,
            $eremark,
            $fpkp,
            $esuppliername
        );

        for ($i = 1; $i <= $jml; $i++) {
            $ipp            = $this->input->post('ipp' . $i, TRUE);
            $idpp           = $this->input->post('idpp' . $i, TRUE);
            $imaterial      = $this->input->post('imaterial' . $i, TRUE);
            $imaterialsupplier      = $this->input->post('imaterialsupplier' . $i, TRUE);
            $nquantity      = $this->input->post('nquantity' . $i, TRUE);
            $isatuan        = $this->input->post('isatuan' . $i, TRUE);
            $nsisa          = $nquantity;
            $vprice         = str_replace(",", "", $this->input->post('vprice' . $i, TRUE));
            $vpriceppn         = str_replace(",", "", $this->input->post('vpriceppn' . $i, TRUE));
            $ppn         = str_replace(",", "", $this->input->post('ppn' . $i, TRUE));
            $remark         = $this->input->post('eremark' . $i, TRUE);

            $vpricedefault = str_replace(",", "", $this->input->post('vpricedefault' . $i, TRUE));
            $minorder      = $this->input->post('minorder' . $i, TRUE);

            $this->mmaster->updatedetail($id, $idpp, $imaterial, $nquantity, $nsisa, $vprice, $remark, $vpricedefault, $minorder, $isatuan, $vpriceppn, $ppn, $imaterialsupplier);
        }
        $data = array(
            'sukses' => true,
            'kode'   => $iop,
            'id'     => $id,
        );
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $iop,
                'id'     => $id,
            );
        }

        $this->load->view('pesan', $data);
    }

    public function editharga()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iop   = $this->uri->segment(4);
        $idpp  = $this->uri->segment(5);
        $idop  = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->cek_data($idop)->row(),
            'data2'      => $this->mmaster->get_item($idpp, $idop, $idcompany)->result(),
            'datatotal'     => $this->mmaster->get_item_total($idop, $idcompany)->result(),
            'dfrom'      => $this->uri->segment(7),
            'dto'        => $this->uri->segment(8),
            'jenis'      => strtolower($this->uri->segment(9)),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => $this->mmaster->runningnumber(date('ym'), date('Y'))
        );

        $this->Logger->write('Membuka Menu Edit Harga ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformeditharga', $data);
    }

    public function updateharga()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $iop            = $this->input->post('iop', TRUE);
        /*$ibagian        = $this->input->post('ibagian', TRUE);
        $isupplier      = $this->input->post('isupplier', TRUE);
        $esuppliername  = $this->input->post('esuppliername', TRUE);
        $ipp            = $this->input->post('ipp', TRUE);
        $dop            = $this->input->post('dop', TRUE);
        if ($dop) {
            $tmp   = explode('-', $dop);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $thbl = $year . $month;
            $dateop = $year . '-' . $month . '-' . $day;
        }
        $ddeliv = $this->input->post('dbp', TRUE);
        if ($ddeliv) {
            $tmp   = explode('-', $ddeliv);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datedeliv = $year . '-' . $month . '-' . $day;
        }
        $istatus = $this->input->post('istatus');
        if ($istatus != '6') {
            $importantstatus = $this->input->post('importantstatus', TRUE);
        } else if ($istatus == '6') {
            $importantstatus = $this->input->post('importantstatusharga', TRUE);
        }
        $eremark        = $this->input->post('eremarkh', TRUE);
        $fppn           = $this->input->post('fppn', TRUE);
        if ($fppn == 't') {
            $itypepajak = 'I';
        } else if ($fppn == 'f') {
            $itypepajak = 'E';
        }
        $ndiskon        = $this->input->post('ndiskon', TRUE);
        $fpkp           = $this->input->post('fpkp', TRUE);
        $ntop           = $this->input->post('ntop', TRUE);*/
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        /*$this->mmaster->update(
            $id,
            $iop,
            $dateop,
            $ibagian,
            $isupplier,
            $ntop,
            $ndiskon,
            $itypepajak,
            $importantstatus,
            $datedeliv,
            $eremark,
            $fpkp,
            $esuppliername
        );*/

        for ($i = 1; $i <= $jml; $i++) {
            $ipp            = $this->input->post('ipp' . $i, TRUE);
            $idpp           = $this->input->post('idpp' . $i, TRUE);
            $imaterial      = $this->input->post('imaterial' . $i, TRUE);
            $nquantity      = $this->input->post('nquantity' . $i, TRUE);
            $nsisa          = $nquantity;
            $vprice         = str_replace(",", "", $this->input->post('vprice' . $i, TRUE));
            $vpriceppn         = str_replace(",", "", $this->input->post('vpriceppn' . $i, TRUE));
            $remark         = $this->input->post('eremark' . $i, TRUE);

            $vpricedefault = str_replace(",", "", $this->input->post('vpricedefault' . $i, TRUE));
            $minorder      = $this->input->post('minorder' . $i, TRUE);

            if ($imaterial != '' && $vprice > 0) {
                $this->mmaster->updateharga($id, $imaterial, $nquantity, $vprice, $vpricedefault, $minorder,$vpriceppn);
            }
        }
        $data = array(
            'sukses' => true,
            'kode'   => $iop,
            'id'     => $id,
        );
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $iop,
                'id'     => $id,
            );
            $this->Logger->write('Update Data Harga ' . $this->global['title'] . ' Kode : ' . $iop);
        }

        $this->load->view('pesan', $data);
    }

    public function changestatus()
    {

        $id      = $this->input->post('id', true);
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

    public function changestatusx()
    {

        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatusx($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status Harga ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function approval()
    {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iop   = $this->uri->segment(4);
        $idpp  = $this->uri->segment(5);
        $idop  = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(7),
            'dto'        => $this->uri->segment(8),
            'jenis'      => strtolower($this->uri->segment(9)),
            'data'       => $this->mmaster->cek_data($idop)->row(),
            'data2'      => $this->mmaster->get_item($idpp, $idop, $idcompany)->result(),
            'datatotal'     => $this->mmaster->get_item_total($idop, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function approvalharga()
    {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iop   = $this->uri->segment(4);
        $idpp  = $this->uri->segment(5);
        $idop  = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(7),
            'dto'        => $this->uri->segment(8),
            'jenis'      => strtolower($this->uri->segment(9)),
            'data'       => $this->mmaster->cek_data($idop)->row(),
            'data2'      => $this->mmaster->get_item($idpp, $idop, $idcompany)->result(),
            'datatotal'     => $this->mmaster->get_item_total($idop, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Approve 2 ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove2', $data);
    }

    public function view()
    {
        $iop        = $this->uri->segment(4);
        $idpp       = $this->uri->segment(5);
        $idop       = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(7),
            'dto'        => $this->uri->segment(8),
            'data'       => $this->mmaster->cek_data($idop)->row(),
            'data2'      => $this->mmaster->get_item($idpp, $idop, $idcompany)->result(),
            'datatotal'     => $this->mmaster->get_item_total($idop, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iop = $this->input->post('iop', TRUE);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($iop);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Order Pembelian ' . $iop);
            echo json_encode($data);
        }
    }*/

    public function cetak()
    {
        $iop        = $this->uri->segment(4);
        $idpp       = $this->uri->segment(5);
        $idop       = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Cetak " . $this->global['title'],
            'iop'           => $iop,
            'id'            => $idop,
            'data'          => $this->mmaster->cetakop($idop)->result(),
            'data2'         => $this->mmaster->get_item_cetak($idpp, $idop, $idcompany)->result(),
            'cekcetak'      => $this->mmaster->get_cetakid()->row(),
        );

        $this->Logger->write('Cetak ' . $this->global['title'] . ' No : ' . $iop);

        $this->load->view($this->global['folder'] . '/vformprint', $data);
    }

    public function cetaknonharga()
    {
        $iop        = $this->uri->segment(4);
        $idpp       = $this->uri->segment(5);
        $idop       = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Cetak " . $this->global['title'],
            'iop'           => $iop,
            'id'            => $idop,
            'data'          => $this->mmaster->cetakop($idop)->result(),
            'data2'         => $this->mmaster->get_item_cetak($idpp, $idop, $idcompany)->result(),
            'cekcetak'      => $this->mmaster->get_cetakid()->row(),
        );

        $this->Logger->write('Cetak ' . $this->global['title'] . ' No : ' . $iop);

        $this->load->view($this->global['folder'] . '/vformprintnonharga', $data);
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

    public function edit_supplier()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iop   = $this->uri->segment(4);
        $idpp  = $this->uri->segment(5);
        $idop  = $this->uri->segment(6);
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->cek_data($idop)->row(),
            'data2'      => $this->mmaster->get_item_sisa($idpp, $idop, $idcompany)->result(),
            'datatotal'  => $this->mmaster->get_item_total($idop, $idcompany)->result(),
            'dfrom'      => $this->uri->segment(7),
            'dto'        => $this->uri->segment(8),
            'jenis'      => strtolower($this->uri->segment(9)),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => $this->mmaster->runningnumber(date('ym'), date('Y'))
        );

        $this->Logger->write('Membuka Menu Edit Harga ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformeditsupplier', $data);
    }

    public function update_supplier()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $iop            = $this->input->post('iop', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        for ($i = 1; $i <= $jml; $i++) {
            $id_op_item     = $this->input->post('id_op_item' . $i, TRUE);
            $ipp            = $this->input->post('ipp' . $i, TRUE);
            $idpp           = $this->input->post('idpp' . $i, TRUE);
            $imaterial      = $this->input->post('imaterial' . $i, TRUE);
            $i_satuan_code  = $this->input->post('i_satuan_code' . $i, TRUE);
            $n_qty_sisa = str_replace(",", "", $this->input->post('n_qty_sisa' . $i, TRUE));
            $n_qty_revisi = str_replace(",", "", $this->input->post('n_qty_revisi' . $i, TRUE));
            $e_note_subtitution = str_replace("'", "", $this->input->post('e_note_subtitution' . $i, TRUE));

            if ($imaterial != '' && $n_qty_revisi > 0) {
                $this->mmaster->update_item_supplier($ipp,$idpp,$id, $imaterial, $n_qty_sisa,$n_qty_revisi,$i_satuan_code,$e_note_subtitution, $id_op_item);
            }
        }
        $data = array(
            'sukses' => true,
            'kode'   => $iop,
            'id'     => $id,
        );
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,
            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $iop,
                'id'     => $id,
            );
            $this->Logger->write('Update Item Rubah Supplier ' . $this->global['title'] . ' Kode : ' . $iop);
        }

        $this->load->view('pesan', $data);
    }

    public function approve_validation()
    {
        $id = $this->input->post('id');
        $query = $this->mmaster->get_sisa($id);
        $data = [];
        $sisa = true;
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                if ($key->n_quantity_pemenuhan > $key->n_quantity_sisa) {
                    $sisa = false;
                    break;
                }
            }
        }
        $data = array(
            'sisa' => $sisa,
        );
        echo json_encode($data);
    }
}
/* End of file Cform.php */
