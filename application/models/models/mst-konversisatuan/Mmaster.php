<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu, $folder){
		$datatables = new Datatables(new CodeigniterAdapter);
    $idcompany  = $this->session->userdata('id_company');

		$datatables->query("
                            SELECT DISTINCT
                               0 AS no,
                               a.i_satuan_konversi,
                               a.i_satuan_code,
                               b.e_satuan_name,
                               a.i_satuan_code_konversi,
                               c.e_satuan_name AS satuan_konversi,
                               a.n_angka_faktor_konversi,
                               a.i_rumus_konversi,
                               d.e_rumus_konversi,
                               a.id_company,
                               CASE
                                  WHEN
                                     a.f_status = TRUE 
                                  THEN
                                     'Aktif' 
                                  ELSE
                                     'Tidak Aktif' 
                               END
                               AS status, 
                               '$i_menu' AS i_menu, 
                               '$folder' AS folder 
                            FROM
                                tr_konversi_satuan a 
                                JOIN
                                   tr_satuan b 
                                   ON b.i_satuan_code = a.i_satuan_code
                                   AND a.id_company = b.id_company
                                JOIN
                                   tr_satuan c 
                                   ON c.i_satuan_code = a.i_satuan_code_konversi
                                   AND a.id_company = c.id_company
                                JOIN 
                                    tr_rumus_konversi d
                                    ON a.i_rumus_konversi = d.i_rumus_konversi
                                    AND a.id_company = d.id_company
                                WHERE
                                    a.id_company = '$idcompany'
                            ", FALSE);

        $datatables->edit(
            'status', 
            function ($data) {
                $id         = trim($data['i_satuan_konversi']);
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

		$datatables->add('action', function ($data) {
            $id = trim($data['i_satuan_konversi']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$id/\",\"#main\"); return false;'><i class='ti-eye'></i></a>&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$id/\",\"#main\"); return false;'><i class='ti-pencil-alt'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('i_satuan_code');
        $datatables->hide('i_satuan_code_konversi');
        $datatables->hide('i_rumus_konversi');
        $datatables->hide('folder');
        $datatables->hide('id_company');
        
        return $datatables->generate();
	}

  public function status($id){
          $this->db->select('f_status');
          $this->db->from('tr_konversi_satuan');
          $this->db->where('id', $id);
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
          $this->db->where('id', $id);
          $this->db->update('tr_konversi_satuan', $data);
  }

  public function cekkode($kode){
        $idcompany  = $this->session->userdata('id_company');
        
        return $this->db->query("SELECT i_satuan_konversi FROM tr_konversi_satuan WHERE i_satuan_konversi ='$kode' AND id_company = '$idcompany'", FALSE);
  }

  public function getkonversi(){
        $idcompany  = $this->session->userdata('id_company');
        return $this->db->query("SELECT * FROM tr_rumus_konversi where id_company = '$idcompany' ", FALSE);
  }

	function cek_data($kodekonversi, $idcompany){
        return $this->db->query("
                                SELECT 
                                    a.id,
                                    a.i_satuan_konversi,
                                    a.i_satuan_code,
                                    a.i_satuan_code_konversi,
                                    a.n_angka_faktor_konversi,
                                    a.i_rumus_konversi,
                                    b.e_satuan_name as e_satuan_awal,
                                    c.e_satuan_name as e_satuan_konversi,
                                    d.e_rumus_konversi
                                FROM
                                    tr_konversi_satuan a
                                    LEFT JOIN 
                                        tr_satuan b ON (a.i_satuan_code = b.i_satuan_code)
                                    LEFT JOIN
                                        tr_satuan c ON (a.i_satuan_code_konversi = c.i_satuan_code)
                                    LEFT JOIN
                                        tr_rumus_konversi d ON (a.i_rumus_konversi = d.i_rumus_konversi)
                                WHERE
                                    a.i_satuan_konversi = '$kodekonversi'
                                AND
                                    a.id_company = '$idcompany'
                                ", FALSE);
        return $this->db->get();
  }

  function get_satuan(){
        $idcompany  = $this->session->userdata('id_company');

        $this->db->select('*');
        $this->db->from('tr_satuan');
        $this->db->where('id_company', $idcompany);
        return $this->db->get();
  }

	public function insert($kodekonversi, $isatuanawal, $isatuankonversi, $nfaktorkonversi, $irumuskonversi){
        $idcompany  = $this->session->userdata('id_company');
  
        $data = array(
              'i_satuan_konversi'       => $kodekonversi,
              'i_satuan_code'           => $isatuanawal,
              'i_satuan_code_konversi'  => $isatuankonversi,
              'n_angka_faktor_konversi' => $nfaktorkonversi,
              'i_rumus_konversi'        => $irumuskonversi, 
              'id_company'              => $idcompany, 
              'd_entry'                 => current_datetime(),              
        );
    
    $this->db->insert('tr_konversi_satuan', $data);
  }

  public function update($id, $kodekonversi, $isatuanawal, $isatuankonversi, $nfaktorkonversi, $irumuskonversi, $idcompany){
    
        $data = array(  
              'id'                      => $id,
              'i_satuan_konversi'       => $kodekonversi,
              'i_satuan_code'           => $isatuanawal,
              'i_satuan_code_konversi'  => $isatuankonversi,
              'n_angka_faktor_konversi' => $nfaktorkonversi,
              'i_rumus_konversi'        => $irumuskonversi,       
              'd_update'                => current_datetime(),              
    );

    $this->db->where('id', $id);
    $this->db->where('id_company', $idcompany);
    $this->db->update('tr_konversi_satuan', $data);
  }
}
/* End of file Mmaster.php */