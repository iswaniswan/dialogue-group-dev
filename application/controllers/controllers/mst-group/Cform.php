<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010804';
   
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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function data(){
		echo $this->mmaster->data($this->i_menu,$this->global['folder']);
    }


    function datacabang(){
        $ipusat = $this->uri->segment(4);
		echo $this->mmaster->datacabang($this->i_menu,$this->global['folder'],$ipusat);
    }

    public function getpartner(){
        $isuppliergroup = $this->input->post('id');
        $query = $this->mmaster->getpartner($isuppliergroup);
        if($query->num_rows()>0) {
            $c      = "";
            $jenis  = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_kepala_pusat." >".$row->i_kepala_pusat.' - '.$row->e_pusat."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Partner Group -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Partner Group Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getkategoripartner(){
        $query = $this->mmaster->getkategoripartner();
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_supplier_group." >".$row->e_supplier_group_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Kategori Partner -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Ketegori Partner Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function getdetailpartner(){
        header("Content-Type: application/json", true);
        $ipartner = $this->input->post('ipusat');
        $data = $this->db->query("
                                SELECT 
                                    i_supplier as i_kepala_pusat
                                FROM 
                                    tr_supplier 
                                WHERE 
                                    f_status_supplier = 't'
                                    and i_supplier = '$ipartner'
                                UNION ALL
                                SELECT 
                                    i_customer as i_kepala_pusat
                                FROM
                                    tr_customer
                                WHERE 
                                    f_customer_aktif = 't'
                                    and i_customer = '$ipartner'
                                GROUP BY
                                    i_kepala_pusat
                                ORDER BY
                                    i_kepala_pusat
                                ", FALSE);
        echo json_encode($data->result_array());
    }

    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            //'kategori'   => $this->mmaster->getsuppliergroup()
                
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isuppliergroup  = $this->input->post('isuppliergroup', TRUE);
        $levelgroup      = $this->input->post('ilevel', TRUE);       
        $ipartner        = $this->input->post('isupplier', TRUE);
        
        if ($isuppliergroup != ''){
            $this->db->trans_begin();
            $this->mmaster->update($isuppliergroup, $levelgroup, $ipartner);
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Partner Group :'.$this->global['title'].' Kode : '.$ipartner);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update Partner Group : '.$ipartner,
                    'folder'    => $this->global['folder'],
                    'title'     => $this->global['title']
                );
            }
        }else{
                $data = array(
                    'sukses' => false,
                    'folder'    => $this->global['folder'],
                    'title'     => $this->global['title']
                );
        }
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ipartner = $this->uri->segment('4');
        $ilevel   = $this->uri->segment('5');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'level'      => $this->mmaster->bacalevel($ilevel),
            'isi'        => $this->mmaster->baca($ipartner)->row()
          
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function getpusat(){
        $isuppliergroup = $this->input->post('isuppliergroup');
        $ipartner = $this->input->post('ipartner');
        $query = $this->mmaster->getpusat($isuppliergroup,$ipartner);
        if($query->num_rows()>0) {
            $c      = "";
            $jenis  = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_kepala_pusat." >".$row->e_pusat."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Kepala Group -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Kepala Group Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ipartner = $this->input->post('ipartner', TRUE);
        $ilevel   = $this->input->post('ilevel', TRUE);      
        $ipusat   = $this->input->post('ipusat', TRUE);     

        if ($ipartner != ''){
            $this->db->trans_begin();
            $this->mmaster->updatelevel($ipartner, $ilevel, $ipusat);
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Partner Group :'.$this->global['title'].' Kode : '.$ipartner);
                $data = array(
                    'sukses'    => true,
                    'kode'      => 'Update Partner Group : '.$ipartner
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }


    public function view(){

        $ipartner= $this->uri->segment('4');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'ipusat'     => $ipartner,
            'isi'        => $this->mmaster->baca($ipartner)->row()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

}

/* End of file Cform.php */