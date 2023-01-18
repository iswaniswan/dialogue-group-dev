<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function gudang()
    {
        $idepartemen = $this->session->userdata('i_departement');
        $id_company  = $this->session->userdata('id_company');
        $ilevel      = $this->session->userdata('i_level');
        $username    = $this->session->userdata('username');
        if ($username!='admin') {
            $where = "
                WHERE
                    username = '$username'
                    AND a.i_departement = '$idepartemen'
                    AND a.i_level = '$ilevel' 
                    AND id_company = '$id_company'";
        }else{
            $where = "";
        }
        return $this->db->query("
            SELECT DISTINCT
                b.i_departement, 
                e_departement_name
            FROM
                public.tm_user_deprole a
            INNER JOIN public.tr_departement b ON
                a.i_departement = b.i_departement
            INNER JOIN public.tr_level c ON
                a.i_level = c.i_level
            $where
            ORDER BY e_departement_name
        ", FALSE);
    }

    function data($i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_bonk, a.d_bonk, a.i_bon_permintaan, a.d_bon_permintaan, a.f_bonk_cancel, $i_menu as i_menu
                            from tm_bonkeluarpengadaan_aksesories a ",false);
        
        $datatables->edit('f_bonk_cancel', function ($data) {
            $f_bonk_cancel = trim($data['f_bonk_cancel']);
            if($f_bonk_cancel == 'f'){
               return  "Aktif";
            }else {
              return "Batal";
            }
        });
            $datatables->add('action', function ($data) {
            $ibonk    = trim($data['i_bonk']);
            $i_menu   = $data['i_menu'];
            $f_bonk_cancel    = trim($data['f_bonk_cancel']);
            $data       = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"pengeluaranproduksikepengadaan/cform/view/$ibonk/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)&& $f_bonk_cancel == 'f'){                
                $data .= "<a href=\"#\" onclick='show(\"pengeluaranproduksikepengadaan/cform/edit/$ibonk/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            
             }if(check_role($i_menu, 3) && $f_bonk_cancel == 'f'){
                $data .= "<a href=\"#\" onclick='cancel(\"$ibonk\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
             }
      return $data;
        });
            
        $datatables->hide('i_menu');
        return $datatables->generate();
    }

    public function getdepartemen($cari){
        $idepartemen = $this->session->userdata('i_departement');
        $id_company  = $this->session->userdata('id_company');
        $ilevel      = $this->session->userdata('i_level');
        $username    = $this->session->userdata('username');
        $cari        = str_replace("'", "", $cari);
        /*return $this->db->query("
            SELECT
                i_sub_bagian, e_sub_bagian
            FROM
                tm_sub_bagian
            WHERE
                (UPPER(i_sub_bagian) LIKE '%$cari%'
                OR UPPER(e_sub_bagian) LIKE '%$cari%')
                ORDER BY i_sub_bagian", 
        FALSE);*/
        return $this->db->query("
            SELECT DISTINCT
                b.i_departement AS id,
                e_departement_name AS name
            FROM
                public.tm_user_deprole a
            INNER JOIN public.tr_departement b ON
                a.i_departement = b.i_departement
            INNER JOIN public.tr_level c ON
                a.i_level = c.i_level
            WHERE
                username <> '$username'
                AND a.i_departement <> '$idepartemen'
                /*AND a.i_level  = '$ilevel'*/ 
                AND id_company = '$id_company'
                AND (UPPER(e_departement_name) LIKE '%$cari%')
            ORDER BY e_departement_name
        ", FALSE);
    } 

    public function getmemo($cari, $gudang){
        $cari = strtoupper($cari);
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_op_code
            FROM
                tm_opaksesoris
            WHERE
                (UPPER(i_op_code) LIKE '%$cari%')
                ORDER BY i_op_code", 
        FALSE);
    }

    public function getmemoheader($imemo, $gudang){
        return $this->db->query("
            SELECT
                a.i_op_code,
                to_char(a.d_op, 'dd-mm-yyyy') AS d_op
            FROM
                tm_opaksesoris a
            WHERE
                a.i_op_code = '$imemo'
        ", false);
    }

    public function getdetailmemo($imemo, $gudang){
      return $this->db->query("
            select a.*, m.e_material_name, s.e_satuan 
            from tm_op_itemaksesoris a
            inner join tr_material m on (a.i_product = m.i_material)
            inner join tr_satuan s on (s.i_satuan_code = m.i_satuan_code)
            where a.i_op_code = '$imemo' ", false);
    }   

    function runningnumberpp($yearmonth, $istore){
        $bl = substr($yearmonth,4,2);
        $th = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= substr($istore,5,2);
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);
            $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='BKP'
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
                                  where i_modul='BKP'
                                  and e_periode='$asal' 
                                  and i_area='$area'
                                  and substring(e_periode,1,4)='$th'", false);
                settype($nobonmk,"string");
                $a=strlen($nobonmk);
                while($a<5){
                  $nobonmk="0".$nobonmk;
                  $a=strlen($nobonmk);
                }
                    $nobonmk  ="BKP-".$thn.$bl."-".$area.$nobonmk;
                return $nobonmk;
            }else{
                $nobonmk  ="00001";
                $nobonmk  ="BKP-".$thn.$bl."-".$area.$nobonmk;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('BKP','$area','$asal',1)");
                return $nobonmk;
            }
    }
      
    function insertheader($ikeluarprod, $istore, $datebonk, $imemo, $datememo, $idepartement, $eremark){
            $dentry = date("Y-m-d");
            $data   = array(
                        'i_bonk'            => $ikeluarprod,
                        'd_bonk'            => $datebonk,
                        'i_bon_permintaan'  => $imemo,
                        'd_bon_permintaan'  => $datememo,
                        'i_departemen'      => $idepartement,
                        'i_kode_master'     => $istore,
                        'e_remark'          => $eremark,
                        'd_entry'           => $dentry
            );
            $this->db->insert('tm_bonkeluarpengadaan_aksesories', $data);
    }

    function insertdetail($ikeluarprod, $i_material, $n_qtyout, $n_qtykeluar, $edesc, $i){ 
        $data = array(
                     'i_bonk'                   => $ikeluarprod,
                     'i_kode_barang'            => $i_material,
                     'n_quantity_permintaan'    => $n_qtyout,
                     'n_quantity_keluar'        => $n_qtykeluar,
                     'e_remark'                 => $edesc,
                     'n_item_no'                => $i,
        );
        $this->db->insert('tm_bonkeluarpengadaan_aksesories_detail', $data);
    }

    public function bacadepartemen(){
        $this->db->select(" * from tm_sub_bagian
                            order by i_sub_bagian",false);
        return $this->db->get()->result();
    }

    public function get_header(){
        $this->db->select(" a.* 
                        from tm_bonkeluarpengadaan_aksesories a",false);
        return $this->db->get();
    }

    public function get_detail(){
        $this->db->select(" a.*, c.e_material_name 
                        from tm_bonkeluarpengadaan_aksesories_detail a
                        join tm_bonkeluarpengadaan_aksesories b on a.i_bonk =  b.i_bonk
                        join tr_material c on a.i_kode_barang = c.i_material",false);
        return $this->db->get();
    }

    function updateheader($ikeluarprod, $istore, $datebonk, $imemo, $idepartement, $eremark){
        $dupdate = date("Y-m-d");
        $this->db->set(
          array(         
                    'd_bonk'            => $datebonk,
                    'i_bon_permintaan'  => $imemo,
                    'i_departemen'      => $idepartement,
                    'i_kode_master'     => $istore,
                    'e_remark'          => $eremark,
                    'd_update'          => $dupdate
            )
          );
        $this->db->where('i_bonk',$ikeluarprod);
        $this->db->update('tm_bonkeluarpengadaan_aksesories');
    }

    public function deletedetail($ikeluarprod){
        $this->db->query("DELETE FROM tm_bonkeluarpengadaan_aksesories_detail WHERE i_bonk='$ikeluarprod'");
    }
    
    function cancel($ibonk){
    $this->db->set(
      array(
        'f_bonk_cancel' => 't',
      )
    );
        $this->db->where('i_bonk',$ibonk);
    $this->db->update('tm_bonkeluarpengadaan_aksesories');
  }
}
/* End of file Mmaster.php */