<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1040106';

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
        /*$data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ayeuna=date('Y-m-d');
        if($ayeuna!=''){
            $tmp=explode("-",$ayeuna);
            $th=$tmp[0];
            $bl=$tmp[1];
            $hr=$tmp[2];
            $ayeuna=$th."-".$bl."-".$hr;
            $thn=$th;
        }*/
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            /*'isi'           => $this->mmaster->bacasemua($thn),
            'opstatus'      => $this->mmaster->getop(),
            'area'          => $this->mmaster->getar(),
            'tgl'           => date('d-m-Y')*/
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        echo $this->mmaster->data($this->i_menu);
    }

    public function edit(){
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        if($this->uri->segment(5)){
            $ispb          = $this->uri->segment(4);
            $iarea         = $this->uri->segment(5);
            $tmp           = explode('-',$ispb);
            if($tmp[0]=='SPB'){
               $query   = $this->db->query("select a.* from tm_spb_item a, tr_product b
                                    where a.i_spb = '$ispb'
                                    and a.i_area='$iarea' and b.i_product_status<>'4'
                                    and a.i_product=b.i_product
                                    and a.n_deliver<a.n_order
                                    and a.i_op isnull");
            }else if($tmp[0]=='SPMB'){
               $query   = $this->db->query("select a.* from tm_spmb_item a, tr_product b
                                    where a.i_spmb = '$ispb' and b.i_product_status<>'4'
                                    and a.i_product=b.i_product
                                    and a.n_deliver<a.n_acc
                                    and a.n_acc>0
                                    and a.n_saldo>0
                                    and a.n_stock<a.n_acc");
               $data['ispmbold']  = $ispb;
            }
            $ayeuna=date('Y-m-d');
            if($ayeuna!=''){
                $tmp=explode("-",$ayeuna);
                $th=$tmp[0];
                $bl=$tmp[1];
                $hr=$tmp[2];
                $ayeuna=$th."-".$bl."-".$hr;
                $thbl=$th.$bl;
            }
            $this->mmaster->update_sisa_saldo();
            $data = array(
                'folder'        => $this->global['folder'],
                'title'         => "Edit ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'jmlitem'       => $query->num_rows(),
                'iop'           => '',
                'iopold'        => '',
                'ispb'          => $ispb,
                'isi'           => $this->mmaster->baca($ispb,$iarea),
                'detail'        => $this->mmaster->bacadetail($ispb,$iarea),
                'opstatus'      => $this->mmaster->getop(),
                'tgl'           => date('d-m-Y')
            );
            $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
            $this->load->view('opforecast/vformedit',$data);
        }
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iopold     = $this->input->post('iopold', TRUE);
            $iarea      = $this->input->post('iarea', TRUE);
            $iopstatus  = $this->input->post('iopstatus', TRUE);
            $ireff      = $this->input->post('ispb', TRUE);
            $dop        = $this->input->post('dop', TRUE);
            $old        = $this->input->post('asal', TRUE);

            if($dop!=''){
              $tmp  =explode("-",$dop);
              $th   =$tmp[2];
              $bl   =$tmp[1];
              $hr   =$tmp[0];
              $dop  =$th."-".$bl."-".$hr;
              $thbl =$th.$bl;
            }
            $dreff      = $this->input->post('dspb', TRUE);

            $eopremark        = $this->input->post('eopremark', TRUE);
            if($eopremark=='')
                $eopremark=null;
                $ndeliverylimit  = $this->input->post('ndeliverylimit', TRUE);
                $ntoplength      = $this->input->post('ntoplength', TRUE);
                $jml             = $this->input->post('jml');
                $i=0;
            if(($iopstatus!='') && ($dop!='')){
                $this->db->trans_begin();
                $tmp=explode('-',$ireff);
                $iop='';
                for($i=1;$i<=$jml;$i++){
                  $norder     = $this->input->post('norder'.$i, TRUE);
                  $iproduct   = $this->input->post('iproduct'.$i, TRUE);
                  $rp         = $this->mmaster->cekproduct($iproduct);
                  $isupplier  = 'SP030';
                  if(($norder!='0')){
                    if($iop==''){
                          $iop = $this->mmaster->runningnumber($thbl);
                          $this->mmaster->insertheader( $iop, $dop, $isupplier, $iarea, $iopstatus, $ireff, $eopremark,
                                                        $ndeliverylimit, $ntoplength, $dreff, $old,$iopold);
                    }
                    $iproductgrade    = 'A';
                    $iproductmotif    = $this->input->post('motif'.$i, TRUE);
                    $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                    $vproductmill     = $this->input->post('vproductmill'.$i, TRUE);
                    $vproductmill     = str_replace(',','',$vproductmill);
                    $norder           = $this->input->post('norder'.$i, TRUE);
                    $nquantitystock   = $this->input->post('nquantitystock'.$i, TRUE);
                    $this->mmaster->insertdetail($iop,$iproduct,$iproductgrade,$eproductname,$norder,
                                                 $vproductmill,$iproductmotif,$i);
                  }else{
                      $data = array(
                          'sukses' => false
                      );
                  }
                }

                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iop);

                    $data = array(
                        'sukses'    => true,
                        'kode'      => $iop
                    );
                }
            }else{
                $data = array(
                    'sukses' => false
                );
            }
            $this->load->view('pesan', $data); 
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

    function getharga(){
        header("Content-Type: application/json", true);
        $area = $this->input->post('i_area');
        $$this->db->select("select a.*, b.i_store, b.i_store_location, c.e_product_motifname, d.v_product_mill
                            from dgu.tm_spb_item a, dgu.tm_spb b, dgu.tr_product_motif c, dgu.tr_product d, dgu.tr_harga_beli e
                            where b.i_spb = '$ispb' and b.i_spb=a.i_spb and a.i_product=d.i_product and b.i_area='$area'
                            and d.i_product_status<>'4' and a.i_product=e.i_product and e.i_price_group='00' and a.i_product_motif=c.i_product_motif 
                            and a.i_product=c.i_product order by a.n_item_no
                            union all
                            select a.*, b.i_store, b.i_store_location, c.e_product_motifname, d.v_product_mill
                            from dgu.tm_spmb_item a, dgu.tm_spmb b, dgu.tr_product_motif c, dgu.tr_product d, dgu.tr_harga_beli e
                            where b.i_spmb = '$ispb' and b.i_spmb=a.i_spmb and a.i_product=d.i_product and b.i_area='$area'
                            and d.i_product_status<>'4' and a.i_product=e.i_product and e.i_price_group='00' and a.i_product_motif=c.i_product_motif 
                            and a.i_product=c.i_product order by a.n_item_no
                            ",false);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }


    function getDate(){
        header("Content-Type: application/json", true);
        $ispb = $this->input->post('i_spb');
        $this->db->select("distinct(b.i_spb), a.d_spb, a.i_spb_old, b.i_area as i_area, c.e_area_name as e_area_name ,
                           d.e_customer_name as e_customer_name, e.i_op, e.d_op, e.e_op_remark
                           from tm_spb_item b, tm_spb a left join tm_opfc e on (a.i_spb=e.i_reff and a.i_area=e.i_area and e.f_op_close='f'),
                           tr_customer_area c, tr_customer d
                           where not a.i_approve1 isnull
                           and not a.i_approve2 isnull
                           and a.i_store isnull
                           and a.i_store_location isnull
                           and a.f_spb_cancel = 'f'
                           and a.f_spb_stockdaerah='f'
                           and a.i_nota isnull
                           and a.i_spb=b.i_spb and a.i_area=b.i_area and a.f_spb_pemenuhan='f' --and b.n_deliver<b.n_order
                           and d.i_customer=c.i_customer and d.i_customer=a.i_customer
                           and a.i_customer=c.i_customer
                           and e.i_op isnull
                           and a.i_spb='$ispb'
                           union all
                           select distinct(b.i_spmb), a.d_spmb, a.i_spmb_old, a.i_area as i_area, c.e_area_name as e_area_name,
                           'STOCK '||a.i_area||'-'||c.e_area_name as e_customer_name, e.i_op, e.d_op, e.e_op_remark
                           from tm_spmb_item b, tm_spmb a left join tm_opfc e on (a.i_spmb=e.i_reff and a.i_area=e.i_area and e.f_op_close='f'), tr_area c
                           where not a.i_approve2 isnull
                           and a.i_store isnull
                           and a.i_store_location isnull
                           and a.f_spmb_pemenuhan='f'
                           and (b.n_deliver<b.n_acc and b.n_acc>0 and b.n_saldo>0)
                           and a.i_spmb=b.i_spmb
                           and a.i_area=c.i_area
                           and a.f_spmb_opclose='f'
                           and e.i_op isnull
                           and a.i_spmb='$ispb'
                           ",false);
        $data = $this->db->get();
        echo json_encode($data->result_array());
    }

    /*function editop(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');

            if(($this->uri->segment(4)) && ($this->uri->segment(5))){
                $ispb       = $this->uri->segment(4);
                $iop        = $this->uri->segment(5);
                $isupplier  = $this->uri->segment(6);
                $area       = $this->uri->segment(7);
                $dfrom      = $this->uri->segment(8);
                $dto        = $this->uri->segment(9);
                $query      = $this->db->query("select * from tm_op_item where i_op = '$iop'");
                
                $data = array(
                    'folder'        => $this->global['folder'],
                    'title'         => "Edit ".$this->global['title'],
                    'title_list'    => 'List '.$this->global['title'],
                    'jmlitem'       => $query->num_rows(),
                    'iop'           => $iop,
                    'ispb'          => $ispb,
                    'supplier'      => $isupplier,
                    'isi'           => $this->mmaster->bacaop($iop,$iarea),
                    'detail'        => $this->mmaster->bacadetailop($iop,$iarea),
                    'opstatus'      => $this->mmaster->getop()->result(),
                    'dfrom'         => $dfrom,
                    'dto'           => $dto
                );
                $this->load->view('opnew/vformupdate',$data);
            }
        }
        
    }

    function updateop(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');

            $iop        = $this->input->post('iop', TRUE);
            $iopold     = $this->input->post('iopold', TRUE);
            $isupplier  = $this->input->post('isupplier', TRUE);
            $iarea      = $this->input->post('iarea', TRUE);
            $iopstatus  = $this->input->post('iopstatus', TRUE);
            $ireff      = $this->input->post('ispb', TRUE);
            $dop        = $this->input->post('dop', TRUE);
            $old        = $this->input->post('asal', TRUE);

            if($dop!=''){
                $tmp=explode("-",$dop);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dop=$th."-".$bl."-".$hr;
            }
            $dreff      = $this->input->post('dspb', TRUE);
            if($dreff!=''){
                $tmp=explode("-",$dreff);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dreff=$th."-".$bl."-".$hr;
            }

            $eopremark     = $this->input->post('eopremark', TRUE);
            if($eopremark=='')
            $eopremark=null;
            $ndeliverylimit = $this->input->post('ndeliverylimit', TRUE);
            $ntoplength     = $this->input->post('ntoplength', TRUE);
            $jml            = $this->input->post('jml', TRUE);

            if(($isupplier!='') && ($iopstatus!='') && ($dop!='')){
                $benar      = 'false';
                $this->mmaster->updateheader($iop, $dop, $isupplier, $iarea, $iopstatus, $ireff,
                $eopremark, $ndeliverylimit, $ntoplength, $dreff, $old, $iopold);
                for($i=1;$i<=$jml;$i++){
                    $norder                 =$this->input->post('norder'.$i, TRUE);
                    if($norder!='0'){
                     $iproduct              =$this->input->post('iproduct'.$i, TRUE);
                     $iproductgrade         ='A';
                     $iproductmotif         =$this->input->post('motif'.$i, TRUE);
                     $eproductname          =$this->input->post('eproductname'.$i, TRUE);
                     $vproductmill          =$this->input->post('vproductmill'.$i, TRUE);
                     $vproductmill          =str_replace(',','',$vproductmill);
                     $norder                =$this->input->post('norder'.$i, TRUE);
                     $data = array(
                        'iproduct'      => $iproduct,
                        'iproductgrade' => $iproductgrade,
                        'iproductmotif' => $iproductmotif,
                        'eproductname'  => $eproductname,
                        'vproductmill'  => $vproductmill,
                        'norder'        => $norder
                     );
                     $this->mmaster->deletedetail($iproduct, $iproductgrade, $iop, $iproductmotif);
                     $this->mmaster->insertdetail( $iop,$iproduct,$iproductgrade,$eproductname,$norder,$vproductmill,$iproductmotif,$i);
                     $this->mmaster->updatespb($ireff,$iop,$iproduct,$iproductgrade,$iproductmotif,$iarea,$norder);
                    }
                }
            }
            $benar='true';
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$iop);

                $data = array(
                    'sukses'    => true,
                    'kode'      => $iop
                );
            }
        }
    }*/
}
/* End of file Cform.php */
