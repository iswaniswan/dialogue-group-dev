<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20205';

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
    

    public function index()    {
        //$iop = $this->uri->segment('4');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'], 
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        echo $this->mmaster->data($this->i_menu);
    }

    public function supplier(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_supplier");
            $this->db->like("UPPER(i_supplier)", $cari);
            $this->db->or_like("UPPER(e_supplier_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_supplier,  
                    'text' => $icolor->i_supplier.'-'.$icolor->e_supplier_name,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
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
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function proses(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isupplier      = $this->input->post('isupplier', TRUE); 
       
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'datasup'    => $this->mmaster->cek_sup($isupplier)->row(), 
            'data'       => $this->mmaster->get_notaitem($isupplier)->result(),
        );
        $this->Logger->write('Membuka Menu Input Item '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ipajak      = $this->input->post('ipajak', TRUE);
        $dpajak      = $this->input->post('dpajak', TRUE); 
        if($dpajak){
                 $tmp   = explode('-', $dpajak);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datepajak = $year.'-'.$month.'-'.$day;
        }
        $isupplier  = $this->input->post("isupplier",TRUE);
        $totdpp     = str_replace(',','',$this->input->post("totdpp",TRUE));
        $totppn     = str_replace(',','',$this->input->post("totppn",TRUE));
        $totakhir   = str_replace(',','',$this->input->post("totakhir",TRUE));

        $jml        = $this->input->post('jml', TRUE); 
        $cek        = $this->input->post('cek', TRUE); 

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ipajak);
        $this->mmaster->insert($ipajak, $datepajak, $isupplier, $totdpp, $totppn, $totakhir);
       // if($cek == TRUE){
            for($i=1;$i<=$jml;$i++){ 
                if($this->input->post('cek'.$i)=='cek'){  
                    $inota     = $this->input->post('inota'.$i, TRUE);
                    $vtotal    = $this->input->post('vtotal'.$i, TRUE); 
                    $inoitem   = $i;
                    $this->mmaster->insert1($ipajak, $inota, $vtotal, $inoitem);
                    $this->mmaster->updatenota($ipajak, $inota);
                }
            }
        
             if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $ipajak,
                );
        }
    $this->load->view('pesan', $data); 
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ipajak         = $this->uri->segment('4');
        $isupplier      = $this->uri->segment('5');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],            
            'data'       => $this->mmaster->cek_data($ipajak)->row(),
            'data1'      => $this->mmaster->get_edititem($ipajak, $isupplier)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ipajak      = $this->input->post('ipajak', TRUE);
        $dpajak      = $this->input->post('dpajak', TRUE); 
        if($dpajak){
                 $tmp   = explode('-', $dpajak);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datepajak = $year.'-'.$month.'-'.$day;
        }
        $isupplier  = $this->input->post("isupplier",TRUE);
        $totdpp     = str_replace(',','',$this->input->post("totdpp",TRUE));
        $totppn     = str_replace(',','',$this->input->post("totppn",TRUE));
        $totakhir   = str_replace(',','',$this->input->post("totakhir",TRUE));

        $jml        = $this->input->post('jml', TRUE); 
        $cek        = $this->input->post('cek', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ipajak);
        $this->mmaster->update($ipajak, $datepajak, $isupplier, $totdpp, $totppn, $totakhir);
        $this->mmaster->deletenotapajak($ipajak);

            for($i=1;$i<=$jml;$i++){   
                if($this->input->post('cek'.$i)=='cek'){                      
                    $inota     = $this->input->post('inota'.$i, TRUE);
                    $vtotal    = $this->input->post('vtotal'.$i, TRUE); 
                    $inoitem   = $i;
                    $this->mmaster->insert1($ipajak, $inota, $vtotal, $inoitem);
                    $this->mmaster->updatenota($ipajak, $inota);
                }
            }
             if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'      => $ipajak,
                );
        }
    $this->load->view('pesan', $data);  
    }


    public function view(){
        $ipajak         = $this->uri->segment('4');
        $isupplier      = $this->uri->segment('5');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_data($ipajak)->row(),
            'data1'      => $this->mmaster->get_edititem($ipajak, $isupplier)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ipajak        = $this->input->post('ipajak', TRUE);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ipajak);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Cancel Faktur Pajak '.$ipajak);
                echo json_encode($data);
        }
    }
}
/* End of file Cform.php */