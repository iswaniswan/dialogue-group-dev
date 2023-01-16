<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT a.i_product_color, a.i_product_motif, b.e_product_motifname, $i_menu as i_menu FROM tr_product_color a join tr_product_motif b on a.i_product_motif = b.i_product_motif");
        //SELECT distinct a.i_product_color, a.i_product_motif, b.e_product_motifname, $i_menu as i_menu FROM tr_product_color a, tr_product_motif b
                           // WHERE a.i_product_motif = b.i_product_motif
                           // ORDER BY a.i_product_motif

        $datatables->add('action', function ($data) {
            $iproductmotif= trim($data['i_product_motif']);
            $iproductcolor= trim($data['i_product_color']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-warnajadi/cform/view/$iproductcolor/$iproductmotif/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-warnajadi/cform/edit/$iproductcolor/$iproductmotif/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
    }

    function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_product_color');
    $this->db->join('tr_product_motif','tr_product_motif.i_product_motif = tr_product_color.i_product_motif');
    $this->db->where('tr_product_color.i_product_color', $id);
    $this->db->order_by('tr_product_color.i_product_motif', 'ASC');
    return $this->db->get();
    }

    function cek_data2($iproductcolor, $iproductmotif){
    $this->db->select('a.i_product_color, a.i_color, b.e_color_name, a.i_product_motif');
    $this->db->from('tr_product_color a');   
    $this->db->join('tr_color b', 'a.i_color = b.i_color');
    $this->db->where('a.i_product_motif', $iproductmotif);
    return $this->db->get();
    }

   /* select a.i_product_color, a.i_color, b.nama, a.i_product_motif
    from duta_prod.tr_product_color a
    join duta_prod.tm_warna  b 
    on a.i_color = b.i_color
    where a.i_product_motif = 'DGG411000'*/
    
    function get_productmotif(){
        $this->db->select('*');
        $this->db->from('tr_product_motif');
    return $this->db->get();
    }

    function get_productcolor(){
        $this->db->select('*');
        $this->db->from('tr_product_color');
    return $this->db->get();
    }

    public function insert($iproductmotif, $icolor){
      $dentry = date("Y-m-d H:i:s");
      $qwarna = $this->db->query("SELECT i_product_color FROM tr_product_color ORDER BY i_product_color DESC LIMIT 1");
                if ($qwarna->num_rows() > 0) {
                    $row_warna = $qwarna->row();
                    $iproductcolor= $row_warna->i_product_color+1;
                }
                else
                    $iproductcolor = 1;
            $data = array(
              'i_product_color' => $iproductcolor,              
              'i_product_motif' => $iproductmotif, 
              'i_color'         => $icolor,
              'd_entry'         => $dentry
        );       
        $this->db->insert('tr_product_color', $data); 
    }

    // public function delete($iproductcolor){
    //      $query = $this->db
    //     ->where('i_product_color', $iproductcolor)
    //     ->delete("tr_product_color");
      
    //   // Return hasil query
    //   return $query;
    // }

    function delete($iproductcolor){
        $this->db->query("DELETE FROM tr_product_color WHERE i_product_color='$iproductcolor'");
    }

    function insertdetail($iproductmotif, $icolor){
    $tgl = date("Y-m-d H:i:s");
    $listwarna = explode(";", $icolor);
    foreach ($listwarna as $row1) {
        if (trim($row1!= '')) {
            $sqlcek = $this->db->query(" SELECT i_product_color FROM tr_product_color 
                                     WHERE i_product_motif = '$iproductmotif' AND i_color = '$row1' ");
                if($sqlcek->num_rows() == 0) {
                    $query2 = $this->db->query(" SELECT i_product_color FROM tr_product_color ORDER BY i_product_color DESC LIMIT 1 ");
                    $hasilrow = $query2->row();
                    $i_product_color    = $hasilrow->i_product_color;
                    $new_i_product_color = $i_product_color+1;
                    
                    $datanya    = array(
                             'i_product_color'=>$new_i_product_color,
                             'i_product_motif'=>$iproductmotif,
                             'i_color'=>$row1,
                             'd_entry'=>$tgl,
                             'd_update'=>$tgl);
                     $this->db->insert('tr_product_color',$datanya);
                }
            }
        }
    }

    public function update($iproductcolor, $iproductmotif, $icolor){
        $dupdate = date("Y-m-d H:i:s");
        
        $data = array(
              'i_product_color' => $iproductcolor,
              'i_color'         => $icolor,
              'i_product_motif' => $iproductmotif,  
              'd_update'        => $dupdate,  
    );

    $this->db->where('i_product_color', $iproductcolor);
    $this->db->update('tr_product_color', $data);
    }
}
/* End of file Mmaster.php */