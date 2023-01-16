<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cform extends CI_Controller {

    public $global = array();
    public $i_menu = '1020205';

    public function __construct(){
        parent::__construct();
        cek_session();
        
        $data = check_role($this->i_menu, 2);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $this->global['folder'] = $data[0]['e_folder'];
        $this->global['title'] = $data[0]['e_menu'];
        $this->load->model($this->global['folder'].'/mmaster');
        $this->load->library('fungsi');
        /*require_once("php/fungsi.php");*/
    }  

    public function index(){
        $data = array(
            'folder'    => $this->global['folder'],
            'title'     => $this->global['title']
        );
        $this->Logger->write('Membuka Menu '.$this->global['title']);
        $this->load->view($this->global['folder'].'/vformview', $data);

    }

    public function data(){
        $username   = $this->session->userdata('username');
        $id_company = $this->session->userdata('id_company');
        $siareana   = $this->mmaster->cekuser($username, $id_company);
        echo $this->mmaster->data($this->global['folder'], $siareana, $username, $id_company);
    }

    public function edit(){
        $isj        = $this->uri->segment(4);
        $iarea      = $this->uri->segment(5);
        $ispb       = $this->uri->segment(6);
        $iareasj    = substr($isj,8,2);        
        $fspbconsigment = $this->mmaster->fspb($ispb, $iarea);
        $topspb         = $this->mmaster->topspb($isj, $iarea);
        $query          = $this->mmaster->getnota($isj,$iarea);
        if ($query->num_rows() > 0){
            foreach($query->result() as $row){
                if($iareasj=='BK'){
                    $iareasj=$iarea;      
                }
                $istore = $this->mmaster->getstore($iareasj);
                /*$data['libur'] = 0;*/
                /*--------------- | AWAL | -----------------*/
                /*$newdate = date('Y-m-d',strtotime('-1 day'));
                $tgl=substr($newdate,8,2);
                $bln=substr($newdate,5,2);
                $thn=substr($newdate,0,4);
                if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                    $data['libur'] = $data['libur']+1;
                    $newdate = date('Y-m-d',strtotime('-2 day'));
                    $x1 = $this->mmaster->getholiday($newdate);
                    if ($x1->num_rows() > 0){
                        $data['libur'] = $data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-3 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-4 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                            }
                        }
                    }
                }else{
                    $x1 = $this->mmaster->getholiday($newdate);
                    if ($x1->num_rows() > 0){
                        $data['libur']=$data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-2 day'));
                        $tgl=substr($newdate,8,2);
                        $bln=substr($newdate,5,2);
                        $thn=substr($newdate,0,4);
                        if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-3 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-4 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur']=$data['libur']+1;
                                }
                            }
                        }else{
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-3 day'));
                                $tgl = substr($newdate,8,2);
                                $bln = substr($newdate,5,2);
                                $thn = substr($newdate,0,4);
                                if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                    $data['libur'] = $data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-4 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur'] = $data['libur']+1;
                                        $newdate = date('Y-m-d',strtotime('-5 day'));
                                        $x1 = $this->mmaster->getholiday($newdate);
                                        if ($x1->num_rows() > 0){
                                            $data['libur']=$data['libur']+1;
                                        }
                                    }
                                }else{
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur']=$data['libur']+1;
                                        $newdate = date('Y-m-d',strtotime('-4 day'));
                                        $tgl=substr($newdate,8,2);
                                        $bln=substr($newdate,5,2);
                                        $thn=substr($newdate,0,4);
                                        if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                            $data['libur']=$data['libur']+1;
                                            $newdate = date('Y-m-d',strtotime('-5 day'));
                                            $x1 = $this->mmaster->getholiday($newdate);
                                            if ($x1->num_rows() > 0){
                                                $data['libur']=$data['libur']+1;
                                                $newdate = date('Y-m-d',strtotime('-6 day'));
                                                $x1 = $this->mmaster->getholiday($newdate);
                                                if ($x1->num_rows() > 0){
                                                    $data['libur']=$data['libur']+1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }*/
                /*---------- | END AWAL | ------------*/

                /*---------- |   SATU   | ------------*/
                /*if($data['libur']==0){
                    $newdate = date('Y-m-d',strtotime('-2 day'));
                    $tgl = substr($newdate,8,2);
                    $bln = substr($newdate,5,2);
                    $thn = substr($newdate,0,4);
                    if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                        $data['libur'] = $data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-3 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-4 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-5 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur']=$data['libur']+1;
                                }
                            }
                        }
                    }else{
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur']=$data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-3 day'));
                            $tgl = substr($newdate,8,2);
                            $bln = substr($newdate,5,2);
                            $thn = substr($newdate,0,4);
                            if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-4 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur']=$data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-5 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur']=$data['libur']+1;
                                    }
                                }
                            }
                        }
                    }
                }elseif($data['libur']==1){
                    $newdate = date('Y-m-d',strtotime('-3 day'));
                    $tgl = substr($newdate,8,2);
                    $bln = substr($newdate,5,2);
                    $thn = substr($newdate,0,4);
                    if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                        $data['libur']=$data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-4 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-5 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur']=$data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-6 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur']=$data['libur']+1;
                                }
                            }
                        }
                    }else{
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-4 day'));
                            $tgl = substr($newdate,8,2);
                            $bln = substr($newdate,5,2);
                            $thn = substr($newdate,0,4);
                            if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-5 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-6 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur']=$data['libur']+1;
                                    }
                                }
                            }
                        }
                    }
                }elseif($data['libur']==2){
                    $newdate = date('Y-m-d',strtotime('-4 day'));
                    $tgl = substr($newdate,8,2);
                    $bln = substr($newdate,5,2);
                    $thn = substr($newdate,0,4);
                    if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                        $data['libur'] = $data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-5 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-6 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-7 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur']=$data['libur']+1;
                                }
                            }
                        }
                    }else{
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur']=$data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-5 day'));
                            $tgl = substr($newdate,8,2);
                            $bln = substr($newdate,5,2);
                            $thn = substr($newdate,0,4);
                            if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-6 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-7 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur']=$data['libur']+1;
                                    }
                                }
                            }
                        }
                    }
                }elseif($data['libur']==3){
                    $newdate = date('Y-m-d',strtotime('-5 day'));
                    $tgl = substr($newdate,8,2);
                    $bln = substr($newdate,5,2);
                    $thn = substr($newdate,0,4);
                    if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                        $data['libur'] = $data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-6 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-7 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-8 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur']=$data['libur']+1;
                                }
                            }
                        }
                    }else{
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur']=$data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-6 day'));
                            $tgl = substr($newdate,8,2);
                            $bln = substr($newdate,5,2);
                            $thn = substr($newdate,0,4);
                            if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-7 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-8 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur']=$data['libur']+1;
                                    }
                                }
                            }
                        }
                    }
                }*/
                /*---------- | END SATU | ------------*/

                /*---------- |   DUA   | ------------*/
                /*if($data['libur']==0){
                    $newdate = date('Y-m-d',strtotime('-3 day'));
                    $tgl = substr($newdate,8,2);
                    $bln = substr($newdate,5,2);
                    $thn = substr($newdate,0,4);
                    if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                        $data['libur'] = $data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-4 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-5 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-6 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                }
                            }
                        }
                    }else{
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-4 day'));
                            $tgl = substr($newdate,8,2);
                            $bln = substr($newdate,5,2);
                            $thn = substr($newdate,0,4);
                            if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-5 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-6 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur'] = $data['libur']+1;
                                    }
                                }
                            }
                        }
                    }
                }elseif($data['libur']==1){
                    $newdate = date('Y-m-d',strtotime('-4 day'));
                    $tgl = substr($newdate,8,2);
                    $bln = substr($newdate,5,2);
                    $thn = substr($newdate,0,4);
                    if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                        $data['libur'] = $data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-5 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-6 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-7 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                }
                            }
                        }
                    }else{
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-5 day'));
                            $tgl = substr($newdate,8,2);
                            $bln = substr($newdate,5,2);
                            $thn = substr($newdate,0,4);
                            if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-6 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-7 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur'] = $data['libur']+1;
                                    }
                                }
                            }
                        }
                    }
                }elseif($data['libur']==2){
                    $newdate = date('Y-m-d',strtotime('-5 day'));
                    $tgl = substr($newdate,8,2);
                    $bln = substr($newdate,5,2);
                    $thn = substr($newdate,0,4);
                    if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                        $data['libur'] = $data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-6 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-7 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-8 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                }
                            }
                        }
                    }else{
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-6 day'));
                            $tgl = substr($newdate,8,2);
                            $bln = substr($newdate,5,2);
                            $thn = substr($newdate,0,4);
                            if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-7 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-8 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur'] = $data['libur']+1;
                                    }
                                }
                            }
                        }
                    }
                }elseif($data['libur']==3){
                    $newdate = date('Y-m-d',strtotime('-6 day'));
                    $tgl = substr($newdate,8,2);
                    $bln = substr($newdate,5,2);
                    $thn = substr($newdate,0,4);
                    if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                        $data['libur'] = $data['libur']+1;
                        $newdate = date('Y-m-d',strtotime('-7 day'));
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-8 day'));
                            $x1 = $this->mmaster->getholiday($newdate);
                            if ($x1->num_rows() > 0){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-9 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                }
                            }
                        }
                    }else{
                        $x1 = $this->mmaster->getholiday($newdate);
                        if ($x1->num_rows() > 0){
                            $data['libur'] = $data['libur']+1;
                            $newdate = date('Y-m-d',strtotime('-7 day'));
                            $tgl = substr($newdate,8,2);
                            $bln = substr($newdate,5,2);
                            $thn = substr($newdate,0,4);
                            if(date('w', mktime(0,0,0,$bln,$tgl,$thn))=='0'){
                                $data['libur'] = $data['libur']+1;
                                $newdate = date('Y-m-d',strtotime('-8 day'));
                                $x1 = $this->mmaster->getholiday($newdate);
                                if ($x1->num_rows() > 0){
                                    $data['libur'] = $data['libur']+1;
                                    $newdate = date('Y-m-d',strtotime('-9 day'));
                                    $x1 = $this->mmaster->getholiday($newdate);
                                    if ($x1->num_rows() > 0){
                                        $data['libur'] = $data['libur']+1;
                                    }
                                }
                            }
                        }
                    }
                }*/
                /*--------------| END DUA |---------------*/
                $data = array(
                    'folder'            => $this->global['folder'],
                    'title'             => $this->global['title'],
                    'isj'               => $isj,
                    'iarea'             => $iarea,
                    'iareasj'           => $iareasj,
                    'n_spb_toplength'   => $topspb,
                    'isi'               => $this->mmaster->baca($isj,$iarea),
                    'detail'            => $this->mmaster->bacadetail($isj,$iarea),
                    'dsj'               => $row->d_sj,
                    'ispb'              => $row->i_spb,
                    'dspb'              => $row->d_spb,
                    'istore'            => $istore,
                    'isjold'            => $row->i_sj_old,
                    'eareaname'         => $row->e_area_name,
                    'vsjgross'          => $row->v_nota_gross,
                    'nsjdiscount1'      => $row->n_nota_discount1,
                    'nsjdiscount2'      => $row->n_nota_discount2,
                    'nsjdiscount3'      => $row->n_nota_discount3,
                    'vsjdiscount1'      => $row->v_nota_discount1,
                    'vsjdiscount2'      => $row->v_nota_discount2,
                    'vsjdiscount3'      => $row->v_nota_discount3,
                    'vsjdiscounttotal'  => $row->v_nota_discounttotal,
                    'vsjnetto'          => $row->v_nota_netto,
                    'icustomer'         => $row->i_customer,
                    'ecustomername'     => $row->e_customer_name,
                    'isalesman'         => $row->i_salesman,
                    'fplusppn'          => $row->f_plus_ppn,
                    'ntop'              => $row->n_nota_toplength,
                    'fspbconsigment'    => $fspbconsigment,
                    'hariini'           => date('Y-m-d')
                );
            }
        }
        $this->load->view($this->global['folder'].'/vform', $data);
    }

    public function update(){
        $data = check_role($this->i_menu, 1);
        if(!$data){
            redirect(base_url(),'refresh');
        }

        $isj            = $this->input->post('isj', TRUE);
        $iarea          = $this->input->post('iarea', TRUE);
        $dsjreceive     = $this->input->post('dsjreceive', TRUE);
        $eremark        = $this->input->post('eremark', TRUE);
        $nnotatoplength = $this->input->post('n_spb_toplength',TRUE);
        if($dsjreceive!=''){
            $tmp    = explode("-", $dsjreceive);
            $det    = $tmp[0];
            $mon    = $tmp[1];
            $yir    = $tmp[2];
            $ddspb  = $yir."/".$mon."/".$det;
            $this->db->trans_begin();
            if($nnotatoplength<0){
                $nnotatoplength = $nnotatoplength*-1;
            }
            $dudet  = $this->fungsi->dateAdd("d",$nnotatoplength,$ddspb);
            $this->mmaster->updatesj($isj,$iarea,$dsjreceive,$eremark,$dudet);
            if(($this->db->trans_status()=== False)){
                $this->db->trans_rollback();
                $data = array(
                    'sukses' => false
                );
            }else{
                $this->db->trans_commit();
                $this->Logger->write('Receive SJ '.$iarea.' No:'.$isj);
                $data = array(
                    'sukses'    => true,
                    'kode'      => $isj
                );
            }
        }else{
            $data = array(
                'sukses' => false
            );
        }
        $this->load->view('pesan', $data);
    }
}
/* End of file Cform.php */
