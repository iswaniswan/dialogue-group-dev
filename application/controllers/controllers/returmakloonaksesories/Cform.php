<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050318';

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
            'number'        => "SJ-".date('ym')."-123456",
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
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

    public function getsupplier()
    {
        $filter = [];
        $data   = $this->mmaster->getsupplier($this->uri->segment(4));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id.'|'.$row->i_supplier,
                'text'  => $row->e_supplier_name,
            );
        }
        echo json_encode($filter);
    }

    public function getreferensi(){
        $supplier  = explode('|',$this->input->get('isupplier'));
        $idsupplier = $supplier[0];
        $isupplier  = $supplier[1];
        $ibagian   = $this->input->get('ibagian');
        $data     = $this->mmaster->getreferensi($isupplier,$ibagian);
        // if($query->num_rows()>0) {
        //     $c   = "";
        //     $spb = $query->result();
        //     foreach($spb as $row) {
        //         $c.="<option value=".$row->id." >".$row->i_document."</option>";
        //     }
        //     $kop  = "<option value=\"\">Pilih No Referensi".$c."</option>";
        //     echo json_encode(array(
        //         'kop'   => $kop
        //     ));
        // }else{
        //     $kop  = "<option value=\"\">Data Kosong</option>";
        //     echo json_encode(array(
        //         'kop'    => $kop,
        //         'kosong' => 'kopong'
        //     ));
        // }

        if ($data->num_rows()>0) {
            $groupreff   = [];
            $arr     = [];
            foreach ($data->result() as $key) {
                $arr[] = $key->group;
            }
            $unique_data = array_unique($arr);
            foreach($unique_data as $val) {
                $child  = [];
                foreach ($data->result() as $row) {
                    if ($val==$row->group) {
                        $child[] = array(
                            'id' => $row->id, 
                            'text' => $row->i_document, 
                        );
                    }
                }
                $filter[] = array(
                    'id' => 0,
                    'text' => strtoupper($val),
                    'children' => $child
                );
            }
        } else {
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function getreferensidetail(){
        header("Content-Type: application/json", true);
        $id         = $this->input->post('id');
        $ibagian    = $this->input->post('ibagian');
        $query  = array(
            'head'   => $this->mmaster->getheader($ibagian, $id)->row(),
            'detail' => $this->mmaster->getitem($ibagian, $id)->result()
        );
        echo json_encode($query);  
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian');
        $idocument  = $this->input->post('iretur');
        $ddocument  = date('Y-m-d', strtotime($this->input->post('dretur')));
        $supplier   = explode('|', $this->input->post('isupplier'));
        $idsupplier = $supplier[0];
        $isupplier  = $supplier[1];
        $idreferensi= $this->input->post('ifaktur');
        $eremark    = $this->input->post('eremark');
        $jml            = $this->input->post('jml', TRUE);
     
        if ($idocument!='' && $ddocument!='' && $ibagian!='' && $idsupplier!='' && $idreferensi!='') {
            $cekkode = $this->mmaster->cekkode($idocument, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $ibagian, $idocument, $ddocument, $idsupplier, $idreferensi, $eremark);
                $x = 0;
                for($i=1;$i<=$jml;$i++){
                    $idreff         = $this->input->post('idreffsj'.$i, TRUE);
                    $idmaterial     = $this->input->post('idmaterial'.$i, TRUE);
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $nretur         = $this->input->post('nretur'.$i, TRUE);
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    if($nretur != 0 || $nretur != null){
                       $this->mmaster->insertdetail($id, $idmaterial, $nquantity, $nretur, $edesc, $idreff);
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
            );
        }
        $this->load->view('pesan2', $data);    
    }

    public function view() {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $idsupplier = $this->uri->segment(5);
        $ibagian    = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->bacaheader($id, $idsupplier, $ibagian)->row(),
            'detail'     => $this->mmaster->bacadetail($id, $idsupplier, $ibagian)->result()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
    
    public function edit() {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $idsupplier = $this->uri->segment(5);
        $ibagian    = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        $isupplier  = $this->uri->segment(9);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian()->result(),
            'supplier'   => $this->mmaster->getsupplier($ibagian),
            'referensi'  => $this->mmaster->getreferensi($isupplier, $ibagian),
            'data'       => $this->mmaster->bacaheader($id, $idsupplier, $ibagian)->row(),
            'detail'     => $this->mmaster->bacadetail($id, $idsupplier, $ibagian)->result()
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function getdetailedit(){
        header("Content-Type: application/json", true);
        $id         = $this->input->post('id', FALSE);
        $supplier   = explode('|',$this->input->post('isupplier', FALSE));
        $idsupplier = $supplier[0];
        $isupplier  = $supplier[1];
        $ibagian    = $this->input->post('ibagian', FALSE);
        $query  = array(
            'head'   => $this->mmaster->bacaheader($id, $idsupplier, $ibagian)->row(),
            'detail' => $this->mmaster->bacadetail($id, $idsupplier, $ibagian)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function update(){

        
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibagian    = $this->input->post('ibagian');
        $idocument  = $this->input->post('iretur');
        $ikodeold   = $this->input->post('idocumentold');
        $id         = $this->input->post('id');
        $ddocument  = date('Y-m-d', strtotime($this->input->post('dretur')));
        $supplier   = explode('|', $this->input->post('isupplier'));
        $idsupplier = $supplier[0];
        $isupplier  = $supplier[1];
        $idreferensi= $this->input->post('ifaktur');
        $eremark    = $this->input->post('eremark');
        $jml            = $this->input->post('jml', TRUE);
     
        if ($idocument!='' && $ddocument!='' && $ibagian!='' && $idsupplier!='' && $idreferensi!='') {
            $cekkode = $this->mmaster->cek_kodeedit($idocument,$ikodeold, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->updateheader($id, $ibagian, $idocument, $ddocument, $idsupplier, $idreferensi, $eremark);
                $this->mmaster->deletedetail($id);
                $x = 0;
                for($i=1;$i<=$jml;$i++){
                    $idreff         = $this->input->post('idreffsj'.$i, TRUE);
                    $idmaterial     = $this->input->post('idmaterial'.$i, TRUE);
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $nretur         = $this->input->post('nretur'.$i, TRUE);
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    if($nretur != 0 || $nretur != null){
                       $this->mmaster->insertdetail($id, $idmaterial, $nquantity, $nretur, $edesc, $idreff);
                    }
                }
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Ubah Data ' . $this->global['title'] . ' Kode : ' . $idocument);
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

    public function changestatus(){

        $id           = $this->input->post('id', true);
        $istatus      = $this->input->post('istatus', true);
        $estatus      = $this->mmaster->estatus($istatus);
        $groupmakloon = '';
        $ibagian      = '';

        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus, $groupmakloon, $ibagian);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function approve(){
        
        $id           = $this->input->post('id', TRUE);
        $idocument    = $this->input->post('iretur', TRUE);
        $istatus      = '6';
        $groupmakloon = $this->input->post('groupmakloon', TRUE);
        $ibagian      = $this->input->post('ibagian', TRUE);

        $this->mmaster->estatus($istatus);
        $this->mmaster->changestatus($id, $istatus, $groupmakloon, $ibagian);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array (
                'sukses' => FALSE,
            );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Approved Data ' . $this->global['title'] . ' Kode : ' . $idocument);
            $data = array (
                'sukses' => TRUE,
                'kode'   => $idocument,
                'id'     => $id,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $idsupplier = $this->uri->segment(5);
        $ibagian    = $this->uri->segment(6);
        $dfrom      = $this->uri->segment(7);
        $dto        = $this->uri->segment(8);
        // var_dump($id, $idsupplier, $ibagian, $dfrom, $dto);
        // die();
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approved " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'id'         => $id,
            'idsupplier' => $idsupplier,
            'ibagian'    => $ibagian,
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagian()->result(),
            'data'       => $this->mmaster->bacaheader($id, $idsupplier, $ibagian)->row(),
            'detail'     => $this->mmaster->bacadetail($id, $idsupplier, $ibagian)->result()
        );

        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }
}
/* End of file Cform.php */
