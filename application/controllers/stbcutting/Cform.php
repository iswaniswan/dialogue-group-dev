<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2090105';

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
            'dfrom'     => formatdmY($dfrom),
            'dto'       => formatdmY($dto)
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

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist_referensi', $data);
    }

    public function data_referensi()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
        echo $this->mmaster->data_referensi($dfrom, $dto);
    }

    public function proses()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $dfrom    = $this->input->post('dfrom', true);
        $dto      = $this->input->post('dto', true);

        $company  = [];
        $schedule = [];
        if ($this->input->post('jml', true) > 0) {
            for ($i = 1; $i <= $this->input->post('jml', true); $i++) {
                $check       = $this->input->post('chk' . $i, true);
                $id_schedule = $this->input->post('id' . $i, true);
                $id_company_referensi = $this->input->post('id_company_referensi' . $i, true);
                if ($check == 'on') {
                    array_push($company, $id_company_referensi);
                    array_push($schedule, $id_schedule);
                }
            }
        }
        $company     = array_unique($company);
        $schedule    = array_unique($schedule);
        $id_company  = implode(",", $company);
        $id_schedule = "'" . implode("', '", $schedule) . "'";

        /* var_dump($id_company, $company);
        die; */

        if (count($company) == 1) {
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Transfer " . $this->global['title'],
                'title_list' => 'List ' . $this->global['title'],
                'data_detail' => $this->mmaster->data_detail($id_schedule),
                'company'    => $this->db->get_where('public.company', ['id' => $id_company])->row(),
                'dfrom'      => $dfrom,
                'dto'        => $dto,
                'bagian'     => $this->mmaster->bagian(),
                'jenis'      => $this->db->get_where('tr_jenis_barang_keluar', ['id <>' => '3']),
            );
            $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);
            $this->load->view($this->global['folder'] . '/vformadd', $data);
        } else {
            echo '<script>
            swal({
                title: "Maaf :(",
                text: "Perusahaan Tidak Boleh Beda!",
                showConfirmButton: true,
                type: "error",
                },function(){
                    show("' . $this->global['folder'] . '/cform/tambah/' . $dfrom . '/' . $dto . '","#main");
                    });
            </script>';
        }
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))));
        }
        echo json_encode($number);
    }

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
        $id_company_tujuan = $this->input->post('id_company_tujuan', TRUE);
        $id_jenis_barang_keluar = $this->input->post('id_jenis_barang_keluar', TRUE);
        $e_remark = $this->input->post('e_remark', TRUE);
        $jml = $this->input->post('jml', TRUE);
        $id = $this->mmaster->runningid();
        if ($i_bagian != '' && $i_document != '' && $id_company_tujuan != '' && $d_document != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->simpan_header($id, $i_document, $d_document, $i_bagian, $id_company_tujuan, $e_remark, $id_jenis_barang_keluar);
            for ($i = 1; $i <= $jml; $i++) {
                $id_schedule_item = $this->input->post('id_schedule_item' . $i, TRUE);
                $id_panel_item = $this->input->post('id_panel_item' . $i, TRUE);
                $n_quantity_stb_cutting = str_replace(",", "", $this->input->post('n_quantity_stb_cutting' . $i, TRUE));
                $n_quantity_panel = str_replace(",", "", $this->input->post('n_quantity_panel' . $i, TRUE));
                $n_quantity_selisih = str_replace(",", "", $this->input->post('n_quantity_selisih' . $i, TRUE));
                $n_quantity = str_replace(",", "", $this->input->post('n_quantity' . $i, TRUE));
                $n_qty_penyusun = str_replace(",", "", $this->input->post('n_qty_penyusun' . $i, TRUE));
                $n_jumlah_gelar = str_replace(",", "", $this->input->post('n_jumlah_gelar' . $i, TRUE));
                // $n_quantity_manual = str_replace(",", "", $this->input->post('n_quantity_manual' . $i, TRUE));
                $e_remark_item = $this->input->post('e_remark_item' . $i, TRUE);
                if ($id_schedule_item != '' && $id_panel_item != '') {
                    $this->mmaster->simpan_item($id, $id_schedule_item, $id_panel_item, $n_quantity_stb_cutting, $n_quantity_panel, $n_quantity_selisih, $n_quantity, $e_remark_item, $n_qty_penyusun, $n_jumlah_gelar);
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
    }

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id  = $this->uri->segment(4);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->get_data_headers($id)->row(),
            'data_detail' => $this->mmaster->get_data_items($id),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'jenis'      => $this->db->get_where('tr_jenis_barang_keluar', ['id <>' => '3']),
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

        $id         = $this->input->post('id', TRUE);
        $i_document = $this->input->post('i_document', TRUE);
        $d_document = $this->input->post('d_document', TRUE);
        if ($d_document != '') {
            $d_document = formatYmd($d_document);
        }
        $i_bagian = $this->input->post('i_bagian', TRUE);
        $e_remark = $this->input->post('e_remark', TRUE);
        $id_jenis_barang_keluar = $this->input->post('id_jenis_barang_keluar', TRUE);
        $jml = $this->input->post('jml', TRUE);
        if ($id != '' && $i_bagian != '' && $i_document != ''  && $d_document != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update_header($id, $i_document, $d_document, $i_bagian, $e_remark, $id_jenis_barang_keluar);
            for ($i = 1; $i <= $jml; $i++) {
                $id_item = $this->input->post('id_item' . $i, TRUE);
                $id_schedule_item = $this->input->post('id_schedule_item' . $i, TRUE);
                $id_panel_item = $this->input->post('id_panel_item' . $i, TRUE);
                $n_quantity_stb_cutting = str_replace(",", "", $this->input->post('n_quantity_stb_cutting' . $i, TRUE));
                $n_quantity_panel = str_replace(",", "", $this->input->post('n_quantity_panel' . $i, TRUE));
                $n_quantity_selisih = str_replace(",", "", $this->input->post('n_quantity_selisih' . $i, TRUE));
                $n_quantity = str_replace(",", "", $this->input->post('n_quantity' . $i, TRUE));
                // $n_quantity_manual = str_replace(",", "", $this->input->post('n_quantity_manual' . $i, TRUE));
                $e_remark_item = $this->input->post('e_remark_item' . $i, TRUE);
                if ($id_schedule_item != '' && $id_item != '' && $id_panel_item != '') {
                    $this->mmaster->update_item($id_item, $id, $id_schedule_item, $id_panel_item, $n_quantity_stb_cutting, $n_quantity_panel, $n_quantity_selisih, $n_quantity, $e_remark_item);
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
                    'kode'   => $i_document,
                    'sukses' => true,
                    'id'     => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id . ', Kode : ' . $i_document);
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

    public function approval()
    {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id  = $this->uri->segment(4);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->get_data_headers($id)->row(),
            'data_detail' => $this->mmaster->get_data_items($id),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function view()
    {
        $id  = $this->uri->segment(4);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $this->mmaster->get_data_headers($id)->row(),
            'data_detail' => $this->mmaster->get_data_items_view($id),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
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

        $_data = $this->mmaster->get_data_headers_print($id)->row();
        $no_urut = $this->generate_nomor_urut_cetak($_data->i_document, $ibagian);

        $data = [
            'folder'     => $this->global['folder'],
            'title'      => "Cetak " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data'       => $_data,
            'datadetail' => $this->mmaster->get_data_items_view($id)->result(),
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian(),            
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
