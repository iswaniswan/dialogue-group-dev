<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        $datatables->query("select a.i_bonk, a.d_bonk, a.e_remark, a.f_approve, '$i_menu' as i_menu
                            from tr_master_gudang b, tm_bonkeluarbb a 
                            where a.f_cancel='false' 
                            and a.i_kode_master=b.i_kode_master and a.i_tujuan='CT' 
                            ORDER BY a.i_bonk DESC ");

      $datatables->edit('f_approve', function ($data) {
        $f_approve = trim($data['f_approve']);
        if($f_approve == 't'){
           return  "Approve";
        }else {
          return "Belum Approve";
        }
    });
        
        $datatables->add('action', function ($data) {
            $i_bonk         = trim($data['i_bonk']);
            $i_menu       = $data['i_menu'];
            $f_approve  = $data['f_approve'];
            $data         = '';
            if(check_role($i_menu, 1)){
              //if ($f_approve = "f"){  
              $data .= "<a href=\"#\" onclick='show(\"bonmcutting/cform/approve/$i_bonk/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
              //}
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($i_bonk){
		$this->db->select('a.*, b.e_nama_master');
        $this->db->from('tm_bonkeluarbb a');
        $this->db->join('tr_master_gudang b','a.i_kode_master = b.i_kode_master');
        $this->db->where('a.i_bonk', $i_bonk);
        return $this->db->get();
  }
  function cek_dataheader($i_bonk){
		$this->db->select('a.*, b.e_material_name');
        $this->db->from('tm_bonkeluarbb_detail a');
        $this->db->join('tr_material b','a.i_material = b.i_material');
        $this->db->where('a.i_bonk', $i_bonk);
        return $this->db->get();
  }
  public function cekdatadetail($ipp, $imaterial){
    $this->db->select('*');
        $this->db->from('tm_pp_item');
        $this->db->where('i_pp', $ipp);
        $this->db->where('i_material', $imaterial);
        return $this->db->get();
  }
  function cek_datadet($i_bonk){
		$this->db->select('a.*, b.e_material_name');
        $this->db->from('tm_bonkeluarbb_detail a');
        $this->db->join('tr_material b','a.i_material = b.i_material');
        $this->db->where('a.i_bonk', $i_bonk);
        return $this->db->get();
	}
    public function bacagudang(){
        return $this->db->order_by('e_nama_master','ASC')->get('tr_master_gudang')->result();
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
	public function insertheader($ikodemaster,$ppcancel,$now,$datepp,$ipp,$remark){
        $data = array(
            'i_pp' => $ipp,
            'd_pp' => $datepp,
            'i_kode_master' => $ikodemaster,
            'e_remark' => $remark,
            'd_entry' => $now,
            'e_approve' => 'f'
            
    );
    $this->db->insert('tm_pp', $data);
    }
  public function insertdetail($ipp, $imaterial ,$isatuan ,
  $nquantity ,$vprice ,$fopcomplete,$i){
      $data = array(
          'i_pp' => $ipp,
          'i_material' => $imaterial,
          'i_satuan' => $isatuan,
          'n_quantity' => $nquantity,
          'v_price' => $vprice,
          'n_item_no' => $i,
          'f_op_complete' => $fopcomplete
          
  );
  $this->db->insert('tm_pp_item', $data);
  }
    

    public function updateheader($ipp, $ikodemaster, $remark, $dpp, $now){
        $data = array(
            'i_kode_master' => $ikodemaster,
            'e_remark' => $remark,
            'd_pp' => $dpp,
            'd_update' => $now
    );

    $this->db->where('i_pp', $ipp);
    $this->db->update('tm_pp', $data);
    }
    public function updatedetail($nquantity, $ipp,$imaterial){
      $data = array(
          'n_quantity' => $nquantity,
      );

      $this->db->where('i_pp', $ipp);
      $this->db->where('i_material', $imaterial);
      $this->db->update('tm_pp_item', $data);
    }
    public function approve($ibonk){
      $data = array(
        'f_approve' => 't',
        //'d_approve' => $now,
    );
    $this->db->where('i_bonk', $ibonk);
      $this->db->update('tm_bonkeluarbb', $data);
    }

    public function cancel($i_pp){
        $this->db->set(
            array(
                'f_pp_cancel'  => 't'
            )
        );
        $this->db->where('i_pp',$i_pp);
        return $this->db->update('tm_pp');
    }
}

/* End of file Mmaster.php */
