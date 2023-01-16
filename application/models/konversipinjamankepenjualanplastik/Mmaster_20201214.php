<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT a.i_konv, a.d_konv, a.i_customer, b.e_supplier_name, a.e_remark, $i_menu as i_menu 
                            FROM tm_konversipinjamanpenjualan_plastik a 
                            join tr_supplier b on a.i_customer = b.i_supplier ");

		$datatables->add('action', function ($data) {
            $ikonversi      = trim($data['i_konv']);
            $i_menu         = $data['i_menu'];
            $data           = '';

            if(check_role($i_menu, 2)){
                  $data .= "<a href=\"#\" onclick='show(\"konversipinjamankepenjualanplastik/cform/view/$ikonversi/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
              }

            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"konversipinjamankepenjualanplastik/cform/edit/$ikonversi/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_customer');
        //$datatables->hide('i_product');
        return $datatables->generate();
	}

  public function bacagudang(){
        $this->db->select('*');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', 'GD10003');
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
  }

  public function sjkp($cari, $gudang, $customer){
        //$cari = strtoupper($this->input->get('q'));
        //$cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_bonmk, a.department, b.e_supplier_name
            FROM
                tm_bonmkeluar_pinjamanbpplastik a
            JOIN  
                tr_supplier b on a.department = b.i_supplier
            WHERE
                a.i_kode_master = '$gudang'
                AND a.department = '$customer'
                AND a.tujuan_keluar = 'external'
                AND a.f_cancel='f'
                AND (UPPER(a.i_bonmk) LIKE '%$cari%'
                OR UPPER(b.e_supplier_name) LIKE '%$cari%')", 
        FALSE);
  }

  public function getsjkp($isjkp, $gudang, $customer){
      return $this->db->query("
          select a.i_bonmk, a.department, b.e_supplier_name
          from tm_bonmkeluar_pinjamanbpplastik a
          inner join tr_supplier b on a.department = b.i_supplier
          where a.i_bonmk = '$isjkp' 
          and a.i_kode_master = '$gudang' 
          and a.department = '$customer'
          and a.tujuan_keluar = 'external'", false);
  }

  public function getsjkp_detail($isjkp, $gudang){
      return $this->db->query("select a.i_bonmk, a.i_kode_master, a.i_material, a.n_qty, a.e_remark, a.i_satuan, a.e_material_name, a.e_satuan,
            case when b.konversi is null then a.sisa-0 else a.sisa-b.konversi end as sisa from
            ( 
            select final.*, case when n_deliver is null then n_qty-0 else n_qty-n_deliver end as sisa  from (
                            select x.* ,y.n_deliver from (
                              select b.*, m.e_material_name, s.e_satuan 
                                    from tm_bonmkeluar_pinjamanbpplastik_detail b
                                    inner join tm_bonmkeluar_pinjamanbpplastik a on (b.i_bonmk = a.i_bonmk and b.i_kode_master = a.i_kode_master)
                                    inner join tr_material m on (b.i_material = m.i_material)
                                    inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
                                    where b.i_bonmk = '$isjkp' and b.i_kode_master = '$gudang' and a.f_cancel = 'f'
                            ) as x
                            left join (
                            select a.i_bonmk, a.i_kode_master, b.i_material, sum(n_deliver) as n_deliver
                            from tm_bonmmasuk_pinjamanbpplastik a
                            inner join tm_bonmmasuk_pinjamanbpplastik_detail b on (a.i_bonmm = b.i_bonmm and a.i_kode_master = b.i_kode_master)
                            where a.i_bonmk = '$isjkp' and a.i_kode_master = '$gudang' and a.f_cancel = 'f'
                            group by a.i_bonmk, a.i_kode_master, b.i_material
                            ) y on (x.i_bonmk = y.i_bonmk and x.i_kode_master = y.i_kode_master and x.i_material = y.i_material)
                         ) as final
            ) as a
            left join (
            select a.i_reff, a.i_kode_master, b.i_material, sum(n_konversi) as konversi
            from tm_konversipinjamanpenjualan_plastik a
            inner join tm_konversipinjamanpenjualan_plastik_detail b on (a.i_konv = b.i_konv)
            where a.i_reff = '$isjkp' and a.i_kode_master = '$gudang' and a.f_cancel = 'f'
            group by a.i_reff, a.i_kode_master, b.i_material
            ) as b on (a.i_bonmk = b.i_reff and a.i_kode_master = b.i_kode_master and a.i_material = b.i_material)", false);
  }

  function runningnumbernokonversi($yearmonth, $istore){
      $bl = substr($yearmonth,4,2);
      $th = substr($yearmonth,0,4);
      $thn = substr($yearmonth,2,2);
      $area= substr($istore,5,2);
      $asal= substr($yearmonth,0,4);
      $yearmonth= substr($yearmonth,0,4);

          $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='KPP'
                          and i_area='$area'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' for update", false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              foreach($query->result() as $row){
                $terakhir=$row->max;
              }
              $nokonversi = $terakhir+1;
              $this->db->query("update tm_dgu_no 
                          set n_modul_no=$nokonversi
                          where i_modul='KPP'
                          and e_periode='$asal' 
                          and i_area='$area'
                          and substring(e_periode,1,4)='$th'", false);
              settype($nokonversi,"string");
              $a=strlen($nokonversi);
              while($a<5){
                $nokonversi="0".$nokonversi;
                $a=strlen($nokonversi);
              }
                $nokonversi  ="KPP-".$thn.$bl."-".$area.$nokonversi;
              return $nokonversi;
          }else{
              $nokonversi  ="00001";
            $nokonversi  ="KPP-".$thn.$bl."-".$area.$nokonversi;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('KPP','$area','$asal',1)");
              return $nokonversi;
        }
    }

    public function insertheader($nokonversi, $istore, $datekonversi, $isjkp, $icustomer, $eremark){
        $dentry = date("Y-m-d");

        $data = array(
                'i_konv'         => $nokonversi,
                'i_customer'     => $icustomer,
                'i_reff'         => $isjkp,
                'd_konv'         => $datekonversi,
                'e_remark'       => $eremark,
                'i_kode_master'  => $istore,
                'd_entry'        => $dentry, 
    );
    $this->db->insert('tm_konversipinjamanpenjualan_plastik', $data);
    }

    public function insertdetail($nokonversi, $imaterial, $nqtyawal, $nqtyout , $nquantity, $isatuan, $no, $edesc){
        $data = array(   
                'i_konv'              => $nokonversi,
                'i_material'          => $imaterial,
                'n_qty'               => $nqtyawal,
                'n_qty_out'           => $nqtyout,
                'n_konversi'          => $nquantity,
                'i_satuan_code'       => $isatuan,
                'e_remark'            => $edesc, 
                'i_no_item'           => $no
    );
    $this->db->insert('tm_konversipinjamanpenjualan_plastik_detail', $data);
    }

    function getforecast($nokonversi){
        $this->db->select("a.*, b.e_supplier_name 
                          from tm_konversipinjamanpenjualan_plastik a
                          join tr_supplier b on a.i_customer=b.i_supplier
                          where a.i_konv='$nokonversi'");
        return $this->db->get();
    }

    function getforecastdetail($nokonversi){
        $this->db->select("a.*, b.e_material_name 
                          from tm_konversipinjamanpenjualan_plastik_detail a
                          join tr_material b on a.i_material = b.i_material
                           where a.i_konv='$nokonversi'");
        return $this->db->get();
    }

    public function bacacustomer(){
        $this->db->select("distinct (a.i_supplier), a.e_supplier_name");
            $this->db->from("tr_supplier a");
            $this->db->join("tm_bonmkeluar_pinjamanbpplastik b", "a.i_supplier = b.department");
        return $this->db->get()->result();
  }

  function updateheader($nokonversi, $istore, $datekonversi, $isjkp, $icustomer, $eremark){
        $dupdate = date("Y-m-d");
        $this->db->set(
          array(         
                'i_customer'     => $icustomer,
                'i_reff'         => $isjkp,
                'd_konv'         => $datekonversi,
                'e_remark'       => $eremark,
                'i_kode_master'  => $istore,
                'd_update'       => $dupdate, 
            )
          );
        $this->db->where('i_konv',$nokonversi);
        $this->db->update('tm_konversipinjamanpenjualan_plastik');
  }

  public function deletedetail($nokonversi){
        $this->db->query("DELETE FROM tm_konversipinjamanpenjualan_plastik_detail WHERE i_konv='$nokonversi'");
  }
}
/* End of file Mmaster.php */