<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select i_bonk, d_bonk, e_remark, f_approve_realisasi, f_bonk_cancel, '$i_menu' as i_menu
                            from tm_bonmkeluar_qcset where f_bonk_cancel = 'f' and  (d_bonk >= to_date('$dfrom','yyyy-mm-dd')
                            and d_bonk <= to_date('$dto','yyyy-mm-dd')) order by i_bonk",false);
        
        
        $datatables->edit('f_approve_realisasi', function ($data) {
            $f_approve_realisasi = trim($data['f_approve_realisasi']);
            if($f_approve_realisasi == 't'){
               return  "Belum Diterima";
            }else {
              return "Diterima";
            }
        });
        
            $datatables->add('action', function ($data) {
            $i_bonk    = trim($data['i_bonk']);
            $i_menu     = $data['i_menu'];
            $f_bonk_cancel    = trim($data['f_bonk_cancel']);
            $data       = '';
            if(check_role($i_menu, 3)){
                
                $data .= "<a href=\"#\" onclick='show(\"bonkkeluarqc/cform/edit/$i_bonk/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if ($f_bonk_cancel!='t') {
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_bonk\"); return false;'><i class='fa fa-trash'></i></a>";
          }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('f_bonk_cancel');

        return $datatables->generate();
	}

    	function cek_data($i_bonk){
    		$this->db->select('a.i_bonk , a.d_bonk , a.e_remark , a.i_gudang , b.e_nama_master');
            $this->db->from('tm_bonmkeluar_qcset a');
            $this->db->join('tr_master_gudang b','a.i_gudang = b.i_kode_master');
            $this->db->where('a.i_bonk', $i_bonk);
            return $this->db->get();
            // select a.i_bonk , a.d_bonk , a.e_remark , a.i_gudang , b.e_nama_master
            // from tm_bonmkeluar_qcset a  
            // inner join tr_master_gudang b on a.i_gudang=b.i_kode_master
            // where a.i_bonk='BON-1909-000001'
      }
        function cek_datadet($i_bonk){
            // $this->db->select('a.i_bonk ,a.i_product ,a.e_product_name ,a.i_color ,b.e_color_name , a.n_quantity , a.e_remark, a.f_item_cancel');
            // $this->db->from('tm_bonmkeluar_qcset_item a');
            // $this->db->join('tr_color b','a.i_color=b.i_color');
            // $this->db->join('tr_satuan c','a.i_satuan = c.i_satuan');
            // $this->db->where('a.i_pp', $id);
            
            $this->db->select("a.i_bonk ,a.i_product ,a.e_product_name ,a.i_color ,b.e_color_name , a.n_quantity , a.e_remark, 
                            a.f_item_cancel
                            from tm_bonmkeluar_qcset_item a 
                            inner join tr_color b on a.i_color=b.i_color
                            where a.i_bonk='$i_bonk' and f_item_cancel = 'f'
                            order by a.i_product, a.i_color, a.n_item_no",false);
            return $this->db->get();
        }
        function cek_datadetheader($i_bonk){
            $this->db->select("a.i_bonk, a.d_bonk, b.i_product, b.e_product_name, b.i_color ,c.e_color_name, b.e_remark
                            ,d.e_nama_master, a.i_gudang
                            from tm_bonmkeluar_qcset a 
                            inner join tm_bonmkeluar_qcset_item b on a.i_bonk=b.i_bonk
                            inner join tr_color c on b.i_color=c.i_color
                            inner join tr_master_gudang d on a.i_gudang=d.i_kode_master
                            where a.i_bonk='$i_bonk'
                            order by b.i_product, b.i_color, b.n_item_no",false);
            return $this->db->get();
        }

        function cek_datdetail($i_bonk, $i_product, $i_color){
            $this->db->select("a.i_material, b.e_material_name, a.n_quantity from tm_bonmkeluar_qcset_itemdetail a, tr_material b 
                            where a.i_bonk ='$i_bonk' and a.i_product='$i_product' and a.i_color='$i_color' and a.i_material=b.i_material
                            group by a.i_material , b.e_material_name , a.n_quantity",false);
            return $this->db->get();
        }

    public function bacagudang(){
        return $this->db->order_by('e_nama_master','ASC')->get('tr_master_gudang')->result();
    }
    function runningnumber($thbl){
        $th	= substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
            $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='BON'
                            and i_area='G12'
                            and e_periode='$asal' 
                            and substring(e_periode,1,4)='$th' for update", false);
            $query = $this->db->get();
            if ($query->num_rows() > 0){
                foreach($query->result() as $row){
                  $terakhir=$row->max;
                }
                $nobonmk  =$terakhir+1;
                $this->db->query(" update tm_dgu_no 
                                  set n_modul_no=$nobonmk
                                  where i_modul='BON'
                                  and e_periode='$asal' 
                                  and i_area='G12'
                                  and substring(e_periode,1,4)='$th'", false);
                settype($nobonmk,"string");
                $a=strlen($nobonmk);
                while($a<6){
                  $nobonmk="0".$nobonmk;
                  $a=strlen($nobonmk);
                }
                    $nobonmk  ="BON-".$thbl."-".$nobonmk;
                return $nobonmk;
            }else{
                $nobonmk  ="000001";
                $nobonmk  ="BON-".$thbl."-".$nobonmk;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('BON','G12','$asal',1)");
                return $nobonmk;
            }
      }
      function insertheader($ibon, $dbon, $eremark , $igudang)
    {	
		$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_bonk'		=> $ibon,
				'd_bonk'		=> $dbon,
				'i_gudang'		=> $igudang,
				'e_remark'	    => $eremark,
				'd_entry'		=> $dentry
    		)
    	);
    	
    	$this->db->insert('tm_bonmkeluar_qcset');
    }
    function insertdetail($ibon,$iproduct,$icolor,$eproductname,$nquantity,$eremark,$nitemno){			
    	$this->db->set(
    		array(
					'i_product'	 	        => $iproduct,
					'e_product_name'	    => $eproductname,
					'i_color' 			    => $icolor,
					'n_quantity'		    => $nquantity,
					'e_remark'		        => $eremark,
					'i_bonk'				=> $ibon,
					'n_item_no'             => $nitemno,
    		)
    	);
    	$this->db->insert('tm_bonmkeluar_qcset_item');
    }
    function insertdetailproduct($ibon,$iproduct,$icolor,$imaterial,$ematerialname,$nquantity,$nitemno,$nodetail)
    {	
        $this->db->set(
    		array(
					'i_bonk'	   	    	=> $ibon,
					'i_product'	 	        => $iproduct,
					'i_color' 			    => $icolor,
					'i_material'		    => $imaterial,
					'n_quantity'			=> $nquantity,
					'n_item_no'				=> $nitemno,
					'n_no'					=> $nodetail
    		)
    	);
    	
    	$this->db->insert('tm_bonmkeluar_qcset_itemdetail');
    }

    function cancelheader($i_bonk)
    {
		$this->db->set(
			array(
				'f_bonk_cancel' => TRUE,
			)
		);
		$this->db->where('i_bonk',$i_bonk);
		$this->db->update('tm_bonmkeluar_qcset');
	}

	function cancelsemuadetail($i_bonk)
	{
		$this->db->set(
			array(
					'f_item_cancel' =>TRUE,
				)
			);
		$this->db->where('i_bonk',$i_bonk);
		$this->db->update('tm_bonmkeluar_qcset_item');

    }
    function deleteheader($i_bonk, $i_product, $i_color)
    {
		$this->db->set(
			array(
				'f_item_cancel' => TRUE,
			)
		);
        $this->db->where('i_bonk',$i_bonk);
        $this->db->where('i_product',$i_product);
        $this->db->where('i_color',$i_color);
		$this->db->update('tm_bonmkeluar_qcset_item');
	}

	function deletesemuadetail($i_bonk, $i_product)
	{
		$this->db->set(
			array(
					'f_cancel' =>TRUE,
				)
			);
            $this->db->where('i_bonk',$i_bonk);
            $this->db->where('i_product',$i_product);
		    $this->db->update('tm_bonmkeluar_qcset_itemdetail');

	}

    function updatenota($inota,$iarea,$ifakturkomersial){
      $query=$this->db->query("select i_faktur_komersial, i_seri_pajak from tm_nota where i_nota='$inota' and i_area='$iarea'");
      foreach($query->result() as $row){
            $komersial=$row->i_faktur_komersial;
            $pajak=$row->i_seri_pajak;
      }
      $this->db->query("insert into th_notapajak select * from tm_nota where i_nota='$inota' and i_area='$iarea'");
      $query=$this->db->query(" select a.*, b.i_customer_plu from tm_nota_item a
                                inner join tr_customer_plu b on (a.i_product=b.i_product)
                                where a.i_nota='$inota' and a.i_area='$iarea'");
      foreach($query->result() as $row){
            $this->db->query("insert into th_notapajak_item values('$row->i_sj','$komersial','$pajak',
                              '$row->i_nota', '$row->i_product', '$row->i_product_grade', '$row->i_product_motif', $row->n_deliver,
                              $row->v_unit_price, '$row->e_product_name', '$row->i_area', '$row->d_nota', $row->n_item_no, 
                              '$row->i_customer_plu')");
      }
    	$this->db->query("update tm_nota set i_faktur_komersial='$ifakturkomersial', i_seri_pajak=null, d_pajak=null, f_pajak_pengganti='t' 
                        where i_nota='$inota' and i_area='$iarea'");
    }
}

/* End of file Mmaster.php */
