<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    public function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $and   = "";
        }

        $cek = $this->db->query("SELECT i_bagian FROM tm_masuk_makloon_packing a WHERE i_status <> '5' AND id_company = '$this->id_company' $and AND i_bagian IN (
            SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement' AND id_company = '$this->id_company' AND username = '$this->username')", FALSE);
        if ($this->i_departement == '1') {
            $bagian = "";
        } else {
            if ($cek->num_rows() > 0) {
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            } else {
                $bagian = "AND a.i_bagian IN (
                    SELECT i_bagian FROM tr_departement_cover WHERE i_departement = '$this->i_departement' AND id_company = '$this->id_company' AND username = '$this->username')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                b.e_supplier_name,
                array_agg(distinct(dt.i_document)) AS i_reff,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                f.i_level,
			    l.e_level_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto 
            FROM
                tm_masuk_makloon_packing a 
            INNER JOIN
                tm_masuk_makloon_packing_item ai 
                ON (a.id = ai.id_document AND a.id_company = ai.id_company) 
            INNER JOIN
                tm_keluar_makloon_packing dt 
                ON (dt.id = ai.id_document_reff AND a.id_company = dt.id_company) 
            INNER JOIN
                tr_supplier b 
                ON (b.id = a.id_supplier AND a.id_company = b.id_company) 
            INNER JOIN
                tr_status_document d 
                ON (d.i_status = a.i_status) 
            LEFT JOIN tr_menu_approve f ON
                (a.i_approve_urutan = f.n_urut
                AND f.i_menu = '$i_menu')
            LEFT JOIN public.tr_level l ON
                (f.i_level = l.i_level)
            WHERE
                a.i_status <> '5' 
                AND a.id_company = '$this->id_company' 
                $bagian 
                $and
            GROUP BY
                a.id,
                a.i_document,
                a.d_document,
                b.e_supplier_name,
                e_status_name,
                label_color,
                a.i_status,
                f.i_level,
			    l.e_level_name
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name'] . ' ' . $data['e_level_name'];
            }
            return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>';
            /* return '<span class="label label-' . $data['label_color'] . ' label-rouded">' . $data['e_status_name'] . '</span>'; */
        });

        $datatables->edit('i_reff', function ($data) {
            return '<span>' . str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['i_reff']))) . '</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $i_level  = $data['i_level'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success mr-3'></i></a>";
            }

            if (check_role($i_menu, 3)) {
                if ($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary mr-3'></i></a>";
                }
            }

            if (check_role($i_menu, 4)  && ($i_status == '1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close text-danger'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        $datatables->hide('i_level');
        $datatables->hide('e_level_name');
        return $datatables->generate();
    }

    /*----------  DATA BAGIAN PEMBUAT DOKUMENT  ----------*/

    public function bagian()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian AND a.id_company = b.id_company', 'inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }


    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_masuk_makloon_packing');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_masuk_makloon_packing
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata('id_company') . "'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'BBM';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_masuk_makloon_packing
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata('id_company') . "'
            AND substring(i_document, 1, 3) = '$kode'
            AND substring(i_document, 5, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    /*----------  BACA PARTNER (SUPPLIER)  ----------*/

    public function partner($cari)
    {
        return $this->db->query("
                                  SELECT
                                      DISTINCT b.id,
                                      b.e_supplier_name
                                  FROM
                                      tr_supplier_makloon a
                                  INNER JOIN tr_supplier b ON
                                      (b.i_supplier = a.i_supplier
                                      AND a.id_company = b.id_company)
                                  INNER JOIN tr_type_makloon c ON
                                      (c.i_type_makloon = a.i_type_makloon
                                      AND a.id_company = c.id_company)
                                  INNER JOIN tm_keluar_makloon_packing d ON 
                                      (b.id = d.id_supplier
                                      AND a.id_company = d.id_company)
                                  INNER JOIN tm_keluar_makloon_packing_item e ON 
                                      (d.id = e.id_document
                                      AND a.id_company = e.id_company)
                                  WHERE
                                      b.f_status = 't'
                                      AND
                                      d.i_status = '6'
                                      AND e.n_quantity_sisa <> 0
                                      AND (e_supplier_name ILIKE '%$cari%')
                                      AND a.id_company = '" . $this->session->userdata('id_company') . "'
                                  ORDER BY
                                      b.e_supplier_name
                              ", FALSE);
    }

    /*----------  SIMPAN DATA  ----------*/

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_makloon_packing');
        return $this->db->get()->row()->id + 1;
    }

    public function simpan($id, $idocument, $ddocument, $ibagian, $ipartner, $idocumentsup, $eremarkh)
    {
        $data = array(
            'id'                  => $id,
            'id_company'          => $this->session->userdata('id_company'),
            'i_document'          => $idocument,
            'd_document'          => $ddocument,
            'i_bagian'            => $ibagian,
            'id_supplier'         => $ipartner,
            'i_document_supplier' => $idocumentsup,
            'e_remark'            => $eremarkh,
            'd_entry'             => current_datetime(),
        );
        $this->db->insert('tm_masuk_makloon_packing', $data);
    }

    public function simpandetail($id, $id_document_reff, $id_product, $nquantity, $eremark)
    {
        $data = array(
            'id_company'        => $this->session->userdata('id_company'),
            'id_document'       => $id,
            'id_document_reff'  => $id_document_reff,
            'id_product'        => $id_product,
            'n_quantity'        => $nquantity,
            'n_quantity_sisa'   => $nquantity,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_masuk_makloon_packing_item', $data);
    }

    public function changestatus($id, $istatus)
    {
        /* if ($istatus == '6') {
            // $query = $this->db->query("
            //     SELECT id_document, id_product, n_quantity, id_document_reff
            //     FROM tm_masuk_makloon_qc_item
            //     WHERE id_document = '$id' ", FALSE);
            // if ($query->num_rows()>0) {
            //     foreach ($query->result() as $key) {
            //         $this->db->query("
            //             UPDATE
            //                 tm_keluar_makloonqc_item
            //             SET
            //                 n_sisa = n_sisa - $key->n_quantity
            //             WHERE
            //                 id_document = '$key->id_document_reff'
            //                 AND id_product_base = '$key->id_product'
            //                 AND id_company = '".$this->session->userdata('id_company')."'
            //         ", FALSE);
            //     }
            // }
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        } else {
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_makloon_packing', $data); */
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, a.i_approve_urutan, coalesce(max(b.n_urut),1) as n_urut 
				from tm_masuk_makloon_packing a
				inner join tr_menu_approve b on (b.i_menu = '$this->i_menu')
				where a.id = '$id'
				group by 1,2", FALSE)->row();
            if ($istatus == '3') {
            	if ($awal->i_approve_urutan - 1 == 0 ) {
            		$data = array(
	                    'i_status'  => $istatus,
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
            	}
            	$this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' and i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
            	if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
                    $query = $this->db->query("SELECT id_document_reff, id_product, n_quantity FROM tm_masuk_makloon_packing_item WHERE id_document = '$id' ");
                    if ($query->num_rows()>0) {
                        foreach ($query->result() as $key) {
                            $this->updatekeluar($key->id_document_reff, $key->id_product, $key->n_quantity);
                        }
                    }
            		$data = array(
	                    'i_status' => $istatus,
	                    'i_approve_urutan' => $awal->i_approve_urutan + 1,
                        'e_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	}
                $now = date('Y-m-d');
            	$this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_masuk_makloon_packing');", FALSE);
            }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_makloon_packing', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id)
    {
        return $this->db->query("
                                  SELECT
                                     a.id,
                                     a.i_document,
                                     to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                                     a.i_bagian,
                                     a.id_supplier,
                                     b.e_supplier_name,
                                     a.i_document_supplier,
                                     a.i_status,
                                     a.e_remark,
                                     e.e_bagian_name 
                                  FROM
                                     tm_masuk_makloon_packing a 
                                     INNER JOIN
                                        tr_supplier b 
                                        ON (b.id = a.id_supplier AND a.id_company = b.id_company) 
                                     INNER JOIN
                                        tr_status_document d 
                                        ON (d.i_status = a.i_status) 
                                     INNER JOIN
                                        tr_bagian e 
                                        ON (e.i_bagian = a.i_bagian 
                                        AND a.id_company = e.id_company) 
                                  WHERE
                                     a.id = '$id'
                                ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id)
    {
        return $this->db->query("
                                    SELECT
                                       a.id,
                                       b.id_document_reff,
                                       k.i_document,
                                       b.id_product,
                                       c.i_product_base,
                                       c.e_product_basename,
                                       d.e_color_name,
                                       ki.n_quantity as keluarfull,
                                       ki.n_quantity_sisa as keluar,
                                       COALESCE(b.n_quantity, 0) as masuk,
                                       b.e_remark 
                                    FROM
                                       tm_masuk_makloon_packing a 
                                       INNER JOIN
                                          tm_masuk_makloon_packing_item b 
                                          ON (a.id = b.id_document 
                                          AND a.id_company = b.id_company) 
                                       INNER JOIN
                                          tm_keluar_makloon_packing k 
                                          ON (k.id = b.id_document_reff 
                                          AND a.id_company = k.id_company) 
                                       INNER JOIN
                                          tm_keluar_makloon_packing_item ki 
                                          ON (k.id = ki.id_document 
                                          AND b.id_product = ki.id_product_base
                                          AND a.id_company = ki.id_company) 
                                       inner join
                                          tr_product_base c 
                                          on (b.id_product = c.id 
                                          AND a.id_company = c.id_company) 
                                       INNER JOIN
                                          tr_color d 
                                          ON (c.i_color = d.i_color 
                                          AND c.id_company = d.id_company) 
                                    WHERE
                                       a.id = '$id' 
                                    ORDER BY
                                       b.id_product,
                                       a.i_document,
                                       c.e_product_basename ASC
                                ", FALSE);
    }

    public function datareferensi($id = null)
    {
        return $this->db->query("SELECT
                DISTINCT id_document_reff id,
                b.i_document
            FROM
                tm_masuk_makloon_packing_item a
            INNER JOIN tm_keluar_makloon_packing b ON
                b.id = a.id_document_reff
            WHERE
                a.id_document = '$id'");
    }

    public function update($id, $idocument, $ddocument, $ibagian, $ipartner, $idocumentsup, $eremarkh)
    {
        $idcompany = $this->session->userdata('id_company');
        $data = array(
            'i_document'          => $idocument,
            'd_document'          => $ddocument,
            'i_bagian'            => $ibagian,
            'id_supplier'         => $ipartner,
            'i_document_supplier' => $idocumentsup,
            'e_remark'            => $eremarkh,
            'd_update'            => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_masuk_makloon_packing', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->where('id_document', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_masuk_makloon_packing_item');
    }


    public function referensieks($cari, $partner)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("    
                                  SELECT DISTINCT
                                     a.i_document,
                                     a.id,
                                     to_char(d_document, 'dd-mm-yyyy') as d_document 
                                  FROM
                                     tm_keluar_makloon_packing a 
                                     INNER JOIN
                                        tm_keluar_makloon_packing_item b 
                                        ON (a.id = b.id_document AND a.id_company = b.id_company) 
                                     INNER JOIN
                                        tr_product_base c 
                                        on (b.id_product_base = c.id AND b.id_company = c.id_company) 
                                     INNER JOIN
                                        tr_color d 
                                        ON (c.i_color = d.i_color AND c.id_company = d.id_company) 
                                  WHERE
                                     a.i_status = '6' 
                                     AND COALESCE(b.n_quantity_sisa, 0) > 0 
                                     AND a.id_supplier = '$partner' 
                                     AND 
                                     (
                                        TRIM(a.i_document) ILIKE '$cari%'
                                     )
                                ", FALSE);
    }

    public function getdetailrefeks($id)
    {
        $in_str = "'" . implode("', '", $id) . "'";
        $and   = "AND a.id IN (" . $in_str . ")";
        return $this->db->query("
                                  SELECT
                                     a.i_document,
                                     a.id,
                                     b.id_product_base,
                                     c.i_product_base,
                                     c.e_product_basename,
                                     d.e_color_name,
                                     b.n_quantity,
                                     b.n_quantity_sisa 
                                  FROM
                                     tm_keluar_makloon_packing a 
                                     INNER JOIN
                                        tm_keluar_makloon_packing_item b 
                                        ON (a.id = b.id_document AND a.id_company = b.id_company) 
                                     INNER JOIN
                                        tr_product_base c 
                                        ON (b.id_product_base = c.id AND b.id_company = c.id_company) 
                                     INNER JOIN
                                        tr_color d 
                                        ON (c.i_color = d.i_color AND c.id_company = d.id_company) 
                                  WHERE
                                     COALESCE (b.n_quantity_sisa, 0) > 0  $and
                                  ORDER BY
                                     a.i_document,
                                     c.e_product_basename ASC
                                ", FALSE);
    }

    public function updatekeluar($id_document_reff, $id_product, $nquantity)
    {
        $idcompany = $this->session->userdata('id_company');
        $this->db->query("
                          UPDATE 
                            tm_keluar_makloon_packing_item 
                          SET 
                            n_quantity_sisa = n_quantity_sisa - $nquantity 
                          WHERE 
                            id_document = '$id_document_reff' 
                            AND  id_product_base = '$id_product' 
                            AND id_company = '$idcompany'
                        ", FALSE);
    }
}
/* End of file Mmaster.php */