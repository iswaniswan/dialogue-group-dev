<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_sj, a.d_sj, b.e_jenis_keluar, a.i_penerima, c.e_nama_master, a.f_cancel, $i_menu as i_menu
                            from tm_sjkeluar_gdjadi a 
                            join tr_jenis_keluargdjadi b on a.i_jenis = b.i_jenis
                            join tr_master_gudang c on a.i_tujuan_kirim = c.i_kode_master
                            ", false);
        //where (a.d_sj >= to_date('$from','yyyy-mm-dd') and a.d_sj <= to_date('$to','yyyy-mm-dd'))
        
        $datatables->edit('f_cancel', function ($data) {
            $f_cancel  = trim($data['f_cancel']);
            if($f_cancel == 'f'){
               return  "Akitf";
            }else{
              return "Batal";
            }
        });
       
        $datatables->add('action', function ($data) {
        $isj        = trim($data['i_sj']);
        $f_cancel   = trim($data['f_cancel']);
        $i_menu     = $data['i_menu'];
        $data       = '';
        // var_dump(check_role($i_menu, 3));
        // die;
        if(check_role($i_menu, 3)){            
            $data .= "<a href=\"#\" onclick='show(\"sjkeluargdjadipenjualan/cform/edit/$isj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
        }
        if ($f_cancel!='t') {
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isj\"); return false;'><i class='fa fa-trash'></i></a>";
        }
			return $data;
        });
            
        $datatables->hide('i_menu');
        // $datatables->hide('f_receive');

        return $datatables->generate();
	}

  function getkaryawan(){
        $this->db->select('*');
        $this->db->from('tm_master_karyawan');
    return $this->db->get();
  }

    function runningnumber($thbl){
          $th   = substr($thbl,0,4);
          $asal=$thbl;
          $thbl=substr($thbl,2,2).substr($thbl,4,2);
              $this->db->select(" n_modul_no as max from tm_dgu_no 
                              where i_modul='SJ'
                              and i_area='G8'
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
                              and i_area='G8'
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
                                   values ('SJ','G8','$asal',1)");
                  return $nosj;
            }
    }

    function insertheader($isj, $datesj, $ijenis, $eremark, $ipenerima){
        $dentry = date("d F Y H:i:s");
        $data   = array(
                    'i_sj'               => $isj,
                    'd_sj'               => $datesj,                    
                    'i_jenis'            => $ijenis,                    
                    'e_remark'           => $eremark,
                    'i_penerima'         => $ipenerima,
                    'd_entry'            => $dentry,          
        );
        $this->db->insert('tm_sjkeluar_gdjadi', $data);
    }

    function insertdetail($isj, $iproduct, $icolor, $eproductname, $nquantity, $nitemno){ 
        $data = array(
                    'i_sj'               => $isj,
                    'i_product'          => $iproduct,
                    'e_product_name'     => $eproductname,
                    'i_color'            => $icolor,
                    'n_quantity'         => $nquantity,               
                    'n_item_no'          => $nitemno,
        );
        $this->db->insert('tm_sjkeluar_gdjadi_item', $data);
    }

    function cek_dataheader($isj){
        $this->db->select("a.i_sj, a.d_sj, a.i_jenis, b.e_jenis_keluar, a.e_remark 
                           from tm_sjkeluar_gdjadi a join tr_jenis_keluargdjadi b on b.i_jenis=a.i_jenis 
                            where a.i_sj='$isj'", false);
    return $this->db->get();
    }

    function cek_datadetail($isj){
        $this->db->select("b.i_product, b.e_product_name, d.e_color_name, b.i_color, b.n_quantity  
                          from tm_sjkeluar_gdjadi a 
                          join tm_sjkeluar_gdjadi_item b on a.i_sj=b.i_sj 
                          join tr_color d on b.i_color=d.i_color 
                          where b.i_sj='$isj'                         
                          order by b.i_product",false);
        return $this->db->get();
    }

    function updateheader($isj, $dsj, $ijenis, $eremark){   
        $dupdate  = date("d F Y H:i:s");
        $data  = array(
                'd_sj'              => $dsj,
                'i_jenis'           => $ijenis,
                'e_remark'          => $eremark,   
                'd_update'          => $dupdate
        );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sjkeluar_gdjadi', $data);
    }

    function deletedetail($isj,$iproduct,$icolor) {
        $this->db->query("DELETE FROM tm_sjkeluar_gdjadi_item  WHERE i_sj='$isj' and i_product='$iproduct' and i_color='$icolor' ");
    }
}

/* End of file Mmaster.php */
