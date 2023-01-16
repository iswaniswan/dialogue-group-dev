<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($from, $to, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select i_sj, d_sj, i_unit_packing, e_nama_packing, i_periode_forcast, i_jenis, e_jenis_keluar, i_tujuan, tujuan, i_tujuan_kirim, e_remark, f_receive, f_cancel, $i_menu as i_menu from(
                        select a.* , b.e_jenis_keluar , (upper(c.e_nama_master)) as tujuan , UPPER(d.e_nama_packing) as e_nama_packing from tm_sj_keluarpacking a, tr_jenis_keluarpacking b ,tr_master_gudang  c,tr_unit_packing d
                            where (a.d_sj >= to_date('$from','yyyy-mm-dd')
                            and a.d_sj <= to_date('$to','yyyy-mm-dd'))
                            and a.i_jenis=b.i_jenis 
                            and a.i_tujuan_kirim=c.i_kode_master
                            and a.i_unit_packing=d.i_unit_packing

                            union all

                        select a.* , b.e_jenis_keluar , (upper(c.e_nama_packing)) as tujuan, UPPER(c.e_nama_packing) as e_nama_packing from tm_sj_keluarpacking a, tr_jenis_keluarpacking b ,tr_unit_packing  c
                            where (a.d_sj >= to_date('$from','yyyy-mm-dd')
                            and a.d_sj <= to_date('$to','yyyy-mm-dd'))
                            and a.i_jenis=b.i_jenis 
                            and a.i_tujuan_kirim=c.i_unit_packing
                            and a.i_unit_packing=c.i_unit_packing

                            union all

                        select a.* , b.e_jenis_keluar , (upper(c.e_unitjahit_name)) as tujuan, UPPER(d.e_nama_packing) as e_nama_packing from tm_sj_keluarpacking a, tr_jenis_keluarpacking b ,tr_unit_jahit  c,tr_unit_packing d
                            where (a.d_sj >= to_date('$from','yyyy-mm-dd')
                            and a.d_sj <= to_date('$to','yyyy-mm-dd'))
                            and a.i_jenis=b.i_jenis 
                            and a.i_tujuan_kirim=c.i_unit_jahit
                            and a.i_unit_packing=d.i_unit_packing
                            ) as a
                            order by i_sj", false);
        
        $datatables->edit('f_receive', function ($data) {
            $f_receive = trim($data['f_receive']);
            if($f_receive == 'f'){
               return  "Belum Approve";
            }else {
              return "Approve";
            }
        });

        $datatables->edit('i_tujuan', function ($data) {
            $i_tujuan = trim($data['i_tujuan']);
            if($i_tujuan == 'UJ'){
               return "Unit Jahit";
            }else if($i_tujuan == 'UP'){
              return "Unit Packing";
            }else{
                return "Gudang";
            }
        });
       
        $datatables->add('action', function ($data) {
        $isj        = trim($data['i_sj']);
        $f_cancel        = trim($data['f_cancel']);
        $i_menu     = $data['i_menu'];
        $data       = '';
        // var_dump(check_role($i_menu, 3));
        // die;
        if(check_role($i_menu, 3)){            
            $data .= "<a href=\"#\" onclick='show(\"sjkeluarpacking/cform/edit/$isj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
        }
        if ($f_cancel!='t') {
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$isj\"); return false;'><i class='fa fa-trash'></i></a>";
        }
			return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('i_jenis');
        $datatables->hide('i_unit_packing');
        $datatables->hide('i_tujuan_kirim');
        $datatables->hide('f_cancel');

        return $datatables->generate();
	}

    function bacaunit(){
        $this->db->select(" i_unit_packing, e_nama_packing from tr_unit_packing",false);
        return $this->db->get();
    }

    function cancelheader($isj){
        $this->db->set(
            array(
                'f_cancel' => TRUE,
            )
        );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sj_keluarpacking');
    }

    public function gettujuann($itujuan) {
        $this->db->select("* from (
                        SELECT b.i_tujuan as tujuan,a.i_kode_master as kode , a.e_nama_master as nama FROM tr_master_gudang a , tr_jenis_kirimqc b
                        where i_tujuan='GU'
                        union all
                        SELECT b.i_tujuan as tujuan ,a.i_unit_packing as kode ,a.e_nama_packing as nama FROM tr_unit_packing a,tr_jenis_kirimqc b
                        where i_tujuan='UP'
                        union all
                        SELECT b.i_tujuan as tujuan ,a.i_unit_jahit as kode , a.e_unitjahit_name as nama FROM tr_unit_jahit a,tr_jenis_kirimqc b
                        where i_tujuan='UJ'
                        ) as a 
                        where tujuan ='$itujuan'
                        order by tujuan, kode", false);
    return $this->db->get();
    }

    function runningnumber($thbl){
      $th   = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
          $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SJ'
                          and i_area='UP'
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
                          and i_area='UP'
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
                           values ('SJ','UP','$asal',1)");
              return $nosj;
        }
    }

    function insertheader($isj, $datesj, $ipacking, $iperiode, $ijenis, $itujuan, $igudang, $eremark){
        $dentry = date("d F Y H:i:s");
        $data   = array(
                    'i_sj'               => $isj,
                    'd_sj'               => $datesj,
                    'i_unit_packing'     => $ipacking,
                    'i_periode_forcast'  => $iperiode,
                    'i_jenis'            => $ijenis,
                    'i_tujuan'           => $itujuan,
                    'i_tujuan_kirim'     => $igudang,
                    'e_remark'           => $eremark,
                    'd_entry'            => $dentry,          
        );
        $this->db->insert('tm_sj_keluarpacking', $data);
    }

    function insertdetail($isj, $iproduct, $icolor, $eproductname, $nquantity, $eremarkh, $nitemno){ 
        $data = array(
                    'i_sj'               => $isj,
                    'i_product'          => $iproduct,
                    'e_product_name'     => $eproductname,
                    'i_color'            => $icolor,
                    'n_quantity'         => $nquantity,
                    'e_remark'           => $eremarkh,                    
                    'n_item_no'          => $nitemno,
        );
        $this->db->insert('tm_sj_keluarpacking_item', $data);
    }

    function cek_dataheader($isj){
        $this->db->select(" * from (
                        select a.* ,b.e_jenis_keluar, c.e_nama_master as tujuan , d.e_tujuan , UPPER(e.e_nama_packing) as e_nama_packing FROM tm_sj_keluarpacking a , tr_jenis_keluarpacking b, tr_master_gudang c, tr_jenis_kirimqc d,tr_unit_packing e
                            where a.i_sj='$isj'
                            and a.i_jenis=b.i_jenis
                            and a.i_tujuan_kirim=c.i_kode_master
                            and a.i_tujuan=d.i_tujuan
                            and a.i_unit_packing=e.i_unit_packing

                            union all

                        select a.* ,b.e_jenis_keluar, c.e_unitjahit_name as tujuan , d.e_tujuan , UPPER(e.e_nama_packing) as e_nama_packing FROM tm_sj_keluarpacking a , tr_jenis_keluarpacking b, tr_unit_jahit c, tr_jenis_kirimqc d,tr_unit_packing e
                            where a.i_sj='$isj'
                            and a.i_jenis=b.i_jenis
                            and a.i_tujuan_kirim=c.i_unit_jahit
                            and a.i_tujuan=d.i_tujuan
                            and a.i_unit_packing=e.i_unit_packing

                            union all

                        select a.* ,b.e_jenis_keluar, c.e_nama_packing as tujuan , d.e_tujuan ,UPPER(c.e_nama_packing) as e_nama_packing FROM tm_sj_keluarpacking a , tr_jenis_keluarpacking b, tr_unit_packing c, tr_jenis_kirimqc d
                            where a.i_sj='$isj'
                            and a.i_jenis=b.i_jenis
                            and a.i_tujuan_kirim=c.i_unit_packing
                            and a.i_tujuan=d.i_tujuan
                            and a.i_unit_packing=c.i_unit_packing
                        ) as a",false);
    return $this->db->get();
    }

    function cek_datadetail($isj){
        $this->db->select("a.*,b.e_color_name from tm_sj_keluarpacking_item a, tr_color b
                        where a.i_color=b.i_color
                        and a.i_sj='$isj'
                        order by a.n_item_no",false);
        return $this->db->get();
    }

    function updateheader($isj, $dsj, $ijenis, $iperiode, $igudang, $eremark, $itujuan,$ipacking){   
        $dupdate  = date("d F Y H:i:s");
        $data  = array(
                'd_sj'              => $dsj,
                'i_jenis'           => $ijenis,
                'i_periode_forcast' => $iperiode,
                'i_tujuan_kirim'    => $igudang,
                'e_remark'          => $eremark,            
                'i_tujuan'          => $itujuan,
                'i_unit_packing'    => $ipacking,
                'd_update'          => $dupdate
        );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sj_keluarpacking', $data);
    }

    function deletedetail($isj,$iproduct,$icolor) {
        $this->db->query("DELETE FROM tm_sj_keluarpacking_item  WHERE i_sj='$isj' and i_product='$iproduct' and i_color='$icolor' ");
    }

    function insertdetailproduct($ibon,$iproduct,$icolor,$imaterial,$ematerialname,$nquantity,$nitemno,$nodetail){	
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
    	
    	$this->db->insert('tm_bonmkeluar_qc_itemdetail');
    }

	function cancelsemuadetail($i_bonk){
		$this->db->set(
			array(
					'f_item_cancel' =>TRUE,
				)
			);
		$this->db->where('i_bonk',$i_bonk);
		$this->db->update('tm_bonmkeluar_qc_item');
    }

    function deleteheader($i_bonk, $i_product, $i_color){
		$this->db->set(
			array(
				'f_item_cancel' => TRUE,
			)
		);
        $this->db->where('i_bonk',$i_bonk);
        $this->db->where('i_product',$i_product);
        $this->db->where('i_color',$i_color);
		$this->db->update('tm_bonmkeluar_qc_item');
	}

	function deletesemuadetail($i_bonk, $i_product){
		$this->db->set(
			array(
					'f_cancel' =>TRUE,
				)
			);
            $this->db->where('i_bonk',$i_bonk);
            $this->db->where('i_product',$i_product);
		    $this->db->update('tm_bonmkeluar_qc_itemdetail');

	}
}

/* End of file Mmaster.php */
