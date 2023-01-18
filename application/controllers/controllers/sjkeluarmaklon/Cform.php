<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050407';

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

    /*----------  DEFAULT CONTROLLERS  ----------*/    
    
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

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    /*----------  DAFTAR DATA MASUK INTERNAL  ----------*/    

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

    /*----------  MEMBUKA FORM TAMBAH DATA  ----------*/

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
            'type'          => $this->mmaster->type($this->i_menu),
            'bagian'        => $this->mmaster->bagian(),
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'number'        => "SJ-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    
    /*----------  RUNNING NO DOKUMEN  ----------*/

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK NO DOKUMEN  ----------*/    

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    /*----------  DATA PARTNER SESUAI TYPE MAKLOON  ----------*/    
    
    public function partner()
    {
        $filter = [];
        if ($this->input->get('idtype')!='') {
            $data = $this->mmaster->partner($this->input->get('idtype'),str_replace("'", "", $this->input->get('q')));
            if ($data->num_rows()>0) {
                foreach($data->result() as $key){
                    $filter[] = array(
                        'id'   => $key->id,  
                        'text' => $key->e_name
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data."
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tipe Makloon Tidak Boleh Kosong!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  DETAIL PARTNER  ----------*/    

    public function detailsupplier()
    {
        header("Content-Type: application/json", true);
        echo json_encode($this->mmaster->detailpartner($this->input->post('idsupplier',TRUE))->row());
    }

    /*----------  DATA REFERENSI SESUAI PARTNER  ----------*/
    
    public function referensi()
    {
        $filter = [];
        if ($this->input->get('idpartner')!='') {
            $data = $this->mmaster->datareferensi(str_replace("'", "", $this->input->get('q')),$this->input->get('idpartner'));
            if ($data->num_rows()>0) {
                foreach($data->result() as $key){
                    $filter[] = array(
                        'id'   => $key->id,  
                        'text' => $key->i_document
                    );
                }
            }else{
                $filter[] = array(
                    'id'   => null,  
                    'text' => "Tidak Ada Data."
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Partner Tidak Boleh Kosong!"
            );
        }
        echo json_encode($filter);
    }

    /*----------  DETAIL ITEM REFERENSI  ----------*/    

    public function detailreferensi()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'data'   => $this->mmaster->ref($this->input->post('id',TRUE))->row(),
            'detail' => $this->mmaster->detailreferensi($this->input->post('id',TRUE),$this->input->post('tgl',TRUE))->result_array(),
        );
        echo json_encode($query);  
    }

    /*----------  SIMPAN DATA  ----------*/
    
    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if($ddocument!=''){
            $ddocument= date('Y-m-d', strtotime($ddocument));
        }
        $destimate    = $this->input->post('destimate', TRUE);
        if($destimate!=''){
            $destimate= date('Y-m-d', strtotime($destimate));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $idtype       = $this->input->post('idtype', TRUE);
        $idpartner    = $this->input->post('idpartner', TRUE);
        $itypepajak   = $this->input->post('itypepajak', TRUE);
        $ndiskon      = $this->input->post('ndiskon', TRUE);
        $idreff       = $this->input->post('idreff', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($ibagian!='' && $idtype!='' && $idreff!=''  && $idpartner!='' && $jml > 0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->simpan($id,$idocument,$ddocument,$destimate,$ibagian,$idtype,$idpartner,$idreff,$eremark,$itypepajak,$ndiskon);
            for($i=0;$i<$jml;$i++){
                $idmaterial      = $this->input->post('idmaterial'.$i, TRUE);
                $nqty            = $this->input->post('nqty'.$i, TRUE);
                $idmateriallist  = $this->input->post('idmateriallist'.$i, TRUE);
                $nqtylist        = $this->input->post('nqtylist'.$i, TRUE);
                $eremark         = $this->input->post('eremark'.$i, TRUE);
                $vunitprice      = str_replace(",", "", $this->input->post('vunitprice'.$i, TRUE));
                $vunitpricelist  = str_replace(",", "", $this->input->post('vunitpricelist'.$i, TRUE));
                if (($idmaterial!='' || $idmaterial!=null) && $nqty > 0 && ($idmateriallist!='' || $idmateriallist!=null) && $nqtylist > 0) {
                    $this->mmaster->simpandetail($id,$idreff,$idmaterial,$nqty,$idmateriallist,$nqtylist,$eremark,$vunitprice,$vunitpricelist);
                }
            } 

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $idocument,
                    'sukses' => false,
                    'id'     => $id,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Id : '.$id.', Kode : '.$idocument);
            }
        }else{
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA FORM EDIT  ----------*/
    
    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'number'     => "SJ-".date('ym')."-123456",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'type'       => $this->mmaster->type($this->i_menu),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/
    
    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }        

        $id           = $this->input->post('id', TRUE);
        $idocument    = $this->input->post('idocument', TRUE);
        $ddocument    = $this->input->post('ddocument', TRUE);
        if($ddocument!=''){
            $ddocument= date('Y-m-d', strtotime($ddocument));
        }
        $destimate    = $this->input->post('destimate', TRUE);
        if($destimate!=''){
            $destimate= date('Y-m-d', strtotime($destimate));
        }
        $ibagian      = $this->input->post('ibagian', TRUE);
        $idtype       = $this->input->post('idtype', TRUE);
        $idpartner    = $this->input->post('idpartner', TRUE);
        $itypepajak   = $this->input->post('itypepajak', TRUE);
        $ndiskon      = $this->input->post('ndiskon', TRUE);
        $idreff       = $this->input->post('idreff', TRUE);
        $eremark      = $this->input->post('eremark', TRUE);
        $jml          = $this->input->post('jml', TRUE);
        if ($id!='' && $ibagian!='' && $idtype!='' && $idreff!=''  && $idpartner!='' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->update($id,$idocument,$ddocument,$destimate,$ibagian,$idtype,$idpartner,$idreff,$eremark,$itypepajak,$ndiskon);
            $this->mmaster->delete($id);
            for($i=0;$i<$jml;$i++){
                $idmaterial      = $this->input->post('idmaterial'.$i, TRUE);
                $nqty            = $this->input->post('nqty'.$i, TRUE);
                $idmateriallist  = $this->input->post('idmateriallist'.$i, TRUE);
                $nqtylist        = $this->input->post('nqtylist'.$i, TRUE);
                $eremark         = $this->input->post('eremark'.$i, TRUE);
                $vunitprice      = str_replace(",", "", $this->input->post('vunitprice'.$i, TRUE));
                $vunitpricelist  = str_replace(",", "", $this->input->post('vunitpricelist'.$i, TRUE));
                if (($idmaterial!='' || $idmaterial!=null) && $nqty > 0 && ($idmateriallist!='' || $idmateriallist!=null) && $nqtylist > 0) {
                    $this->mmaster->simpandetail($id,$idreff,$idmaterial,$nqty,$idmateriallist,$nqtylist,$eremark,$vunitprice,$vunitpricelist);
                }
            } 

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'kode'   => $idocument,
                    'sukses' => false,
                    'id'     => $id,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Id : '.$id.', Kode : '.$idocument);
            }
        }else{
            $data = array(
                'kode'      => $idocument,
                'sukses'    => false,
                'id'        => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA MENU FORM VIEW  ----------*/
    
    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    /*----------  MEMBUKA MENU FORM APPROVE  ----------*/
    
    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian(),
            'data'       => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/    

    public function changestatus()
    {

        $id      = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        if ($istatus=='6') {
            $this->mmaster->updatesisa($id);
            $this->mmaster->simpanjurnal($id,$this->global['title']);
        }
        $this->mmaster->changestatus($id,$istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode (true);
        }
    }
}

/* End of file Cform.php */