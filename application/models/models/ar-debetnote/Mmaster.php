<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu, $username, $idcompany, $idepartemen, $ilevel){
        $datatables = new Datatables(new CodeigniterAdapter);
        
      $datatables->query("select a.i_nota_ar, a.d_nota_ar, a.i_partner,c.e_customer_name, a.i_referensi, a.i_kasbank, b.e_nama_kas, a.e_remark, a.f_dn_cancel, a.i_status, d.e_status, '$i_menu' as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement 
                        from tm_debet_nota_ar a
                        join tm_kas_bank b on a.i_kasbank=b.i_kode_kas
                        join tr_customer c on a.i_partner = c.i_customer
                        join tm_status_dokumen d on a.i_status=d.i_status");

        $datatables->add('action', function ($data) {
            $inotaar          = trim($data['i_nota_ar']);
            $ipartner        = trim($data['i_partner']);
            $ireferensi        = trim($data['i_referensi']);
            $f_dn_cancel      = trim($data['f_dn_cancel']);
            $i_status         = trim($data['i_status']);
            $i_menu           = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                 $data .= "<a href=\"#\" onclick='show(\"ar-debetnote/cform/view/$inotaar/$ipartner/$ireferensi/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_dn_cancel != 't' && $i_status !='6'){
                $data .= "<a href=\"#\" onclick='show(\"ar-debetnote/cform/edit/$inotaar/$ipartner/$ireferensi/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 1)&& $f_dn_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
              $data .= "<a href=\"#\" onclick='show(\"ar-debetnote/cform/approve/$inotaar/$ipartner/$ireferensi/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 4) && $f_dn_cancel == 'f' && $i_status!='6'){
                $data .= "<a href=\"#\" onclick='cancel(\"$inotaar\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('f_dn_cancel');
        $datatables->hide('i_level');
        $datatables->hide('i_departement');
        $datatables->hide('i_partner');
        $datatables->hide('i_kasbank');
        $datatables->hide('i_status');
        return $datatables->generate();
    }

    function partner(){
      $this->db->select("x.i_customer, x.e_customer_name
                                from(
                                    select distinct a.i_customer as i_customer,
                                        a.e_customer_name as e_customer_name
                                        from tr_customer a 
                                        join tm_alokasi b on a.i_customer = b.i_customer 
                                    UNION ALL
                                    select distinct a.i_karyawan as i_customer, a.e_nama_karyawan 
                                        from tm_karyawan a 
                                        join  tm_kas_masuk_hutangdagang b on a.i_karyawan=b.i_pic
                                    ) as x 
                                    group by x.i_customer, x.e_customer_name", false);
       return $this->db->get();
    }

    function bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany){
        $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";
        return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name
                                  from public.tm_user_deprole a
                                  inner join public.tr_departement b on a.i_departement = b.i_departement
                                  inner join public.tr_level c on a.i_level = c.i_level $where ", FALSE);
    }

    function getreferensi($ipartner){
        $ipartner    = $this->input->post('ipartner');
        $this->db->select("a.i_alokasi as i_referensi 
                            from tm_alokasi a 
                            where a.i_customer='$ipartner'");
        $data = $this->db->get();
        return $data;
    }

    function getreferensikb($ipartner){
        $ipartner    = $this->input->post('ipartner');
        $this->db->select("a.i_kas_masuk_hd as i_referensi 
                            from tm_kas_masuk_hutangdagang a 
                            where a.i_pic='$ipartner'");
        $data = $this->db->get();
        return $data;
    }

    function getitemp($ireferensipp, $ipartner){
        $ireferensipp  = $this->input->post('ireferensipp');
        $ipartner      = $this->input->post('ipartner');

        $this->db->select("a.i_alokasi as nodok, a.d_alokasi as ddok, a.i_customer as partner, b.e_customer_name as epartner, a.v_lebih as jumlah_lebih 
                          from tm_alokasi a
                            join tr_customer b on a.i_customer = b.i_customer
                            where a.i_alokasi = '$ireferensipp'
                            and a.i_customer = '$ipartner'
                            and a.v_lebih > 0",false);

        return $this->db->get();
    }

    function getitemk($ireferensikb, $ipartner){
        $ireferensikb  = $this->input->post('ireferensikb');
        $ipartner      = $this->input->post('ipartner');

        $this->db->select("a.i_kas_masuk_hd as nodok, a.d_kas_masuk_hd as ddok, a.i_pic as partner, a.n_lebih as jumlah_lebih, b.e_nama_karyawan as epartner
                        from tm_kas_masuk_hutangdagang a
                        join tm_karyawan b on a.i_pic = b.i_karyawan
                        where a.i_kas_masuk_hd = '$ireferensikb'
                        and a.i_pic = '$ipartner'
                        and a.n_lebih > 0",false);

        return $this->db->get();
    }

    public function runningnumber($yearmonth, $bagian){
        $bl  = substr($yearmonth,4,2);
        $th  = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= "0".trim($bagian);
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);

        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='DNAR'
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
                            where i_modul='DNAR'
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
            $nopp  ="DNAR-".$area."-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="DNAR-".$area."-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('DNAR','$area','$asal',1)");
          return $nopp;
        }
    }

    public function send($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_nota_ar', $kode);
    $this->db->update('tm_debet_nota_ar', $data);
    }

    function insertheader($inotaar, $ibagian, $datenota, $ireferensi, $ipartner, $ikasbank, $eremark){
        $dentry = date("Y-m-d");
        
        $data = array(
                      'i_nota_ar'      => $inotaar,
                      'd_nota_ar'      => $datenota,
                      'i_referensi'    => $ireferensi,
                      'i_bagian'       => $ibagian,
                      'i_partner'      => $ipartner,
                      'i_kasbank'      => $ikasbank,
                      'i_status'       => '1',
                      'e_remark'       => $eremark,
                      'd_entry'        => $dentry,
        );
        $this->db->insert('tm_debet_nota_ar', $data);
    }

    function insertdetail($inotaar, $nodok, $ddok, $partner, $jumlah_lebih, $jumlah, $nitemno){
        $data = array(
                      'i_nota_ar'       => $inotaar,
                      'i_referensi'     => $nodok,
                      'd_referensi'     => $ddok,
                      'i_customer'      => $partner,
                      'n_price'         => $jumlah_lebih,
                      'n_price_back'    => $jumlah,
                      'n_item_no'       => $nitemno,
        );
        $this->db->insert('tm_debet_nota_ar_detail', $data);
    }

    function cekalokasi($ireferensi, $ipartner){
      $this->db->select("i_alokasi from tm_alokasi where i_alokasi='$ireferensi' and i_customer='$ipartner'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $i_nota = $kuy->i_alokasi; 
        }else{
            $i_nota = '';
        }
        return $i_nota;
    }

    function ceknilaialokasi($ireferensi, $ipartner){
      $this->db->select("v_lebih from tm_alokasi where i_alokasi='$ireferensi' and i_customer='$ipartner'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $v_lebih = $kuy->v_lebih; 
        }else{
            $v_lebih = '';
        }
        return $v_lebih;
    }

    function cekhutangdagang($ireferensi, $ipartner){
        $this->db->select("i_kas_masuk_hd from tm_kas_masuk_hutangdagang where i_kas_masuk_hd='$ireferensi' and i_customer='$ipartner'", false);
        $query = $this->db->get();
          if ($query->num_rows()>0) {
              $kuy   = $query->row();
              $i_nota = $kuy->i_kas_masuk_hd; 
          }else{
              $i_nota = '';
          }
          return $i_nota;
    }

    function ceknilaihd($ireferensi, $ipartner){
      $this->db->select("n_lebih from tm_kas_masuk_hutangdagang where i_kas_masuk_hd='$ireferensi' and i_pic='$ipartner'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $n_lebih = $kuy->n_lebih; 
        }else{
            $n_lebih = '';
        }
         return $n_lebih;
    }

    function updatejumlahalokasi($ireferensi, $ipartner, $total){
          $data = array(
              'v_lebih' => $total,
          );
          $this->db->where('i_alokasi', $ireferensi);
          $this->db->where('i_customer',$ipartner);
          $this->db->update('tm_alokasi', $data);
    }

    function updatejumlahhutangdagang($ireferensi, $ipartner, $totalhd){
          $data = array(
              'n_lebih' => $totalhd,
          );
          $this->db->where('i_kas_masuk_hd', $ireferensi);
          $this->db->where('i_pic',$ipartner);
          $this->db->update('tm_kas_masuk_hutangdagang', $data);
    }

    function cek_referensi($ipartner){
        $this->db->select("a.i_alokasi as i_referensi 
                            from tm_alokasi a 
                            where a.i_customer='$ipartner'");
        return $this->db->get();
    }

    function cek_referensii($ipartner){
        $this->db->select("a.i_kas_masuk_hd as i_referensi 
                            from tm_kas_masuk_hutangdagang a 
                            where a.i_customer='$ipartner'");
        return $this->db->get();
    }

    function cek_kasbank(){
        $this->db->select("* from tm_kas_bank", false);
        return $this->db->get();
    }

    function cek_dataheader($inotaar){
        $this->db->select("a.i_nota_ar, to_char(a.d_nota_ar, 'dd-mm-yyyy') as d_nota_ar, a.i_referensi, a.i_bagian, a.i_partner, a.i_kasbank, a.e_remark, a.i_status 
                          from tm_debet_nota_ar a
                          where a.i_nota_ar='$inotaar'", false);
        return $this->db->get();
    }

    function cek_datadetail($inotaar){
        $this->db->select("a.i_nota_ar, a.i_referensi, a.d_referensi, a.i_customer, b.e_customer_name, a.n_price, a.n_price_back 
                          from tm_debet_nota_ar_detail a
                          join tr_customer b on a.i_customer=b.i_customer
                          where a.i_nota_ar='$inotaar'", false);
        return $this->db->get();
    }

    function cek1($ireferensi){
        $this->db->select("a.i_alokasi as i_referensi 
                            from tm_alokasi a 
                            where a.i_alokasi='$ireferensi'", false);
         return $this->db->get();
    }

    function cek2($ireferensi){
        $this->db->select("a.i_kas_masuk_hd as i_referensi 
                            from tm_kas_masuk_hutangdagang a 
                            where a.i_kas_masuk_hd='$ireferensi'", false);
         return $this->db->get();
    }


    function updateheader($inotaar, $ibagian, $datenota, $ireferensi, $ipartner, $ikasbank, $eremark){
        $dupdate = date("Y-m-d ");
        $data = array(
                      'd_nota_ar'      => $datenota,
                      'i_referensi'    => $ireferensi,
                      'i_bagian'       => $ibagian,
                      'i_partner'      => $ipartner,
                      'i_kasbank'      => $ikasbank,
                      'e_remark'       => $eremark,
                      'd_update'       => $dupdate,
        );
        $this->db->where('i_nota_ar', $inotaar);
        $this->db->update('tm_debet_nota_ar', $data);
    }

    public function deletedetail($inotaar){
        $this->db->query("DELETE FROM tm_debet_nota_ar_detail WHERE i_nota_ar='$inotaar'");
    }

    public function sendd($inotaar){
      $data = array(
          'i_status'    => '2'
    );

    $this->db->where('i_nota_ar', $inotaar);
    $this->db->update('tm_debet_nota_ar', $data);
    }

    public function cancel_approve($inotaar){
        $data = array(
                  'i_status'=>'7',
    );
    $this->db->where('i_nota_ar', $inotaar);
    $this->db->update('tm_debet_nota_ar', $data);
    }

    public function cancel($inotaar){
        $data = array(
                  'f_dn_cancel' => 't',
                  'i_status'              => '9',
    );
    $this->db->where('i_nota_ar', $inotaar);
    $this->db->update('tm_debet_nota_ar', $data);
    }

    public function approve($inotaar){
        $data = array(
                'i_status'          =>'6',
    );
    $this->db->where('i_nota_ar', $inotaar);
    $this->db->update('tm_debet_nota_ar', $data);
    }

    public function change_approve($inotaar){
        $data = array(
                'i_status'=>'3',
    );
    $this->db->where('i_nota_ar', $inotaar);
    $this->db->update('tm_debet_nota_ar', $data);
    }

    public function reject_approve($inotaar){
      $data = array(
              'i_status'=>'4',
    );
    $this->db->where('i_nota_ar', $inotaar);
    $this->db->update('tm_debet_nota_ar', $data);
    }
}
/* End of file Mmaster.php */