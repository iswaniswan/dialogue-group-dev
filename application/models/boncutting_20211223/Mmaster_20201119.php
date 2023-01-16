<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    function data($i_menu,$folder,$dfrom, $dto)
    {
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT
                a.id,
                a.i_document,
                to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
                c.i_document AS i_schedule,
                a.e_remark,
                e_status_name,
                label_color,
                a.i_status,
                '$i_menu' AS i_menu,
                '$folder' AS folder,
                '$dfrom' AS dfrom,
                '$dto' AS dto
            FROM
                tm_keluar_cutting a
            INNER JOIN tr_status_document b ON
                (b.i_status = a.i_status)
            INNER JOIN tm_schedule c ON
                (c.id = a.id_reff)
            WHERE
                a.i_status <> '5'
                AND a.id_company = '".$this->session->userdata('id_company')."'
                $and
            ORDER BY
                a.id ");

        $datatables->edit('i_status', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id       = $data['id'];
            $i_status = trim($data['i_status']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3)) {
                if (($i_status == '1' || $i_status == '2' || $i_status == '3' || $i_status == '7')) {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 7)) {
                if ($i_status == '2') {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }
            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('label_color');
        $datatables->hide('e_status_name');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function gudang()
    {
      return $this->db->query("
          SELECT
          a.i_departement,
          c.e_departement_name
          FROM
          public.tm_user_cover a
          INNER JOIN public.tr_departement c ON
          (c.i_departement = a.i_departement)
          WHERE
          a.status = 't'
          AND a.i_type = '02'
          AND username = '".$this->session->userdata('username')."'
          AND id_company = ".$this->session->userdata('id_company')."
          ", FALSE);
  }



  /* INI BACA SCHEDULE */
  function getreferensi(){
    return $this->db->query(" SELECT
        b.i_schedule,
        sum(b.n_order) AS n_order,
        sum(b.n_deliver) AS n_deliver,
        sum(b.n_order-b.n_deliver) AS sisa
        FROM
        (
        /**HITUNG BARANG YANG ADA DI BONK CUTTING SEBAGAI DELIVER**/
        SELECT i_schedule , i_product, n_pemenuhan AS n_order, n_quantity_product AS n_deliver  FROM(
        SELECT
        DISTINCT a.i_bonk, b.i_schedule , i_product, n_quantity_product, 0 AS n_pemenuhan
        FROM
        tm_bonkeluar_cutting_item a
        INNER JOIN tm_bonkeluar_cutting b ON
        (a.i_bonk = b.i_bonk AND b.f_bonk_cancel = 'f' AND i_status != '9')
        ) AS a
        /*******************************************/
        UNION ALL
        /**AMBIL NILAI ORDER DARI SCHEDULE **/
        SELECT
        DISTINCT a.i_schedule , i_product, n_quantity AS n_order, 0 AS n_deliver
        FROM
        tm_schedule_itemdetail a
        INNER JOIN tm_schedule b on(a.i_schedule = b.i_schedule AND b.f_schedule_cancel = 'f' AND i_status = '6')
                                  /*WHERE
                                  a.n_pemenuhan > 0*/
                                  /************************************/
                                  ) AS b
                                  GROUP BY
                                  b.i_schedule
                                  ORDER BY i_schedule  ");
}

  #function bacadetail($ischedule,$tmpproduct,$tmpqty){
function bacadetail($ischedule){
    /* if($tmpproduct == '' || $tmpproduct == NULL){
      $tmp = "";
    }else{
      $tmp = "AND a.i_product = '$tmpproduct'";
  } */

  $this->db->select("   a.*,
      b.e_color_name AS warna,
      c.e_namabrg AS e_product_name,
      d.v_toset,
      CASE
      WHEN e.n_quantity_product IS NULL THEN 0
      ELSE e.n_quantity_product
      END AS n_qtytmp
      FROM
      tm_schedule_itemdetail a
      INNER JOIN tr_color b ON
      (a.i_color = b.i_color)
      INNER JOIN tm_barang_wip c ON
      (a.i_product = c.i_kodebrg)
      INNER JOIN tr_polacutting d ON
      (a.i_material = d.i_material
      AND a.i_color = d.i_color
      AND a.i_product = d.i_product)
      LEFT JOIN v_bonk_cutting_delivtmp e ON
      (a.i_schedule = e.i_schedule
      AND a.i_product = e.i_product)
      WHERE
      a.i_schedule = '$ischedule'
      /*AND n_pemenuhan > 0*/ ", false);
  return $this->db->get();
}
/* **************************************************************************************  */

/* INI BACA BONK */
function getbonk(){
    return $this->db->query("   SELECT
      DISTINCT a.i_bonk,
      b.d_bonk
      FROM
      tm_bonkeluar_cutting_item a
      INNER JOIN tm_bonkeluar_cutting b ON
      (a.i_bonk = b.i_bonk)
      WHERE
      n_sisa > 0
      /*n_material_sisa < n_quantity_material*/
      AND b.i_status != '6'
      ORDER BY
      i_bonk  ");
}

function bacabonkdetail($ibonk){
    $this->db->select("   a.i_bonk,
      a.i_product,
      a.e_product_name,
      a.i_color_wip,
      a.e_color_name,
      a.qtywip,
      a.i_material,
      a.e_material_name,
      a.qtymaterial,
      a.n_material_sisa,
      a.i_schedule,
      b.n_set,
      b.n_gelar,
      a.v_toset,
      a.e_remark,
      a.i_departement,
      a.tujuan
      FROM
      (
      SELECT
      a.i_bonk, a.i_product, a.e_product_name, a.i_color_wip, b.e_color_name, a.n_quantity_product AS qtywip, a.i_material, c.e_material_name, a.n_quantity_material AS qtymaterial, a.n_material_sisa, d.v_toset, e.i_schedule, a.e_remark, e.i_departement, e.i_sub_bagian AS tujuan
      FROM
      tm_bonkeluar_cutting_item a
      INNER JOIN tr_color b ON
      a.i_color_wip = b.i_color
      INNER JOIN tr_material c ON
      a.i_material = c.i_material
      INNER JOIN tr_polacutting d ON
      (a.i_material = d.i_material
      AND a.i_color_wip = d.i_color
      AND a.i_product = d.i_product)
      INNER JOIN tm_bonkeluar_cutting e ON
      (a.i_bonk = e.i_bonk) ) AS a
      LEFT JOIN tm_schedule_itemdetail b ON
      (a.i_schedule = b.i_schedule
      AND a.i_product = b.i_product
      AND a.i_color_wip = b.i_color
      AND a.i_material = b.i_material)
      WHERE
      a.i_bonk = '$ibonk'
      AND a.n_material_sisa < a.qtymaterial
      ORDER BY
      a.i_bonk,
      a.i_product,
      a.i_material ", false);
    return $this->db->get();
}
/* **************************************************************************************  */

public function ngadug()
{
    $idepartemen = $this->session->userdata('i_departement');
    return $this->db->query("
        SELECT
        b.*
        FROM
        tr_tujuan_detail a,
        public.tr_departement b
        WHERE
        a.i_departement_tujuan = b.i_departement
        AND a.i_modul = 'BONK'
        AND i_departement_asal = '13'
        ", FALSE);
}

public function bacagudang(){
    $this->db->select(" * from public.tr_departement /*where i_departement = '14'*/ ORDER BY i_departement ",false);
    return $this->db->get();
    //return $this->db->order_by('e_nama_master','ASC')->get('tr_master_gudang')->result();
}

public function gethead($iproduct){
   return $this->db->query("SELECT * from tm_barang_wip
      where i_kodebrg = '$iproduct'", false);
}

public function getdetail($iproduct){
    return $this->db->query(" SELECT a.*, b.e_material_name, c.e_namabrg, d.e_color_name
      from tr_polacutting a
      inner join tr_material b on a.i_material = b.i_material
      inner join tm_barang_wip c on a.i_product = c.i_kodebrg
      inner join tr_color d on a.i_color = d.i_color
      where i_product = '$iproduct'", false);
}

public function getheadbonk($ibonk){
 return $this->db->query("SELECT * from tm_bonkeluar_cutting where i_bonk = '$ibonk'", false);
}

public function getdetailbonk($ibonk){
    return $this->db->query(" SELECT a.*, b.e_material_name, c.e_color_name
      from tm_bonkeluar_cutting_item a
      inner join tr_material b on a.i_material = b.i_material
      inner join tr_color c on a.i_color_wip = c.i_color
      where i_bonk = '$ibonk' and f_item_complete = 'f'", false);
}


function runningnumberbonk($thbl,$dep,$ijenis,$ibonk){
  $th     = substr($thbl,0,4);
  $asal   = $thbl;
  $thbl   = substr($thbl,2,2).substr($thbl,4,2);

  if($ijenis == "0"){
    $query  = $this->db->query("  SELECT
      n_modul_no AS max
      FROM
      tm_dgu_no
      WHERE
      i_modul = 'BCC'
      AND i_area = '$dep'
      AND substr(e_periode, 1, 4) = '$th' FOR
      UPDATE ", false);
    if ($query->num_rows() > 0){
      foreach($query->result() as $row){
        $terakhir=$row->max;
    }
    $nobonmk  =$terakhir+1;
    $this->db->query("  UPDATE
      tm_dgu_no
      SET
      n_modul_no = $nobonmk
      WHERE
      i_modul = 'BCC'
      AND i_area = '$dep'
      AND substr(e_periode, 1, 4) = '$th' ", false);
    settype($nobonmk,"string");
    $a=strlen($nobonmk);
    while($a<5){
        $nobonmk="0".$nobonmk;
        $a=strlen($nobonmk);
    }
    $nobonmk  ="BCC-".$dep."-".$thbl."-".$nobonmk;
    return $nobonmk;
}else{
  $nobonmk  ="00001";
  $nobonmk  ="BCC-".$dep."-".$thbl."-".$nobonmk;
  $this->db->query(" insert into tm_dgu_no(i_modul, i_area, e_periode, n_modul_no) 
    values ('BCC','$dep','$asal',1)");
  return $nobonmk;
}
}else{ /* ********************************************* */
    $query  = $this->db->query("  SELECT
        RIGHT(max, 2)::NUMERIC AS max
        FROM
        (
        SELECT
        max(i_bonk)
        FROM
        tm_bonkeluar_cutting
        WHERE
        i_bonk LIKE '$ibonk%' ) AS a
        WHERE
        length(max) = '20' ", false);
    if ($query->num_rows() > 0){
        foreach($query->result() as $row){
          $terakhir = $row->max;
      }
      $nobonmk  = $terakhir+1;
      settype($nobonmk,"string");
      $a=strlen($nobonmk);

      while($a<2){
          $nobonmk="0".$nobonmk;
          $a=strlen($nobonmk);
      }

      $ibonk    = substr($ibonk,0,17);
      $nobonmk  = $ibonk."-".$nobonmk;

      return $nobonmk;
  }else{
    $nobonmk  ="-01";
    $nobonmk  = $ibonk.$nobonmk;

    return $nobonmk;
}
}
}

public function changestatus($id,$istatus){
    $iapprove = $this->session->userdata('username');
    if ($istatus=='6') {

        // $this->db->query("  UPDATE
        //                       tm_schedule_itemdetail
        //                     SET
        //                       n_quantity_pemenuhan = n_quantity_pemenuhan - $nquantity
        //                     WHERE
        //                       i_schedule = '$ischedule'
        //                       AND i_product = '$iproduct'
        //                       AND trim(i_color) = '$icolor'
        //                       AND n_quantity_pemenuhan > 0 ", FALSE);

        $data = array(
            'i_status'  => $istatus,
            'i_approve' => $iapprove,
            'd_approve' => date('Y-m-d'),
        );
    }else{
      $data = array(
          'i_status' => $istatus,
      );
  }
  $this->db->where('i_bonk', $id);
  $this->db->update('tm_bonkeluar_cutting', $data);
}

public function getspbb_detail($ispbb){
  return $this->db->query("
      select a.*,  b.e_color_name, c.e_product_name ,c.e_material_name 
      from tm_spbb_itemdetail a
      inner join tr_color b on(a.i_color = b.i_color)
      inner join tm_spbb_item c on(a.i_product = c.i_product and a.i_material = c.i_material)
      where a.i_spbb = '$ispbb'
      ", false);
}

  /* public function cancel($ibonk){
    $data = array(
      'f_bonk_cancel'=>TRUE,
  );
    $this->db->where('i_bonk', $ibonk);
    $this->db->update('tm_bonkeluar_cutting', $data);
} */

public function insertheader($ibonk, $datebonk, $itujuan, $thbl, $ischedule, $idepartement){  
    $dentry = date("Y-m-d H:i:s");
    $data = array(
      'i_bonk'        => $ibonk,
      'd_bonk'        => $datebonk,
      'i_sub_bagian'  => $itujuan,
      'i_departement' => $idepartement,
      'i_periode'     => $thbl,
      'i_schedule'    => $ischedule,
      'd_entry'       => $dentry,
  );       
    $this->db->insert('tm_bonkeluar_cutting', $data);
}
function updatebonkheader($ibonk){
  $data = array(
    'i_status'      => '1',
);
  $this->db->where('i_bonk',$ibonk);
  $this->db->update('tm_bonkeluar_cutting', $data);
}
function updatebonkref($ibonk,$iproduct,$icolor,$imaterial){
  $data = array(
    'n_sisa'          => 0,
);
  $this->db->where('i_bonk',$ibonk);
  $this->db->where('i_product',$iproduct);
  $this->db->where('i_material',$imaterial);
  $this->db->where('i_color_wip',$icolor);
  $this->db->update('tm_bonkeluar_cutting_item', $data);
}
function updatebonkdetail($ibonk,$iproduct,$eproductname,$icolor,$nquantity,$nquantitym,$ndeliver,$sisamaterial,$keterangan,$imaterial,$j,$cek){
  $data = array(
    'n_material_sisa' => $ndeliver,
    'e_remark'        => $keterangan,
    'n_sisa'          => $sisamaterial
);
  $this->db->where('i_bonk',$ibonk);
  $this->db->where('i_product',$iproduct);
  $this->db->where('i_material',$imaterial);
  $this->db->where('i_color_wip',$icolor);
  $this->db->update('tm_bonkeluar_cutting_item', $data);
}
function insertbonkdetail($ibonk,$iproduct,$eproductname,$icolor,$nquantity,$nquantitym,$ndeliver,$sisamaterial,$keterangan,$imaterial,$j,$cek,$ischedule){
    $data = array(
      'i_bonk'              =>$ibonk,
      'i_product'           =>$iproduct,
      'e_product_name'      =>$eproductname,
      'i_color_wip'         =>$icolor,
      'n_quantity_product'  =>$nquantity,
      'i_material'          =>$imaterial,
      'n_quantity_material' =>$nquantitym,
      'n_material_sisa'     =>$ndeliver,
      'e_remark'            =>$keterangan,
      'n_item_no'           =>$j,
      'f_item_complete'     =>$cek,
      'n_sisa'              =>$sisamaterial,
  );
    $this->db->insert('tm_bonkeluar_cutting_item', $data);
}

function insertbonkdetailitem($iproduct,$icolor,$npemenuhan,$ibonk,$ischedule,$nitemno, $imaterial, $nquantity){
  $dentry = date("Y-m-d H:i:s");
  $data = array(
    'i_bonk'          => $ibonk  ,
    'i_product'       => $iproduct  ,
    'i_color'         => $icolor ,
    'i_material'      => $imaterial  ,
    'n_quantity'      => $nquantity ,
    'd_entry'         => $dentry ,
    'n_item_no'       => $nitemno ,
    'n_no'            => $nitemno ,
    'n_pemenuhan'     => $npemenuhan 
);
  $this->db->insert('tm_bonmkeluar_cutting_itemdetail', $data);
}

function updatescheduledetail($iproduct,$eproductname,$icolor,$ecolorname,$nquantity,$npemenuhan,$eremark,$ibonk,$dschedule,$ischedule,$nitemno,$datebonk){
    $query  = $this->db->query("SELECT SUM(n_pemenuhan) as saldo FROM tm_schedule_item
      WHERE i_product='$iproduct'
      AND i_color='$icolor'
      AND f_item_cancel='FALSE'
      AND i_schedule='$ischedule'");
    if($query->num_rows()>0){
        $row  = $query->row();
        $nsaldo = $row->saldo;
        $nsaldopemenuhan = $nsaldo + $npemenuhan;
        $this->db->set(
            array(
              'i_bonk'      => $ibonk,
              'd_bonk'      => $datebonk,
              'n_pemenuhan' => $nsaldopemenuhan,
          )
        );
        $this->db->where('i_schedule',$ischedule);
        $this->db->where('i_product',$iproduct);
        $this->db->where('i_color',$icolor);
        $this->db->where('n_quantity',$nquantity);
        $this->db->update('tm_schedule_item');
    }else{
      $this->db->set(
        array(
          'i_bonk'      => $ibonk,
          'd_bonk'      => $datebonk,
          'n_pemenuhan' => $npemenuhan,
      )
    );
      $this->db->where('i_schedule',$ischedule);
      $this->db->where('i_product',$iproduct);
      $this->db->where('i_color',$icolor);
      $this->db->where('n_quantity',$nquantity);
      $this->db->update('tm_schedule_item');
  }
}

public function cek_data($ibonk){          
  $this->db->select("   a.*,
    b.e_departement_name,
    c.d_schedule
    FROM
    tm_bonkeluar_cutting a
    INNER JOIN public.tr_departement b ON
    a.i_sub_bagian = b.i_departement
    INNER JOIN tm_schedule c ON
    a.i_schedule = c.i_schedule
    WHERE
    a.i_bonk = '$ibonk' ", false);
  return $this->db->get();
}

public function cek_datadetail($ibonk){
  $this->db->select("   a.*,
    b.e_material_name,
    c.e_color_name
    FROM
    tm_bonkeluar_cutting_item a
    INNER JOIN tr_material b ON
    a.i_material = b.i_material
    INNER JOIN tr_color c ON
    a.i_color_wip = c.i_color
    WHERE
    a.i_bonk = '$ibonk' ",false);
  return $this->db->get();
}

function updateheader($ibonk,$datebonk,$eremark){ 
    $dupdate  = date("Y-m-d H:i:s");
    $data = array(
        'd_bonk'    => $datebonk,
        'd_update'  => $dupdate,
        'e_remark'  => $eremark,
    );
    $this->db->where('i_bonk',$ibonk);
    $this->db->update('tm_bonkeluar_cutting', $data);
}

function deletedetail($ibonk){
  $this->db->query("DELETE FROM tm_bonkeluar_cutting_item WHERE  i_bonk='$ibonk' ");
}

function updatesaldo($ibonk,$dbonk,$ischedule,$iproduct,$icolor){
    $query  = $this->db->query("SELECT SUM(n_quantity) as saldo FROM tm_bonmkeluar_cutting_item
      WHERE i_product='$iproduct'
      AND i_color='$icolor'
      AND f_item_cancel='FALSE'
      AND i_schedule='$ischedule'");
    $row  = $query->row();
    $nsaldopemenuhan = $row->saldo;
    $this->db->set(
        array(
          'i_bonk'      => $ibonk,
          'd_bonk'      => $dbonk,
          'n_pemenuhan' => $nsaldopemenuhan,
      )
    );
    $this->db->where('i_schedule',$ischedule);
    $this->db->where('i_product',$iproduct);
    $this->db->where('i_color',$icolor);
    $this->db->update('ttm_schedule_item');
}
}
/* End of file Mmaster.php */