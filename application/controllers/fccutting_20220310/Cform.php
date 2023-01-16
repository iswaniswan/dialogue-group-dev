<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    /*----------  Created By A  ----------*/

    public $global = array();
    public $i_menu = '2090100';

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
        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];

        /*----------  Load Model  ----------*/        
        $this->load->model($this->global['folder'].'/mmaster');

        /*----------  Load Librabry  ----------*/        
        $this->load->library('fungsi');
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

    /*----------  DAFTAR DATA  ----------*/
    
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
    
    /*----------  REDIRECT LIST REFERENSI  ----------*/
    
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

        $this->load->view($this->global['folder'].'/vformlistreferensi', $data);
    }

    /*----------  DAFTAR DATA REFERENSI  ----------*/
    
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

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'tahun'      => $this->uri->segment(7),
            'bulan'      => $this->uri->segment(8),
            'bagian'     => $this->mmaster->bagian()->result(),
            'datadetail' => $this->mmaster->datadetail($this->uri->segment(4))->result(),
            // 'datadetaill'=> $this->mmaster->datadetaill($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->datadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "FC-".date('ym')."-123456",
        );
        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
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
        $idreferensi        = $this->input->post('idforecast', TRUE);
        $iperiode           = $this->input->post('iperiode', TRUE);

        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     !="") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);

        if ($idocument!='' && $ddocument!='' && $ibagian!='' && $idreferensi!='' && $jml > 0) {
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
                /** Simpan Data Header */
                $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$idreferensi,$iperiode,$eremark);

                /** Simpan Data Detail Yang Barang Jadi */ 
                for ($i = 1; $i <= $jml; $i++) {
                    $id_product_base    = $this->input->post('id_product_base'.$i, TRUE);
                    $nilai_base         = str_replace(",","",$this->input->post('nilai_base'.$i, TRUE));
                    $id_material        = $this->input->post('id_material'.$i, TRUE);
                    $v_gelar    = str_replace(",","",$this->input->post('v_gelar'.$i, TRUE));
                    $v_set    = str_replace(",","",$this->input->post('v_set'.$i, TRUE));
                    $p_kain    = str_replace(",","",$this->input->post('p_kain'.$i, TRUE));
                    $e_remark        = $this->input->post('e_remark'.$i, TRUE);
                    if ($nilai_base > 0 && ($id_product_base!=null || $id_product_base!='')) {
                        $this->mmaster->insertdetailbase($id,$id_product_base,$nilai_base,$id_material,$v_gelar,$v_set,$p_kain,$e_remark);
                    }
                }
                
                // /** Simpan Data Detail Yang Material */
                // for ($i = 1; $i <= $jml_item; $i++) {
                //     $id_material_item       = $this->input->post('id_material_item'.$i, TRUE);
                //     $i_satuan_konversi      = $this->input->post('i_satuan_konversi'.$i, TRUE);
                //     $nilai_kebutuhan_item   = str_replace(",","",$this->input->post('nilai_kebutuhan_item'.$i, TRUE));
                //     $nilai_mutasi           = str_replace(",","",$this->input->post('nilai_mutasi'.$i, TRUE));
                //     $nilai_budgeting        = str_replace(",","",$this->input->post('nilai_budgeting'.$i, TRUE));
                //     $nilai_estimasi         = str_replace(",","",$this->input->post('nilai_estimasi'.$i, TRUE));
                //     $nilai_op_sisa          = str_replace(",","",$this->input->post('nilai_op_sisa'.$i, TRUE));
                //     $ket                    = $this->input->post('ket'.$i, TRUE);
                //     $up                     = str_replace(",","",$this->input->post('up'.$i, TRUE));
                //     if ($nilai_budgeting > 0 && ($id_material_item!=null || $id_material_item!='')) {
                //         $this->mmaster->insertdetailmaterial($id,$idreferensi,$id_material_item,$nilai_kebutuhan_item,$nilai_mutasi,$nilai_budgeting,$i_satuan_konversi,$up,$ket,$nilai_estimasi,$nilai_op_sisa);
                //     }
                // }

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
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "FC-".date('ym')."-123456",
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
        $ibagianold         = $this->input->post('ibagianold', TRUE);
       
        $idocument          = $this->input->post('idocument', TRUE);
        $idreferensi        = $this->input->post('idforecast', TRUE);
        $iperiode           = $this->input->post('iperiode', TRUE);

        $ddocument          = $this->input->post('ddocument', TRUE);
        if ($ddocument     !="") {
            $ddocument      = date('Y-m-d', strtotime($ddocument));
        }
        $ibagian            = $this->input->post('ibagian', TRUE);
        $eremark            = $this->input->post('eremark', TRUE);
        $jml                = $this->input->post('jml', TRUE);


        if ($id!='' && $idocument!='' && $ddocument!='' && $ibagian!='' && $idreferensi!='' && $jml > 0) {
            $cekkode = $this->mmaster->cek_kode_edit($idocument,$ibagian,$idocumentold,$ibagianold);
            if ($cekkode->num_rows()>0) {
                $data = array(
                    'sukses' => 'ada',
                    'kode'   => $idocument,
                    'id'     => null
                );
            }else{
                $this->db->trans_begin();
                /** Update Header */
                $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$idreferensi,$iperiode,$eremark);

                /** Delete Item Sebelum Insert */
                $this->mmaster->delete($id);

                /** Simpan Data Detail Yang Barang Jadi */ 
                for ($i = 1; $i <= $jml; $i++) {
                    $id_product_base    = $this->input->post('id_product_base'.$i, TRUE);
                    $nilai_base         = str_replace(",","",$this->input->post('nilai_base'.$i, TRUE));
                    $id_material        = $this->input->post('id_material'.$i, TRUE);
                    $v_gelar    = str_replace(",","",$this->input->post('v_gelar'.$i, TRUE));
                    $v_set    = str_replace(",","",$this->input->post('v_set'.$i, TRUE));
                    $p_kain    = str_replace(",","",$this->input->post('p_kain'.$i, TRUE));
                    $e_remark        = $this->input->post('e_remark'.$i, TRUE);
                    if ($nilai_base > 0 && ($id_product_base!=null || $id_product_base!='')) {
                        $this->mmaster->insertdetailbase($id,$id_product_base,$nilai_base,$id_material,$v_gelar,$v_set,$p_kain,$e_remark);
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
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "FC-".date('ym')."-123456",
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
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->editheader($this->uri->segment(4))->row(),
            'datadetail' => $this->mmaster->edititembase($this->uri->segment(4))->result(),
            // 'datadetaill'=> $this->mmaster->edititemmaterial($this->uri->segment(4))->result(),
            // 'bisbisan'   => $this->mmaster->editdatadetailbisbisan($this->uri->segment(4))->result(),
            'number'     => "FC-".date('ym')."-123456",
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