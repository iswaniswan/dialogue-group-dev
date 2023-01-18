<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020101';

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
        $iarea = $this->input->post('iarea');
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'area'=> $this->mmaster->bacaarea(),
            'customer'=> $this->mmaster->bacapelanggan($iarea),
            'nota'=> $this->mmaster->bacanota(),
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
    public function getcust(){
        $iarea = $this->input->post('iarea');
        $query = $this->mmaster->getcustomer($iarea);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_customer." >".$row->i_customer." - ".$row->e_customer_name."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Pelanggan -- ".$c."</option>";
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
    public function getfaktur(){
        $icustomer = $this->input->post('icustomer');
        $query = $this->mmaster->getfaktur($icustomer);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_nota." >".$row->i_nota."</option>";
            }
            $kop  = "<option value=\"\"> -- Pilih Pelanggan -- ".$c."</option>";
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
        $iproduct = $this->input->post('i_product');
        $this->db->select("i_product, e_product_name, v_product_mill");
            $this->db->from("tr_product");
            $this->db->where("UPPER(i_product)", $iproduct);
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
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select("i_product, e_product_name");
            $this->db->from("tr_product");
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
         $ecustomername   = $this->input->post('ecustomername', TRUE);
         $iarea           = $this->input->post('iarea', TRUE);
         $eareaname       = $this->input->post('eareaname', TRUE);
          $isalesman       = $this->input->post('isalesman',TRUE);
         $ipricegroup     = $this->input->post('ipricegroup',TRUE);
         $inota           = $this->input->post('inota',TRUE);
         $esalesmanname   = $this->input->post('esalesmanname',TRUE);
         $nttbdiscount1   = $this->input->post('nttbdiscount1',TRUE);
         $nttbdiscount2   = $this->input->post('nttbdiscount2',TRUE);
         $nttbdiscount3   = $this->input->post('nttbdiscount3',TRUE);
         $vttbdiscount1   = $this->input->post('vttbdiscount1',TRUE);
         $vttbdiscount1   = str_replace(',','',$vttbdiscount1);
         $vttbdiscount2   = $this->input->post('vttbdiscount2',TRUE);
         $vttbdiscount2   = str_replace(',','',$vttbdiscount2);
         $vttbdiscount3   = $this->input->post('vttbdiscount3',TRUE);
         $vttbdiscount3   = str_replace(',','',$vttbdiscount3);
         $vttbdiscounttotal   = $this->input->post('vttbdiscounttotal',TRUE);
         $vttbdiscounttotal   = str_replace(',','',$vttbdiscounttotal);
         $vttbnetto        = $this->input->post('vttbnetto',TRUE);
         $vttbnetto        = str_replace(',','',$vttbnetto);
         $vttbgross        = $this->input->post('vttbgross',TRUE);
         $vttbgross        = str_replace(',','',$vttbgross);
        //  $fttbplusppn      = $this->input->post('fttbplusppn',TRUE);
        //  $fttbplusdiscount = $this->input->post('fttbplusdiscount',TRUE);
         $fttbplusppn      = 'f';
         $fttbplusdiscount = 'f';
         $jml              = $this->input->post('jml', TRUE);
         $ialasanretur     = $this->input->post('ialasanretur', TRUE);
         $ittb             = $this->input->post('ittb', TRUE);
         $dttb             = $this->input->post('dttb', TRUE);
         $fttbcancel       ='f';

         if($dttb!=''){
            $tmp=explode("-",$dttb);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dttb=$th."-".$bl."-".$hr;
            $tahun   = $th;
         }
         $dreceive1  = $this->input->post('dreceive1', TRUE);
         if($dreceive1!=''){
            $tmp=explode("-",$dreceive1);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $dreceive1=$th."-".$bl."-".$hr;
         }else{
        $dreceive1=null;
      }
      $ettbremark    = $this->input->post('eremark', TRUE);
         if($ettbremark=='')
            $ettbremark=null;
         $ecustomerpkpnpwp    = $this->input->post('ecustomerpkpnpwp', TRUE);
         if($ecustomerpkpnpwp==''){
            $fttbpkp = 'f';
         }else{
            $fttbpkp = 't';
         $fttbcancel='f';
         }
         if(($dttb!='') && ($ittb!='') && ($iarea!='') && ($icustomer!='') && ($ialasanretur!='')){
            $this->db->trans_begin();
            $dbbm                = $dttb;
            $istore               = 'AA';
            $istorelocation     = '01';
            $istorelocationbin= '00';
            $eremark       = 'TTB Retur';
            $ibbktype         = '01';
            $ibbmtype         = '05';
            $this->mmaster->insertheader( $iarea,$ittb,$dttb,$icustomer,
                                    $isalesman,
                                    $nttbdiscount1,$nttbdiscount2,
                                    $nttbdiscount3,$vttbdiscount1,$vttbdiscount2,$vttbdiscount3,$fttbpkp,$fttbplusppn,
                                    $fttbplusdiscount,$vttbgross,$vttbdiscounttotal,$vttbnetto,$ettbremark,$fttbcancel,
                                    $dreceive1,$tahun,$ialasanretur,$ipricegroup,$inota);
            for($i=1;$i<=$jml;$i++){
              $iproduct             = $this->input->post('iproduct'.$i, TRUE);
              $iproductgrade        = 'A';
              $iproductmotif        = '00';
              $eproductname            = $this->input->post('eproductname'.$i, TRUE);
              $vunitprice           = $this->input->post('vunitprice'.$i, TRUE);
              $vunitprice           = str_replace(',','',$vunitprice);
              $ndeliver             = $this->input->post('ndeliver'.$i, TRUE);
              $nquantity            = $this->input->post('nquantity'.$i, TRUE);
              $ettbremark           = $this->input->post('eremark'.$i, TRUE);
#             $inota             = $this->input->post('inota'.$i, TRUE);
#             $dnota             = $this->input->post('dnota'.$i, TRUE);
              if($ettbremark=='')
               $ettbremark=null;
              if($nquantity>0){
                 $this->mmaster->insertdetail(  $iarea,$ittb,$dttb,$iproduct,$iproductgrade,$iproductmotif,
                                       $nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver,$i);
#                $this->mmaster->insertdetail(  $iarea,$ittb,$dttb,$inota,$dnota,$iproduct,$iproductgrade,$iproductmotif,
#                                      $nquantity,$vunitprice,$ettbremark,$tahun,$ndeliver);
//               $this->mmaster->insertbbmdetail(  $iproduct,$iproductgrade,$eproductname,$iproductmotif,$nquantity,
//                                        $vunitprice,$ittb,$ibbm,$eremark,$dttb);
              }
            }
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $data = array(
                'sukses' => false
                );
            }else{
               $this->db->trans_commit();

               $pesan='Input TTB Retur Area '.$iarea.' No:'.$ittb;
               //$this->load->model('logger');
            //    $this->logger->write($id, $ip_address, $now , $pesan );

#              $this->db->trans_rollback();
                    $data = array(
                'sukses'    => true,
                'kode'      => $iarea.$ittb
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
