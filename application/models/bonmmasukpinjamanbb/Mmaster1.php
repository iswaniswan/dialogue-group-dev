<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public function data($folder,$i_menu,$dfrom,$dto){
        $dfrom = date('Y-m-d', strtotime($dfrom));
        $dto   = date('Y-m-d', strtotime($dto));
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                i_bonmk AS id,
                to_char(d_bonmk, 'dd-mm-yyyy') AS d_bonmk,
                a.i_kode_master,
                CASE
                WHEN e_sub_bagian IS NULL AND e_supplier_name IS NULL THEN e_nama_karyawan
                WHEN e_sub_bagian IS NULL AND e_nama_karyawan IS NULL THEN e_supplier_name
                ELSE e_sub_bagian
                END AS departemen,
                e_remark,
                f_cancel,
                a.i_status,
                g.e_status,
                label_color,
                d_approve1,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_bonmmasuk_pinjamanbj a
            INNER JOIN tm_status_dokumen g ON
                (g.i_status = a.i_status)
            LEFT JOIN tm_sub_bagian d ON
                (d.i_sub_bagian = a.i_departement)
            LEFT JOIN tr_supplier e ON
                (e.i_supplier = a.i_departement)
            LEFT JOIN tm_karyawan f ON
                (f.i_karyawan = a.i_departement)
            WHERE
                d_bonmk >= '$dfrom'
                AND d_bonmk <= '$dto'
        ", FALSE);

        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id            = trim($data['id']);
            $f_cancel      = trim($data['f_cancel']);
            $d_approve     = trim($data['d_approve1']);
            $i_status      = trim($data['i_status']);
            $ibagian       = trim($data['i_kode_master']);
            $i_menu        = $data['i_menu'];
            $folder        = $data['folder'];
            $dfrom         = $data['dfrom'];
            $dto           = $data['dto'];
            $idepartemen   = trim($this->session->userdata('i_departement'));
            $ilevel        = trim($this->session->userdata('i_level'));
            $data          = '';
            $data         .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            if ($d_approve == "" || $d_approve == null) {
                if ($f_cancel!='t' && check_role($i_menu, 3)) {
                    if (($i_status == '1' || $i_status == '3' || $i_status == '7') && $f_cancel!='t') {
                        $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                    }elseif($i_status == '2' && $f_cancel!='t'){
                        $data         .= "<a href=\"#\" title='Batal Kirim' onclick='batalkirim(\"$id\",\"$ibagian\",\"1\"); return false;'><i class='ti-reload'></i></a>&nbsp;&nbsp;&nbsp;";
                    }

                    if ((($idepartemen == '19' && $ilevel == 6) || ($idepartemen == '1' && $ilevel == 1)) && $i_status == '2' && $f_cancel!='t') {
                        $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                    }
                }
                if ($f_cancel!='t' && check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6')) {
                    $data .= "<a href=\"#\" title='Cancel' onclick='cancel(\"$id\",\"$ibagian\"); return false;'><i class='ti-close'></i></a>";
                }
            }
            return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_kode_master');
        $datatables->hide('d_approve1');
        $datatables->hide('f_cancel');
        $datatables->hide('label_color');
        $datatables->hide('e_status');
        return $datatables->generate();
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

    public function departemen($cari)
    {
        $i_kode_lokasi = $this->session->userdata('i_sub_bagian');
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                *
            FROM
                (
                SELECT
                    i_sub_bagian AS id, e_sub_bagian AS name
                FROM
                    tm_sub_bagian
                WHERE i_sub_bagian <> '$i_kode_lokasi'
            UNION ALL
                SELECT
                    i_karyawan AS id, e_nama_karyawan AS name
                FROM
                    tm_karyawan
            UNION ALL
                SELECT
                    i_supplier AS id, e_supplier_name AS name
                FROM
                    tr_supplier    
                    ) AS x
            WHERE
                (UPPER(name) LIKE '%$cari%' 
                OR TRIM(id) LIKE '%$cari%')
            ORDER BY name
        ", FALSE);
    }

    public function referensi($cari,$idepartemen)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT
                a.i_bonmk AS id,
                to_char(a.d_bonmk, 'dd-mm-yyyy') AS d_bonmk
            FROM
                tm_bonmkeluar_pinjamanbj a
            INNER JOIN tm_bonmkeluar_pinjamanbj_detail b ON
                (a.i_bonmk = b.i_bonmk
                AND a.i_kode_master = b.i_kode_master)
            INNER JOIN tr_color l ON
                (l.i_color = b.i_color)
            WHERE
                a.f_cancel = 'f'
                AND a.i_status = '6'
                AND COALESCE(b.n_qty_sisa, 0) > 0
                AND a.i_departement = '$idepartemen'
                AND (TRIM(a.i_bonmk) LIKE '$cari%')
        ", FALSE);
    }

    public function getdetailreferensi($id,$idepartemen)
    {
        return $this->db->query("
            SELECT
                b.*,
                e_color_name,
                COALESCE(b.n_qty_sisa, 0) AS sisa
            FROM
                tm_bonmkeluar_pinjamanbj a
            INNER JOIN tm_bonmkeluar_pinjamanbj_detail b ON
                (a.i_bonmk = b.i_bonmk
                AND a.i_kode_master = b.i_kode_master)
            INNER JOIN tr_color l ON
                (l.i_color = b.i_color)
            WHERE
                a.f_cancel = 'f'
                AND a.i_status = '6'
                AND COALESCE(b.n_qty_sisa, 0) > 0
                AND a.i_departement = '$idepartemen'
                AND TRIM(a.i_bonmk) = '$id'
        ", FALSE);
    }

    public function runningnumber($thbl){
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
                i_modul = 'DOK'
                AND i_area = '$dep'
                AND e_periode = '$asal'
                AND substring(e_periode, 1, 4)= '$th' FOR
            UPDATE
        ", false);
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir=$row->max;
            }
            $nobonmk  = $terakhir+1;
            $this->db->query("
                UPDATE
                    tm_dgu_no
                SET
                    n_modul_no = $nobonmk
                WHERE
                    i_modul = 'DOK'
                    AND e_periode = '$asal'
                    AND i_area = '$dep'
                    AND substring(e_periode, 1, 4)= '$th'
            ", false);
            settype($nobonmk,"string");
            $a=strlen($nobonmk);
            while($a<5){
                $nobonmk="0".$nobonmk;
                $a=strlen($nobonmk);
            }
            $nobonmk  ="DOK-".$dep."-".$thbl."-".$nobonmk;
            return $nobonmk;
        }else{
            $nobonmk  ="00001";
            $nobonmk  ="DOK-".$dep."-".$thbl."-".$nobonmk;
            $this->db->query("
                INSERT
                    INTO
                    tm_dgu_no(i_modul, i_area, e_periode, n_modul_no)
                VALUES ('DOK', '$dep', '$asal', 1)
            ");
            return $nobonmk;
        }
    }

    public function insertheader($id,$ikodemaster,$datebonk,$idepartemen,$remark,$ireferensi,$dreferensi)
    {
        $dentry = current_datetime();
        $data = array(
            'i_bonmk'         => $id,
            'd_bonmk'         => $datebonk,
            'i_kode_master'   => $ikodemaster,
            'i_departement'   => $idepartemen,
            'i_referensi'     => $ireferensi,
            'd_referensi'     => $dreferensi,
            'e_remark'        => $remark,
            'd_entry'         => $dentry,
        );
        $this->db->insert('tm_bonmmasuk_pinjamanbj', $data);
    }

    public function baca($id,$ibagian){
        $query = $this->db->query("
            SELECT
                a.*,
                to_char(d_bonmk, 'dd-mm-yyyy') AS dbonk,
                to_char(d_referensi, 'dd-mm-yyyy') AS dreferensi,
                c.e_departement_name, 
                CASE
                    WHEN d.e_sub_bagian IS NULL
                    AND e_supplier_name IS NULL THEN f.e_nama_karyawan
                    WHEN d.e_sub_bagian IS NULL
                    AND f.e_nama_karyawan IS NULL THEN e_supplier_name
                    ELSE d.e_sub_bagian
                END AS departemen,
                e_remark,
                f_cancel,
                a.i_status,
                d_approve1
            FROM
                tm_bonmmasuk_pinjamanbj a
            INNER JOIN tm_status_dokumen g ON
                (g.i_status = a.i_status)
            INNER JOIN public.tr_departement c ON
                (c.i_departement = i_kode_master)
            LEFT JOIN tm_sub_bagian d ON
                (d.i_sub_bagian = a.i_departement)
            LEFT JOIN tr_supplier e ON
                (e.i_supplier = a.i_departement)
            LEFT JOIN tm_karyawan f ON
                (f.i_karyawan = a.i_departement)
            WHERE
                i_bonmk = '$id'
                AND a.i_kode_master = '$ibagian'
        ", FALSE);
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    }

    public function bacadetail($id){
        $query = $this->db->query("
            SELECT
                a.*,
                b.e_color_name,
                c.n_qty_sisa,
                c.n_qty AS n_qty_asal
            FROM
                tm_bonmmasuk_pinjamanbj_detail a
            INNER JOIN tr_color b ON
                (a.i_color = b.i_color)
            INNER JOIN tm_bonmkeluar_pinjamanbj_detail c ON
                (c.i_product = a.i_product
                AND a.i_referensi = c.i_bonmk)
            WHERE
                a.i_bonmk = '$id'
            ORDER BY
                i_no_item
        ", FALSE);
        if ($query->num_rows() > 0) {
            return $query->result();
        }
    }

    public function cancelitem($ibonk,$icolor,$iproduct){
        $this->db->where('i_bonmk', $ibonk);
        $this->db->where('i_product', $iproduct);
        $this->db->where('i_color', $icolor);
        return $this->db->delete('tm_bonmmasuk_pinjamanbj_detail');
    }

    public function updatestatus($ibonk,$status,$ibagian){
        $dentry = current_datetime();
        if ($status=='6') {
            $query = $this->db->query("
                SELECT
                    *
                FROM
                    tm_bonmmasuk_pinjamanbj_detail
                WHERE
                    i_bonmk = '$ibonk'
            ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $this->db->query("
                        UPDATE
                            tm_bonmkeluar_pinjamanbj_detail
                        SET
                            n_qty_sisa = n_qty_sisa - '$key->n_qty'
                        WHERE
                            i_bonmk = '$key->i_referensi'
                            AND i_product = '$key->i_product'
                    ", FALSE);
                }
            }
            $data = array(
                'i_status'   => $status,
                'i_approve1' => $this->session->userdata('username'),
                'd_approve1' => $dentry,
            );
        }else{
            $data = array(
                'i_status' => $status,
            );
        }
        $this->db->where('i_bonmk', $ibonk);
        $this->db->where('i_kode_master', $ibagian);
        $this->db->update('tm_bonmmasuk_pinjamanbj', $data);
    }

    public function update($id,$ikodemaster,$datebonk,$idepartemen,$remark,$ireferensi,$dreferensi,$ikodemasterold)
    {
        $dentry = current_datetime();
        $data = array(
            'd_bonmk'         => $datebonk,
            'i_kode_master'   => $ikodemaster,
            'i_departement'   => $idepartemen,
            'i_referensi'     => $ireferensi,
            'd_referensi'     => $dreferensi,
            'e_remark'        => $remark,
            'd_update'        => $dentry,
        );
        $this->db->where('i_bonmk',$id);
        $this->db->where('i_kode_master',$ikodemasterold);
        $this->db->update('tm_bonmmasuk_pinjamanbj', $data);
    }

    public function deletedetail($ibonk){   
        $this->db->where('i_bonmk',$ibonk);
        $this->db->delete('tm_bonmmasuk_pinjamanbj_detail');
    }

    public function insertdetail($id,$iproduct,$eproductname,$nquantity,$edesc,$x,$icolor,$ireferensi)
    {
        $data = array(
            'i_bonmk'         => $id,
            'i_product'       => $iproduct,
            'e_product_name'  => $eproductname,
            'n_qty'           => $nquantity,
            'e_remark'        => $edesc,
            'i_no_item'       => $x,
            'i_color'         => $icolor,
            'i_referensi'     => $ireferensi,
        );
        $this->db->insert('tm_bonmmasuk_pinjamanbj_detail', $data);
    }

    public function cancel($bonmkp, $ibagian){
        $data = array(
            'f_cancel' => 't',
        );
        $this->db->where('i_bonmk', $bonmkp);
        $this->db->where('i_kode_master', $ibagian);
        $this->db->update('tm_bonmmasuk_pinjamanbj', $data);
    }
}

/* End of file Mmaster.php */
