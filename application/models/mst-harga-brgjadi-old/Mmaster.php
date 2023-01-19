<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select
                               ROW_NUMBER () OVER (
                            ORDER BY
                               i_product) as no,
                               i_product,
                               e_product_basename,
                               v_price,
                               to_char(tgl_input, 'dd-mm-yyyy') as tgl_input,
                               to_char(tgl_update, 'dd-mm-yyyy') as tgl_update,
                               case
                                  when
                                     status = TRUE 
                                  then
                                     'Aktif' 
                                  else
                                     'Tidak Aktif' 
                               end
                               as status, $i_menu as i_menu, '$folder' as folder 
                            from
                               tm_hpp_gudang_jadi a 
                               inner join
                                  tr_product_base b 
                                  on a.i_product = b .i_product_motif", FALSE);                            
        
        $datatables->edit(
                'status', 
                function ($data) {
                    $id         = trim($data['i_product']);
                    $folder     = $data['folder'];
                    $id_menu    = $data['i_menu'];
                    $status     = $data['status'];
                    if ($status=='Aktif') {
                        $warna = 'success';
                    }else{
                        $warna = 'danger';
                    }
                    $data    = '';
                    if(check_role($id_menu, 3)){
                        $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    }else{
                        $data   .= "<span class=\"label label-$warna\">$status</span>";
                    }
                    return $data;
                }
        );
        
        $datatables->edit('v_price', function ($data) {
          $data = "Rp. ".number_format($data['v_price']);
          return $data;
        });
        

        $datatables->add('action', function ($data) {
            $iproduct     = trim($data['i_product']);
            $vprice       = trim($data['v_price']);
            $i_menu       = $data['i_menu'];
            $data         = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-harga-brgjadi/cform/view/$iproduct/$vprice/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-harga-brgjadi/cform/edit/$iproduct/$vprice\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            }
            
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');

        return $datatables->generate();
	}

   public function status($id){
        $this->db->select('status');
        $this->db->from('tm_hpp_gudang_jadi');
        $this->db->where('i_product', $id);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row    = $query->row();
            $status = $row->status;
            if ($status=='t') {
                $stat = 'f';
            }else{
                $stat = 't';
            }
        }
        $data = array(
            'status' => $stat 
        );
        $this->db->where('i_product', $id);
        $this->db->update('tm_hpp_gudang_jadi', $data);
    }

	function cek_data($iproduct, $vprice){
		$this->db->select('a.*, b.e_product_basename');
        $this->db->from('tm_hpp_gudang_jadi a');
        $this->db->join('tr_product_base b','a.i_product = b.i_product_motif');
        // $this->db->join('tr_color c','a.i_color = c.i_color');
        $this->db->where('a.i_product', $iproduct);
        $this->db->where('a.v_price', $vprice);
        return $this->db->get();
  }

  function cek_datadet($iproduct, $vprice){
    $this->db->select('a.*, b.e_material_name ');
        $this->db->from('tr_polacutting a');
        $this->db->join('tr_material b','a.i_material = b.i_material');
        $this->db->where('a.i_product', $iproduct);
        $this->db->where('a.i_color', $vprice);
        return $this->db->get();
  }

  function cek_dataheader($iproductwip){
		$this->db->select('*');
        $this->db->from('tr_polacutting ');
        $this->db->where('i_product', $iproductwip);
        return $this->db->get();
  }

  public function cekdatadetail($iproductwip, $xcolor, $imaterial){
    $this->db->select('*');
        $this->db->from('tr_polacutting');
        $this->db->where('i_product', $iproductwip);
        $this->db->where('i_color', $xcolor);
        $this->db->where('i_material', $imaterial);
        return $this->db->get();
  }

  public function bacaproduct(){
        return $this->db->order_by('i_kodebrg','ASC')->get('tm_barang_wip')->result();
  }

  public function getcolor($iproductwip) {
      $this->db->select("a.i_product_wip, a.i_color, b.e_color_name");
      $this->db->from('tr_product_wipcolor a');
      $this->db->join('tr_color b','a.i_color = b.i_color');
      $this->db->where('a.i_product_wip', $iproductwip);
      return $this->db->get();
  }
    
  public function insertdetail($iproduct, $vprice){
    $dentry = date("Y-m-d");
      $data = array(
          'i_product'   => $iproduct,
          'v_price'     => $vprice,
          'tgl_input'   =>$dentry
  );
  $this->db->insert('tm_hpp_gudang_jadi', $data);
  }
    
  public function updatedetail($iproduct, $vprice){
     $dupdate = date("Y-m-d");
      $data = array(
        'v_price'       => $vprice,
        'tgl_update' => $dupdate,
      );

      $this->db->where('i_product', $iproduct);
      $this->db->update('tm_hpp_gudang_jadi', $data);
  }

  public function deletedetail($iproductwip, $xcolor){
      $this->db->query("DELETE FROM tr_polacutting WHERE i_product='$iproductwip' and 
                        i_color='$xcolor'");
  }
}
/* End of file Mmaster.php */