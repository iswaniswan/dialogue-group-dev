<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

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
}

/* End of file Mmaster.php */
