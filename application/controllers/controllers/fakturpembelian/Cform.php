<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2040101';

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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
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

    public function awal()
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
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformmain', $data);
    }

    function awalnext()
    {

        echo $this->mmaster->awalnext();
    }

    public function tambah()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vform', $data);
    }

    public function supplier()
    {
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->bacasupplier($cari);
        foreach ($data->result() as  $ikode) {
            $filter[] = array(
                'id'   => $ikode->i_supplier,
                'text' => $ikode->i_supplier . '-' . $ikode->e_supplier_name,
            );
        }
        echo json_encode($filter);
    }

    public function getiop()
    {
        $cari       = strtoupper($this->input->get('q'));
        $isupplier  = $this->input->post('isupplier');
        $query      = $this->mmaster->getiop($isupplier, $cari);
        if ($query->num_rows() > 0) {
            $c  = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .= "<option value=" . $row->id_op . " >" . $row->i_op . "</option>";
            }
            $kop  = "<option value=\"IOP\"> -- Pilih Order Pembelian -- " . $c . "</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        } else {
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getibtb()
    {
        $cari       = strtoupper($this->input->get('q'));
        $id         = $this->input->post('id');
        $isupplier  = $this->input->post('isupplier');
        $iop        = $this->input->post('iop');

        $query = $this->mmaster->getibtb($id, $isupplier, $iop, $cari);
        if ($query->num_rows() > 0) {
            $c  = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .= "<option value=" . $row->id . " >" . $row->i_btb . "</option>";
            }
            $kop  = "<option value=\"IBTB\"> -- Semua Bukti Terima Barang -- " . $c . "</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        } else {
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function btb()
    {
        $query = $this->mmaster->btb();
        if ($query->num_rows() > 0) {
            $c  = "";
            $ibtb = $query->result();
            foreach ($ibtb as $row) {
                $c .= "<option value=" . $row->id . " >" . $row->i_btb . "</option>";
            }
            $kop  = "<option value=\"all\">Semua Nomor Bukti Terima Barang" . $c . "</option>";
            $kop = $c;
            echo json_encode(array(
                'kop'   => $kop
            ));
        } else {
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getidoksup()
    {
        $cari       = strtoupper($this->input->get('q'));
        $isupplier  = $this->input->post('isupplier');
        $iop        = $this->input->post('iop');
        $ibtb       = $this->input->post('ibtb');
        $query      = $this->mmaster->getidoksup($isupplier, $iop, $ibtb, $cari);
        if ($query->num_rows() > 0) {
            $c  = "";
            $spb = $query->result();
            foreach ($spb as $row) {
                $c .= "<option value=" . $row->i_sj_supplier . " >" . $row->i_sj_supplier . "</option>";
            }
            $kop  = "<option value=\"ISJ\"> -- Semua No Dok Supplier -- " . $c . "</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        } else {
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function getipayment()
    {
        header("Content-Type: application/json", true);
        $isupplier = $this->input->post('isupplier');
        $this->db->select("a.i_jenis_pembelian, case a.i_jenis_pembelian when '0' then 'Cash' when '1' then 'Kredit' end as epayment
                    from duta_prod.tr_supplier a
                    where i_supplier='$isupplier'");
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function proses()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $isupplier      = $this->input->post('isupplier', TRUE);
        $iop            = $this->input->post('id', TRUE);
        $ibtb           = $this->input->post('ibtb', TRUE);
        $isj            = $this->input->post('isj', TRUE);
        //var_dump($iop);
        //var_dump($ibtb);
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'datasup'    => $this->mmaster->cek_sup($isupplier)->row(),
            'data'       => $this->mmaster->get_btbitem($isupplier)->row(),
            'data1'      => $this->mmaster->get_item2($isupplier, $iop, $ibtb)->result(),
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => 'FP-'.date('Ym').'-123456(optional)',
        );
        $this->Logger->write('Membuka Menu Input Item ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vforminput', $data);
    }

    public function number(){
        $number = "";
        if ($this->input->post('dnota', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('dnota', TRUE))),date('Y', strtotime($this->input->post('dnota', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

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

        $ibagian      = $this->input->post('ibagian', TRUE);
        $ifaktur      = $this->input->post('ifaktur', TRUE);
        $inota        = $this->input->post('inota', TRUE);
        $isupplier    = $this->input->post("isupplier", TRUE);
        $isuppliername = $this->input->post("isuppliername", TRUE);
        $fsupplierpkp = $this->input->post("fsupplierpkp");
        $dnota        = $this->input->post('dnota', TRUE);
        if ($dnota) {
            $tmp   = explode('-', $dnota);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonthh = $year . $month;
            $datenota = $year . '-' . $month . '-' . $day;
        }
        $ipajak       = $this->input->post("ipajak", TRUE);
        $dpajak       = $this->input->post('dpajak', TRUE);
        if ($dpajak) {
            $tmp   = explode('-', $dpajak);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datepajak = $year . '-' . $month . '-' . $day;
        } else {
            $datepajak = null;
        }
        $dreceivefaktur = $this->input->post('dreceivefaktur', TRUE);
        if ($dreceivefaktur) {
            $tmp   = explode('-', $dreceivefaktur);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datereceivefaktur = $year . '-' . $month . '-' . $day;
        } else {
            $datereceivefaktur = date('Y-m-d');
        }
        $dfsupp = $this->input->post('dfsupp', TRUE);
        if ($dfsupp) {
            $tmp   = explode('-', $dfsupp);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datefsupp = $year . '-' . $month . '-' . $day;
        }
        $djatuhtempo  = $this->input->post('djatuhtempo', TRUE);
        if ($djatuhtempo) {
            $tmp   = explode('-', $djatuhtempo);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datejatuhtempo = $year . '-' . $month . '-' . $day;
        }
        $ntop         = $this->input->post('ntop', TRUE);
        $vdiskon      = $this->input->post('vdiskon', TRUE);
        $fpkp         = $this->input->post('fsupplierpkp', TRUE);
        $vtotaldpp    = str_replace(',', '', $this->input->post("vtotaldpp", TRUE));
        $vtotalppn    = str_replace(',', '', $this->input->post("vtotalppn", TRUE));
        $vtotal       = str_replace(',', '', $this->input->post("vtotalfa", TRUE));
        $vsisa        = str_replace(',', '', $this->input->post("vtotalfa", TRUE));
        $vtotaldis    = str_replace(',', '', $this->input->post("vtotaldis", TRUE));
        $vtotalbruto  = str_replace(',', '', $this->input->post("vtotalbruto", TRUE));
        $vtotalnet    = str_replace(',', '', $this->input->post("vtotalnet", TRUE));
        $vdiskontotal = str_replace(',', '', $this->input->post("vdiskontotal", TRUE));
        $vdiskonsup   = str_replace(',', '', $this->input->post("vdiskonsup", TRUE));
        $vdiskon      = str_replace(',', '', $this->input->post("vdiskon", TRUE));
        $v_pembulatan = str_replace(',', '', $this->input->post("v_pembulatan", TRUE));
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);

        //$inota = $this->mmaster->runningnumber($yearmonthh);
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $inota);
        $id       = $this->mmaster->runningid();
        $this->mmaster->insert($id, $inota, $datenota, $ipajak, $datepajak, $isupplier, $datereceivefaktur, $vdiskon, $vtotaldpp, $vtotalppn, $vtotal, $eremark, $datefsupp, $vtotaldis, $vtotalbruto, $vtotalnet, $ibagian, $ntop, $isuppliername, $vsisa, $datejatuhtempo, $ifaktur, $vdiskontotal, $vdiskonsup, $vdiskon, $v_pembulatan);
        //var_dump($jml);
        for ($i = 1; $i <= $jml; $i++) {
            if ($this->input->post('cek' . $i) == 'on') {
                // var_dump($jml);
                $idpp           = $this->input->post('idpp' . $i, TRUE);
                $idiop          = $this->input->post('idiop' . $i, TRUE);
                $idbtb          = $this->input->post('idbtb' . $i, TRUE);
                $isj            = $this->input->post('isj' . $i, TRUE);
                $dsj            = $this->input->post('dsj' . $i, TRUE);
                $ibtb           = $this->input->post('ibtb' . $i, TRUE);
                $iop            = $this->input->post('iop' . $i, TRUE);
                $imaterial      = $this->input->post('imaterial' . $i, TRUE);
                $f_toleransi    = $this->input->post('plus' . $i, TRUE);
                $f_toleransi = ($f_toleransi == 'on') ? TRUE : FALSE;
                $nquantitybtb   = str_replace(',', '', $this->input->post('qty' . $i, TRUE));
                $toleransi      = str_replace(',', '', $this->input->post('toleransi' . $i, TRUE));
                $nquantity      = str_replace(',', '', $this->input->post('nquantity' . $i, TRUE));
                $vprice         = str_replace(',', '', $this->input->post('vharga' . $i, TRUE));
                $vprice_manual  = str_replace(',', '', $this->input->post('vharga_manual' . $i, TRUE));
                $vdpp           = str_replace(',', '', $this->input->post('vdpp' . $i, TRUE));
                $vppn           = str_replace(',', '', $this->input->post('vppn' . $i, TRUE));
                $vtotalsem      = str_replace(',', '', $this->input->post('vtotalsem' . $i, TRUE));
                $v_pembulatan_item = str_replace(',', '', $this->input->post('v_pembulatan_item' . $i, TRUE));
                $itipe          = $this->input->post('itipe' . $i, TRUE);
                //$inoitem  = $i;

                $this->mmaster->insertdetail($id, $isj, $dsj, $idbtb, $idiop, $imaterial, $nquantity, $vprice, $vprice_manual, $vdpp, $vppn, $vtotalsem, $itipe, $fpkp, $idpp, $f_toleransi, $nquantitybtb, $toleransi, $v_pembulatan_item);
                $this->mmaster->updatestatus($idbtb, $idiop, $imaterial, $idpp);
                //$this->mmaster->updatestatusop($idiop, $imaterial);
            }
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
                'kode'   => $inota,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function send()
    {
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function changestatus()
    {

        /* $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        } */

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

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment('4');
        $isupplier  = $this->uri->segment('5');
        $dfrom      = $this->uri->segment('6');
        $dto        = $this->uri->segment('7');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->cek_data($id)->row(),
            'date'       => $this->mmaster->get_date($id)->row(),
            'data1'      => $this->mmaster->get_itemm($id, $isupplier)->result(),
            'ppnop'      => $this->mmaster->get_ppn_op_edit($id)->row(),
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
        $inota      = $this->input->post('inota', TRUE);
        $dnota      = $this->input->post('dnota', TRUE);
        $ifaktur      = $this->input->post('ifaktur', TRUE);
        if ($dnota) {
            $tmp   = explode('-', $dnota);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datenota = $year . '-' . $month . '-' . $day;
        }
        $dreceivefaktur      = $this->input->post('dreceivefaktur', TRUE);
        if ($dreceivefaktur) {
            $tmp   = explode('-', $dreceivefaktur);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datereceivefaktur = $year . '-' . $month . '-' . $day;
        }
        $dpajak      = $this->input->post('dpajak', TRUE);
        if ($dpajak) {
            $tmp   = explode('-', $dpajak);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datepajak = $year . '-' . $month . '-' . $day;
        } else {
            $datepajak = null;
        }

        $djatuhtempo  = $this->input->post('djatuhtempo', TRUE);
        if ($djatuhtempo) {
            $tmp   = explode('-', $djatuhtempo);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datejatuhtempo = $year . '-' . $month . '-' . $day;
        }
        $ipajak       = $this->input->post("ipajak", TRUE);
        $isupplier    = $this->input->post("isupplier", TRUE);
        $vdiskon      = ($this->input->post("vdiskon", TRUE));
        $vtotal       = str_replace(',', '', $this->input->post("vtotalfa", TRUE));
        $vsisa        = str_replace(',', '', $this->input->post("vtotalfa", TRUE));
        $vtotaldis    = str_replace(',', '', $this->input->post("vtotaldis", TRUE));
        $vdiskontotal = str_replace(',', '', $this->input->post("vdiskontotal", TRUE));
        $vdiskonsup   = str_replace(',', '', $this->input->post("vdiskonsup", TRUE));
        $vdiskon      = str_replace(',', '', $this->input->post("vdiskon", TRUE));


        $vtotaldpp    = str_replace(',', '', $this->input->post("vtotaldpp", TRUE));
        $vtotalppn    = str_replace(',', '', $this->input->post("vtotalppn", TRUE));
        $vtotal       = str_replace(',', '', $this->input->post("vtotalfa", TRUE));
        $vsisa        = str_replace(',', '', $this->input->post("vtotalfa", TRUE));
        $vtotaldis    = str_replace(',', '', $this->input->post("vtotaldis", TRUE));
        $vtotalbruto  = str_replace(',', '', $this->input->post("vtotalbruto", TRUE));
        $vtotalnet    = str_replace(',', '', $this->input->post("vtotalnet", TRUE));
        $vdiskontotal = str_replace(',', '', $this->input->post("vdiskontotal", TRUE));
        $vdiskonsup   = str_replace(',', '', $this->input->post("vdiskonsup", TRUE));
        $vdiskon      = str_replace(',', '', $this->input->post("vdiskon", TRUE));
        $v_pembulatan = str_replace(',', '', $this->input->post("v_pembulatan", TRUE));

        $eremark      = ($this->input->post("eremark", TRUE));
        $jml          = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $inota);
        $this->mmaster->update($id, $inota, $datenota, $ipajak, $isupplier, $vtotal, $vsisa, $datereceivefaktur, $datepajak, $vdiskon, $eremark, $datejatuhtempo, $ifaktur, $vtotaldis, $vdiskontotal, $vdiskonsup, $vdiskon, $vtotaldpp, $vtotalppn, $vtotalbruto, $vtotalnet, $v_pembulatan);
        // $this->mmaster->deletenotasj($id);

        // for($i=1;$i<=$jml;$i++){   
        //      if($this->input->post('cek'.$i)=='cek'){  
        //         $isj      = $this->input->post('isj'.$i, TRUE);
        //         $dsj      = $this->input->post('dsj'.$i, TRUE);
        //         $ibtb     = $this->input->post('ibtb'.$i, TRUE);
        //         $iop      = $this->input->post('iop'.$i, TRUE);
        //         $imaterial= $this->input->post('imaterial'.$i, TRUE);
        //         $nquantity= $this->input->post('nquantity'.$i, TRUE);
        //         $isatuan  = $this->input->post('isatuan'.$i, TRUE);
        //         $vprice   = str_replace(',','',$this->input->post('vharga'.$i,TRUE));
        //         $vdpp     = str_replace(',','',$this->input->post('vdpp'.$i,TRUE));
        //         $vppn     = str_replace(',','',$this->input->post('vppn'.$i,TRUE));
        //         $vtotalsem= str_replace(',','',$this->input->post('vtotalsem'.$i,TRUE));
        //         $inoitem  = $i;

        //         $this->mmaster->insertdetail($inota, $isj, $dsj, $ibtb, $iop, $imaterial, $nquantity, $isatuan, $vprice, $vdpp, $vppn, $vtotalsem, $inoitem);
        //        // $this->mmaster->updatesj($isj);
        //     //}
        // }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'      => $inota,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function sendd()
    {
        header("Content-Type: application/json", true);
        $inota = $this->input->post('inota');
        $this->mmaster->sendd($inota);
    }

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment('4');
        $isupplier  = $this->uri->segment('5');
        $dfrom      = $this->uri->segment('6');
        $dto        = $this->uri->segment('7');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->cek_data($id)->row(),
            'data1'      => $this->mmaster->get_itemm($id, $isupplier)->result(),
            'ppnop'      => $this->mmaster->get_ppn_op_edit($id)->row(),
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
        $isupplier  = $this->uri->segment('5');
        $dfrom      = $this->uri->segment('6');
        $dto        = $this->uri->segment('7');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->cek_data($id)->row(),
            'data1'      => $this->mmaster->get_itemm($id, $isupplier)->result(),
            'ppnop'      => $this->mmaster->get_ppn_op_edit($id)->row(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $inota        = $this->input->post('inota', TRUE);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($inota);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Faktur Pembelian ' . $inota);
            echo json_encode($data);
        }
    }


    public function proses2()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $xnoop = '';
        $supplier = array();
        $idbtb = array();
        if ($this->input->post('jml', true) > 0) {
            for ($i = 1; $i <= $this->input->post('jml', true); $i++) {
                $check = $this->input->post('chk' . $i, true);
                $id    = $this->input->post('id' . $i, true);
                $btb   = $this->input->post('btb' . $i, true);
                $isupplier   = $this->input->post('isupplier' . $i, true);
                if ($check == 'on') {
                    //$this->mmaster->closing($id);
                    //$xnoop .= $iop." - ";
                    array_push($supplier, $isupplier);
                    array_push($idbtb, $btb);
                }
            }
        }
        $supplier = array_unique($supplier);
        $idbtb = array_unique($idbtb);

        $isupplier = implode(",", $supplier);
        // $idbtb = implode(",", ."'".$idbtb."'");
        $idbtb = "'" . implode("', '", $idbtb) . "'";
        // var_dump();
        // die();

        if (count($supplier) == 1) {
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Tambah " . $this->global['title'],
                'title_list' => 'List ' . $this->global['title'],
                'datasup'    => $this->mmaster->cek_sup($isupplier)->row(),
                'data'       => $this->mmaster->get_btbitem($isupplier, $idbtb)->row(),
                'data1'      => $this->mmaster->get_item2($isupplier, $idbtb)->result(),
                'ppnop'      => $this->mmaster->get_ppn_op($idbtb)->row(),
                'dfrom'      => $this->input->post('dfrom', true),
                'dto'        => $this->input->post('dto', true),
                'bagian'     => $this->mmaster->bagian()->result(),
                'number'     => 'FP-'.date('Ym').'-123456(optional)',
            );
            $this->Logger->write('Membuka Menu Input Item ' . $this->global['title']);
            $this->load->view($this->global['folder'] . '/vforminput', $data);
        } else {
            $data = array(
                'sukses' => false,
                'kode' => "Supplier Tidak Boleh Beda"
            );
            $this->load->view('pesan2', $data);
        }
    }

    public function editpajak()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment('4');
        $isupplier  = $this->uri->segment('5');
        $dfrom      = $this->uri->segment('6');
        $dto        = $this->uri->segment('7');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit PAJAK " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->cek_data($id)->row(),
            'data1'      => $this->mmaster->get_itemm($id, $isupplier)->result(),
            'ppnop'      => $this->mmaster->get_ppn_op_edit($id)->row(),
        );

        $this->Logger->write('Membuka Menu Edit Pajak ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformeditpajak', $data);
    }

    public function update_pajak()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->input->post('id', TRUE);
        $inota      = $this->input->post('inota', TRUE);
        $ipajak     = $this->input->post('ipajak', TRUE);
        $dpajak     = $this->input->post('dpajak', TRUE);
        $ifaktur    = $this->input->post('ifaktur', TRUE);
        if ($dpajak) {
            $tmp   = explode('-', $dpajak);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dpajak = $year . '-' . $month . '-' . $day;
        } else {
            $dpajak = null;
        }
        $this->db->trans_begin();
        $this->mmaster->update_pajak($id, $ipajak, $dpajak);
        $this->Logger->write('Update Data Faktur Pajak ' . $this->global['title'] . ' Kode : ' . $inota);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $data = array(
                'sukses'  => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode'   => $inota,
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */