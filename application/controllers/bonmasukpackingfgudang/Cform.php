<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090609';
    public $i_menu1 = '2090501';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 1);
        if(!$data){
            $data = check_role($this->i_menu, 7);
            if(!$data){
                redirect(base_url(),'refresh');
            }   
        }

        $this->i_level = $this->session->userdata('i_level');
        $this->i_departement = $this->session->userdata('i_departement');
        $this->username = $this->session->userdata('username');
        $this->id_company = $this->session->userdata('id_company');
        $this->idcompany = $this->session->userdata('id_company');

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['folder1'] = 'bonmasukqc';
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
            'folder1'   => $this->global['folder1'],
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
        echo $this->mmaster->data($this->i_menu, $this->i_menu1, $this->global['folder'], $this->global['folder1'], $dfrom, $dto);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'folder1'       => $this->global['folder1'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "BBM-".date('ym')."-1234"            
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function bagianpengirim(){
        $q = $this->input->get('q');
        $i_menu = $this->i_menu;
        $data   = $this->mmaster->bagianpengirim($q, $i_menu);

        $filters = [];
        foreach ($data->result() as $row) {

            $group = [
                'id' => $row->id,
                'text' => $row->e_bagian_name,
                'name' => trim($row->name)
            ];

            $filters[] = $group;
        }

        // var_dump($filters); die();
        echo json_encode($filters);
    }

    public function referensi(){
        $filter = [];
        $q = $this->input->get('q');
        $ibagian = $this->input->get('ibagian');
        $ipengirim = $this->input->get('ipengirim');

        $data   = $this->mmaster->referensi(strtoupper($q), $ibagian, $ipengirim);
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'    => $row->id, 
                'text'  => $row->i_document,
            );
        }
        echo json_encode($filter);
    }

    public function getdataitem(){
        header("Content-Type: application/json", true);
        $idreff    = $this->input->post('idreff');

        $data_head = $this->mmaster->get_data_header($idreff)->row();
        $query_items = $this->mmaster->get_data_item($idreff);
        $data_items = $query_items->result_array();
        $total = $query_items->num_rows();

        $result = [
            'datahead' => $data_head,
            'dataitem' => $data_items,
            'jmlitem' => $total,
        ];

        echo json_encode($result);
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
        $ibagian = $this->input->post('ibagian', TRUE);
        $number = $this->mmaster->generate_nomor_dokumen($ibagian);
        
        echo json_encode($number);
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
            $yearmonth = $year . $month;
            $datedocument = $year . '-' . $month . '-' . $day;
        }

        $ipengirim      = $this->input->post('ipengirim', TRUE);
        $ireff          = $this->input->post('ireff', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $i_material        = $this->input->post('idmaterial[]', TRUE);    
        $n_quantity        = str_replace(',','',$this->input->post('nquantitymasuk[]', TRUE));
        $e_desc            = $this->input->post('edesc[]', TRUE); 

        if($ibagian != ''  && $idocument != '' && $ipengirim != '' && $ireff != ''){
            $cekkode = $this->mmaster->cek_kode($idocument, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $idocument, $datedocument, $ibagian, $ipengirim, $ireff, $eremark);
                $no = 0;
                foreach ($i_material as $imaterial) {
                    $imaterial     = $imaterial;
                    $nquantity     = $n_quantity[$no];
                    $edesc         = $e_desc[$no];    
                    
                    $this->mmaster->insertdetail($id, $imaterial, $nquantity, $edesc);
                    $no++;
                }
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
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'folder1'    => $this->global['folder1'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result()
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

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year . $month;
            $datedocument = $year . '-' . $month . '-' . $day;
        }

        $ipengirim      = $this->input->post('ipengirim', TRUE);
        $ireff          = $this->input->post('ireff', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $i_material        = $this->input->post('idmaterial[]', TRUE);    
        $n_quantity        = str_replace(',','',$this->input->post('nquantitymasuk[]', TRUE));
        $e_desc            = $this->input->post('edesc[]', TRUE); 

        if($ibagian != ''  && $idocument != '' ){
            $this->db->trans_begin();
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
            $this->mmaster->updateheader($id, $idocument, $datedocument, $ibagian, $ipengirim, $ireff, $eremark);
            $this->mmaster->deletedetail($id);

            $no = 0;
            foreach ($i_material as $imaterial) {
                $imaterial     = $imaterial;
                $nquantity     = $n_quantity[$no];
                $edesc         = $e_desc[$no];    
                
                $this->mmaster->insertdetail($id, $imaterial, $nquantity, $edesc, $ireff);
                $no++;
            }

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

    public function view()
    {
        /* $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        } */

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);
        $dfrom      = $this->uri->segment(6);
        $dto        = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'folder1'    => $this->global['folder1'],
            'title'      => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()
        );
        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
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

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $ibagian    = $this->uri->segment(5);

        $data = array(
            'folder'     => $this->global['folder'],
            'folder1'    => $this->global['folder1'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $this->uri->segment(6),
            'dto'        => $this->uri->segment(7),
            'data'       => $this->mmaster->cek_data($id, $ibagian)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id, $ibagian)->result()

        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */