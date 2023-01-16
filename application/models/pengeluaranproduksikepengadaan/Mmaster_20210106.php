<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
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

    function data($i_menu,$folder,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query(
            "   
                SELECT
                    DISTINCT 0 AS NO,
                    a.id AS id,
                    i_document,
                    to_char(d_document, 'dd-mm-yyyy') AS d_document,
                    i_spbb,
                    a.e_remark,
                    e_status_name AS e_status,
                    label_color,
                    a.i_status,
                    '$i_menu' AS i_menu,
                    '$folder' AS folder,
                    '$dfrom' AS dfrom,
                    '$dto' AS dto
                FROM
                    tm_keluar_produksiak a
                INNER JOIN tr_status_document b ON
                    (b.i_status = a.i_status)
                INNER JOIN tm_keluar_produksiak_item c ON
                    (c.id_document = a.id)
                INNER JOIN tm_spbb d ON
                    (d.id = c.id_spbb)
                WHERE
                    a.i_status <> '5'
                    AND a.id_company = '".$this->session->userdata('id_company')."'
                    $and
                ORDER BY
                    a.id
            ", FALSE
        );

        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = $data['id'];
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
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
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
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $query = $this->db->query("
                SELECT
                    a.id_product_wip,
                    id_material,
                    b.n_quantity AS qtywip,
                    a.n_quantity AS qtymaterial,
                    a.id_spbb
                FROM
                    tm_keluar_produksiak_itemdetail a
                INNER JOIN tm_keluar_produksiak_item b ON
                    (b.id_document = a.id_document
                    AND a.id_product_wip = b.id_product_wip)
                WHERE a.id_document = '$id'
            ", FALSE);
            if ($query->num_rows()>0) {
                foreach ($query->result() as $key) {
                    $this->db->query("
                        UPDATE
                            tm_spbb_item
                        SET
                            n_quantity_sisa = n_quantity_sisa - $key->qtywip,
                            n_panjang_kain_sisa = n_panjang_kain_sisa - $key->qtymaterial
                        WHERE
                            id_spbb = '$key->id_spbb'
                            AND id_product = '$key->id_product_wip'
                            AND id_material = '$key->id_material'
                    ", FALSE);
                }
            }
            $data = array(
                'i_status'  => $istatus,
                'i_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_produksiak', $data);
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_produksiak');
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
            FROM tm_keluar_produksiak 
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
                tm_keluar_produksiak
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

    public function dataspbb($cari,$ibagian)
    {
        return $this->db->query("            
            SELECT
                DISTINCT a.id,
                a.i_spbb
            FROM
                tm_spbb a
            INNER JOIN tm_spbb_item b ON
                (a.id = b.id_spbb)
            WHERE
                a.i_spbb ILIKE '%$cari%'
                AND i_status = '6'
                AND n_quantity_sisa > 0
                AND n_panjang_kain_sisa > 0
                AND i_type IN (
                SELECT
                    i_type
                FROM
                    tr_bagian
                WHERE
                    i_bagian = '$ibagian'
                    AND id_company = '".$this->session->userdata('id_company')."')
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY a.i_spbb
        ", FALSE);
    }

    public function getspbb($id)
    {
        return $this->db->query("            
            SELECT
                a.*,
                to_char(d_spbb, 'dd-mm-yyyy') AS dspbb,
                e_bagian_name
            FROM
                tm_spbb a,
                tr_bagian b
            WHERE
                a.i_bagian = b.i_bagian
                AND a.id_company = b.id_company
                AND a.id = '$id'
                AND i_status = '6'
        ", FALSE);
    }

    public function getspbb_detail($id)
    {
        return $this->db->query(
            "
            SELECT
                c.id_spbb,
                c.id_product,
                c.id_material,
                sum(c.n_quantity_sisa) AS n_quantity_sisa,
                sum(c.n_panjang_kain) AS n_panjang_kain,
                sum(c.n_panjang_kain_sisa) AS n_panjang_kain_sisa,
                d.i_material,
                d.e_material_name,
                CASE
                    WHEN e_satuan_name ISNULL THEN ''
                    ELSE e_satuan_name
                END AS e_satuan
            FROM
                tm_spbb_item c
            LEFT JOIN tr_material d ON
                (d.id = c.id_material)
            LEFT JOIN tr_satuan e ON
                (e.i_satuan_code = d.i_satuan_code
                AND d.id_company = e.id_company)
            WHERE
                c.id_spbb = '$id'
                AND c.n_panjang_kain_sisa > 0
            GROUP BY 1,2,3,7,8,9
            ORDER BY
                c.id_product
            ",
            FALSE
        );
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_produksiak');
        return $this->db->get()->row()->id+1;
    }

    public function insertheader($id,$idocument,$ddocument,$ibagian,$itujuan,$eremarkh,$ispbb)
    {
        $data = array(
            'id'              => $id,
            'id_company'      => $this->session->userdata('id_company'),
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'i_bagian_tujuan' => $itujuan,
            'id_spbb'         => $ispbb,
            'e_remark'        => $eremarkh,
            'd_entry'         => current_datetime(),
        );
        $this->db->insert('tm_keluar_produksiak', $data);
    }

    public function insertbonkdetail($id,$idproduct,$nquantity,$ispbb)
    {
        $data = array(
            'id_document'     => $id,
            'id_product_wip'  => $idproduct,
            'n_quantity'      => $nquantity,
            'n_quantity_sisa' => $nquantity,
            'id_spbb'         => $ispbb,
            'id_company'      => $this->session->userdata('id_company'),
        );
        $this->db->insert('tm_keluar_produksiak_item', $data);
    }

    public function insertbonkdetailitem($id,$idproduct,$idmaterial,$npemenuhan,$npanjangkain,$ispbb,$eremark)
    {
        $data = array(
            'id_document'     => $id,
            'id_product_wip'  => $idproduct,
            'id_material'     => $idmaterial,
            'n_quantity'      => $npemenuhan,
            'n_quantity_sisa' => $npemenuhan,
            'id_spbb'         => $ispbb,
            'n_panjang_kain'  => $npanjangkain,
            'e_remark'        => $eremark,
            'id_company'      => $this->session->userdata('id_company'),
        );
        $this->db->insert('tm_keluar_produksiak_itemdetail', $data);
    }

    public function cek_data($id)
    {
        return $this->db->query(
            "
            SELECT
                a.id,
                a.i_document,
                a.i_bagian,
                a.i_bagian_tujuan,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                i_spbb,
                a.id_spbb,
                to_char(e.d_spbb, 'dd-mm-yyyy') AS d_spbb,
                d.e_bagian_name,
                b.e_bagian_name AS e_bagian,
                a.e_remark,
                a.i_status
            FROM
                tm_keluar_produksiak a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            LEFT JOIN tr_bagian d ON
                (d.i_bagian = a.i_bagian_tujuan
                AND a.id_company = d.id_company)
            INNER JOIN tm_spbb e ON
                (e.id = a.id_spbb)
            WHERE
                a.id = '$id'
            ",
            FALSE
        );
        return $this->db->get();
    }    

    public function cek_datadetail($id)
    {
        return $this->db->query(
            "
            SELECT
                i_material,
                a.id_material,
                a.id_product_wip,
                b.e_material_name,
                e_satuan_name,
                d.n_quantity AS qtywip,
                a.n_quantity AS qtymaterial,
                n_panjang_kain,
                a.e_remark
            FROM
                tm_keluar_produksiak_itemdetail a
            INNER JOIN tr_material b ON
                (b.id = a.id_material)
            INNER JOIN tr_satuan c ON
                (c.i_satuan_code = b.i_satuan_code
                AND b.id_company = c.id_company)
            INNER JOIN tm_keluar_produksiak_item d ON
                (d.id_document = a.id_document
                AND a.id_product_wip = d.id_product_wip)
            WHERE a.id_document = '$id'
            ",
            FALSE
        );
    }

    public function updateheader($id,$idocument,$ddocument,$ibagian,$itujuan,$eremarkh,$ispbb)
    {
        $data = array(
            'id_company'      => $this->session->userdata('id_company'),
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'i_bagian_tujuan' => $itujuan,
            'id_spbb'         => $ispbb,
            'e_remark'        => $eremarkh,
            'd_entry'         => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_produksiak', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_keluar_produksiak_item');

        $this->db->where('id_document', $id);
        $this->db->delete('tm_keluar_produksiak_itemdetail');
    }

    public function cek_sisa($idspbb,$idmaterial,$idproductwip)
    {
        $this->db->select('sum(n_panjang_kain_sisa) AS n_panjang_kain_sisa');
        $this->db->from('tm_spbb_item');
        $this->db->where('id_spbb', $idspbb);
        $this->db->where('id_material', $idmaterial);
        $this->db->where('id_product', $idproductwip);
        return $this->db->get()->row()->n_panjang_kain_sisa;
    }
}
/* End of file Mmaster.php */