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
		                  <th class="text-center">SPB GROSS</th>
                      <th class="text-center">%SPB GROSS</th>
                      <th class="text-center">SPB NETTO</th>
                      <th class="text-center">%SPB NETTO</th>
                      <th class="text-center">Nota Gross</th>
                      <th class="text-center">%Nota Gross</th>
                      <th class="text-center">Nota Netto</th>
                      <th class="text-center">%Nota Netto</th>
                      <th class="text-center">SPB-Nota Gross</th>
                      <th class="text-center">% Gross</th>
                      <th class="text-center">SPB-Nota Netto</th>
                      <th class="text-center">% Netto</th>
                      <th class="text-center">Retur Netto</th>
                      <th class="text-center">% Retur Netto</th>
                      <th class="text-center">Retur Gross</th>
                      <th class="text-center">% Retur Gross</th>
                    </tr>
                    <tr>
                    <?php 
                      $area='';

                      $targetsub=0;
                      $spbsub=0;
                      $spbsubnetto=0;
                      $notasub=0;
                      $notasubnetto=0;
                      $spbnotsub=0;
                      $spbnotsubnetto=0;
                      $retursub=0;
                      $retursubgross=0;
                
                      $targettot=0;
                      $spbtot=0;
                      $spbtotnetto=0;
                      $notatot=0;
                      $notatotnetto=0;
                      $spbnottot=0;
                      $spbnottotnetto=0;
                      $returtot=0;
                      $returtotgross=0;
                
                      foreach($isi as $row){
                          $persenretur = $row->persenretur;
                          $persenreturgross = $row->persenreturgross;
                          if($row->v_target>0){
                            $perspb=($row->v_spb/$row->v_target)*100;
                            $perspbnetto=($row->v_spb_netto/$row->v_target)*100;
                            $pernot=($row->v_nota/$row->v_target)*100;
                            $pernotnetto=($row->v_nota_netto/$row->v_target)*100;
                          }else{
                            $perspb=100;
                            $perspbnetto=100;
                            $pernot=100;
                            $pernotnetto=100;
                          }
                          $spbnot=$row->v_spb-$row->v_nota;
                          $spbnotnetto=$row->v_spb_netto-$row->v_nota_netto;
                          if($spbnot>0){
                            if($row->v_spb>0){
                              $persno=($spbnot/$row->v_spb)*100;
                            }else{
                              $persno=0;
                            }
                          }
                          else{
                            $persno=0;
                          }
                          if($spbnotnetto>0){
                            if($row->v_spb_netto>0){
                              $persnonetto=($spbnotnetto/$row->v_spb_netto)*100;
                            }else{
                              $persnonetto=0;
                            }
                          }
                          else{
                            $persnonetto=0;
                          }
                          if($row->v_retur==null || $row->v_retur=='')$row->v_retur=0;
                        
                          if( ($area!='') && ($area!=$row->i_area) ){
                            echo "<tr><td colspan=2></td>
                                    <td align=right><b>".number_format($targetsub)."</td><td align=right><b>".number_format($spbsub)."</td>
                                    <td align=right><b>".number_format($perspbsub,2)."%</td><td align=right><b>".number_format($spbsubnetto)."</td>
                                    <td align=right><b>".number_format($perspbsubnetto,2)."%</td><td align=right><b>".number_format($notasub)."</td>
                                    <td align=right><b>".number_format($pernotsub,2)."%</td><td align=right><b>".number_format($notasubnetto)."</td>
                                    <td align=right><b>".number_format($pernotsubnetto,2)."%</td><td align=right><b>".number_format($spbnotsub)."</td>
                                    <td align=right><b>".number_format($persnotsub,2)."%</td><td align=right><b>".number_format($spbnotsubnetto)."</td>
                                    <td align=right><b>".number_format($persnotsubnetto,2)."%</td><td align=right><b>".number_format($retursub)."</td>
                                    <td align=right><b>".number_format(floatval($persenretursub),2)." %</td><td align=right><b>".number_format($retursubgross)."</td>
                                    <td align=right><b>".number_format(floatval($persenretursubgross),2)." %</td>
                                  </tr>";
                
                            $targettot=$targettot+$targetsub;
                            $spbtot=$spbtot+$spbsub;
                            $spbtotnetto=$spbtotnetto+$spbsubnetto;
                            $notatot=$notatot+$notasub;
                            $notatotnetto=$notatotnetto+$notasubnetto;
                            $spbnottot=$spbnottot+$spbnotsub;
                            $spbnottotnetto=$spbnottotnetto+$spbnotsubnetto;
                            $returtot=$returtot+$retursub;
                            $returtotgross=$returtotgross+$retursubgross;
                
                            $targetsub=0;
                            $spbsub=0;
                            $spbsubnetto=0;
                            $notasub=0;
                            $notasubnetto=0;
                            $spbnotsub=0;
                            $spbnotsubnetto=0;
                            $retursub=0;
                            $retursubgross=0;
                
                            $targetsub=$targetsub+$row->v_target;
                            $spbsub=$spbsub+$row->v_spb;
                            $spbsubnetto=$spbsubnetto+$row->v_spb_netto;
                            $notasub=$notasub+$row->v_nota;
                            $notasubnetto=$notasubnetto+$row->v_nota_netto;
                            if($targetsub>0){
                              $perspbsub=($spbsub/$targetsub)*100;
                              $perspbsubnetto=($spbsubnetto/$targetsub)*100;
                              $pernotsub=($notasub/$targetsub)*100;
                              $pernotsubnetto=($notasubnetto/$targetsub)*100;
                            }
                            else{
                              $perspbsub=100;
                              $perspbsubnetto=100;
                              $pernotsub=100;
                              $pernotsubnetto=100;
                            }
                            $spbnotsub=$spbnotsub+$spbnot;
                            $spbnotsubnetto=$spbnotsubnetto+$spbnotnetto;
                            $spbnsub=$spbsub-$notasub;
                            $spbnsubnetto=$spbsubnetto-$notasubnetto;
                            if($spbsub>0){
                              $persnotsub=($spbnotsub/$spbsub)*100;
                            }
                            else{
                              $persnotsub=0;
                            }
                            $retursub=$retursub+$row->v_retur;
                            if($notasub>0){
                              $persenretursub=number_format(($retursub/$notasub)*100,2);
                            }
                            else{
                              $persenretursub=0;
                            }
                /*----------------------NETTO------------------------------------*/
                            if($spbsubnetto>0){
                              $persnotsubnetto=($spbnotsubnetto/$spbsubnetto)*100;
                            }
                            else{
                              $persnotsubnetto=0;
                            }
                            $retursubgross=$retursubgross+$row->v_retur_gross;
                            if($notasubnetto>0){
                              $persenretursubgross=number_format(($retursubgross/$notasubnetto)*100,2);
                            }
                            else{
                              $persenretursubgross=0;
                            }
                            echo "<tr><td>$row->i_area - $row->e_area_name</td><td>$row->i_salesman - $row->e_salesman_name</td>
                                      <td align=right>".number_format($row->v_target)."</td><td align=right>".number_format($row->v_spb)."</td>
                                      <td align=right>".number_format($perspb,2)."%</td><td align=right>".number_format($row->v_spb_netto)."</td>
                                      <td align=right>".number_format($perspbnetto,2)."%</td><td align=right>".number_format($row->v_nota)."</td>
                                      <td align=right>".number_format($pernot,2)."%</td><td align=right>".number_format($row->v_nota_netto)."</td>
                                      <td align=right>".number_format($pernotnetto,2)."%</td><td align=right>".number_format($spbnot)."</td>
                                      <td align=right>".number_format($persno,2)."%</td><td align=right>".number_format($spbnotnetto)."</td>
                                      <td align=right>".number_format($persnonetto,2)."%</td><td align=right>".number_format($row->v_retur)."</td>
                                      <td align=right>".number_format(floatval($persenretur),2)." %</td><td align=right>".number_format($row->v_retur_gross)."</td>
                                      <td align=right>".number_format(floatval($persenreturgross),2)." %</td>
                                  </tr>";
                        }else{
                            $targetsub=$targetsub+$row->v_target;
                            $spbsub=$spbsub+$row->v_spb;
                            $spbsubnetto=$spbsubnetto+$row->v_spb_netto;
                            $notasub=$notasub+$row->v_nota;
                            $notasubnetto=$notasubnetto+$row->v_nota_netto;
                            if($targetsub>0){
                              $perspbsub=($spbsub/$targetsub)*100;
                              $perspbsubnetto=($spbsubnetto/$targetsub)*100;
                              $pernotsub=($notasub/$targetsub)*100;
                              $pernotsubnetto=($notasubnetto/$targetsub)*100;
                            }
                            else{
                              $perspbsub=100;
                              $perspbsubnetto=100;
                              $pernotsub=100;
                              $pernotsubnetto=100;
                            }
                            $spbnotsub=$spbnotsub+$spbnot;
                            $spbnsub=$spbsub-$notasub;
                            $spbnotsubnetto=$spbnotsubnetto+$spbnotnetto;
                            $spbnsubnetto=$spbsubnetto-$notasubnetto;
                            if($spbsub>0){
                              $persnotsub=($spbnotsub/$spbsub)*100;
                            }
                            else{
                              $persnotsub=0;
                            }
                            if($spbsubnetto>0){
                              $persnotsubnetto=($spbnotsubnetto/$spbsubnetto)*100;
                            }
                            else{
                              $persnotsubnetto=0;
                            }
                            $retursub=$retursub+$row->v_retur;
                            $retursubgross=$retursubgross+$row->v_retur_gross;
                            if($row->v_retur==null || $row->v_retur=='')$row->v_retur=0;
                            if($row->v_retur_gross==null || $row->v_retur_gross=='')$row->v_retur_gross=0;
                            if($row->v_nota!=0){
                              $persenretursub=number_format(($retursub/$notasub)*100,2);
                            }
                            else{
                              $persenretursub='0.00';
                            }
                            if($row->v_nota_netto!=0){
                              $persenretursubgross=number_format(($retursubgross/$notasubnetto)*100,2);
                            }
                            else{
                              $persenretursubgross='0.00';
                            }
                            echo "<tr><td>$row->i_area - $row->e_area_name</td><td>$row->i_salesman - $row->e_salesman_name</td>
                                      <td align=right>".number_format($row->v_target)."</td><td align=right>".number_format($row->v_spb)."</td>
                                      <td align=right>".number_format($perspb,2)."%</td><td align=right>".number_format($row->v_spb_netto)."</td>
                                      <td align=right>".number_format($perspbnetto,2)."%</td><td align=right>".number_format($row->v_nota)."</td>
                                      <td align=right>".number_format($pernot,2)."%</td><td align=right>".number_format($row->v_nota_netto)."</td>
                                      <td align=right>".number_format($pernotnetto,2)."%</td><td align=right>".number_format($spbnot)."</td>
                                      <td align=right>".number_format($persno,2)."%</td><td align=right>".number_format($spbnotnetto)."</td>
                                      <td align=right>".number_format($persnonetto,2)."%</td><td align=right>".number_format($row->v_retur)."</td>
                                      <td align=right>".number_format($persenretur,2)." %</td><td align=right>".number_format($row->v_retur_gross)."</td>
                                      <td align=right>".number_format($persenreturgross,2)." %</td>
                                  </tr>";
                            }
                            $area=$row->i_area;
                      }
                        echo "<tr><td colspan=2></td>
                                  <td align=right><b>".number_format($targetsub)."</td><td align=right><b>".number_format($spbsub)."</td>
                                  <td align=right><b>".number_format($perspbsub,2)."%</td><td align=right><b>".number_format($spbsubnetto)."</td>
                                  <td align=right><b>".number_format($perspbsubnetto,2)."%</td><td align=right><b>".number_format($notasub)."</td>
                                  <td align=right><b>".number_format($pernotsub,2)."%</td><td align=right><b>".number_format($notasubnetto)."</td>
                                  <td align=right><b>".number_format($pernotsubnetto,2)."%</td><td align=right><b>".number_format($spbnotsub)."</td>
                                  <td align=right><b>".number_format($persnotsub,2)."%</td><td align=right><b>".number_format($spbnotsubnetto)."</td>
                                  <td align=right><b>".number_format($persnotsubnetto,2)."%</td><td align=right><b>".number_format($row->v_retur)."</td>
                                  <td align=right><b>".$persenretur." %</td><td align=right><b>".number_format($row->v_retur_gross)."</td>
                                  <td align=right><b>".$persenreturgross." %</td>
                              </tr>";
                        $targettot=$targettot+$targetsub;
                        $spbtot=$spbtot+$spbsub;
                        $spbtotnetto=$spbtotnetto+$spbsubnetto;
                        $notatot=$notatot+$notasub;
                        $notatotnetto=$notatotnetto+$notasubnetto;
                        $spbnottot=$spbnottot+$spbnotsub;
                        $spbnottotnetto=$spbnottotnetto+$spbnotsubnetto;
                        $sistot=$spbtot-$notatot;
                        $sistotnetto=$spbtotnetto-$notatotnetto;
                        $returtot=$returtot+$retursub;
                        $returtotgross=$returtotgross+$retursubgross;
                
                        if($targettot==0){
                          $perspbtot=0;
                          $perspbtotnetto=0;
                        }else{
                          $perspbtot=($spbtot/$targettot)*100;
                          $perspbtotnetto=($spbtotnetto/$targettot)*100;
                        }
                        if($targettot==0){
                          $pernottot=0;
                          $pernottotnetto=0;
                        }else{
                          $pernottot=($notatot/$targettot)*100;
                          $pernottotnetto=($notatotnetto/$targettot)*100;
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
                /*-------------NETTO----------------------------------*/        
                        if($spbtotnetto==0){
                          $pernottotsnetto=0;
                        }else{
                          $pernottotsnetto=($sistotnetto/$spbtotnetto)*100;
                        }
                        if($notatotnetto==0){
                          $perreturtotgross=0;
                        }else{
                          $perreturtotgross=number_format(($returtotgross/$notatotnetto)*100,2);
                        }
                        echo "<tr><td>NA</td><td>Total Nasional</td>
                                  <td align=right><b>".number_format($targettot)."</td><td align=right><b>".number_format($spbtot)."</td>
                                  <td align=right><b>".number_format($perspbtot,2)."%</td><td align=right><b>".number_format($spbtotnetto)."</td>
                                  <td align=right><b>".number_format($perspbtotnetto,2)."%</td><td align=right><b>".number_format($notatot)."</td>
                                  <td align=right><b>".number_format($pernottot,2)."%</td><td align=right><b>".number_format($notatotnetto)."</td>
                                  <td align=right><b>".number_format($pernottotnetto,2)."%</td><td align=right><b>".number_format($spbnottot)."</td>
                                  <td align=right><b>".number_format($pernottots,2)."%</td><td align=right><b>".number_format($spbnottotnetto)."</td>
                                  <td align=right><b>".number_format($pernottotsnetto,2)."%</td><td align=right><b>".number_format($returtot)."</td>
                                  <td align=right><b>".number_format($perreturtot,2)." %</td><td align=right><b>".number_format($returtotgross)."</td>
                                  <td align=right><b>".number_format($perreturtotgross,2)." %</td>
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
