<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107031003';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title'],
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    public function data(){
        $iperiode  = $this->input->post('iperiode');
        if($iperiode==''){
            $iperiode=$this->uri->segment(4);
        } 
        echo $this->mmaster->data($iperiode,$this->global['folder'],$this->i_menu);
    }
    
    public function view(){
        $iperiode  = $this->input->post('tahun',TRUE).$this->input->post('bulan',TRUE);
        if($iperiode==''){
            $iperiode=$this->uri->segment(4);
        } 

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'iperiode'      => $iperiode,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibbm               = $this->input->post('ibbm');
        $id                 = $this->input->post('id');
        $istore             = 'AA';
        $istorelocation     = '01';
        $istorelocationbin  = '00';
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($id,$istore,$istorelocation,$istorelocationbin);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Hapus Konversi Stock No : '.$id.' Gudang :'.$istore);
            echo json_encode($data);
        }
    }

    public function edit(){
        if(($this->uri->segment(4)!='') && ($this->uri->segment(5)!='')){
            $id            = $this->uri->segment(4);
            $irefference   = $this->uri->segment(5);
            $iperiode      = $this->uri->segment(6);
            $query = $this->db->query("select distinct a.i_refference, a.d_ic_convertion, a.f_ic_convertioncancel, b.i_area, c.e_area_name from tm_ic_convertion a, tm_bbm b, tr_area c 
                                        where a.i_refference = '$irefference' and a.i_refference = b.i_bbm and b.i_area=c.i_area");
            $data   = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'irefference'   => $irefference,
                'id'            => $id,
                'i_menu'        => $this->i_menu,
                'jmlitem'       => $query->num_rows(),
                'iperiode'      => $iperiode,
                'isi'           => $this->mmaster->bacaheaderdetail($irefference)->row(),
                'detail'        => $this->mmaster->bacadetail($irefference)->result()
            );   
        }        

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformedit', $data);
    }
}

/* End of file Cform.php */
