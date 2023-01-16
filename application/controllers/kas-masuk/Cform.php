<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    /*----------  Created By A  ----------*/
    
    public $global = array();
    public $i_menu = '2040302';

    public function __construct()
    {
        parent::__construct();

        /*----------  Cek Session Di Helper  ----------*/
        cek_session();

        /*----------  Cek Menu Di Helper  ----------*/
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        /*----------  Deklarasi Session, Folder dan Nama Menu  ----------*/        
        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->level            = $this->session->i_level;
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/        
        $this->load->model($this->global['folder'].'/mmaster');
    }

    /*----------  DEFAULT CONTROLLER  ----------*/
    
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
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    /*----------  LIST DATA  ----------*/
    
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
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }

    /*----------  REDIRECT KE FORM TAMBAH  ----------*/
    
    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "KMP-".date('ym')."-123456"            
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/
    
    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK NO DOKUMEN SUDAH ADA  ----------*/

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }
    
    /*----------  CARI KAS / BANK  ----------*/
    
    public function kasbank()
    {
        $filter = [];
        $data   = $this->mmaster->kasbank(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id.'|'.$row->i_bank,
                    'text'  => $row->e_kas_name,
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

    /*----------  CARI BANK  ----------*/
    
    public function bank()
    {
        $filter = [];
        if($this->input->get('ikasbank') != ''){
            $ikasbank   = explode('|', $this->input->get('ikasbank'));
            $idkas      = $ikasbank[0];
            $ibank      = $ikasbank[1];
            $data   = $this->mmaster->bank(str_replace("'", "", $this->input->get('q')), $ibank);
            if ($data->num_rows()>0) {
                foreach ($data->result() as $row) {
                    $filter[] = array(
                        'id'    => $row->id,
                        'text'  => $row->e_bank_name,
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Bank"
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Kas/Bank harus dipilih"
            );
        }
        echo json_encode($filter);
    }

    /*----------  CARI CUSTOMER/PELANGGAN  ----------*/    

    public function customer()
    {
        $filter = [];
        $data   = $this->mmaster->customer(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
                $group   = [];
                $arr     = [];
                foreach ($data->result() as $key) {
                    $arr[] = $key->group_partner;
                }
                $unique_data = array_unique($arr);
                foreach($unique_data as $val) {
                    $child  = [];
                    foreach ($data->result() as $row) {
                        if ($val==$row->group_partner) {
                            $child[] = array(
                                'id' => $row->id_partner.'|'.$row->group_partner.'|'.$row->jenis_faktur,
                                'text' =>  $row->i_partner.' - '.$row->e_partner.' ('.strtoupper($row->jenis_faktur).')',
                            );
                        }
                    }
                    $filter[] = array(
                        'id'        => 0,
                        'text'      => strtoupper($val),
                        'children'  => $child
                    );
                }
            }else {
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Customer Sisa Piutang"
                );
            }
        echo json_encode($filter);
    }

    /*----------  GET DETAIL CUSTOMER  ----------*/
    
    public function getcustomer()
    {
        header("Content-Type: application/json", true);
        
        $idpartner = '';
        foreach ($this->input->post('icustomer') as $key => $value) {
            $idcustomer     = explode('|', $value);
            $idpartner     .= "'".$idcustomer[0].$idcustomer[1].$idcustomer[2]."'".",";
        }
        $idpartner = substr($idpartner, 0, -1);
        $data = array(
                        'data' => $this->mmaster->getcustomer($idpartner)->result_array(),
        );
        echo json_encode($data);
    }

    /*----------  SIMPAN DATA  ----------*/
    
    public function simpan()
    {
        /*----------  Cek Hak Akses Input  ----------*/        
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $datedocument = date('Y-m-d', strtotime($ddocument));
        }

        $ikasbank       = $this->input->post('ikasbank', TRUE);
        if ($ikasbank) {
            $tmp        = explode('|', $ikasbank);
            $idkasbank  = $tmp[0];
            $ibank      = $tmp[1];
        }
        $ibank          = $this->input->post('ibank', TRUE);
        if($ibank == ''){
            $ibank = 0;
        }else{
            $ibank = $ibank;
        }
        $vnilai         = str_replace(',','',$this->input->post('vnilai',TRUE));
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        if($ibagian != ''  && $idocument != '' && $ddocument != '' && $ikasbank != ''){
            $cekkode = $this->mmaster->cek_kode($idocument, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id,$idocument,$datedocument,$ibagian,$idkasbank,$ibank,$vnilai,$eremark);
                for($i = 0; $i < $jml; $i++){
                    if($this->input->post('cek'.$i)=='on'){
                        $idpartner      = $this->input->post('idcustomer'.$i, TRUE);
                        $jenisfaktur    = $this->input->post('jenisfaktur'.$i, TRUE);
                        $grouppartner   = $this->input->post('grouppartner'.$i, TRUE);
                        $vvalue         = str_replace(',','',$this->input->post('v_nilai'.$i,TRUE));
                        $edesc          = $this->input->post('edesc'.$i, TRUE);
                        if ($vvalue>0) {
                            $this->mmaster->insertdetail($id,$idpartner,$jenisfaktur,$grouppartner,$vvalue,$edesc);
                        }
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                } else {
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id,
                    );
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  REDIRECT KE FORM EDIT  ----------*/
    
    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(5), 
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagianpembuat()->result(),
            'number'     => "KMP-".date('ym')."-123456",
            'customer'   => $this->mmaster->cek_customer($this->uri->segment(4)),
            'data'       => $this->mmaster->cek_data($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->cek_datadetail($this->uri->segment(4))->result()
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/
    
    public function update()
    {
        /*----------  Cek Hak Akses Update  ----------*/        
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $ibagianold     = $this->input->post('ibagianold', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $idocumentold   = $this->input->post('idocumentold', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $datedocument = date('Y-m-d', strtotime($ddocument));
        }

        $ikasbank       = $this->input->post('ikasbank', TRUE);
        if ($ikasbank) {
            $tmp        = explode('|', $ikasbank);
            $idkasbank  = $tmp[0];
            $ibank      = $tmp[1];
        }
        $ibank          = $this->input->post('ibank', TRUE);
        if($ibank == ''){
            $ibank = 0;
        }else{
            $ibank = $ibank;
        }
        $vnilai         = str_replace(',','',$this->input->post('vnilai',TRUE));
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        if($id != '' && $ibagian != '' && $idocument != '' && $ddocument != '' && $ikasbank != ''){
            $cekkode = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id,$idocument,$datedocument,$ibagian,$idkasbank,$ibank,$vnilai,$eremark);
                $this->mmaster->deletedetail($id);
                for($i = 0; $i < $jml; $i++){
                    if($this->input->post('cek'.$i)=='on'){
                        $idpartner      = $this->input->post('idcustomer'.$i, TRUE);
                        $jenisfaktur    = $this->input->post('jenisfaktur'.$i, TRUE);
                        $grouppartner   = $this->input->post('grouppartner'.$i, TRUE);
                        $vvalue         = str_replace(',','',$this->input->post('v_nilai'.$i,TRUE));
                        $edesc          = $this->input->post('edesc'.$i, TRUE);
                        if ($vvalue>0) {
                            $this->mmaster->insertdetail($id,$idpartner,$jenisfaktur,$grouppartner,$vvalue,$edesc);
                        }
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                } else {
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id,
                    );
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                }
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => $idocument,
                'id'     => null
            );
        }
        echo json_encode($data);
    }

    /*----------  REDIRECT KE FORM VIEW  ----------*/
    
    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(5), 
            'dto'        => $this->uri->segment(6),
            'customer'   => $this->mmaster->cek_customer($this->uri->segment(4)),
            'data'       => $this->mmaster->cek_data($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->cek_datadetail($this->uri->segment(4))->result()
        );

        $this->Logger->write('Membuka Menu Detail ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    /*----------  REDIRECT KE FORM APPROVE  ----------*/
    
    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $this->uri->segment(4), 
            'dfrom'      => $this->uri->segment(5), 
            'dto'        => $this->uri->segment(6),
            'customer'   => $this->mmaster->cek_customer($this->uri->segment(4)),
            'data'       => $this->mmaster->cek_data($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->cek_datadetail($this->uri->segment(4))->result()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/    

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
}
/* End of file Cform.php */