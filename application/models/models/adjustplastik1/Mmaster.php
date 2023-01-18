<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu,$username, $idcompany, $idepartemen, $ilevel){
    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
          select a.i_adjustment, a.d_tanggal, a.e_remark, a.i_status, e_status, '$i_menu' as i_menu, '$username' as username, '$idcompany' as idcompany, d.i_departement, d.i_level
          from tm_adjusment_accessories a
          inner join tm_status_dokumen b on (a.i_status = b.i_status)
          join public.tm_user_deprole d on d.username = d.username
          where d.username='$username' and d.id_company='$idcompany' and d.i_departement='$idepartemen' and d.i_level='$ilevel'
          order by i_adjustment
        ");

        $datatables->add('action', function ($data) {
            $iadjustment  = trim($data['i_adjustment']);
            $i_menu       = $data['i_menu'];
            $i_departement= trim($data['i_departement']);
            $i_level      = trim($data['i_level']);
            $i_status     = $data['i_status'];
            $data = '';

            if(check_role($i_menu, 2)){
                  $data .= "<a href=\"#\" onclick='show(\"adjustmentaccessories/cform/view/$iadjustment/$i_status/$i_departement/$i_level/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }

            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"adjustmentaccessories/cform/edit/$iadjustment/$i_status/$i_departement/$i_level\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";

                if ((($i_departement == '11' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
                   $data .= "<a href=\"#\" onclick='show(\"adjustmentaccessories/cform/approval/$iadjustment/$i_status/$i_departement/$i_level\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
                }
            }

            if(check_role($i_menu, 4) && ($i_status!= '7' || $i_status!= '9') && ($i_status == '1' || $i_status == '3')){
                $data .= "<a href=\"#\" onclick='cancel(\"$iadjustment\");'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
           return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('username');
        $datatables->hide('idcompany');
        $datatables->hide('i_departement');
        $datatables->hide('i_level');
        $datatables->hide('i_status');
        return $datatables->generate();
  }

  public function bacagudang(){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', 'GD10003');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
  }

  public function jenisbarang(){
        $this->db->select('i_type_code, e_type_name');
        $this->db->from('tr_item_type a');
        $this->db->join("tm_kelompok_barang d","a.i_kode_kelompok = d.i_kode_kelompok");
        $this->db->join("tr_master_gudang e","e.i_kode_master = d.i_kode_master");
        $this->db->join("tr_jenis_gudang f","e.i_kode_jenis = f.i_kode_jenis");
        $this->db->where('d.i_kode_master', 'GD10003');
        $this->db->order_by('a.e_type_name');
        return $this->db->get()->result();
  }

  function runningnumberadjustment($yearmonth, $istore) {
      $bl         = substr($yearmonth,4,2);
      $th         = substr($yearmonth,0,4);
      $thn        = substr($yearmonth,2,2);
      $asal       = substr($yearmonth,0,4);
      $yearmonth  = substr($yearmonth,0,4);
      $area       = substr($istore,5,2);
      $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='ADJ'
                          and i_area='$area'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $noad  =$terakhir+1;
              $this->db->query("update tm_dgu_no 
                          set n_modul_no=$noad
                          where i_modul='ADJ'
                          and e_periode='$asal' 
                          and i_area='$area'
                          and substring(e_periode,1,4)='$th'", false);
        settype($noad,"string");
        $a=strlen($noad);
        while($a<5){
          $noad="0".$noad;
          $a=strlen($noad);
        }
          $noad  ="ADJ-".$thn.$bl."-".$area.$noad;
        return $noad;
      }else{
        $noad  ="00001";
        $noad  ="ADJ-".$thn.$bl."-".$area.$noad;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('ADJ','$area','$asal',1)");
        return $noad;
      }
    }

    public function insertheader($noadjus, $dateadjus, $remark, $istore){
        $dentry = date("d F Y");
        $data = array(
            'i_adjustment'     => $noadjus,
            'd_tanggal'        => $dateadjus,
            'i_status'         => '1',
            'e_remark'         => $remark,
            'i_kode_master'    => $istore,
            'd_entry'          => $dentry,
            
        );
        $this->db->insert('tm_adjusment_accessories', $data);
    }

    public function insertdetail($noadjus, $imaterial, $nquantity, $isatuan, $edesc, $urutan) {               
        $data = array(        
            'i_adjustment'     => $noadjus,
            'i_material'       => $imaterial,
            'n_qty'            => $nquantity,
            'i_satuan_code'    => $isatuan,
            'e_remark'         => $edesc,
            'n_urut'           => $urutan
            
        );
        $this->db->insert('tm_adjusment_accessories_detail', $data);
    }

    public function send($kode){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_adjustment', $kode);
      $this->db->update('tm_adjusment_accessories', $data);
    }

    public function change($kode){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_adjustment', $kode);
      $this->db->update('tm_adjusment_accessories', $data);
    }

    public function reject($kode){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_adjustment', $kode);
      $this->db->update('tm_adjusment_accessories', $data);
    }

    public function approve($kode){
      $now = date("Y-m-d");
      $data = array(
          'i_status'   => '5',
          'd_approve'  => $now
      );

      $this->db->where('i_adjustment', $kode);
      $this->db->update('tm_adjusment_accessories', $data);
    }
      

   public function baca_header($iadjustment){
      return $this->db->query("select ad.i_adjustment, to_char(ad.d_tanggal, 'dd-mm-yyyy') as tanggal, ad.e_remark, ad.i_status, ad.i_kode_master, a.e_nama_master 
        from tm_adjusment_accessories ad 
        inner join tr_master_gudang a on ad.i_kode_master = a.i_kode_master
        where ad.i_adjustment = '$iadjustment' ", false);
    }

    public function baca_detail($iadjustment){
      return $this->db->query("select ad.*, m.e_material_name, s.e_satuan 
        from tm_adjusment_accessories_detail ad
            inner join tr_material m on (ad.i_material = m.i_material)
            inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
            where i_adjustment = '$iadjustment' order by ad.n_urut ", false);
    }

    public function updateheader($noadjus, $dateadjus, $remark, $istore){
        $dupdate = date("d F Y");
        $data = array(
            'd_tanggal'     => $dateadjus,
            'e_remark'      => $remark,
            'i_kode_master' => $istore,
            'd_update'      => $dupdate
            
        );
        $this->db->where('i_adjustment', $noadjus);
        $this->db->update('tm_adjusment_accessories', $data);
    }

    public function deletedetail($noadjus){
        $this->db->query("DELETE FROM tm_adjusment_accessories_detail WHERE i_adjustment='$noadjus' ");
    }

    public function cek_product($bonmkp,$gudang, $i_material){
      return $this->db->query("select i_material from tm_bonmkeluar_pinjamanbb_detail where i_bonmk = '$bonmkp' and i_kode_master = '$gudang' and i_material = '$i_material' ", false);
    }

    public function updatedetail($nobonkeluar,$istore, $imaterial, $nquantity,$isatuan, $edesc, $urutan){
        $this->db->query("UPDATE tm_bonmkeluar_pinjamanbb_detail SET n_qty = n_qty+$nquantity WHERE i_bonmk='$nobonkeluar' and i_kode_master='$istore' and i_material = '$imaterial' ");
    }

    public function cancel($i_adjus){
        $this->db->set(
            array(
                'i_status'  => '7'
            )
        );
        $this->db->where('i_adjustment',$i_adjus);
        return $this->db->update('tm_adjusment_accessories');
    }   
}
/* End of file Mmaster.php */