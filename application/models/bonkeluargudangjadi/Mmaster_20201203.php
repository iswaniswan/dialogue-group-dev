<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($dfrom, $dto, $i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
 
        $datatables->query("
                            SELECT 
                               ROW_NUMBER() OVER  (ORDER BY i_sj) as nomor,
                               a.i_sj,
                               to_char(a.d_sj, 'dd-mm-yyyy') as d_sj,
                               a.i_bagian,
                               a.i_tujuan,
                               b.e_nama_master,
                               a.i_status,
                               a.f_cancel,
                               c.label_color as label,
                               c.e_status as namastatus,
                               '$i_menu' as i_menu,
                               '$dfrom' as dfrom,
                               '$dto' as dto,
                               '$folder' as folder
                            FROM
                               tm_sjkeluar_gdjadi_lain a 
                               LEFT JOIN
                                  tr_master_gudang b 
                                  on a.i_tujuan = b.i_departemen 
                               LEFT JOIN
                                  tm_status_dokumen c 
                                  on a.i_status = c.i_status 
                            WHERE 
                              a.d_sj >= to_date('$dfrom','dd-mm-yyyy')
                              AND a.d_sj <= to_date('$dto', 'dd-mm-yyyy')
                            ORDER BY
                               i_sj

                            ",false);        

        $datatables->edit('f_cancel', function ($data) {
          $f_bonm_cancel = trim($data['f_cancel']);
            if($f_bonm_cancel == 't'){
               return  "Batal";
            }else {
              return "Aktif";
            }
        });

        $datatables->edit('i_status', function ($data) {
          if($data['f_cancel'] == 't'){
            return '<span class="label label-danger label-rouded">Batal</span>';
          }
          return '<span class="label label-'.$data['label'].' label-rouded">'.$data['namastatus'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $i_sj       = trim($data['i_sj']);
            $i_menu     = $data['i_menu'];
            $f_cancel   = trim($data['f_cancel']);
            $i_status   = trim($data['i_status']);
            $dfrom      = $data['dfrom'];
            $dto        = $data['dto'];
            $folder     = $data['folder'];
            $bagian     = trim($data['i_bagian']);
            $data       = '';

            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$i_sj/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3) && $f_cancel != 't' && $i_status != '6'){                
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_sj/$dfrom/$dto/$bagian/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
            }
            if($f_cancel!='t' && $i_status !='1' && $i_status!='5' && $i_status=='2'){
              $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$i_sj/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>&nbsp;&nbsp;";
            }
            if ($f_cancel!='t' && $i_status != '6'){
                $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_sj\"); return false;'><i class='ti-close'></i></a>";
            }
            
			    return $data;
        });
            
        $datatables->hide('i_menu');
        $datatables->hide('i_tujuan');
        $datatables->hide('namastatus');
        $datatables->hide('f_cancel');
        $datatables->hide('label');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('folder');
        $datatables->hide('i_bagian');

        return $datatables->generate();
	}

    public function gettujuan(){
      return $this->db->query("SELECT i_departemen as i_sub_bagian, e_nama_master as e_sub_bagian FROM tr_master_gudang ORDER BY i_departemen", FALSE);
    }

    public function getproduct($eproduct){
      $username = $this->session->userdata('username');
      $query = $this->db->query("SELECT i_kode_lokasi FROM public.tm_user_deprole WHERE username='$username'");
      foreach($query->result() as $row){
          $ilokasi = $row->i_kode_lokasi;
      }
      return $this->db->query("
                              SELECT
                                a.*, 
                                b.e_color_name,
                                c.n_quantity_stock
                              FROM 
                                tr_product_base a
                                LEFT JOIN tr_color b
                                ON (a.i_color = b.i_color)
                                LEFT JOIN tm_ic c
                                ON (a.i_product_motif = c.i_product)
                              WHERE 
                                a.i_product_motif = '$eproduct'
                                AND c.i_kode_lokasi = '$ilokasi'
                                AND c.i_product_grade = 'A'
                              ORDER BY 
                                a.i_product_motif
                              ", FALSE);
    }

    public function getgudanglain($ibagian){
      return $this->db->query("
                              SELECT 
                                i_departemen as i_sub_bagian, 
                                e_nama_master as e_sub_bagian 
                              FROM 
                                tr_master_gudang 
                              WHERE 
                                i_departemen 
                                NOT IN(
                                    SELECT
                                      i_departemen
                                    FROM
                                      tr_master_gudang
                                    WHERE
                                      i_departemen = '$ibagian'
                                )
                              ORDER BY 
                                i_departemen
                              ", FALSE);
    }

    function runningnumber($thbl, $ilokasi){
        $bl  = substr($thbl,4,2);
        $th  = substr($thbl,0,4);
        $thn = substr($thbl,2,2);
        $area= $ilokasi;
        $asal= substr($thbl,0,4);
        $thbl= substr($thbl,0,4);

        $this->db->select(" n_modul_no as max from tm_dgu_no 
                            where i_modul='BKG'
                            and i_area='$area'
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
                            where i_modul='BKG'
                            and e_periode='$asal' 
                            and i_area='$area'
                            and substring(e_periode,1,4)='$th'", false);
          settype($nopp,"string");
          $a=strlen($nopp);
  
          //u/ 0
          while($a<5){
            $nopp="0".$nopp;
            $a=strlen($nopp);
          }
            $nopp  ="BKG-".$area."-".$thn.$bl."-".$nopp;
          return $nopp;
        }else{
          $nopp  ="00001";
          $nopp  ="BKG-".$area."-".$thn.$bl."-".$nopp;
          $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                             values ('BKG','$area','$asal',1)");
          return $nopp;
        }
    }

    function insertheader($isj, $ibagian, $itujuan, $datesj, $eremark){
        $dentry = date("Y-m-d");
            $data   = array(
                        'i_sj'           => $isj,
                        'd_sj'           => $datesj,                    
                        'i_bagian'       => $ibagian,
                        'i_tujuan'       => $itujuan,                    
                        'e_remark'       => $eremark,
                        'd_entry'        => $dentry,    
                        'i_status'       => '1'
            );
            $this->db->insert('tm_sjkeluar_gdjadi_lain', $data);
    }

    public function cekic($iproduct, $icolor){
      return $this->db->query("SELECT i_product, n_quantity_stock FROM tm_ic WHERE i_product='$iproduct' AND i_product_grade='A'", FALSE)->row();
    }

    public function updateic($iproduct, $ikodelokasi, $nquantity, $nqty){
      $data = array(
        'n_quantity_stock' => $nqty-$nquantity,
      );
      $this->db->where('i_product', $iproduct);
      $this->db->where('i_kode_lokasi', $ikodelokasi);
      $this->db->where('i_product_grade', 'A');
      $this->db->update('tm_ic', $data);
    }
  
    public function insertic($iproduct, $ikodelokasi, $nquantity){
      $data = array(
          'i_product'         => $iproduct,
          'i_product_grade'   => 'A',
          'i_kode_lokasi'     => $ikodelokasi,
          'n_quantity_stock'  => $nquantity,
          'f_product_active'  => 't'
          
      );
      $this->db->insert('tm_ic', $data);
    }
  
    public function insertictrans($iproduct, $iproductgrade, $ikodelokasi, $isj, $nqty_in, $nqty_out, $nqty_akhir, $nqty_awal, $no){
      $now = date("Y-m-d");
  
      $data = array(
        'i_product'              => $iproduct,
        'i_product_grade'        => $iproductgrade,
        'i_lokasi'               => $ikodelokasi,
        'i_refference_document'  => $isj,
        'd_transaction'          => $now,
        'n_quantity_in'          => $nqty_in,
        'n_quantity_out'         => $nqty_out,
        'n_quantity_akhir'       => $nqty_akhir,
        'n_quantity_awal'        => $nqty_awal
        
      );
      $this->db->insert('tm_ic_trans', $data);
    }

    function insertdetail($isj, $iproduct, $icolor, $nquantity, $edesc, $no){ 
        $data = array(
                    'i_sj'               => $isj,
                    'i_product'          => $iproduct,
                    'i_color'            => $icolor,
                    'n_quantity'         => $nquantity,               
                    'n_item_no'          => $no,
                    'e_remark'           => $edesc,
        );
        $this->db->insert('tm_sjkeluar_gdjadi_lain_detail', $data);
    }

    public function send($kode){
      $data = array(
          'i_status'    => '2'
    );

    $this->db->where('i_sj', $kode);
    $this->db->update('tm_sjkeluar_gdjadi_lain', $data);
    }

    function cek_dataheader($i_sj){
        return $this->db->query("
                                SELECT
                                   a.i_sj,
                                   to_char(a.d_sj, 'dd-mm-yyyy') as d_sj,
                                   a.i_tujuan,
                                   a.i_bagian,
                                   a.e_remark,
                                   a.i_status,
                                   b.e_status,
                                   c.e_nama_master,
                                   d.e_departement_name
                                FROM
                                    tm_sjkeluar_gdjadi_lain a 
                                    LEFT JOIN
                                      tm_status_dokumen b 
                                      ON (a.i_status = b.i_status)
                                    LEFT JOIN
                                      tr_master_gudang c
                                      ON (a.i_bagian = c.i_departemen) 
                                    LEFT JOIN
                                      public.tr_departement d
                                      ON (a.i_tujuan = d.i_departement)
                                WHERE
                                  a.i_sj = '$i_sj'
                                ", false);
    }
    
    function cek_datadetail($i_sj){   
      return $this->db->query("
                              SELECT
                                a.*,
                                c.e_product_basename,
                                d.e_color_name 
                              FROM
                                tm_sjkeluar_gdjadi_lain_detail a 
                                LEFT JOIN 
                                tm_sjkeluar_gdjadi_lain b
                                ON (a.i_sj = b.i_sj)
                                LEFT JOIN
                                   tr_product_base c 
                                   ON a.i_product = c.i_product_motif 
                                LEFT JOIN
                                   tr_color d 
                                   on a.i_color = d.i_color
                              WHERE
                                    a.i_sj = '$i_sj'
                              ",false);
    }

    public function baca_bagian($bagian){
      return $this->db->query("SELECT i_departemen, e_nama_master FROM tr_master_gudang WHERE i_departemen !='$bagian'", false);
    }

    function baca_tujuan(){
        $this->db->select("* from tr_master_gudang",false);
        return $this->db->get();
    }

    function updateheader($isj, $ibagian, $itujuan, $datesj, $eremark){
         $dupdate = date("Y-m-d");
         $data = array(
                        'd_sj'           => $datesj,                    
                        'i_bagian'       => $ibagian,
                        'i_tujuan'       => $itujuan,                    
                        'e_remark'       => $eremark,
                        'd_update'       => $dupdate, 
        );
        $this->db->where('i_sj',$isj);
        $this->db->update('tm_sjkeluar_gdjadi_lain', $data);
    }

    function deletedetail($isj){
          $this->db->query("DELETE FROM tm_sjkeluar_gdjadi_lain_detail  WHERE i_sj='$isj'");
    }

    public function approve($isj){
      $now = date("Y-m-d");
      $data = array(
        'i_status'  =>'6',
        'd_approve' => $now
      );
      $this->db->where('i_sj', $isj);
      $this->db->update('tm_sjkeluar_gdjadi_lain', $data);
    }

    public function cancel_approve($isj){
        $data = array(
                'i_status'=>'7',
        );
        $this->db->where('i_sj', $isj);
        $this->db->update('tm_sjkeluar_gdjadi_lain', $data);
    }

    public function change_approve($isj){
        $data = array(
              'i_status'=>'3',
        );
        $this->db->where('i_sj', $isj);
        $this->db->update('tm_sjkeluar_gdjadi_lain', $data);
    }

    public function reject_approve($isj){
        $data = array(
          'i_status'=>'4',
        );
        $this->db->where('i_sj', $isj);
        $this->db->update('tm_sjkeluar_gdjadi_lain', $data);
    }

    public function cancel($isj){
      $this->db->set(
          array(
              'i_status'   => '9',
              'f_cancel'   => 't'
          )
      );
      $this->db->where('i_sj',$isj);
      return $this->db->update('tm_sjkeluar_gdjadi_lain');
    }
}
/* End of file Mmaster.php */
