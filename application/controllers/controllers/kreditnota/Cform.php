<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050109';

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
        // $iarea = $this->input->post('iarea');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            // 'area'=> $this->mmaster->bacaarea(),
            'customer'=> $this->mmaster->bacapelanggan(),
            'gudang'=> $this->mmaster->bacagudang(),
            // 'nota'=> $this->mmaster->bacanota(),
            'alasan'=> $this->mmaster->bacaalasan()

            // 'customer'=> $this->mmaster->bacapelanggan(),
            //'nota'=> $this->mmaster->bacapereferensi(),
            //'alasanretur'=> $this->mmaster->bacaalasanretur()
            //'productgrade'=> $this->mmaster->bacagrade()
            //$data['iproductgroup'] = $this->mmaster->bacagroup();
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
        
    }
    public function getfaktur(){
        $ibranch = $this->input->post('i_branch');
        $query = $this->mmaster->getfaktur($ibranch);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_faktur_code." >".$row->i_faktur_code."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Faktur -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Pelanggan</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }
    public function getbranch(){
        $icustomer = $this->input->post('i_customer');
        $query = $this->mmaster->getbranch($icustomer);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                // $c.="<option value=".$row->i_branch." >".$row->i_branch."</option>";
                $c.="<option value=".$row->i_branch." >".$row->i_branch." - ".$row->e_branch_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Cabang -- ".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Pelanggan</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }
    function data(){
        //$data['isi']=$this->mmaster->bacakode();
		echo $this->mmaster->data($this->i_menu);
    }
    function datacust(){
        $filter = [];
        $per    = date('Ym');
        $iarea = $this->input->post('iarea');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('a.*, b.e_customer_name');
            $this->db->from('tr_customer_salesman a');
            $this->db->join('tr_customer b ','a.i_customer = b.i_customer','a.i_area = b.i_area');
            $this->db->where('a.e_periode',$per);
            $this->db->where('a.i_area',$iarea);
            $this->db->like('UPPER(a.i_customer)', $cari);
            $this->db->or_like('UPPER(b.e_customer_name)', $cari);
            $data = $this->db->get();
            foreach($data->result() as  $customer){
                    $filter[] = array(
                    'id' => $customer->i_customer,  
                    'text' => $customer->e_customer_name.' - '.$customer->e_salesman_name
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    function datanota(){
        $filter = [];
        $per    = date('Ym');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select('*');
            $this->db->from('tm_nota');
            //$this->db->where('a.e_periode',$per);
            $this->db->like('UPPER(i_nota)', $cari);
            $data = $this->db->get();
            foreach($data->result() as  $nota){
                    $filter[] = array(
                    'id' => $nota->i_nota,  
                    'text' => $nota->i_nota//.' - '.$nota->i_nota
                );
            }
            
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }
    function getharga(){
        header("Content-Type: application/json", true);
        $inota = $this->uri->segment('4');
        $iproduct = $this->input->post('i_product');
        $this->db->select("a.i_product, a.e_product_name, a.v_unit_price, 
                            a.n_customer_discount1, a.n_customer_discount2, a.n_customer_discount3, 
                            a.n_quantity, a.i_color, b.e_color_name");
            $this->db->from("tm_faktur_do_item_t a");
            $this->db->join("tr_color b","a.i_color = b.i_color");
            $this->db->where("a.i_faktur", $inota);
            $this->db->where("UPPER(a.i_product)", $iproduct);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }
    function getharga2(){
        header("Content-Type: application/json", true);
        $inota = $this->input->post('i_nota');
        $this->db->select("a.*, b.i_salesman, b.e_salesman_name");
            $this->db->from("tm_nota a");
            $this->db->join("tr_salesman b","a.i_salesman = b.i_salesman");
            $this->db->where("UPPER(a.i_nota)", $inota);
            $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function databrg(){
        $filter = [];
        $inota = $this->uri->segment('4');
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_product, e_product_name");
            $this->db->from("tm_faktur_do_item_t");
            $this->db->where("i_faktur", $inota);
            $this->db->like("UPPER(i_product)", $cari);
            $this->db->or_like("UPPER(e_product_name)", $cari);
            $data = $this->db->get();
            foreach($data->result() as  $product){
                    $filter[] = array(
                    'id' => $product->i_product,  
                    'text' => $product->i_product.' - '.$product->e_product_name
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
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }
    public function simpan(){
         $icustomer       = $this->input->post('icustomer', TRUE);
         $ibranch         = $this->input->post('ibranch', TRUE);
         $inota           = $this->input->post('inota', TRUE);
         $vttbdiscounttotal   = $this->input->post('vspbdiscounttotal',TRUE);
         $vttbdiscounttotal   = str_replace(',','',$vttbdiscounttotal);
         $vttbnetto        = $this->input->post('vspbbersih',TRUE);
         $vttbnetto        = str_replace(',','',$vttbnetto);
         $vttbgross        = $this->input->post('vspb',TRUE);
         $vttbgross        = str_replace(',','',$vttbgross);
         $ialasanretur     = $this->input->post('ialasanretur', TRUE);
         $ittb             = $this->input->post('ittb', TRUE);
         $dttb             = $this->input->post('dttb', TRUE);
         $fttbcancel       ='f';
         $ikodelokasi              = $this->input->post('ikodelokasi', TRUE);
         $jml              = $this->input->post('jml', TRUE);

         if($dttb!=''){
            $tmp=explode("-",$dttb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dttb=$th."-".$bl."-".$hr;
            $tahun   = $th;
         }
      $ettbremark    = $this->input->post('eremark', TRUE);
         if($ettbremark=='')
            $ettbremark=null;
         if(($dttb!='') && ($ittb!='') && ($icustomer!='') && ($ialasanretur!='')){
            $this->db->trans_begin();
            // $dbbm                = $dttb;
            // $istore               = 'AA';
            // $istorelocation     = '01';
            // $istorelocationbin= '00';
            // $eremark       = 'TTB Retur';
            // $ibbktype         = '01';
            // $ibbmtype         = '05';
            $this->mmaster->insertheader( $icustomer, $ibranch, $inota, $vttbdiscounttotal,
            $vttbnetto, $vttbgross, $ialasanretur, $ittb, $dttb, $fttbcancel, $ettbremark);
            for($i=1;$i<=$jml;$i++){
              $iproduct             = $this->input->post('iproduct'.$i, TRUE);
              $eproductname           = $this->input->post('eproductname'.$i, TRUE);
              $icolor           = $this->input->post('icolor'.$i, TRUE);
              $vunitprice           = $this->input->post('vproductretail'.$i, TRUE);
              $vunitprice           = str_replace(',','',$vunitprice);
              $ncustomerdiscount1           = $this->input->post('ncustomerdiscount1'.$i, TRUE);
              $ncustomerdiscount1           = str_replace(',','',$ncustomerdiscount1);
              $ncustomerdiscount2           = $this->input->post('ncustomerdiscount2'.$i, TRUE);
              $ncustomerdiscount2           = str_replace(',','',$ncustomerdiscount2);
              $ncustomerdiscount3           = $this->input->post('ncustomerdiscount3'.$i, TRUE);
              $ncustomerdiscount3           = str_replace(',','',$ncustomerdiscount3);
              $nretur               = $this->input->post('ndeliver'.$i, TRUE);
              $nquantity            = $this->input->post('nquantitystock'.$i, TRUE);
              $getstock           = $this->input->post('eremark'.$i, TRUE);
              if($ettbremark=='')
               $ettbremark=null;
               $query2 = $this->mmaster->cekstock($iproduct,$ikodelokasi,$icolor);
                    if ($query2->num_rows() > 0){
                        $hasilrow6 = $query2->row();
                        $n_quantity_stock = $hasilrow6->n_quantity_stock;
                        $total = $n_quantity_stock+$nretur;
                        $this->mmaster->updatestock($iproduct, $total, $ikodelokasi);
                    }else {
                        $this->mmaster->insertstock($iproduct, $ikodelokasi, $eproductname, $nretur, $icolor);
                    }
            //   if($nquantity>0){
                 $this->mmaster->insertdetail($ittb, $dttb, $th, $inota, $iproduct, $eproductname, $icolor, $vunitprice, 
                 $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3, $nretur, $nquantity,$i);
#                $this->mmaster->insertdetail(  $iarea,$ittb,$dttb,$inota,$dnota,$iproduct,$iproductgrade,$iproductmotif,
#                                      $nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver);
//               $this->mmaster->insertbbmdetail(  $iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,
//                                        $vunitprice,$ittb,$ibbm,$eremark,$dttb);
            //   }
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $data = array(
                'sukses' => false
                );
            }else{
               $this->db->trans_commit();

               $pesan='Input TTB Retur Area '.$ibranch.' No:'.$ittb;
               //$this->load->model('logger');
            //    $this->logger->write($id, $ip_address, $now , $pesan );

#              $this->db->trans_rollback();
                    $data = array(
                'sukses'    => true,
                'kode'      => $ibranch.$ittb
                    );
            //    $data['sukses']=true;
            //    $data['inomor']   = $iarea.$ittb;
            //    $this->load->view('nomor',$data);
            }
            $this->load->view('pesan', $data);
         }
    }
}
/* End of file Cform.php */
