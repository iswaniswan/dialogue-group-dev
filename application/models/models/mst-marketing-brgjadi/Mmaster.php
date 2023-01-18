<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_product_motif.i_product_motif, tr_product_motif.e_product_motifname, 
        tr_product_motif.i_kel_brg_jadi, tr_product_motif.f_product_aktif, $i_menu as i_menu FROM tr_product_motif");

        $datatables->edit('f_product_aktif', function ($data) {
            $f_product_aktif = trim($data['f_product_aktif']);
            if($f_product_aktif == 'f'){
               return  "Tidak Aktif";
            }else {
              return "Aktif";
            }
        });

		$datatables->add('action', function ($data) {
            $iproductmotif = trim($data['i_product_motif']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-marketing-brgjadi/cform/view/$iproductmotif/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"mst-marketing-brgjadi/cform/edit/$iproductmotif/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if(check_role($i_menu, 4)){
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$iproductmotif\"); return false;'><i class='fa fa-trash'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_product_motif');
    $this->db->join('tm_kel_brg_jadi', 'tr_product_motif.i_kel_brg_jadi = tm_kel_brg_jadi.i_kel_brg_jadi');
    $this->db->where('i_product_motif', $id);

    return $this->db->get();
    }
    
    function get_barangjadi(){
        $this->db->select('*');
        $this->db->from('tr_product_motif');
    return $this->db->get();
    }

    function get_kelompokbrgjadi(){
        $this->db->select('*');
        $this->db->from('tm_kel_brg_jadi');
    return $this->db->get();
    }


    public function update($iproductmotif, $eproductmotifname, $ikelbrgjadi){
        $data = array(
              'i_product_motif'       => $iproductmotif,
              'e_product_motifname'   => $eproductmotifname,    
              'i_kel_brg_jadi'        => $ikelbrgjadi, 

    );

    $this->db->where('i_product_motif', $iproductmotif);
    $this->db->update('tr_product_motif', $data);
    }

    public function cancel($iproductmotif){
        $data = array(
          'f_product_aktif'=>'f',
      );
        $this->db->where('i_product_motif', $iproductmotif);
        $this->db->update('tr_product_motif', $data);
      }

}

/* End of file Mmaster.php */