<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '210160204';

    public function __construct()
    {
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->library('fungsi');

        $this->load->model($this->global['folder'].'/mmaster');
    }   

    public function index()
    {
        $dfrom = $this->input->post('dfrom', TRUE);
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(4);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto', TRUE);
        if ($dto == '') {
            $dto = $this->uri->segment(5);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $salesReportByCategory = $this->mmaster->get_detail(formatYmd($dfrom), formatYmd($dto), formatperiode($dfrom), formatperiode($dto));
        $sum_oa_tahun_sebelumnya = 0;
        $sum_oa_tahun_saat_ini = 0;
        $total_sum_percentage_oa = 0;
        $sum_sales_qty_tahun_sebelumnya = 0;
        $sum_sales_qty_tahun_saat_ini = 0;
        $total_sum_percentage_sales_qty = 0;
        $sum_net_sales_tahun_sebelumnya = 0;
        $sum_net_sales_tahun_saat_ini = 0;
        $total_sum_percentage_net_sales = 0;

        foreach($salesReportByCategory->result() as $key) {
            $sum_oa_tahun_sebelumnya += $key->oa_tahun_sebelumnya;
            $sum_oa_tahun_saat_ini += $key->oa_tahun_saat_ini;
            $total_sum_percentage_oa += ($key->oa_tahun_saat_ini != 0 AND $key->oa_tahun_sebelumnya != 0) ? round(($key->oa_tahun_saat_ini / $key->oa_tahun_sebelumnya * 100) - 100, 2) : 0;
            $sum_sales_qty_tahun_sebelumnya += $key->sales_qty_tahun_sebelumnya;
            $sum_sales_qty_tahun_saat_ini += $key->sales_qty_tahun_saat_ini;
            $total_sum_percentage_sales_qty += ($key->sales_qty_tahun_saat_ini != 0 AND $key->sales_qty_tahun_sebelumnya != 0) ? round(($key->sales_qty_tahun_saat_ini / $key->sales_qty_tahun_sebelumnya * 100) - 100, 2) : 0;
            $sum_net_sales_tahun_sebelumnya += $key->net_sales_tahun_sebelumnya;
            $sum_net_sales_tahun_saat_ini += $key->net_sales_tahun_saat_ini;
            $total_sum_percentage_net_sales += ($key->net_sales_tahun_saat_ini != 0 AND $key->net_sales_tahun_sebelumnya != 0) ? round(($key->net_sales_tahun_saat_ini / $key->net_sales_tahun_sebelumnya * 100) - 100, 2) : 0;
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "Tambah " . $this->global['title'],
            'title_list'    => 'List ' . $this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'header'        => $this->mmaster->get_header(formatYmd($dfrom), formatYmd($dto)),
            'detail'        => $salesReportByCategory,
            'sum_oa_tahun_sebelumnya' => $sum_oa_tahun_sebelumnya,
            'sum_oa_tahun_saat_ini'   => $sum_oa_tahun_saat_ini,
            'total_sum_percentage_oa' => $total_sum_percentage_oa,
            'sum_sales_qty_tahun_sebelumnya' => $sum_sales_qty_tahun_sebelumnya,
            'sum_sales_qty_tahun_saat_ini'   => $sum_sales_qty_tahun_saat_ini,
            'total_sum_percentage_sales_qty' => $total_sum_percentage_sales_qty,
            'sum_net_sales_tahun_sebelumnya' => $sum_net_sales_tahun_sebelumnya,
            'sum_net_sales_tahun_saat_ini'   => $sum_net_sales_tahun_saat_ini,
            'total_sum_percentage_net_sales' => $total_sum_percentage_net_sales
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }
}
/* End of file Cform.php */