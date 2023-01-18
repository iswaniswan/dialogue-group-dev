<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model{
    
    public function bagian(){
        $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian AND a.id_company = b.id_company','inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    public function jenis() {
        return $this->db->query("                                    
                                    SELECT
                                       i_jenis_faktur,
                                       e_jenis_faktur_name 
                                    FROM
                                       tr_jenis_faktur 
                                    WHERE 
                                        i_type = '2'
                                    AND 
                                        f_status = 't'
                                    ORDER BY
                                       i_jenis_faktur
                                ", FALSE);
    }

    public function seripajak(){
      return $this->db->query("
                                SELECT 
                                  max(i_seri_pajak_awal) AS i_seri_pajak_awal  
                                FROM 
                                  tr_seri_pajak 
                                WHERE 
                                  id_company = '".$this->session->userdata('id_company')."'
                                ORDER BY i_seri_pajak_awal 
                                DESC LIMIT 1
                              ", FALSE);
    }

    public function getiseri($ijenis){
        $idcompany = $this->session->userdata('id_company');
        //faktur penjualan barang jadi
        if($ijenis == '9'){
            $query = $this->db->query("SELECT  max(i_pajak) AS i_pajak FROM tm_nota_penjualan WHERE id_company ='$idcompany' ORDER BY i_pajak DESC LIMIT 1");
            if ($query->num_rows() > 0) {
                $cek = $query->row();
                $ipajak= is_numeric($cek->i_pajak)+1;
            }
            else{
                $pa = "1";
                $ipajak = "123231".$pa;
              }
              return $ipajak;
        }
 
        //faktur penjualan bahan baku
        if($ijenis == '10'){
            $query = $this->db->query("SELECT max(i_pajak) AS i_pajak FROM tm_nota_penjualan_bb WHERE id_company ='$idcompany' ORDER BY i_pajak DESC LIMIT 1");
            if ($query->num_rows() > 0) {
                $cek = $query->row();
                $ipajak= is_numeric($cek->i_pajak)+1;
            }
            else{
                $pa = "1";
                $ipajak = "123231".$pa;
            }
            return $ipajak;
        }
    }

    public function getnota($ijenis, $dawal, $dakhir){
        if($ijenis == 'all'){
            return $this->db->query("                       
                                      SELECT
                                        z.id,
                                        z.i_document,
                                        z.d_document
                                      FROM
                                        (
                                          SELECT
                                            a.id,
                                            a.i_document,
                                            to_char(a.d_document, 'dd-mm-yyyy') as d_document 
                                          FROM
                                            tm_nota_penjualan a 
                                          WHERE
                                          d_document BETWEEN to_date('$dawal', 'dd-mm-yyyy') AND to_date('$dakhir', 'dd-mm-yyyy') 
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."'

                                          UNION ALL
                                          SELECT
                                            a.id,
                                            a.i_document,
                                            to_char(a.d_document, 'dd-mm-yyyy') as d_document
                                          FROM
                                            tm_nota_penjualan_bb a 
                                          WHERE
                                            d_document BETWEEN to_date('$dawal', 'dd-mm-yyyy') AND to_date('$dakhir', 'dd-mm-yyyy') 
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."' 
                                        )as z
                                    ", FALSE);
        }
        else if($ijenis == '9'){      
            return $this->db->query("                       
                                        SELECT
                                           a.id,
                                           a.i_document,
                                           to_char(a.d_document, 'dd-mm-yyyy') as d_document
                                        FROM
                                           tm_nota_penjualan a 
                                        WHERE
                                          d_document BETWEEN to_date('$dawal', 'dd-mm-yyyy') AND to_date('$dakhir', 'dd-mm-yyyy') 
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."'
                                        ORDER BY a.i_document
                                    ", FALSE);
        }
        //faktur penjualan bahan baku
        else if($ijenis == '10'){
            return $this->db->query("
                                        SELECT
                                           a.id,
                                           a.i_document,
                                           to_char(a.d_document, 'dd-mm-yyyy') as d_document
                                        FROM
                                           tm_nota_penjualan_bb a 
                                        WHERE
                                           d_document BETWEEN to_date('$dawal', 'dd-mm-yyyy') AND to_date('$dakhir', 'dd-mm-yyyy') 
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."' 
                                        ORDER BY a.i_document
                                    ", FALSE);
        }
    }

    public function getdetail($ijenis, $jtawal, $jtakhir){
        //all jenis
        if($ijenis == 'all'){
            return $this->db->query(" 
                                      SELECT
                                        z.id,
                                        z.i_document,
                                        z.d_document,
                                        z.i_pajak,
                                        z.d_pajak,
                                        z.d_jatuh_tempo,
                                        z.v_bersih,
                                        z.v_sisa,
                                        z.jenis_faktur,
                                        z.i_jenis_faktur 
                                      FROM 
                                      (
                                        SELECT
                                           a.id,
                                           a.i_document,
                                           to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                           a.i_pajak,
                                           a.d_pajak,
                                           to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo,
                                           a.v_bersih,
                                           a.v_sisa,
                                           'Faktur Penjualan Barang Jadi' as jenis_faktur,
                                           '9' as i_jenis_faktur
                                        FROM
                                           tm_nota_penjualan a 
                                        WHERE
                                          d_document BETWEEN to_date('$jtawal', 'dd-mm-yyyy') AND to_date('$jtakhir', 'dd-mm-yyyy') 
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."'

                                        UNION ALL
                                        SELECT
                                           a.id,
                                           a.i_document,
                                           to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                           a.i_pajak,
                                           a.d_pajak,
                                           to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo,
                                           a.v_bersih,
                                           a.v_sisa,
                                           'Faktur Penjualan Bahan Baku' as jenis_faktur,
                                           '10' as i_jenis_faktur 
                                        FROM
                                           tm_nota_penjualan_bb a 
                                        WHERE
                                           d_document BETWEEN to_date('$jtawal', 'dd-mm-yyyy') AND to_date('$jtakhir', 'dd-mm-yyyy') 
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."' 
                                      )as z
                                    ", FALSE);
        }

        //faktur penjualan barang jadi
        if($ijenis == '9'){      
            return $this->db->query("                       
                                        SELECT
                                           a.id,
                                           a.i_document,
                                           to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                           a.i_pajak,
                                           a.d_pajak,
                                           to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo,
                                           a.v_bersih,
                                           a.v_sisa,
                                           'Faktur Penjualan Bahan Baku' as jenis_faktur,
                                           '10' as i_jenis_faktur 
                                        FROM
                                           tm_nota_penjualan a 
                                        WHERE
                                          d_document BETWEEN to_date('$jtawal', 'dd-mm-yyyy') AND to_date('$jtakhir', 'dd-mm-yyyy') 
                                           -- AND a.id BETWEEN '$inotafrom' AND '$inotato'
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."'
                                        ORDER BY a.i_document
                                    ", FALSE);
        }
        //faktur penjualan bahan baku
        else if($ijenis == '10'){
            return $this->db->query("
                                        SELECT
                                           a.id,
                                           a.i_document,
                                           to_char(a.d_document, 'dd-mm-yyyy') as d_document,
                                           a.i_pajak,
                                           a.d_pajak,
                                           to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo,
                                           a.v_bersih,
                                           a.v_sisa,
                                           'Faktur Penjualan Bahan Baku' as jenis_faktur,
                                           '10' as i_jenis_faktur 
                                        FROM
                                           tm_nota_penjualan_bb a 
                                        WHERE
                                           d_document BETWEEN to_date('$jtawal', 'dd-mm-yyyy') AND to_date('$jtakhir', 'dd-mm-yyyy') 
                                           -- AND a.id BETWEEN '$inotafrom' AND '$inotato'
                                           AND a.i_status = '6' 
                                           AND a.id_company = '".$this->session->userdata('id_company')."' 
                                        ORDER BY a.i_document
                                    ", FALSE);
        }
    }
    
    public function updatenota($jenisfaktur, $idfaktur, $ifaktur, $ipajakk, $idcompany, $ipajakawal) {
        //faktur penjualan barang jadi
        if ($jenisfaktur=='9') {
            $data = array(
                          'i_pajak'             => $ipajakk,
                          'i_faktur_komersial'  => $ipajakawal,              
            );
            $this->db->where('id', $idfaktur);
            $this->db->where('i_document', $ifaktur);
            $this->db->where('id_company', $idcompany);
            $this->db->update('tm_nota_penjualan', $data);

        //faktur penjualan bahan baku
        }else if($jenisfaktur == '10'){
            $data = array(
                          'i_pajak'             => $ipajakk, 
                          'i_faktur_komersial'  => $ipajakawal,
            );
            $this->db->where('id', $idfaktur);
            $this->db->where('i_document', $ifaktur);
            $this->db->where('id_company', $idcompany);
            $this->db->update('tm_nota_penjualan_bb', $data);
        }
    }

    public function insertseripajak($ipajak){
        $idcompany = $this->session->userdata('id_company');

        $data = array(
                      'i_seri_pajak_awal' => $ipajak,
                      'id_company'        => $idcompany,
        );
        $this->db->insert('tr_seri_pajak', $data);
    }
}
/* End of file Mmaster.php */