<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2040119';

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
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'] . '/mmaster');
    }

    public function index(){
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

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
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

    public function tambah(){
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagian()->result(),
            'number'        => "FP-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function getpartner()
    {
        $filter = [];
        $data   = $this->mmaster->getpartner(strtoupper($this->input->get('q')),$this->i_menu);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id_supplier.'|'.$row->i_supplier.'|'.$row->i_type_pajak.'|'.$row->f_pkp.'|'.$row->n_supplier_toplength.'|'.$row->n_diskon,
                'text'  => $row->e_supplier_name,
            );
        }
        echo json_encode($filter);
    }

    public function getreferensi()
    {
        $filter = [];
        $partner   = explode('|', $this->input->get('ipartner'));
        $idpartner = $partner[0];
        $ipartner  = $partner[1];
        $ipajak    = $partner[2];
        $data   = $this->mmaster->getreferensi(strtoupper($this->input->get('q')),$idpartner,$this->i_menu);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id,
                'text'  => $row->i_document,
            );
        }
        echo json_encode($filter);
    }

    public function getdetailref(){
        header("Content-Type: application/json", true);
        $id      = $this->input->post('id');
        $partner = explode('|', $this->input->post('partner'));
        $idpartner = $partner[0];
        $ipartner  = $partner[1]; 
        $ipajak    = $partner[2];
        $fpkp      = $partner[3];
        $ntop      = $partner[4];
        $ndiskon   = $partner[5];
        $jml    = $this->mmaster->getdetailref($id, $idpartner, $this->i_menu);
        $query  = array(
            'head'   => $this->mmaster->getheadref($id, $ipajak, $fpkp, $ntop, $ndiskon)->row(),
            'jmlitem'=> $jml->num_rows(),
            'detail' => $this->mmaster->getdetailref($id, $idpartner, $this->i_menu)->result_array()
        );
        echo json_encode($query);  
    }

    public function cekkode()
    {
        $data = $this->mmaster->cekkode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
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

    public function simpan() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        
        $ibagian        = $this->input->post('ibagian', TRUE);
        $inota          = $this->input->post('inota', TRUE);
        $dnota          = date('Y-m-d', strtotime($this->input->post('dnota', TRUE)));
        $dreceivefaktur = date('Y-m-d', strtotime($this->input->post('dreceivefaktur', TRUE)));
        $partner        = explode('|', $this->input->post('ipartner'));
        $idpartner      = $partner[0];
        $ipartner       = $partner[1];
        $itypepajak     = $partner[2];
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $dreferensi     = date('Y-m-d', strtotime($this->input->post('dreferensi', TRUE)));
        $ifaktursup     = $this->input->post('ifaktursupp', TRUE);
        $dfaktursup     = date('Y-m-d', strtotime($this->input->post('dfaktursup'), TRUE));
        $ipajak         = $this->input->post('ipajak', TRUE);
        $dpajak         = date('Y-m-d', strtotime($this->input->post('dpajak', TRUE)));
        $djatuhtempo    = date('Y-m-d', strtotime($this->input->post('djatuhtempo', TRUE)));
        $vdiskon        = $this->input->post('vdiskon', TRUE);
        $vtotaldiskon   = $this->input->post('vtotaldis', TRUE);
        $diskonsup      = $this->input->post('diskonsup', TRUE);
        $vtotaldpp      = $this->input->post('vtotaldpp', TRUE);
        $vtotalppn      = $this->input->post('vtotalppn', TRUE);
        $vtotalbruto    = $this->input->post('vtotalbruto', TRUE);
        $vtotalnetto    = $this->input->post('vtotalnetto', TRUE);
        $vtotalneto     = $this->input->post('vtotalneto', TRUE);
        $vtotalfa       = $this->input->post('vtotalfa', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);
     
        if ($inota!='' && $dnota!='' && $ibagian!='' && $partner!='' && $ireferensi!='') {
            $cekkode = $this->mmaster->cekkode($inota, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $ibagian, $inota, $dnota, $dreceivefaktur, $ipartner, $ireferensi, $dreferensi, $ifaktursup, $dfaktursup, $ipajak, 
                                             $dpajak, $vdiskon, $vtotaldiskon, $diskonsup, $vtotaldpp, $vtotalppn, $vtotalbruto, $vtotalnetto, $vtotalfa, $eremark, $djatuhtempo);
                $x = 0;
                for($i=1;$i<=$jml;$i++){
                    $idreffmasuk    = $this->input->post('idreffitem'.$i, TRUE);
                    $idwip          = $this->input->post('idproductwip'.$i, TRUE);
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $harga          = $this->input->post('vprice'.$i, TRUE);
                    $hargatotal     = $this->input->post('vtotalitem'.$i, TRUE);
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $this->mmaster->insertdetail($id, $idreffmasuk, $idwip, $nquantity, $harga, $hargatotal, $edesc);

                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $inota);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $inota,
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

    public function view() {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id       = $this->uri->segment(4);
        $ipartner = $this->uri->segment(5);
        $dfrom    = $this->uri->segment(6);
        $dto      = $this->uri->segment(7);

        $ada = false;
        $query = $this->mmaster->bacapp($id);
        if($query->num_rows()>0){
            foreach($query->result() as $row){
                $id = $row->id;
                $ada =true;
            }
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'ada'        => $ada,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->bacaheader($id, $ipartner)->row(),
            'detail'     => $this->mmaster->bacadetail($id)->result(),
            'cekpp'      => $this->mmaster->bacapp($id)->row()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function getdetailsjedit(){
        header("Content-Type: application/json", true);
        $sj         = $this->input->post('sj', FALSE);
        $partner    = $this->input->post('partner', FALSE);
        $nota       = $this->input->post('nota', FALSE);
        $query  = array(
            'head'     => $this->mmaster->gethead($sj, $partner)->row(),
            'detail'   => $this->mmaster->getdetailedit($sj, $partner,$nota)->result_array()
        );
        echo json_encode($query);  
    }

    public function edit() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id       = $this->uri->segment(4);
        $ipartner = $this->uri->segment(5);
        $dfrom    = $this->uri->segment(6);
        $dto      = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'number'        => "FP-".date('ym')."-123456",
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->bacaheader($id, $ipartner)->row(),
            'detail'        => $this->mmaster->bacadetail($id)->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $inotaold       = $this->input->post('inotaold', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $inota          = $this->input->post('inota', TRUE);
        $dnota          = date('Y-m-d', strtotime($this->input->post('dnota', TRUE)));
        $dreceivefaktur = date('Y-m-d', strtotime($this->input->post('dreceivefaktur', TRUE)));
        $ipartner       = $this->input->post('ipartner');
        $ireferensi     = $this->input->post('ireferensi', TRUE);
        $dreferensi     = date('Y-m-d', strtotime($this->input->post('dreferensi', TRUE)));
        $ifaktursup     = $this->input->post('ifaktursupp', TRUE);
        $dfaktursup     = date('Y-m-d', strtotime($this->input->post('dfaktursup'), TRUE));
        $ipajak         = $this->input->post('ipajak', TRUE);
        $dpajak         = date('Y-m-d', strtotime($this->input->post('dpajak', TRUE)));
        $vdiskon        = $this->input->post('vdiskon', TRUE);
        $vtotaldiskon   = $this->input->post('vtotaldis', TRUE);
        $diskonsup      = $this->input->post('diskonsup', TRUE);
        $vtotaldpp      = $this->input->post('vtotaldpp', TRUE);
        $vtotalppn      = $this->input->post('vtotalppn', TRUE);
        $vtotalbruto    = $this->input->post('vtotalbruto', TRUE);
        $vtotalnetto    = $this->input->post('vtotalnetto', TRUE);
        $vtotalneto     = $this->input->post('vtotalneto', TRUE);
        $vtotalfa       = $this->input->post('vtotalfa', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        if ($inota!='' && $dnota!='' && $ibagian!='') {
            $cekkode = $this->mmaster->cek_kodeedit($inota, $inotaold, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $ibagian, $ipartner, $inota, $dnota, $dreceivefaktur, $ifaktursup, $dfaktursup, $ipajak, 
                                             $dpajak, $vdiskon, $vtotaldiskon, $diskonsup, $vtotaldpp, $vtotalppn, $vtotalbruto, $vtotalnetto, $vtotalfa, $eremark);
                $x = 0;
                for($i=1;$i<=$jml;$i++){
                    $idreffmasuk    = $this->input->post('idreffitem'.$i, TRUE);
                    $idwip          = $this->input->post('idproductwip'.$i, TRUE);
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $harga          = $this->input->post('vprice'.$i, TRUE);
                    $hargatotal     = $this->input->post('vtotalitem'.$i, TRUE);
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $this->mmaster->updatedetail($id, $idreffmasuk, $idwip, $nquantity, $harga, $hargatotal, $edesc);

                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $inota);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $inota,
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

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function changestatus(){

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

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
}
/* End of file Cform.php */