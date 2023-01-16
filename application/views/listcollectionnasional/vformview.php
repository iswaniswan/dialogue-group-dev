<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <!-- div awal -->
    <h3 class="box-title" style="text-align: center;"><?= $title; ?></h3>
<?php 
  //include ("php/fungsi.php");
?>
    <p class="text-muted" style="text-align: center;"><?php echo 'Periode : '.substr($dfrom,0,2).' '.mbulan(substr($dfrom,3,2)).' '.substr($dfrom,6,4).' s/d '.substr($dto,0,2).' '.mbulan(substr($dto,3,2)).' '.substr($dto,6,4); ?></p>
    <div class="panel-body table-responsive">
        <table class="table color-bordered-table info-bordered-table" id="sitabel" cellpadding="0" cellspacing="0">
<thead>
<?php 
  if($isi){
?>
    <!-- <tr>
    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Area</th>
    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Nama Toko</th> -->
    <tr>
    <!-- <th rowspan=2>Keterangan</th>
    <th align=center colspan="<?php //echo $interval; ?>">Bulan</th> -->
    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Keterangan</th>
    <th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan="<?php echo $interval;?>">Bulan</th>
    </tr><tr>
<?php 
    if($dfrom!=''){
      $tmp=explode("-",$dfrom);
      $blasal=$tmp[1];
      settype($blasal,'integer');
    }
    $bl=$blasal;
    for($i=1;$i<=$interval;$i++){
      switch ($bl){
      case '1' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Jan</th>';
        break;
      case '2' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Feb</th>';
        break;
      case '3' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Mar</th>';
        break;
      case '4' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Apr</th>';
        break;
      case '5' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Mei</th>';
        break;
      case '6' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Jun</th>';
        break;
      case '7' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Jul</th>';
        break;
      case '8' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Agu</th>';
        break;
      case '9' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Sep</th>';
        break;
      case '10' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Okt</th>';
        break;
      case '11' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Nov</th>';
        break;
      case '12' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Des</th>';
        break;
      }
      $bl++;
      if($bl==13)$bl=1;
    }
    $bl=$blasal;
    echo '</tr>';
?>
    </thead>
    <tbody>
<?php 
    echo '<tr><td>Target</td>';
    foreach($isi as $row){
      echo '<td align=right>'.number_format($row->total).'</td>';
    }
    echo '</tr><tr><td>Realisasi</td>';
    foreach($isi as $row){
      echo '<td align=right>'.number_format($row->realisasi).'</td>';
    }
    echo '</tr><tr><td>Tidak tertagih</td>';
    foreach($isi as $row){
      echo '<td align=right>'.number_format($row->total-$row->realisasi).'</td>';
    }
    echo '<tr><td>% tertagih</td>';
    foreach($isi as $row){
      if($row->realisasi>0){
        $persen=($row->realisasi*100)/$row->total;
      }else{
        $persen=0;
      }
      echo '<td align=right>'.number_format($persen,2).' %</td>';
    }
    echo '<tr><td>% Tidak tertagih</td>';
    foreach($isi as $row){
      $nontagih=$row->total-$row->realisasi;
      $persennontagih=($nontagih*100)/$row->total;
      echo '<td align=right>'.number_format($persennontagih,2).' %</td>'; 
    }
        ?>
      </tbody>
<?php 
  }
    ?>
    </table>
        <input name="cmdreset" id="cmdreset" value="Export to Excel" type="button">
    </div> <!-- end div awal -->
    <script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript">
    $("#cmdreset").click(function() {
        var Contents = $('#sitabel').html();
        window.open('data:application/vnd.ms-excel, ' + '<table>' + encodeURIComponent($('#sitabel').html()) +
            '</table>');
    });

    function dipales() {
        this.close();
    }
    </script>