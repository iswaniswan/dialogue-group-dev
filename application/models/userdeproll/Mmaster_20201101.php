<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $idcompany, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);

        $datatables->query("SELECT ROW_NUMBER () OVER (ORDER BY x.i_departement) as no, x.name, x.id_company,
                            x.i_departement, x.e_departement_name, x.i_level, x.e_level_name, x.i_apps, x.i_menu, '$folder' as folder
                            from (SELECT distinct on(a.i_departement, a.i_level)  
                            a.i_departement, b.e_departement_name, a.i_level, c.e_level_name, '$i_menu' as i_menu, e.name, a.i_apps,d.id_company
                            from tm_user_role a
                            inner join tr_departement b on a.i_departement = b.i_departement
                            inner join tr_level c on a.i_level = c.i_level
                            left join tm_user_deprole d on a.i_departement = d.i_departement and a.i_level = d.i_level
                            inner join company e on d.id_company = e.id
                            where a.i_apps = '2' and e.id = '$idcompany') as x ", false);

        // $datatables->edit(
        // 'status', 
        // function ($data) {
        //             $id         = trim($data['id_company']);
        //             $dept       = trim($data['i_departement']);
        //             $i_level    = trim($data['i_level']);
        //             $i_apps     = trim($data['i_apps']);
        //             $folder     = $data['folder'];
        //             $id_menu    = $data['i_menu'];
                    // $status     = $data['status'];
                    // if ($status=='Aktif') {
                    //     $warna = 'success';
                    // }else{
                    //     $warna = 'danger';
                    // }
                    // $combine = $id.'|'.$dept.'|'.$i_level.'|'.$i_apps;
        //             $data    = '';
        //             if(check_role($id_menu, 3)){
        //                 $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$combine\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
        //             }else{
        //                 $data   .= "<span class=\"label label-$warna\">$status</span>";
        //             }
        //             return $data;
        //         }
        // );

        $datatables->add('action', function ($data) {
            $ilevel         = trim($data['i_level']);
            $idept          = trim($data['i_departement']);
            $icompany       = trim($data['id_company']);
            $iapps          = trim($data['i_apps']);
            $i_menu         = trim($data['i_menu']);
            $data           = '';
// $ilevel/$idept/$icompany/$iapps

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"userdeproll/cform/view/$ilevel/$idept/$icompany/$iapps/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"userdeproll/cform/edit/$ilevel/$idept/$icompany/$iapps/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            }
            // if(check_role($i_menu, 1)&& $f_pp_cancel!='t' && $e_approval !='1'){
            //   $data .= "<a href=\"#\" onclick='show(\"pembelianpp/cform/approve/$i_pp/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            // }
            // if ($f_pp_cancel!='t' && $e_approval == '1' || $e_approval == '5' || $e_approval == '6') {
            //       $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_pp\"); return false;'><i class='fa fa-trash'></i></a>";
            // }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        // $datatables->hide('username');
        $datatables->hide('i_level');
        $datatables->hide('i_departement');
        $datatables->hide('id_company');
        $datatables->hide('i_apps');
        return $datatables->generate();
  }

  public function status($id_company,$i_departement, $i_level, $i_apps){
        $this->db->select('status');
        $this->db->from('public.tm_user_deprole');
        $this->db->where('id_company', $id_company);
        $this->db->where('i_departement', $i_departement);
        $this->db->where('i_level', $i_level);
        $this->db->where('i_apps', $i_apps);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row    = $query->row();
            $status = $row->status;
            if ($status=='t') {
                $stat = 'f';
            }else{
                $stat = 't';
            }
        }
        $data = array(
            'status' => $stat 
        );
        $this->db->where('id_company', $id_company);
        $this->db->where('i_departement', $i_departement);
        $this->db->where('i_level', $i_level);
        $this->db->where('i_apps', $i_apps);
        $this->db->update('public.tm_user_deprole', $data);
  }

  public function getmenudetail($imenu){
    $this->db->select("a.i_menu, c.id, b.e_menu, a.i_apps, c.e_name, 0 as ada
                      FROM tm_user_role a
                      inner join tm_menu b on (a.i_menu = b.i_menu)
                      inner join tm_user_power c on (a.id_user_power = c.id)
                      WHERE a.i_departement = '1' 
                      and a.i_level = '1' 
                      and a.i_apps = '2' 
                      and b.i_menu = '$imenu'",false);  
              $data = $this->db->get();
              return $data;
  }

  function getpower($imenu, $idept, $ilevel){
    $this->db->select(" i_menu, id_user_power, i_departement, i_level, i_apps
                        from tm_user_role where i_apps = '2' and i_menu = '$imenu' 
                        and i_departement = '1' and i_level = '1'
                        and i_menu||id_user_power not in(
                        select i_menu||id_user_power
                        from tm_user_role where i_apps = '2' and i_menu = '$imenu' 
                        and i_departement = '$idept' and i_level = '$ilevel') order by id_user_power",false);  
              $data = $this->db->get();
              return $data;
    
  }

  public function getcustomer($idep, $ilev, $imenu, $isubmenu){
    if($isubmenu==''||$isubmenu==NULL){
    $this->db->select("distinct on(x.i_menu) x.i_menu,, x.id, x.e_menu, x.i_apps, sum(x.ada) as adaaja from(
      select a.i_menu, c.id, b.e_menu, a.i_apps, c.e_name, 0 as ada
      FROM tm_user_role a
      inner join tm_menu b on (a.i_menu = b.i_menu)
      inner join tm_user_power c on (a.id_user_power = c.id)
      WHERE a.i_departement = '1' 
      and a.i_level = '1' 
      and a.i_apps = '2' 
      and b.i_parent = '$imenu'
      union all
      select a.i_menu, c.id, b.e_menu, a.i_apps, c.e_name, 1 as ada
      FROM tm_user_role a
      inner join tm_menu b on (a.i_menu = b.i_menu)
      inner join tm_user_power c on (a.id_user_power = c.id)
      WHERE a.i_departement = '$idep' 
      and a.i_level = '$ilev' 
      and a.i_apps = '2'
      and b.i_parent = '$imenu'
      ) as x 
      group by x.i_menu, x.id, x.e_menu, x.i_apps, x.e_name
      order by x.i_menu, x.id",false);
    }else{
      $this->db->select("distinct on(x.i_menu) x.i_menu,, x.id, x.e_menu, x.i_apps, sum(x.ada) as adaaja from(
        select a.i_menu, c.id, b.e_menu, a.i_apps, c.e_name, 0 as ada
        FROM tm_user_role a
        inner join tm_menu b on (a.i_menu = b.i_menu)
        inner join tm_user_power c on (a.id_user_power = c.id)
        WHERE a.i_departement = '1' 
        and a.i_level = '1' 
        and a.i_apps = '2' 
        and b.i_parent = '$isubmenu'
        union all
        select a.i_menu, c.id, b.e_menu, a.i_apps, c.e_name, 1 as ada
        FROM tm_user_role a
        inner join tm_menu b on (a.i_menu = b.i_menu)
        inner join tm_user_power c on (a.id_user_power = c.id)
        WHERE a.i_departement = '$idep' 
        and a.i_level = '$ilev' 
        and a.i_apps = '2'
        and b.i_parent = '$isubmenu'
        ) as x 
        group by x.i_menu, x.id, x.e_menu, x.i_apps, x.e_name
        order by x.i_menu, x.id",false);
    }
    $data = $this->db->get();
    return $data;
}

public function getstock($lok,$iproduct){
  return $this->db->query("SELECT n_quantity_stock from tm_ic
      where i_product= '$iproduct' and i_kode_lokasi = '$lok' and i_product_grade = 'A'")->row()->n_quantity_stock;
}
  
  // public function getcustomer(){
  //   // return $this->db->query("SELECT a.i_menu, c.id, b.e_menu, a.i_apps, c.e_name
  //   // FROM tm_user_role a
  //   // inner join tm_menu b on (a.i_menu = b.i_menu)
  //   // inner join tm_user_power c on (a.id_user_power = c.id)
  //   // WHERE a.i_departement = '1' 
  //   // and a.i_level = '1' 
  //   // and a.i_apps = '2' 
  //   // order by a.i_menu, a.id_user_power ", FALSE);

  //   $this->db->select(" a.i_menu, c.id, b.e_menu, a.i_apps, c.e_name
  //   FROM tm_user_role a
  //   inner join tm_menu b on (a.i_menu = b.i_menu)
  //   inner join tm_user_power c on (a.id_user_power = c.id)
  //   WHERE a.i_departement = '1' 
  //   and a.i_level = '1' 
  //   and a.i_apps = '2' 
  //   order by a.i_menu, a.id_user_power",false);
  //   $data = $this->db->get();
  //   return $data;
  // }

	function cek_data($ilevel, $idept, $icompany, $iapps){
		$this->db->select('a.*, b.name, c.e_departement_name, d.e_level_name ');
        $this->db->from('tm_user_deprole a');
        $this->db->join('company b','a.id_company = b.id');
        $this->db->join('tr_departement c', 'a.i_departement = c.i_departement');
        $this->db->join('tr_level d', 'a.i_level = d.i_level');
        $this->db->where('a.i_level', $ilevel);
        $this->db->where('a.i_departement', $idept);
        $this->db->where('a.id_company', $icompany);
        $this->db->where('a.i_apps', $iapps);
        return $this->db->get();

  }

  public function cek_datadetail($ilevel, $idept, $icompany, $iapps){
    $this->db->select('a.*, b.e_menu, c.e_departement_name, d.e_level_name, e.e_name');
        $this->db->from(' tm_user_role a');
        $this->db->join('tm_menu b','a.i_menu = b.i_menu');
        $this->db->join('tr_departement c','a.i_departement = c.i_departement');
        $this->db->join('tr_level d','a.i_level = d.i_level');
        $this->db->join('tm_user_power e','a.id_user_power = e.id');
        $this->db->where('a.i_departement', $idept);
        $this->db->where('a.i_level', $ilevel);
        $this->db->where('a.i_apps', '2');
        return $this->db->get();

        // select a.*, b.e_menu, c.e_departement_name, d.e_level_name, e.e_name
        // from tm_user_role a
        // inner join tm_menu b on a.i_menu = b.i_menu
        // inner join tr_departement c on a.i_departement = c.i_departement
        // inner join tr_level d on a.i_level = d.i_level 
        // inner join tm_user_power e on a.id_user_power = e.id 
        // where a.i_departement = '1' and a.i_level = '1' and a.i_apps = '2'
        // order by a.i_menu
  }

  // function cek_datadet($id){
	// 	$this->db->select('a.*, b.e_material_name, b.i_type_code, c.e_satuan');
  //       $this->db->from('tm_pp_item a');
  //       $this->db->join('tr_material b','a.i_material = b.i_material');
  //       $this->db->join('tr_satuan c','a.i_satuan = c.i_satuan');
  //       $this->db->where('a.i_pp', $id);
  //       return $this->db->get();
	// }

  public function bacauser($idcompany){
        $this->db->select(" * from public.tm_user where id_company = '$idcompany' ",false);
        return $this->db->get();
  }
  public function bacacompany($idcompany){
    $this->db->select(" * from public.company where id = '$idcompany' ",false);
    return $this->db->get();
  }
  public function bacadepart(){
    $this->db->select(" distinct on(a.i_departement::int) a.i_departement::int, b.e_departement_name
                        from public.tm_user_deprole a
                        inner join public.tr_departement b on a.i_departement = b.i_departement
                        order by a.i_departement::int",false);
    return $this->db->get();
  }
  public function bacalevel(){
    $this->db->select(" * from public.tr_level ",false);
    return $this->db->get();
  }

  public function bacamenu(){
    $this->db->select(" distinct a.i_menu, a.e_menu ,length(a.i_menu) as jumlah 
                        from public.tm_menu a
                        inner join tm_user_role b on a.i_menu = b.i_menu
                        WHERE length(a.i_menu)<=3
                        and b.i_apps = '2'
                        order by a.i_menu ",false);
    return $this->db->get();
  }

  public function bacasubmenu(){
    $this->db->select(" distinct a.i_parent, a.e_menu ,length(a.i_parent) as jumlah 
                        from public.tm_menu a
                        inner join tm_user_role b on a.i_menu = b.i_menu
                        WHERE length(a.i_parent)=3 
                        and b.i_apps = '2'
                        order by a.i_parent",false);
    return $this->db->get();
  }
  public function cek_dataheader($idep, $ilev){
      $this->db->select(" * from public.tm_user_role where 
      i_level = '$ilev' and i_departement = '$idep'",false);
      return $this->db->get();
  }

  // public function cek_dataheaderin($idept, $ilevel, $imenu, $iuserpower){
  //   $this->db->select(" * from public.tm_user_role where id_user_power = '$iuserpower' 
  //   and i_menu = '$imenu' and i_level = '$ilevel' and i_departement = '$idept'",false);
  //   return $this->db->get();
  // }

  // public function getkategori() {
  //   return $this->db->order_by('i_kode_kelompok','ASC')->get('tm_kelompok_barang')->result();
  // }

  // public function getjenis() {
  //   return $this->db->order_by('kode_jenis','ASC')->get('tm_jenis_barang')->result();
  // }

  public function getsubmenu($imenu) {
        // $this->db->select(" distinct a.i_parent, a.e_menu ,length(a.i_parent) as jumlah 
        //                     from public.tm_menu a
        //                     inner join tm_user_role b on a.i_menu = b.i_menu
        //                     WHERE length(a.i_parent)=3 
        //                     and b.i_apps = '2'
        //                     order by a.i_parent");
        $this->db->select(" * from tm_menu where i_parent = '$imenu' order by i_menu",false);
        return $this->db->get();
  }

  public function getlev($ideptart) {
    // $this->db->select(" distinct a.i_parent, a.e_menu ,length(a.i_parent) as jumlah 
    //                     from public.tm_menu a
    //                     inner join tm_user_role b on a.i_menu = b.i_menu
    //                     WHERE length(a.i_parent)=3 
    //                     and b.i_apps = '2'
    //                     order by a.i_parent");
    $this->db->select(" distinct on(a.i_level::int)a.i_level::int, b.e_level_name
                        from public.tm_user_deprole a
                        inner join public.tr_level b on a.i_level = b.i_level
                        where a.i_departement = '$ideptart'
                        order by a.i_level::int",false);
    return $this->db->get();
}

  // getlev($ideptart)

  // public function getjeniss($ikategori) {
  //       $this->db->select("i_type_code, e_type_name");
  //       $this->db->from('tr_item_type');
  //       if($ikategori != 'AKB'){

  //       $this->db->where('i_kode_kelompok', $ikategori);
  //       }
  //       $this->db->order_by('i_type_code');
  //       return $this->db->get();
  // }

//   function runningnumber($yearmonth,$ikodemaster){
        
// // var_dump($th);
//         $bl = substr($yearmonth,4,2);
//         $th = substr($yearmonth,0,4);
//         $thn = substr($yearmonth,2,2);
//         $area= substr($ikodemaster,5,2);
// // var_dump($bl);
// //var_dump($area);
//         //$asal=$yearmonth;
//         $asal= substr($yearmonth,0,4);
//         $yearmonth= substr($yearmonth,0,4);
//         //$yearmonth=substr($yearmonth,2,2).substr($yearmonth,4,2);
// // var_dump($yearmonth);
// // die;
//         $this->db->select(" n_modul_no as max from tm_dgu_no 
//                             where i_modul='PP'
//                             and i_area='$area'
//                             and e_periode='$asal' 
//                             and substring(e_periode,1,4)='$th' for update", false);
//         $query = $this->db->get();
//         if ($query->num_rows() > 0){
//           foreach($query->result() as $row){
//             $terakhir=$row->max;
//           }
//           $nopp  =$terakhir+1;
//                 $this->db->query("update tm_dgu_no 
//                             set n_modul_no=$nopp
//                             where i_modul='PP'
//                             and e_periode='$asal' 
//                             and i_area='$area'
//                             and substring(e_periode,1,4)='$th'", false);
//           settype($nopp,"string");
//           $a=strlen($nopp);
  
//           //u/ 0
//           while($a<5){
//             $nopp="0".$nopp;
//             $a=strlen($nopp);
//           }
//             $nopp  ="PP-".$thn.$bl."-".$area.$nopp;
//           return $nopp;
//         }else{
//           $nopp  ="00001";
//           $nopp  ="PP-".$thn.$bl."-".$area.$nopp;
//           $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
//                              values ('PP','$area','$asal',1)");
//           return $nopp;
//         }
//   }
  
	public function insertheader($imenu, $idept, $ilevel,$iuserpower){
        // $dentry = date("d F Y H:i:s");
        $data = array(
          'i_menu'          =>$imenu,
          'id_user_power'   =>$iuserpower,
          'i_departement'   =>$idept,
          'i_level'         =>$ilevel,
          'i_apps'          =>'2'
        );
    $this->db->insert('public.tm_user_role', $data);
  }

  function insertheaderall($imenu, $idept, $ilevel, $iuserpower){
        $data = array(
          'i_menu'          =>$imenu,
          'id_user_power'   =>$iuserpower,
          'i_departement'   =>$idept,
          'i_level'         =>$ilevel,
          'i_apps'          =>'2'
        );
        $this->db->insert('public.tm_user_role', $data);
  }

  // public function insertdetail($ipp, $imaterial ,$isatuan, $nquantity ,$vprice, $fopcomplete, $i, $ikategori){
  //     $data = array(
  //         'i_pp'           => $ipp,
  //         'i_material'     => $imaterial,
  //         'i_satuan'       => $isatuan,
  //         'n_quantity'     => $nquantity,
  //         'v_price'        => $vprice,
  //         'n_item_no'      => $i,
  //         'f_op_complete'  => $fopcomplete,
  //         'n_pemenuhan'    => $nquantity,  
  //         'i_kode_kelompok'=> $ikategori,   
  //     );
  // $this->db->insert('tm_pp_item', $data);
  // }
  
  public function deletedetail($idept, $ilevel, $imenu, $iuserpower){
          $this->db->query("DELETE FROM public.tm_user_role WHERE i_departement='$idept' 
          AND i_level='$ilevel' AND i_apps='2' AND i_menu = '$imenu' AND id_user_power = '$iuserpower'",false);
  }

  public function updateheader($ipp, $ikodemaster, $remark, $dpp){
      $dupdate = date("d F Y H:i:s");
      $data = array(
          'i_kode_master' => $ikodemaster,
          'e_remark'      => $remark,
          'd_pp'          => $dpp,
          'd_update'      => $dupdate
  );

  $this->db->where('i_pp', $ipp);
  $this->db->update('tm_pp', $data);
  }

  // public function updatedetail($nquantity, $ipp,$imaterial){
  //   $data = array(
  //       'n_quantity'    => $nquantity,
  //       'n_pemenuhan'   => $nquantity,   
  //   );

  //   $this->db->where('i_pp', $ipp);
  //   $this->db->where('i_material', $imaterial);
  //   $this->db->update('tm_pp_item', $data);
  // }

  // public function approve($ipp){
  //   $data = array(
  //     'e_approve' => 't',
  //     'e_approval'=>'8',
  //     'd_approve' => date("d F Y H:i:s"),
  // );
  //   $this->db->where('i_pp', $ipp);
  //   $this->db->update('tm_pp', $data);
  // }

  // public function send($kode){
  //     $data = array(
  //         'e_approval'    => '2'
  // );

  // $this->db->where('i_pp', $kode);
  // $this->db->update('tm_pp', $data);
  // }

  // public function sendd($ipp){
  //     $data = array(
  //         'e_approval'    => '2'
  // );

  // $this->db->where('i_pp', $ipp);
  // $this->db->update('tm_pp', $data);
  // }

  // public function cancel_approve($ipp){
  //   $data = array(
  //     'e_approval'=>'7',
  // );
  //   $this->db->where('i_pp', $ipp);
  //   $this->db->update('tm_pp', $data);
  // }

  // public function change_approve($ipp){
  //   $data = array(
  //     'e_approval'=>'3',
  // );
  //   $this->db->where('i_pp', $ipp);
  //   $this->db->update('tm_pp', $data);
  // }

  // public function reject_approve($ipp){
  //   $data = array(
  //     'e_approval'=>'4',
  // );
  //   $this->db->where('i_pp', $ipp);
  //   $this->db->update('tm_pp', $data);
  // }

  // public function appr2($ipp){
  //   $data = array(
  //     'e_approval'=>'5',
  // );
  //   $this->db->where('i_pp', $ipp);
  //   $this->db->update('tm_pp', $data);
  // }

  // public function cancel($i_pp){
  //     $this->db->set(
  //         array(
  //             'e_approval'   => '9',
  //             'f_pp_cancel'  => 't'
  //         )
  //     );
  //     $this->db->where('i_pp',$i_pp);
  //     return $this->db->update('tm_pp');
  // }
}
/* End of file Mmaster.php */