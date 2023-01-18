<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu, $folder, $dfrom, $dto){
    $datatables = new Datatables(new CodeigniterAdapter);
    $id_company = $this->session->userdata('id_company');
    $datatables->query("SELECT
                          0 AS no,
                          a.id,
                          a.i_spbb,
                          to_char(a.d_spbb, 'dd-mm-yyyy') AS d_spbb,
                          e.i_document,
                          to_char(e.d_document, 'dd-mm-yyyy') AS d_schedule,
                          d.e_type_name AS gudang,
                          a.e_remark,
                          a.i_status,
                          c.e_status_name,
                          c.label_color,
                          '$i_menu' AS i_menu,
                          '$folder' AS folder,
                          '$dfrom' AS dfrom,
                          '$dto' AS dto,
                          x.hapus,
                          x.approve
                        FROM
                          tm_spbb a
                        inner join tr_bagian b on (a.i_bagian = b.i_bagian and a.id_company = b.id_company)
                        INNER JOIN tr_status_document c ON a.i_status = c.i_status
                        INNER JOIN tr_type d ON (a.i_type = d.i_type)
                        INNER JOIN tm_schedule e ON (a.id_schedule = e.id)
                        left join ( select id_schedule, d_entry, count(*) filter(where i_status = '6') as hapus, count(*) filter(where i_status = '5') as approve from tm_spbb group by id_schedule, d_entry ) as x on (a.id_schedule = x.id_schedule and a.d_entry = x.d_entry)
                        WHERE
                          a.d_spbb >= to_date('$dfrom', 'dd-mm-yyyy')
                          AND a.d_spbb <= to_date('$dto', 'dd-mm-yyyy') 
                          AND a.id_company = '$id_company'
                          AND a.i_status <> '5'
                        ORDER BY a.id ASC", FALSE);

    $datatables->edit('e_status_name', function ($data) {
      return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
    });
    
    $datatables->add('action', function ($data) {
            $id        = trim($data['id']);
            $i_menu    = $data['i_menu'];
            $folder    = $data['folder'];
            $i_status  = $data['i_status'];
            $dfrom     = $data['dfrom'];
            $dto       = $data['dto'];
            $hapus     = $data['hapus'];
            $approve   = $data['approve'];
            $data      = '';
            
            if(check_role($i_menu, 2)){
              $data           .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }

            // if (check_role($i_menu, 3)) {
            //     if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
            //         $data         .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
            //     }
            // }

            if (check_role($i_menu, 7) && $approve == 0) {  
              if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
              }
            }

            if (check_role($i_menu, 4) && ($i_status!='4' && $i_status!='6' && $i_status!='9') && $hapus == 0) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"5\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }
      return $data;
    });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('hapus');  
        $datatables->hide('approve'); 
        $datatables->hide('label_color');
        return $datatables->generate();
  }

  public function bagian() {
    $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
    $this->db->from('tr_bagian a');
    $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian and a.id_company = b.id_company','inner');
    $this->db->where('i_departement', $this->session->userdata('i_departement'));
    $this->db->where('i_level', $this->session->userdata('i_level'));
    $this->db->where('username', $this->session->userdata('username'));
    $this->db->where('b.id_company', $this->session->userdata('id_company'));
    $this->db->where('a.i_type', '07');
    $this->db->order_by('e_bagian_name');
    return $this->db->get();
  }

  public function bagianedit() {
    $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
    $this->db->from('tr_bagian a');
    $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
    $this->db->where('b.id_company', $this->session->userdata('id_company'));
    $this->db->where('a.i_type', '07');
    $this->db->order_by('e_bagian_name');
    return $this->db->get();
  }

  public function runningnumber($thbl,$tahun,$ibagian) {
        $id_company = $this->session->userdata('id_company');
        $cek = $this->db->query("
            SELECT 
                substring(i_spbb, 1, 4) AS kode 
            FROM tm_spbb 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            ORDER BY id DESC
        ");

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'SPBB';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_spbb, 11, 6)) AS max
            FROM
                tm_spbb
            WHERE to_char (d_spbb, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            AND substring(i_spbb, 1, 4) = '$kode'
            AND substring(i_spbb, 6, 2) = substring('$thbl',1,2)
        ", false);
        if ($query->num_rows() > 0){          
            foreach($query->result() as $row){
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number,"string");
            $n = strlen($number);        
            while($n < 6){            
                $number = "0".$number;
                $n = strlen($number);
            }
            $number = $kode."-".$thbl."-".$number;
            return $number;    
        }else{      
            $number = "000001";
            $nomer  = $kode."-".$thbl."-".$number;
            return $nomer;
        }
    }

    public function nextnumber($id, $ibagian) {
        // $id = 'SPBB-2011-000003';
       $id_company = $this->session->userdata('id_company');
       $query  = $this->db->query("
            SELECT id
            FROM
                tm_spbb
            WHERE 
            i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '$id_company'
            AND i_spbb = '$id'
        ", false);

        if ($query->num_rows() > 0){
          $newid =  substr($id,10,6)+1;
          $newcode =  substr($id,0,4);
          $newmon =  substr($id,5,4);
          settype($newid,"string");

          $n = strlen($newid);        
          while($n < 6){            
            $newid = "0".$newid;
            $n = strlen($newid);
          }
          $number = $newcode."-".$newmon."-".$newid;
          return $number;
        } else {
          return $id;
        }            
    }

    public function runningid() {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_spbb');
        return $this->db->get()->row()->id+1;
    }

    public function cek_kode($kode,$ibagian) {
        $this->db->select('i_spbb');
        $this->db->from('tm_spbb');
        $this->db->where('i_spbb', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode,$kodeold, $ibagian) {
        $this->db->select('i_spbb');
        $this->db->from('tm_spbb');
        $this->db->where('i_spbb', $kode);
        $this->db->where('i_spbb <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    function getreferensi(){
      $id_company = $this->session->userdata('id_company');
      $this->db->select(" 
          x.id_schedule as id, a.i_document,
          to_char(a.d_document, 'dd-mm-yyyy') AS d_document from (
            select id_schedule, sum(n_quantity_sisa) as n_sisa 
            from tm_schedule_item group by id_schedule
          ) as x
          inner join tm_schedule a on (a.id = x.id_schedule)
          where a.i_status = '6' and x.n_sisa > 0 and a.id_company = '$id_company'
      ");

      return $this->db->get();
    }

    function bacadetail($ischedule, $id_company){
      $this->db->select(" 
            x.*, a.e_product_wipname, b.e_material_name, c.e_color_name, '01' as i_type, a.id as id_product, b.id as id_material from (
              select a.id_company, a.id, b.i_product_wip, b.i_color, d.i_material, b.n_quantity_sisa , d.v_set, 
              d.v_gelar, round(cast(b.n_quantity_sisa / d.v_set as decimal(10,2)), 2) AS total_gelar,
              round(cast(b.n_quantity_sisa / d.v_set*v_gelar as decimal(10,2)), 2)  AS panjang_kain,
              d.f_bisbisan from 
              tm_schedule a
              inner join tm_schedule_item b on (a.id = b.id_schedule)
              inner join tr_polacutting d on (d.i_product_wip = b.i_product_wip  and d.i_color = b.i_color)
              where a.id_company = '$id_company' and d.id_company = '$id_company' and id_schedule = '$ischedule' and d.f_bisbisan = 'false'
              union all 
              select a.id_company, a.id, b.i_product_wip, b.i_color, d.i_material, b.n_quantity_sisa , d.v_set, 
              d.v_gelar, d.n_bagibis AS total_gelar, 
              --case when d.n_bagibis <= '0' then '0'
              --else
              round(cast((b.n_quantity_sisa*d.v_gelar*d.v_set)/ d.n_bagibis as decimal(10,2)), 2) AS panjang_kain,
              d.f_bisbisan from 
              tm_schedule a
              inner join tm_schedule_item b on (a.id = b.id_schedule)
              inner join tr_polacutting d on (d.i_product_wip = b.i_product_wip  and d.i_color = b.i_color)
              where a.id_company = '$id_company' and d.id_company = '$id_company' and id_schedule = '$ischedule' and d.f_bisbisan = 'true'
            ) as x
            inner join tr_product_wip a on (x.i_product_wip = a.i_product_wip and a.i_color = x.i_color and a.id_company = x.id_company)
            inner join tr_material b on (x.i_material = b.i_material and b.id_company = x.id_company)
            inner join tr_color c on (x.i_color = c.i_color and c.id_company = x.id_company)
            inner join tr_type d on (b.i_kode_group_barang = d.i_kode_group_barang)
            order by x.i_product_wip, i_color
      ", false);
      return $this->db->get();
    }

    function bacadetailedit($id_spbb, $id_company){
      $this->db->select(" 
           b.i_product_wip, b.e_product_wipname, b.i_color, d.e_color_name, c.i_material, c.e_material_name, a.n_quantity, e.n_quantity_sisa, a.n_set as v_set, 
            a.n_gelar as v_gelar, n_jumlah_gelar as total_gelar, n_panjang_kain as panjang_kain, a.f_bisbisan, a.id_product, a.id_material  from tm_spbb_item a
            inner join tr_product_wip b on (a.id_product = b.id)
            inner join tr_material c on (a.id_material = c.id)
            inner join tr_color d on (b.i_color = d.i_color and b.id_company = d.id_company)
            inner join tm_spbb f on (f.id = a.id_spbb)
            inner join tm_schedule_item e on (e.id_schedule = f.id_schedule and b.i_product_wip = e.i_product_wip and d.i_color = e.i_color)
            where a.id_spbb = '$id_spbb' and f.id_company = '$id_company'
            order by b.i_product_wip, b.i_color
      ", false);
      return $this->db->get();
    }

    public function changestatus($id,$istatus) {
        if ($istatus=='6') {
            $data = array(
                'i_status'  => $istatus,
                'e_approve' => $this->session->userdata('username'),
                'd_approve' => date('Y-m-d'),
            );
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_spbb', $data);
    }

    public function update_schedule($id_spbb,$id_schedule, $d_entry, $i_product, $i_color, $nquantity, $nquantitysisa) {
        // $id = 'SPBB-2011-000003';
       $id_company = $this->session->userdata('id_company');
       $query  = $this->db->query("select id from tm_spbb where id_schedule = '$id_schedule' and d_entry = '$d_entry' and i_status = '6' and id_company = '$id_company'", false);

        if ($query->num_rows() <= 0){
            $sisa  = $this->db->query("select n_quantity_sisa from tm_schedule_item where i_product_wip = '$i_product' and i_color = '$i_color' and id_schedule = '$id_schedule'", false)->row()->n_quantity_sisa;

            if ($nquantity > $sisa) {
               return 1;
            } else {
              $this->db->query("update tm_schedule_item set n_quantity_sisa = n_quantity_sisa - '$nquantity' where i_product_wip = '$i_product' and i_color = '$i_color' and id_schedule = '$id_schedule'", false);
              return 0;
            }
        } else {
          foreach ($query->result() as $row) {
              $id = $row->id;
              $wip  = $this->db->query("
                select a.i_product_wip, a.i_color from (
                  select id_product from tm_spbb_item where id_spbb = '$id' group by id_product
                ) as x
                inner join tr_product_wip a on (a.id = x.id_product)
                where a.i_product_wip = '$i_product' and a.i_color = '$i_color'
              ", false);

                if ($wip->num_rows() <= 0){
                    $sisa  = $this->db->query("select n_quantity_sisa from tm_schedule_item where i_product_wip = '$i_product' and i_color = '$i_color' and id_schedule = '$id_schedule'", false)->row()->n_quantity_sisa;

                    if ($nquantity > $sisa) {
                       return 1;
                    } else {
                      $this->db->query("update tm_schedule_item set n_quantity_sisa = n_quantity_sisa - '$nquantity' where i_product_wip = '$i_product' and i_color = '$i_color' and id_schedule = '$id_schedule'", false);
                      return 0;
                    }
                } else {
                            return 0;
                }
          }

        }  
        
    }


















  function getguang($igudang){
    $this->db->select('a.i_type_code, a.e_type_name, a.i_kode_kelompok, b.e_nama AS e_kategori, a.i_kode_group_barang, c.e_nama_group_barang');
    $this->db->from('tr_item_type a');
    $this->db->join('tm_kelompok_barang b','a.i_kode_kelompok = b.i_kode_kelompok');
    $this->db->join('tm_group_barang c','a.i_kode_group_barang = c.i_kode_group_barang');
    $this->db->where('a.i_type_code',$ikelompokbrg);
    $this->db->order_by('a.i_type_code', 'ASC');
    return $this->db->get();
  }

  public function hapus($id,$istatus){
    if($istatus=='9'){
      $gg =  $this->db->query("   SELECT
                                    i_schedule,
                                    d_spbb,
                                    i_status
                                  FROM
                                    tm_spbb
                                  WHERE
                                    i_spbb = '$id' ");
      foreach($gg->result() AS $c){
        $status = $c->i_status;
        
        $cc = $this->db->query("  SELECT
                                    i_schedule,
                                    d_spbb
                                  FROM
                                    tm_spbb
                                  WHERE
                                      i_schedule = '$c->i_schedule'
                                      AND d_spbb = '$c->d_spbb' 
                                      AND i_status = '6' ");
        if($cc->num_rows() > 0){
          return 2;
        }else{
          $this->db->query("  UPDATE
                                tm_spbb
                              SET
                                  i_status = '9'
                              WHERE
                                  i_schedule = '$c->i_schedule'
                                  AND d_spbb = '$c->d_spbb' 
                                  AND i_status != '6' ", FALSE);
        }
      } 
      $data = array(
        'i_status'  => $istatus,
      );
    }
    $this->db->where('i_spbb', $id);
    $this->db->update('tm_spbb', $data);
  }
  
  function ceknomor($thbl){
    $th = substr($thbl,0,4);
    $asal=$thbl;
    $thbl=substr($thbl,2,2).substr($thbl,4,2);
    $dep    = $this->session->userdata('i_lokasi');

    $this->db->select(" n_modul_no as max from tm_dgu_no 
                        where i_modul='SPBB'
                        and i_area = '$dep'
                        and substring(e_periode,1,4)='$th'", false);
    $query = $this->db->get();
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
      }
      $nospbb  =$terakhir+1;
      settype($nospbb,"string");

      $a = strlen($nospbb);
      while($a<5){
        $nospbb = "0".$nospbb;
        $a      = strlen($nospbb);
      }
      $nospbb = "SPBB-".$dep."-".$thbl."-".$nospbb;
      
      return $nospbb;
    }else{
      $nospbb  ="00001";
      $nospbb  ="SPBB-".$dep."-".$thbl."-".$nospbb;
      return $nospbb;
    }
  }
  
  function runningnumberispbb($thbl){
      $th = substr($thbl,0,4);
      $asal=$thbl;
      $thbl=substr($thbl,2,2).substr($thbl,4,2);
      $dep    = $this->session->userdata('i_lokasi');

      $this->db->select(" n_modul_no as max from tm_dgu_no 
                          where i_modul='SPBB'
                          and i_area = '$dep'
                          and substring(e_periode,1,4)='$th' for update", false);
      $query = $this->db->get();
      if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir=$row->max;
        }
        $nospbb  =$terakhir+1;
        $this->db->query(" update tm_dgu_no 
                                set n_modul_no=$nospbb
                                where i_modul='SPBB'
                                and i_area='$dep'
                                and substring(e_periode,1,4)='$th'", false);
        settype($nospbb,"string");
        $a=strlen($nospbb);
        while($a<5){
          $nospbb="0".$nospbb;
          $a=strlen($nospbb);
        }
          $nospbb  ="SPBB-".$dep."-".$thbl."-".$nospbb;
        return $nospbb;
      }else{
        $nospbb  ="00001";
        $nospbb  ="SPBB-".$dep."-".$thbl."-".$nospbb;
        $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
                           values ('SPBB','$dep','$asal',1)");
        return $nospbb;
      }
    }

  public function insertheader($id, $ibagian, $ispbb, $datespbb, $ischedule, $eremarkh, $gudang){  
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'id_company'    => $this->session->userdata('id_company'),
              'id'            => $id,
              'i_bagian'      => $ibagian,
              'i_spbb'        => $ispbb,
              'd_spbb'        => $datespbb,
              'id_schedule'   => $ischedule,
              'e_remark'      => $eremarkh,
              'i_type'        => $gudang,
              'd_entry'       => $dentry,
        );       
        $this->db->insert('tm_spbb', $data);
    }

    public function insertdetail($id_spbb, $id_product, $id_material, $sisa, $v_gelar, $v_set, $total_gelar, $panjang_kain, $fbisbisan){
        $data = array(
              'id_spbb'             => $id_spbb,
              'id_product'          => $id_product,
              'id_material'         => $id_material,
              'n_quantity'          => $sisa,
              'n_quantity_sisa'     => $sisa,
              'n_set'               => $v_set,
              'n_gelar'             => $v_gelar,
              'n_jumlah_gelar'      => $total_gelar,
              'n_panjang_kain'      => $panjang_kain,
              'n_panjang_kain_sisa' => $panjang_kain,
              'f_bisbisan'          => $fbisbisan,
        );       
        $this->db->insert('tm_spbb_item', $data); 
    }

    function deleteheader($id){
      $this->db->query("DELETE FROM tm_spbb WHERE id='$id'");
    }
    
    public function updateheaderschedule($ischedule,$ispbb,$datespbb,$igudang){
        $data = array(
            'i_spbb'    => $ispbb,
            'd_spbb'    => $datespbb,
            'i_gudang'  => $igudang,
    );

    $this->db->where('i_schedule', $ischedule);
    $this->db->update('tm_schedule', $data);
    }

    public function cek_data($id){          
          $this->db->select("
              a.id,
              a.i_spbb,
              to_char(a.d_spbb, 'dd-mm-yyyy') AS d_spbb,
              e.i_document,
              a.i_bagian,
              to_char(e.d_document, 'dd-mm-yyyy') AS d_schedule,
              d.e_type_name AS gudang,
              a.e_remark,
              a.id_schedule, 
              a.i_status,
              a.d_entry
            FROM
              tm_spbb a
            INNER JOIN tr_type d ON (a.i_type = d.i_type)
            INNER JOIN tm_schedule e ON (a.id_schedule = e.id)
            WHERE
              a.id = '$id'
          ", false);
          return $this->db->get();
    }

    public function cek_datadetail($ispbb){
          $this->db->select("   a.*,
                                b.e_color_name AS warna
                              FROM
                                tm_spbb_item a,
                                tr_color b
                              WHERE
                                a.i_color = b.i_color
                                AND a.i_spbb = '$ispbb'
                              GROUP BY
                                a.i_product,
                                a.e_product_name ,
                                a.i_color,
                                a.i_material,
                                a.e_material_name ,
                                a.n_quantity ,
                                a.n_set ,
                                a.n_gelar ,
                                a.jumlah_gelar ,
                                a.panjang_kain,
                                a.n_pemenuhan ,
                                a.i_schedule,
                                a.d_schedule,
                                a.i_spbb,
                                b.e_color_name,
                                a.d_spbb ,
                                a.n_item_no,
                                f_spbb_cancel
                              ORDER BY
                                a.i_product,
                                a.f_bisbisan ,
                                a.n_item_no ",false);
           return $this->db->get();
    }

    function updateheader($ispbb,$dspbb,$igudang,$eremarkh){ 
        $dupdate = date("Y-m-d H:i:s");
         $data = array(
            'd_spbb'    => $dspbb,
            'i_gudang'  => $igudang,
            'd_update'  => $dupdate,
            'e_remark'  => $eremarkh,
    );

    $this->db->where('i_spbb', $ispbb);
    $this->db->update('tm_spbb', $data);
    }

    function updateheadersch($ischedule,$ispbb,$dspbb,$igudang){
          $data = array(
                'd_spbb'    => $dspbb,
                'i_gudang'  => $igudang,
          );

    $this->db->where('i_schedule', $ischedule);
    $this->db->update('tm_schedule', $data);
    }

    function deletedetail($ispbb,$iproduct,$icolor,$imaterial){
      $this->db->query("DELETE FROM tm_spbb_item WHERE i_spbb='$ispbb' and i_product='$iproduct' and i_color='$icolor' and i_material='$imaterial'");
    }

     public function detailup($iproduct,$eproductname,$icolor,$imaterial,$ematerialname,$nquantity,$vset,$vgelar,$jumgelar,$pjgkain,$nitemno,$ischedule,$dschedule,$ispbb,$dspbb,$fbisbisan){
        $data = array(
              'i_product'       => $iproduct,
              'e_product_name'  => $eproductname,
              'i_color'         => $icolor,
              'i_material'      => $imaterial,
              'e_material_name' => $ematerialname,
              'n_quantity'      => $nquantity,
              'n_set'           => $vset,
              'n_gelar'         => $vgelar,
              'jumlah_gelar'    => $jumgelar,
              'panjang_kain'    => $pjgkain,
              'i_schedule'      => $ischedule,
              'd_schedule'      => $dschedule,
              'i_spbb'          => $ispbb,
              'd_spbb'          => $dspbb,
              'f_bisbisan'      => $fbisbisan,
              'n_item_no'       => $nitemno,
        );       
        $this->db->insert('tm_spbb_item', $data); 
    }
}
/* End of file Mmaster.php */