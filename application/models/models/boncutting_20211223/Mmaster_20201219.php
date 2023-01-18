<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$folder,$dfrom, $dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS no,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                c.i_document AS i_schedule,
                d.i_document AS i_refference,
                a.id_refference,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_cutting a
            INNER JOIN tr_status_document b ON
                (b.i_status = a.i_status)
            INNER JOIN tm_schedule c ON
                (c.id = a.id_reff)
            LEFT JOIN tm_keluar_cutting d ON 
                (a.id_refference = d.id)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '".$this->session->userdata('id_company')."'
                $and
            ORDER BY
                a.id ");

        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = $data['id'];
            $idreff   = $data['id_refference'];
            $i_status = trim($data['i_status']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/$idreff\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('id_refference');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function gudang()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_cutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl,$tahun,$ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_keluar_cutting 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BBK';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_keluar_cutting
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 6){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "000001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    public function tujuan($i_menu)
    {
        $this->db->select('a.i_bagian, e_bagian_name');
        $this->db->from('tr_tujuan_menu a');
        $this->db->join('tr_bagian b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_menu', $i_menu);
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        return $this->db->get();
    }

    /****** UNTUK KELUAR BARU ******/

    public function datareferensi($cari)
    {
        return $this->db->query(
            "
            SELECT 
                DISTINCT
                a.id,
                i_document
            FROM
                tm_schedule a
            INNER JOIN tm_schedule_item b ON
                (b.id_schedule = a.id)
            WHERE
                i_status = '6'
                AND b.n_quantity_sisa_bon > 0
                AND i_document ILIKE '%$cari%'
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY a.id
            ",
            FALSE
        );
    }

    public function dataheader($id)
    {
        return $this->db->query(
            "
            SELECT
                to_char(d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_schedule
            WHERE
                id = '$id'
            ",
            FALSE
        );
    }

    public function datadetail($id)
    {
        return $this->db->query("
            SELECT
                b.id AS id_product_wip,
                a.i_product_wip,
                e_product_wipname,
                d.id AS id_material,
                c.i_material,
                e_material_name,
                n_quantity_sisa_bon AS qty,
                e_color_name,
                e_satuan_name,
                c.v_set,
                c.v_toset
            FROM
                tm_schedule_item a
            INNER JOIN tr_product_wip b ON
                (b.i_product_wip = a.i_product_wip
                AND a.i_color = b.i_color)
            INNER JOIN tr_polacutting c ON
                (c.i_product_wip = b.i_product_wip
                AND b.i_color = c.i_color
                AND c.id_company = b.id_company)
            INNER JOIN tr_material d ON
                (d.i_material = c.i_material
                AND c.id_company = d.id_company)
            INNER JOIN tr_color e ON
                (e.i_color = b.i_color
                AND b.id_company = e.id_company)
            INNER JOIN tr_satuan f ON
                (f.i_satuan_code = d.i_satuan_code
                AND d.id_company = f.id_company)
            WHERE
                a.id_schedule = '$id'
            ORDER BY
                b.id,
                a.i_product_wip,
                d.i_material ASC
        ", FALSE);
    }

    /********** END KELUAR BARU *********/

    /****** UNTUK KELUAR PENDINGAN ******/

    public function numberpending($id)
    {
        $ibonk = $this->db->query("
            SELECT 
                substring(i_document, 1, 15) AS i_document
            FROM 
                tm_keluar_cutting 
            WHERE id = '$id' ", FALSE)->row()->i_document;
        $query = $this->db->query("
            SELECT
                RIGHT(max, 2)::NUMERIC AS max
            FROM
                (
                SELECT
                    max(substring(i_document, 1, 18)) AS max
                FROM
                    tm_keluar_cutting
                WHERE
                    i_document LIKE '%$ibonk%' ) AS a
            WHERE
                length(max) = '18'
        ", FALSE);
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                $terakhir = $row->max;
            }
            $nobonmk = $terakhir+1;
            settype($nobonmk,"string");
            $a = strlen($nobonmk);

            while($a < 2){
                $nobonmk="0".$nobonmk;
                $a=strlen($nobonmk);
            }
            $nobonmk  = $ibonk."-".$nobonmk;
            return $nobonmk;
        }else{
            $nobonmk  ="-01";
            $nobonmk  = $ibonk.$nobonmk;
            return $nobonmk;
        }
    }

    public function datareferensip($cari)
    {
        return $this->db->query(
            "
            SELECT
                DISTINCT a.id,
                a.i_document
            FROM
                tm_keluar_cutting a
            INNER JOIN tm_keluar_cutting_item b ON
                (b.id_document = a.id)
            WHERE
                a.i_status = '6'
                AND n_quantity_reff_sisa <> n_quantity_reff
                AND i_document ILIKE '%$cari%'
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY a.id
            ",
            FALSE
        );
    }

    public function dataheaderp($id)
    {
        return $this->db->query(
            "
            SELECT
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                id_reff
            FROM
                tm_keluar_cutting
            WHERE
                id = '$id'
            ",
            FALSE
        );
    }

    public function datadetailp($id)
    {
        return $this->db->query("
            SELECT
                a.id_product_wip,
                b.i_product_wip,
                e_product_wipname,
                a.id_material,
                c.i_material,
                e_material_name,
                a.n_quantity_wip AS qty,
                e_color_name,
                e_satuan_name,
                n_quantity_reff - n_quantity_reff_sisa AS jmlset
            FROM
                tm_keluar_cutting_item a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            INNER JOIN tr_material c ON
                (c.id = a.id_material)
            INNER JOIN tr_color d ON
                (d.i_color = b.i_color
                AND b.id_company = d.id_company)
            INNER JOIN tr_satuan e ON
                (e.i_satuan_code = c.i_satuan_code
                AND c.id_company = e.id_company)
            INNER JOIN tr_polacutting f ON
                (f.i_material = c.i_material
                AND c.id_company = f.id_company
                AND b.i_product_wip = f.i_product_wip
                AND f.i_color = b.i_color
                AND b.id_company = f.id_company)
            WHERE
                id_document = '$id'
                AND n_quantity_reff_sisa <> n_quantity_reff
            ORDER BY
                a.id_product_wip,
                b.i_product_wip,
                c.i_material ASC
        ", FALSE);
    }

    /****** END KELUAR PENDINGAN ******/

    /***** UPDATE STATUS DOKUMENT & REFERENSI *****/

    public function changestatus($id,$istatus)
    {
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $refference = $this->db->query("SELECT COALESCE (id_refference,0) AS id_refference FROM tm_keluar_cutting WHERE id = '$id' ", fALSE)->row()->id_refference;
            if ($refference == 0) {
                $query = $this->db->query("
                    SELECT
                        DISTINCT id_reff,
                        b.i_product_wip,
                        b.i_color,
                        a.n_quantity_wip,
                        a.id_company
                    FROM
                        tm_keluar_cutting_item a
                    INNER JOIN tr_product_wip b ON
                        (b.id = a.id_product_wip)
                    WHERE
                        a.id_document = '$id'
                    ORDER BY
                        b.i_product_wip
                ", FALSE);
                if ($query->num_rows()>0) {
                    foreach ($query->result() as $key) {
                        $this->db->query("
                            UPDATE
                                tm_schedule_item
                            SET
                                n_quantity_sisa_bon = n_quantity_sisa_bon - $key->n_quantity_wip
                            WHERE
                                id_schedule = '$key->id_reff'
                                AND i_product_wip = '$key->i_product_wip'
                                AND i_color = '$key->i_color'
                                AND id_company = '$key->id_company'
                        ", FALSE);
                    }
                }
            }else{
                $query = $this->db->query("
                    SELECT
                        id_refference AS id_document,
                        n_quantity,
                        id_product_wip,
                        id_material,
                        a.id_company
                    FROM
                        tm_keluar_cutting_item a
                    INNER JOIN tm_keluar_cutting b ON
                        (b.id = a.id_document)
                    WHERE
                        b.id = '$id'
                ", FALSE);
                if ($query->num_rows()>0) {
                    foreach ($query->result() as $key) {
                        $this->db->query("
                            UPDATE
                                tm_keluar_cutting_item
                            SET
                                n_quantity_reff_sisa = n_quantity_reff_sisa + $key->n_quantity
                            WHERE
                                id_document = '$key->id_document'
                                AND id_product_wip = '$key->id_product_wip'
                                AND id_material = '$key->id_material'
                                AND id_company = '$key->id_company'
                        ", FALSE);
                    }
                }
            }
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
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_cutting', $data);
    }

    /******* END STATUS DOKUMENT ******/

    /************ SIMPAN DATA *********/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_cutting');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$itujuan,$idreff,$eremark,$irefference)
    {
        $data = array(
            'id'              => $id,
            'id_company'      => $this->session->userdata('id_company'),
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'i_bagian_tujuan' => $itujuan,
            'id_reff'         => $idreff,
            'e_remark'        => $eremark,
            'id_refference'   => $irefference,
            'd_entry'         => current_datetime(),
        );
        $this->db->insert('tm_keluar_cutting', $data);
    }

    public function simpandetail($id,$idreff,$idproductwip,$qtywip,$idmaterial,$qtyreff,$qty,$eremark)
    {
        $data = array(
            'id_document'           => $id,
            'id_reff'               => $idreff,
            'id_product_wip'        => $idproductwip,
            'n_quantity_wip'        => $qtywip,
            'n_quantity_wip_sisa'   => $qtywip,
            'id_material'           => $idmaterial,
            'n_quantity'            => $qty,
            'n_quantity_sisa'       => $qty,
            'e_remark'              => $eremark,
            'id_company'            => $this->session->userdata('id_company'),
            'n_quantity_reff'       => $qtyreff,
            'n_quantity_reff_sisa'  => $qty,
        );
        $this->db->insert('tm_keluar_cutting_item', $data);
    }

    /**************** END SIMPAN DATA ******************/

    /******************* EDIT DATA *******************/

    public function dataedit($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.id,
                a.i_bagian,
                b.e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.id_reff,
                c.i_document AS i_schedule,
                to_char(c.d_document, 'dd-mm-yyyy') AS d_schedule,
                a.i_bagian_tujuan,
                d.e_bagian_name AS e_bagian_tujuan,
                a.e_remark,
                a.i_status,
                a.id_refference,
                to_char(k.d_document, 'dd-mm-yyyy') AS d_refference,
                k.i_document AS i_refference
            FROM
                tm_keluar_cutting a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tm_schedule c ON
                (c.id = a.id_reff)
            INNER JOIN tr_bagian d ON
                (d.i_bagian = a.i_bagian_tujuan
                AND a.id_company = b.id_company)
            LEFT JOIN tm_keluar_cutting k ON
                (k.id = a.id_refference)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    public function dataeditdetail($id)
    {
        return $this->db->query("
            SELECT DISTINCT
                a.id_product_wip,
                b.i_product_wip,
                b.e_product_wipname,
                e_color_name,
                a.id_material,
                d.i_material,
                d.e_material_name,
                e.e_satuan_name,
                a.e_remark,
                a.n_quantity_wip AS qty,
                a.n_quantity_reff,
                a.n_quantity_reff_sisa,
                v_set,
                v_toset,
                n_quantity_sisa_bon,
                COALESCE (k.n_quantity_reff,
                0) AS qtyreff,
                COALESCE (k.n_quantity_reff_sisa,
                0) AS qtyreffsisa
            FROM
                tm_keluar_cutting_item a
            INNER JOIN tr_product_wip b ON
                (b.id = a.id_product_wip)
            INNER JOIN tr_color c ON
                (c.i_color = b.i_color
                AND b.id_company = c.id_company)
            INNER JOIN tr_material d ON
                (d.id = a.id_material)
            INNER JOIN tr_satuan e ON
                (e.i_satuan_code = d.i_satuan_code
                AND d.id_company = e.id_company)
            INNER JOIN tr_polacutting f ON
                (f.i_material = d.i_material
                AND d.id_company = f.id_company
                AND b.i_product_wip = f.i_product_wip
                AND f.i_color = b.i_color
                AND b.id_company = f.id_company)
            INNER JOIN tm_schedule_item g ON
                (g.id_schedule = a.id_reff
                AND a.id_company = g.id_company
                AND g.i_product_wip = b.i_product_wip
                AND b.i_color = g.i_color)
            LEFT JOIN tm_keluar_cutting h ON
                (h.id = a.id_document)
            LEFT JOIN tm_keluar_cutting_item k ON
                (k.id_document = h.id_refference)
            WHERE
                a.id_document = $id
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY
                a.id_product_wip,
                b.i_product_wip,
                d.i_material ASC
        ", FALSE);
    }

    /**************** END EDIT DATA ******************/

    /***************** UPDATE DATA *******************/

    public function update($id,$idocument,$ddocument,$ibagian,$itujuan,$idreff,$eremark,$irefference)
    {
        $data = array(
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'i_bagian_tujuan' => $itujuan,
            'id_reff'         => $idreff,
            'e_remark'        => $eremark,
            'id_refference'   => $irefference,
            'd_update'        => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_cutting', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_keluar_cutting_item');
    }

    /*************** END UPDATE DATA *****************/
}
/* End of file Mmaster.php */