<?php echo $this->pquery->form_remote_tag(array('url'=>'listtpperarea/cform/fcf','update'=>'#main','type'=>'post'));?>
<?php echo $graph ; ?>
<select name="selfile" id="selfile" onchange="pilih()">
  <?php 
    for($i=0;$i<count($isi);$i++)
    {
      $esi=str_replace(".","tandatitik",$isi[$i]);
      echo '<option label="'.$isi[$i].'">'.$isi[$i].'</option>';
    }
  ?>
</select>
<input name="cmdreset" id="cmdreset" value="Keluar" type="button" onclick='show("<?php echo $modul; ?>/cform/view/<?php echo $iperiode; ?>","#main")'>
</form>
<script language="javascript">
	function pilih(){
		tipe=document.getElementById("selfile").value;
    a="<?php echo $modul; ?>/cform/fcf/"+<?php echo $iperiode; ?>+"/"+tipe+"/";
    show(a,"#main");
	}
</script>
