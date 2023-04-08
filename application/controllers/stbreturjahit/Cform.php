<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090406';

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
        $this->doc_qe = $data[0]['doc_qe'];
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
            'number'        => "SJ-" . date('ym') . "-000001",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function get_bagian_tujuan()
    {
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->get_bagian_tujuan($cari);
        foreach ($data->result() as $bagian) {
            $filter[] = array(
                'id'    => $bagian->id,
                'text'  => $bagian->e_bagian_name,
                'name'  => $bagian->name,
            );
        }
        echo json_encode($filter);
        
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
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE));
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
                'name'  => $product->e_product_basename,
                'text'  => $product->i_product_base . ' - ' . $product->e_product_basename . ' - ' . $product->e_color_name,
            );
        }
        echo json_encode($filter);
    }

    public function get_reject()
    {
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->db->query("select id, e_reject_name, e_group, e_remark from tr_reject where f_status = true and e_reject_name ilike '%$cari%'", FALSE);

        //$this->db->get_where('tr_reject',['f_status'=>'t']);

        if ($data->num_rows() > 0) {
            $group   = [];
            $arr     = [];
            foreach ($data->result() as $key) {
                $arr[] = $key->e_group;
            }
            $unique_data = array_unique($arr);
            foreach ($unique_data as $val) {
                $child  = [];
                foreach ($data->result() as $row) {
                    if ($val == $row->e_group) {
                        $child[] = array(
                            'id' => $row->id,
                            'text' => $row->e_reject_name,
                            'title' => $row->e_remark,
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

        // foreach($data->result() as $row){       
        //     $filter[] = array(
        //         'id'    => $row->id,
        //         'text'  => $row->e_reject_name,
        //     );
        // }   
        echo json_encode($filter);
    }

    public function getproduct()
    {
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getproduct($this->input->post('eproduct'));

        echo json_encode($data->result_array());
    }

    public function getdataitem()
    {
        header("Content-Type: application/json", true);
        $ibagian  = $this->input->post('ibagian');
        $dfrom  = date('Y-m-d', strtotime($this->input->post('dfrom')));
        $dto    = date('Y-m-d', strtotime($this->input->post('dto')));
        $data = array(
            'dataitem' => $this->mmaster->getdataitem($ibagian, $dfrom, $dto)->result_array()
        );
        echo json_encode($data);
    }

    public function simpan()
    {
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
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        $id           = $this->mmaster->runningid();

        $i_product    = $this->input->post('idproduct[]', TRUE);
        $i_color      = $this->input->post('idcolorproduct[]', TRUE);
        $n_qtyawal    = $this->input->post('nquantityawal[]', TRUE);
        $n_qtyawal    = str_replace(',', '', $n_qtyawal);
        $n_qtyproduct = $this->input->post('nquantity[]', TRUE);
        $n_qtyproduct = str_replace(',', '', $n_qtyproduct);
        $e_desc       = $this->input->post('edesc[]', TRUE);
        $bagian       = $this->input->post('bagian[]', TRUE);
        $id_reject    = $this->input->post('id_reject[]', TRUE);
        $detail_reject = $this->input->post('detail_reject[]', TRUE);
        $idreference = $this->input->post('idreference[]', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonk);
        $this->mmaster->insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark);

        $no = 0;
        foreach ($i_product as $iproduct) {
            if ($n_qtyproduct[$no] > 0) {
                $iproduct    = $iproduct;
                $icolor      = $i_color[$no];
                $nqtyawal    = $n_qtyawal[$no];
                $nqtyproduct = $n_qtyproduct[$no];
                $edesc       = $e_desc[$no];
                // $edesc       = null;
                $e_bagian    = $bagian[$no];
                $reject      = $id_reject[$no];
                $det_reject  = $detail_reject[$no];
                $id_reff  = $idreference[$no];

                $this->mmaster->insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc, $e_bagian, $reject, $det_reject, $id_reff, $nqtyawal);
            }
            $no++;
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
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'number'        => "SJ-" . date('ym') . "-000001",
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany)->result(),
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
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        $i_product    = $this->input->post('idproduct[]', TRUE);
        $i_color      = $this->input->post('idcolorproduct[]', TRUE);
        $n_qtyawal    = $this->input->post('nquantityawal[]', TRUE);
        $n_qtyawal    = str_replace(',', '', $n_qtyawal);
        $n_qtyproduct = $this->input->post('nquantity[]', TRUE);
        $n_qtyproduct = str_replace(',', '', $n_qtyproduct);
        $e_desc       = $this->input->post('edesc[]', TRUE);
        $bagian       = $this->input->post('bagian[]', TRUE);
        $id_reject    = $this->input->post('id_reject[]', TRUE);
        $detail_reject = $this->input->post('detail_reject[]', TRUE);
        $idreference = $this->input->post('idreference[]', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $ibonk);
        $this->mmaster->updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark);
        $this->mmaster->deletedetail($id);

        $no = 0;
        foreach ($i_product as $iproduct) {
            if ($n_qtyproduct[$no] > 0) {
                $iproduct    = $iproduct;
                $icolor      = $i_color[$no];
                $nqtyawal    = $n_qtyawal[$no];
                $nqtyproduct = $n_qtyproduct[$no];
                $edesc       = $e_desc[$no];
                $e_bagian    = $bagian[$no];
                $reject      = $id_reject[$no];
                $det_reject  = $detail_reject[$no];
                $id_reff  = $idreference[$no];

                $this->mmaster->insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc, $e_bagian, $reject, $det_reject, $id_reff, $nqtyawal);
            }
            $no++;
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
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);
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
        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'id'            => $id,
            'bagian'        => $this->mmaster->bagian()->result(),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'data'          => $this->mmaster->cek_data($id, $idcompany)->row(),
            'detail'        => $this->mmaster->cek_datadetail($id, $idcompany)->result(),
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
            'folder' => $this->global['folder'],
            'title' => "Cetak ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
            'id' => $id,
            'bagian' => $this->mmaster->bagian()->result(),
            'tujuan' => $this->mmaster->tujuan($this->i_menu, $id_company)->result(),
            'data' => $_data,
            'datadetail' => $this->mmaster->cek_datadetail_print($id, $id_company)->result(),
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