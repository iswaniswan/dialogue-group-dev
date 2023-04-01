<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090503';

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
        $this->global['title']  = $data[0]['e_menu'];
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
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
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
            'number'        => "STB-" . date('ym') . "-0001",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            // $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE), $this->input->post('itujuan', TRUE));

            $bagian = $this->input->post('ibagian');
            $tujuan = $this->input->post('itujuan');
            $number = $this->mmaster->generate_nomor_dokumen($bagian, $tujuan);
        }
        echo json_encode($number);
    }

    public function dataproduct()
    {
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->dataproduct($cari);
        foreach ($data->result() as $product) {
            $filter[] = array(
                'id'    => $product->id,
                'name'  => $product->i_product_base . ' - ' . $product->e_product_basename . ' - ' . $product->e_color_name,
                'text'  => $product->i_product_base . ' - ' . $product->e_product_basename . ' - ' . $product->e_color_name,
            );
        }
        echo json_encode($filter);
    }

    public function getcolor()
    {
        $data = array(
            'data' => $this->mmaster->getproduct($this->input->post('id_product_wip'))->row(), 
        );
        echo json_encode($data);
    }

    /*-------------- CARI MARKER ------------- */
    public function marker()
    {
        $filter = [];
        $q = str_replace("'", "", $this->input->get('q'));
        $id_product_wip = str_replace("'", "", $this->input->get('id_product_wip'));

        $data = $this->mmaster->marker($q, $id_product_wip);
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id_marker,
                    'text' => $row->e_marker_name
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

    public function getproduct()
    {
        header("Content-Type: application/json", true);
        $data = array(
            'data' => $this->mmaster->getproduct($this->input->post('eproduct'))->result_array(), 
            'detail' => $this->mmaster->getproduct_material($this->input->post('eproduct'), $this->input->post('emarker'))->result_array(), 
        );
        echo json_encode($data);
    }

    public function getstok()
    {
        header("Content-Type: application/json", true);
        $produk = $this->input->post('idproduct');
        $bagian = $this->input->post('ibagian');
        $data = $this->mmaster->getstok($produk, $bagian);

        echo json_encode($data->row());
    }

    public function jenis_barang()
    {
        $filter = [];
        $itujuan = strtoupper($this->input->get('itujuan'));
        $data = $this->mmaster->jenis_barang($itujuan);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id,
                'text'  => $row->e_jenis_name,
            );
        }
        echo json_encode($filter);
    }

    public function simpan()
    {
    //    echo '<pre>';
    //    var_dump($this->input->post());
    //    echo '</pre>';
    //    die('here');

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian     = $this->input->post('ibagian', TRUE);
        $ibonk       = $this->input->post('ibonk', TRUE);
        $dbonk       = $this->input->post('dbonk', TRUE);
        if ($dbonk) {
            $tmp   = explode('-', $dbonk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datebonk  = $year . '-' . $month . '-' . $day;
        }

        $itujuan      = $this->input->post('itujuan', TRUE);
        $array_itujuan = explode("|", $itujuan);

        $_itujuan = $array_itujuan[1];
        $_id_company_tujuan = $array_itujuan[0];

        $ijenis       = $this->input->post('ijenis', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        $jml_item     = $this->input->post('jml_item', TRUE);
        $id           = $this->mmaster->runningid();

        $i_product    = $this->input->post('idproduct[]', TRUE);
        $id_marker    = $this->input->post('idmarker[]', TRUE);
        $i_color      = $this->input->post('idcolorproduct[]', TRUE);
        $n_qtyproduct = $this->input->post('nquantity[]', TRUE);
        $n_qtyproduct = str_replace(',', '', $n_qtyproduct);
        $e_desc       = $this->input->post('edesc[]', TRUE);
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonk);
        $this->mmaster->insertheader($id, $ibonk, $ibagian, $datebonk, $_itujuan, $ijenis, $eremark, $_id_company_tujuan);

        $no = count($i_product)-1;
        $no2 = 1;
        for ($a = $no; $a>=0; $a--/* $i_product as $iproduct */) {
            $iproduct    = $i_product[$a];
            $imarker    = $id_marker[$a];
            $icolor      = $i_color[$a];
            $nqtyproduct = $n_qtyproduct[$a];
            $edesc       = $e_desc[$a];
            if ($nqtyproduct != 0 && $nqtyproduct != null) {
                $this->mmaster->insertdetail($id, $iproduct, $imarker, $icolor, $nqtyproduct, $edesc);
            }
            // $no--;
            $iditem = $this->mmaster->runningiditem();
            for($i = 1; $i <= $jml_item; $i++) {
                $eproduct_bundle = $this->input->post("eproduct_bundle$no2$i");
                $n_qty_bundle = $this->input->post("n_qty_bundle$no2$i");
                if(($eproduct_bundle != '' && $n_qty_bundle != 0) || ($eproduct_bundle != null && $n_qty_bundle != null)) {
                    $this->mmaster->insertbundling($id, $iditem, $eproduct_bundle, $n_qty_bundle);
                }
            }
            $no2++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $ibonk,
                'id'     => $id,
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

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $ibagian    = $this->uri->segment('7');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'number'        => "STB-" . date('ym') . "-000001",
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany, $ibagian)->result(),
            'bundling'      => $this->mmaster->view_databundling($id, $idcompany)->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
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

        $id          = $this->input->post('id', TRUE);
        $ibagian     = $this->input->post('ibagian', TRUE);
        $ibonk       = $this->input->post('ibonk', TRUE);
        $dbonk       = $this->input->post('dbonk', TRUE);
        if ($dbonk) {
            $tmp   = explode('-', $dbonk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datebonk  = $year . '-' . $month . '-' . $day;
        }

        $itujuan      = $this->input->post('itujuan', TRUE);
        $array_tujuan = explode('|', $itujuan);
        $_itujuan = $array_tujuan[1];
        $_id_company_tujuan = $array_tujuan[0];

        $ijenis       = $this->input->post('ijenis', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        $jml_item     = $this->input->post('jml_item', TRUE);

        $i_product    = $this->input->post('idproduct[]', TRUE);
        $id_marker    = $this->input->post('idmarker[]', TRUE);
        $i_color      = $this->input->post('idcolorproduct[]', TRUE);
        $n_qtyproduct = $this->input->post('nquantity[]', TRUE);
        $n_qtyproduct = str_replace(',', '', $n_qtyproduct);
        $e_desc       = $this->input->post('edesc[]', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonk);
        $this->mmaster->updateheader($id, $ibonk, $ibagian, $datebonk, $_itujuan, $_id_company_tujuan, $ijenis, $eremark);
        $this->mmaster->deletebundling($id);
        $this->mmaster->deletedetail($id);

        // $no = 0;
        // foreach ($i_product as $iproduct) {
        //     $iproduct    = $iproduct;
        //     $imarker    = $id_marker[$no];
        //     $icolor      = $i_color[$no];
        //     $nqtyproduct = $n_qtyproduct[$no];
        //     $edesc       = $e_desc[$no];
        //     if ($nqtyproduct != 0 && $nqtyproduct != null) {
        //         $this->mmaster->insertdetail($id, $iproduct, $imarker, $icolor, $nqtyproduct, $edesc);
        //     }
        //     $no++;
        // }

        $no = 0;
        $no2 = 1;
        for ($a = 0; $a<count($i_product); $a++/* $i_product as $iproduct */) {
            $iproduct    = $i_product[$a];
            $imarker    = $id_marker[$a];
            $icolor      = $i_color[$a];
            $nqtyproduct = $n_qtyproduct[$a];
            $edesc       = $e_desc[$a];
            if ($nqtyproduct != 0 && $nqtyproduct != null) {
                // var_dump($id, $iproduct, $imarker, $icolor, $nqtyproduct, $edesc);
                $this->mmaster->insertdetail($id, $iproduct, $imarker, $icolor, $nqtyproduct, $edesc);
            }
            // $no--;
            $iditem = $this->mmaster->runningiditem();
            for($i = 1; $i <= $jml_item; $i++) {
                $eproduct_bundle = $this->input->post("eproduct_bundle$no2$i");
                $n_qty_bundle = $this->input->post("n_qty_bundle$no2$i");
                if(($eproduct_bundle != '' && $n_qty_bundle != 0) || ($eproduct_bundle != null && $n_qty_bundle != null)) {
                    // var_dump($id, $iditem, $eproduct_bundle, $n_qty_bundle);
                    $this->mmaster->insertbundling($id, $iditem, $eproduct_bundle, $n_qty_bundle);
                }
            }
            $no2++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $ibonk,
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

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->view_datadetail($id, $idcompany)->result(),
            'bundling'      => $this->mmaster->view_databundling($id, $idcompany)->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval()
    {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment('4');
        $dfrom      = $this->uri->segment('5');
        $dto        = $this->uri->segment('6');
        $i_bagian   = $this->uri->segment('7');
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'i_bagian'      => $i_bagian,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany, $i_bagian)->result(),
            'bundling'      => $this->mmaster->view_databundling($id, $idcompany)->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function cetak()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id_company = $this->session->userdata('id_company');

        $id = $this->uri->segment(4);
        $dfrom = $this->uri->segment(5);
        $dto = $this->uri->segment(6);
        $ibagian = $this->uri->segment(7);

        $_data = $this->mmaster->cek_data_print($id, $id_company)->row();
        $no_urut = $this->generate_nomor_urut_cetak($_data->i_document, $ibagian);

        $data = [
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $id_company)->result(),
            'data'          => $_data,
            'datadetail' => $this->mmaster->cek_data_detail_print($id, $id_company, $ibagian)->result(),
            'bundling'      => $this->mmaster->view_databundling($id, $id_company)->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
            'company' => $this->mmaster->session_company()->row(),
            'no_urut'   => $no_urut
        ];

        $this->Logger->write('Cetak Data ' . $this->global['title'].' Id : '.$id);

        $this->load->view($this->global['folder'] . '/vformprint', $data);
    }

    public function generate_nomor_urut_cetak($i_document=null, $i_bagian=null)
    {
        $array = explode('-', $i_document);
        $_urutan = $array[2];
        
        $_ym = $array[1];
        $ym = str_split($_ym, 2);
        $y = $ym[0];
        $m = $ym[1];

        $kode_lokasi = null;
        $query = $this->mmaster->get_kode_lokasi_bagian($i_bagian);
        
        if ($query->row() != null) {
            $kode_lokasi = $query->row()->e_kode_lokasi;
        }

        $bulan = angkaRomawi($m);
        
        return "$_urutan-$kode_lokasi-$bulan-$y"; 
        // return $text;
    }
}
/* End of file Cform.php */