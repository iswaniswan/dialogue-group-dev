<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20714';

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

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'], 
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $username    = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');
        echo $this->mmaster->data($this->i_menu, $username, $idcompany, $idepartemen, $ilevel);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => ' List '.$this->global['title'],            
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function bagian(){
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');
        $username   = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("public.tr_departement");
            $this->db->where("i_departement", "$idepart");
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id'   => $iproduct->i_departement,  
                    'text' => $iproduct->e_departement_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function tujuan(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("* FROM tm_sub_bagian
                            WHERE i_sub_bagian not in('SDP0008')
                            AND i_kode ='01'
                            ORDER BY i_sub_bagian");
            $data = $this->db->get();
            foreach($data->result() as  $iproduct){
                    $filter[] = array(
                    'id'   => $iproduct->i_sub_bagian,  
                    'text' => $iproduct->e_sub_bagian
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function alasanretur(){
        
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tr_alasan_retur");
            $data = $this->db->get();
            foreach($data->result() as  $ialasanretur){
                    $filter[] = array(
                    'id'   => $ialasanretur->i_alasan_retur,  
                    'text' => $ialasanretur->e_alasan_returname
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getpelanggan(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" i_customer, e_customer_name from tr_customer where f_customer_aktif='t' and e_customer_name like '%$cari%' order by e_customer_name ", false);
            $data = $this->db->get();
            foreach($data->result() as  $ipelanggan){
                    $filter[] = array(
                    'id'   => $ipelanggan->i_customer,  
                    'text' => $ipelanggan->e_customer_name
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getnotapenjualan(){
            $ipelanggan = $this->input->post('ipelanggan');
        
            $query = $this->mmaster->getnotapenjualan($ipelanggan);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_faktur_code." >".$row->i_faktur_code." || ".$row->d_faktur."</option>";
                }
                $kop  = "<option value=\"\"> -- Pilih No Referensi -- ".$c."</option>";
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

    function getdataitem(){
        header("Content-Type: application/json", true);
        $inota        = $this->input->post('inota'); 

        $this->db->select("* from tm_ttbretur_item a where a.i_nota = '$inota'");
        $data = $this->db->get();

        $query   = $this->mmaster->getdataitem($inota);

        $dataa = array(
            'data'       => $data->result_array(),
            'jmlitem'    => $query->num_rows(),
            'dataheader' => $this->mmaster->getdataheader($inota)->row(),
            'dataitem'   => $this->mmaster->getdataitem($inota)->result_array(),
        );
        // var_dump($this->mmaster->getdataheader($inota)->row());
        // die();
        echo json_encode($dataa);
    }


/*    function dataproduct(){
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

    public function datamaterial(){
        $filter = [];
        $iproduct = $this->uri->segment(4);
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $data = $this->db->query("select a.i_product, a.i_material, b.e_material_name, a.i_color
                                        from tr_polacutting a 
                                        join tr_material b on a.i_material = b.i_material
                                        where (a.i_material like '%$cari%' or b.e_material_name like '%$cari%') 
                                        order by a.i_material
                                        ");
            foreach ($data->result() as $material) {
                $filter[] = array(
                    'id'   => $material->i_material,
                    'name' => $material->e_material_name,
                    'text' => $material->i_material.' - '.$material->e_material_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getmaterial(){
        header("Content-Type: application/json", true);
        $ematerial = $this->input->post('ematerial');
  
        $this->db->select("distinct(a.i_material), b.e_material_name, a.i_color, c.e_color_name, b.i_satuan_code, g.e_satuan
                         from tr_polacutting a 
                                        join tr_material b on a.i_material = b.i_material
                                        join tr_color c on a.i_color = c.i_color
                                        join tr_satuan g on b.i_satuan_code= g.i_satuan_code
                                        where a.i_material = '$ematerial'
                                        order by a.i_material");
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }*/

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
          $ikodebagian  = $this->input->post('ikodebagian', TRUE);
          $dttb        = $this->input->post('dttb', TRUE);
          if($dttb){
                 $tmp   = explode('-', $dttb);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $thbl = $year.$month;
                 $datettb = $year.'-'.$month.'-'.$day;
          }

          $ipelanggan            = $this->input->post('ipelanggan', TRUE);
          $inota                 = $this->input->post('i_nota', TRUE);
          $eremark               = $this->input->post('eremark', TRUE);
          $jml                   = $this->input->post('jml', TRUE);
          $ialasanretur          = $this->input->post('ialasanretur', TRUE);
          $noreturpelanggan      = $this->input->post('noreturpelanggan', TRUE);
          $disc                  = $this->input->post('discount',TRUE);
          $pajak                 = $this->input->post('ipajak',TRUE);
          $bruto                 = str_replace(',','',$this->input->post('vspb',TRUE));
          $netto                 = str_replace(',','',$this->input->post('vspbbersih',TRUE));
          $vdisc                 = str_replace(',','',$this->input->post('vspbdiscounttotal',TRUE));
          $dpp                   = str_replace(',','',$this->input->post('dpp',TRUE));
          $ppn                   = str_replace(',','',$this->input->post('ppn',TRUE));
            $i_product           = $this->input->post('iproduct[]',TRUE);
            $n_quantityfaktur    = $this->input->post('nquantityfaktur[]',TRUE);
            $n_quantityretur     = $this->input->post('nquantity[]',TRUE);
            $e_desc              = $this->input->post('edesc[]',TRUE);
            $v_total             = str_replace(',','',$this->input->post('total[]',TRUE));
            $unit_price          = $this->input->post('vprice[]',TRUE);
        // var_dump($i_product, $n_quantityfaktur, $n_quantityretur, $e_desc, $v_total, $unit_price);
        // die;
        $lok      = $this->session->userdata('i_lokasi');
        $ittb     = $this->mmaster->runningnumber($thbl, $lok);
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ittb);
        $this->mmaster->insertheader($ikodebagian, $ittb, $inota, $datettb, $ipelanggan, $ialasanretur, $noreturpelanggan, $disc, $pajak, 
        $bruto, $netto, $vdisc, $dpp, $ppn);

        $no=0;
          for($i=1;$i<=$jml;$i++){
                
                $iproduct       = $i_product[$no];
                $nquantityfaktur= $n_quantityfaktur[$no];
                $nquantityretur = $n_quantityretur[$no];
                $edesc          = $e_desc[$no];
                $vtotal         = $v_total[$no];
                $unitprice      = $unit_price[$no];
                $this->mmaster->insertdetail($ittb, $inota, $datettb, $iproduct, $nquantityfaktur, $nquantityretur, $edesc, $vtotal, $unitprice, $disc); 
                $no++;
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
                    'kode'      => $ittb,
                );
        }
    $this->load->view('pesan', $data);  
    }    

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ittb            = $this->uri->segment('4');
        $ipelanggan      = $this->uri->segment('5');
        $ibagian         = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],            
            'data'       => $this->mmaster->cek_data($ittb)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($ittb)->result(),
            'kodebagian' => $this->mmaster->cek_bagian($ibagian)->result(),
            'getpelanggan'  => $this->mmaster->cek_pelanggan($ipelanggan)->result(),
            'getnota'    => $this->mmaster->cek_nota($ittb)->result(),
            'getalasan'  => $this->mmaster->cek_alasanretur($ittb)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function approval(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ittb            = $this->uri->segment('4');
        $ipelanggan      = $this->uri->segment('5');
        $ibagian         = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],            
            'data'       => $this->mmaster->cek_data($ittb)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($ittb)->result(),
            'kodebagian' => $this->mmaster->cek_bagian($ibagian)->result(),
            'getpelanggan'  => $this->mmaster->cek_pelanggan($ipelanggan)->result(),
            'getnota'    => $this->mmaster->cek_nota($ittb)->result(),
            'getalasan'  => $this->mmaster->cek_alasanretur($ittb)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iloc      = $this->session->userdata('i_lokasi');
        // var_dump($iloc);
        // die();

          $ittb        = $this->input->post('ittb', TRUE);
          $ikodebagian  = $this->input->post('ikodebagian', TRUE);
          $dttb        = $this->input->post('dttb', TRUE);
          if($dttb){
                 $tmp   = explode('-', $dttb);
                 $day   = $tmp[2];
                 $month = $tmp[1];
                 $year  = $tmp[0];
                 $yearmonth = $year.$month;
                 $datettb = $year.'-'.$month.'-'.$day;
          }

          $ipelanggan        = $this->input->post('ipelanggan', TRUE);
          $inota        = $this->input->post('i_nota', TRUE);
          $ialasanretur      = $this->input->post('ialasanretur', TRUE);
          $noreturpelanggan  = $this->input->post('noreturpelanggan', TRUE);
          $jml          = $this->input->post('jml', TRUE);
         

            /*$i_product       = $this->input->post('iproduct[]',TRUE);
            $i_colorpro      = $this->input->post('icolorpro[]',TRUE);
            $i_material      = $this->input->post('imaterial[]',TRUE);
            $i_colorma       = $this->input->post('icolorma[]',TRUE);
            $n_quantitypro   = $this->input->post('nquantitypro[]',TRUE);
            $n_quantityma    = $this->input->post('nquantityma[]',TRUE);
            $n_quantitymasuk = $this->input->post('nquantitymasuk[]',TRUE);
            $e_desc          = $this->input->post('edesc[]',TRUE);
            $i_satuan        = $this->input->post('isatuan[]',TRUE);*/
        $this->db->trans_begin();
        $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ittb);
        $this->mmaster->updateheader($ittb, $ikodebagian, $datettb, $ipelanggan, $inota, $noreturpelanggan, $ialasanretur);  
        $this->mmaster->deletedetail($ittb);

            for($i=1;$i<=$jml;$i++){
                $iproduct       = $this->input->post('iproduct'.$i, TRUE);
                $nquantityfaktur      = $this->input->post('nquantityfaktur'.$i, TRUE);
                $nquantityretur      = $this->input->post('nquantityretur'.$i, TRUE);
                $edesc            = $this->input->post('edesc'.$i, TRUE);
                //$noitem          = $i;

                if(($nquantityretur == 0)||($nquantityretur == '')){
                exit;
                }else{
                    //$this->mmaster->insertdetail($ittb, $iproduct, $icolorpro, $imaterial, $icolorma, $nquantitymasuk, $edesc, $noitem); 
                    $this->mmaster->insertdetail($ittb, $inota, $datettb, $iproduct, $nquantityfaktur, $nquantityretur, $edesc);
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
                    'kode'      => $ittb,
                );
        }
    $this->load->view('pesan', $data);  
    }
    
    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->change($kode);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->reject($kode);
    }

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $ittb   = $this->input->post('ittb');
        
        $this->db->trans_begin();
        $this->mmaster->approve($ittb);
        
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $ittb,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function view(){

        $ittb           = $this->uri->segment('4');
        $ipelanggan     = $this->uri->segment('5');
        $ibagian        = $this->uri->segment('6');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],            
            'data'       => $this->mmaster->cek_data($ittb)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($ittb, $ipelanggan)->result(),
            'kodebagian' => $this->mmaster->cek_bagian($ibagian)->result(),
            'getpelanggan'  => $this->mmaster->cek_pelanggan($ipelanggan)->result(),
            'getnota'    => $this->mmaster->cek_nota($ittb)->result(),
            'getalasan'  => $this->mmaster->cek_alasanretur($ittb)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ittb   = $this->input->post('ittb');

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ittb);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel TTB Retur '.$ittb);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */