<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
                            SELECT 
                                0 as no,
                                i_coa_segment,
                                e_coa_segment_name,
                                case when f_status = TRUE then 'Aktif' else 'Tidak Aktif' end as status,
                                '$folder' as folder,
                                '$i_menu' as i_menu
                            FROM
                                tr_coa_segment
                            ", FALSE);
        $datatables->edit(
        'status', 
                function ($data) {
                    $id         = trim($data['i_coa_segment']);
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
                        $data   .= "<a href=\"#\" title='Update Status' onclick='status(\"$id\",\"$folder\"); return false;'><span class=\"label label-$warna\">$status</span></a>";
                    }else{
                        $data   .= "<span class=\"label label-$warna\">$status</span>";
                    }
                    return $data;
                }
        );
        $datatables->hide('folder');
        $datatables->hide('i_menu');
        return $datatables->generate();
    }

    public function status($id){
        $this->db->select('f_status');
        $this->db->from('tr_coa_segment');
        $this->db->where('i_coa_segment', $id);
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
        $this->db->where('i_coa_segment', $id);
        $this->db->update('tr_coa_segment', $data);
    }
}
/* End of file Mmaster.php */