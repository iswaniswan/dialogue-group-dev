<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($folder, $i_menu, $dfrom, $dto){
     $idcompany = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_keluar_pengadaan_retur
            WHERE
                i_status <> '5'
                and d_keluar_pengadaan_retur between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$idcompany'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$idcompany')

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
                        AND id_company = '$idcompany')";
            }
        }

    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("   
                           SELECT
                             0 as no,
                             a.id,
                             a.i_keluar_pengadaan_retur,
                             to_char(a.d_keluar_pengadaan_retur, 'dd-mm-yyyy') as d_keluar_pengadaan_retur,
                             a.i_tujuan,
                             b.e_bagian_name,
                             to_char(a.d_receive_jahit, 'dd-mm-yyyy') as d_receive_jahit,
                             a.e_remark,
                             a.id_company,
                             f.i_level,
                             l.e_level_name,
                             a.i_status,
                             c.e_status_name,
                             a.i_bagian,
                             c.label_color as label,
                             '$dfrom' AS dfrom,
                             '$dto' AS dto,
                             '$i_menu' as i_menu,
                             '$folder' AS folder
                          FROM
                             tm_keluar_pengadaan_retur a 
                             JOIN
                                tr_bagian b 
                                ON (a.i_tujuan = b.i_bagian AND a.id_company = b.id_company) 
                             JOIN
                                tr_status_document c 
                                ON (a.i_status = c.i_status) 
                            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
                            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
                             WHERE 
                                a.id_company = '$idcompany'
                             AND
                                a.i_status <> '5'
                          $bagian
                          ORDER BY
                             a.i_keluar_pengadaan_retur asc
        ",false);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id         = trim($data['id']);
            $i_menu     = $data['i_menu'];
            $folder     = $data['folder'];
            $i_status   = $data['i_status'];
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $data       = '';

            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }   

            if (check_role($i_menu, 5)) {
                if ($i_status == '6') {
                    $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
                } 
            }

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            }
          return $data;
        });

        $datatables->hide('folder'); 
        $datatables->hide('i_menu');
        $datatables->hide('label');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_company');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('i_tujuan');
        return $datatables->generate();
  }

  public function bagian() {
        /* 
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('b.id_company', $this->session->userdata('id_company'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
         */

        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
            INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
            LEFT JOIN tr_type c on (a.i_type = c.i_type)
            LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
            WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
            ORDER BY 4, 3 ASC NULLS LAST
        ", false);
  }

  public function tujuan($i_menu, $idcompany){
      return $this->db->query(" 
                                SELECT 
                                    a.*,
                                    b.e_bagian_name 
                                FROM 
                                    tr_tujuan_menu a
                                JOIN tr_bagian b 
                                ON a.i_bagian = b.i_bagian AND a.id_company = b.id_company
                                WHERE
                                  a.i_menu = '$i_menu'
                                  AND a.id_company = '$idcompany'");
  }

  public function runningnumber($thbl,$tahun,$ibagian) {
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT 
                substring(i_keluar_pengadaan_retur, 1, 3) AS kode 
            FROM tm_keluar_pengadaan_retur 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'STB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_keluar_pengadaan_retur, 10, 6)) AS max
            FROM
                tm_keluar_pengadaan_retur
            WHERE to_char (d_keluar_pengadaan_retur, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            AND substring(i_keluar_pengadaan_retur, 1, 3) = '$kode'
            AND substring(i_keluar_pengadaan_retur, 5, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 6){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "000001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
  }

  public function cek_kode($kode,$ibagian) {
      $this->db->select('i_keluar_pengadaan_retur');
      $this->db->from('tm_keluar_pengadaan_retur');
      $this->db->where('i_keluar_pengadaan_retur', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  public function cek_kodeedit($kode,$kodeold, $ibagian) {
      $this->db->select('i_keluar_pengadaan_retur');
      $this->db->from('tm_keluar_pengadaan_retur');
      $this->db->where('i_keluar_pengadaan_retur', $kode);
      $this->db->where('i_keluar_pengadaan_retur <>', $kodeold);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->session->userdata('id_company'));
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  /*----------  CARI BARANG  ----------*/

    public function product($cari) {
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("            
                                  SELECT DISTINCT 
                                      a.id,
                                      a.i_product_wip,
                                      UPPER(a.e_product_wipname) AS e_product_wipname,
                                      b.e_color_name
                                  FROM
                                      tr_product_wip a
                                  INNER JOIN tr_color b ON
                                      (a.i_color = b.i_color
                                      AND a.id_company = b.id_company)
                                  WHERE
                                      a.id_company = '$idcompany'
                                      AND a.f_status = 't'
                                      AND b.f_status = 't'
                                      AND (a.i_product_wip ILIKE '%$cari%'
                                      OR a.e_product_wipname ILIKE '%$cari%')
                                  ORDER BY
                                      a.i_product_wip ASC
                                ", FALSE);
    }

    /*----------  DETAIL BARANG  ----------*/

    public function detailproduct($id) {
        return $this->db->query("            
                                  SELECT
                                      a.id AS id_product_wip,
                                      a.i_product_wip,
                                      UPPER(a.e_product_wipname) AS e_product_wipname,
                                      d.id AS id_color,
                                      d.e_color_name,
                                      c.id AS id_material,
                                      c.i_material,
                                      UPPER(c.e_material_name) AS e_material_name
                                  FROM
                                      tr_product_wip a
                                  INNER JOIN tr_polacutting_new b ON
                                      (a.id = b.id_product_wip
                                      AND a.id_company = b.id_company)
                                  INNER JOIN tr_material c ON
                                      (b.id_material = c.id
                                      AND a.id_company = c.id_company)
                                  INNER JOIN tr_color d ON
                                      (a.i_color = d.i_color
                                      AND a.id_company = d.id_company)
                                  WHERE
                                      a.f_status = 't'
                                      AND a.id = '$id'
                                      AND a.id_company = '".$this->session->userdata('id_company')."'
                                      AND b.f_marker_utama = 't'
                                  ORDER BY
                                      a.i_product_wip,
                                      c.i_material ASC
                                ", FALSE);
    }


    /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_pengadaan_retur');
        return $this->db->get()->row()->id+1;
    }

    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremarkh)
    {
        $data = array(
                      'id'                        => $id,
                      'id_company'                => $this->session->userdata('id_company'),
                      'i_keluar_pengadaan_retur'  => $ibonk,
                      'd_keluar_pengadaan_retur'  => $datebonk,
                      'i_bagian'                  => $ibagian,
                      'i_tujuan'                  => $itujuan,
                      'e_remark'                  => $eremarkh,
                      'i_status'                  => '1',
                      'd_entry'                   => current_datetime(),
        );
        $this->db->insert('tm_keluar_pengadaan_retur', $data);
    }

    // public function insertdetail($id,$idproductwip,$idmaterial,$nquantitywip,$nquantitymat, $eremark)
    public function insertdetail($id, $idproduct, $nquantitywip, $eremarkwip)
    {
        $data = array(
                        'id_company'                => $this->session->userdata('id_company'),
                        'id_keluar_pengadaan_retur' => $id,
                        'id_product_wip'            => $idproduct,
                        'n_quantity_product_wip'    => $nquantitywip,
                        'e_remark_product_wip'      => $eremarkwip,
                        'n_sisa_wip'                => $nquantitywip,
        );
        $this->db->insert('tm_keluar_pengadaan_retur_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id) {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
            a.id,
            a.i_keluar_pengadaan_retur,
            to_char(a.d_keluar_pengadaan_retur, 'dd-mm-yyyy') as d_keluar_pengadaan,
            a.i_bagian,
            a.i_tujuan,
            a.d_receive_jahit,
            a.e_remark,
            a.i_status 
        FROM
            tm_keluar_pengadaan_retur a 
        WHERE
            a.id = '$id'
        AND 
            a.id_company = '$idcompany' 
        ORDER BY
            d_keluar_pengadaan_retur asc
    ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id) {
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT
                b.id_product_wip,
                c.i_product_wip,
                c.e_product_wipname,
                d.id,
                c.i_color,
                d.e_color_name,
                b.n_quantity_product_wip as n_quantity_wip, 
                b.e_remark_product_wip as e_remark_wip
            FROM
                tm_keluar_pengadaan_retur a 
                JOIN
                tm_keluar_pengadaan_retur_item b 
                ON (a.id = b.id_keluar_pengadaan_retur) 
                JOIN
                tr_product_wip c 
                ON (b.id_product_wip = c.id AND a.id_company = c.id_company) 
                JOIN
                tr_color d 
                ON (c.i_color = d.i_color AND a.id_company = d.id_company) 
            WHERE
                a.id = '$id'
            AND 
                a.id_company = '$idcompany'
        ", FALSE);
    }


    public function updateheader($id, $ibonk, $datebonk, $ibagian, $itujuan, $eremarkh)
    {
        $data = array(
                      'i_keluar_pengadaan_retur' => $ibonk,
                      'd_keluar_pengadaan_retur' => $datebonk,
                      'i_bagian'                 => $ibagian,
                      'i_tujuan'                 => $itujuan,
                      'e_remark'                 => $eremarkh,
                      'd_update'                 => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_pengadaan_retur', $data);
    }

    public function deletedetail($id){
        $this->db->query("DELETE FROM tm_keluar_pengadaan_retur_item WHERE id_keluar_pengadaan_retur='$id'", false);
    }

    /* 
    public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_pengadaan_retur', $data);
    }  
    */


    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
                from tm_keluar_pengadaan_retur a
                inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
                where a.id = '$id'
                group by 1,2", FALSE)->row();
            if ($istatus == '3') {
                if ($awal->i_approve_urutan - 1 == 0 ) {
                    $data = array(
                        'i_status'  => $istatus,
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
                }
                $this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
                if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
                    
                    $data = array(
                        'i_status'  => $istatus,
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->session->userdata('username'),
                        'd_approve' => date('Y-m-d'),
                    );
                } else {
                    $data = array(
                        'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
                }
                $now = date('Y-m-d');
                $this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
                    ('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_keluar_pengadaan_retur');", FALSE);
            }
        } else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_pengadaan_retur', $data);
    }
}
/* End of file Mmaster.php */