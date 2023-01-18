<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2090105';
   
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
        $dfrom = $this->input->post('dfrom');
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom  = date('01-m-Y');
            }
        }

        $dto = $this->input->post('dto');
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => date('d-m-Y', strtotime($dfrom)),
            'dto'       => date('d-m-Y', strtotime($dto)),
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
        echo $this->mmaster->data($this->i_menu,$this->global['folder'],$dfrom,$dto);
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom      = $this->input->post('dfrom');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }

        $dto        = $this->input->post('dto');
        if($dto==''){
            $dto=$this->uri->segment(5);
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'gudang'        => $this->mmaster->gudang(),
            'tujuan'        => $this->mmaster->tujuan(),
            'dfrom'         => $dfrom,
            'dto'           => $dto,
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vforminput', $data);
    }

    public function proses(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $dfrom      = $this->input->post('dfrom');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }

        $dto        = $this->input->post('dto');
        if($dto==''){
            $dto=$this->uri->segment(5);
        }

        $jnskeluar  = $this->input->post('jnskeluar', TRUE);
        
        if($jnskeluar == 0){
            $data = array(
                'folder'            => $this->global['folder'],
                'title'             => "Edit ".$this->global['title'],
                'title_list'        => 'List '.$this->global['title'],
                'area'              => $this->mmaster->bacagudang()->result(),
                'ngadug'            => $this->mmaster->ngadug(), 
                'dfrom'             => $dfrom,
                'dto'               => $dto,
            );
            $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);
            $this->load->view($this->global['folder'].'/vforminput', $data);
        }else if($jnskeluar == 1){
            $data = array(
                'folder'            => $this->global['folder'],
                'title'             => "Edit ".$this->global['title'],
                'title_list'        => 'List '.$this->global['title'],
                'area'              => $this->mmaster->bacagudang()->result(),
                'ngadug'            => $this->mmaster->ngadug(), 
                'dfrom'             => $dfrom,
                'dto'               => $dto,
            );
            $this->Logger->write('Membuka Menu Penuhi Pendingan '.$this->global['title']);
            $this->load->view($this->global['folder'].'/vformpendingan', $data);
        }
    }

    public function getreferensi(){
        //$cari = $this->input->post('cari');
        $query = $this->mmaster->getreferensi();
        if($query->num_rows()>0) {
            $c  = "";
            $sch = $query->result();
            foreach($sch as $row) {
                if($row->sisa > 0){
                    $c.="<option value=".$row->i_schedule." >".$row->i_schedule."</option>";
                }
            }
            $kop  = "<option value=\"\" selected disabled>Pilih No Schedule".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Ada Data</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }

    }

    function getschedule(){
        header("Content-Type: application/json", true);
        $ischedule = $this->input->post('ischedule');
        $this->db->select("to_char(d_schedule,'dd-mm-yyyy') AS d_schedule");
        $this->db->from("tm_schedule a");
        $this->db->where("i_schedule", $ischedule);
        $this->db->where ("f_schedule_cancel",'f');
        $data = $this->db->get();

        $dataa = array(
            'data'      => $data->result_array(),
            'brgop'     => $this->mmaster->bacadetail($ischedule)->result_array()
        );
        echo json_encode($dataa);
    }

    public function getbonk(){
        $query = $this->mmaster->getbonk();
        if($query->num_rows()>0) {
            $c  = "";
            $bonk = $query->result();
            foreach($bonk as $row) {
                $c.="<option value=".$row->i_bonk." >".$row->i_bonk."</option>";
            }
            $kop  = "<option value=\"\" selected disabled>Pilih No Bonk".$c."</option>";
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Tidak Ada Data</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }

    function getbonkdetail(){
        header("Content-Type: application/json", true);
        $ibonk = $this->input->post('ibonk');
        $this->db->select("to_char(d_bonk,'dd-mm-yyyy') AS d_bonk, i_bonk, i_departement, i_sub_bagian AS tujuan, i_schedule");
        $this->db->from("tm_bonkeluar_cutting a");
        $this->db->where("UPPER(i_bonk)", $ibonk);
        $this->db->where ("f_bonk_cancel",'f');
        $data = $this->db->get();

        $query2   = $this->db->query("  SELECT
                                            *
                                        FROM
                                            tm_bonkeluar_cutting_item
                                        WHERE
                                            i_bonk = '$ibonk'
                                            AND n_material_sisa < n_quantity_material ");
        $dataa = array(
            'data'      => $data->result_array(),
            'jmlitem'   => $query2->num_rows(),
            'bonkitem'  => $this->mmaster->bacabonkdetail($ibonk)->result_array(),
        );
        echo json_encode($dataa);
    }
    
    public function getmateriall(){
        header("Content-Type: application/json", true);
        $iproduct = $this->input->post('iproductwip');
        // $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'head' => $this->mmaster->gethead($iproduct)->row(),
            'detail' => $this->mmaster->getdetail($iproduct)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function getmateriallpendingan(){
        header("Content-Type: application/json", true);
        $ibonk = $this->input->post('ibonk');
        // $gudang  = $this->input->post('gudang', FALSE);
        $query  = array(
            'head' => $this->mmaster->getheadbonk($ibonk)->row(),
            'detail' => $this->mmaster->getdetailbonk($ibonk)->result_array()
        );
        //var_dump($query);
        echo json_encode($query);  
    }

    public function simpan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ijenis             = $this->input->post('ijenis', TRUE);
        $ibonk              = $this->input->post('ibonk', TRUE);
        
        if($ibonk == ''){
            $ibonk = "";
        }
        
        if($ijenis == "0"){
            $itujuan        = $this->input->post('itujuan', TRUE);
            $ischedule      = $this->input->post('ischedule', TRUE);
            $idepartement   = $this->input->post('idepartement', TRUE);
        }else{
            $itujuan        = $this->input->post('itujuanx', TRUE);
            $ischedule      = $this->input->post('ischedulex', TRUE);
            $idepartement   = $this->input->post('idepartementx', TRUE);
        }

        $dbonk          = $this->input->post('dbonk', TRUE);
        $jml            = $this->input->post('jml', TRUE);  
        
        if($dbonk){
            $tmp   = explode('-', $dbonk);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $thbl = $year.$month;
            $datebonk = $year.'-'.$month.'-'.$day;
        }
        
        $this->db->trans_begin();
        
        $ibonkx = $this->mmaster->runningnumberbonk($thbl,$idepartement,$ijenis,$ibonk);
        
        /* INSERT HEADER */
        $this->mmaster->insertheader($ibonkx, $datebonk, $itujuan, $thbl, $ischedule, $idepartement);
       
        /* INSERT ITEM */
        for($j=0;$j<$jml;$j++){
            $iproduct         = $this->input->post('iproduct'.$j, TRUE);
            $eproductname     = $this->input->post('eproductname'.$j, TRUE);
            $imaterial        = $this->input->post('imaterial'.$j, TRUE);
            $icolor           = $this->input->post('icolor'.$j, TRUE);
            $nquantity        = $this->input->post('nqtyitemtmp'.$j, TRUE);
            $nmaterial        = $this->input->post('nmaterial'.$j, TRUE);
            $ndeliver         = $this->input->post('ndeliver'.$j, TRUE);
            $sisamaterial     = $nmaterial-$ndeliver;
            $keterangan       = $this->input->post('eremark'.$j, TRUE);
            
            if($sisamaterial > 0){   
                $cek = FALSE;
            }else{
                $cek = TRUE;
            }
         
            $this->mmaster->insertbonkdetail($ibonkx,$iproduct,$eproductname,$icolor,$nquantity,$nmaterial,$ndeliver,$sisamaterial,$keterangan,$imaterial,$j,$cek,$ischedule);

            if($ijenis == "1"){
                $this->mmaster->updatebonkref($ibonk,$iproduct,$icolor,$imaterial);
            }
        }
        
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
                $data = array(
                    'sukses'    => false,
                );
        }else{
            $this->db->trans_commit();
            $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonk);
            $data = array(
                'sukses' => true,
                'kode'      => $ibonkx,
            );
        }
    $this->load->view('pesan', $data);      
    }

    public function changestatus()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $this->db->trans_begin();
        $data = $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $istatus . ' No : ' . $id);
            echo json_encode(true);
        }
    }

    public function edit(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibonk  = $this->uri->segment('4');
        $dfrom  = $this->uri->segment('5');
        $dto    = $this->uri->segment('6');

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Edit ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'data'          => $this->mmaster->cek_data($ibonk)->row(),
            'datadetail'    => $this->mmaster->cek_datadetail($ibonk)->result(),       
            'ngadug'        => $this->mmaster->ngadug(), 
            'gudang'        => $this->mmaster->gudang(),
            'dfrom'         => $dfrom,
            'dto'           => $dto,    
        );

        $this->Logger->write('Membuka Menu Edit '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformedit', $data);
    }

    public function update(){

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibonk      = $this->input->post('ibonk', TRUE);
        $dbonk      = $this->input->post('dbonk', TRUE);
        $eremarkh   = $this->input->post('eremarkh', TRUE);
        $jml        = $this->input->post('jml', TRUE);  
        
        $this->db->trans_begin();

        $this->mmaster->updatebonkheader($ibonk);

        for($i=1;$i<=$jml;$i++){ 
            // if($this->input->post('cek'.$i)=='cek'){                  
                $iproduct         = $this->input->post('iproduct'.$i, TRUE);
                $eproductname     = $this->input->post('eproductname'.$i, TRUE);
                $imaterial        = $this->input->post('imaterial'.$i, TRUE);
                $icolor           = $this->input->post('warna'.$i, TRUE);
                $ecolorname       = $this->input->post('icolorname'.$i, TRUE);
                $nquantity        = $this->input->post('nqtyitemtmp'.$i, TRUE);
                $nmaterial        = $this->input->post('nmaterial'.$i, TRUE);
                $ndeliver         = $this->input->post('ndeliver'.$i, TRUE);
                $sisamaterial     = $nmaterial-$ndeliver;
                $keterangan       = $this->input->post('eremark'.$i, TRUE);

                if($nquantity == $nmaterial){
                    $cek = TRUE;
                }else{
                    $cek = FALSE;
                }

                
                $this->mmaster->updatebonkdetail($ibonk,$iproduct,$eproductname,$icolor,$nquantity,$nmaterial,$ndeliver,$sisamaterial,$keterangan,$imaterial,$i,$cek);
                
                // $this->mmaster->updatesaldo($ibonk,$dbonk,$ischedule,$iproduct,$icolor);
            // }
        }  
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                    $data = array(
                        'sukses'    => false,
                    );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ibonk);
                $data = array(
                    'sukses' => true,
                    'kode'      => $ibonk,
                );
            }
        $this->load->view('pesan', $data); 
    }

    public function pendingan(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $this->db->trans_begin();
        $ibonk  = $this->input->post('ibonk', TRUE);
        $dbonk  = $this->input->post('dbonk', TRUE);
        // $eremarkh     = $this->input->post('eremarkh', TRUE);
        $jml          = $this->input->post('jml', TRUE);  
        
        
        $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ibonk);
        // $this->mmaster->updateheader($ibonk,$dbonk,$eremarkh);
        $this->mmaster->deletedetail($ibonk);

        for($i=1;$i<=$jml;$i++){ 
            // if($this->input->post('cek'.$i)=='cek'){                  
                $iproduct           = $this->input->post('iproduct'.$i, TRUE);
                $eproductname       = $this->input->post('eproduct'.$i, TRUE);
                $imaterial          = $this->input->post('imaterial'.$i, TRUE);
                $icolor             = $this->input->post('icolor'.$i, TRUE);
                $ecolorname         = $this->input->post('warna'.$i, TRUE);
                $nquantity          = $this->input->post('qtyproduct'.$i, TRUE);
                $nquantity2         = $this->input->post('qtymaterial'.$i, TRUE);
                $nquantitya         = $this->input->post('nquantity'.$i, TRUE);
                $nquantitym         = $nquantitya + $nquantity2;
                // var_dump($nquantitym);
                // die();
                
                $keterangan          = $this->input->post('eremark'.$i, TRUE);
                if($nquantity == $nquantitym){
                    $cek = TRUE;
                }else{
                    $cek = FALSE;
                }
                $this->mmaster->insertbonkdetail($ibonk,$iproduct,$eproductname,$icolor,$nquantitym,$nquantity,$keterangan,$imaterial,$i,$cek);
                // $this->mmaster->updatesaldo($ibonk,$dbonk,$ischedule,$iproduct,$icolor);
            // }
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
                    'kode'      => $ibonk,
                );
        }
        $this->load->view('pesan', $data); 
        
    }

    /* public function delete(){
        $data = check_role($this->i_menu, 4);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibonk = $this->input->post('i_bonk', true);
        // var_dump($ibonk);
        // die();

        $this->db->trans_begin();
        $data = $this->mmaster->cancel($ibonk);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Cancel Bon Keluar ' . $ibonk);
            echo json_encode($data);
        }
    } */

    public function view(){

        $ibonk  = $this->uri->segment('4');
        $dfrom  = $this->uri->segment('5');
        $dto    = $this->uri->segment('6');

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'data'              => $this->mmaster->cek_data($ibonk)->row(),
            'datadetail'        => $this->mmaster->cek_datadetail($ibonk)->result(),  
            'gudang'            => $this->mmaster->gudang(),          
            'ngadug'            => $this->mmaster->ngadug(),          
            'dfrom'             => $dfrom,
            'dto'               => $dto,
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function approval()
    {
        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ibonk     = $this->uri->segment(4);
        $dfrom     = $this->uri->segment(5);
        $dto       = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Approve " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom,
            'dto'        => $dto,
            'gudang'     => $this->mmaster->gudang(),          
            'ngadug'     => $this->mmaster->ngadug(),    
            'data'       => $this->mmaster->cek_data($ibonk)->row(),
            'datadetail' => $this->mmaster->cek_datadetail($ibonk)->result(),
        );

        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}

/* End of file Cform.php */