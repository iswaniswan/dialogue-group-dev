<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_sj, a.d_sj, a.i_memo, b.e_customer_name,  a.f_sj_cancel, $i_menu as i_menu
                            from tm_sj_keluar_penjualanplastik a 
                            join tr_customer b on a.i_customer = b.i_customer",false);
        
        $datatables->edit('f_sj_cancel', function ($data) {
            $f_sj_cancel = trim($data['f_sj_cancel']);
            if($f_sj_cancel == 'f'){
               return  "Aktif";
            }else {
              return "Batal";
            }
        });
        
            $datatables->add('action', function ($data) {
            $isj    = trim($data['i_sj']);
            $i_menu     = $data['i_menu'];
            $f_sj_cancel    = trim($data['f_sj_cancel']);
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"sjkeluarpenjualanplastik/cform/view/$isj/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)&& $f_sj_cancel == 'f'){                
                $data .= "<a href=\"#\" onclick='show(\"sjkeluarpenjualanplastik/cform/edit/$isj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            
             }if(check_role($i_menu, 3) && $f_sj_cancel == 'f'){
                $data .= "<a href=\"#\" onclick='cancel(\"$isj\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
             }
			return $data;
        });
            
        $datatables->hide('i_menu');
        // $datatables->hide('f_receive');

        return $datatables->generate();
	}

    public function bacagudang(){
        $this->db->select(" * from tr_master_gudang 
                        where i_kode_master in('GD10003')
                        group by id, i_kode_master, e_nama_master",false);
        return $this->db->get()->result();
    }

    public function bacamemo(){          
          $this->db->select("* from tm_permintaanpengeluaranbb ", false);
          return $this->db->get();
    }

    public function product($cari, $gudang){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.*, d.e_satuan
            FROM
                tr_material a
           INNER JOIN 
               tm_kelompok_barang b on (a.i_kode_kelompok=b.i_kode_kelompok) 
            INNER JOIN 
               tr_master_gudang c on (b.i_kode_master=c.i_kode_master) 
            INNER JOIN
               tr_satuan d on (a.i_satuan_code=d.i_satuan_code)
            WHERE
                a.i_kode_kelompok='KTB0005' and
                b.i_kode_master = '$gudang'
                AND (UPPER(a.i_material) LIKE '%$cari%'
                OR UPPER(a.e_material_name) LIKE '%$cari%')
                order by a.i_material", 
        FALSE);
  }

    function runningnumberkeluar($yearmonth, $istore){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= substr($istore,5,2);
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);
            $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='SJK'
                            and i_area='$area'
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
                                  where i_modul='SJK'
                                  and e_periode='$asal' 
                                  and i_area='$area'
                                  and substring(e_periode,1,4)='$th'", false);
                settype($nobonmk,"string");
                $a=strlen($nobonmk);
                while($a<5){
                  $nobonmk="0".$nobonmk;
                  $a=strlen($nobonmk);
                }
                    $nobonmk  ="SJK-".$thn.$bl."-".$area.$nobonmk;
                return $nobonmk;
            }else{
                $nobonmk  ="00001";
                $nobonmk  ="SJK-".$thn.$bl."-".$area.$nobonmk;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('SJK','$area','$asal',1)");
                return $nobonmk;
            }
    }
      
    function insertheader($nosjkeluar, $imemo, $datesjk, $datememo, $icustomer, $istore, $remark){
            $dentry = date("Y-m-d");
            $data   = array(
                        'i_sj'              => $nosjkeluar,
                        'd_sj'              => $datesjk,
                        'i_memo'            => $imemo,
                        'd_memo'            => $datememo,
                        'i_customer'        => $icustomer,
                        'i_kode_master'     => $istore,
                        'e_remark'          => $remark,
                        'd_entry'           => $dentry
            );
            $this->db->insert('tm_sj_keluar_penjualanplastik', $data);
    }

    function insertdetail($nosjkeluar, $imaterial, $nquantity, $isatuan, $edesc, $no){ 
        $data = array(
                     'i_sj'         => $nosjkeluar,
                     'i_product'    => $imaterial,
                     'n_quantity'   => $nquantity,
                     'i_satuan'     => $isatuan,
                     'e_remark'     => $edesc,
                     'n_item_no'    => $no,
        );
        $this->db->insert('tm_sj_keluar_penjualanplastik_item', $data);
    }

    public function bacacustomer(){
        $this->db->select(" * from tr_customer 
                        group by i_customer",false);
        return $this->db->get()->result();
    }

    public function baca_header($isj){
        $this->db->select(" a.i_sj, a.d_sj, a.i_memo, a.d_memo, a.i_kode_master, a.e_remark, a.i_customer
                            from tm_sj_keluar_penjualanplastik a 
                            join tr_customer b on a.i_customer = b.i_customer
                            where a.i_sj='$isj'",false);
        return $this->db->get();
    }

    public function baca_detail($isj){
        $this->db->select(" a.i_sj, a.i_product, a.n_quantity, a.i_satuan, a.e_remark, b.e_satuan, c.e_material_name
                            from tm_sj_keluar_penjualanplastik_item a 
                            join tr_satuan b on a.i_satuan = b.i_satuan_code
                            join tr_material c on a.i_product = c.i_material
                            where a.i_sj='$isj'",false);
        return $this->db->get();
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

    function updateheader($isj, $datesjk, $istore, $imemo, $datememo, $icustomer, $remark){
        $dupdate = date("Y-m-d");
        $this->db->set(
          array(         
            'i_memo'    => $imemo,
            'd_memo'    => $datememo, 
            'i_customer'=> $icustomer,
            'e_remark'  => $remark,
            'd_sj'      => $datesjk
            )
          );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sj_keluar_penjualanplastik');
    }

     public function deletedetail($isj){
        $this->db->query("DELETE FROM tm_sj_keluar_penjualanplastik_item WHERE i_sj='$isj'");
    }
    
    function delete($isj,$iproduct,$icolor,$i){
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
}
/* End of file Mmaster.php */
