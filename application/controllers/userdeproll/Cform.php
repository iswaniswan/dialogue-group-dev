<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2010904';

    public function __construct()
    {
        parent::__construct();
        cek_session();

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];

        $this->load->model($this->global['folder'] . '/mmaster');
    }

    public function index()
    {

        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => $this->global['title'],
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    function data()
    {
        echo $this->mmaster->data($this->i_menu,$this->global['folder']);
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Update " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'depart'        => $this->mmaster->bacadepart()->result(),
            'userpower'     => $this->mmaster->bacapower()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function level()
    {
        $filter = [];
        if ($this->input->get('i_departement')!='') {
            $data = $this->mmaster->bacalevel(str_replace("'", "", $this->input->get('q')),$this->input->get('i_departement'));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_level,  
                    'text' => ucwords(strtolower($key->e_level_name)),
                );
            }
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function menu()
    {
        $filter = [];
        $data = $this->mmaster->bacamenu(str_replace("'", "", $this->input->get('q')));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->i_menu,  
                'text' => ucwords(strtolower($key->e_menu)),
            );
        }          
        echo json_encode($filter);
    }

    public function submenu()
    {
        $filter = [];
        if ($this->input->get('i_menu')!='') {
            $data = $this->mmaster->bacamenusub(str_replace("'", "", $this->input->get('q')),$this->input->get('i_menu'));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->i_menu,  
                    'text' => ucwords(strtolower($key->e_menu)),
                );
            }          
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function getmenu()
    {
        header("Content-Type: application/json", true);
        $data = array(
            'menu'  => $this->mmaster->getmenu($this->input->post('dep', TRUE), $this->input->post('lev', TRUE), $this->input->post('imenu', FALSE), $this->input->post('isubmenu', FALSE))->result_array(),
        );
        echo json_encode($data);
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        
        $idept      = $this->input->post('idept', true);
        $ilevel     = $this->input->post('ilevel', true);
        $imenu      = $this->input->post("imenu", true);
        $isubmenu   = $this->input->post("isubmenu", true);
        $jml        = $this->input->post("jml", true);

        if ($idept != '' && $ilevel != '' && $imenu != '' && $jml > 0) {
            $this->db->trans_begin();
            $this->mmaster->insertheader($imenu,2,$idept,$ilevel);
            if ($isubmenu != '' || $isubmenu != null) {
                $this->mmaster->insertheader($isubmenu,2,$idept,$ilevel);
            }
            /*Baca nama userpowernya dari database*/
            $power = $this->mmaster->bacapower();
            if ($power->num_rows()>0) {
                foreach ($power->result() as $key) {
                    for ($i=1; $i <= $jml; $i++) {
                        $i_menu  = $this->input->post('i_menu'.$i, TRUE);
                        /*Get post nama userpower dari view sesuai nama yang ada didatabase*/
                        $cek     = $this->input->post(strtolower($key->e_name).$i, TRUE);
                        if ($cek=='on') {
                            $this->mmaster->insertdetail($i_menu,$key->id,$idept,$ilevel);
                        } else {
                            $this->mmaster->deletedetail($i_menu,$key->id,$idept,$ilevel);
                        }
                    }
                }
            }
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => '',
                );

            }
        }else{
            $data = array(
                'sukses' => false,    
            );
        }

        $this->load->view('pesan', $data);
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Detail " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'row'           => $this->mmaster->baca($this->uri->segment(4),$this->uri->segment(5))->row(),
            'data'          => $this->mmaster->bacadata($this->uri->segment(4),$this->uri->segment(5))->result(),
            'userpower'     => $this->mmaster->bacapower()->result(),
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }
}
/* End of file Cform.php */