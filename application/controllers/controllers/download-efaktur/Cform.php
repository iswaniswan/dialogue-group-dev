<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '2040109';

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

    /*----------  DEFAULT CONTROLLERS  ----------*/
    
    public function index()
    {

        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $supplier = $this->input->post('supplier');
        if($supplier== ''){
            $supplier  = $this->uri->segment(4);
            if($supplier== ''){
                $supplier = 'SP';
            }
        }

        $dfrom = $this->input->post('dfrom');
        if ($dfrom == '') {
            $dfrom = $this->uri->segment(5);
            if ($dfrom == '') {
                $dfrom = '01-' . date('m-Y');
            }
        }
        $dto = $this->input->post('dto');
        if ($dto == '') {
            $dto = $this->uri->segment(6);
            if ($dto == '') {
                $dto = date('d-m-Y');
            }
        }

        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => date('d-m-Y', strtotime($dfrom)),
            'dto'           => date('d-m-Y', strtotime($dto)),
            'supplier'      => $supplier,
            'ceksup'        => $this->mmaster->cek_supplier($dfrom,$dto)->result(),
        );

        $this->Logger->write('Membuka Data '.$this->global['title'].' '.$supplier.' Tanggal : '.$dfrom.' Sampai '.$dto);

        $this->load->view($this->global['folder'].'/vformview', $data);
    }

    /*----------  LIST DATA FAKTUR PEMBELIAN  ----------*/
    
    public function data()
    {
        $supplier  = $this->input->post('supplier');
        if ($supplier=='') {
            $supplier = $this->uri->segment(4);
        }
        $dfrom  = $this->input->post('dfrom');
        if ($dfrom=='') {
            $dfrom = $this->uri->segment(5);
        }
        $dto  = $this->input->post('dto');
        if ($dto=='') {
            $dto = $this->uri->segment(6);
        }

        echo $this->mmaster->data($supplier,$dfrom,$dto,$this->i_menu,$this->global['folder']);
    }

    /*----------  EXPORT CSV  ----------*/
    
    public function exportcsv()
    {
        $data = check_role($this->i_menu, 6);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $supplier = ($this->input->post('supplier',TRUE) != '' ? $this->input->post('supplier',TRUE) : $this->uri->segment(4));
        $dfrom    = ($this->input->post('dfrom',TRUE) != '' ? $this->input->post('dfrom',TRUE) : $this->uri->segment(5));
        $dto      = ($this->input->post('dto',TRUE) != '' ? $this->input->post('dto',TRUE) : $this->uri->segment(6));

        $query    = $this->mmaster->exportdata($supplier,$dfrom,$dto);

        if ($query->num_rows()>0) {
            $spreadsheet  = new Spreadsheet;
            $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'FM')
            ->setCellValue('B1', 'KD_JENIS_TRANSAKSI')
            ->setCellValue('C1', 'FG_PENGGANTI')
            ->setCellValue('D1', 'NOMOR_FAKTUR')
            ->setCellValue('E1', 'MASA_PAJAK')
            ->setCellValue('F1', 'TAHUN_PAJAK')
            ->setCellValue('G1', 'TANGGAL_FAKTUR')
            ->setCellValue('H1', 'NPWP')
            ->setCellValue('I1', 'NAMA')
            ->setCellValue('J1', 'ALAMAT_LENGKAP')
            ->setCellValue('K1', 'JUMLAH_DPP')
            ->setCellValue('L1', 'JUMLAH_PPN')
            ->setCellValue('M1', 'JUMLAH_PPNBM')
            ->setCellValue('N1', 'IS_CREDITABLE');

            $kolom = 2;
            foreach($query->result() as $row) {
                $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $kolom, $row->fm)
                ->setCellValue('B' . $kolom, $row->kode_jt)
                ->setCellValue('C' . $kolom, $row->fg)
                ->setCellValue('D' . $kolom, $row->i_pajak)
                ->setCellValue('E' . $kolom, $row->masa_pajak)
                ->setCellValue('F' . $kolom, $row->tahun_pajak)
                ->setCellValue('G' . $kolom, $row->tanggal_faktur)
                ->setCellValue('H' . $kolom, str_replace('.', '', $row->npwp))
                ->setCellValue('I' . $kolom, $row->e_npwp_name)
                ->setCellValue('J' . $kolom, $row->e_npwp_address)
                ->setCellValue('K' . $kolom, $row->jumlah_dpp)
                ->setCellValue('L' . $kolom, $row->jumlah_ppn)
                ->setCellValue('M' . $kolom, $row->jumlah_ppnbm)
                ->setCellValue('N' . $kolom, $row->is_creditable);
                $kolom++;
            }

            $writer = new Csv($spreadsheet);
            $nama_file = "Pajak_Masukan_".str_replace("-", "", $dfrom)."_".str_replace("-", "", $dto).".csv";
            /*header('Content-Type: application/vnd.ms-excel');*/
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment;filename='.$nama_file.'');
            header('Cache-Control: max-age=0');
            ob_end_clean();
            ob_start();
            $writer->save('php://output');
        }else{
            echo '<script>
            swal({
                title: "Maaf :(",
                text: "Distributor Tidak Boleh Beda!",
                showConfirmButton: true,
                type: "error",
                },function(){
                    show("'.$this->global['folder'].'/cform/index/'.$supplier.'/'.$dfrom.'/'.$dto.'","#main");
            });
            </script>';
        }
    }
}
/* End of file Cform.php */