<h2>
<?php 
	echo $page_title;
?>
</h2>
<p class="error">
<?php 
	echo $this->lang->line('printspb_wrong_input');
?>
</p>
<?php 
	$this->load->view('printspbbaru/vformrpt');
?>
