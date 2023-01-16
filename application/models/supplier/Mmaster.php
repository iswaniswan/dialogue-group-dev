<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
		$datatables->query("SELECT tr_supplier.i_supplier,tr_supplier.e_supplier_name,tr_supplier.e_supplier_address,tr_supplier.e_supplier_phone, $i_menu as i_menu FROM tr_supplier 
                            LEFT JOIN tr_supplier_group
                            ON (tr_supplier.i_supplier_group = tr_supplier_group.i_supplier_group)");

		$datatables->add('action', function ($data) {
            $i_supplier = trim($data['i_supplier']);
            $i_menu = $data['i_menu'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"supplier/cform/view/$i_supplier/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"supplier/cform/edit/$i_supplier/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');

        return $datatables->generate();
	}

	function cek_data($id){
    $this->db->select('*');
    $this->db->from('tr_supplier');
    $this->db->join('tr_supplier_group', 'tr_supplier_group.i_supplier_group = tr_supplier.i_supplier_group');
    $this->db->where('i_supplier', $id);

    return $this->db->get();

	}

    function get_supplier_group(){
        $this->db->select('*');
        $this->db->from('tr_supplier_group');
    return $this->db->get();
    }


	public function insert($isupplier, $isuppliergroup, $isuppliername, $isupplieraddres, $isuppliercity, $isupplierpostalcode, $isupplierphone, 
                        $isupplierfax, $isupplierownername, $isupplierowneraddress, $isuppliernpwp, $isupplierphone2, $isuppliercontact, $isupplieremail, $isupplierdiscount, 
                        $isupplierdiscount2, $isuppliertoplength, $pkp, $ppn){
        $data = array(
              'i_supplier'              => $isupplier,
              'i_supplier_group'        => $isuppliergroup,
              'e_supplier_name'         => $isuppliername,
              'e_supplier_address'      => $isupplieraddres,
              'e_supplier_city'         => $isuppliercity,
              'e_supplier_postalcode'   => $isupplierpostalcode,
              'e_supplier_phone'        => $isupplierphone,
              'e_supplier_fax'          => $isupplierfax,
              'e_supplier_ownername'    => $isupplierownername,
              'e_supplier_owneraddress' => $isupplierowneraddress,
              'e_supplier_npwp'         => $isuppliernpwp,
              'e_supplier_phone2'       => $isupplierphone2,
              'e_supplier_contact'      => $isuppliercontact,
              'e_supplier_email'        => $isupplieremail,
              'n_supplier_discount'     => $isupplierdiscount,
              'n_supplier_discount2'    => $isupplierdiscount2,
              'n_supplier_toplength'    => $isuppliertoplength,
              'f_supplier_pkp'          => $pkp,
              'f_supplier_ppn'          => $ppn
    );
    var_dump($data);
    $this->db->insert('tr_supplier', $data);
    }

    public function update($isupplier, $isuppliergroup, $isuppliername, $isupplieraddres, $isuppliercity, $isupplierpostalcode, $isupplierphone, 
                        $isupplierfax, $isupplierownername, $isupplierowneraddress, $isuppliernpwp, $isupplierphone2, $isuppliercontact, $isupplieremail, $isupplierdiscount, 
                        $isupplierdiscount2, $isuppliertoplength, $pkp, $ppn){
        $data = array(
              'i_supplier'              => $isupplier,
              'i_supplier_group'        => $isuppliergroup,
              'e_supplier_name'         => $isuppliername,
              'e_supplier_address'      => $isupplieraddres,
              'e_supplier_city'         => $isuppliercity,
              'e_supplier_postalcode'   => $isupplierpostalcode,
              'e_supplier_phone'        => $isupplierphone,
              'e_supplier_fax'          => $isupplierfax,
              'e_supplier_ownername'    => $isupplierownername,
              'e_supplier_owneraddress' => $isupplierowneraddress,
              'e_supplier_npwp'         => $isuppliernpwp,
              'e_supplier_phone2'       => $isupplierphone2,
              'e_supplier_contact'      => $isuppliercontact,
              'e_supplier_email'        => $isupplieremail,
              'n_supplier_discount'     => $isupplierdiscount,
              'n_supplier_discount2'    => $isupplierdiscount2,
              'n_supplier_toplength'    => $isuppliertoplength,
              'f_supplier_pkp'          => $pkp,
              'f_supplier_ppn'          => $ppn
    );

    $this->db->where('i_supplier', $isupplier);
    $this->db->update('tr_supplier', $data);
    }

}

/* End of file Mmaster.php */
