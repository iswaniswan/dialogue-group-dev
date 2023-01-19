<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$folder,$dfrom,$dto){
        $idcompany  = $this->session->userdata('id_company');
        if ($dfrom!='' && $dto!='') {
              $dfrom = date('Y-m-d', strtotime($dfrom));
              $dto   = date('Y-m-d', strtotime($dto));
              $where = "AND d_keluar_packing BETWEEN '$dfrom' AND '$dto'";
        }else{
                $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
          
        $cek = $this->db->query("
                SELECT
                    i_bagian
                FROM
                    tm_keluar_packing
                WHERE
                    i_status <> '5'
                    AND id_company = '".$this->session->userdata('id_company')."'
                    $where
                    AND i_bagian IN (
                        SELECT
                            i_bagian
                        FROM
                            tr_departement_cover
                        WHERE
                            i_departement = '".$this->session->userdata('i_departement')."'
                            AND id_company = '".$this->session->userdata('id_company')."'
                            AND username = '".$this->session->userdata('username')."')

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
                            AND id_company = '".$this->session->userdata('id_company')."'
                            AND username = '".$this->session->userdata('username')."')";
                }
            }

        $datatables->query("
                            SELECT
                               0 as no,
                               a.id,
                               a.i_keluar_packing,
                               to_char(a.d_keluar_packing, 'dd-mm-yyyy') as d_keluar_packing,
                               a.i_tujuan,
                               b.e_bagian_name,
                               a.d_receive_gdjd,
                               a.e_remark,
                               a.id_company,
                               a.i_status,
                               c.e_status_name,
                               a.i_bagian,
                               c.label_color as label,
                               '$dfrom' AS dfrom,
                               '$dto' AS dto,
                               '$i_menu' as i_menu,
                               '$folder' AS folder
                            FROM
                               tm_keluar_packing a 
                               JOIN
                                  tr_bagian b 
                                  ON (a.i_tujuan = b.i_bagian AND a.id_company = b.id_company) 
                               JOIN
                                  tr_status_document c 
                                  ON (a.i_status = c.i_status) 
                               WHERE 
                                  a.id_company = '$idcompany'
                               AND
                                  a.i_status <> '5'
                            $where
                            $bagian
                            ORDER BY
                               a.i_keluar_packing asc
                          ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status_name'].'</span>';            
        });

        $datatables->add('action', function ($data) {
            $id           = trim($data['id']);
            $i_status     = trim($data['i_status']);
            $itujuan      = trim($data['i_tujuan']);
            $folder       = $data['folder'];
            $dfrom        = $data['dfrom'];
            $dto          = $data['dto'];
            $i_menu       = $data['i_menu'];
            
            $data       = '';
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
      
        $datatables->hide('folder'); 
        $datatables->hide('i_menu');
        $datatables->hide('label');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('id_company');
        $datatables->hide('id');
        $datatables->hide('i_status');
        $datatables->hide('i_bagian');
        $datatables->hide('i_tujuan');

        return $datatables->generate();
    }

    public function estatus($istatus){
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status',$istatus);
        return $this->db->get()->row()->e_status_name;
    }

    public function changestatus($id,$istatus){
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_keluar_packing', $data);
    }

    public function bagian(){
      $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
      $this->db->where('i_departement', $this->session->userdata('i_departement'));
      $this->db->where('username', $this->session->userdata('username'));
      $this->db->where('a.id_company', $this->session->userdata('id_company'));
      $this->db->order_by('e_bagian_name');
      return $this->db->get();
    }

    public function cek_kode($kode,$ibagian){
        $this->db->select('i_keluar_packing');
        $this->db->from('tm_keluar_packing');
        $this->db->where('i_keluar_packing', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningnumber($thbl,$tahun,$ibagian){
    //var_dump($thbl);
        $cek = $this->db->query("
            SELECT 
                substring(i_keluar_packing, 1, 3) AS kode 
            FROM tm_keluar_packing
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '".$this->session->userdata("id_company")."'
            ORDER BY id DESC");
        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'BBK';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_keluar_packing, 10, 6)) AS max
            FROM
                tm_keluar_packing
            WHERE to_char (d_keluar_packing, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_keluar_packing, 1, 3) = '$kode'
            AND substring(i_keluar_packing, 5, 2) = substring('$thbl',1,2)
            AND id_company = '".$this->session->userdata("id_company")."'
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

    public function runningid(){
        $this->db->select('max(id) AS id');
        $this->db->from('tm_keluar_packing');
        return $this->db->get()->row()->id+1;
    }

    public function tujuan($i_menu, $idcompany){      
        return $this->db->query("   
                                    SELECT
                                       * 
                                    FROM
                                       tr_bagian 
                                    WHERE
                                       i_type = 
                                       (
                                          SELECT
                                             i_type 
                                          FROM
                                             tr_bagian 
                                          WHERE
                                             i_bagian = 
                                             (
                                                SELECT
                                                   i_bagian 
                                                FROM
                                                   tr_tujuan_menu 
                                                WHERE
                                                   i_menu = '$i_menu' 
                                                   AND id_company = '$idcompany'
                                             )
                                       )
                                       AND id_company='$idcompany'
                                ", FALSE);
    }

    public function dataproduct($cari){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("    
                                    SELECT  
                                        a.id,
                                        a.i_product_base,
                                        a.e_product_basename,
                                        a.i_color,
                                        b.e_color_name
                                    FROM
                                        tr_product_base a
                                    INNER JOIN tr_color b ON
                                        (b.i_color = a.i_color AND a.id_company = b.id_company)
                                    WHERE
                                        a.id_company = '$idcompany'
                                    AND
                                        (upper(a.i_product_base) LIKE '%$cari%'
                                        OR upper(a.e_product_basename) LIKE '%$cari%') ", FALSE);
    }

    public function getproduct($eproduct){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("            
                                    SELECT 
                                        a.id as id_product, 
                                        a.i_product_base,
                                        a.e_product_basename,
                                        b.id as id_color,
                                        a.i_color,
                                        b.e_color_name
                                    FROM
                                        tr_product_base a
                                    INNER JOIN tr_color b ON
                                        (b.i_color = a.i_color AND a.id_company = b.id_company)
                                    WHERE
                                        a.id_company = '$idcompany'
                                    AND 
                                        a.id = '$eproduct'
                              ", FALSE);
    }
      
    public function insertheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark){ 
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
                        'id_company'        => $idcompany,
                        'id'                => $id,
                        'i_keluar_packing'  => $ibonk,
                        'd_keluar_packing'  => $datebonk,
                        'i_bagian'          => $ibagian,
                        'i_tujuan'          => $itujuan,
                        'e_remark'          => $eremark,
                        'd_entry'           => current_datetime(),
        );
        $this->db->insert('tm_keluar_packing', $data);
    }

    public function insertdetail($id, $iproduct, $icolor, $nqtyproduct, $edesc){
        $idcompany  = $this->session->userdata('id_company');
        $data = array(
                        'id_keluar_packing' => $id,
                        'id_product'        => $iproduct,
                        'id_color'          => $icolor,
                        'n_quantity_product'=> $nqtyproduct,
                        'n_sisa'            => $nqtyproduct,
                        'id_company'        => $idcompany,
                        'e_remark'          => $edesc,
        );
        $this->db->insert('tm_keluar_packing_item', $data);
    }    

    public function cek_data($id, $idcompany){
        return $this->db->query(" 
                                    SELECT
                                        a.id,
                                        a.i_keluar_packing,
                                        to_char(a.d_keluar_packing, 'dd-mm-yyyy') as d_keluar_packing,
                                        a.i_bagian,
                                        a.i_tujuan,
                                        a.i_status,
                                        a.e_remark
                                    FROM
                                       tm_keluar_packing a
                                    WHERE                                        
                                        a.id = '$id'
                                    AND
                                        a.id_company = '$idcompany' 
                                ", FALSE);
    }

    public function cek_datadetail($id, $idcompany){
        return $this->db->query(" 
                                    SELECT
                                       a.id_keluar_packing,
                                       a.id_product,
                                       b.i_product_base,
                                       b.e_product_basename,
                                       a.id_color,
                                       c.i_color,
                                       c.e_color_name,
                                       a.n_quantity_product,
                                       a.e_remark 
                                    FROM
                                       tm_keluar_packing_item a 
                                       JOIN
                                          tm_keluar_packing d 
                                          ON a.id_keluar_packing = d.id 
                                       JOIN
                                          tr_product_base b 
                                          ON a.id_product = b.id 
                                          AND d.id_company = b.id_company 
                                       JOIN
                                          tr_color c 
                                          ON a.id_color = c.id 
                                          AND d.id_company = c.id_company 
                                    WHERE
                                       a.id_keluar_packing = '$id' 
                                       AND d.id_company = '$idcompany'
                                    ", FALSE);
    }

    public function updateheader($id, $ibonk, $ibagian, $datebonk, $itujuan, $eremark){
        $idcompany  = $this->session->userdata('id_company');   
        $data = array(
                        'i_keluar_packing'      => $ibonk,
                        'd_keluar_packing'      => $datebonk,
                        'i_bagian'              => $ibagian,
                        'i_tujuan'              => $itujuan,
                        'e_remark'              => $eremark,
                        'd_update'              => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->where('id_company', $idcompany);
        $this->db->update('tm_keluar_packing', $data);
    }

    public function deletedetail($id){ 
        $idcompany  = $this->session->userdata('id_company');    
        $this->db->where('id_keluar_packing',$id);
        $this->db->where('id_company',$idcompany);
        $this->db->delete('tm_keluar_packing_item');
    }
}
/* End of file Mmaster.php */