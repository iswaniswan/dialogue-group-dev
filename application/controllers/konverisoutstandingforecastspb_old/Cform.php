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

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $icustomer    = $this->uri->segment('4');
        $periode      = $this->uri->segment('5');
        $periodeth    = substr($periode,0,4);
        $periodebl    = substr($periode,4,2);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'thn'        => $periodeth,
            'bln'        => $periodebl,             
            'data'       => $this->mmaster->getforecast($icustomer, $periode)->row(),
            'datadetail' => $this->mmaster->getforecastdetail($icustomer, $periode)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $icustomer      = $this->input->post('icustomer', TRUE);
        $periode        = $this->input->post('periode', TRUE); 

        $date           =  date("Y-m-d");
        if($date){
                 $tmp   = explode('-', $date);
                 $day   = $tmp[2];
                 $month = $tmp[1];
                 $year  = $tmp[0];
                 $thbl  = $year.$month;
                 $dateo = $year.'-'.$month.'-'.$day;
        }
        $ispb       = $this->mmaster->runningnumber($thbl);
        $jml        = $this->input->post('jml', TRUE); 

        $this->db->trans_begin();
            $vdis1=0;
            $vdis2=0;
            $vdis3=0;
            $vtot =0;
            
            for($i=1;$i<=$jml;$i++){                       
                $iproduct     = $this->input->post('iproduct'.$i, TRUE);
                $eproduct     = $this->input->post('eproduct'.$i, TRUE);
                $icolor       = $this->input->post('icolor'.$i, TRUE); 
                $nquantity    = $this->input->post('nquantity'.$i, TRUE); 
                $nsisa        = $this->input->post('nsisa'.$i, TRUE); 
                $vprice       = $this->input->post('vprice'.$i, TRUE); 
                $inoitem      = $i;

            $discount = $this->mmaster->getdiscount($icustomer);
            $hasilrow = $discount->row();
            $ncustomerdiscount1    = $hasilrow->n_customer_discount1;
            $ncustomerdiscount2    = $hasilrow->n_customer_discount2;
            $ncustomerdiscount3    = $hasilrow->n_customer_discount3;

            $vtotalgross = $vprice*$nquantity;
            $vtot        =$vtot+$vtotalgross;
            $ncustomerdiscoun1=$ncustomerdiscount1+(($vtot*$ncustomerdiscount1)/100);
            $ncustomerdiscoun2=$ncustomerdiscount2+((($vtot-$ncustomerdiscount2)*$ncustomerdiscount2)/100);
            $ncustomerdiscoun3=$ncustomerdiscount3+((($vtot-($ncustomerdiscount1+$ncustomerdiscount2))*$ncustomerdiscount3)/100);
            $vtotaldiscount    = $ncustomerdiscoun1+$ncustomerdiscoun2+$ncustomerdiscoun3;
            $vtotalnetto       = $vtot-$vtotaldiscount;

            $this->mmaster->insertdetail($ispb, $iproduct, $eproduct, $ncustomerdiscount1, $ncustomerdiscount2, $ncustomerdiscount3, $vprice, $icolor);
            }

            $this->mmaster->insert($icustomer, $dateo, $ispb, $vtotaldiscount, $vtotalnetto, $vtotalgross);

            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ispb);
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
}
/* End of file Cform.php */