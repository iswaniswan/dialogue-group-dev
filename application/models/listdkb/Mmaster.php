<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function area($cari){
        $cari      = str_replace("'", "", $cari);
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND (UPPER(i_area) LIKE '%$cari%'
                OR UPPER(e_area_name) LIKE '%$cari%')
        ", FALSE);
    }

    public function data($dfrom,$dto,$iarea,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.i_dkb,
                to_char(a.d_dkb, 'dd-mm-yyyy') AS d_dkb,
                b.e_area_name,
                a.e_sopir_name,
                a.i_kendaraan,
                sum(v_jumlah) AS jumlah,
                a.f_dkb_batal AS status,
                a.i_area,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                '$folder' AS folder,
                '$i_menu' AS i_menu
            FROM
                tr_area b,
                tm_dkb a,
                tm_dkb_item c
            WHERE
                a.i_area = b.i_area
                AND a.i_dkb = c.i_dkb
                AND a.i_area = c.i_area
                AND b.i_area = c.i_area
                AND a.i_area = '$iarea'
                AND a.d_dkb >= to_date('$dfrom', 'dd-mm-yyyy')
                AND a.d_dkb <= to_date('$dto', 'dd-mm-yyyy')
            GROUP BY
                a.i_dkb,
                a.d_dkb,
                b.e_area_name,
                a.e_sopir_name,
                a.i_kendaraan,
                a.f_dkb_batal,
                a.i_area
            ORDER BY
                a.i_dkb DESC"
        , FALSE);

        $datatables->add('action', function ($data) {
            $id             = trim($data['i_dkb']);
            $f_dkb_batal    = $data['status'];
            $i_area         = $data['i_area'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $cekdkb         = $this->db->query("
                SELECT
                    i_nota
                FROM
                    tm_nota
                WHERE
                    i_dkb = '$id'
            ", FALSE);
            if ($cekdkb->num_rows()>0) {
                $bisahapus = 'f';
            }else{
                $bisahapus = 't';
            }
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$i_area/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $bisahapus == 't' && $status == 'f'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$id\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('i_dkb', function ($data) {
            if ($data['status']=='f') {
                $data = '<span class="label label-success label-rouded">'.$data['i_dkb'].'</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">'.$data['i_dkb'].'</span>';
            }
            return $data;
        });

        $datatables->hide('status');
        $datatables->hide('i_area');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

     public function cancel($idkb, $iarea){
        $this->db->query("
            UPDATE
                tm_dkb
            SET
                f_dkb_batal = 't'
            WHERE
                i_dkb = '$idkb'
                AND i_area = '$iarea'
        ");
        $que=$this->db->query("
            SELECT
                i_sj,
                i_area
            FROM
                tm_dkb_item
            WHERE
                i_dkb = '$idkb'
                AND i_area = '$iarea'
        ");
        if ($que->num_rows() > 0){
            foreach($que->result() as $row){
                return $this->db->query("
                    UPDATE
                        tm_nota
                    SET
                        i_dkb = NULL,
                        d_dkb = NULL
                    WHERE
                        i_sj = '$row->i_sj'
                        AND i_area = '$iarea'
                ");
            }
        }
    }

    public function bacadetailrunningjml($idkb,$iarea){
        return $this->db->query("
            SELECT
                sum(v_jumlah) AS v_total
            FROM
                tm_dkb_item
            WHERE
                i_dkb = '$idkb'
                AND i_area = '$iarea'
        ", false);
    }   

    public function baca($idkb,$iarea){
        $query = $this->db->query("
            SELECT
                DISTINCT ON
                (tm_dkb.i_dkb || substring(x.i_nota, 1, 7)) (tm_dkb.i_dkb || substring(x.i_nota, 1, 7)) AS xx,
                tm_dkb.i_dkb,
                tm_dkb.i_dkb_kirim,
                tm_dkb.i_dkb_via,
                tm_dkb.i_area,
                tm_dkb.i_ekspedisi,
                tm_dkb.d_dkb,
                tm_dkb.i_dkb_old,
                tm_dkb.i_kendaraan,
                tm_dkb.e_sopir_name,
                tm_dkb.v_dkb,
                tm_dkb.f_dkb_batal,
                tm_dkb.d_entry,
                to_char(tm_dkb.d_entry, 'yyyy-mm-dd') AS tglentry,
                tm_dkb.d_update,
                tm_dkb.i_approve1,
                tr_area.e_area_name,
                tr_dkb_kirim.e_dkb_kirim,
                tr_dkb_via.e_dkb_via,
                tr_ekspedisi.e_ekspedisi,
                x.i_nota
            FROM
                tm_dkb
            LEFT JOIN tm_nota x ON
                (tm_dkb.i_dkb = x.i_dkb
                AND tm_dkb.i_area = x.i_area)
            INNER JOIN tr_area ON
                (tm_dkb.i_area = tr_area.i_area)
            INNER JOIN tr_dkb_kirim ON
                (tm_dkb.i_dkb_kirim = tr_dkb_kirim.i_dkb_kirim)
            INNER JOIN tr_dkb_via ON
                (tm_dkb.i_dkb_via = tr_dkb_via.i_dkb_via)
            LEFT JOIN tr_ekspedisi ON
                (tm_dkb.i_ekspedisi = tr_ekspedisi.i_ekspedisi)
            WHERE
                tm_dkb.i_dkb = '$idkb'
        ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($idkb,$iarea){
        $query = $this->db->query("
            SELECT
                a.*
            FROM
                tm_dkb_item a
            WHERE
                a.i_dkb = '$idkb'
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacadetailx($idkb,$iarea){
        $query = $this->db->query("
            SELECT
                a.*,
                b.e_ekspedisi
            FROM
                tm_dkb_ekspedisi a,
                tr_ekspedisi b
            WHERE
                a.i_dkb = '$idkb'
                AND a.i_area = '$iarea'
                AND a.i_ekspedisi = b.i_ekspedisi
            ORDER BY
                a.i_dkb
        ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacaarea(){
        return $this->db->order_by('i_area','ASC')->get('tr_area')->result();
    }

    public function bacadkb($username, $idcompany){
        $this->db->select('*');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $this->db->where('i_area', '00');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $this->db->select('*');
            $this->db->from('tr_dkb_kirim');
            return $this->db->get();
        }else{
            $this->db->select('*');
            $this->db->from('tr_dkb_kirim');
            $this->db->where('i_dkb_kirim','1');
            return $this->db->get();
        }
    }

    public function bacavia(){
        return $this->db->order_by('i_dkb_via','ASC')->get('tr_dkb_via')->result();
    }

    public function deleteheader($idkb, $iarea) {
        $this->db->query("DELETE FROM tm_dkb WHERE i_dkb='$idkb' and i_area='$iarea'");
    }

    public function bacasj($cari,$iarea,$ddkbx){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                a.i_sj
            FROM
                tm_nota a,
                tr_customer b
            WHERE
                a.i_area = '$iarea'
                AND (SUBSTRING(a.i_sj, 9, 2) IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'))
                AND a.i_dkb ISNULL
                AND a.f_nota_cancel = 'f'
                AND a.i_customer = b.i_customer
                AND a.d_sj <= '$ddkbx'
                AND (UPPER(a.i_sj) LIKE '%$cari%'
                OR UPPER(b.e_customer_name) LIKE '%$cari%'
                OR UPPER(a.i_spb) LIKE '%$cari%'
                OR UPPER(a.i_customer) LIKE '%$cari%'
                OR UPPER(a.i_sj_old) LIKE '%$cari%')
            ORDER BY
                a.i_sj", FALSE);
    }

    public function bacasjx($iarea,$ddkbx,$isj){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                a.*,
                to_char(a.d_sj,'dd-mm-yyyy') AS dsj,
                b.i_customer, 
                b.e_customer_name
            FROM
                tm_nota a,
                tr_customer b
            WHERE
                a.i_area = '$iarea'
                AND (SUBSTRING(a.i_sj, 9, 2) IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany'))
                AND a.i_dkb ISNULL
                AND a.f_nota_cancel = 'f'
                AND a.i_customer = b.i_customer
                AND a.d_sj <= '$ddkbx'
                AND a.i_sj = '$isj'
            ORDER BY
                a.i_sj", FALSE);
    }

    public function bacaex($cari,$iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT 
                i_ekspedisi,
                e_ekspedisi
            FROM
                tr_ekspedisi
            WHERE
                i_area = '$iarea'
                AND (UPPER(i_ekspedisi) LIKE '%$cari%'
                OR UPPER(e_ekspedisi) LIKE '%$cari%')
            ORDER BY
                i_ekspedisi", FALSE);
    }

    public function bacaexx($iarea,$iekspedisi){
        return $this->db->query("
            SELECT 
                *
            FROM
                tr_ekspedisi
            WHERE
                i_area = '$iarea'
                AND i_ekspedisi = '$iekspedisi'
            ORDER BY
                i_ekspedisi", FALSE);
    }

    public function areanya(){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $this->db->select('i_area');
        $this->db->from('public.tm_user_area');
        $this->db->where('username', $username);
        $this->db->where('id_company', $idcompany);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $x = $query->row();
            $iarea = $x->i_area;
        }else{
            $iarea = '';
        }
        return $iarea;
    }

    public function runningnumber($iarea,$thbl){      
        $th     = substr($thbl,0,4);      
        $asal   = $thbl;      
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no AS MAX
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'DKB'
                AND substr(e_periode, 1, 4)= '$th'
                AND i_area = '$iarea' FOR
            UPDATE", false);
        $query = $this->db->get();        
        if ($query->num_rows() > 0){           
            foreach($query->result() as $row){             
                $terakhir=$row->max;           
            }           
            $nodkb  =$terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nodkb
                WHERE
                    i_modul = 'DKB'
                    AND substr(e_periode, 1, 4)= '$th'
                    AND i_area = '$iarea' ", false);            
            settype($nodkb,"string");            
            $a=strlen($nodkb);            
            while($a<4){               
                $nodkb="0".$nodkb;               
                $a=strlen($nodkb);           
            }           
            $nodkb  ="DKB-".$thbl."-".$iarea.$nodkb;       
            return $nodkb;       
        }else{         
            $nodkb  ="0001";         
            $nodkb  ="DKB-".$thbl."-".$iarea.$nodkb;         
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul,
                    i_area,
                    e_periode,
                    n_modul_no)
                VALUES ('DKB',
                '$iarea',
                '$asal',
                1) ");         
            return $nodkb;     
        } 
    }

    public function insertheader($idkb, $ddkb, $iareasj, $idkbkirim, $idkbvia, $ikendaraan, $esopirname, $vdkb, $idkbold){       
        $dentry  = current_datetime();     
        $this->db->set(
            array(
                'i_dkb'         => $idkb,
                'i_dkb_kirim'   => $idkbkirim,
                'i_dkb_via'     => $idkbvia,
                'i_area'        => $iareasj,
                'd_dkb'         => $ddkb,
                'i_kendaraan'   => $ikendaraan,
                'e_sopir_name'  => $esopirname,
                'v_dkb'         => $vdkb,
                'f_dkb_batal'   => 'f',
                'd_entry'       => $dentry,
                'i_dkb_old'     => $idkbold
            )
        );
        $this->db->insert('tm_dkb');
    }

    public function deletedetail($idkb, $iarea, $isj) {
        $this->db->select("v_jumlah from tm_dkb_item
         where i_dkb = '$idkb' and i_area='$iarea' and i_sj='$isj'", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $this->db->query("update tm_dkb set v_dkb=v_dkb WHERE i_dkb='$idkb' and i_area='$iarea'");
                $this->db->query("update tm_nota set i_dkb=null, d_dkb=null WHERE i_sj='$isj' and i_area='$iarea'");
            }
        }
        $this->db->query("DELETE FROM tm_dkb_item WHERE i_dkb='$idkb' and i_area='$iarea' and i_sj='$isj'");
    }

    public function insertdetail($idkb,$iareasj,$isj,$ddkb,$dsj,$vjumlah,$eremark,$i){      
        if($eremark=='') {        
            $eremark=null;    
        }      
        $this->db->where('i_dkb', $idkb);
        $this->db->where('i_area', $iareasj);
        $this->db->where('i_sj', $isj);
        $this->db->delete('tm_dkb_item');

        $this->db->set(
            array(
                'i_dkb'     => $idkb,
                'i_area'    => $iareasj,
                'i_sj'      => $isj,
                'd_dkb'     => $ddkb,
                'd_sj'      => $dsj,
                'v_jumlah'  => $vjumlah,
                'e_remark'  => $eremark,
                'n_item_no' => $i
            )
        );
        $this->db->insert('tm_dkb_item');
    }

    public function updatesj($idkb,$isj,$iareasj,$ddkb){
        $this->db->set(
            array(
                'i_dkb' => $idkb,   
                'd_dkb' => $ddkb
            )
        );
        $this->db->where('i_sj',$isj);
        $this->db->where('i_area',$iareasj);
        $this->db->update('tm_nota');
    }

    public function deletedetailekspedisi($idkb,$iarea,$iekspedisi){
        $this->db->query("DELETE FROM tm_dkb_ekspedisi WHERE i_dkb='$idkb' AND i_area='$iarea' AND i_ekspedisi='$iekspedisi'");
    }

    public function insertdetailekspedisi($idkb,$iarea,$iekspedisi,$ddkb,$eremark,$i){
        $this->db->where('i_dkb', $idkb);
        $this->db->where('i_area', $iarea);
        $this->db->where('i_ekspedisi', $iekspedisi);
        $this->db->delete('tm_dkb_ekspedisi');
        $this->db->set(
            array(
                'i_dkb'         => $idkb,
                'i_area'        => $iarea,
                'i_ekspedisi'   => $iekspedisi,
                'd_dkb'         => $ddkb,
                'e_remark'      => $eremark,
                'n_item_no'     => $i
            )
        );
        $this->db->insert('tm_dkb_ekspedisi');
    }

    public function customertodetail($isj,$dsj,$iarea){
        return $this->db->query("
            SELECT
                b.i_customer,
                b.e_customer_name
            FROM
                tm_nota a
            INNER JOIN tr_customer b ON
                b.i_customer = a.i_customer
            WHERE
                a.i_sj = '$isj'
                AND a.d_sj = '$dsj'
                AND a.i_area = '$iarea'
        ");
    }   

    public function cekperiode(){
        return $this->db->query("SELECT i_periode FROM tm_periode ",false);
    }
}

/* End of file Mmaster.php */
