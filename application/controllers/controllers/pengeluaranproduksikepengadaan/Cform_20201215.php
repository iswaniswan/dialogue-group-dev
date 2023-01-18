<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050303';

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        echo $this->mmaster->data($this->i_menu);
    }
    
    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->gudang(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getdepartemen(){
        //var_dump($gudang);
        $filter = [];
        /*if($this->input->get('q') != '') {*/
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->getdepartemen($cari);
            foreach($data->result() as  $idept){       
                $filter[] = array(
                    'id' => $idept->id,  
                    'text' => $idept->name
                );
            }   
            echo json_encode($filter);
        /*}else{
            echo json_encode($filter);
        }*/
    } 

    public function getmemo(){
        //var_dump($gudang);
        $filter = [];
        /*if($this->input->get('q') != '') {*/
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang = $this->input->get('gudang', FALSE);
            $data = $this->mmaster->getmemo($cari,$gudang);
            foreach($data->result() as  $imemo){       
                $filter[] = array(
                    'id' => $imemo->i_op_code,  
                    'text' => $imemo->i_op_code
                );
            }   
            echo json_encode($filter);
        /*}else{
            echo json_encode($filter);
        }*/
    } 

    public function getdetailmemo(){
        header("Content-Type: application/json", true);
        $imemo  = $this->input->post('imemo', FALSE);
        $gudang = $this->input->post('gudang', FALSE);
        $query  = array(
            'head'      => $this->mmaster->getmemoheader($imemo, $gudang)->row(),
            'detail'    => $this->mmaster->getdetailmemo($imemo, $gudang)->result_array()
        );
        echo json_encode($query);  
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $istore   = $this->input->post("istore",true);
        $dbonk    = $this->input->post("dbonk",true);
        if($dbonk){
                 $tmp   = explode('-', $dbonk);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datebonk = $year.'-'.$month.'-'.$day;
        }

        $imemo    = $this->input->post('imemo', TRUE);
        $dmemo    = $this->input->post("dmemo",true);
        if($dmemo){
                 $tmp   = explode('-', $dmemo);
                 $day   = $tmp[2];
                 $month = $tmp[1];
                 $year  = $tmp[0];
                 $datememo = $year.'-'.$month.'-'.$day;
        }

        $idepartement  = $this->input->post('idepartement', TRUE);
        $eremark       = $this->input->post('eremark', TRUE);
        $jml           = $this->input->post('jml', TRUE); 
        $ikeluarprod   = $this->mmaster->runningnumberpp($yearmonth, $istore);
       
        $this->db->trans_begin();
        $this->mmaster->insertheader($ikeluarprod, $istore, $datebonk, $imemo, $datememo, $idepartement, $eremark);

            for($i=1;$i<=$jml;$i++){
                $i_material     = $this->input->post('i_material'.$i, TRUE);
                $n_qtyout       = $this->input->post('n_qtyout'.$i, TRUE);
                $n_qtykeluar    = $this->input->post('n_qtykeluar'.$i, TRUE);
                $edesc          = $this->input->post('edesc'.$i, TRUE);
                $this->mmaster->insertdetail($ikeluarprod, $i_material, $n_qtyout, $n_qtykeluar, $edesc, $i);
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikeluarprod);
                $data = array(
                    'sukses' => true,
                    'kode'      => $ikeluarprod,
                );
        }
    $this->load->view('pesan', $data);        
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibonk = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->gudang(),
            'departemen'    => $this->mmaster->bacadepartemen(),
            'data'          => $this->mmaster->get_header($ibonk)->row(),
            'detail'        => $this->mmaster->get_detail($ibonk)->result(),
        );
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $ikeluarprod   = $this->input->post("ibonk",true);
        $istore        = $this->input->post("istore",true);
        $dbonk         = $this->input->post("dbonk",true);
        if($dbonk){
                 $tmp   = explode('-', $dbonk);
                 $day   = $tmp[2];
                 $month = $tmp[1];
                 $year  = $tmp[0];
                 $yearmonth = $year.$month;
                 $datebonk = $year.'-'.$month.'-'.$day;
        }

        $imemo    = $this->input->post('imemo', TRUE);
        $dmemo    = $this->input->post("dmemo",true);

        $idepartement  = $this->input->post('idepartement', TRUE);
        $eremark       = $this->input->post('eremark', TRUE);
        $jml           = $this->input->post('jml', TRUE); 
       
        $this->db->trans_begin();
        $this->mmaster->updateheader($ikeluarprod, $istore, $datebonk, $imemo, $idepartement, $eremark);
        $this->mmaster->deletedetail($ikeluarprod);

            for($i=1;$i<=$jml;$i++){
                $i_material     = $this->input->post('imaterial'.$i, TRUE);
                $n_qtyout       = $this->input->post('nqtyout'.$i, TRUE);
                $n_qtykeluar    = $this->input->post('nqtykeluar'.$i, TRUE);
                $edesc          = $this->input->post('edesc'.$i, TRUE);

                $this->mmaster->insertdetail($ikeluarprod, $i_material, $n_qtyout, $n_qtykeluar, $edesc, $i);
            }
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' No Bon : '.$ikeluarprod);
                $data = array(
                    'sukses' => true,
                    'kode'      => $ikeluarprod,
                );
        }
        $this->load->view('pesan', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ibonk   = $this->input->post('ibonk');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ibonk);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Bon Keluar Pengadaan '.$ibonk);
            echo json_encode($data);
        }
    }

     public function view(){

        $ibonk = $this->uri->segment('4');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'kodemaster'    => $this->mmaster->bacagudang(),
            'departemen'    => $this->mmaster->bacadepartemen(),
            'data'          => $this->mmaster->get_header($ibonk)->row(),
            'detail'        => $this->mmaster->get_detail($ibonk)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */