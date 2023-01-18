<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <!-- div awal -->
    <h3 class="box-title" style="text-align: center;"><?= $title; ?></h3>
    <p class="text-muted" style="text-align: center;"><?php echo 'Periode : '.substr($dfrom,0,2).' '.mbulan(substr($dfrom,3,2)).' '.substr($dfrom,6,4).' s/d '.substr($dto,0,2).' '.mbulan(substr($dto,3,2)).' '.substr($dto,6,4); ?></p>
    <div class="panel-body table-responsive">
        <table class="table color-bordered-table info-bordered-table" id="sitabel" cellpadding="0" cellspacing="0">
            <thead>
                <input name="dfrom" id="dfrom" value="<?php echo $dfrom; ?>" type="hidden" readonly>
                <input name="dto" id="dto" value="<?php echo $dto; ?>" type="hidden" readonly>
                <?php if($isi){ ?>
                    <tr>
	                    <th style="font-size: 12px;text-align: center;vertical-align: middle;" rowspan=2>Nama Sales</th>
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
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '2' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '3' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '4' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '5' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '6' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '7' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '8' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '9' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '10' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '11' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
                                      break;
                                    case '12' :
                                      echo '<th style="font-size: 12px;text-align: center;vertical-align: middle;">Target</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Realisasi</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">Tdk Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tertagih</th>
                                      <th style="font-size: 12px;text-align: center;vertical-align: middle;">% Tdk Tertagih</th>';
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
                $jenis='';
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
                    if($row->realisasi>0){
                      if($row->total!=0){
                        $persen=($row->realisasi*100)/$row->total;
                      }else{
                        $persen=0;
                      }
                    }else{
                      $persen=0;
                    }
                    $nontagih=$row->total-$row->realisasi;
                    if($row->total!=0){
                      $persennontagih=($nontagih*100)/$row->total;
                    }else{
                      $persen=0;
                    }
                    if($jenis==''){
                        if($row->bln==$bl){
                            foreach($sumperiode as $tt){
                                if($row->bln==$tt->bln){
                                  $totaltarget=$tt->total;
                                  break;
                                }
                            }
                            $persentarget=($row->total/$totaltarget)*100;
                            echo '<tr><td>'.$row->i_salesman.' - '.$row->e_salesman_name.'</td>';
                            echo '<td align=right>'.number_format($row->total).'</td>';
                            echo '<td align=right>'.number_format($row->realisasi).'</td>';
                            echo '<td align=right>'.number_format($nontagih).'</td>';
                            echo '<td align=right>'.number_format($persen,2).'%</td>';
                            echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                            switch ($row->bln){
                              case '1' :
                                $grandtarget01=$grandtarget01+$row->total;
                                $grandreal01=$grandreal01+$row->realisasi;
                                $grandnontagih01=$grandnontagih01+$nontagih;
                                break;
                              case '2' :
                                $grandtarget02=$grandtarget02+$row->total;
                                $grandreal02=$grandreal02+$row->realisasi;
                                $grandnontagih02=$grandnontagih02+$nontagih;
                                break;
                              case '3' :
                                $grandtarget03=$grandtarget03+$row->total;
                                $grandreal03=$grandreal03+$row->realisasi;
                                $grandnontagih03=$grandnontagih03+$nontagih;
                                break;
                              case '4' :
                                $grandtarget04=$grandtarget04+$row->total;
                                $grandreal04=$grandreal04+$row->realisasi;
                                $grandnontagih04=$grandnontagih04+$nontagih;
                                break;
                              case '5' :
                                $grandtarget05=$grandtarget05+$row->total;
                                $grandreal05=$grandreal05+$row->realisasi;
                                $grandnontagih05=$grandnontagih05+$nontagih;
                                break;
                              case '6' :
                                $grandtarget06=$grandtarget06+$row->total;
                                $grandreal06=$grandreal06+$row->realisasi;
                                $grandnontagih06=$grandnontagih06+$nontagih;
                                break;
                              case '7' :
                                $grandtarget07=$grandtarget07+$row->total;
                                $grandreal07=$grandreal07+$row->realisasi;
                                $grandnontagih07=$grandnontagih07+$nontagih;
                                break;
                              case '8' :
                                $grandtarget08=$grandtarget08+$row->total;
                                $grandreal08=$grandreal08+$row->realisasi;
                                $grandnontagih08=$grandnontagih08+$nontagih;
                                break;
                              case '9' :
                                $grandtarget09=$grandtarget09+$row->total;
                                $grandreal09=$grandreal09+$row->realisasi;
                                $grandnontagih09=$grandnontagih09+$nontagih;
                                break;
                              case '10' :
                                $grandtarget10=$grandtarget10+$row->total;
                                $grandreal10=$grandreal10+$row->realisasi;
                                $gra7ndnontagih10=$grandnontagih10+$nontagih;
                                break;
                              case '11' :
                                $grandtarget11=$grandtarget11+$row->total;
                                $grandreal11=$grandreal11+$row->realisasi;
                                $grandnontagih11=$grandnontagih11+$nontagih;
                                break;
                              case '12' :
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
                                    echo '<tr><td>'.$row->i_salesman.' - '.$row->e_salesman_name.'</td>';
                                    echo '<td align=right>'.number_format($row->total).'</td>';
                                    echo '<td align=right>'.number_format($row->realisasi).'</td>';
                                    echo '<td align=right>'.number_format($nontagih).'</td>';
                                    echo '<td align=right>'.number_format($persen,2).'%</td>';
                                    echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                                    switch ($row->bln){
                                      case '1' :
                                        $grandtarget01=$grandtarget01+$row->total;
                                        $grandreal01=$grandreal01+$row->realisasi;
                                        $grandnontagih01=$grandnontagih01+$nontagih;
                                        break;
                                      case '2' :
                                        $grandtarget02=$grandtarget02+$row->total;
                                        $grandreal02=$grandreal02+$row->realisasi;
                                        $grandnontagih02=$grandnontagih02+$nontagih;
                                        break;
                                      case '3' :
                                        $grandtarget03=$grandtarget03+$row->total;
                                        $grandreal03=$grandreal03+$row->realisasi;
                                        $grandnontagih03=$grandnontagih03+$nontagih;
                                        break;
                                      case '4' :
                                        $grandtarget04=$grandtarget04+$row->total;
                                        $grandreal04=$grandreal04+$row->realisasi;
                                        $grandnontagih04=$grandnontagih04+$nontagih;
                                        break;
                                      case '5' :
                                        $grandtarget05=$grandtarget05+$row->total;
                                        $grandreal05=$grandreal05+$row->realisasi;
                                        $grandnontagih05=$grandnontagih05+$nontagih;
                                        break;
                                      case '6' :
                                        $grandtarget06=$grandtarget06+$row->total;
                                        $grandreal06=$grandreal06+$row->realisasi;
                                        $grandnontagih06=$grandnontagih06+$nontagih;
                                        break;
                                      case '7' :
                                        $grandtarget07=$grandtarget07+$row->total;
                                        $grandreal07=$grandreal07+$row->realisasi;
                                        $grandnontagih07=$grandnontagih07+$nontagih;
                                        break;
                                      case '8' :
                                        $grandtarget08=$grandtarget08+$row->total;
                                        $grandreal08=$grandreal08+$row->realisasi;
                                        $grandnontagih08=$grandnontagih08+$nontagih;
                                        break;
                                      case '9' :
                                        $grandtarget09=$grandtarget09+$row->total;
                                        $grandreal09=$grandreal09+$row->realisasi;
                                        $grandnontagih09=$grandnontagih09+$nontagih;
                                        break;
                                      case '10' :
                                        $grandtarget10=$grandtarget10+$row->total;
                                        $grandreal10=$grandreal10+$row->realisasi;
                                        $grandnontagih10=$grandnontagih10+$nontagih;
                                        break;
                                      case '11' :
                                        $grandtarget11=$grandtarget11+$row->total;
                                        $grandreal11=$grandreal11+$row->realisasi;
                                        $grandnontagih11=$grandnontagih11+$nontagih;
                                        break;
                                      case '12' :
                                        $grandtarget12=$grandtarget12+$row->total;
                                        $grandreal12=$grandreal12+$row->realisasi;
                                        $grandnontagih12=$grandnontagih12+$nontagih;
                                        break;
                                    }
                                    $blakhir=$bl;
                                    break;
                                }else{
                                    echo '<tr><td>'.$row->i_salesman.' - '.$row->e_salesman_name.'</td>';
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
                    }elseif ($jenis==$row->i_salesman){
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
                                $grandtarget01=$grandtarget01+$row->total;
                                $grandreal01=$grandreal01+$row->realisasi;
                                $grandnontagih01=$grandnontagih01+$nontagih;
                                break;
                              case '2' :
                                $grandtarget02=$grandtarget02+$row->total;
                                $grandreal02=$grandreal02+$row->realisasi;
                                $grandnontagih02=$grandnontagih02+$nontagih;
                                break;
                              case '3' :
                                $grandtarget03=$grandtarget03+$row->total;
                                $grandreal03=$grandreal03+$row->realisasi;
                                $grandnontagih03=$grandnontagih03+$nontagih;
                                break;
                              case '4' :
                                $grandtarget04=$grandtarget04+$row->total;
                                $grandreal04=$grandreal04+$row->realisasi;
                                $grandnontagih04=$grandnontagih04+$nontagih;
                                break;
                              case '5' :
                                $grandtarget05=$grandtarget05+$row->total;
                                $grandreal05=$grandreal05+$row->realisasi;
                                $grandnontagih05=$grandnontagih05+$nontagih;
                                break;
                              case '6' :
                                $grandtarget06=$grandtarget06+$row->total;
                                $grandreal06=$grandreal06+$row->realisasi;
                                $grandnontagih06=$grandnontagih06+$nontagih;
                                break;
                              case '7' :
                                $grandtarget07=$grandtarget07+$row->total;
                                $grandreal07=$grandreal07+$row->realisasi;
                                $grandnontagih07=$grandnontagih07+$nontagih;
                                break;
                              case '8' :
                                $grandtarget08=$grandtarget08+$row->total;
                                $grandreal08=$grandreal08+$row->realisasi;
                                $grandnontagih08=$grandnontagih08+$nontagih;
                                break;
                              case '9' :
                                $grandtarget09=$grandtarget09+$row->total;
                                $grandreal09=$grandreal09+$row->realisasi;
                                $grandnontagih09=$grandnontagih09+$nontagih;
                                break;
                              case '10' :
                                $grandtarget10=$grandtarget10+$row->total;
                                $grandreal10=$grandreal10+$row->realisasi;
                                $grandnontagih10=$grandnontagih10+$nontagih;
                                break;
                              case '11' :
                                $grandtarget11=$grandtarget11+$row->total;
                                $grandreal11=$grandreal11+$row->realisasi;
                                $grandnontagih11=$grandnontagih11+$nontagih;
                                break;
                              case '12' :
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
                                        $grandtarget01=$grandtarget01+$row->total;
                                        $grandreal01=$grandreal01+$row->realisasi;
                                        $grandnontagih01=$grandnontagih01+$nontagih;
                                        break;
                                      case '2' :
                                        $grandtarget02=$grandtarget02+$row->total;
                                        $grandreal02=$grandreal02+$row->realisasi;
                                        $grandnontagih02=$grandnontagih02+$nontagih;
                                        break;
                                      case '3' :
                                        $grandtarget03=$grandtarget03+$row->total;
                                        $grandreal03=$grandreal03+$row->realisasi;
                                        $grandnontagih03=$grandnontagih03+$nontagih;
                                        break;
                                      case '4' :
                                        $grandtarget04=$grandtarget04+$row->total;
                                        $grandreal04=$grandreal04+$row->realisasi;
                                        $grandnontagih04=$grandnontagih04+$nontagih;
                                        break;
                                      case '5' :
                                        $grandtarget05=$grandtarget05+$row->total;
                                        $grandreal05=$grandreal05+$row->realisasi;
                                        $grandnontagih05=$grandnontagih05+$nontagih;
                                        break;
                                      case '6' :
                                        $grandtarget06=$grandtarget06+$row->total;
                                        $grandreal06=$grandreal06+$row->realisasi;
                                        $grandnontagih06=$grandnontagih06+$nontagih;
                                        break;
                                      case '7' :
                                        $grandtarget07=$grandtarget07+$row->total;
                                        $grandreal07=$grandreal07+$row->realisasi;
                                        $grandnontagih07=$grandnontagih07+$nontagih;
                                        break;
                                      case '8' :
                                        $grandtarget08=$grandtarget08+$row->total;
                                        $grandreal08=$grandreal08+$row->realisasi;
                                        $grandnontagih08=$grandnontagih08+$nontagih;
                                        break;
                                      case '9' :
                                        $grandtarget09=$grandtarget09+$row->total;
                                        $grandreal09=$grandreal09+$row->realisasi;
                                        $grandnontagih09=$grandnontagih09+$nontagih;
                                        break;
                                      case '10' :
                                        $grandtarget10=$grandtarget10+$row->total;
                                        $grandreal10=$grandreal10+$row->realisasi;
                                        $grandnontagih10=$grandnontagih10+$nontagih;
                                        break;
                                      case '11' :
                                        $grandtarget11=$grandtarget11+$row->total;
                                        $grandreal11=$grandreal11+$row->realisasi;
                                        $grandnontagih11=$grandnontagih11+$nontagih;
                                        break;
                                      case '12' :
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
                            echo '</tr><tr><td>'.$row->i_salesman.' - '.$row->e_salesman_name.'</td>';
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
                                $grandtarget01=$grandtarget01+$row->total;
                                $grandreal01=$grandreal01+$row->realisasi;
                                $grandnontagih01=$grandnontagih01+$nontagih;
                                break;
                              case '2' :
                                $grandtarget02=$grandtarget02+$row->total;
                                $grandreal02=$grandreal02+$row->realisasi;
                                $grandnontagih02=$grandnontagih02+$nontagih;
                                break;
                              case '3' :
                                $grandtarget03=$grandtarget03+$row->total;
                                $grandreal03=$grandreal03+$row->realisasi;
                                $grandnontagih03=$grandnontagih03+$nontagih;
                                break;
                              case '4' :
                                $grandtarget04=$grandtarget04+$row->total;
                                $grandreal04=$grandreal04+$row->realisasi;
                                $grandnontagih04=$grandnontagih04+$nontagih;
                                break;
                              case '5' :
                                $grandtarget05=$grandtarget05+$row->total;
                                $grandreal05=$grandreal05+$row->realisasi;
                                $grandnontagih05=$grandnontagih05+$nontagih;
                                break;
                              case '6' :
                                $grandtarget06=$grandtarget06+$row->total;
                                $grandreal06=$grandreal06+$row->realisasi;
                                $grandnontagih06=$grandnontagih06+$nontagih;
                                break;
                              case '7' :
                                $grandtarget07=$grandtarget07+$row->total;
                                $grandreal07=$grandreal07+$row->realisasi;
                                $grandnontagih07=$grandnontagih07+$nontagih;
                                break;
                              case '8' :
                                $grandtarget08=$grandtarget08+$row->total;
                                $grandreal08=$grandreal08+$row->realisasi;
                                $grandnontagih08=$grandnontagih08+$nontagih;
                                break;
                              case '9' :
                                $grandtarget09=$grandtarget09+$row->total;
                                $grandreal09=$grandreal09+$row->realisasi;
                                $grandnontagih09=$grandnontagih09+$nontagih;
                                break;
                              case '10' :
                                $grandtarget10=$grandtarget10+$row->total;
                                $grandreal10=$grandreal10+$row->realisasi;
                                $grandnontagih10=$grandnontagih10+$nontagih;
                                break;
                              case '11' :
                                $grandtarget11=$grandtarget11+$row->total;
                                $grandreal11=$grandreal11+$row->realisasi;
                                $grandnontagih11=$grandnontagih11+$nontagih;
                                break;
                              case '12' :
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
                                        $grandtarget01=$grandtarget01+$row->total;
                                        $grandreal01=$grandreal01+$row->realisasi;
                                        $grandnontagih01=$grandnontagih01+$nontagih;
                                        break;
                                      case '2' :
                                        $grandtarget02=$grandtarget02+$row->total;
                                        $grandreal02=$grandreal02+$row->realisasi;
                                        $grandnontagih02=$grandnontagih02+$nontagih;
                                        break;
                                      case '3' :
                                        $grandtarget03=$grandtarget03+$row->total;
                                        $grandreal03=$grandreal03+$row->realisasi;
                                        $grandnontagih03=$grandnontagih03+$nontagih;
                                        break;
                                      case '4' :
                                        $grandtarget04=$grandtarget04+$row->total;
                                        $grandreal04=$grandreal04+$row->realisasi;
                                        $grandnontagih04=$grandnontagih04+$nontagih;
                                        break;
                                      case '5' :
                                        $grandtarget05=$grandtarget05+$row->total;
                                        $grandreal05=$grandreal05+$row->realisasi;
                                        $grandnontagih05=$grandnontagih05+$nontagih;
                                        break;
                                      case '6' :
                                        $grandtarget06=$grandtarget06+$row->total;
                                        $grandreal06=$grandreal06+$row->realisasi;
                                        $grandnontagih06=$grandnontagih06+$nontagih;
                                        break;
                                      case '7' :
                                        $grandtarget07=$grandtarget07+$row->total;
                                        $grandreal07=$grandreal07+$row->realisasi;
                                        $grandnontagih07=$grandnontagih07+$nontagih;
                                        break;
                                      case '8' :
                                        $grandtarget08=$grandtarget08+$row->total;
                                        $grandreal08=$grandreal08+$row->realisasi;
                                        $grandnontagih08=$grandnontagih08+$nontagih;
                                        break;
                                      case '9' :
                                        $grandtarget09=$grandtarget09+$row->total;
                                        $grandreal09=$grandreal09+$row->realisasi;
                                        $grandnontagih09=$grandnontagih09+$nontagih;
                                        break;
                                      case '10' :
                                        $grandtarget10=$grandtarget10+$row->total;
                                        $grandreal10=$grandreal10+$row->realisasi;
                                        $grandnontagih10=$grandnontagih10+$nontagih;
                                        break;
                                      case '11' :
                                        $grandtarget11=$grandtarget11+$row->total;
                                        $grandreal11=$grandreal11+$row->realisasi;
                                        $grandnontagih11=$grandnontagih11+$nontagih;
                                        break;
                                      case '12' :
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
                                        while ($blakhir<=$akhir){
                                          echo '<td align=right>0</td>';
                                          echo '<td align=right>0</td>';
                                          echo '<td align=right>0</td>';
                                          echo '<td align=right>0%</td>';
                                          echo '<td align=right>xxx0%</td>';
                                            $blakhir++;
                                        }
                                    }
                                  echo '</tr><tr><td>'.$row->i_salesman.' - '.$row->e_salesman_name.'</td>';
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
                    $jenis=$row->i_salesman;
                    $bl++;
                    if($bl>($interval+$blasal))$bl=1;
                }
                echo '</tr>';
                echo '<tr><td><b>Total</td>';
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
                          echo '<td align=right>'.number_format($grandtarget01).'</td>';
                          echo '<td align=right>'.number_format($grandreal01).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih01).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget02).'</td>';
                          echo '<td align=right>'.number_format($grandreal02).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih02).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget03).'</td>';
                          echo '<td align=right>'.number_format($grandreal03).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih03).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget04).'</td>';
                          echo '<td align=right>'.number_format($grandreal04).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih04).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget05).'</td>';
                          echo '<td align=right>'.number_format($grandreal05).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih05).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget06).'</td>';
                          echo '<td align=right>'.number_format($grandreal06).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih06).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget07).'</td>';
                          echo '<td align=right>'.number_format($grandreal07).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih07).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget08).'</td>';
                          echo '<td align=right>'.number_format($grandreal08).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih08).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget09).'</td>';
                          echo '<td align=right>'.number_format($grandreal09).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih09).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget10).'</td>';
                          echo '<td align=right>'.number_format($grandreal10).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih10).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget11).'</td>';
                          echo '<td align=right>'.number_format($grandreal11).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih11).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
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
                          echo '<td align=right>'.number_format($grandtarget12).'</td>';
                          echo '<td align=right>'.number_format($grandreal12).'</td>';
                          echo '<td align=right>'.number_format($grandnontagih12).'</td>';
                          echo '<td align=right>'.number_format($persen,2).'%</td>';
                          echo '<td align=right>'.number_format($persennontagih,2).'%</td>';
                          break;
                    }
                    $bl++;
                }
            }
	        ?>
            </tbody>
            <!-- end if isi -->
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