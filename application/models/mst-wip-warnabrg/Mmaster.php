<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("SELECT distinct a.i_warna, a.i_kodebrg, b.e_namabrg, $i_menu as i_menu
            FROM tm_warna_wip a, tm_barang_wip b where a.i_kodebrg = b.i_kodebrg 
            order by a.i_kodebrg");

            //"SELECT tm_warna_wip.i_warna, tm_warna_wip.i_kodebrg, tm_warna_wip.d_entry, tm_warna_wip.d_update, $i_menu as i_menu FROM tm_warna_wip");
        

        $datatables->add('action', function ($data) {
            $iwarna= trim($data['i_warna']);
            $ikodebrg= trim($data['i_kodebrg']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-wip-warnabrg/cform/view/$iwarna/$ikodebrg/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-wip-warnabrg/cform/edit/$iwarna/$ikodebrg/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
    }

    function cek_dataheader($iwarna){
        $this->db->select('*');
        $this->db->from('tm_warna_wip a');
        $this->db->join('tm_warna_wip_item b', 'a.i_warna = b.i_warna');
        $this->db->join('tm_warna c', 'c.i_color = b.i_color');
        $this->db->where('b.i_warna', $iwarna);
        $this->db->order_by('a.i_kodebrg', 'ASC');
    return $this->db->get();
    }

    function cek_datadetail($iwarna, $ikodebrg){
        $this->db->select('*');
        $this->db->from('tm_warna_wip a');
        $this->db->join('tm_warna_wip_item b', 'a.i_warna = b.i_warna');
        $this->db->join('tm_warna c', 'c.i_color = b.i_color');
        $this->db->where('b.i_warna', $iwarna);
        $this->db->where('b.i_kodebrg', $ikodebrg);
        $this->db->order_by('a.i_kodebrg', 'ASC');
    return $this->db->get();
    }

    function cek_data2($iwarna, $ikodebrg){
    $this->db->select('a.i_warna, a.i_color, b.i_color, b.nama, a.i_kodebrg');
    $this->db->from('tm_warna_wip a');   
    $this->db->join('tm_warna b', 'a.i_color = b.i_color');
    $this->db->where('a.i_kodebrg', $ikodebrg);
    return $this->db->get();
    }
  
    function get_wipwarnabrg(){
        $this->db->select('*');
        $this->db->from('tm_warna_wip');
    return $this->db->get();
    }

    function get_wipbarang(){
        $this->db->select('*');
        $this->db->from('tm_barang_wip');
    return $this->db->get();
    }

    function get_warna(){
        $this->db->select('*');
        $this->db->from('tr_color');
    return $this->db->get();
    }

    public function insertheader($ikodebrg, $iwarna){
        $dentry = date("Y-m-d H:i:s");        

        $data = array(              
              'i_kodebrg'   => $ikodebrg, 
              'i_warna'     => $iwarna,              
              'd_entry'     => $dentry,               
        );
        
        $this->db->insert('tm_warna_wip', $data);             
    }
    
    public function insertdetail($iwarna, $ikodebrg, $icolor){
        $qwarna = $this->db->query("SELECT i_warna_item FROM tm_warna_wip_item ORDER BY i_warna_item DESC LIMIT 1");
                if ($qwarna->num_rows() > 0) {
                    $row_warna = $qwarna->row();
                    $iwarnaitem= $row_warna->i_warna_item+1;
                }
                else
                    $iwarnaitem = 1;
        $data = array(              
              'i_kodebrg'   => $ikodebrg, 
              'i_warna'     => $iwarna,  
              'i_color'     => $icolor,
              'i_warna_item'=> $iwarnaitem,             
        );
        
        $this->db->insert('tm_warna_wip_item', $data);             
    }

    public function insert($ikodebrg, $iwarna){
        $dentry = date("Y-m-d H:i:s");
        $data = array(              
              'i_kodebrg'   => $ikodebrg, 
              'i_warna'     => $iwarna,              
              'd_entry'     => $dentry,               
        );
        
        $this->db->insert('tm_warna_wip', $data);             
    }

    public function insert2($icolor, $ikodebrg){
       $dentry = date("Y-m-d H:i:s");
       $qwarna = $this->db->query("SELECT i_warna FROM tm_warna_wip ORDER BY i_warna DESC LIMIT 1");
                if ($qwarna->num_rows() > 0) {
                    $row_warna = $qwarna->row();
                    $iwarna= $row_warna->i_warna+1;
                }
                else
                    $iwarna = 1;
            $data = array(
              'i_warna'     => $iwarna,
              'i_color'     => $icolor,
              'i_kodebrg'   => $ikodebrg, 
              'd_entry'     => $dentry,
        );       
        $this->db->insert('tm_warna_wip', $data); 
    }

    public function update($iwarna, $ikodebrg){
        $dupdate = date("Y-m-d H:i:s");
        
        $data = array(
              'i_warna'     => $iwarna,
              'i_kodebrg'   => $ikodebrg,
              'd_update'    => $dupdate,  
    );

    $this->db->where('i_warna', $iwarna);
    $this->db->update('tm_warna_wip', $data);
    }

    function deletedetail($iwarna, $ikodebrg, $icolor){
        $this->db->query("DELETE FROM tm_warna_wip_item WHERE i_warna='$iwarna' and i_kodebrg='$ikodebrg' and i_color='$icolor' ");
    }
}
/* End of file Mmaster.php */