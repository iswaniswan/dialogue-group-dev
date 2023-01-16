<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
	function data($i_menu, $dfrom, $dto){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
      $datatables->query("SELECT a.i_kb, d.i_pv, a.d_kb, b.e_area_name, a.v_kb, a.v_sisa, d.e_remark, '$i_menu' as i_menu 
                          FROM tm_kb a 
                          inner join tr_area b on (a.i_area = b.i_area )
                          inner join tm_pv_item d on (d.i_kk = a.i_kb )
                          WHERE a.i_kb LIKE '%KB-%' 
                          AND a.d_kb >= to_date('$dfrom','dd-mm-yyyy') 
                          AND a.d_kb <= to_date('$dto','dd-mm-yyyy') 
                          AND a.i_coa LIKE '%210-1%' AND a.f_kb_cancel = 'f' 
                          AND a.v_sisa > 0 AND d.i_kk = a.i_kb 
                          ORDER BY a.i_kb");
    
        
        $datatables->add('action', function ($data) {
            $i_kb = trim($data['i_kb']);
            $i_menu = $data['i_menu'];
            $data = '';
            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"alokasikasbesar/cform/view/$i_kb/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"alokasikasbesar/cform/edit/$i_kb/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}
	function cek_data($i_kb){
    $this->db->select('*');
    $this->db->from('tm_kb');
    $this->db->where('i_kb', $i_kb);
    return $this->db->get();
  }
  
  public function cek_dataheader($id){
		$this->db->select('a.*, b.e_nama_master');
        $this->db->from('tm_pp a');
        $this->db->join('tr_master_gudang b','a.i_kode_master = b.i_kode_master');
        $this->db->where('a.i_pp', $id);
        return $this->db->get();
  }
  public function cekdatadetail($ipp, $imaterial){
    $this->db->select('*');
        $this->db->from('tm_pp_item');
        $this->db->where('i_pp', $ipp);
        $this->db->where('i_material', $imaterial);
        return $this->db->get();
  }
  function cek_datadet($id){
		$this->db->select('a.*, b.e_material_name, c.e_satuan');
        $this->db->from('tm_pp_item a');
        $this->db->join('tr_material b','a.i_material = b.i_material');
        $this->db->join('tr_satuan c','a.i_satuan = c.i_satuan');
        $this->db->where('a.i_pp', $id);
        return $this->db->get();
	}
    public function bacasupplier(){
        return $this->db->order_by('e_supplier_name','ASC')->get('tr_supplier')->result();
    }
    function runningnumber($yearmonth){
        $th = substr($yearmonth,0,4);
        $asal=$yearmonth;
        $yearmonth=substr($yearmonth,2,2).substr($yearmonth,4,2);
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='PP'
                            and i_area='00'
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
                            where i_modul='PP'
                            and e_periode='$asal' 
                            and i_area='00'
                            and substring(e_periode,1,4)='$th'", false);
          settype($nopp,"string");
          $a=strlen($nopp);
          while($a<7){
            $nopp="0".$nopp;
            $a=strlen($nopp);
          }
            $nopp  ="PP-".$yearmonth."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="0000001";
          $nopp  ="PP-".$yearmonth."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('PP','00','$asal',1)");
          return $nopp;
        }
      }

    public function runningnumberpl($iarea,$thbl){
    $th   = substr($thbl,0,4);
    $asal=$thbl;  
    $thbl=substr($thbl,2,2).substr($thbl,4,2);
    $this->db->select(" n_modul_no as max FROM tm_dgu_no
      WHERE i_modul='AK'
      AND substr(e_periode,1,4)='$th'
      AND i_area='$iarea' for update", false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
      }
      $noal  =$terakhir+1;
      $this->db->query(" UPDATE tm_dgu_no SET n_modul_no = $noal WHERE i_modul='AK' AND substr(e_periode,1,4)='$th' AND i_area = '$iarea'", FALSE);
      settype($noal,"string");
      $a=strlen($noal);
      while($a<5){
        $noal="0".$noal;
        $a=strlen($noal);
      }
      $noal  ="AK-".$thbl."-".$noal;
      return $noal;
    }else{
      $noal  ="00001";
      $noal  ="AK-".$thbl."-".$noal;
      $this->db->query(" INSERT INTO tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) VALUES ('AK','$iarea','$asal',1)");
      return $noal;
    }
    }

    public function inserttransheader($ireff, $iarea, $egirodescription, $fclose, $dkb){
    $dentry = date("d F Y H:i:s");
    $this->db->set(
      array(
        'i_refference'  => $ireff, 
        'i_area'        => $iarea,
        'd_entry'       => $dentry,
        'e_description' => $egirodescription,
        'f_close'       => $fclose,
        'd_refference'  => $dkb,
        'd_mutasi'      => $dkb
      )
    );
    $this->db->insert('tm_jurnal_transharian');
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
  }

  public function insertdetail($ialokasi,$ikb,$isupplier,$inota,$ddtap,$vjumlah,$vsisa,$inoitem,$eremark){
    $this->db->select("i_alokasi");
    $this->db->from("tm_alokasi_kb_item");
    $this->db->where("i_alokasi", $ialokasi);
    $this->db->where("i_supplier", $isupplier);
    $this->db->where("i_nota", $inota);
    $this->db->where("i_kb", $ikb);
    $query = $this->db->get();
    if($query->num_rows()>0){
      $data = array(
        'd_nota'  => $ddtap,
        'v_jumlah'  => $vjumlah,
        'v_sisa'  => $vsisa,
        'e_remark'  => $eremark 
      );
      $this->db->where("i_alokasi", $ialokasi);
      $this->db->where("i_supplier", $isupplier);
      $this->db->where("i_nota", $inota);
      $this->db->where("i_kb", $ikb);
      $this->db->update("tm_alokasi_kb_item", $data);
    }else{
      $this->db->set(
        array(
          'i_alokasi'  => $ialokasi, 
          'i_kb'       => $ikb,
          'i_supplier' => $isupplier,
          'i_nota'   => $inota,
          'd_nota'   => $ddtap,
          'v_jumlah'   => $vjumlah,
          'v_sisa'   => $vsisa,
          'e_remark'   => $eremark
        )
      );
      $this->db->insert('tm_alokasi_kb_item');
    }
  }

  public function inserttransitemkredit($acckredit,$ireff,$namakredit,$iarea,$egirodescription,$vjumlah,$dkb){
    $query  = $this->db->query("SELECT current_timestamp as c");
    $row    = $query->row();
    $dentry = $row->c;
    $fdebet='f';
    $fposting='t';
    $this->db->set(
      array(
        'i_coa'       => $acckredit, 
        'i_refference'      => $ireff,
        'e_coa_description' => $namakredit,
        'f_debet'       => $fdebet,
        'f_posting'     => $fposting,
        'v_mutasi_kredit' => $vjumlah,
        'd_refference'    => $dkb,
        'd_mutasi'      => $dkb,
        'd_entry'     => $dentry
      )
    );
    $this->db->insert('tm_jurnal_transharianitem');
  }

  public function inserttranskredit($ikb,$iarea,$dkb){
    $query  = $this->db->query("SELECT current_timestamp as c");
    $row    = $query->row();
    $dentry = $row->c;
    $this->db->set(
      array(
        'i_refference'  => $ikb,
        'i_area'    => $iarea,
        'd_entry'     => $dentry,
        'd_refference'  => $dkb
      )
    );
    $this->db->insert('tm_jurnal_transharian');
  }
  public function updatenota($inota,$isupplier,$vsisa){
		$this->db->query("UPDATE tm_notabtb SET v_sisa = v_sisa-$vsisa 
		WHERE i_nota = '$inota' AND id_supplier = '$isupplier'");
		/*$data = array(
			'sisa' 	=> "sisa-$vsisa"
		);
		$this->db->where("id", $idtap);
		$this->db->where("id_supplier", $isupplier);
		$this->db->update("tm_pembelian_nofaktur", $data);*/

		$this->db->select("v_sisa");
		$this->db->from("tm_notabtb");
		$this->db->where("i_nota", $inota);
		$this->db->where("id_supplier", $isupplier);
		$this->db->where("sisa <= 0");
		$query = $this->db->get();
		if ($query->num_rows()>0) {
			$status = array(
				'status_lunas' => 't'
			);
			$this->db->where("i_nota", $inota);
			$this->db->where("isupplier", $isupplier);
			$this->db->update("tm_notabtb", $status);
		}
  }
  public function insertgldebet($acckredit,$ireff,$namadebet,$vjumlah,$dalokasi,$iarea,$egirodescription){
    $fdebet = 'f';
    $query  = $this->db->query("SELECT current_timestamp as c");
		$row    = $query->row();
		$dentry = $row->c;
		$this->db->set(
			array(
				'i_refference'   => $ireff,
				'i_coa' 		 => $acckredit,
				'd_mutasi' 		 => $dalokasi,
				'e_coa_name'	 => $namadebet,
				'f_debet'		 => $fdebet,
				'v_mutasi_debet' => $vjumlah,
				'i_area'		 => $iarea,
				'd_refference'	 => $dalokasi,
				'e_description'	 => $egirodescription,
				'd_entry' 		 => $dentry
			)
		);
		$this->db->insert('tm_general_ledger');
  }
  public function insertglkredit($acckredit,$ireff,$namakredit,$vjumlah,$dalokasi,$iarea,$egirodescription, $dentry){
    $fdebet = 't';
		$this->db->set(
			array(
				'i_refference'   => $ireff,
				'i_coa' 		 => $acckredit,
				'd_mutasi' 		 => $dalokasi,
				'e_coa_name'	 => $namakredit,
				'f_debet'		 => $fdebet,
				'v_mutasi_debet' => $vjumlah,
				'i_area'		 => $iarea,
				'd_refference'	 => $dalokasi,
				'e_description'	 => $egirodescription,
				'd_entry' 		 => $dentry
			)
		);
		$this->db->insert('tm_general_ledger');
  }
  public function insertheader($ialokasi,$ikb,$isupplier,$dalokasi,$vjumlah,$vlebih){
		$query  = $this->db->query("SELECT current_timestamp as c");
		$row    = $query->row();
		$dentry = $row->c;
		$this->db->set(
			array(
				'i_alokasi'   => $ialokasi,
				'i_kb' 	  	  => $ikb,
				'i_supplier'  => $isupplier,
				'd_alokasi'	  => $dalokasi,
				'v_jumlah'    => $vjumlah,
				'v_lebih'	  => $vlebih,
				'd_entry'	  => $dentry
			)
		);
		$this->db->insert('tm_alokasi_kb');
	}

  public function inserttransitemdebet($accdebet,$ireff,$namadebet,$iarea,$egirodescription,$vjumlah,$dkb){
    $query  = $this->db->query("SELECT current_timestamp as c");
    $row    = $query->row();
    $dentry = $row->c;
    $fdebet ='t';
    $fposting='t';
    $this->db->set(
      array(
        'i_coa'       => $accdebet, 
        'i_refference'      => $ireff,
        'e_coa_description' => $namadebet,
        'f_debet'       => $fdebet,
        'f_posting'     => $fposting,
        'v_mutasi_kredit' => $vjumlah,
        'd_refference'    => $dkb,
        'd_mutasi'      => $dkb,
        'd_entry'     => $dentry,
      )
    );
    $this->db->insert('ttm_jurnal_transharianitem');
  }
}

/* End of file Mmaster.php */
