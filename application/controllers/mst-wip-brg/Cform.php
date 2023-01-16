<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010211';

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
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data()
    {
        echo $this->mmaster->data($this->i_menu, $this->global['folder']);
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

    public function material()
    {
        $filter = [];
        $data = $this->mmaster->material(str_replace("'","",$this->input->get('q')));
        foreach ($data->result() as $row) {
            $filter[] = array(
                'id'   => $row->id,
                'text' => $row->i_material.'-'.$row->e_material_name,
            );
        }
        echo json_encode($filter);
    }

    public function tambah()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Tambah ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'group_barang'      => $this->mmaster->get_groupbarang()->result(), 
            'kelompok_barang'   => $this->mmaster->get_kelompokbarang()->result(),            
            'jenis_barang'      => $this->mmaster->get_jenis()->result(), 
            'brand'             => $this->mmaster->get_brand()->result(), 
            'style'             => $this->mmaster->get_style()->result(), 
            'statusproduksi'    => $this->mmaster->get_statusproduksi()->result(), 
            'satuan_barang'     => $this->mmaster->get_satuan()->result(), 
            'satuan_berat'      => $this->mmaster->get_satuanberat()->result(),
        );


        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function getgroup(){
        $filter = [];
        $data   = $this->mmaster->getgroup(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach($data->result() as  $igroup){
                $filter[] = array(
                    'id'   => $igroup->i_kode_group_barang,  
                    'text' => $igroup->e_nama_group_barang,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function getcolor(){
        $filter = [];
        $data   = $this->mmaster->get_color(str_replace("'", "", $this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach($data->result() as  $row){
                $filter[] = array(
                    'id'   => $row->i_color,  
                    'text' => $row->e_color_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function getkelompok(){
        $filter = [];
        $data   = $this->mmaster->getkelompok(str_replace("'", "", $this->input->get('q')),$this->input->get('igroup'));
        if ($data->num_rows()>0) {
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'   => $row->i_kode_kelompok,  
                    'text' => $row->e_nama_kelompok,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
    }

    public function getjenis(){
        $filter = [];
        $data   = $this->mmaster->getjenis(str_replace("'", "", $this->input->get('q')),$this->input->get('ikelompok'));
        if ($data->num_rows()>0) {
            foreach($data->result() as $row){
                $filter[] = array(
                    'id'   => $row->i_type_code,  
                    'text' => $row->e_type_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,  
                'text' => "Tidak Ada Data",
            );
        }
        echo json_encode($filter);
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

    public function getsatuan()
    {
        header("Content-Type: application/json", true);
        $this->db->select('i_satuan_code, e_satuan_name AS e_satuan');
        $this->db->from('tr_satuan');
        $this->db->where('f_status', 't');
        $this->db->like('UPPER(e_satuan_name)', 'CENTI');
        echo json_encode($this->db->get()->result_array());
    }

    public function getsatuanmaterial()
    {
        header("Content-Type: application/json", true);
        $data = $this->mmaster->getsatuanmaterial($this->input->post('idmaterial'));
        echo json_encode($data->result_array());
    }

    public function supplier(){
        $filter = [];
        $data   = $this->mmaster->supplier(str_replace("'","",$this->input->get('q')));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->i_supplier,  
                'text' => $key->e_supplier_name,
            );
        }
        echo json_encode($filter);
    }

    public function simpan()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikodebrg           = $this->input->post('ikodebrg', TRUE);
        $enamabrg           = $this->input->post('enamabrg', TRUE);
        $igroupbrg          = $this->input->post('igroupbrg', TRUE);
        $ikelompok          = $this->input->post('ikelompok', TRUE);
        $ijenisbrg          = $this->input->post('ijenisbrg', TRUE);         
        $isatuan            = $this->input->post('isatuan', TRUE);
        $edeskripsi         = $this->input->post('edeskripsi', TRUE);
        $isupplier          = $this->input->post('isupplier', TRUE);
        $npanjang           = $this->input->post('npanjang', TRUE);
        $nlebar             = $this->input->post('nlebar', TRUE);
        $ntinggi            = $this->input->post('ntinggi', TRUE);
        $nberat             = $this->input->post('nberat', TRUE);
        $isatuanberat       = $this->input->post('isatuanberat', TRUE);
        $isatuanukuran      = $this->input->post('isatuanukuran', TRUE);
        $ibrand             = $this->input->post('ibrand', TRUE);
        $istyle             = $this->input->post('istyle', TRUE);  
        $istatusproduksi    = $this->input->post('istatusproduksi', TRUE);
        if ($ikodebrg != '' && $enamabrg != ''){
            $this->db->trans_begin();
            if ($this->input->post('icolor', TRUE)) {
                foreach ($this->input->post('icolor', TRUE) as $icolor) {
                    $cekada = $this->mmaster->cekada($ikodebrg,$icolor);
                    if($cekada->num_rows() > 0){
                        $data = array(
                            'sukses' => false
                        );
                    }else{
                        $this->mmaster->insert($ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, $icolor);
                    }
                }
            }else{
                $cekada = $this->mmaster->cekada($ikodebrg,'00');
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->mmaster->insert($ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, '00');
                }
            }
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $ikodebrg
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikodebrg);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function simpandetail()
    {

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $ikodebrg           = $this->input->post('ikodebrg', TRUE);
        $enamabrg           = $this->input->post('enamabrg', TRUE);
        $igroupbrg          = $this->input->post('igroupbrg', TRUE);
        $ikelompok          = $this->input->post('ikelompok', TRUE);
        $ijenisbrg          = $this->input->post('ijenisbrg', TRUE);         
        $isatuan            = $this->input->post('isatuan', TRUE);
        $edeskripsi         = $this->input->post('edeskripsi', TRUE);
        $isupplier          = $this->input->post('isupplier', TRUE);
        $npanjang           = $this->input->post('npanjang', TRUE);
        $nlebar             = $this->input->post('nlebar', TRUE);
        $ntinggi            = $this->input->post('ntinggi', TRUE);
        $nberat             = $this->input->post('nberat', TRUE);
        $isatuanberat       = $this->input->post('isatuanberat', TRUE);
        $isatuanukuran      = $this->input->post('isatuanukuran', TRUE);
        $ibrand             = $this->input->post('ibrand', TRUE);
        $istyle             = $this->input->post('istyle', TRUE);  
        $istatusproduksi    = $this->input->post('istatusproduksi', TRUE);
        $jml    = $this->input->post('jml', TRUE);

        if ($ikodebrg != '' && $enamabrg != ''){
            $id_material  = $this->input->post('imaterial[]', true);
            $n_quantity   = str_replace(',','',$this->input->post('nquantity[]', true));
            $bagian       = $this->input->post('bagian[]', true);
            $this->db->trans_begin();
            if ($this->input->post('icolor', TRUE)) {
                foreach ($this->input->post('icolor', TRUE) as $icolor) {
                    $cekada = $this->mmaster->cekada($ikodebrg,$icolor);
                    $id = $this->mmaster->runningid();
                    if($cekada->num_rows() > 0){
                        $data = array(
                            'sukses' => false
                        );
                    }else{
                        $this->mmaster->insert($id, $ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, $icolor);

                        if ($jml > 0) {
                            $no = 0;
                            foreach ($id_material as $idmaterial) {
                                $imaterial = $idmaterial;
                                $nquantity  = $n_quantity[$no];
                                $bagian     = $bagian[$no];
                                if (($imaterial != '' || $imaterial != null)) {
                                    $this->mmaster->insertdetail($id,$imaterial,$nquantity,$bagian);
                                }
                                $no++;
                            }
                        } 
                    }
                }
            }else{
                $cekada = $this->mmaster->cekada($ikodebrg,'00');
                $id = $this->mmaster->runningid();
                if($cekada->num_rows() > 0){
                    $data = array(
                        'sukses' => false
                    );
                }else{
                    $this->mmaster->insert($id, $ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, '00');
                    if ($jml > 0) {
                        $no = 0;
                        foreach ($id_material as $idmaterial) {
                            $imaterial = $idmaterial;
                            $nquantity  = $n_quantity[$no];
                            $bagian     = $bagian[$no];
                            if (($imaterial != '' || $imaterial != null)) {
                                $this->mmaster->insertdetail($id,$imaterial,$nquantity,$bagian);
                            }
                            $no++;
                        }
                    } 
                }
            }
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $ikodebrg
                );
                $this->Logger->write('Simpan Data '.$this->global['title'].' Kode : '.$ikodebrg);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function view()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "View ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'group_barang'      => $this->mmaster->get_groupbarang()->result(), 
            'kelompok_barang'   => $this->mmaster->get_kelompokbarang()->result(),            
            'jenis_barang'      => $this->mmaster->get_jenis()->result(), 
            'brand'             => $this->mmaster->get_brand()->result(), 
            'style'             => $this->mmaster->get_style()->result(), 
            'statusproduksi'    => $this->mmaster->get_statusproduksi()->result(), 
            'satuan_barang'     => $this->mmaster->get_satuan()->result(), 
            'satuan_berat'      => $this->mmaster->get_satuanberat()->result(),
            'data'              => $this->mmaster->get_data($this->uri->segment(4))->row(),
            'datadetail'        => $this->mmaster->get_datadetail($this->uri->segment(4))->result(),
            'color'             => $this->mmaster->get_detail($this->uri->segment(4)),
        );

        $this->Logger->write('Membuka Menu View '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    public function edit()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'            => $this->global['folder'],
            'title'             => "Edit ".$this->global['title'],
            'title_list'        => 'List '.$this->global['title'],
            'group_barang'      => $this->mmaster->get_groupbarang()->result(), 
            'kelompok_barang'   => $this->mmaster->get_kelompokbarang()->result(),            
            'jenis_barang'      => $this->mmaster->get_jenis()->result(), 
            'brand'             => $this->mmaster->get_brand()->result(), 
            'style'             => $this->mmaster->get_style()->result(), 
            'statusproduksi'    => $this->mmaster->get_statusproduksi()->result(), 
            'satuan_barang'     => $this->mmaster->get_satuan()->result(), 
            'satuan_berat'      => $this->mmaster->get_satuanberat()->result(),
            'data'              => $this->mmaster->get_data($this->uri->segment(4))->row(),
            'datadetail'        => $this->mmaster->get_datadetail($this->uri->segment(4))->result(),
            'color'             => $this->mmaster->get_detail($this->uri->segment(4)),
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

        $ikodebrgold        = $this->input->post('ikodebrgold', TRUE);
        $ikodebrg           = $this->input->post('ikodebrg', TRUE);
        $enamabrg           = $this->input->post('enamabrg', TRUE);
        $igroupbrg          = $this->input->post('igroupbrg', TRUE);
        $ikelompok          = $this->input->post('ikelompok', TRUE);
        $ijenisbrg          = $this->input->post('ijenisbrg', TRUE);         
        $isatuan            = $this->input->post('isatuan', TRUE);
        $edeskripsi         = $this->input->post('edeskripsi', TRUE);
        $isupplier          = $this->input->post('isupplier', TRUE);
        $npanjang           = $this->input->post('npanjang', TRUE);
        $nlebar             = $this->input->post('nlebar', TRUE);
        $ntinggi            = $this->input->post('ntinggi', TRUE);
        $nberat             = $this->input->post('nberat', TRUE);
        $isatuanberat       = $this->input->post('isatuanberat', TRUE);
        $isatuanukuran      = $this->input->post('isatuanukuran', TRUE);
        $ibrand             = $this->input->post('ibrand', TRUE);
        $istyle             = $this->input->post('istyle', TRUE);  
        $istatusproduksi    = $this->input->post('istatusproduksi', TRUE);
        if ($ikodebrg != '' && $ikodebrgold != '' && $enamabrg != ''){
            $this->db->trans_begin();
            foreach ($this->input->post('icolor', TRUE) as $icolor) {               
                $this->mmaster->update($ikodebrgold,$ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, $icolor);
            }
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $ikodebrg
                );
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ikodebrg);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }

    public function updatedetail()
    {

        $data = check_role($this->i_menu, 3);
        if(!$data){
            redirect(base_url(),'refresh');
        }
        $id                 = $this->input->post('id');
        $ikodebrgold        = $this->input->post('ikodebrgold', TRUE);
        $ikodebrg           = $this->input->post('ikodebrg', TRUE);
        $enamabrg           = $this->input->post('enamabrg', TRUE);
        $igroupbrg          = $this->input->post('igroupbrg', TRUE);
        $ikelompok          = $this->input->post('ikelompok', TRUE);
        $ijenisbrg          = $this->input->post('ijenisbrg', TRUE);         
        $isatuan            = $this->input->post('isatuan', TRUE);
        $edeskripsi         = $this->input->post('edeskripsi', TRUE);
        $isupplier          = $this->input->post('isupplier', TRUE);
        $npanjang           = $this->input->post('npanjang', TRUE);
        $nlebar             = $this->input->post('nlebar', TRUE);
        $ntinggi            = $this->input->post('ntinggi', TRUE);
        $nberat             = $this->input->post('nberat', TRUE);
        $isatuanberat       = $this->input->post('isatuanberat', TRUE);
        $isatuanukuran      = $this->input->post('isatuanukuran', TRUE);
        $ibrand             = $this->input->post('ibrand', TRUE);
        $istyle             = $this->input->post('istyle', TRUE);  
        $istatusproduksi    = $this->input->post('istatusproduksi', TRUE);
        $jml    = $this->input->post('jml', TRUE);
        if ($ikodebrg != '' && $ikodebrgold != '' && $enamabrg != ''){
            $id_material = $this->input->post('imaterial[]', TRUE);
            $n_quantity  = $this->input->post('nquantity[]', TRUE);
            $bagian      = $this->input->post('bagian[]', true);
            $this->db->trans_begin();
            foreach ($this->input->post('icolor', TRUE) as $icolor) {               
                $this->mmaster->update($ikodebrgold,$ikodebrg, $ijenisbrg, $isatuan, $enamabrg, $edeskripsi, $ikelompok, $isupplier, $npanjang, $nlebar, $ntinggi, $nberat, $isatuanberat, $isatuanukuran, $igroupbrg, $ibrand, $istyle, $istatusproduksi, $icolor);
            }

            // if ($jml > 0) {
            //     $this->mmaster->deletedetail($id);
            //     $no = 0;
            //     foreach ($id_material as $idmaterial) {
            //         $imaterial = $idmaterial;
            //         $nquantity  = $n_quantity[$no];
            //         $bagian     = $bagian[$no];
            //         if (($imaterial != '' || $imaterial != null)) {
            //             $this->mmaster->insertdetail($id,$imaterial,$nquantity,$bagian);
            //         }
            //         $no++;
            //     }
            // } 
            
            if($this->db->trans_status() === False){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            }else{
                $this->db->trans_commit();
                $data = array(
                    'sukses'  => true,
                    'kode'    => $ikodebrg
                );
                $this->Logger->write('Update Data '.$this->global['title'].' Kode : '.$ikodebrg);
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan', $data);  
    }
}
/* End of file Cform.php */