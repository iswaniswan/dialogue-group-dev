<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Dgapps extends CI_Controller
{
    public $url_api = 'http://202.150.150.58/dgapps_demo/index.php/rest/';

    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        set_time_limit(0);
        include 'CurlDgapps.php';
        $this->CurlDgapps = new CurlDgapps;
        $this->company = $this->db->query("SELECT * FROM public.company WHERE f_status = 't' AND i_apps = '2' and api_key is not null");
        //$this->db->get_where('public.company', ['f_status' => 't', 'i_apps' => '2']);
    }

    public function index()
    {
        echo 'HAI API';
        die;
    }

    // POST AREA
    public function postarea()
    {
        $response = [];
        if ($this->company->num_rows() > 0) {
            foreach ($this->company->result() as $key) {
                if (strlen($key->i_company_dgapps) > 0 && strlen($key->api_key) > 0) {
                    $area = $this->db->query("SELECT i_area, e_area FROM produksi.tr_area WHERE f_status = 't' AND $key->id = ANY (id_company)");
                    if ($area->num_rows() > 0) {
                        foreach ($area->result() as $row) {
                            $data = array(
                                'action' => 'create',
                                'api_key' => $key->api_key,
                                'i_company' => $key->i_company_dgapps,
                                'i_area' => $row->i_area,
                                'e_area_name' => $row->e_area,
                                'f_active' => 'true',
                            );
                            /* echo '<pre>';
                            var_dump($data);
                            echo '</pre>'; */
                            $url = $this->url_api . 'area';
                            $data = json_encode($data);
                            $this->CurlDgapps->postCURL($url, $data);
                        }
                    }
                }
            }
        }
        echo json_encode($response);
        die;
    }

    // POST CUSTOMER
    public function postcustomer()
    {
        $response = [];
        if ($this->company->num_rows() > 0) {
            foreach ($this->company->result() as $key) {
                if (strlen($key->i_company_dgapps) > 0 && strlen($key->api_key) > 0) {
                    $customer = $this->db->query(
                        "SELECT i_customer, e_customer_name, e_customer_contact, e_customer_phone, e_customer_address, i_area, c.i_harga, a.v_customer_discount, a.v_customer_discount2, a.v_customer_discount3 
                        FROM produksi.tr_customer a 
                        INNER JOIN produksi.tr_area b ON (b.id = a.id_area)
                        INNER JOIN produksi.tr_harga_kode c ON (c.id = a.id_harga_kode)
                        WHERE a.f_status = TRUE AND a.id_company = '$key->id'
                        /* AND ( 
                                a.d_entry >= date_trunc('day', NOW() - interval '1 month')::date
                                OR
                                a.d_update >= date_trunc('day', NOW() - interval '1 month')::date
                            ) */
                        ORDER BY 2"
                    );
                    if ($customer->num_rows() > 0) {
                        foreach ($customer->result() as $row) {
                            $data = array(
                                'action' => 'create',
                                'api_key' => $key->api_key,
                                'i_company' => $key->i_company_dgapps,
                                'i_customer' => trim($row->i_customer),
                                'e_customer_name' => $row->e_customer_name,
                                'e_contact_name' => $row->e_customer_contact,
                                'e_phone_number' => $row->e_customer_phone,
                                'e_customer_address' => $row->e_customer_address,
                                'i_area' => $row->i_area,
                                'i_price_group' => $row->i_harga,
                                'n_customer_discount1' => $row->v_customer_discount,
                                'n_customer_discount2' => $row->v_customer_discount2,
                                'f_active' => 'true',
                            );
                            /* echo '<pre>';
                            var_dump($data);
                            echo '</pre>'; */
                            $url = $this->url_api . 'customer';
                            $data = json_encode($data);
                            $response[] = $this->CurlDgapps->postCURL($url, $data);
                        }
                    }
                }
            }
        }
        echo json_encode($response);
        die;
    }

    // POST BARANG JADI
    public function postproduct()
    {
        $response = [];
        if ($this->company->num_rows() > 0) {
            foreach ($this->company->result() as $key) {
                if (strlen($key->i_company_dgapps) > 0 && strlen($key->api_key) > 0) {
                    $product = $this->db->query(
                        "SELECT DISTINCT i_product_base, e_product_basename, c.i_jenis AS i_product_grade, '00' AS i_product_motif, 
                        d.i_harga, case when a.id_company in (12) then '01' else e.i_brand end as i_brand, 
                        case when a.id_company in (12) then h.name else e.e_brand_name end as e_brand_name, case when a.f_status = true then 'true' else 'false' end as f_status, round(b.v_price * (COALESCE(n_tax,0) / 100 + 1)) AS v_product_retail,
                        d.e_harga,(n_tax / 100 + 1) excl_divider, n_tax / 100 AS n_tax_val
                        FROM produksi.tr_product_base a
                        INNER JOIN produksi.tr_harga_jualbrgjd b ON (b.id_product_base = a.id)
                        INNER JOIN produksi.tr_jenis_barang_keluar c ON (c.id = b.id_jenis_barang_keluar)
                        INNER JOIN produksi.tr_harga_kode d ON (d.id = b.id_harga_kode)
                        INNER JOIN produksi.tr_brand e ON (e.i_brand = a.i_brand AND a.id_company = e.id_company)
                        INNER JOIN produksi.tr_status_produksi f ON (f.i_status_produksi = a.i_status_produksi)
                        LEFT JOIN public.tr_tax_amount g ON (current_date BETWEEN g.d_start AND g.d_finish)
                        inner join public.company h on (a.id_company = h.id)
                        WHERE a.id_company = '$key->id' AND c.f_transfer = TRUE AND (current_date BETWEEN b.d_berlaku AND b.d_akhir OR b.d_akhir ISNULL) AND b.f_status = TRUE
                        ORDER BY 1,2,3,4,5,6,7"
                    );
                    if ($product->num_rows() > 0) {
                        foreach ($product->result() as $row) {
                            $data = array(
                                'action' => 'create',
                                'api_key' => $key->api_key,
                                'i_company' => $key->i_company_dgapps,
                                'i_product' => $row->i_product_base,
                                'e_product_name' => $row->e_product_basename,
                                'i_product_group' => $row->i_brand,
                                'e_product_groupname' => $row->e_brand_name,
                                'f_active' => $row->f_status,
                            );
                            $url = $this->url_api . 'product';
                            $data = json_encode($data);
                            //var_dump($data);
                            $this->CurlDgapps->postCURL($url, $data);

                            $data = array(
                                'action' => 'create',
                                'api_key' => $key->api_key,
                                'i_company' => $key->i_company_dgapps,
                                'i_product' => $row->i_product_base,
                                'e_product_name' => $row->e_product_basename,
                                'i_product_grade' => $row->i_product_grade,
                                'i_price_group' => $row->i_harga,
                                'e_price_groupname' => $row->e_harga,
                                'v_product_price' => $row->v_product_retail,
                            );
                            $url = $this->url_api . 'productprice';
                            $data = json_encode($data);
                            $response = $this->CurlDgapps->postCURL($url, $data);
                        }
                    }
                }
            }
        }
        echo json_encode($response);
        die;
    }

    // POST STOK BARANG JADI
    public function stok()
    {
        $response = [];
        $i_periode = date('Ym');
        $dfrom = date('Y-m-01');
        $dto = date('Y-m-t');
        if ($this->company->num_rows() > 0) {
            foreach ($this->company->result() as $key) {
                if (strlen($key->i_company_dgapps) > 0 && strlen($key->api_key) > 0) {
                    $stok = $this->db->query(
                        "SELECT b.i_product_base AS i_product, 'AA' as i_store, sum(a.n_saldo_akhir + a.n_saldo_akhir_repair) as  n_quantity_stock  
                        FROM produksi.f_mutasi_gudang_jadi ('$key->id','$i_periode','9999-01-01','9999-01-31','$dfrom','$dto','') a 
                        INNER JOIN produksi.tr_product_base b ON (b.id = a.id_product_base)
                        WHERE b.f_status = 't' AND b.id_company = '$key->id'
                        group by 1,2
                        ORDER BY 1"
                    );
                    $data = array(
                        'action' => 'create',
                        'api_key' => $key->api_key,
                        'i_company' => $key->i_company_dgapps,
                        'data' => $stok->result_array(),
                    );

                    $url = $this->url_api . 'stokproduct';
                    $data = json_encode($data);
                    $response = $this->CurlDgapps->postCURL($url, $data);
                }
            }
        }
        echo json_encode($response);
        die;
    }


    public function postproductstatus()
    {

        $cari = strtoupper($this->uri->segment('4'));
        $response = [];
        if ($this->company->num_rows() > 0) {
            foreach ($this->company->result() as $key) {
                if (strlen($key->i_company_dgapps) > 0 && strlen($key->api_key) > 0) {
                    $product = $this->db->query(
                        "SELECT DISTINCT a.i_product_base AS i_product, a.e_product_basename AS e_product_name, b.i_brand AS i_product_group,
                        c.f_stp as status_product 
                        from produksi.tr_product_base a
                        INNER JOIN produksi.tr_brand b ON (a.i_brand = b.i_brand AND b.id_company = a.id_company)
                        INNER JOIN produksi.tr_status_produksi c ON (c.i_status_produksi = a.i_status_produksi)
                        where  a.id_company = '$key->id' AND a.f_status = 't'
                        and a.i_product_base like '%$cari%'
                        ORDER BY 1,2"
                    );
                    $data = array(
                        'action' => 'create',
                        'api_key' => $key->api_key,
                        'i_company' => $key->i_company_dgapps,
                        'data' => $product->result_array(),
                    );

                    $url = $this->url_api . 'productstatus';
                    $data = json_encode($data);
                    $response = $this->CurlDgapps->postCURL($url, $data);
                }
            }
        }
        echo json_encode($response);
        die;
    }

    public function postrrkh()
    {
        $response = [];
        if ($this->company->num_rows() > 0) {
            foreach ($this->company->result() as $key) {
                if (strlen($key->i_company_dgapps) > 0 && strlen($key->api_key) > 0) {
                    $query = $this->db->query(
                        "SELECT 'create' AS act, a.id_area AS i_area, a.id_salesman AS i_salesman, a.d_document AS d_rrkh, c.i_customer  
                        FROM produksi.tm_rrkh a
                        INNER JOIN produksi.tm_rrkh_item d ON (d.id_rrkh = a.id)
                        INNER JOIN produksi.tr_customer c ON (c.id = d.id_customer)
                        WHERE a.i_status = '6' AND a.id_company = '$key->id' AND d_document >= (NOW() - INTERVAL '7 DAY')::date
                        ORDER BY d_document"
                    );
                    if ($query->num_rows() > 0) {
                        foreach ($query->result() as $row) {
                            $data = array(
                                'action' => $row->act,
                                'api_key' => $key->api_key,
                                'i_company' => $key->i_company_dgapps,
                                'i_area' => $row->i_area,
                                'username' => $row->i_salesman,
                                'd_rrkh' => $row->d_rrkh,
                                'i_customer' => $row->i_customer,
                            );
                            $url = $this->url_api . 'rrkh';
                            /* echo '<pre>';
                            var_dump($data);
                            echo '</pre>'; */
                            $data = json_encode($data);
                            $response = $this->CurlDgapps->postCURL($url, $data);
                        }
                    }
                }
            }
        }
        echo json_encode($response);
        die;
    }

    public function getsalesorder()
    {
        $startTime = date('Y-m-d', strtotime('-3 days', strtotime(date('Y-m-d'))));
        $endTime = date('Y-m-d');
        $fulfilled = 'false';
        if ($this->company->num_rows() > 0) {
            foreach ($this->company->result() as $key) {
                if (strlen($key->i_company_dgapps) > 0 && strlen($key->api_key) > 0) {
                    $url = $this->url_api . "salesorder?action=list&api_key=" . $key->api_key . "&i_company=" . $key->i_company_dgapps . "&starttime=" . $startTime . "&endtime=" . $endTime . "&fulfilled=" . $fulfilled;
                    $response = $this->CurlDgapps->get_curl($url);
                    /* echo '<pre>';
                    var_dump($response['data']);
                    echo '</pre>'; */
                    if ($response['data']) {
                        $hasil = [];
                        foreach ($response['data'] as $row) {
                            $i_spb_reff = $row['i_spb'];
                            $i_customer = $row['i_customer'];
                            $i_area = $row['i_area'];
                            $i_salesman = $row['i_staff'];
                            $d_spb = $row['d_spb'];
                            $i_price_group = $row['i_price_group'];
                            $e_remark = $row['e_remark'];
                            $ndiskon1 = $row['n_spb_discount1'];
                            $ndiskon2 = $row['n_spb_discount2'];
                            $ndiskon3 = $row['n_spb_discount3'];
                            $d_spb = formatYmd($d_spb);
                            $thbl = format_to_ym($d_spb);
                            $tahun = format_Y($d_spb);

                            $cek_spb = $this->db->query(
                                "SELECT i_reff_salesforce 
                                FROM produksi.tm_spb a
                                INNER JOIN produksi.tr_area b ON (b.id = a.id_area)
                                INNER JOIN produksi.tr_customer c ON (c.id = a.id_customer)
                                WHERE i_reff_salesforce = '$i_spb_reff' AND a.id_company = '$key->id' 
                                AND b.i_area = '$i_area' AND c.i_customer = '$i_customer'
                            ");

                            $no = 1;
                            if ($cek_spb->num_rows() == 0) {
                                $customer = $this->db->query("SELECT id, e_customer_name FROM produksi.tr_customer WHERE i_customer = '$i_customer' AND id_company = '$key->id' ")->row();
                                $idcustomer = $customer->id;
                                $ecustomername = $customer->e_customer_name;
                                $ibagian = $this->db->query("SELECT i_bagian FROM produksi.tr_bagian WHERE i_type = '24' AND id_company = '$key->id' ORDER BY id DESC LIMIT 1")->row()->i_bagian;
                                $idarea = $this->db->query("SELECT id FROM produksi.tr_area WHERE i_area = '$i_area' AND '$key->id' = ANY (id_company)")->row()->id;
                                $idsales = $this->db->query("SELECT id FROM produksi.tr_salesman WHERE i_sales = '$i_salesman' AND id_company = '$key->id'")->row()->id;
                                $idharga = $this->db->query("SELECT id FROM produksi.tr_harga_kode WHERE id_company = '$key->id' AND lower(i_harga) = lower('$i_price_group')")->row()->id;
                                $nppn = $this->db->query("SELECT n_tax FROM public.tr_tax_amount WHERE current_date BETWEEN d_start AND d_finish AND f_active = TRUE")->row()->n_tax;
                                $ireferensi = null;
                                $etypespb = 'Transfer';
                                $f_spb_stockdaerah = 'f';
                                $i_area_distributor = null;
                                $vdiskon = 0;
                                $vkotor = 0;
                                $vppn = 0;
                                $vbersih = 0;
                                $vdpp = 0;
                                $productgrade = [];
                                foreach ($row['items'] as $raw) {
                                    array_push($productgrade, $raw['i_product_grade']);
                                }
                                $i_product_grade = str_replace('"','',to_pg_array(array_unique($productgrade)));
                                $id_jenis_barang_keluar = $this->db->query("SELECT id FROM produksi.tr_jenis_barang_keluar WHERE lower(i_jenis) = lower('$i_product_grade')")->row()->id;

                                // LOAD MODEL SPB
                                $this->load->model('spbpenjualan/Mmaster','mmaster');
                                $this->db->trans_begin();
                                // Running ID SPB
                                $id = $this->mmaster->runningid();
                                // Running Number SPB
                                $idocument = $this->mmaster->runningnumber_salesforce($thbl, $tahun, $ibagian, $idcustomer, $key->id);
                                // Simpan Header SPB
                                // var_dump($id,$idocument,$d_spb,$ibagian,$idcustomer,$ecustomername,$idarea,$idsales,$ireferensi,$vdiskon,$vkotor,$vppn,$vbersih,$e_remark,$vdpp,$idharga,$etypespb,$id_jenis_barang_keluar,$nppn, $i_area_distributor, $f_spb_stockdaerah);
                                $this->mmaster->insertheader_salesforce($id,$idocument,$d_spb,$ibagian,$idcustomer,$ecustomername,$idarea,$idsales,$ireferensi,$vdiskon,$vkotor,$vppn,$vbersih,$e_remark,$vdpp,$idharga,$etypespb,$id_jenis_barang_keluar,$nppn, $i_area_distributor, $f_spb_stockdaerah, $key->id, $i_spb_reff);

                                // Simpan Detail SPB
                                foreach ($row['items'] as $raw) {
                                    $nquantity = $raw['n_order'];
                                    $i_product = $raw['i_product'];
                                    $idproduct = $this->db->query("SELECT DISTINCT id FROM produksi.tr_product_base WHERE i_product_base = '$i_product' AND id_company = '$key->id' ORDER BY id LIMIT 1 ")->row()->id;
                                    $vprice = $raw['v_unit_price'];
                                    $eremark = $raw['e_remark'];
                                    $vdiskon1 = 0;
                                    $vdiskon2 = 0;
                                    $vdiskon3 = 0;
                                    $vdiskonplus = 0;
                                    $vtotaldiskon = 0;
                                    $vtotal = 0;
                                    $this->mmaster->insertdetail_salesforce($id, $idproduct, $nquantity, $vprice, $ndiskon1, $ndiskon2, $ndiskon3, $vdiskon1, $vdiskon2, $vdiskon3, $vdiskonplus, $vtotaldiskon, $vtotal, $eremark, $key->id);
                                }

                                if (($this->db->trans_status() === false)) {
                                    $this->db->trans_rollback();
                                } else {
                                    $data_fulfill = array(
                                        'action' => 'fulfill',
                                        'api_key' => $key->api_key,
                                        'i_company' => $key->i_company_dgapps,
                                        'i_spb' => $i_spb_reff,
                                        'i_area' => $i_area,
                                        'i_customer' => $i_customer,
                                    );

                                    $url_fulfill = $this->url_api . 'salesorder';
                                    $data_fulfill = json_encode($data_fulfill);
                                    $this->CurlDgapps->postCURL($url_fulfill, $data_fulfill);

                                    $this->db->trans_commit();
                                    $hasil[$no]['i_reff_advo'] = $i_spb_reff;
                                    $hasil[$no]['i_spb'] = $idocument;
                                    $no++;
                                }
                            }
                        }
                        echo json_encode($hasil);
                    }
                }
            }
        }
    }
}