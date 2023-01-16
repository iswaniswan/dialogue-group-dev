<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  function data($i_menu,$folder,$dfrom,$dto){
     if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE d_sj BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }

    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT ROW_NUMBER() OVER (ORDER BY sj.i_sj) as nomor,
                                sj.i_sj as i_sj,
                                sj.d_sj as d_sj,
                                sj.i_supplier,
                                s.e_supplier_name as supplier,
                                sj.e_no_dok_supplier,
                                sj.e_remark as ket,
                                sj.f_cancel as f_cancel,
                                sj.i_status,
                                st.e_status,
                                st.label_color as label,
                                '$i_menu' as i_menu
                            from
                               tm_sjmasukmakloon sj 
                               inner join
                                  tr_supplier s 
                                  on (sj.i_supplier = s.i_supplier) 
                                inner join 
                                    tm_sjmasukmakloon_detail a
                                    on (sj.i_sj = a.i_sj)
                                inner join
                                    tm_status_dokumen st
                                    on sj.i_status = st.i_status
                                    $where
                            group by
                                sj.i_sj,
                                sj.d_sj,
                                sj.i_supplier,
                                s.e_supplier_name,
                                sj.e_no_dok_supplier,
                                sj.e_remark,
                                sj.f_cancel,
                                sj.i_status,
                                st.e_status,
                                st.label_color", FALSE);

        $datatables->edit('f_cancel', function ($data) {
          $f_cancel = trim($data['f_cancel']);
          if($f_cancel == 't'){
             return  "Batal";
          }else {
            return "Aktif";
          }
         });

        $datatables->edit('e_status', function ($data) {
            $f_cancel = trim($data['f_cancel']);
            if($f_cancel == 't'){
              return '<span class="label label-danger label-rouded">Batal</span>';
            }else {
              return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status'].'</span>';
            }
        });

        $datatables->add('action', function ($data) {
            $sj         = trim($data['i_sj']);
            $isupplier  = trim($data['i_supplier']);
            $i_status   = trim($data['i_status']);
            $f_cancel   = trim($data['f_cancel']);
            $i_menu     = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='View' onclick='show(\"sjmasukmakloon/cform/view/$sj/$isupplier/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_cancel == 'f' && $i_status !='6' && $i_status != '4'){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"sjmasukmakloon/cform/edit/$sj/$isupplier/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 1)&& $f_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
               $data .= "<a href=\"#\" title='Approve' onclick='show(\"sjmasukmakloon/cform/approve/$sj/$isupplier/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;";
            }
           if ($f_cancel!='t' && $i_status != '6' && $i_status !='4') {
                $data .= "<a href=\"#\" title='Delete' onclick='cancel(\"$sj\"); return false;'><i class='ti-close'></i></a>&nbsp;&nbsp;";
            }
      return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_supplier');
        $datatables->hide('i_status');
        $datatables->hide('f_cancel');
        $datatables->hide('label');

        return $datatables->generate();
  }

  public function bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany){
       if(trim($idepart) == '1'){
        return $this->db->query(" SELECT a.i_departemen as i_departement, a.e_nama_master as e_departement_name from tr_master_gudang a order by a.i_kode_master", FALSE);
      }else{
        $where = "WHERE a.username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";

        return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name, d.i_bagian
                                  from public.tm_user_deprole a
                                  inner join public.tr_departement b on a.i_departement = b.i_departement
                                  inner join public.tr_level c on a.i_level = c.i_level
                                  inner join public.tm_user d on a.id_company = d.id_company and a.username = d.username $where", FALSE);
      }
  }

  public function bacasupplier(){
      return $this->db->query("
                                SELECT DISTINCT
                                    a.i_supplier,
                                    b.e_supplier_name
                                FROM
                                    tm_sjkeluarmakloon a
                                    LEFT JOIN 
                                        tr_supplier b
                                        ON (a.i_supplier = b.i_supplier)
                                WHERE a.i_status='6'
                            ", FALSE)->result();
  }

  public function getrefferensi($isupplier){
      return $this->db->query("
                                SELECT
                                    i_sj,
                                    to_char(d_sj, 'dd-mm-yyyy') AS d_sj 
                                FROM
                                    tm_sjkeluarmakloon
                                WHERE
                                    i_supplier = '$isupplier'
                                AND i_status= '6'",FALSE);
  }

  public function getsjkm($isjkm,$gudang){
    $in_str = "'".implode("', '", $isjkm)."'";
    $and   = "AND sj.i_sj IN (".$in_str.")";
      return $this->db->query("
                                select
                                   sj.i_supplier,
                                   s.e_supplier_name,
                                   sj.i_type_makloon,
                                   ma.e_type_makloon 
                                from
                                   tm_sjkeluarmakloon sj 
                                   inner join 
                                      tm_sjkeluarmakloon_detail sjd 
                                      on sj.i_sj = sjd.i_sj
                                   inner join 
                                      tm_type_makloon ma 
                                      on sj.i_type_makloon = ma.i_type_makloon
                                   inner join
                                      tr_supplier s 
                                      on (sj.i_supplier = s.i_supplier) 
                                      WHERE  i_status='6'
                                      AND sjd.n_qty_sisa > 0 AND sjd.n_qty_sisa2 > 0
                                   $and
                            ", false);
  }

  public function getsjkm_detail($isjkm,$gudang){
    $in_str = "'".implode("', '", $isjkm)."'";
    $and   = "AND sj.i_sj IN (".$in_str.")";
    return $this->db->query("
                            select
                               sj.*,
                               m.e_material_name,
                               s.e_satuan 
                            from
                               tm_sjkeluarmakloon_detail sj 
                               inner join
                                  tm_sjkeluarmakloon a on a.i_sj = sj.i_sj 
                               inner join
                                  tr_material m 
                                  on (sj.i_material = m.i_material) 
                               inner join
                                  tr_satuan s 
                                  on (s.i_satuan_code = m.i_satuan_code) 
                                  WHERE  a.i_status='6'
                                      AND sj.n_qty_sisa > 0 AND sj.n_qty_sisa2 > 0
                               $and
                            ", false);
  }

  public function baca($sj, $isupplier){
      return $this->db->query("    
                                SELECT
                                  a.i_sj,
                                  to_char(a.d_sj, 'dd-mm-yyyy') as d_sj,
                                  b.i_sj_reff,
                                  a.i_supplier,
                                  d.e_supplier_name,
                                  a.i_kode_master,
                                  a.e_remark,
                                  a.e_no_dok_supplier,
                                  a.i_status,
                                  a.i_type_makloon,
                                  v.e_type_makloon
                                FROM
                                  tm_sjmasukmakloon a
                                  JOIN tm_sjmasukmakloon_detail b
                                       ON (a.i_sj = b.i_sj)
                                  LEFT JOIN
                                       tr_supplier d
                                       ON (a.i_supplier = d.i_supplier)
                                  LEFT JOIN 
                                       tm_type_makloon v 
                                       ON (a.i_type_makloon = v.i_type_makloon)
                                WHERE
                                  a.i_sj = '$sj'
                                  and a.i_supplier = '$isupplier'", false);
  }

  public function bacadetail($sj, $isupplier){
        return $this->db->query("
                                SELECT
                                    a.i_sj_reff as i_reff,
                                    a.i_material_reff as material_keluar,
                                    g.e_material_name as nama_material_keluar,
                                    c.i_satuan as satuan_keluar,
                                    h.e_satuan as nama_satuan_keluar,
                                    c.n_permintaan as qty_keluar,
                                    a.i_material as material_masuk,
                                    d.e_material_name as nama_material_masuk,
                                    a.i_satuan as satuan_masuk,
                                    e.e_satuan as nama_satuan_masuk,
                                    a.n_quantity as qty_masuk,
                                    a.e_remark,
                                    a.n_quantity_reff
                                FROM
                                    tm_sjmasukmakloon_detail a
                                    LEFT JOIN tm_sjmasukmakloon b ON (a.i_sj = b.i_sj)
                                    LEFT JOIN tm_sjkeluarmakloon_detail c ON (a.i_sj_reff = c.i_sj and a.i_material_reff = c.i_material)
                                    LEFT JOIN tm_sjkeluarmakloon f ON (c.i_sj = f.i_sj)
                                    INNER JOIN tr_material d ON (a.i_material = d.i_material)
                                    INNER JOIN tr_material g ON (a.i_material_reff = g.i_material and a.i_material_reff = c.i_material)
                                    INNER JOIN tr_satuan e ON (a.i_satuan = e.i_satuan_code)
                                    INNER JOIN tr_satuan h ON (c.i_satuan = h.i_satuan_code)
                                WHERE 
                                    a.i_sj = '$sj'
                                    AND b.i_supplier = '$isupplier'
                                ORDER BY
                                    i_reff", FALSE);
    }

    public function bacareferensi($sj){
        $query = $this->db->query("
            SELECT DISTINCT
                a.i_sj_reff
            FROM
                tm_sjmasukmakloon_detail a
            INNER JOIN tm_sjmasukmakloon b ON
                (b.i_sj = b.i_sj)
            WHERE
                a.i_sj = '$sj'", FALSE);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

  public function getsjmm_detail($isjkm, $isjmm, $gudang){
      return $this->db->query("
                            select
                               sjk.i_material,
                               m1.e_material_name,
                               sjk.n_permintaan,
                               sjk.i_satuan,
                               s1.e_satuan,
                               sjm.i_material as i_2material,
                               m2.e_material_name as e_2material_name,
                               sjm.n_qty as n_2qty,
                               sjm.i_satuan as i_2satuan,
                               s2.e_satuan as e_2satuan,
                               sjm.e_remark 
                            from
                               tm_sjkeluarmakloon_detail sjk 
                               left join
                                  tm_sjmasukmakloon_detail sjm 
                                  on (sjk.i_sj = sjm.i_sj_reff 
                                  and sjk.i_kode_master = sjm.i_kode_master 
                                  and sjk.i_material = sjm.i_material_reff) 
                               inner join
                                  tr_material m1 
                                  on (sjk.i_material = m1.i_material) 
                               left join
                                  tr_material m2 
                                  on (sjm.i_material = m2.i_material) 
                               inner join
                                  tr_satuan s1 
                                  on (s1.i_satuan_code = sjk.i_satuan) 
                               left join
                                  tr_satuan s2 
                                  on (s2.i_satuan_code = sjm.i_satuan) 
                               inner join
                                  tm_sjmasukmakloon hsjm 
                                  on (sjk.i_kode_master = hsjm.i_kode_master 
                                  and sjk.i_sj = hsjm.i_sj_reff) 
                            where
                               hsjm.i_sj = '$isjmm' 
                               and sjk.i_kode_master = '$gudang' 
                               and sjk.i_sj = '$isjkm'

                            ", false);
  }

  function runningnumbermasukm($yearmonth,$ibagian){
      
        $bl  = substr($yearmonth,4,2);
        $th  = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= trim($ibagian);
        $b   = strlen($area);
        if($b <= 1){
              settype($b,"string");
              $b="0".$b;
              $area = $b;
        }else{
              $area;
        }

        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);

        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='SJMM'
                            and i_area='$area'
                            and e_periode='$asal' 
                            and substring(e_periode,1,4)='$th' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $terakhir=$row->max;
          }
          $nopp  =$terakhir+1;
                $this->db->query("update tm_dgu_no 
                            set n_modul_no=$nopp
                            where i_modul='SJMM'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
          settype($nopp,"string");
          $a=strlen($nopp);
  
          //u/ 0
          while($a<5){
            $nopp="0".$nopp;
            $a=strlen($nopp);
          }
            $nopp  ="SJMM"."-".$area."-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="SJMM"."-".$area."-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('SJMM','$area','$asal',1)");
          return $nopp;
        }
    }

  public function product($cari,$i_material, $ireferensi, $kelompokbrg){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
              d.i_material2, a.e_material_name, d.i_satuan2, b.e_satuan
            FROM
                tr_material a
            INNER JOIN 
                tr_satuan b on (a.i_satuan_code=b.i_satuan_code)
            INNER JOIN 
                tm_kelompok_barang c on (a.i_kode_kelompok=c.i_kode_kelompok)
            INNER JOIN    
                tm_sjkeluarmakloon_detail d on (a.i_material = d.i_material)
            WHERE
                a.i_kode_kelompok='$kelompokbrg' 
            AND 
                d.i_material = '$i_material'
            AND 
                d.i_sj = '$ireferensi'
            AND (a.i_material LIKE '%$cari%' OR a.e_material_name LIKE '%$cari%')
            ORDER BY a.i_material", 
        FALSE);
  }

  public function insertheader($nosjmasuk, $datesjk, $ibagian, $supplier, $itypemakloon, $remark, $inodoksup){
        $dentry = date("Y-m-d");
        $data = array(
            'i_sj'              => $nosjmasuk,
            'd_sj'              => $datesjk,
            'i_kode_master'     => $ibagian,
            'i_supplier'        => $supplier,
            'e_no_dok_supplier' => $inodoksup,
            'i_type_makloon'    => $itypemakloon,
            'e_remark'          => $remark,
            'd_insert'          => $dentry   
        );
        $this->db->insert('tm_sjmasukmakloon', $data);
    }

    public function insertdetail($nosjmasuk, $ibagian, $nosjkeluar, $imaterial_reff, $imaterial, $nquantity, $nquantityy, $isatuan, $isatuann, $edesc, $i, $vprice) {               
        $data = array(        

            'i_sj'            => $nosjmasuk,
            'i_kode_master'   => $ibagian,
            'i_sj_reff'       => $nosjkeluar,
            'i_material_reff' => $imaterial_reff,
            'i_material'      => $imaterial,
            'n_quantity_reff' => $nquantity,
            'n_quantity'      => $nquantityy,
            'i_satuan_reff'   => $isatuan,
            'i_satuan'        => $isatuann,
            'v_price'         => $vprice,
            'e_remark'        => $edesc,
            'i_no_item'       => $i      
        );
        $this->db->insert('tm_sjmasukmakloon_detail', $data);
    }

    public function ceksjkeluar2($nosjkeluar, $imaterial_reff, $imaterial){
        $this->db->select("n_qty_sisa2 from tm_sjkeluarmakloon_detail 
                       where i_sj = '$nosjkeluar' 
                       and i_material = '$imaterial_reff'
                       and i_material2 = '$imaterial'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $n_qty_sisa2 = $kuy->n_qty_sisa2; 
        }else{
            $n_qty_sisa2 = '';
        }
        return $n_qty_sisa2;
    }

     public function send($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_sj', $kode);
    $this->db->update('tm_sjmasukmakloon', $data);
    }

    public function updatesjkeluar($nosjkeluar, $imaterial_reff, $imaterial, $sisa, $sisaa){
        $data = array(
                      'n_qty_sisa'    => $sisa,
                      'n_qty_sisa2'   => $sisaa,
        );

    $this->db->where('i_sj', $nosjkeluar);
    $this->db->where('i_material', $imaterial_reff);
    $this->db->where('i_material2', $imaterial);
    $this->db->update('tm_sjkeluarmakloon_detail', $data);

    }

    public function updateheader($nosjmasuk, $datesjk, $ibagian, $remark){
      $dupdate = date("Y-m-d");
        $data = array(
                      'd_sj'              => $datesjk,
                      'e_remark'          => $remark,
                      'd_update'          => $dupdate
            
        );
        $this->db->where('i_sj', $nosjmasuk);
        $this->db->where('i_kode_master', $ibagian);
        $this->db->update('tm_sjmasukmakloon', $data);
    }

    public function updatedetail($nosjmasuk, $imaterial_reff, $imaterial, $nquantity, $edesc, $i){
        $data = array(
                      'n_quantity'  => $nquantity,
                      'e_remark'    => $edesc,
        );
        $this->db->where('i_sj', $nosjmasuk);
        $this->db->where('i_material_reff', $imaterial_reff);
        $this->db->where('i_material', $imaterial);
        $this->db->update('tm_sjmasukmakloon_detail', $data);
    }

    public function sendd($isjkm){
      $data = array(
          'i_status'    => '2'
    );

    $this->db->where('i_sj', $isjkm);
    $this->db->update('tm_sjmasukmakloon', $data);
    }

    public function cancel_approve($isjkm){
        $data = array(
                  'i_status'   =>'7',
    );
    $this->db->where('i_sj', $isjkm);
    $this->db->update('tm_sjmasukmakloon', $data);
    }

    // public function deletedetail($istore, $nosjmasuk){
    //     $this->db->query("DELETE FROM tm_sjmasukmakloon_detail WHERE i_sj='$nosjmasuk' and i_kode_master='$istore'");
    // }

    public function cancel($isjkm){
        $data = array(
                  'f_cancel'   => 't',
                  'i_status'   => '9',
    );
    $this->db->where('i_sj', $isjkm);
    $this->db->update('tm_sjmasukmakloon', $data);
    }

    public function approve($isjkm){
        $data = array(
                'i_status'     =>'6',
    );
    $this->db->where('i_sj', $isjkm);
    $this->db->update('tm_sjmasukmakloon', $data);
    }

    public function change_approve($isjkm){
        $data = array(
                'i_status'     =>'3',
    );
    $this->db->where('i_sj', $isjkm);
    $this->db->update('tm_sjmasukmakloon', $data);
    }

    public function reject_approve($isjkm){
      $data = array(
              'i_status'      =>'4',
    );
    $this->db->where('i_sj', $isjkm);
    $this->db->update('tm_sjmasukmakloon', $data);
    }
}
/* End of file Mmaster.php */
