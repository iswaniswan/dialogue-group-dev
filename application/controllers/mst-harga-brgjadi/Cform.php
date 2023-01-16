<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2010502';

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
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title'],
            'dfrom'     => $dfrom,
            'dto'       => $dto,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformlist', $data);
    }

    function data(){
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

    public function tambah(){

        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah ".$this->global['title'],
            'title_list'    => ' List '.$this->global['title'],
            'dfrom'         => $this->uri->segment(4),
            'dto'           => $this->uri->segment(5),
            'bagian'        => $this->mmaster->bagianpembuat()->result(),
            'number'        => "TF-".date('ym')."-123456"            
        );

        $this->Logger->write('Membuka Menu Tambah '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformadd', $data);
    }

    public function cekkode(){
        $data = $this->mmaster->cek_kode($this->input->post('kode',TRUE),$this->input->post('ibagian',TRUE));
        if ($data->num_rows()>0) {
            echo json_encode(1);
        }else{
            echo json_encode(0);
        }
    }

    public function number(){
        $number = "";
        if ($this->input->post('tgl', TRUE) != '') {
            $number = $this->mmaster->runningnumber(date('ym', strtotime($this->input->post('tgl', TRUE))),date('Y', strtotime($this->input->post('tgl', TRUE))),$this->input->post('ibagian', TRUE));
        }
        echo json_encode($number);
    }

    public function kasbank(){
        $filter = [];
        $data   = $this->mmaster->kasbank(strtoupper($this->input->get('q')));
        if ($data->num_rows()>0) {
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id,
                    'text'  => $row->e_kas_name.' ('.$row->e_coa_name.')',
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    public function kasbanktujuan(){
        $ikasbankaw = $this->input->post('ikasbankaw', TRUE);
        $query = $this->mmaster->kasbanktujuan($ikasbankaw);
        if($query->num_rows()>0) {
            $c  = "";
            $jenis = $query->result();
            foreach($jenis as $row) {
                $c.="<option value=".$row->id." >".$row->e_kas_name.' ('.$row->e_coa_name.')'."</option>";
            }
            $kop  = $c;
            echo json_encode(array(
                'kop'   => $kop
            ));
        }else{
            $kop  = "<option value=\"\">Data Kosong</option>";
            echo json_encode(array(
                'kop'    => $kop,
                'kosong' => 'kopong'
            ));
        }
    }


    public function simpan(){
        $data = check_role($this->i_menu, 1);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $ikasbankaw      = $this->input->post('ikasbankaw', TRUE);
        $ikasbankak      = $this->input->post('ikasbankak', TRUE);
        $vnilai         = str_replace(',','',$this->input->post('vnilai',TRUE));
        $eremark      = $this->input->post('eremark', TRUE);

        if($ibagian != ''  && $idocument != ''){
            $cekkode = $this->mmaster->cek_kode($idocument, $ibagian);
            if($cekkode->num_rows()>0){
                $data = array(
                    'sukses' => false,
                    'kode' => "",
                );
            }else{
                $this->db->trans_begin();
                $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
                $id = $this->mmaster->runningid();
                $this->mmaster->insertheader($id, $idocument, $datedocument, $ibagian, $ikasbankaw, $ikasbankak, $vnilai, $eremark);

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    $data = array(
                        'sukses' => false,
                        'kode' => "",
                    );
                } else {
                    $this->db->trans_commit();
                    $data = array(
                        'sukses' => true,
                        'kode'   => $idocument,
                        'id'     => $id,
                    );
                }
            }
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function edit()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'bagian'     => $this->mmaster->bagianpembuat()->result(),
            'number'     => "TF-".date('ym')."-123456",          
            'head'       => $this->mmaster->cek_data($id)->row(),
        );

        $this->Logger->write('Membuka Menu Edit ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformedit', $data);
    }

     public function getcustomeredit(){
        $filter = [];

        $data   = $this->mmaster->customer(strtoupper($this->input->get('q')));
        if ($data->num_rows()>0) {
            $filter[] = array(
                    'id'    => 'ALL',
                    'text'  => "Semua Customer",
            );
            foreach ($data->result() as $row) {
                $filter[] = array(
                    'id'    => $row->id,
                    'text'  => $row->e_customer_name,
                );
            }
        }else{
            $filter[] = array(
                'id'   => null,
                'text' => "Tidak Ada Data"
            );
        }
        echo json_encode($filter);
    }

    function getitemcustomer_edit(){
        header("Content-Type: application/json", true);
        $icustomer      = $this->input->post('icustomer');
        $id            = $this->input->post('id');
        
        $data = $this->mmaster->getitemcustomer_edit($icustomer, $id);


        $dataa = array(
            'data'       => $data->result_array(),
            'dataitem'   => $this->mmaster->getitemcustomer_edit($icustomer, $id)->result_array(),
        );
        echo json_encode($dataa);
    }

    public function update()
    {
        $data = check_role($this->i_menu, 3);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        $id             = $this->input->post('id', TRUE);
        $ibagian        = $this->input->post('ibagian', TRUE);
        $idocument      = $this->input->post('idocument', TRUE);
        $ddocument      = $this->input->post('ddocument', TRUE);
        if ($ddocument) {
            $tmp   = explode('-', $ddocument);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $datedocument = $year . '-' . $month . '-' . $day;
        }
        $ikasbankaw      = $this->input->post('ikasbankaw', TRUE);
        $ikasbankak      = $this->input->post('ikasbankak', TRUE);
        $vnilai         = str_replace(',','',$this->input->post('vnilai',TRUE));
        $eremark      = $this->input->post('eremark', TRUE);


        if($ibagian != ''  && $idocument != '' ){
            $this->db->trans_begin();
            $this->Logger->write('Simpan Data ' . $this->global['title'] . ' Kode : ' . $idocument);
            $this->mmaster->updateheader($id, $idocument, $datedocument, $ibagian, $ikasbankaw, $ikasbankak, $vnilai, $eremark);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false,
                );
            } else {
                $this->db->trans_commit();
                $data = array(
                    'sukses' => true,
                    'kode'   => $idocument,
                    'id'     => $id,
                );
            }
            
        }else{
            $data = array(
                'sukses' => false,
            );
        }
        $this->load->view('pesan2', $data);
    }

    public function changestatus()
    {
        $id = $this->input->post('id', true);
        $istatus = $this->input->post('istatus', true);
        $estatus = $this->mmaster->estatus($istatus);
        $this->db->trans_begin();
        $this->mmaster->changestatus($id, $istatus);
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode (false);
        } else {
            $this->db->trans_commit();
            $this->Logger->write('Update Status ' . $this->global['folder'] . ' Menjadi : ' . $estatus . ' Id : ' . $id);
            echo json_encode(true);
        }
    }

    public function view()
    {
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

       
        $i_hpp         = $this->uri->segment(4);
        $i_hpp     = str_replace("tandatambah", "+", $i_hpp);
        $i_hpp     = str_replace("tandadan", "&", $i_hpp);
        $i_hpp     = str_replace("tandaslash", "/", $i_hpp);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);
        $id_company_hpp        = $this->uri->segment(7);
        $id_company = $this->session->userdata('id_company');
        $key = $this->db->query("
            SELECT
                *
            FROM
                tr_hpp_link a
            WHERE
                a.id_company = '$id_company'
        ", FALSE)->row();

        $cek = $this->db->query("
                  SELECT
                     *
                  FROM
                      dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                      $$
                      select a.i_hpp, to_char(a.d_hpp, 'dd-mm-yyyy') as d_hpp, a.i_product, a.i_motif, a.v_biaya_ekspedisi, a.n_overhead, a.v_overhead,
                      a.v_hpp, a.f_acc1, a.i_acc1, to_char(a.d_acc1, 'dd-mm-yyyy') as d_acc1, a.f_rev1, a.f_acc2, a.i_acc2, to_char(a.d_acc2, 'dd-mm-yyyy') as d_acc2, a.f_rev2, a.f_active,
                      b.e_motif, c.i_redaksi, a.n_hjp1, a.v_hjpin1, a.n_hjp2, a.v_hjpin2, a.n_hjp3, a.v_hjpin3, a.n_hjp4,
                      a.v_hjpin4, a.n_hjp5, a.v_hjpin5, a.n_hjp6, a.v_hjpin6, a.v_hjpex1, a.v_hjpex2, a.v_hjpex3, a.v_hjpex4,
                      a.v_hjpex5, a.v_hjpex6, a.id
                      from tr_motif b, tm_hpp a
                      left join tm_redaksi c on (a.i_hpp=c.i_hpp) where a.i_motif=b.i_motif and a.i_hpp='$i_hpp'
                      $$
                      ) AS tm_hpp (
                        i_hpp varchar(255), d_hpp text, i_product varchar(255), i_motif varchar(255), v_biaya_ekspedisi numeric(12,2), n_overhead numeric(5,2), v_overhead numeric(12,2), v_hpp numeric(12,2), f_acc1 boolean, i_acc1 varchar(255), d_acc1 text, f_rev1 boolean, f_acc2 boolean, i_acc2 varchar(255), d_acc2 text, f_rev2 boolean, f_active boolean, e_motif varchar(255), i_redaksi varchar(255), n_hjp1 numeric(5,2), v_hjpin1 numeric(12,2), n_hjp2 numeric(5,2), v_hjpin2 numeric(12,2), n_hjp3 numeric(5,2), v_hjpin3 numeric(12,2), n_hjp4 numeric(5,2), v_hjpin4 numeric(12,2), n_hjp5 numeric(5,2), v_hjpin5 numeric(12,2), n_hjp6 numeric(5,2), v_hjpin6 numeric(12,2), v_hjpex1 numeric(12,2), v_hjpex2 numeric(12,2), v_hjpex3 numeric(12,2), v_hjpex4 numeric(12,2), v_hjpex5 numeric(12,2), v_hjpex6 numeric(12,2), id integer )
                ");

        $data_product;

        $jahit = $this->db->query("
                          SELECT
                             *
                          FROM
                          dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                          $$
                          select a.*, b.n_quantity,b.v_harga
                           from tm_hpp_jahit a, tr_aksesorisjahit_harga b
                           where a.i_aksesorisjahit=b.i_aksesorisjahit and a.i_aksesorisjahit_harga=b.i_aksesorisjahit_harga
                           and a.i_hpp='$i_hpp' and a.i_elemen='02' and b.i_company='$id_company_hpp' order by a.id
                          $$
                          ) AS tm_hpp (
                            i_hpp varchar(255), i_product varchar(255), i_motif varchar(255), i_elemen varchar(255),i_aksesorisjahit varchar(255), 
                            i_aksesorisjahit_jenis varchar(255), i_color varchar(255), i_uom varchar(255), d_hpp date, i_aksesorisjahit_harga varchar(255),
                            n_jumlah_pemakaian numeric(10,2), v_pemakaian numeric(10,2), v_uom_harga numeric(10,2), n_persen_tambahan numeric(5,2),
                            id integer, i_company varchar(255), n_jumlah_uom numeric(10,2), n_quantity numeric(10,2), v_harga numeric(12,2)
                          );
                ");
        $bahanbaku = $this->db->query("
                            SELECT
                             *
                            FROM
                            dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                            $$
                            select a.*, b.e_remark, c.e_color from tm_hpp_bahanbaku a, tr_product_item b, tr_color c
                            where a.i_hpp='$i_hpp' and a.i_elemen='01' and a.i_color=c.i_color
                            and a.i_product=b.i_product and a.i_motif=b.i_motif and a.i_item=b.i_item order by a.id
                            $$
                              ) AS tm_hpp (
                                i_hpp varchar(255), i_product varchar(255), i_motif varchar(255), i_elemen varchar(255), i_item varchar(255), 
                                i_bahan_baku varchar(255), i_color varchar(255), i_uom varchar(255), d_hpp date, i_bahanbaku_harga varchar(255),
                                n_panjang numeric(10,2), n_lebar numeric(10,2), n_panjang_pemakaian numeric(10,2), n_lebar_pemakaian numeric(5,2),
                                n_jumlah_pemakaian numeric(10,2), v_pemakaian numeric(12,2), v_uom_harga numeric(12,2), n_persen_tambahan numeric(5,2),
                                id integer, i_company varchar(255), e_remark varchar(255), e_color varchar(255)
                              );
                ");
        $packing = $this->db->query("
                              SELECT
                                 *
                              FROM
                                  dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                                  $$
                                   select a.*, b.n_quantity,b.v_harga, b.i_aksesorispacking, c.e_aksesorispacking
                               from tm_hpp_packing a, tr_aksesorispacking_harga b, tr_aksesorispacking c
                               where a.i_item=b.i_aksesorispacking and a.i_aksesorispacking_harga=b.i_aksesorispacking_harga
                               and a.i_hpp='$i_hpp' and a.i_elemen='03' and b.i_aksesorispacking = c.i_aksesorispacking 
                               and b.i_company='$id_company_hpp' order by a.id
                                  $$
                                  ) AS tm_hpp (
                                    i_hpp varchar(255), i_product varchar(255), i_motif varchar(255), i_elemen varchar(255), i_item varchar(255), 
                                    i_uom varchar(255), d_hpp date, i_aksesorispacking_harga varchar(255),
                                    n_jumlah_pemakaian numeric(10,2), v_pemakaian numeric(12,2), v_uom_harga numeric(12,2), n_persen_tambahan numeric(5,2),
                                    id integer, i_company varchar(255), n_jumlah_uom numeric(10,2), n_quantity numeric(10,2), v_harga numeric(12,2), 
                                    i_aksesorispacking varchar(255), e_aksesorispacking varchar(255)
                                  );
                ");
        $operasi = $this->db->query("
                            SELECT
                                 *
                            FROM
                                dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                                $$
                                select * from tm_hpp_operasional where i_hpp = '$i_hpp' and i_elemen = '04'
                                $$
                                ) AS tm_hpp (
                                i_hpp varchar(255), i_product varchar(255), i_motif varchar(255), i_elemen varchar(255), i_item varchar(255), 
                                i_uom varchar(255), d_hpp date, i_operasional_harga varchar(255), n_jumlah_pemakaian numeric(10,2), v_pemakaian numeric(12,2), 
                                v_uom_harga numeric(12,2), n_persen_tambahan numeric(5,2),
                                id integer, i_company varchar(255)
                                );
                ");

        $data_operasional = $this->db->query("
                            SELECT
                                *
                            FROM
                            dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                            $$
                            select a.*, b.v_menit, c.i_operasional, c.e_operasional
                            from tm_hpp_operasional a, tr_operasional_harga b, tr_operasional c
                            where a.i_operasional_harga=b.i_operasional_harga and a.i_hpp='$i_hpp' and a.i_elemen='04' 
                            and a.i_company = '$id_company_hpp' and a.i_company=b.i_company
                            and a.i_item=c.i_operasional order by a.id
                            $$
                            ) AS tm_hpp (
                                i_hpp varchar(255), i_product varchar(255), i_motif varchar(255), i_elemen varchar(255), i_item varchar(255), 
                                i_uom varchar(255), d_hpp date, i_operasional_harga varchar(255), n_jumlah_pemakaian numeric(10,2), v_pemakaian numeric(12,2), 
                                v_uom_harga numeric(12,2), n_persen_tambahan numeric(5,2), id integer, i_company varchar(255), v_menit numeric(12,2), 
                                i_operasional varchar(255), e_operasional varchar(255)
                            );
                ");
        $data_jasa = $this->db->query("
                          SELECT
                             *
                          FROM
                              dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                              $$
                              select a.*, b.e_jasa
                          from tm_hpp_jasa a, tr_jasa b
                          where a.i_hpp='$id_company_hpp' and a.i_elemen='05' and a.i_item=b.i_jasa order by a.id
                              $$
                              ) AS tm_hpp (
                                i_hpp varchar(255), i_product varchar(255), i_motif varchar(255), i_elemen varchar(255), i_item varchar(255), 
                                v_jasa numeric(12,2), id integer, i_company varchar(255), e_jasa varchar(255)
                              );
                ");

        $data_operasional_harga = $this->db->query("
                          SELECT
                             *
                          FROM
                              dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                              $$
                              select * from tr_operasional_harga where i_company = '$id_company_hpp'
                              $$
                              ) AS tm_hpp (
                                i_operasional_harga varchar(255), d_operasional_harga date, v_umk numeric(12,2), v_bpjs_ketenagakerjaan numeric(12,2), n_bpjs_ketenagakerjaan numeric(5,2),
                                v_bpjs_kesehatan numeric(12,2), n_bpjs_kesehatan numeric(5,2), v_cuti numeric(12,2), n_cuti numeric(5,2), v_thr numeric(12,2), n_thr numeric(5,2), 
                                v_sabtu numeric(12,2), n_sabtu numeric(5,2), v_lain numeric(12,2), n_lain numeric(5,2), v_biaya numeric(12,2), n_biaya numeric(5,2), v_menit numeric(12,2), 
                                v_detik numeric(12,2), id integer, i_company character varying(255)
                              );
                ");
        $countbahanbaku = $this->db->query("
                          SELECT
                             *
                          FROM
                              dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                              $$
                              select count(i_hpp) as jmlbb from tm_hpp_bahanbaku where i_hpp='$i_hpp'
                              $$
                              ) AS tm_hpp (
                                jmlbb bigint
                              );
        ");

        $countjahit = $this->db->query("
                          SELECT
                             *
                          FROM
                              dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                              $$
                              select count(i_hpp) as jmljht from tm_hpp_jahit where i_hpp='$i_hpp'
                              $$
                              ) AS tm_hpp (
                                jmljht bigint
                              );
        ");

        $countpacking = $this->db->query("
                          SELECT
                             *
                          FROM
                              dblink('host=$key->url_db port=$key->port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                              $$
                              select count(i_hpp) as jmlpc from tm_hpp_packing where i_hpp='$i_hpp' and i_elemen='03'
                              $$
                              ) AS tm_hpp (
                                jmlpc bigint
                              );
        ");

        if ($cek->num_rows() > 0) {
            $data = array(
                'folder'            => $this->global['folder'],
                'title'             => "View " . $this->global['title'],
                'title_list'        => 'List ' . $this->global['title'],
                'dfrom'             => $dfrom, 
                'dto'               => $dto,        
                'data'              => $cek->row(),
                'jahit'             => $jahit,
                'bahanbaku'         => $bahanbaku,
                'packing'           => $packing,
                'operasi'           => $operasi,
                'data_operasional'  => $data_operasional,
                'data_jasa'         => $data_jasa,
                'data_operasional_harga' => $data_operasional_harga,
                'countbahanbaku'         => $countbahanbaku,
                'countjahit'             => $countjahit,
                'countpacking'           => $countpacking,
                'url_db'            => $key->url_db,
                'port'              => $key->port,
                'user_postgre'      => $key->user_postgre,
                'password_postgre'  => $key->password_postgre,
                'db_name'           => $key->db_name,
            );
        }


        $this->Logger->write('Membuka Menu View ' . $this->global['title']);
        $this->load->view($this->global['folder'] . '/vformview', $data);
    }

    public function approval()
    {

        $data = check_role($this->i_menu, 7);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }

        
        $id         = $this->uri->segment(4);
        $dfrom      = $this->uri->segment(5);
        $dto        = $this->uri->segment(6);

        $data = array(
            'folder'     => $this->global['folder'],
            'title'      => "Edit " . $this->global['title'],
            'title_list' => 'List ' . $this->global['title'],
            'dfrom'      => $dfrom, 
            'dto'        => $dto,
            'id'         => $id,
            'bagian'     => $this->mmaster->bagianpembuat()->result(),
            'number'     => "TF-".date('ym')."-123456",          
            'head'       => $this->mmaster->cek_data($id)->row(),
        );


        $this->Logger->write('Membuka Menu View ' . $this->global['title']);

        $this->load->view($this->global['folder'] . '/vformapprove', $data);
    }
}
/* End of file Cform.php */