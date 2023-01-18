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
  <!-- <tr>
    <td align="left"> -->
<h2><?php //echo $page_title; ?></h2>

  <?php //echo $this->pquery->form_remote_tag(array('url'=>'listcollectionperpareto/cform/view','update'=>'#main','type'=>'post'));?>
  <!-- <div class="effect">
    <div class="accordion2">
      <table class="listtable" id="sitabel"> -->
<thead>
<?php 
  if($isi){
?>
    <tr>
    <!-- <th rowspan=2>Area</th>
    <th rowspan=2>Nama Toko</th> -->
    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Area</th>
    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Nama Toko</th>
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
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Jan</th>';
        break;
      case '2' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Feb</th>';
        break;
      case '3' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Mar</th>';
        break;
      case '4' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Apr</th>';
        break;
      case '5' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Mei</th>';
        break;
      case '6' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Jun</th>';
        break;
      case '7' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Jul</th>';
        break;
      case '8' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Agu</th>';
        break;
      case '9' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Sep</th>';
        break;
      case '10' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Okt</th>';
        break;
      case '11' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Nov</th>';
        break;
      case '12' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" colspan=5>Des</th>';
        break;
      }
      $bl++;
      if($bl==13)$bl=1;
    }
    $bl=$blasal;
    echo '</tr><tr>';
    for($i=1;$i<=$interval;$i++){
      switch ($bl){
      case '1' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '2' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '3' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '4' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '5' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '6' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '7' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '8' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '9' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '10' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '11' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      case '12' :
        echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;" >Target</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Realisasi</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >Tdk Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tertagih</th>
              <th style="font-size: 12px;text-align: center;vertical-align: middle;" >% Tdk Tertagih</th>';
        break;
      }
      $bl++;
      if($bl==13)$bl=1;
    }
?>
    </tr>
    </thead>
    <tbody>
