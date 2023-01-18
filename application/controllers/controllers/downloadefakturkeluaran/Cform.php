<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '20710';

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

        $this->load->model($this->global['folder'].'/mmaster');
    }   

    public function index(){
        $d = new DateTime();
        $one_month = new DateInterval('P1M');
        $one_month_ago = new DateTime();
        $one_month_ago->sub($one_month);

        // Output the microseconds.

        $awal  = $one_month_ago->format('d-m-Y');
        $blalu = $one_month_ago->format('m');
        $tlalu = $one_month_ago->format('Y');

        $akhir = $d->format('d-m-Y');
        $bnow  = $d->format('m');
        $tnow  = $d->format('Y');

        //echo $blalu."'".$tlalu."'".$awal."'".$akhir."'".$bnow."'".$tnow;

        $data = array(
            'folder' => $this->global['folder'],
            'title' => "Tambah ".$this->global['title'],
            'title_list' => 'List '.$this->global['title'],
            // 'blalu'         => $blalu,
            // 'tlalu'         => $tlalu,
            'dfrom'         => $awal,
            'dto'           => $akhir,
            'bnow'          => $bnow,
            'tnow'          => $tnow,
        );

        $this->Logger->write('Membuka Menu '.$this->global['title']);

        $this->load->view($this->global['folder'].'/vformmain', $data);
    }

    function data(){
       // $pbulan  = $this->input->post('pbulan');
       // $ptahun  = $this->input->post('ptahun');

       //  if($pbulan==''){
       //      $pbulan=$this->uri->segment(4);
       //  }
       //  if($ptahun==''){
       //      $ptahun=$this->uri->segment(5);
       //  }
       //  $datenow = "01-".$pbulan."-".$ptahun;
       //  $d = new DateTime($datenow);
       //  $one_month = new DateInterval('P1M');
       //  $one_month_ago = new DateTime($datenow);
       //  $one_month_ago->sub($one_month);

       //  $awal  = $d->format('01-m-Y');
       //  $akhir = $d->format('t-m-Y');

       //  $blalu = $one_month_ago->format('m');
       //  $tlalu = $one_month_ago->format('Y');

       //  $bnow  = $d->format('m');
       //  $tnow  = $d->format('Y');


        $dto=$this->uri->segment(4);
        $jenis=$this->uri->segment(5);
        $partner=$this->uri->segment(6);
        
        // var_dump($dfrom, $dto, $partner);
        // die();

        // $from = explode("-", $dfrom);
        // $tgl= $from[0];
        // $bln= $from[1];
        // $thn= $from[2];

        // $to = explode("-", $dto);
        // $tgl2= $to[0];
        // $bln2= $to[1];
        // $thn2= $to[2];


        //$datenow = "01-".$pbulan."-".$ptahun;
        // $d = new DateTime($dto);
        // $one_month = new DateInterval('P1M');
        // $one_month_ago = new DateTime($dfrom);
        // $one_month_ago->sub($one_month);

        // // $awal  = $d->format('01-m-Y');
        // // $akhir = $d->format('d-m-Y');
        // $blalu = $one_month_ago->format('m');
        // $tlalu = $one_month_ago->format('Y');
        // $bnow  = $d->format('m');
        // $tnow  = $d->format('Y');


        // $one_day = new DateInterval('P1D');
        // $one_day_ago = new DateTime($dfrom);
        // $one_day_ago->sub($one_day);

        // $djangka = new DateTime($dfrom);
        // $dawal  = $djangka->format('01-m-Y');
        // $dlalu_j = $one_day_ago->format('d');
        // $blalu_j = $one_day_ago->format('m');
        // $tlalu_j = $one_day_ago->format('Y');

        // $dakhir = $dlalu_j."-".$blalu_j."-".$tlalu_j;
        // $partner2;
        // if ($dawal == $dfrom) {
        //     $partner2 = "xx";
        // } else {
        //     $partner2 = $partner;
        // }

        
        //var_dump($blalu."'".$tlalu."'".$dfrom."'".$dto."'".$bnow."'".$tnow."'".$partner."'".$dawal."'".$dakhir."'".$partner2);
        //die();
        echo $this->mmaster->data($dto,$jenis,$partner);   
    }

    public function partner() {
        $filter = [];
        $cari   = $this->input->get('q');
        $data   = $this->mmaster->partner($cari,$this->input->get('jenis'));
        foreach($data->result() as $key){
            $filter[] = array(
                'id'   => $key->i_customer,  
                'text' => $key->i_customer. " - ".$key->e_customer_name. " - ".$key->e_customer_npwp,
            );
        }          
        echo json_encode($filter);
    }

     public function export(){
        // $data = check_role($this->i_menu, 2);
        // if(!$data){
        //     redirect(base_url(),'refresh');
        // }
        
        $dfrom = ($this->input->post('dfrom',TRUE) != '' ? $this->input->post('dfrom',TRUE) : $this->uri->segment(4));
        $dto = ($this->input->post('dto',TRUE) != '' ? $this->input->post('dto',TRUE) : $this->uri->segment(5));
        $partner = ($this->input->post('partner',TRUE) != '' ? $this->input->post('partner',TRUE) : $this->uri->segment(6));
        // $query = $this->mmaster->cek_datadet($dso, $partner)->result();
        // $epartner = $this->mmaster->getpartnerbyid($partner)->e_unitjahit_name;
        $now = gmdate("D, d M Y H:i:s");
        $filename = "efaktur(".$dfrom."_".$dto."_".$partner.").csv";
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    
        //$out = fopen('php://output', 'w');

        $out = fopen('php://output', 'w');
        //$out    = fopen('export/pajakkeluaran/'.$filename,'w');
        fputcsv($out, array("FK","KD_JENIS_TRANSAKSI", "FG_PENGGANTI", "NOMOR_FAKTUR", "MASA_PAJAK", "TAHUN_PAJAK", "TANGGAL_FAKTUR", "NPWP", "NAMA", "ALAMAT_LENGKAP", "JUMLAH_DPP", "JUMLAH_PPN", "JUMLAH_PPNMBM", "ID_KETERANGAN_TAMBAHAN", "FG_UANG_MUKA", "UANG_MUKA_DPP", "UANG_MUKA_PPN", "UANG_MUKA_PPNBM", "REFERENSI"));
        fputcsv($out, array("LT","NPWP", "NAMA", "JALAN", "BLOK", "NOMOR", "RT", "RW", "KECAMATAN", "KELURAHAN", "KABUPATEN", "PROPINSI", "KODE_POS", "NOMOR_TELEPON"));
        fputcsv($out, array("OF","KODE_OBJEK", "NAMA", "HARGA_SATUAN", "JUMLAH_BARANG", "HARGA_TOTAL", "DISKON", "DPP", "PPN", "TARIF_PPNBM", "PPNBM"));
        fclose($out);

        //var_dump($out);
        die();
    }
    
}
/* End of file Cform.php */