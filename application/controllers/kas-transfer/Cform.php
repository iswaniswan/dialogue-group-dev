<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040315';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'].'/mmaster');
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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
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
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "TF-".date('ym')."-123456"            
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function kasbank(){
        $filter = [];
        $data   = $this->mmaster->kasbank(strtoupper($this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id,
                    'text'  => $row->e_kas_name.' ('.$row->e_coa_name.')',
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

    public function kasbanktujuan(){
        $ikasbankaw = $this->input->post('ikasbankaw', TRUE);
        $query = $this->mmaster->kasbanktujuan($ikasbankaw);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->id." >".$row->e_kas_name.' ('.$row->e_coa_name.')'."</option>";
            }
            $kop  = $c;
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }


    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $ikasbankaw      = $this->input->post('ikasbankaw', TRUE);
        $ikasbankak      = $this->input->post('ikasbankak', TRUE);
        $vnilai         = str_replace(',','',$this->input->post('vnilai',TRUE));
        $eremark      = $this->input->post('eremark', TRUE);

        if($ibagian != ''  && $idocument != ''){
            $cekkode = $this->mmaster->cek_kode($idocument, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                    'kode' => "",
                );
            }else{
                $this->db->trans_begin();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $idocument, $datedocument, $ibagian, $ikasbankaw, $ikasbankak, $vnilai, $eremark);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode' => "",
                    );
                } else {
                    $this->db->trans_commit();
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

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagianpembuat()->result(),
            'number'     => "TF-".date('ym')."-123456",          
            'head'       => $this->mmaster->cek_data($id)->row(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

     public function getcustomeredit(){
        $filter = [];

        $data   = $this->mmaster->customer(strtoupper($this->input->get('q')));
        if ($data->num_rows()>0) {
            $filter[] = array(
                    'id'    => 'ALL',
                    'text'  => "Semua Customer",
            );
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id,
                    'text'  => $row->e_customer_name,
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

    function getitemcustomer_edit(){
        header("Content-Type: application/json", true);
        $icustomer      = $this->input->post('icustomer');
        $id            = $this->input->post('id');
        
        $data = $this->mmaster->getitemcustomer_edit($icustomer, $id);


        $dataa = array(
            'data'       => $data->result_array(),
            'dataitem'   => $this->mmaster->getitemcustomer_edit($icustomer, $id)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $ikasbankaw      = $this->input->post('ikasbankaw', TRUE);
        $ikasbankak      = $this->input->post('ikasbankak', TRUE);
        $vnilai         = str_replace(',','',$this->input->post('vnilai',TRUE));
        $eremark      = $this->input->post('eremark', TRUE);


        if($ibagian != ''  && $idocument != '' ){
            $this->db->trans_begin();
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
            $this->mmaster->updateheader($id, $idocument, $datedocument, $ibagian, $ikasbankaw, $ikasbankak, $vnilai, $eremark);

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
            
        }else{
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

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

       
        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'id'        => $id,
            'bagian'     => $this->mmaster->bagianpembuat()->result(),
            'number'     => "TF-".date('ym')."-123456",          
            'head'       => $this->mmaster->cek_data($id)->row(),
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

        
        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'id'        => $id,
            'bagian'     => $this->mmaster->bagianpembuat()->result(),
            'number'     => "TF-".date('ym')."-123456",          
            'head'       => $this->mmaster->cek_data($id)->row(),
        );


        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */