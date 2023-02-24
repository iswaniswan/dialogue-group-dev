<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '204021303';

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
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
            'dfrom' => $dfrom,
            'dto' => $dto,
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

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'bagian' => $this->mmaster->bagian()->result(),
            'number' => "DT-" . date('ym') . "-0001",
            'dfrom' => $this->uri->segment(4),
            'dto' => $this->uri->segment(5),
            'all_area' => $this->mmaster->area()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))), date('Y', strtotime($this->input->post('tgl', TRUE))), $this->input->post('ibagian', TRUE), $this->input->post('i_area', TRUE), $this->input->post('id', TRUE));
        }
        echo json_encode($number);
    }

    public function generate_nomor_dokumen()
    {
        $number = "";

        if ($this->input->post('tgl', TRUE) != '') {
            $id_bagian = $this->input->post('ibagian');
            $number = $this->mmaster->generate_nomor_dokumen($id_bagian);
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

    public function nota()
    {
        $filter = [];
        $data = $this->mmaster->nota(str_replace("'", "", $this->input->get('q')), $this->input->get('i_area'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id' => $row->id,
                    'text' => $row->i_document . ' - ' . $row->e_customer_name
                );
            }
        } else {
            $filter[] = array(
                'id' => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL NOTA  ----------*/

    public function detail_tunai_item()
    {
        header("Content-Type: application/json", true);
        $query = array(
            'detail' => $this->mmaster->detail_tunai_item($this->input->post('id'))->result_array()
        );
        echo json_encode($query);
    }

    public function __simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian = $this->input->post('ibagian');
        $i_dt_id = $this->input->post('i_dt_id');
        $d_dt = $this->input->post('d_dt');
        if (strlen($d_dt) > 0) {
            $d_dt = formatYmd($d_dt);
        } else {
            $d_dt = date('Y-m-d');
        }
        $i_area = $this->input->post('i_area');
        $v_jumlah = $this->input->post('v_jumlah');
        $jml = $this->input->post('jml');

        if ($ibagian != '' && $d_dt != '' && $ibagian != '' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->create_header($id, $i_dt_id, $ibagian, $i_area, $d_dt, $v_jumlah);
            $no = 0;
            for ($x = 1; $x <= $jml; $x++) {
                $i_nota = $this->input->post('i_nota' . $x);

                if ($i_nota != "" || $i_nota != NULL || strlen($i_nota) > 0) {
                    $no++;
                    $d_nota = $this->input->post('d_nota_' . $x);
                    $v_bayar = str_replace(",", ".", $this->input->post('v_nota_' . $x));
                    $v_sisa = str_replace(",", ".", $this->input->post('v_sisa_' . $x));
                    $this->mmaster->create_detail($id, $i_nota, $d_nota, $v_sisa, $v_bayar, $no);
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
                    'kode' => $i_dt_id,
                    'id' => $id
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

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id_bagian = $this->input->post('ibagian');
        $i_st_id = $this->input->post('i_st_id');
        $d_dt = $this->input->post('d_dt');
        /** reformat tanggal */
        $d_dt = formatYmd($d_dt);

        $id_area = $this->input->post('id_area');
        $id_bank = $this->input->post('id_bank');
        
        $keterangan = $this->input->post('keterangan');
        $items = $this->input->post('items');
        
        $grand_total = $this->input->post('grand_total');
        $grand_total = str_replace(".", "", $grand_total);

        $result = [
            'sukses' => false,
            'kode' => '-',
            'id' => '-'
        ];

        /** insert table */
        $this->db->trans_begin();            
        // $this->mmaster->create_header($id, $i_st_id, $ibagian, $i_area, $d_dt, $v_jumlah);
        $this->mmaster->insert_setor_tunai($i_st_id, $d_dt, $id_bagian, $id_company=null, 
                                        $id_area, $id_bank, $keterangan, $grand_total);

        $insert_id = $this->db->insert_id();

        foreach ($items as $item) {
            $i_tunai = $item['i_tunai'];
            $v_jumlah = $item['v_jumlah'];
            $v_jumlah = str_replace(".", "", $v_jumlah);
            $this->mmaster->insert_setor_tunai_item($insert_id, $i_tunai, $v_jumlah, null);            
        }        
            
        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            $result = [
                'sukses' => true,
                'kode' => $i_st_id,
                'id' => $insert_id
            ];
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $insert_id);
        } 

        $this->db->trans_rollback();
        $this->load->view('pesan2', $result);
    }

    /*----------  MEMBUKA MENU EDIT  ----------*/

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id' => $this->uri->segment(4),
            'dfrom' => $this->uri->segment(5),
            'dto' => $this->uri->segment(6),
            'bagian' => $this->mmaster->bagian()->result(),
            'data' => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'all_area' => $this->mmaster->area()->result(),
            'all_bank' => $this->mmaster->get_all_bank()->result()
            // 'doc' => $this->mmaster->doc($this->i_menu)->row()->doc_qe,
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post('id');
        $id_bagian = $this->input->post('ibagian');
        $i_st_id = $this->input->post('i_st_id');
        $d_st = $this->input->post('d_st');
        /** reformat tanggal */
        $d_st = formatYmd($d_st);

        $id_area = $this->input->post('id_area');
        $id_bank = $this->input->post('id_bank');
        
        $keterangan = $this->input->post('keterangan');
        $items = $this->input->post('items');
        
        $grand_total = $this->input->post('grand_total');
        $grand_total = str_replace(".", "", $grand_total);

        $result = [
            'sukses' => false,
            'kode' => '-',
            'id' => '-'
        ];

        /** insert table */
        $this->db->trans_begin();            
        // $this->mmaster->create_header($id, $i_dt_id, $ibagian, $i_area, $d_st, $v_jumlah);
        $this->mmaster->update_setor_tunai($i_st_id, $d_st, $id_bagian, $id_company=null, 
                                        $id_area, $id_bank, $keterangan, $grand_total, $id);

        $this->mmaster->delete_setor_tunai_item($id);                                        

        foreach ($items as $item) {
            $i_tunai = $item['i_tunai'];
            $v_jumlah = $item['v_jumlah'];
            $v_jumlah = str_replace(".", "", $v_jumlah);
            $this->mmaster->insert_setor_tunai_item($id, $i_tunai, $v_jumlah, null);
        }      
            
        if ($this->db->trans_status()) {
            $this->db->trans_commit();
            $result = [
                'sukses' => true,
                'kode' => $i_st_id,
                'id' => $id
            ];
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
        } 

        $this->db->trans_rollback();
        $this->load->view('pesan2', $result);
    }

    public function __update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post('id', TRUE);
        $ibagian = $this->input->post('ibagian');
        $i_dt_id = $this->input->post('i_dt_id');
        $d_dt = $this->input->post('d_dt');
        if (strlen($d_dt) > 0) {
            $d_dt = formatYmd($d_dt);
        } else {
            $d_dt = date('Y-m-d');
        }
        $i_area = $this->input->post('i_area');
        $v_jumlah = $this->input->post('v_jumlah');
        $jml = $this->input->post('jml');

        if ($ibagian != '' && $d_dt != '' && $ibagian != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update_header($id, $i_dt_id, $ibagian, $i_area, $d_dt, $v_jumlah);
            $this->mmaster->delete($id);
            $no = 0;
            for ($x = 1; $x <= $jml; $x++) {
                $i_nota = $this->input->post('i_nota' . $x);
                if ($i_nota != "" || $i_nota != NULL || strlen($i_nota) > 0) {
                    $no++;
                    $d_nota = $this->input->post('d_nota_' . $x);
                    $v_bayar = str_replace(",", ".", $this->input->post('v_nota_' . $x));
                    $v_sisa = str_replace(",", ".", $this->input->post('v_sisa_' . $x));
                    $this->mmaster->create_detail($id, $i_nota, $d_nota, $v_sisa, $v_bayar, $no);
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
                    'kode' => $i_dt_id,
                    'id' => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
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

    /*----------  MEMBUKA MENU Approve  ----------*/

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

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
            'area'          => $this->mmaster->area()->result(),
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

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Lihat " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'area'          => $this->mmaster->area()->result(),
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
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $id,
            'company'       => $this->db->get_where('public.company',['id'=>$this->id_company])->row(),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($id)->row(),
            'detail'        => $this->mmaster->dataeditdetail($id)->result(),
        );

        $this->Logger->write('Cetak Data ' . $this->global['title'] . ' Id : ' . $id);

        $this->load->view($this->global['folder'] . '/print', $data);
    }

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

    public function get_all_customer()
    {
        $q = $this->input->get('q');
        $id_area = $this->input->get('id_area');

        $data = [];

        $query = $this->mmaster->get_all_customer(str_replace("'", "", $q), $id_area);       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->id,
                'text' => $result->e_customer_name
            );
        }
        echo json_encode($data);
    }

    public function get_all_salesman()
    {
        $q = $this->input->get('q');
        $id_area = $this->input->get('id_area');
        $id_customer = $this->input->get('id_customer');

        $data = [];

        $query = $this->mmaster->get_all_salesman(str_replace("'", "", $q), $id_area, $id_customer);       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->id,
                'text' => $result->e_sales
            );
        }
        echo json_encode($data);
    }

    public function get_all_daftar_tagihan()
    {
        $q = $this->input->get('q');
        $id_area = $this->input->get('id_area');

        $data = [];

        $query = $this->mmaster->get_all_daftar_tagihan(str_replace("'", "", $q), $id_area);       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->i_dt,
                'text' => $result->i_dt_id
            );
        }
        echo json_encode($data);
    }

    public function get_all_tunai_item()
    {
        $q = $this->input->get('q');
        $i_tunai = $this->input->get('i_tunai');

        $data = [];

        $query = $this->mmaster->get_all_tunai_item(str_replace("'", "", $q), $i_tunai);
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->i_tunai,
                'text' => $result->i_tunai_id,
                'userdata' => [
                    'data' => $result
                ]
            );
        }
        echo json_encode($data);
    }



    public function get_all_bank()
    {
        $q = $this->input->get('q');

        $query = $this->mmaster->get_all_bank(str_replace("'", "", $q));       
            
        foreach ($query->result() as $result) {
            $data[] = array(
                'id' => $result->id,
                'text' => $result->e_bank_name
            );
        }
        echo json_encode($data);
    }
}
/* End of file Cform.php */