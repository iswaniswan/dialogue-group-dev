<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bacadepartement(){
    $departement =  $this->session->userdata('i_departement');
    if($departement ==  1){
      return $this->db->query ("SELECT i_departement, e_departement_name FROM public.tr_departement", FALSE)->result();
    }else{
      return $this->db->query ("SELECT i_departement, e_departement_name FROM public.tr_departement WHERE i_departement='$departement'", FALSE)->result();
    }
  }

  public function getmakloonjahit(){
      $username = $this->session->userdata('username');
      $query = $this->db->query("SELECT i_type_makloon FROM tm_user_deprole WHERE username = '$username'", FALSE);
      if($query->num_rows()>0){
        foreach($query->result() as $row){
          $unitjahit =  $row->i_type_makloon;
        }
      }
      return $this->db->query("SELECT i_supplier, e_supplier_name FROM tr_supplier WHERE i_type_makloon = '$unitjahit'", FALSE);
  }

  public function getdiskonsupplier($isupplier){
    return $this->db->query("SELECT f_supplier_pkp, v_diskon FROM tr_supplier WHERE i_supplier = '$isupplier'", FALSE);
  }

  public function getproductwip($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
                              select 
                                x.*, 
                                c.e_color_name 
                              from (
                                select 
                                  a.i_product, 
                                  b.e_namabrg, 
                                  a.i_color  
                                from 
                                  tr_polacutting a
                                  inner join 
                              	  tm_barang_wip b 
                              	  on (b.i_kodebrg = a.i_product)
                                      inner join
                              	  tr_product_base c
                              	  on (a.i_product = c.i_product_base and b.i_kodebrg = c.i_product_base)
                                      inner join 
                              	  tm_price_makloon_supplier_unitjahit d
                              	  on (c.i_product_motif = d.i_material)
                              where 
                                (UPPER(a.i_product) LIKE '%$cari%'
                                OR UPPER(b.e_namabrg) LIKE '%$cari%')
                              group by  a.i_product,b.e_namabrg, a.i_color 
                              ) as x 
                              left join tr_color c on (x.i_color = c.i_color)
                              order by x.e_namabrg
                            ", FALSE);
  }

  public function getwiphead($iwip,$icolor,$dsjk){
    return $this->db->query("
                              select
                               x.*,
                               c.e_color_name 
                              from
                               (
                                  select
                                     a.i_product,
                                     b.e_namabrg,
                                     a.i_color,
                                     d.v_price,
                                     d.f_status_aktif
                                  from
                                      tr_polacutting a 
                                      inner join
                              	        tm_barang_wip b 
                              	        on (b.i_kodebrg = a.i_product) 
                                      left join
                              	        tr_product_base c
                              	        on (a.i_product = c.i_product_base and b.i_kodebrg = c.i_product_base)
                                      left join 
                                        tm_price_makloon_supplier_unitjahit d
                                        on (c.i_product_motif = d.i_material)
                                  where
                                     a.i_product = '$iwip' 
                                     and a.i_color = '$icolor' 
                                     and d.d_berlaku
                                     in (
                                     select
                              	 y.d_berlaku
                                     from(
                              	  select 
                              		z.i_material, 
                              		z.d_berlaku,
                              		z.f_status_aktif,
                              		z.d_akhir_tmp
                              	   from(
                              		select
                              			i_material as i_material, 
                              			d_berlaku as d_berlaku,
                              			f_status_aktif as f_status_aktif,
                              			case when d_akhir is not null then d_akhir else '5000-01-01' end as d_akhir_tmp
                              		from
                              			tm_price_makloon_supplier_unitjahit 
                              	   ) as z
                              	   where 
                              		z.d_berlaku <= to_date('$dsjk','dd-mm-yyyy')
                              		and z.d_akhir_tmp >= to_date('$dsjk','dd-mm-yyyy')
                              	   )as y
                              	  )
                                  group by
                                     a.i_product,
                                     b.e_namabrg,
                                     a.i_color,
                                     d.v_price,
                                     d.f_status_aktif
                               )
                               as x 
                               left join
                                  tr_color c 
                                  on (x.i_color = c.i_color) 
                              order by
                               x.e_namabrg
                              ", false);
  }

  public function getwipdetail($iwip,$icolor){
      return $this->db->query("
                            SELECT a.i_product,a.i_color, a.i_material, b.e_material_name
                            from tr_polacutting a
                            inner join tr_material b on a.i_material = b.i_material
                            where a.i_product = '$iwip' and a.i_color = '$icolor'
                            order by e_material_name
      ", false);
  }

  public function runningnumbersj($yearmonth,$isubbagian){
    $bl       = substr($yearmonth,4,2);
    $th       = substr($yearmonth,0,4);
    $thn      = substr($yearmonth,2,2);
    $area     = trim($isubbagian);
    $asal     = substr($yearmonth,0,4);
    $yearmonth= substr($yearmonth,0,4);

    $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='SJMJ'
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
                        where i_modul='SJMJ'
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
        $nopp  ="SJMJ"."-".$area."-".$thn.$bl."-".$nopp;
      return $nopp;
    }else{
      $nopp  ="00001";
      $nopp  ="SJMJ"."-".$area."-".$thn.$bl."-".$nopp;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('SJMJ','$area','$asal',1)");
      return $nopp;
    }
  }

  public function insertheader($isj, $dsjk, $iforecast, $dforecast, $iunitjahit, $pkp, $ndiscount, $isubbagian, $dback, $edesc){
    $dentry = date("Y-m-d H:i:s");
    $this->db->set( 
      array(
          'i_sj'            => $isj,
          'd_sj'            => $dsjk,
          'i_forecast'      => $iforecast,
          'd_forecast'      => $dforecast,
          'i_unit_jahit'    => $iunitjahit,
          'i_kode_master'   => $isubbagian,
          'd_back'          => $dback,
          'e_remark'        => $edesc,
          'd_entry'         => $dentry,
          'i_status'        => '1',
          'n_discount'      => $ndiscount,
          'pkp'             => $pkp
      )
    );
    $this->db->insert('tm_sj_keluar_makloonunitjahit');
  }

  public function insertdetail($isj, $iwip, $vprice, $imaterial, $icolor,$eremark, $nquantity, $nquantitywip, $no){               
    $this->db->set(
      array(        
              'i_sj'          => $isj,
              'i_product'     => $iwip,
              'i_material'    => $imaterial,
              'i_color'       => $icolor,
              'e_remark'      => $eremark,
              'n_quantity'    => $nquantity,
              'n_quantity_wip'=> $nquantitywip,
              'n_item_no'     => $no,
              'n_sisa'        => $nquantitywip,
              'v_price'       => $vprice
      )
    );
    $this->db->insert('tm_sj_keluar_makloonunitjahit_item');
  }

	function data($username,$idcompany,$idepartemen,$ilevel,$i_menu,$folder){
    $datatables = new Datatables(new CodeigniterAdapter);
    $itypemakloon = $this->session->set_userdata('type_makloon');
    $datatables->query("
                        select
                           a.i_sj,
                           a.d_sj,
                           a.i_forecast,
                           a.d_forecast,
                           a.i_unit_jahit,
                           c.e_supplier_name,
                           a.d_back,
                           a.i_status as status,
                           a.f_sj_cancel,
                           a.i_kode_master,
                           d.e_status as namastatus,
                           d.label_color as label,
                           '$username' as username,
                           '$idcompany' as idcompany,
                           '$idepartemen' as departement,
                           '$ilevel' as level,
                           '$i_menu' as i_menu,
                           '$folder' as folder
                        from
                           tm_sj_keluar_makloonunitjahit a 
                           left join
                              public.tr_departement b 
                              on (a.i_kode_master = b.i_departement) 
                           left join
                              tr_supplier c 
                              on (a.i_unit_jahit = c.i_supplier) 
                            left join 
                              tm_status_dokumen d 
                              on (a.i_status = d.i_status)
                        order by
                           a.i_sj desc
                        ");
        
        $datatables->add('action', function ($data) {
            $sj               = trim($data['i_sj']);
            $gudang           = trim($data['i_kode_master']);
            $unitjahit        = trim($data['i_unit_jahit']);
            $status           = trim($data['status']);
            $i_menu           = $data['i_menu'];
            $folder           = $data['folder'];
            $i_status         = $data['status'];
            $fsjcancel        = $data['f_sj_cancel'];
            $username         = trim($data['username']);
            $idcompany        = trim($data['idcompany']);
            $i_departement    = trim($data['departement']);
            $i_level          = trim($data['level']);
            $data = '';
            if(check_role($i_menu, 2)){
               $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$sj/$gudang\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
              if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$sj/$gudang\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;";
              }
              if ((($i_departement == '16' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$sj/$gudang\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;&nbsp;";
              }
            }
            if(check_role($i_menu, 4) && $fsjcancel == 'f'){
                $data .= "<a href=\"#\" onclick='cancel(\"$sj\",\"$gudang\"); return false;'><i class='fa fa-trash'></i></a>";
            }
         
			    return $data;
        });

        $datatables->edit('status', function ($data) {
          if($data['f_sj_cancel'] == 't'){
            return '<span class="label label-danger label-rouded">Batal</span>';
          }
          return '<span class="label label-'.$data['label'].' label-rouded">'.$data['namastatus'].'</span>';
        });

        $datatables->edit('e_supplier_name', function ($data) {
          $iu = $data['i_unit_jahit'];
          $eu = $data['e_supplier_name'];
          return '( '.$iu.' )'.' - '.$eu;
        });

        $datatables->edit('d_sj', function ($data) {
          if($data['d_sj'] == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($data['d_sj']) );
          }
        });

        $datatables->edit('d_forecast', function ($data) {
          if($data['d_forecast'] == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($data['d_forecast']) );
          }
        });

        $datatables->edit('d_back', function ($data) {
          if($data['d_back'] == ''){
              return '';
          }else{
              return date("d-m-Y", strtotime($data['d_back']) );
          }
        });

        $datatables->hide('i_unit_jahit');
        $datatables->hide('i_menu');
        $datatables->hide('i_kode_master');
        $datatables->hide('f_sj_cancel');
        $datatables->hide('folder');
        $datatables->hide('namastatus');
        $datatables->hide('label');
        $datatables->hide('username');
        $datatables->hide('idcompany');
        $datatables->hide('departement');
        $datatables->hide('level');

        return $datatables->generate();
	}

   public function cancel($sj, $gudang){
        $this->db->set(
            array(
                'f_sj_cancel'  => 't',
                'i_status'     => '9'
            )
        );
        $this->db->where('i_sj',$sj);
        $this->db->where('i_kode_master',$gudang);
        return $this->db->update('tm_sj_keluar_makloonunitjahit');
    }
    
    public function baca($sj,$gudang){
      return $this->db->query("
                              select 
                                a.*, 
                                b.e_departement_name,
                                c.e_supplier_name
                              from 
                                tm_sj_keluar_makloonunitjahit a
                                left join public.tr_departement b on (a.i_kode_master = b.i_departement)
                                left join tr_supplier c on (a.i_unit_jahit = c.i_supplier)
                              where 
                                a.i_sj = '$sj' 
                                and a.i_kode_master = '$gudang' 
                              ", false);
    }

    public function bacadetail($sj,$gudang){
      return $this->db->query("
                              select distinct
                                a.*,
                                c.e_namabrg,
                                e.e_material_name,
                                f.e_color_name 
                              from
                                tm_sj_keluar_makloonunitjahit_item a
                                left join tm_sj_keluar_makloonunitjahit b on (a.i_sj = b.i_sj)
                                left join tm_barang_wip c on (a.i_product = c.i_kodebrg)
                                left join tr_polacutting d on (a.i_product = d.i_product)
                                left join tr_material e on (a.i_material = e.i_material)
                                left join tr_color f on (a.i_color = f.i_color)
                              where
                                a.i_sj = '$sj'
                                and b.i_kode_master = '$gudang'
                                ", false);
    }

    public function cekdata($isj){
      $this->db->select(" 
                        select
                           a.i_sj,
                           to_char(a.d_sj, 'dd-mm-yyyy') as d_sj,
                           a.i_kode_master,
                           b.e_sub_bagian,
                           a.e_remark 
                        from
                           tm_sj_keluar_makloonunitjahit a 
                           inner join
                              tm_sub_bagian b 
                              on (a.i_kode_master = b.i_sub_bagian) 
                        where
                           a.i_sj = '$isj' 
                        order by
                           d_sj desc

                        "
                        ,false);
      return $this->db->get();
    }

    public function deletedetail($isj){
		  $this->db->query("
                      DELETE FROM 
                        tm_sj_keluar_makloonunitjahit_item 
                      WHERE 
                        i_sj='$isj' 
                      ");
    }

    public function updateheader($isj, $dsjk, $dback, $edesc){
        $dupdate = date("Y-m-d H:i:s");
        $this->db->set( 
          array(
            'd_sj'            => $dsjk,
            'd_back'          => $dback,
            'e_remark'        => $edesc,
            'd_update'        => $dupdate     
          )
        );
        $this->db->where('i_sj', $isj);
        $this->db->update('tm_sj_keluar_makloonunitjahit');
    }

    public function send($kode){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_sj', $kode);
      $this->db->update('tm_sj_keluar_makloonunitjahit', $data);
    }

    public function change($kode){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_sj', $kode);
      $this->db->update('tm_sj_keluar_makloonunitjahit', $data);
    }

    public function reject($kode){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_sj', $kode);
      $this->db->update('tm_sj_keluar_makloonunitjahit', $data);
    }

    public function approve($kode){
      $now = date("Y-m-d");
      $username = $this->session->userdata('username');
      $data = array(
          'i_status'   => '6',
          'i_approve1'  => $username,
          'd_approve1'  => $now
      );

      $this->db->where('i_sj', $kode);
      $this->db->update('tm_sj_keluar_makloonunitjahit', $data);
    }

    public function batal($kode){
      $data = array(
          'i_status'    => '9'
      );

      $this->db->where('i_sj', $kode);
      $this->db->update('tm_sj_keluar_makloonunitjahit', $data);
    }
}
/* End of file Mmaster.php */
