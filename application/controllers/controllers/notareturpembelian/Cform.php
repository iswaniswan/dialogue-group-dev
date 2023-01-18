<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    /*----------  Created By A  ----------*/

    public $global = array();
    public $i_menu = '20208';

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

        /*----------  Deklarasi Session, Folder dan Nama / Judul Menu  ----------*/        
        $this->company          = $this->session->id_company;
        $this->departement      = $this->session->i_departement;
        $this->username         = $this->session->username;
        $this->level            = $this->session->i_level;
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/        
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

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    /*----------  DAFTAR DATA SPB  ----------*/
    
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

        echo $this->mmaster->data($this->global['folder'],$this->i_menu,$dfrom,$dto);
    }
    
    /*----------  REDIRECT LIST KN  ----------*/
    
    public function indexx()
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

        $this->load->view($this->global['folder'].'/vformlistsj', $data);
    }

    /*----------  DAFTAR DATA SPB  ----------*/
    
    public function datareferensi()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }

        echo $this->mmaster->datareferensi($this->global['folder'],$this->i_menu,$dfrom,$dto);
    }

    /*----------  PROSES DATA  ----------*/

    public function prosesdata() 
    {
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom    = $this->input->post('dfrom', true);
        $dto      = $this->input->post('dto', true);

        $supplier   = [];
        $idr        = [];
        $jenis      = [];
        $referensi  = [];
        if ($this->input->post('jml', true) > 0) {
            for ($i=1; $i <= $this->input->post('jml', true); $i++) { 
                $check      = $this->input->post('chk'.$i, true);
                $id         = $this->input->post('id'.$i, true);
                $idjenis    = $this->input->post('idjenis'.$i, true);
                $isupplier  = $this->input->post('isupplier'.$i, true);
                $ireferensi = $this->input->post('ireferensi'.$i, true);
                if ($check=='on') {
                    array_push($supplier,$isupplier);
                    array_push($idr,$id);
                    array_push($jenis,$idjenis);
                    array_push($referensi,$ireferensi);
                }
            }
        }
        $supplier   = array_unique($supplier);
        $idr        = array_unique($idr);
        $jenis      = array_unique($jenis);
        $referensi  = array_unique($referensi);
        $isupplier  = implode(",", $supplier);
        $idr        = "'".implode("', '", $idr)."'";
        $jenis      = "'".implode("', '", $jenis)."'";
        $referensi  = "'".implode("', '", $referensi)."'";

        if (count($supplier) == 1) {
            $data = array(
                'folder'     => $this->global['folder'],
                'title'      => "Tambah ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'data'       => $this->mmaster->dataheader($isupplier)->row(), 
                'isupplier'  => $isupplier,
                'datadetail' => $this->mmaster->datadetail($isupplier,$idr,$jenis,$referensi)->result(),
                'dfrom'      => $dfrom,
                'dto'        => $dto,
                'bagian'     => $this->mmaster->bagian()->result(),
                'number'     => "NRP-".date('ym')."-123456",
            );
            $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
            $this->load->view($this->global['folder'].'/vformadd', $data);
        } else {
            echo '<script>
            swal({
                title: "Maaf :(",
                text: "Supplier Tidak Boleh Beda!",
                showConfirmButton: true,
                type: "error",
                },function(){
                    show("'.$this->global['folder'].'/cform/indexx/'.$dfrom.'/'.$dto.'","#main");
                    });
            </script>';
        }
    }
    
    /*----------  RUNNING NUMBER DOKUMEN  ----------*/

    public function number() 
    {
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    /*----------  CEK KODE SUDAH ADA / BELUM  ----------*/

    public function cekkode() 
    {
        if ($this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE))->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    /*----------  SIMPAN DATA  ----------*/    

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idocument          = $this->input->post('idocument', TRUE);
        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     !="") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $idsupplier         = $this->input->post('idsupplier', TRUE);
        $esupplier          = $this->input->post('esupplier', TRUE);
        $eremarkh           = $this->input->post('eremarkh', TRUE);
        $jml                = $this->input->post('jml', TRUE);
        if ($idocument!='' && $ddocument!='' && $ibagian!='' && $idsupplier!='' && $jml>0) {
            $cekkode = $this->mmaster->cek_kode($idocument,$ibagian);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$idsupplier,$esupplier,$eremarkh);
                for ($i = 1; $i <= $jml; $i++) {
                    $idreferensi    = $this->input->post('id_referensi'.$i, TRUE);
                    $idreturgudang  = $this->input->post('id_retur_gudang'.$i, TRUE);
                    $idmaterial     = $this->input->post('id_material'.$i, TRUE);
                    $nqty           = str_replace(",","",$this->input->post('n_qty'.$i, TRUE));
                    $nqtyretur      = str_replace(",","",$this->input->post('n_qty_retur'.$i, TRUE));
                    $vprice         = str_replace(",","",$this->input->post('v_price'.$i, TRUE));
                    $eremark        = str_replace("'","",$this->input->post('eremark'.$i, TRUE));
                    if ($nqtyretur > 0 && ($idreferensi!=null || $idreferensi!='')) {
                        $this->mmaster->insertdetail($id,$idreferensi,$idmaterial,$idreturgudang,$nqty,$nqtyretur,$vprice,$eremark);
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
                        'id'     => $id
                    );
                    $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Id : ' . $id);
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

    /*----------  MEMBUKA MENU EDIT  ----------*/
    
    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4))->result(),
            'number'     => "NRP-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    /*----------  UPDATE DATA  ----------*/
    
    public function update() 
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id                 = $this->input->post('id', TRUE);
        $idocumentold       = $this->input->post('idocumentold', TRUE);
        $idocument          = $this->input->post('idocument', TRUE);
        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument      != '') {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $ibagianold         = $this->input->post('ibagianold', TRUE);
        $idsupplier         = $this->input->post('idsupplier', TRUE);
        $esupplier          = $this->input->post('esupplier', TRUE);
        $eremarkh           = $this->input->post('eremarkh', TRUE);
        $jml                = $this->input->post('jml', TRUE);
        if ($id!='' && $idocument!='' && $ddocument!='' && $ibagian!='' && $idsupplier!='' && $jml>0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$idsupplier,$esupplier,$eremarkh);
                $this->mmaster->delete($id);
                for ($i = 1; $i <= $jml; $i++) {
                    $idreferensi    = $this->input->post('id_referensi'.$i, TRUE);
                    $idreturgudang  = $this->input->post('id_retur_gudang'.$i, TRUE);
                    $idmaterial     = $this->input->post('id_material'.$i, TRUE);
                    $nqty           = str_replace(",","",$this->input->post('n_qty'.$i, TRUE));
                    $nqtyretur      = str_replace(",","",$this->input->post('n_qty_retur'.$i, TRUE));
                    $vprice         = str_replace(",","",$this->input->post('v_price'.$i, TRUE));
                    $eremark        = str_replace("'","",$this->input->post('eremark'.$i, TRUE));
                    if ($nqtyretur > 0 && ($idreferensi!=null || $idreferensi!='')) {
                        $this->mmaster->insertdetail($id,$idreferensi,$idmaterial,$idreturgudang,$nqty,$nqtyretur,$vprice,$eremark);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                }else{
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id
                    );
                    $this->Logger->write('Update Data ' . $this->global['title'] . ' Id : ' . $id);
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

    /*----------  MEMBUKA MENU APPROVE  ----------*/
    
    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4))->result(),
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    /*----------  MEMBUKA FORM DETAIL  ----------*/
    
    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititem($this->uri->segment(4))->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
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