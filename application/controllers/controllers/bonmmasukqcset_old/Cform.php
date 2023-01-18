<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2090201';

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
        $this->idcompany = $this->session->id_company;

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
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
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    public function bagianpengirim()
    {
        $filter = [];
        $data   = $this->mmaster->bagianpengirim(strtoupper($this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->i_bagian,
                'text'  => $row->e_bagian_name,
            );
        }
        echo json_encode($filter);
    }

    public function referensi()
    {
        $filter = [];
        $data   = $this->mmaster->referensi(strtoupper($this->input->get('q')),$this->input->get('iasal'));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id, 
                'text'  => $row->i_document,
            );
        }
        echo json_encode($filter);
    }

    public function getdataitem()
    {
        header("Content-Type: application/json", true);
        $idreff    = $this->input->post('idreff');
        $ipengirim = $this->input->post('ipengirim');
        $jml = $this->mmaster->getdataitem($idreff, $ipengirim);
        $data = array(
            'datahead'   => $this->mmaster->getdataheader($idreff, $ipengirim)->row(),
            'jmlitem'    => $jml->num_rows(),
            'dataitem'   => $this->mmaster->getdataitem($idreff, $ipengirim)->result_array()
        );
        echo json_encode($data);
    }

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'       => $this->global['folder'],
            'title'        => "Tambah " . $this->global['title'],
            'title_list'   => ' List ' . $this->global['title'],
            'dfrom'        => $this->uri->segment(4),
            'dto'          => $this->uri->segment(5),
            'bagian'       => $this->mmaster->bagianpembuat()->result(),
            'number'       => "BBM-".date('ym')."-123456"
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function getreferensi()
    {
        $iasal = $this->input->post('iasal');

        if ($iasal == 'SDP0005') {
            $query = $this->mmaster->getreferensi($iasal);
            if ($query->num_rows() > 0) {
                $c = "";
                $spb = $query->result();
                foreach ($spb as $row) {
                    $c .= "<option value=" . $row->i_bonk . " >" . $row->i_bonk . " || " . $row->d_bonk . "</option>";
                }
                $kop = "<option value=\"\"> -- Pilih No Referensi -- " . $c . "</option>";
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
        } else if ($iasal == 'GD10002') {
            $query = $this->mmaster->getreferensiaks($iasal);
            if ($query->num_rows() > 0) {
                $c = "";
                $spb = $query->result();
                foreach ($spb as $row) {
                    $c .= "<option value=" . $row->i_permintaan . " >" . $row->i_permintaan . " || " . $row->d_pp . "</option>";
                }
                $kop = "<option value=\"\"> -- Pilih No Referensi -- " . $c . "</option>";
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
    }

    function getdataaks()
    {
        header("Content-Type: application/json", true);
        $ireff = $this->input->post('ireff');

        $this->db->select("* from tm_permintaanpengeluaranaks_detail a where a.i_permintaan = '$ireff'");
        $data = $this->db->get();

        $query   = $this->mmaster->getdataaks($ireff);

        $dataa = array(
            'data'       => $data->result_array(),
            'jmlitem'    => $query->num_rows(),
            'dataitem'   => $this->mmaster->getdataaks($ireff)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function datamaterial()
    {
        $filter = [];
        $iproduct = $this->uri->segment(4);
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query(
                "select a.i_product, a.i_material, b.e_material_name, a.i_color
                from tr_polacutting a 
                join tr_material b on a.i_material = b.i_material
                where (a.i_material like '%$cari%' or b.e_material_name like '%$cari%') 
                order by a.i_material
                "
            );
            foreach ($data->result() as $material) {
                $filter[] = array(
                    'id'   => $material->i_material,
                    'name' => $material->e_material_name,
                    'text' => $material->i_material . ' - ' . $material->e_material_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getmaterial()
    {
        header("Content-Type: application/json", true);
        $ematerial = $this->input->post('ematerial');

        $this->db->select(
            "distinct(a.i_material), b.e_material_name, a.i_color, c.e_color_name, b.i_satuan_code, g.e_satuan
            from tr_polacutting a 
            join tr_material b on a.i_material = b.i_material
            join tr_color c on a.i_color = c.i_color
            join tr_satuan g on b.i_satuan_code= g.i_satuan_code
            where a.i_material = '$ematerial'
            order by a.i_material"
        );
        $data = $this->db->get();
        echo json_encode($data->result_array());
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

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibonm        = $this->input->post('idocument', TRUE);
        $ikodemaster  = $this->input->post('ibagian', TRUE);
        $dbonm        = $this->input->post('ddocument', TRUE);
        if ($dbonm) {
            $tmp   = explode('-', $dbonm);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $dbonm = $year . '-' . $month . '-' . $day;
        }

        $iasal        = $this->input->post('ipengirim', TRUE);
        $ireff        = $this->input->post('ireff', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        $i_product       = $this->input->post('iproduct[]', TRUE);
        $id_product      = $this->input->post('idproduct[]', TRUE);
        $i_colorpro      = $this->input->post('icolorpro[]', TRUE);
        $i_material      = $this->input->post('imaterial[]', TRUE);
        $id_material      = $this->input->post('idmaterial[]', TRUE);

        $n_quantitywip         = $this->input->post('nquantitywip[]', TRUE);
        $n_quantitywipmasuk    = $this->input->post('nquantitywipmasuk[]', TRUE);
        $n_quantitybahan       = $this->input->post('nquantitybahan[]', TRUE);
        $n_quantitybahanmasuk  = $this->input->post('nquantitybahanmasuk[]', TRUE);
        $e_desc                = $this->input->post('edesc[]', TRUE);

        if($ibonm != ''  && $dbonm != '' && $ikodemaster != '' /* && $iasal != '' */ && $ireff != ''){
            $cekkode = $this->mmaster->cek_kode($ibonm, $ikodemaster);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                    'kode'   => $ibonm,
                );
            }else{
                $this->db->trans_begin();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonm);
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $ibonm, $dbonm, $ikodemaster, $iasal, $ireff, $eremark);
                for($x=0; $x<=$jml; $x++){
                    $idproduct         = $this->input->post('idproduct'.$x, TRUE);
                    $nquantitywipmasuk = str_replace(",",".",$this->input->post('nquantitywipmasuk'.$x, TRUE));
                    $i = 0;
                    if($idproduct != "" || $idproduct != NULL){
                        foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                            if($idproduct ==  $idproductwip){
                                $idmaterial           = $this->input->post("idmaterial[]", TRUE)[$i];
                                $nquantitybahanmasuk  = str_replace(",",".",$this->input->post("nquantitybahanmasuk[]", TRUE))[$i];
                                $edesc                = $this->input->post("edesc[]", TRUE)[$i];
                                if($nquantitywipmasuk > 0  && $nquantitywipmasuk >'' && $nquantitybahanmasuk > 0 && $nquantitybahanmasuk > ''){
                                    $this->mmaster->insertdetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc);
                                }
                            }
                            $i++;
                        }
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $ibonm,
                    );
                } else {
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $ibonm,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $ibonm,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()
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

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()
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

        $id           = $this->input->post('id', TRUE);
        $ibonm        = $this->input->post('idocument', TRUE);
        $ibonmold     = $this->input->post('idocumentold', TRUE);
        $ikodemaster  = $this->input->post('ibagian', TRUE);
        $dbonm        = $this->input->post('ddocument', TRUE);
        if ($dbonm) {
            $tmp   = explode('-', $dbonm);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $dbonm = $year . '-' . $month . '-' . $day;
        }

        $iasal        = $this->input->post('ibagianpengirim', TRUE);
        $ireff        = $this->input->post('ireff', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        $id_product       = $this->input->post('idproductwip[]', TRUE);
        $id_material      = $this->input->post('idmaterial[]', TRUE);
        $n_quantity_wip   = $this->input->post('nquantitywipmsk[]', TRUE);
        $n_quantity       = $this->input->post('nquantitybahanmsk[]', TRUE);
        $e_desc           = $this->input->post('edesc[]', TRUE);
        if($ibonm != ''  && $dbonm != '' && $ikodemaster != ''){
            $cekkode = $this->mmaster->cek_kodeedit($ibonm, $ibonmold, $ikodemaster);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonm);
                $this->mmaster->updateheader($id, $ikodemaster, $ibonm, $dbonm, $eremark);
                $this->mmaster->deletedetail($id);
                
                for($x=0; $x<=$jml; $x++){
                    $idproduct         = $this->input->post('idproduct'.$x, TRUE);
                    $nquantitywipmasuk = str_replace(",",".",$this->input->post('nquantitywipmasuk'.$x, TRUE));
                    $i = 0;
                    if($idproduct != "" || $idproduct != NULL){
                        foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                            if($idproduct ==  $idproductwip){
                                $idmaterial           = $this->input->post("idmaterial[]", TRUE)[$i];
                                $nquantitybahanmasuk  = str_replace(",",".",$this->input->post("nquantitybahanmasuk[]", TRUE))[$i];
                                $edesc                = $this->input->post("edesc[]", TRUE)[$i];
                                if($nquantitywipmasuk > 0  && $nquantitywipmasuk >'' && $nquantitybahanmasuk > 0 && $nquantitybahanmasuk > ''){
                                    $this->mmaster->insertdetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc);
                                }
                            }
                            $i++;
                        }
                    }
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
                        'kode'   => $ibonm,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }


    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
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

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(6),
            'dto'        => $this->uri->segment(7),
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()

        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */