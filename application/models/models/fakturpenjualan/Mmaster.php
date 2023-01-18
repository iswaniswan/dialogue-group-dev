<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $dfrom, $dto, $username, $idcompany, $idepartemen, $ilevel){
        // function data($i_menu, $dfrom, $dto, $username, $idcompany, $idepartemen, $ilevel)
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query(" select a.i_faktur_code, b.e_customer_name, a.d_faktur, a.v_total_faktur ,a.i_status, c.e_status, 
                            '$i_menu' as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement
                             from tm_faktur_do_t a
                             inner join tr_customer b on a.i_customer = b.i_customer
                             inner join tm_status_dokumen c on a.i_status = c.i_status
                             order by a.i_faktur_code, a.d_faktur",false);
        
        
        // $datatables->edit('f_op_cancel', function ($data) {
        //     $f_op_cancel = trim($data['f_op_cancel']);
        //     if($f_op_cancel == 'f'){
        //        return  "Aktif";
        //     }else {
        //       return "Batal";
        //     }
        // });
        
            $datatables->add('action', function ($data) {
            $ifaktur        = trim($data['i_faktur_code']);
            $i_menu         = $data['i_menu'];
            $i_status       = trim($data['i_status']);
            $i_departement  = trim($data['i_departement']);
            $i_level        = trim($data['i_level']);
            $data           = '';
            // if(check_role($i_menu, 1)){
                
            //     $data .= "<a href=\"#\" onclick='show(\"fakturpenjualan/cform/edit/$ifaktur/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            // }
//----------------------------------
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"fakturpenjualan/cform/view/$ifaktur/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }

            if(check_role($i_menu, 3)){
                if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" onclick='show(\"fakturpenjualan/cform/edit/$ifaktur/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
                }
            
                if ((($i_departement == '5' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"fakturpenjualan/cform/approval/$ifaktur\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
                }
            }
            if ($i_status!='5') {
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ifaktur\"); return false;'><i class='fa fa-trash'></i></a>";
            }

        //     if ($f_op_cancel!='t') {
        //         $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$iop\"); return false;'><i class='fa fa-trash'></i></a>";
        //   }
			return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('i_departement');
        $datatables->hide('i_level');
        // $datatables->hide('f_faktur_created');
        // $datatables->hide('f_do_cancel');

        return $datatables->generate();
    }
    
    public function getsj($ido){
        $in_str = "'".implode("', '", $ido)."'";
        $and   = "WHERE a.i_do IN (".$in_str.")";
        return $this->db->query(" SELECT a.*, b.e_customer_name, c.e_departement_name
                                  from tm_do a
                                  inner join tr_customer b on a.i_customer = b.i_customer
                                  inner join public.tr_departement c on a.i_kode_master = c.i_departement
                                  $and", false);
      }
    
      public function getsj_detail($ido){
        $in_str = "'".implode("', '", $ido)."'";
        $and   = "WHERE i_do IN (".$in_str.")";
          return $this->db->query(" SELECT * from tm_do_item $and", false);
      }

      public function bacado($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("SELECT * from tm_do
                                where f_faktur_created = 'f' 
                                and f_do_cancel = 'f'
                                and i_status = '6'
                                and upper(i_do) like '%$cari%' 
                                order by i_do, d_do",false);
      }

      public function bacado2(){
        // $cari = str_replace("'", "", $cari);
        return $this->db->query("SELECT * from tm_do
                                where f_faktur_created = 'f' 
                                and f_do_cancel = 'f'
                                and i_status = '6'
                                order by i_do, d_do",false);
      }
      

    	function cek_data($ifaktur){
            // select a.*, b.e_customer_name
            // from tm_faktur_do_t a
            // inner join tr_customer b on a.i_customer = b.i_customer 
            // where a.i_faktur_code = 'FP-02-2009-000014'
    		$this->db->select('a.*, b.e_customer_name, c.e_departement_name');
            $this->db->from('tm_faktur_do_t a');
            $this->db->join('tr_customer b','a.i_customer = b.i_customer');
            $this->db->join('public.tr_departement c','a.i_dept = c.i_departement');
            $this->db->where('a.i_faktur_code', $ifaktur);
            return $this->db->get();
      }
        function cek_datadet($ifaktur){   
            $this->db->select('*');
            $this->db->from('tm_faktur_do_item_t a');
            $this->db->where('a.i_do', $ifaktur);
            return $this->db->get();
        }

        function cek_datadetail($iop, $ikodelokasi){
            $this->db->select("a.i_op_code, a.i_product, a.e_product_name, a.n_customer_discount1, 
            a.n_customer_discount2, a.n_customer_discount3, (a.n_count-a.n_deliver) as n_count,a.v_price, b.e_color_name, c.n_quantity_stock, a.n_deliver, a.i_color
            from tm_op_item a 
            inner join tr_color b on a.i_color=b.i_color
            left join tm_ic c on a.i_product=c.i_product
            where a.i_op_code='$iop' 
            and i_kode_lokasi = '$ikodelokasi'
            order by a.i_product, a.i_color",false);
            return $this->db->get();
        }
        function cek_datadetheader($i_bonk){
            $this->db->select("a.i_bonk, a.d_bonk, b.i_product, b.e_product_name, b.i_color ,c.e_color_name, b.e_remark
                            ,d.e_nama_packing, a.i_unit
                            from tm_bonmkeluar_qc a 
                            inner join tm_bonmkeluar_qcset_item b on a.i_bonk=b.i_bonk
                            inner join tr_color c on b.i_color=c.i_color
                            inner join tr_unit_packing d on a.i_unit=d.i_unit_packing
                            where a.i_bonk='$i_bonk'
                            order by b.i_product, b.i_color, b.n_item_no",false);
            return $this->db->get();
        }
        public function getdetstore($ikodelokasi){
                return $this->db->query("
                    SELECT * FROM tm_ic WHERE i_kode_lokasi = '$ikodelokasi'  ORDER BY i_product",false);
            }

        function cek_datdetail($i_bonk, $i_product, $i_color){
            $this->db->select("a.i_material, b.e_material_name, a.n_quantity from tm_bonmkeluar_qc_itemdetail a, tr_material b 
                            where a.i_bonk ='$i_bonk' and a.i_product='$i_product' and a.i_color='$i_color' and a.i_material=b.i_material
                            group by a.i_material , b.e_material_name , a.n_quantity",false);
            return $this->db->get();
        }

        public function updatestatus($ifaktur){
            $user = $this->session->userdata('username');
            $dentry = current_datetime();
            if ($status=='6') {
                $data = array(
                    'i_status' => $status,
                    'f_receive' => 't',
                    'd_receive' => $dentry,
                    'i_approve' => $user,
                    'd_approve' => $dentry,
                );
            }elseif($status=='3'){
                $data = array(
                    'i_status' => $status,
                    'i_status_change' => $status,
                );
            }else{
                $data = array(
                    'i_status' => $status,
                );
            }
            $this->db->where('i_bonk', $ifaktur);
            $this->db->update('tm_bonmkeluar_unitjahit', $data);
        }

    // public function bacagudang(){
    //     $this->db->select(" * from tr_master_gudang 
    //                     where i_kode_master in('G08','G13')
    //                     group by id, i_kode_master, e_nama_master",false);
    //     return $this->db->get()->result();
    // }

    function bacagudang($ilevel, $idepart, $lokasi, $username,$idcompany){
        if ($username != 'admin') {
            $where = "WHERE username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";
            // where username = 'mimin' and a.i_departement = '1' and a.i_level = '1'
        } 
        else {
            $where = "";
        }
        return $this->db->query(" SELECT a.* , b.e_departement_name, c.e_level_name
                                  from public.tm_user_deprole a
                                  inner join public.tr_departement b on a.i_departement = b.i_departement
                                  inner join public.tr_level c on a.i_level = c.i_level $where ", FALSE);
    }

    public function bacastore($ikodelokasi){
        $this->db->select(" a.*, b.i_kode_lokasi
                            from tr_master_gudang a
                            inner join tm_ic b on a.i_kode_lokasi = b.i_kode_lokasi
                            where a.i_kode_master in('G08','G13') and b.i_kode_lokasi = '$ikodelokasi'
                            group by a.id, a.i_kode_master, a.e_nama_master, b.i_kode_lokasi",false);
        return $this->db->get();
    }

    public function bacaarea(){
        $this->db->select(" * from tr_area ",false);
        return $this->db->get()->result();
    }

    public function ceklokasi($ikodemaster){
        $this->db->select(" i_kode_lokasi from tr_master_gudang 
                        where i_kode_master = '$ikodemaster'",false);
        return $this->db->get();
    }
    function cekqtysj($isj,$iproduct,$icolor){
        $this->db->select(" n_quantity from tm_sjkeluar_gdjadihadiah_item 
                        where i_sj = '$isj' and i_product = '$iproduct' and i_color = '$icolor'",false);
        return $this->db->get();
    }

    public function cekstock($iproduct,$kodelokasi,$icolor){
        $this->db->select("n_quantity_stock from tm_ic 
                        where i_product = '$iproduct' and i_kode_lokasi = '$kodelokasi' 
                        and i_product_grade = 'A' and i_color = '$icolor'",false);
        return $this->db->get();
    }

    public function  cekqtyop($iop, $iproduct, $icolor){
        $this->db->select("n_count from tm_op_item 
                        where i_op_code = '$iop' and i_product = '$iproduct' and i_color = '$icolor' ",false);
        return $this->db->get();
    }
    function runningnumber($thbl, $lok){
        $th	= substr($thbl,0,4);
        $asal=$thbl;
        $thbl=substr($thbl,2,2).substr($thbl,4,2);
            $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='FP'
                            and i_area='FP'
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
                                  where i_modul='FP'
                                  and e_periode='$asal' 
                                  and i_area='FP'
                                  and substring(e_periode,1,4)='$th'", false);
                settype($nobonmk,"string");
                $a=strlen($nobonmk);
                while($a<6){
                  $nobonmk="0".$nobonmk;
                  $a=strlen($nobonmk);
                }
                    // $nobonmk  ="FP-".$thbl."-".$nobonmk;
                    $nobonmk  ="FP-".$lok."-".$thbl."-".$nobonmk;
                return $nobonmk;
            }else{
                $nobonmk  ="000001";
                $nobonmk  ="FP-".$lok."-".$thbl."-".$nobonmk;
                // $nobonmk  ="FP-".$thbl."-".$nobonmk;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('FP','FP','$asal',1)");
                return $nobonmk;
            }
      }
      
    function insertheader($ifaktur, $icustomer, $dfaktur, $ndis, $vspbdiscounttotal, $vspb, $vspbbersih, $dpp, $ppn, $vspbfppn,$dept){
            $query 		= $this->db->query("SELECT current_timestamp as c");
            $row   		= $query->row();
            $dentry	= $row->c;

            $data   = array(
                        'i_faktur'          => '0',
                        'i_faktur_code'     => $ifaktur,
                        'i_customer'        => $icustomer,
                        'd_faktur'          => $dfaktur,
                        'n_discount'        => $ndis,
                        'v_discount'        => $vspbdiscounttotal,
                        'v_total_faktur'    => $vspbbersih,
                        'v_total_fppn'      => $vspbfppn,
                        'd_entry'           => $dentry,
                        'f_pelunasan'       => 'f',
                        'v_grand_sisa'      => $vspbbersih,
                        'v_sisa_alo'        => $vspbbersih,
                        'v_dpp'               => $dpp,
                        'v_ppn'               => $ppn,
                        'i_dept'              => $idept,
                        'v_kotor'             => $vspb
            );
            $this->db->insert('tm_faktur_do_t', $data);
    }
    function insertdetail($ifaktur, $idoo, $iproduct, $eproductname, $ndis, $ndeliver, $vproductretail,$j,$total){ 
        $query 		= $this->db->query("SELECT current_timestamp as c");
        $row   		= $query->row();
        $dentry	= $row->c;
        $data = array(
            'i_faktur'              => $ifaktur,
            'i_do'                  => $idoo,
            'i_product'             => $iproduct,
            'e_product_name'        => $eproductname,
            'n_customer_discount1'  => $ndis,
            'n_quantity'            => $ndeliver,
            'v_unit_price'          => $vproductretail,
            'v_total'               => $total,
            'd_entry'               => $dentry,
        );
        $this->db->insert('tm_faktur_do_item_t', $data);
    }
    

    function updatestock($iproduct, $total, $kodelokasi){
        $this->db->set(
          array(          
            'n_quantity_stock'  => $total,
            )
          );
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_kode_lokasi',$kodelokasi);
        $this->db->where('i_product_grade','A');
        $this->db->update('tm_ic');
    }

    function updateheader($isj,$dsj,$eremark){
        $this->db->set(
          array(          
            'e_remark'  => $eremark,
            'd_sj'  => $dsj
            )
          );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sjkeluar_gdjadihadiah');
    }

    function updatedo($ido){
        $this->db->set(
            array(          
              'f_faktur_created'  => TRUE,
              )
            );
          $this->db->where('i_do',$ido);
          $this->db->update('tm_do');

    }

    function updateopheader($iop){

        $this->db->set(
            array(          
              'f_do_created'  => TRUE,
              )
            );

          $this->db->where('i_op_code',$iop);
          $this->db->update('tm_op');

    }
    

    function cancelheader($ifaktur)
    {
		$this->db->set(
			array(
				'f_faktur_cancel' => TRUE,
			)
		);
		$this->db->where('i_faktur_code',$ifaktur);
		$this->db->update('tm_faktur_do_t');
	}

	// function cancelsemuadetail($i_bonk)
	// {
	// 	$this->db->set(
	// 		array(
	// 				'f_item_cancel' =>TRUE,
	// 			)
	// 		);
	// 	$this->db->where('i_bonk',$i_bonk);
	// 	$this->db->update('tm_bonmkeluar_qc_item');

    // }
    function delete($isj,$iproduct,$icolor,$i)
    {
		$this->db->set(
			array(
				'f_item_cancel' => TRUE,
			)
		);
        $this->db->where('i_sj',$isj);
        $this->db->where('i_product',$i_product);
        $this->db->where('i_color',$i_color);
        $this->db->where('n_item_no',$i);
		$this->db->update('tm_sjkeluar_gdjadihadiah_item');
    }
    
    public function send($kode){
        $data = array(
            'i_status'    => '2'
        );
  
        $this->db->where('i_faktur_code', $kode);
        $this->db->update('tm_faktur_do_t', $data);
      }
  
      public function change($kode){
        $data = array(
            'i_status'    => '3'
        );
  
        $this->db->where('i_faktur_code', $kode);
        $this->db->update('tm_faktur_do_t', $data);
      }
  
      public function reject($kode){
        $data = array(
            'i_status'    => '4'
        );
  
        $this->db->where('i_faktur_code', $kode);
        $this->db->update('tm_faktur_do_t', $data);
      }
  
      public function approve($kode){
        $now = date("Y-m-d");
        $data = array(
            'i_status'   => '6',
            'd_approve' => $now
        );
  
        $this->db->where('i_faktur_code', $kode);
        $this->db->update('tm_faktur_do_t', $data);
      }

	// function deletesemuadetail($i_bonk, $i_product)
	// {
	// 	$this->db->set(
	// 		array(
	// 				'f_cancel' =>TRUE,
	// 			)
	// 		);
    //         $this->db->where('i_bonk',$i_bonk);
    //         $this->db->where('i_product',$i_product);
	// 	    $this->db->update('tm_bonmkeluar_qc_itemdetail');

	// }
}

/* End of file Mmaster.php */
