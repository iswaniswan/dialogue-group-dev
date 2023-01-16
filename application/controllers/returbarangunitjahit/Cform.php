<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090406';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
    /*----------  DEFAULT CONTROLLERS CALL  ----------*/    

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

        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    /*----------  DAFTAR SJ MAKLOON  ----------*/

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
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }

    /*----------  MASUK FORM TAMBAH DATA  ----------*/

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),
            'bagian'     => $this->mmaster->bagian()->result(),
            'number'     => "SJ-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    /*----------  CEK NO DOK  ----------*/

    public function cekkode()
    {        
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    /*----------  GET NO DOK  ----------*/    

    public function number()
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CARI SUPPLIER  ----------*/

    public function partner()
    {
        $filter = [];
        $data = $this->mmaster->partner(str_replace("'","",$this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'   => $row->id,
                    'text' => $row->e_name
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

    /*----------  DATA REFERENSI  ----------*/    

    public function referensi()
    {
        $filter = [];
        if ($this->input->get('ipartner') != '' || $this->input->get('ipartner') != null) {
            $data   = $this->mmaster->referensi(str_replace("'","",$this->input->get('q')),$this->input->get('ipartner'));
            if ($data->num_rows()>0) {
                foreach($data->result() as $key){
                    $filter[] = array(
                        'id'   => $key->id.'|'.$key->d_document,  
                        'text' => $key->i_document,
                    );
                }   
            }else{
                $filter[] = array(
                    'id'   => null,
                    'text' => "Tidak Ada Data"
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Partner Masih Kosong!"
            );
        }
        echo json_encode($filter);
    }

    public function getdetailreff()
    {
        header("Content-Type: application/json", true);
        $query  = array(
            'detail' => $this->mmaster->getdetailreff($this->input->post('id'))->result_array()
        );
        echo json_encode($query);  
    }

    /*----------  SIMPAN DATA  ----------*/

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian    = $this->input->post('ibagian', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        $ipartner   = $this->input->post('ipartner', TRUE);
        $idreff     = $this->input->post('ireff', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        if ($idocument!='' && $jml>0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->simpan($id,$idocument,$idreff,$ddocument,$ibagian,$ipartner,$eremarkh);
            for ($i = 0; $i < $jml; $i++) {
                $idproduct = $this->input->post('idproduct' . $i, TRUE);
                $nquantity = str_replace(",",".",$this->input->post('nquantity' . $i, TRUE));
                $eremark   = $this->input->post('eremark' . $i, TRUE);
                if ($nquantity > 0 && $nquantity != null) {
                    $this->mmaster->simpandetail($id,$idreff,$idproduct,$nquantity,$eremark);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'   => 'Dokumen Gagal Disimpan',
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => 'Dokumen Gagal Disimpan',
            );
        }

        $this->load->view('pesan2', $data);
    }

    /*----------  UPDATE STATUS DOKUMEN  ----------*/

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode (true);
        }
    } 

    /*----------  MEMBUKA FORM EDIT  ----------*/          

    public function edit() 
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJ-".date('ym')."-123456",
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
        $ibagian    = $this->input->post('ibagian', TRUE);
        $idocument  = $this->input->post('idocument', TRUE);
        $ddocument  = $this->input->post('ddocument', TRUE);
        $ipartner   = $this->input->post('ipartner', TRUE);
        $idreff     = $this->input->post('ireff', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);
        if ($ddocument != '') {
            $ddocument = date('Y-m-d', strtotime($ddocument));
        }
        if ($id != '' && $idocument != '' && $jml>0) {
            $this->db->trans_begin();
            $this->mmaster->update($id,$idocument,$idreff,$ddocument,$ibagian,$ipartner,$eremarkh);
            $this->mmaster->delete($id);
            for ($i = 0; $i < $jml; $i++) {
                $idproduct = $this->input->post('idproduct' . $i, TRUE);
                $nquantity = str_replace(",",".",$this->input->post('nquantity' . $i, TRUE));
                $eremark   = $this->input->post('eremark' . $i, TRUE);
                if ($nquantity > 0 && $nquantity != null) {
                    $this->mmaster->simpandetail($id,$idreff,$idproduct,$nquantity,$eremark);
                }
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                    'kode'   => 'Dokumen Gagal Diupdate',
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id
                );
                $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
            }
        }else{
            $data = array(
                'sukses' => false,
                'kode'   => 'Dokumen Gagal Diupdate',
            );
        }

        $this->load->view('pesan2', $data);
    }

    /*----------  MEMBUKA FORM APPROVE  ----------*/

    public function approval() 
    {
        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    /*----------  MEMBUKA FORM DETAIL  ----------*/    

    public function view() 
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Detail ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'id'            => $this->uri->segment(4),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'bagian'        => $this->mmaster->bagian()->result(),
            'data'          => $this->mmaster->dataedit($this->uri->segment(4))->row(),
            'datadetail'    => $this->mmaster->dataeditdetail($this->uri->segment(4))->result(),
            'number'        => "SJ-".date('ym')."-000001",
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
}

/* End of file Cform.php */
