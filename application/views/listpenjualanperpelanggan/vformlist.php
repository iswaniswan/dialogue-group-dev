<style>
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 8px;
}

tr:nth-child(){background-color: #f2f2f2}

th {
  background-color: #0099ff;
  color: white;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <table class="tablesaw table-bordered table-hover table" id="sitabel">
                <thead>
                    <tr>
                        <th class="text-center">K-LANG</th>
                        <th class="text-center">NAMA LANG</th>
                        <th class="text-center">ALAMAT</th>
                        <th class="text-center">KOTA</th>
                        <th class="text-center">KS</th>
                        <th class="text-center">N.KOTOR</th>
                        <th class="text-center">N.BERSIH</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
		            if($isi){
		                $notasub=0;
                        $notatot=0;
                        $nettotot=0;
                        $nettosub=0;
                        $kota='';
			            foreach($isi as $row){
                            if( ($kota!='') && ($kota!=$row->e_city_name) ){
		                          echo "<tr><td colspan=3></td>
		                    		        <td colspan=2><b>".$kota."</td><td align=right><b>".number_format($notasub)."</td>
                                    <td align=right><b>".number_format($nettosub)."</td></tr>";
                              $notatot=$notatot+$notasub;
                              $nettotot=$nettotot+$nettosub;
                              $notasub=0;
                              $nettosub=0;
                              $notasub=$notasub+$row->nota;
                              $nettosub=$nettosub+$row->bersih;
		                    	    echo "<tr><td>$row->i_customer</td><td>$row->e_customer_name</td><td>$row->e_customer_address</td>
		                    		    <td>$row->e_city_name</td><td>$row->i_salesman</td><td align=right>".number_format($row->nota)."</td>
                                        <td align=right>".number_format($row->bersih)."</td></tr>";
                            }else{
                              $notasub=$notasub+$row->nota;
                              $nettosub=$nettosub+$row->bersih;
		                    	    echo "<tr><td>$row->i_customer</td><td>$row->e_customer_name</td><td>$row->e_customer_address</td>
		                    		        <td>$row->e_city_name</td><td>$row->i_salesman</td><td align=right>".number_format($row->nota)."</td>
                                            <td align=right>".number_format($row->bersih)."</td></tr>";
                            }
                            $kota=$row->e_city_name;
		                }
                        echo "<tr><td colspan=3></td>
	                    		  <td colspan=2><b>".$kota."</td><td align=right><b>".number_format($notasub)."</td>
                                  <td align=right><b>".number_format($nettosub)."</td></tr>";
                        $notatot=$notatot+$notasub;
                        $nettotot=$nettotot+$nettosub;
                        echo "<tr><td colspan=3></td>
	                    		  <td colspan=2><b>Grand Total</td><td align=right><b>".number_format($notatot)."</td>
                                  <td align=right><b>".number_format($nettotot)."</td></tr>";

	                }
                ?>
                </tbody>
            </table>
            <td colspan='13' align='center'>
				<br>
                <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export ke Excel</button></a>
			</td>
        </div>
    </div>
</form>
</div>
</div>

<script>
    $( "#cmdreset" ).click(function() {  
    	var Contents = $('#sitabel').html();    
    	window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
  	});
</script>
