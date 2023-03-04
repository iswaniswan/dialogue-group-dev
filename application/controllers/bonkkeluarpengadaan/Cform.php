<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2090302';

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

        echo $this->mmaster->data($this->global['folder'], $this->i_menu, $dfrom, $dto);
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "SJ-" . date('ym') . "-0001",
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            // $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE), $this->input->post('itujuan', TRUE), $this->input->post('id', TRUE));
            $ibagian = $this->input->post('ibagian');
            $itujuan = $this->input->post('itujuan');
            $number = $this->mmaster->generate_nomor_dokumen($ibagian, $itujuan);
        }
        echo json_encode($number);
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

    public function cekkodeedit()
    {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode', TRUE), $this->input->post('kodeold', TRUE), $this->input->post('ibagian', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    /*----------  CARI BARANG  ----------*/

    public function product()
    {
        $filter = [];
        if ($this->input->get('q') != '') {
            $data = $this->mmaster->product(str_replace("'", "", $this->input->get('q')), $this->input->get('ibagian'));
            if ($data->num_rows() > 0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'   => $row->id . '-' . $row->id_color,
                        'text' => $row->i_product_wip . ' - ' . $row->e_product_wipname . ' - ' . $row->e_color_name
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
                'text' => "Cari Barang Berdasarkan Nama / Kode"
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL BARANG  ----------*/

    public function detailproduct()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->detailproduct($this->input->post('id', TRUE))->result_array()
        );
        echo json_encode($query);
    }

    public function getstok()
    {
        header("Content-Type: application/json", true);
        $produk = explode('-', $this->input->post('idproduct'));
        $ibagian = $this->input->post('ibagian');
        $data = '';
        if ($produk[0] != '') {
            $data = $this->mmaster->getstok($produk[0], $ibagian)->row();
        }

        echo json_encode($data);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian     = $this->input->post('ibagian', TRUE);
        $itujuan      = $this->input->post('itujuan', TRUE);
        
        /** regenerate nomor dokumen */
        // $ibonk       = $this->input->post('ibonk', TRUE);
        $ibonk = $this->mmaster->generate_nomor_dokumen($ibagian, $itujuan);

        $dbonk       = $this->input->post('dbonk', TRUE);
        if ($dbonk) {
            $tmp   = explode('-', $dbonk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datebonk  = $year . '-' . $month . '-' . $day;
        }
        
        $ijenis       = $this->input->post('ijenis', TRUE);
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        if ($ibonk != '' && $datebonk != '' && $ibagian != '' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $ijenis, $eremarkh);

            for ($x = 1; $x <= $jml; $x++) {
                $idproduct = $this->input->post('idproduct' . $x, TRUE);

                if ($idproduct != "" || $idproduct != NULL) {
                    $product = explode('-', $idproduct);
                    $produk = $product[0];
                    $color = $product[1];
                    $nquantitywip = str_replace(",", ".", $this->input->post('nquantity'.$x , TRUE));
                    $i_periode = $this->input->post('periode'. $x).'-01';
                    $detailproduk = $this->mmaster->detailproduct($produk, $color)->result();
                    $edesc     = $this->input->post("eremark" . $x, TRUE);

                    foreach ($detailproduk as $rowdetail) {
                        $this->mmaster->insertdetail($id, $rowdetail->id_product_wip, $color, $nquantitywip, $nquantitywip, $edesc, $i_periode);
                    }
                }
            }
            //die();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $ibonk,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }

        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            // 'number'        => "SJ-".date('ym')."-123456",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
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

        $id           = $this->input->post('id', TRUE);
        $ibonk        = $this->input->post('ibonk', TRUE);
        $ibonkold     = $this->input->post('ibonkold', TRUE);
        $dbonk        = $this->input->post('dbonk', TRUE);
        if ($dbonk) {
            $tmp   = explode('-', $dbonk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datebonk  = $year . '-' . $month . '-' . $day;
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $itujuan      = $this->input->post('itujuan', TRUE);
        $ijenis       = $this->input->post('ijenis', TRUE);
        $eremarkh     = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);

        // var_dump($id_company, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark);
        // die();

        if ($ibonk != '' && $datebonk != '' && $ibagian != '' && $jml > 0) {
            $cekdata     = $this->mmaster->cek_kode($ibonkold, $ibagian);
            if ($cekdata->num_rows() == 0) {
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $ibonk, $datebonk, $ibagian, $itujuan, $ijenis, $eremarkh);
                $this->mmaster->deletedetail($id);

                for ($x = 1; $x <= $jml; $x++) {
                    $idproduct = $this->input->post('idproduct' . $x, TRUE);

                    if ($idproduct != "" || $idproduct != NULL) {
                        $product = explode('-', $idproduct);
                        $produk = $product[0];
                        $color = $product[1];
                        $nquantitywip = str_replace(",", ".", $this->input->post('nquantity' . $x, TRUE));
                        $i_periode = $this->input->post('periode'. $x).'-01';
                        $detailproduk = $this->mmaster->detailproduct($produk, $color)->result();
                        $edesc = $this->input->post("eremark" . $x, TRUE);

                        foreach ($detailproduk as $rowdetail) {
                            $this->mmaster->insertdetail($id, $rowdetail->id_product_wip, $color, $nquantitywip, $nquantitywip, $edesc, $i_periode);
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
                        'kode'   => $ibonk,
                        'id'     => $id
                    );
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
                }
            }
        } else {
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
        $id_jenis = $this->db->query("SELECT id_jenis_barang_keluar FROM tm_keluar_pengadaan WHERE id='$id'")->row();
        if($istatus == '6' && $id_jenis->id_jenis_barang_keluar == '1') {
            $insert = $this->insertProses($id);
            if($insert) {
                $this->db->trans_begin();
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
        } else {
            $this->db->trans_begin();
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
    }

    public function insertProses($id)
    {
        $keluarpengadaanitem = $this->mmaster->getpengadaanitembyidkeluar($id)->result();
        $maxId = $this->mmaster->getmaxidtrproses()->row();
        $data = array();
        foreach($keluarpengadaanitem as $row) {
            for($i=0;$i<$row->n_quantity_product_wip;$i++) {
                array_push($data, [
                    'id' => ++$maxId->id,
                    'id_product_base' => $row->id_product_base,
                    'id_product_wip' => $row->id_product_wip,
                    'id_keluar_pengadaan' => $row->id_keluar_pengadaan
                ]);
            }
        }
        $this->db->trans_begin();
        $this->mmaster->inserttrproses($data);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Insert proses keluar pengadaan,id keluar pengadaan: ' . $id);
            return true;
        }
    }

    /*----------  MEMBUKA MENU Approve  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJ-" . date('ym') . "-1234",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany  = $this->session->userdata('id_company');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJ-" . date('ym') . "-1234",
            'tujuan'        => $this->mmaster->tujuan($this->i_menu, $idcompany)->result(),
            'jenisbarang'   => $this->mmaster->jeniskeluar()->result(),
            'doc'           => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function cetak()
    {
        $data = check_role($this->i_menu, 5);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $id = decrypt_url($this->uri->segment(4));
        $data = array(
            'id' => $id, 
            'title' => "STB Pengadaan", 
            'data' => $this->mmaster->get_print($id),
        );

        $this->Logger->write('Cetak Data ' . $this->global['title'].' Id : '.$id);

        $this->load->view($this->global['folder'] . '/vformprint', $data);
    }

    public function cetak2()
    {
        $data = check_role($this->i_menu, 5);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $id = decrypt_url($this->uri->segment(4));
        $data = array(
            'id' => $id, 
            'title' => "STB Pengadaan", 
            'data_product' => $this->mmaster->get_print_product($id),
            'data_barcode' => $this->mmaster->get_print_barcode($id),
        );

        $this->Logger->write('Cetak Data ' . $this->global['title'].' Id : '.$id);

        $this->load->view($this->global['folder'] . '/vformprintbarcode', $data);
    }

    public function cetak3()
    {
        $data = check_role($this->i_menu, 5);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $id = decrypt_url($this->uri->segment(4));
        $data = array(
            'id' => $id, 
            'title' => "STB Pengadaan", 
            'data_product' => $this->mmaster->get_print_product($id),
            'data_barcode' => $this->mmaster->get_print_barcode($id),
        );

        $this->Logger->write('Cetak Data ' . $this->global['title'].' Id : '.$id);

        $this->load->view($this->global['folder'] . '/vformprintqrcode', $data);
    }

    public function cetak4()
    {
        $id = str_replace('%20', ' ', $this->uri->segment(4)) ;

        $event = $this->load->database('event', TRUE);
        $query = $event->query("
            select id, e_participant_name from tr_participant where e_participant_company = '$id' or idcabang::text = '$id'
        ");

        $data = array(
            'id' => $id, 
            'title' => $id, 
            'data_barcode' => $query,
        );

        $this->load->view($this->global['folder'] . '/vformprintevent', $data);
    }

    public function cetakevent()
    {
        
        $event = $this->load->database('event', TRUE);
        // $query = $event->query("
        //     select id, e_company, e_name, e_ruangan from tr_participant_new order by e_company, e_name asc
        // ");
        
        $query = $event->query("
            select id, e_company, e_name, e_ruangan from tr_participant_new  where d_entry::date = current_date order by e_company, e_name asc
        ");

        $data = array(
            'data_barcode' => $query,
        );

        $this->load->view($this->global['folder'] . '/vformprinteventnew', $data);
    }
}
/* End of file Cform.php */