<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Ozdemir\Datatables\Datatables;
use Ozdemir\Datatables\DB\CodeigniterAdapter;

class Mmaster extends CI_Model {

	function data($folder,$i_menu){
		$datatables = new Datatables(new CodeigniterAdapter);
        $datatables->query("
            SELECT 
                i_product, 
                e_product_name, 
                '$i_menu' AS i_menu,
                '$folder' AS folder
            FROM 
                tr_product
            WHERE i_product_status='2'
        ");
		$datatables->add('action', function ($data) {
            $i_product = trim($data['i_product']);
            $i_menu = $data['i_menu'];
            $folder = $data['folder'];
            $data = '';
            if(check_role($i_menu, 2)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/view/$i_product/\",\"#main\"); return false;'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
            }
            if(check_role($i_menu, 3)){
                $data .= "<a href=\"#\" onclick='show(\"$folder/cform/edit/$i_product/\",\"#main\"); return false;'><i class='fa fa-pencil'></i></a>";
            }
			return $data;
        });
        $datatables->hide('i_menu');
        $datatables->hide('folder');
        return $datatables->generate();
	}

    public function cari_brg($nbrg){
        return $this->db->query(" SELECT * FROM tr_product WHERE /*i_product_status='2' AND*/ UPPER(i_product)='$nbrg' ");
    }

    public function bacatype($cari,$iproductgroup){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_product_type,
                e_product_typename
            FROM
                tr_product_type
            WHERE
                i_product_group = '$iproductgroup'
                AND (UPPER(e_product_typename) LIKE '%$cari%'
                OR UPPER(i_product_type) LIKE '%$cari%')
            ORDER BY
                i_product_type
        ", FALSE);
    }

    public function bacakategori($cari,$iproductclass){
        $cari = str_replace("'", "", $cari);
        return $this->db->query("
            SELECT
                i_product_category,
                e_product_categoryname
            FROM
                tr_product_category
            WHERE
                i_product_class = '$iproductclass'
                AND (UPPER(e_product_categoryname) LIKE '%$cari%'
                OR UPPER(i_product_category) LIKE '%$cari%')
            ORDER BY
                i_product_category
        ", FALSE);
    }

	function cek_data($id){
        $this->db->select('*');
        $this->db->from('tr_product');
        $this->db->join('tr_product_class','tr_product.i_product_class = tr_product_class.i_product_class');
        $this->db->join('tr_product_category','tr_product.i_product_category = tr_product_category.i_product_category');
        $this->db->join('tr_product_type','tr_product.i_product_type= tr_product_type.i_product_type');
        $this->db->join('tr_product_group','tr_product_group.i_product_group = tr_product_type.i_product_group');
        $this->db->join('tr_product_status','tr_product.i_product_status = tr_product_status.i_product_status');
        $this->db->join('tr_supplier','tr_product.i_supplier = tr_supplier.i_supplier');
        $this->db->where('tr_product.i_product', $id);
        return $this->db->get();
	}

    function get_supplier(){
        $this->db->select('*');
        $this->db->from('tr_supplier');
        return $this->db->get();
    }
    function get_productgroup(){
        $this->db->select('*');
        $this->db->from('tr_product_group');
        return $this->db->get();
    }
    function get_productclass(){
        $this->db->select('*');
        $this->db->from('tr_product_class');
        return $this->db->get();
    }
    function get_productstatus(){
        $this->db->select('*');
        $this->db->from('tr_product_status');
        return $this->db->get();
    }
    function get_producttype(){
        $this->db->select('*');
        $this->db->from('tr_product_type');
        return $this->db->get();
    }
    function get_iproductcategory(){
        $this->db->select('*');
        $this->db->from('tr_product_category');
        return $this->db->get();
    }
    function get_iproductseri(){
        $this->db->select('*');
        $this->db->from('tr_product_seri');
        return $this->db->get();
    }
    public function bacasupplier(){
        return $this->db->order_by('i_supplier','ASC')->get('tr_supplier')->result();
    }
    public function bacaproductgroup(){
        return $this->db->order_by('i_product_group','ASC')->get('tr_product_group')->result();
    }
    public function bacaproductclass(){
        return $this->db->order_by('i_product_class','ASC')->get('tr_product_class')->result();
    }
    public function bacaproductstatus(){
        return $this->db->order_by('i_product_status','ASC')->get('tr_product_status')->result();
    }
    public function bacaproducttype(){
        return $this->db->order_by('i_product_type','ASC')->get('tr_product_type')->result();
    }
    public function bacaproductcategory(){
        return $this->db->order_by('i_product_category','ASC')->get('tr_product_category')->result();
    }

	public function insert($iproduct,$iproductsupplier,$isupplier,$iproductstatus,$iproducttype,$iproductcategory,$iproductclass,$iproductgroup,$eproductname,$eproductsuppliername,$vproductretail,$vproductmill,$fproductpricelist,$dproductstopproduction,$dproductregister){
        if($fproductpricelist=='on'){
            $fproductpricelist='TRUE';
        }else{
            $fproductpricelist='FALSE';
        }
        if($dproductregister!=''){	
            $tmp=explode("-",$dproductregister);
            $t=$tmp[2];
            $b=$tmp[1];
            $h=$tmp[0];
            $dproductregister=$t."-".$b."-".$h." 00:00:00";
        }else{
            $dproductregister=NULL;
        }
        if($dproductstopproduction!=''){	
            $tmp=explode("-",$dproductstopproduction);
            $t=$tmp[2];
            $b=$tmp[1];
            $h=$tmp[0];
            $dproductstopproduction=$t."-".$b."-".$h." 00:00:00";
        }else{
            $dproductstopproduction=NUll;
        }
        $query = $this->db->query("SELECT current_timestamp as c");
        $row   = $query->row();
        $dentry= $row->c;
        $this->db->set(
            array(
                'i_product'                 => $iproduct, 
                'i_product_supplier'        => $iproductsupplier, 
                'i_supplier'                => $isupplier, 
                'i_product_status'          => $iproductstatus, 
                'i_product_type'            => $iproducttype, 
                'i_product_category'        => $iproductcategory, 
                'i_product_class'           => $iproductclass,
                'e_product_name'            => $eproductname, 
                'e_product_suppliername'    => $eproductsuppliername, 
                'v_product_retail'          => $vproductretail, 
                'v_product_mill'            => $vproductmill, 
                'f_product_pricelist'       => $fproductpricelist, 
                'd_product_stopproduction'  => $dproductstopproduction, 
                'd_product_register'        => $dproductregister, 
                'd_product_entry'           => $dentry
            )
        );
        $this->db->insert('tr_product');
    }

    public function insertmotif($iproductmotif, $eproductmotifname,$iproduct, $eproductname){
        $query = $this->db->query("SELECT current_timestamp as c");
        $row   = $query->row();
        $dentry= $row->c;
        $query = $this->db->query("select * from tr_product_motif 
            where i_product='$iproduct' and i_product_motif='$iproductmotif'");
        if($query->num_rows()>0){
            $this->db->set(
                array(
                    'i_product'			    => $iproduct,
                    'i_product_motif' 		=> $iproductmotif,
                    'e_product_motifname'   => $eproductmotifname,
                    'd_product_motifentry'  => $dentry
                )
            );
            $this->db->where('i_product',$iproduct);
            $this->db->where('i_product_motif',$iproductmotif);
            $this->db->update('tr_product_motif');
        }else{
            $this->db->set(
                array(
                    'i_product'				=> $iproduct,
                    'i_product_motif' 		=> $iproductmotif,
                    'e_product_motifname' 	=> $eproductmotifname,
                    'd_product_motifentry'	=> $dentry
                )
            );
            $this->db->insert('tr_product_motif');
        }
    }

    public function insertprice($iproduct,$eproductname,$iproductgrade,$nproductmargin,$vproductmill){
        $query = $this->db->query("SELECT current_timestamp as c");
        $row   = $query->row();
        $dentry= $row->c;
        $query = $this->db->get('tr_price_group');
        foreach ($query->result() as $row){
            $tmp = $row->i_price_group;
            switch($tmp){
                case '00':
                $margin=(100-$nproductmargin)/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                break;
                case '01':
                $margin=(100-($nproductmargin+5+3+9))/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                break;
                case '02':
                $margin=(100-($nproductmargin+5))/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                break;
                case '03':
                $margin=(100-($nproductmargin+5+3))/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                break;
                case '04':
                $margin=(100-($nproductmargin+5+3+9+5))/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                break;
                case '05':
                $margin=(100-($nproductmargin+5+3+3))/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                break;
                case 'G0':
                $margin=(100-$nproductmargin)/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                $vproductretail=$vproductretail*0.9;
                break;
                case 'G2':
                $margin=(100-($nproductmargin+5))/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                $vproductretail=$vproductretail*0.9;
                break;
                case 'G3':
                $margin=(100-($nproductmargin+5+3))/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                $vproductretail=$vproductretail*0.9;
                break;
                case 'G5':
                $margin=(100-($nproductmargin+5+3+3))/100;
                $vproductretail=round((($vproductmill*1.1/$margin)),-2);
                $vproductretail=$vproductretail*0.9;
                break;
            }
            $query=$this->db->query("select * from tr_product_price 
             where i_product='$iproduct' and i_product_grade='$iproductgrade' and i_price_group='$tmp'");
            if($query->num_rows()>0){
                $this->db->query("update tr_product_price set e_product_name='$eproductname', 
                    v_product_retail=$vproductretail, v_product_mill=$vproductmill, 
                    d_product_priceentry='$dentry', n_product_margin=$nproductmargin
                    where i_product='$iproduct' and i_price_group='$tmp' and i_product_grade='$iproductgrade'");
            }else{
                $this->db->query("insert into tr_product_price (i_product, e_product_name, i_product_grade,
                   v_product_retail, v_product_mill, d_product_priceentry, i_price_group, 
                   n_product_margin) values
                   ('$iproduct','$eproductname','$iproductgrade',$vproductretail,$vproductmill, '$dentry',
                   '$tmp',$nproductmargin)");
            }
        }
    }

    public function update($iproduct,$iproductsupplier,$isupplier,$iproductstatus,$iproducttype,$iproductcategory,$iproductclass,$iproductgroup,$eproductname,$eproductsuppliername,$vproductretail,$vproductmill,$fproductpricelist,$dproductstopproduction, $dproductregister){
        if($fproductpricelist=='on'){
            $fproductpricelist='TRUE';
        }else{
            $fproductpricelist='FALSE';
        }
        if($dproductregister!=''){	
            $tmp=explode("-",$dproductregister);
            $t=$tmp[2];
            $b=$tmp[1];
            $h=$tmp[0];
            $dproductregister=$t."-".$b."-".$h." 00:00:00";
        }else{
            $dproductregister=NULL;
        }
        if($dproductstopproduction!=''){	
            $tmp=explode("-",$dproductstopproduction);
            $t=$tmp[2];
            $b=$tmp[1];
            $h=$tmp[0];
            $dproductstopproduction=$t."-".$b."-".$h." 00:00:00";
        }else{
            $dproductstopproduction=NUll;
        }
        $query = $this->db->query("SELECT current_timestamp as c");
        $row   = $query->row();
        $dupdate= $row->c;
        $data = array(
            'i_product'                  => $iproduct,
            'i_product_supplier'         => $iproductsupplier,
            'i_supplier'                 => $isupplier,
            'i_product_status'           => $iproductstatus,
            'i_product_type'             => $iproducttype,
            'i_product_category'         => $iproductcategory,
            'i_product_class'            => $iproductclass,
            'e_product_name'             => $eproductname,
            'e_product_suppliername'     => $eproductsuppliername,
            'v_product_retail'           => $vproductretail,
            'v_product_mill'             => $vproductmill,
            'f_product_pricelist'        => $fproductpricelist,
            'd_product_stopproduction'   => $dproductstopproduction,
            'd_product_register'         => $dproductregister,
            'd_product_update'		     => $dupdate
        );

        $this->db->where('i_product', $iproduct);
        $this->db->update('tr_product', $data);
        $data = array('v_product_mill'  => $vproductmill);
        $this->db->where('i_product', $iproduct);
        $this->db->update('tr_product_price', $data);
    }
}

/* End of file Mmaster.php */
