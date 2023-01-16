<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cform extends CI_Controller
{

    public $global = array();
    public $i_menu = '107011902';
 
    public function __construct()
    {
        parent::__construct();
        cek_session();
     
        $data = check_role($this->i_menu, 2);
        if (!$data) {
            redirect(base_url(), 'refresh');
        }
     
        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
     
        $this->load->model($this->global['folder'] . '/mmaster');
    }
   
    public function index()
    {
      $data = array(
         'folder'          => $this->global['folder'],
         'title'           => $this->global['title'],
         'bulan'     		=> date('m'),
         'tahun'     		=> date('Y')
     );
     $this->Logger->write('Membuka Menu '.$this->global['title']);
     $this->load->view($this->global['folder'].'/vform', $data);

    }

    function view()
    {
      $username   = $this->session->userdata('username');
      #$idcompany  = $this->session->userdata('id_company');
      $bulan      = $this->uri->segment(4);
      $tahun      = $this->uri->segment(5);
      $iperiode   = $tahun.$bulan;

        $bln   = substr($iperiode,4,2);
        $thn   = substr($iperiode,0,4);
        $akhir = '';
        
        $data = array(
         'folder'        => $this->global['folder'],
         'title'         => "View ".$this->global['title'],
         'title_list'    => 'List '.$this->global['title'],
         'iperiode'      => $iperiode,
         'akhir'         => $akhir,
         'isi'           => $this->mmaster->baca($iperiode,$username)->result()
        );
        $this->Logger->write('Membuka Menu View '.$this->global['title']);
  
        $this->load->view($this->global['folder'].'/vformviewnew', $data);
    }
   
   function detail()
   {
         $iperiode = $this->uri->segment(4);
         $iarea    = $this->uri->segment(5);

         $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title']." (Detail Nota)",
            'title_list'    => 'List '.$this->global['title'],
            'iperiode'      => $iperiode,
            'iarea'         => $iarea,
            'detail'        => $this->mmaster->detail($iarea,$iperiode)->result()
           );
         $this->Logger->write('Membuka Menu View '.$this->global['title']);
   
         $this->load->view($this->global['folder'].'/vformdetailnew', $data);
   }
   function sales()
   {
      $iperiode = $this->uri->segment(4);
      $iarea    = $this->uri->segment(5);

      $data = array(
         'folder'        => $this->global['folder'],
         'title'         => "View ".$this->global['title']." (Detail Sales)",
         'title_list'    => 'List '.$this->global['title'],
         'iperiode'      => $iperiode,
         'iarea'         => $iarea,
         'detail'        => '',
         'sales'         => $this->mmaster->detailsales($iarea,$iperiode)->result()
        );
      $this->Logger->write('Membuka Menu View '.$this->global['title']);

      $this->load->view($this->global['folder'].'/vformdetailsalesnew', $data);
   }
   function divisi()
   {
      $iperiode = $this->uri->segment(4);
      $iarea    = $this->uri->segment(5);

      $data = array(
         'folder'        => $this->global['folder'],
         'title'         => "View ".$this->global['title']." (Detail Divisi)",
         'title_list'    => 'List '.$this->global['title'],
         'iperiode'      => $iperiode,
         'iarea'         => $iarea,
         'detail'        => '',
         'divisi'         => $this->mmaster->detaildivisi($iarea,$iperiode)->result()
        );
      $this->Logger->write('Membuka Menu View '.$this->global['title']);

      $this->load->view($this->global['folder'].'/vformdetaildivisinew', $data);
   }
   function cetakdetail()
   {
      if (
         (($this->session->userdata('logged_in')) &&
         ($this->session->userdata('menu337')=='t')) ||
         (($this->session->userdata('logged_in')) &&
         ($this->session->userdata('allmenu')=='t'))
         ){
      $this->load->model('listtargetcollectionrealtime/mmaster');
      $iperiode = $this->uri->segment(4);
      $iarea    = $this->uri->segment(5);
         $this->load->model('listtargetcollectionrealtime/mmaster');
         $data['page_title'] = $this->lang->line('listtargetcollectionrealtime');
         $data['iperiode']   = $iperiode;
         $data['iarea']     = $iarea;
         $data['detail']       = $this->mmaster->detailcetak($iarea,$iperiode);

         $sess=$this->session->userdata('session_id');
         $id=$this->session->userdata('user_id');
         $sql  = "select * from dgu_session where session_id='$sess' and not user_data isnull";
         $rs      = pg_query($sql);
         if(pg_num_rows($rs)>0){
            while($row=pg_fetch_assoc($rs)){
               $ip_address   = $row['ip_address'];
               break;
            }
         }else{
            $ip_address='kosong';
         }
         $query   = pg_query("SELECT current_timestamp as c");
       while($row=pg_fetch_assoc($query)){
         $now    = $row['c'];
         }
         $pesan='Cetak Detail Target Collection Credit Periode '.$iperiode.' Area:'.$iarea;
         $this->load->model('logger');
         $this->logger->write($id, $ip_address, $now , $pesan );

         $this->load->view('listtargetcollectionrealtime/vformprintdetail',$data);

      }else{
         $this->load->view('awal/index.php');
      }
   }
   function cetaksales()
   {
      if (
         (($this->session->userdata('logged_in')) &&
         ($this->session->userdata('menu337')=='t')) ||
         (($this->session->userdata('logged_in')) &&
         ($this->session->userdata('allmenu')=='t'))
         ){
      $iperiode = $this->uri->segment(4);
      $iarea = $this->uri->segment(5);
         $this->load->model('listtargetcollectionrealtime/mmaster');
         $data['page_title'] = $this->lang->line('listtargetcollectionrealtime');
         $data['iperiode']   = $iperiode;
         $data['iarea']     = $iarea;
         $data['detail']       = '';
         $data['sales']     = $this->mmaster->cetaksales($iarea,$iperiode);

         $sess=$this->session->userdata('session_id');
         $id=$this->session->userdata('user_id');
         $sql  = "select * from dgu_session where session_id='$sess' and not user_data isnull";
         $rs      = pg_query($sql);
         if(pg_num_rows($rs)>0){
            while($row=pg_fetch_assoc($rs)){
               $ip_address   = $row['ip_address'];
               break;
            }
         }else{
            $ip_address='kosong';
         }
         $query   = pg_query("SELECT current_timestamp as c");
       while($row=pg_fetch_assoc($query)){
         $now    = $row['c'];
         }
         $pesan='Cetak Target Collection Credit Sales Periode '.$iperiode;
         $this->load->model('logger');
         $this->logger->write($id, $ip_address, $now , $pesan );

         $this->load->view('listtargetcollectionrealtime/vformprintsales',$data);

      }else{
         $this->load->view('awal/index.php');
      }
   }
   function export()
   {
      if (
         (($this->session->userdata('logged_in')) &&
         ($this->session->userdata('menu219')=='t')) ||
         (($this->session->userdata('logged_in')) &&
         ($this->session->userdata('allmenu')=='t'))
         ){
         $this->load->model('listtargetcollectionrealtime/mmaster');
         $cari    = strtoupper($this->input->post('cari'));
         $iperiode   = $this->input->post('pperiode');
      $iarea = $this->input->post('iarea');
         if($iperiode==''){
        $iperiode=$this->uri->segment(4);
      }
         if($iarea=='') $iarea=$this->uri->segment(5);
      $query = $this->db->query("select i_store from tr_area where i_area='$iarea'");
      $st=$query->row();
      $store=$st->i_store;
#      $query=$this->mmaster->bacaexcel($iperiode,$store,$cari);
      $this->db->select("  a.*, b.e_product_name from tm_mutasi a, tr_product b
                                where e_mutasi_periode = '$iperiode' and a.i_product=b.i_product
                                and i_store='$store' order by b.e_product_name ",false);#->limit($num,$offset);
        $query = $this->db->get();
         $this->load->library('PHPExcel');
         $this->load->library('PHPExcel/IOFactory');
         $objPHPExcel = new PHPExcel();
         $objPHPExcel->getProperties()->setTitle("Laporan Mutasi ")->setDescription("PT. Dialogue Garmindo Utama");
         $objPHPExcel->setActiveSheetIndex(0);
         if ($query->num_rows() > 0){
            $objPHPExcel->getActiveSheet()->duplicateStyleArray(
            array(
               'font' => array(
                  'name'   => 'Arial',
                  'bold'  => true,
                  'italic'=> false,
                  'size'  => 12
               ),
               'alignment' => array(
                  'horizontal'=> Style_Alignment::HORIZONTAL_LEFT,
                  'vertical'  => Style_Alignment::VERTICAL_CENTER,
                  'wrap'      => true
               )
            ),
            'A2:A4'
            );
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(6);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(6);

            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'LAPORAN MUTASI STOK GUDANG REGULER');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0,2,11,2);
            $objPHPExcel->getActiveSheet()->setCellValue('A3', 'Area :                     Bulan :                    Hal :');
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(0,3,11,3);
            $objPHPExcel->getActiveSheet()->setCellValue('A6', 'No');
            $objPHPExcel->getActiveSheet()->getStyle('A6')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  )

               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Kode');
            $objPHPExcel->getActiveSheet()->getStyle('B6')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('C6', 'Nama');
            $objPHPExcel->getActiveSheet()->getStyle('C6')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('D6', 'Saldo');
            $objPHPExcel->getActiveSheet()->getStyle('D6')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('E6', 'PENERIMAAN');
            $objPHPExcel->getActiveSheet()->getStyle('E6')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->mergeCellsByColumnAndRow(5,6,6,6);
            $objPHPExcel->getActiveSheet()->setCellValue('F6', 'PENGELUARAN');
            $objPHPExcel->getActiveSheet()->getStyle('F6')->applyFromArray(

               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  )
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('H6', 'Saldo');
            $objPHPExcel->getActiveSheet()->getStyle('H6')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('I6', 'Stok');
            $objPHPExcel->getActiveSheet()->getStyle('I6')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('J6', '+/-');
            $objPHPExcel->getActiveSheet()->getStyle('J6')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
        $objPHPExcel->getActiveSheet()->setCellValue('A7', 'Urut');
            $objPHPExcel->getActiveSheet()->getStyle('A7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  )

               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('B7', 'Barang');
            $objPHPExcel->getActiveSheet()->getStyle('B7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('C7', 'Barang');
            $objPHPExcel->getActiveSheet()->getStyle('C7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('D7', 'Awal');
            $objPHPExcel->getActiveSheet()->getStyle('D7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('E7', 'd/Pusat');
            $objPHPExcel->getActiveSheet()->getStyle('E7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('F7', 'Penj.');
            $objPHPExcel->getActiveSheet()->getStyle('F7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  )
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('G7', 'KePusat');
            $objPHPExcel->getActiveSheet()->getStyle('G7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  )
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('H7', 'Akhir');
            $objPHPExcel->getActiveSheet()->getStyle('H7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('I7', 'Opnam');
            $objPHPExcel->getActiveSheet()->getStyle('I7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $objPHPExcel->getActiveSheet()->setCellValue('J7', '( pc )');
            $objPHPExcel->getActiveSheet()->getStyle('J7')->applyFromArray(
               array(
                  'borders' => array(
                     'top'    => array('style' => Style_Border::BORDER_THIN),
                     'bottom'=> array('style' => Style_Border::BORDER_THIN),
                     'left'  => array('style' => Style_Border::BORDER_THIN),
                     'right' => array('style' => Style_Border::BORDER_THIN)
                  ),
               )
            );
            $i=7;
            $j=7;
            $xarea='';
            $saldo=0;
        $no=0;
            foreach($query->result() as $row){
          $no++;
          $selisih=$row->n_saldo_stockopname-$row->n_saldo_akhir;
               $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $no);
               $objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $row->i_product);
               $objPHPExcel->getActiveSheet()->getStyle('B'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $row->e_product_name);
               $objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $row->n_saldo_awal);
               $objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('E'.$i, $row->n_mutasi_bbm);
               $objPHPExcel->getActiveSheet()->getStyle('E'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $row->n_mutasi_penjualan);
               $objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('G'.$i, $row->n_mutasi_bbk);
               $objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('H'.$i, $row->n_saldo_akhir);
               $objPHPExcel->getActiveSheet()->getStyle('H'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $row->n_saldo_stockopname);
               $objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $selisih);
               $objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray(
                  array(
                     'borders' => array(
                        'top'    => array('style' => Style_Border::BORDER_THIN),
                        'bottom'=> array('style' => Style_Border::BORDER_THIN),
                        'left'  => array('style' => Style_Border::BORDER_THIN),
                        'right' => array('style' => Style_Border::BORDER_THIN)
                     ),
                  )
               );
               $i++;
               $j++;
            }
            $x=$i-1;
            $objPHPExcel->getActiveSheet()->getStyle('G7:J'.$x)->getNumberFormat()->setFormatCode(Style_NumberFormat::FORMAT_TEXT_COA);
         }
         $objPHPExcel->getActiveSheet()->getStyle('A6:I6')->getFill()->setFillType(Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
         $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
      $nama='SO-'.$iperiode.'.xls';
         $objWriter->save("excel/".$iarea.'/'.$nama);
         $data['sukses'] = true;
         $data['inomor']   = "Laporan Mutasi Stok Reguler";
         $this->load->view('nomor',$data);
      }else{
         $this->load->view('awal/index.php');
      }
   }

  function fcf(){
    $iperiode=$this->uri->segment(4);
    $tipe=$this->uri->segment(5);
    if($tipe==''){
      $graph_swfFile      = base_url().'flash/FCF_MSColumn3D.swf';
    }else{
      $tipe=str_replace("tandatitik",".",$tipe);
      $graph_swfFile      = base_url().'flash/'.$tipe;
    }
    $th=substr($iperiode,0,4);
    $bl=substr($iperiode,4,2);
    $bl=mbulan($bl);
    $graph_caption      = 'Target Collection Credit Per Area Periode : '.$bl.' '.$th ;
    $graph_numberPrefix = 'Rp.' ;
    $graph_title        = 'Target Collection Credit' ;
    $graph_width        = 954;
    $graph_height       = 500;
    $this->load->model('listtargetcollectionrealtime/mmaster');

    // Area
    $i=0;
    $result = $this->mmaster->bacaareas($iperiode);
    foreach($result as $row){
      $category[$i] = $row->i_area;
      $i++;
    }

    // data set
    $dataset[0] = 'Target' ;
    $dataset[1] = 'Realisasi' ;
#    $dataset[2] = 'SJ' ;
#    $dataset[3] = 'Nota' ;

    //data 1
    $i=0;
    $result = $this->mmaster->baca($iperiode);
    foreach($result as $row){
      $arrData['Target'][$i] = intval($row->total);
      $arrData['Realisasi'][$i] = intval($row->realisasi);
      $i++;
    }

    //data 2
#    $i=0;
#    $result = $this->mmaster->bacaspb($iperiode);
#    foreach($result as $row){
#      $arrData['Realisasi'][$i] = intval($row->v_spb_gross);
#      $i++;
#    }

    //data 3
#    $i=0;
#    $result = $this->mmaster->bacasj($iperiode);
#    foreach($result as $row){
#      $arrData['SJ'][$i] = intval($row->v_sj_gross);
#      $i++;
#    }


    //data 4
#    $i=0;
#    $result = $this->mmaster->bacanota($iperiode);
#    foreach($result as $row){
#      $arrData['Nota'][$i] = intval($row->v_nota_gross);
#      $i++;
#    }

    $strXML = "<graph hovercapbg='DEDEBE' hovercapborder='889E6D' rotateNames='0' yAxisMaxValue='100' numdivlines='9' divLineColor='CCCCCC' divLineAlpha='80' decimalPrecision='0' showAlternateHGridColor='1' AlternateHGridAlpha='30' AlternateHGridColor='CCCCCC' caption='".$graph_caption."' numberPrefix='".$graph_numberPrefix."' showValues='0'>" ;

    //Convert category to XML and append
    $strXML .= "<categories font='Arial' fontSize='11' fontColor='000000'>" ;
    foreach ($category as $c) {
        $strXML .= "<category name='".$c."'/>" ;
    }
    $strXML .= "</categories>" ;

#    echo 'hiji = '.$strXML.'<br><br>';

    //Convert dataset and data to XML and append
    foreach ($dataset as $set) {
        $strXML .= "<dataset seriesname='".$set."' color='".  getFCColor()."'>" ;
        foreach ($arrData[$set] as $d) {
            $strXML .= "<set value='".$d."'/>" ;
        }
        $strXML .= "</dataset>" ;
    }

#    echo 'dua = '.$strXML.'<br><br>';

//Close <chart> element
$strXML .= "</graph>";

    $data['graph']  = renderChart($graph_swfFile, $graph_title, $strXML, "div" , $graph_width, $graph_height);
    $data['iperiode']=$iperiode;
    $data['modul']='listtargetcollectionrealtime';
    $data['isi']= directory_map('./flash/');
    $data['file']='';

#    echo 'tilu = '.$strXML.'<br><br>';

    $this->load->view('listtargetcollectionrealtime/chart_view',$data) ;
  }

   function all()
   {
      if (
         (($this->session->userdata('logged_in')) &&
         ($this->session->userdata('menu337')=='t')) ||
         (($this->session->userdata('logged_in')) &&
         ($this->session->userdata('allmenu')=='t'))
         ){
         $iperiode = $this->uri->segment(4);
         $this->load->model('listtargetcollectionrealtime/mmaster');
         $data['page_title'] = $this->lang->line('listtargetcollectionrealtime');
         $data['iperiode']   = $iperiode;
         $data['detail']       = '';
         $data['all']     = $this->mmaster->detailall($iperiode);

         $this->load->view('listtargetcollectionrealtime/vmainform',$data);

      }else{
         $this->load->view('awal/index.php');
      }
   }
}
?>