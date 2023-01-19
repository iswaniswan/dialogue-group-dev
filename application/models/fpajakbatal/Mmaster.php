<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto, $area, $i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("select a.i_seri_pajak,a.d_pajak,a.i_nota,a.d_nota,a.i_spb,a.d_spb,b.e_customer_name,a.v_nota_netto,a.v_sisa,a.i_area,a.f_nota_cancel as cancel ,'$dfrom' as dfrom,'$dto' as dto,'$i_menu' as i_menu
                            from tm_nota a, tr_customer b
                            where a.i_customer=b.i_customer 
                            and not a.i_nota isnull and not a.i_seri_pajak isnull
                            and a.i_area='$area' and
                            a.d_nota >= to_date('$dfrom','yyyy-mm-dd') AND
                            a.d_nota <= to_date('$dto','yyyy-mm-dd')
                            ORDER BY a.i_nota ",false);
		$datatables->add('action', function ($data) {
            $i_nota     = trim($data['i_nota']);
            $i_area     = trim($data['i_area']);
            $dfrom      = trim($data['dfrom']);
            $dto        = trim($data['dto']);
            $f_nota_cancel= trim($data['cancel']);
            $i_menu     = $data['i_menu'];
            $data       = '';
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"fpajakbatal/cform/edit/$i_nota/$i_area/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });

        $datatables->edit('d_nota', function ($data) {
        $d_nota = $data['d_nota'];
            if($d_nota == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_nota) );
            }
        });

        $datatables->edit('d_spb', function ($data) {
            $d_spb = $data['d_spb'];
            if($d_spb == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_spb) );
            }
        });

        $datatables->edit('d_pajak', function ($data) {
            $d_pajak = $data['d_pajak'];
            if($d_pajak == ''){
                return '';
            }else{
                return date("d-m-Y", strtotime($d_pajak) );
            }
        });

        $datatables->edit('i_nota', function ($data) {
            $i_nota = $data['i_nota'];
            $f_nota_cancel = $data['cancel'];
                if($f_nota_cancel == 't'){
                    return "<h3 style='background-color:red;'><b>$i_nota<b></h3>";
                }else{
                    return $i_nota;
                }
            });

        $datatables->hide('i_menu');
        $datatables->hide('i_area');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('cancel');

        return $datatables->generate();
	}

	function baca($isj,$iarea){
		$this->db->select(" a.i_spb, a.d_nota, a.d_sj, a.d_spb, a.i_spb, a.i_area, a.i_customer,
                        a.v_nota_discounttotal, a.i_salesman, a.i_sj, a.i_nota, a.v_nota_discounttotal, 
                        a.v_nota_netto, 
                        j.i_spb_program, j.i_spb_old, j.i_spb_po, j.f_spb_consigment, j.n_spb_toplength, j.v_spb,
                        j.v_spb_discounttotal, j.i_price_group, j.n_spb_discount1,
                        j.n_spb_discount2, j.n_spb_discount3, j.n_spb_discount4, j.v_spb_discount1,
                        j.v_spb_discount2, j.v_spb_discount3, j.v_spb_discount4, j.f_spb_plusppn, 
                        j.f_spb_plusdiscount, j.f_spb_pkp, j.e_customer_pkpnpwp, 
                        e.e_promo_name, 
                        f.e_customer_name, f.f_customer_cicil,
                        g.e_salesman_name, 
                        h.e_area_name,
                        i.e_price_groupname,
                        k.n_toleransi_pusat, k.n_toleransi_cabang
                        from tm_nota a 
                        inner join tm_spb j on (a.i_spb=j.i_spb and a.i_area=j.i_area) 
                        left join tm_promo e on (j.i_spb_program=e.i_promo) 
                        inner join tr_customer f on (a.i_customer=f.i_customer)
                        inner join tr_salesman g on (a.i_salesman=g.i_salesman) 
                        inner join tr_customer_area h on (a.i_customer=h.i_customer) 
                        left join tr_price_group i on (j.i_price_group=i.i_price_group)
                        left join tr_city k on (f.i_city=k.i_city and f.i_area=k.i_area)
              			where a.i_sj = '$isj' and a.i_area = '$iarea'", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }
	
	function bacadetail($isj,$iarea){
		$this->db->select(" a.i_product, a.e_product_name, a.v_unit_price, c.n_deliver, c.n_order, 
                        a.i_product_motif, b.e_product_motifname
                        from tm_nota_item a, tr_product_motif b, tm_spb_item c, tm_spb d, tm_nota e
                        where b.i_product_motif=a.i_product_motif and b.i_product=a.i_product
                        and a.i_sj=e.i_sj and a.i_area=e.i_area
                        and e.i_spb=d.i_spb and e.i_area=d.i_area
                        and d.i_spb=c.i_spb and d.i_area=c.i_area
                        and a.i_product=c.i_product and a.i_product_motif=c.i_product_motif 
                        and a.i_product_grade=c.i_product_grade and a.n_deliver>0
					    and a.i_sj = '$isj' and a.i_area='$iarea' and a.n_deliver>0
					    order by a.n_item_no", false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function updatenota($isj,$iarea,$eremarkpajak,$dapprovepajak){
      $user=$this->session->userdata('username');
    	$this->db->set(
    		array(
			'd_approve_pajak'   => $dapprovepajak,
			'e_approve_pajak'	=> $eremarkpajak,
			'i_approve_pajak' 	=> $user
    		)
    	);
        $this->db->where('i_sj',$isj);
        $this->db->where('i_area',$iarea);
        $this->db->update('tm_nota');
    }
  function updatenotabaru($isj,$iarea,$inota,$dnota,$eremark,$inotaold,$djatuhtempo,$nnotatoplength,$nprice,$vspbdiscounttotalafter,$vspbafter){
    if($eremark='')$eremark=null;
    $query 	= $this->db->query("SELECT current_timestamp as c");
	  $row   	= $query->row();
	  $dentry	= $row->c;
		$data = array(
          'n_price'         => $nprice,
		  'i_nota'			=> $inota,
		  'd_nota'			=> $dnota,
		  'i_nota_old'	    => $inotaold,
          'e_remark'        => $eremark,
          'd_jatuh_tempo'   => $djatuhtempo,
          'n_nota_toplength'=> $nnotatoplength,
          'v_nota_discount' => $vspbdiscounttotalafter,
          'v_nota_netto'    => $vspbafter,
          'v_sisa'          => $vspbafter,
          'd_nota_entry'    => $dentry
					 );
  	$this->db->where('i_sj', $isj);
  	$this->db->where('i_area', $iarea);
	$this->db->update('tm_nota', $data); 
	$data = array(
				'i_nota'	=> $inota,
				'd_nota'	=> $dnota
				 );
  	$this->db->where('i_sj', $isj);
  	$this->db->where('i_area', $iarea);
 	$this->db->update('tm_nota_item', $data); 
  }
}

/* End of file Mmaster.php */
