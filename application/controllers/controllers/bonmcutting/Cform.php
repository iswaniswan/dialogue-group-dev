<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090103';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    
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
            'number'        => "BBM-".date('ym')."-123456",
            'gudang'        => $this->mmaster->gudang(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
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

    public function referensi()
    {
        $filter = [];
        if ($this->input->get('ibagian')!='') {
            $data = $this->mmaster->datareferensi(str_replace("'", "", $this->input->get('q')),$this->input->get('ibagian'),date('Ym', strtotime($this->input->get('ddocument'))));
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
                'text' => "Bagian tidak boleh kosong!"
            );
        }
        echo json_encode($filter);
    }

    public function referensidetail()
    {
        header("Content-Type: application/json", true);

        $query  = array(
            'head'   => $this->mmaster->dataheader($this->input->post('id',FALSE))->row(),
            'detail' => $this->mmaster->datadetail($this->input->post('id',FALSE))->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $idocument   = $this->input->post("idocument",TRUE);
        if ($this->input->post("ddocument",TRUE)!='') {
            $ddocument = date('Y-m-d', strtotime($this->input->post("ddocument",TRUE)));
        };

        $ibagian     = $this->input->post('ibagian', TRUE);
        $ipengirim   = $this->input->post('ipengirim', TRUE);
        $ireff       = $this->input->post('ireferensi', TRUE);
        $eremark     = $this->input->post('eremark', TRUE);
        $jml         = $this->input->post('jml', TRUE);
        if($idocument != '' && $ddocument != '' && $ibagian != '' && $ipengirim != '' && $ireff != '' && $jml >0) {
            $this->db->trans_begin();
            $id = $this->mmaster->runningid();
            $this->mmaster->insertheader($id,$idocument,$ddocument,$ibagian,$ireff,$eremark,$ipengirim);
            for($i=0; $i <$jml; $i++){
                $id_reff_item  = $this->input->post('id_reff_item'.$i, TRUE);
                $idproductwip  = $this->input->post('idproduct'.$i, TRUE);
                $idmaterial    = $this->input->post('idmaterial'.$i, TRUE);
                $nquantity     = $this->input->post('npemenuhan'.$i, TRUE);
                $eremarkitem   = $this->input->post('eremark'.$i, TRUE);
                if (($idmaterial != '' || $idmaterial != null) && ($idproductwip != '' || $idproductwip != null) && ($nquantity > 0) || $nquantity > '') {
                    $this->mmaster->insertdetail($id,$id_reff_item,$idproductwip,$idmaterial,$nquantity,$eremarkitem);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Id : '.$id.' Kode : '.$idocument);
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id,
                );
            }
        }else{
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
            );
        }        
        $this->load->view('pesan2', $data);
    }

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
            'number'     => "BBM-".date('ym')."-123456",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->data_detail($this->uri->segment(4))->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $id 		  = $this->input->post("id",TRUE);
        $idocumentold = $this->input->post("idocumentold",TRUE);
        $idocument 	  = $this->input->post("idocument",TRUE);
        if ($this->input->post("ddocument",TRUE)!='') {
            $ddocument = date('Y-m-d', strtotime($this->input->post("ddocument",TRUE)));
        };

        $ibagian 	 = $this->input->post('ibagian', TRUE);
        $ipengirim   = $this->input->post('ipengirim', TRUE);
        $ireff 		 = $this->input->post('ireferensi', TRUE);
        $eremark     = $this->input->post('eremark', TRUE);
        $jml         = $this->input->post('jml', TRUE);
        if($id != '' && $idocumentold != '' && $idocument != '' && $ddocument != '' && $ibagian != '' && $ipengirim != '' && $ireff != '' && $jml >0) {
            $this->db->trans_begin();
            $this->mmaster->updateheader($id,$idocument,$ddocument,$ibagian,$ireff,$eremark,$ipengirim);
            $this->mmaster->deletedetail($id);
            for($i=0; $i <$jml; $i++){
                $id_reff_item  = $this->input->post('id_reff_item'.$i, TRUE);
                $idproductwip  = $this->input->post('idproduct'.$i, TRUE);
                $idmaterial    = $this->input->post('idmaterial'.$i, TRUE);
                $nquantity     = $this->input->post('npemenuhan'.$i, TRUE);
                $eremarkitem   = $this->input->post('eremark'.$i, TRUE);
                if (($idmaterial != '' || $idmaterial != null) && ($idproductwip != '' || $idproductwip != null) && ($nquantity > 0) || $nquantity > '') {
                    $this->mmaster->insertdetail($id,$id_reff_item,$idproductwip,$idmaterial,$nquantity,$eremarkitem);
                }
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Id : '.$id.' Kode : '.$idocument);
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'   	 => $id,
                );
            }
        }else{
            $this->db->trans_rollback();
            $data = array(
                'sukses'    => false,
            );
        }        
        $this->load->view('pesan2', $data);
    }


    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data   = array(
            'folder'     => $this->global['folder'],
            'title'      => "Detail ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->data_detail($this->uri->segment(4))->result(),
        );
        $this->Logger->write('Membuka Menu Detail '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


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
            'number'     => "BBM-".date('ym')."-123456",
            'id'         => $this->uri->segment(4),
            'dfrom'      => $this->uri->segment(5),
            'dto'        => $this->uri->segment(6),
            'gudang'     => $this->mmaster->gudang(),
            'data'       => $this->mmaster->data_header($this->uri->segment(4))->row(),
            'detail'     => $this->mmaster->data_detail($this->uri->segment(4))->result(),
        );
        $this->Logger->write('Membuka Menu Approve '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

}
/* End of file Cform.php */