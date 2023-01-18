<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model
{
    public $idcompany;
    public $i_menu = '2090101';

    function __construct()
    {
        parent::__construct();
        $this->idcompany = $this->session->id_company;
    }

    public function bagianpembuat()
    {
        /* $this->db->select('a.id, a.i_bagian, e_bagian_name');
        $this->db->from('tr_bagian a');
        $this->db->join('tr_departement_cover b', 'b.i_bagian = a.i_bagian AND a.id_company = b.id_company', 'inner');
        $this->db->where('i_departement', $this->session->userdata('i_departement'));
        $this->db->where('i_level', $this->session->userdata('i_level'));
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->where('a.id_company', $this->session->userdata('id_company'));
        $this->db->order_by('e_bagian_name');
        return $this->db->get(); */
        return $this->db->query("SELECT DISTINCT a.id, a.i_bagian, e_bagian_name, d.i_departement FROM tr_bagian a 
			INNER JOIN tr_departement_cover b ON b.i_bagian = a.i_bagian AND a.id_company = b.id_company 
			LEFT JOIN tr_type c on (a.i_type = c.i_type)
			LEFT JOIN public.tm_menu d on (d.i_menu = '$this->i_menu' and c.i_departement  = d.i_departement)
			WHERE a.f_status = 't' AND b.i_departement = '$this->i_departement' AND username = '$this->username' AND a.id_company = '$this->id_company' 
			ORDER BY 4, 3 ASC NULLS LAST
        ", false);
    }


    public function referensi($cari){
      $cari = str_replace("'", "", $cari);
      return $this->db->query("
          SELECT DISTINCT
		      a.id,
		      a.i_document,
		      to_char(a.d_document, 'dd-mm-yyyy') AS d_document,
		      to_char(to_date(a.i_periode, 'yyyymm'), 'FMMonth yyyy') AS i_periode
		  from tm_fccutting a
		  left join tm_fccutting_item b on (a.id = b.id_forecast)
		  left join tm_schedule_item f on (b.id = f.id_fccutting_item)
		  left join tm_schedule g on (f.id_schedule = g.id)
		  WHERE
			  a.i_status = '6'
		      AND a.id_company = '$this->idcompany'
		      AND a.i_document ILIKE '%$cari%'
		      and (f.d_schedule is null or (g.i_status is null or g.i_status not in ('6')))
		      /*and a.id not in (select id_referensi from tm_schedule where i_status in ('1','2','3','6') and id_referensi is not null)*/
		  ORDER BY
		      i_document, d_document
		      ", FALSE);
    }

    public function getdataitem($idreff)
    {
        return $this->db->query("
            SELECT DISTINCT 
		    	a.id,
		    	a.id_product_wip,
		    	a.id_material,
		    	c.i_product_wip,
		        c.e_product_wipname,
		        a.n_quantity_wip as n_quantity_wip,
		        a.n_sisa_wip as n_quantity_wip_sisa,
		        c.i_color, 
		        e.id as id_color,
		    	e.e_color_name,
		    	d.i_material,
		    	d.e_material_name,
		    	round(a.n_quantity_material,3) as n_quantity,
			    round(a.n_sisa_material,3) as n_quantity_sisa,
		        a.e_remark
		    FROM
		    	tm_fccutting_item a
		    	INNER JOIN tr_product_wip c ON (a.id_product_wip = c.id AND a.id_company = c.id_company)
		    	INNER JOIN tr_material d ON (a.id_material = d.id AND a.id_company = d.id_company)
		    	INNER JOIN tr_color e ON (c.i_color = e.i_color AND c.id_company = e.id_company)
		    	left join tm_schedule_item f on (a.id = f.id_fccutting_item)
		    	left join tm_schedule g on (f.id_schedule = g.id)
		    WHERE
		    	a.id_forecast = '$idreff' AND a.id_company = '$this->idcompany'
		        AND a.n_sisa_wip <> 0 AND a.n_sisa_material <> 0 
		       and (f.d_schedule is null or (g.i_status is null or g.i_status not in ('6')))
        ", FALSE);
    }

    public function getdataheader($idreff){
	  return $this->db->query("
	                          SELECT
	                              to_char(d_document, 'dd-mm-yyyy') as d_document
	                          FROM 
	                              tm_fccutting
	                          WHERE
	                              id = '$idreff'
	                          ", FALSE);
	}

    public function data($i_menu, $folder, $dfrom, $dto)
    {
        if ($dfrom != '' && $dto != '') {
            $dfrom = date('Y-m-d', strtotime($dfrom));
            $dto   = date('Y-m-d', strtotime($dto));
            $where = "AND a.d_document BETWEEN '$dfrom' AND '$dto'";
        } else {
            $where = "";
        }
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                        SELECT 
                        	0 as no,
                        	a.id,
                        	a.i_document,
                        	to_char(a.d_document,'dd-mm-yyyy') as d_document,
                        	a.i_bagian,
                        	b.e_bagian_name,
                        	a.id_referensi,
                        	d.i_document as i_fc,
                        	a.e_remark, 
                          a.i_status,
                          e.e_status_name,
                          e.label_color,
                          f.i_level,
                          l.e_level_name,
                          '$i_menu' as i_menu, 
                          '$folder' as folder,
                          '$dfrom' as dfrom,
                          '$dto' as dto
                        FROM 
                        	tm_schedule a
                        	INNER JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
                        	LEFT JOIN tm_fccutting d ON (a.id_referensi = d.id AND a.id_company = d.id_company)
                        	INNER JOIN tr_status_document e ON (a.i_status = e.i_status)
                            LEFT JOIN tr_menu_approve f ON (a.i_approve_urutan = f.n_urut AND f.i_menu = '$i_menu')
                            LEFT JOIN public.tr_level l ON (f.i_level = l.i_level)
                        WHERE 
                        	a.id_company = '$this->idcompany'
                          AND a.i_status <> '5'
                          $where
                        ORDER BY 
                            a.i_document,
                            a.d_document desc
                        ", FALSE);
        $datatables->edit('e_status_name', function ($data) {
            $i_status = $data['i_status'];
            if ($i_status == '2') {
                $data['e_status_name'] = $data['e_status_name']. ' '. $data['e_level_name']  ;
            }
            return '<span class="label label-'.$data['label_color'].' label-rouded">'.$data['e_status_name'].'</span>';
        });

        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $ibagian        = trim($data['i_bagian']);
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $i_status       = trim($data['i_status']);           
            $i_level = $data['i_level'];
            $data           = '';

            if (check_role($i_menu, 2)) {
                $data .= "<a href=\"#\" title='Detail' onclick='show(\"$folder/cform/view/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-eye text-success'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 3) && $i_status != '5' && $i_status != '6' && $i_status != '9') {
                $data .= "<a href=\"#\" title='Edit' onclick='show(\"$folder/cform/edit/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>&nbsp;&nbsp;&nbsp;";
            }
            if (check_role($i_menu, 7) && $i_status == '2') {
                if (($i_level == $this->i_level || $this->i_level == 1) ) {
                    $data .= "<a href=\"#\" onclick='show(\"$folder/cform/approval/$id/$ibagian/$dfrom/$dto/\",\"#main\"); return false;'><i class='ti-check-box text-primary'></i></a>&nbsp;&nbsp;&nbsp;";
                }
            }
            if (check_role($i_menu, 4) && ($i_status != '4' && $i_status != '6' && $i_status != '9' && $i_status != '2')) {
                $data .= "<a href=\"#\" title='Cancel' onclick='changestatus(\"$folder\",\"$id\",\"9\"); return false;'><i class='ti-close'></i></a>";
            }


            return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id');
        $datatables->hide('i_bagian');
        $datatables->hide('e_bagian_name');
        $datatables->hide('id_referensi');
        $datatables->hide('i_status');
        $datatables->hide('label_color');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
		$datatables->hide('i_level');
		$datatables->hide('e_level_name');
        return $datatables->generate();
    }

    public function cek_kode($kode, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_schedule');
        $this->db->where('i_document', $kode);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata("id_company"));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function cek_kodeedit($kode, $kodeold, $ibagian)
    {
        $this->db->select('i_document');
        $this->db->from('tm_schedule');
        $this->db->where('i_document', $kode);
        $this->db->where('i_document <>', $kodeold);
        $this->db->where('i_bagian', $ibagian);
        $this->db->where('id_company', $this->session->userdata('id_company'));
        $this->db->where_not_in('i_status', '5');
        return $this->db->get();
    }

    public function runningid()
    {
        $this->db->select('max(id) AS id');
        $this->db->from('tm_schedule');
        return $this->db->get()->row()->id + 1;
    }

    public function runningnumber($thbl, $tahun, $ibagian)
    {
        $cek = $this->db->query("
            SELECT 
                substring(i_document, 1, 2) AS kode 
            FROM tm_schedule 
            WHERE i_status <> '5'
            AND i_bagian = '$ibagian'
            AND id_company = '" . $this->session->userdata("id_company") . "'
            ORDER BY id DESC");
        if ($cek->num_rows() > 0) {
            $kode = $cek->row()->kode;
        } else {
            $kode = 'SC';
        }
        $query  = $this->db->query("
            SELECT
                max(substring(i_document, 9, 6)) AS max
            FROM
              tm_schedule
            WHERE to_char (d_document, 'yyyy') >= '$tahun'
            AND i_status <> '5'
            AND i_bagian = '$ibagian'
            AND substring(i_document, 1, 2) = '$kode'
            AND substring(i_document, 4, 2) = substring('$thbl',1,2)
            AND id_company = '" . $this->session->userdata("id_company") . "'
        ", false);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $no = $row->max;
            }
            $number = $no + 1;
            settype($number, "string");
            $n = strlen($number);
            while ($n < 6) {
                $number = "0" . $number;
                $n = strlen($number);
            }
            $number = $kode . "-" . $thbl . "-" . $number;
            return $number;
        } else {
            $number = "000001";
            $nomer  = $kode . "-" . $thbl . "-" . $number;
            return $nomer;
        }
    }

    function insertheader($id, $ibonm, $dbonm, $ikodemaster, $ireff, $eremark)
    {

        $data = array(
            'id'                 => $id,
            'id_company'         => $this->idcompany,
            'i_document'         => $ibonm,
            'd_document'         => $dbonm,
            'i_bagian'           => $ikodemaster,
            'id_referensi'       => $ireff,
            'e_remark'           => $eremark,
            'i_status'           => '1',
            'd_entry'            => current_datetime(),
        );
        $this->db->insert('tm_schedule', $data);
    }

    function insertdetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc, $id_fccutting_item, $d_schedule)
    {
    	
        $data = array(
            'id_company'                => $this->idcompany,
            'id_schedule'               => $id,
            'd_schedule'                => date('Y-m-d', strtotime($d_schedule)),
            'id_fccutting_item'         => $id_fccutting_item,
            'id_product_wip'            => $idproductwip,
            'n_quantity_wip'            => $nquantitywipmasuk,
            'n_sisa_wip'       			=> $nquantitywipmasuk,
            'id_material'               => $idmaterial,
            'n_quantity_material'       => $nquantitybahanmasuk,
            'n_sisa_material'           => $nquantitybahanmasuk,
            'n_sisa_material_keluar'    => $nquantitybahanmasuk,
            'e_remark'                  => $edesc,
        );
        $this->db->insert('tm_schedule_item', $data);
    }

    public function changestatus($id, $istatus)
    {
        if ($istatus=='3' || $istatus== '6') {
            $awal = $this->db->query("SELECT b.i_menu, 
                    a.i_approve_urutan, 
                    coalesce(max(b.n_urut),1) as n_urut 
				FROM tm_schedule a
				JOIN tr_menu_approve b on (b.i_menu = '$this->i_menu')
				WHERE a.id = '$id'
				GROUP BY 1,2", FALSE)->row();
            if ($istatus == '3') {
            	if ($awal->i_approve_urutan - 1 == 0 ) {
            		$data = array(
	                    'i_status'  => $istatus,
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan - 1,
                    );
            	}
            	$this->db->query("DELETE FROM tm_menu_approve WHERE i_menu = '$this->i_menu' AND i_level = '$this->i_level' AND i_document = '$id' ", FALSE);
            } else if ($istatus == '6'){
            	if ($awal->i_approve_urutan + 1 > $awal->n_urut ) {
            		$data = array(
	                    'i_status'  => $istatus,
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                        'i_approve' => $this->username,
                        'd_approve' => date('Y-m-d'),
                    );
            	} else {
            		$data = array(
	                    'i_approve_urutan'  => $awal->i_approve_urutan + 1,
                    );
            	}
                $now = date('Y-m-d');
            	$this->db->query("INSERT INTO tm_menu_approve (i_menu,i_level,i_document,e_approve,d_approve,e_database) VALUES
					('$this->i_menu','$this->i_level','$id','$this->username','$now','tm_schedule');", FALSE);
                }
        }else{
            $data = array(
                'i_status'  => $istatus,
            );
        }
        $this->db->where('id', $id);
        $this->db->update('tm_schedule', $data);
    }

    public function estatus($istatus)
    {
        $this->db->select('e_status_name');
        $this->db->from('tr_status_document');
        $this->db->where('i_status', $istatus);
        return $this->db->get()->row()->e_status_name;
    }


    public function cek_data($id, $ibagian)
    {
        return $this->db->query("
                               SELECT 
								a.id,
								a.i_document, 
								to_char(a.d_document,'dd-mm-yyyy') as d_document,
								a.id_referensi as id_reff,
								d.i_document as i_reff,
								to_char(d.d_document, 'dd-mm-yyyy') as d_reff,
								a.i_bagian,
								b.e_bagian_name,
								a.e_remark,
								a.i_status
								from tm_schedule a
								LEFT JOIN tm_fccutting d ON (a.id_referensi = d.id AND a.id_company = d.id_company)
								INNER JOIN tr_bagian b ON (a.i_bagian = b.i_bagian AND a.id_company = b.id_company)
								WHERE 
								a.id  = '$id'
								AND a.i_bagian = '$ibagian'
								AND a.id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function cek_datadetail($id, $ibagian)
    {
        return $this->db->query("
                                 SELECT
								a.id,
								a.id_fccutting_item ,
								a.id_product_wip,
								a.id_material,
								a.d_schedule,
								c.i_product_wip,
								c.e_product_wipname,
								a.n_quantity_wip as n_quantity_wip,
								a.n_sisa_wip as n_quantity_wip_sisa,
								c.i_color, 
								e.id as id_color,
								e.e_color_name,
								d.i_material,
								d.e_material_name,
								round(a.n_quantity_material,3) as n_quantity,
								round(a.n_sisa_material,3) as n_quantity_sisa,
								a.e_remark
							FROM
								tm_schedule_item a 
								INNER JOIN tr_product_wip c ON (a.id_product_wip = c.id AND a.id_company = c.id_company)
								INNER JOIN tr_material d ON (a.id_material = d.id AND a.id_company = d.id_company)
								INNER JOIN tr_color e ON (c.i_color = e.i_color AND c.id_company = e.id_company)
								
							WHERE 
								a.id_schedule = '$id'
							  	AND a.id_company = '$this->idcompany'
                                ", FALSE);
    }

    public function updateheader($id, $ikodemaster, $ibonm, $dbonm, $eremark, $ireff)
    {
        $data = array(
            'i_document' => $ibonm,
            'i_bagian'   => $ikodemaster,
            'd_document' => $dbonm,
            'id_referensi' => $ireff,
            'e_remark'   => $eremark,
            'd_update'   => current_datetime(),
            'i_status'           => '1',
        );

        $this->db->where('id', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->where('i_bagian', $ikodemaster);
        $this->db->update('tm_schedule', $data);
    }

    public function deletedetail($id)
    {
        $this->db->where('id_schedule', $id);
        $this->db->where('id_company', $this->idcompany);
        $this->db->delete('tm_schedule_item');
    }

    // public function updatedetail($id, $ireff, $idproductwip, $idmaterial, $nquantitywipmasuk, $nquantitybahanmasuk, $edesc)
    // {
    //     $data = array(
    //         'n_quantity_wip'      => $nquantitywipmasuk,
    //         'n_quantity_wip_sisa' => $nquantitywipmasuk,
    //         'n_quantity'          => $nquantitybahanmasuk,
    //         'n_quantity_sisa'     => $nquantitybahanmasuk,
    //         'e_remark'            => $edesc,
    //     );

    //     $this->db->where('id_document', $id);
    //     $this->db->where('id_product_wip', $idproductwip);
    //     $this->db->where('id_material', $idmaterial);
    //     $this->db->where('id_company', $this->idcompany);
    //     $this->db->update('tm_schedule_item', $data);
    // }
}
/* End of file Mmaster.php */