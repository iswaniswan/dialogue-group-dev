<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $dfrom, $dto){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT x.kode, x.barang, x.kodegudang, x.gudang, x.satuan, 
        sum(x.bonkeluarlain) as keluar, sum(x.bonmasuklain) as masuk,'$i_menu' as i_menu
        from(
        SELECT c.i_material as kode, c.e_material_name as barang, a.i_kode_master as kodegudang, 
        f.e_nama_master as gudang, d.e_satuan as satuan, sum(b.n_qty) as bonkeluarlain, 0 AS bonmasuklain
        FROM tm_bonmkeluar_pinjamanbpplastik a
        JOIN tm_bonmkeluar_pinjamanbpplastik_detail b ON (a.i_bonmk = b.i_bonmk and a.i_kode_master = b.i_kode_master)
        JOIN tr_material c ON c.i_material = b.i_material
        JOIN tr_satuan d ON d.i_satuan_code = c.i_satuan_code
        JOIN tr_master_gudang f ON f.i_kode_master = a.i_kode_master
        WHERE 
        a.d_bonmk >= to_date('$dfrom','dd-mm-yyyy')
        AND a.d_bonmk <= to_date('$dto','dd-mm-yyyy') 
        and a.f_cancel = 'f'
        GROUP BY c.i_material, c.e_material_name, a.i_kode_master, f.e_nama_master, d.e_satuan
        
        UNION ALL
        
        SELECT c.i_material as kode, c.e_material_name as barang, a.i_kode_master as kodegudang, 
        f.e_nama_master as gudang, d.e_satuan as satuan, 0 as bonkeluarlain, sum(b.n_qty) AS bonmasuklainn
        FROM 
        tm_bonmmasuk_pinjamanbpplastik a 
        JOIN tm_bonmmasuk_pinjamanbpplastik_detail b ON (a.i_bonmm = b.i_bonmm  and a.i_kode_master = b.i_kode_master)
        JOIN tr_material c ON c.i_material = b.i_material
        JOIN tr_satuan d ON d.i_satuan_code = c.i_satuan_code
        JOIN tr_master_gudang f ON f.i_kode_master = a.i_kode_master
        WHERE a.d_bonmm >= to_date('$dfrom','dd-mm-yyyy')
        AND a.d_bonmm <= to_date('$dto','dd-mm-yyyy')
        AND a.f_cancel = 'f'
        GROUP BY c.i_material, c.e_material_name, a.i_kode_master, f.e_nama_master, d.e_satuan
        )  AS x
        GROUP BY  x.kode, x.barang, x.kodegudang, x.gudang, x.satuan",false);
        
        
        // $datatables->edit('f_bonk_cancel', function ($data) {
        //     $f_bonk_cancel = trim($data['f_bonk_cancel']);
        //     if($f_bonk_cancel == 'f'){
        //         return  "Aktif";
        //     }else {
        //         return "Batal";
        //     }
        // });
        
            $datatables->add('action', function ($data) {
            $kode    = trim($data['kode']);
            $i_menu     = $data['i_menu'];
            // $i_status    = trim($data['i_status']);
            // $i_departement= trim($data['i_departement']);
            // $i_level      = trim($data['i_level']);
            
            $data       = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"laporanmutasiplastikpinjaman/cform/view/$kode/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }            
            
			return $data;
        });
            
        $datatables->hide('kodegudang');
        $datatables->hide('i_menu');
        

        return $datatables->generate();
	}

    	function cek_data($ido){
    	
            $this->db->select("a.*, b.e_customer_name, c.e_departement_name
                              from tm_do a
                              inner join tr_customer b on a.i_customer = b.i_customer
                              inner join public.tr_departement c on a.i_kode_master = c.i_departement
                              where a.i_do = '$ido'",false);
            return $this->db->get();
      }
        function cek_datadet($ido){     
            $this->db->select("a.*, b.e_color_name
                                from tm_do_item a
                                inner join tr_color b on a.i_color = b.i_color
                                where a.i_do='$ido'",false);
            return $this->db->get();
        }
        // function cek_datadetheader($i_bonk){
        //     $this->db->select("a.i_bonk, a.d_bonk, b.i_product, b.e_product_name, b.i_color ,c.e_color_name, b.e_remark
        //                     ,d.e_nama_master, a.i_gudang
        //                     from tm_bonmkeluar_qcset a 
        //                     inner join tm_bonmkeluar_qcset_item b on a.i_bonk=b.i_bonk
        //                     inner join tr_color c on b.i_color=c.i_color
        //                     inner join tr_master_gudang d on a.i_gudang=d.i_kode_master
        //                     where a.i_bonk='$i_bonk'
        //                     order by b.i_product, b.i_color, b.n_item_no",false);
        //     return $this->db->get();
        // }

        function cek_datdetail($i_bonk, $i_product, $i_color){
            $this->db->select("a.i_material, b.e_material_name, a.n_quantity from tm_bonmkeluar_qcset_itemdetail a, tr_material b 
                            where a.i_bonk ='$i_bonk' and a.i_product='$i_product' and a.i_color='$i_color' and a.i_material=b.i_material
                            group by a.i_material , b.e_material_name , a.n_quantity",false);
            return $this->db->get();
        }

    function bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany){
        // if ($username != 'admin') {
            $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";
            // where username = 'mimin' and a.i_departement = '1' and a.i_level = '1'
        // } 
        // else {
        //     $where = "";
        // }
        return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name
                                  from public.tm_user_deprole a
                                  inner join public.tr_departement b on a.i_departement = b.i_departement
                                  inner join public.tr_level c on a.i_level = c.i_level $where ", FALSE);
    }

    // select a.* , b.e_departement_name, c.e_level_name
    // from tm_user_deprole a
    // inner join tr_departement b on a.i_departement = b.i_departement
    // inner join tr_level c on a.i_level = c.i_level
    // where username = 'mimin' and a.i_departement = '1' and a.i_level = '1'

    function runningnumber($thbl, $lok){
        $th	= substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
            $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='DO'
                            and i_area='$lok'
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
                                  where i_modul='DO'
                                  and e_periode='$asal' 
                                  and i_area='$lok'
                                  and substring(e_periode,1,4)='$th'", false);
                settype($nobonmk,"string");
                $a=strlen($nobonmk);
                while($a<5){
                  $nobonmk="0".$nobonmk;
                  $a=strlen($nobonmk);
                }
                    $nobonmk  ="DO-".$lok."-".$thbl."-".$nobonmk;
                return $nobonmk;
            }else{
                $nobonmk  ="00001";
                $nobonmk  ="DO-".$lok."-".$thbl."-".$nobonmk;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('DO','$lok','$asal',1)");
                return $nobonmk;
            }
      }
    
    function insertheader($ido, $ddo, $icustomer, $vdo, $dept, $eremark, $ireff) {	
  		$query 		= $this->db->query("SELECT current_timestamp as c");
  		$row   		= $query->row();
  		$dentry	= $row->c;
      	$this->db->set(
      		array(
          'i_do'              =>$ido,
          'd_do'              =>$ddo,
          'i_customer'        =>$icustomer,
          'v_total_gross'     =>$vdo,
          'v_total_discount'  =>'0',
          'v_total_netto'     =>'0',
          'i_kode_master'     =>$dept,
          'e_remark'          =>$eremark,
          'd_entry'           =>$dentry,
          'i_reff'            =>$ireff
      		)
      	);
    	
    	$this->db->insert('tm_do');
    }

    function insertdetail($ido, $ireff, $iproduct, $eproduct, $icolor, $vprice, $qty, $vgros, $eremarkh, $j, $qtyorder){			
      $query 		= $this->db->query("SELECT current_timestamp as c");
  		$row   		= $query->row();
  		$dentry	= $row->c;
      $this->db->set(
    		array(
          // 'i_sj'         => $ibonm,
          // 'i_reff'	 	   => $ireff,
          // 'i_color'	     => $icolor,
          // 'i_product'    => $iproduct,
          // 'n_quantity'   => $qtymasuk,
          // 'e_remark'		 => $edesc,
          // 'n_item_no'    => $no,

          'i_do'          =>$ido,
          'i_op'          =>$ireff,
          'i_product'     =>$iproduct,
          'e_product_name'=>$eproduct,
          'i_color'       =>$icolor,
          'v_price'       =>$vprice,
          'n_deliver'     =>$qty,
          'n_order'       =>$qtyorder,
          'v_do_gross'    =>$vgros,
          'e_remark'      =>$eremarkh,
          'n_item_no'     =>$j,
          'd_entry'       =>$dentry
          // n_customer_discount1
          // n_customer_discount2
          // n_customer_discount3
    		)
    	);
    	$this->db->insert('tm_do_item');
    }

    public function getop($ireff,$ilokasi){
        return $this->db->query("
            select * from tm_op
            where i_op_code='$ireff'
          ", false);
    }
  
    public function getop_detail($ireff,$ilokasi){
        return $this->db->query("SELECT a.*, b.e_color_name, c.n_quantity_stock 
                                from tm_op_item a 
                                inner join tr_color b on a.i_color= b.i_color
                                left join tm_ic c on a.i_product= c.i_product and a.i_color = c.i_color
                                where a.i_op_code = '$ireff' 
                                and c.i_kode_lokasi = '$ilokasi'", false);
    }

    public function getstock($lok,$iproduct,$icolor){
      return $this->db->query("
          select n_quantity_stock from tm_ic
          where i_product= '$iproduct' and i_color = '$icolor' and i_kode_lokasi = '$lok' and i_product_grade = 'A'")->row()->n_quantity_stock;
    }


    function updateheader($idept, $ddo,$ireff, $eremark, $ido) {   
        $query      = $this->db->query("SELECT current_timestamp as c");
        $row        = $query->row();
        $dentry = $row->c;
        $this->db->set(
            array(
                'd_do'           => $ddo,
                'e_remark'       => $eremark,
                'd_update'       => $dentry
            )
        );
        
       $this->db->where('i_do',$ido);
       $this->db->update('tm_do');
    }

    function updatestock($lok,$iproduct,$icolor,$stokupdate){   
      $this->db->set(
          array(
              'n_quantity_stock'       => $stokupdate
          )
      );
      
     $this->db->where('i_product',$iproduct);
     $this->db->where('i_kode_lokasi',$lok);
     $this->db->where('i_color',$icolor);
     $this->db->update('tm_ic');
    }

    function updateop($ireff){   
      $this->db->set(
          array(
              'f_do_created'       => TRUE
          )
      );
      
     
     $this->db->where('i_op_code',$ireff);
     $this->db->update('tm_op');
    }

    function deletedetail($ido) {
         $this->db->query("DELETE FROM tm_do_item WHERE i_do='$ido'");
    }

    function cancelheader($i_bonk)
    {
		$this->db->set(
			array(
				'i_status' => '9',
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


    // public function getsjhead($ireff){
    //     return $this->db->query("
    //         select a.i_sj, to_char(a.d_sj, 'dd-mm-yyyy') as d_sj, a.i_unit_jahit, b.e_unitjahit_name from tm_sj_keluar_makloonunitjahit a
    //         inner join tr_unit_jahit b on (a.i_unit_jahit = b.i_unit_jahit)
    //         where a.i_sj = '$ireff'
    //         /*where a.i_status = '5'*/
    //     ", false);
    // }

    public function getsjdetail($isj, $ipartner){
        // if ($isj == "semua") {
        //   $isj = 'i_sj';
        // }
        return $this->db->query("
            select x.i_sj,to_char(d.d_sj, 'dd-mm-yyyy') as d_sj, d.i_tujuan_kirim, x.i_product, x.i_color, e_product_basename, e_color_name,x.n_sisa from (
              select i_sj, i_product, i_color, n_sisa from tm_sj_keluarqc_item 
              where i_sj = (case when '$isj' = 'semua' then i_sj else '$isj' end) group by i_sj, i_product, i_color, n_sisa
            ) as x 
            inner join tr_product_base b on (x.i_product = b.i_product_motif)
            inner join tr_color c on (x.i_color = c.i_color)
            inner join tm_sj_keluarqc d on (x.i_sj = d.i_sj)
            where d.i_tujuan_kirim = '$ipartner' and x.n_sisa >0
            order by d.d_sj, x.i_sj, e_product_basename asc
        ", false);
    }

    public function getsjdetailedit($isj, $ipartner){
        return $this->db->query("
            select a.i_reff, to_char(b.d_sj, 'dd-mm-yyyy') as d_sj, b.i_tujuan_kirim, a.i_color, a.i_product, 
             d.e_product_basename, e.e_color_name, c.n_sisa, a.n_quantity, a.e_remark from tm_sjmasuk_makloonqc_item a
            inner join tm_sj_keluarqc b on (a.i_reff = b.i_sj)
            inner join (
              select i_sj, i_product, i_color, n_sisa from tm_sj_keluarqc_item group by i_sj, i_product, i_color, n_sisa
            ) as c on (a.i_reff = c.i_sj and a.i_product = c.i_product and a.i_color = c.i_color)
            inner join tr_product_base d on (a.i_product = d.i_product_motif)
            inner join tr_color e on (a.i_color = e.i_color)   
            where a.i_sj = '$isj' and b.i_tujuan_kirim = '$ipartner'
            order by b.d_sj, a.i_reff, d.e_product_basename asc
        ", false);
    }

    // public function getdetailsj($i_bonk, $ipartner){
    //     return $this->db->query("
    //         select a.*, b.n_sisa, e_namabrg, e_color_name, e.i_unit_jahit, f.e_unitjahit_name from tm_sj_masuk_makloonunitjahit_item a
    //         inner join (
    //           select i_sj, i_product, i_color, n_sisa from tm_sj_keluar_makloonunitjahit_item 
    //           where i_sj IN (select i_reff from tm_sj_masuk_makloonunitjahit_item where i_sj = '$i_bonk') 
    //           group by i_sj, i_product, i_color, n_sisa
    //         ) b on ( a.i_reff = b.i_sj and a.i_wip = b.i_product and a.i_color = b.i_color)
    //         inner join tm_barang_wip c on (a.i_wip = c.i_kodebrg)
    //         inner join tr_color d on (a.i_color = d.i_color)
    //         inner join tm_sj_keluar_makloonunitjahit e on (a.i_reff = e.i_sj)
    //         inner join tr_unit_jahit f on (e.i_unit_jahit = f.i_unit_jahit)
    //         where a.i_sj = '$i_bonk' and b.n_sisa >0
    //         order by a.i_reff
    //     ", false);
    // }

    public function send($kode){
      $data = array(
          'i_status'    => '2'
      );

      $this->db->where('i_do', $kode);
      $this->db->update('tm_do', $data);
    }

    public function change($kode){
      $data = array(
          'i_status'    => '3'
      );

      $this->db->where('i_do', $kode);
      $this->db->update('tm_do', $data);
    }

    public function reject($kode){
      $data = array(
          'i_status'    => '4'
      );

      $this->db->where('i_do', $kode);
      $this->db->update('tm_do', $data);
    }

    public function approve($kode){
      $now = date("Y-m-d");
      $data = array(
          'i_status'   => '6',
          'd_approve' => $now
      );

      $this->db->where('i_do', $kode);
      $this->db->update('tm_do', $data);
    }

    public function batal($kode){
      $data = array(
          'i_status'    => '9'
      );

      $this->db->where('i_do', $kode);
      $this->db->update('tm_do', $data);
    }

    public function updatedetailkeluar($ireff, $iproduct, $icolor, $qtymasuk) {
        $this->db->query("update tm_sj_keluarqc_item set n_sisa = n_sisa - $qtymasuk where i_sj='$ireff' and i_product='$iproduct' and i_color = '$icolor'");
    }


    ////baru
    public function getpartnerreff($ipartner){
        // $this->db->select('*');
        // $this->db->from('tm_kelompok_barang');
        // // $this->db->where('i_kode_master',$ikodemaster);
        // $this->db->where('i_kode_group_barang','GRB0001');
        // $this->db->where('i_kode_kelompok','KTB0005');
        return $this->db->query("
        select * from tm_op where i_customer = '$ipartner' and f_do_created = 'f'", false);
  }

}

/* End of file Mmaster.php */
