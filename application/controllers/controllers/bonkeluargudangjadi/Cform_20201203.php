<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2050103';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $d = new DateTime();

        $one_year = new DateInterval('P1M');
        $one_year_ago = new DateTime();
        $one_year_ago->sub($one_year); 

        $akhir = $d->format('d-m-Y');
        $awal  = $one_year_ago->format('d-m-Y');

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $awal,
            'dto'       => $akhir
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
        $dfrom  = $this->uri->segment(4);
        $dto    = $this->uri->segment(5);
    	echo $this->mmaster->data($dfrom,$dto,$this->i_menu,$this->global['folder']);
    }

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom = $this->uri->segment(4);
        $dto   = $this->uri->segment(5);
       
        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function gudang(){
        $filter = [];
        $data = $this->mmaster->gettujuan();
        foreach($data->result() as  $idept){
                $filter[] = array(
                'id'   => $idept->i_sub_bagian,  
                'text' => $idept->e_sub_bagian,
            );
        }          
        echo json_encode($filter);
    }

    public function tujuan(){
        $ibagian = $this->input->post('ibagian');
        $query = $this->mmaster->getgudanglain($ibagian);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->i_sub_bagian." >".$row->e_sub_bagian."</option>";
            }
            $kop  = "<option value=\"\">Pilih Tujuan".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tujuan Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    public function dataproduct(){
        $filter = [];
        if ($this->input->get('q') != '') {
            $filter = [];
            $cari = strtoupper($this->input->get('q'));

            $data = $this->db->query("select a.*, b.e_color_name
                                    from tr_product_base a
                                    join tr_color b on a.i_color = b.i_color
                                    where
                                    (a.i_product_motif like '%$cari%' or a.e_product_basename like '%$cari%') order by a.i_product_motif");
            foreach ($data->result() as $ma) {
                $filter[] = array(
                    'id'   => $ma->i_product_motif,
                    'name' => $ma->e_product_basename,
                    'text' => $ma->i_product_motif.' - '.$ma->e_product_basename.' - '.$ma->e_color_name,
                );
            }
            echo json_encode($filter);
        } else {
            echo json_encode($filter);
        }
    }

    public function getproduct(){
        header("Content-Type: application/json", true);
        $eproduct = $this->input->post('eproduct');
        $data = $this->mmaster->getproduct($eproduct);
        echo json_encode($data->result_array());
    }

    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
            
        $username = $this->session->userdata('username');
        $query = $this->db->query("SELECT i_kode_lokasi FROM public.tm_user_deprole WHERE username='$username'");
        foreach($query->result() as $row){
            $ilokasi = $row->i_kode_lokasi;
        }
        $ibagian     = $this->input->post('ibagian', TRUE);
        $itujuan     = $this->input->post('itujuan',TRUE);
        $dsj         = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $datesj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
           
        $eremark  = $this->input->post('eremark', TRUE);
        $jml      = $this->input->post('jml', TRUE);

        $i_product  = $this->input->post('iproduct[]', true);
        $i_color    = $this->input->post('icolor[]', true);
        $n_quantity = $this->input->post('nquantity[]', true);
        $e_desc     = $this->input->post('edesc[]', true);
        
        $this->db->trans_begin();
        $isj      = $this->mmaster->runningnumber($thbl, $ilokasi);
        $this->mmaster->insertheader($isj, $ibagian, $itujuan, $datesj, $eremark);

        $no=0;
        foreach ($i_product as $iproduct) {
            $iproduct   = $iproduct;
            $icolor     = $i_color[$no];
            $nquantity  = $n_quantity[$no];
            $edesc      = $e_desc[$no];

            $this->mmaster->insertdetail($isj, $iproduct, $icolor, $nquantity, $edesc, $no);
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
                    'kode'   => $isj,
                );
            }
            $this->load->view('pesan', $data);  
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_sj       = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);
        $bagian     = $this->uri->segment(7);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_dataheader($i_sj)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($i_sj)->result(),
            'tujuan'     => $this->mmaster->baca_bagian($bagian)->result(),
            'dfrom'      => $dfrom,
            'dto'        => $dto
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        
        $isj      = $this->input->post('isj', TRUE);       
        $ibagian  = $this->input->post('ibagian',TRUE);
        $itujuan  = $this->input->post('itujuan',TRUE);
        $dsj      = $this->input->post('dsj', TRUE);
        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $datesj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
           
        $eremark  = $this->input->post('eremark', TRUE);
        $jml      = $this->input->post('jml', TRUE);

        $i_product  = $this->input->post('iproduct[]', TRUE);
        $i_color    = $this->input->post('icolor[]', TRUE);
        $n_quantity = $this->input->post('nquantity[]', TRUE);
        $e_desc     = $this->input->post('edesc[]', TRUE);
        
        $this->db->trans_begin();
        $this->mmaster->updateheader($isj, $ibagian, $itujuan, $datesj, $eremark);
        $this->mmaster->deletedetail($isj);

        $no=0;
        foreach ($i_product as $iproduct) {
            $iproduct   = $iproduct;
            $icolor     = $i_color[$no];
            $nquantity  = $n_quantity[$no];
            $edesc      = $e_desc[$no];

            $query2 = $this->mmaster->cekstock($iproduct, $icolor);
            if ($query2->num_rows() > 0){
                $hasilrow6 = $query2->row();
                $n_quantity_stock = $hasilrow6->n_quantity_stock;
                $total = $n_quantity_stock-$nquantity;
            }
            $this->mmaster->insertdetail($isj, $iproduct, $icolor, $nquantity, $edesc, $no);
            $no++;
        }
        //die();
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $data = array(
                    'sukses'    => false      
            );
        }else{
            $this->db->trans_commit();
            $data = array(
                'sukses'    => true,
                'kode'      => $isj
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function view(){

       $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $i_sj   = $this->uri->segment(4);
        $dfrom  = $this->uri->segment(5);
        $dto    = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "View ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            'data'       => $this->mmaster->cek_dataheader($i_sj)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($i_sj)->result(),
            'dto'        => $dto,
            'dfrom'      => $dfrom
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformdetail', $data);
    }

    public function delete(){
        $data = check_role($this->i_menu, 4);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $i_sj   = $this->input->post('i_sj');
        $this->db->trans_begin();
        $data = $this->mmaster->cancel($i_sj);
        if(($this->db->trans_status()=== False)){
            $this->db->trans_rollback();
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Cancel Pengeluaran ke Gudang Lain'.$i_sj);
            echo json_encode($data);
        }
    }

    public function send(){
        header("Content-Type: application/json", true);
        $kode = $this->input->post('kode');
        $this->mmaster->send($kode);
    }

    public function approval(){

        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $i_sj = $this->uri->segment(4);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve ". $this->global['title'],
            'title_list' => 'List '. $this->global['title'],
            'data'       => $this->mmaster->cek_dataheader($i_sj)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($i_sj)->result()
        );

        $this->Logger->write('Membuka Menu Approve ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }

    public function approve(){
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
        $isj      = $this->input->post('isj', true);
        $ibagian  = $this->input->post('ibagian',TRUE);
        $itujuan  = $this->input->post('itujuan',TRUE);
        $dsj      = $this->input->post('dsj', TRUE);

        if($dsj!=''){
            $tmp=explode("-",$dsj);
            $th=$tmp[2];
            $bl=$tmp[1];
            $hr=$tmp[0];
            $datesj=$th."-".$bl."-".$hr;
            $thbl=$th.$bl;
        }
           
        $eremark     = $this->input->post('eremark', TRUE);
        $jml         = $this->input->post('jml', TRUE);
        $ikodelokasi = substr($isj,4,2);

        $i_product      = $this->input->post('iproduct[]', true);
        $i_color        = $this->input->post('icolor[]', true);
        $n_quantity     = $this->input->post('nquantity[]', true);
        $e_desc         = $this->input->post('edesc[]', true);
        $iproductgrade  = 'A';

        $this->db->trans_begin();
        $this->mmaster->approve($isj);
        $this->mmaster->deletedetail($isj);

        $no=0;
        foreach ($i_product as $iproduct) {
            $iproduct   = $iproduct;
            $icolor     = $i_color[$no];
            $nquantity  = $n_quantity[$no];
            $edesc      = $e_desc[$no];

            $this->mmaster->insertdetail($isj, $iproduct, $icolor, $nquantity, $edesc, $no);
            $cekic = $this->mmaster->cekic($iproduct, $icolor);
            if(!$cekic){
                $this->mmaster->insertic($iproduct, $ikodelokasi, $nquantity);
                $nqty_in    = 0;
                $nqty_out   = $nquantity;
                $nqty_awal  = 0;
                $nqty_akhir = $nqty_awal-$nquantity;
            }else{
                $this->mmaster->updateic($iproduct, $ikodelokasi, $nquantity, $cekic->n_quantity_stock);
                $nqty_in    = 0;
                $nqty_out   = $nquantity;
                $nqty_akhir = $cekic->n_quantity_stock - $nquantity;
                $nqty_awal  = $cekic->n_quantity_stock;
            }
            $this->mmaster->insertictrans($iproduct, $iproductgrade, $ikodelokasi, $isj, $nqty_in, $nqty_out, $nqty_akhir, $nqty_awal, $no);
            $no++;
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            $data = array(
                'sukses' => false,

            );
        } else {
            $this->db->trans_commit();
            $data = array(
                'sukses' => true,
                'kode' => $isj,
            );
        }
        $this->load->view('pesan', $data);
    }

    public function cancel(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('kode');
        $this->mmaster->cancel_approve($isj);
    }

    public function change(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('kode');
        $this->mmaster->change_approve($isj);
    }

    public function reject(){
        header("Content-Type: application/json", true);
        $isj = $this->input->post('kode');
        $this->mmaster->reject_approve($isj);
    }
}
/* End of file Cform.php */
