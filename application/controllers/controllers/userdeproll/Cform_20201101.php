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
            'user' => $this->mmaster->bacauser($idcompany)->result(),
            'company' => $this->mmaster->bacacompany($idcompany)->result(),
            'depart' => $this->mmaster->bacadepart($idcompany)->result(),
            'level' => $this->mmaster->bacalevel($idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformlist', $data);
    }

    public function data()
    {
        $idcompany = $this->session->userdata('id_company');
        echo $this->mmaster->data($this->i_menu, $idcompany, $this->global['folder']);
    }

    public function status(){
        $data = check_role($this->i_menu, 3);
        // if(!$data){
        //     redirect(base_url(),'refresh');
        // }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        $str = explode('|', $id);
        $id_company = $str[0];
        $i_departement = $str[1];
        $i_level = $str[2];
        $i_apps = $str[3];
        // var_dump($iproduct. " ".$icolor);
        // die();
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id_company,$i_departement, $i_level, $i_apps);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }
    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $idcompany = $this->session->userdata('id_company');
        // $idcompany = $this->session->userdata('id_company');


        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'user'          => $this->mmaster->bacauser($idcompany)->result(),
            'company'       => $this->mmaster->bacacompany($idcompany)->result(),
            'depart'        => $this->mmaster->bacadepart($idcompany)->result(),
            'level'         => $this->mmaster->bacalevel($idcompany)->result(),
            'menu'          => $this->mmaster->bacamenu()->result(),
            'submenu'       => $this->mmaster->bacasubmenu()->result(),
            'kode' => '',
        );

        $this->Logger->write('Membuka Menu Tambah ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformadd', $data);
    }

    // public function getgudang()
    // {
    //     $ikodemaster = $this->input->post('ikodemaster');
    //     $query = $this->mmaster->getgudang($ikodemaster);
    //     if ($query->num_rows() > 0) {
    //         $c = "";
    //         $spb = $query->result();
    //         foreach ($spb as $row) {
    //             $c .= "<option value=" . $row->i_kode_kelompok . " >" . $row->i_kode_kelompok . " - " . $row->e_nama . "</option>";
    //         }
    //         $kop = "<option value=\"\"> -- Pilih Kategori -- " . $c . "</option>";
    //         echo json_encode(array(
    //             'kop' => $kop,
    //         ));
    //     } else {
    //         $kop = "<option value=\"\">Data Kosong</option>";
    //         echo json_encode(array(
    //             'kop' => $kop,
    //             'kosong' => 'kopong',
    //         ));
    //     }
    // }


public function getsubmenu(){
    $imenu = $this->input->post('imenu');
    $query = $this->mmaster->getsubmenu($imenu);
    if($query->num_rows()>0) {
        $c  = "";
        $spb = $query->result();
        foreach($spb as $row) {
            $c.="<option value=".$row->i_menu." >".$row->e_menu."</option>";
        }
        // $kop  = "<option value="">Pilih Parent".$c."</option>";
        $kop = "<option value=\"\"> -- Pilih Parent -- " . $c . "</option>";
        echo json_encode(array(
            'kop'   => $kop
        ));
    }else{
        $kop  = "<option value=\"\">Data Kosong</option>";
        echo json_encode(array(
            'kop'    => $kop,
            'kosong' => 'kopong'
        ));
    }
}

