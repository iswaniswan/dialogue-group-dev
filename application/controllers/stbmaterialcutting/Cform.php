<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2050224';

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
        $this->doc_qe = $data[0]['doc_qe'];

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

    /*----------  DAFTAR DATA MASUK INTERNAL  ----------*/

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

    /*----------  MEMBUKA FORM TAMBAH DATA  ----------*/

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'         => $this->global['folder'],
            'title'          => "Tambah " . $this->global['title'],
            'title_list'     => 'List ' . $this->global['title'],
            'bagian'         => $this->mmaster->bagian(),
            'bagian_receive' => $this->mmaster->bagian_receive(),
            'dfrom'          => $this->uri->segment(4),
            'dto'            => $this->uri->segment(5),
            'number'         => "SJ-" . date('ym') . "-1234",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function tambah_kirim()
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

        $i_product_wip = $this->input->post('i_product_wip', TRUE);
        $i_material = $this->input->post('i_material', TRUE);
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
            'i_product_wip' => $i_product_wip,
            'i_material'    => $i_material,
            'detail'        => $this->mmaster->schedule_detail($this->global['folder'], $dfrom, $dto, $i_product_wip, $i_material),
            'wip'           => $this->mmaster->schedule_detail_wip($dfrom, $dto),
            'material'      => $this->mmaster->schedule_detail_material($dfrom, $dto),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist_schedule', $data);
    }

    /*----------  MEMBUKA FORM EDIT  ----------*/

    public function proses_kirim()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian = $this->input->post('ibagian', TRUE);
        $dfrom = $this->input->post('d_from', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('d_to', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $jml = $this->input->post('jml');
        $material = '';
        $wip = '';
        $typemk = '';
        $periode = '';
        $id = '';
        for ($i=1; $i <= $jml; $i++) { 
            $check = $this->input->post("chk$i");
            $id_material = $this->input->post("id_material$i");
            $id_product_wip = $this->input->post("id_product_wip$i");
            $id_type_makloon = $this->input->post("id_type_makloon$i");
            $i_periode = $this->input->post("i_periode$i");
            $id_schedule = $this->input->post("id$i");
            if ($check=='on') {
                $material .= $id_material.',';
                $wip .= $id_product_wip.',';
                $typemk .= $id_type_makloon.',';
                $id .= $id_schedule."','";
                $periode .= $i_periode."','";
            }
        }
        $id_material = substr($material,0,-1);
        $id_product_wip = substr($wip,0,-1);
        $id_type_makloon = substr($typemk,0,-1);
        $id = "'".substr($id,0,-2);
        $i_periode = "'".substr($periode,0,-2);
        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'number'     => "SJ-" . date('ym') . "-1234",
            // 'id'         => $this->uri->segment(4),
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian(),
            'bagian_receive' => $this->mmaster->bagian_receive_schedule(),
            /* 'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),*/
            'datadetail' => $this->mmaster->dataeditdetail_schedule($id_material,$id_product_wip,$ibagian,$i_periode,$id)->result(),
            'ibagian'    => $this->mmaster->getbagbyid($ibagian)->row()
        );

        $this->Logger->write('Membuka Menu Tambah Dari ke Cutting ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd_schedule', $data);
    }

    /*----------        GET BAGIAN         ----------*/
    public function getbag()
    {
        $idmaterial = $this->input->post('idmaterial');
        $idproductwip = $this->input->post('idproductwip');
        $idtypemakloon = $this->input->post('idtypemakloon');
        $stridtypemakloon = implode(',', $idtypemakloon);
        $query = $this->mmaster->getbag(/* $idmaterial, $idproductwip */$stridtypemakloon);
        if ($query->num_rows() > 0) {
            $c = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .= "<option value=" . $row->id . " >" . $row->e_bagian_name . "</option>";
            }
            $kop = "<option value=\"\"> -- Pilih Bagian -- " . $c . "</option>";
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

    /*----------  DAFTAR DATA MASUK INTERNAL  ----------*/

    public function data_schedule()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data_schedule($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        $date = $this->input->post('tgl', TRUE);
        if ($date != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($date)), $this->input->post('i_bagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/

    public function product()
    {
        $filter = [];
        $data = $this->mmaster->product(str_replace("'", "", $this->input->get('q')), $this->input->get('dfrom'), $this->input->get('dto'), $this->input->get('i_bagian'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $key) {
                $filter[] = array(
                    'id'   => $key->id,
                    'text' => '[' . $key->i_material . '] - ' . $key->e_material_name. ' - ' . $key->e_satuan_name
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data."
            );
        }
        echo json_encode($filter);
    }

    /*----------  DETAIL ITEM REFERENSI  ----------*/

    public function get_stock()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->get_stock($this->input->post('id_material', TRUE), $this->input->post('dfrom', TRUE), $this->input->post('dto', TRUE), $this->input->post('i_bagian', TRUE))->result_array(),
        );
        echo json_encode($query);
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_document = $this->input->post('i_document', TRUE);
        $d_document = $this->input->post('d_document', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        $i_bagian = $this->input->post('i_bagian', TRUE);
        $i_bagian_receive = $this->input->post('i_bagian_receive', TRUE);
        $e_remark = $this->input->post('e_remark', TRUE);
        $jml = $this->input->post('jml', TRUE);
        $id = $this->mmaster->runningid();
        if ($i_bagian != '' && $i_bagian_receive != '' && $i_document != ''  && $d_document != ''/*  && $jml > 0 */) {
            $this->db->trans_begin();
            $forecast = [];
            for ($i = 1; $i <= $jml; $i++) {
                $id_forecast   = $this->input->post('id_forecast' . $i, TRUE);
                if($id_forecast!=''){
                    array_push($forecast,$id_forecast);
                }
            }
            $forc   = array_unique($forecast);
            $for_caset = implode(",", $forc);
            $this->mmaster->simpan($id, $i_document, $d_document, $i_bagian, $i_bagian_receive, $e_remark, $for_caset);
            $forecash = [];
            for ($i = 1; $i <= $jml; $i++) {
                $id_forecast   = $this->input->post('id_forecast' . $i, TRUE);
                $id_material   = $this->input->post('id_material' . $i, TRUE);
                $id_product_wip   = $this->input->post('id_product_wip' . $i, TRUE);
                $n_quantity    = $this->input->post('n_quantity' . $i, TRUE);
                $e_remark_item = $this->input->post('e_remark_item' . $i, TRUE);
                if (($id_material != '' || $id_material != null) && ($id_product_wip != '' || $id_product_wip != null) && $n_quantity > 0) {
                    // var_dump($id, $id_material, $n_quantity, $e_remark_item);
                    $this->mmaster->simpandetail($id, $id_material, $id_product_wip, $n_quantity, $e_remark_item);
                }
            }
            // die;
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $i_document,
                    'sukses' => false,
                    'id'     => $id,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $i_document,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $i_document);
            }
        } else {
            $data = array(
                'kode'      => $i_document,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        echo json_encode($data);
    }

    /*----------  MEMBUKA FORM EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'number'     => "SJ-" . date('ym') . "-1234",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'bagian_receive' => $this->mmaster->bagian_receive_schedule(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4),$this->uri->segment(7))->result(),
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

        $id         = $this->input->post('id', TRUE);
        $i_document = $this->input->post('i_document', TRUE);
        $d_document = $this->input->post('d_document', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        $i_bagian = $this->input->post('i_bagian', TRUE);
        $i_bagian_receive = $this->input->post('i_bagian_receive', TRUE);
        $e_remark = $this->input->post('e_remark', TRUE);
        $jml = $this->input->post('jml', TRUE);
        if ($id != '' && $i_bagian != '' && $i_bagian_receive != '' && $i_document != ''  && $d_document != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id, $i_document, $d_document, $i_bagian, $i_bagian_receive, $e_remark);
            $this->mmaster->delete($id);
            for ($i = 1; $i <= $jml; $i++) {
                $id_material   = $this->input->post('id_material' . $i, TRUE);
                $id_product_wip   = $this->input->post('id_product_wip' . $i, TRUE);
                $n_quantity    = $this->input->post('n_quantity' . $i, TRUE);
                $e_remark_item = $this->input->post('e_remark_item' . $i, TRUE);
                if (($id_material != '' || $id_material != null) && ($id_product_wip != '' || $id_product_wip != null) && $n_quantity > 0) {
                    // var_dump($id, $id_material, $n_quantity, $e_remark_item);
                    $this->mmaster->simpandetail($id, $id_material, $id_product_wip, $n_quantity, $e_remark_item);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $i_document,
                    'sukses' => false,
                    'id'     => $id,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $i_document,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $i_document);
            }
        } else {
            $data = array(
                'kode'      => $i_document,
                'sukses'    => false,
                'id'        => $id,
            );
        }

        echo json_encode($data);
        // $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU FORM VIEW  ----------*/

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4),$this->uri->segment(7))->result(),
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  MEMBUKA MENU FORM APPROVE  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4),$this->uri->segment(7))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {

        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        /* if ($istatus == '6') {
            $this->mmaster->updatesisa($id);
            $this->mmaster->simpanjurnal($id, $this->global['title']);
        } */
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
        }
    }

    public function data_example()
    {
        header('Content-type: application/json; charset=utf-8');
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        
        $search = $this->input->get('search');
        $offset = $this->input->get('offset');
        $limit = $this->input->get('limit');
        // var_dump($search, $offset, $limit);

        $data = [];
        $rows = $this->mmaster->data_example($search, $offset, $limit)->num_rows();
        $query = $this->mmaster->data_example_limit($search, $offset, $limit);
        $detail = [];
        if($query->num_rows()>0)
        foreach($query->result() as $key){
            $detail [] = array(
                'id'   => $key->id,
                'name' => $key->e_product_wipname,
                "qty" => floor($key->n_quantity)
            );
        }
        $data = array(
            'total'   => $rows,
            'totalNotFiltered' => $rows,
            "rows" => $detail,
        );
        // echo json_encode($data, JSON_PRETTY_PRINT);
        echo json_encode($data);
        // var_dump($this->mmaster->data_example($this->i_menu, $this->global['folder'], $dfrom, $dto));
        // echo $this->mmaster->data_example($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }
}

/* End of file Cform.php */