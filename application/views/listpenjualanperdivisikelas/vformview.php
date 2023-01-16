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
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
        </div>
        <div class="panel-body table-responsive">
        </h2>
            <h3><?php 
                if($dfrom){
            	    $tmp=explode('-',$dfrom);
            	    $tgl=$tmp[2];
            	    $bln=$tmp[1];
            	    $thn=$tmp[0];
            	    $dfrom=$tgl.'-'.$bln.'-'.$thn;
                }
                if($dto){
            	    $tmp=explode('-',$dto);
            	    $tgl=$tmp[2];
            	    $bln=$tmp[1];
            	    $thn=$tmp[0];
            	    $dto=$tgl.'-'.$bln.'-'.$thn;
                }
            echo 'Dari Tanggal : '.$dfrom.' Sampai Tanggal : '.$dto; ?></h3>
            <table class="tablesaw table-bordered table-hover table" id="sitabel">
                <thead>
                <?php 
	if($isi){
      $group='';
?>
	  <th>AREA</th>
<?php 
    foreach($kelas as $rw){
      echo "<th>$rw->e_customer_classname</th>";
    }
    echo '<tbody>';
    $grandatu=0;
    $grandua=0;
    $grandiga=0;
    $grandampa=0;
    $grandima=0;
    $grandanam=0;
    $granduju=0;
    $grandapan=0;
    $grandalan=0;
    $grandsiji=0;
    $grandloro=0;
    $grandtelu=0;
    $grandpapat=0;
    $grandlimo=0;
    $grandanem=0;
    $grandpitu=0;
    $grandlapan=0;
    $grandsanga=0;
    foreach($prod as $row)
    {
      if($group==''){
        echo "<tr><th colspan=10 style='background-color:#808080;'>$row->e_product_groupname</th></tr>";
      }
      if($group!='' && $group!=$row->e_product_groupname){
        echo "<tr><th style='background-color:#808080;' colspan=10>$row->e_product_groupname</th></tr>";
      }
      $subatu=0;
      $subua=0;
      $subiga=0;
      $subampa=0;
      $subima=0;
      $subanam=0;
      $subuju=0;
      $subapan=0;
      $subalan=0;
      $subsiji=0;
      $subloro=0;
      $subtelu=0;
      $subpapat=0;
      $sublimo=0;
      $subanem=0;
      $subpitu=0;
      $sublapan=0;
      $subsanga=0;
      foreach($area as $raw)
      { 
        echo "<tr><td>".$raw->i_area."-".$raw->e_area_name."</td>";
        $ada=false;
        foreach($isi as $riw)
        {
          if( ($riw->area==$raw->i_area) && ($riw->grup==$row->i_product_group)){
            $ada=true;
            echo "<td align=right>".number_format($riw->atu)." - (".number_format($riw->siji)." toko)</td>";
            echo "<td align=right>".number_format($riw->ua)." - (".number_format($riw->loro)." toko)</td>";
            echo "<td align=right>".number_format($riw->iga)." - (".number_format($riw->telu)." toko)</td>";
            echo "<td align=right>".number_format($riw->ampa)." - (".number_format($riw->papat)." toko)</td>";
            echo "<td align=right>".number_format($riw->ima)." - (".number_format($riw->limo)." toko)</td>";
            echo "<td align=right>".number_format($riw->anam)." - (".number_format($riw->anem)." toko)</td>";
            echo "<td align=right>".number_format($riw->uju)." - (".number_format($riw->pitu)." toko)</td>";
            echo "<td align=right>".number_format($riw->apan)." - (".number_format($riw->lapan)." toko)</td>";
            echo "<td align=right>".number_format($riw->alan)." - (".number_format($riw->sanga)." toko)</td>";
            echo "</tr>";
            $subatu=$subatu+$riw->atu;
            $subua=$subua+$riw->ua;
            $subiga=$subiga+$riw->iga;
            $subampa=$subampa+$riw->ampa;
            $subima=$subima+$riw->ima;
            $subanam=$subanam+$riw->anam;
            $subuju=$subuju+$riw->uju;
            $subapan=$subapan+$riw->apan;
            $subalan=$subalan+$riw->alan;
            $grandatu=$grandatu+$riw->atu;
            $grandua=$grandua+$riw->ua;
            $grandiga=$grandiga+$riw->iga;
            $grandampa=$grandampa+$riw->ampa;
            $grandima=$grandima+$riw->ima;
            $grandanam=$grandanam+$riw->anam;
            $granduju=$granduju+$riw->uju;
            $grandapan=$grandapan+$riw->apan;
            $grandalan=$grandalan+$riw->alan;
            $subsiji=$subsiji+$riw->siji;
            $subloro=$subloro+$riw->loro;
            $subtelu=$subtelu+$riw->telu;
            $subpapat=$subpapat+$riw->papat;
            $sublimo=$sublimo+$riw->limo;
            $subanem=$subanem+$riw->anem;
            $subpitu=$subpitu+$riw->pitu;
            $sublapan=$sublapan+$riw->lapan;
            $subsanga=$subsanga+$riw->sanga;
            $grandsiji=$grandsiji+$riw->siji;
            $grandloro=$grandloro+$riw->loro;
            $grandtelu=$grandtelu+$riw->telu;
            $grandpapat=$grandpapat+$riw->papat;
            $grandlimo=$grandlimo+$riw->limo;
            $grandanem=$grandanem+$riw->anem;
            $grandpitu=$grandpitu+$riw->pitu;
            $grandlapan=$grandlapan+$riw->lapan;
            $grandsanga=$grandsanga+$riw->sanga;
          }
        }
        if(!$ada){
          echo "<td align=right>0</td>";
          echo "<td align=right>0</td>";
          echo "<td align=right>0</td>";
          echo "<td align=right>0</td>";
          echo "<td align=right>0</td>";
          echo "<td align=right>0</td>";
          echo "<td align=right>0</td>";
          echo "<td align=right>0</td>";
          echo "<td align=right>0</td>";
          echo "</tr>";
        }
      }
      echo "<tr><th>Sub Total</th>";
      echo "<th align=right>".number_format($subatu)." - (".number_format($subsiji)." toko)</th>";
      echo "<th align=right>".number_format($subua)." - (".number_format($subloro)." toko)</th>";
      echo "<th align=right>".number_format($subiga)." - (".number_format($subtelu)." toko)</th>";
      echo "<th align=right>".number_format($subampa)." - (".number_format($subpapat)." toko)</th>";
      echo "<th align=right>".number_format($subima)." - (".number_format($sublimo)." toko)</th>";
      echo "<th align=right>".number_format($subanam)." - (".number_format($subanem)." toko)</th>";
      echo "<th align=right>".number_format($subuju)." - (".number_format($subpitu)." toko)</th>";
      echo "<th align=right>".number_format($subapan)." - (".number_format($sublapan)." toko)</th>";
      echo "<th align=right>".number_format($subalan)." - (".number_format($subsanga)." toko)</th>";
      echo "</tr>";

    }
    echo "<tr><th>Grand Total</th>";
    echo "<th align=right>".number_format($grandatu)." - (".number_format($grandsiji)." toko)</th>";
    echo "<th align=right>".number_format($grandua)." - (".number_format($grandloro)." toko)</th>";
    echo "<th align=right>".number_format($grandiga)." - (".number_format($grandtelu)." toko)</th>";
    echo "<th align=right>".number_format($grandampa)." - (".number_format($grandpapat)." toko)</th>";
    echo "<th align=right>".number_format($grandima)." - (".number_format($grandlimo)." toko)</th>";
    echo "<th align=right>".number_format($grandanam)." - (".number_format($grandanem)." toko)</th>";
    echo "<th align=right>".number_format($granduju)." - (".number_format($grandpitu)." toko)</th>";
    echo "<th align=right>".number_format($grandapan)." - (".number_format($grandlapan)." toko)</th>";
    echo "<th align=right>".number_format($grandalan)." - (".number_format($grandsanga)." toko)</th>";
    echo "</tr>";
	}
	      ?>
	    </tbody>
            </table>
            <br>
            <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
        </div>
    </div>
</div>
</div>

<script>
    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });   
</script>