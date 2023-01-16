<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function bagianpembuat(){
    $username   = $this->session->userdata('username');
    $idcompany  = $this->session->userdata('id_company');
    $ilevel     = $this->session->userdata('i_level');
    $idepart    = $this->session->userdata('i_departement');
    $lokasi     = $this->session->userdata('i_lokasi');

    if(trim($idepart) == '1'){
      return $this->db->query(" SELECT a.* FROM public.tr_departement a ORDER BY a.i_departement", FALSE);
    }else{
      $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";
    
      return $this->db->query(" 
                                SELECT 
                                    a.*, 
                                    b.e_departement_name, 
                                    c.e_level_name
                                FROM 
                                    public.tm_user_deprole a
                                INNER JOIN 
                                    public.tr_departement b 
                                    ON a.i_departement = b.i_departement
                                INNER JOIN 
                                    public.tr_level c 
                                    ON a.i_level = c.i_level $where 
                            ", FALSE);
    }
  }

  function data($dfrom, $dto, $i_menu, $folder){
    $datatables = new Datatables(new CodeigniterAdapter);
    $i_departement = $this->session->userdata('i_departement');
    $i_departement = trim($i_departement);
    $i_level       = $this->session->userdata('i_level');
    $i_level       = trim($i_level);
        $datatables->query("
                            SELECT
                              ROW_NUMBER() OVER (ORDER BY i_bonm) as nomor,
                              a.i_bonm,
                              to_char(a.d_bonm, 'dd-mm-yyyy') as d_bonm,
                              a.i_referensi,
                              a.e_remark,
                              a.i_status,
                              b.e_status as namastatus,
                              b.label_color as label,
                              a.f_bonm_cancel,
                              '$i_menu' as i_menu, 
                              '$folder' as folder,
                              '$dfrom' as dfrom,
                              '$dto' as dto,
                              '$i_departement' as i_departement,
                              '$i_level' as i_level
                            FROM
                              tm_bonmmasuk_gudangjadi a
                              LEFT JOIN tm_status_dokumen b
                              ON (a.i_status = b.i_status)
                            WHERE 
                              a.d_bonm >= to_date('$dfrom','dd-mm-yyyy')
                              AND a.d_bonm <= to_date('$dto', 'dd-mm-yyyy')
                            ", false);

        $datatables->edit('f_bonm_cancel', function ($data) {
          $f_bonm_cancel = trim($data['f_bonm_cancel']);
            if($f_bonm_cancel == 't'){
               return  "Batal";
            }else {
              return "Aktif";
            }
        });

        $datatables->edit('i_status', function ($data) {
          if($data['f_bonm_cancel'] == 't'){
            return '<span class="label label-danger label-rouded">Batal</span>';
          }
          return '<span class="label label-'.$data['label'].' label-rouded">'.$data['namastatus'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $ibonm          = trim($data['i_bonm']);
            $folder         = $data['folder'];
            $i_menu         = $data['i_menu'];
            $f_bonm_cancel  = trim($data['f_bonm_cancel']);
            $dfrom          = trim($data['dfrom']);
            $dto            = trim($data['dto']);
            $i_departement  = ($data['i_departement']);
            $i_level        = ($data['i_level']);
            $i_status       = ($data['i_status']);
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$ibonm/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if ($f_bonm_cancel == 'f' && $i_status != '6') {
              $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$ibonm/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
              if ((($i_departement == '16' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$ibonm/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
              }
              $data .= "<a href=\"#\" onclick='cancel(\"$ibonm\"); return false;'><i class='ti-close'></i></a>&nbsp;&nbsp;";
            }
          return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('f_bonm_cancel');
        $datatables->hide('namastatus');
        $datatables->hide('label');
        $datatables->hide('i_departement');
        $datatables->hide('i_level');
        return $datatables->generate();
  }

  public function bacagudang(){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', 'GD10004');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
  }

  public function send($kode){
    $data = array(
        'i_status'    => '2'
    );

    $this->db->where('i_bonm', $kode);
    $this->db->update('tm_bonmmasuk_gudangjadi', $data);
  }

  public function change($kode){
    $data = array(
        'i_status'    => '3'
    );

    $this->db->where('i_bonm', $kode);
    $this->db->update('tm_bonmmasuk_gudangjadi', $data);
  }

  public function reject($kode){
    $data = array(
        'i_status'    => '4'
    );

    $this->db->where('i_bonm', $kode);
    $this->db->update('tm_bonmmasuk_gudangjadi', $data);
  }

  public function approve($ibonm, $datebonm, $istore, $ireferensi, $eremark){
    $now = date("Y-m-d");
    $data = array(
                'i_status'        => '6',
                'd_bonm'          => $datebonm,
                'i_kode_master'   => $istore,
                'i_referensi'     => $ireferensi,
                'e_remark'        => $eremark,
                'd_approve'       => $now
    );
    $this->db->where('i_bonm', $ibonm);
    $this->db->update('tm_bonmmasuk_gudangjadi', $data);
}

  public function bonmk($cari, $gudang){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                SELECT
                                    i_bonk, to_char(d_bonk, 'dd-mm-yyyy') AS d_bonk 
                                FROM
                                    tm_bonmkeluar_packing
                                WHERE
                                    i_tujuan = '$gudang'
                                    AND f_bonk_cancel = 'f'", 
                                FALSE);
  }

  public function getbonmk($ibonmk,$gudang){
    $ibonmk = trim($ibonmk);
    return $this->db->query("SELECT i_bonk, to_char(d_bonk, 'dd-mm-yyyy') as d_bonk, e_remark, i_bagian FROM tm_bonmkeluar_packing WHERE i_bonk='$ibonmk' AND i_tujuan='$gudang'", FALSE);
  }

  public function getbonmk_detail($ibonmk,$gudang){
    return $this->db->query("
                            SELECT
                              a.i_bonk,
                              a.i_product,
                              a.i_color,
                              a.n_quantity,
                              c.e_product_basename,
                              d.e_color_name
                            FROM
                              tm_bonmkeluar_packing_item a
                              LEFT JOIN tm_bonmkeluar_packing b
                              ON (a.i_bonk = b.i_bonk)
                              LEFT JOIN tr_product_base c
                              ON (a.i_product = c.i_product_motif)
                              LEFT JOIN tr_color d
                              ON (a.i_color = d.i_color)
                            WHERE
                              a.i_bonk = '$ibonmk'
                              AND b.i_tujuan = '$gudang'
                              ", FALSE);
  }

  public function runningnumber($yearmonth,$isubbagian){
    $bl       = substr($yearmonth,4,2);
    $th       = substr($yearmonth,0,4);
    $thn      = substr($yearmonth,2,2);
    $area     = trim($isubbagian);
    $asal     = substr($yearmonth,0,4);
    $yearmonth= substr($yearmonth,0,4);

    $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='BONM'
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
                        where i_modul='BONM'
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
        $nopp  ="BONM"."-".$area."-".$thn.$bl."-".$nopp;
      return $nopp;
    }else{
      $nopp  ="00001";
      $nopp  ="BONM"."-".$area."-".$thn.$bl."-".$nopp;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('BONM','$area','$asal',1)");
      return $nopp;
    }
  }

  public function insertheader($ibonm, $datebonm, $istore, $ireferensi, $eremark){
        $dentry = date("Y-m-d");
        $data = array(
            'i_bonm'          => $ibonm,
            'd_bonm'          => $datebonm,
            'i_kode_master'   => $istore,
            'i_referensi'     => $ireferensi,
            'e_remark'        => $eremark,
            'd_entry'         => $dentry
            
        );
        $this->db->insert('tm_bonmmasuk_gudangjadi', $data);
  }

  public function insertdetail($ibonm, $iproduct, $icolor, $nquantitykeluar, $nquantitymasuk, $edesc, $nitemno){               
        $data = array(        
            'i_bonm'            => $ibonm,
            'i_product'         => $iproduct,
            'i_color'           => $icolor,
            'n_quantity_keluar' => $nquantitykeluar,
            'n_quantity_masuk'  => $nquantitymasuk,
            'e_remark'          => $edesc,
            'n_item_no'         => $nitemno,
            
        );
        $this->db->insert('tm_bonmmasuk_gudangjadi_detail', $data);
  }

	public function baca_header($ibonm){
    return $this->db->query("
                          SELECT
                            a.i_bonm,
                            to_char(a.d_bonm, 'dd-mm-yyyy') as d_bonm,
                            a.i_kode_master,
                            a.i_referensi,
                            a.e_remark,
                            b.e_departement_name, 
                            to_char(c.d_bonk, 'dd-mm-yyyy') as d_bonk
                          FROM
                            tm_bonmmasuk_gudangjadi a 
                            LEFT JOIN public.tr_departement b
                            ON (a.i_kode_master =  b.i_departement)
                            LEFT JOIN tm_bonmkeluar_packing c
                            ON (a.i_referensi = c.i_bonk)
                          WHERE
                            a.i_bonm = '$ibonm'
                          ", FALSE);
  }

  public function baca_detail($ibonm){
      return $this->db->query("
                          SELECT
                            a.i_bonm,
                            a.i_product,
                            b.e_product_basename,
                            a.i_color,
                            c.e_color_name,
                            a.n_quantity_keluar,
                            a.n_quantity_masuk,
                            a.e_remark 
                          FROM
                            tm_bonmmasuk_gudangjadi_detail a 
                            LEFT JOIN
                               tr_product_base b 
                               ON a.i_product = b.i_product_motif 
                            LEFT JOIN
                               tr_color c 
                               ON a.i_color = c.i_color 
                          WHERE
                            a.i_bonm = '$ibonm'
                          ", FALSE);
  }

  public function cek_gudang(){
       return $this->db->query("select * from tr_master_gudang", false);

  }

  public function updateheader($ibonm, $datebonm, $istore, $ireferensi, $eremark){
        $dupdate = date("Y-m-d");
        $data = array(
                    'd_bonm'          => $datebonm,
                    'i_kode_master'   => $istore,
                    'i_referensi'     => $ireferensi,
                    'e_remark'        => $eremark,
                    'd_update'        => $dupdate
        );
        $this->db->where('i_bonm', $ibonm);
        $this->db->update('tm_bonmmasuk_gudangjadi', $data);
  }

  public function deletedetail($ibonm){
        $this->db->query("DELETE FROM tm_bonmmasuk_gudangjadi_detail WHERE i_bonm='$ibonm'");
  }  

  public function cekic($iproduct, $icolor){
    return $this->db->query("SELECT i_product, n_quantity_stock FROM tm_ic WHERE i_product='$iproduct' AND i_product_grade='A'", FALSE)->row();
  }

  public function updateic($iproduct, $ikodelokasi, $nquantitymasuk, $nqty){
    $data = array(
      'n_quantity_stock' => $nqty+$nquantitymasuk,
    );
    $this->db->where('i_product', $iproduct);
    $this->db->where('i_kode_lokasi', $ikodelokasi);
    $this->db->where('i_product_grade', 'A');
    $this->db->update('tm_ic', $data);
  }

  public function insertic($iproduct, $ikodelokasi, $nquantitymasuk){
    $data = array(
        'i_product'         => $iproduct,
        'i_product_grade'   => 'A',
        'i_kode_lokasi'     => $ikodelokasi,
        'n_quantity_stock'  => $nquantitymasuk,
        'f_product_active'  => 't'
        
    );
    $this->db->insert('tm_ic', $data);
  }

  public function insertictrans($iproduct, $iproductgrade, $ikodelokasi, $ibonm, $nqty_in, $nqty_out, $nqty_akhir, $nqty_awal, $i){
    $now = date("Y-m-d");

    $data = array(
      'i_product'              => $iproduct,
      'i_product_grade'        => $iproductgrade,
      'i_lokasi'               => $ikodelokasi,
      'i_refference_document'  => $ibonm,
      'd_transaction'          => $now,
      'n_quantity_in'          => $nqty_in,
      'n_quantity_out'         => $nqty_out,
      'n_quantity_akhir'       => $nqty_akhir,
      'n_quantity_awal'        => $nqty_awal
      
    );
    $this->db->insert('tm_ic_trans', $data);
  }

  public function cancel($ibonm){
        $this->db->set(
            array(
                'f_bonm_cancel'  => 't'
            )
        );
        $this->db->where('i_bonm',$ibonm);
        return $this->db->update('tm_bonmmasuk_gudangjadi');
  }
}
/* End of file Mmaster.php */
