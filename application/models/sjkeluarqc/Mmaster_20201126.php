<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $username, $idcompany, $idepartemen, $ilevel){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_sj, a.d_sj, b.e_supplier_name, a.f_cancel, c.e_status, a.i_status,
                            '$i_menu' as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement
                            from tm_sj_keluarqc a
                            inner join tr_supplier b on a.i_tujuan_kirim = b.i_supplier
                            inner join tm_status_dokumen c on (a.i_status = c.i_status)
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


            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"sjkeluarqc/cform/view/$i_sj/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){   
                // $data .= "<a href=\"#\" onclick='show(\"sjkeluarqc/cform/edit/$i_sj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
                if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" onclick='show(\"sjkeluarqc/cform/edit/$i_sj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
                }

                if ((($i_departement == '14' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"sjkeluarqc/cform/approval/$i_sj\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
                }
            }
            // if ($i_status!='5') {
            //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_bonk\"); return false;'><i class='fa fa-trash'></i></a>";
            // }
            if ($f_cancel!='t' || $i_status!='5') {
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_sj\"); return false;'><i class='fa fa-trash'></i></a>";
          }
			return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('i_departement');
        $datatables->hide('i_level');
        //$datatables->hide('f_receive');
        return $datatables->generate();
	}

    function bacagudang(){
        $this->db->select(" i_sub_bagian, e_sub_bagian from tm_sub_bagian where i_sub_bagian = 'SDP0009'",false);
        return $this->db->get();
    }

    function cancelheader($isj){
        $this->db->set(
            array(
                'f_cancel' => TRUE,
            )
        );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sj_keluarqc');
    }

    public function gettujuann($itujuan) {
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

    public function runningnumber($thbl){
        $th = substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $dep = $this->db->query("
            SELECT
                i_kode
            FROM tm_sub_bagian
            WHERE i_sub_bagian = 'SDP0009'
        ", FALSE)->row()->i_kode;
        $query = $this->db->query("
            SELECT
                n_modul_no AS max
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'BON'
                AND i_area = 'SDP0009'
                AND e_periode = '$asal'
                AND substring(e_periode, 1, 4)= '$th' FOR
            UPDATE
        ", false);
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobonmk  =$terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nobonmk
                WHERE
                    i_modul = 'BON'
                    AND e_periode = '$asal'
                    AND i_area = 'SDP0009'
                    AND substring(e_periode, 1, 4)= '$th'
            ", false);
            settype($nobonmk,"string");
            $a=strlen($nobonmk);
            while($a<5){
                $nobonmk="0".$nobonmk;
                $a=strlen($nobonmk);
            }
            $nobonmk  ="BON-".$dep."-".$thbl."-".$nobonmk;
            return $nobonmk;
        }else{
            $nobonmk  ="00001";
            $nobonmk  ="BON-".$dep."-".$thbl."-".$nobonmk;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                VALUES ('BON', '$dep', '$asal', 1)
            ");
            return $nobonmk;
        }
    }

    function insertheader($isj, $datesj, $iforcast, $eremark, $itujuan, $igudangqc, $itujuankirim){
        // var_dump($igudangqc);
        // die();
        $dentry = date("d F Y H:i:s");
        $data   = array(
                    'i_sj'              => $isj,
                    'd_sj'              => $datesj,
                    'i_forcast'         => $iforcast,
                    'e_remark'          => $eremark,
                    'd_entry'           => $dentry,
                    'i_tujuan'          => $itujuan,
                    'd_entry'           => $dentry,  
                    'i_kode_master'     => $igudangqc,
                    'i_tujuan_kirim'    => $itujuankirim
        );
        $this->db->insert('tm_sj_keluarqc', $data);
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
        $this->db->insert('tm_sj_keluarqc_item', $data);
    }

    function cek_dataheader($isj){
        $this->db->select(" a.*, b.e_supplier_name, c.e_sub_bagian, to_char(a.d_sj, 'dd-mm-yyyy') as dsj
                            from tm_sj_keluarqc a
                            inner join tr_supplier b on a.i_tujuan_kirim = b.i_supplier
                            inner join tm_sub_bagian c on a.i_kode_master = c.i_sub_bagian
                            where a.i_sj='$isj'",false);
    return $this->db->get();
    }

    function cek_datadetail($isj){
        $this->db->select(" a.*, b.e_color_name
                            from tm_sj_keluarqc_item a
                            inner join tr_color b on a.i_color = b.i_color
                            where a.i_sj = '$isj'",false);
        return $this->db->get();
    }

    function updateheader($isj, $dsj, $igudangqc, $ijenis, $iperiode, $eremark){ 
        $dupdate  = date("d F Y H:i:s");
        $data  = array(
                'd_sj'              => $dsj,
                'i_tujuan_kirim'    => $ijenis,
                'i_forcast'         => $iperiode,
                'e_remark'          => $eremark,            
                'i_kode_master'     => $igudangqc,
                'd_update'          => $dupdate
        );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sj_keluarqc', $data);
    }

    public function send($kode){
        $data = array(
            'i_status'    => '2'
        );
  
        $this->db->where('i_sj', $kode);
        $this->db->update('tm_sj_keluarqc', $data);
      }
  
      public function change($kode){
        $data = array(
            'i_status'    => '3'
        );
  
        $this->db->where('i_sj', $kode);
        $this->db->update('tm_sj_keluarqc', $data);
      }
  
      public function reject($kode){
        $data = array(
            'i_status'    => '4'
        );
  
        $this->db->where('i_sj', $kode);
        $this->db->update('tm_sj_keluarqc', $data);
      }
  
      public function approve($kode){
        $now = date("Y-m-d");
        $data = array(
            'i_status'   => '5',
            'd_approve' => $now
        );
  
        $this->db->where('i_sj', $kode);
        $this->db->update('tm_sj_keluarqc', $data);
      }

    function deletedetail($isj) {
        $this->db->query("DELETE FROM tm_sj_keluarqc_item  WHERE i_sj='$isj'");
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
