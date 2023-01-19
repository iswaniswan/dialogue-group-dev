<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($folder, $i_menu, $username, $idcompany, $idepartemen, $ilevel, $dfrom, $dto){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        // $datatables->query("select a.i_sj, a.d_sj, b.e_nama_packing, a.f_cancel, c.e_status, a.i_status,
        //                     '$i_menu' as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement
        //                     from tm_sj_keluarpacking a
        //                     inner join tr_unit_packing b on (a.i_unit_packing = b.i_unit_packing)
        //                     inner join tm_status_dokumen c on (a.i_status = c.i_status)
        //                     order by a.i_sj",false);
        $datatables->query("SELECT a.i_sj, a.d_sj, a.f_cancel, c.e_status, a.i_status,
                            '$i_menu' as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement,
                            '$folder' AS folder,
                            '$dfrom' AS dfrom,
                            '$dto' AS dto
                            from tm_sj_keluarpacking a
                            inner join tm_status_dokumen c on (a.i_status = c.i_status)
                            WHERE (d_sj >= to_date('$dfrom','dd-mm-yyyy')
                            AND d_sj <= to_date('$dto','dd-mm-yyyy'))
                            order by a.i_sj",false);
        
        $datatables->edit('f_cancel', function ($data) {
            $f_cancel = trim($data['f_cancel']);
            if($f_cancel == 't'){
               return  "Batal";
            }else {
              return "Aktif";
            }
        });
        
            $datatables->add('action', function ($data) {
            $i_sj           = trim($data['i_sj']);
            $i_menu         = $data['i_menu'];
            $f_cancel       = trim($data['f_cancel']);
            $i_status       = trim($data['i_status']);
            $i_departement  = trim($data['i_departement']);
            $i_level        = trim($data['i_level']);
            $data           = '';


            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"sjkeluarpacking/cform/view/$i_sj/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }
            // if(check_role($i_menu, 3)){   
            //     if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
            //         $data .= "<a href=\"#\" onclick='show(\"sjkeluarpacking/cform/edit/$i_sj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            //     }

            //     if ((($i_departement == '14' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
            //         $data .= "<a href=\"#\" onclick='show(\"sjkeluarpacking/cform/approval/$i_sj\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
            //     }
            // }
            // if ($f_cancel!='t' || $i_status!='5') {
            //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_sj\"); return false;'><i class='fa fa-trash'></i></a>";
            // }

            if(check_role($i_menu, 2)){
            $data .= "<a href=\"#\" onclick='show(\"sjkeluarpacking/cform/view/$i_sj/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_cancel == 'f' && $i_status !='6' && $i_status != '4'){
                $data .= "<a href=\"#\" onclick='show(\"sjkeluarpacking/cform/edit/$i_sj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }   
            if(check_role($i_menu, 1)&& $f_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
                $data .= "<a href=\"#\" onclick='show(\"sjkeluarpacking/cform/approve/$i_sj/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            }
            if ($f_cancel!='t' && $i_status != '6' && $i_status !='4') {
                $data .= "<a href=\"#\" onclick='cancel(\"$i_sj\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }

            
			return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('i_departement');
        $datatables->hide('i_level');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        //$datatables->hide('f_receive');
        return $datatables->generate();
	}

    function bacagudang(){
        $this->db->select(" i_sub_bagian, e_sub_bagian from tm_sub_bagian where i_sub_bagian = 'SDP0010'",false);
        return $this->db->get();
    }
    
    public function bacadepartement(){
        $departement =  $this->session->userdata('i_departement');
        if($departement ==  1){
          return $this->db->query ("SELECT i_departement, e_departement_name FROM public.tr_departement", FALSE)->result();
        }else{
          return $this->db->query ("SELECT i_departement, e_departement_name FROM public.tr_departement WHERE i_departement='$departement'", FALSE)->result();
        }
    }

    public function getmakloonpacking(){
        $username = $this->session->userdata('username');
        $idepartemen = $this->session->userdata('i_departement');
        $ilevel      = $this->session->userdata('i_level');
        $query = $this->db->query("SELECT i_type_makloon FROM tm_user_deprole WHERE username = '$username' and i_departement ='$idepartemen' and i_level ='$ilevel' ", FALSE);
        if($query->num_rows()>0){
          foreach($query->result() as $row){
            $unitpacking =  $row->i_type_makloon;
          }
        }
        return $this->db->query("SELECT i_supplier, e_supplier_name FROM tr_supplier WHERE i_type_makloon = '$unitpacking'", FALSE);
    }

    function cancelheader($isj){
        $this->db->set(
            array(
                'f_cancel' => TRUE,
                'i_status'    => '7'
            )
        );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sj_keluarpacking');
    }

    public function gettujuann($itujuan) {
        // $this->db->select("* from (
        //                 SELECT b.i_tujuan as tujuan,a.i_kode_master as kode , a.e_nama_master as nama FROM tr_master_gudang a , tr_jenis_kirimqc b
        //                 where i_tujuan='GU'
        //                 union all
        //                 SELECT b.i_tujuan as tujuan ,a.i_unit_packing as kode ,a.e_nama_packing as nama FROM tr_unit_packing a,tr_jenis_kirimqc b
        //                 where i_tujuan='UP'
        //                 union all
        //                 SELECT b.i_tujuan as tujuan ,a.i_unit_jahit as kode , a.e_unitjahit_name as nama FROM tr_unit_jahit a,tr_jenis_kirimqc b
        //                 where i_tujuan='UJ'
        //                 ) as a 
        //                 where tujuan ='$itujuan'
        //                 order by tujuan, kode", false);
        $this->db->select("* from (
            SELECT b.i_tujuan as tujuan,a.i_kode_master as kode , a.e_nama_master as nama FROM tr_master_gudang a , tr_jenis_kirimqc b
            where i_tujuan='GU'
            union all
            SELECT b.i_tujuan as tujuan ,a.i_unit_packing as kode ,a.e_nama_packing as nama FROM tr_unit_packing a,tr_jenis_kirimqc b
            where i_tujuan='UP'
            union all
            SELECT b.i_tujuan as tujuan ,a.i_unit_jahit as kode , a.e_unitjahit_name as nama FROM tr_unit_jahit a,tr_jenis_kirimqc b
            where i_tujuan='UJ'
            ) as a 
            where tujuan ='$itujuan'
            order by tujuan, kode", false);
    return $this->db->get();
    }

    function run($thbl){
        $th = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
          $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SJ'
                          and i_area='09'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' for update", false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              foreach($query->result() as $row){
                $terakhir=$row->max;
              }
              $nosj = $terakhir+1;
              $this->db->query("update tm_dgu_no 
                          set n_modul_no=$nosj
                          where i_modul='SJ'
                          and e_periode='$asal' 
                          and i_area='09'
                          and substring(e_periode,1,4)='$th'", false);
              settype($nosj,"string");
              $a=strlen($nosj);
              while($a<7){
                $nosj="0".$nosj;
                $a=strlen($nosj);
              }
                $nosj  ="SJ-".$thbl."-".$nosj;
              return $nosj;
          }else{
              $nosj  ="0000001";
            $nosj  ="SJ-".$thbl."-".$nosj;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SJ','09','$asal',1)");
              return $nosj;
          }
    }

    // public function runningnumber($thbl){
    //     $th = substr($thbl,0,4);
    //     $asal=$thbl;
    //     $thbl=substr($thbl,2,2).substr($thbl,4,2);
    //     $dep = $this->db->query("
    //         SELECT
    //             i_kode
    //         FROM tm_sub_bagian
    //         WHERE i_sub_bagian = 'SDP0010'
    //     ", FALSE)->row()->i_kode;
    //     $query = $this->db->query("
    //         SELECT
    //             n_modul_no AS max
    //         FROM
    //             tm_dgu_no
    //         WHERE
    //             i_modul = 'BON'
    //             AND i_area = 'SDP0010'
    //             AND e_periode = '$asal'
    //             AND substring(e_periode, 1, 4)= '$th' FOR
    //         UPDATE
    //     ", false);
    //     if ($query->num_rows() > 0){
    //         foreach($query->result() as $row){
    //             $terakhir=$row->max;
    //         }
    //         $nobonmk  =$terakhir+1;
    //         $this->db->query("
    //             UPDATE
    //                 tm_dgu_no
    //             SET
    //                 n_modul_no = $nobonmk
    //             WHERE
    //                 i_modul = 'BON'
    //                 AND e_periode = '$asal'
    //                 AND i_area = 'SDP0010'
    //                 AND substring(e_periode, 1, 4)= '$th'
    //         ", false);
    //         settype($nobonmk,"string");
    //         $a=strlen($nobonmk);
    //         while($a<5){
    //             $nobonmk="0".$nobonmk;
    //             $a=strlen($nobonmk);
    //         }
    //         $nobonmk  ="BON-".$dep."-".$thbl."-".$nobonmk;
    //         return $nobonmk;
    //     }else{
    //         $nobonmk  ="00001";
    //         $nobonmk  ="BON-".$dep."-".$thbl."-".$nobonmk;
    //         $this->db->query("
    //             INSERT
    //                 INTO
    //                 tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
    //             VALUES ('BON', '$dep', '$asal', 1)
    //         ");
    //         return $nobonmk;
    //     }
    // }

    function runningnumber($lok,$yearmonth){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= $lok;
        // $area= 'PB';
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);
    
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='BON'
                            and i_area='$area'
                            and e_periode='$asal' 
                            and substring(e_periode,1,4)='$th' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $terakhir=$row->max;
          }
          $nobonmk  =$terakhir+1;
                $this->db->query("update tm_dgu_no 
                            set n_modul_no=$nobonmk
                            where i_modul='BON'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
          settype($nobonmk,"string");
          $a=strlen($nobonmk);
    
          //u/ 0
          while($a<5){
            $nobonmk="0".$nobonmk;
            $a=strlen($nobonmk);
          }
            $nobonmk  ="BON-".$lok."-".$thn.$bl."-".$nobonmk;
          return $nobonmk;
        }else{
          $nobonmk  ="00001";
          $nobonmk  ="BON-".$lok."-".$thn.$bl."-".$nobonmk;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('BON','$area','$asal',1)");
          return $nobonmk;
        }
    }

    function insertheader($isj, $datesj, $iforcast, $eremark, $igudangqc, $itujuankirim, $dtd){
        // var_dump($igudangqc);
        // die();
        $dentry = date("d F Y H:i:s");
        $data   = array(
                    'i_sj'              => $isj,
                    'd_sj'              => $datesj,
                    'i_forcast'         => $iforcast,
                    'e_remark'          => $eremark,
                    'd_entry'           => $dentry,
                    'i_tujuan'          => "UP",
                    'd_entry'           => $dentry,  
                    'i_kode_master'     => $igudangqc,
                    'd_etd'             => $dtd,
                    'i_unit_packing'    => $itujuankirim
        );
        $this->db->insert('tm_sj_keluarpacking', $data);
    }

    function insertdetail($isj, $iproduct, $icolor, $eproductname, $nquantity, $eremarkh, $nitemno){ 
        $data = array(
                    'i_sj'               => $isj,
                    'i_product'          => $iproduct,
                    'e_product_name'     => $eproductname,
                    'i_color'            => $icolor,
                    'n_quantity'         => $nquantity,
                    'e_remark'           => $eremarkh,                    
                    'n_item_no'          => $nitemno,
                    'n_sisa'             => $nquantity,
        );
        $this->db->insert('tm_sj_keluarpacking_item', $data);
    }

    function cek_dataheader($isj){
        // $this->db->select(" a.*, b.e_nama_packing, c.e_sub_bagian, d.e_nama_packing, to_char(a.d_sj, 'dd-mm-yyyy') as dsj, to_char(a.d_etd, 'dd-mm-yyyy') as detd
        //                     from tm_sj_keluarpacking a
        //                     inner join tr_unit_packing b on a.i_unit_packing = b.i_unit_packing
        //                     inner join tm_sub_bagian c on a.i_kode_master = c.i_sub_bagian
        //                     inner join tr_unit_packing d on a.i_unit_packing = d.i_unit_packing
        //                     where a.i_sj='$isj'",false);
        // $this->db->select(" a.*, c.e_sub_bagian, to_char(a.d_sj, 'dd-mm-yyyy') as dsj, to_char(a.d_etd, 'dd-mm-yyyy') as detd
        //                     from tm_sj_keluarpacking a
        //                     inner join tm_sub_bagian c on a.i_kode_master = c.i_sub_bagian
        //                     where a.i_sj='$isj'",false);
        $this->db->select(" a.*, b.e_departement_name, c.e_supplier_name, to_char(a.d_sj, 'dd-mm-yyyy') as dsj, to_char(a.d_etd, 'dd-mm-yyyy') as detd 
                            from tm_sj_keluarpacking a 
                            inner join public.tr_departement b on b.i_departement = a.i_kode_master 
                            inner join tr_supplier c on c.i_supplier = a.i_unit_packing 
                            where a.i_sj='$isj'",false);
    return $this->db->get();
    }

    function cek_datadetail($isj){
        // $this->db->select(" a.*, b.e_color_name
        //                     from tm_sj_keluarpacking_item a
        //                     inner join tr_color b on a.i_color = b.i_color
        //                     where a.i_sj = '$isj'",false);
        $this->db->select(" a.*, c.e_product_basename , b.e_color_name from tm_sj_keluarpacking_item a 
                            inner join tr_color b on a.i_color = b.i_color 
                            inner join tr_product_base c on c.i_product_motif = a.i_product 
                            where a.i_sj = '$isj'",false);
        return $this->db->get();
    }

    function updateheader($isj, $dsj, $igudangqc, $ijenis, $iperiode, $eremark, $detd){ 
        $dupdate  = date("d F Y H:i:s");
        $data  = array(
                'd_sj'              => $dsj,
                'i_unit_packing'    => $ijenis,
                'i_forcast'         => $iperiode,
                'e_remark'          => $eremark,            
                'i_kode_master'     => $igudangqc,
                'd_etd'             => $detd,
                'd_update'          => $dupdate
        );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sj_keluarpacking', $data);
    }

    public function send($kode){
        $data = array(
            'i_status'    => '2'
        );
  
        $this->db->where('i_sj', $kode);
        $this->db->update('tm_sj_keluarpacking', $data);
      }
  
      public function change($kode){
        $data = array(
            'i_status'    => '3'
        );
  
        $this->db->where('i_sj', $kode);
        $this->db->update('tm_sj_keluarpacking', $data);
      }
  
      public function reject($kode){
        $data = array(
            'i_status'    => '4'
        );
  
        $this->db->where('i_sj', $kode);
        $this->db->update('tm_sj_keluarpacking', $data);
      }
  
      public function approve($kode){
        $now = date("Y-m-d");
        $data = array(
            'i_status'   => '6',
            'd_approve' => $now
        );
  
        $this->db->where('i_sj', $kode);
        $this->db->update('tm_sj_keluarpacking', $data);
      }

    function deletedetail($isj) {
        $this->db->query("DELETE FROM tm_sj_keluarpacking_item  WHERE i_sj='$isj'");
    }

    function insertdetailproduct($ibon,$iproduct,$icolor,$imaterial,$ematerialname,$nquantity,$nitemno,$nodetail){  
        $this->db->set(
            array(
                    'i_bonk'                => $ibon,
                    'i_product'             => $iproduct,
                    'i_color'               => $icolor,
                    'i_material'            => $imaterial,
                    'n_quantity'            => $nquantity,
                    'n_item_no'             => $nitemno,
                    'n_no'                  => $nodetail
            )
        );
        $this->db->insert('tm_bonmkeluar_qc_itemdetail');
    }

    function cancelsemuadetail($i_bonk){
        $this->db->set(
            array(
                    'f_item_cancel' =>TRUE,
                )
            );
        $this->db->where('i_bonk',$i_bonk);
        $this->db->update('tm_bonmkeluar_qc_item');
    }

    function deleteheader($i_bonk, $i_product, $i_color){
        $this->db->set(
            array(
                'f_item_cancel' => TRUE,
            )
        );
        $this->db->where('i_bonk',$i_bonk);
        $this->db->where('i_product',$i_product);
        $this->db->where('i_color',$i_color);
        $this->db->update('tm_bonmkeluar_qc_item');
    }

    function deletesemuadetail($i_bonk, $i_product){
        $this->db->set(
            array(
                    'f_cancel' =>TRUE,
                )
            );
            $this->db->where('i_bonk',$i_bonk);
            $this->db->where('i_product',$i_product);
            $this->db->update('tm_bonmkeluar_qc_itemdetail');

    }
}

/* End of file Mmaster.php */
