<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  public function __construct(){
        parent::__construct();
        $this->company     = $this->session->id_company;
        $this->departement = $this->session->i_departement;
        $this->username    = $this->session->username;
        $this->level       = $this->session->i_level;
  }

	public function data($folder,$i_menu,$dfrom,$dto){
        if ($dfrom!='' && $dto!='') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $and   = "AND d_document BETWEEN '$dfrom' AND '$dto'";
        }else{
            $and   = "";
        }
        $cek = $this->db->query("
            SELECT
                i_bagian
            FROM
                tm_nota_penjualan_bb
            WHERE
                i_status <> '5'
                AND id_company = $this->company
                AND i_bagian IN (
                    SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company)
            ", FALSE);
        if ($this->departement=='1') {
            $bagian = "";
        }else{
            if ($cek->num_rows()>0) {                
                $i_bagian = $cek->row()->i_bagian;
                $bagian = "AND a.i_bagian = '$i_bagian' ";
            }else{
                $bagian = "AND a.i_bagian IN (SELECT
                        i_bagian
                    FROM
                        tr_departement_cover
                    WHERE
                        i_departement = '$this->departement'
                        AND username = '$this->username'
                        AND id_company = $this->company) ";
            }
        }

        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT distinct 
             0 AS NO,
             a.id,
             a.i_document,
             to_char(a.d_document, 'dd-mm-yyyy') as d_document,
             to_char(a.d_terima_faktur, 'dd-mm-yyyy') as d_terima_faktur,
             CASE
                    WHEN a.e_partner_type = 'supplier' THEN e_supplier_name
                    WHEN a.e_partner_type = 'customer' THEN e_customer_name
                    WHEN a.e_partner_type = 'karyawan' THEN e_nama_karyawan
                    WHEN a.e_partner_type = 'bagian' THEN e_bagian_name
             END AS e_partner_name,
             a.v_bersih,
             a.e_remark,
             a.i_status,
             d.e_status_name,
             d.label_color,
             '$i_menu' AS i_menu,
             '$folder' AS folder,
             '$dfrom' AS dfrom,
             '$dto' AS dto 
          FROM
             tm_nota_penjualan_bb a 
              LEFT JOIN tr_supplier e ON (e.id = a.id_partner)
              LEFT JOIN tr_customer f ON (f.id = a.id_partner)
              LEFT JOIN tr_karyawan g ON (g.id = a.id_partner)
              LEFT JOIN tr_bagian h ON (h.id = a.id_partner)
             JOIN tr_status_document d ON (a.i_status = d.i_status)
            WHERE
                a.i_status <> '5'
                AND a.id_company = $this->company 
                $and 
                $bagian
            ORDER BY
                a.id DESC
        ", FALSE);

        $datatables->edit('e_status_name', function ($data) {
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->edit('v_bersih', function ($data) {
            $data = "Rp. ".number_format($data['v_bersih']);
            return $data;
        });

        $datatables->add('action', function ($data) {
            $id       = trim($data['id']);
            $i_menu   = $data['i_menu'];
            $folder   = $data['folder'];
            $i_status = $data['i_status'];
            $dfrom    = $data['dfrom'];
            $dto      = $data['dto'];
            $data     = '';
            
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye '></i></a>&nbsp;&nbsp;&nbsp;";
            }
            
            if (check_role($i_menu, 3)) {
                if ($i_status == '1'|| $i_status == '2' || $i_status == '3' || $i_status == '7') {
                    $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt '></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }

            if (check_role($i_menu, 7) && ($i_status=='2')) {
                $data .= "<a href=\"#\" title='Approve' onclick='show(\"$folder/cform/approval/$id/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box'></i></a>&nbsp;&nbsp;&nbsp;";
            }   

            if (check_role($i_menu, 5)  && ($i_status=='6')) {
                $data .= "<a href=\"#\" title='Cetak SPB' onclick='cetak(\"$id\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-print'></i></a>";
            }

            if (check_role($i_menu, 4)  && ($i_status=='1')) {
                $data .= "<a href=\"#\" title='Batal' onclick='statuschange(\"$folder\",\"$id\",\"9\",\"$dfrom\",\"$dto\",); return false;'><i class='ti-close'></i></a>";
            }

            return $data;
        });
        $datatables->hide('id');
        $datatables->hide('i_menu');
        $datatables->hide('i_status');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('label_color');
        return $datatables->generate();
  }

  public function awalnext(){
      $idcompany  = $this->session->userdata('id_company');
      $datatables = new Datatables(new CodeigniterAdapter);
      $datatables->query("
                        WITH CTE AS (
                               select   
                               0 AS NO,
                               ROW_NUMBER() OVER (ORDER BY x.id_sj) AS i,
                               x.jenis, x.id_sj, x.i_document, x.d_document, x.id_partner, x.e_partner_type,
                               CASE
                                            WHEN x.e_partner_type = 'supplier' THEN e_supplier_name
                                            WHEN x.e_partner_type = 'customer' THEN e_customer_name
                                            WHEN x.e_partner_type = 'karyawan' THEN e_nama_karyawan
                                            WHEN x.e_partner_type = 'bagian' THEN e_bagian_name
                                     END AS e_partner_name
                               from (
                              select 'Bahan Baku' as jenis, id as id_sj, i_document, to_char(d_document, 'dd-mm-yyyy') as d_document, id_partner, e_partner_type 
                              from tm_penjualan_bb where id_company = '$idcompany' and i_status = '6' and f_nota_created = 'f'
                              union all
                              select 'Aksesoris' as jenis,id as id_sj, i_document, to_char(d_document, 'dd-mm-yyyy') as d_document, id_partner, e_partner_type 
                              from tm_penjualan_ak where id_company = '$idcompany' and i_status = '6' and f_nota_created = 'f'
                              union all
                              select 'Aksesoris Packing' as jenis,id as id_sj, i_document, to_char(d_document, 'dd-mm-yyyy') as d_document, id_partner, e_partner_type 
                              from tm_penjualan_bp where id_company = '$idcompany' and i_status = '6' and f_nota_created = 'f'
                               ) as x  
                              LEFT JOIN tr_supplier e ON (e.id = x.id_partner)
                              LEFT JOIN tr_customer f ON (f.id = x.id_partner)
                              LEFT JOIN tr_karyawan g ON (g.id = x.id_partner)
                              LEFT JOIN tr_bagian h ON (h.id = x.id_partner)
                        )
                        select no, i, id_sj, id_partner, e_partner_name, e_partner_type, i_document , d_document, jenis, ( SELECT count(i) AS jml FROM CTE) AS jml FROM CTE
                        order by e_partner_name, d_document
                          ", false);


    $datatables->add('action', function ($data) {
        $id_sj            = $data['id_sj'];
        $jenis            = $data['jenis'];
        $id_partner       = $data['id_partner'];
        $e_partner_type   = $data['e_partner_type'];
        $e_partner_name   = $data['e_partner_name'];

        $jml      = $data['jml'];
        $i      = $data['i'];
        
        $data    = '';
        $data  .= "
                <label class=\"custom-control custom-checkbox\">
                <input type=\"checkbox\" id=\"chk\" name=\"chk".$i."\" class=\"custom-control-input\">
                <span class=\"custom-control-indicator\"></span><span class=\"custom-control-description\"></span>
                <input name=\"id_sj".$i."\" value=\"".$id_sj."\" type=\"hidden\">
                <input name=\"jenis".$i."\" value=\"".$jenis."\" type=\"hidden\">
                <input name=\"id_partner".$i."\" value=\"".$id_partner."\" type=\"hidden\">
                <input name=\"e_partner_type".$i."\" value=\"".$e_partner_type."\" type=\"hidden\">
                <input name=\"e_partner_name".$i."\" value=\"".$e_partner_name."\" type=\"hidden\">
                <input name=\"jml\" value=\"".$jml."\" type=\"hidden\">";
        //$data .= "<a href=\"#\" title='Edit' onclick='callswal(\"$id\",\"$isupplier\",\"$iop\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;";
          
      return $data;
    });
    $datatables->hide('id_sj');
    $datatables->hide('i');  
    $datatables->hide('jml');
    $datatables->hide('id_partner');

    return $datatables->generate();
  }

  public function bagian(){
      $this->db->select('a.id, a.i_bagian, e_bagian_name')->distinct();
      $this->db->from('tr_bagian a');
      $this->db->join('tr_departement_cover b','b.i_bagian = a.i_bagian','inner');
      $this->db->where('a.f_status', 't');
      $this->db->where('i_departement', $this->departement);
      $this->db->where('username', $this->username);
      $this->db->where('a.id_company', $this->company);
      $this->db->order_by('e_bagian_name');
      return $this->db->get();
  }

  public function get_kodeharga() {
      $idcompany  = $this->session->userdata('id_company');
      $this->db->select(" id,i_harga, e_harga from tr_harga_kode where id_company = '$idcompany' order by i_harga ", FALSE);
      return $this->db->get();
  }

  public function runningnumber($thbl,$tahun,$ibagian){
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode
            FROM tm_nota_penjualan_bb
            WHERE 
                i_status <> '5'
                --AND i_bagian = '$ibagian'
                AND id_company = $this->company
            ORDER BY id DESC LIMIT 1
            ", FALSE);

        if ($cek->num_rows()>0) {
            $kode = $cek->row()->kode;
        }else{
            $kode = 'FP';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
                tm_nota_penjualan_bb
            WHERE 
                i_status <> '5'
                --AND i_bagian = '$ibagian'
                AND id_company = $this->company
                AND substring(i_document, 1, 2) = '$kode'
                AND substring(i_document, 4, 2) = substring('$thbl',1,2)
                AND to_char (d_document, 'yyyy') >= '$tahun'
            ", FALSE);
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

  function get_customer($id_partner, $arr_jenis_sj, $id_sj, $e_partner_name, $e_partner_type){
      $idcompany  = $this->session->userdata('id_company');
      $and1 = '';
      $and2 = '';
      $and3 = '';
      $and = "select d_document, '$e_partner_name' as e_partner_name from tm_penjualan_bb where id_partner = '$id_partner' 
                      and id IN (".$id_sj.") and id_company = '$idcompany' and e_partner_type = $e_partner_type
              union all
              select d_document, '$e_partner_name' as e_partner_name from tm_penjualan_ak where id_partner = '$id_partner' 
                      and id IN (".$id_sj.") and id_company = '$idcompany' and e_partner_type = $e_partner_type
              union all
              select d_document, '$e_partner_name' as e_partner_name from tm_penjualan_bp where id_partner = '$id_partner' 
                      and id IN (".$id_sj.") and id_company = '$idcompany' and e_partner_type = $e_partner_type
              ";
      // foreach ($arr_jenis_sj as $key) {
      //   if ($key == 'Bahan Baku') {
      //     $and1 = "  ";
      //   }

      //   if ($key == 'Aksesoris') {
      //     $and2   = " ";
      //   }

      //   if ($key == 'Aksesoris Packing') {
      //     $and3  = " ";
      //   }
      // }

      $this->db->select(" 
        to_char(tgl, 'dd-mm-yyyy') as tgl, e_partner_name, $e_partner_type as e_partner_type,
        CASE WHEN $e_partner_type = 'customer' THEN f.n_customer_toplength ELSE '0' END AS n_customer_toplength,
        CASE 
            WHEN $e_partner_type = 'customer' THEN f.f_pkp 
            WHEN $e_partner_type = 'supplier' THEN e.f_pkp
            ELSE FALSE 
        END AS f_pkp, 
        CASE 
            WHEN $e_partner_type = 'customer' THEN f.id_harga_kode 
            ELSE '0' 
        END AS id_harga_kode,
        CASE 
            WHEN $e_partner_type = 'customer' THEN g.e_harga 
            ELSE '0' 
        END AS e_harga
        from (
          select max(x.d_document) as tgl, x.e_partner_name
          from (
          $and
          ) as x
          group by x.e_partner_name
        ) as x
        LEFT JOIN tr_supplier e ON (e.id = $id_partner)
        LEFT JOIN tr_customer f ON (f.id = $id_partner)
        left join tr_harga_kode g on (f.id_harga_kode = g.id)
      ", FALSE);
    return $this->db->get();
  }

  function get_item2($id_partner, $arr_jenis_sj, $id_sj, $e_partner_type, $jenis){
      $idcompany  = $this->session->userdata('id_company');
      $where   = " b.id_document IN (".$id_sj.")";
      $this->db->select("
        x.*, a.e_material_name, a.i_material, coalesce(v_customer_discount,0) as disc1, coalesce(v_customer_discount2,0) as disc2, coalesce(v_customer_discount3,0) as disc3 from 
        (
            select 'Bahan Baku' as jenis, b.id_document, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') as d_document, b.id_material, b.n_quantity, b.e_remark from tm_penjualan_bb a 
            inner join tm_penjualan_bb_item b on (a.id = b.id_document and a.id_company = b.id_company)
            where $where and a.id_partner = '$id_partner' and e_partner_type = $e_partner_type
            union all
            select 'Aksesoris' as jenis, b.id_document, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') as d_document, b.id_material, b.n_quantity, b.e_remark from tm_penjualan_ak a 
            inner join tm_penjualan_ak_item b on (a.id = b.id_document and a.id_company = b.id_company)
            where $where and a.id_partner = '$id_partner' and e_partner_type = $e_partner_type
            union all
            select 'Aksesoris Packing' as jenis, b.id_document, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') as d_document, b.id_material, b.n_quantity, b.e_remark from tm_penjualan_bp a 
            inner join tm_penjualan_bp_item b on (a.id = b.id_document and a.id_company = b.id_company)
            where $where and a.id_partner = '$id_partner' and e_partner_type = $e_partner_type
        ) as x
        inner join tr_material a on (x.id_material = a.id)
        LEFT JOIN tr_customer f ON (f.id = $id_partner)
        where jenis IN ($jenis)
        order by x.jenis, x.id_document, a.e_material_name
      ", FALSE);
      /*left join tr_harga_jualbb c on (c.id_harga_kode = '$kode_harga' AND (c.d_berlaku >= '$d_document' OR c.d_akhir isnull) )*/
      return $this->db->get();
  }

  function get_harga_item($kodeharga, $ddocument, $id_material){
      $idcompany  = $this->session->userdata('id_company');
      $where   = " id_material IN (".$id_material.")";
      $this->db->select(" 
        id_material, v_price from tr_harga_jualbb 
        where f_status = 't' and id_company = '$idcompany' and $where and id_harga_kode = '$kodeharga' 
        and (d_berlaku >= to_date('$ddocument', 'dd-mm-yyyy') OR d_akhir isnull)
      ", FALSE);
      /*left join tr_harga_jualbb c on (c.id_harga_kode = '$kode_harga' AND (c.d_berlaku >= '$d_document' OR c.d_akhir isnull) )*/
      return $this->db->get();
  }

  function get_head_edit($id){
      $idcompany  = $this->session->userdata('id_company');

      $this->db->select(" 
            a.id_company, a.id, a.i_document, to_char(a.d_document, 'dd-mm-yyyy') as tgl, to_char(a.d_terima_faktur, 'dd-mm-yyyy') as d_terima_faktur, 
            a.n_customer_toplength,  
            CASE 
            WHEN a.e_partner_type = 'customer' THEN f.f_pkp 
            WHEN a.e_partner_type = 'supplier' THEN e.f_pkp
            ELSE FALSE 
            END AS f_pkp
            , to_char(a.d_jatuh_tempo, 'dd-mm-yyyy') as d_jatuh_tempo, a.i_pajak,  to_char(a.d_pajak, 'dd-mm-yyyy') as d_pajak,
            a.i_bagian, a.id_partner, a.e_partner_type, a.e_partner_name, a.v_kotor, a.v_diskon, a.v_ppn, a.v_dpp, a.v_bersih, 
            a.e_remark, a.i_status, c.e_bagian_name, a.id_harga_kode, g.e_harga 
            from tm_nota_penjualan_bb a
            LEFT JOIN tr_supplier e ON (e.id = a.id_partner)
            LEFT JOIN tr_customer f ON (f.id = a.id_partner)
            inner join tr_bagian c on (a.i_bagian = c.i_bagian and a.id_company = c.id_company)
            left join tr_harga_kode g on (a.id_harga_kode = g.id)
            where a.id = '$id'
      ", FALSE);
        return $this->db->get();
  }

  function get_item_edit($id_nota){
      $idcompany  = $this->session->userdata('id_company');
      return $this->db->query(" 
        WITH CTE AS (
          select x.*, x.azazaz[1] as i_document, x.azazaz[2] as d_document from (
            select b.id_document, b.id_document_reff,b.e_type_reff,  
            b.id_material, c.i_material, c.e_material_name, b.n_quantity, b.v_price, b.n_diskon1, b.v_diskon1, b.n_diskon2, b.v_diskon2, b.n_diskon3, b.v_diskon3, 
            b.v_diskon_tambahan, b.v_diskon_total, b.v_total, b.e_remark, 
            case 
                when b.e_type_reff = 'Bahan Baku' then (select array[i_document::text, to_char(d_document, 'dd-mm-yyyy')] from tm_penjualan_bb where id in (b.id_document_reff)) 
                when b.e_type_reff = 'Aksesoris' then (select array[i_document::text, to_char(d_document, 'dd-mm-yyyy')] from tm_penjualan_ak where id in (b.id_document_reff)) 
                when b.e_type_reff = 'Aksesoris Packing' then (select array[i_document::text, to_char(d_document, 'dd-mm-yyyy')] from tm_penjualan_bp where id in (b.id_document_reff)) 
            end as azazaz
            from tm_nota_penjualan_bb a
            inner join tm_nota_penjualan_bb_item b on (a.id = b.id_document)
            inner join tr_material c on (b.id_material = c.id)
            where b.id_document = '$id_nota' and b.id_company = '$idcompany'
          ) as x
        )

        select x.*, 
        case 
              when e_type_reff = 'Bahan Baku' then (select a.n_quantity_sisa from tm_penjualan_bb_item a where a.id_document = x.id_document_reff and a.id_material = x.id_material) 
              when e_type_reff = 'Aksesoris' then (select a.n_quantity_sisa from tm_penjualan_ak_item a where a.id_document = x.id_document_reff and a.id_material = x.id_material) 
              when e_type_reff = 'Aksesoris Packing' then (select a.n_quantity_sisa from tm_penjualan_bp_item a where a.id_document = x.id_document_reff and a.id_material = x.id_material) 
              else 0
        end as n_quantity_sisa from CTE as x

        ORDER BY x.i_document ASC
      ", FALSE);
    }

  /*----------  CEK DOKUMEN SUDAH ADA  ----------*/
  public function cek_kode($kode,$ibagian) {
      $this->db->select('i_document');
      $this->db->from('tm_nota_penjualan_bb');
      $this->db->where('i_document', $kode);
      $this->db->where('i_bagian', $ibagian);
      $this->db->where('id_company', $this->company);
      $this->db->where_not_in('i_status', '5');
      return $this->db->get();
  }

  /*----------  GET ID DOKUMEN  ----------*/
  public function runningid() {
      $this->db->select('max(id) AS id');
      $this->db->from('tm_nota_penjualan_bb');
      return $this->db->get()->row()->id+1;
  }

  public function insertheader($id,$idocument,$ddocument,$ibagian,$id_partner,$e_partner_type,$f_pkp,$n_customer_toplength,$dreceivefaktur,$ipajak,$dpajak,$djatuhtempo,$eremark,$vkotor,$vdiskon,$vdpp,$vppn,$vbersih, $kodeharga,$e_partner_name){

        $data = array(
                        'id'                    => $id,
                        'id_company'            => $this->company,
                        'i_document'            => $idocument,
                        'd_document'            => $ddocument,
                        'i_bagian'              => $ibagian,
                        'id_partner'            => $id_partner,
                        'e_partner_type'        => $e_partner_type,
                        'e_partner_name'        => $e_partner_name,
                        'd_terima_faktur'       => $dreceivefaktur,
                        'n_customer_toplength'  => $n_customer_toplength,
                        'd_jatuh_tempo'         => $djatuhtempo,
                        'i_pajak'               => $ipajak,
                        'd_pajak'               => $dpajak,
                        'v_kotor'               => $vkotor,
                        'v_diskon'              => $vdiskon,
                        'v_dpp'                 => $vdpp,
                        'v_ppn'                 => $vppn,
                        'v_bersih'              => $vbersih,
                        'v_sisa'                => $vbersih,
                        'e_remark'              => $eremark,
                        'id_harga_kode'         => $kodeharga,
                        'd_entry'               => current_datetime(),
        );
        $this->db->insert('tm_nota_penjualan_bb', $data);
  }

  /*----------  SIMPAN DATA ITEM  ----------*/
  public function insertdetail($id, $id_document, $id_material,$nquantity,$vprice,$ndiskon1,$ndiskon2,$ndiskon3,$vdiskon1,$vdiskon2,$vdiskon3,$vdiskonplus,$vtotaldiskon,$vtotal,$eremark, $e_type_reff){
        $data = array(
                        'id_company'            => $this->company,
                        'id_document'           => $id,
                        'id_document_reff'      => $id_document,
                        'e_type_reff'           => $e_type_reff,
                        'id_material'           => $id_material,
                        'n_quantity'            => $nquantity,
                        'n_quantity_sisa'       => $nquantity,
                        'v_price'               => $vprice,
                        'n_diskon1'             => $ndiskon1,
                        'n_diskon2'             => $ndiskon2,
                        'n_diskon3'             => $ndiskon3,
                        'v_diskon1'             => $vdiskon1,
                        'v_diskon2'             => $vdiskon2,
                        'v_diskon3'             => $vdiskon3,
                        'v_diskon_tambahan'     => $vdiskonplus,
                        'v_diskon_total'        => $vtotaldiskon,
                        'v_total'               => $vtotal,
                        'e_remark'              => $eremark,
        );
        $this->db->insert('tm_nota_penjualan_bb_item', $data);
  }

  /*----------  CEK DOKUMEN SUDAH ADA PAS EDIT  ----------*/
  public function cek_kode_edit($kode,$ibagian,$kodeold,$ibagianold){
        $this->db->select('i_document');
        $this->db->from('tm_nota_penjualan_bb');
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian <>', $ibagianold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->company);
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
  }

  public function updateheader($id,$idocument,$ddocument,$ibagian,$id_partner,$e_partner_type,$f_pkp,$n_customer_toplength,$dreceivefaktur,$ipajak,$dpajak,$djatuhtempo,$eremark,$vkotor,$vdiskon,$vdpp,$vppn,$vbersih, $kodeharga,$e_partner_name){

        $data = array(
                        'i_document'            => $idocument,
                        'd_document'            => $ddocument,
                        'i_bagian'              => $ibagian,
                        'id_partner'            => $id_partner,
                        'e_partner_type'        => $e_partner_type,
                        'e_partner_name'        => $e_partner_name,
                        'd_terima_faktur'       => $dreceivefaktur,
                        'n_customer_toplength'  => $n_customer_toplength,
                        'd_jatuh_tempo'         => $djatuhtempo,
                        'i_pajak'               => $ipajak,
                        'd_pajak'               => $dpajak,
                        'v_kotor'               => $vkotor,
                        'v_diskon'              => $vdiskon,
                        'v_dpp'                 => $vdpp,
                        'v_ppn'                 => $vppn,
                        'v_bersih'              => $vbersih,
                        'v_sisa'                => $vbersih,
                        'e_remark'              => $eremark,
                        'id_harga_kode'         => $kodeharga,
                        'd_update'              => current_datetime(),
        );
        $this->db->where('id',$id);
        $this->db->update('tm_nota_penjualan_bb', $data);
  }

  /*----------  DELETE DETAIL PAS EDIT  ----------*/
  public function delete($id){
      $this->db->where('id_document', $id);
      $this->db->delete('tm_nota_penjualan_bb_item');
  }

  /* ----------------------- GET NAMA STATUS ----------------*/
  public function estatus($istatus) {
      $this->db->select('e_status_name');
      $this->db->from('tr_status_document');
      $this->db->where('i_status',$istatus);
      return $this->db->get()->row()->e_status_name;
  }

  public function changestatus($id,$istatus) {   

      if ($istatus == '6') {  
        $this->db->query("
        update tm_penjualan_bb set f_nota_created = 't' where id IN (select id_document_reff from tm_nota_penjualan_bb_item where id_document = '$id' and e_type_reff = 'Bahan Baku');
        update tm_penjualan_ak set f_nota_created = 't' where id IN (select id_document_reff from tm_nota_penjualan_bb_item where id_document = '$id' and e_type_reff = 'Aksesoris');
        update tm_penjualan_bp set f_nota_created = 't' where id IN (select id_document_reff from tm_nota_penjualan_bb_item where id_document = '$id' and e_type_reff = 'Aksesoris Packing');
        ", false);

        $this->db->query("
        update tm_penjualan_bb_item set n_quantity_sisa = 0 where id_document IN (select id_document_reff from tm_nota_penjualan_bb_item where id_document = '$id' and e_type_reff = 'Bahan Baku');
        update tm_penjualan_ak_item set n_quantity_sisa = 0 where id_document IN (select id_document_reff from tm_nota_penjualan_bb_item where id_document = '$id' and e_type_reff = 'Aksesoris');
        update tm_penjualan_bp_item set n_quantity_sisa = 0 where id_document IN (select id_document_reff from tm_nota_penjualan_bb_item where id_document = '$id' and e_type_reff = 'Aksesoris Packing');


        ", false);
      } 

      if ($istatus=='6') {
          $data = array(
              'i_status'  => $istatus,
              'e_approve' => $this->username,
              'd_approve' => date('Y-m-d'),
          );
      }else{
          $data = array(
              'i_status'  => $istatus,
          );
      }
      $this->db->where('id', $id);
      $this->db->update('tm_nota_penjualan_bb', $data);
  }
}
/* End of file Mmaster.php */