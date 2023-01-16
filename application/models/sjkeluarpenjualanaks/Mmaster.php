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
        //'tidak' as status,'ada' as status, 
        $datatables->query("select distinct a.i_btb, a.d_sj, a.i_sj, c.i_pp, c.i_op, c.d_pp, b.e_supplier_name, a.e_remark, a.e_approval, d.e_status, a.f_sj_cancel, '$i_menu' as i_menu
                            FROM tm_sj_pembelian a 
                            JOIN tr_supplier b ON a.i_supplier=b.i_supplier 
                            JOIN tm_sj_pembelian_detail c ON a.i_sj=c.i_sj 
                            JOIN tm_status_dokumen d ON a.e_approval=d.i_status
                            WHERE a.i_makloon_type='0'
                            and a.i_sj not in(select a.i_sj 
                            from tm_bonmasuk_gudangdetail a, tm_bonmasuk_gudang b 
                            where a.i_bonm=b.i_bonm and b.f_bonm_cancel='f')
                            union all 
                            SELECT a.i_btb, a.d_sj, a.i_sj, c.i_pp, c.i_op, c.d_pp,  b.e_supplier_name, a.e_remark, a.e_approval, d.e_status, a.f_sj_cancel, '$i_menu' as i_menu
                            FROM tm_sj_pembelian a 
                            JOIN tr_supplier b ON a.i_supplier=b.i_supplier 
                            JOIN tm_sj_pembelian_detail c ON a.i_sj=c.i_sj 
                            JOIN tm_status_dokumen d ON a.e_approval=d.i_status
                            WHERE a.i_makloon_type='0' and a.i_sj in(select a.i_sj 
                            from tm_bonmasuk_gudangdetail a, tm_bonmasuk_gudang b 
                            where a.i_bonm=b.i_bonm and b.f_bonm_cancel='f')");

        $datatables->edit('f_sj_cancel', function ($data) {
          $f_sj_cancel = trim($data['f_sj_cancel']);
          if($f_sj_cancel == 't'){
             return  '<font color="red">Batal</font>';;
          }else {
           

            return "Aktif";
          }
        });

        $datatables->add('action', function ($data) {
            $i_sj        = trim($data['i_sj']);
            $ibtb        = trim($data['i_btb']);
            $i_menu      = $data['i_menu'];
            $f_sj_cancel = $data['f_sj_cancel'];
            $e_approval  = $data['e_approval'];
            $data        = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"pembelianbtb/cform/view/$ibtb/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }

            if(check_role($i_menu, 3)&& $f_sj_cancel!='t'){
                $data .= "<a href=\"#\" onclick='show(\"pembelianbtb/cform/edit/$ibtb/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            }

            if(check_role($i_menu, 1)&& $f_sj_cancel!='t' && $e_approval !='1' && $e_approval!='5' && $e_approval=='2'){
              $data .= "<a href=\"#\" onclick='show(\"pembelianbtb/cform/approve/$ibtb/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
            }

            if(check_role($i_menu, 1)&& $f_sj_cancel!='t' && $e_approval =='8' && $e_approval != '6'){
              $data .= "<a href=\"#\" onclick='show(\"pembelianbtb/cform/approvenext/$ibtb/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            }

            if ($f_sj_cancel!='t' && $e_approval == '1' || $e_approval == '6' || $e_approval == '2' || $e_approval == '8') {
                  $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_sj\"); return false;'><i class='fa fa-trash'></i></a>";
            }
			  return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('e_approval');
        $datatables->hide('f_sj_cancel');

        return $datatables->generate();
	}

  public function bacasupplier(){
        return $this->db->order_by('e_supplier_name','ASC')->get('tr_supplier')->result();
  }

  public function getiop($dfrom1, $dto1, $isupplier) {
        $this->db->select("distinct (a.i_op) from tm_opbb a
                        JOIN tm_opbb_item b on a.i_op=b.i_op
                        JOIN tr_supplier c ON a.i_supplier = c.i_supplier
                        JOIN tr_material d ON b.i_material = d.i_material
                        JOIN tr_satuan e ON b.i_satuan = e.i_satuan
                        where a.d_op>='$dfrom1' and a.d_op<='$dto1' and a.i_supplier='$isupplier' and a.e_approval='6' and f_op_close='f'", false);
        return $this->db->get();
  }

   public function getsup($dfrom1, $dto1) {
        $this->db->select("distinct a.i_supplier, c.e_supplier_name from tm_opbb a
                        JOIN tm_opbb_item b on a.i_op=b.i_op
                        JOIN tr_supplier c ON a.i_supplier = c.i_supplier
                        JOIN tr_material d ON b.i_material = d.i_material
                        JOIN tr_satuan e ON b.i_satuan = e.i_satuan
                        where a.d_op>='$dfrom1' and a.d_op<='$dto1' and a.e_approval='6'", false);
        return $this->db->get();
  }

  public function getOPitem($dfrom, $dto, $isupplier, $iop){
        $dfrom      = $this->input->post('dfrom');
        $dto        = $this->input->post('dto');
        $isupplier  = $this->input->post('isupplier');
        $ipp        = $this->input->post('ipp');

        if(isset($dfrom)){
            $tmp   = explode('-', $dfrom);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dfrom1 = $year.'-'.$month.'-'.$day;
        }
        if(isset($dto)){
            $tmp   = explode('-', $dto);
            $day   = $tmp[0];
            $month = $tmp[1];
            $year  = $tmp[2];
            $dto1 = $year.'-'.$month.'-'.$day;
        }
        if(!isset($dfrom))$dfrom=date('Y-m-d');
        if(!isset($dto))$dto=date('Y-m-d');
        $this->db->select("a.*, c.i_supplier, c.e_supplier_name, d.e_satuan, e.e_material_name, b.i_payment_type as ipayment, f.e_payment_typename, g.d_pp, b.d_op, h.e_nama_master, a.n_pemenuhan
                      FROM tm_opbb_item a
                      JOIN tm_opbb b ON a.i_op = b.i_op
                      JOIN tr_supplier c ON b.i_supplier = c.i_supplier
                      JOIN tr_satuan d ON a.i_satuan = d.i_satuan
                      JOIN tr_material e ON a.i_material =e.i_material
                      JOIN tr_payment_type f ON b.i_payment_type = f.i_payment_type
                      JOIN tm_pp g ON g.i_pp=a.i_pp 
                      JOIN tr_master_gudang h ON h.i_kode_master=a.i_kode_master
                      WHERE 
                      c.i_supplier = '$isupplier'
                      AND a.i_op = '$iop'
                      AND b.d_op>='$dfrom1' and b.d_op<='$dto1'
                      and a.n_pemenuhan not in ('0.00')
                      AND b.e_approval='6' ", false);
        //and a.n_quantity > a.n_pemenuhan
        return $this->db->get();
  }

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
                    f.v_price as hrgasup, (a.n_quantity * f.v_price) as totalhrgasup, g.i_payment_type, a.n_pemenuhan
                    from tm_opbb_item a
                    join tm_opbb g on a.i_op=g.i_op and g.f_op_cancel='f'
                    join tr_supplier b on b.i_supplier=g.i_supplier 
                    join tr_master_gudang c on a.i_kode_master=c.i_kode_master
                    join tr_material d on a.i_material=d.i_material
                    join tr_satuan e on e.i_satuan=a.i_satuan
                    join tr_supplier_materialprice f on f.i_material=a.i_material
                    where a.i_material='$imaterial' 
                    
                    and
                    g.i_supplier='$isupplier' and a.i_op='$iop') as x
                    order by x.i_op",false);
        return $this->db->get();
  }

  public function cek_header($isupplier, $iop){
        $this->db->select('a.i_op, c.i_pp, a.d_op, d.d_pp, a.i_supplier, b.e_supplier_name, c.i_kode_master');
        $this->db->from('tm_opbb a');    
        $this->db->join('tr_supplier b','a.i_supplier = b.i_supplier');
        $this->db->join('tm_opbb_item c','c.i_op = a.i_op');
        $this->db->join('tm_pp d','d.i_pp = c.i_pp');
        $this->db->where('a.i_op', $iop);
        $this->db->where('a.i_supplier', $isupplier);
        //$this->db->where('a.i_payment_type', $ipaymenttype);
        return $this->db->get();
  }

  public function cek_gudang($iop){
        $this->db->select("a.i_kode_master, a.e_nama_master 
                          FROM tr_master_gudang a 
                          JOIN tm_opbb_item b on a.i_kode_master =b.i_kode_master
                          WHERE a.i_departemen='03' AND b.i_op in('$iop')");
        return $this->db->get();
  }

  function runningnumber($yearmonth, $ikode){
        // $th = substr($yearmonth,0,4);
        // $asal=$yearmonth;
        // $yearmonth=substr($yearmonth,2,2).substr($yearmonth,4,2);
        $iarea    = substr($ikode, 5,2);
        $bl       = substr($yearmonth,4,2);
        $th       = substr($yearmonth,0,4);
        $thn      = substr($yearmonth,2,2);
        $asal     = substr($yearmonth,0,4);
        $yearmonth=substr($yearmonth,0,4);
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='BTB'
                            and i_area='$iarea'
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
                            where i_modul='BTB'
                            and e_periode='$asal' 
                            and i_area='$iarea'
                            and substring(e_periode,1,4)='$th'", false);
          settype($nopp,"string");
          $a=strlen($nopp);
          while($a<5){
            $nopp="0".$nopp;
            $a=strlen($nopp);
          }
            $nopp  ="BTB-".$thn.$bl."-".$iarea.$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="BTB-".$thn.$bl."-".$iarea.$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('BTB','$iarea','$asal',1)");
          return $nopp;
        }
  }

  public function cekstock($iop, $imaterial){
      $this->db->select("n_pemenuhan from tm_opbb_item 
                      where i_op = '$iop' and i_material = '$imaterial' ",false);
      return $this->db->get();
  }
  

  function updatestock($iop, $imaterial, $total){
        $this->db->set(
          array(          
            'n_pemenuhan'  => $total,
            )
          );
        $this->db->where('i_op',$iop);
        $this->db->where('i_material',$imaterial);
        $this->db->update('tm_opbb_item');
  }

  //public function insertheader($ibtb, $imaterial, $isj, $datesj, $isupplier, $paymenttype, $tipepajak, $totppn, $pkp, $grandtot, $eremark, $vtotalop, $dentry){
  public function insertheader( $ibtb, $imaterial, $isj, $datesj, $isupplier, $eremark, $dentry, $igudang, $datebtb){
        $data = array(
          'i_sj'            =>$isj,
          'd_sj'            =>$datesj,
          'i_supplier'      =>$isupplier,
          //'i_payment_type'  =>$paymenttype,
          // 'i_tax_type'      =>$tipepajak,
          // 'v_tax'           =>$totppn,
          // 'f_pkp'           =>$pkp,   
          // 'v_total'         =>$grandtot,
          // 'v_total_op'      =>$vtotalop,
          // 'v_sisa'          =>$grandtot,
          'f_faktur_created'=>'f',
          'e_remark'        =>$eremark,
          'd_entry'         =>$dentry,
          'f_sj_cancel'     =>'f',
          'f_status_lunas'  =>'f',
          'i_makloon_type'  =>'0',
          'i_btb'           => $ibtb,
          'i_kode_master'   => $igudang,
          'd_btb'           => $datebtb,
          'e_approval'      => '1',
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

  //public function insertdetail($ibtb, $iop, $imaterial, $qty, $ndiscount, $vunitprice, $vunitpriceop, $iunit, $iformula, $nformula_factor, $i, $isj, $now){
  public function insertdetail($ibtb, $iop, $imaterial, $qty, $i, $isj, $dentry, $iunit, $ikodemaster, $ipp, $dpp, $dateop, $qtyeks, $satuaneks, $vunitprice){
      $data = array(
        'i_sj'              => $isj,
        'i_op'              => $iop,
        'i_material'        => $imaterial,
        'n_qty'             => $qty,
        // 'n_discount'        => $ndiscount,
        'v_unit_price'      => $vunitprice,
        // 'v_unit_price_op'   => $vunitpriceop,
         'i_unit'           => $iunit,
        'd_entry'           => $dentry,
        'f_unit_conversion' => false,
        'i_formula'         => 0,
        'n_formula_factor'  => 0,
        'i_no_item'         => $i,
        'i_btb'             => $ibtb,
        'i_pp'              => $ipp,
        'd_op'              => $dateop,
        'd_pp'              => $dpp,
        'i_kode_master'     => $ikodemaster,
        'n_qty_eks'         => $qtyeks,
        'i_satuan_eks'      => $satuaneks,
      );
      $this->db->insert('tm_sj_pembelian_detail', $data);
  }

  public function gudang($ibtb){
      $this->db->select('a.i_kode_master, a.e_nama_master ');
      $this->db->from('tr_master_gudang a'); 
      $this->db->join('tm_sj_pembelian b', 'a.i_kode_master =b.i_kode_master'); 
      $this->db->where('b.i_btb', $ibtb);
      $this->db->where('a.i_departemen', '03'); 
      return $this->db->get();
  }

  function cek_dataapr($ibtb){
      $this->db->select('a.*, c.i_op, c.i_pp, c.d_pp, c.d_op, b.i_supplier, b.e_supplier_name, f_supplier_pkp, f_tipe_pajak, d.e_status');
      $this->db->from('tm_sj_pembelian a');
      $this->db->join('tr_supplier b','a.i_supplier = b.i_supplier');
      $this->db->join('tm_sj_pembelian_detail c','a.i_btb = c.i_btb');
      $this->db->join('tm_status_dokumen d', 'a.e_approval=d.i_status');
      $this->db->where('a.i_btb', $ibtb);
      return $this->db->get();
  }

  function cek_datadetapr($ibtb){
      $this->db->select('distinct on(a.i_material) a.*, a.v_unit_price_op as hrgop, (a.n_qty * a.v_unit_price_op) as totalop, a.i_kode_master, f.e_nama_master,
      d.v_price as hrgasup, (a.n_qty * d.v_price) as totalhrgasupb, b.i_satuan, b.e_satuan, (a.n_qty * v_unit_price) as v_total,
      (a.n_qty * v_unit_price_op) as v_total_op, e.e_material_name, g.e_satuan as satuaneks');
      $this->db->from('tm_sj_pembelian_detail a');
      $this->db->join('tr_satuan b','a.i_unit = b.i_satuan');
      $this->db->join('tm_sj_pembelian c','a.i_btb = c.i_btb');
      $this->db->join('tr_supplier_materialprice d','c.i_supplier = d.i_supplier');
      $this->db->join('tr_material e','a.i_material = e.i_material');
      $this->db->join('tr_master_gudang f','f.i_kode_master = a.i_kode_master');
      $this->db->join('tr_satuan g','g.i_satuan = a.i_satuan_eks');
      $this->db->where('a.i_btb', $ibtb);
      return $this->db->get();
          //   select distinct on(a.i_material) a.*, a.v_unit_price_op as hrgop, (a.n_qty * a.v_unit_price_op) as totalop,
          // d.harga as hrgasup, (a.n_qty * d.harga) as totalhrgasupb, b.i_satuan, b.e_satuan, (a.n_qty * v_unit_price) as v_total,
          // (a.n_qty * v_unit_price_op) as v_total_op
          // from duta_prod.tm_sj_pembelian_detail a
          // inner join duta_prod.tr_satuan b on(a.i_unit = b.i_satuan)
          // inner join duta_prod.tm_sj_pembelian c on(a.i_sj = c.i_sj)
          // inner join duta_prod.tm_harga_brg_supplier d on(c.i_supplier = d.i_supplier)
          // where a.i_sj = '300'
  }

  public function updateheader($isj, $dentry, $eremark, $igudang, $datesj, $datebtb, $ibtb){
        $data = array(
            'i_sj'          => $isj,
            'i_btb'         => $ibtb,
            'd_update'      => $dentry,
            'e_remark'      => $eremark,
            'i_kode_master' => $igudang,
            'd_sj'          => $datesj,
            'd_btb'         => $datebtb,
        );

        $this->db->where('i_sj', $isj);
        $this->db->where('i_btb', $ibtb);
        $this->db->update('tm_sj_pembelian', $data);
  }

  function cek_dataheader($ibtb){
        $this->db->select('*');
        $this->db->from('tm_sj_pembelian_detail');
        $this->db->where('i_btb', $ibtb);
        return $this->db->get();
  }

  public function updatedetail($nquantity, $imaterial, $isj){
      $data = array(
          'n_qty'   => $nquantity,
          //'v_unit_price'  => $vprice
      );

      $this->db->where('i_sj', $isj);
      $this->db->where('i_material', $imaterial);
      $this->db->update('tm_sj_pembelian_detail', $data);
  }

  public function approve($isj, $date, $ibtb){
      $data = array(
        'e_approve' => 't',
        'e_approval'=> '8',
        'd_approve' => $date,
      );
      $this->db->where('i_sj', $isj);
      $this->db->where('i_btb', $ibtb);
      $this->db->update('tm_sj_pembelian', $data);
  }

  public function approvenext($ibtb){
    $data = array(
      'e_approval'=>'6',
      'd_approve' => date("d F Y"),
  );
    $this->db->where('i_btb', $ibtb);
    $this->db->update('tm_sj_pembelian', $data);
  }

  public function cancel($ibtb){
      $this->db->set(
          array(
              'e_approval'   => '9',
              'f_sj_cancel'  => 't'
          )
      );
      $this->db->where('i_btb',$ibtb);
      return $this->db->update('tm_sj_pembelian');
  }

  public function send($kode){
      $data = array(
          'e_approval'    => '2'
  );

  $this->db->where('i_btb', $kode);
  $this->db->update('tm_sj_pembelian', $data);
  }

  public function sendd($ibtb){
      $data = array(
          'e_approval'    => '2'
  );

  $this->db->where('i_btb', $ibtb);
  $this->db->update('tm_sj_pembelian', $data);
  }

  public function cancel_approve($ibtb){
    $data = array(
      'e_approval'=>'7',
  );
    $this->db->where('i_btb', $ibtb);
    $this->db->update('tm_sj_pembelian', $data);
  }

  public function change_approve($ibtb){
    $data = array(
      'e_approval'=>'3',
  );
    $this->db->where('i_btb', $ibtb);
    $this->db->update('tm_sj_pembelian', $data);
  }

  public function appr2($ibtb){
    $data = array(
      'e_approval'=>'6',
  );
    $this->db->where('i_btb', $ibtb);
    $this->db->update('tm_sj_pembelian', $data);
  }


//*
	function cek_data($isupplier, $jenispembayaran){
    $this->db->select('a.*, c.i_supplier, c.e_supplier_name, d.e_satuan, e.e_material_name, b.i_payment_type as ipayment, 
                      f.e_payment_typename');
        $this->db->from('tm_opbb_item a');
        $this->db->join('tm_opbb b ','a.i_op = b.i_op');
        $this->db->join('tr_supplier c','b.i_supplier = c.i_supplier');
        $this->db->join('tr_satuan d','a.i_satuan = d.i_satuan');
        $this->db->join('tr_material e','a.i_material = e.i_material');
        $this->db->join('tr_payment_type f','b.i_payment_type = f.i_payment_type');
        $this->db->where('a.f_complete', false);
        $this->db->where('c.i_supplier', $isupplier);
        $this->db->where('b.i_payment_type', $jenispembayaran);
        return $this->db->get();
  }


// select a.*, b.i_supplier, b.e_supplier_name, f_supplier_pkp, f_tipe_pajak
// from duta_prod.tm_sj_pembelian a
// inner join duta_prod.tr_supplier b on(a.i_supplier = b.i_supplier)
// where a.i_sj = '300'

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
          'e_remark'      =>$eremark,
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
   
}

/* End of file Mmaster.php */
