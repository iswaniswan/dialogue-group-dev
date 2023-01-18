<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20716';

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
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
        }
            
        echo $this->mmaster->data($this->i_menu, $this->global['folder'], $dfrom, $dto);
    }

    public function status()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id = $this->input->post('id', TRUE);
        if ($id=='') {
            $id = $this->uri->segment(4);
        }
        if ($id!='') {
            $this->db->trans_begin();
            $data = $this->mmaster->status($id);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
                $this->Logger->write('Update status '.$this->global['title'].' Id : '.$id);
                echo json_encode($data);
            }
        }
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],  
            'dfrom'      => $this->uri->segment(4),
            'dto'        => $this->uri->segment(5),             
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode()
    {
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function dataproduct()
    {
        $filter = [];
        $cari = strtoupper($this->input->get('q'));
        $data = $this->mmaster->dataproduct($cari);
        foreach($data->result() as $product){       
            $filter[] = array(
                'id'    => $product->id,
                'name'  => $product->e_product_basename,
                'text'  => $product->i_product_base.' - '.$product->e_product_basename.' - '.$product->e_color_name,
            );
        }   
        echo json_encode($filter);
    }  

    public function getproduct()
    {
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getproduct($this->input->post('eproduct'));

        echo json_encode($data->result_array());
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ipromo      = $this->input->post('ipromo', TRUE);
        $epromo      = $this->input->post('epromo', TRUE);
        $ejenis      = $this->input->post('ejenis', TRUE);  
        $njumlah     = str_replace(',','',$this->input->post('njumlah', TRUE));
        $dperiode    = $this->input->post('dperiode', TRUE);     
        if($dperiode){
            $tmp   = explode('-', $dperiode);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dateperiode = $year.'-'.$month.'-'.$day;
        }
        $id           = $this->mmaster->runningid();

        $id_product   = $this->input->post('idproduct[]',TRUE);
        $id_color     = $this->input->post('idcolor[]',TRUE);
        $n_diskon     = $this->input->post('ndiskon[]',TRUE);
        $n_diskon     = str_replace(',','',$n_diskon);

        if ($ipromo != '' && $ejenis != ''){
            $cekada = $this->mmaster->cek_kode($ipromo);
            if($cekada->num_rows() > 0){
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_begin();
                $this->mmaster->insertheader($id, $ipromo, $epromo, $ejenis, $njumlah, $dateperiode);

                $no=0;
                //var_dump($id_product);
                foreach ($id_product as $idproduct) {     
                    $idproduct    = $idproduct;
                    $idcolor      = $id_color[$no];
                    $ndiskon      = $n_diskon[$no];

                    $this->mmaster->insertdetail($id, $idproduct, $idcolor, $ndiskon); 

                    $no++;
                }     
                if($this->db->trans_status() === False){
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                    );
                }else{
                    $this->db->trans_commit();
                    $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ipromo);
                    $data = array(
                        'sukses'  => true,
                        'kode'    => $ipromo
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result(),
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $id          = $this->input->post('id', TRUE);
        $ipromo      = $this->input->post('ipromo', TRUE);
        $epromo      = $this->input->post('epromo', TRUE);
        $ejenis      = $this->input->post('ejenis', TRUE);  
        $njumlah     = str_replace(',','',$this->input->post('njumlah', TRUE));
        $dperiode    = $this->input->post('dperiode', TRUE);     
        if($dperiode){
            $tmp   = explode('-', $dperiode);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dateperiode = $year.'-'.$month.'-'.$day;
        }
        $id_product   = $this->input->post('idproduct[]',TRUE);
        $id_color     = $this->input->post('idcolor[]',TRUE);
        $n_diskon     = $this->input->post('ndiskon[]',TRUE);
        $n_diskon     = str_replace(',','',$n_diskon);

        if ($id != '' && $ipromo != '' && $epromo != ''){            
            $this->db->trans_begin();       
            $this->mmaster->updateheader($id, $ipromo, $epromo, $ejenis, $njumlah, $dateperiode);
            $this->mmaster->deletedetail($id);

            $no=0;
                //var_dump($id_product);
                foreach ($id_product as $idproduct) {     
                    $idproduct    = $idproduct;
                    $idcolor      = $id_color[$no];
                    $ndiskon      = $n_diskon[$no];

                    $this->mmaster->insertdetail($id, $idproduct, $idcolor, $ndiskon); 

                    $no++;
                }     
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ipromo);
                $data = array(
                    'sukses' => true,
                    'kode'   => $ipromo
                );
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function view(){

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'data'       => $this->mmaster->cek_data($id)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($id)->result(),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */