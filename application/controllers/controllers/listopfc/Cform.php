<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1070508';
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
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vmainform', $data);

    }

    public function view(){
        $dfrom     = $this->input->post('dfrom', TRUE);
        if ($dfrom =='') {
            $dfrom = $this->uri->segment(4);
        }
        $dto       = $this->input->post('dto', TRUE);
        if ($dto   =='') {
            $dto   = $this->uri->segment(5);
        }
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto
        );
        $this->Logger->write('Melihat Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function data(){
        $dfrom      = $this->uri->segment(4);
        $dto        = $this->uri->segment(5);
        $username   = $this->session->userdata('username');
        $idcompany  = $this->session->userdata('id_company');
        $cekdepartemen = $this->mmaster->cekdepartemen($username,$idcompany);
        echo $this->mmaster->data($dfrom, $dto, $cekdepartemen, $this->global['folder'], $this->global['title']);
    }

    public function edit(){
        $iop     = $this->uri->segment(4);
        $isupplier  = $this->uri->segment(5);
        $area    = $this->uri->segment(6);
        $dfrom   = $this->uri->segment(7);
        $dto     = $this->uri->segment(8);
        $username       = $this->session->userdata('username');
        $idcompany      = $this->session->userdata('id_company');
        $query          = $this->db->query("select * from tm_opfc_item where i_op = '$iop'");
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' =>'List '.$this->global['title'],
            'iop'        => $iop,
            'supplier'   => $isupplier,
            'jmlitem'    => $query->num_rows(),
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'opstatus'   => $this->mmaster->getop()->result(),
            'isi'        => $this->mmaster->bacaop($iop,$area)->row(),
            'detail'     => $this->mmaster->bacadetailop($iop)->result()
        );
       
        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
            $iop     = $this->input->post('iop', TRUE);
            $iopold     = $this->input->post('iopold', TRUE);
            $isupplier  = $this->input->post('isupplier', TRUE);
            $iarea      = $this->input->post('iarea', TRUE);
            $iopstatus  = $this->input->post('iopstatus', TRUE);
            $ireff      = $this->input->post('i_reff', TRUE);
            $dop     = $this->input->post('dop', TRUE);
            $old     = $this->input->post('asal', TRUE);

            if($dop!=''){
                $tmp=explode("-",$dop);
                $th=$tmp[2];
                $bl=$tmp[1];
                $hr=$tmp[0];
                $dop=$th."-".$bl."-".$hr;
             }
             $dreff      = $this->input->post('dop', TRUE);
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
             $ndeliverylimit   = $this->input->post('ndeliverylimit', TRUE);
             $ntoplength    = $this->input->post('ntoplength', TRUE);
             $jml        = $this->input->post('jml', TRUE);

            if(($isupplier!='') && ($iopstatus!='') && ($dop!='')){
                $benar      = 'false';

                $this->db->trans_begin();
                
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
            $this->load->view('pesan', $data);  
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $iop	= $this->input->post('iop');
        $iarea	= $this->input->post('iarea');
        $this->db->trans_begin();
        $this->mmaster->delete($iop,$iarea);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Mengcancel OP Forecast Area '.$iarea.' No:'.$iop);
            echo json_encode($data);
        }
    }

}
/* End of file Cform.php */
