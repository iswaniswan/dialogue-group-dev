<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Transfer extends Ci_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        echo "Transfer Unity";
    }

    public function get_surat_jalan()
    {
        $this->db->trans_begin();
        $id_company_duta = 4;
        $query = $this->db->get_where("produksi.tr_link_db", ['f_status'=>'true']);
        if ($query->num_rows()>0) {
            foreach ($query->result() as $key) {
                /******** SIMPAN UNIT JAHIT *********/
                $this->db->query(
                    "SELECT setval('produksi.tm_keluar_jahit_id_seq', COALESCE((SELECT MAX(id)+1 FROM produksi.tm_keluar_jahit), 1), false);
                    WITH sj AS (
                        SELECT * FROM produksi.dblink(
                            'host=$key->url_db port=$key->db_port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                            $$
                            select a.i_company, a.i_store, a.i_customer_id, a.i_sj, a.d_sj , c.i_product_supplier as i_product, replace(b.e_product_name, 'JASA JAHIT ', '') as e_product_name, 
                            replace(d.e_product_motifname, 'STD', 'TIDAK ADA') AS e_product_motifname, a.d_sj_entry, sum(b.n_deliver) AS n_deliver 
                            from tm_sj a 
                            inner join tm_sj_item b on (a.i_sj = b.i_sj and a.i_store = b.i_store and a.i_company = b.i_company)
                            inner join tr_product_company c on (b.i_product = c.i_product and b.i_company = c.i_company)
                            inner join tr_product_motif d on (c.i_product = d.i_product and b.i_product_motif = d.i_product_motif and d.f_product_motifaktif = true)
                            where a.i_sj_type = '02' and a.i_customer_id = '$key->id_company_from' AND a.i_company = '$key->id_company_unity' and b.i_product like '%J' /*and a.i_store = 'JKN11'*/ /*and b.n_deliver > 0*/ and a.f_sj_cancel = FALSE
                            /* AND a.d_sj >= current_date - INTERVAL '7 days' */
                            AND (a.d_sj_entry >= now() - INTERVAL '30 minute' OR a.d_sj_update >= now() - INTERVAL '30 minute')
                            GROUP BY 1,2,3,4,5,6,7,8,9
                            order by a.d_sj desc
                            $$
                        ) AS get_data (
                            i_company int,
                            i_store varchar(10),
                            i_customer_id int,
                            i_sj varchar(50),
                            d_sj date,
                            i_product varchar(50),
                            e_product_name text,
                            e_product_motifname varchar(30),
                            d_sj_entry timestamp WITHOUT time ZONE,
                            n_deliver NUMERIC
                        )	
                    ),
                    a AS (
                        INSERT INTO produksi.tm_keluar_jahit
                        (id_company, i_keluar_jahit, d_keluar_jahit, i_bagian, i_tujuan, i_status, e_approve, d_approve, e_remark, d_entry, id_jenis_barang_keluar, id_company_bagian)
                        SELECT DISTINCT $key->id_company AS id_company, i_sj, d_sj, b.i_bagian, (SELECT i_bagian FROM produksi.tr_bagian WHERE id_company = '$id_company_duta' AND i_type = '23' ORDER BY id DESC LIMIT 1) AS i_tujuan, 6 AS i_status, 'System' AS e_approve, current_date AS d_approve, 'Transfer dari unity' AS e_remark, d_sj_entry AS d_entry, 1 AS id_jenis_barang_keluar, $id_company_duta AS id_company_bagian
                        FROM sj a
                        INNER JOIN produksi.tr_bagian b ON (b.id_company_unity = a.i_company AND lower(trim(a.i_store)) = lower(trim(b.i_store_unity)))
                        WHERE b.id_company = '$key->id_company' AND b.i_type = '10'
                        ON CONFLICT (id_company, i_keluar_jahit, d_keluar_jahit, i_bagian, i_tujuan, d_entry) DO UPDATE SET d_update = now()
                        RETURNING id, i_keluar_jahit, d_keluar_jahit
                    ),
                    b (i_sj, d_sj, id_product, id_color, n_deliver, e_product_motifname) AS (
                        SELECT i_sj, d_sj, id_product, id_color, n_deliver, e_product_motifname
                        FROM sj a
                        INNER JOIN (
                            SELECT b.id AS id_product, i_product_base AS i_product, c.id AS id_color, c.e_color_name FROM produksi.tr_product_base b 
                            INNER JOIN produksi.tr_color c ON (c.i_color = b.i_color AND b.id_company = c.id_company)
                            WHERE b.id_company = $id_company_duta
                        ) b ON (b.i_product = a.i_product AND a.e_product_motifname = b.e_color_name)
                    )
                    INSERT INTO produksi.tm_keluar_jahit_item
                    (id_company, id_keluar_jahit, id_product, id_color, n_quantity_product, n_sisa, e_remark)
                    SELECT $key->id_company AS id_company, a.id AS id_keluar_jahit, b.id_product, b.id_color, b.n_deliver AS n_quantity_product, b.n_deliver AS n_sisa, b.e_product_motifname AS e_remark
                    FROM a
                    INNER JOIN b ON (b.i_sj = a.i_keluar_jahit AND a.d_keluar_jahit = b.d_sj)
                    ON CONFLICT (id_company,id_keluar_jahit,id_product) DO UPDATE 
                        SET (n_quantity_product, n_sisa) = (produksi.tm_keluar_jahit_item.n_quantity_product, produksi.tm_keluar_jahit_item.n_sisa);"
                );
                
                /******** SIMPAN UNIT PACKING *********/
                $this->db->query(
                    "SELECT setval('produksi.tm_keluar_qc_id_seq', COALESCE((SELECT MAX(id)+1 FROM produksi.tm_keluar_qc), 1), false);
                    WITH sj AS (
                        SELECT * FROM produksi.dblink(
                            'host=$key->url_db port=$key->db_port user=$key->user_postgre password=$key->password_postgre dbname=$key->db_name',
                            $$
                            select a.i_company, a.i_store, a.i_customer_id, a.i_sj, a.d_sj , c.i_product_supplier as i_product, replace(b.e_product_name, 'JASA PACKING ', '') as e_product_name, 
                            replace(d.e_product_motifname, 'STD', 'TIDAK ADA') AS e_product_motifname, a.d_sj_entry, sum(b.n_deliver) AS n_deliver 
                            from tm_sj a 
                            inner join tm_sj_item b on (a.i_sj = b.i_sj and a.i_store = b.i_store and a.i_company = b.i_company)
                            inner join tr_product_company c on (b.i_product = c.i_product and b.i_company = c.i_company)
                            inner join tr_product_motif d on (c.i_product = d.i_product and b.i_product_motif = d.i_product_motif and d.f_product_motifaktif = true)
                            where a.i_sj_type = '02' and a.i_customer_id = '$key->id_company_from' AND a.i_company = '$key->id_company_unity' and b.i_product like '%P' /*and a.i_store = 'JKN11'*/ /*and b.n_deliver > 0*/ and a.f_sj_cancel = FALSE
                            /* AND a.d_sj >= current_date - INTERVAL '7 days' */
                            AND (a.d_sj_entry >= now() - INTERVAL '30 minute' OR a.d_sj_update >= now() - INTERVAL '30 minute')
                            GROUP BY 1,2,3,4,5,6,7,8,9
                            order by a.d_sj desc
                            $$
                        ) AS get_data (
                            i_company int,
                            i_store varchar(10),
                            i_customer_id int,
                            i_sj varchar(50),
                            d_sj date,
                            i_product varchar(50),
                            e_product_name text,
                            e_product_motifname varchar(30),
                            d_sj_entry timestamp WITHOUT time ZONE,
                            n_deliver NUMERIC
                        )	
                    ),
                    a AS (
                        INSERT INTO produksi.tm_keluar_qc
                        (id_company, i_keluar_qc, d_keluar_qc, i_bagian, i_tujuan, i_status, e_approve, d_approve, e_remark, d_entry, id_jenis_barang_keluar, id_company_tujuan)
                        SELECT DISTINCT $key->id_company AS id_company, i_sj, d_sj, b.i_bagian, (SELECT i_bagian FROM produksi.tr_bagian WHERE id_company = '$id_company_duta' AND i_type = '04' ORDER BY id DESC LIMIT 1) AS i_tujuan, 6 AS i_status, 'System' AS e_approve, current_date AS d_approve, 'Transfer dari unity' AS e_remark, d_sj_entry AS d_entry, 1 AS id_jenis_barang_keluar, $id_company_duta AS id_company_bagian
                        FROM sj a
                        INNER JOIN produksi.tr_bagian b ON (b.id_company_unity = a.i_company AND lower(trim(a.i_store)) = lower(trim(b.i_store_unity)))
                        WHERE b.id_company = '$key->id_company' AND b.i_type = '12'
                        ON CONFLICT (id_company, i_keluar_qc, d_keluar_qc, i_bagian, i_tujuan, d_entry) DO UPDATE SET d_update = now()
                        RETURNING id, i_keluar_qc, d_keluar_qc
                    ),
                    b (i_sj, d_sj, id_product, id_color, n_deliver, e_product_motifname) AS (
                        SELECT i_sj, d_sj, id_product, id_color, n_deliver, e_product_motifname
                        FROM sj a
                        INNER JOIN (
                            SELECT b.id AS id_product, i_product_base AS i_product, c.id AS id_color, c.e_color_name FROM produksi.tr_product_base b 
                            INNER JOIN produksi.tr_color c ON (c.i_color = b.i_color AND b.id_company = c.id_company)
                            WHERE b.id_company = $id_company_duta
                        ) b ON (b.i_product = a.i_product AND a.e_product_motifname = b.e_color_name)
                    )
                    INSERT INTO produksi.tm_keluar_qc_item
                    (id_keluar_qc, id_product, id_color, n_quantity_product, n_sisa, e_remark, id_company, id_marker)
                    SELECT a.id AS id_keluar_qc, b.id_product, b.id_color, b.n_deliver AS n_quantity_product, b.n_deliver AS n_sisa, b.e_product_motifname AS e_remark, $key->id_company AS id_company, 1 AS id_marker
                    FROM a
                    INNER JOIN b ON (b.i_sj = a.i_keluar_qc AND a.d_keluar_qc = b.d_sj)
                    ON CONFLICT (id_company,id_keluar_qc,id_product) DO UPDATE 
                        SET (n_quantity_product, n_sisa) = (produksi.tm_keluar_qc_item.n_quantity_product, produksi.tm_keluar_qc_item.n_sisa);"
                );
            }
        }
        if ($this->db->trans_status() === false || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo "<center><h1>Data Gagal Disimpan :(</h1></center>";
        } else {
            $this->db->trans_commit();
            echo "<center><h1>Data Berhasil Disimpan :)</h1></center>";
        }
    }
}
