<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$folder,$dfrom,$dto){
        $id_company = $this->session->userdata('id_company');
          $cek = $this->db->query("SELECT
                i_bagian
            FROM
                tm_stockopname_qcset
            WHERE
                i_status <> '5'
                AND d_document BETWEEN to_date('$dfrom','01-mm-yyyy') AND to_date('$dto','01-mm-yyyy') AND id_company = '$id_company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$id_company')
            ", FALSE);
          if ($this->session->userdata('i_departement')=='1') {
              $bagian = "";
          }else{
              if ($cek->num_rows()>0) {                
                  $i_bagian = $cek->row()->i_bagian;
                  $bagian = "AND a.i_bagian = '$i_bagian' ";
              }else{
                  $bagian = "AND a.i_bagian IN (SELECT
                          i_bagian
                      FROM
                          tr_departement_cover
                      WHERE
                          i_departement = '".$this->session->userdata('i_departement')."'
                          AND username = '".$this->session->userdata('username')."'
                          AND id_company = '$id_company')";
              }
          }

          $datatables = new Datatables(new CodeigniterAdapter);
          $datatables->query("           
                              SELECT
                                 0 as no,
                                 a.id,
                                 a.id_company,
                                 a.i_document,
                                 to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                 a.i_bagian,
                                 a.i_periode,
                                 d.e_bagian_name,
                                 a.i_status,
                                 a.e_remark,
                                 c.e_status_name,
                                 c.label_color,
                                 f.i_level,
			                     l.e_level_name,
                                 '$i_menu' as i_menu,
                                 '$folder' as folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto 
                              FROM
                                 tm_stockopname_qcset a 
                                 INNER JOIN
                                    tr_status_document c 
                                    ON (c.i_status = a.i_status) 
                                 INNER JOIN
                                    tr_bagian d 
                                    ON (a.id_company = d.id_company 
                                    and a.i_bagian = d.i_bagian)
                                 LEFT JOIN tr_menu_approve f ON
                                    (a.i_approve_urutan = f.n_urut
                                    AND f.i_menu = '$i_menu')
                                 LEFT JOIN public.tr_level l ON
                                    (f.i_level = l.i_level) 
                              WHERE
                                 a.d_document between to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                                 AND a.id_company = '$id_company'
                                 AND a.i_status <> '5' 
                                 $bagian 
                              ORDER BY
                                 d_document DESC,
                                 a.i_document DESC
                            ", FALSE);

          $datatables->edit('e_status_name', function ($data) {
              $i_status = $data['i_status'];
              if ($i_status == '2') {
                  $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
              }
              return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
          });

          $datatables->add('action', function ($data) {
              $id        = trim($data['id']);
              $i_menu    = $data['i_menu'];
              $folder    = $data['folder'];
              $i_status  = $data['i_status'];
              $dfrom     = $data['dfrom'];
              $dto       = $data['dto'];
              $data      = '';
              
              if(check_role($i_menu, 2)){
                  $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
              }
              
              if (check_role($i_menu, 3)) {
                  if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                      $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                  }
              }

              if (check_role($i_menu, 7)) {
                  if ($i_status == '2') {
                      $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                  }
              }   

              if (check_role($i_menu, 4)  && ($i_status=='1')) {
                  $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
              }

              return $data;
          });
          $datatables->hide('id');
          $datatables->hide('i_menu');       
          $datatables->hide('folder');
          $datatables->hide('dfrom');
          $datatables->hide('dto');
          $datatables->hide('label_color');
          $datatables->hide('id_company');
          $datatables->hide('i_status');
          $datatables->hide('i_bagian');
          $datatables->hide('i_level');
		  $datatables->hide('e_level_name');
          return $datatables->generate();
    }

    public function runningid() {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_stockopname_qcset');
        return $this->db->get()->row()->id+1;
    }

    public function runningnumber($thbl, $tahun, $ibagian){
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_stockopname_qcset
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SO';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 4)) AS max
            FROM
              tm_stockopname_qcset
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
            AND id_company = '".$this->session->userdata("id_company")."'
        ", false);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 4){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "0001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.i_type', '08');
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_stockopname_qcset');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function get_bagian($ibagian, $idcompany){
        return $this->db->query("
                                  SELECT
                                    i_bagian, e_bagian_name 
                                    FROM
                                       tr_bagian 
                                    WHERE
                                        i_bagian = '$ibagian' 
                                    AND 
                                        id_company = '$idcompany'
                                ", FALSE);
    }

    public function dataheader($idcompany, $i_document, $ibagian){
        return $this->db->query("
                                  SELECT
                                      id 
                                  FROM
                                     tm_stockopname_qcset 
                                  WHERE
                                    id_company = '$idcompany' 
                                    AND i_bagian = '$ibagian' 
                                    AND i_document = '$i_document'
                                ", FALSE);
    }

    public function dataheader_awal($idcompany, $ddocument, $ibagian){
        $ddocument      = DateTime::createFromFormat('d-m-Y', $ddocument);
        $id_company     = $this->session->userdata('id_company');
        $i_periode      = $ddocument->format('Ym');
        $d_jangka_awal  = '9999-01-01';
        $d_jangka_akhir = '9999-01-31';
        $dfrom          = $ddocument->format('Y-m-01');
        $dto            = $ddocument->format('Y-m-d');
        return $this->db->query("SELECT 
                                a.id_product_wip 
                                FROM tm_panel_item a
                                LEFT JOIN
                                produksi.f_mutasi_saldoawal_pengesettan('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') b 
                                ON (a.id = b.id_panel_item)
                                GROUP BY 1");
    }

    public function datadetail($idcompany, $ddocument, $ibagian) {
          $ddocument      = DateTime::createFromFormat('d-m-Y', $ddocument);
          $id_company     = $this->session->userdata('id_company');
          $i_periode      = $ddocument->format('Ym');
          $d_jangka_awal  = '9999-01-01';
          $d_jangka_akhir = '9999-01-31';
          $dfrom          = $ddocument->format('Y-m-01');
          $dto            = $ddocument->format('Y-m-d');

          return $this->db->query(" SELECT DISTINCT
                x.id_panel_item,
                x.id_company,
                p.id_product_wip,
                p.i_panel,
                p.bagian,
                c.i_product_wip,
                c.e_product_wipname,
                d.e_color_name,
                a.i_material,
                a.id,
                a.e_material_name,
                b.e_satuan_name,
                0 AS n_quantity,
                '' as e_remark 
            FROM
                produksi.f_mutasi_saldoawal_pengesettan('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') x 
                INNER JOIN
                    tm_panel_item p
                    ON (x.id_panel_item = p.id)
                INNER JOIN
                    tr_material a 
                    ON (a.id_company = x.id_company 
                    AND p.id_material = a.id) 
                INNER JOIN
                    tr_satuan b 
                    ON (a.id_company = b.id_company 
                    AND a.i_satuan_code = b.i_satuan_code)
                INNER JOIN
                    tr_product_wip c
                    ON (p.id_product_wip = c.id 
                    AND c.id_company = x.id_company)
                INNER JOIN
                    tr_color d
                    ON (c.i_color = d.i_color
                    AND c.id_company = d.id_company)
            WHERE
                x.id_company = '$id_company'
            ORDER BY 
                x.id_panel_item
            ", FALSE);
            
    }

    public function datagrade() {

          return $this->db->query(" SELECT
                id, 
                i_grade, 
                e_grade_name
            FROM
                produksi.tr_grade_bahan
            ", FALSE);
    }

    /*----------  CARI BARANG  ----------*/
    public function barang($cari, $ibagian, $ddocument) {

          $ddocument      = DateTime::createFromFormat('d-m-Y', $ddocument);
          $id_company     = $this->session->userdata('id_company');
          $i_periode      = $ddocument->format('Ym');
          $d_jangka_awal  = '9999-01-01';
          $d_jangka_akhir = '9999-01-31';
          $dfrom          = $ddocument->format('Y-m-01');
          $dto            = $ddocument->format('Y-m-d');

          return $this->db->query(" SELECT DISTINCT a.id_product_wip, d.i_product_wip , d.e_product_wipname, e.e_color_name 
          FROM tm_panel a
          LEFT JOIN
          tm_panel_item b 
          ON (a.id_product_wip = b.id_product_wip)
          LEFT JOIN
          f_mutasi_saldoawal_pengesettan('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') c 
          ON (b.id = c.id_panel_item)
          INNER JOIN
          tr_product_wip d 
          ON (b.id_product_wip = d.id AND d.id_company = '$id_company' AND d.f_status = 't')
          INNER JOIN 
          tr_color e
          ON (d.i_color = e.i_color AND e.id_company = 4 AND e.f_status = 't')
          WHERE 
          a.id_company = '$id_company'
            ", FALSE);
    }

    public function detailbarang($id, $ibagian, $ddocument) {

        $ddocument      = DateTime::createFromFormat('d-m-Y', $ddocument);
        $id_company     = $this->session->userdata('id_company');
        $i_periode      = $ddocument->format('Ym');
        $d_jangka_awal  = '9999-01-01';
        $d_jangka_akhir = '9999-01-31';
        $dfrom          = $ddocument->format('Y-m-01');
        $dto            = $ddocument->format('Y-m-d');

        return $this->db->query(" SELECT DISTINCT 
        a.id_product_wip, 
        d.i_product_wip , 
        d.e_product_wipname, 
        e.e_color_name,
        c.id_panel_item AS id_panel,
        a.id_company,
        b.i_panel,
        b.bagian,
        f.i_material,
        f.id,
        f.e_material_name,
        0 AS n_quantity,
        '' as e_remark 
        FROM tm_panel a
        LEFT JOIN
        tm_panel_item b 
        ON (a.id_product_wip = b.id_product_wip)
        LEFT JOIN
        f_mutasi_saldoawal_pengesettan('$id_company', '$i_periode', '$d_jangka_awal', '$d_jangka_akhir', '$dfrom', '$dto', '$ibagian') c 
        ON (b.id = c.id_panel_item)
        INNER JOIN
        tr_product_wip d 
        ON (b.id_product_wip = d.id AND d.id_company = 4 AND d.f_status = 't')
        INNER JOIN 
        tr_color e
        ON (d.i_color = e.i_color AND e.id_company = 4 AND e.f_status = 't')
        INNER JOIN
        tr_material f 
        ON (b.id_material = f.id AND f.id_company = 4 AND f.f_status = 't')
        WHERE 
        a.id_company = 4
        AND a.id_product_wip = '$id'
        ORDER BY
        c.id_panel_item
        ", FALSE);
  }

    /*----------  CARI GRADE  ----------*/
    public function cargrade($cari) {


          return $this->db->query(" SELECT
                    id, 
                    i_grade, 
                    e_grade_name
                FROM
                    produksi.tr_grade_bahan
          ", FALSE);
    }

    public function simpan($id,$idcompany,$ibagian,$idocument,$ddocument,$iperiode, $eremarkh){
          $dentry = current_datetime();
          $data = array(
                        'id'           => $id,
                        'id_company'   => $idcompany,
                        'i_document'   => $idocument,
                        'd_document'   => $ddocument,
                        'i_bagian'     => $ibagian,
                        'i_periode'    => $iperiode,
                        'e_remark'     => $eremarkh,
                        'd_entry'      => $dentry,
          );
          $this->db->insert('tm_stockopname_qcset', $data);
    }

    public function simpandetail($idcompany, $id, $idpanel, $qty, $eremark){
          $data = array(
                        'id_company'      => $idcompany,
                        'id_document'     => $id,
                        'id_panel_item'     => $idpanel,
                        'n_quantity'      => $qty,
                        'e_remark'        => $eremark,
          );
      $this->db->insert('tm_stockopname_qcset_item', $data);
    }

    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id, $istatus){
        $dreceive = '';
        $dreceive = date('Y-m-d');
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $data = array(
                            'i_status'  => $istatus,
                            'e_approve' => $iapprove,
                            'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                            'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->update('tm_stockopname_qcset', $data);
    }

    public function dataheader_edit($id){
        return $this->db->query("
                                  SELECT
                                     a.id,
                                     a.i_document,
                                     to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                     a.e_remark,
                                     a.i_status,
                                     a.i_bagian,
                                     b.e_bagian_name 
                                  FROM
                                     tm_stockopname_qcset a 
                                     INNER JOIN
                                        tr_bagian b 
                                        ON (a.i_bagian = b.i_bagian 
                                        AND a.id_company = b.id_company) 
                                  WHERE
                                     a.id = '$id'
                                ", FALSE);
    }

    public function datadetail_edit($id) {
        $id_company     = $this->session->userdata('id_company');
        return $this->db->query("SELECT
            x.id_panel_item,
            x.id_company,
            p.id_product_wip,
            p.i_panel,
            p.bagian,
            c.i_product_wip,
            c.e_product_wipname,
            d.e_color_name,
            a.i_material,
            a.id,
            a.e_material_name,
            b.e_satuan_name,
            x.n_quantity,
            '' as e_remark 
        FROM
            tm_stockopname_qcset_item x 
            INNER JOIN
                tm_panel_item p
                ON (x.id_panel_item = p.id)
            INNER JOIN
                tr_material a 
                ON (a.id_company = x.id_company 
                AND p.id_material = a.id) 
            INNER JOIN
                tr_satuan b 
                ON (a.id_company = b.id_company 
                AND a.i_satuan_code = b.i_satuan_code)
            INNER JOIN
                tr_product_wip c
                ON (p.id_product_wip = c.id 
                AND c.id_company = 4)
            INNER JOIN
                tr_color d
                ON (c.i_color = d.i_color
                AND c.id_company = d.id_company)
        WHERE
            x.id_document = '$id'
            AND x.id_company = '$id_company'
        ORDER BY 
            x.id_panel_item
                                        
                                ", FALSE);
    }

    public function updateheader($id, $eremarkh){
        $data=array(
                    'e_remark' => $eremarkh,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_stockopname_qcset', $data);
    } 

    public function hapusdetail($id) {
          return $this->db->query(" 
                                    DELETE
                                    FROM
                                       tm_stockopname_qcset_item 
                                    WHERE
                                       id_document = '$id'
                                  ", FALSE);
    }

    public function data_export_panel(){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT a.id as id_panel , c.i_product_wip , c.e_product_wipname , d.e_color_name , e.i_material , e.e_material_name , 
            a.i_panel , a.bagian , a.n_qty_penyusun , n_panjang_cm, n_lebar_cm, n_panjang_gelar, n_lebar_gelar, n_hasil_gelar, n_qty_per_gelar, n_efficiency,
            case when f_print = true then 'Ya' else 'Tidak' end as print, case when f_bordir = true then 'Ya' else 'Tidak' end as bordir,   g.e_style_name as e_series_name
            from tm_panel_item a
            inner join tm_panel b on (a.id_product_wip = b.id_product_wip)
            inner join tr_product_wip c on (a.id_product_wip = c.id)
            INNER JOIN tr_color d on (c.i_color = d.i_color AND c.id_company = d.id_company)
            INNER JOIN tr_material e on (a.id_material = e.id AND b.id_company = e.id_company)
            inner join tr_style g on (c.i_style = g.i_style and c.id_company = g.id_company)
            where b.id_company = '$idcompany' and a.f_status = true 
            order by 1,2,3, 5 asc;
        ", FALSE);
    }

    public function cek_panel($id_panel){
        return $this->db->query("
            select a.id as id_panel_item, a.id_product_wip , a.i_panel , a.bagian,
            b.i_product_wip, b.e_product_wipname, c.e_color_name , a.id_material as id, 
            d.i_material , d.e_material_name , e.e_satuan_name 
            from tm_panel_item a 
            inner join tr_product_wip b on (a.id_product_wip = b.id)
            inner join tr_color c on (b.i_color = c.i_color and b.id_company = c.id_company)
            inner join tr_material d on (a.id_material = d.id)
            inner join tr_satuan e on (d.i_satuan_code = e.i_satuan_code and d.id_company = e.id_company)
            where a.id = $id_panel
        ", FALSE);
    }

}
/* End of file Mmaster.php */