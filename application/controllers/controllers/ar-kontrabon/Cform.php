<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller
{
    public $global = array();
    public $i_menu = '2040201';

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

        $ipartner = $this->input->post('ipartner', TRUE);
        if($ipartner== ''){
            $ipartner  = $this->uri->segment(6);
            if($ipartner== ''){
                $ipartner = 'ALL';
            }
        }  

        $ijenis = "";

        $idpartner    = $this->input->post('idpartner', TRUE);
        if($idpartner== ''){
            $idpartner  = $this->uri->segment(7);
            if($idpartner== ''){
                $idpartner = 'ALL';
            }
        }  

        $epartnertype = $this->input->post('epartnertype');
        if($epartnertype== ''){
            $epartnertype  = $this->uri->segment(8);
        }  


        $epartner = $this->input->post('epartner');
        if($epartner== ''){
            $epartner  = $this->uri->segment(9);
        }  
/*var_dump($i)*/
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'ipartner'      => $ipartner,
            'idpartner'     => $idpartner,
            'epartnertype'  => $epartnertype,
            'epartner'      => $epartner,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function getpartner(){
        $filter = [];
        $data = $this->mmaster->getpartner();
        if ($data->num_rows()>0) {
            $group    = [];
            $arr      = [];
            $filter[] = array(
                            'id'   => 'ALL', 
                            'text' => "Semua Partner", 
                        );

            foreach ($data->result() as $key) {
                $arr[] = $key->grouppartner;
            }
            $unique_data = array_unique($arr);           
            foreach($unique_data as $val) {
                $child  = [];
                foreach ($data->result() as $row) {                    
                    if ($val==$row->grouppartner) {
                        $child[] = array(
                            'id'   => $row->id.'-'.$row->grouppartner, 
                            'text' => $row->nama, 
                        );
                    }
                    
                }
                $filter[] = array(
                    'id'        => 0,
                    'text'      => strtoupper($val),
                    'children'  => $child
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }   

    public function data() {
        $dfrom          = $this->uri->segment(4);
        $dto            = $this->uri->segment(5);
        $ipartner       = $this->uri->segment(6);
        $idpartner      = $this->uri->segment(7);
        $epartnertype   = $this->uri->segment(8);
        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto,$ipartner,$idpartner,$epartnertype);
    }

    public function tambah() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_next = new DateTime();
        $one_month_next->modify('+7 day');
        $awal = $d->format('d-m-Y');
        $akhir  = $one_month_next->format('d-m-Y');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'bagian'        => $this->mmaster->bagian()->result(),
            'jenis'         => $this->mmaster->jenis(),
            'number'        => "KNB-".date('ym')."-000001",
            'dfrom'         => $awal,
            'dto'           => $akhir,
            'ldfrom'        => $this->uri->segment(4),
            'ldto'          => $this->uri->segment(5),
        );
        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function cekkode() {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function cekkodeedit() {
        $data = $this->mmaster->cek_kodeedit($this->input->post('kode',TRUE),$this->input->post('kodeold',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function partner(){
        $filter = [];
        $data = $this->mmaster->partner($this->input->get('ijenis'));
        if ($data->num_rows()>0) {
            $group   = [];
            $arr     = [];
            foreach ($data->result() as $key) {
                $arr[] = $key->grouppartner;
            }
            $unique_data = array_unique($arr);
            foreach($unique_data as $val) {
                $child  = [];
                foreach ($data->result() as $row) {
                    if ($val==$row->grouppartner) {
                        $child[] = array(
                            'id'   => $row->id.'|'.$row->grouppartner, 
                            'text' => $row->e_name, 
                        );
                    }
                }
                $filter[] = array(
                    'id'        => 0,
                    'text'      => strtoupper($val),
                    'children'  => $child
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }   

    public function getdetail(){
        header("Content-Type: application/json", true);
        $ipartner   = $this->input->post('ipartner', FALSE);
        if($ipartner){
            $tmp   = explode('|', $ipartner);
            $idpartner   = $tmp[0];
            $epartnertype = $tmp[1];
        }
        $ijenis     = $this->input->post('ijenis', FALSE);
        $jtawal     = $this->input->post('jtawal', FALSE);
        $jtakhir    = $this->input->post('jtakhir', FALSE);

        $query  = array(
            'detail' => $this->mmaster->getdetail($idpartner, $epartnertype, $ijenis, $jtawal, $jtakhir)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function changestatus() {
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

    public function simpan() {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id_company     = $this->session->userdata('id_company');
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = date('Y-m-d', strtotime($this->input->post("ddocument", true)));
        $ijenis         = $this->input->post('ijenis', TRUE);
        $ipartner       = $this->input->post('ipartner', TRUE);
        if ($ipartner) {
            $tmp = explode('|', $ipartner);
            $idpartner      = $tmp[0];
            $epartnertype   = $tmp[1];
        }
        $sisa           = str_replace(',','',$this->input->post('sisa', TRUE));
        $jumlah         = str_replace(',','',$this->input->post('jumlah', TRUE));
        $remark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);
    
   
        if ($idocument != '') {
            $cekdata     = $this->mmaster->cek_kode($idocument,$ibagian);
            if ($cekdata->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id_company, $id, $ibagian, $idocument, $ddocument, $ijenis, $idpartner, $epartnertype, $jumlah, $sisa, $remark);

                for($i=1;$i<=$jml;$i++){
                    $idfaktur       = $this->input->post('idfaktur'.$i, TRUE);
                    $vtotal         = str_replace(',','',$this->input->post('vtotal'.$i, TRUE));
                    $vsisa          = str_replace(',','',$this->input->post('vsisa'.$i, TRUE));
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $check          = $this->input->post('chk'.$i, TRUE);
                    if($check=="cek"){
                        $this->mmaster->insertdetail($id, $idfaktur, $ijenis, $vtotal, $vsisa, $edesc, $id_company);  
                    }
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function edit() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->uri->segment(4);
        $dfrom          = $this->uri->segment(5);
        $dto            = $this->uri->segment(6);
        $ijenis         = $this->uri->segment(7);
        $epartnertype   = $this->uri->segment(8);
        $idcompany      = $this->session->userdata('id_company');  

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'jenis'      => $this->mmaster->jenis($ijenis)->result(),
            'number'     => "KNB-".date('ym')."-000001",
            'partner'    => $this->mmaster->partner($ijenis)->result(),
            'data'       => $this->mmaster->cek_data($id, $epartnertype, $idcompany)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ijenis, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id');
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $idocumentold   = $this->input->post('idocumentold', TRUE);
        $ddocument      = date('Y-m-d', strtotime($this->input->post("ddocument", true)));
        $ijenis         = $this->input->post('ijenis', TRUE);
        $ipartner       = $this->input->post('ipartner', TRUE);
        if ($ipartner) {
            $tmp = explode('|', $ipartner);
            $idpartner      = $tmp[0];
            $epartnertype   = $tmp[1];
        }
        $sisa           = str_replace(',','',$this->input->post('sisa', TRUE));
        $jumlah         = str_replace(',','',$this->input->post('jumlah', TRUE));
        $remark         = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);
        $id_company     = $this->session->userdata('id_company');
        
        // var_dump($id_company, $ibagian, $ippap, $dppap, $partner, $drppap, $jumlah,$remark);
        // die();
        if ($idocument != '') {
            $cekdata     = $this->mmaster->cek_kode($idocumentold, $ibagian);
            if ($cekdata->num_rows()==0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id_company, $id, $ibagian, $idocument, $ddocument, $ijenis, $idpartner, $epartnertype, $jumlah, $sisa, $remark);
                $this->mmaster->deletedetail($id);

               for($i=1;$i<=$jml;$i++){
                    $idfaktur       = $this->input->post('idfaktur'.$i, TRUE);
                    $vtotal         = str_replace(',','',$this->input->post('vtotal'.$i, TRUE));
                    $vsisa          = str_replace(',','',$this->input->post('vsisa'.$i, TRUE));
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $check          = $this->input->post('chk'.$i, TRUE);
                    if($check=="cek"){
                        $this->mmaster->insertdetail($id, $idfaktur, $ijenis, $vtotal, $vsisa, $edesc, $id_company);  
                    }
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function view() {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->uri->segment(4);
        $dfrom          = $this->uri->segment(5);
        $dto            = $this->uri->segment(6);
        $ijenis         = $this->uri->segment(7);
        $epartnertype   = $this->uri->segment(8);
        $idcompany      = $this->session->userdata('id_company');  

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'jenis'      => $this->mmaster->jenis($ijenis)->result(),
            'partner'    => $this->mmaster->partner($ijenis)->result(),
            'data'       => $this->mmaster->cek_data($id, $epartnertype, $idcompany)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ijenis, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval() {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->uri->segment(4);
        $dfrom          = $this->uri->segment(5);
        $dto            = $this->uri->segment(6);
        $ijenis         = $this->uri->segment(7);
        $epartnertype   = $this->uri->segment(8);
        $idcompany      = $this->session->userdata('id_company');  

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagian()->result(),
            'jenis'      => $this->mmaster->jenis($ijenis)->result(),
            'partner'    => $this->mmaster->partner($ijenis)->result(),
            'data'       => $this->mmaster->cek_data($id, $epartnertype, $idcompany)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ijenis, $idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */