<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller{
    public $global = array();
    public $i_menu = '2090303';

    public function __construct(){
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

    public function index(){
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

    public function gudang(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_sub_bagian");
            $this->db->where("i_sub_bagian", "SDP0007");
            $data = $this->db->get();
            foreach($data->result() as  $idept){
                    $filter[] = array(
                    'id'   => $idept->i_sub_bagian,  
                    'text' => $idept->e_sub_bagian,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function tujuan(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_sub_bagian");
            $this->db->where("i_sub_bagian", "SDP0008");
            $data = $this->db->get();
            foreach($data->result() as  $idept){
                    $filter[] = array(
                    'id'   => $idept->i_sub_bagian,  
                    'text' => $idept->e_sub_bagian,
                );
            }          
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function dataproduct(){
        $filter = [];
        $iproduct = $this->uri->segment(4);
        if($this->input->get('q') != ''){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("distinct(a.i_product), b.e_product_namewip, a.i_color, c.e_color_name
                                from tr_polacutting a, tr_product_wip b, tr_color c
                                where a.i_product = b.i_product_wip
                                and a.i_color = c.i_color
                                order by a.i_product", false); 
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                    'id'    => $product->i_product,
                    'name'  => $product->e_product_namewip,
                    'text'  => $product->i_product.' - '.$product->e_product_namewip.'-'.$product->e_color_name
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    public function getproduct(){
        header("Content-Type: application/json", true);
        $eproduct = $this->input->post('eproduct');
        $this->db->select("distinct(a.i_product), b.e_product_namewip, a.i_color, c.e_color_name");
            $this->db->from("tr_polacutting a");
            $this->db->join("tr_product_wip b", "a.i_product = b.i_product_wip");
            $this->db->join("tr_color c", "a.i_color = c.i_color");
            $this->db->where("a.i_product", $eproduct);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function datamaterial(){
        $filter = [];
        $eproduct = $this->uri->segment(4);
        if($this->input->get('q') != ''){
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" a.i_product, a.i_material, b.e_material_name 
                                from tr_polacutting a
                                join tr_material b on a.i_material=b.i_material
                                where a.i_product='$eproduct'", false); 
            $data = $this->db->get();
            foreach($data->result() as  $product){       
                    $filter[] = array(
                    'id'    => $product->i_material,
                    'name'  => $product->e_material_name,
                    'text'  => $product->i_material.' - '.$product->e_material_name,
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    public function getmaterial(){
        header("Content-Type: application/json", true);
        $ematerial= $this->input->post('ematerial');
        $this->db->select("a.*,b.i_satuan, b.e_satuan");
        $this->db->from("tr_material a");
        $this->db->join("tr_satuan b", "a.i_satuan_code=b.i_satuan_code");
        $this->db->where("UPPER(i_material)", $ematerial);
        $this->db->order_by('a.i_material', 'ASC');
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }
   
    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dsj            = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $datesj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $ikodemaster    = $this->input->post('ikodemaster', TRUE);
        $itujuan       = $this->input->post('itujuan', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $isj  = $this->mmaster->runningnumber($thbl, $ikodemaster); 
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);
        $this->mmaster->insertheader($isj, $datesj, $ikodemaster, $itujuan, $eremark);           
            for($i=1;$i<=$jml;$i++){
                  $iproduct      = $this->input->post('iproduct'.$i, TRUE);
                  $icolor        = $this->input->post('icolorproduct'.$i, TRUE);
                  $imaterial     = $this->input->post('imaterial'.$i, TRUE);
                  $nquantity     = $this->input->post('nquantity'.$i, TRUE);
                  $edesc         = $this->input->post('edesc'.$i, TRUE);
                  $inoitem       = $i;

                  if($nquantity>0){
                     $this->mmaster->insertdetail($isj, $iproduct, $icolor, $imaterial, $nquantity, $edesc, $inoitem);
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
                    'kode'      => $isj,
                );
        }
    $this->load->view('pesan', $data); 
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $isj  = $this->uri->segment(4);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->baca_header($isj)->row(),
            'datadetail' => $this->mmaster->baca_detail($isj)->result(),
            'kodemaster' => $this->mmaster->baca_gudang()->result(),
            'tujuan'     => $this->mmaster->baca_tujuan()->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj            = $this->input->post('isj', TRUE);
        $dsj            = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $datesj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
        $ikodemaster    = $this->input->post('ikodemaster', TRUE);
        $itujuan        = $this->input->post('itujuan', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $jml            = $this->input->post('jml', TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$isj);
        $this->mmaster->updateheader($isj, $datesj, $ikodemaster, $itujuan, $eremark);    
        $this->mmaster->deletedetail($isj);   

            for($i=1;$i<=$jml;$i++){
                  $iproduct      = $this->input->post('iproduct'.$i, TRUE);
                  $icolor        = $this->input->post('icolorproduct'.$i, TRUE);
                  $imaterial     = $this->input->post('imaterial'.$i, TRUE);
                  $nquantity     = $this->input->post('nquantity'.$i, TRUE);
                  $edesc         = $this->input->post('edesc'.$i, TRUE);
                  $inoitem       = $i;

                  if($nquantity>0){
                     $this->mmaster->insertdetail($isj, $iproduct, $icolor, $imaterial, $nquantity, $edesc, $inoitem);
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
                    'kode'      => $isj,
                );
        }
    $this->load->view('pesan', $data); 
    }

    public function view(){
        $isj  = $this->uri->segment(4);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->baca_header($isj)->row(),
            'datadetail' => $this->mmaster->baca_detail($isj)->result(),
            'kodemaster' => $this->mmaster->baca_gudang()->result(),
            'tujuan'     => $this->mmaster->baca_tujuan()->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */