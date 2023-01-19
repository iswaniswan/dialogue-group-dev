<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
  function data($username, $idcompany, $idepartemen, $ilevel, $i_menu, $folder, $dfrom, $dto){
     if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE q.d_sj BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
    $datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("
                        select
                          distinct q.i_sj,
                           q.d_sj,
                           q.i_partner,
                           q.e_supplier_name,
                           q.i_permintaan,
                           q.i_type_makloon,
                           q.e_type_makloon,
                           q.i_status,
                           q.e_status,
                           q.f_cancel,
                           q.n_dok,
                           '$folder' as folder,
                           '$i_menu' as i_menu,
                           '$username' as username,
                           '$idcompany' as idcompany,
                           '$ilevel' as i_level,
                           '$idepartemen' as i_departement 
                        from
                           (
                              select
                                 a.i_sj,
                                 a.d_sj,
                                 a.i_partner,
                                 b.e_supplier_name,
                                 a.i_permintaan,
                                 a.i_type_makloon,
                                 c.e_type_makloon,
                                 a.i_status,
                                 d.e_status,
                                 a.f_cancel,
                                 a.n_dok,
                                 '$folder' as folder,
                                 '$i_menu' as i_menu,
                                 '$username' as username,
                                 '$idcompany' as idcompany,
                                 '$ilevel' as i_level,
                                 '$idepartemen' as i_departement 
                              from
                                 tm_sj_keluar_makloonqcset a 
                                 join
                                    tr_supplier b 
                                    on a.i_partner = b.i_supplier 
                                 join
                                    tm_type_makloon c 
                                    on a.i_type_makloon = c.i_type_makloon 
                                 join
                                    tm_status_dokumen d 
                                    on a.i_status = d.i_status 
                                 UNION ALL
                                 SELECT
                                    a.i_sj,
                                    a.d_sj,
                                    a.i_partner,
                                    b.e_supplier_name,
                                    a.i_permintaan,
                                    a.i_type_makloon,
                                    c.e_type_makloon,
                                    a.i_status,
                                    d.e_status,
                                    a.f_cancel,
                                    a.n_dok,
                                    '$folder' as folder,
                                    '$i_menu' as i_menu,
                                    '$username' as username,
                                    '$idcompany' as idcompany,
                                    '$ilevel' as i_level,
                                    '$idepartemen' as i_departement 
                                 from
                                    tm_sj_keluar_makloon_qcset_bordir a 
                                    join
                                       tr_supplier b 
                                       on a.i_partner = b.i_supplier 
                                    join
                                       tm_type_makloon c 
                                       on a.i_type_makloon = c.i_type_makloon 
                                    join
                                       tm_status_dokumen d 
                                       on a.i_status = d.i_status 
                                    UNION ALL
                                    SELECT
                                       a.i_sj,
                                       a.d_sj,
                                       a.i_partner,
                                       b.e_supplier_name,
                                       a.i_permintaan,
                                       a.i_type_makloon,
                                       c.e_type_makloon,
                                       a.i_status,
                                       d.e_status,
                                       a.f_cancel,
                                       a.n_dok,
                                       '$folder' as folder,
                                       '$i_menu' as i_menu,
                                       '$username' as username,
                                       '$idcompany' as idcompany,
                                       '$ilevel' as i_level,
                                       '$idepartemen' as i_departement 
                                    from
                                       tm_sj_keluar_makloon_qcset_embosh a 
                                       join
                                          tr_supplier b 
                                          on a.i_partner = b.i_supplier 
                                       join
                                          tm_type_makloon c 
                                          on a.i_type_makloon = c.i_type_makloon 
                                       join
                                          tm_status_dokumen d 
                                          on a.i_status = d.i_status 
                           )
                           as q $where
                        order by
                           q.i_sj", false);
        
        $datatables->add('action', function ($data) {
            $isj              = trim($data['i_sj']);
            $itypemakloon     = trim($data['i_type_makloon']);
            $ipartner         = trim($data['i_partner']);
            $i_status         = trim($data['i_status']);
            $ndok             = trim($data['n_dok']);
            $i_menu           = $data['i_menu'];
            $folder           = $data['folder'];
            $f_cancel         = $data['f_cancel'];
            $username         = trim($data['username']);
            $idcompany        = trim($data['idcompany']);
            $i_departement    = trim($data['i_departement']);
            $i_level          = trim($data['i_level']);
            $data = '';
            if(check_role($i_menu, 2)){
               $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$isj/$itypemakloon/$ipartner/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
              if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$isj/$itypemakloon/$ipartner/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;";
              }
              if ((($i_departement == '16' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$isj/$itypemakloon/$ipartner/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;&nbsp;";
              }
            }
            if(check_role($i_menu, 4) && $f_cancel == 'f' && $i_status != '6'){
                $data .= "<a href=\"#\" onclick='cancel(\"$isj\",\"$ndok\"); return false;'><i class='fa fa-trash'></i></a>";
            }
         
          return $data;
        });

        $datatables->edit('d_sj', function ($data) {
          if($data['d_sj'] == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($data['d_sj']) );
          }
        });

        $datatables->hide('folder');
        $datatables->hide('i_menu');
        $datatables->hide('username');
        $datatables->hide('idcompany');
        $datatables->hide('i_departement');
        $datatables->hide('i_level');
        $datatables->hide('i_partner');
        $datatables->hide('i_type_makloon');
        $datatables->hide('i_status');
        $datatables->hide('f_cancel');
        $datatables->hide('n_dok');

        return $datatables->generate();
  }

  function bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany){
      //var_dump($idepart);
          if(trim($idepart) == '1'){
            return $this->db->query(" SELECT a.*
                                      from public.tr_departement a
                                      order by a.i_departement", FALSE);
          }else{
            $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";

            return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name
                                      from public.tm_user_deprole a
                                      inner join public.tr_departement b on a.i_departement = b.i_departement
                                      inner join public.tr_level c on a.i_level = c.i_level $where ", FALSE);
          }
  }

  public function bacajenis(){
    return $this->db->order_by('i_jenis','ASC')->get('tr_jenis_keluarbb')->result();
  }

  public function bacatujuan(){
      return $this->db->query("
                                select
                                  a.i_supplier,
                                  a.e_supplier_name 
                                from
                                  tr_supplier a,
                                  tr_supplier_group 
                                where
                                  a.i_supplier_group = 'KTG0002'
                                  and f_status_aktif='t'
                                "
                                ,FALSE)->result();
  }

  public function getpartner(){
    $cari = strtoupper($this->input->get('q'));
        $this->db->select("
                            c.i_partner, c.e_partner 
                            from
                               (
                                  select
                                     a.i_supplier as i_partner,
                                     a.e_supplier_name as e_partner 
                                  from
                                     tr_supplier a 
                                  where
                                     a.i_type_makloon = 'JNM0001' 
                               )
                               as c 
                               where
                                  (
                                     c.i_partner like '%$cari%' 
                                     or c.e_partner like '%$cari%'
                                  )
                                  group by
                                     c.i_partner,
                                     c.e_partner 
                                     order by
                                        c.i_partner", false);
        //join tm_permintaanpengeluaranbb b on c.i_partner= b.partnerwhere b.i_status='6'
        return $this->db->get();
  }

  public function getpartner2(){
    $cari = strtoupper($this->input->get('q'));
        $this->db->select("
                            c.i_partner, c.e_partner 
                            from
                               (
                                  select
                                     a.i_supplier as i_partner,
                                     a.e_supplier_name as e_partner 
                                  from
                                     tr_supplier a 
                                  where
                                     a.i_type_makloon = 'JNM0003' 
                               )
                               as c 
                               where
                                  (
                                     c.i_partner like '%$cari%' 
                                     or c.e_partner like '%$cari%'
                                  )
                                  group by
                                     c.i_partner,
                                     c.e_partner 
                                     order by
                                        c.i_partner", false);
        //join tm_permintaanpengeluaranbb b on c.i_partner= b.partnerwhere b.i_status='6'
        return $this->db->get();
  }

   public function getpartner3(){
    $cari = strtoupper($this->input->get('q'));
        $this->db->select("
                            c.i_partner, c.e_partner 
                            from
                               (
                                  select
                                     a.i_supplier as i_partner,
                                     a.e_supplier_name as e_partner 
                                  from
                                     tr_supplier a 
                                  where
                                     a.i_type_makloon = 'JNM0005' 
                               )
                               as c 
                               where
                                  (
                                     c.i_partner like '%$cari%' 
                                     or c.e_partner like '%$cari%'
                                  )
                                  group by
                                     c.i_partner,
                                     c.e_partner 
                                     order by
                                        c.i_partner", false);
        //join tm_permintaanpengeluaranbb b on c.i_partner= b.partnerwhere b.i_status='6'
        return $this->db->get();
  }

  public function gettypemakloon($ipartner){
        $cari = strtoupper($this->input->get('q'));
        $this->db->select("distinct x.i_type_makloon, x.i_supplier, x.e_type_makloon 
                            from ( 
                                  select pi.i_supplier, pi.i_type_makloon, ma.e_type_makloon 
                                  from tm_price_makloon_supplier_print pi 
                                  join tm_type_makloon ma on pi.i_type_makloon = ma.i_type_makloon
                                 ) 
                              as x where x.i_supplier= '$ipartner'", false);
                          //join tm_permintaanpengeluaranbb b on x.i_supplier= b.partner
        return $this->db->get();
  }
   public function gettypemakloon2($i_partner){
        $cari = strtoupper($this->input->get('q'));
        $this->db->select("distinct x.i_type_makloon, x.i_supplier, x.e_type_makloon 
                            from ( 
                                  select pi.i_supplier, pi.i_type_makloon, ma.e_type_makloon
                                  from tm_price_makloon_supplier_bordir pi 
                                  join tm_type_makloon ma on pi.i_type_makloon = ma.i_type_makloon 
                                 ) 
                              as x where x.i_supplier= '$i_partner'", false);
                          //join tm_permintaanpengeluaranbb b on x.i_supplier= b.partner
        return $this->db->get();
  }

  public function gettypemakloon3($ipartnerr){
        $cari = strtoupper($this->input->get('q'));
        $this->db->select("distinct x.i_type_makloon, x.i_supplier, x.e_type_makloon 
                            from ( 
                                  select pi.i_supplier, pi.i_type_makloon, ma.e_type_makloon 
                                  from tm_price_makloon_supplier_embosh pi 
                                  join tm_type_makloon ma on pi.i_type_makloon = ma.i_type_makloon 
                                 ) 
                              as x where x.i_supplier= '$ipartnerr'", false);
                          //join tm_permintaanpengeluaranbb b on x.i_supplier= b.partner
        return $this->db->get();
  }

  public function getproduct($cari, $dsjk){
    //$dsjk = $this->input->post('dsjk');
     $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                  select
                                     b.i_material,
                                     a.e_namabrg,
                                     b.v_price 
                                  from
                                     tm_barang_wip a 
                                     join
                                        tm_price_makloon_supplier_print b 
                                        on a.i_kodebrg = b.i_material 
                                  where (b.i_material like '%$cari%' or a.e_namabrg like '%$cari%')
                                   and  b.d_berlaku in 
                                     (
                                        select
                                           y.d_berlaku 
                                        from
                                           (
                                              select
                                                 z.i_material,
                                                 z.d_berlaku,
                                                 z.f_status_aktif,
                                                 z.d_akhir_tmp 
                                              from
                                                 (
                                                    select
                                                       i_material as i_material,
                                                       d_berlaku as d_berlaku,
                                                       f_status_aktif as f_status_aktif,
                                                       case
                                                          when
                                                             d_akhir is not null 
                                                          then
                                                             d_akhir 
                                                          else
                                                             '5000-01-01' 
                                                       end
                                                       as d_akhir_tmp 
                                                    from
                                                       tm_price_makloon_supplier_print 
                                                 )
                                                 as z 
                                              where
                                                 z.d_berlaku <= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.d_akhir_tmp >= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.f_status_aktif = 't' 
                                           )
                                           as y)", FALSE);
  }

  public function get_product($cari, $dsjk){
    //$dsjk = $this->input->post('dsjk');
     $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                  select
                                     b.i_material,
                                     a.e_namabrg,
                                     b.v_price 
                                  from
                                     tm_barang_wip a 
                                     join
                                        tm_price_makloon_supplier_bordir b 
                                        on a.i_kodebrg = b.i_material 
                                  where (b.i_material like '%$cari%' or a.e_namabrg like '%$cari%')
                                   and  b.d_berlaku in 
                                     (
                                        select
                                           y.d_berlaku 
                                        from
                                           (
                                              select
                                                 z.i_material,
                                                 z.d_berlaku,
                                                 z.f_status_aktif,
                                                 z.d_akhir_tmp 
                                              from
                                                 (
                                                    select
                                                       i_material as i_material,
                                                       d_berlaku as d_berlaku,
                                                       f_status_aktif as f_status_aktif,
                                                       case
                                                          when
                                                             d_akhir is not null 
                                                          then
                                                             d_akhir 
                                                          else
                                                             '5000-01-01' 
                                                       end
                                                       as d_akhir_tmp 
                                                    from
                                                       tm_price_makloon_supplier_bordir 
                                                 )
                                                 as z 
                                              where
                                                 z.d_berlaku <= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.d_akhir_tmp >= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.f_status_aktif = 't' 
                                           )
                                           as y)", FALSE);
  }

  public function get_productt($cari, $dsjk){
    //$dsjk = $this->input->post('dsjk');
     $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                  select
                                     b.i_material,
                                     a.e_namabrg,
                                     b.v_price 
                                  from
                                     tm_barang_wip a 
                                     join
                                        tm_price_makloon_supplier_embosh b 
                                        on a.i_kodebrg = b.i_material 
                                  where (b.i_material like '%$cari%' or a.e_namabrg like '%$cari%')
                                   and  b.d_berlaku in 
                                     (
                                        select
                                           y.d_berlaku 
                                        from
                                           (
                                              select
                                                 z.i_material,
                                                 z.d_berlaku,
                                                 z.f_status_aktif,
                                                 z.d_akhir_tmp 
                                              from
                                                 (
                                                    select
                                                       i_material as i_material,
                                                       d_berlaku as d_berlaku,
                                                       f_status_aktif as f_status_aktif,
                                                       case
                                                          when
                                                             d_akhir is not null 
                                                          then
                                                             d_akhir 
                                                          else
                                                             '5000-01-01' 
                                                       end
                                                       as d_akhir_tmp 
                                                    from
                                                       tm_price_makloon_supplier_embosh
                                                 )
                                                 as z 
                                              where
                                                 z.d_berlaku <= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.d_akhir_tmp >= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.f_status_aktif = 't' 
                                           )
                                           as y)", FALSE);
  }

  public function getma($eproduct, $ipartner, $itypemakloon, $dsjk){
      $this->db->select ("
                          x.i_material,
                          x.e_namabrg,
                          x.v_price,
                          x.f_supplier_pkp,
                          x.v_diskon,
                          x.i_supplier 
                            from
                               (
                                  select
                                     b.i_material,
                                     a.e_namabrg,
                                     b.v_price,
                                     b.i_supplier,
                                     c.e_supplier_name,
                                     c.f_supplier_pkp,
                                     c.v_diskon,
                                     b.i_type_makloon 
                                  from
                                     tm_barang_wip a 
                                     join
                                        tm_price_makloon_supplier_print b 
                                        on a.i_kodebrg = b.i_material 
                                     join
                                        tr_supplier c 
                                        on b.i_supplier = c.i_supplier        
                                  where
                                     b.d_berlaku in 
                                     (
                                        select
                                           y.d_berlaku 
                                        from
                                           (
                                              select
                                                 z.i_material,
                                                 z.d_berlaku,
                                                 z.f_status_aktif,
                                                 z.d_akhir_tmp 
                                              from
                                                 (
                                                    select
                                                       i_material as i_material,
                                                       d_berlaku as d_berlaku,
                                                       f_status_aktif as f_status_aktif,
                                                       case
                                                          when
                                                             d_akhir is not null 
                                                          then
                                                             d_akhir 
                                                          else
                                                             '5000-01-01' 
                                                       end
                                                       as d_akhir_tmp 
                                                    from
                                                       tm_price_makloon_supplier_print 
                                                 )
                                                 as z 
                                              where
                                                 z.d_berlaku <= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.d_akhir_tmp >= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.f_status_aktif = 't' 
                                           )
                                           as y
                                     )
                               )
                               x 
                            where
                               x.i_supplier = '$ipartner' 
                               and x.i_type_makloon = '$itypemakloon' 
                               and x.i_material = '$eproduct' 
                            group by
                               x.i_material, x.e_namabrg, x.v_price, x.f_supplier_pkp, x.v_diskon, x.i_supplier", false);
        return $this->db->get();
  }

  public function get_ma($eproduct, $ipartner, $itypemakloon, $dsjk){
      $this->db->select ("
                          x.i_material,
                          x.e_namabrg,
                          x.v_price,
                          x.f_supplier_pkp,
                          x.v_diskon,
                          x.i_supplier 
                            from
                               (
                                  select
                                     b.i_material,
                                     a.e_namabrg,
                                     b.v_price,
                                     b.i_supplier,
                                     c.e_supplier_name,
                                     c.f_supplier_pkp,
                                     c.v_diskon,
                                     b.i_type_makloon 
                                  from
                                     tm_barang_wip a 
                                     join
                                        tm_price_makloon_supplier_bordir b 
                                        on a.i_kodebrg = b.i_material 
                                     join
                                        tr_supplier c 
                                        on b.i_supplier = c.i_supplier        
                                  where
                                     b.d_berlaku in 
                                     (
                                        select
                                           y.d_berlaku 
                                        from
                                           (
                                              select
                                                 z.i_material,
                                                 z.d_berlaku,
                                                 z.f_status_aktif,
                                                 z.d_akhir_tmp 
                                              from
                                                 (
                                                    select
                                                       i_material as i_material,
                                                       d_berlaku as d_berlaku,
                                                       f_status_aktif as f_status_aktif,
                                                       case
                                                          when
                                                             d_akhir is not null 
                                                          then
                                                             d_akhir 
                                                          else
                                                             '5000-01-01' 
                                                       end
                                                       as d_akhir_tmp 
                                                    from
                                                       tm_price_makloon_supplier_bordir 
                                                 )
                                                 as z 
                                              where
                                                 z.d_berlaku <= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.d_akhir_tmp >= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.f_status_aktif = 't' 
                                           )
                                           as y
                                     )
                               )
                               x 
                            where
                               x.i_supplier = '$ipartner' 
                               and x.i_type_makloon = '$itypemakloon' 
                               and x.i_material = '$eproduct' 
                            group by
                               x.i_material, x.e_namabrg, x.v_price, x.f_supplier_pkp, x.v_diskon, x.i_supplier", false);
        return $this->db->get();
  }

  public function get_maa($eproduct, $ipartner, $itypemakloon, $dsjk){
      $this->db->select ("
                          x.i_material,
                          x.e_namabrg,
                          x.v_price,
                          x.f_supplier_pkp,
                          x.v_diskon,
                          x.i_supplier 
                            from
                               (
                                  select
                                     b.i_material,
                                     a.e_namabrg,
                                     b.v_price,
                                     b.i_supplier,
                                     c.e_supplier_name,
                                     c.f_supplier_pkp,
                                     c.v_diskon,
                                     b.i_type_makloon 
                                  from
                                     tm_barang_wip a 
                                     join
                                        tm_price_makloon_supplier_embosh b 
                                        on a.i_kodebrg = b.i_material 
                                     join
                                        tr_supplier c 
                                        on b.i_supplier = c.i_supplier        
                                  where
                                     b.d_berlaku in 
                                     (
                                        select
                                           y.d_berlaku 
                                        from
                                           (
                                              select
                                                 z.i_material,
                                                 z.d_berlaku,
                                                 z.f_status_aktif,
                                                 z.d_akhir_tmp 
                                              from
                                                 (
                                                    select
                                                       i_material as i_material,
                                                       d_berlaku as d_berlaku,
                                                       f_status_aktif as f_status_aktif,
                                                       case
                                                          when
                                                             d_akhir is not null 
                                                          then
                                                             d_akhir 
                                                          else
                                                             '5000-01-01' 
                                                       end
                                                       as d_akhir_tmp 
                                                    from
                                                       tm_price_makloon_supplier_embosh 
                                                 )
                                                 as z 
                                              where
                                                 z.d_berlaku <= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.d_akhir_tmp >= to_date('$dsjk', 'dd-mm-yyyy') 
                                                 and z.f_status_aktif = 't' 
                                           )
                                           as y
                                     )
                               )
                               x 
                            where
                               x.i_supplier = '$ipartner' 
                               and x.i_type_makloon = '$itypemakloon' 
                               and x.i_material = '$eproduct' 
                            group by
                               x.i_material, x.e_namabrg, x.v_price, x.f_supplier_pkp, x.v_diskon, x.i_supplier", false);
        return $this->db->get();
  }

  public function runningnumber_a($yearmonth, $ibagian){

      $bl         = substr($yearmonth,4,2);
      $th         = substr($yearmonth,0,4);
      $thn        = substr($yearmonth,2,2);
      $area       = trim($ibagian);
      $b   = strlen($area);
        if($b <= 1){
              settype($b,"string");
              $b="0".$b;
              $area = $b;
        }else{
              $area;
        }
      $asal       = substr($yearmonth,0,4);
      $yearmonth  = substr($yearmonth,0,4);
      $this->db->select("  
                          n_modul_no as max 
                        from 
                          tm_dgu_no 
                        where 
                          i_modul='SJMP'
                          and i_area='$area'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' 
                        for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nosj  =$terakhir+1;
        $this->db->query("
                          update 
                            tm_dgu_no 
                          set 
                            n_modul_no=$nosj
                          where 
                            i_modul='SJMP'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
        settype($nosj,"string");
        $a=strlen($nosj);
        while($a<5){
          $nosj="0".$nosj;
          $a=strlen($nosj);
        }
          $nosj  ="SJMP-".$area."-".$thn.$bl."-".$nosj;
        return $nosj;
      }else{
        $nosj  ="00001";
        $nosj  ="SJMP-".$area."-".$thn.$bl."-".$nosj;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SJMP', '$area', '$asal', 1)");
        return $nosj;
      }
  }

  public function runningnumber_b($yearmonth2, $i_bagian){

      $bl         = substr($yearmonth2,4,2);
      $th         = substr($yearmonth2,0,4);
      $thn        = substr($yearmonth2,2,2);
      $area       = trim($i_bagian);
      $b   = strlen($area);
        if($b <= 1){
              settype($b,"string");
              $b="0".$b;
              $area = $b;
        }else{
              $area;
        }
      $asal       = substr($yearmonth2,0,4);
      $yearmonth2  = substr($yearmonth2,0,4);
      $this->db->select("  
                          n_modul_no as max 
                        from 
                          tm_dgu_no 
                        where 
                          i_modul='SJMB'
                          and i_area='$area'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' 
                        for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nosj  =$terakhir+1;
        $this->db->query("
                          update 
                            tm_dgu_no 
                          set 
                            n_modul_no=$nosj
                          where 
                            i_modul='SJMB'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
        settype($nosj,"string");
        $a=strlen($nosj);
        while($a<5){
          $nosj="0".$nosj;
          $a=strlen($nosj);
        }
          $nosj  ="SJMB-".$area."-".$thn.$bl."-".$nosj;
        return $nosj;
      }else{
        $nosj  ="00001";
        $nosj  ="SJMB-".$area."-".$thn.$bl."-".$nosj;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SJMB', '$area', '$asal', 1)");
        return $nosj;
      }
  }

   public function runningnumber_c($yearmonth3, $ibagiann){

      $bl         = substr($yearmonth3,4,2);
      $th         = substr($yearmonth3,0,4);
      $thn        = substr($yearmonth3,2,2);
      $area       = trim($ibagiann);
      $b   = strlen($area);
        if($b <= 1){
              settype($b,"string");
              $b="0".$b;
              $area = $b;
        }else{
              $area;
        }
      $asal       = substr($yearmonth3,0,4);
      $yearmonth3  = substr($yearmonth3,0,4);
      $this->db->select("  
                          n_modul_no as max 
                        from 
                          tm_dgu_no 
                        where 
                          i_modul='SJME'
                          and i_area='$area'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' 
                        for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nosj  =$terakhir+1;
        $this->db->query("
                          update 
                            tm_dgu_no 
                          set 
                            n_modul_no=$nosj
                          where 
                            i_modul='SJME'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
        settype($nosj,"string");
        $a=strlen($nosj);
        while($a<5){
          $nosj="0".$nosj;
          $a=strlen($nosj);
        }
          $nosj  ="SJME-".$area."-".$thn.$bl."-".$nosj;
        return $nosj;
      }else{
        $nosj  ="00001";
        $nosj  ="SJME-".$area."-".$thn.$bl."-".$nosj;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SJME', '$area', '$asal', 1)");
        return $nosj;
      }
  }

  public function insertheader_a($isj, $ibagian, $datesjk, $dateback, $ipermintaan, $datepermintaan, $ipartner, $itypemakloon, $vdiskon, $fpkp, $eremark, $ddok){
    $dentry = date("d F Y H:i:s");
    $this->db->set( 
      array(
            'i_sj'            => $isj,
            'd_sj'            => $datesjk,
            'd_back'          => $dateback,
            'i_bagian'        => $ibagian,
            'i_partner'       => $ipartner,
            'i_permintaan'    => $ipermintaan,
            'd_permintaan'    => $datepermintaan,
            'i_type_makloon'  => $itypemakloon,
            'f_pkp'           => $fpkp,
            'n_diskon'        => $vdiskon,
            'e_remark'        => $eremark,
            'n_dok'           => $ddok,
            'd_entry'         => $dentry,
            'i_status'        => '1'
      )
    );
    $this->db->insert('tm_sj_keluar_makloonqcset');
  }

  public function insertdetail_a($isj, $iproduct, $nquantity, $vharga, $edesc, $no){               
    $this->db->set(
      array(        
              'i_sj'                  => $isj,
              'i_material'            => $iproduct,
              'n_quantity'            => $nquantity,
              'n_sisa'                => $nquantity,
              'v_price'               => $vharga,
              'e_remark'              => $edesc,
              'n_item_no'             => $no,
      )
    );
    $this->db->insert('tm_sj_keluar_makloonqcset_detail');
  }

  public function send($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_sj', $kode);
    $this->db->update('tm_sj_keluar_makloonqcset', $data);
  }

  public function insertheader_b($i_sj, $i_bagian, $date_sjk, $date_back, $i_permintaan, $date_permintaan, $i_partner, $i_typemakloon, $v_diskon, $f_pkp, $e_remark, $ddok){
    $dentry = date("d F Y H:i:s");
    $this->db->set( 
      array(
            'i_sj'            => $i_sj,
            'd_sj'            => $date_sjk,
            'd_back'          => $date_back,
            'i_bagian'        => $i_bagian,
            'i_partner'       => $i_partner,
            'i_permintaan'    => $i_permintaan,
            'd_permintaan'    => $date_permintaan,
            'i_type_makloon'  => $i_typemakloon,
            'f_pkp'           => $f_pkp,
            'n_diskon'        => $v_diskon,
            'e_remark'        => $e_remark,
            'n_dok'           => $ddok,
            'd_entry'         => $dentry,
            'i_status'        => '1'
      )
    );
    $this->db->insert('tm_sj_keluar_makloon_qcset_bordir');
  }

  public function insertdetail_b($i_sj, $iproduct, $nquantity, $vharga, $edesc, $no){               
    $this->db->set(
      array(        
              'i_sj'                  => $i_sj,
              'i_material'            => $iproduct,
              'n_quantity'            => $nquantity,
              'n_sisa'                => $nquantity,
              'v_price'               => $vharga,
              'e_remark'              => $edesc,
              'n_item_no'             => $no,
      )
    );
    $this->db->insert('tm_sj_keluar_makloon_qcset_bordir_detail');
  }

  public function s_send($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_sj', $kode);
    $this->db->update('tm_sj_keluar_makloon_qcset_bordir', $data);
  }

  public function insertheader_c($isjj, $ibagiann, $datesjkk, $datebackk, $ipermintaann, $datepermintaann, $ipartnerr, $itypemakloonn, $vdiskonn, $fpkpp, $eremarkk, $ddok){
    $dentry = date("d F Y H:i:s");
    $this->db->set( 
      array(
            'i_sj'            => $isjj,
            'd_sj'            => $datesjkk,
            'd_back'          => $datebackk,
            'i_bagian'        => $ibagiann,
            'i_partner'       => $ipartnerr,
            'i_permintaan'    => $ipermintaann,
            'd_permintaan'    => $datepermintaann,
            'i_type_makloon'  => $itypemakloonn,
            'f_pkp'           => $fpkpp,
            'n_diskon'        => $vdiskonn,
            'e_remark'        => $eremarkk,
            'n_dok'           => $ddok,
            'd_entry'         => $dentry,
            'i_status'        => '1'
      )
    );
    $this->db->insert('tm_sj_keluar_makloon_qcset_embosh');
  }

  public function insertdetail_c($isjj, $iproduct, $nquantity, $vharga, $edesc, $no){               
    $this->db->set(
      array(        
              'i_sj'                  => $isjj,
              'i_material'            => $iproduct,
              'n_quantity'            => $nquantity,
              'n_sisa'                => $nquantity,
              'v_price'               => $vharga,
              'e_remark'              => $edesc,
              'n_item_no'             => $no,
      )
    );
    $this->db->insert('tm_sj_keluar_makloon_qcset_embosh_detail');
  }

  public function sendd($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_sj', $kode);
    $this->db->update('tm_sj_keluar_makloon_qcset_embosh', $data);
  }

  public function baca_header($sj){
      $this->db->select("
                         x.i_sj,
                         x.d_sj,
                         x.i_bagian,
                         x.i_partner,
                         x.i_permintaan,
                         x.d_permintaan,
                         x.i_status,
                         x.e_remark,
                         x.n_diskon,
                         x.f_pkp,
                         x.d_back, 
                         x.i_type_makloon,
                         x.n_dok 
                          from
                             (
                                select
                                   a.i_sj,
                                   to_char(a.d_sj, 'dd-mm-yyyy') as d_sj,
                                   a.i_bagian,
                                   a.i_partner,
                                   a.i_permintaan,
                                   to_char(a.d_permintaan, 'dd-mm-yyyy') as d_permintaan,
                                   a.i_status,
                                   a.e_remark,
                                   a.n_diskon,
                                   a.f_pkp,
                                   to_char(a.d_back, 'dd-mm-yyyy') as d_back,
                                   a.i_type_makloon,
                                   a.n_dok  
                                from
                                   tm_sj_keluar_makloonqcset a 
                                UNION ALL
                                select
                                   a.i_sj,
                                   to_char(a.d_sj, 'dd-mm-yyyy') as d_sj,
                                   a.i_bagian,
                                   a.i_partner,
                                   a.i_permintaan,
                                   to_char(a.d_permintaan, 'dd-mm-yyyy') as d_permintaan,
                                   a.i_status,
                                   a.e_remark,
                                   a.n_diskon,
                                   a.f_pkp,
                                   to_char(a.d_back, 'dd-mm-yyyy') as d_back,
                                   a.i_type_makloon,
                                   a.n_dok   
                                from
                                   tm_sj_keluar_makloon_qcset_embosh a 
                                UNION ALL
                                select
                                   a.i_sj,
                                   to_char(a.d_sj, 'dd-mm-yyyy') as d_sj,
                                   a.i_bagian,
                                   a.i_partner,
                                   a.i_permintaan,
                                   to_char(a.d_permintaan, 'dd-mm-yyyy') as d_permintaan,
                                   a.i_status,
                                   a.e_remark,
                                   a.n_diskon,
                                   a.f_pkp,
                                   to_char(a.d_back, 'dd-mm-yyyy') as d_back,
                                   a.i_type_makloon,
                                   a.n_dok   
                                from
                                   tm_sj_keluar_makloon_qcset_bordir a 
                             )
                             as x 
                             where x.i_sj='$sj'
                          group by
                             x.i_sj,
                             x.d_sj,
                             x.i_bagian,
                             x.i_partner,
                             x.i_permintaan,
                             x.d_permintaan,
                             x.i_status,
                             x.e_remark,
                             x.n_diskon,
                             x.f_pkp,
                             x.d_back,
                             x.i_type_makloon,
                             x.n_dok",false);
      return $this->db->get();
  }

  public function baca_partner($itypemakloon){
        $this->db->select("
                            a.i_supplier as i_partner, a.e_supplier_name as e_partner 
                            from
                               tr_supplier a 
                            where i_type_makloon = '$itypemakloon'
                               order by
                                  a.i_supplier", false);
        return $this->db->get();
  }

  public function baca_typemakloon($partner){
        $this->db->select("
                           a.i_type_makloon,
                           a.e_type_makloon 
                            from
                               tm_type_makloon a 
                               join
                                  tr_supplier b 
                                  on a.i_type_makloon = b.i_type_makloon
                                  where b.i_supplier = '$partner'", false);
        return $this->db->get();
  }

  public function baca_detail($sj){
        $this->db->select("
                            a.i_sj,
                            a.i_material,
                            b.e_namabrg,
                            a.n_quantity,
                            a.v_price,
                            a.e_remark 
                            from
                               (
                                  select
                                     a.i_sj,
                                     a.i_material,
                                     a.n_quantity,
                                     a.v_price,
                                     a.e_remark 
                                  from
                                     tm_sj_keluar_makloon_qcset_embosh_detail a 
                                  UNION ALL
                                  select
                                     a.i_sj,
                                     a.i_material,
                                     a.n_quantity,
                                     a.v_price,
                                     a.e_remark 
                                  from
                                     tm_sj_keluar_makloon_qcset_bordir_detail a 
                                  UNION ALL
                                  select
                                     a.i_sj,
                                     a.i_material,
                                     a.n_quantity,
                                     a.v_price,
                                     a.e_remark 
                                  from
                                     tm_sj_keluar_makloonqcset_detail a 
                               )
                               as a
                            join tm_barang_wip b on a.i_material = b.i_kodebrg 
                            where a.i_sj='$sj'
                            group by
                               a.i_sj,
                               a.i_material,
                               b.e_namabrg,
                               a.n_quantity,
                               a.v_price,
                               a.e_remark",false);
        return $this->db->get();
  }

  public function send_print($isj){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloonqcset', $data);
  }

  public function send_bordir($isj){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_bordir', $data);
  }

  public function send_embosh($isj){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_embosh', $data);
  }

  public function cancel_print($isj){
      $data = array(
          'i_status'    => '7'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloonqcset', $data);
  }

  public function cancel_bordir($isj){
      $data = array(
          'i_status'    => '7'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_bordir', $data);
  }

  public function cancel_embosh($isj){
      $data = array(
          'i_status'    => '7'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_embosh', $data);
  }
  
  public function updateheader_print($isj, $ibagian, $datesjk, $dateback, $ipermintaan, $datepermintaan, $ipartner, $itypemakloon, $vdiskon, $fpkp, $eremark, $ndok){
        $dupdate = date("d F Y H:i:s");
          $data = array(
                        'd_sj'            => $datesjk,
                        'd_back'          => $dateback,
                        'i_bagian'        => $ibagian,
                        'i_partner'       => $ipartner,
                        'i_permintaan'    => $ipermintaan,
                        'd_permintaan'    => $datepermintaan,
                        'i_type_makloon'  => $itypemakloon,
                        'f_pkp'           => $fpkp,
                        'n_diskon'        => $vdiskon,
                        'e_remark'        => $eremark,
                        'd_update'        => $dupdate,    
        );
        $this->db->where('i_sj', $isj);
        $this->db->update('tm_sj_keluar_makloonqcset', $data);
  }  
   
  public function deletedetail_print($isj){
		  $this->db->query("
                      DELETE FROM 
                        tm_sj_keluar_makloonqcset_detail 
                      WHERE 
                        i_sj='$isj' 
                      ");
  }

  public function updateheader_bordir($isj, $ibagian, $datesjk, $dateback, $ipermintaan, $datepermintaan, $ipartner, $itypemakloon, $vdiskon, $fpkp, $eremark, $ndok){
        $dupdate = date("d F Y H:i:s");
          $data = array(
                        'd_sj'            => $datesjk,
                        'd_back'          => $dateback,
                        'i_bagian'        => $ibagian,
                        'i_partner'       => $ipartner,
                        'i_permintaan'    => $ipermintaan,
                        'd_permintaan'    => $datepermintaan,
                        'i_type_makloon'  => $itypemakloon,
                        'f_pkp'           => $fpkp,
                        'n_diskon'        => $vdiskon,
                        'e_remark'        => $eremark,
                        'd_update'        => $dupdate,    
        );
        $this->db->where('i_sj', $isj);
        $this->db->update('tm_sj_keluar_makloon_qcset_bordir', $data);
  }  
   
  public function deletedetail_bordir($isj){
      $this->db->query("
                      DELETE FROM 
                        tm_sj_keluar_makloon_qcset_bordir_detail 
                      WHERE 
                        i_sj='$isj' 
                      ");
  }

  public function updateheader_embosh($isj, $ibagian, $datesjk, $dateback, $ipermintaan, $datepermintaan, $ipartner, $itypemakloon, $vdiskon, $fpkp, $eremark, $ndok){
        $dupdate = date("d F Y H:i:s");
          $data = array(
                        'd_sj'            => $datesjk,
                        'd_back'          => $dateback,
                        'i_bagian'        => $ibagian,
                        'i_partner'       => $ipartner,
                        'i_permintaan'    => $ipermintaan,
                        'd_permintaan'    => $datepermintaan,
                        'i_type_makloon'  => $itypemakloon,
                        'f_pkp'           => $fpkp,
                        'n_diskon'        => $vdiskon,
                        'e_remark'        => $eremark,
                        'd_update'        => $dupdate,    
        );
        $this->db->where('i_sj', $isj);
        $this->db->update('tm_sj_keluar_makloon_qcset_embosh', $data);
  }  
   
  public function deletedetail_embosh($isj){
      $this->db->query("
                      DELETE FROM 
                        tm_sj_keluar_makloon_qcset_embosh_detail 
                      WHERE 
                        i_sj='$isj' 
                      ");
  }

  public function approve_print($isj){
      //$now = date("Y-m-d");
      //$username = $this->session->userdata('username');
      $data = array(
          'i_status'   => '6',
          //'i_approve1'  => $username,
          //'d_approve1'  => $now
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloonqcset', $data);
  }

  public function approve_bordir($isj){
      //$now = date("Y-m-d");
      //$username = $this->session->userdata('username');
      $data = array(
          'i_status'   => '6',
          //'i_approve1'  => $username,
          //'d_approve1'  => $now
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_bordir', $data);
  }

  public function approve_embosh($isj){
      //$now = date("Y-m-d");
      //$username = $this->session->userdata('username');
      $data = array(
          'i_status'   => '6',
          //'i_approve1'  => $username,
          //'d_approve1'  => $now
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_embosh', $data);
  }
  

  public function change_print($isj){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloonqcset', $data);
  }

  public function change_bordir($isj){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_bordir', $data);
  }

  public function change_embosh($isj){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_embosh', $data);
  }

  public function reject_print($isj){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloonqcset', $data);
  }

  public function reject_bordir($isj){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_bordir', $data);
  }

  public function reject_embosh($isj){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_embosh', $data);
  }

  public function delete_print($isj){
      $data = array(
          'i_status'    => '9',
          'f_cancel'    => 't'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloonqcset', $data);
  }
  public function delete_bordir($isj){
      $data = array(
          'i_status'    => '9',
          'f_cancel'    => 't'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_bordir', $data);
  }
  public function delete_embosh($isj){
      $data = array(
          'i_status'    => '9',
          'f_cancel'    => 't'
      );

      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_keluar_makloon_qcset_embosh', $data);
  }
}
/* End of file Mmaster.php */
