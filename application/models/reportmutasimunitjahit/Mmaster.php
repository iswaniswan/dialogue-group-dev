<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function getpartner(){
      $this->db->select("i_unit_jahit, e_unitjahit_name from tr_unit_jahit order by e_unitjahit_name", false);
      return $this->db->get()->result();
    }

  public function getQCset($dfrom, $dto){
        //header("Content-Type: application/json", true);   
        $pisah1 = explode("-", $dfrom);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];
        $iperiode = $thn1.$bln1;
        if($bln1 == 1) {
          $bln_query = 12;
          $thn_query = $thn1-1;
        }else {
          $bln_query = $bln1-1;
          $thn_query = $thn1;
          if ($bln_query < 10){
            $bln_query = "0".$bln_query;
          }
        }
        $pisah1 = explode("-", $dto);
            $tgl1= $pisah1[0];
            $bln1= $pisah1[1];
            $thn1= $pisah1[2];

        $this->db->select("* from f_mutasi_qcset($bln_query, $thn_query, '$dfrom','$dto', $bln1, $thn1)",false);
        $data = $this->db->get();
        return $data;
  }

  function data($blalu,$tlalu,$awal,$akhir,$bnow,$tnow,$partner, $dawal, $dakhir, $partner2){
    $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("select  ROW_NUMBER() OVER(order by kodewip) as no, kodewip, barangwip, icolor, ecolor, saldoawal, sjmasuk, sjmasukretur, sjkeluar, sjkeluarretur, saldoakhir, git, so, selisih from f_mutasi_makloonjahit('$blalu','$tlalu','$awal','$akhir','$bnow','$tnow','$partner', '$dawal', '$dakhir', '$partner2')",false);
        
        
        // $datatables->edit('f_bonk_cancel', function ($data) {
        //     $f_bonk_cancel = trim($data['f_bonk_cancel']);
        //     if($f_bonk_cancel == 'f'){
        //         return  "Aktif";
        //     }else {
        //         return "Batal";
        //     }
        // });
            // $datatables->edit('e_status', function ($data) {
            //   return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status'].'</span>';
            // });

            // $datatables->add('action', function ($data) {
            // $i_sj    = trim($data['i_sj']);
            // $i_menu     = $data['i_menu'];
            // $i_status    = trim($data['i_status']);
            // $i_departement= trim($data['i_departement']);
            // $i_level      = trim($data['i_level']);
            
            // $data       = '';

            // if(check_role($i_menu, 2)){
            //     $data .= "<a href=\"#\" onclick='show(\"penerimaanbarangunitjahit/cform/view/$i_sj/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            // }

            // if(check_role($i_menu, 3)){
            //     if ($i_status == '1'|| $i_status == '3' || $i_status == '7') {
            //         $data .= "<a href=\"#\" onclick='show(\"penerimaanbarangunitjahit/cform/edit/$i_sj/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            //     }

            //     if ((($i_departement == '18' && $i_level == '6') || ($i_departement == '1' && $i_level == '1')) && $i_status == '2') {
            //         $data .= "<a href=\"#\" onclick='show(\"penerimaanbarangunitjahit/cform/approval/$i_sj\",\"#main\"); return false;'><i class='fa fa-check-square'></i></a>";
            //     }
            // }
            // if ($i_status!='6') {
            //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$i_sj\"); return false;'><i class='fa fa-trash'></i></a>";
            // }
            
            
            // return $data;
            // });
            
        $datatables->hide('icolor');
        $datatables->hide('git');

        return $datatables->generate();
  }
}
/* End of file Mmaster.php */