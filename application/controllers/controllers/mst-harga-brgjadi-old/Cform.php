<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010502';

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

    function data(){
		echo $this->mmaster->data($this->i_menu, $this->global['folder']);
    }
    
    public function status(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
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
            'product'    => $this->mmaster->bacaproduct(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function getmaterial(){
        header("Content-Type: application/json", true);
        $ematerialname = $this->input->post('ematerialname');
        $this->db->select("*");
            $this->db->from("tr_product_base");
            // $this->db->join("tr_satuan b","a.i_satuan_code=b.i_satuan_code");
            $this->db->where("UPPER(i_product_motif)", $ematerialname);
            $this->db->order_by('e_product_basename', 'ASC');            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function datamaterial(){
        $filter = [];
        $ikodemaster = $this->uri->segment(4);
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("select *
                            from tr_product_base 
                            where (i_product_motif like '%$cari%' or e_product_basename like '%$cari%') order by i_product_motif");
            foreach($data->result() as  $material){       
                    $filter[] = array(
                    'id' => $material->i_product_motif,  
                    'name' => $material->e_product_basename,
                    'text' => $material->i_product_motif.' - '.$material->e_product_basename
                );
            }   
            echo json_encode($filter);
        }else{
            echo json_encode($filter);
        }
    }  

    function getnamawarna(){
        header("Content-Type: application/json", true);
        $xcolor = $this->input->post('i_color');
        $this->db->select("i_color, e_color_name");
            $this->db->from("tr_color");
            $this->db->where("UPPER(i_color)", $xcolor);            
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function material(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("a.i_material, a.e_material_name ,b.i_satuan, b.e_satuan");
            $this->db->from("from tr_material a");
            $this->db->join("tr_satuan b","a.i_satuan=b.i_satuan");
            $this->db->or_like("UPPER(i_color)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->nama,
        
                );
            }          
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getcolor(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_color");
            $this->db->like("UPPER(i_color)", $cari);
            $this->db->or_like("UPPER(e_color_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $icolor){
                    $filter[] = array(
                    'id'   => $icolor->i_color,  
                    'text' => $icolor->i_color.'-'.$icolor->e_color_name,
        
                );
            }          
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getcolor3(){
        $iproductwip = $this->input->post('iproductwip');
        $query = $this->mmaster->getcolor($iproductwip);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_color." >".$row->i_color." - ".$row->e_color_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Warna -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Warna Tidak ada</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        // $iproductwip 	= $this->input->post('iproductwip', TRUE);
        // $xcolor         = $this->input->post('xcolor', TRUE);
        // $jml            = $this->input->post('jml', TRUE); 
        // $ppcancel       = 'f';

        $i_material  = $this->input->post('imaterial[]', TRUE);
        $v_toset     = $this->input->post('vprice[]', TRUE);
        // $v_gelar     = $this->input->post('vgelar[]', TRUE);
        // $v_set       = $this->input->post('vset[]', TRUE);
        // $f_bis       = $this->input->post('fbis[]', TRUE);
       
        $this->db->trans_begin(); 
        

             $no = 0;
            foreach ($i_material as $imaterial) {
                $iproduct   = $imaterial;
                $vprice     = $v_toset[$no];
                // $vgelar     = $v_gelar[$no];
                // $vset       = $v_set[$no];
                // $fbis       = $f_bis[$no];
                // var_dump($iproduct, $vprice);
                // die;
                $this->mmaster->insertdetail($iproduct, $vprice);

                $no++;
            }
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$iproduct);
            // for($i=1;$i<=$jml;$i++){

            // }
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
                    'kode'      => $iproduct,
                );
        }
    $this->load->view('pesan', $data);      
    }

    
    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iproduct = $this->uri->segment('4');
        $vprice   = $this->uri->segment('5');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_data($iproduct, $vprice)->row(),
            // 'data2'      => $this->mmaster->cek_datadet($iproduct, $vprice)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $iproduct	    = $this->input->post('iproduct', TRUE);
        $vprice         = $this->input->post('vprice', TRUE);

        $this->db->trans_begin(); 
        

        if ($iproduct != ""){
            $this->mmaster->updatedetail($iproduct, $vprice);

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $iproduct
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

        $iproduct = $this->uri->segment('4');
        $vprice = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($iproduct, $vprice)->row(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

        public function approve(){

            $data = check_role($this->i_menu, 3);
            if(!$data){
                redirect(base_url(),'refresh');
            }
    
            $ipp = $this->uri->segment('4');
    
            $data = array(
                'folder' => $this->global['folder'],
                'title' => "Edit ".$this->global['title'],
                'title_list' => 'List '.$this->global['title'],
                'data' => $this->mmaster->cek_data($ipp)->row(),
                'data2' => $this->mmaster->cek_datadet($ipp)->result(),
            );
    
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
        }
        public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ipp 			= $this->input->post('ipp', TRUE);
        $ikodemaster 			= $this->input->post('ikodemaster', TRUE);
        $query 	= $this->db->query("SELECT current_timestamp as c");
	   		$row   	= $query->row();
            $now	  = $row->c;
            $this->db->trans_begin(); 
        $this->mmaster->approve($ipp, $now);
        if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
               $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ipp
                );
            }
            $this->load->view('pesan', $data);  
        }

        public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $i_pp        = $this->input->post('i_pp', TRUE);
        
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($i_pp);
        if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Cancel Permintaan Pembelian '.$i_pp);
                echo json_encode($data);
            }
        }
    }
/* End of file Cform.php */
