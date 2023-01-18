<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050314';

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
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'kodemaster'=> $this->mmaster->bacagudang(),
            //'jnskeluar'=> $this->mmaster->bacajenis(),
            //'tujuan'=> $this->mmaster->bacatujuan(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function customer(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->mmaster->customer($cari);
            foreach($data->result() as  $partner){       
                $filter[] = array(
                    'id' => $partner->id,  
                    'name' => $partner->partner,
                    'text' => $partner->id.' - '.$partner->partner
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }


    function getbonmk(){
        //var_dump($gudang);
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $gudang = $this->input->get('gudang', FALSE);
            $icustomer = $this->input->get('icustomer', FALSE);
            $data = $this->mmaster->bonmk($cari,$gudang,$icustomer);
            foreach($data->result() as  $bonmk){       
                $filter[] = array(
                    'id' => $bonmk->i_bonmk,  
                    'text' => $bonmk->i_bonmk.' || '.$bonmk->d_bonmk
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    } 

    public function getdetailbonmk(){
        header("Content-Type: application/json", true);
        $ibonmk  = $this->input->post('ibonmk', FALSE);
        $gudang  = $this->input->post('gudang', FALSE);
        //$icustomer  = $this->input->post('icustomer', FALSE);
        $query  = array(
            'head' => $this->mmaster->getbonmk($ibonmk, $gudang)->row(),
            'detail' => $this->mmaster->getbonmk_detail($ibonmk, $gudang)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dkonversi = $this->input->post("dkonversi",true);
        $tmp = explode('-', $dkonversi);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = substr($tmp[2],2,2);
        $dkonversi = $tmp[2].'-'.$bl.'-'.$hr;

        $istore                 = $this->input->post('istore', TRUE);
        $icustomer                 = $this->input->post('icustomer', TRUE);
        $nobonkeluar             = $this->input->post('ibonmk', TRUE);
        $remark                 = $this->input->post('eremark', TRUE);
        $nokonversi             = $this->mmaster->runningnumberkonversi($th,$bl,$istore);
        $jml                    = $this->input->post('jml', TRUE); 
        
        $cancel               = 'f';
        $query      = $this->db->query("SELECT current_timestamp as c");
        $row        = $query->row();
        $now        = $row->c;
        //var_dump($dbonk,$istore, $remark, $nobonmasuk, $nobonmasuk);
        $this->db->trans_begin();
        $this->mmaster->insertheader($dkonversi,$istore, $remark, $nobonkeluar, $nokonversi, $now, $icustomer);
            $urutan = 1;
            for($i=1;$i<=$jml;$i++){
                $imaterial = $this->input->post('i_material'.$i, TRUE);
                $check = $this->input->post('chk'.$i, TRUE);
                $deliver = $this->input->post('n_2qty'.$i, TRUE);
                if ( ($imaterial != '' or $imaterial != null) && $check == "on" && $deliver > 0) {
                    $nquantity      = $this->input->post('n_qty'.$i, TRUE);
                    $ndeliver      = $deliver;
                    $isatuan        = $this->input->post('i_satuan'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $this->mmaster->insertdetail($nokonversi, $istore, $imaterial, $nquantity,$isatuan, $edesc, $urutan, $ndeliver);
                    $urutan++;
                }
                
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$nokonversi);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nokonversi,
                );
        }
        $this->load->view('pesan', $data);      
    
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ikonv   = $this->input->post('ikonv');
        $gudang  = $this->input->post('gudang');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ikonv, $gudang);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Konversi Pinjaman ke Penjualan Aksesoris '.$ikonv.' Gudang:'.$gudang);
            echo json_encode($data);
        }
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_konv = $this->uri->segment('4');
        $gudang = $this->uri->segment('5');
        $i_reff = $this->uri->segment('6');
        $cari = "";
        //var_dump($i_konv, $gudang, $i_reff);
        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'head' => $this->mmaster->baca_header($i_konv, $gudang)->row(),
            'detail' => $this->mmaster->baca_detail($i_konv, $gudang,$i_reff)->result()
        );
        // 'customer'   => $this->mmaster->customer($cari)->result()
        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $dkonversi = $this->input->post("dkonversi",true);
        $tmp = explode('-', $dkonversi);
        $hr = $tmp[0];
        $bl = $tmp[1];
        $th = substr($tmp[2],2,2);
        $dkonversi = $tmp[2].'-'.$bl.'-'.$hr;

        $remark               = $this->input->post('eremark', TRUE);
        $nokonversi          = $this->input->post('nokonversi', TRUE);
        $jml                  = $this->input->post('jml', TRUE);
        $istore               = $this->input->post('istore', TRUE);

        $cancel               = 'f';
        $query      = $this->db->query("SELECT current_timestamp as c");
        $row        = $query->row();
        $now        = $row->c;
        // var_dump($dbonk, $remark, $nobonmasuk, $now, $istore);
        $this->db->trans_begin();
        $this->mmaster->updateheader($dkonversi, $remark, $nokonversi, $now, $istore);
        $this->mmaster->deletedetail($nokonversi, $istore);
            $urutan = 1;
            for($i=1;$i<=$jml;$i++){
                $imaterial = $this->input->post('i_material'.$i, TRUE);
                $check = $this->input->post('chk'.$i, TRUE);
                $deliver = $this->input->post('n_qty'.$i, TRUE);
                //var_dump($imaterial, $check,$deliver);
                if ( ($imaterial != '' or $imaterial != null) && $check == "on" && $deliver > 0) {
                    $nquantity      = $this->input->post('nquantity'.$i, TRUE);
                    $ndeliver      = $deliver;
                    $isatuan        = $this->input->post('i_satuan'.$i, TRUE); 
                    $edesc          = $this->input->post('edesc'.$i, TRUE);
                    $this->mmaster->insertdetail($nokonversi, $istore, $imaterial, $nquantity,$isatuan, $edesc, $urutan, $ndeliver);
                    $urutan++;
                }
                
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                        
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$nokonversi);
                $data = array(
                    'sukses' => true,
                    'kode'      => $nokonversi,
                );
        }
        $this->load->view('pesan', $data);
    }

}
/* End of file Cform.php */
