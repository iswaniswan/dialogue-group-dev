<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tm_notabtb_pajak.i_pajak, tm_notabtb_pajak.d_pajak, tm_notabtb_pajak.i_supplier, tm_notabtb_pajak.v_ppn, tm_notabtb_pajak.f_pajak_cancel, $i_menu as i_menu FROM tm_notabtb_pajak");

        $datatables->edit('f_pajak_cancel', function ($data) {
          $f_pajak_cancel = trim($data['f_pajak_cancel']);
          if($f_pajak_cancel == 't'){
             return  "Batal";
          }else {
            return "Aktif";
          }
        });

		$datatables->add('action', function ($data) {
            $ipajak          = trim($data['i_pajak']);
            $isupplier       = trim($data['i_supplier']);
            $f_pajak_cancel  = trim($data['f_pajak_cancel']);
            $i_menu          = $data['i_menu'];
            $data            = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"fakturpajak/cform/view/$ipajak/$isupplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"fakturpajak/cform/edit/$ipajak/$isupplier/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
            if ($f_pajak_cancel!='t') {
                  $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$ipajak\"); return false;'><i class='fa fa-trash'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

    public function cek_sup($isupplier){
      $this->db->select('*');
          $this->db->from('tr_supplier');
          $this->db->where('i_supplier',$isupplier);
      return $this->db->get();
    }

     function get_notaitem($isupplier){
        $this->db->select("a.*, b.e_supplier_name");
        $this->db->from("tm_notabtb a");
        $this->db->join("tr_supplier b","a.i_supplier=b.i_supplier");
        $this->db->where("a.i_supplier",$isupplier);
        $this->db->where("a.i_pajak","");
        $this->db->where("a.f_nota_cancel","f");
        $this->db->order_by("a.i_nota");
        return $this->db->get();
    }

	public function insert($ipajak, $datepajak, $isupplier, $totdpp, $totppn, $totakhir){
        $dentry = date("Y-m-d H:i:s");

        $data = array(
            'i_pajak'       => $ipajak,
            'd_pajak'       => $datepajak, 
            'i_supplier'    => $isupplier, 
            'v_dpp'         => $totdpp, 
            'v_ppn'         => $totppn,
            'v_total'       => $totakhir,       
            'd_entry'       => $dentry,        
    );
    
    $this->db->insert('tm_notabtb_pajak', $data);
    }

    function updatenota($ipajak, $inota){
        $this->db->set('i_pajak',$ipajak);
        $this->db->where('i_nota',$inota);
        $this->db->where('f_nota_cancel','f');
        return $this->db->update('tm_notabtb');
    }

    public function insert1($ipajak, $inota, $vtotal, $inoitem){
        $dentry = date("Y-m-d H:i:s");
        $data = array(
            'i_pajak'     => $ipajak,
            'i_nota'      => $inota, 
            'v_nota'      => $vtotal,
            'i_no_item'   => $inoitem, 
            'd_entry'     => $dentry        
    );
    
    $this->db->insert('tm_notabtb_pajak_item', $data);
    }

    function cek_data($ipajak){
        $this->db->select("a.*, b.e_supplier_name");
        $this->db->from("tm_notabtb_pajak a");
        $this->db->join("tr_supplier b","a.i_supplier=b.i_supplier");
        $this->db->where("i_pajak",$ipajak);
        return $this->db->get();
    }

    function get_edititem($ipajak,$isupplier){
        $this->db->select("(select a.i_nota from tm_notabtb_pajak_item a, tm_notabtb_pajak b where 
              a.i_pajak=b.i_pajak and b.i_pajak='$ipajak' and b.f_pajak_cancel='f' and a.i_nota=x.i_nota) as pajaknota,
                x.*
            from 
            (select a.*, b.e_supplier_name 
            from tr_supplier b, tm_notabtb a
            where a.i_supplier=b.i_supplier and a.f_nota_cancel='f' and a.i_supplier='$isupplier'
            order by a.i_nota ) as x",false);
        return $this->db->get();
    }

    public function update($ipajak, $datepajak, $isupplier, $totdpp, $totppn, $totakhir){
        $dupdate = date("Y-m-d H:i:s");

        $data = array(
            'i_pajak'       => $ipajak,
            'd_pajak'       => $datepajak, 
            'i_supplier'    => $isupplier, 
            'v_dpp'         => $totdpp, 
            'v_ppn'         => $totppn,
            'v_total'       => $totakhir,       
            'd_update'      => $dupdate,      
    );
    $this->db->where('i_pajak', $ipajak);
    $this->db->update('tm_notabtb_pajak', $data);
    }

    function deletenotapajak($ipajak){
        //update f_faktur_created sj
        $this->db->where('i_pajak',$ipajak);
        $query = $this->db->get('tm_notabtb_pajak_item');
        if($query->num_rows()>0){
            foreach ($query->result() as $row) {
                $inota = trim($row->i_nota);
                // var_dump($isj);
                $this->db->set('i_pajak','');
                $this->db->where('i_nota',$inota);
                $this->db->update('tm_notabtb');
            }
            // die;
        }
        //update tm_notabtb
        $qdelete = $this->db->where('i_pajak',$ipajak)->delete("tm_notabtb_pajak_item");
            // $qupdate = $this->db->update('tm_notabtb');
        return $qdelete;
    }

    public function cancel($ipajak){
        $this->db->set(
            array(
                'f_pajak_cancel'  => 't'
            )
        );
        $this->db->where('i_pajak',$ipajak);
        return $this->db->update('tm_notabtb_pajak');
    }
}
/* End of file Mmaster.php */