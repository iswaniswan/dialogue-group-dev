<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu, $username, $idcompany, $idepartemen, $ilevel){
    $datatables = new Datatables(new CodeigniterAdapter);
    $datatables->query(" SELECT a.i_ttb, a.d_ttb, a.i_nota, a.i_customer, c.e_customer_name, b.e_alasan_returname, a.i_status,
                a.i_nota_retur_customer, a.f_approve, a.i_kode_bagian, d.e_status,'$i_menu' as i_menu, '$ilevel' as i_level, '$idepartemen' as i_departement
                FROM tm_ttbretur a
                INNER JOIN tr_alasan_retur b ON b.i_alasan_retur=a.i_alasan_retur
                INNER JOIN tr_customer c ON c.i_customer=a.i_customer
                INNER JOIN tm_status_dokumen d ON a.i_status=d.i_status
                WHERE a.f_ttb_cancel='f'
                ");
    
    $datatables->edit('f_approve', function ($data) {
          $f_approve = trim($data['f_approve']);
          if($f_approve != 't'){
            return "Belum di Approve";
          }else{
             return "Approve";
          }
      });

    $datatables->add('action', function ($data) {
            $ittb           = trim($data['i_ttb']);
            $ipelanggan     = trim($data['i_customer']);
            $ibagian           = trim($data['i_kode_bagian']);
            $i_menu         = $data['i_menu'];
            $i_status    = trim($data['i_status']);
            $i_departement= trim($data['i_departement']);
            $i_level      = trim($data['i_level']);
            $data           = '';

            if(check_role($i_menu, 2)){
              $data .= "<a href=\"#\" onclick='show(\"returpenjualan/cform/view/$ittb/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }

            if(check_role($i_menu, 3)){
                if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" onclick='show(\"returpenjualan/cform/edit/$ittb/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
                }

                if ((($i_departement == '17' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"returpenjualan/cform/approval/$ittb\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
                }
            }
            
            
      return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('f_approve');
        $datatables->hide('i_customer');
        $datatables->hide('i_kode_bagian');
        $datatables->hide('i_departement');
        $datatables->hide('i_level');
        $datatables->hide('i_status');
        // $datatables->hide('i_kode_bagian');
        return $datatables->generate();
  }

  public function getnotapenjualan($ipelanggan) {
    $this->db->select(" i_faktur_code, d_faktur FROM tm_faktur_do_t WHERE i_customer='$ipelanggan' ");
        return $this->db->get();
  }

  public function getdataheader($inota){
    // $inota        = $this->input->post('i_nota');
    $this->db->select(" * from tm_faktur_do_t
                      WHERE i_faktur_code ='$inota' 
                      and f_faktur_cancel='f'",false);
    return  $this->db->get();
    
  }

  public function getdataitem($inota){
        // $inota        = $this->input->post('i_nota');
        
        $this->db->select(" a.i_faktur, a.i_product, a.e_product_name, a.v_unit_price as harga, a.n_quantity as n_quantity_faktur, 
                            coalesce(ab.n_quantity_retur,0) as n_quantity_tlah_retur 
                            , (a.n_quantity - coalesce(ab.n_quantity_retur,0)) as n_quantity_sisa
                            FROM tm_faktur_do_item_t a
                            INNER JOIN tm_faktur_do_t b ON b.i_faktur_code=a.i_faktur
                            LEFT JOIN (select a.i_nota, b.i_product, sum(b.n_quantity_retur) as n_quantity_retur from tm_ttbretur a
                                      inner join tm_ttbretur_item b ON b.i_nota=a.i_nota and b.i_ttb=a.i_ttb
                                      where f_ttb_cancel='f' and a.i_nota='$inota'
                                      group by a.i_nota, b.i_product) ab ON ab.i_nota=a.i_faktur and ab.i_product=a.i_product 
                            WHERE a.i_faktur='$inota' and b.f_faktur_cancel='f' ");

        $data = $this->db->get();
        return $data;
  }

  public function getdataaks($inota){
        $inota        = $this->input->post('inota');

        $this->db->select("ac.i_permintaan, ac.i_material, c.e_material_name, ac.n_qty, ac.i_satuan_code, sa.e_satuan, ac.e_remark 
                        from tm_permintaanpengeluaranaks_detail ac
                        join tm_permintaanpengeluaranaks ab on ac.i_permintaan=ab.i_permintaan
                        join tr_material c on ac.i_material = c.i_material
                        join tr_satuan sa on ac.i_satuan_code=sa.i_satuan_code
                        where ab.i_permintaan = '$inota'",false);
        $data = $this->db->get();
        return $data;
  }

  function runningnumber($thbl, $lok){
    $th	= substr($thbl,0,4);
    $asal=$thbl;
    $thbl=substr($thbl,2,2).substr($thbl,4,2);
        $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='TTB'
                        and i_area='$lok'
                        and e_periode='$asal' 
                        and substring(e_periode,1,4)='$th' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
              $terakhir=$row->max;
            }
            $nobonmk  =$terakhir+1;
            $this->db->query(" update tm_dgu_no 
                              set n_modul_no=$nobonmk
                              where i_modul='TTB'
                              and e_periode='$asal' 
                              and i_area='$lok'
                              and substring(e_periode,1,4)='$th'", false);
            settype($nobonmk,"string");
            $a=strlen($nobonmk);
            while($a<5){
              $nobonmk="0".$nobonmk;
              $a=strlen($nobonmk);
            }
                $nobonmk  ="TTB-".$lok."-".$thbl."-".$nobonmk;
            return $nobonmk;
        }else{
            $nobonmk  ="00001";
            $nobonmk  ="TTB-".$lok."-".$thbl."-".$nobonmk;
      $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                         values ('TTB','$lok','$asal',1)");
            return $nobonmk;
        }
  }

    function insertheader($ikodebagian, $ittb, $inota, $datettb, $ipelanggan, $ialasanretur, $noreturpelanggan, $disc, $pajak, 
    $bruto, $netto, $vdisc, $dpp, $ppn){
        $dentry = date("Y-m-d ");
        
        $data = array(
                      'i_kode_bagian'         => $ikodebagian,
                      'i_ttb'                 => $ittb,
                      'i_nota'                => $inota,
                      'd_ttb'                 => $datettb,
                      'i_customer'            => $ipelanggan,
                      'i_alasan_retur'        => $ialasanretur,
                      'd_entry'               => $dentry,
                      'i_alasan_retur'        => $ialasanretur,
                      'i_nota_retur_customer' => $noreturpelanggan,
                      'dpp'                   => $dpp,
                      'ppn'                   => $ppn,
                      'i_pajak'               => $pajak,
                      'n_discount'            => $disc,
                      'v_ttb_gross'           => $bruto,
                      'v_ttb_discounttotal'   => $vdisc,
                      'v_ttb_netto'           => $netto
        );
        $this->db->insert('tm_ttbretur', $data);
    }

    function insertdetail($ittb, $inota, $datettb, $iproduct, $nquantityfaktur, $nquantityretur, $edesc, $vtotal, $unitprice, $disc){
        $data = array(
                      'i_ttb'               => $ittb,
                      'i_nota'              => $inota,
                      'd_ttb'               => $datettb,
                      'i_product'           => $iproduct,
                      'n_quantity_faktur'   => $nquantityfaktur,
                      'n_quantity_retur'    => $nquantityretur,
                      'v_total'             => $vtotal,
                      'v_unit_price'        => $unitprice,
                      'n_customer_discount1'=> $disc,
                      'e_ttb_remark'        => $edesc,
        );
        $this->db->insert('tm_ttbretur_item', $data);
    } 

    function cek_data($ittb) {
        $this->db->select(" a.*, e_customer_name 
                          from tm_ttbretur a
                          inner join tr_customer b on a.i_customer = b.i_customer
                          where a.i_ttb = '$ittb'", false);
        return $this->db->get();
    }

    function cek_bagian($ibagian) {
        $this->db->select(" * from tm_sub_bagian WHERE i_sub_bagian ='$ibagian' ", false);

        return $this->db->get();
    }

    function cek_pelanggan($ipelanggan){
        $this->db->select("  a.i_customer, a.e_customer_name
                                FROM tr_customer a
                                WHERE a.i_customer='$ipelanggan' ", false);
        return $this->db->get();
    }

    function cek_nota($ittb) {
        $this->db->select(" a.i_faktur_code FROM tm_faktur_do_t a 
                            LEFT JOIN tm_ttbretur b ON b.i_nota=a.i_faktur_code
                            WHERE b.i_ttb='$ittb' and a.f_faktur_cancel='f' ", false);
        return $this->db->get();
    }

    function cek_alasanretur($ittb) {
        $this->db->select(" a.i_alasan_retur, a.e_alasan_returname FROM tr_alasan_retur a 
                            LEFT JOIN tm_ttbretur b ON b.i_alasan_retur=a.i_alasan_retur
                            WHERE b.i_ttb='$ittb' ", false);
        return $this->db->get();
    }

    function cek_datadetail($ittb){

        $this->db->select(" b.i_ttb, b.i_nota, b.i_product, c.e_product_name, b.n_quantity_faktur, 
        (b.n_quantity_faktur-d.n_total_quantity_retur+b.n_quantity_retur) as n_quantity_sisa, b.v_unit_price, b.v_total,
        b.n_quantity_retur, b.e_ttb_remark 
                  FROM tm_ttbretur a
                  INNER JOIN tm_ttbretur_item b ON b.i_ttb=a.i_ttb
                  INNER JOIN tm_faktur_do_item_t c ON c.i_product=b.i_product and c.i_faktur=b.i_nota
                  LEFT JOIN (select a.i_nota, b.i_product, sum(b.n_quantity_retur) as n_total_quantity_retur from duta_prod.tm_ttbretur a
                            inner join duta_prod.tm_ttbretur_item b ON b.i_nota=a.i_nota and b.i_ttb=a.i_ttb
                            where f_ttb_cancel='f' 
                            group by a.i_nota, b.i_product) d ON d.i_nota=a.i_nota and d.i_product=b.i_product 
                  WHERE a.i_ttb = '$ittb' and a.f_ttb_cancel='f' ", false);
        return $this->db->get();
    }

    public function updateheader($ittb, $ikodebagian, $datettb, $ipelanggan, $inota, $noreturpelanggan, $ialasanretur){
        $dupdate = date("d F Y H:i:s");
        $data = array(
                      'd_ttb'                   => $datettb,
                      // 'i_customer'              => $ipelanggan,
                      'i_alasan_retur'          => $ialasanretur,
                      // 'i_nota'                  => $inota,
                      // 'i_nota_retur_customer'   => $noreturpelanggan,
                      // 'i_kode_bagian'           => $ikodebagian,
                      'd_update'                => $dupdate,
    );

    $this->db->where('i_ttb', $ittb);
    $this->db->update('tm_ttbretur', $data);
  }

  function deletedetail($ittb) {
        $this->db->query("DELETE from tm_ttbretur_item where i_ttb='$ittb' ");
    }

  public function cancel($ittb){
        $this->db->set(
            array(
                'f_ttb_cancel'  => 't'
            )
        );
        $this->db->where('i_ttb',$ittb);
        return $this->db->update('tm_ttbretur');
  }
  
  public function send($kode){
    $data = array(
        'i_status'    => '2'
    );

    $this->db->where('i_ttb', $kode);
    $this->db->update('tm_ttbretur', $data);
  }

  public function change($kode){
    $data = array(
        'i_status'    => '3'
    );

    $this->db->where('i_ttb', $kode);
    $this->db->update('tm_ttbretur', $data);
  }

  public function reject($kode){
    $data = array(
        'i_status'    => '4'
    );

    $this->db->where('i_ttb', $kode);
    $this->db->update('tm_ttbretur', $data);
  }

  public function approve($kode){
    $now = date("Y-m-d");
    $data = array(
        'i_status'   => '6',
        'd_approve' => $now
    );

    $this->db->where('i_ttb', $kode);
    $this->db->update('tm_ttbretur', $data);
  }

}
/* End of file Mmaster.php */