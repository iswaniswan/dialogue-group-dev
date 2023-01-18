<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		//$datatables->query("Select i_supplier_group, e_supplier_groupname, e_supplier_groupnameprint1, e_supplier_groupnameprint2, '$i_menu' as i_menu from tr_supplier_group");
        // $datatables->query("select a.i_pp, a.d_pp, b.e_nama_master, a.e_remark, a.d_op, a.e_approve, a.f_pp_cancel,'$i_menu' as i_menu  
        //                     from tm_pp a inner join tr_master_gudang b on(a.i_kode_master=b.i_kode_master)");

        $datatables->query("select i_bonm, d_bonm, f_bonm_cancel, '$i_menu' as i_menu from tm_bonmasuk_bisbisan");

        $datatables->edit('f_bonm_cancel', function ($data) {
          $f_bonm_cancel = trim($data['f_bonm_cancel']);
          if($f_bonm_cancel == 't'){
             return  "Aktiv";
          }else {
            return "Batal";
          }
      });
    //   $datatables->edit('status', function ($data) {
    //     $status = trim($data['status']);
    //     if($status == 't'){
    //        return  "Approve";
    //     }else {
    //       return "Belum Approve";
    //     }
    // });
        $datatables->add('action', function ($data) {
            $i_bonm = trim($data['i_bonm']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"bonmpemaklonbis/cform/view/$i_bonm/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"bonmpemaklonbis/cform/edit/$i_bonm/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }
          //   if(check_role($i_menu, 1)){
          //     $data .= "<a href=\"#\" onclick='show(\"bonmpemaklonbis/cform/approve/$i_bonm/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
          // }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($isupplier, $jenispembayaran){
		$this->db->select('a.*, c.i_supplier, c.e_supplier_name, d.e_satuan, e.e_material_name, b.i_payment_type as ipayment ');
        $this->db->from('tm_opbb_item a');
        $this->db->join('tm_opbb b ','a.i_op = b.i_op');
        $this->db->join('tr_supplier c','b.i_supplier = c.i_supplier');
        $this->db->join('tr_satuan d','a.i_satuan = d.i_satuan');
        $this->db->join('tr_material e','a.i_material = e.i_material');
        $this->db->where('a.f_complete', false);
        $this->db->where('c.i_supplier', $isupplier);
        $this->db->where('b.i_payment_type', $jenispembayaran);
        return $this->db->get();
  }

  public function cek_header($isupplier, $iop, $ipaymenttype){
    $this->db->select('a.*, b.e_supplier_name');
    $this->db->from('tm_opbb a');
    $this->db->join('tr_supplier b','a.i_supplier = b.i_supplier');
    $this->db->where('a.i_op', $iop);
    $this->db->where('a.i_supplier', $isupplier);
    $this->db->where('a.i_payment_type', $ipaymenttype);
    return $this->db->get();
}
function cek_datadet2($dfrom, $dto){
  $this->db->select("a.i_sj, a.d_sj, b.i_material, e.e_material_name, c.i_supplier, c.e_supplier_name,
                      b.n_qty, d.e_satuan, d.i_satuan, b.v_unit_price, b.i_unit_conversion, e.i_store, b.i_cut_type, g.e_potongan,
                      b.i_size_type, f.e_nama_ukuran, b.n_qty_conv 
                      from tm_sj_pembelian a, tm_sj_pembelian_detail b, tr_supplier c, tr_satuan 
                      d, tr_material e, tr_ukuran_bisbisan f, tr_potongan_bisbisan g
                      where a.i_sj = b.i_sj 
                      and a.i_supplier = c.i_supplier 
                      and b.i_unit = d.i_satuan
                      and b.i_material = e.i_material 
                      and b.i_size_type = f.i_kode_ukuran
                      and b.i_cut_type = g.i_potongan
                      and a.d_sj >= to_date('$dfrom','dd-mm-yyyy')
                      and a.d_sj <= to_date('$dto','dd-mm-yyyy')
                      and b.f_bonm_approve = 'f' 
                      and a.i_makloon_type = '1' 
                      and a.f_sj_cancel = 'f'
                      and a.i_sj not in (select i_sj from tm_bonmasuk_gudangdetail b, tm_bonmasuk_gudang a
                      where a.i_bonm=b.i_bonm and a.f_bonm_cancel='f')
                      order by a.d_sj",false);
  return $this->db->get();
}
function cek_dataapr($i_sj){
  $this->db->select('a.*, b.i_supplier, b.e_supplier_name, f_supplier_pkp, f_tipe_pajak');
  $this->db->from('tm_sj_pembelian a');
  $this->db->join('tr_supplier b','a.i_supplier = b.i_supplier');
  $this->db->where('a.i_sj', $i_sj);
  return $this->db->get();
}
// select a.*, b.i_supplier, b.e_supplier_name, f_supplier_pkp, f_tipe_pajak
// from tm_sj_pembelian a
// inner join tr_supplier b on(a.i_supplier = b.i_supplier)
// where a.i_sj = '300'
public function cek_det($iop, $imaterial, $isupplier){
  $this->db->select(" x.*,
                    (select sum(a.n_qty) from tm_sj_pembelian_detail a, tm_sj_pembelian b
                    where a.i_sj=b.i_sj and b.f_sj_cancel='f' and a.i_material=x.i_material and a.i_unit=x.i_satuan
                    and a.i_op=x.i_op
                    )as qtysj
                    from (
                    select a.i_op, b.e_supplier_name, b.f_supplier_pkp, b.f_tipe_pajak, b.n_supplier_toplength,
                    a.i_kode_master, c.e_nama_master, a.i_material, d.e_material_name,
                    a.i_satuan, e.e_satuan, a.n_quantity, 
                    a.v_price as hrgop, (a.n_quantity * a.v_price) as totalop,
                    f.harga as hrgasup, (a.n_quantity * f.harga) as totalhrgasup, 
                    g.i_payment_type
                    from tm_opbb_item a
                    join tm_opbb g on a.i_op=g.i_op and g.f_op_cancel='f'
                    join tr_supplier b on b.i_supplier=g.i_supplier 
                    join tr_master_gudang c on a.i_kode_master=c.i_kode_master
                    join tr_material d on a.i_material=d.i_material
                    join tr_satuan e on e.i_satuan=a.i_satuan
                    join tm_harga_brg_supplier f on f.kode_brg=a.i_material
                    where a.i_material='$imaterial' and 
                    g.i_supplier='$isupplier' and a.i_op='$iop') as x
                    order by x.i_op",false);
    return $this->db->get();
}
function cek_sup($isupplier){
		$this->db->select('*');
        $this->db->from('tr_supplier a');
        $this->db->where('i_supplier', $isupplier);
        return $this->db->get();
  }

  function getpkp($isupplier){
    $this->db->where('i_supplier',$isupplier);
    return $this->db->get('tr_supplier');
  }
function cek_dataheader($isj){
		$this->db->select('*');
        $this->db->from('tm_sj_pembelian_detail');
        $this->db->where('i_sj', $isj);
        return $this->db->get();
  }
  public function cekdatadetail($ipp, $imaterial){
    $this->db->select('*');
        $this->db->from('tm_pp_item');
        $this->db->where('i_pp', $ipp);
        $this->db->where('i_material', $imaterial);
        return $this->db->get();
  }
  function cek_datadet($id){
		$this->db->select('a.*, b.e_material_name, c.e_satuan');
        $this->db->from('tm_pp_item a');
        $this->db->join('tr_material b','a.i_material = b.i_material');
        $this->db->join('tr_satuan c','a.i_satuan = c.i_satuan');
        $this->db->where('a.i_pp', $id);
        return $this->db->get();
	}
    public function bacasupplier(){
        return $this->db->order_by('e_supplier_name','ASC')->get('tr_supplier')->result();
    }
    function runningnumber($yearmonth){
        $th = substr($yearmonth,0,4);
        $asal=$yearmonth;
        $yearmonth=substr($yearmonth,2,2).substr($yearmonth,4,2);
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='PP'
                            and i_area='00'
                            and e_periode='$asal' 
                            and substring(e_periode,1,4)='$th' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $terakhir=$row->max;
          }
          $nopp  =$terakhir+1;
                $this->db->query("update tm_dgu_no 
                            set n_modul_no=$nopp
                            where i_modul='PP'
                            and e_periode='$asal' 
                            and i_area='00'
                            and substring(e_periode,1,4)='$th'", false);
          settype($nopp,"string");
          $a=strlen($nopp);
          while($a<7){
            $nopp="0".$nopp;
            $a=strlen($nopp);
          }
            $nopp  ="PP-".$yearmonth."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="0000001";
          $nopp  ="PP-".$yearmonth."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('PP','00','$asal',1)");
          return $nopp;
        }
      }
  public function insertheader($imaterial, $isj, $datesj, $isupplier, $paymenttype, 
  $tipepajak, $totppn, $pkp, $grandtot, $eremark, $vtotalop, $dentry){
        $data = array(
          'i_sj'            =>$isj,
          'd_sj'            =>$datesj,
          'i_supplier'      =>$isupplier,
          'i_payment_type'  =>$paymenttype,
          'i_tax_type'      =>$tipepajak,
          'v_tax'           =>$totppn,
          'f_pkp'           =>$pkp,   
          'v_total'         =>$grandtot,
          'v_total_op'      =>$vtotalop,
          'v_sisa'          =>$grandtot,
          'f_faktur_created'=>'f',
          'e_desc'          =>$eremark,
          'd_entry'         =>$dentry,
          'f_sj_cancel'     =>'f',
          'f_status_lunas'  =>'f',
          'i_makloon_type'  =>'0'
    );
    $this->db->insert('tm_sj_pembelian', $data);
    }
    function cekqty($iop,$imaterial){
      $this->db->select('b.n_qty as sj, a.n_quantity as op');
      $this->db->from('tm_opbb_item a');
      $this->db->join('tm_sj_pembelian_detail b','a.i_op=b.i_op and a.i_material=b.i_material and b.i_unit=a.i_satuan','left');
      $this->db->where('a.i_op',$iop);
      $this->db->where('a.i_material',$imaterial);
      return $this->db->get();
      // if($data->num_rows() > 0){
        // return $data;
      // }
    }
  public function itemComplete($fcomplete,$iop,$imaterial){
      $data = array(
          'f_complete'  => $fcomplete
  );
      $this->db->where('i_op', $iop);
      $this->db->where('i_material', $imaterial);
      $this->db->update('tm_opbb_item', $data);
  }
  public function insertdetail($iop, $imaterial, $qty, $ndiscount, $vunitprice, 
  $vunitpriceop, $iunit, $iformula, $nformula_factor, $i, $isj, $now){
      $data = array(
        'i_sj'              => $isj,
        'i_op'              => $iop,
        'i_material'        => $imaterial,
        'n_qty'             => $qty,
        'n_discount'        => $ndiscount,
        'v_unit_price'      => $vunitprice,
        'v_unit_price_op'   => $vunitpriceop,
        'i_unit'            => $iunit,
        'd_entry'           => $now,
        'f_unit_conversion' => false,
        'i_formula'         => 0,
        'n_formula_factor'  => 0,
        'i_no_item'         => $i
      );
      $this->db->insert('tm_sj_pembelian_detail', $data);
  }
  public function insertheadertanpaop($isj, $datesj, $isupplier, $paymenttype, 
  $tipepajak, $totppn, $pkp, $grandtot, $eremark, $dentry){
        $data = array(
          'i_sj'          =>$isj,
          'd_sj'          =>$datesj,
          'i_supplier'    =>$isupplier,
          'i_payment_type'=>$paymenttype,
          'i_tax_type'    =>$tipepajak,
          'v_tax'         =>$totppn,
          'f_pkp'         =>$pkp,
          'v_total'       =>$grandtot,
          'v_sisa'        =>$grandtot,
          'e_desc'        =>$eremark,
          'd_entry'       =>$dentry
    );
    $this->db->insert('tm_sj_pembelian', $data);
    }
    public function insertdetailtanpaop($imaterial, $qty, $ndiscount, $vunitprice, 
    $iunit, $iformula, $nformula_factor, $i, $isj, $now){
      $data = array(
        'i_sj'              => $isj,
        'i_op'              => 0,
        'i_material'        => $imaterial,
        'n_qty'             => $qty,
        'n_discount'        => $ndiscount,
        'v_unit_price'      => $vunitprice,
        'v_unit_price_op'   => 0,
        'i_unit'            => $iunit,
        'd_entry'           => $now,
        'f_unit_conversion' => false,
        'i_formula'         => 0,
        'n_formula_factor'  => 0,
        'i_no_item'         => $i
      );
      $this->db->insert('tm_sj_pembelian_detail', $data);
  }
    public function updateheader($grandtot ,$grandtotop, $isj, $now){
        $data = array(
            'v_total' => $grandtot,
            'v_total_op' => $grandtotop,
            'd_update'  =>$now
    );

    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sj_pembelian', $data);
    }
    public function updatedetail($nquantity, $vprice, $imaterial, $isj){
      $data = array(
          'n_qty'         => $nquantity,
          'v_unit_price'  => $vprice
      );

      $this->db->where('i_sj', $isj);
      $this->db->where('i_material', $imaterial);
      $this->db->update('tm_sj_pembelian_detail', $data);
    }
    public function approve($isj, $now){
      $data = array(
        'e_approve' => 't',
        'd_approve' => $now,
    );
    $this->db->where('i_sj', $isj);
      $this->db->update('tm_sj_pembelian', $data);
    }
}

/* End of file Mmaster.php */
