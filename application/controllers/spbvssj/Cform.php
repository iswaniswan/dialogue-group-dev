<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '107020211';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title']  = $data[0]['e_menu'];
        $this->load->library('fungsi');
        $this->load->helper('form');
        $this->load->model($this->global['folder'].'/mmaster');
    }
    

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => "Info ".$this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformmain', $data);
    }
    
    public function view(){
        $dfrom  = $this->input->post('dfrom');
        $dto    = $this->input->post('dto');
        $is_groupbrg = $this->input->post('is_groupbrg');
        $userid      = $this->session->userdata('username');
        if($dfrom==''){
            $dfrom=$this->uri->segment(4);
        }
        if($dto==''){
            $dto=$this->uri->segment(5);
        }
        if ($is_groupbrg == '2'){
            $data = array(
                'folder'        => $this->global['folder'],
                'title'         => "View ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'is_groupbrg'   => $is_groupbrg,
                'userid'        => $userid,
                'isi'           => $this->mmaster->bacaperiode($userid,$dfrom,$dto,$is_groupbrg),
                'datagrup'      => ''
            );
         }else{
            // ambil grup brg dari tr_product_group, relasi ke tr_product_type
            $queryxx = $this->db->query(" 
                                 SELECT 
                                    i_product_group, 
                                    e_product_groupname 
                                 FROM 
                                    tr_product_group 
                                 ORDER BY 
                                    i_product_group 
                                 ");
            if ($queryxx->num_rows() > 0){
               $datagrup = array();
               $hasilxx=$queryxx->result();
               foreach ($hasilxx as $rowxx) {
                  $i_product_group = $rowxx->i_product_group;
                  $e_product_groupname = $rowxx->e_product_groupname;
                  $datagrup[] = array( 
                                 'i_product_group'=> $i_product_group,
                                 'e_product_groupname'=> $e_product_groupname,
                                 'totalspb'=> 0,
                                 'totalsj'=> 0,
                                 'totalnota'=> 0
                              );
               }
            }
            $data = array(
                'folder'        => $this->global['folder'],
                'title'         => "View ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'is_groupbrg'   => $is_groupbrg,
                'userid'        => $userid,
                'isi'           => $this->mmaster->bacaperiode($userid,$dfrom,$dto,$is_groupbrg),
                'datagrup'      => $datagrup
            );
         }
        if ($is_groupbrg == '2'){
            $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$dfrom.$dto);

            $this->load->view($this->global['folder'].'/vformview', $data);
        }else{
            $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$dfrom.$dto);

            $this->load->view($this->global['folder'].'/vformviewgroup', $data);
        }
    }

    public function detailpersales(){
        $iarea          = $this->uri->segment(4);
		$dfrom          = $this->uri->segment(5);
		$dto            = $this->uri->segment(6);
        $is_groupbrg    = $this->uri->segment(7);

        $queryxx = $this->db->query(" 
                            SELECT 
                                e_area_name 
                            FROM 
                                tr_area 
                            WHERE 
                                i_area = '".$iarea."' 
                            ");
		if ($queryxx->num_rows() > 0){
			$hasilxx = $queryxx->row();
			$namaarea = $hasilxx->e_area_name;
		}
        
        if ($is_groupbrg == '2'){
            $data = array(
                'folder'        => $this->global['folder'],
                'title'         => "View ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'iarea'         => $iarea,
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'iarea'         => $iarea,
                'is_groupbrg'   => $is_groupbrg,
                'namaarea'      => $namaarea,
                'isi'           => $this->mmaster->bacapersales($iarea,$dfrom,$dto,$is_groupbrg),
                'datagrup'      => ''
            );
        }else{
            // ambil grup brg dari tr_product_group, relasi ke tr_product_type
            $queryxx = $this->db->query(" 
                                 SELECT 
                                    i_product_group, 
                                    e_product_groupname 
                                 FROM 
                                    tr_product_group 
                                 ORDER BY 
                                    i_product_group 
                                 ");
            if ($queryxx->num_rows() > 0){
               $datagrup = array();
               $hasilxx=$queryxx->result();
               foreach ($hasilxx as $rowxx) {
                  $i_product_group = $rowxx->i_product_group;
                  $e_product_groupname = $rowxx->e_product_groupname;
                  $datagrup[] = array( 
                                 'i_product_group'=> $i_product_group,
                                 'e_product_groupname'=> $e_product_groupname,
                                 'totalspb'=> 0,
                                 'totalsj'=> 0,
                                 'totalnota'=> 0
                              );
               }
            }
            $data = array(
                'folder'        => $this->global['folder'],
                'title'         => "View ".$this->global['title'],
                'title_list'    => 'List '.$this->global['title'],
                'dfrom'         => $dfrom,
                'dto'           => $dto,
                'iarea'         => $iarea,
                'is_groupbrg'   => $is_groupbrg,
                'namaarea'      => $namaarea,
                'isi'           => $this->mmaster->bacapersales($iarea,$dfrom,$dto,$is_groupbrg),
                'datagrup'      => $datagrup
            );
        }

        if ($is_groupbrg == '2'){
            $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$dfrom.$dto);

            $this->load->view($this->global['folder'].'/vdetailpersales', $data);
        }else{
            $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$dfrom.$dto);

            $this->load->view($this->global['folder'].'/vdetailsalespergrup', $data);
        }
    }

    public function detailperdata(){
        $isalesman = $this->uri->segment(4);
		$dfrom = $this->uri->segment(5);
		$dto = $this->uri->segment(6);
        $iarea = $this->uri->segment(7);
        
        // query ambil nama area
		$queryxx = $this->db->query(" 
                            SELECT 
                                e_area_name 
                            FROM 
                                tr_area 
                            WHERE 
                                i_area = '".$iarea."' 
                            ");
		if ($queryxx->num_rows() > 0){
			$hasilxx = $queryxx->row();
			$namaarea = $hasilxx->e_area_name;
		}else{
            $namaarea = '';
        }
		
		// query ambil nama salesman
		$queryxx = $this->db->query(" 
                            SELECT 
                                e_salesman_name 
                            FROM 
                                tr_salesman 
                            WHERE 
                                i_salesman = '".$isalesman."' 
                            ");
		if ($queryxx->num_rows() > 0){
			$hasilxx = $queryxx->row();
			$namasalesman = $hasilxx->e_salesman_name;
		}else{
            $namasalesman = '';
        }

        $listdata = array();
		$sqlnya	= $this->db->query(" 
                                SELECT 
                                    a.i_spb, 
                                    a.d_spb, 
                                    a.i_sj, 
                                    a.d_sj, 
                                    a.i_nota, 
                                    a.d_nota, 
                                    a.v_nota_netto, 
                                    (a.d_sj)-(a.d_spb) AS selisihsj, 
                                    (a.d_nota)-(a.d_sj) AS selisihnota,
                                    a.i_customer, 
                                    b.e_customer_name 
                                FROM 
                                    tm_nota a, 
                                    tr_customer b 
                                WHERE 
                                    a.i_customer = b.i_customer 
                                    AND ((a.d_spb >= to_date('$dfrom','dd-mm-yyyy') 
                                    AND a.d_spb <= to_date('$dto','dd-mm-yyyy')) 
                                    OR(a.d_sj >= to_date('$dfrom','dd-mm-yyyy') 
                                    AND a.d_sj <= to_date('$dto','dd-mm-yyyy')) 
                                    OR (a.d_nota >= to_date('$dfrom','dd-mm-yyyy') 
									AND a.d_nota <= to_date('$dto','dd-mm-yyyy')))
                                    AND a.i_area = '".$iarea."' 
                                    AND a.i_salesman = '".$isalesman."' 
                                    AND a.f_nota_cancel='f'
                                ORDER BY
                                    a.d_spb DESC, 
                                    a.i_spb DESC 
                                ");
        if ($sqlnya->num_rows() > 0){
            $hasilnya=$sqlnya->result();
            foreach ($hasilnya as $rownya) {
                $ispb = $rownya->i_spb;
                $dspb = $rownya->d_spb;
                $isj = $rownya->i_sj;
                $dsj = $rownya->d_sj;
                $inota = $rownya->i_nota;
                $dnota = $rownya->d_nota;
                $selisihsj = $rownya->selisihsj;
                $selisihnota = $rownya->selisihnota;
                $vnotagross = $rownya->v_nota_netto;
                $icustomer = $rownya->i_customer;
                $ecustomername = $rownya->e_customer_name;
                
                $listdata[] = array(		'ispb'=> $ispb,	
                                        'dspb'=> $dspb,	
                                        'isj'=> $isj,	
                                        'dsj'=> $dsj,	
                                        'inota'=> $inota,	
                                        'dnota'=> $dnota,
                                        'selisihsj'=> $selisihsj,
                                        'selisihnota'=> $selisihnota,	
                                        'vnotagross'=> $vnotagross,	
                                        'icustomer'=> $icustomer,	
                                        'ecustomername'=> $ecustomername
                                        );
            }
        }else{
            $listdata = '';
        }
        $data = array(
            'folder'        => $this->global['folder'],
            'title'         => "View ".$this->global['title'],
            'title_list'    => 'List '.$this->global['title'],
            'dfrom'         => $dfrom,
            'dto'           => $dto,
            'iarea'         => $iarea,
            'isalesman'     => $isalesman,
            'namasalesman'  => $namasalesman,
            'listdata'      => $listdata,
            'namaarea'      => $namaarea,
        );
        $this->Logger->write('Membuka Data '.$this->global['title'].' Periode : '.$dfrom.$dto);

        $this->load->view($this->global['folder'].'/vdetailperdata', $data);
    }

    public function export_excel(){
        $userid         = $this->session->userdata('username');
		$dfrom		    = $this->input->post('dfrom');
		$dto		    = $this->input->post('dto');
        $is_groupbrg	= $this->input->post('is_groupbrg');
        
        $queryxx = $this->db->query(" 
                                SELECT 
                                    i_product_group, 
                                    e_product_groupname 
                                FROM 
                                    tr_product_group 
                                ORDER BY
                                    i_product_group 
                                ");
		if ($queryxx->num_rows() > 0){
			$datagrup = array();
			$hasilxx=$queryxx->result();
			foreach ($hasilxx as $rowxx) {
				$i_product_group = $rowxx->i_product_group;
				$e_product_groupname = $rowxx->e_product_groupname;
				$datagrup[] = array( 
									'i_product_group'=> $i_product_group,
									'e_product_groupname'=> $e_product_groupname,
									'totalspb'=> 0,
									'totalsj'=> 0,
									'totalnota'=> 0
							        );
			}
        }
        $this->load->model('spbvssj/mmaster');
			$query = $this->mmaster->bacaperiode($userid,$dfrom,$dto,$is_groupbrg,"all");
			
			$html_data = "<table border='1' cellpadding= '1' cellspacing = '1' width='100%'>
				 <tr>
					<th colspan='4' align='center'>LAPORAN SPB VS SJ</th>
				 </tr>
				 <tr>
					<th colspan='4' align='center'>Periode: $dfrom s.d $dto</th>
				 </tr></table><br>";
			
			if ($is_groupbrg == '2') { // yg biasa
				$html_data .= "<table border='1' cellpadding= '1' cellspacing = '1' width='100%'>
					<tr>
						<td>Area</td>
						<td>Nilai SPB</td>
						<td>Nilai SJ</td>
						<td>Nilai Nota</td>
					</tr>";
				$totalspb = 0; $totalsj = 0; $totalnota = 0;
				if (is_array($query)) {
					for($a=0;$a<count($query);$a++){
						if ($query[$a]['nilaispb'] == '')
							$query[$a]['nilaispb'] = 0;
						if ($query[$a]['nilaisj'] == '')
							$query[$a]['nilaisj'] = 0;
						if ($query[$a]['nilainota'] == '')
							$query[$a]['nilainota'] = 0;
						
						$totalspb+=$query[$a]['nilaispb'];
						$totalsj+=$query[$a]['nilaisj'];
						$totalnota+=$query[$a]['nilainota'];
						
						$html_data.="<tr>
							<td>".$query[$a]['i_area']." - ".$query[$a]['namaarea']."</td>
							<td>".$query[$a]['nilaispb']."</td>
							<td>".$query[$a]['nilaisj']."</td>
							<td>".$query[$a]['nilainota']."</td>
						</tr>";
					}
				}
				$html_data.= "<tr>
					            <td align='center'><b>TOTAL SELURUH AREA/NASIONAL</b></td>
					            <td>".$totalspb."</td>
					            <td>".$totalsj."</td>
					            <td>".$totalnota."</td>
				            </tr>";
				$html_data.="</table><br>";
			}else{
				$html_data = "<table border='1' cellpadding= '1' cellspacing = '1' width='100%'>
				                <tr>
				                	<th colspan='7' align='center'>LAPORAN SPB VS SJ</th>
				                </tr>
				                <tr>
				                	<th colspan='7' align='center'>Periode: $dfrom s.d $dto</th>
                                </tr>
                            </table>
                            <br>";
			$html_data .= "<table border='1' cellpadding= '1' cellspacing = '1' width='100%'>
				            <tr>
				            	<td rowspan='2'>Area</td>
				            	<td colspan='".count($datagrup)."'>Nilai SPB</td>
				            	<td colspan='".count($datagrup)."'>Nilai SJ</td>
				            	<td colspan='".count($datagrup)."'>Nilai Nota</td>
				            </tr>
				            <tr>";
				for($xx=0;$xx<count($datagrup);$xx++){
					$html_data .= "<td>".$datagrup[$xx]['e_product_groupname']."</td>";
				}
				
				for($xx=0;$xx<count($datagrup);$xx++){
					$html_data .= "<td>".$datagrup[$xx]['e_product_groupname']."</td>";
				}
				
				for($xx=0;$xx<count($datagrup);$xx++){
					$html_data .= "<td>".$datagrup[$xx]['e_product_groupname']."</td>";
				}
				
				$html_data.="</tr>";
			$totalspb = 0; $totalsj = 0; $totalnota = 0;
			if (is_array($query)) {
				for($a=0;$a<count($query);$a++){
					$spbpergrup = $query[$a]['nilaispb'];
					$sjpergrup = $query[$a]['nilaisj'];
					$notapergrup = $query[$a]['nilainota'];
					
					$html_data.="<tr><td>".$query[$a]['i_area']." - ".$query[$a]['namaarea']."</td>";
					for($j1=0;$j1<count($spbpergrup);$j1++){
						if ($spbpergrup[$j1]['nilaispb'] == '')
							$spbpergrup[$j1]['nilaispb'] = 0;
						if ($datagrup[$j1]['i_product_group'] == $spbpergrup[$j1]['i_product_group']) {
							$datagrup[$j1]['totalspb']+= $spbpergrup[$j1]['nilaispb'];
						}
						$html_data.= "<td align='right'>".$spbpergrup[$j1]['nilaispb']."</td>";
					}
						
					for($j1=0;$j1<count($sjpergrup);$j1++){
						if ($sjpergrup[$j1]['nilaisj'] == '')
							$sjpergrup[$j1]['nilaisj'] = 0;
						if ($datagrup[$j1]['i_product_group'] == $sjpergrup[$j1]['i_product_group']) {
							$datagrup[$j1]['totalsj']+= $sjpergrup[$j1]['nilaisj'];
						}
						$html_data.= "<td align='right'>".$sjpergrup[$j1]['nilaisj']."</td>";
					}
						
					for($j1=0;$j1<count($notapergrup);$j1++){
						if ($notapergrup[$j1]['nilainota'] == '')
							$notapergrup[$j1]['nilainota'] = 0;
						if ($datagrup[$j1]['i_product_group'] == $notapergrup[$j1]['i_product_group']) {
							$datagrup[$j1]['totalnota']+= $notapergrup[$j1]['nilainota'];
						}
						$html_data.= "<td align='right'>".$notapergrup[$j1]['nilainota']."</td>";
					}
										
					$html_data.="</tr>";
				}
			}
			$html_data.= "<tr>
				<td align='center'><b>TOTAL SELURUH AREA/NASIONAL</b></td>";
				for($xx=0;$xx<count($datagrup);$xx++){
					$html_data.= "<td align='right'><b>".$datagrup[$xx]['totalspb']."</b></td>";
				}
				
				for($xx=0;$xx<count($datagrup);$xx++){
					$html_data.= "<td align='right'><b>".$datagrup[$xx]['totalsj']."</b></td>";
				}
				
				for($xx=0;$xx<count($datagrup);$xx++){
					$html_data.= "<td align='right'><b>".$datagrup[$xx]['totalnota']."</b></td>";
				}
			
			$html_data.="</tr>";
			$html_data.="</table><br>";
			}
			$nama_file = "laporan_spbvssj.xls";
			$data = $html_data;
			header('Content-Disposition: attachment; filename="' . $nama_file . '"');
			print_r($data);
            return true;
    }
}

/* End of file Cform.php */
