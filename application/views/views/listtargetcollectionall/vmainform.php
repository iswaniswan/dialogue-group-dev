<?php 
  if( ($iperiode=='') ){
    echo "<h2>$page_title</h2>";
     $this->load->view('listtargetcollectionrealtime/vform');
  }elseif(isset($isi)){
     $data['iperiode']  = $iperiode;
     $data['isi']       = $isi;
     $data['akhir']     = $akhir;
#     print_r($isi); die;
     $this->load->view('listtargetcollectionrealtime/vformview',$data);
  }elseif(isset($detail) && $detail!=''){
     $data['iperiode']  = $iperiode;
     $data['detail']   = $detail;
     $this->load->view('listtargetcollectionrealtime/vformdetail',$data);
  }elseif(isset($sales) && $sales!=''){
     $data['iperiode']  = $iperiode;
     $data['detail']   = $sales;
     $this->load->view('listtargetcollectionrealtime/vformdetailsales',$data);
  }elseif(isset($divisi) && $divisi!=''){
     $data['iperiode']  = $iperiode;
     $data['detail']   = $divisi;
     $this->load->view('listtargetcollectionrealtime/vformdetaildivisi',$data);
  }elseif(isset($all) && $all!=''){
     $data['iperiode']  = $iperiode;
     $data['detail']   = $all;
     $this->load->view('listtargetcollectionrealtime/vformdetailall',$data);
  }else{
    echo "<h2>Belum ada data collection !!!</h2>";
  }
?>
