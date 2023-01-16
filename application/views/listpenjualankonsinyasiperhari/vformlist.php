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
  background-color: #737373;
  color: white;
}
</style>
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
      <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
        class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table color-table info-table hover-table" id="sitabel">
                <thead>
                    <?php 
		            if($isi){
                    ?>
	   	            <th>Customer</th>
	   	            <th>Tanggal</th>
                    <?php 
                          $y=1;
                          foreach($diskon as $row){
                      		  echo "<th> Jml Pcs ".$row->diskon." %</th>";
                      		  echo "<th> Kotor ".$row->diskon." %</th>";
                      		  $dis[$y]=$row->diskon;
                      		  $y++;
                          }
                    ?>
                </thead>
                <tbody>
                <?php 
                    echo "<input type='hidden' id='dfrom' name='dfrom' value='$dfrom'>
                          <input type='hidden' id='dto' name='dto' value='$dto'>
                          <input type='hidden' id='icustomer' name='icustomer' value='$icustomer'>";
                    $i      =0;
                    $disc   ='';
                    $tgl    ='';
                    $jmltot =count($diskon);
                    $pos    =0;
                    foreach($isi as $raw){
                      $x=0;
                      if($tgl==''){
                        $i++;
                        echo "<tr><td>$raw->i_customer - $raw->e_customer_name</td>
                                  <td>$raw->d_notapb</td>";
                         
                        foreach($diskon as $row){
                            $x++;
                            if($row->diskon==$raw->n_notapb_discount && $dis[$x]==$raw->n_notapb_discount){
                                echo "
                                    <td align=right>$raw->jumlah</td>
                                    <td align=right>".number_format($raw->kotor)."</td>";
                                $pos=$x;
                                break;
                            }elseif($pos<$x){
                                echo "
                                    <td align=right>0</td>
                                    <td align=right>0</td>";
                                $pos++;
                            }
                        }
                      }
                      if($tgl==$raw->d_notapb){
                        foreach($diskon as $row){
                          $x++;
                          if($row->diskon==$raw->n_notapb_discount && $dis[$x]==$raw->n_notapb_discount){
                            echo "
                                <td align=right>$raw->jumlah</td>
                                <td align=right>".number_format($raw->kotor)."</td>";
                            $pos=$x;
                            break;
                          }elseif($x!=$pos){
                            echo "
                                <td align=right>0</td>
                                <td align=right>0</td>";
                          }
                        }
                      }
                      if($tgl!=$raw->d_notapb && $tgl!=''){
                        while($pos<$jmltot){
                          echo "  <td align=right>0</td>
                                  <td align=right>0</td>";
                          $pos++;
                        }
                        $i++;
                        echo "</tr><tr><td>$raw->i_customer - $raw->e_customer_name</td>
                                <td>$raw->d_notapb</td>";
                        foreach($diskon as $row){
                          $x++;
                          if($row->diskon==$raw->n_notapb_discount){
                            echo "
                                <td align=right>$raw->jumlah</td>
                                <td align=right>".number_format($raw->kotor)."</td>";
                            $pos=$x;
                            break;
                          }elseif($x>$pos){
                            echo "
                                <td align=right>0</td>
                                <td align=right>0</td>";
                          }
                        }
                      }
                      $tgl=$raw->d_notapb;
                      if($pos==$jmltot){
                        echo "</tr>";
                      }
                    }
                
                    while($pos<$jmltot){
                      echo "  <td>0</td>
                              <td>0</td>";
                      $pos++;
                    }
                
                    foreach($total as $row){
                      echo "<tr><td colspan=2 align=right>Total</td>";
                      foreach($diskon as $raw){
                        foreach($total as $row){
                          if($row->n_notapb_discount==$raw->diskon){
                            echo "<td align=right>$row->totalpcs</td>
                                  <td align=right>".number_format($row->totalkotor)."</td>";
                          }
                        }
                      }
                      echo "</tr>";
                    
                      echo "<tr><td colspan=2 align=right>Diskon</td>";
                      foreach($diskon as $raw){
                        foreach($total as $row){
                          if($row->n_notapb_discount!=0){
                            $discount=($row->n_notapb_discount/100)*$row->totalkotor;
                          }else{
                            $discount=0;
                          }
                          if($row->n_notapb_discount==$raw->diskon){
                            echo "<td align=right>$row->n_notapb_discount</td>
                                  <td align=right>".number_format($discount)."</td>";
                          }
                        }
                      }
                      echo "</tr>";
                    
                      echo "<tr><td colspan=2 align=right>Netto</td>";
                      foreach($diskon as $raw){
                        foreach($total as $row){
                          $bersih=$row->totalkotor-$discount;
                          if($row->n_notapb_discount==$raw->diskon){
                            echo "<td>&nbsp;</td>
                                  <td align=right>".number_format($bersih)."</td>";
                          }
                        }
                      }
                      echo "</tr>";
                    }
		        }
	                      ?>
            </tbody>
        </table>
        <td colspan='13' align='center'>
            <br>
            <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button></a>
        </td>
    </div>
</div>
</div>
</div>
</div>
<script>
    $( "#cmdreset" ).click(function() {
        var Contents = $('#sitabel').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
    });
</script>
