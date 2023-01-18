<?php echo "<h2>$page_title</h2>";?>
<table class="maintable">
  <tr>
    <td align="left">
      <?php echo $this->pquery->form_remote_tag(array('url'=>'printspbbaru/cform/view','update'=>'#main','type'=>'post'));?>
	<div id="spbperareaform">
	<div class="effect">
	  <div class="accordion2">
	    <table class="mastertable">
	      <tr>
		<td width="19%">Date From</td>
		<td width="1%">:</td>
		<td width="80%">
						<input type="hidden" id="areafrom" name="areafrom" value="">
						<?php 
				$data = array(
			              'name'        => 'dfrom',
			              'id'          => 'dfrom',
			              'value'       => '',
			              'readonly'   	=> 'true',
				      	  'onclick'	    => "showCalendar('',this,this,'','dfrom',0,20,1)");
				echo form_input($data);?></td>
	      </tr>
	      <tr>
		<td width="19%">Date To</td>
		<td width="1%">:</td>
		<td width="80%">
						<?php 
				$data = array(
			              'name'        => 'dto',
			              'id'          => 'dto',
			              'value'       => '',
			              'readonly'   	=> 'true',
				      	  'onclick'	    => "showCalendar('',this,this,'','dto',0,20,1)");
				echo form_input($data);?></td>
	      </tr>
	      <tr>
		<td width="19%">Area</td>
		<td width="1%">:</td>
		<td width="80%">
			<input type="hidden" id="iarea" name="iarea" value="">
			<input type="text" id="eareaname" name="eareaname" value="" onclick='showModal("printspbbaru/cform/area/","#light");jsDlgShow("#konten *", "#fade", "#light");'>
		    			</td>
        </tr>
	      <tr>
		<td width="19%">&nbsp;</td>
		<td width="1%">&nbsp;</td>
		<td width="80%">
		  <input name="login" id="login" value="View" type="submit">
		  <input name="cmdreset" id="cmdreset" value="Keluar" type="button" onclick="show('printspbbaru/cform/','#main')">
		</td>
	      </tr>
	    </table>
	  </div>
	</div>
	</div>
	<?=form_close()?>
    </td>
  </tr>
</table>
