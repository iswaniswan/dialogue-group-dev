<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu,$folder,$dfrom,$dto){
    //var_dump($dfrom);
    if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE a.d_konversi BETWEEN '$dfrom' AND '$dto'";
    }else{
        $where = "";
    }
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("
                         SELECT ROW_NUMBER() OVER (ORDER BY a.i_konversi) as nomor,
                           a.i_konversi,
                           a.d_konversi,
                           a.i_referensi,
                           a.i_partner,
                           b.e_supplier_name,
                           a.e_remark,
                           a.i_status,
                           c.e_status,
                           a.f_cancel,
                           c.label_color as label,
                           $i_menu as i_menu 
                        FROM
                           tm_konversipinjamanpenjualan_bb a 
                           JOIN
                              tr_supplier b 
                              ON a.i_partner = b.i_supplier 
                           JOIN
                              tm_status_dokumen c 
                              ON a.i_status = c.i_status
                           $where", FALSE);

    $datatables->edit('e_status', function ($data) {
        $f_cancel = trim($data['f_cancel']);
        if($f_cancel == 't'){
          return '<span class="label label-danger label-rouded">Batal</span>';
        }else {
          return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status'].'</span>';
        }
    });

		$datatables->add('action', function ($data) {
            $ikonversi     = trim($data['i_konversi']);
            $ipartner      = trim($data['i_partner']);
            $ireferensi    = trim($data['i_referensi']);
            $i_status      = trim($data['i_status']);
            $f_cancel      = trim($data['f_cancel']);
            $i_menu        = $data['i_menu'];
            $data          = '';

            if(check_role($i_menu, 2)){
                  $data .= "<a href=\"#\" title='View' onclick='show(\"konversipinjamankepenjualan/cform/view/$ikonversi/$ipartner/$ireferensi/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_cancel == 'f' && $i_status !='6' && $i_status != '4'){
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"konversipinjamankepenjualan/cform/edit/$ikonversi/$ipartner/$ireferensi/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 1)&& $f_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
               $data .= "<a href=\"#\" title='Approve' onclick='show(\"konversipinjamankepenjualan/cform/approve/$ikonversi/$ipartner/$ireferensi/\",\"#main\"); return false;'><i class='fa ti-check-box'></i></a>&nbsp;&nbsp;";
            }
            if ($f_cancel!='t' && $i_status != '6' && $i_status !='4') {
                $data .= "<a href=\"#\" title='Delete' onclick='cancel(\"$ikonversi\"); return false;'><i class='ti-close'></i></a>&nbsp;&nbsp;";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_partner');
        $datatables->hide('i_status');
        $datatables->hide('f_cancel');
        $datatables->hide('label');

        return $datatables->generate();
	}

  function bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany){
      //var_dump($idepart);
      if(trim($idepart) == '1'){
        return $this->db->query("SELECT
                                     a.i_departemen as i_departement,
                                     a.e_nama_master as e_departement_name 
                                FROM
                                     tr_master_gudang a 
                                ORDER BY
                                     a.i_kode_master", FALSE);
      }else{
        $where = "WHERE a.username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";

        return $this->db->query("SELECT
                                     a.*,
                                     b.e_departement_name,
                                     c.e_level_name,
                                     d.i_bagian 
                                  FROM
                                     public.tm_user_deprole a 
                                    JOIN
                                        public.tr_departement b 
                                        ON a.i_departement = b.i_departement 
                                    JOIN
                                        public.tr_level c 
                                        ON a.i_level = c.i_level 
                                    JOIN
                                        public.tm_user d 
                                        ON a.id_company = d.id_company 
                                        AND a.username = d.username
                                        $where", FALSE);
      }
  }

  public function sjkp($cari, $ipartner){
        return $this->db->query("
            SELECT
                a.i_bonmk, a.d_bonmk, a.department, b.e_supplier_name
            FROM
                tm_bonmkeluar_pinjamanbb a
            JOIN  
                tr_supplier b on a.department = b.i_supplier
            WHERE
                a.department = '$ipartner'
                AND a.tujuan_keluar = 'external'
                AND a.f_konversi = 'f'
                AND a.i_status = '6'
                AND (UPPER(a.i_bonmk) LIKE '%$cari%'
                OR UPPER(b.e_supplier_name) LIKE '%$cari%')", 
        FALSE);
  }

  public function getsjkp($isjkp, $ipartner){
      $in_str = "'".implode("', '", $isjkp)."'";
      $and   = "and a.i_bonmk IN (".$in_str.")";
      return $this->db->query("
                                select
                                   a.i_bonmk,
                                   to_char(a.d_bonmk, 'dd-mm-yyyy') as d_bonmk,
                                   a.department,
                                   b.e_supplier_name 
                                from
                                   tm_bonmkeluar_pinjamanbb a 
                                   inner join
                                      tr_supplier b 
                                      on a.department = b.i_supplier 
                                where
                                  
                                   a.department = '$ipartner' 
                                   and a.tujuan_keluar = 'external'
                                   $and", false);
  }

  public function getsjkp_detail($isjkp){
      $in_str = "'".implode("', '", $isjkp)."'";
      $and   = "where a.i_bonmk IN (".$in_str.")";
      return $this->db->query("
                                select
                                   a.i_bonmk,
                                   a.i_material,
                                   b.e_material_name,
                                   a.n_qty,
                                   a.i_satuan,
                                   s.e_satuan 
                                from
                                   tm_bonmkeluar_pinjamanbb_detail a 
                                   inner join
                                      tr_material b 
                                      on (a.i_material = b.i_material) 
                                   inner join
                                      tr_satuan s 
                                      on (s.i_satuan_code = a.i_satuan) 
                                  $and", false);
  }

  function runningnumbernokonversi($yearmonth, $ilokasi){
        $bl  = substr($yearmonth,4,2);
        $th  = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= trim($ilokasi);
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
          $kode  =$terakhir+1;
                $this->db->query("update tm_dgu_no 
                            set n_modul_no=$kode
                            where i_modul='KPP'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
          settype($kode,"string");
          $a=strlen($kode);
  
          //u/ 0
          while($a<5){
            $kode="0".$kode;
            $a=strlen($kode);
          }
            $kode  ="KPP"."-".$area."-".$thn.$bl."-".$kode;
          return $kode;
        }else{
          $kode  ="00001";
          $kode  ="KPP"."-".$area."-".$thn.$bl."-".$kode;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('KPP','$area','$asal',1)");
          return $kode;
        }
    }

    public function insertheader($nokonversi, $istore, $datekonversi, $isjkp, $ipartner, $eremark, $datebonmk){
        $dentry = date("Y-m-d");

        $data = array(
                'i_konversi'     => $nokonversi,
                'i_partner'      => $ipartner,
                'i_referensi'    => $isjkp,
                'd_konversi'     => $datekonversi,
                'e_remark'       => $eremark,
                'i_bagian'       => $istore,
                'd_referensi'    => $datebonmk,
                'd_entry'        => $dentry, 
    );
    $this->db->insert('tm_konversipinjamanpenjualan_bb', $data);
    }

    public function insertdetail($nokonversi, $imaterial, $nqtyawal, $nqtyout , $nquantity, $isatuan, $no, $edesc){
        $data = array(   
                'i_konversi'              => $nokonversi,
                'i_product'               => $imaterial,
                'n_quantity_awal'         => $nqtyawal,
                'n_quantity_outstanding'  => $nqtyout,
                'n_quantity_konversi'     => $nquantity,
                'i_satuan'                => $isatuan,
                'e_remark'                => $edesc, 
                'n_item_no'               => $no
    );
    $this->db->insert('tm_konversipinjamanpenjualan_bb_detail', $data);
    }

    public function send($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_konversi', $kode);
    $this->db->update('tm_konversipinjamanpenjualan_bb', $data);
    }


    function get_konversi($nokonversi){
        $this->db->select("
                            a.i_konversi,
                            to_char(a.d_konversi, 'dd-mm-yyyy') as d_konversi,
                            a.i_bagian,
                            a.i_partner,
                            b.e_supplier_name,
                            a.i_referensi,
                            to_char(a.d_referensi, 'dd-mm-yyyy') as d_referensi,
                            a.f_cancel,
                            a.n_discount,
                            a.e_remark,
                            a.f_pkp,
                            a.i_status 
                              from
                                 tm_konversipinjamanpenjualan_bb a 
                              join
                                  tr_supplier b 
                                  on a.i_partner = b.i_supplier 
                              where 
                                  a.i_konversi='$nokonversi'", false);
        return $this->db->get();
    }

    function get_konversidetail($nokonversi){
        $this->db->select("
                           a.*,
                           b.e_material_name 
                            from
                                tm_konversipinjamanpenjualan_bb_detail a 
                            join
                                tr_material b 
                                on a.i_product = b.i_material 
                            where 
                                a.i_konversi='$nokonversi'");
        return $this->db->get();
    }

    public function bacapartner($ireferensi){
        $this->db->select("
                            distinct
                               a.i_supplier,
                               a.e_supplier_name 
                            from
                               tr_supplier a 
                               join
                                  tm_bonmkeluar_pinjamanbb b 
                                  on a.i_supplier = b.department
                               where
                                  b.i_bonmk = '$ireferensi'", false);
        return $this->db->get()->result();
  }

  public function bacareferensi($ipartner){
        $this->db->select("
                            i_bonmk,
                            d_bonmk 
                            from
                               tm_bonmkeluar_pinjamanbb 
                            where
                               department = '$ipartner'", false);
        return $this->db->get()->result();
  }

  function updateheader($nokonversi, $istore, $datekonversi, $isjkp, $ipartner, $eremark, $datereferensi){
        $dupdate = date("Y-m-d");
        $this->db->set(
          array(         
                'i_partner'      => $ipartner,
                'i_referensi'    => $isjkp,
                'd_referensi'    => $datereferensi,
                'd_konversi'     => $datekonversi,
                'e_remark'       => $eremark,
                'i_bagian'       => $istore,
                'd_update'       => $dupdate, 
            )
          );
        $this->db->where('i_konversi',$nokonversi);
        $this->db->update('tm_konversipinjamanpenjualan_bb');
  }

  public function deletedetail($nokonversi){
        $this->db->query("DELETE FROM tm_konversipinjamanpenjualan_bb_detail WHERE i_konversi='$nokonversi'");
  }

  public function sendd($nokonversi){
      $data = array(
          'i_status'    => '2'
    );

    $this->db->where('i_konversi', $nokonversi);
    $this->db->update('tm_konversipinjamanpenjualan_bb', $data);
  }

  public function cancel_approve($nokonversi){
        $data = array(
                  'i_status'   => '7',
    );
    $this->db->where('i_konversi', $nokonversi);
    $this->db->update('tm_konversipinjamanpenjualan_bb', $data);
  }

  public function cancel($nokonversi){
        $data = array(
                  'f_cancel'   => 't',
                  'i_status'   => '9',
    );
    $this->db->where('i_konversi', $nokonversi);
    $this->db->update('tm_konversipinjamanpenjualan_bb', $data);
  }

  public function approve($nokonversi, $isjkp){
        $data = array(
                'i_status'     => '6',
    );
    $this->db->where('i_konversi', $nokonversi);
    $this->db->update('tm_konversipinjamanpenjualan_bb', $data);

        $dataa = array(
                'f_konversi'   => 't',
    );
    $this->db->where('i_bonmk', $isjkp);
    $this->db->update('tm_bonmkeluar_pinjamanbb', $dataa);
  }

  public function change_approve($nokonversi){
        $data = array(
                'i_status'     => '3',
    );
    $this->db->where('i_konversi', $nokonversi);
    $this->db->update('tm_konversipinjamanpenjualan_bb', $data);
  }

  public function reject_approve($nokonversi){
      $data = array(
              'i_status'      => '4',
    );
    $this->db->where('i_konversi', $nokonversi);
    $this->db->update('tm_konversipinjamanpenjualan_bb', $data);
  }
}
/* End of file Mmaster.php */