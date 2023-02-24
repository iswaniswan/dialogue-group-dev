<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    // public $i_menu = '210150501';
    public $i_menu = '2050125';

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
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }   

    public function index()
    {
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => $this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function customer(){
        $filter = [];
        /* $filter[] = array(
            'id'    => 'null',
            'text'  => 'SEMUA UNIT',
        ); */
        $search = str_replace("'", "", $this->input->get('search'));
        $data = $this->mmaster->customer($search, $this->session->userdata('id_company'));
        foreach ($data->result() as $icustomer) {
            $filter[] = array(
                'id'    => $icustomer->id,
                'text'  => $icustomer->e_customer_name,
            );
        }
        echo json_encode($filter);
    }

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id_company = $this->session->userdata('id_company');
        $id    = $this->input->post("id",true);

        $awal = DateTime::createFromFormat('d-m-Y', $this->input->post("dfrom",true));
        $akhir   = DateTime::createFromFormat('d-m-Y', $this->input->post("dto",true));

        $dfrom = $awal->format('Y-m-d');
        $dto = $akhir->format('Y-m-d');
        $d_jangka_awal = $awal->format('Y-m-01');
        $i_periode = $awal->format('Ym');
        $d_jangka_akhir = $awal->modify('-1 day')->format('Y-m-d');

        if ($d_jangka_awal == $dfrom) {
            $d_jangka_awal = '9999-01-01';
            $d_jangka_akhir = '9999-01-31';
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'customer'        => $this->mmaster->customerId($id)->row(),
            'dfrom'         => $this->input->post("dfrom",true),
            'dto'           => $this->input->post("dto",true),
            'data2'         => $this->mmaster->cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $ibagian, $ikelompok, $jnsbarang)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function cetak(){

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id_company = $this->session->userdata('id_company');
        $id    = $this->uri->segment(4);

        $awal = DateTime::createFromFormat('d-m-Y', $this->uri->segment(5));
        $akhir   = DateTime::createFromFormat('d-m-Y', $this->uri->segment(6));

        $dfrom = $awal->format('Y-m-d');
        $dto = $akhir->format('Y-m-d');
        $d_jangka_awal = $awal->format('Y-m-01');
        $i_periode = $awal->format('Ym');
        $d_jangka_akhir = $awal->modify('-1 day')->format('Y-m-d');

        if ($d_jangka_awal == $dfrom) {
            $d_jangka_awal = '9999-01-01';
            $d_jangka_akhir = '9999-01-31';
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'customer'        => $this->mmaster->customerId($id, $id_company)->row(),
            'dfrom'         => $this->uri->segment(5),
            'dto'           => $this->uri->segment(6),
            'data'         => $this->mmaster->cek_datadet($id_company, $i_periode, $dfrom, $dto, $id),
            // 'data2'         => $this->mmaster->cek_datadet($id_company, $i_periode, $d_jangka_awal, $d_jangka_akhir, $dfrom, $dto, $bagian, $ikelompok, $jnsbarang)->result(),
        );
        $this->Logger->write('Membuka Menu Cetak '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformprint', $data);
    }
}
/* End of file Cform.php */