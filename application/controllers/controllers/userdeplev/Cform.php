<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '2010905';

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

        $data = array(
            'folder' => $this->global['folder'],
            'title'  => $this->global['title'],
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    public function data()
    {
        $idcompany = $this->session->userdata('id_company');
        echo $this->mmaster->data($this->i_menu, $idcompany, $this->global['folder']);
    }

    public function status()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        $str            = explode('|', $id);
        $id_company     = $str[0];
        $i_departement  = $str[1];
        $i_level        = $str[2];
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id_company,$i_departement,$i_level);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }

    public function user()
    {
        $filter = [];
        if ($this->input->get('q')!='') {
            $data = $this->mmaster->get_user($this->input->get('q'));
            foreach($data->result() as $key){
                $filter[] = array(
                    'id'   => $key->username,  
                    'text' => $key->username,
                );
            }          
            echo json_encode($filter);
        }else{            
            echo json_encode($filter);
        }
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'company'    => $this->mmaster->bacacompany()->result(),
            'depart'     => $this->mmaster->bacadepart($idcompany)->result(),
            'level'      => $this->mmaster->bacalevel($idcompany)->result(),
            'kode'       => '',
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    public function simpan()
    {
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $iuser     = $this->input->post('iuser', true);
        $icompany  = $this->input->post('icompany', true);
        $idept     = $this->input->post('idept', true);
        $ilevel    = $this->input->post('ilevel', true);
        if ($iuser != '' && $idept != '' && $ilevel != '') {
            $query = $this->mmaster->cek_data($iuser,$icompany,$idept,$ilevel);
            if ($query->num_rows()>0) {
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->insert($iuser,$icompany,$idept,$ilevel);
                if ($this->db->trans_status() === false) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                } else {
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Username : '.$iuser);
                    $data = array(
                        'sukses' => true,
                        'kode'   => $iuser,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $username   = $this->uri->segment(4);
        $icompany   = $this->uri->segment(5);
        $idept      = $this->uri->segment(6);
        $ilevel     = $this->uri->segment(7);

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'username'      => $username,
            'icompany'      => $icompany,
            'xdept'         => $idept,
            'xlevel'        => $ilevel,
            'data'          => $this->mmaster->cek_data($ilevel,$idept,$icompany,$username)->row(),
            'company'       => $this->mmaster->bacacompany($icompany)->result(),
            'depart'        => $this->mmaster->bacadepart($icompany)->result(),
            'level'         => $this->mmaster->bacalevel($icompany)->result(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iuser       = $this->input->post('iuser', TRUE);
        $icompany    = $this->input->post('icompany', TRUE);
        $idept       = $this->input->post('idept', TRUE);
        $ilevel      = $this->input->post('ilevel', TRUE);
        
        $iuserold    = $this->input->post('iuserold', TRUE);
        $icompanyold = $this->input->post('icompanyold', TRUE);
        $ideptold    = $this->input->post('ideptold', TRUE);
        $ilevelold   = $this->input->post('ilevelold', TRUE);

        if ($iuser != '' && $idept != '' && $ilevel != '' && $icompany != ''){
            $cekada = $this->mmaster->cek_data($ilevelold,$ideptold,$icompanyold,$iuserold);
            if($cekada->num_rows() > 0){
                $cekada = $this->mmaster->cek_data($ilevel,$idept,$icompany,$iuser);
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false,
                    );
                }else{
                    $this->db->trans_begin();
                    $this->mmaster->update($iuser,$icompany,$idept,$ilevel,$iuserold,$icompanyold,$ideptold,$ilevelold);
                    if ($this->db->trans_status() === false) {
                        $this->db->trans_rollback();
                        $data = array(
                            'sukses' => false,
                        );
                    } else {
                        $this->db->trans_commit();
                        $this->Logger->write('Update Data '.$this->global['title'].' Username : '.$iuser);
                        $data = array(
                            'sukses' => true,
                            'kode'   => $iuser,
                        );
                    }
                }
            }else{
                $data = array(
                    'sukses' => false
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        if ($_POST!='' || $_POST!=null) {
            $this->db->trans_begin();
            $data = $this->mmaster->delete($this->input->post('username', TRUE),$this->input->post('ilevel', TRUE),$this->input->post('idept', TRUE),$this->input->post('idcompany', TRUE));
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Hapus Data '.$this->global['title'].' Username : '.$this->input->post('username', TRUE).' Departement : '.$this->input->post('idept', TRUE).' Level : '.$this->input->post('ilevel', TRUE).' Company : '.$this->input->post('idcompany', TRUE));
                echo json_encode($data);
            }
        }
    }
}
/* End of file Cform.php */