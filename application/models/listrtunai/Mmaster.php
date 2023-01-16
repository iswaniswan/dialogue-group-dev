<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

    public function bacaarea($idcompany){
        return $this->db->query("
            SELECT
                *
            FROM
                tr_area where f_area_real='t' order by i_area
        ", FALSE)->result();
    }

    public function area($idcompany,$username){
        $query = $this->db->select("i_area from public.tm_user_area where id_company='$idcompany' 
                                     and username='$username'",FALSE);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $ar =  $query->row();
            $area = $ar->i_area;
        }else{
            $area='';
        }
        return $area;
    }

    public function cekperiode(){
        $this->db->select('i_periode');
        $this->db->from('tm_periode');
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $kuy   = $query->row();
            $iperiode = $kuy->i_periode; 
        }else{
            $iperiode = '';
        }
        return $iperiode;
    } 

//    public function data($dfrom, $dto, $isupplier, $folder, $iperiode, $title){
    public function data($dfrom, $dto, $iarea, $iperiode, $area, $folder){
        $this->load->library('fungsi');
        $tmp 	= explode("-", $dfrom);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dfrom	= $yir."-".$mon."-".$det;
        $tmp 	= explode("-", $dto);
        $det	= $tmp[0];
        $mon	= $tmp[1];
        $yir 	= $tmp[2];
        $dto	= $yir."-".$mon."-".$det;
        $datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT 
                                a.i_area as iarea, 
                                a.i_rtunai as irtunai, 
                                a.d_rtunai as drtunai,
                                a.v_jumlah as vjumlah, 
                                a.e_remark as eremark,
                                a.f_rtunai_cancel as frtunaicancel,
                                a.i_cek as cek,
                                '$folder' as folder,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$area' as area,
                                '$iperiode' as iperiode
                            FROM
                                tm_rtunai a
                                left join tr_area d on(a.i_area=d.i_area)
                            WHERE 
                                a.f_rtunai_cancel='f'
                                and a.i_area='$iarea'
                                and(a.d_rtunai >= '$dfrom'
                                and a.d_rtunai <= '$dto')
                            ORDER BY 
                                irtunai"
                        ,false);
        
        $datatables->edit('vjumlah', function($data){
            return number_format($data['vjumlah']);
        });

        $datatables->edit('drtunai', function($data){
            return date("d-m-Y", strtotime($data['drtunai']));
        });

        $datatables->add('action', function ($data) {
            $irtunai        = $data['irtunai'];
            $folder         = $data['folder'];
            $iarea          = $data['iarea'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $frtunaicancel  = $data['frtunaicancel'];
            $vjumlah        = $data['vjumlah'];
            $cek            = $data['cek'];
            $iperiode       = $data['iperiode'];
            $drtunai        = $data['drtunai'];
            $area           = $data['area'];
            $bisaedit       = false;
            $data           = '';
            $tmp=explode('-',$drtunai);
            $tgl=$tmp[2];
            $bln=$tmp[1];
            $thn=$tmp[0];
            $drtunai=$tgl.'-'.$bln.'-'.$thn;
            $thbl=$thn.$bln;
         
            $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$irtunai/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            if($frtunaicancel != 't' && $area == '00'
            && ($cek == '' || $cek == null) && $iperiode <= $thbl){
                $data .= "<a href=\"#\" onclick='hapus(\"$irtunai\",\"$iarea\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('frtunaicancel');
        $datatables->hide('cek');
        $datatables->hide('iperiode');
        $datatables->hide('area');
        return $datatables->generate();
    }

    function baca($iarea, $irtunai){
		$this->db->select("	a.d_rtunai, a.i_rtunai, d.e_area_name, a.v_jumlah, a.i_area, a.e_remark, a.i_cek, a.i_bank, b.e_bank_name
		                    from tm_rtunai a
		                    left join tr_bank b on(a.i_bank=b.i_bank)
					        left join tr_area d on(a.i_area=d.i_area)
					        where a.i_rtunai='$irtunai' and a.i_area='$iarea'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->row();
		}
    }

    function bacadetail($iarea, $irtunai){
		$this->db->select("	a.*, b.e_area_name, to_char(c.d_tunai,'dd-mm-yyyy') as d_tunai, c.i_customer, c.e_remark, d.e_customer_name
		                    from tm_rtunai_item a
					        left join tr_area b on(a.i_area_tunai=b.i_area),
					        tm_tunai c, tr_customer d
					        where a.i_rtunai='$irtunai' and a.i_area='$iarea' and c.i_customer=d.i_customer
					        and a.i_tunai=c.i_tunai and a.i_area_tunai=c.i_area
					        order by a.n_item_no",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			return $query->result();
		}
    }

    function bacabank(){
        $this->db->select(" * from tr_bank order by i_bank ",FALSE);
       $query = $this->db->get();
       if ($query->num_rows() > 0){
           return $query->result();
       }	
   }

   function gettunai($cari,$drtunaix,$iarea){
      $user = $this->session->userdata('username');
      $cari = str_replace("'", "", $cari);
      return $this->db->query(" 
                            SELECT 
                                a.*, 
                                b.i_customer, 
                                b.e_customer_name, 
                                c.e_area_name 
                            FROM 
                                tm_tunai a, 
                                tr_customer b, 
                                tr_area c
                           WHERE 
                                (a.i_rtunai isnull or a.v_sisa>0) 
                                and a.d_tunai<='$drtunaix'
                                and a.i_area = '$iarea'
                                and a.f_tunai_cancel='f' 
                                and a.i_customer=b.i_customer 
                                and a.i_area=c.i_area 
                                and a.v_sisa > 0
                                and (upper(a.i_tunai) like '%$cari%' 
                                or upper(b.e_customer_name) like '%$cari%' 
                                or upper(a.i_customer) like '%$cari%')
                           ORDER BY 
                            a.i_tunai ", 
                        false);
   }

    function getdetailtunai($itunai,$iarea){
        return $this->db->query("
                                SELECT 
                                    a.i_area,
                                    a.d_tunai,
                                    a.i_customer,
                                    a.v_jumlah,
                                    a.e_remark, 
                                    b.e_customer_name,
                                    c.e_area_name
                                FROM 
                                    tm_tunai a 
                                    left join tr_customer b on (a.i_customer=b.i_customer) 
                                    left join tr_area c on (a.i_area=c.i_area)
                                WHERE 
                                    a.v_sisa > 0 
                                    AND a.i_area='$iarea' 
                                    AND a.i_tunai='$itunai'
                                    AND a.f_tunai_cancel='f'",
                                false);
    }

    function updatedetail($irtunai,$iarea,$xiarea,$itunai,$iareatunai,$vjumlah,$i){
        $this->db->select("	v_jumlah from tm_rtunai_item 
                			where i_rtunai='$irtunai' and i_area='$xiarea' and i_tunai='$itunai' and i_area_tunai='$iareatunai'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		    $this->db->set(array(
				'i_area'		=> $iarea,
				'i_rtunai'		=> $irtunai,
				'i_tunai' 		=> $itunai,
				'i_area_tunai'  => $iareatunai,
				'v_jumlah'	    => $vjumlah,
				'n_item_no'     => $i
      		));
      	    $this->db->where('i_rtunai',$irtunai);
      	    $this->db->where('i_area',$xiarea);
      	    $this->db->where('i_tunai',$itunai);
      	    $this->db->where('i_area_tunai',$iareatunai);
      	    $this->db->update('tm_rtunai_item');
        }else{
            $this->db->set(array(
				'i_area'			=> $iarea,
				'i_rtunai'		    => $irtunai,
				'i_tunai' 		    => $itunai,
				'i_area_tunai'      => $iareatunai,
				'v_jumlah'			=> $vjumlah,
				'n_item_no'         => $i
      		));
      	    $this->db->insert('tm_rtunai_item');
        }
    }

    function updatetunai($irtunai,$iarea,$itunai,$iareatunai,$vjumlah){
		$this->db->query("update tm_tunai set i_area_rtunai='$iarea', i_rtunai='$irtunai', v_sisa=v_sisa-$vjumlah 
                        where i_tunai='$itunai' and i_area='$iareatunai'");
    }

    public function cancel($irtunai,$iarea) {
		$this->db->query(" update tm_rtunai set f_rtunai_cancel='t', d_update=now() WHERE i_rtunai='$irtunai' and i_area='$iarea' ");
        $this->db->select("* from tm_rtunai_item WHERE i_rtunai='$irtunai' and i_area='$iarea' ");
		$query = $this->db->get();
		if ($query->num_rows() > 0){
			foreach($query->result() as $row){
			    $itunai=$row->i_tunai;
			    $area=$row->i_area_tunai;
			    $vjml=$row->v_jumlah;
      		    $this->db->query(" update tm_tunai set i_rtunai=null, i_area_rtunai=null, d_update=now(), v_sisa=v_sisa+$vjml 
      		                        WHERE i_tunai='$itunai' and i_area='$area' ");
			}
		}
    }

    function update($irtunai,$drtunai,$iarea,$xiarea,$eremark,$vjumlah,$ibank){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
    	$this->db->set(
    		array(
				'i_area'	=> $iarea,
				'i_rtunai'	=> $irtunai,
				'd_rtunai'	=> $drtunai,
				'd_update'  => $dentry,
				'e_remark'  => $eremark,
				'v_jumlah'  => $vjumlah,
				'i_bank'    => $ibank
    		)
    	);
    	$this->db->where('i_rtunai',$irtunai);
    	$this->db->where('i_area',$xiarea);
    	$this->db->update('tm_rtunai');
    }

    function deletedetail($irtunai,$iarea){
    	$this->db->query("delete from tm_rtunai_item 
    	                  where i_rtunai='$irtunai' and i_area='$iarea'");
    }
}

/* End of file Mmaster.php */
