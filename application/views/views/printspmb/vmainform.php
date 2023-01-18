<?php 
if($ispmb==''){
	$data['isi']=$isi;
	$data['page_title']=$page_title;
	$data['cari']=$cari;
	echo "<div id=\"tmp\">";
	$this->load->view('printspmb/vform',$data);
	echo "</div>";
}else{
	$data['isi']	  = $isi;
	$data['detail'] = $detail;
	// $data['user']	  = $user;
	$data['host']	  = $host;
	$data['uri']	  = $uri;
	$this->load->view('printspmb/vformrpt',$data);
}
?>
