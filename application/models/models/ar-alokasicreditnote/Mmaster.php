<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    /*----------  DAFTAR DATA SPB  ----------*/    

    public function data($folder,$i_menu,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_alokasi_kn a
            WHERE
                i_status <> '5'
                AND id_company = $this->company
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company)
            ", FALSE);
        if ($this->departement=='1') {
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
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company) ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                b.e_customer_name,
                f.i_document AS i_referensi,
                a.v_jumlah,
                a.v_lebih,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_alokasi_kn a
            INNER JOIN tr_customer b ON
                (a.id_customer = b.id)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tm_kn f ON
                (f.id = a.id_referensi
                AND a.id_company = f.id_company)
            WHERE
                a.i_status <> '5'
                AND a.id_company = $this->company
                $and
                $bagian
            ORDER BY
                a.id DESC
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('v_jumlah', function ($data) {
            return number_format($data['v_jumlah']);
        });

        $datatables->edit('v_lebih', function ($data) {
            return number_format($data['v_lebih']);
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
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7) && ($i_status=='2')) {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
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

    /*----------  DAFTAR DATA KN  ----------*/    

    public function datakn($folder,$i_menu,$dfrom,$dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_kn a
            WHERE
                i_status <> '5'
                AND id_company = $this->company
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company)
            ", FALSE);
        if ($this->departement=='1') {
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
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company) ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                DISTINCT 0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                b.e_customer_name,
                f.i_document AS i_referensi,
                a.v_bersih AS v_nilai,
                a.v_sisa,
                a.e_remark,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_kn a
            INNER JOIN tr_customer b ON
                (a.id_customer = b.id)
            INNER JOIN tr_status_document d ON
                (d.i_status = a.i_status)
            INNER JOIN tm_bbm_retur f ON
                (f.id = a.id_referensi
                AND a.id_company = f.id_company)
            WHERE
                a.i_status = '6'
                AND a.id_company = $this->company
                AND a.v_sisa > 0
                $and
                $bagian
            ORDER BY
                a.id DESC
        ", FALSE);

        $datatables->edit('v_nilai', function ($data) {
            return number_format($data['v_nilai']);
        });

        $datatables->edit('v_sisa', function ($data) {
            return number_format($data['v_sisa']);
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            
            if(check_role($i_menu, 1)){
                $data .= "<a href=\"#\" title='Tambah Data Alokasi' onclick='show(\"$folder/cform/tambah/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-new-window'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    /*----------  DATA HEADER KN  ----------*/
    
    public function getdatakn($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                v_sisa,
                a.id_customer,
                b.i_customer,
                b.e_customer_name,
                b.e_customer_address,
                b.id_city,
                c.e_city_name
            FROM
                tm_kn a
            INNER JOIN tr_customer b ON
                (b.id = a.id_customer AND 
                a.id_company = b.id_company)
            INNER JOIN tr_city c ON
                (c.id = b.id_city)
            WHERE
                v_sisa > 0
                AND i_status = '6'
                AND a.id = $id
                AND a.id_company = '$this->company'
            ", FALSE);
    }

    /*----------  BAGIAN PEMBUAT SESUAI SESSION  ----------*/    

    public function bagian()
    {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('a.f_status', 't');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('username', $this->username);
        $this->db->where('a.id_company', $this->company);
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
    }

    /*----------  RUNNING NOMOR DOKUMEN  ----------*/
    
    public function runningnumber($thbl,$tahun,$ibagian) 
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode
            FROM tm_alokasi_kn
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'AKR';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_alokasi_kn
            WHERE 
                i_status <> '5'
                AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND substring(i_document, 1, 3) = '$kode'
                AND substring(i_document, 5, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
            ", FALSE);
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

    /*----------  CEK DOKUMEN SUDAH ADA  ----------*/

    public function cek_kode($kode,$ibagian) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_alokasi_kn');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  CARI REFERENSI  ----------*/
    
    public function referensi($cari,$idcustomer)
    {
        return $this->db->query("
            SELECT
                DISTINCT id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document
            FROM
                tm_nota_penjualan
            WHERE
                i_status = '6'
                AND id_customer = '$idcustomer'
                AND v_sisa > 0
                AND (i_document ILIKE '%$cari%')
                AND id_company = '$this->company'
            ORDER BY
                3,
                2
            ", FALSE);
    }

    /*----------  GET DETAIL REFERENSI  ----------*/    

    public function getdetailref($idnota,$idcustomer)
    {
        return $this->db->query("
            SELECT
                DISTINCT id,
                i_document,
                to_char(d_document, 'dd-mm-yyyy') AS d_document,
                v_sisa
            FROM
                tm_nota_penjualan
            WHERE
                i_status = '6'
                AND id_customer = '$idcustomer'
                AND v_sisa > 0
                AND id = $idnota
                AND id_company = '$this->company'
            ", FALSE);
    }

    /*----------  RUNNING ID  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_alokasi_kn');
        return $this->db->get()->row()->id+1;
    }

    /*----------  SIMPAN DATA HEADER ----------*/

    public function insertheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomer,$idreferensi,$vjumlah,$vlebih,$eremarkh)
    {
        $data = array(
            'id'              => $id,
            'id_company'      => $this->company,
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'id_customer'     => $idcustomer,
            'e_customer_name' => $ecustomer,
            'id_referensi'    => $idreferensi,
            'v_jumlah'        => $vjumlah,
            'v_lebih'         => $vlebih,
            'e_remark'        => $eremarkh,
        );
        $this->db->insert('tm_alokasi_kn', $data);
    }

    /*----------  SIMPAN DATA ITEM  ----------*/
    
    public function insertdetail($id,$idreferensi,$idnota,$vbayar,$vsisa,$eremark)
    {
        $data = array(
            'id_company'        => $this->company,
            'id_document'       => $id,
            'id_referensi'      => $idreferensi,
            'id_referensi_nota' => $idnota,
            'v_jumlah'          => $vbayar,
            'v_sisa'            => $vsisa,
            'e_remark'          => $eremark,
        );
        $this->db->insert('tm_alokasi_kn_item', $data);
    }

    /*----------  GET VIEW, EDIT & APPROVE HEADER  ----------*/
    
    public function editheader($id)
    {
        return $this->db->query("
            SELECT
                a.id,
                a.id_referensi,
                a.i_bagian,
                e_bagian_name,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                c.i_document AS i_referensi,
                to_char(c.d_document, 'dd-mm-yyyy') AS d_referensi,
                a.id_customer,
                i_customer,
                e.e_customer_name,
                a.e_remark,
                a.i_status,
                a.v_jumlah,
                a.v_lebih,
                e.e_customer_address,
                f.e_city_name
            FROM
                tm_alokasi_kn a
            INNER JOIN tr_bagian b ON
                (b.i_bagian = a.i_bagian
                AND a.id_company = b.id_company)
            INNER JOIN tm_kn c ON
                (c.id = a.id_referensi)
            INNER JOIN tr_customer e ON
                (e.id = a.id_customer
                AND a.id_company = e.id_company)
            INNER JOIN tr_city f ON
                (f.id = e.id_city)
            WHERE
                a.id = $id
        ", FALSE);
    }

    /*----------  GET VIEW, EDIT & APPROVE ITEM  ----------*/
    
    public function edititem($id)
    {
        return $this->db->query("
            SELECT
                DISTINCT a.*,
                b.v_bersih AS v_sisa_nota,
                b.i_document AS i_nota,
                b.id AS id_nota,
                to_char(b.d_document, 'dd-mm-yyyy') AS d_nota
            FROM
                tm_alokasi_kn_item a
            INNER JOIN tm_nota_penjualan b ON
                (b.id = a.id_referensi_nota
                AND a.id_company = b.id_company)
            WHERE
                a.id_document = $id
            ORDER BY
                a.id
        ", FALSE);
    }

    /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/

    public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_alokasi_kn');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  DELETE DETAIL PAS EDIT  ----------*/
    
    public function delete($id)
    {
        $this->db->where('id_document', $id);
        $this->db->delete('tm_alokasi_kn_item');
    }

    /*----------  UPDATE HEADER  ----------*/
    
    public function updateheader($id,$idocument,$ddocument,$ibagian,$idcustomer,$ecustomer,$idreferensi,$vjumlah,$vlebih,$eremarkh)
    {
        $data = array(
            'id_company'      => $this->company,
            'i_document'      => $idocument,
            'd_document'      => $ddocument,
            'i_bagian'        => $ibagian,
            'id_customer'     => $idcustomer,
            'e_customer_name' => $ecustomer,
            'id_referensi'    => $idreferensi,
            'v_jumlah'        => $vjumlah,
            'v_lebih'         => $vlebih,
            'e_remark'        => $eremarkh,
        );
        $this->db->where('id', $id);
        $this->db->update('tm_alokasi_kn', $data);
    }

    /*----------  UPDATE SISA KN  ----------*/

    public function updatesisakn($id)
    {
        $query = $this->db->query("
            SELECT 
                id_referensi, 
                sum(v_jumlah) AS v_jumlah
            FROM 
                tm_alokasi_kn_item
            WHERE 
                id = '$id'
                AND id_company = '$this->company'
            GROUP BY 
                1
            ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $nsisa = $this->db->query("
                    SELECT
                        v_sisa
                    FROM
                        tm_kn
                    WHERE
                        id              = '$key->id_referensi'
                        AND id_company  = '$this->company'
                        AND v_sisa      >= '$key->v_jumlah'
                ", FALSE);

                if ($nsisa->num_rows() > 0) {
                    $this->db->query("
                        UPDATE
                            tm_kn
                        SET
                            v_sisa = v_sisa - $key->v_jumlah
                        WHERE
                            id              = '$key->id_referensi'
                            AND id_company  = '$this->company'
                            AND v_sisa      >= '$key->v_jumlah'
                        ", FALSE);
                } else {
                    die();
                }
            }
        }
    }    

    /*----------  UPDATE SISA NOTA  ----------*/

    public function updatesisanota($id)
    {
        $query = $this->db->query("
            SELECT 
                id_referensi_nota, 
                v_jumlah
            FROM 
                tm_alokasi_kn_item
            WHERE 
                id_document = '$id'
                AND id_company = '$this->company'
            ", FALSE);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key) {
                $nsisa = $this->db->query("
                    SELECT
                        v_sisa
                    FROM
                        tm_nota_penjualan
                    WHERE
                        id              = '$key->id_referensi_nota'
                        AND id_company  = '$this->company'
                        AND v_sisa      >= '$key->v_jumlah'
                ", FALSE);

                if ($nsisa->num_rows() > 0) {
                    $this->db->query("
                        UPDATE
                            tm_nota_penjualan
                        SET
                            v_sisa = v_sisa - $key->v_jumlah
                        WHERE
                            id              = '$key->id_referensi_nota'
                            AND id_company  = '$this->company'
                            AND v_sisa      >= '$key->v_jumlah'
                        ", FALSE);
                } else {
                    die();
                }
            }
        }
    }    

    /*----------  RUBAH STATUS  ----------*/
    
    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }
    
    public function changestatus($id,$istatus)
    {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->username,
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_alokasi_kn', $data);
    }

    /*----------  END RUBAH STATUS  ----------*/
}
/* End of file Mmaster.php */