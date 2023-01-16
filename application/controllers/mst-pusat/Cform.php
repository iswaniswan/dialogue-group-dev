<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2010807';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    function data()
    {
        echo $this->mmaster->data($this->i_menu, $this->global['folder']);
    }

    public function status()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id == '') {
            $id = $this->uri->segment(4);
        }
        if ($id != '') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if (($this->db->trans_status() === False)) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status ' . $this->global['title'] . ' Id : ' . $id);
                echo json_encode($data);
            }
        }
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'bank'          => $this->mmaster->bank(),
            'area'          => $this->mmaster->area(),
            'harga'         => $this->mmaster->harga(),
            'i_group'       => $this->mmaster->get_group(),
            'typeindustry'  => $this->mmaster->get_type_industry()->result(),
            'levelcompany'  => $this->mmaster->get_level_company()->result(),
        );


        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_data($this->input->post('kode', TRUE));
        if ($data->num_rows() > 0) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }

    public function getpusat()
    {
        $isuppliergroup = $this->input->post('isuppliergroup');
        $ilevelcompany  = $this->input->post('ilevelcompany');

        if ($ilevelcompany == 'PLV01') {
            $query = $this->mmaster->getpusat($isuppliergroup, 'PLV00');
            if ($query->num_rows() > 0) {
                $c      = "";
                $jenis  = $query->result();
                foreach ($jenis as $row) {
                    $c .= "<option value=" . $row->i_kepala_pusat . " >" . $row->e_pusat . "</option>";
                }
                // $kop  = "<option value=\"\"> -- Pilih Kepala Group -- ".$c."</option>";
                $kop = $c;
                echo json_encode(array(
                    'kop'   => $kop
                ));
            } else {
                $kop  = "<option value=\"\">Kepala Group Kosong</option>";
                echo json_encode(array(
                    'kop'    => $kop,
                    'kosong' => 'kopong'
                ));
            }
        } else {
            $kop  = "<option value=\"\"></option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getcity()
    {
        $iarea = $this->input->post('iarea');

        $query = $this->mmaster->getcity($iarea);
        if ($query->num_rows() > 0) {
            $c      = "";
            $jenis  = $query->result();
            foreach ($jenis as $row) {
                $c .= "<option value=" . $row->id . " >" . $row->e_city_name . "</option>";
            }
            $kop = $c;
            echo json_encode(array(
                'kop'   => $kop
            ));
        } else {
            $kop  = "<option value=\"\">Tidak Ada Data</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $icustomer              = $this->input->post('icustomer', TRUE);
        $ecustomername          = $this->input->post('ecustomername', TRUE);
        $ecustomeraddress       = $this->input->post('ecustomeraddress', TRUE);
        $ecity                  = $this->input->post('ecity', TRUE);
        $epostalcode            = $this->input->post('epostalcode', TRUE);
        $ecustomerphone         = $this->input->post('ecustomerphone', TRUE);
        $ecustomerfax           = $this->input->post('ecustomerfax', TRUE);
        $ecustomercontact       = $this->input->post('ecustomercontact', TRUE);
        $ncustomerdiscount      = $this->input->post('ncustomerdiscount1', TRUE);
        $ncustomerdiscount2     = $this->input->post('ncustomerdiscount2', TRUE);
        $ncustomerdiscount3     = $this->input->post('ncustomerdiscount3', TRUE);
        $fcustomerkonsinyasi    = $this->input->post('fcustomerkonsinyasi', TRUE);
        if (isset($fcustomerkonsinyasi)) {
            $fkonsinyasi = 't';
        } else {
            $fkonsinyasi = 'f';
        }
        $ncustomertop           = $this->input->post('ncustomertop', TRUE);
        $fcustomerpkp           = $this->input->post('fcustomerpkp', TRUE);
        if (isset($fcustomerpkp)) {
            $fpkp = 't';
        } else {
            $fpkp = 'f';
        }
        $icustomernpwp          = $this->input->post('icustomernpwp', TRUE);
        $ecustomernpwpname      = $this->input->post('ecustomernpwpname', TRUE);
        $icustomerpwpaddress    = $this->input->post('icustomerpwpaddress', TRUE);
        $ibank                  = $this->input->post('ibank', TRUE);
        $inorekening            = $this->input->post('inorekening', TRUE);
        $enamarekening          = $this->input->post('enamarekening', TRUE);
        $igroup                 = $this->input->post('igroup', TRUE);
        $iarea                  = $this->input->post('iarea', TRUE);
        $itypeindustry          = $this->input->post('itypeindustry', TRUE);
        $ilevelcompany          = $this->input->post('ilevelcompany', TRUE);
        $ikepalapusat           = $this->input->post('ikepalapusat', TRUE);
        $iharga                 = $this->input->post('iharga', TRUE);

        $e_shipping_address     = $this->input->post('e_shipping_address', TRUE);
        $e_billing_address      = $this->input->post('e_billing_address', TRUE);

        $id                     = $this->mmaster->runningid();

        if ($icustomer != '') {
            $cekada = $this->mmaster->cek_data($icustomer);
            if ($cekada->num_rows() > 0) {
                $data = array(
                    'sukses' => false
                );
            } else {
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $icustomer);
                $this->mmaster->insert($id, $icustomer, $ecustomername, $ecustomeraddress, $ecity, $epostalcode, $ecustomerphone, $ecustomerfax, $ecustomercontact, $ncustomerdiscount, $fkonsinyasi, $ncustomertop, $fpkp, $icustomernpwp, $ecustomernpwpname, $icustomerpwpaddress, $ibank, $inorekening, $enamarekening, $igroup, $itypeindustry, $ilevelcompany, $ikepalapusat, $ncustomerdiscount2, $ncustomerdiscount3, $iarea, $iharga, $e_shipping_address, $e_billing_address);

                $data = array(
                    'sukses'    => true,
                    'kode'      => $icustomer,
                    'id'        => $id,
                );
            }
        } else {
            $data = array(
                'sukses' => false,
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

        $id             = $this->uri->segment(4);
        $icustomergroup = $this->uri->segment(5);
        $ilevelcompany  = $this->uri->segment(6);
        $iarea          = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $id,
            'data'          => $this->mmaster->get_data($id)->row(),
            'typeindustry'  => $this->mmaster->get_type_industry()->result(),
            'levelcompany'  => $this->mmaster->get_level_company()->result(),
            'bank'          => $this->mmaster->bank(),
            'area'          => $this->mmaster->area(),
            'harga'         => $this->mmaster->harga(),
            'city'          => $this->mmaster->getcity($iarea)->result(),
            'customergroup' => $this->mmaster->get_group(),
            'kepalapusat'   => $this->mmaster->getpusat($icustomergroup, $ilevelcompany)->result()
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

        $id                     = $this->input->post('id', TRUE);
        $icustomer              = $this->input->post('icustomer', TRUE);
        $ecustomername          = $this->input->post('ecustomername', TRUE);
        $ecustomeraddress       = $this->input->post('ecustomeraddress', TRUE);
        $ecity                  = $this->input->post('ecity', TRUE);
        $epostalcode            = $this->input->post('epostalcode', TRUE);
        $ecustomerphone         = $this->input->post('ecustomerphone', TRUE);
        $ecustomerfax           = $this->input->post('ecustomerfax', TRUE);
        $ecustomercontact       = $this->input->post('ecustomercontact', TRUE);
        $ncustomerdiscount      = $this->input->post('ncustomerdiscount1', TRUE);
        $fcustomerkonsinyasi    = $this->input->post('fcustomerkonsinyasi', TRUE);
        if (isset($fcustomerkonsinyasi)) {
            $fkonsinyasi = 't';
        } else {
            $fkonsinyasi = 'f';
        }
        $ncustomertop           = $this->input->post('ncustomertop', TRUE);
        $fcustomerpkp           = $this->input->post('fcustomerpkp', TRUE);
        if (isset($fcustomerpkp)) {
            $fpkp = 't';
        } else {
            $fpkp = 'f';
        }
        $icustomernpwp          = $this->input->post('icustomernpwp', TRUE);
        $ecustomernpwpname      = $this->input->post('ecustomernpwpname', TRUE);
        $icustomerpwpaddress    = $this->input->post('icustomerpwpaddress', TRUE);
        $ibank                  = $this->input->post('ibank', TRUE);
        $inorekening            = $this->input->post('inorekening', TRUE);
        $enamarekening          = $this->input->post('enamarekening', TRUE);
        $igroup                 = $this->input->post('igroup', TRUE);
        $itypeindustry          = $this->input->post('itypeindustry', TRUE);
        $ilevelcompany          = $this->input->post('ilevelcompany', TRUE);
        $ikepalapusat           = $this->input->post('ikepalapusat', TRUE);
        $ncustomerdiscount2     = $this->input->post('ncustomerdiscount2', TRUE);
        $ncustomerdiscount3     = $this->input->post('ncustomerdiscount3', TRUE);
        $iarea                  = $this->input->post('iarea', TRUE);
        $iharga                 = $this->input->post('iharga', TRUE);

        $e_shipping_address     = $this->input->post('e_shipping_address', TRUE);
        $e_billing_address      = $this->input->post('e_billing_address', TRUE);

        if ($icustomer != '') {
            $cekada = $this->mmaster->cek_data($icustomer);
            if ($cekada->num_rows() > 0) {
                $this->mmaster->update($id, $icustomer, $ecustomername, $ecustomeraddress, $ecity, $epostalcode, $ecustomerphone, $ecustomerfax, $ecustomercontact, $ncustomerdiscount, $fkonsinyasi, $ncustomertop, $fpkp, $icustomernpwp, $ecustomernpwpname, $icustomerpwpaddress, $ibank, $inorekening, $enamarekening, $igroup, $itypeindustry, $ilevelcompany, $ikepalapusat, $ncustomerdiscount2, $ncustomerdiscount3, $iarea, $iharga, $e_shipping_address, $e_billing_address);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $icustomer,
                    'id'        => $id
                );
            } else {
                $data = array(
                    'sukses' => false
                );
            }
        } else {
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function view()
    {

        $id             = $this->uri->segment(4);
        $icustomergroup = $this->uri->segment(5);
        $ipusat         = $this->uri->segment(6);
        $ilevelcompany  = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'id'            => $id,
            'data'          => $this->mmaster->get_data($id)->row(),
            'typeindustry'  => $this->mmaster->get_type_industry()->result(),
            'levelcompany'  => $this->mmaster->get_level_company()->result(),
            'bank'          => $this->mmaster->bank(),
            'area'          => $this->mmaster->area(),
            'harga'         => $this->mmaster->harga(),
            'customergroup' => $this->mmaster->get_group(),
            'kepalapusat'   => $this->mmaster->getpusat($icustomergroup, $ilevelcompany)->result()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
}
/* End of file Cform.php */