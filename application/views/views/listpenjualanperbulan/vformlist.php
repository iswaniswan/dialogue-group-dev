<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
      <a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
        class="fa fa-arrow-circle-o-left"></i> &nbsp;<?= "Kembali"; ?></a>
      </div>
      <div class="panel-body table-responsive">
        <div id="pesan"></div>
        <table class="table color-table info-table hover-table" id="sitabel">
          <thead>
            <?php
            if($isi){
              ?>
              <tr>
                <th rowspan="2">AREA</th>
                <th rowspan="2">K-LANG</th>
                <th rowspan="2">KOTA/KAB</th>
                <th rowspan="2">JENIS</th>
                <th rowspan="2">NAMA LANG</th>
                <th rowspan="2">ALAMAT</th>
                <?php 
                if($dfrom!=''){
                  $tmp=explode("-",$dfrom);
                  $blasal=$tmp[1];
                  settype($bl,'integer');
                }
                $bl=$blasal;
                $col=$interval;
                ?>
                <th colspan=<?php echo $col; ?> align=center>Nota</th>
                <?php 
                echo '<th rowspan=2>Total Nota(NETTO)</th>';
                ?>
              </tr>
              <tr>
                <?php 
                for($i=1;$i<=$interval;$i++){
                  switch ($bl){
                    case '1' :
                    echo '<th>Jan(netto)</th>';
                    break;
                    case '2' :
                    echo '<th>Feb(netto)</th>';
                    break;
                    case '3' :
                    echo '<th>Mar(netto)</th>';
                    break;
                    case '4' :
                    echo '<th>Apr(netto)</th>';
                    break;
                    case '5' :
                    echo '<th>Mei(netto)</th>';
                    break;
                    case '6' :
                    echo '<th>Jun(netto)</th>';
                    break;
                    case '7' :
                    echo '<th>Jul(netto)</th>';
                    break;
                    case '8' :
                    echo '<th>Agu(netto)</th>';
                    break;
                    case '9' :
                    echo '<th>Sep(netto)</th>';
                    break;
                    case '10' :
                    echo '<th>Okt(netto)</th>';
                    break;
                    case '11' :
                    echo '<th>Nov(netto)</th>';
                    break;
                    case '12' :
                    echo '<th>Des(netto)</th>';
                    break;
                  }
                  $bl++;
                  if($bl==13)$bl=1;
                }
                ?>
              </tr>
            </thead>
            <tbody><?php 

            $subtot01=0;
            $subtot02=0;
            $subtot03=0;
            $subtot04=0;
            $subtot05=0;
            $subtot06=0;
            $subtot07=0;
            $subtot08=0;
            $subtot09=0;
            $subtot10=0;
            $subtot11=0;
            $subtot12=0;
            $grandtot01=0;
            $grandtot02=0;
            $grandtot03=0;
            $grandtot04=0;
            $grandtot05=0;
            $grandtot06=0;
            $grandtot07=0;
            $grandtot08=0;
            $grandtot09=0;
            $grandtot10=0;
            $grandtot11=0;
            $grandtot12=0;
            $totarea01=0;
            $totarea02=0;
            $totarea03=0;
            $totarea04=0;
            $totarea05=0;
            $totarea06=0;
            $totarea07=0;
            $totarea08=0;
            $totarea09=0;
            $totarea10=0;
            $totarea11=0;
            $totarea12=0;
            $icity='';
            $kode='';
            $totkota=0;
            $totarea=0;
            $grandtotkota=0;
  //-----------------NETTO-----------------
            $subtot01netto=0;
            $subtot02netto=0;
            $subtot03netto=0;
            $subtot04netto=0;
            $subtot05netto=0;
            $subtot06netto=0;
            $subtot07netto=0;
            $subtot08netto=0;
            $subtot09netto=0;
            $subtot10netto=0;
            $subtot11netto=0;
            $subtot12netto=0;
            $grandtot01netto=0;
            $grandtot02netto=0;
            $grandtot03netto=0;
            $grandtot04netto=0;
            $grandtot05netto=0;
            $grandtot06netto=0;
            $grandtot07netto=0;
            $grandtot08netto=0;
            $grandtot09netto=0;
            $grandtot10netto=0;
            $grandtot11netto=0;
            $grandtot12netto=0;
            $totarea01netto=0;
            $totarea02netto=0;
            $totarea03netto=0;
            $totarea04netto=0;
            $totarea05netto=0;
            $totarea06netto=0;
            $totarea07netto=0;
            $totarea08netto=0;
            $totarea09netto=0;
            $totarea10netto=0;
            $totarea11netto=0;
            $totarea12netto=0;
            $icity='';
            $kode='';
            $totkotanetto=0;
            $totareanetto=0;
            $grandtotkotanetto=0;

            foreach($isi as $row){
              $total=0;
              $totalnetto=0;
              if($icity=='' || ($icity==$row->icity && $kode==substr($row->kode,0,2)) ){
                echo "<tr>
                <td>".substr($row->kode,0,2)."-".$row->area."</td>
                <td>$row->kode</td>
                <td>$row->kota</td>
                <td>$row->jenis</td>
                <td>$row->nama</td>
                <td>$row->alamat</td>";
                $bl=$blasal;
                for($i=1;$i<=$interval;$i++){
                  switch ($bl){
                    case '1' :
            //--------------NETTO---------------------//
                    $totalnetto=$totalnetto+$row->notajannet;
                    echo '<th align=right>'.number_format($row->notajannet).'</th>';
                    $subtot01netto=$subtot01netto+$row->notajannet;
                    $totarea01netto=$totarea01netto+$row->notajannet;
                    $grandtot01netto=$grandtot01netto+$row->notajannet;
                    $totkotanetto=$totkotanetto+$row->notajannet;
                    $totareanetto=$totareanetto+$row->notajannet;

                    break;
                    case '2' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notafebnet;
                    echo '<th align=right>'.number_format($row->notafebnet).'</th>';
                    $subtot02netto=$subtot02netto+$row->notafebnet;
                    $totarea02netto=$totarea02netto+$row->notafebnet;
                    $grandtot02netto=$grandtot02netto+$row->notafebnet;
                    $totkotanetto=$totkotanetto+$row->notafebnet;
                    $totareanetto=$totareanetto+$row->notafebnet;
                    break;
                    case '3' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notamarnet;
                    echo '<th align=right>'.number_format($row->notamarnet).'</th>';
                    $subtot03netto=$subtot03netto+$row->notamarnet;
                    $totarea03netto=$totarea03netto+$row->notamarnet;
                    $grandtot03netto=$grandtot03netto+$row->notamarnet;
                    $totkotanetto=$totkotanetto+$row->notamarnet;
                    $totareanetto=$totareanetto+$row->notamarnet;
                    break;
                    case '4' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notaaprnet;
                    echo '<th align=right>'.number_format($row->notaaprnet).'</th>';
                    $subtot04netto=$subtot04netto+$row->notaaprnet;
                    $totarea04netto=$totarea04netto+$row->notaaprnet;
                    $grandtot04netto=$grandtot04netto+$row->notaaprnet;
                    $totkotanetto=$totkotanetto+$row->notaaprnet;
                    $totareanetto=$totareanetto+$row->notaaprnet;
                    break;
                    case '5' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notamaynet;
                    echo '<th align=right>'.number_format($row->notamaynet).'</th>';
                    $subtot05netto=$subtot05netto+$row->notamaynet;
                    $totarea05netto=$totarea05netto+$row->notamaynet;
                    $grandtot05netto=$grandtot05netto+$row->notamaynet;
                    $totkotanetto=$totkotanetto+$row->notamaynet;
                    $totareanetto=$totareanetto+$row->notamaynet;
                    break;
                    case '6' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notajunnet;
                    echo '<th align=right>'.number_format($row->notajunnet).'</th>';
                    $subtot06netto=$subtot06netto+$row->notajunnet;
                    $totarea06netto=$totarea06netto+$row->notajunnet;
                    $grandtot06netto=$grandtot06netto+$row->notajunnet;
                    $totkotanetto=$totkotanetto+$row->notajunnet;
                    $totareanetto=$totareanetto+$row->notajunnet;
                    break;
                    case '7' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notajulnet;
                    echo '<th align=right>'.number_format($row->notajulnet).'</th>';
                    $subtot07netto=$subtot07netto+$row->notajulnet;
                    $totarea07netto=$totarea07netto+$row->notajulnet;
                    $grandtot07netto=$grandtot07netto+$row->notajulnet;
                    $totkotanetto=$totkotanetto+$row->notajulnet;
                    $totareanetto=$totareanetto+$row->notajulnet;
                    break;
                    case '8' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notaaugnet;
                    echo '<th align=right>'.number_format($row->notaaugnet).'</th>';
                    $subtot08netto=$subtot08netto+$row->notaaugnet;
                    $totarea08netto=$totarea08netto+$row->notaaugnet;
                    $grandtot08netto=$grandtot08netto+$row->notaaugnet;
                    $totkotanetto=$totkotanetto+$row->notaaugnet;
                    $totareanetto=$totareanetto+$row->notaaugnet;
                    break;
                    case '9' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notasepnet;
                    echo '<th align=right>'.number_format($row->notasepnet).'</th>';
                    $subtot09netto=$subtot09netto+$row->notasepnet;
                    $totarea09netto=$totarea09netto+$row->notasepnet;
                    $grandtot09netto=$grandtot09netto+$row->notasepnet;
                    $totkotanetto=$totkotanetto+$row->notasepnet;
                    $totareanetto=$totareanetto+$row->notasepnet;
                    break;
                    case '10' :
            //---------------NETTO------------------//
                    $total=$totalnetto+$row->notaoctnet;
                    echo '<th align=right>'.number_format($row->notaoctnet).'</th>';
                    $subtot10netto=$subtot10netto+$row->notaoctnet;
                    $totarea10netto=$totarea10netto+$row->notaoctnet;
                    $grandtot10netto=$grandtot10netto+$row->notaoctnet;
                    $totkotanetto=$totkotanetto+$row->notaoctnet;
                    $totareanetto=$totareanetto+$row->notaoctnet;
                    break;
                    case '11' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notanovnet;
                    echo '<th align=right>'.number_format($row->notanovnet).'</th>';
                    $subtot11netto=$subtot11netto+$row->notanovnet;
                    $totarea11netto=$totarea11netto+$row->notanovnet;
                    $grandtot11netto=$grandtot11netto+$row->notanovnet;
                    $totkotanetto=$totkotanetto+$row->notanovnet;
                    $totareanetto=$totareanetto+$row->notanovnet;
                    break;
                    case '12' :
            //---------------NETTO------------------//
                    $totalnetto=$totalnetto+$row->notadesnet;
                    echo '<th align=right>'.number_format($row->notadesnet).'</th>';
                    $subtot12netto=$subtot12netto+$row->notadesnet;
                    $totarea12netto=$totarea12netto+$row->notadesnet;
                    $grandtot12netto=$grandtot12netto+$row->notadesnet;
                    $totkotanetto=$totkotanetto+$row->notadesnet;
                    $totareanetto=$totareanetto+$row->notadesnet;
                    break;
                  }
                  $bl++;
                  if($bl==13)$bl=1;
                }
              }elseif( $kode!=substr($row->kode,0,2) ){
                echo "<tr>
                <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   K o t a</td>";
                $bl=$blasal;
                for($i=1;$i<=$interval;$i++){
                  switch ($bl){
                    case '1' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot01netto).'</th>';
                    break;
                    case '2' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot02netto).'</th>';
                    break;
                    case '3' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot03netto).'</th>';
                    break;
                    case '4' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot04netto).'</th>';
                    break;
                    case '5' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot05netto).'</th>';
                    break;
                    case '6' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot06netto).'</th>';
                    break;
                    case '7' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot07netto).'</th>';
                    break;
                    case '8' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot08netto).'</th>';
                    break;
                    case '9' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot09netto).'</th>';
                    break;
                    case '10' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot10netto).'</th>';
                    break;
                    case '11' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot11netto).'</th>';
                    break;
                    case '12' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot12netto).'</th>';
                    break;
                  }
                  $bl++;
                  if($bl==13)$bl=1;
                }
                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totkotanetto).'</th></tr>';
                $grandtotkotanetto=$grandtotkotanetto+$totkotanetto;
                echo "<tr>
                <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
                $bl=$blasal;
                for($i=1;$i<=$interval;$i++){
                  switch ($bl){
                    case '1' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea01netto).'</th>';
                    break;
                    case '2' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea02netto).'</th>';
                    break;
                    case '3' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea03netto).'</th>';
                    break;
                    case '4' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea04netto).'</th>';
                    break;
                    case '5' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea05netto).'</th>';
                    break;
                    case '6' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea06netto).'</th>';
                    break;
                    case '7' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea07netto).'</th>';
                    break;
                    case '8' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea08netto).'</th>';
                    break;
                    case '9' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea09netto).'</th>';
                    break;
                    case '10' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea10netto).'</th>';
                    break;
                    case '11' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea11netto).'</th>';
                    break;
                    case '12' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea12netto).'</th>';
                    break;
                  }
                  $bl++;
                  if($bl==13)$bl=1;
                }
                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totareanetto).'</th></tr>';
                $totarea01netto=0;
                $totarea02netto=0;
                $totarea03netto=0;
                $totarea04netto=0;
                $totarea05netto=0;
                $totarea06netto=0;
                $totarea07netto=0;
                $totarea08netto=0;
                $totarea09netto=0;
                $totarea10netto=0;
                $totarea11netto=0;
                $totarea12netto=0;
                $totareanetto=0;
      //---------NETTO-----------------//
                $subtot01netto=0;
                $subtot02netto=0;
                $subtot03netto=0;
                $subtot04netto=0;
                $subtot05netto=0;
                $subtot06netto=0;
                $subtot07netto=0;
                $subtot08netto=0;
                $subtot09netto=0;
                $subtot10netto=0;
                $subtot11netto=0;
                $subtot12netto=0;
                $totkotanetto=0;
                echo "<tr>
                <td>".substr($row->kode,0,2)."-".$row->area."</td>
                <td>$row->kode</td>
                <td>$row->kota</td>
                <td>$row->jenis</td>
                <td>$row->nama</td>
                <td>$row->alamat</td>";
                $bl=$blasal;
                for($i=1;$i<=$interval;$i++){
                 switch ($bl){
                  case '1' :
            //--------------NETTO---------------------//
                  $totalnetto=$totalnetto+$row->notajannet;
                  echo '<th align=right>'.number_format($row->notajannet).'</th>';
                  $subtot01netto=$subtot01netto+$row->notajannet;
                  $totarea01netto=$totarea01netto+$row->notajannet;
                  $grandtot01netto=$grandtot01netto+$row->notajannet;
                  $totkotanetto=$totkotanetto+$row->notajannet;
                  $totareanetto=$totareanetto+$row->notajannet;

                  break;
                  case '2' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notafebnet;
                  echo '<th align=right>'.number_format($row->notafebnet).'</th>';
                  $subtot02netto=$subtot02netto+$row->notafebnet;
                  $totarea02netto=$totarea02netto+$row->notafebnet;
                  $grandtot02netto=$grandtot02netto+$row->notafebnet;
                  $totkotanetto=$totkotanetto+$row->notafebnet;
                  $totareanetto=$totareanetto+$row->notafebnet;
                  break;
                  case '3' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notamarnet;
                  echo '<th align=right>'.number_format($row->notamarnet).'</th>';
                  $subtot03netto=$subtot03netto+$row->notamarnet;
                  $totarea03netto=$totarea03netto+$row->notamarnet;
                  $grandtot03netto=$grandtot03netto+$row->notamarnet;
                  $totkotanetto=$totkotanetto+$row->notamarnet;
                  $totareanetto=$totareanetto+$row->notamarnet;
                  break;
                  case '4' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notaaprnet;
                  echo '<th align=right>'.number_format($row->notaaprnet).'</th>';
                  $subtot04netto=$subtot04netto+$row->notaaprnet;
                  $totarea04netto=$totarea04netto+$row->notaaprnet;
                  $grandtot04netto=$grandtot04netto+$row->notaaprnet;
                  $totkotanetto=$totkotanetto+$row->notaaprnet;
                  $totareanetto=$totareanetto+$row->notaaprnet;
                  break;
                  case '5' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notamaynet;
                  echo '<th align=right>'.number_format($row->notamaynet).'</th>';
                  $subtot05netto=$subtot05netto+$row->notamaynet;
                  $totarea05netto=$totarea05netto+$row->notamaynet;
                  $grandtot05netto=$grandtot05netto+$row->notamaynet;
                  $totkotanetto=$totkotanetto+$row->notamaynet;
                  $totareanetto=$totareanetto+$row->notamaynet;
                  break;
                  case '6' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notajunnet;
                  echo '<th align=right>'.number_format($row->notajunnet).'</th>';
                  $subtot06netto=$subtot06netto+$row->notajunnet;
                  $totarea06netto=$totarea06netto+$row->notajunnet;
                  $grandtot06netto=$grandtot06netto+$row->notajunnet;
                  $totkotanetto=$totkotanetto+$row->notajunnet;
                  $totareanetto=$totareanetto+$row->notajunnet;
                  break;
                  case '7' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notajulnet;
                  echo '<th align=right>'.number_format($row->notajulnet).'</th>';
                  $subtot07netto=$subtot07netto+$row->notajulnet;
                  $totarea07netto=$totarea07netto+$row->notajulnet;
                  $grandtot07netto=$grandtot07netto+$row->notajulnet;
                  $totkotanetto=$totkotanetto+$row->notajulnet;
                  $totareanetto=$totareanetto+$row->notajulnet;
                  break;
                  case '8' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notaaugnet;
                  echo '<th align=right>'.number_format($row->notaaugnet).'</th>';
                  $subtot08netto=$subtot08netto+$row->notaaugnet;
                  $totarea08netto=$totarea08netto+$row->notaaugnet;
                  $grandtot08netto=$grandtot08netto+$row->notaaugnet;
                  $totkotanetto=$totkotanetto+$row->notaaugnet;
                  $totareanetto=$totareanetto+$row->notaaugnet;
                  break;
                  case '9' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notasepnet;
                  echo '<th align=right>'.number_format($row->notasepnet).'</th>';
                  $subtot09netto=$subtot09netto+$row->notasepnet;
                  $totarea09netto=$totarea09netto+$row->notasepnet;
                  $grandtot09netto=$grandtot09netto+$row->notasepnet;
                  $totkotanetto=$totkotanetto+$row->notasepnet;
                  $totareanetto=$totareanetto+$row->notasepnet;
                  break;
                  case '10' :
            //---------------NETTO------------------//
                  $total=$totalnetto+$row->notaoctnet;
                  echo '<th align=right>'.number_format($row->notaoctnet).'</th>';
                  $subtot10netto=$subtot10netto+$row->notaoctnet;
                  $totarea10netto=$totarea10netto+$row->notaoctnet;
                  $grandtot10netto=$grandtot10netto+$row->notaoctnet;
                  $totkotanetto=$totkotanetto+$row->notaoctnet;
                  $totareanetto=$totareanetto+$row->notaoctnet;
                  break;
                  case '11' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notanovnet;
                  echo '<th align=right>'.number_format($row->notanovnet).'</th>';
                  $subtot11netto=$subtot11netto+$row->notanovnet;
                  $totarea11netto=$totarea11netto+$row->notanovnet;
                  $grandtot11netto=$grandtot11netto+$row->notanovnet;
                  $totkotanetto=$totkotanetto+$row->notanovnet;
                  $totareanetto=$totareanetto+$row->notanovnet;
                  break;
                  case '12' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notadesnet;
                  echo '<th align=right>'.number_format($row->notadesnet).'</th>';
                  $subtot12netto=$subtot12netto+$row->notadesnet;
                  $totarea12netto=$totarea12netto+$row->notadesnet;
                  $grandtot12netto=$grandtot12netto+$row->notadesnet;
                  $totkotanetto=$totkotanetto+$row->notadesnet;
                  $totareanetto=$totareanetto+$row->notadesnet;
                  break;
                }
                $bl++;
                if($bl==13)$bl=1;
              }
            }elseif( ($icity!='' && $icity!=$row->icity) ){
              echo "<tr>
              <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   K o t a</td>";
              $bl=$blasal;
              for($i=1;$i<=$interval;$i++){
                switch ($bl){
                  case '1' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot01netto).'</th>';
                  break;
                  case '2' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot02netto).'</th>';
                  break;
                  case '3' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot03netto).'</th>';
                  break;
                  case '4' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot04netto).'</th>';
                  break;
                  case '5' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot05netto).'</th>';
                  break;
                  case '6' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot06netto).'</th>';
                  break;
                  case '7' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot07netto).'</th>';
                  break;
                  case '8' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot08netto).'</th>';
                  break;
                  case '9' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot09netto).'</th>';
                  break;
                  case '10' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot10netto).'</th>';
                  break;
                  case '11' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot11netto).'</th>';
                  break;
                  case '12' :
                  echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot12netto).'</th>';
                  break;
                }
                $bl++;
                if($bl==13)$bl=1;
              }
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totkotanetto).'</th></tr>';
              $grandtotkotanetto=$grandtotkotanetto+$totkotanetto;
              if($kode!=substr($row->kode,0,2)){
                echo "<tr>
                <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
                $bl=$blasal;
                for($i=1;$i<=$interval;$i++){
                  switch ($bl){
                    case '1' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea01netto).'</th>';
                    break;
                    case '2' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea02netto).'</th>';
                    break;
                    case '3' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea03netto).'</th>';
                    break;
                    case '4' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea04netto).'</th>';
                    break;
                    case '5' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea05netto).'</th>';
                    break;
                    case '6' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea06netto).'</th>';
                    break;
                    case '7' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea07netto).'</th>';
                    break;
                    case '8' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea08netto).'</th>';
                    break;
                    case '9' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea09netto).'</th>';
                    break;
                    case '10' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea10netto).'</th>';
                    break;
                    case '11' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea11netto).'</th>';
                    break;
                    case '12' :
                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea12netto).'</th>';
                    break;
                  }
                  $bl++;
                  if($bl==13)$bl=1;
                }
                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</th></tr>';
                $totarea01netto=0;
                $totarea02netto=0;
                $totarea03netto=0;
                $totarea04netto=0;
                $totarea05netto=0;
                $totarea06netto=0;
                $totarea07netto=0;
                $totarea08netto=0;
                $totarea09netto=0;
                $totarea10netto=0;
                $totarea11netto=0;
                $totarea12netto=0;
                $totareanetto=0;
              }
        //---------NETTO-----------------//
              $subtot01netto=0;
              $subtot02netto=0;
              $subtot03netto=0;
              $subtot04netto=0;
              $subtot05netto=0;
              $subtot06netto=0;
              $subtot07netto=0;
              $subtot08netto=0;
              $subtot09netto=0;
              $subtot10netto=0;
              $subtot11netto=0;
              $subtot12netto=0;
              $totkotanetto=0;

              echo "<tr>
              <td>".substr($row->kode,0,2)."-".$row->area."</td>
              <td>$row->kode</td>
              <td>$row->kota</td>
              <td>$row->jenis</td>
              <td>$row->nama</td>
              <td>$row->alamat</td>";
              $bl=$blasal;
              for($i=1;$i<=$interval;$i++){
                switch ($bl){
                  case '1' :
            //--------------NETTO---------------------//
                  $totalnetto=$totalnetto+$row->notajannet;
                  echo '<th align=right>'.number_format($row->notajannet).'</th>';
                  $subtot01netto=$subtot01netto+$row->notajannet;
                  $totarea01netto=$totarea01netto+$row->notajannet;
                  $grandtot01netto=$grandtot01netto+$row->notajannet;
                  $totkotanetto=$totkotanetto+$row->notajannet;
                  $totareanetto=$totareanetto+$row->notajannet;

                  break;
                  case '2' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notafebnet;
                  echo '<th align=right>'.number_format($row->notafebnet).'</th>';
                  $subtot02netto=$subtot02netto+$row->notafebnet;
                  $totarea02netto=$totarea02netto+$row->notafebnet;
                  $grandtot02netto=$grandtot02netto+$row->notafebnet;
                  $totkotanetto=$totkotanetto+$row->notafebnet;
                  $totareanetto=$totareanetto+$row->notafebnet;
                  break;
                  case '3' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notamarnet;
                  echo '<th align=right>'.number_format($row->notamarnet).'</th>';
                  $subtot03netto=$subtot03netto+$row->notamarnet;
                  $totarea03netto=$totarea03netto+$row->notamarnet;
                  $grandtot03netto=$grandtot03netto+$row->notamarnet;
                  $totkotanetto=$totkotanetto+$row->notamarnet;
                  $totareanetto=$totareanetto+$row->notamarnet;
                  break;
                  case '4' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notaaprnet;
                  echo '<th align=right>'.number_format($row->notaaprnet).'</th>';
                  $subtot04netto=$subtot04netto+$row->notaaprnet;
                  $totarea04netto=$totarea04netto+$row->notaaprnet;
                  $grandtot04netto=$grandtot04netto+$row->notaaprnet;
                  $totkotanetto=$totkotanetto+$row->notaaprnet;
                  $totareanetto=$totareanetto+$row->notaaprnet;
                  break;
                  case '5' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notamaynet;
                  echo '<th align=right>'.number_format($row->notamaynet).'</th>';
                  $subtot05netto=$subtot05netto+$row->notamaynet;
                  $totarea05netto=$totarea05netto+$row->notamaynet;
                  $grandtot05netto=$grandtot05netto+$row->notamaynet;
                  $totkotanetto=$totkotanetto+$row->notamaynet;
                  $totareanetto=$totareanetto+$row->notamaynet;
                  break;
                  case '6' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notajunnet;
                  echo '<th align=right>'.number_format($row->notajunnet).'</th>';
                  $subtot06netto=$subtot06netto+$row->notajunnet;
                  $totarea06netto=$totarea06netto+$row->notajunnet;
                  $grandtot06netto=$grandtot06netto+$row->notajunnet;
                  $totkotanetto=$totkotanetto+$row->notajunnet;
                  $totareanetto=$totareanetto+$row->notajunnet;
                  break;
                  case '7' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notajulnet;
                  echo '<th align=right>'.number_format($row->notajulnet).'</th>';
                  $subtot07netto=$subtot07netto+$row->notajulnet;
                  $totarea07netto=$totarea07netto+$row->notajulnet;
                  $grandtot07netto=$grandtot07netto+$row->notajulnet;
                  $totkotanetto=$totkotanetto+$row->notajulnet;
                  $totareanetto=$totareanetto+$row->notajulnet;
                  break;
                  case '8' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notaaugnet;
                  echo '<th align=right>'.number_format($row->notaaugnet).'</th>';
                  $subtot08netto=$subtot08netto+$row->notaaugnet;
                  $totarea08netto=$totarea08netto+$row->notaaugnet;
                  $grandtot08netto=$grandtot08netto+$row->notaaugnet;
                  $totkotanetto=$totkotanetto+$row->notaaugnet;
                  $totareanetto=$totareanetto+$row->notaaugnet;
                  break;
                  case '9' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notasepnet;
                  echo '<th align=right>'.number_format($row->notasepnet).'</th>';
                  $subtot09netto=$subtot09netto+$row->notasepnet;
                  $totarea09netto=$totarea09netto+$row->notasepnet;
                  $grandtot09netto=$grandtot09netto+$row->notasepnet;
                  $totkotanetto=$totkotanetto+$row->notasepnet;
                  $totareanetto=$totareanetto+$row->notasepnet;
                  break;
                  case '10' :
            //---------------NETTO------------------//
                  $total=$totalnetto+$row->notaoctnet;
                  echo '<th align=right>'.number_format($row->notaoctnet).'</th>';
                  $subtot10netto=$subtot10netto+$row->notaoctnet;
                  $totarea10netto=$totarea10netto+$row->notaoctnet;
                  $grandtot10netto=$grandtot10netto+$row->notaoctnet;
                  $totkotanetto=$totkotanetto+$row->notaoctnet;
                  $totareanetto=$totareanetto+$row->notaoctnet;
                  break;
                  case '11' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notanovnet;
                  echo '<th align=right>'.number_format($row->notanovnet).'</th>';
                  $subtot11netto=$subtot11netto+$row->notanovnet;
                  $totarea11netto=$totarea11netto+$row->notanovnet;
                  $grandtot11netto=$grandtot11netto+$row->notanovnet;
                  $totkotanetto=$totkotanetto+$row->notanovnet;
                  $totareanetto=$totareanetto+$row->notanovnet;
                  break;
                  case '12' :
            //---------------NETTO------------------//
                  $totalnetto=$totalnetto+$row->notadesnet;
                  echo '<th align=right>'.number_format($row->notadesnet).'</th>';
                  $subtot12netto=$subtot12netto+$row->notadesnet;
                  $totarea12netto=$totarea12netto+$row->notadesnet;
                  $grandtot12netto=$grandtot12netto+$row->notadesnet;
                  $totkotanetto=$totkotanetto+$row->notadesnet;
                  $totareanetto=$totareanetto+$row->notadesnet;
                  break;
                }
                $bl++;
                if($bl==13)$bl=1;
              }
            }
            echo '<th align=right>'.number_format($totalnetto).'</th>';
            echo "</tr>";
            $icity=$row->icity;
            $kode=substr($row->kode,0,2);
          }
          echo "<tr>
          <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   K o t a</td>";
          $bl=$blasal;
          for($i=1;$i<=$interval;$i++){
            switch ($bl){
              case '1' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot01netto).'</th>';
              break;
              case '2' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot02netto).'</th>';
              break;
              case '3' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot03netto).'</th>';
              break;
              case '4' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot04netto).'</th>';
              break;
              case '5' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot05netto).'</th>';
              break;
              case '6' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot06netto).'</th>';
              break;
              case '7' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot07netto).'</th>';
              break;
              case '8' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot08netto).'</th>';
              break;
              case '9' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot09netto).'</th>';
              break;
              case '10' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot10netto).'</th>';
              break;
              case '11' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot11netto).'</th>';
              break;
              case '12' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot12netto).'</th>';
              break;
            }
            $bl++;
            if($bl==13)$bl=1;
          }
          echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totkotanetto).'</th></tr>';
          $grandtotkotanetto=$grandtotkotanetto+$totkotanetto;

          echo "<tr>
          <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
          $bl=$blasal;
          for($i=1;$i<=$interval;$i++){
            switch ($bl){
              case '1' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea01netto).'</th>';
              break;
              case '2' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea02netto).'</th>';
              break;
              case '3' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea03netto).'</th>';
              break;
              case '4' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea04netto).'</th>';
              break;
              case '5' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea05netto).'</th>';
              break;
              case '6' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea06netto).'</th>';
              break;
              case '7' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea07netto).'</th>';
              break;
              case '8' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea08netto).'</th>';
              break;
              case '9' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea09netto).'</th>';
              break;
              case '10' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea10netto).'</th>';
              break;
              case '11' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea11netto).'</th>';
              break;
              case '12' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea12netto).'</th>';
              break;
            }
            $bl++;
            if($bl==13)$bl=1;
          }
          echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totareanetto).'</th></tr>';

          echo "<tr>
          <td style='background-color:#F2F2F2;' colspan=6 align=center>G r a n d   T o t a l</td>";
          $bl=$blasal;
          for($i=1;$i<=$interval;$i++){
            switch ($bl){
              case '1' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot01netto).'</th>';
              break;
              case '2' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot02netto).'</th>';
              break;
              case '3' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot03netto).'</th>';
              break;
              case '4' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot04netto).'</th>';
              break;
              case '5' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot05netto).'</th>';
              break;
              case '6' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot06netto).'</th>';
              break;
              case '7' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot07netto).'</th>';
              break;
              case '8' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot08netto).'</th>';
              break;
              case '9' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot09netto).'</th>';
              break;
              case '10' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot10netto).'</th>';
              break;
              case '11' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot11netto).'</th>';
              break;
              case '12' :
              echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot12netto).'</th>';
              break;
            }
            $bl++;
            if($bl==13)$bl=1;
          }
          echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtotkotanetto).'</th></tr>';
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
</form>
</div>
</div>

<script>
  $( "#cmdreset" ).click(function() {  
   var Contents = $('#sitabel').html();    
   window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
 });
</script>
