<table class="maintable">
<tr>
  <td align="left">
  <?php echo $this->pquery->form_remote_tag(array('url'=>'productpricemanual/cform/simpan','update'=>'#pesan','type'=>'post'));?>
  <div id="masterproductpriceform">
<div class="effect">
  <div class="accordion2">
    <table class="mastertable">
      <tr>
	<td width="12%">Kode Barang</td>
	<td width="1%">:</td>
	<td width="87%"><input readonly type="text" name="iproduct" id="iproduct" value="" 
			 onclick='showModal("productpricemanual/cform/product/","#light");jsDlgShow("#konten *", "#fade", "#light");'></td>
      </tr>
      <tr>
	<td width="12%">Nama Barang</td>
	<td width="1%">:</td>
	<td width="87%"><input readonly name="eproductname" id="eproductname" value="" 
			 onclick='showModal("productpricemanual/cform/product/","#light");jsDlgShow("#konten *", "#fade", "#light");'></td>
      </tr>
      <tr>
	<td width="12%">Kode Grade</td>
	<td width="1%">:</td>
	<td width="87%"><input type="hidden" name="iproductgrade" id="iproductgrade" value="" 
			 onclick='showModal("productpricemanual/cform/productgrade/","#light");jsDlgShow("#konten *", "#fade", "#light");'>
			 <input readonly name="eproductgradename" id="eproductgradename" value="" 
			 onclick='showModal("productpricemanual/cform/productgrade/","#light");jsDlgShow("#konten *", "#fade", "#light");'></td>
      </tr>
  </table>
  <table class="mastertable">
      <tr>
	<td width="8%">Kode Harga</td>
	<td width="16%">Harga Pabrik</td>
	<td width="76%">Harga</td>
      </tr>
      <?php 
        if($isi){
          $i=1;
          foreach($isi as $row){
            echo "
                    <tr>
	              <td width=\"8%\"><input readonly name=\"ipricegroup$i\" id=\"ipricegroup$i\" value=\"$row->i_price_group\"></td>
	              <td width=\"16%\"><input name=\"vproductmill$i\" id=\"vproductmill$i\" value=\"\" onkeyup=\"reformat(this);samain($i);\"></td>
	              <td width=\"76%\"><input name=\"vproductretail$i\" id=\"vproductretail$i\" value=\"\" onkeyup=\"reformat(this);\"></td>
                    </tr>";
            $i++;
          }
        }
        $jml=$i-1;
        echo "<input type=hidden name=\"jmlitem\" id=\"jmlitem\" value=\"$jml\">";
      ?>
      <tr>
	<td colspan=3>
	  <input name="login" id="login" value="Simpan" type="submit">
	  <input name="cmdreset" id="cmdreset" value="Keluar" type="button" onclick="show('productpricemanual/cform/','#main');">
	</td>
  </tr>
  </table>
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
<div id="pesan"></div>
<script language="javascript" type="text/javascript">
  function samain(i){
    for(x=1;x<=10;x++){
      document.getElementById("vproductmill"+x).value=document.getElementById("vproductmill"+i).value;
    }
  }
</script>
