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
<div class="col-sm-12">
    <div class="white-box">
    <h3 class="box-title m-b-0">Periode : <?= mbulan($bulan)." ".$tahun;?></h3>
        <div class="table-responsive">
            <table class="tablesaw table-bordered table-hover table" id="sitabel">
                <thead>
                <?php
                if($isi){?>
                    <tr>
                      <th class="text-center">Area</th>
	                    <th class="text-center">Salesman</th>
		                  <th class="text-center">Target</th>
		                  <th class="text-center">SPB</th>
		                  <th class="text-center">%SPB</th>
		                  <th class="text-center">Nota</th>
		                  <th class="text-center">%Nota</th>
		                  <th class="text-center">SPB-Nota</th>
		                  <th class="text-center">%</th>
    	                <th class="text-center">Retur</th>
    	                <th class="text-center">% Retur</th>
                    </tr>
                    <tr>
                    <?php 
                      $area='';
                  
                      $targetsub=0;
                      $spbsub=0;
                      $notasub=0;
                      $spbnotsub=0;
                      $retursub=0;
                  
                      $targettot=0;
                      $spbtot=0;
                      $notatot=0;
                      $spbnottot=0;
                      $returtot=0;
              
                      foreach($isi as $row){
                        $persenretur = $row->persenretur;
                        if($row->n_target>0){
                          $perspb=($row->n_spb/$row->n_target)*100;
                          $pernot=($row->n_nota/$row->n_target)*100;
                        }else{
                          $perspb=100;
                          $pernot=100;
                        }
                        $spbnot=$row->n_spb-$row->n_nota;
                        if($spbnot>0){
                          if($row->n_spb>0){
                            $persno=($spbnot/$row->n_spb)*100;
                          }else{
                            $persno=0;
                          }
                        }
                        else{
                          $persno=0;
                        }

                        if($row->n_retur==null || $row->n_retur==''){
                          $row->n_retur=0;
                        }
                      
                        if( ($area!='') && ($area!=$row->i_area) ){
                            echo "<tr><td colspan=2></td>
                                    <td align=right><b>".number_format($targetsub)."</td><td align=right><b>".number_format($spbsub)."</td>
                                    <td align=right><b>".number_format($perspbsub,2)."%</td><td align=right><b>".number_format($notasub)."</td>
                                    <td align=right><b>".number_format($pernotsub,2)."%</td><td align=right><b>".number_format($spbnotsub)."</td>
                                    <td align=right><b>".number_format($persnotsub,2)."%</td><td align=right><b>".number_format($retursub)."</td>
                                    <td align=right><b>".number_format(floatval($persenretursub),2)." %</td>
                                  </tr>";
              
                            $targettot=$targettot+$targetsub;
                            $spbtot=$spbtot+$spbsub;
                            $notatot=$notatot+$notasub;
                            $spbnottot=$spbnottot+$spbnotsub;
                            $returtot=$returtot+$retursub;
                          
                            $targetsub=0;
                            $spbsub=0;
                            $notasub=0;
                            $spbnotsub=0;
                            $retursub=0;
                          
                            $targetsub=$targetsub+$row->n_target;
                            $spbsub=$spbsub+$row->n_spb;
                            $notasub=$notasub+$row->n_nota;
                            if($targetsub>0){
                              $perspbsub=($spbsub/$targetsub)*100;
                              $pernotsub=($notasub/$targetsub)*100;
                            }
                            else{
                              $perspbsub=100;
                              $pernotsub=100;
                            }
                            $spbnotsub=$spbnotsub+$spbnot;
                            $spbnsub=$spbsub-$notasub;
                            if($spbsub>0){
                              $persnotsub=($spbnotsub/$spbsub)*100;
                            }
                            else{
                              $persnotsub=0;
                            }
                            $retursub=$retursub+$row->n_retur;
                            if($notasub>0){
                              $persenretursub=number_format(($retursub/$notasub)*100,2);
                            }
                            else{
                              $persenretursub=0;
                            }
                            echo "<tr><td>$row->i_area - $row->e_area_name</td><td>$row->i_salesman - $row->e_salesman_name</td>
                                   <td align=right>".number_format($row->n_target)."</td><td align=right>".number_format($row->n_spb)."</td>
                                   <td align=right>".number_format($perspb,2)."%</td><td align=right>".number_format($row->n_nota)."</td>
                                   <td align=right>".number_format($pernot,2)."%</td><td align=right>".number_format($spbnot)."</td>
                                   <td align=right>".number_format($persno,2)."%</td><td align=right>".number_format($row->n_retur)."</td>
                                   <td align=right>".number_format(floatval($persenretur),2)." %</td>
                                 </tr>";
                        }else{
                            $targetsub=$targetsub+$row->n_target;
                            $spbsub=$spbsub+$row->n_spb;
                            $notasub=$notasub+$row->n_nota;
                            if($targetsub>0){
                              $perspbsub=($spbsub/$targetsub)*100;
                              $pernotsub=($notasub/$targetsub)*100;
                            }
                            else{
                              $perspbsub=100;
                              $pernotsub=100;
                            }
                            $spbnotsub=$spbnotsub+$spbnot;
                            $spbnsub=$spbsub-$notasub;
                            if($spbsub>0){
                              $persnotsub=($spbnotsub/$spbsub)*100;
                            }
                            else{
                              $persnotsub=0;
                            }
                            $retursub=$retursub+$row->n_retur;
                            if($row->n_retur==null || $row->n_retur==''){
                              $row->n_retur=0;
                            }
                            if($row->n_nota!=0){
                              $persenretursub=number_format(($retursub/$notasub)*100,2);
                            }
                            else{
                              $persenretursub='0.00';
                            }
                          
                            echo "<tr><td>$row->i_area - $row->e_area_name</td><td>$row->i_salesman - $row->e_salesman_name</td>
                                  <td align=right>".number_format($row->n_target)."</td><td align=right>".number_format($row->n_spb)."</td>
                                  <td align=right>".number_format($perspb,2)."%</td><td align=right>".number_format($row->n_nota)."</td>
                                  <td align=right>".number_format($pernot,2)."%</td><td align=right>".number_format($spbnot)."</td>
                                  <td align=right>".number_format($persno,2)."%</td><td align=right>".number_format($row->n_retur)."</td>
                                  <td align=right>".number_format($persenretur,2)." %</td>
                                </tr>";
                        }
                          $area=$row->i_area;
                      }
                          echo "<tr><td colspan=2></td>
                                <td align=right><b>".number_format($targetsub)."</td><td align=right><b>".number_format($spbsub)."</td>
                                <td align=right><b>".number_format($perspbsub,2)."%</td><td align=right><b>".number_format($notasub)."</td>
                                <td align=right><b>".number_format($pernotsub,2)."%</td><td align=right><b>".number_format($spbnotsub)."</td>
                                <td align=right><b>".number_format($persnotsub,2)."%</td><td align=right><b>".number_format($row->n_retur)."</td>
                                <td align=right><b>".$persenretur." %</td>
                              </tr>";
                      $targettot=$targettot+$targetsub;
                      $spbtot=$spbtot+$spbsub;
                      $notatot=$notatot+$notasub;
                      $spbnottot=$spbnottot+$spbnotsub;
                      $sistot=$spbtot-$notatot;
                      $returtot=$returtot+$retursub;
              
                      if($targettot==0){
                        $perspbtot=0;
                      }else{
                        $perspbtot=($spbtot/$targettot)*100;
                      }
                      if($targettot==0){
                        $pernottot=0;
                      }else{
                        $pernottot=($notatot/$targettot)*100;
                      }
                      if($spbtot==0){
                        $pernottots=0;
                      }else{
                        $pernottots=($sistot/$spbtot)*100;
                      }
                      if($notatot==0){
                        $perreturtot=0;
                      }else{
                        $perreturtot=number_format(($returtot/$notatot)*100,2);
                      }
                      echo "<tr><td>NA</td><td>Total Nasional</td>
                                <td align=right><b>".number_format($targettot)."</td><td align=right><b>".number_format($spbtot)."</td>
                                <td align=right><b>".number_format($perspbtot,2)."%</td><td align=right><b>".number_format($notatot)."</td>
                                <td align=right><b>".number_format($pernottot,2)."%</td><td align=right><b>".number_format($spbnottot)."</td>
                                <td align=right><b>".number_format($pernottots,2)."%</td><td align=right><b>".number_format($returtot)."</td>
                                <td align=right><b>".number_format($perreturtot,2)." %</td>
                            </tr>";
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
</div>

<script>
  function xxx(x,a,g){
    if (confirm(g)==1){
	    document.getElementById("ispbdelete").value=a;
   	  document.getElementById("inotadelete").value=x;
	    formna=document.getElementById("listform");
	    formna.action="<?php echo site_url(); ?>"+"/listpenjualanperpelanggankonsinyasi/cform/delete";
  	  formna.submit();
    }
  }
  function yyy(x,b){
	  document.getElementById("ispbedit").value=b;
	  document.getElementById("inotaedit").value=x;
	  formna=document.getElementById("listform");
	  formna.action="<?php echo site_url(); ?>"+"/nota/cform/edit";
	  formna.submit();
  }
  $( "#cmdreset" ).click(function() {  
  	var Contents = $('#sitabel').html();    
  	window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
  });
</script>
