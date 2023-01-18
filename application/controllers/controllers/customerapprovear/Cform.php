<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '10513';

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
            'title'             => $this->global['title']
            /*'area'              => $this->mmaster->bacaarea(),
            'retensi'           => $this->mmaster->bacaretensi(),
            'shop'              => $this->mmaster->bacashop(),
            'status'            => $this->mmaster->bacastatus(),
            'kelamin'           => $this->mmaster->bacakelamin(),
            'agama'             => $this->mmaster->bacaagama(),
            'traversed'         => $this->mmaster->bacatraversed(),
            'class'             => $this->mmaster->bacaclass(),
            'payment'           => $this->mmaster->bacapayment(),
            'call'              => $this->mmaster->bacacall(),
            'customergroup'     => $this->mmaster->bacacustomergroup(),
            'plu'               => $this->mmaster->bacaplugroup(),
            'customertype'      => $this->mmaster->bacacustomertype(),
            'customerstatus'    => $this->mmaster->bacacustomerstatus(),
            'customergrade'     => $this->mmaster->bacacustomergrade(),
            'customerservice'   => $this->mmaster->bacacustomerservice(),
            'customersalestype' => $this->mmaster->bacacustomersalestype(),
            'pricegroup'        => $this->mmaster->bacapricegroup()*/
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformlist', $data);

    }

    function data(){
		echo $this->mmaster->data($this->i_menu);
    }

    public function getkota(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') != '') {
            $filter = [];
            $iarea  = $this->input->get('iarea');
            $cari   = strtoupper($this->input->get('q'));
            $data   = $this->mmaster->getkota($iarea, $cari);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_city,  
                    'text'  => $row->i_city.' - '.$row->e_city_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getsalesman(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('iarea') != '') {
            $filter = [];
            $iarea  = $this->input->get('iarea');
            $cari   = strtoupper($this->input->get('q'));
            $data   = $this->mmaster->getsalesman($iarea, $cari);
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'    => $row->i_salesman,  
                    'text'  => $row->e_salesman_name
                );
            }        
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getcustomerspecialproduct(){
        $iproducttype = $this->input->post('iproducttype');
        $query = $this->mmaster->getcustomerspecialproduct($iproducttype);
        if($query->num_rows()>0) {
            $c  = "";
            $spb = $query->result();
            foreach($spb as $row) {
                $c.="<option value=".$row->i_customer_specialproduct." >".strtoupper($row->e_customer_specialproductname)."</option>";
            }
            $kop  = $c;
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\"></option>";
            echo json_encode(array(
                'kop'    => $kop
            ));
        }
    }

    public function databrg(){
        $filter = [];
        if($this->input->get('q') != '' && $this->input->get('kdharga') != '') {
            $filter  = [];
            $cari    = strtoupper($this->input->get('q'));
            $kdharga = strtoupper($this->input->get('kdharga', FALSE));
            $data    = $this->mmaster->bacaproduct($cari,$kdharga);
            foreach($data->result() as  $product){
                $filter[] = array(
                    'id'    => $product->kode,  
                    'text'  => $product->kode.' - '.$product->nama
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getdetailbar(){
        header("Content-Type: application/json", true);
        $kdharga  = $this->input->post('kdharga', FALSE);
        $iproduct = $this->input->post('iproduct', FALSE);
        $data     = $this->mmaster->bacaproductx($kdharga, $iproduct);
        echo json_encode($data->result_array());  
    }

    public function edit(){
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ispb = $this->uri->segment(4);
        $iarea = $this->uri->segment(5);
		$ipricegroup = $this->uri->segment(6);
        $query = $this->db->query("select * from tm_spb_item where i_spb = '$ispb' and i_area='$iarea'");
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'ispb'          => $ispb,
            'iarea'         => $iarea,
            'ipricegroup'   => $ipricegroup,
            'isi'           => $this->mmaster->baca($ispb,$iarea),
            'isispb'        => $this->mmaster->bacaspb($ispb,$iarea),
            'jmlitem'       => $query->num_rows(),
            'isidetail'     => $this->mmaster->bacadetail($ispb,$iarea,$ipricegroup)
        );
        $qnilaiorderspb	= $this->mmaster->bacadetailnilaiorderspb($ispb,$iarea,$ipricegroup);
		if($qnilaiorderspb->num_rows()>0){
			$row_nilaiorderspb	= $qnilaiorderspb->row();
			$data['nilaiorderspb']	= $row_nilaiorderspb->nilaiorderspb;
		}else{
			$data['nilaiorderspb']	= 0;
		}

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vform',$data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $ispb		= $this->input->post('ispb', TRUE);
		$iarea		= $this->input->post('iarea', TRUE);
		$eapprove	= $this->input->post('eapprove', TRUE);
		$iapprove	= $this->session->userdata('username');
        $fapprove	= $this->input->post('chkapprove', TRUE);
        
        if($fapprove!=''){
            $fapprove='t';
        }else{
            $fapprove   ='f';
            $fparkir    = $this->input->post('chkparkir', TRUE);
            if($fparkir!=''){
				$fparkir= 't';
            }else{
				$fparkir= 'f';
                $fkuli  = $this->input->post('chkkuli', TRUE);
            }
			if($fkuli!=''){
				$fkuli	= 't';
            }else{
				$fkuli	= 'f';
            $fkontrabon	= $this->input->post('chkkontrabon', TRUE);
            }
			if($fkontrabon!=''){
				$fkontrabon	= 't';
            }else{
                $fkontrabon	= 'f';
            }
            $this->mmaster->update($ispb, $iarea, $iapprove, $eapprove, $fapprove, $fparkir, $fkuli, $fkontrabon);
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Approve Pelanggan Baru'.$this->global['title'].' Kode : '.$ispb);

                $data = array(
                    'sukses'    => true,
                    'kode'      => $ispb
                );
            }
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
