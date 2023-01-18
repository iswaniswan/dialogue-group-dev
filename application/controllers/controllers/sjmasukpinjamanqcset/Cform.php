<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2090210';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

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
                'id'    => $row->id_partner.'|'.$row->i_partner,
                'text'  => $row->e_partner_name,
            );
        }
        echo json_encode($filter);
    }

    public function referensi()
    {
        $filter = [];
        $partner = explode('|', $this->input->get('iasal'));
        $idpartner = $partner[0];
        $ipartner  = $partner[1];
        $data   = $this->mmaster->referensi(strtoupper($this->input->get('q')),$idpartner,$ipartner);
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
        $pengirim = explode('|',$this->input->post('ipengirim'));
        $idpengirim = $pengirim[0];
        $ipengirim  = $pengirim[1];
        $jml = $this->mmaster->getdataitem($idreff, $idpengirim, $ipengirim);
        $data = array(
            'datahead'   => $this->mmaster->getdataheader($idreff, $idpengirim, $ipengirim)->row(),
            'jmlitem'    => $jml->num_rows(),
            'dataitem'   => $this->mmaster->getdataitem($idreff, $idpengirim, $ipengirim)->result_array()
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
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "SJP-".date('ym')."-123456"
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

        $idocument = $this->input->post('idocument', TRUE);
        $ibagian   = $this->input->post('ibagian', TRUE);
        $ddocument = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $ddocument = $year . '-' . $month . '-' . $day;
        }

        $asal     = explode('|',$this->input->post('ipengirim', TRUE));
        $idasal   = $asal[0];
        $iasal    = $asal[1];
        $ireff    = $this->input->post('ireff', TRUE);
        $eremark  = $this->input->post('eremark', TRUE);
        $jml      = $this->input->post('jml', TRUE);

        if($idocument != ''  && $ddocument != '' && $ibagian != '' && $iasal != '' && $ireff != ''){
            $this->db->trans_begin();
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id, $idocument, $ddocument, $ibagian, $iasal, $ireff, $eremark);
            for($x=0; $x<=$jml; $x++){
                $idproduct         = $this->input->post('idproduct'.$x, TRUE);
                $nquantitywipmasuk = str_replace(",",".",$this->input->post('nquantitywipmasuk'.$x, TRUE));
                $i = 0;
                if($idproduct != "" || $idproduct != NULL){
                    foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                        if($idproduct ==  $idproductwip){
                            $idmaterial           = $this->input->post("idmaterial[]", TRUE)[$i];
                            $nquantitybahanmasuk  = str_replace(",",".",$this->input->post("nquantitymaterialmasuk[]", TRUE))[$i];
                            $edesc                = $this->input->post("edesc[]", TRUE)[$i];
                            if($nquantitywipmasuk <> 0 && $nquantitybahanmasuk <> 0){
                                $this->mmaster->insertdetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc);
                            }
                        }
                        $i++;
                    }
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
                'kode'   => $idocument,
                'id'     => $id,
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

        $id        = $this->input->post('id', TRUE);
        $idocument = $this->input->post('idocument', TRUE);
        $ibagian   = $this->input->post('ibagian', TRUE);
        $ddocument = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $ddocument = $year . '-' . $month . '-' . $day;
        }

        $iasal        = $this->input->post('ipengirim', TRUE);
        $ireff        = $this->input->post('ireff', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        if($idocument != ''  && $ddocument != '' && $ibagian != ''){
            $this->db->trans_begin();
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
            $this->mmaster->updateheader($id, $idocument, $ddocument, $ibagian, $iasal, $ireff, $eremark);
            $this->mmaster->deletedetail($id);
            
            for($x=0; $x<=$jml; $x++){
                $idproduct         = $this->input->post('idproduct'.$x, TRUE);
                $nquantitywipmasuk = str_replace(",",".",$this->input->post('nquantitywipmasuk'.$x, TRUE));
                $i = 0;
                if($idproduct != "" || $idproduct != NULL){
                    foreach ($this->input->post("idproductwip[]", TRUE) as $idproductwip) {
                        if($idproduct ==  $idproductwip){
                            $idmaterial           = $this->input->post("idmaterial[]", TRUE)[$i];
                            $nquantitybahanmasuk  = str_replace(",",".",$this->input->post("nquantitymaterialmasuk[]", TRUE))[$i];
                            $edesc                = $this->input->post("edesc[]", TRUE)[$i];
                            if($nquantitywipmasuk <> 0 && $nquantitybahanmasuk <> 0){
                                $this->mmaster->insertdetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc);
                            }
                        }
                        $i++;
                    }
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
                'kode'   => $idocument,
                'id'     => $id,
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
            'id'         => $id,
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()

        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
    
}
/* End of file Cform.php */