<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
    }

    public function data($i_menu,$folder,$dfrom, $dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                DISTINCT 
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                string_agg(DISTINCT d.i_document, ', ') AS i_referensi,
                a.e_remark,
                e_status_name,
                label_color,
                g.i_level,
                l.e_level_name,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_cutting a
            INNER JOIN tm_keluar_cutting_item aa ON
                (aa.id_document = a.id)
            INNER JOIN tr_status_document b ON
                (b.i_status = a.i_status)
            INNER JOIN tm_schedule_item_detail c ON
                (c.id_schedule = aa.id_reff
                AND aa.id_material = c.id_material)
            INNER JOIN tm_schedule d ON
                (d.id = c.id_schedule)                 
            LEFT JOIN tr_menu_approve g ON
                (a.i_approve_urutan = g.n_urut
                AND g.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (g.i_level = l.i_level)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->company'
                $and
            GROUP BY
                a.id,
                b.e_status_name,
                b.label_color
            ORDER BY
                a.id ASC");

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('i_referensi', function ($data) {
            return '<span>'.str_replace(",", "<br>", $data['i_referensi']).'</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = $data['id'];
            $i_status = trim($data['i_status']);
            $i_level  = $data['i_level'];
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
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
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
        $datatables->hide('i_status');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function gudang()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('i_level', $this->level);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function cek_kode($kode,$ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_keluar_cutting');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
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
            AND id_company = '$this->company'
            ORDER BY id DESC LIMIT 1");
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
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->company'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
            AND to_char (d_document, 'yyyy') >= '$tahun'
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
        $this->db->where('a.id_company', $this->company);
        return $this->db->get();
    }

    /****** UNTUK KELUAR BARU ******/

    public function datareferensi($cari)
    {
        return $this->db->query("
            SELECT 
                DISTINCT
                a.id,
                'Nomor : '||i_document|| ' - Tanggal : '|| to_char(d_document, 'dd-mm-yyyy') AS i_document
            FROM
                tm_schedule a
            INNER JOIN tm_schedule_item b ON
                (b.id_schedule = a.id)
            INNER JOIN tm_schedule_item_detail c ON
                (c.id_schedule = b.id_schedule
                AND b.i_product_wip = c.i_product_wip)
            WHERE
                i_status = '6'
                AND c.n_quantity_sisa > 0
                AND i_document ILIKE '%$cari%'
                AND a.id_company = '$this->company'
            ORDER BY a.id
            ",
            FALSE
        );
    }

    public function dataheader($id)
    {
        $in_str = "'".implode("', '", $id)."'";
        $where  = "WHERE id IN (".$in_str.")";
        return $this->db->query(
            "
            SELECT
                max(to_char(d_document, 'dd-mm-yyyy')) AS d_document
            FROM
                tm_schedule
            $where
            ",
            FALSE
        );
    }

    public function datadetail($id)
    {
        $in_str = "'".implode("', '", $id)."'";
        $where  = "WHERE a.id_schedule IN (".$in_str.")";
        return $this->db->query("
            SELECT
                a.id_schedule AS id_schedule,
                b.id AS id_product_wip,
                aa.i_document,
                a.i_product_wip,
                e_product_wipname,
                a.n_quantity as qty_wip,
                a.n_quantity_sisa_bon as qty_sisa_wip,
                d.id AS id_material,
                d.i_material,
                e_material_name,
                c.n_quantity as qty_ma,
                c.n_quantity_sisa AS qty_sisa_ma,
                e_color_name,
                c.n_set,
                c.n_gelar,
                c.n_jumlah_gelar
            FROM
                tm_schedule_item a
            INNER JOIN tm_schedule aa ON
                (aa.id = a.id_schedule
                AND a.id_company =aa.id_company)
            INNER JOIN tr_product_wip b ON
                (b.i_product_wip = a.i_product_wip
                AND a.i_color = b.i_color 
                AND a.id_company = b.id_company)
            INNER JOIN tm_schedule_item_detail c ON
                (c.i_product_wip = a.i_product_wip
                AND a.id_schedule = c.id_schedule)
            INNER JOIN tr_material d ON
                (d.id = c.id_material 
                AND a.id_company = d.id_company)
            INNER JOIN tr_color e ON
                (e.i_color = b.i_color
                AND b.id_company = e.id_company)
            $where
            ORDER BY
                a.id,
                b.id,
                a.i_product_wip,
                d.i_material ASC
        ", FALSE);
    }

    /********** END KELUAR BARU *********/

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
            'id_company'      => $this->company,
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

    public function simpandetail($id,$idreff,$idproductwip,$qtywip,$idmaterial,$qty,$eremark)
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
            'id_company'            => $this->company,
        );
        $this->db->insert('tm_keluar_cutting_item', $data);
    }

    /******************** END SIMPAN DATA *******************/

    /*** GET DATA HEADER EDIT, VIEW DAN APPROVE MULTIPLE  ***/
    
    public function dataeditreferensi($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT id_schedule AS id,
                'Nomor : '||i_document|| ' - Tanggal : '|| to_char(d_document, 'dd-mm-yyyy') AS i_document
            FROM
                tm_keluar_cutting_item a
            INNER JOIN tm_schedule_item_detail b ON
                (b.id_schedule = a.id_reff AND 
                a.id_material = b.id_material)
            INNER JOIN tm_schedule c ON
                (c.id = b.id_schedule)
            WHERE
                a.id_document = $id
            ORDER BY
                2
            ",
            FALSE
        );
    }

    /*----------  GET DATA HEADER EDIT, VIEW DAN APPROVE TANGGAL REFERENSI  ----------*/
    
    public function tanggalreferensi($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT 
                max(to_char(d_document, 'dd-mm-yyyy')) AS d_document
            FROM
                tm_keluar_cutting_item a
            INNER JOIN tm_schedule_item_detail b ON
                (b.id_schedule = a.id_reff AND 
                a.id_material = b.id_material)
            INNER JOIN tm_schedule c ON
                (c.id = b.id_schedule)
            WHERE
                a.id_document = $id
            ",
            FALSE
        )->row()->d_document;
    }

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
                a.i_bagian_tujuan,
                d.e_bagian_name AS e_bagian_tujuan,
                a.e_remark,
                a.i_status
            FROM
                tm_keluar_cutting a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tr_bagian d ON
                (d.i_bagian = a.i_bagian_tujuan
                AND a.id_company = b.id_company)
            WHERE
                a.id = '$id'
        ", FALSE);
    }

    public function dataeditdetail($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.id_reff AS id_schedule,
                a.id_product_wip AS id_product_wip,
                c.i_document,
                d.i_product_wip,
                e_product_wipname,
                e_color_name,
                a.id_material,
                i_material,
                e_material_name,
                n_set,
                n_gelar,
                (a.n_quantity / n_set) AS n_jumlah_gelar,
                se.n_quantity AS qtywip_pemenuhan,
                a.n_quantity_wip AS qtywip,
                se.n_quantity_sisa_bon AS qtysisawip,
                b.n_quantity AS qtyma_pemenuhan,
                a.n_quantity AS qtyma,  
                b.n_quantity_sisa AS qtysisama,
                a.e_remark
           FROM
                tm_keluar_cutting_item a
            INNER JOIN tm_schedule_item_detail b ON
                (b.id_schedule = a.id_reff
                AND a.id_material = b.id_material
                AND a.id_company = b.id_company)
            INNER JOIN tr_product_wip d ON
                (d.id = a.id_product_wip
                AND d.id_company = a.id_company)
            INNER JOIN tm_schedule_item se ON
                (se.id_schedule = a.id_reff
                AND se.id_company = a.id_company and se.i_product_wip = d.i_product_wip)
            INNER JOIN tm_schedule c ON
                (c.id = b.id_schedule
                AND a.id_company = c.id_company)
            INNER JOIN tr_material e ON
                (e.id = a.id_material
                AND e.id_company = a.id_company)
            INNER JOIN tr_color f ON
                (f.i_color = d.i_color
                AND d.id_company = f.id_company)
            WHERE
                a.id_document = '$id'
            ORDER BY
                1,
                2 
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

    
    /***** UPDATE STATUS DOKUMENT & REFERENSI *****/

    public function changestatus($id,$istatus)
    {
        $iapprove = $this->session->userdata('username');
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'i_approve' => $this->username,
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

    public function updatesisa($id)
    {

        /*----------  Cek ada data atau tidak  ----------*/
        
        $query = $this->db->query("
            SELECT 
                a.id_reff,
                a.id_product_wip,
                a.id_material,
                a.n_quantity,
                a.n_quantity_wip,
                b.i_product_wip
            FROM 
                tm_keluar_cutting_item a
            JOIN    
                tr_product_wip b ON a.id_product_wip = b.id AND a.id_company = b.id_company
            WHERE 
                a.id_document = '$id'

        ", FALSE);

        $query2 = $this->db->query("
            SELECT DISTINCT
                a.id_reff,
                a.id_product_wip,
                a.n_quantity_wip,
                b.i_product_wip
            FROM 
                tm_keluar_cutting_item a
            JOIN    
                tr_product_wip b ON a.id_product_wip = b.id AND a.id_company = b.id_company
            WHERE 
                a.id_document = '$id'

        ", FALSE);

        /*----------  Jika Data Ada  ----------*/
        
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {

                /*----------  Cek Sisa Di Item Tidak Kurang Dari Pemenuhan  ----------*/
                
                $ceksisa1 = $this->db->query("
                    SELECT 
                        n_quantity_sisa
                    FROM 
                        tm_schedule_item_detail
                    WHERE 
                        id_schedule = '$key->id_reff'
                        AND id_material = '$key->id_material'
                        AND n_quantity_sisa >= '$key->n_quantity'
                ", FALSE);
                
                if ($ceksisa1->num_rows()>0) {

                    /*----------  Update Sisa Di Schedule  ----------*/                    
                    $this->db->query("
                        UPDATE 
                            tm_schedule_item_detail
                        SET 
                            n_quantity_sisa = n_quantity_sisa - '$key->n_quantity'
                        WHERE 
                            id_schedule = '$key->id_reff'
                            AND id_material = '$key->id_material'
                            AND n_quantity_sisa >= '$key->n_quantity'
                            AND id_company = '".$this->session->userdata('id_company')."'
                    ", FALSE);

                   
                }else{
                    die();
                }
            }
        }if ($query2->num_rows()>0) {
            foreach ($query2->result() as $key) {
                $ceksisa2 = $this->db->query("
                    SELECT 
                        n_quantity_sisa_bon
                    FROM 
                        tm_schedule_item
                    WHERE 
                        id_schedule = '$key->id_reff'
                        AND i_product_wip = '$key->i_product_wip'
                        AND n_quantity_sisa_bon >= '$key->n_quantity_wip'
                ", FALSE);

                if ($ceksisa2->num_rows()>0) {
                    $this->db->query("
                        UPDATE 
                            tm_schedule_item
                        SET 
                            n_quantity_sisa_bon = n_quantity_sisa_bon - '$key->n_quantity_wip'
                        WHERE 
                            id_schedule = '$key->id_reff'
                            AND i_product_wip = '$key->i_product_wip'
                            AND n_quantity_sisa_bon >= '$key->n_quantity_wip'
                            AND id_company = '".$this->session->userdata('id_company')."'
                    ", FALSE);
                }else{
                    die();
                }
            }
        }else{
            die();
        }
    }

    /*----------  SIMPAN KE JURNAL  ----------*/

    public function simpanjurnal($id,$title)
    {
        $this->db->query("
            INSERT
                INTO
                tm_jurnal_dokumen (id_company,
                id_document,
                i_document,
                i_periode,
                id_material,
                id_product_wip,
                id_product_base,
                i_coa,
                e_coa,
                id_payment_type,
                v_price,
                n_quantity_material,
                n_quantity_wip,
                n_quantity_base,
                n_total,
                title)
            SELECT
                a.id_company,
                id_document,
                i_document,
                to_char(d_document, 'yyyymm') AS i_periode,
                id_material AS id_material,
                id_product_wip AS id_product_wip,
                NULL AS id_product_base,
                '110-84001' AS i_coa,
                'Cutting' AS e_coa,
                NULL AS id_payment_type,
                NULL AS v_price,
                n_quantity AS n_quantity_material,
                n_quantity_wip AS n_quantity_wip,
                NULL AS n_quatity_base,
                NULL AS total,
                '$title' AS title
            FROM
                tm_keluar_cutting a
            INNER JOIN tm_keluar_cutting_item b ON
                (id_document = a.id)
            WHERE
                a.id = $id
        ", FALSE);
    }    
}
/* End of file Mmaster.php */