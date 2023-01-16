<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function data($bulan,$tahun,$iarea){
        $iperiode   = $tahun.$bulan ;
        $store = $iarea;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        SELECT 
                           x.i_product, 
                           x.e_product_name, 
                           x.e_product_groupname,
                           x.i_store, 
                           x.i_store_location, 
                           x.i_product_group,  
                           SUM(x.jumlah) AS jumlah, 
                           SUM(x.sisasaldo) AS sisasaldo, 
                           SUM(x.git) AS git 
                        FROM (
                              SELECT 
                                 i.i_store, 
                                 i.i_store_location, 
                                 g.i_product, 
                                 g.e_product_name, 
                                 d.i_product_group, 
                                 d.e_product_groupname,
                                 SUM(i.n_mutasi_penjualan) AS jumlah, 
                                 SUM(i.n_saldo_akhir) AS sisasaldo, 
                                 SUM((i.n_mutasi_git+n_git_penjualan)) AS git
                              FROM 
                                 tr_product_group d, 
                                 tr_product g, 
                                 tr_product_type h , 
                                 tm_mutasi i
                              WHERE 
                                 i.e_mutasi_periode='$iperiode' 
                                 AND i.i_product=g.i_product 
                                 AND (i.i_store_location<>'PB' and i.i_store<>'PB')
                                 AND g.i_product_type = h.i_product_type 
                                 AND h.i_product_group = d.i_product_group 
                                 AND i.i_store = '$store'
                              GROUP BY 
                                 i.i_store, 
                                 i.i_store_location, 
                                 d.i_product_group, 
                                 d.e_product_groupname, 
                                 g.i_product
                              UNION ALL
                              SELECT 
                                 i.i_store, 
                                 i.i_store_location, 
                                 g.i_product, 
                                 g.e_product_name, 
                                 'PB' as i_product_group, 
                                 'Modern Outlet ' AS e_product_groupname, 
                                 SUM(i.n_mutasi_penjualan) AS jumlah, 
                                 SUM(i.n_saldo_akhir) AS sisasaldo, 
                                 SUM((i.n_mutasi_git+n_git_penjualan)) AS git
                              FROM 
                                 tr_product_group d, 
                                 tr_product g, tr_product_type h , 
                                 tm_mutasi i
                              WHERE 
                                 i.e_mutasi_periode='$iperiode' 
                                 AND i.i_product=g.i_product 
                                 AND (i.i_store_location='PB' OR i.i_store='PB')
                                 AND g.i_product_type = h.i_product_type 
                                 AND h.i_product_group = d.i_product_group 
                                 AND i.i_store = '$store'
                              GROUP BY 
                                 i.i_store, 
                                 i.i_store_location, 
                                 d.i_product_group, 
                                 d.e_product_groupname, 
                                 g.i_product
                        ) x 
                        GROUP BY 
                           x.i_store, 
                           x.i_store_location, 
                           x.i_product_group, 
                           x.e_product_groupname, 
                           x.i_product, 
                           x.e_product_name
                        ORDER BY 
                           x.i_store, 
                           x.e_product_groupname, 
                           x.i_product"
                , FALSE);

        /*$datatables->edit('jumlah', function ($data) {
            return number_format($data['jumlah'],2);
        });
        $datatables->edit('jml_netto', function ($data) {
            return number_format($data['jml_netto'],2);
        });*/
        $datatables->hide('i_product_group');
        $datatables->hide('i_store_location');
        $datatables->hide('i_store');
        return $datatables->generate();
    }

    public function bacaarea($username, $idcompany){
      return $this->db->query("
          SELECT
              *
          FROM
              tr_area
          WHERE
              i_area IN (
              SELECT
                  i_area
              FROM
                  public.tm_user_area
              WHERE
                  username = '$username'
                  AND id_company = '$idcompany')
      ", FALSE)->result();
  }

    public function total($bulan, $tahun, $iarea){   
      $iperiode   = $tahun.$bulan ;
      $store = $iarea;
        return $this->db->query("
                              SELECT
                                SUM(i.n_mutasi_penjualan) AS jumlah, 
                                SUM(i.n_saldo_akhir) AS sisasaldo, 
                                SUM((i.n_mutasi_git+n_git_penjualan)) AS git 
                              FROM 
                                tr_product_group d, 
                                tr_product g, 
                                tr_product_type h , 
                                tm_mutasi i
                              WHERE 
                                i.e_mutasi_periode='$iperiode' 
                                AND i.i_product=g.i_product 
                                AND (i.i_store_location<>'PB' and i.i_store<>'PB')
                                AND g.i_product_type = h.i_product_type 
                                AND h.i_product_group = d.i_product_group 
                                AND i.i_store = '$store'"
                                , FALSE);
    }
}

/* End of file Mmaster.php */
