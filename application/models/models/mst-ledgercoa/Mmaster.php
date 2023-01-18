<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

  function data($i_menu, $folder){
    $datatables = new Datatables(new CodeigniterAdapter);
    $idcompany  = $this->session->userdata('id_company');

    $datatables->query("
                        SELECT 
                            0 as no, 
                            a.i_coa_ledger, 
                            a.e_coa_ledger_name, 
                            b.e_coa_type_name,
                            a.id_company,
                            case when a.f_status = TRUE then 'Aktif' else 'Tidak Aktif' end as status , 
                            $i_menu as i_menu , 
                            '$folder' as folder 
                        FROM 
                            tr_coa_ledger a
                            LEFT JOIN 
                                tr_coa_type b ON (a.id_coa_type = b.id)
                        /*WHERE a.id_company = '$idcompany'*/
                        ORDER BY 
                            a.i_coa_ledger
                        ", FALSE);

      $datatables->edit(
        'status', 
              function ($data) {
                  $id         = trim($data['i_coa_ledger']);
                  $folder     = $data['folder'];
                  $id_menu    = $data['i_menu'];
                  $status     = $data['status'];
                  if ($status=='Aktif') {
                      $warna = 'success';
                  }else{
                      $warna = 'danger';
                  }
                  $data    = '';
                  if(check_role($id_menu, 3)){
                      // $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                      $data   .= "<span class=\"label label-$warna\">$status</span>";
                  }else{
                      $data   .= "<span class=\"label label-$warna\">$status</span>";
                  }
                  return $data;
              }
      );
        
    $datatables->add('action', function ($data) {
            $iledger  = trim($data['i_coa_ledger']);
            $f_status = trim($data['status']);
            $i_menu   = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"mst-ledgercoa/cform/view/$iledger/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)&& $f_status != 'f'){
                $data .= "<a href=\"#\" onclick='show(\"mst-ledgercoa/cform/edit/$iledger/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
            // if(check_role($i_menu, 4)&& $f_status_aktif != 'f'){
            //     $data .= "&nbsp;&nbsp;<a href=\"#\" onclick='hapus(\"$iledger\"); return false;'><i class='fa fa-trash'></i></a>";
            // }
      return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        $datatables->hide('id_company');
        
        return $datatables->generate();
    }

    public function cekkode($kode){
        return $this->db->query("SELECT i_coa_ledger  FROM tr_coa_ledger WHERE i_coa_ledger ='$kode'", FALSE);
    } 

    public function typecoa($cari){
        return $this->db->query(" 
                                SELECT 
                                    id, 
                                    i_coa_type, 
                                    e_coa_type_name
                                FROM 
                                    tr_coa_type
                                WHERE
                                    f_status = 't' 
                                    AND (i_coa_type like '%$cari%' or e_coa_type_name like '%$cari%')
                                ORDER BY
                                    i_coa_type
                                ", FALSE);
    }

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_coa_ledger');
        $this->db->where('i_kode_ledger_coa', $id);
        $query = $this->db->get();
        if ($query->num_rows()>0) {
            $row    = $query->row();
            $status = $row->f_status;
            if ($status=='t') {
                $stat = 'f';
            }else{
                $stat = 't';
            }
        }
        $data = array(
            'f_status' => $stat 
        );
        $this->db->where('i_kode_ledger_coa', $id);
        $this->db->update('tr_coa_ledger', $data);
    }

    public function cek_data($id){
        return $this->db->query(" 
                                SELECT 
                                    a.*,
                                    b.e_coa_type_name
                                FROM 
                                    tr_coa_ledger a
                                    LEFT JOIN
                                        tr_coa_type b ON (a.id_coa_type = b.id)
                                WHERE 
                                    i_coa_ledger = '$id'
                                ", FALSE);
    }

    public function insert($iledger, $eledger, $icoatype){
        $idcompany  = $this->session->userdata('id_company');
 
        $dentry = date("Y-m-d H:i:s");
        $data = array(
              'i_coa_ledger'        => $iledger,
              'e_coa_ledger_name'   => $eledger,   
              'id_coa_type'         => $icoatype,   
              'id_company'          => $idcompany,  
              'd_entry'             => $dentry,        
        );
    
        $this->db->insert('tr_coa_ledger', $data);
    }

    public function update($id, $iledger, $eledger, $icoatype){
        $dupdate = date("Y-m-d H:i:s");
        $data = array(
            'i_coa_ledger'      => $iledger,
            'e_coa_ledger_name' => $eledger,        
            'd_update'          => $dupdate, 
            'id_coa_type'       => $icoatype,
    );

    $this->db->where('id', $id);
    $this->db->update('tr_coa_ledger', $data);
    }

    public function cancel($iledger){
        $data = array(
          'f_status_aktif'=>'f',
      );
        $this->db->where('i_kode_ledger_coa', $iledger);
        $this->db->update('tr_coa_ledger', $data);
      }

}

/* End of file Mmaster.php */