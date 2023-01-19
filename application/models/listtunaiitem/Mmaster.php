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
                                distinct on (dtunai, icustomer, itunai) a.i_customer as icustomer, 
                                a.i_area as iarea, 
                                a.i_tunai as itunai, 
                                a.d_tunai as dtunai,
                                c.e_customer_name as ecustomername, 
                                a.v_jumlah as vjumlah, 
                                a.e_remark as eremark, 
                                a.f_tunai_cancel as ftunaicancel,
                                f.i_rtunai as irtunai, 
                                a.v_sisa as vsisa, 
                                a.f_close as fclose,
                                '$dfrom' as dfrom,
                                '$dto' as dto,
                                '$folder' as folder,
                                '$iperiode' as iperiode,
                                '$area' as area
                            FROM
                                tm_tunai a
                                left join tr_customer c on(a.i_customer=c.i_customer)
                                left join tr_area d on(a.i_area=d.i_area)
                                left join tm_rtunai_item f on(a.i_tunai=f.i_tunai and a.i_area=f.i_area_tunai)
                            WHERE 
                                a.f_tunai_cancel='f'
                                and a.i_area='$iarea'
                                and(a.d_tunai >= '$dfrom'
                                and a.d_tunai <= '$dto')
                            ORDER BY 
                                dtunai, 
                                icustomer, 
                                itunai "
                        ,false);
        
        $datatables->edit('vjumlah', function($data){
            return number_format($data['vjumlah']);
        });

        $datatables->edit('dtunai', function($data){
            return date("d-m-Y", strtotime($data['dtunai']));
        });

        $datatables->edit('ecustomername', function($data){
            return '('.($data['icustomer']).')'.($data['ecustomername']);
        });

        $datatables->add('action', function ($data) {
            $itunai         = $data['itunai'];
            $folder         = $data['folder'];
            $iarea          = $data['iarea'];
            $dfrom          = $data['dfrom'];
            $dto            = $data['dto'];
            $fclose         = $data['fclose'];
            $ftunaicancel   = $data['ftunaicancel'];
            $vjumlah        = $data['vjumlah'];
            $vsisa          = $data['vsisa'];
            $irtunai        = $data['irtunai'];
            $iperiode       = $data['iperiode'];
            $dtunai         = $data['dtunai'];
            $area           = $data['area'];
            $bisaedit       = false;
            $data           = '';
            $tmp=explode('-',$dtunai);
            $tgl=$tmp[2];
            $bln=$tmp[1];
            $thn=$tmp[0];
            $dtunai=$tgl.'-'.$bln.'-'.$thn;
            $dtunai=$thn.$bln;
            if($iperiode <= $dtunai){
                $bisaedit=true;
            }
            if($fclose == 'f'){
                $data      .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$itunai/$iarea/$dfrom/$dto\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                if($ftunaicancel != 't' && $vjumlah == $vsisa && $area == '00'
                && ($irtunai == '' || $irtunai == null) && $bisaedit){
                    $data .= "<a href=\"#\" onclick='hapus(\"$itunai\",\"$iarea\",\"$dfrom\",\"$dto\"); return false;'><i class='fa fa-trash'></i></a>&nbsp;&nbsp;";
                }
            }
            return $data;
        });
        $datatables->hide('folder');
        $datatables->hide('dfrom');
        $datatables->hide('dto');
        $datatables->hide('ftunaicancel');
        $datatables->hide('irtunai');
        $datatables->hide('vsisa');
        $datatables->hide('fclose');
        $datatables->hide('icustomer');
        $datatables->hide('iperiode');
        $datatables->hide('area');
        return $datatables->generate();
    }

    function baca($iarea,$itunai){
        $this->db->select(" a.d_tunai, a.i_tunai, d.e_area_name, a.v_jumlah, a.v_sisa, a.i_area, a.i_salesman, e.e_salesman_name,
                            a.i_customer, a.i_customer_groupar, c.e_customer_name, a.e_remark, a.i_rtunai, a.f_lebihbayar
                            from tm_tunai a
                            left join tr_customer_salesman e on(a.i_customer=e.i_customer and a.i_area=e.i_area and a.i_salesman=e.i_salesman
                            and e.e_periode=to_char(a.d_tunai,'yyyymm'))
                            left join tr_customer c on(a.i_customer=c.i_customer)
                            left join tr_area d on(a.i_area=d.i_area) 
                            where a.i_tunai='$itunai' and a.i_area='$iarea'",false);
        $query = $this->db->get();
        if ($query->num_rows() > 0){
          return $query->row();
        }
    }

    function bacadetail($iarea, $itunai){
		$this->db->select("	a.* , b.i_area, b.i_customer, b.i_salesman,b.e_remark, c.e_area_name, d.d_nota
                            from tm_tunai b 
                            left join tm_tunai_item a using (i_tunai) 
                            left join tr_area c on b.i_area=c.i_area 
                            left join tm_nota d on a.i_nota=d.i_nota
                            where b.i_tunai='$itunai' 
                            and b.i_area='$iarea' and d.i_area='$iarea' order by a.n_item_no",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
            $jml = 0;
            foreach ($query->result() as $row) {
              $jml = $jml + $row->v_jumlah;
            }
            $data = array(
              'v_jumlah' => $jml
            );
      
            $this->db->where('i_tunai', $itunai);
            $this->db->where('i_area', $iarea);
            $this->db->update('tm_tunai', $data);
			return $query->result();
		}
    }

    function bacacustomer($iarea,$iperiode){
        $this->db->select(" distinct on (a.i_customer, a.e_customer_name, c.e_salesman_name) a.*, 
                            b.i_customer_groupar, 
                            c.e_salesman_name, 
                            c.i_salesman,
                            d.e_customer_setor 
                            from tr_customer a 
                            left join tr_customer_groupar b on(a.i_customer=b.i_customer) 
                            left join tr_customer_salesman c on(a.i_customer=c.i_customer and a.i_area=c.i_area and c.e_periode='$iperiode') 
                            left join tr_customer_owner d on(a.i_customer=d.i_customer)
                            
                            where a.i_area='$iarea' 
                            order by a.i_customer ",FALSE);

        $query = $this->db->get();
        if ($query->num_rows() > 0){
          return $query->result();
        } 
    }

    function getnota($cari,$dtunaix,$icustomer,$iarea){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
                                SELECT 
                                    a.i_nota
                                FROM 
                                    tm_nota a 
                                    left join tr_area b using(i_area) 
                                    left join tr_customer c using (i_customer) 
                                WHERE 
                                    a.d_nota<='$dtunaix' 
                                    AND a.v_sisa is not null 
                                    AND a.i_customer='$icustomer' 
                                    AND a.i_area='$iarea' 
                                    AND (UPPER(a.i_nota) LIKE '%$cari%'
                                    OR a.i_nota LIKE '%$cari%')
                                    AND a.f_nota_cancel='f'",
                                false);
    }

    function getdetailnota($inota,$iarea){
        return $this->db->query("
                                SELECT 
                                    a.d_nota, 
                                    a.v_sisa, 
                                    a.i_area, 
                                    b.e_area_name, 
                                    a.i_customer, 
                                    c.e_customer_name, 
                                    a.e_remark 
                                FROM 
                                    tm_nota a 
                                    left join tr_area b using(i_area) 
                                    left join tr_customer c using (i_customer) 
                                WHERE 
                                    a.v_sisa is not null 
                                    AND a.i_area='$iarea' 
                                    AND a.i_nota='$inota'
                                    AND a.f_nota_cancel='f'",
                                false);
    }

    function insertdetail($itunai,$iarea,$inota,$vjumlah,$i){
        $this->db->select("	v_jumlah from tm_tunai_item where i_tunai='$itunai' and i_area='$iarea' and i_nota='$inota'",false);
		$query = $this->db->get();
		if ($query->num_rows() > 0){
		    $this->db->set(
      		    array(
			    	'i_tunai'           => $itunai,
                    'i_area'            => $iarea,
                    'i_nota'            => $inota,
                    'v_jumlah'          => $vjumlah,
                    'n_item_no'         => $i
      		    )
      	    );
      	    $this->db->where('i_tunai',$itunai);
      	    $this->db->where('i_area',$iarea);
      	    $this->db->where('i_nota',$inota);
      	    $this->db->update('tm_tunai_item');
        }else{
        $this->db->set(
      		array(
				    'i_tunai'          => $itunai,
                    'i_area'           => $iarea,
                    'i_nota'           => $inota,
                    'v_jumlah'         => $vjumlah,
                    'n_item_no'        => $i
      		)
      	);
      	$this->db->insert('tm_tunai_item');
      }
    }

    function update($itunai,$dtunai,$iarea,$eremark,$vjumlah,$lebihbayar){
		$query 	= $this->db->query("SELECT current_timestamp as c");
		$row   	= $query->row();
		$dentry	= $row->c;
        $this->db->set(
    		array(
                'i_tunai'           => $itunai,
				'i_area'			=> $iarea,
				'd_tunai'	        => $dtunai,
				'd_update'		    => $dentry,
				'e_remark'			=> $eremark,
				'v_jumlah'			=> $vjumlah,
				'v_sisa'            => $vjumlah,
                'f_lebihbayar'      => $lebihbayar
    		)
    	);
    	$this->db->where('i_tunai',$itunai);
    	$this->db->where('i_area',$iarea);
    	$this->db->update('tm_tunai');
    }

    public function cancel($itunai, $iarea){
        $this->db->set(
            array(
                'f_tunai_cancel'  => 't',
                'd_update' => 'now()'
            )
        );
        $this->db->where('i_tunai',$itunai);
        $this->db->where('i_area',$iarea);
        return $this->db->update('tm_tunai');
    }

    function deletedetail($itunai,$iarea){
    	$this->db->query("delete from tm_tunai_item 
    	                  where i_tunai='$itunai' and i_area='$iarea'");
    }
}

/* End of file Mmaster.php */
