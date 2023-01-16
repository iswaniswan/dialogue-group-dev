<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu,$folder,$dfrom,$dto){
     if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE x.d_sj BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(" 
                            SELECT
                               ROW_NUMBER() OVER (ORDER BY x.i_sj) as nomor,
                               x.i_sj,
                               x.d_sj,
                               x.d_back,
                               x.i_supplier,
                               x.e_supplier_name,
                               x.i_referensi,
                               x.e_remark,
                               x.i_status,
                               x.e_status,
                               x.f_cancel,
                               '$i_menu' as i_menu,
                               x.i_kode_master,
                               x.label
                            from
                               (
                                  select
                                     sj.i_sj,
                                     sj.d_sj,
                                     sj.d_back,
                                     g.e_departement_name,
                                     sj.i_supplier,
                                     s.e_supplier_name,
                                     sj.i_referensi,
                                     sj.e_remark,
                                     sj.i_status,
                                     st.e_status,
                                     sj.f_cancel,
                                     '$i_menu' as i_menu,
                                     sj.i_kode_master,
                                     st.label_color as label 
                                  from
                                     tm_sjkeluarmakloon sj 
                                     inner join
                                        public.tr_departement g 
                                        on (sj.i_kode_master = g.i_departement) 
                                     inner join
                                        tr_supplier s 
                                        on (sj.i_supplier = s.i_supplier) 
                                     inner join
                                        tm_status_dokumen st 
                                        on (sj.i_status = st.i_status) 
                                  order by
                                     sj.i_sj desc 
                               )
                               as x 
                               left join
                                  (
                                     select distinct (i_referensi),
                                        i_kode_master 
                                     from
                                        tm_sjkeluarmakloon 
                                     where
                                        f_cancel = 'f' 
                                  )
                                  as y 
                                  on (x.i_sj = y.i_referensi 
                                  and x.i_kode_master = y.i_kode_master) 
                                  $where", false);

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
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
               $data .= "<a href=\"#\" title='View' onclick='show(\"sjkeluarmakloon/cform/view/$sj/$isupplier/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_cancel == 'f' && $i_status !='6' && $i_status != '4'){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"sjkeluarmakloon/cform/edit/$sj/$isupplier/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            }   
            if(check_role($i_menu, 1)&& $f_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
               $data .= "<a href=\"#\" title='Approve' onclick='show(\"sjkeluarmakloon/cform/approve/$sj/$isupplier/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;";
            }
            if($f_cancel !='t'){
                $data .= "<a href=\"#\" title='Print' onclick='printx(\"$sj\"); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;";
            }
            if ($f_cancel!='t' && $i_status != '6' && $i_status !='4') {
                $data .= "<a href=\"#\" title='Delete' onclick='cancel(\"$sj\"); return false;'><i class='ti-close'></i></a>&nbsp;&nbsp;";
            }
            
      return $data;
        });
        $datatables->hide('i_kode_master');
        $datatables->hide('i_menu'); 
        $datatables->hide('i_supplier'); 
        $datatables->hide('i_status');
        $datatables->hide('f_cancel');
        $datatables->hide('label');

        return $datatables->generate();
  }

  function bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany){
      //var_dump($idepart);
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

  public function getpartner(){
    $cari = strtoupper($this->input->get('q'));
        $this->db->select("c.i_partner, c.e_partner 
                      from(
                        select a.i_supplier as i_partner, a.e_supplier_name as e_partner from tr_supplier a

                        UNION ALL
                        select a.i_customer as i_partner, a.e_customer_name as e_partner from tr_customer a
                      ) as c 
                        join tm_permintaanpengeluaranbb b on c.i_partner= b.partner
                        where b.i_status='6'
                        and (c.i_partner like '%$cari%' or c.e_partner like '%$cari%')
                      group by c.i_partner, c.e_partner
                      order by c.i_partner", false);
        return $this->db->get();
  }

  public function gettypemakloon($ipartner){
        $cari = strtoupper($this->input->get('q'));
        $this->db->select("distinct x.i_type_makloon, x.i_supplier, x.e_type_makloon
                          from (
                              select
                              pi.i_supplier, pi.i_type_makloon, ma.e_type_makloon 
                              from tm_price_makloon_supplier_cutting pi
                              left join tm_type_makloon ma on pi.i_type_makloon = ma.i_type_makloon
                             
                              UNION ALL
                              select 
                              pi.i_supplier, pi.i_type_makloon, ma.e_type_makloon 
                              from tm_price_makloon_supplier_qwilting pi 
                              left join tm_type_makloon ma on pi.i_type_makloon = ma.i_type_makloon
                          ) as x

                          join tm_permintaanpengeluaranbb b on x.i_supplier= b.partner
                          where b.partner= '$ipartner'", false);
        return $this->db->get();
  }

  public function reff($ipartner){
        $cari = strtoupper($this->input->get('q'));
        return $this->db->query("
            SELECT
                distinct a.i_permintaan as referensi, to_char(a.d_pp, 'dd-mm-yyyy') AS d_pp , a.tujuan_keluar, a.pic, a.partner, a.pic_eks
            FROM
                tm_permintaanpengeluaranbb a
            JOIN tm_permintaanpengeluaranbb_detail b on a.i_permintaan = b.i_permintaan
            WHERE
                a.i_status = '6' 
            AND a.jenis_pengeluaran = 'JK00002' 
            AND a.i_permintaan NOT IN (select i_referensi from tm_sjkeluarmakloon where i_status = '6')
            AND a.partner = '$ipartner'
            AND b.n_qty_sisa > 0
            AND (UPPER(a.i_permintaan) LIKE '%$cari%')", 
        FALSE);
  }

  public function getreff($reff){
      return $this->db->query("
                          select x.d_pp, x.i_permintaan, x.partner, x.f_pkp
                            from(
                                select to_char(a.d_pp, 'dd-mm-yyyy') as d_pp, a.i_permintaan, a.partner, su.f_supplier_pkp as f_pkp
                              from tm_permintaanpengeluaranbb a
                              join tr_supplier su on a.partner = su.i_supplier

                              UNION ALL
                              select to_char(a.d_pp, 'dd-mm-yyyy') as d_pp, a.i_permintaan, a.partner, cu.f_customer_pkp as f_pkp 
                              from tm_permintaanpengeluaranbb a
                              join tr_customer cu on a.partner = cu.i_customer
                                    
                              )as x
                              where x.i_permintaan = '$reff'", false);
  }

  public function getreff_detail($reff, $ipartner, $itypemakloon, $dsjk){
      return $this->db->query("
                              select
                                 x.i_permintaan,
                                 x.i_material,
                                 x.e_material_name,
                                 x.e_satuan,
                                 x.n_qty,
                                 x.n_qty_sisa,
                                 x.i_satuan_code,
                                 x.i_material2,
                                 x.n_qty2,
                                 x.n_qty_sisa2,
                                 x.e_material_name as e_material_name2,
                                 x.i_satuan_code2,
                                 x.e_satuan as e_satuan2,
                                 x.e_remark,
                                 x.n_qty_pemenuhan,
                                 x.i_supplier,
                                 x.v_price,
                                 x.i_type_makloon 
                              from
                                 (
                                    select
                                       sj.i_permintaan,
                                       sj.i_material,
                                       m.e_material_name,
                                       s.e_satuan,
                                       sj.n_qty,
                                       sj.n_qty_sisa,
                                       sj.i_satuan_code,
                                       sj.i_material2,
                                       sj.n_qty2,
                                       sj.n_qty_sisa2,
                                       m2.e_material_name as e_material_name2,
                                       sj.i_satuan_code2,
                                       s2.e_satuan as e_satuan2,
                                       sj.e_remark,
                                       sj.n_qty_pemenuhan,
                                       pi.i_supplier,
                                       pi.v_price,
                                       pi.i_type_makloon 
                                    from
                                       tm_permintaanpengeluaranbb_detail sj 
                                       join
                                          tm_permintaanpengeluaranbb sa 
                                          on sj.i_permintaan = sa.i_permintaan 
                                       join
                                          tr_material m 
                                          on (sj.i_material = m.i_material) 
                                       join
                                          tr_satuan s 
                                          on (s.i_satuan_code = m.i_satuan_code) 
                                       join
                                          tr_material m2 
                                          on (sj.i_material2 = m2.i_material) 
                                       join
                                          tr_satuan s2 
                                          on (s2.i_satuan_code = m2.i_satuan_code) 
                                       left join
                                          tm_price_makloon_supplier_cutting pi 
                                          on (sj.i_material = pi.i_material 
                                          and sa.partner = pi.i_supplier) 
                                    where
                                       sj.i_permintaan = '$reff' 
                                       and sa.partner = '$ipartner' 
                                       and sa.i_status = '6' 
                                       and pi.i_type_makloon = '$itypemakloon' 
                                       and sj.n_qty_sisa > 0 
                                       and pi.d_berlaku in 
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
                                                         tm_price_makloon_supplier_cutting 
                                                   )
                                                   as z 
                                                where
                                                   z.d_berlaku <= to_date('$dsjk', 'dd-mm-yyyy') 
                                                   and z.d_akhir_tmp >= to_date('$dsjk', 'dd-mm-yyyy') 
                                                   and z.f_status_aktif = 't' 
                                             )
                                             as y 
                                       )
                                    UNION ALL
                                    select
                                       sj.i_permintaan,
                                       sj.i_material,
                                       m.e_material_name,
                                       s.e_satuan,
                                       sj.n_qty,
                                       sj.n_qty_sisa,
                                       sj.i_satuan_code,
                                       sj.i_material2,
                                       sj.n_qty2,
                                       sj.n_qty_sisa2,
                                       m2.e_material_name as e_material_name2,
                                       sj.i_satuan_code2,
                                       s2.e_satuan as e_satuan2,
                                       sj.e_remark,
                                       sj.n_qty_pemenuhan,
                                       pi.i_supplier,
                                       pi.v_price,
                                       pi.i_type_makloon 
                                    from
                                       tm_permintaanpengeluaranbb_detail sj 
                                       join
                                          tm_permintaanpengeluaranbb sa 
                                          on sj.i_permintaan = sa.i_permintaan 
                                       join
                                          tr_material m 
                                          on (sj.i_material = m.i_material) 
                                       join
                                          tr_satuan s 
                                          on (s.i_satuan_code = m.i_satuan_code) 
                                       join
                                          tr_material m2 
                                          on (sj.i_material2 = m2.i_material) 
                                       join
                                          tr_satuan s2 
                                          on (s2.i_satuan_code = m2.i_satuan_code) 
                                       left join
                                          tm_price_makloon_supplier_qwilting pi 
                                          on (sj.i_material = pi.i_material 
                                          and sa.partner = pi.i_supplier) 
                                    where
                                       sj.i_permintaan = '$reff' 
                                       and sa.partner = '$ipartner' 
                                       and sa.i_status = '6' 
                                       and pi.i_type_makloon = '$itypemakloon'
                                       and sj.n_qty_sisa > 0 
                                       and pi.d_berlaku in 
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
                                                         tm_price_makloon_supplier_cutting 
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
                                 as x 
                              order by
                                 i_permintaan", false);
  }

  function runningnumberkeluarm($yearmonth, $ibagian){
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
                            where i_modul='SJKM'
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
                            where i_modul='SJKM'
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
            $nopp  ="SJKM"."-".$area."-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="SJKM"."-".$area."-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('SJKM','$area','$asal',1)");
          return $nopp;
        }
  }

	public function insertheader($nosjkeluar, $datesjk, $ibagian, $partner, $remark, $dateback, $reff, $datereff, $itypemakloon, $fpkp){
        $dentry = date("Y-m-d");
        $data = array(
            'i_sj'            => $nosjkeluar,
            'd_sj'            => $datesjk,
            'i_kode_master'   => $ibagian,
            'i_supplier'      => $partner,
            'e_remark'        => $remark,
            'd_back'          => $dateback,
            'i_referensi'     => $reff,
            'd_referensi'     => $datereff,
            'i_type_makloon'  => $itypemakloon,
            'f_pkp'           => $fpkp,
            'd_insert'        => $dentry
    );
    $this->db->insert('tm_sjkeluarmakloon', $data);
    }

    public function insertdetail($nosjkeluar, $ibagian, $imaterial, $nquantity, $isatuan, $pemenuhan_1, $imaterial2, $nquantity2, $isatuan2,  $pemenuhan_2, $no, $edesc, $vprice){                     
          $isatuan = str_replace(" ", "", $isatuan);
          $isatuan2 = str_replace(" ", "", $isatuan2);
          $data = array(        
                      'i_sj'          => $nosjkeluar,
                      'i_kode_master' => $ibagian,
                      'i_material'    => $imaterial,
                      'i_satuan'      => $isatuan,
                      'n_permintaan'  => $nquantity,
                      'n_pemenuhan'   => $pemenuhan_1,
                      'i_material2'   => $imaterial2,
                      'i_satuan2'     => $isatuan2,
                      'n_permintaan2' => $nquantity2,
                      'n_pemenuhan2'  => $pemenuhan_2,
                      'n_qty_sisa'    => $pemenuhan_1,
                      'n_qty_sisa2'   => $pemenuhan_2,
                      'e_remark'      => $edesc,
                      'v_price'       => $vprice,
                      'i_no_item'     => $no
    );
    $this->db->insert('tm_sjkeluarmakloon_detail', $data);
    }
    
    function cekpermintaan($reff, $imaterial, $imaterial2){
      $this->db->select("n_qty_pemenuhan2 from tm_permintaanpengeluaranbb_detail 
                       where a.i_permintaan = '$reff' 
                       and i_material = '$imaterial'
                       and i_material2 = '$imaterial2'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $n_qty_pemenuhan2 = $kuy->n_qty_pemenuhan2; 
        }else{
            $n_qty_pemenuhan2 = '';
        }
        return $n_qty_pemenuhan2;
    }

    function cekpermintaan2($reff, $imaterial, $imaterial2){
      $this->db->select("n_qty_pemenuhan from tm_permintaanpengeluaranbb_detail 
                       where a.i_permintaan = '$reff' 
                       and i_material = '$imaterial'
                       and i_material2 = '$imaterial2'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $n_qty_pemenuhan = $kuy->n_qty_pemenuhan; 
        }else{
            $n_qty_pemenuhan = '';
        }
        return $n_qty_pemenuhan;
    }

    public function updatesisa($reff, $imaterial, $imaterial2, $sisa, $sisaa, $npemenuh, $npemenuh2){
        $data = array(
                      'n_qty_sisa'    => $sisa,
                      'n_qty_sisa2'   => $sisaa,
                      'n_quantity_pemenuhan'=> $npemenuh,
                      'n_quantity_pemenuhan'=> $npemenuh2,
        );

    $this->db->where('i_permintaan', $reff);
    $this->db->where('i_material', $imaterial);
    $this->db->where('i_material2', $imaterial2);
    $this->db->update('tm_permintaanpengeluaranbb_detail', $data);

    }

    public function send($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_sj', $kode);
    $this->db->update('tm_sjkeluarmakloon', $data);
    }

    public function baca_header($sj){
      return $this->db->query(" select a.i_sj, to_char(a.d_sj, 'dd-mm-yyyy') as d_sj, to_char(a.d_back, 'dd-mm-yyyy') as d_kembali, a.i_kode_master, a.i_supplier as partner, b.e_supplier_name, a.i_referensi as i_reff, to_char(a.d_referensi, 'dd-mm-yyyy') as d_referensi, a.e_remark, a.i_type_makloon, a.i_status, c.e_status 
                            from tm_sjkeluarmakloon a
                              join tr_supplier b on a.i_supplier=b.i_supplier
                              join tm_status_dokumen c on a.i_status=c.i_status
                              where a.i_sj = '$sj'", false);
    }

    public function baca_detail($sj){
      return $this->db->query(" select sj.*, m.e_material_name, a.e_satuan, sj.v_price,   m2.e_material_name as e_material_name2, a2.e_satuan  as e_satuan2, sj.n_pemenuhan
                              from tm_sjkeluarmakloon_detail sj
                              left join tr_material m on (sj.i_material = m.i_material)
                              left join tr_satuan a on (sj.i_satuan = a.i_satuan_code)
                              left join tr_material m2 on (sj.i_material2 = m2.i_material)
                              left join tr_satuan a2 on (sj.i_satuan2 = a2.i_satuan_code)
                              where sj.i_sj = '$sj'", false);
    }

    public function baca_itemm($sj){
      return $this->db->query(" select distinct (sj.i_sj), sj.i_kode_master, sj.i_material, sj.i_material2, m.e_material_name, sj.i_satuan2, sj.n_pemenuhan2, a.e_satuan
         from tm_sjkeluarmakloon_detail sj
          left join tr_material m on (sj.i_material2 = m.i_material)
          left join tr_satuan a on (sj.i_satuan2 = a.i_satuan_code)
           where sj.i_sj = '$sj' 
          order by sj.i_material2", false);
    }

    public function updateheader($nosjkeluar, $datesjk, $ibagian, $reff, $supplier, $remark, $dateback, $datereff, $itypemakloon){
        $dupdate = date("Y-m-d");
        $data = array(
            'd_sj'            => $datesjk,
            'i_referensi'     => $reff,
            'i_kode_master'   => $ibagian,
            'i_supplier'      => $supplier,
            'e_remark'        => $remark,
            'd_back'          => $dateback,
            'd_referensi'     => $datereff,
            'i_type_makloon'  => $itypemakloon,
            'd_update'        => $dupdate
            
        );
        $this->db->where('i_sj', $nosjkeluar);
        $this->db->update('tm_sjkeluarmakloon', $data);
    }

    public function deletedetail($nosjkeluar){
        $this->db->query("DELETE FROM tm_sjkeluarmakloon_detail WHERE i_sj='$nosjkeluar'");
    }

    public function sendd($isj){
      $data = array(
          'i_status'    => '2'
    );

    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sjkeluarmakloon', $data);
    }

    public function cancel_approve($isj){
        $data = array(
                  'i_status'   =>'7',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sjkeluarmakloon', $data);
    }

    public function cancel($isj){
        $data = array(
                  'f_cancel'   => 't',
                  'i_status'   => '9',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sjkeluarmakloon', $data);
    }

    public function approve($isj){
        $data = array(
                'i_status'     =>'6',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sjkeluarmakloon', $data);
    }

    public function change_approve($isj){
        $data = array(
                'i_status'     =>'3',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sjkeluarmakloon', $data);
    }

    public function reject_approve($isj){
      $data = array(
              'i_status'      =>'4',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sjkeluarmakloon', $data);
    }
}
/* End of file Mmaster.php */
