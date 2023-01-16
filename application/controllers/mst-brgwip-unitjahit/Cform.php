<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010701';

    public function __construct()
    {
        
        parent::__construct();
        cek_session();
        $this->load->library('fungsi');
        
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
		echo $this->mmaster->data($this->i_menu);
    }
    public function getjenis(){
        $ikode2 = $this->input->post('ikode2');
        $query = $this->mmaster->getjenis($ikode2);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->kode." >".$row->kode."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Jenis Barang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Jenis Barang Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }
    public function getid(){
        $ikode2 = $this->input->post('ikodeunit');
        $query = $this->mmaster->get_kodeunit($ikode2);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->id." >".$row->id."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Jenis Barang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Jenis Barang Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }
    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment('4');
        $iunitjahit = $this->uri->segment('5');
        
        // echo $id;
        // die;
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($id)->row(),
            'data2' => $this->mmaster->cek_data2($id,$iunitjahit)->result()
        );

        // $data['isupplier']          = $this->mmaster->bacasupplier();
        // $data['iproductgroup']      = $this->mmaster->bacaproductgroup();
        // $data['iproductclass']      = $this->mmaster->bacaproductclass();
        // $data['iproductstatus']     = $this->mmaster->bacaproductstatus();
        // $data['iproducttype']       = $this->mmaster->bacaproducttype();
        // $data['iproductcategory']   = $this->mmaster->bacaproductcategory();

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
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
            'kodeunit'     => $this->mmaster->get_kodeunit()->result(),
            'kode'     => $this->mmaster->get_kode()->result(),
            'kode2'     => $this->mmaster->get_jenis()->result()
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    function getproductname(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproduct');
        $this->db->select("id, kode_brg, nama_brg");
            $this->db->from("tm_barang_wip");
            $this->db->where("UPPER(kode_brg)", $iproduct);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }
    function databrg(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("id, kode_brg, nama_brg");
            $this->db->from("tm_barang_wip");
            $this->db->like("UPPER(kode_brg)", $cari);
            $this->db->or_like("UPPER(nama_brg)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->kode_brg,  
                    'text' => $product->kode_brg//.' - '.$product->e_product_basename
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $kodeunit       = explode("||",$this->input->post('ikodeunit', TRUE));
        $ikodeunit       = $kodeunit[0];
        $ikode   = $this->input->post('ikode', TRUE);
        $ikode2           = $this->input->post('ikode2', TRUE);
        // $idunitjahit           = '1';
         $idunitjahit           = $this->input->post('idunitjahit', TRUE);
        $jml              = $this->input->post('jml', TRUE);
        
        if(($ikodeunit!='') && ($ikode!='') && ($ikode2!='')){
            
            $this->db->trans_begin();
        for($i=1;$i<=$jml;$i++){
            $iproduct       = $this->input->post('iproduct'.$i, TRUE);
            $eproductbasename   = $this->input->post('eproductbasename'.$i, TRUE);
            $harga           = $this->input->post('harga'.$i, TRUE);
            $idprod           = $this->input->post('idprod'.$i, TRUE);
            $this->mmaster->insertheader($ikodeunit,$ikode,$ikode2,$idunitjahit,$iproduct,$harga,$idprod);
                            // insertheader($ikodeunit,$ikode,$ikode2,$idunitjahit,$iproduct,$harga,$idprod)
            //$this->mmaster->insertdetail($iproduct,$eproductbasename,$harga);

        }
        if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $data = array(
                'sukses' => false
                );
            }else{
               $this->db->trans_commit();

               //$pesan='Input TTB Retur Area '.$iarea.' No:'.$ittb;
               //$this->load->model('logger');
            //    $this->logger->write($id, $ip_address, $now , $pesan );

#              $this->db->trans_rollback();
                    $data = array(
                'sukses'    => true,
                'kode'      => $ikodeunit
                    );
            //    $data['sukses']=true;
            //    $data['inomor']   = $iarea.$ittb;
            //    $this->load->view('nomor',$data);
            }
            $this->load->view('pesan', $data);
            }
        }
        public function update(){

            $data = check_role($this->i_menu, 1);
            if(!$data){
                redirect(base_url(),'refresh');
            }

            
            $kodeunit       = explode("||",$this->input->post('ikodeunit', TRUE));
            $ikodeunit       = $kodeunit[0];
            $ikode   = $this->input->post('ikode', TRUE);
            $ikode2           = $this->input->post('ikode2', TRUE);
            // $idunitjahit           = '1';
             $idunitjahit           = $this->input->post('idunitjahit', TRUE);
            $jml              = $this->input->post('jml', TRUE);
            echo $jml;
            die;
            $this->db->trans_begin();
            if(($ikodeunit!='') && ($ikode!='') && ($ikode2!='')){
                
                
            for($i=1;$i<=$jml;$i++){
                
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $eproductbasename   = $this->input->post('eproductbasename'.$i, TRUE);
                $harga           = $this->input->post('harga'.$i, TRUE);
                $idprod           = $this->input->post('idprod'.$i, TRUE);
                if($jml > 0 ){
                $this->mmaster->delete($ikodeunit,$ikode,$ikode2,$idunitjahit,$iproduct,$harga,$idprod);
                $this->mmaster->insertheader($ikodeunit,$ikode,$ikode2,$idunitjahit,$iproduct,$harga,$idprod);
                }
                // $this->db->query(" select a.id, a.kode_unit_jahit, e.nama, b.nama, a.kode_brg, 
                //         d.nama_brg, a.harga, a.tgl_update 
                //         from  duta_prod.tm_harga_brg_unit_jahit a 
                //         inner join  duta_prod.tm_unit_jahit b on a.kode_unit_jahit = b.kode_unit 
                //         inner join  duta_prod.tm_barang_wip d on a.id_brg = d.id 
                //         inner join  duta_prod.tm_kel_brg_wip e on d.id_kel_brg_wip = e.id 
                //         where kode_unit_jahit = '$ikodeunit'
                //         and a.kode_brg = '$iproduct'", false);
                // $query = $this->db->get();
                //  if ($query->num_rows() > 0){
                //  $tgl = date("Y-m-d");
                // $this->mmaster->updateheader($ikodeunit,$ikode,$ikode2,$idunitjahit,$iproduct,$harga,$idprod);
                //  }else{
                // $tgl = date("Y-m-d");
                // $this->mmaster->insertheader($ikodeunit,$ikode,$ikode2,$idunitjahit,$iproduct,$harga,$idprod);
                // }
            if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    $data = array(
                    'sukses' => false
                    );
                }else{
                   $this->db->trans_commit();
    
                   //$pesan='Input TTB Retur Area '.$iarea.' No:'.$ittb;
                   //$this->load->model('logger');
                //    $this->logger->write($id, $ip_address, $now , $pesan );
    
    #              $this->db->trans_rollback();
                        $data = array(
                    'sukses'    => true,
                    'kode'      => $ikodeunit
                        );
                //    $data['sukses']=true;
                //    $data['inomor']   = $iarea.$ittb;
                //    $this->load->view('nomor',$data);
                }
                $this->load->view('pesan', $data);
                }
            }
        }
    public function view(){

        $id         = $this->uri->segment('4');
        $iunitjahit = $this->uri->segment('5');

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data' => $this->mmaster->cek_data($id)->row(),
            'data2' => $this->mmaster->cek_data2($id,$iunitjahit)->result()
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }


}

/* End of file Cform.php */
