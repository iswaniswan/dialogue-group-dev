<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function runningnumberid($thbl)
    {
        $th     = substr($thbl,0,4);
        $asal   = $thbl;
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $dep    = $this->session->userdata('i_lokasi');
        $query  = $this->db->query("
            SELECT
                n_modul_no AS max
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'SJ'
                AND i_area = '$dep'
                AND e_periode = '$asal'
                AND substring(e_periode, 1, 4)= '$th'
        ", false);
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $id  = $terakhir+1;
            settype($id,"string");
            $a=strlen($id);
            while($a<5){
                $id = "0".$id;
                $a = strlen($id);
            }
            $id  = "SJ-".$dep."-".$thbl."-".$id;
            return $id;
        }else{
            $id  = "00001";
            $id  = "SJ-".$dep."-".$thbl."-".$id;
            return $id;
        }
    }

    public function runningnumber($thbl)
    {
        $th     = substr($thbl,0,4);
        $asal   = $thbl;
        $thbl   = substr($thbl,2,2).substr($thbl,4,2);
        $dep    = $this->session->userdata('i_lokasi');
        $query  = $this->db->query("
            SELECT
                n_modul_no AS max
            FROM
                tm_dgu_no
            WHERE
                i_modul = 'SJ'
                AND i_area = '$dep'
                AND e_periode = '$asal'
                AND substring(e_periode, 1, 4)= '$th' FOR
            UPDATE
        ", false);
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $id  = $terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $id
                WHERE
                    i_modul = 'SJ'
                    AND e_periode = '$asal'
                    AND i_area = '$dep'
                    AND substring(e_periode, 1, 4)= '$th'
            ", false);
            settype($id,"string");
            $a=strlen($id);
            while($a<5){
                $id = "0".$id;
                $a = strlen($id);
            }
            $id  = "SJ-".$dep."-".$thbl."-".$id;
            return $id;
        }else{
            $id  = "00001";
            $id  = "SJ-".$dep."-".$thbl."-".$id;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                VALUES ('SJ', '$dep', '$asal', 1)
            ");
            return $id;
        }
    }

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

    function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE d_sj >= '$dfrom' AND d_sj <= '$dto'";
        }else{
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "SELECT
                i_sj,
                to_char(d_sj, 'dd-mm-yyyy') AS d_sj,
                e_from,
                e_remark,
                a.i_status,
                e_status,
                label_color,
                '$i_menu' AS i_menu,
                '$folder' AS folder
            FROM
                tm_sj_masuk_pengadaan a
            INNER JOIN tm_status_dokumen b ON
                (a.i_status = b.i_status)
            $where", FALSE
        );
        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id            = trim($data['i_sj']);
            $i_status      = trim($data['i_status']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $ilevel        = trim($this->session->userdata('i_level'));
            $data          = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
                if ($ilevel <= 6 && $i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6' && $i_status!='9')) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status');
        return $datatables->generate();
    }

    public function product($cari){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("            
            SELECT
                DISTINCT i_kodebrg AS id,
                e_namabrg AS nama,
                b.i_color,
                c.e_color_name
            FROM
                tm_barang_wip a
            INNER JOIN tr_polacutting b ON
                (b.i_product = a.i_kodebrg)
            INNER JOIN tr_color c ON
                (c.i_color = b.i_color)
            WHERE
                (upper(i_kodebrg) LIKE '%$cari%'
                OR upper(e_namabrg) LIKE '%$cari%') 
        ", FALSE);
    }

    public function getproduct($iproduct){
        return $this->db->query("            
            SELECT
                DISTINCT i_kodebrg AS id,
                e_namabrg AS nama,
                b.i_color,
                c.e_color_name
            FROM
                tm_barang_wip a
            INNER JOIN tr_polacutting b ON
                (b.i_product = a.i_kodebrg)
            INNER JOIN tr_color c ON
                (c.i_color = b.i_color)
            WHERE
                i_kodebrg = '$iproduct'
        ", FALSE);
    }

    public function insertheader($id,$tanggal,$idepartemen,$pengirim,$eremark)
    {
        $data = array(
            'i_sj'          => $id,
            'd_sj'          => $tanggal,
            'i_departement' => $idepartemen,
            'e_from'        => $pengirim,
            'e_remark'      => $eremark,
            'd_entry'       => current_datetime(),
            'i_status'      => 1,
        );
        $this->db->insert('tm_sj_masuk_pengadaan', $data);
    }

    public function insertdetail($id,$iwip,$eproductname,$icolor,$enote,$qty,$x)
    {
        $data = array(
            'i_sj'           => $id,
            'i_wip'          => $iwip,
            'e_product_name' => $eproductname,
            'i_color'        => $icolor,
            'e_remark'       => $enote,
            'n_quantity'     => $qty,
            'n_sisa'         => $qty,
            'n_item_no'      => $x,
        );
        $this->db->insert('tm_sj_masuk_pengadaan_item', $data);
    }

    public function changestatus($id,$istatus){
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'i_approve' => $iapprove,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('i_sj', $id);
        $this->db->update('tm_sj_masuk_pengadaan', $data);
    }

    public function dataheader($id)
    {
        $this->db->select("*");
        $this->db->from('tm_sj_masuk_pengadaan');
        $this->db->where('i_sj ', $id);
        return $this->db->get();
    }

    public function datadetail($id)
    {
        $this->db->select("a.*, e_color_name");
        $this->db->from("tm_sj_masuk_pengadaan_item a");
        $this->db->join("tr_color b","b.i_color = a.i_color","inner");
        $this->db->where("i_sj",$id);
        return $this->db->get();
    }

    public function updateheader($id,$tanggal,$idepartemen,$pengirim,$eremark)
    {
        $data = array(
            'd_sj'          => $tanggal,
            'i_departement' => $idepartemen,
            'e_from'        => $pengirim,
            'e_remark'      => $eremark,
            'd_update'      => current_datetime(),
        );
        $this->db->where('i_sj',$id);
        $this->db->update('tm_sj_masuk_pengadaan', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('i_sj',$id);
        $this->db->delete('tm_sj_masuk_pengadaan_item');
    }
}

/* End of file Mmaster.php */
?>
