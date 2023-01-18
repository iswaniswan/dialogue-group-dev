<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$dfrom,$dto){
        $idcompany = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_sj
            WHERE
                d_document between to_date('$dfrom','dd-mm-yyyy') and to_date('$dto','dd-mm-yyyy') and  id_company = '$idcompany'
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '".$this->session->userdata('i_departement')."'
                        AND username = '".$this->session->userdata('username')."'
                        AND id_company = '$idcompany')

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
                        AND id_company = '$idcompany')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            WITH query AS (
                            SELECT
                            NO,
                            ROW_NUMBER() OVER (
                            ORDER BY a.id) AS i,
                            id,
                            i_document,
                            d_document,
                            id_type_spb,
                            e_type_name,
                            id_customer,
                            e_customer_name,
                            i_document_reff, 
                            id_document_reff,
                            i_menu
                            FROM(
                                SELECT
                                   DISTINCT ON
                                    (id) 0 AS no,
                                   a.id,
                                   a.i_document,
                                   to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                   a.id_type_spb,
                                   b.e_type_name,
                                   a.id_customer,
                                   cus.e_customer_name,
                                   CASE
                                      WHEN
                                         a.id_type_spb = '1' 
                                      THEN
                                         c.i_document 
                                      WHEN
                                         a.id_type_spb = '2' 
                                      THEN
                                         d.i_document 
                                      WHEN
                                         a.id_type_spb = '3' 
                                      THEN
                                         e.i_document 
                                   END
                                   AS i_document_reff, 
                                   a.id_document_reff,
                                   '$i_menu' AS i_menu
                                FROM
                                   tm_sj a 
                                   JOIN
                                      tr_type_spb b 
                                      ON a.id_type_spb = b.id 
                                   JOIN
                                      tr_customer cus 
                                      ON a.id_customer = cus.id 
                                      AND a.id_company = cus.id_company 
                                   LEFT JOIN
                                      tm_spb c 
                                      ON a.id_document_reff = c.id 
                                      AND a.id_company = c.id_company 
                                   LEFT JOIN
                                      tm_spb_ds d 
                                      ON a.id_document_reff = d.id 
                                      AND a.id_company = d.id_company 
                                   LEFT JOIN
                                      tm_spb_distributor e 
                                      ON a.id_document_reff = e.id 
                                      AND a.id_company = e.id_company 
                                WHERE
                                   a.f_transfer = 'f' 
                                   AND a.i_status = '6'
                                   AND a.id_company = '".$this->session->userdata('id_company')."'
                                   $bagian
                                ORDER BY a.id
                                )AS a)
                                   SELECT
                                        NO,
                                        i,
                                       id,
                                       i_document,
                                       d_document,
                                       id_type_spb,
                                       e_type_name,
                                       id_customer,
                                       e_customer_name,
                                       i_document_reff, 
                                       id_document_reff,
                                       i_menu,
                                        (
                                        SELECT
                                            count(i) AS jml
                                        FROM
                                            query) AS jml
                                    FROM
                                        query
                            ", FALSE);

        $datatables->add('action', function ($data) {
            $iddo             = trim($data['id']);
            $idocument        = trim($data['i_document']);
            $idtypespb        = trim($data['id_type_spb']);
            $idcustomer       = trim($data['id_customer']);
            $iddocumentreff   = trim($data['id_document_reff']);    
            $i                = trim($data['i']);
            $jml              = $data['jml'];
            $data   = '';

                $data  .= "
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"jml\" value=\"".$jml."\" type=\"hidden\">
                <input name=\"iddo".$i."\" value=\"".$iddo."\" type=\"hidden\">
                <input name=\"idocument".$i."\" value=\"".$idocument."\" type=\"hidden\">
                <input name=\"idtypespb".$i."\" value=\"".$idtypespb."\" type=\"hidden\">
                <input name=\"idcustomer".$i."\" value=\"".$idcustomer."\" type=\"hidden\">
                <input name=\"idspb".$i."\" value=\"".$iddocumentreff."\" type=\"hidden\">";

            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('id');
        $datatables->hide('id_type_spb');
        $datatables->hide('id_customer');
        $datatables->hide('id_document_reff');
        $datatables->hide('i');
        $datatables->hide('jml');
        return $datatables->generate();
    }

    public function transfer($iddo, $idspb, $idtypespb, $idcustomer){
        $data = array(
                        'f_transfer' => 't', 
        );
        $this->db->where('id', $iddo);
        $this->db->where('id_document_reff', $idspb);
        $this->db->where('id_type_spb', $idtypespb);
        $this->db->where('id_customer', $idcustomer);
        $this->db->update('tm_sj', $data);
    }
}
/* End of file Mmaster.php */