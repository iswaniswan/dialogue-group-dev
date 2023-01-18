<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT tr_product_price.i_product_price, tr_product_price.i_product, tr_product_price.i_product_motif, tr_product_price.v_price, $i_menu as i_menu FROM tr_product_price");

        $datatables->add('action', function ($data) {
            $iproductprice = trim($data['i_product_price']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-hargajadimotif/cform/view/$iproductprice/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-hargajadimotif/cform/edit/$iproductprice/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
    }

    function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_product_price');
    $this->db->join('tr_product_base', 'tr_product_base.i_product_base = tr_product_price.i_product');
    $this->db->join('tr_product_motif', 'tr_product_motif.i_product_motif = tr_product_price.i_product_motif');
    $this->db->where('tr_product_price.i_product_price', $id);

    return $this->db->get();
    }

    function get_barangjadi(){
        $this->db->select('a.i_product, a.i_product_motif, a.e_product_motifname, b.v_unitprice');
        $this->db->from('tr_product_motif a');
        $this->db->join('tr_product_base b', 'b.i_product_base = a.i_product');
        $this->db->where('a.n_active = 1');
        $this->db->order_by('a.i_product_motif', 'ASC');
    return $this->db->get();
    }

    /*public function kode(){
        $qry_price =$this->db->query ("SELECT i_product_price FROM tr_product_price ORDER BY i_product_price DESC LIMIT 1");
    }*/
    public function insert($iproductprice, $iproduct, $iproductmotif, $vprice){
        $dentry = date("d F Y H:i:s");
        $kodeharga = $this->db->query("SELECT i_product_price FROM tr_product_price ORDER BY i_product_price DESC LIMIT 1");
        if ($kodeharga->num_rows() > 0) {
            $row_kode = $kodeharga->row();

            $kode1  = $row_kode->i_product_price; // H00001
            $kode2  = substr($kode1,1,strlen($kode1)-1); // 00001
            $kodejml= $kode2+1;
           var_dump($kodejml);
            switch(strlen($kodejml)) {
              case 1:
                $icode  = 'HJM'.'000'.$kodejml;
              break;
              case 2:
                $icode  = 'HJM'.'00'.$kodejml;
              break;
              case 3:
                $icode  = 'HJM'.'0'.$kodejml;
              break;
              default:
                $icode  = 'HJM'.$kodejml;
            }        
            $iproductprice = $icode;
        }else
        $iproductprice = "HJM0001";

        $data = array(
              'i_product_price'   => $iproductprice, 
              'i_product'         => $iproduct,
              'i_product_motif'   => $iproductmotif, 
              'v_price'           => $vprice, 
              'd_entry'           => $dentry
    );
    
    $this->db->insert('tr_product_price', $data);
    }

    public function update($iproductprice, $iproduct, $iproductmotif, $vprice){
        $dupdate = date("d F Y H:i:s");
        $data = array(
              'i_product_price'   => $iproductprice, 
              'i_product'         => $iproduct,
              'i_product_motif'   => $iproductmotif, 
              'v_price'           => $vprice,   
              'd_update'          => $dupdate, 

    );

    $this->db->where('i_product_price', $iproductprice);
    $this->db->update('tr_product_price', $data);
    }

}

/* End of file Mmaster.php */