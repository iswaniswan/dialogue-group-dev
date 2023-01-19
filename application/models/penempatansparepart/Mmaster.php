<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($folder, $i_menu, $dfrom, $dto){
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_penempatan_sparepart
            WHERE
                i_status <> '5'
                AND id_company = '$this->company'
                $where 
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = '$this->company')
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
                        AND id_company = '$this->company')";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                0 AS NO,
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                d.e_bagian_name,
                a.e_remark,
                a.i_status,
                c.e_status_name,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto,
                c. label_color
            FROM
                tm_penempatan_sparepart a
            INNER JOIN tr_status_document c ON
                (c.i_status = a.i_status)
            INNER JOIN tr_bagian d ON
                (a.i_bagian = d.i_bagian
                    AND a.id_company = d.id_company)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '$this->company' 
                $where
                $bagian
            ORDER BY
                a.d_document DESC   
          
        ",false);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id        = trim($data['id']);
            $i_menu    = $data['i_menu'];
            $folder    = $data['folder'];
            $i_status  = $data['i_status'];
            $dfrom     = $data['dfrom'];
            $dto       = $data['dto'];
            $data      = '';

            if(check_role($i_menu, 2)){
                $data     .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }   

            if (check_role($i_menu, 5)) {
                if ($i_status == '6') {
                    $data .= "<a href=\"#\" title='Print' onclick='cetak($id); return false;'><i class='ti-printer'></i></a>&nbsp;&nbsp;&nbsp;";
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

  public function bagian() {
        $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
        $this->db->where('a.f_status','t');
        $this->db->where('i_departement', $this->departement);
        $this->db->where('i_level', $this->level);
        $this->db->where('username', $this->username);
        $this->db->where('b.id_company', $this->company);
        $this->db->where('a.id_company', $this->company);
        $this->db->where('a.i_type', '19');
        $this->db->order_by('e_bagian_name');
        return $this->db->get();
  }

  public function kelompok($cari,$ibagian){
        $cari = str_replace("'", "", $cari);
        if ($this->departement!='1') {
            $bagian = "AND i_bagian = '$ibagian'";
        }else{
            $bagian = "";
        }
        return $this->db->query("
            SELECT
                i_kode_kelompok,
                e_nama_kelompok
            FROM
                tr_kelompok_barang
            WHERE
                f_status = 't'
                AND i_kode_kelompok IN (
                SELECT
                    i_kode_kelompok
                FROM
                    tr_bagian_kelompokbarang
                WHERE
                    e_nama_kelompok ILIKE '%$cari%'
                    AND id_company = '$this->company'
                    $bagian )
                AND id_company = '$this->company'
            ORDER BY
                e_nama_kelompok
        ", FALSE);
    }

    public function jenis($cari,$ikelompok,$ibagian)
    {
        $jenis = "";
        if ($this->departement!='1') {
            if (($ikelompok != '' || $ikelompok != null) && $ikelompok!='all') {
                $jenis = "AND i_kode_kelompok = '$ikelompok' ";
            }else{
                $jenis = "AND i_kode_kelompok IN 
                (SELECT
                    i_kode_kelompok
                FROM
                    tr_bagian_kelompokbarang
                WHERE
                    i_bagian = '$ibagian'
                    AND id_company = '$this->company')";
            }
        }
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                DISTINCT i_type_code,
                e_type_name
            FROM
                tr_item_type
            WHERE
                e_type_name ILIKE '%$cari%'
                AND f_status = 't'
                AND id_company = '$this->company'
                $jenis
            ORDER BY
                e_type_name
        ", FALSE);
    }

    public function material($cari,$ikategori,$ijenis,$ibagian)
    {
        $kategori = "";
        $jenis    = "";
        if ($this->departement!='1') {
            if (($ikategori != '' || $ikategori != null) && $ikategori!='all') {
                $kategori = "AND i_kode_kelompok = '$ikategori' ";
            }else{
                $kategori = "AND i_kode_kelompok 
                IN (SELECT
                        i_kode_kelompok
                    FROM
                        tr_bagian_kelompokbarang
                    WHERE
                        i_bagian = '$ibagian'
                        AND id_company = '$this->company')";
            }

            if (($ijenis != '' || $ijenis != null) && $ijenis!='all') {
                $jenis = "AND i_type_code = '$ijenis' ";
            }else{
                $jenis = "AND i_type_code 
                IN (SELECT
                        i_type_code
                    FROM
                        tr_item_type
                    WHERE
                        f_status = 't'
                        AND id_company = '$this->company'
                        AND i_kode_kelompok IN 
                            (SELECT
                                i_kode_kelompok
                            FROM
                                tr_bagian_kelompokbarang
                            WHERE
                                i_bagian = '$ibagian'
                                AND id_company = '$this->company'))";
            }
        }
        return $this->db->query("
            SELECT
                a.id,
                a.i_material,
                a.e_material_name,
                a.i_kode_kelompok,
                b.e_satuan_name,
                a.i_satuan_code
            FROM
                tr_material a,
                tr_satuan b
            WHERE
                a.i_satuan_code = b.i_satuan_code
                AND a.f_status = 't'
                AND (i_material ILIKE '%$cari%' 
                OR e_material_name ILIKE '%$cari%')
                AND a.id_company = '$this->company'
                AND b.id_company = '$this->company'
                $kategori
                $jenis
            ORDER BY
                i_material
        ", FALSE);
    }

    public function runningnumber($thbl,$tahun,$ibagian) 
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 3) AS kode 
            FROM tm_penempatan_sparepart 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'PPS';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 10, 6)) AS max
            FROM
                tm_penempatan_sparepart
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$this->company'
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

    public function cek_kode($kode,$ibagian) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_penempatan_sparepart');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold) 
    {
        $this->db->select('i_document');
        $this->db->from('tm_penempatan_sparepart');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    /*----------  SIMPAN DATA  ----------*/
    
    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_penempatan_sparepart');
        return $this->db->get()->row()->id+1;
    }

    public function simpan($id,$idocument,$ddocument,$ibagian,$eremarkh)
    {
        $data = array(
            'id'            => $id,
            'id_company'    => $this->company,
            'i_document'    => $idocument,
            'd_document'    => $ddocument,
            'i_bagian'      => $ibagian,
            'e_remark'      => $eremarkh,
        );
        $this->db->insert('tm_penempatan_sparepart', $data);
    }

    public function simpandetail($id,$idproduct,$jumlah,$iventaris,$tujuan)
    {
        $data = array(
            'id_company'    => $this->company,
            'id_document'   => $id,
            'id_product'    => $idproduct,
            'n_jumlah'      => $jumlah,
            'e_inventaris'  => $iventaris,
            'e_tujuan'      => $tujuan,
        );
        $this->db->insert('tm_penempatan_sparepart_item', $data);
    }

    /*----------  DATA EDIT HEADER  ----------*/

    public function dataedit($id) {
        return $this->db->query("
            SELECT
                a.id,
                a.i_bagian,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                a.e_remark,
                b.e_bagian_name,
                a.i_status
            FROM
                tm_penempatan_sparepart a
            INNER JOIN tr_bagian b ON (b.i_bagian = a.i_bagian AND a.id_company = b.id_company)
            WHERE a.id = '$id'
            AND a.id_company = '$this->company'
        ", FALSE);
    }

    /*----------  DATA EDIT DETAIL  ----------*/

    public function dataeditdetail($id) {
        return $this->db->query("
            SELECT
                a.*,
                c.i_material AS i_product,
                c.e_material_name AS e_product
            FROM tm_penempatan_sparepart_item a
            INNER JOIN tr_material c ON (c.id = a.id_product)
            WHERE a.id_document = '$id' AND a.id_company = '$this->company'
            ORDER BY a.id ASC
        ", FALSE);
    }


    public function updateheader($id,$idocument,$ddocument,$ibagian,$eremarkh)
    {
        $data = array(
            'i_document'   => $idocument,
            'd_document'   => $ddocument,
            'i_bagian'     => $ibagian,
            'e_remark'     => $eremarkh,
            'd_update'     => current_datetime(),
        );
        $this->db->where('id', $id);
        $this->db->update('tm_penempatan_sparepart', $data);
    }

    public function deletedetail($id){
        $this->db->query("DELETE FROM tm_penempatan_sparepart_item WHERE id_document='$id'", false);
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
                'i_status' => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_penempatan_sparepart', $data);
    }  
}
/* End of file Mmaster.php */