public function getlev(){
    $ideptart = $this->input->post('ideptart');
    $query = $this->mmaster->getlev($ideptart);
    if($query->num_rows()>0) {
        $c  = "";
        $spb = $query->result();
        foreach($spb as $row) {
            $c.="<option value=".$row->i_level." >".$row->e_level_name."</option>";
        }
        // $kop  = "<option value="">Pilih Parent".$c."</option>";
        $kop = "<option value=\"\"> -- Pilih Level -- " . $c . "</option>";
        echo json_encode(array(
            'kop'   => $kop
        ));
    }else{
        $kop  = "<option value=\"\">Data Kosong</option>";
        echo json_encode(array(
            'kop'    => $kop,
            'kosong' => 'kopong'
        ));
    }
}

    function getcustomer(){
        header("Content-Type: application/json", true);
        $idep       = $this->input->post('dep');
        $ilev       = $this->input->post('lev');
        $imenu      = $this->input->post('imenu');
        $isubmenu   = $this->input->post('isubmenu');
            $data = array(
                'dataitem'   => $this->mmaster->getcustomer($idep, $ilev, $imenu, $isubmenu)->result_array(),
            );
            echo json_encode($data);
        }

        function getmenudetail(){
            header("Content-Type: application/json", true);
            $imenu       = $this->input->post('imenu');
                $data = array(
                    'dataitem'   => $this->mmaster->getmenudetail($imenu)->result_array(),
                );
                echo json_encode($data);
            }
        
        

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        
        $idept      = $this->input->post('idept', true);
        $ilevel     = $this->input->post('ilevel', true);
        $jml        = $this->input->post("jml", true);
        $jmldet     = $this->input->post("jmldetail", true);
        $imenu2     = $this->input->post("imenu", true);
        $isubmenu   = $this->input->post("isubmenu", true);
        $cekall     = $this->input->post("cekall", true);
        
        for($i=1;$i<=$jml;$i++){
                
            $imenu      = $this->input->post('imenu2'.$i, TRUE);
            if($cek=$this->input->post('cek'.$i)=='cek' || $cekall == TRUE ){
                $query2 = $this->mmaster->getpower($imenu, $idept, $ilevel);
                if ($query2->num_rows() > 0) {
                    foreach ($query2->result() as $row) {
                        $imenu               = $row->i_menu;
                        $iuserpower          = $row->id_user_power;
                            $this->mmaster->insertheaderall($imenu, $idept, $ilevel, $iuserpower);
                    }
                }    
                // $imenu      = $this->input->post('imenu2'.$i, TRUE);
                // $iuserpower = $this->input->post('userpower'.$i, TRUE);
                // $this->mmaster->insertheader($imenu, $idept, $ilevel, $iuserpower);
            }
        }

        $query = $this->db->query("select * from tm_user_role where i_departement = '$idept' and i_level = '$ilevel' and i_menu = '$imenu2'");
            if($query->num_rows() > 0){

            }else{
                $this->mmaster->insertheader($imenu2, $idept, $ilevel, '2');
            }

        $query3 = $this->db->query("select * from tm_user_role where i_departement = '$idept' and i_level = '$ilevel' and i_menu = '$isubmenu'");
            if($query3->num_rows() > 0){

            }else{
                $this->mmaster->insertheader($isubmenu, $idept, $ilevel, '2');
            }

        // foreach ($menu as $row) {
        //     $imenu = substr($row, 0, -1);
        //     $iuserpower = substr($row, -1);
        //     $cekada = $this->mmaster->cek_dataheader($idept, $ilevel, $imenu, $iuserpower);
        //     if ($cekada->num_rows() > 0) {

        //     }else{
        //         $this->mmaster->insertheader($imenu, $idept, $ilevel, $iuserpower);
        //     }
        $this->db->trans_begin();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $ilevel . '-' . $idept,
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

        $ilevel = $this->uri->segment('4');
        $idept = $this->uri->segment('5');
        $icompany = $this->uri->segment('6');
        $iapps = $this->uri->segment('7');
        $idcompany = $this->session->userdata('id_company');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'ilevel'            => $ilevel,
            'idept'             => $idept,
            'icompany'          => $icompany,
            'iapps'             => $iapps,
            'data' => $this->mmaster->cek_data($ilevel, $idept, $icompany, $iapps)->row(),
            'data2' => $this->mmaster->cek_datadetail($ilevel, $idept, $icompany, $iapps)->result(),
            'user' => $this->mmaster->bacauser($idcompany)->result(),
            'company' => $this->mmaster->bacacompany($idcompany)->result(),
            'depart' => $this->mmaster->bacadepart($idcompany)->result(),
            'level' => $this->mmaster->bacalevel($idcompany)->result(),
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

        $idept          = $this->input->post('idept', true);
        $ilevel         = $this->input->post('ilevel', true);
        $jml            = $this->input->post('jml', true);
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idept);
        
        for($j=1;$j<=$jml;$j++){
            $imenu                  = $this->input->post('imenu'.$j, TRUE);
            $iuserpower              = $this->input->post('power'.$j, TRUE);
            $this->mmaster->insertheader($imenu, $idept, $ilevel,$iuserpower);
        }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $idept,
                );
            }
            $this->load->view('pesan', $data);
    }

    public function deletedetail(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $idept          = $this->input->post('idept', true);
        $ilevel         = $this->input->post('ilevel', true);
        $imenu          = $this->input->post('imenu', true);
        $iuserpower     = $this->input->post('iuserpower', true);
        $this->db->trans_begin();
        $this->mmaster->deletedetail($idept, $ilevel, $imenu, $iuserpower);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Delete Menu : '.$imenu.' Departement : '.$idept. 'Level : '.$ilevel);
            echo json_encode($data);
        }
    }

    public function view()
    {

        $ipp = $this->uri->segment('4');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'data' => $this->mmaster->cek_data($ipp)->row(),
            'data2' => $this->mmaster->cek_datadet($ipp)->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function send()
    {
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function sendd()
    {
        header("Content-Type: application/json", true);
        $ipp = $this->input->post('ipp');
        $this->db->trans_begin();
        $this->mmaster->sendd($ipp);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $ipp,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function delete()
    {
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_pp = $this->input->post('i_pp', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($i_pp);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Permintaan Pembelian ' . $i_pp);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */