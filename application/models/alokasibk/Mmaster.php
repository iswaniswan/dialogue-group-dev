<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $from, $to, $ibank){
		$datatables = new Datatables(new CodeigniterAdapter);
		
      $datatables->query("select a.i_kbank, d.i_pv, a.d_bank, b.e_area_name, a.v_bank, a.v_sisa, c. i_bank, d.e_remark, '$i_menu' as i_menu
      FROM tm_kbank a, tr_area b, tr_bank c, tm_pv_item d 
      WHERE a.i_area = b.i_area AND a.i_kbank LIKE '%BK-%' AND a.d_bank >= to_date('$from','dd-mm-yyyy') AND a.d_bank <= to_date('$to','dd-mm-yyyy') 
      AND a.i_coa LIKE '%210-1%' AND a.i_coa_bank = c.i_coa AND c.i_bank = '$ibank' AND a.f_kbank_cancel = 'f' AND a.v_sisa > 0 AND d.i_kk = a.i_kbank ORDER BY a.i_kbank ");

        $datatables->add('action', function ($data) {
            $ikbank = trim($data['i_kbank']);
            $ibank = trim($data['i_bank']);
            $i_menu = $data['i_menu'];
            $data = '';
            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"alokasibk/cform/view/$ikbank/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"alokasibk/cform/edit/$ikbank/$ibank/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_bank');
        return $datatables->generate();
	}

  public function gettrbank($ibank){
    $this->db->SELECT("*");
    $this->db->FROM("tr_bank");
    $this->db->WHERE("i_bank", $ibank);
    return $this->db->get();
  }

  public function gettmbank($ikbank){
    $this->db->SELECT("*");
    $this->db->FROM("tm_kbank");
    $this->db->WHERE("i_kbank", $ikbank);
    return $this->db->get();
  }

  public function cek_sup($isupplier){
      $this->db->select('*');
          $this->db->from('tr_supplier');
          $this->db->where('i_supplier',$isupplier);
      return $this->db->get();
  }



	public function runningnumberpl($iarea, $thbl){
    $th   = substr($thbl,0,4);
    $asal = $thbl;
    $thbl = substr($thbl,2,2).substr($thbl,4,2);
    $this->db->select(" n_modul_no as max FROM tm_dgu_no
      WHERE i_modul='BAO'
      AND substr(e_periode,1,4)='$th'
      AND i_area='$iarea' for update", false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
      }
      $noal  =$terakhir+1;
      $this->db->query(" UPDATE tm_dgu_no SET n_modul_no= $noal WHERE i_modul='BAO' AND substr(e_periode,1,4)='$th' AND i_area='$iarea'", false);
      settype($noal,"string");
      $a=strlen($noal);
      while($a<5){
        $noal="0".$noal;
        $a=strlen($noal);
      }
      $noal  ="AO-".$thbl."-".$noal;
      return $noal;
    }else{
      $noal  ="00001";
      $noal  ="AO-".$thbl."-".$noal;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
        values ('BAO','$iarea','$asal',1)");
      return $noal;
    }
  }
  
  public function inserttransheader($ireff, $iarea, $egirodescription, $fclose, $dalokasii){
    $dentry = date("d F Y H:i:s");

    $data=array(
        'i_refference'  => $ireff, 
        'i_area'        => $iarea,
        'd_entry'       => $dentry,
        'e_description' => $egirodescription,
        'f_close'       => $fclose,
        'd_refference'  => $dalokasii,
        'd_mutasi'      => $dalokasii
    );
    $this->db->insert('tm_jurnal_transharian',$data);
  } 

  public function namaacc($icoa){
    $this->db->select("e_coa_name");
    $this->db->from("tr_coa"); 
    $this->db->where("i_coa", $icoa);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $tmp){
        $xxx=$tmp->e_coa_name;
      }
      return $xxx;
    }
    //return $this->db->get();
  }

  public function insertdetail($ialokasi,$ikbank,$isupplier,$inota,$ddtap,$vjumlah,$vsisa,$inoitem,$eremark,$icoabank){

    $this->db->select("i_alokasi");
    $this->db->from("tm_alokasi_bk_item");
    $this->db->where("i_alokasi", $ialokasi);
    $this->db->where("i_supplier", $isupplier);
    $this->db->where("i_nota", $inota);
    $this->db->where("i_kbank", $ikbank);
    $this->db->where("i_coa_bank", $icoabank);
    $query = $this->db->get();

    if($query->num_rows()>0){
      $data = array(
        'd_nota'    => $ddtap,
        'v_jumlah'  => $vjumlah,
        'v_sisa'    => $vsisa,
        'e_remark'  => $eremark 
      );
      $this->db->where("i_alokasi", $ialokasi);
      $this->db->where("i_supplier", $isupplier);
      $this->db->where("i_nota", $inota);
      $this->db->where("i_kbank", $ikbank);
      $this->db->where("i_coa_bank", $icoabank);
      $this->db->update("tm_alokasi_bk_item", $data);
    }else{
      $data = array(
            'i_alokasi'  => $ialokasi, 
            'i_kbank'    => $ikbank,
            'i_supplier' => $isupplier,
            'i_nota'     => $inota,
            'd_nota'     => $ddtap,
            'v_jumlah'   => $vjumlah,
            'v_sisa'     => $vsisa,
            'n_item_no'  => $inoitem,
            'e_remark'   => $eremark,
            'i_coa_bank' => $icoabank
        
      );
      $this->db->insert('tm_alokasi_bk_item', $data);
    }
  }

  public function inserttransitemkredit($acckredit,$ireff,$namakredit,$iarea,$egirodescription,$vjumlah,$dalokasi,$icoabank){
    $dentry = date("d F Y H:i:s");
    $fdebet='f';
    $fposting='t';
    $this->db->set(
      array(
        'i_coa'             => $acckredit, 
        'i_refference'      => $ireff,
        'e_coa_description' => $namakredit,
        'f_debet'           => $fdebet,
        'f_posting'         => $fposting,
        'i_area'            => $iarea,
        'v_mutasi_kredit'   => $vjumlah,
        'd_refference'      => $dalokasi,
        'd_mutasi'          => $dalokasi,
        'd_entry'           => $dentry,
        'i_coa_bank'        => $icoabank
      )
    );
    $this->db->insert('tm_jurnal_transharianitem');
  }

  public function inserttranskredit($ikbank,$iarea,$dalokasi,$icoabank){
    $dentry = date("d F Y H:i:s");
    $this->db->set(
      array(
        'i_refference'  => $ikbank,
        'i_area'        => $iarea,
        'd_entry'       => $dentry,
        'd_refference'  => $dalokasi,
        'i_coa_bank'    => $icoabank
      )
    );
    $this->db->insert('tm_jurnal_transharian');
  }

  public function inserttransitemdebet($accdebet,$ireff,$namadebet,$iarea,$egirodescription,$vjumlah,$dalokasi,$icoabank){
    $dentry = date("d F Y H:i:s");
    $fdebet='t';
    $fposting='t';
    $this->db->set(
      array(
        'i_coa'             => $accdebet, 
        'i_refference'      => $ireff,
        'e_coa_description' => $namadebet,
        'f_debet'           => $fdebet,
        'f_posting'         => $fposting,
        'v_mutasi_kredit'   => $vjumlah,
        'd_refference'      => $dalokasi,
        'd_mutasi'          => $dalokasi,
        'd_entry'           => $dentry,
        'i_coa_bank'        => $icoabank
      )
    );
    $this->db->insert('tm_jurnal_transharianitem');
  }

  public function updatenota($inota,$isupplier,$vsisa){
    $this->db->query("UPDATE tm_notabtb SET v_sisa = v_sisa-$vsisa WHERE i_nota = '$inota' AND i_supplier = '$isupplier'");
    /*$data = array(
      'sisa'  => "sisa-$vsisa"
    );
    $this->db->where("id", $idtap);
    $this->db->where("id_supplier", $isupplier);
    $this->db->update("tm_pembelian_nofaktur", $data);*/

    $this->db->select("v_sisa");
    $this->db->from("tm_notabtb");
    $this->db->where("i_nota", $inota);
    $this->db->where("i_supplier", $isupplier);
    $this->db->where("v_sisa <= 0");
    $query = $this->db->get();
    if ($query->num_rows()>0) {
      $status = array(
        'f_status_lunas' => 't'
      );
      $this->db->where("i_nota", $inota);
      $this->db->where("i_supplier", $isupplier);
      $this->db->update("tm_notabtb", $status);
    }
  }

  public function insertgldebet($accdebet,$ireff,$namadebet,$vjumlah,$dalokasi,$iarea,$egirodescription,$icoabank){
    $dentry = date("d F Y H:i:s");
    $fdebet='f';
    $this->db->set(
      array(
        'i_refference'  => $ireff,
        'i_coa'         => $accdebet,
        'd_mutasi'      => $dalokasi,
        'e_coa_name'    => $namadebet,
        'f_debet'       => $fdebet,
        'v_mutasi_debet'=> $vjumlah,
        'i_area'        => $iarea,
        'd_refference'  => $dalokasi,
        'e_description' => $egirodescription,
        'd_entry'       => $dentry,
        'i_coa_bank'    => $icoabank
      )
    );
    $this->db->insert('tm_general_ledger');
  }

  public function insertglkredit($acckredit,$ireff,$namakredit,$vjumlah,$dalokasi,$iarea,$egirodescription,$icoabank){
    $dentry = date("d F Y H:i:s");
    $fdebet='t';
    $this->db->set(
      array(
        'i_refference'   => $ireff,
        'i_coa'          => $acckredit,
        'd_mutasi'       => $dalokasi,
        'e_coa_name'     => $namakredit,
        'f_debet'        => $fdebet,
        'v_mutasi_debet' => $vjumlah,
        'i_area'         => $iarea,
        'd_refference'   => $dalokasi,
        'e_description'  => $egirodescription,
        'd_entry'        => $dentry,
        'i_coa_bank'     => $icoabank
      )
    );
    $this->db->insert('tm_general_ledger');
  }

  public function insertheader($ialokasi,$ikbank,$isupplier,$dalokasi,$ebankname,$vjumlahx,$vlebihx,$icoabank){
    
    $dentry = date("d F Y H:i:s");
    $this->db->set(
      array(
        'i_alokasi'   => $ialokasi,
        'i_kbank'     => $ikbank,
        'i_supplier'  => $isupplier,
        'd_alokasi'   => $dalokasi,
        'e_bank_name' => $ebankname,
        'v_jumlah'    => $vjumlahx,
        'v_lebih'     => $vlebihx,
        'd_entry'     => $dentry,
        'i_coa_bank'  => $icoabank
      )
    );
    $this->db->insert('tm_alokasi_bk');
  }

  public function updatebank($ikbank,$icoabank,$isupplier,$pengurang){
    $this->db->query("UPDATE tm_kbank SET v_sisa = v_sisa - $pengurang WHERE i_kbank = '$ikbank' AND i_coa_bank = '$icoabank'");
  }
}

/* End of file Mmaster.php */