<?php 
    $bl=$blasal;
    $area='';
    $cust='';

    $subtarget01=0;
    $subtarget02=0;
    $subtarget03=0;
    $subtarget04=0;
    $subtarget05=0;
    $subtarget06=0;
    $subtarget07=0;
    $subtarget08=0;
    $subtarget09=0;
    $subtarget10=0;
    $subtarget11=0;
    $subtarget12=0;
    $subreal01=0;
    $subreal02=0;
    $subreal03=0;
    $subreal04=0;
    $subreal05=0;
    $subreal06=0;
    $subreal07=0;
    $subreal08=0;
    $subreal09=0;
    $subreal10=0;
    $subreal11=0;
    $subreal12=0;
    $subnontagih01=0;
    $subnontagih02=0;
    $subnontagih03=0;
    $subnontagih04=0;
    $subnontagih05=0;
    $subnontagih06=0;
    $subnontagih07=0;
    $subnontagih08=0;
    $subnontagih09=0;
    $subnontagih10=0;
    $subnontagih11=0;
    $subnontagih12=0;

    $grandtarget01=0;
    $grandtarget02=0;
    $grandtarget03=0;
    $grandtarget04=0;
    $grandtarget05=0;
    $grandtarget06=0;
    $grandtarget07=0;
    $grandtarget08=0;
    $grandtarget09=0;
    $grandtarget10=0;
    $grandtarget11=0;
    $grandtarget12=0;
    $grandreal01=0;
    $grandreal02=0;
    $grandreal03=0;
    $grandreal04=0;
    $grandreal05=0;
    $grandreal06=0;
    $grandreal07=0;
    $grandreal08=0;
    $grandreal09=0;
    $grandreal10=0;
    $grandreal11=0;
    $grandreal12=0;
    $grandnontagih01=0;
    $grandnontagih02=0;
    $grandnontagih03=0;
    $grandnontagih04=0;
    $grandnontagih05=0;
    $grandnontagih06=0;
    $grandnontagih07=0;
    $grandnontagih08=0;
    $grandnontagih09=0;
    $grandnontagih10=0;
    $grandnontagih11=0;
    $grandnontagih12=0;
    foreach($isi as $row){
      if($row->realisasi>0 && $row->total<>0){
        $persen=($row->realisasi*100)/$row->total;
      }else{
        $persen=0;
      }
      $nontagih=$row->total-$row->realisasi;
      if($row->total<>0){
        $persennontagih=($nontagih*100)/$row->total;
      }else{
        $persennontagih=0;
      }

      if($area==''){
        if($cust==''){
          if($row->bln==$bl){
            foreach($sumperiode as $tt){
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            echo '<tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            $bl=$blasal;
            for($i=1;$i<=$interval;$i++){
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                echo '<tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
#                break;
              }else{
                echo '<tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }elseif($cust==$row->i_customer){
          if($row->bln==$bl){
            foreach($sumperiode as $tt){
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            for($i=1;$i<=$interval;$i++){
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
                break;
              }else{
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }else{
          $bl=$blasal;
          if($row->bln==$bl){
            $akhir=($blasal+$interval)-1;
  #          echo 'akhir='.$akhir.'<br>';
            if($blakhir!=$akhir){
              while ($blakhir<$akhir){
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
                $blakhir++;
              }
            }
            if($blakhir>$akhir)$blakhir=$akhir;
            echo '</tr><tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
            foreach($sumperiode as $tt){
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            for($i=1;$i<=$interval;$i++){
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
                break;
              }elseif($bl==$blasal){
                $akhir=($blasal+$interval)-1;
                if($blakhir!=$akhir){
                  while ($blakhir<$akhir){
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0%</td>';
                    echo '<td align=right>0%</td>';
                    $blakhir++;
                  }
                }
                if($blakhir>$akhir)$blakhir=$akhir;
                echo '</tr><tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }else{
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }
      }elseif($area==substr($row->i_customer,0,2)){
######
        if($cust==''){
          if($row->bln==$bl){
            foreach($sumperiode as $tt){
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            echo '<tr><td></td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
            
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            $bl=$blasal;
            for($i=1;$i<=$interval;$i++){
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                echo '<tr><td></td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
                break;
              }else{
                echo '<tr><td></td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }elseif($cust==$row->i_customer){
#          if($row->i_customer=='03667')echo 'rowbln='.$row->bln.' || bl='.$bl.'<br>';
          if($row->bln==$bl){
            foreach($sumperiode as $tt){
#              if($row->i_customer=='03667')echo 'rowblnx='.$row->bln.' || ttbln='.$tt->bln.'<br>';
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            settype($row->bln,'integer');
#            $bl=$blasal;
            for($i=1;$i<=$interval;$i++){
#              if($row->i_customer=='02040')echo $row->bln.' - '.$bl.'<br>';
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
                break;
              }else{
                echo '<td align=right>qqq0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }else{
          $bl=$blasal;
          $akhir=($blasal+$interval)-1;
#          if($row->i_customer=='03740')echo 'blakhir='.$blakhir.' || akhir='.$akhir.'<br>';
#          if($row->i_customer=='03762')echo 'blakhir='.$blakhir.' || akhir='.$akhir.'<br>';
          if($row->bln==$bl){
#            $akhir=($blasal+$interval)-1;
            if($blakhir!=$akhir){
              while ($blakhir<$akhir){
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
                $blakhir++;
              }
            }
            if($blakhir>$akhir)$blakhir=$akhir;
            echo '</tr><tr><td></td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
            foreach($sumperiode as $tt){
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            for($i=1;$i<=$interval;$i++){
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
                break;
              }elseif($bl==$blasal){
                $akhir=($blasal+$interval)-1;
                if($blakhir!=$akhir){
                  while ($blakhir<$akhir){
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0%</td>';
                    echo '<td align=right>0%</td>';
                    $blakhir++;
                  }
                }
                if($blakhir>$akhir)$blakhir=$akhir;
                echo '</tr><tr><td></td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }else{
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }
######
      }else{
        if($cust==''){
          if($row->bln==$bl){
            foreach($sumperiode as $tt){
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            echo '<tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
            
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            $bl=$blasal;
            for($i=1;$i<=$interval;$i++){
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                echo '<tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
                break;
              }else{
                echo '<tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }elseif($cust==$row->i_customer){
          if($row->bln==$bl){
            foreach($sumperiode as $tt){
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            for($i=1;$i<=$interval;$i++){
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
                break;
              }else{
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }else{
          $bl=$blasal;
          if($row->bln==$bl){
            $akhir=($blasal+$interval)-1;
  #          echo 'akhir='.$akhir.'<br>';
            if($blakhir!=$akhir){
              while ($blakhir<$akhir){
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
                $blakhir++;
              }
            }
            if($blakhir>$akhir)$blakhir=$akhir;
#*#*            
            echo '</tr>';
            echo '<tr><td colspan=2><b>Sub Total</td>';
            $bl=$blasal;
            for($i=1;$i<=$interval;$i++){
              switch($bl){
              case '1':
                if($subtarget01>0){
                  $persen=($subreal01*100)/$subtarget01;
                }else{
                  $persen=0;
                }
                if($subtarget01>0){
                  $persennontagih=($subnontagih01*100)/$subtarget01;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget01).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal01).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih01).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '2':
                if($subtarget02>0){
                  $persen=($subreal02*100)/$subtarget02;
                }else{
                  $persen=0;
                }
                if($subtarget02>0){
                  $persennontagih=($subnontagih02*100)/$subtarget02;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget02).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal02).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih02).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '3':
                if($subtarget03>0){
                  $persen=($subreal03*100)/$subtarget03;
                }else{
                  $persen=0;
                }
                if($subtarget03>0){
                  $persennontagih=($subnontagih03*100)/$subtarget03;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget03).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal03).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih03).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '4':
                if($subtarget04>0){
                  $persen=($subreal04*100)/$subtarget04;
                }else{
                  $persen=0;
                }
                if($subtarget04>0){
                  $persennontagih=($subnontagih04*100)/$subtarget04;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget04).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal04).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih04).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '5':  
                if($subtarget05>0){
                  $persen=($subreal05*100)/$subtarget05;
                }else{
                  $persen=0;
                }
                if($subtarget05>0){
                  $persennontagih=($subnontagih05*100)/$subtarget05;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget05).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal05).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih05).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '6':
                if($subtarget06>0){
                  $persen=($subreal06*100)/$subtarget06;
                }else{
                  $persen=0;
                }
                if($subtarget06>0){
                  $persennontagih=($subnontagih06*100)/$subtarget06;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget06).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal06).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih06).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '7':
                if($subtarget07>0){
                  $persen=($subreal07*100)/$subtarget07;
                }else{
                  $persen=0;
                }
                if($subtarget07>0){
                  $persennontagih=($subnontagih07*100)/$subtarget07;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget07).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal07).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih07).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '8':
                if($subtarget08>0){
                  $persen=($subreal08*100)/$subtarget08;
                }else{
                  $persen=0;
                }
                if($subtarget08>0){
                  $persennontagih=($subnontagih08*100)/$subtarget08;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget08).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal08).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih08).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '9':
                if($subtarget09>0){
                  $persen=($subreal09*100)/$subtarget09;
                }else{
                  $persen=0;
                }
                if($subtarget09>0){
                  $persennontagih=($subnontagih09*100)/$subtarget09;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget09).'</td>';
                echo '<td align=right><b>'.number_format($subreal09).'</td>';
                echo '<td align=right<b>>'.number_format($subnontagih09).'</td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</td>';
                break;
              case '10':
                if($subtarget10>0){
                  $persen=($subreal10*100)/$subtarget10;
                }else{
                  $persen=0;
                }
                if($subtarget10>0){
                  $persennontagih=($subnontagih10*100)/$subtarget10;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget10).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal10).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih10).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '11':
                if($subtarget11>0){
                  $persen=($subreal11*100)/$subtarget11;
                }else{
                  $persen=0;
                }
                if($subtarget11>0){
                  $persennontagih=($subnontagih11*100)/$subtarget11;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget11).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal11).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih11).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              case '12':  
                if($subtarget12>0){
                  $persen=($subreal12*100)/$subtarget12;
                }else{
                  $persen=0;
                }
                if($subtarget12>0){
                  $persennontagih=($subnontagih12*100)/$subtarget12;
                }else{
                  $persennontagih=0;
                }
                echo '<td align=right><b>'.number_format($subtarget12).'</b></td>';
                echo '<td align=right><b>'.number_format($subreal12).'</b></td>';
                echo '<td align=right><b>'.number_format($subnontagih12).'</b></td>';
                echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
                echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
                break;
              }
              $bl++;
            }
            $bl=$blasal;
            $subtarget01=0;
            $subtarget02=0;
            $subtarget03=0;
            $subtarget04=0;
            $subtarget05=0;
            $subtarget06=0;
            $subtarget07=0;
            $subtarget08=0;
            $subtarget09=0;
            $subtarget10=0;
            $subtarget11=0;
            $subtarget12=0;
            $subreal01=0;
            $subreal02=0;
            $subreal03=0;
            $subreal04=0;
            $subreal05=0;
            $subreal06=0;
            $subreal07=0;
            $subreal08=0;
            $subreal09=0;
            $subreal10=0;
            $subreal11=0;
            $subreal12=0;
            $subnontagih01=0;
            $subnontagih02=0;
            $subnontagih03=0;
            $subnontagih04=0;
            $subnontagih05=0;
            $subnontagih06=0;
            $subnontagih07=0;
            $subnontagih08=0;
            $subnontagih09=0;
            $subnontagih10=0;
            $subnontagih11=0;
            $subnontagih12=0;
        
#*#*            
            echo '</tr><tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
            foreach($sumperiode as $tt){
              if($row->bln==$tt->bln){
                $totaltarget=$tt->total;
                break;
              }
            }
            $persentarget=($row->total/$totaltarget)*100;
            
            echo '<td align=right>'.number_format($row->total).'</td>';
            echo '<td align=right>'.number_format($row->realisasi).'</td>';
            echo '<td align=right>'.number_format($nontagih).'</td>';
            echo '<td align=right>'.number_format($persen,2).'%</td>';
            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
            switch ($row->bln){
            case '1' :
              $subtarget01=$subtarget01+$row->total;
              $subreal01=$subreal01+$row->realisasi;
              $subnontagih01=$subnontagih01+$nontagih;
              $grandtarget01=$grandtarget01+$row->total;
              $grandreal01=$grandreal01+$row->realisasi;
              $grandnontagih01=$grandnontagih01+$nontagih;
              break;
            case '2' :
              $subtarget02=$subtarget02+$row->total;
              $subreal02=$subreal02+$row->realisasi;
              $subnontagih02=$subnontagih02+$nontagih;
              $grandtarget02=$grandtarget02+$row->total;
              $grandreal02=$grandreal02+$row->realisasi;
              $grandnontagih02=$grandnontagih02+$nontagih;
              break;
            case '3' :
              $subtarget03=$subtarget03+$row->total;
              $subreal03=$subreal03+$row->realisasi;
              $subnontagih03=$subnontagih03+$nontagih;
              $grandtarget03=$grandtarget03+$row->total;
              $grandreal03=$grandreal03+$row->realisasi;
              $grandnontagih03=$grandnontagih03+$nontagih;
              break;
            case '4' :
              $subtarget04=$subtarget04+$row->total;
              $subreal04=$subreal04+$row->realisasi;
              $subnontagih04=$subnontagih04+$nontagih;
              $grandtarget04=$grandtarget04+$row->total;
              $grandreal04=$grandreal04+$row->realisasi;
              $grandnontagih04=$grandnontagih04+$nontagih;
              break;
            case '5' :
              $subtarget05=$subtarget05+$row->total;
              $subreal05=$subreal05+$row->realisasi;
              $subnontagih05=$subnontagih05+$nontagih;
              $grandtarget05=$grandtarget05+$row->total;
              $grandreal05=$grandreal05+$row->realisasi;
              $grandnontagih05=$grandnontagih05+$nontagih;
              break;
            case '6' :
              $subtarget06=$subtarget06+$row->total;
              $subreal06=$subreal06+$row->realisasi;
              $subnontagih06=$subnontagih06+$nontagih;
              $grandtarget06=$grandtarget06+$row->total;
              $grandreal06=$grandreal06+$row->realisasi;
              $grandnontagih06=$grandnontagih06+$nontagih;
              break;
            case '7' :
              $subtarget07=$subtarget07+$row->total;
              $subreal07=$subreal07+$row->realisasi;
              $subnontagih07=$subnontagih07+$nontagih;
              $grandtarget07=$grandtarget07+$row->total;
              $grandreal07=$grandreal07+$row->realisasi;
              $grandnontagih07=$grandnontagih07+$nontagih;
              break;
            case '8' :
              $subtarget08=$subtarget08+$row->total;
              $subreal08=$subreal08+$row->realisasi;
              $subnontagih08=$subnontagih08+$nontagih;
              $grandtarget08=$grandtarget08+$row->total;
              $grandreal08=$grandreal08+$row->realisasi;
              $grandnontagih08=$grandnontagih08+$nontagih;
              break;
            case '9' :
              $subtarget09=$subtarget09+$row->total;
              $subreal09=$subreal09+$row->realisasi;
              $subnontagih09=$subnontagih09+$nontagih;
              $grandtarget09=$grandtarget09+$row->total;
              $grandreal09=$grandreal09+$row->realisasi;
              $grandnontagih09=$grandnontagih09+$nontagih;
              break;
            case '10' :
              $subtarget10=$subtarget10+$row->total;
              $subreal10=$subreal10+$row->realisasi;
              $subnontagih10=$subnontagih10+$nontagih;
              $grandtarget10=$grandtarget10+$row->total;
              $grandreal10=$grandreal10+$row->realisasi;
              $grandnontagih10=$grandnontagih10+$nontagih;
              break;
            case '11' :
              $subtarget11=$subtarget11+$row->total;
              $subreal11=$subreal11+$row->realisasi;
              $subnontagih11=$subnontagih11+$nontagih;
              $grandtarget11=$grandtarget11+$row->total;
              $grandreal11=$grandreal11+$row->realisasi;
              $grandnontagih11=$grandnontagih11+$nontagih;
              break;
            case '12' :
              $subtarget12=$subtarget12+$row->total;
              $subreal12=$subreal12+$row->realisasi;
              $subnontagih12=$subnontagih12+$nontagih;
              $grandtarget12=$grandtarget12+$row->total;
              $grandreal12=$grandreal12+$row->realisasi;
              $grandnontagih12=$grandnontagih12+$nontagih;
              break;
            }
            $blakhir=$bl;
          }else{
            for($i=1;$i<=$interval;$i++){
              if($row->bln==$bl){
                foreach($sumperiode as $tt){
                  if($row->bln==$tt->bln){
                    $totaltarget=$tt->total;
                    break;
                  }
                }
                $persentarget=($row->total/$totaltarget)*100;
                
                echo '<td align=right>'.number_format($row->total).'</td>';
                echo '<td align=right>'.number_format($row->realisasi).'</td>';
                echo '<td align=right>'.number_format($nontagih).'</td>';
                echo '<td align=right>'.number_format($persen,2).'%</td>';
                echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                switch ($row->bln){
                case '1' :
                  $subtarget01=$subtarget01+$row->total;
                  $subreal01=$subreal01+$row->realisasi;
                  $subnontagih01=$subnontagih01+$nontagih;
                  $grandtarget01=$grandtarget01+$row->total;
                  $grandreal01=$grandreal01+$row->realisasi;
                  $grandnontagih01=$grandnontagih01+$nontagih;
                  break;
                case '2' :
                  $subtarget02=$subtarget02+$row->total;
                  $subreal02=$subreal02+$row->realisasi;
                  $subnontagih02=$subnontagih02+$nontagih;
                  $grandtarget02=$grandtarget02+$row->total;
                  $grandreal02=$grandreal02+$row->realisasi;
                  $grandnontagih02=$grandnontagih02+$nontagih;
                  break;
                case '3' :
                  $subtarget03=$subtarget03+$row->total;
                  $subreal03=$subreal03+$row->realisasi;
                  $subnontagih03=$subnontagih03+$nontagih;
                  $grandtarget03=$grandtarget03+$row->total;
                  $grandreal03=$grandreal03+$row->realisasi;
                  $grandnontagih03=$grandnontagih03+$nontagih;
                  break;
                case '4' :
                  $subtarget04=$subtarget04+$row->total;
                  $subreal04=$subreal04+$row->realisasi;
                  $subnontagih04=$subnontagih04+$nontagih;
                  $grandtarget04=$grandtarget04+$row->total;
                  $grandreal04=$grandreal04+$row->realisasi;
                  $grandnontagih04=$grandnontagih04+$nontagih;
                  break;
                case '5' :
                  $subtarget05=$subtarget05+$row->total;
                  $subreal05=$subreal05+$row->realisasi;
                  $subnontagih05=$subnontagih05+$nontagih;
                  $grandtarget05=$grandtarget05+$row->total;
                  $grandreal05=$grandreal05+$row->realisasi;
                  $grandnontagih05=$grandnontagih05+$nontagih;
                  break;
                case '6' :
                  $subtarget06=$subtarget06+$row->total;
                  $subreal06=$subreal06+$row->realisasi;
                  $subnontagih06=$subnontagih06+$nontagih;
                  $grandtarget06=$grandtarget06+$row->total;
                  $grandreal06=$grandreal06+$row->realisasi;
                  $grandnontagih06=$grandnontagih06+$nontagih;
                  break;
                case '7' :
                  $subtarget07=$subtarget07+$row->total;
                  $subreal07=$subreal07+$row->realisasi;
                  $subnontagih07=$subnontagih07+$nontagih;
                  $grandtarget07=$grandtarget07+$row->total;
                  $grandreal07=$grandreal07+$row->realisasi;
                  $grandnontagih07=$grandnontagih07+$nontagih;
                  break;
                case '8' :
                  $subtarget08=$subtarget08+$row->total;
                  $subreal08=$subreal08+$row->realisasi;
                  $subnontagih08=$subnontagih08+$nontagih;
                  $grandtarget08=$grandtarget08+$row->total;
                  $grandreal08=$grandreal08+$row->realisasi;
                  $grandnontagih08=$grandnontagih08+$nontagih;
                  break;
                case '9' :
                  $subtarget09=$subtarget09+$row->total;
                  $subreal09=$subreal09+$row->realisasi;
                  $subnontagih09=$subnontagih09+$nontagih;
                  $grandtarget09=$grandtarget09+$row->total;
                  $grandreal09=$grandreal09+$row->realisasi;
                  $grandnontagih09=$grandnontagih09+$nontagih;
                  break;
                case '10' :
                  $subtarget10=$subtarget10+$row->total;
                  $subreal10=$subreal10+$row->realisasi;
                  $subnontagih10=$subnontagih10+$nontagih;
                  $grandtarget10=$grandtarget10+$row->total;
                  $grandreal10=$grandreal10+$row->realisasi;
                  $grandnontagih10=$grandnontagih10+$nontagih;
                  break;
                case '11' :
                  $subtarget11=$subtarget11+$row->total;
                  $subreal11=$subreal11+$row->realisasi;
                  $subnontagih11=$subnontagih11+$nontagih;
                  $grandtarget11=$grandtarget11+$row->total;
                  $grandreal11=$grandreal11+$row->realisasi;
                  $grandnontagih11=$grandnontagih11+$nontagih;
                  break;
                case '12' :
                  $subtarget12=$subtarget12+$row->total;
                  $subreal12=$subreal12+$row->realisasi;
                  $subnontagih12=$subnontagih12+$nontagih;
                  $grandtarget12=$grandtarget12+$row->total;
                  $grandreal12=$grandreal12+$row->realisasi;
                  $grandnontagih12=$grandnontagih12+$nontagih;
                  break;
                }
                $blakhir=$bl;
                break;
              }elseif($bl==$blasal){
                $akhir=($blasal+$interval)-1;
                if($blakhir!=$akhir){
                  while ($blakhir<$akhir){
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0</td>';
                    echo '<td align=right>0%</td>';
                    echo '<td align=right>0%</td>';
                    $blakhir++;
                  }
                }
                if($blakhir>$akhir)$blakhir=$akhir;
                echo '</tr><tr><td>'.substr($row->i_customer,0,2).'</td><td>'.$row->i_customer.' - '.$row->e_customer_name.'</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }else{
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0</td>';
                echo '<td align=right>0%</td>';
                echo '<td align=right>0%</td>';
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
        }
      }
      $cust=$row->i_customer;
      $area=substr($row->i_customer,0,2);
      $bl++;
      if($bl>($interval+$blasal))$bl=1;
    }
    echo '</tr>';
    echo '<tr><td colspan=2><b>Sub Total</b></td>';
    $bl=$blasal;
    for($i=1;$i<=$interval;$i++){
      switch($bl){
      case '1':
        if($subtarget01>0){
          $persen=($subreal01*100)/$subtarget01;
        }else{
          $persen=0;
        }
        if($subtarget01>0){
          $persennontagih=($subnontagih01*100)/$subtarget01;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget01).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal01).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih01).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '2':
        if($subtarget02>0){
          $persen=($subreal02*100)/$subtarget02;
        }else{
          $persen=0;
        }
        if($subtarget02>0){
          $persennontagih=($subnontagih02*100)/$subtarget02;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget02).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal02).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih02).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '3':
        if($subtarget03>0){
          $persen=($subreal03*100)/$subtarget03;
        }else{
          $persen=0;
        }
        if($subtarget03>0){
          $persennontagih=($subnontagih03*100)/$subtarget03;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget03).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal03).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih03).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '4':
        if($subtarget04>0){
          $persen=($subreal04*100)/$subtarget04;
        }else{
          $persen=0;
        }
        if($subtarget04>0){
          $persennontagih=($subnontagih04*100)/$subtarget04;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget04).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal04).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih04).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '5':  
        if($subtarget05>0){
          $persen=($subreal05*100)/$subtarget05;
        }else{
          $persen=0;
        }
        if($subtarget05>0){
          $persennontagih=($subnontagih05*100)/$subtarget05;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget05).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal05).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih05).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '6':
        if($subtarget06>0){
          $persen=($subreal06*100)/$subtarget06;
        }else{
          $persen=0;
        }
        if($subtarget06>0){
          $persennontagih=($subnontagih06*100)/$subtarget06;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget06).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal06).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih06).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '7':
        if($subtarget07>0){
          $persen=($subreal07*100)/$subtarget07;
        }else{
          $persen=0;
        }
        if($subtarget07>0){
          $persennontagih=($subnontagih07*100)/$subtarget07;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget07).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal07).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih07).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '8':
        if($subtarget08>0){
          $persen=($subreal08*100)/$subtarget08;
        }else{
          $persen=0;
        }
        if($subtarget08>0){
          $persennontagih=($subnontagih08*100)/$subtarget08;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget08).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal08).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih08).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '9':
        if($subtarget09>0){
          $persen=($subreal09*100)/$subtarget09;
        }else{
          $persen=0;
        }
        if($subtarget09>0){
          $persennontagih=($subnontagih09*100)/$subtarget09;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget09).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal09).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih09).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '10':
        if($subtarget10>0){
          $persen=($subreal10*100)/$subtarget10;
        }else{
          $persen=0;
        }
        if($subtarget10>0){
          $persennontagih=($subnontagih10*100)/$subtarget10;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget10).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal10).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih10).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '11':
        if($subtarget11>0){
          $persen=($subreal11*100)/$subtarget11;
        }else{
          $persen=0;
        }
        if($subtarget11>0){
          $persennontagih=($subnontagih11*100)/$subtarget11;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget11).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal11).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih11).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '12':  
        if($subtarget12>0){
          $persen=($subreal12*100)/$subtarget12;
        }else{
          $persen=0;
        }
        if($subtarget12>0){
          $persennontagih=($subnontagih12*100)/$subtarget12;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($subtarget12).'</b></td>';
        echo '<td align=right><b>'.number_format($subreal12).'</b></td>';
        echo '<td align=right><b>'.number_format($subnontagih12).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      }
      $bl++;
    }
    echo '</tr>';
    echo '<tr><td colspan=2><b>Total</b></td>';
    $bl=$blasal;
    for($i=1;$i<=$interval;$i++){
      switch($bl){
      case '1':
        if($grandtarget01>0){
          $persen=($grandreal01*100)/$grandtarget01;
        }else{
          $persen=0;
        }
        if($grandtarget01>0){
          $persennontagih=($grandnontagih01*100)/$grandtarget01;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget01).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal01).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih01).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '2':
        if($grandtarget02>0){
          $persen=($grandreal02*100)/$grandtarget02;
        }else{
          $persen=0;
        }
        if($grandtarget02>0){
          $persennontagih=($grandnontagih02*100)/$grandtarget02;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget02).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal02).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih02).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '3':
        if($grandtarget03>0){
          $persen=($grandreal03*100)/$grandtarget03;
        }else{
          $persen=0;
        }
        if($grandtarget03>0){
          $persennontagih=($grandnontagih03*100)/$grandtarget03;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget03).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal03).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih03).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '4':
        if($grandtarget04>0){
          $persen=($grandreal04*100)/$grandtarget04;
        }else{
          $persen=0;
        }
        if($grandtarget04>0){
          $persennontagih=($grandnontagih04*100)/$grandtarget04;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget04).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal04).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih04).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '5':  
        if($grandtarget05>0){
          $persen=($grandreal05*100)/$grandtarget05;
        }else{
          $persen=0;
        }
        if($grandtarget05>0){
          $persennontagih=($grandnontagih05*100)/$grandtarget05;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget05).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal05).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih05).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '6':
        if($grandtarget06>0){
          $persen=($grandreal06*100)/$grandtarget06;
        }else{
          $persen=0;
        }
        if($grandtarget06>0){
          $persennontagih=($grandnontagih06*100)/$grandtarget06;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget06).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal06).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih06).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '7':
        if($grandtarget07>0){
          $persen=($grandreal07*100)/$grandtarget07;
        }else{
          $persen=0;
        }
        if($grandtarget07>0){
          $persennontagih=($grandnontagih07*100)/$grandtarget07;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget07).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal07).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih07).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '8':
        if($grandtarget08>0){
          $persen=($grandreal08*100)/$grandtarget08;
        }else{
          $persen=0;
        }
        if($grandtarget08>0){
          $persennontagih=($grandnontagih08*100)/$grandtarget08;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget08).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal08).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih08).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '9':
        if($grandtarget09>0){
          $persen=($grandreal09*100)/$grandtarget09;
        }else{
          $persen=0;
        }
        if($grandtarget09>0){
          $persennontagih=($grandnontagih09*100)/$grandtarget09;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget09).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal09).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih09).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '10':
        if($grandtarget10>0){
          $persen=($grandreal10*100)/$grandtarget10;
        }else{
          $persen=0;
        }
        if($grandtarget10>0){
          $persennontagih=($grandnontagih10*100)/$grandtarget10;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget10).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal10).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih10).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '11':
        if($grandtarget11>0){
          $persen=($grandreal11*100)/$grandtarget11;
        }else{
          $persen=0;
        }
        if($grandtarget11>0){
          $persennontagih=($grandnontagih11*100)/$grandtarget11;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget11).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal11).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih11).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      case '12':  
        if($grandtarget12>0){
          $persen=($grandreal12*100)/$grandtarget12;
        }else{
          $persen=0;
        }
        if($grandtarget12>0){
          $persennontagih=($grandnontagih12*100)/$grandtarget12;
        }else{
          $persennontagih=0;
        }
        echo '<td align=right><b>'.number_format($grandtarget12).'</b></td>';
        echo '<td align=right><b>'.number_format($grandreal12).'</b></td>';
        echo '<td align=right><b>'.number_format($grandnontagih12).'</b></td>';
        echo '<td align=right><b>'.number_format($persen,2).'%</b></td>';
        echo '<td align=right><b>'.number_format($persennontagih,2).'%</b></td>';
        break;
      }
      $bl++;
    }
  }
        ?>
      </tbody>
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