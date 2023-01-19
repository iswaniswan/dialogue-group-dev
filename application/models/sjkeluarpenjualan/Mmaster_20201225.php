<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder, $dfrom, $dto){
         if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "WHERE a.d_sj BETWEEN '$dfrom' AND '$dto'";
        }else{
            $where = "";
        }
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT
                               ROW_NUMBER() OVER (ORDER BY a.i_sj) as nomor,
                               a.i_sj,
                               a.d_sj,
                               a.i_memo,
                               b.e_supplier_name,
                               a.f_sj_cancel,
                               a.i_status,
                               c.e_status,
                               c.label_color as label,
                               $i_menu as i_menu 
                            FROM
                               tm_sj_keluar_penjualanbhnbaku a 
                               JOIN
                                  (
                                     select
                                        'supllier' as x,
                                        i_supplier as i_supplier,
                                        e_supplier_name as e_supplier_name 
                                     from
                                        tr_supplier 
                                     union all
                                     select
                                        'customer' as x,
                                        i_customer as i_supplier,
                                        e_customer_name as e_supplier_name 
                                     from
                                        tr_customer 
                                     order by
                                        e_supplier_name asc 
                                  )
                                  b 
                                  on a.i_customer = b.i_supplier 
                               JOIN
                                  tm_status_dokumen c 
                                  on a.i_status = c.i_status
                                $where", false);
        
        $datatables->edit('f_sj_cancel', function ($data) {
            $f_sj_cancel = trim($data['f_sj_cancel']);
            if($f_sj_cancel == 'f'){
               return  "Aktif";
            }else {
              return "Batal";
            }
        });

        $datatables->edit('e_status', function ($data) {
            $f_cancel = trim($data['f_sj_cancel']);
            if($f_cancel == 't'){
              return '<span class="label label-danger label-rouded">Batal</span>';
            }else {
              return '<span class="label label-'.$data['label'].' label-rouded">'.$data['e_status'].'</span>';
            }
        });
        
        $datatables->add('action', function ($data) {
            $isj            = trim($data['i_sj']);
            $i_menu         = $data['i_menu'];
            $f_sj_cancel    = trim($data['f_sj_cancel']);
            $i_status       = trim($data['i_status']);
            $data           = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='View' onclick='show(\"sjkeluarpenjualan/cform/view/$isj/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)&& $f_sj_cancel == 'f' && $i_status !='6'){                
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"sjkeluarpenjualan/cform/edit/$isj/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            
            }
            if(check_role($i_menu, 1)&& $f_sj_cancel!='t' && $i_status !='1' && $i_status!='6' && $i_status=='2'){
              $data .= "<a href=\"#\" title='Approve' onclick='show(\"sjkeluarpenjualan/cform/approve/$isj/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_sj_cancel == 'f' && $i_status!='6'){
                $data .= "<a href=\"#\" title='Delete' onclick='cancel(\"$isj\"); return false;'><i class='ti-close'></i></a>&nbsp;&nbsp;";
            }
			return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('f_sj_cancel');
        $datatables->hide('i_status');
        $datatables->hide('label');

        return $datatables->generate();
	}

    function bacagudang($ilevel, $idepart, $lokasi, $username, $idcompany){
      //var_dump($idepart);
      if(trim($idepart) == '1'){
        return $this->db->query("SELECT
                                     a.i_departemen as i_departement,
                                     a.e_nama_master as e_departement_name 
                                FROM
                                     tr_master_gudang a 
                                ORDER BY
                                     a.i_kode_master", FALSE);
      }else{
            $where = "WHERE a.username = '$username' and a.i_departement = '$idepart' and a.i_level = '$ilevel' and a.id_company = '$idcompany'";

            return $this->db->query("SELECT
                                         a.*,
                                         b.e_departement_name,
                                         c.e_level_name,
                                         d.i_bagian 
                                      FROM
                                         public.tm_user_deprole a 
                                        JOIN
                                            public.tr_departement b 
                                            ON a.i_departement = b.i_departement 
                                        JOIN
                                            public.tr_level c 
                                            ON a.i_level = c.i_level 
                                        JOIN
                                            public.tm_user d 
                                            ON a.id_company = d.id_company 
                                            AND a.username = d.username
                                            $where", FALSE);
      }
    }

    public function getpartner($cari){
        $this->db->select("
                               z.partner,
                               z.epartner,
                               z.jenis_pengeluaran 
                            from
                               (
                                  select
                                     a.partner,
                                     e_customer_name as epartner,
                                     a.jenis_pengeluaran 
                                  from
                                     tm_permintaanpengeluaranbb a 
                                     join
                                        tr_customer b 
                                        on a.partner = b.i_customer 
                                  where
                                     jenis_pengeluaran = 'JK00003' 
                                     and i_status = '6' 
                                  union all
                                  select
                                     a.partner,
                                     e_nama_karyawan as epartner,
                                     a.jenis_pengeluaran 
                                  from
                                     tm_permintaanpengeluaranbb a 
                                     join
                                        tm_karyawan b 
                                        on a.partner = b.i_karyawan 
                                  where
                                     jenis_pengeluaran = 'JK00003' 
                                     and i_status = '6' 
                               )
                               as z 
                                where (UPPER(z.partner) LIKE '%$cari%'
                                OR UPPER(z.epartner) LIKE '%$cari%')
                            group by
                               z.partner,
                               z.epartner,
                               z.jenis_pengeluaran", false);
          return $this->db->get();
    }

    public function getmemobaru($ipelanggan) {
        $this->db->select(" i_permintaan, d_pp from tm_permintaanpengeluaranbb 
                            WHERE partner='$ipelanggan' and jenis_pengeluaran='JK00003' ");
            return $this->db->get();
    }

    public function getdataitemmemo($imemo){
        //$inota        = $this->input->post('i_nota');
        $i_memo        = $this->input->post('i_memo');
        
        $this->db->select(" a.i_permintaan, to_char(a.d_pp,'dd-mm-yyyy') as d_pp, b.i_material, c.e_material_name, b.i_satuan_code, d.e_satuan, b.n_qty, b.e_remark
                            FROM tm_permintaanpengeluaranbb a
                            INNER JOIN tm_permintaanpengeluaranbb_detail b ON b.i_permintaan = a.i_permintaan
                            INNER JOIN tr_material c ON c.i_material = b.i_material
                            INNER JOIN tr_satuan d ON d.i_satuan_code = b.i_satuan_code 
                            WHERE a.i_permintaan='$imemo' 
                            AND n_qty_sisa > 0
                            AND a.i_status='6'");

        $data = $this->db->get();
        return $data;
    }

    public function getpicIN(){
        return $this->db->query("
            select 'dept' as x, i_sub_bagian as i_sub_bagian, e_sub_bagian as e_sub_bagian from tm_sub_bagian
            union all
            select 'karyawan' as x, i_karyawan as i_sub_bagian, e_nama_karyawan as e_sub_bagian from tm_karyawan
            order by x asc", 
        FALSE); 
      }
  
    public function getpicEK(){
        return $this->db->query("
            select 'supllier' as x, i_supplier as i_supplier, e_supplier_name as e_supplier_name from tr_supplier
            union all
            select 'zcustomer' as x, i_customer as i_supplier, e_customer_name as e_supplier_name from tr_customer
            order by e_supplier_name asc", 
    FALSE);   
    }
//--- end ----------------------------------------------

    function runningnumberkeluar($yearmonth, $ilokasi){
        $bl  = substr($yearmonth,4,2);
        $th  = substr($yearmonth,0,4);
        $thn = substr($yearmonth,2,2);
        $area= trim($ilokasi);
        $asal= substr($yearmonth,0,4);
        $yearmonth= substr($yearmonth,0,4);

        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='SJK'
                            and i_area='$area'
                            and e_periode='$asal' 
                            and substring(e_periode,1,4)='$th' for update", false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          foreach($query->result() as $row){
            $terakhir=$row->max;
          }
          $kode  =$terakhir+1;
                $this->db->query("update tm_dgu_no 
                            set n_modul_no=$kode
                            where i_modul='SJK'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
          settype($kode,"string");
          $a=strlen($kode);
  
          //u/ 0
          while($a<5){
            $kode="0".$kode;
            $a=strlen($kode);
          }
            $kode  ="SJK"."-".$area."-".$thn.$bl."-".$kode;
          return $kode;
        }else{
          $kode  ="00001";
          $kode  ="SJK"."-".$area."-".$thn.$bl."-".$kode;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('SJK','$area','$asal',1)");
          return $kode;
        }
    }
      
    function insertheader($nosjkeluar, $imemo, $datesjk, $datememo, $ipartner, $istore, $remark){
            $dentry = date("Y-m-d");
            $data   = array(
                            'i_sj'              => $nosjkeluar,
                            'd_sj'              => $datesjk,
                            'i_memo'            => $imemo,
                            'd_memo'            => $datememo,
                            'i_customer'        => $ipartner,
                            'i_kode_master'     => $istore,
                            'i_status'          => '1',
                            'e_remark'          => $remark,
                            'd_entry'           => $dentry
            );
            $this->db->insert('tm_sj_keluar_penjualanbhnbaku', $data);
    }

    function insertdetail($nosjkeluar, $iproduct, $nquantityp, $nquantity, $isatuan, $edesc, $no){ 
        $data = array(
                     'i_sj'                     => $nosjkeluar,
                     'i_product'                => $iproduct,
                     'n_quantity_permintaan'    => $nquantityp,
                     'n_quantity'               => $nquantity,
                     'i_satuan'                 => $isatuan,
                     'e_remark'                 => $edesc,
                     'n_item_no'                => $no,
        );
        $this->db->insert('tm_sj_keluar_penjualanbhnbaku_item', $data);
    }

    public function ceksjkeluar2($imemo, $iproduct, $sisa, $nquantity){
        $this->db->select("n_qty_pemenuhan from tm_permintaanpengeluaranbb_detail 
                       where i_permintaan = '$imemo' 
                       and i_material = '$iproduct'
                       and i_material2 = '$iproduct'", false);
      $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $n_qty_pemenuhan = $kuy->n_qty_pemenuhan; 
        }else{
            $n_qty_pemenuhan = '';
        }
        return $n_qty_pemenuhan;
    }

    function updatepermintaan($imemo, $iproduct, $sisa, $nquantity, $npemenuhan){
        $this->db->set(
          array(          
                'n_qty_sisa'        => $sisa,
                'n_qty_sisa2'       => $sisa,
                'n_qty_pemenuhan'   => $npemenuhan,
                'n_qty_pemenuhan2'  => $npemenuhan,
            )
          );
        $this->db->where('i_permintaan', $imemo);
        $this->db->where('i_material', $iproduct);
        $this->db->where('i_material2', $iproduct);
        $this->db->update('tm_permintaanpengeluaranbb_detail');
    }

    public function send($kode){
        $data = array(
                      'i_status'    => '2'
        );

    $this->db->where('i_sj', $kode);
    $this->db->update('tm_sj_keluar_penjualanbhnbaku', $data);
    }

    public function baca_header($isj){
            $this->db->select("                                 
                                   a.i_sj,
                                   to_char(a.d_sj, 'dd-mm-yyyy') as d_sj,
                                   a.i_memo,
                                   to_char(a.d_memo, 'dd-mm-yyyy') as d_memo,
                                   a.i_kode_master,
                                   a.e_remark,
                                   a.i_customer,
                                   b.e_partner,
                                   a.i_status,
                                   xxz.jeniskeluar 
                                from
                                   tm_sj_keluar_penjualanbhnbaku a 
                                   join
                                      (
                                         select
                                            'supllier' as x,
                                            i_karyawan as i_partner,
                                            e_nama_karyawan as e_partner 
                                         from
                                            tm_karyawan 
                                         union all
                                         select
                                            'zcustomer' as x,
                                            i_customer as i_partner,
                                            e_customer_name as e_partner 
                                         from
                                            tr_customer 
                                         order by
                                            e_partner asc 
                                      )
                                      b 
                                      on a.i_customer = b.i_partner 
                                   join
                                      (
                                         SELECT
                                            jeniskeluar,
                                            x,
                                            i_kode_cust,
                                            e_cust 
                                         FROM
                                            (
                                               SELECT
                                                  'Internal' as jeniskeluar,
                                                  y.x,
                                                  y.i_sub_bagian as i_kode_cust,
                                                  y.e_sub_bagian as e_cust 
                                               FROM
                                                  (
                                                     select
                                                        'karyawan' as x,
                                                        i_karyawan as i_sub_bagian,
                                                        e_nama_karyawan as e_sub_bagian 
                                                     from
                                                        duta_prod.tm_karyawan 
                                                     order by
                                                        x asc
                                                  )
                                                  y 
                                               UNION ALL
                                               select
                                                  'External' as jeniskeluar,
                                                  z.x,
                                                  z.i_supplier as i_kode_cust,
                                                  z.e_supplier_name as e_cust 
                                               FROM
                                                  (
                                                     select
                                                        'zcustomer' as x,
                                                        i_customer as i_supplier,
                                                        e_customer_name as e_supplier_name 
                                                     from
                                                        duta_prod.tr_customer 
                                                     order by
                                                        e_supplier_name asc
                                                  )
                                                  z 
                                            )
                                            xz 
                                      )
                                      xxz 
                                      ON xxz.i_kode_cust = a.i_customer 
                                where a.i_sj='$isj'",false);                                
        return $this->db->get();
    }

    public function baca_detail($isj){
        $this->db->select("
                               a.i_sj,
                               a.i_product,
                               a.n_quantity_permintaan,
                               a.n_quantity,
                               a.i_satuan,
                               a.e_remark,
                               b.e_satuan,
                               c.e_material_name 
                            from
                               tm_sj_keluar_penjualanbhnbaku_item a 
                               left join
                                  tr_satuan b 
                                  on trim(a.i_satuan) = b.i_satuan_code 
                               join
                                  tr_material c 
                                  on a.i_product = c.i_material 
                            where a.i_sj='$isj'",false);                            
        return $this->db->get();
    }

    function updatestock($iproduct, $total, $kodelokasi){
        $this->db->set(
          array(          
            'n_quantity_stock'  => $total,
            )
          );
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_kode_lokasi',$kodelokasi);
        $this->db->where('i_product_grade','A');
        $this->db->update('tm_ic');
    }

    function updateheader($nosjkeluar, $datesjk, $istore, $imemo, $datememo, $ipartner, $remark){
        $dupdate = date("Y-m-d");
        $this->db->set(
          array(         
            'i_memo'    => $imemo,
            'd_memo'    => $datememo, 
            'i_customer'=> $ipartner,
            'e_remark'  => $remark,
            'd_sj'      => $datesjk,
            )
          );
        $this->db->where('i_sj',$nosjkeluar);
        $this->db->update('tm_sj_keluar_penjualanbhnbaku');
    }

    public function deletedetail($nosjkeluar){
        $this->db->query("DELETE FROM tm_sj_keluar_penjualanbhnbaku_item WHERE i_sj='$nosjkeluar'");
    }

    public function sendd($isj){
      $data = array(
          'i_status'    => '2'
    );

    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sj_keluar_penjualanbhnbaku', $data);
    }

    public function cancel_approve($isj){
        $data = array(
                  'i_status'=>'7',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sj_keluar_penjualanbhnbaku', $data);
    }
    
    public function approve($isj){
        $data = array(
                'i_status'=>'6',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sj_keluar_penjualanbhnbaku', $data);
    }

    public function change_approve($isj){
        $data = array(
                'i_status'=>'3',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sj_keluar_penjualanbhnbaku', $data);
    }

    public function reject_approve($isj){
      $data = array(
              'i_status'=>'4',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sj_keluar_penjualanbhnbaku', $data);
    }

    public function cancel($isj){
        $data = array(
                  'f_sj_cancel' => 't',
                  'i_status'    => '9',
    );
    $this->db->where('i_sj', $isj);
    $this->db->update('tm_sj_keluar_penjualanbhnbaku', $data);
    }
}
/* End of file Mmaster.php */
