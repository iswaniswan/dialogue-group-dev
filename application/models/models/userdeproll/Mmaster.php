<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu,$folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS no,
                x.i_departement,
                x.e_departement_name,
                x.i_level,
                x.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder
            FROM
                (
                SELECT
                    DISTINCT ON
                    (a.i_departement,
                    a.i_level) a.i_departement, b.e_departement_name, a.i_level, c.e_level_name
                FROM
                    public.tm_user_role a
                INNER JOIN public.tr_departement b ON
                    a.i_departement = b.i_departement
                INNER JOIN public.tr_level c ON
                    a.i_level = c.i_level
                WHERE
                    a.i_apps = '".$this->session->userdata('i_apps')."'
                    AND b.f_status = 't'
                    ) AS x
                ORDER BY e_departement_name, e_level_name ASC
        ", false);

        $datatables->add('action', function ($data) {
            $ideptartement  = trim($data['i_departement']);
            $ilevel         = trim($data['i_level']);
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $data           = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$ideptartement/$ilevel\",\"#main\"); return false;'><i class='ti-eye'></i></a>";
            }            
            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('i_level');
        $datatables->hide('i_departement');
        return $datatables->generate();
    }

    public function bacadepart()
    {
        return $this->db->query("
            SELECT
                DISTINCT a.i_departement::int,
                a.e_departement_name
            FROM
                public.tr_departement a
            LEFT JOIN public.tm_user_deprole b ON
                a.i_departement = b.i_departement
            WHERE a.f_status = 't'
                AND a.i_departement <> '1'
            ORDER BY
                e_departement_name
        ",false);
    }

    public function bacapower()
    {
        return $this->db->query("
            SELECT
                id::varchar AS id,
                e_name
            FROM
                public.tm_user_power
            ORDER BY
                n_urut
        ",false);
    }

    public function bacalevel($cari,$ideptartement)
    {
        return $this->db->query(" 
            SELECT
                DISTINCT a.i_level::int AS i_level,
                a.e_level_name
            FROM
                public.tr_level a
            LEFT JOIN public.tm_user_deprole b ON
                a.i_level = b.i_level 
            WHERE
                /*b.i_departement = '$ideptartement'
                AND*/ e_level_name ILIKE '%$cari%'
                AND a.f_status = 't'
                AND a.i_level <> '1'
            ORDER BY
                e_level_name ",false);
    }

    public function bacamenu($cari)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.i_menu,
                a.e_menu ,
                length(a.i_menu) AS jumlah
            FROM
                public.tm_menu a
            INNER JOIN public.tm_user_role b ON
                a.i_menu = b.i_menu
            WHERE
                length(a.i_menu) <= 3
                AND b.i_apps = '2' 
                AND e_menu ILIKE '%$cari%' and a.f_status = true
            ORDER BY
                a.i_menu
        ",false);
    }

    public function bacamenusub($cari,$i_menu)
    {
        return $this->db->query("
            SELECT
                * 
            FROM public.tm_menu 
            WHERE i_parent = '$i_menu' 
            AND e_menu ILIKE '%$cari%' and f_status = true
        ORDER BY i_menu
        ",false);
    }

    public function getmenu($idep,$ilev,$imenu,$isubmenu)
    {
        if ($isubmenu != '' || $isubmenu != null) {
                $and = "AND b.i_parent = '$isubmenu' ";
            /*if ((strlen($isubmenu) > 4)) {
            }else{
                $and = "AND b.i_menu ILIKE '$isubmenu%'";
            }*/
        }else{
            $and = "AND b.i_parent = '$imenu'";
        }
        $iapps = $this->session->userdata('i_apps');
        return $this->db->query("
            SELECT
                x.i_menu,
                x.e_menu,
                y.id,
                x.id AS idmis
            FROM
                (
                SELECT
                    a.i_menu, e_menu, i_apps, string_agg(id_user_power::varchar, ', ') AS id
                FROM
                    public.tm_user_role a
                INNER JOIN public.tm_menu b ON
                    (b.i_menu = a.i_menu)
                INNER JOIN public.tm_user_power c ON
                    (c.id = a.id_user_power)
                WHERE
                    a.i_level = '1'
                    AND a.i_departement = '1'
                    AND a.i_apps = '$iapps' and b.f_status = true
                    $and
                GROUP BY
                    1, 2, 3
                ORDER BY
                    a.i_menu) AS x
            LEFT JOIN (
                SELECT
                    a.i_menu, e_menu, i_apps, string_agg(id_user_power::varchar, ', ') AS id
                FROM
                    public.tm_user_role a
                INNER JOIN public.tm_menu b ON
                    (b.i_menu = a.i_menu)
                INNER JOIN public.tm_user_power c ON
                    (c.id = a.id_user_power)
                WHERE
                    a.i_level = '$ilev'
                    AND a.i_departement = '$idep'
                    AND a.i_apps = '$iapps' and b.f_status = true
                    $and
                GROUP BY
                    1, 2, 3
                ORDER BY
                    a.i_menu) y ON
                (y.i_menu = x.i_menu
                AND y.i_apps = x.i_apps)
        ", FALSE);
    }

    public function delete($menu,$idept,$ilevel)
    {
        $this->db->query("
            DELETE 
                FROM public.tm_user_role
                WHERE i_menu ILIKE '$menu%'
                    AND i_apps = '2'
                    AND i_departement = '$idept'
                    AND i_level = '$ilevel'
        ", FALSE);
    }

    public function insertheader($imenu,$ipower,$idept,$ilevel)
    {
        $this->db->query("
            INSERT
                INTO
                public.tm_user_role (i_menu, id_user_power, i_departement, i_level, i_apps)
            VALUES ('$imenu', '$ipower', '$idept', '$ilevel', '2') 
            ON
                CONFLICT (i_menu, id_user_power, i_departement, i_level) DO
            UPDATE
                SET
                    i_menu = excluded.i_menu,
                    id_user_power = excluded.id_user_power,
                    i_departement = excluded.i_departement,
                    i_level = excluded.i_level
        ", FALSE);
    }

    public function insertdetail($menu,$ipower,$idept,$ilevel)
    {
        $this->db->query("
            INSERT
                INTO
                public.tm_user_role (i_menu, id_user_power, i_departement, i_level, i_apps)
            VALUES ('$menu', '$ipower', '$idept', '$ilevel', '2') 
            ON
                CONFLICT (i_menu, id_user_power, i_departement, i_level) DO
            UPDATE
                SET
                    i_menu = excluded.i_menu,
                    id_user_power = excluded.id_user_power,
                    i_departement = excluded.i_departement,
                    i_level = excluded.i_level
        ", FALSE);
    }

    public function deletedetail($menu,$ipower,$idept,$ilevel)
    {
        $this->db->query("
            DELETE 
                FROM public.tm_user_role
                WHERE i_menu = '$menu'
                    AND i_apps = '2'
                    AND i_departement = '$idept'
                    AND i_level = '$ilevel'
                    and id_user_power = '$ipower';
        ", FALSE);
    }

    public function bacadata($ideptartement,$ilevel)
    {
        $iapps = $this->session->userdata('i_apps');
        return $this->db->query("
            SELECT
                x.i_menu,
                x.e_menu,
                y.id,
                x.id AS idmis
            FROM
                (
                SELECT
                    a.i_menu, e_menu, i_apps, string_agg(id_user_power::varchar, ', ') AS id
                FROM
                    public.tm_user_role a
                INNER JOIN public.tm_menu b ON
                    (b.i_menu = a.i_menu)
                INNER JOIN public.tm_user_power c ON
                    (c.id = a.id_user_power)
                WHERE
                    a.i_level = '1'
                    AND a.i_departement = '1'
                    AND a.i_apps = '$iapps' and b.f_status = true
                GROUP BY
                    1, 2, 3
                ORDER BY
                    a.i_menu) AS x
            INNER JOIN (
                SELECT
                    a.i_menu, e_menu, i_apps, string_agg(id_user_power::varchar, ', ') AS id
                FROM
                    public.tm_user_role a
                INNER JOIN public.tm_menu b ON
                    (b.i_menu = a.i_menu)
                INNER JOIN public.tm_user_power c ON
                    (c.id = a.id_user_power)
                WHERE
                    a.i_level = '$ilevel'
                    AND a.i_departement = '$ideptartement'
                    AND a.i_apps = '$iapps' and b.f_status = true
                GROUP BY
                    1, 2, 3
                ORDER BY
                    a.i_menu) y ON
                (y.i_menu = x.i_menu
                AND y.i_apps = x.i_apps)
        ", FALSE);
    }

    public function baca($ideptartement,$ilevel)
    {
        return $this->db->query("
            SELECT 
                e_departement_name,
                e_level_name
            FROM 
                public.tm_user_role a
            INNER JOIN public.tr_departement b ON
                (b.i_departement = a.i_departement)
            INNER JOIN public.tr_level c ON
                (c.i_level = a.i_level)
            WHERE a.i_departement = '$ideptartement'
                AND a.i_level = '$ilevel'
        ", FALSE);
    }
}
/* End of file Mmaster.php */