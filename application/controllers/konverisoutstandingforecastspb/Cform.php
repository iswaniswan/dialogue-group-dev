<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20707';

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
        echo $this->mmaster->data($this->i_menu);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');
        $username   = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => ' List '.$this->global['title'],            
            'customer'   => $this->mmaster->bacacustomer(),
            'promo'      => $this->mmaster->bacapromo(),
            'bagian'     => $this->mmaster->bacabagian($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getcustaddress(){
        header("Content-Type: application/json", true);
        $icust = $this->input->post('icustomer');
        $query  = array(
            'head' => $this->mmaster->getcustaddress($icust)->row()
        );
        echo json_encode($query);  
    }

    public function datamaterial(){
        $filter = [];
        //$ikodemaster = $this->uri->segment(4);
        // $ijenisbrg = $this->uri->segment(4);
        // $ikategori = $this->uri->segment(5);
        //var_dump($ikodemaster, $ijenisbrg, $ikategori);
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            // $this->db->select("a.*,b.e_satuan");
            // $this->db->from("tr_material a");
            // $this->db->join("tr_satuan b", "a.i_satuan=b.i_satuan");
            // // $this->db->join("tr_master_gudang c","a.i_kode_master=b.i_store");
            // $this->db->where('a.i_store', $ikodemaster);
            // $this->db->where('a.i_type_code', $ijenisbrg);
            // // $this->db->order_by('a.i_material', 'ASC');
            // $data = $this->db->get();
            $where = '';
            //   if($ijenisbrg != 'AJB'){
            //     $where .= "AND a.i_type_code = '$ijenisbrg'";
            //   }if($ikategori != 'KTG'){
            //     $where .= "AND a.i_kode_kelompok = '$ikategori'";
            //   }

            // $data = $this->db->query("sselect a.*,b.e_satuan from tr_material a, tr_satuan b where a.i_satuan_code=b.i_satuan_code  $where and (a.i_material like '%$cari%' or a.e_material_name like '%$cari%') order by a.i_material");
            $data = $this->db->query("select a.i_product_motif , a.e_product_basename , a.v_unitprice from tr_product_base a where a.i_product_motif like '%$cari%' or a.e_product_basename like '%$cari%' order by a.i_product_motif ");

            //and (a.i_material like '%$cari%' or a.e_material_name like '%$cari%')
            //and a.i_kode_master = '$ikodemaster'
            foreach ($data->result() as $product) {
                $filter[] = array(
                    'id'   => $product->i_product_motif,
                    'name' => $product->e_product_basename,
                    'text' => $product->i_product_motif.' - '.$product->e_product_basename,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function approve(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');

        $ispb          = $this->uri->segment('4');
        $iasal         = $this->uri->segment('5');

        // $sj       = $this->uri->segment('4');
        // $ipartner = $this->uri->segment('5');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Approve ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            // 'kodemaster'    => $this->mmaster->bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            // 'partner'       => $this->mmaster->getpartner()->result(),
            // 'typemakloon'   => $this->mmaster->gettypemakloon($ipartner)->result(),
            // 'departement'   => $idepart,
            // 'head'          => $this->mmaster->baca_header($sj)->row(),
            // 'detail'        => $this->mmaster->baca_detail($sj)->result(),

            'data'       => $this->mmaster->cek_data($ispb)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($ispb, $iasal)->result(),
            'bagian'     => $this->mmaster->cek_bagian()->result(),
            'asalkirim'  => $this->mmaster->cek_dept()->result(),
            'referensi'  => $this->mmaster->cek_referensi()->result(),
            'customer'   => $this->mmaster->bacacustomer(),
            'promo'      => $this->mmaster->bacapromo(),
            'bagian'     => $this->mmaster->bacabagian($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
            );
    
            $this->Logger->write('Membuka Menu Approve '.$this->global['title']);
    
            $this->load->view($this->global['folder'].'/vformapprove', $data);
    }

    public function get_outstanding(){
        header("Content-Type: application/json", true);
        $icustomer  = $this->input->post('icustomer', FALSE);
        $ipromo  = $this->input->post('ipromo', FALSE);
        $dspbtemp = $this->input->post('dspb', FALSE);
        if ($dspbtemp) {
            $tmp    = explode('-', $dspbtemp);
            $day    = $tmp[0];
            $month  = $tmp[1];
            $year   = $tmp[2];
            $yearmonth = $year . $month;
            $dspb = $year . '-' . $month . '-' . $day;
        }
        $query  = array(
            // 'head'   => $this->mmaster->getsjkm($isjkm, $gudang)->row(),
            'head'   => $this->mmaster->get_outstanding_head($icustomer, $dspb, $ipromo)->row(),
            'detail' => $this->mmaster->get_outstanding_detail($icustomer, $dspb, $ipromo)->result_array()
        );
        echo json_encode($query);  
    }

    public function approve2(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb = $this->input->post('ispb');
        
        $this->mmaster->approve($ispb);
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                    'sukses'    => false
                    
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispb
                );
            }
            $this->load->view('pesan', $data);  
    }

    public function change(){
        header("Content-Type: application/json", true);
        $ispb = $this->input->post('ispb');
        $this->mmaster->change_approve($ispb);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $ispb = $this->input->post('ispb');
        $this->mmaster->reject_approve($ispb);
    }



    // public function getmateriall(){
    //     header("Content-Type: application/json", true);
    //     $iproduct = $this->input->post('iproductwip');
    //     // $gudang  = $this->input->post('gudang', FALSE);
    //     $query  = array(
    //         'head' => $this->mmaster->gethead($iproduct)->row(),
    //         'detail' => $this->mmaster->getdetail($iproduct)->result_array()
    //     );
    //     //var_dump($query);
    //     echo json_encode($query);  
    // }

    public function getmaterial(){
        header("Content-Type: application/json", true);
        $eproductname = $this->input->post('eproductname');
        $ipromo = $this->input->post('ipromo');
        $dspb  = $this->input->post('dspb');
        // $this->db->select("a.*,b.i_satuan, b.e_satuan");
        // $this->db->from("tr_material a");
        // $this->db->join("tr_satuan b", "a.i_satuan_code=b.i_satuan_code");
        // $this->db->where("UPPER(i_material)", $ematerialname);
        // $this->db->order_by('a.i_material', 'ASC');

        $this->db->select("a.i_product_motif , a.e_product_basename , a.v_unitprice , coalesce (b.n_disc,0) as n_disc ");
        $this->db->from("tr_product_base a");
        //$this->db->join("tr_satuan b", "a.i_satuan_code=b.i_satuan_code");
        $this->db->join("(select b.n_disc , b.i_product_base  from tm_promo a
                        inner join tm_promo_item b on b.i_promo = a.i_promo 
                        where a.i_promo = '$ipromo' and '$dspb' >=to_char(a.d_from,'dd-mm-yyyy')  and '$dspb'<=to_char(a.d_to,'dd-mm-yyyy') and b.i_product_base = '$eproductname') b ", "b on b.i_product_base = a.i_product_motif", "left");
        $this->db->where("UPPER(a.i_product_motif)", $eproductname);
        $this->db->order_by('a.i_product_motif', 'ASC');
        
        // select a.i_product_motif , a.e_product_basename , a.v_unitprice , coalesce(b.n_disc,0) as n_disc from tr_product_base a
        // left join (select b.n_disc from tm_promo a
        // inner join tm_promo_item b on b.i_promo = a.i_promo 
        // where a.i_promo = '' and '' >=a.d_from  and ''<=a.d_to and b.i_product_base = '') b on b.i_product_base = a.i_product_motif
        // order by a.i_product_motif
        
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }





    function bagian(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("*");
            $this->db->from("tm_sub_bagian");
            $this->db->where("i_sub_bagian", "SDP0010");
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

    function asalkirim(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" a.i_sub_bagian, a.e_sub_bagian as nama
                                FROM tm_sub_bagian a
                                WHERE i_sub_bagian='SDP0009' ", false);
            $data = $this->db->get();
            foreach($data->result() as  $iasalkirim){
                    $filter[] = array(
                    'id'   => $iasalkirim->i_sub_bagian,  
                    'text' => $iasalkirim->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getreferensi(){
            $iasal = $this->input->post('iasal');
            $query = $this->mmaster->getreferensi($iasal);
            if($query->num_rows()>0) {
                $c  = "";
                $spb = $query->result();
                foreach($spb as $row) {
                    $c.="<option value=".$row->i_bonk." >".$row->i_bonk." || ".$row->d_bonk."</option>";
                }
                $kop  = "<option value=\"\">Pilih No Referensi".$c."</option>";
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
        $ireff        = $this->input->post('ireff');

        $this->db->select("* from tm_bonmkeluar_qc_detail a where a.i_bonk = '$ireff'");
        $data = $this->db->get();

        $query   = $this->mmaster->getdataitem($ireff);
        $jmlitem = $query->num_rows();
        //var_dump($jmlitem);
        $dataa = array(
            'data'       => $data->result_array(),
            'jmlitem'    => $query->num_rows(),
            'dataitem'   => $this->mmaster->getdataitem($ireff)->result_array()
        );
        echo json_encode($dataa);
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
          $ibagian      = $this->input->post('ibagian', TRUE);
          $dspb        = $this->input->post('dspb', TRUE);
          if($dspb){
                 $tmp   = explode('-', $dspb);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datespb = $year.'-'.$month.'-'.$day;
          }

          $dbatas        = $this->input->post('dbatas', TRUE);
          if($dbatas){
                 $tmp   = explode('-', $dbatas);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datebatas = $year.'-'.$month.'-'.$day;
          }

        //   $iasal        = $this->input->post('iasal', TRUE);
        //   $ireff        = $this->input->post('ireff', TRUE);
        //   $eremark      = $this->input->post('eremark', TRUE);
            $icustomer    = $this->input->post('icustomer', TRUE);
            $iporeff      = $this->input->post('iporeff', TRUE);
            $ipromo       = $this->input->post('ipromo', TRUE);
            $vgross       = $this->input->post('vgross', TRUE);
            $ndiskon      = $this->input->post('ndiskon', TRUE);
            $vdiskon      = $this->input->post('vdiskon', TRUE);
            $vnetto       = $this->input->post('vnetto', TRUE);
            $eremark      = $this->input->post('eremark', TRUE);
            $jml          = $this->input->post('jml', TRUE);
            // $ispb         = $this->mmaster->runningnumber($ibagian,$yearmonth);
            $lok            = $this->session->userdata('i_lokasi');
            $ispb         = $this->mmaster->runningnumber($lok,$yearmonth);

            $i_product       = $this->input->post('iproduct[]',TRUE);
            $v_harga         = $this->input->post('vharga[]',TRUE);           
            $n_quantity      = $this->input->post('nquantity[]',TRUE);
            $n_disc          = $this->input->post('ndisc[]',TRUE);
            $n_quantitymasuk = $this->input->post('nquantitymasuk[]',TRUE);
            $e_desc          = $this->input->post('edesc[]',TRUE);

        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ispb);
        // $this->mmaster->insertheader($ibonm, $ibagian, $datebonm, $iasal, $ireff, $eremark);  
        $this->mmaster->insertheader($ispb, $datespb, $datebatas, $icustomer, $iporeff, $ipromo, $vgross, $ndiskon, $vdiskon, $vnetto, $eremark);  

        $no=0;
          //for($i=1;$i<=$jml;$i++){
          foreach ($i_product as $iproduct) {     
                $iproduct       = $iproduct;
                $vharga         = $v_harga[$no];
                $nquantity      = $n_quantity[$no];
                $ndisc          = $n_disc[$no];
                $edesc          = $e_desc[$no];

                $this->mmaster->insertdetail($ispb, $iproduct, $vharga, $nquantity, $ndisc, $edesc, $no); 
                // $this->mmaster->updatereceive($ireff, $datebonm);

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
                    'kode'      => $ispb,
                );
        }
    $this->load->view('pesan', $data);  
    }    

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        // $ibonm         = $this->uri->segment('4');
        // $iasal         = $this->uri->segment('5');
        $ispb          = $this->uri->segment('4');
        $iasal         = $this->uri->segment('5');

        $ilevel     = $this->session->userdata('i_level');
        $idepart    = $this->session->userdata('i_departement');
        $lokasi     = $this->session->userdata('i_lokasi');
        $username   = $this->session->userdata('username');
        $idcompany   = $this->session->userdata('id_company');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],            
            // 'data'       => $this->mmaster->cek_data($ibonm)->row(),
            // 'datadetail' => $this->mmaster->cek_datadetail($ibonm, $iasal)->result(),
            'data'       => $this->mmaster->cek_data($ispb)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($ispb, $iasal)->result(),
            'bagian'     => $this->mmaster->cek_bagian()->result(),
            'asalkirim'  => $this->mmaster->cek_dept()->result(),
            'referensi'  => $this->mmaster->cek_referensi()->result(),
            'customer'   => $this->mmaster->bacacustomer(),
            'promo'      => $this->mmaster->bacapromo(),
            'bagian'     => $this->mmaster->bacabagian($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function sendd(){
        header("Content-Type: application/json", true);
        $ispb = $this->input->post('ispb');
        $this->mmaster->sendd($ispb);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

          $ispb        = $this->input->post('ispb', TRUE);
          $dspb        = $this->input->post('dspb', TRUE);
$ibagian      = $this->input->post('ibagian', TRUE);
          if($dspb){
                 $tmp   = explode('-', $dspb);
                 $day   = $tmp[0];
                 $month = $tmp[1];
                 $year  = $tmp[2];
                 $yearmonth = $year.$month;
                 $datespb = $year.'-'.$month.'-'.$day;
          }

          $dbatas        = $this->input->post('dbatas', TRUE);
          if($dbatas){
            $tmp   = explode('-', $dspb);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $yearmonth = $year.$month;
            $datebatas = $year.'-'.$month.'-'.$day;
        }

        //   $iasal        = $this->input->post('iasal', TRUE);
        //   $ireff        = $this->input->post('ireff', TRUE);
            $icustomer    = $this->input->post('icustomer', TRUE);
            $iporeff      = $this->input->post('iporeff', TRUE);
            $ipromo       = $this->input->post('ipromo', TRUE);
            $vgross       = $this->input->post('vgross', TRUE);
            $vdiskon      = $this->input->post('vdiskon', TRUE);
            $vnetto       = $this->input->post('vnetto', TRUE);
            $eremark      = $this->input->post('eremark', TRUE);
            $jml          = $this->input->post('jml', TRUE);
         

            $i_product       = $this->input->post('iproduct[]',TRUE);
            // $i_colorpro      = $this->input->post('icolorpro[]',TRUE);
            // $n_quantitypro   = $this->input->post('nquantitypro[]',TRUE);
            // $n_quantitymasuk = $this->input->post('nquantitymasuk[]',TRUE);
            $v_harga      = $this->input->post('vharga[]',TRUE);
            $n_quantity   = $this->input->post('nquantity[]',TRUE);
            $n_disc = $this->input->post('ndisc[]',TRUE);
            $e_desc          = $this->input->post('edesc[]',TRUE);
        $this->db->trans_begin();
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ispb);
        // $this->mmaster->updateheader($ispb, $ibagian, $datespb, $iasal, $ireff, $eremark);  
        $this->mmaster->updateheader($ispb, $datespb, $datebatas, $icustomer, $iporeff, $ipromo, $vgross, $vdiskon, $vnetto, $eremark);
        $this->mmaster->deletedetail($ispb);

        $no=0;
          //for($i=1;$i<=$jml;$i++){
          foreach ($i_product as $iproduct) {     
                $iproduct       = $iproduct;
                // $icolorpro      = $i_colorpro[$no];
                // $nquantitypro   = $n_quantitypro[$no];
                // $nquantitymasuk = $n_quantitymasuk[$no];
                $vharga      = $v_harga[$no];
                $nquantity   = $n_quantity[$no];
                $ndisc       = $n_disc[$no];
                $edesc       = $e_desc[$no];

                // $this->mmaster->insertdetail($ispb, $iproduct, $icolorpro, $nquantitypro, $nquantitymasuk, $edesc, $no); 
                $this->mmaster->insertdetail($ispb, $iproduct, $vharga, $nquantity, $ndisc, $edesc, $no); 

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
                    'kode'      => $ispb,
                );
        }
    $this->load->view('pesan', $data);  
    }   

    public function view(){

        $ispb         = $this->uri->segment('4');
        $iasal         = $this->uri->segment('5');

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],            
            // 'data'       => $this->mmaster->cek_data($ibonm)->row(),
            // 'datadetail' => $this->mmaster->cek_datadetail($ibonm, $iasal)->result(),
            // 'bagian'     => $this->mmaster->cek_bagian()->result(),
            // 'asalkirim'  => $this->mmaster->cek_dept()->result(),
            // 'referensi'  => $this->mmaster->cek_referensi()->result(),

            'data'       => $this->mmaster->cek_data($ispb)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($ispb, $iasal)->result(),
            'bagian'     => $this->mmaster->cek_bagian()->result(),
            'asalkirim'  => $this->mmaster->cek_dept()->result(),
            'referensi'  => $this->mmaster->cek_referensi()->result(),
            'customer'   => $this->mmaster->bacacustomer(),
            'promo'      => $this->mmaster->bacapromo(),
            // 'bagian'     => $this->mmaster->bacabagian($ilevel, $idepart, $lokasi, $username,$idcompany)->result(),
        );
        
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function delete(){
        // //dicoment dulu
        // $data = check_role($this->i_menu, 4);
        // if (!$data) {
        //     redirect(base_url(), 'refresh');
        // }

        $ispb = $this->input->post('ispb', true);

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ispb);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        }else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Bon Masuk Unit Packing ' . $ispb);
            echo json_encode($data);
        }
    }
}
/* End of file Cform.php */