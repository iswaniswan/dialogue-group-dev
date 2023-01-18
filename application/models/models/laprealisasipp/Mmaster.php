<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {
    public function bacagudang($gudang){
        $this->db->select('i_kode_master, e_nama_master');
        $this->db->from('tr_master_gudang');
        $this->db->where('i_kode_master', $gudang);
        $this->db->order_by('e_nama_master','ASC');
        return $this->db->get()->result();
    }

    public function data($dfrom,$dto,$gudang,$folder,$i_menu){
        $dfrom = date('Y-m-d', strtotime($dfrom));
        $dto   = date('Y-m-d', strtotime($dto));
        $username = $this->session->userdata('username');
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("  SELECT
                                DISTINCT ON(x.i_pp, y.i_op)
                                ROW_NUMBER() OVER(ORDER BY x.i_pp, y.i_op) as i,
                                x.i_pp,
                                to_char(x.d_pp,'dd-mm-yyyy') AS d_pp,
                                y.i_op,
                                y.e_supplier_name,
                                x.i_material,
                                c.e_material_name,
                                d.e_satuan,
                                x.n_quantity AS qtypp,
                                CASE
                                  WHEN y.n_quantity IS NULL THEN 0.00
                                  ELSE y.n_quantity
                                END AS qtyop,
                                to_char((y.n_quantity/x.n_quantity)*100,'999D99%') AS persen,
                                CASE
                                  WHEN x.n_quantity = y.n_quantity THEN 'y'
                                  WHEN y.n_quantity IS NULL THEN 'n'
                                  WHEN x.f_pp_cancel = 't' THEN 'c'
                                  ELSE 'x'
                                END AS status,
                                x.i_kode_master,
                                x.f_pp_cancel,
                                '$dfrom' AS dfrom,
                                '$dto' AS dto,
                                '$folder' AS folder,
                                '$i_menu' AS i_menu,
                                '$username' AS username
                              FROM
                                (
                                SELECT
                                  a.d_pp, a.i_kode_master, b.i_pp, b.i_material, b.n_quantity, b.i_satuan, a.f_pp_cancel
                                FROM
                                  tm_pp a
                                INNER JOIN tm_pp_item b ON
                                  (a.i_pp = b.i_pp)
                                WHERE
                                  a.i_kode_master = '$gudang'
                                  AND a.d_pp >= '$dfrom'
                                  AND a.d_pp <= '$dto' ) AS x
                              LEFT JOIN (
                                SELECT
                                  a.i_op, c.e_supplier_name, a.e_approval, b.i_pp, b.i_material, b.n_quantity, b.i_satuan
                                FROM
                                  tm_opbb a
                                INNER JOIN tm_opbb_item b ON
                                  (a.i_op = b.i_op)
                                INNER JOIN tr_supplier c ON
                                  (a.i_supplier = c.i_supplier)
                                WHERE
                                  b.i_kode_master = '$gudang'
                                  AND a.e_approval = '5'
                              ) AS y ON
                                (x.i_pp = y.i_pp
                                AND x.i_material = y.i_material)
                              INNER JOIN tr_material c ON
                                x.i_material = c.i_material
                              INNER JOIN tr_satuan d ON
                                x.i_satuan = d.i_satuan
                              ORDER BY
                                x.i_pp,
                                y.i_op ", FALSE);
        
        $datatables->add('action', function ($data) {
            $id                  = $data['i_pp'];
            $iop                 = trim($data['i_op']);
            $dfrom               = $data['dfrom'];
            $dto                 = $data['dto'];
            $gudang              = $data['i_kode_master'];
            $username            = $data['username'];
            $i_menu              = $data['i_menu'];
            $folder              = $data['folder'];
            $data                = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='View' onclick='show(\"$folder/cform/detail/$id/$iop/$dfrom/$dto/$gudang\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });
        
        $datatables->edit('i_pp', function ($data) {
            if ($data['f_pp_cancel']=='t') {
                $data = '<p class="h2 text-danger">'.$data['i_pp'].'</p>';
            }else{
                $data = $data['i_pp'];
            }
            return $data;
        });

        $datatables->edit('status', function ($data) {
            $status = trim($data['status']);
            if($status == 'y'){
              return  '<span class="label label-success label-rouded">Selesai</span>';
            }else if($status == 'n'){
              return  '<span class="label label-purple label-rouded">Belum OP</span>';
            }else if($status == 'c'){
              return  '<span class="label label-danger label-rouded">Batal</span>';
            }else{
              return '<span class="label label-warning label-rouded">Belum Selesai</span>';
            }
        });

        $datatables->hide('i_material');
        $datatables->hide('e_material_name');
        $datatables->hide('e_satuan');
        $datatables->hide('qtypp');
        $datatables->hide('qtyop');
        $datatables->hide('f_pp_cancel');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('i_kode_master');
        $datatables->hide('i_menu');
        $datatables->hide('username');
        $datatables->hide('folder');

        return $datatables->generate();
    }

    public function baca_header($ipp){
      return $this->db->query(" SELECT
                                  DISTINCT a.i_pp,
                                  to_char(d_pp, 'dd-mm-yyyy') AS d_pp,
                                  b.i_op,
                                  to_char(c.d_op,'dd-mm-yyyy') AS d_op,
                                  a.i_kode_master,
                                  f_pp_cancel,
                                  e_approve,
                                  c.e_remark
                                FROM
                                  tm_pp a
                                LEFT JOIN tm_opbb_item b ON
                                  (a.i_pp = b.i_pp)
                                LEFT JOIN tm_opbb c ON
                                  (b.i_op = c.i_op)
                                WHERE
                                  a.i_pp = '$ipp' ",FALSE);
    }

    public function baca_detail($ipp, $iop){
        $query = $this->db->query(" SELECT
                                  a.i_op,
                                  a.i_pp,
                                  a.i_material,
                                  c.e_material_name,
                                  d.e_satuan,
                                  b.n_quantity AS n_order,
                                  a.n_quantity AS n_deliver
                                FROM
                                  tm_opbb_item a
                                LEFT JOIN tm_pp_item b ON
                                  (a.i_pp = b.i_pp
                                  AND a.i_material = b.i_material)
                                INNER JOIN tr_material c ON
                                  (a.i_material = c.i_material)
                                INNER JOIN tr_satuan d ON
                                  (a.i_satuan = d.i_satuan)
                                WHERE
                                  a.i_pp = '$ipp' 
                                  AND a.i_op = '$iop'
                                ORDER BY a.i_material ",FALSE);
        if ($query->num_rows() > 0) {
          return $query->result();
       }
    }

    public function getAll($dfrom, $dto, $gudang){
      return $this->db->query(" SELECT
                                x.i_pp,
                                to_char(x.d_pp, 'dd-mm-yyyy') AS d_pp,
                                y.i_op,
                                to_char(y.d_op, 'dd-mm-yyyy') AS d_op,
                                y.e_supplier_name,
                                x.i_material,
                                c.e_material_name,
                                d.e_satuan,
                                x.n_quantity AS qtypp,
                                CASE
                                  WHEN y.n_quantity IS NULL THEN 0.00
                                  ELSE y.n_quantity
                                END AS qtyop,
                                CASE
                                  WHEN x.n_quantity = y.n_quantity THEN 'Selesai'
                                  WHEN y.n_quantity IS NULL THEN 'Belum OP'
                                  WHEN x.f_pp_cancel = 't' THEN 'Batal'
                                  ELSE 'Belum Selesai'
                                END AS status
                              FROM
                                (
                                SELECT
                                  a.d_pp, a.i_kode_master, b.i_pp, b.i_material, b.n_quantity, b.i_satuan, a.f_pp_cancel
                                FROM
                                  tm_pp a
                                INNER JOIN tm_pp_item b ON
                                  (a.i_pp = b.i_pp)
                                WHERE
                                  a.i_kode_master = '$gudang'
                                  --AND a.e_approval = '5'
                                  AND a.d_pp >= '$dfrom'
                                  AND a.d_pp <= '$dto' ) AS x
                              LEFT JOIN (
                                SELECT
                                  a.i_op, a.d_op, c.e_supplier_name, a.e_approval, b.i_pp, b.i_material, b.n_quantity, b.i_satuan
                                FROM
                                  tm_opbb a
                                INNER JOIN tm_opbb_item b ON
                                  (a.i_op = b.i_op)
                                INNER JOIN tr_supplier c ON
                                  (a.i_supplier = c.i_supplier)
                                WHERE
                                  b.i_kode_master = '$gudang'
                                  --AND a.e_approval = '6'
                                  --and a.i_supplier = 'SA001'
                              ) AS y ON
                                (x.i_pp = y.i_pp
                                AND x.i_material = y.i_material)
                              INNER JOIN tr_material c ON
                                x.i_material = c.i_material
                              INNER JOIN tr_satuan d ON
                                x.i_satuan = d.i_satuan
                              ORDER BY
                                x.i_pp,
                                y.i_op ", FALSE);
    }
}
/* End of file Mmaster.php */