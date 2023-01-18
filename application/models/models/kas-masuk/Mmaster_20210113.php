<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $username, $idcompany, $idepartemen, $ilevel){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT a.i_kas_masuk, a.d_kas_masuk, a.i_kas_bank, d.e_nama_kas, a.i_customer, b.e_customer_name, a.f_kas_masuk_cancel, a.i_status, c.e_status, $i_menu as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement 
                            FROM tm_kas_masuk a 
                            JOIN tm_kas_masuk_detail ma on a.i_kas_masuk=ma.i_kas_masuk
                            JOIN tr_customer b on ma.i_customer=b.i_customer
                            JOIN tm_status_dokumen c on a.i_status=c.i_status
                            JOIN tm_kas_bank d on a.i_kas_bank=d.i_kode_kas
                            ",false);
        
            $datatables->add('action', function ($data) {
            $ikasmasuk          = trim($data['i_kas_masuk']);
            $i_menu             = $data['i_menu'];
            $f_kas_masuk_cancel = trim($data['f_kas_masuk_cancel']);
            $i_status           = trim($data['i_status']);
            $data               = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"kas-masuk/cform/view/$ikasmasuk/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)&& $f_kas_masuk_cancel == 'f' && $i_status !='6'){                
                $data .= "<a href=\"#\" onclick='show(\"kas-masuk/cform/edit/$ikasmasuk/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            
            }
            if(check_role($i_menu, 1)&& $f_kas_masuk_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
              $data .= "<a href=\"#\" onclick='show(\"kas-masuk/cform/approve/$ikasmasuk/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_kas_masuk_cancel == 'f' && $i_status!='6'){
                $data .= "<a href=\"#\" onclick='cancel(\"$ikasmasuk\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
			return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('f_kas_masuk_cancel');
        $datatables->hide('i_status');
        $datatables->hide('i_level');
        $datatables->hide('i_departement');
        $datatables->hide('i_customer');
        $datatables->hide('i_kas_bank');
        return $datatables->generate();
	}

    function bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany){
        $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";
        return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name
                                  from public.tm_user_deprole a
                                  inner join public.tr_departement b on a.i_departement = b.i_departement
                                  inner join public.tr_level c on a.i_level = c.i_level $where ", FALSE);
    }

    function customer($cari){
        $this->db->select("a.i_customer, a.e_customer_name 
                            from tr_customer a where a.e_customer_name like '%$cari%' order by a.e_customer_name",false);

        $data = $this->db->get();
        return $data;
    }

    function getcustomer($icustomer){
        $icustomer    = $this->input->post('icustomer');

        $where = '';
        if($icustomer != 'ALCUS'){
            $where .= "where a.i_customer = '$icustomer'";
        } 
        $this->db->select("a.i_customer, a.e_customer_name 
                            from tr_customer a
                            $where",false);

        $data = $this->db->get();
        return $data;
    }

    public function runningnumber($yearmonth, $ibagian){
        $bl  = substr($yearmonth,4,2);
        $th  = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= "0".trim($ibagian);
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);

        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='KBM'
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
                            where i_modul='KBM'
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
            $nopp  ="KBM-".$area."-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="KBM-".$area."-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('KBM','$area','$asal',1)");
          return $nopp;
        }
    }

    function insertheader($ikasmasuk, $ibagian, $datemasuk, $ikasbank, $icustomer, $ibank, $eremark, $vnilai){
            $dentry = date("Y-m-d");
            $data   = array(
                        'i_kas_masuk'       => $ikasmasuk,
                        'd_kas_masuk'       => $datemasuk,
                        'i_bagian'          => $ibagian,
                        'i_kas_bank'        => $ikasbank,
                        'i_customer'        => $icustomer,
                        'i_bank'            => $ibank,
                        'n_nilai'           => $vnilai,
                        'i_status'          => '1',
                        'e_remark'          => $eremark,
                        'd_entry'           => $dentry
            );
            $this->db->insert('tm_kas_masuk', $data);
    }

    function insertdetail($ikasmasuk, $icustomer, $edesc, $nitemno){ 
        $data = array(
                     'i_kas_masuk'  => $ikasmasuk,
                     'i_customer'   => $icustomer,
                     //'n_nilai'    => $vnilai,
                     'e_remark'     => $edesc,
                     'n_item_no'    => $nitemno,
        );
        $this->db->insert('tm_kas_masuk_detail', $data);
    } 

    public function send($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_kas_masuk', $kode);
    $this->db->update('tm_kas_masuk', $data);
    }

    public function bacacustomer(){
        $this->db->select(" * from tr_customer 
                        group by i_customer",false);
        return $this->db->get()->result();
    }

    public function bacakasbank(){
        $this->db->select(" * from tm_kas_bank 
                        group by i_kode_kas",false);
        return $this->db->get()->result();
    }

    public function bacabank(){
        $this->db->select(" * from tr_bank 
                        group by i_bank",false);
        return $this->db->get()->result();
    }

    public function baca_header($ikasmasuk){
            $this->db->select("a.i_kas_masuk, to_char(a.d_kas_masuk, 'dd-mm-yyyy') as d_kas_masuk, a.i_bagian, a.i_kas_bank, a.i_status, a.e_remark, a.i_bank, a.n_nilai, b.i_customer 
                from tm_kas_masuk a
                join tm_kas_masuk_detail b on a.i_kas_masuk=b.i_kas_masuk
                where a.i_kas_masuk='$ikasmasuk'",false);
        return $this->db->get();
    }

    public function baca_detail($ikasmasuk){
        $this->db->select("(select distinct a.i_kas_masuk from tm_kas_masuk_detail a, tm_kas_masuk b
                        where a.i_kas_masuk='$ikasmasuk') as kasmasuk,
                        x.*
                        from
                            (select a.i_kas_masuk, a.i_customer, c.e_customer_name,  d.n_nilai, a.e_remark 
                            from tm_kas_masuk_detail a 
                            join tr_customer c on a.i_customer=c.i_customer
                            join tm_kas_masuk d on a.i_kas_masuk=d.i_kas_masuk
                            where a.i_kas_masuk='$ikasmasuk') as x",false);
        return $this->db->get();
    }

    function updateheader($ikasmasuk, $ibagian, $datemasuk, $ikasbank, $icustomer, $ibank, $eremark, $vnilai){
        $dupdate = date("Y-m-d");
              $data = array(         
                            'd_kas_masuk'       => $datemasuk,
                            'i_bagian'          => $ibagian,
                            'i_kas_bank'        => $ikasbank,
                            'i_customer'        => $icustomer,
                            'i_bank'            => $ibank,
                            'n_nilai'           => $vnilai,
                            'e_remark'          => $eremark,
                            'd_update'          => $dupdate
              );
        $this->db->where('i_kas_masuk',$ikasmasuk);
        $this->db->update('tm_kas_masuk', $data);
    }

    public function deletedetail($ikasmasuk){
        $this->db->query("DELETE FROM tm_kas_masuk_detail WHERE i_kas_masuk='$ikasmasuk'");
    }

    public function sendd($ikasmasuk){
      $data = array(
          'i_status'    => '2'
    );

    $this->db->where('i_kas_masuk', $ikasmasuk);
    $this->db->update('tm_kas_masuk', $data);
    }

    public function cancel_approve($ikasmasuk){
        $data = array(
                  'i_status'=>'7',
    );
    $this->db->where('i_kas_masuk', $ikasmasuk);
    $this->db->update('tm_kas_masuk', $data);
    }
    
    public function approve($ikasmasuk){
        $data = array(
                'i_status'=>'6',
    );
    $this->db->where('i_kas_masuk', $ikasmasuk);
    $this->db->update('tm_kas_masuk', $data);
    }

    public function change_approve($ikasmasuk){
        $data = array(
                'i_status'=>'3',
    );
    $this->db->where('i_kas_masuk', $ikasmasuk);
    $this->db->update('tm_kas_masuk', $data);
    }

    public function reject_approve($ikasmasuk){
      $data = array(
              'i_status'=>'4',
    );
    $this->db->where('i_kas_masuk', $ikasmasuk);
    $this->db->update('tm_kas_masuk', $data);
    }

    public function cancel($ikasmasuk){
        $data = array(
                  'f_kas_masuk_cancel'  => 't',
                  'i_status'            => '9',
    );
    $this->db->where('i_kas_masuk', $ikasmasuk);
    $this->db->update('tm_kas_masuk', $data);
    }
}
/* End of file Mmaster.php */
