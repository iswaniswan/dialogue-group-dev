<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $username, $idcompany, $idepartemen, $ilevel){
		$datatables = new Datatables(new CodeigniterAdapter);

        // $datatables->query("SELECT a.i_kasbank_keluar, a.d_kasbank_keluar, a.i_kasbank, b.e_nama_kas, c.e_supplier_name, a.i_pembayaran, a.v_bayar, a.i_status_dokumen as i_status, d.e_status, a.f_kasbank_keluar_cancel, $i_menu as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement 
        //                     from tm_kasbank_keluar a
        //                     inner join tm_kas_bank b ON b.i_kode_kas = a.i_kasbank
        //                     inner join tr_supplier c ON c.i_supplier = a.partner
        //                     inner join tm_status_dokumen d ON d.i_status = a.i_status_dokumen",false);
        $datatables->query("SELECT a.i_kasbank_keluar_nonap, a.d_kasbank_keluar, a.i_kasbank, b.e_nama_kas, a.i_jenis_keluar, a.i_refferensi,a. d_refferensi, a.v_nilai, a.e_desc, a.i_status, c.e_status
                            , a.f_kasbank_keluar_cancel, $i_menu as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement 
                            from tm_kasbank_keluar_nonap a
                            inner join tm_kas_bank b ON b.i_kode_kas = a.i_kasbank
                            inner join tm_status_dokumen c ON c.i_status = a.i_status",false);

            $datatables->add('action', function ($data) {

            $i_kasbank_keluar        = trim($data['i_kasbank_keluar_nonap']);
            $i_menu                  = $data['i_menu'];
            $f_kasbank_keluar_cancel = trim($data['f_kasbank_keluar_cancel']);
            $i_status                = trim($data['i_status']);
            $i_jenis_keluar          = trim($data['i_jenis_keluar']);
            $data                    = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"kas-keluarnonap/cform/view/$i_kasbank_keluar/$i_jenis_keluar/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)&& $f_kasbank_keluar_cancel == 'f' && $i_status !='6'){                
                $data .= "<a href=\"#\" onclick='show(\"kas-keluarnonap/cform/edit/$i_kasbank_keluar/$i_jenis_keluar/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            
            }
            if(check_role($i_menu, 1)&& $f_kasbank_keluar_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
              $data .= "<a href=\"#\" onclick='show(\"kas-keluarnonap/cform/approve/$i_kasbank_keluar/$i_jenis_keluar/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_kasbank_keluar_cancel == 'f' && $i_status!='6'){
                $data .= "<a href=\"#\" onclick='cancel(\"$i_kasbank_keluar\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
			return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('f_kasbank_keluar_cancel');
        $datatables->hide('i_status');
        $datatables->hide('i_level');
        $datatables->hide('i_departement');
        $datatables->hide('i_kasbank');
        return $datatables->generate();
	}

    function bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany){
        $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";
        return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name
                                  from public.tm_user_deprole a
                                  inner join public.tr_departement b on a.i_departement = b.i_departement
                                  inner join public.tr_level c on a.i_level = c.i_level $where ", FALSE);
    }

    // function refferensi($cari){
    // $this->db->select(" a.i_pembayaran, b.e_supplier_name from tm_permintaan_pembayaranap a
    //                     inner join tr_supplier b on b.i_supplier = a.partner 
    //                     where a.i_pembayaran like '%$cari%' order by a.i_pembayaran ",false);

    //     $data = $this->db->get();
    //     return $data;
    // }

    function getrefferensibayar($ikasbankkeluar,$ijeniskeluar){
        if($ijeniskeluar=='kasbon'){
            $this->db->select(" a.i_kas_bon as i_refferensi from tm_kasbon_karyawan a
                                left join tm_kasbank_keluar_nonap b ON b.i_refferensi=a.i_kas_bon ",false);
        }else{
            $this->db->select(" a.i_kas_masuk as i_refferensi from tm_kas_coasubledger a
                                left join tm_kasbank_keluar_nonap b ON b.i_refferensi=a.i_kas_masuk ",false);
        }
    
            $data = $this->db->get();
            return $data;
    }

    // function getcustomer($icustomer){
    //     $icustomer    = $this->input->post('icustomer');

    //     $where = '';
    //     if($icustomer != 'ALCUS'){
    //         $where .= "where a.i_customer = '$icustomer'";
    //     } 
    //     $this->db->select("a.i_customer, a.e_customer_name 
    //                         from tr_customer a
    //                         $where",false);

    //     $data = $this->db->get();
    //     return $data;
    // }

    public function getjeniskasbon(){
        $this->db->select(" i_refferensi from tm_kasbank_keluar_nonap where f_kasbank_keluar_cancel='f'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $irefferensi=$row->i_refferensi;
          }
        
        $this->db->select('i_kas_bon');
        $this->db->from('tm_kasbon_karyawan');
        $this->db->where_not_in('i_kas_bon', $irefferensi);
        return $this->db->get(); 
        
        }
        // // $names = array('Frank', 'Todd', 'James');
        // $this->db->select('i_kas_bon');
        // $this->db->from('tm_kasbon_karyawan');
        // // $this->db->where_not_in('i_kas_bon', $irefferensi);
        // return $this->db->get(); 
    }

    public function getjeniskaskeluar(){
        return $this->db->query("
        SELECT i_kas_masuk from tm_kas_coasubledger
          order by i_kas_masuk asc", 
      FALSE);   
    }

    // function getheadrefferensikasbon($icustomer){
    //     $irefferensi    = $this->input->post('irefferensi');

    //     $this->db->select(" a.i_pembayaran, a.tanggal, a.partner, a.v_sisa
    //                         from tm_permintaan_pembayaranap a
    //                         where a.i_pembayaran = '$irefferensi'",false);

    //     $data = $this->db->get();
    //     return $data;
    // }

    function getrefferensikasbon($icustomer){
        $irefferensi    = $this->input->post('irefferensi');

        // $this->db->select(" a.i_pembayaran, a.tanggal, b.i_nota, b.d_nota, b.v_total, a.partner, a.v_sisa
        //                     from tm_permintaan_pembayaranap a
        //                     inner join tm_permintaan_pembayaranap_item b on b.i_pembayaran = a.i_pembayaran
        //                     where a.i_pembayaran = '$irefferensi'",false);
        $this->db->select(" a.i_kas_bon as i_refferensi, a.d_kas_bon as d_refferensi
                            , a.v_kas_bon as v_nilai, a.ekeperluan as e_remark from tm_kasbon_karyawan a
                            where a.i_kas_bon = '$irefferensi'",false);

        $data = $this->db->get();
        return $data;
    }

    // function getheadrefferensikaskeluar($icustomer){
    //     $irefferensi    = $this->input->post('irefferensi');

    //     $this->db->select(" a.i_kas_masuk as i_refferensi, a.d_kas_masuk as d_refferensi
    //                         , a.n_nilai as v_nilai, a.e_remark from tm_kas_coasubledger a
    //                         where a.i_kas_masuk = '$irefferensi'",false);

    //     $data = $this->db->get();
    //     return $data;
    // }

    function getrefferensikaskeluar($icustomer){
        $irefferensi    = $this->input->post('irefferensi');

        $this->db->select(" a.i_kas_masuk as i_refferensi, a.d_kas_masuk as d_refferensi
                            , a.n_nilai as v_nilai, a.e_remark from tm_kas_coasubledger a
                            where a.i_kas_masuk = '$irefferensi'",false);

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
                            where i_modul='KNAP'
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
                            where i_modul='KNAP'
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
            $nopp  ="KNAP-".$area."-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="KNAP-".$area."-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('KNAP','$area','$asal',1)");
          return $nopp;
        }
    }

    // function insertheader($ikasbankkeluarnap, $ibagian, $datekeluar, $ipembayaran, $ibank, $partner, $ikasbank, $vbayar, $eremark){
    function insertheader($ikasbankkeluarnap, $ibagian, $datekeluar, $ijeniskeluar, $irefferensi, $ikasbank, $ibank, $eremark, $drefferensi, $vnilai, $edesc){
            $dentry = date("Y-m-d");
            $data   = array(
                'i_kasbank_keluar_nonap'    => $ikasbankkeluarnap,
                'i_bagian'                  => $ibagian,
                'd_kasbank_keluar'          => $datekeluar,
                'i_jenis_keluar'            => $ijeniskeluar,
                'i_refferensi'              => $irefferensi,
                'i_kasbank'                 => $ikasbank,
                'i_bank'                    => $ibank,
                'i_status'                  => '1',
                'e_desc'                    => $edesc,
                'e_remark'                  => $eremark,
                'd_refferensi'              => $drefferensi,
                'v_nilai'                   => $vnilai,
                'd_entry'                   => $dentry
            );
            $this->db->insert('tm_kasbank_keluar_nonap', $data);
    }

    // function insertdetail($ikasbankkeluar, $partner, $inota, $dnota, $vtotal, $eremark, $nitemno){ 
    //     $dentry = date("Y-m-d");
    //     $data = array(
    //                     'i_kasbank_keluar'  => $ikasbankkeluar,
    //                     'partner'           => $partner,
    //                     'i_nota'            => $inota,
    //                     'd_nota'            => $dnota,
    //                     'v_total'           => $vtotal,
    //                     'e_remark'          => $eremark,
    //                     'i_no_item'         => $nitemno,
    //                     'd_entry'           => $dentry,
    //                     'jenis'             => ''
    //     );
    //     $this->db->insert('tm_kasbank_keluar_item', $data);
    // } 

    // function updatesisa($ipembayaran, $vsisabaru){
    //     $dentry = date("Y-m-d");

    //     $this->db->set('v_sisa', $vsisabaru);
    //     $this->db->where('i_pembayaran', $ipembayaran);
    //     $this->db->update('tm_permintaan_pembayaranap');
    // }

    public function send($kode){
        $data = array(
                      'i_status_dokumen'    => '2'
        );

    $this->db->where('i_kas_masuk', $kode);
    $this->db->update('tm_kas_masuk', $data);
    }

    // public function bacacustomer(){
    //     $this->db->select(" * from tr_customer 
    //                     group by i_customer",false);
    //     return $this->db->get()->result();
    // }

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

    public function baca_header($ikasbankkeluarnonap){
        
        // $this->db->select(" a.i_kasbank_keluar, a.d_kasbank_keluar, a.i_pembayaran, a.i_kasbank, (b.v_sisa + a.v_bayar) as v_sisa 
	    //                     , a.v_bayar, a.i_bagian, a.i_status_dokumen as i_status, a.e_remark, b.partner,  a.i_bank
	    //     from tm_kasbank_keluar a
        //     inner join tm_permintaan_pembayaranap b on b.i_pembayaran = a.i_pembayaran and a.partner = b.partner 
        //     where a.i_kasbank_keluar='$ikasbankkeluar'",false);
        $this->db->select(" i_kasbank_keluar_nonap, to_char( d_kasbank_keluar, 'DD-MM-YYYY') as d_kasbank_keluar, i_jenis_keluar,i_refferensi, d_refferensi, v_nilai, e_desc 
                            , i_status, e_remark, i_bank, i_kasbank
                            from tm_kasbank_keluar_nonap
                            where i_kasbank_keluar_nonap='$ikasbankkeluarnonap'",false);
        return $this->db->get();
    }

    public function baca_detail($ikasbankkeluarnonap){
        // $this->db->select(" a.i_kasbank_keluar, a.partner, a.i_nota, a.d_nota, a.v_total, a.e_remark 
        //     from tm_kasbank_keluar_item a
        //     where a.i_kasbank_keluar='$ikasbankkeluar'",false);
        $this->db->select(" i_kasbank_keluar_nonap, to_char( d_kasbank_keluar, 'DD-MM-YYYY') asd_kasbank_keluar, i_jenis_keluar,i_refferensi, d_refferensi, v_nilai, e_desc 
                            , i_status, e_remark, i_bank, i_kasbank
                            from tm_kasbank_keluar_nonap
                            where i_kasbank_keluar_nonap='$ikasbankkeluarnonap'",false);
        return $this->db->get();
    }

    //function updateheader($ikasbankkeluar, $ibagian, $dkasbankkeluar, $ikasbank, $ibank, $irefferensi, $vsisa, $vbayar, $eremark){
    function updateheader($ikasbankkeluar,$ibagian, $datekeluar, $ijeniskeluar, $irefferensi, $ikasbank, $ibank, $edesc, $eremark, $drefferensi, $vnilai){
        $dupdate = date("Y-m-d");
              $data = array(         
                            'i_bagian'          => $ibagian,
                            'd_kasbank_keluar'  => $datekeluar,
                            'i_jenis_keluar'    => $ijeniskeluar,
                            'i_refferensi'      => $irefferensi,
                            'i_kasbank'         => $ikasbank,
                            'i_bank'            => $ibank,
                            'e_desc'            => $edesc,
                            'e_remark'          => $eremark,
                            'd_refferensi'      => $drefferensi,
                            'v_nilai'           => $vnilai,
                            'd_update'          => $dupdate
              );
        $this->db->where('i_kasbank_keluar_nonap',$ikasbankkeluar);
        $this->db->update('tm_kasbank_keluar_nonap', $data);
    }

    // public function deletedetail($ikasbankkeluar){
    //     $this->db->query("DELETE FROM tm_kasbank_keluar_item WHERE i_kasbank_keluar='$ikasbankkeluar'");
    // }

    public function sendd($ikasbankkeluar){
      $data = array(
          'i_status'    => '2'
    );

    $this->db->where('i_kasbank_keluar_nonap', $ikasbankkeluar);
    $this->db->update('tm_kasbank_keluar_nonap', $data);
    }

    public function cancel_approve($ikasbankkeluar){
        $data = array(
                  'i_status'=>'7',
    );
    $this->db->where('i_kasbank_keluar_nonap', $ikasbankkeluar);
    $this->db->update('tm_kasbank_keluar_nonap', $data);
    }
    
    public function approve($ikasbankkeluar){
        $data = array(
                'i_status'=>'6',
    );
    $this->db->where('i_kasbank_keluar_nonap', $ikasbankkeluar);
    $this->db->update('tm_kasbank_keluar_nonap', $data);
    }

    public function change_approve($ikasbankkeluar){
        $data = array(
                'i_status'=>'3',
    );
    $this->db->where('i_kasbank_keluar_nonap', $ikasbankkeluar);
    $this->db->update('tm_kasbank_keluar_nonap', $data);
    }

    public function reject_approve($ikasbankkeluar){
      $data = array(
              'i_status'=>'4',
    );
    $this->db->where('i_kasbank_keluar_nonap', $ikasbankkeluar);
    $this->db->update('tm_kasbank_keluar_nonap', $data);
    }

    public function cancel($ikasbankkeluar){
        $data = array(
                  'f_kasbank_keluar_cancel'  => 't',
                  'i_status'            => '9',
    );
    $this->db->where('i_kasbank_keluar_nonap', $ikasbankkeluar);
    $this->db->update('tm_kasbank_keluar_nonap', $data);
    }

    public function cancelpermintaanpembayaran($ikasbankkeluar){

        $this->db->query("UPDATE tm_permintaan_pembayaranap 
                        set v_sisa = (SELECT (a.v_bayar + b.v_sisa) as v_sisaawal from tm_kasbank_keluar a
                                    inner join tm_permintaan_pembayaranap b on b.i_pembayaran = a.i_pembayaran 
                                    where a.i_kasbank_keluar = '$ikasbankkeluar' and a.f_kasbank_keluar_cancel = 'f')
                        where i_pembayaran = (SELECT b.i_pembayaran from tm_kasbank_keluar a
                                    inner join tm_permintaan_pembayaranap b on b.i_pembayaran = a.i_pembayaran 
                                    where a.i_kasbank_keluar = '$ikasbankkeluar' and a.f_kasbank_keluar_cancel = 'f')
                        
                        ");
        
    }

}
/* End of file Mmaster.php */
