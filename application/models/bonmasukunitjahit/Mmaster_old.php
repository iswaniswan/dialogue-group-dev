<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu){
    $datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query("select a.i_bonk, a.d_bonk, b.e_unitjahit_name, a.e_remark, c.e_nama_master, a.e_receive, $i_menu as i_menu 
                        from tm_bonmkeluar_pengadaan a
                        inner join tr_unit_jahit b on a.i_unit_jahit = b.i_unit_jahit
                        inner join tr_master_gudang c on a.i_gudang = c.i_kode_master
                        where f_bonk_cancel='false' 
                        and a.i_gudang='G06' 
                        order by a.d_bonk",false);

    $datatables->edit('e_receive', function ($data) {
        $e_receive = trim($data['e_receive']);
        if($e_receive == 't'){
           return  "Diterima";
        }else {
          return "Belum Diterima";
        }
    });

    $datatables->add('action', function ($data) {
            $i_bonk = trim($data['i_bonk']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"bonmasukunitjahit/cform/edit/$i_bonk/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
      return $data;
        });
        $datatables->hide('i_menu');
        // $datatables->hide('f_status_complete');
        return $datatables->generate();
  }

  function runningnumber($thbl){
      $th   = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
          $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SCH'
                          and i_area='00'
                          and e_periode='$asal' 
                          and substring(e_periode,1,4)='$th' for update", false);
          $query = $this->db->get();
          if ($query->num_rows() > 0){
              foreach($query->result() as $row){
                $terakhir=$row->max;
              }
              $noschedule  =$terakhir+1;
              $this->db->query("update tm_dgu_no 
                          set n_modul_no=$noschedule
                          where i_modul='SCH'
                          and e_periode='$asal' 
                          and i_area='00'
                          and substring(e_periode,1,4)='$th'", false);
              settype($noschedule,"string");
              $a=strlen($noschedule);
              while($a<6){
                $noschedule="0".$noschedule;
                $a=strlen($noschedule);
              }
                $noschedule  ="SCH-".$thbl."-".$noschedule;
              return $noschedule;
          }else{
              $noschedule  ="000001";
            $noschedule  ="SCH-".$thbl."-".$noschedule;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SCH','00','$asal',1)");
              return $noschedule;
          }
    }

    function insertheader($ibonk,$dbonk,$iunitjahit,$eremark,$dreceive,$isumber)
    {	
		$query 		= $this->db->query("SELECT current_timestamp as c");
		$row   		= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_bonk'                => $ibonk,
                'd_bonk'                => $dbonk,
                'i_unitjahit_receive' => $iunitjahit,
                // 'i_jenis'             => $typeout,
                // 'i_periode_forcast'   => $iperiodefc,
                'e_remark'            => $eremark,
                'd_receive'           => $dreceive,
                'd_entry'             => $dentry,
                'i_sumber'            => $isumber
    		)
    	);
    	
    	$this->db->insert('tm_sjmasuk_unitjahit');
    }

    function insertdetail($ibonk,$iproduct,$icolor,$eproductname,$nquantity,$eremark, $inoitem, $iunitjahit,$isumber)
    {
    	$this->db->set(
    		array(
			'i_product'		 		=> $iproduct,
			'e_product_name' 		=> $eproductname,
			'i_color'		 		=> $icolor,
			'n_pemenuhan' 	 		=> $nquantity,
			'e_remark' 		 		=> $eremark,
			'i_bonk'			 	=> $ibonk,
			'i_unit_jahit'  		=> $iunitjahit,
            'i_sumber'              => $isumber,
            'n_item_no' 	 		=> $inoitem,
            )
        );
    	$this->db->insert('tm_sjmasuk_unitjahit_item');
    }

    function insertdetailproduct($ischedule,$iproduct,$icolor,$imaterial,$ematerialname,$nquantity,$nset,$ngelar,$nitemno){   
            $data = array(
                    'i_schedule'            => $ischedule,
                    'i_product'             => $iproduct,
                    'i_color'               => $icolor,
                    'i_material'            => $imaterial,
                    'e_material_name'       => $ematerialname,
                    'n_quantity'            => $nquantity,
                    'n_set'                 => $nset,
                    'n_gelar'               => $ngelar,
                    'n_item_no'             => $nitemno,
            );
            
            $this->db->insert('tm_schedule_itemdetail', $data);
    }

    public function cek_data($i_bonk){
          $this->db->select('a.i_bonk, a.d_bonk, a.i_unit_jahit, b.e_unitjahit_name, a.e_remark, a.i_gudang, c.e_nama_master');
          $this->db->from('tm_bonmkeluar_pengadaan a');
          $this->db->join('tr_unit_jahit b','a.i_unit_jahit = b.i_unit_jahit');
          $this->db->join('tr_master_gudang c','a.i_gudang = c.i_kode_master');
          $this->db->where('a.i_bonk',$i_bonk);
      return $this->db->get();
    //   select a.i_bonk, a.d_bonk, a.i_unit_jahit, b.e_unitjahit_name, a.e_remark 
    //     from duta_prod.tm_bonmkeluar_pengadaan a 
    //     inner join duta_prod.tr_unit_jahit b on (a.i_unit_jahit = b.i_unit_jahit)
    //     where a.i_bonk = 'BON-2003-000003';

    }

    public function cek_datadetail($i_bonk){
        $this->db->select('a.i_product, a.e_product_name, a.i_color, b.e_color_name, a.n_quantity, a.e_remark');
        $this->db->from('tm_bonmkeluar_pengadaan_item a');
        $this->db->join('tr_color b','a.i_color = b.i_color');
        $this->db->where('a.i_bonk',$i_bonk);
        return $this->db->get();
        //    select a.i_product, a.e_product_name, a.i_color, b.e_color_name, a.n_quantity
        //     from duta_prod.tm_bonmkeluar_pengadaan_item a
        //     inner join duta_prod.tr_color b on (a.i_color = b.i_color)
        //     where a.i_bonk = 'BON-2003-000003';
    }

    public function delete($id){
          $this->db->query("DELETE FROM tm_kelompok_unit_detail WHERE id='$id'");
    }

    function updateheaderpengadaan($ibonk,$dreceive)
    {
    	$this->db->set(
    		array(
    			// 'f_unitjahit_receive' => TRUE,
                'd_pengadaan_receive' => $dreceive,
    			)
    		);
    	$this->db->where('i_bonk',$ibonk);
    	$this->db->update('tm_bonmkeluar_pengadaan');
    }

    function deletedetail($iproduct,$ischedule,$icolor){
        $this->db->query("DELETE FROM tm_schedule_item WHERE i_schedule='$ischedule' and i_product='$iproduct' and i_color='$icolor' ");
    }

    function deleteitemdetail($iproduct,$ischedule,$icolor){
        $this->db->query("DELETE FROM tm_schedule_itemdetail WHERE i_schedule='$ischedule' and i_product='$iproduct' and i_color='$icolor' ");
    }
    
    function deleteschedule($ischedule){
        $this->db->where('i_schedule',$ischedule);
        $query = $this->db->get('tm_schedule_item');
        if($query->num_rows()>0){
            foreach ($query->result() as $row) {
                $i_schedule = trim($row->i_schedule);
                // var_dump($isj);
                $this->db->set('i_schedule','');
                $this->db->where('i_schedule',$ischedule);
                $this->db->update('tm_schedule');
            }
            // die;
        }
        //update tm_schedule_item
        $qdelete = $this->db->where('i_schedule',$ischedule)->delete("tm_schedule_item");
        return $qdelete;
    }

    function updateschedule($ischedule){
        $this->db->set('i_schedule',$ischedule);
        return $this->db->update('tm_schedule');
    }
}
/* End of file Mmaster.php */