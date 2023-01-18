<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{

    public function data($i_menu,$folder,$dfrom,$dto)
    {

        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_masuk_makloon_qc
            WHERE
                i_status <> '5'
                and d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$id_company'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$id_company')

        ", FALSE);
        if ($this->session->userdata('i_departement')=='1') {
            $bagian = "";
        }else{
            if ($cek->num_rows()>0) {                
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }else{
                $bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$id_company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                              SELECT
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
                                 '$i_menu' AS i_menu,
                                 '$folder' AS folder,
                                 '$dfrom' AS dfrom,
                                 '$dto' AS dto 
                              FROM
                                 tm_masuk_makloon_qc a 
                                INNER JOIN
                                    tm_masuk_makloon_qc_item ai 
                                    ON (a.id = ai.id_document AND a.id_company = ai.id_company) 
                                 INNER JOIN
                                    tm_keluar_makloonqc dt 
                                    ON (dt.id = ai.id_document_reff AND a.id_company = dt.id_company) 
                                 INNER JOIN
                                    tr_supplier b 
                                    ON (b.id = a.id_supplier AND a.id_company = b.id_company) 
                                 INNER JOIN
                                    tr_status_document d 
                                    ON (d.i_status = a.i_status) 
                              WHERE
                                 a.i_status <> '5' 
                                 AND a.d_document BETWEEN to_date('$dfrom', 'dd-mm-yyyy') AND to_date('$dto', 'dd-mm-yyyy') 
                                 AND a.id_company = '$id_company' $bagian 
                              GROUP BY
                                 a.id,
                                 a.i_document,
                                 a.d_document,
                                 b.e_supplier_name,
                                 e_status_name,
                                 label_color,
                                 a.i_status
                          ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
          });

        $datatables->edit('i_reff', function ($data) {
            return '<span>'.str_replace("}", "", str_replace("{", "", str_replace(",", "<br>", $data['i_reff']))).'</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            
            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }   

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
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
        return $datatables->generate();
    }

      /*----------  DATA BAGIAN PEMBUAT DOKUMENT  ----------*/
    
    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }
    

    public function cek_kode($kode,$ibagian){
        $this->db->select('i_document');
        $this->db->from('tm_masuk_makloon_qc');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl,$tahun,$ibagian)
    {
         $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_masuk_makloon_qc 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SJ';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_masuk_makloon_qc
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata('id_company')."'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
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
    
    /*----------  BACA PARTNER (SUPPLIER)  ----------*/

    public function partner($cari) {
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
            INNER JOIN tm_keluar_makloonqc d ON 
                (b.id = d.id_supplier
                AND a.id_company = d.id_company)
            INNER JOIN tm_keluar_makloonqc_item e ON 
                (d.id = e.id_document
                AND a.id_company = e.id_company)
            WHERE
                b.f_status = 't'
                AND
                d.i_status = '6'
                AND e.n_quantity_sisa <> 0
                AND (e_supplier_name ILIKE '%$cari%')
                AND a.id_company = '".$this->session->userdata('id_company')."'
            ORDER BY
                b.e_supplier_name
        ", FALSE);
    }


   /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_masuk_makloon_qc');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id, $idocument, $ddocument, $ibagian, $ipartner,$idocumentsup, $eremarkh)
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
        $this->db->insert('tm_masuk_makloon_qc', $data);
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
        $this->db->insert('tm_masuk_makloon_qc_item', $data);
    }

    public function changestatus($id,$istatus) {
        if ($istatus=='6') {
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
        }else{
            $data = array(
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_masuk_makloon_qc', $data);
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
                                     tm_masuk_makloon_qc a 
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
                                  SELECT --DISTINCT ON(b.id_product) 
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
                                     tm_masuk_makloon_qc a 
                                     INNER JOIN
                                        tm_masuk_makloon_qc_item b 
                                        ON (a.id = b.id_document 
                                        AND a.id_company = b.id_company) 
                                     INNER JOIN
                                        tm_keluar_makloonqc k 
                                        ON (k.id = b.id_document_reff 
                                        AND a.id_company = k.id_company) 
                                     INNER JOIN
                                        tm_keluar_makloonqc_item ki 
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
                                     b.id_document = '$id' 
                                  ORDER BY
                                     a.i_document,
                                     c.e_product_basename ASC
                                ", FALSE);
    }

    public function update($id, $idocument, $ddocument, $ibagian, $ipartner, $idocumentsup,$eremarkh)
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
        $this->db->update('tm_masuk_makloon_qc', $data);
    }

    /*----------  DELETE DETAIL BEFORE INSERT (ON UPDATE)  ----------*/

    public function delete($id) {
        $idcompany = $this->session->userdata('id_company');
        $this->db->where('id_document', $id);
        $this->db->where('id_company', $idcompany);
        $this->db->delete('tm_masuk_makloon_qc_item');
    }


    public function referensieks($cari,$partner)
    {
        $cari = str_replace("'", "", $cari);
        return $this->db->query("    
                                   SELECT DISTINCT
                                     a.i_document,
                                     a.id,
                                     to_char(d_document, 'dd-mm-yyyy') as d_document 
                                  FROM
                                     tm_keluar_makloonqc a 
                                     INNER JOIN
                                        tm_keluar_makloonqc_item b 
                                        ON (a.id = b.id_document AND a.id_company = b.id_company) 
                                     INNER JOIN
                                        tr_product_base c 
                                        ON (b.id_product_base = c.id AND b.id_company = c.id_company) 
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
        $in_str = "'".implode("', '", $id)."'";
        $and   = "AND a.id IN (".$in_str.")";
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
                                     tm_keluar_makloonqc a 
                                     INNER JOIN
                                        tm_keluar_makloonqc_item b 
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
            update tm_keluar_makloonqc_item set n_quantity_sisa = n_quantity_sisa - $nquantity where id_document = '$id_document_reff' and  id_product_base = '$id_product' and id_company = '$idcompany'
        ", FALSE);
    }
}
/* End of file Mmaster.php */
