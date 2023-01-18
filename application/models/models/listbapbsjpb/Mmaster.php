<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($cari){
        $cari      = str_replace("'", "", $cari);
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area
            WHERE
                i_area IN (
                SELECT
                    i_area
                FROM
                    public.tm_user_area
                WHERE
                    username = '$username'
                    AND id_company = '$idcompany')
                AND (UPPER(i_area) LIKE '%$cari%'
                OR UPPER(e_area_name) LIKE '%$cari%')
        ", FALSE);
    }

    public function bacaareasj($iarea){
        $query = $this->db->query("
                                select
                                   e_area_name 
                                from
                                   tr_area 
                                where
                                   i_area = '$iarea'

                                ", FALSE);
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $areasj = $kuy->e_area_name; 
        }else{
          $areasj = '';
        }
        return $areasj;
    }

    public function data($dfrom,$dto,$folder,$i_menu){
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            select
                                i_bapb AS id,
                                to_char(d_bapb, 'dd-mm-yyyy') AS d_bapb, 
                                UPPER(e_area_name) AS e_area_name,
                                a.i_area,
                                f_bapb_cancel AS status,
                                '$dfrom' AS dfrom,
                                '$dto' AS dto,
                                '$i_menu' AS i_menu, 
                                '$folder' AS folder
                            from
                               tm_bapbsjpb a,
                               tr_area b 
                            where
                               a.i_area = b.i_area 
                               and a.d_bapb >= to_date('$dfrom', 'dd-mm-yyyy') 
                               and a.d_bapb <= to_date('$dto', 'dd-mm-yyyy') 
                            order by
                               a.i_bapb desc
                            ");

        $datatables->add('action', function ($data) {
            $id             = trim($data['id']);
            $status         = $data['status'];
            $i_area         = $data['i_area'];
            $i_menu         = $data['i_menu'];
            $folder         = $data['folder'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $data           = '';
            $data          .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/$i_area/$dfrom/$dto/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;";
            if(check_role($i_menu, 4) && $status == 'f'){
                    $data  .= "<a href=\"#\" onclick='cancel(\"$id\",\"$i_area\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });

        $datatables->edit('status', function ($data) {
            if ($data['status']=='f') {
                $data = '<span class="label label-success label-rouded">Tidak</span>';
            }else{
                $data = '<span class="label label-danger label-rouded">Ya</span>';
            }
            return $data;
        });

        $datatables->hide('i_area');
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        return $datatables->generate();
    }

    public function cancel($id,$iarea){
        return $this->db->query("
            UPDATE tm_bapbsjpb 
            SET f_bapb_cancel='t' 
            WHERE i_bapb='$id' 
            AND i_area='$iarea'");
    }

    public function jumlahitem($id,$iarea){
        return $this->db->query("
                                SELECT 
                                    *
                                FROM
                                    tm_bapbsjpb_item
                                WHERE
                                    i_bapb='$id'
                                    and i_area='$iarea'
                                ");
    }


    public function baca($id,$iarea){
        $query = $this->db->query("
                                    select
                                       * 
                                    from
                                       tm_bapbsjpb 
                                    where
                                       i_bapb = '$id' 
                                       and i_area = '$iarea'
                                ", false);
        if ($query->num_rows() > 0){
            return $query->row();
        }
    }

    public function bacadetail($id,$iarea){
        $query = $this->db->query("
                                    select
                                       * 
                                    from
                                       tm_bapbsjpb_item 
                                    where
                                       i_bapb = '$id' 
                                       and i_area = '$iarea' 
                                    order by
                                       i_sjpb
                                ", false);
        if ($query->num_rows() > 0){
            return $query->result();
        }
    }

    public function bacasj($cari,$iarea){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $cari);
        return $this->db->query("
                                select
                                   a.i_sjpb
                                from
                                   tm_sjpb a,
                                   tm_sjpb_item b 
                                where
                                   a.i_sjpb = b.i_sjpb 
                                   and a.i_area = b.i_area 
                                   and a.i_bapb isnull 
                                   and a.f_sjpb_cancel = 'f' 
                                   and a.i_area_entry isnull 
                                   and a.d_sjpb >= '2019-05-01' 
                                   and upper(a.i_sjpb) like '%$cari%' 
                                group by
                                   a.i_sjpb,
                                   a.d_sjpb 
                                order by
                                   i_sjpb desc
                                ", FALSE);
    }

    public function bacasjdetail($cari,$iarea){
        $username  = $this->session->userdata('username');
        $idcompany = $this->session->userdata('id_company');
        $cari      = str_replace("'", "", $cari);
        return $this->db->query("
                                select
                                   a.i_sjpb,
                                   a.d_sjpb,
                                   sum(b.n_deliver * b.v_unit_price) as v_sjpb 
                                from
                                   tm_sjpb a,
                                   tm_sjpb_item b 
                                where
                                   a.i_sjpb = b.i_sjpb 
                                   and a.i_area = b.i_area 
                                   and a.i_bapb isnull 
                                   and a.f_sjpb_cancel = 'f' 
                                   and a.i_area_entry isnull 
                                   and a.d_sjpb >= '2019-05-01' 
                                   and upper(a.i_sjpb) like '%$cari%' 
                                group by
                                   a.i_sjpb,
                                   a.d_sjpb 
                                order by
                                   i_sjpb desc
                                ", FALSE);
    }

    function deleteitem($ibapb, $iarea){
        $query = $this->db->query("
                                    select 
                                        * 
                                    from 
                                        tm_bapbsjpb_item 
                                    where 
                                        i_bapb = '$ibapb' 
                                        and i_area='$iarea'"
                                    ,false);
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$isjpb = $row->i_sjpb;
                $this->db->query("
                                update 
                                    tm_sjpb 
                                set 
                                    i_bapb=null 
                                    and d_bapb=null 
                                where 
                                    i_sjpb='$isjpb' 
                                    and i_area='$iarea'
                                ");
			}
		}
		$this->db->query("
                        DELETE 
                        FROM 
                            tm_bapbsjpb_item 
                        WHERE 
                            i_bapb='$ibapb' 
                            and i_area='$iarea' 
                        ");
    }

    function deletedetail($ibapb, $iarea, $isjpb){
        $query = $this->db->query("
                                    select 
                                        * 
                                    from 
                                        tm_bapbsjpb_item 
                                    where 
                                        i_bapb = '$ibapb'
                                        and i_area='$iarea' 
                                        and i_sjpb='$isjpb'"
                                    ,false);
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
                $this->db->query("
                                    update 
                                        tm_sjpb 
                                    set 
                                        i_bapb=null 
                                        and d_bapb=null 
                                    where 
                                        i_sjpb='$isjpb' 
                                        and i_area='$iarea'
                                    ");
			}
		}

		$this->db->query("
                        DELETE 
                        FROM 
                            tm_bapbsjpb_item 
                        WHERE 
                            i_bapb='$ibapb' 
                            and i_area='$iarea' 
                            and i_sjpb='$isjpb'
                        ");

        $query = $this->db->query("
                                select 
                                    sum(v_sjpb) as nilai 
                                from 
                                    tm_bapbsjpb_item 
                                where 
                                    i_bapb = '$ibapb'
                                ", false);
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
				$nilai = $row->nilai;
                $this->db->query("
                                update 
                                    tm_bapbsjpb 
                                set 
                                    v_bapb=$nilai 
                                where 
                                    i_bapb = '$ibapb'
                                ");
			}
		}
	}

    function insertdetail($ibapb,$iarea,$isj,$dbapb,$dsj,$eremark,$vsj){
		$this->db->query("DELETE FROM tm_bapbsjpb_item WHERE i_bapb='$ibapb' and i_area='$iarea' and i_sjpb='$isj'");
		$this->db->set(
			array(
				'i_bapb'	=> $ibapb,
				'i_area'	=> $iarea,
				'i_sjpb'	 	=> $isj,
				'd_bapb' 	=> $dbapb,
				'd_sjpb'	 	=> $dsj,
				'e_remark'=> $eremark,
				'v_sjpb'    => $vsj
			)
		);
		$this->db->insert('tm_bapbsjpb_item');
    }
    
    function updatesj($ibapb,$isj,$iarea,$dbapb){
		$this->db->set(
			array(
				'i_bapb'	=> $ibapb,	
				'd_bapb' => $dbapb
			)
		);
		$this->db->where('i_sjpb',$isj);
		$this->db->where('i_area',$iarea);
		$this->db->update('tm_sjpb');
    }
    
    function updatesjb($ibapb,$iarea,$nilaitotal){
		$this->db->set(
			array(
				'v_bapb'	=> $nilaitotal
			)
		);
		$this->db->where('i_bapb',$ibapb);
		$this->db->where('i_area',$iarea);
		$this->db->update('tm_bapbsjpb');
    }
    
    function updatesjpb($ibapb,$iarea){
		$query   = $this->db->query("SELECT current_timestamp as c");
		$row     = $query->row();
		$d_update  = $row->c;

		$this->db->set(
			array(
				'd_update'	=> $d_update
			)
		);
		$this->db->where('i_bapb',$ibapb);
		$this->db->where('i_area',$iarea);
		$this->db->update('tm_bapbsjpb');
	}
}

/* End of file Mmaster.php */
