<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10509';

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
            'folder'            => $this->global['folder'],
            'title'             => $this->global['title'],
            'do1'               => '',
            'do2'               => '',
            'do3'               => '',
            'do4'               => '',
            'do5'               => '',
            'do6'               => '',
            'do7'               => '',
            'do8'               => '',
            'do9'               => '',
            'do10'              => '',
            'do11'              => '',
            'do12'              => '',
            'do13'              => '',
            'do14'              => '',
            'do15'              => '',
            'do16'              => '',
            'do17'              => '',
            'do18'              => '',
            'do19'              => '',
            'do20'              => '',
            'ddtap'             => '',
            'eareaname'         => '',
            'iarea'             => '',
            'esuppliername'     => '',
            'isupplier'         => '',
            'dduedate'          => '',
            'esupplieraddress'  => '',
            'esuppliercity'     => '',
            'ipajak'            => '',
            'dpajak'            => '',
            'gross'             => '0',
            'ndisc'             => '0',
            'vdisc'             => '0',
            'fsupplierpkp'      => '',
            'jmlitem'           => '0'
            );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function data_supplier(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" * from tr_supplier where upper(i_supplier) like '%$cari%' 
                                or upper(e_supplier_name) like '%$cari%' ",false);
            $data = $this->db->get();
            foreach($data->result() as  $isupplier){
                    $filter[] = array(
                    'id' => $isupplier->i_supplier,  
                    'text' => $isupplier->i_supplier.'-'.$isupplier->e_supplier_name
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function data_area(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" * from tr_area
                                where (upper(i_area) like '%$cari%' or upper(e_area_name) like '%$cari%')",false);
            $data = $this->db->get();
            foreach($data->result() as  $iarea){
                    $filter[] = array(
                    'id' => $iarea->i_area,  
                    'text' => $iarea->i_area.'-'.$iarea->e_area_name
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getsup(){
        header("Content-Type: application/json", true);
        $isupplier = $this->input->post('i_supplier');
        $this->db->select("*");
        $this->db->from("tr_supplier");
        $this->db->where("UPPER(i_supplier)", $isupplier);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    function datado(){
        $filter = [];
        $isupplier = $this->uri->segment('4');
        $iarea = $this->uri->segment('5');
        $ddtap = $this->uri->segment('6');
        if($this->input->get('q') != '') {
            $filter = [];
            $periode=substr($ddtap,6,4).substr($ddtap,3,2);
            $cari = strtoupper($this->input->get('q'));
            $query=$this->db->query("select a.i_do, a.d_do, a.i_op, b.e_supplier_name, sum(c.n_deliver*c.v_product_mill) as v_do_gross,
                                     b.e_supplier_address, b.e_supplier_city
                                     from tm_do a, tr_supplier b, tm_do_item c
                                     where a.i_supplier=b.i_supplier
                                     and a.i_do not in
                                     (select d.i_do from tm_dtap_item d, tm_dtap e
                                     where to_char(d.d_dtap,'yyyymm')='$periode' and d.i_dtap=e.i_dtap and d.i_area=e.i_area and d.i_supplier=e.i_supplier
                                     and a.i_area=d.i_area and a.i_supplier=d.i_supplier and a.i_op=d.i_op and a.d_do=d.d_do and e.f_dtap_cancel='f')
                                     and a.i_do=c.i_do
                                     and a.f_do_cancel='f'
                                     and a.i_area='$iarea'
                                     and a.i_supplier='$isupplier'
                                     and to_char(a.d_do,'yyyymm')='$periode'
                                     and (upper(a.i_do) like '%$cari%')
                                     group by a.i_do, a.d_do, a.i_op, b.e_supplier_name, b.e_supplier_address, b.e_supplier_city
                                     order by a.i_do",false);
            foreach($query->result() as  $do){
                    $filter[] = array(
                    'id' => $do->i_do,  
                    'text' => $do->i_do
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getdoitem(){
        header("Content-Type: application/json", true);
        $ido = $this->input->post('i_do');
        $isupplier = $this->uri->segment('4');
        $ddtap = $this->uri->segment('5');
        $this->db->select("a.i_do, a.i_op, a.d_do, a.i_op, b.e_supplier_name, c.i_product, c.e_product_name, c.n_deliver, c.v_product_mill, b.f_supplier_pkp");
        $this->db->from("tm_do a");
        $this->db->join("tr_supplier b","a.i_supplier=b.i_supplier");
        $this->db->join("tm_do_item c","a.i_do=c.i_do");
        $this->db->where("a.f_do_cancel='f'");
        $this->db->where("a.d_do <= to_date('$ddtap','dd-mm-yyyy')");
        $this->db->where("a.i_supplier",$isupplier);
        $this->db->where("UPPER(a.i_do)", $ido);
        $data = $this->db->get();
        $query   = $this->db->query("select * from tm_do_item where i_do = '$ido'");
        $dataa = array(
            'data'       => $data->result_array(),
            'jmlitem'    => $query->num_rows(),
            'do'         => $this->mmaster->bacadetaildo($ido,$isupplier,$ddtap)->result_array()

        );
        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $idtap 	= $this->input->post('idtap', TRUE);
		$ddtap 	= $this->input->post('ddtap', TRUE);
        if($ddtap!=''){
            $tmp	=explode("-",$ddtap);
			$th  	= $tmp[2];
			$bl  	= $tmp[1];
			$hr  	= $tmp[0];
			$ddtap= $th."-".$bl."-".$hr;
            $thbl=substr($th,2,2).$bl;
            $iperiode=$th.$bl;
        }
        $iarea				= $this->input->post('iarea', TRUE);
        $eareaname			= $this->input->post('eareaname', TRUE);
        $isupplier			= $this->input->post('isupplier', TRUE);
        $esuppliername		= $this->input->post('esuppliername', TRUE);
        $esuppliercity		= $this->input->post('esuppliercity', TRUE);
        $esupplieraddress	= $this->input->post('esupplieraddress', TRUE);
        $fsupplierpkp 		= $this->input->post('fsupplierpkp', TRUE);
        $dduedate	 				= $this->input->post('dduedate', TRUE);
        if($dduedate!=''){
            $tmp=explode("-",$dduedate);
            $th  = $tmp[2];
            $bl  = $tmp[1];
            $hr  = $tmp[0];
            $dduedate = $th."-".$bl."-".$hr;
        }
        $vgross		= $this->input->post('vgross', TRUE);
		$vgross		= str_replace(',','',$vgross);
		$ndiscount	= $this->input->post('ndiscount', TRUE);
		$ndiscount	= str_replace(',','',$ndiscount);
		$vdiscount	= $this->input->post('vdiscount', TRUE);
		$vdiscount	= str_replace(',','',$vdiscount);
		$ipajak		= $this->input->post('ipajak', TRUE);
        $dpajak	 	= $this->input->post('dpajak', TRUE);
        if($dpajak!=''){
            $tmp=explode("-",$dpajak);
            $th  = $tmp[2];
            $bl  = $tmp[1];
            $hr  = $tmp[0];
            $dpajak = $th."-".$bl."-".$hr;
        }
        $vppn	= $this->input->post('vppn', TRUE);
		$vppn	= str_replace(',','',$vppn);
		$vnetto	= $this->input->post('vnetto', TRUE);
		$vnetto	= str_replace(',','',$vnetto);
		$jml	= $this->input->post('jml', TRUE);
        if(($iarea!='') && ($idtap!='') && ($isupplier!='') && ($vnetto!='0') && ($jml!='0') && ($dduedate!='')){
            $this->db->trans_begin();
            for($i=1;$i<=$jml;$i++){
		        $ido      = $this->input->post('ido'.$i, TRUE);
                $iop		= $this->input->post('iop'.$i, TRUE);
                $ddo	 	= $this->input->post('ddo'.$i, TRUE);
                if($ddo!=''){
                  $tmp=explode("-",$ddo);
                  $th  = $tmp[2];
                  $bl  = $tmp[1];
                  $hr  = $tmp[0];
                  $ddo = $th."-".$bl."-".$hr;
                }
                $iproduct		= $this->input->post('iproduct'.$i, TRUE);
			    $iproductmotif	= '00';
			    $eproductname	= $this->input->post('eproductname'.$i, TRUE);
			    $njumlah		= $this->input->post('ndeliver'.$i, TRUE);
			    $njumlah		= str_replace(',','',$njumlah);
			    $vpabrik		= $this->input->post('vunitprice'.$i, TRUE);
			    $vpabrik		= str_replace(',','',$vpabrik);
                $diskon			= ($njumlah*$vpabrik*$ndiscount)/100;
                $this->mmaster->insertdetail($idtap,$ido,$iop,$isupplier,$iarea,$iproduct,$iproductmotif,$eproductname,$ddtap,$njumlah,$vpabrik,$diskon,$ddo,$i,$thbl);
      			$this->mmaster->updatedo($ido,$idtap,$iop,$isupplier);
            }
            $this->mmaster->insertheader( $idtap,$iarea,$isupplier,$ipajak,$ddtap,$dduedate,$dpajak,$fsupplierpkp,$ndiscount,$vgross,$vdiscount,$vppn,$vnetto,$thbl);
            $eremarkacc	    = "Pembelian dari:".$isupplier."-".$esuppliername;
            $fclose			= 'f';
            $vpem=$vgross;
            $vppn=$vppn;
            $vdis=$vdiscount;
            $vhut=$vnetto;
            $this->mmaster->inserttransheader($idtap,$iarea,$eremarkacc,$fclose,$ddtap);
            $accdebet		= Pembelian;
            $namadebet		= $this->mmaster->namaacc($accdebet);
            $accdebet2		= HutangPPN;
			$namadebet2		= $this->mmaster->namaacc($accdebet2);
			$acckredit		= HutangDagang;
			$namakredit		= $this->mmaster->namaacc($acckredit);
			$acckredit2		= PotonganPenjualan;
            $namakredit2		= $this->mmaster->namaacc($acckredit2);
            $this->mmaster->inserttransitemdebet($accdebet,$idtap,$namadebet,'t','t',$iarea,$eremarkacc,$vpem,$ddtap);
			$this->mmaster->updatesaldodebet($accdebet,$iperiode,$vpem);
			$this->mmaster->inserttransitemdebet($accdebet2,$idtap,$namadebet2,'t','t',$iarea,$eremarkacc,$vppn,$ddtap);
			$this->mmaster->updatesaldodebet($accdebet2,$iperiode,$vppn);
			$this->mmaster->inserttransitemkredit($acckredit,$idtap,$namakredit,'f','t',$iarea,$eremarkacc,$vhut,$ddtap);
			$this->mmaster->updatesaldokredit($acckredit,$iperiode,$vhut);
            if($vdiscount!='' && $vdiscount!=0 && $vdiscount!='0'){
			    $this->mmaster->inserttransitemkredit($acckredit2,$idtap,$namakredit2,'f','t',$iarea,$eremarkacc,$vdis,$ddtap);
			    $this->mmaster->updatesaldokredit($acckredit2,$iperiode,$vdis);
            }
            $this->mmaster->insertgldebet($accdebet,$idtap,$namadebet,'t',$iarea,$vpem,$ddtap,$eremarkacc);
			$this->mmaster->insertgldebet($accdebet2,$idtap,$namadebet2,'t',$iarea,$vppn,$ddtap,$eremarkacc);
  		    $this->mmaster->insertglkredit($acckredit,$idtap,$namakredit,'f',$iarea,$vhut,$ddtap,$eremarkacc);
            if($vdiscount!='' && $vdiscount!=0 && $vdiscount!='0'){
			  $this->mmaster->insertglkredit($acckredit2,$idtap,$namakredit2,'f',$iarea,$vdis,$ddtap,$eremarkacc);
			}
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Input DT AP :'.$this->global['title'].' Kode : '.$idtap.' Supplier : '.$isupplier);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $idtap
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }

}
/* End of file Cform.php */
