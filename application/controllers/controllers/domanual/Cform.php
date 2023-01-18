<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1040201';

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
            'ido'       => '',
            'isi'       => '',
            'detail'    => '',
            'jmlitem'   => '',
            'tgl'       => date ('d-m-Y')
            );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    function data_op(){
        $filter = [];
        if($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));
            $this->db->select(" a.*, b.e_supplier_name, c.e_area_name, b.n_supplier_discount, b.n_supplier_discount2
                                from tm_op a , tr_supplier b, tr_area c
                                where a.i_supplier=b.i_supplier and a.i_area=c.i_area
                                and a.f_op_cancel='f' and a.f_op_close='f'
                                and b.i_supplier_group<>'G0000'
                                and a.i_op in (select i_op from tm_op_item where (n_delivery isnull or n_delivery<n_order))
                                and upper(i_op) like '%$cari%' order by b.e_supplier_name, a.i_op",false);
            $data = $this->db->get();
            foreach($data->result() as  $iop){
                    $filter[] = array(
                    'id' => $iop->i_op,  
                    'text' => $iop->i_op
                );
            }
        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    function getop(){
        header("Content-Type: application/json", true);
        $iop = $this->input->post('i_op');
        $this->db->select("a.i_op, a.i_supplier, a.i_area, b.e_area_name, c.e_supplier_name, c.n_supplier_discount, c.n_supplier_discount2");
        $this->db->from("tm_op a");
        $this->db->join("tr_area b","a.i_area=b.i_area");
        $this->db->join("tr_supplier c","a.i_supplier=c.i_supplier");
        $this->db->where("UPPER(i_op)", $iop);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    public function view(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $iop            = $this->input->post('iop', TRUE);
        $esuppliername  = $this->input->post('esuppliername', TRUE); 
        $eareaname      = $this->input->post('earename', TRUE);

        $query   = $this->db->query("select * from tm_op_item where i_op = '$iop' and (n_delivery<n_order or n_delivery isnull)");

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'bacaop'     => $this->mmaster->cek_op($iop)->row(), 
            'data'       => $this->mmaster->bacadetailop($iop)->row(),
            'data1'      => $this->mmaster->bacadetailop($iop)->result(),
            'jmlitem'    => $query->num_rows(),
            'tgl'       => date ('d-m-Y')
        );
        $this->Logger->write('Membuka Menu Input Item '.$this->global['title']);
        
        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ido  = $this->input->post('ido', TRUE);
        $ddo  = $this->input->post('ddo', TRUE);
        if($ido==''){
         die;
        }
        
        if($ddo!=''){
           $tmp=explode("-",$ddo);
           $th=$tmp[2];
           $bl=$tmp[1];
           $hr=$tmp[0];
           $ddo=$th."-".$bl."-".$hr;
           $thbl=substr($th,2,2).$bl;
        }
        $isupplier  = $this->input->post('isupplier', TRUE);
        $ido        ='DO-'.$thbl.'-'.$ido;
        $iarea      = $this->input->post('iarea', TRUE);
        $iop        = $this->input->post('iop', TRUE);
        $vdogross   = $this->input->post('vdogross',TRUE);
        $vdogross   = str_replace(',','',$vdogross);
        $jml        = $this->input->post('jml', TRUE);
        if(($ido!='') && ($isupplier!='') && (($vdogross!='') || ($vdogross!='0')) && ($iop!='') && ($ddo!='') && ($jml>0)){
            $this->db->trans_begin();
            $query=$this->db->query("select * from tm_do where i_do='$ido' and i_supplier='$isupplier'");
            if($query->num_rows()==0){
                $istore             = 'AA';
                $istorelocation     = '01';
                $istorelocationbin  = '00';
                $this->mmaster->insertheader($ido,$isupplier,$iop,$iarea,$ddo,$vdogross);
                for($i=1;$i<=$jml;$i++){
                    $iproduct            = $this->input->post('iproduct'.$i, TRUE);
                    $iproductgrade       = 'A';
                    $iproductmotif       = $this->input->post('motif'.$i, TRUE);
                    $eproductname        = $this->input->post('eproductname'.$i, TRUE);
                    $vproductmill        = $this->input->post('vproductmill'.$i, TRUE);
                    $vproductmill        = str_replace(',','',$vproductmill);
                    $ndeliver            = $this->input->post('ndeliver'.$i, TRUE);
                    $ndeliverhidden      = $this->input->post('ndeliverhidden'.$i, TRUE);
                    $ntmp                = $this->input->post('ntmp'.$i, TRUE);
                    $eremark             = $this->input->post('eremark'.$i, TRUE);
                    $this->mmaster->insertdetail($iop,$ido,$isupplier,$iproduct,$iproductgrade,$iproductmotif,$eproductname,$ndeliver,$vproductmill,$ddo,$eremark,$i,$ido);
                    $this->mmaster->updateopdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver,$ndeliverhidden,$ntmp);
                    $this->mmaster->updatespbdetail($iop,$iproduct,$iproductgrade,$iproductmotif,$ndeliver);
                    $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                    if(isset($trans)){
                        foreach($trans as $itrans){
                            $q_aw =$itrans->n_quantity_stock;
                            $q_ak =$itrans->n_quantity_stock;
                            $q_in =0;
                            $q_out=0;
                            break;
                        }
                    }else{
                        $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                        if(isset($trans)){
                            foreach($trans as $itrans){
                                $q_aw =$itrans->n_quantity_stock;
                                $q_ak =$itrans->n_quantity_stock;
                                $q_in =0;
                                $q_out=0;
                                break;
                            }
                        }else{
                            $trans=$this->mmaster->qic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin);
                            if(isset($trans)){
                                foreach($trans as $itrans){
                                    $q_aw =$itrans->n_quantity_stock;
                                    $q_ak =$itrans->n_quantity_stock;
                                    $q_in =0;
                                    $q_out=0;
                                    break;
                                }
                            }else{
                                $q_aw=0;
                                $q_ak=0;
                                $q_in=0;
                                $q_out=0; 
                            }
                        }
                        $this->mmaster->inserttrans4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ido,$q_in,$q_out,$ndeliver,$q_aw,$q_ak);
                        $th=substr($ddo,0,4);
                        $bl=substr($ddo,5,2);
                        $emutasiperiode=$th.$bl;
                        if($this->mmaster->cekmutasi($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$emutasiperiode)){
                            $this->mmaster->updatemutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode);
                        }else{
                            $this->mmaster->insertmutasi4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$emutasiperiode);
                        }
                        if($this->mmaster->cekic($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin)){
                            $this->mmaster->updateic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$ndeliver,$q_ak);
                        }else{
                            $this->mmaster->insertic4($iproduct,$iproductgrade,$iproductmotif,$istore,$istorelocation,$istorelocationbin,$eproductname,$ndeliver);
                        }
                    }
                }
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Nomor DO'.$this->global['title'].' Kode : '.$ido);

                    $data = array(
                        'sukses'    => true,
                        'kode'      => $ido
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

}
/* End of file Cform.php */
