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
                <?php if($isi){ ?>
                    <tr>
                        <th rowspan=2>AREA</th>
                        <th rowspan=2>K-LANG</th>
                        <th rowspan=2>KOTA/KAB</th>
                        <th rowspan=2>Divisi</th>
                        <th rowspan=2>JENIS</th>
                        <th rowspan=2>NAMA LANG</th>
                        <th rowspan=2>ALAMAT</th>
                        <th rowspan=2>Kode</th>
                        <th rowspan=2>Product</th>
                        <th rowspan=2>Supplier</th>
                        <?php 
                        if($dfrom!=''){
                            $tmp=explode("-",$dfrom);
                            $blasal=$tmp[1];
                            settype($bl,'integer');
                        }
                        $bl=$blasal;
                        ?>
                        <th colspan=<?php echo $interval; ?> align=center>Nota</th>
                        <?php 
                        echo '<th rowspan=2>Total Nota</th>';
                        ?>
                    </tr>
                    <tr>
                        <?php 
                        for($i=1;$i<=$interval;$i++){
                            switch ($bl){
                                case '1' :
                                echo '<th>Jan</th>';
                                break;
                                case '2' :
                                echo '<th>Feb</th>';
                                break;
                                case '3' :
                                echo '<th>Mar</th>';
                                break;
                                case '4' :
                                echo '<th>Apr</th>';
                                break;
                                case '5' :
                                echo '<th>Mei</th>';
                                break;
                                case '6' :
                                echo '<th>Jun</th>';
                                break;
                                case '7' :
                                echo '<th>Jul</th>';
                                break;
                                case '8' :
                                echo '<th>Agu</th>';
                                break;
                                case '9' :
                                echo '<th>Sep</th>';
                                break;
                                case '10' :
                                echo '<th>Okt</th>';
                                break;
                                case '11' :
                                echo '<th>Nov</th>';
                                break;
                                case '12' :
                                echo '<th>Des</th>';
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
                    foreach($isi as $row){
                        $total=0;
                        $totalnota=0;
                        if($icity=='' || ($icity==$row->icity && $kode==$row->iarea) ){
                            echo "<tr>
                            <td>".$row->iarea."-".$row->area."</td>
                            <td>$row->kode</td>
                            <td>$row->kota</td>
                            <td>$row->group</td>
                            <td>$row->jenis</td>
                            <td>$row->nama</td>
                            <td>$row->alamat</td>
                            <td>$row->product</td>
                            <td>$row->productname</td>
                            <td>$row->supplier</td>";
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    $total=$total+$row->notajan;
                                    $totalnota=$total*intval($row->notajan);
                                    echo '<th style="text-align: right">'.number_format($row->notajan).'</th>';
                                    $subtot01=$subtot01+$row->notajan;
                                    $totarea01=$totarea01+$row->notajan;
                                    $grandtot01=$grandtot01+$row->notajan;
                                    $totkota=$totkota+$row->notajan;
                                    $totarea=$totarea+$row->notajan;
                                    break;
                                    case '2' :
                                    $total=$total+$row->notafeb;
                                    $totalnota=$total*intval($row->notafeb);
                                    echo '<th style="text-align: right">'.number_format($row->notafeb).'</th>';
                                    $subtot02=$subtot02+$row->notafeb;
                                    $totarea02=$totarea02+$row->notafeb;
                                    $grandtot02=$grandtot02+$row->notafeb;
                                    $totkota=$totkota+$row->notafeb;
                                    $totarea=$totarea+$row->notafeb;
                                    break;
                                    case '3' :
                                    $total=$total+$row->notamar;
                                    $totalnota=$total*intval($row->notamar);
                                    echo '<th style="text-align: right">'.number_format($row->notamar).'</th>';
                                    $subtot03=$subtot03+$row->notamar;
                                    $totarea03=$totarea03+$row->notamar;
                                    $grandtot03=$grandtot03+$row->notamar;
                                    $totkota=$totkota+$row->notamar;
                                    $totarea=$totarea+$row->notamar;
                                    break;
                                    case '4' :
                                    $total=$total+$row->notaapr;
                                    $totalnota=$total*intval($row->notaapr);
                                    echo '<th style="text-align: right">'.number_format($row->notaapr).'</th>';
                                    $subtot04=$subtot04+$row->notaapr;
                                    $totarea04=$totarea04+$row->notaapr;
                                    $grandtot04=$grandtot04+$row->notaapr;
                                    $totkota=$totkota+$row->notaapr;
                                    $totarea=$totarea+$row->notaapr;
                                    break;
                                    case '5' :
                                    $total=$total+$row->notamay;
                                    $totalnota=$total*intval($row->notamay);
                                    echo '<th style="text-align: right">'.number_format($row->notamay).'</th>';
                                    $subtot05=$subtot05+$row->notamay;
                                    $totarea05=$totarea05+$row->notamay;
                                    $grandtot05=$grandtot05+$row->notamay;
                                    $totkota=$totkota+$row->notamay;
                                    $totarea=$totarea+$row->notamay;
                                    break;
                                    case '6' :
                                    $total=$total+$row->notajun;
                                    $totalnota=$total*intval($row->notajun);
                                    echo '<th style="text-align: right">'.number_format($row->notajun).'</th>';
                                    $subtot06=$subtot06+$row->notajun;
                                    $totarea06=$totarea06+$row->notajun;
                                    $grandtot06=$grandtot06+$row->notajun;
                                    $totkota=$totkota+$row->notajun;
                                    $totarea=$totarea+$row->notajun;
                                    break;
                                    case '7' :
                                    $total=$total+$row->notajul;
                                    $totalnota=$total*intval($row->notajul);
                                    echo '<th style="text-align: right">'.number_format($row->notajul).'</th>';
                                    $subtot07=$subtot07+$row->notajul;
                                    $totarea07=$totarea07+$row->notajul;
                                    $grandtot07=$grandtot07+$row->notajul;
                                    $totkota=$totkota+$row->notajul;
                                    $totarea=$totarea+$row->notajul;
                                    break;
                                    case '8' :
                                    $total=$total+$row->notaaug;
                                    $totalnota=$total*intval($row->notaaug);
                                    echo '<th style="text-align: right">'.number_format($row->notaaug).'</th>';
                                    $subtot08=$subtot08+$row->notaaug;
                                    $totarea08=$totarea08+$row->notaaug;
                                    $grandtot08=$grandtot08+$row->notaaug;
                                    $totkota=$totkota+$row->notaaug;
                                    $totarea=$totarea+$row->notaaug;
                                    break;
                                    case '9' :
                                    $total=$total+$row->notasep;
                                    $totalnota=$total*intval($row->notasep);
                                    echo '<th style="text-align: right">'.number_format($row->notasep).'</th>';
                                    $subtot09=$subtot09+$row->notasep;
                                    $totarea09=$totarea09+$row->notasep;
                                    $grandtot09=$grandtot09+$row->notasep;
                                    $totkota=$totkota+$row->notasep;
                                    $totarea=$totarea+$row->notasep;
                                    break;
                                    case '10' :
                                    $total=$total+$row->notaokt;
                                    $totalnota=$total*intval($row->notaokt);
                                    echo '<th style="text-align: right">'.number_format($row->notaokt).'</th>';
                                    $subtot10=$subtot10+$row->notaokt;
                                    $totarea10=$totarea10+$row->notaokt;
                                    $grandtot10=$grandtot10+$row->notaokt;
                                    $totkota=$totkota+$row->notaokt;
                                    $totarea=$totarea+$row->notaokt;
                                    break;
                                    case '11' :
                                    $total=$total+$row->notanov;
                                    $totalnota=$total*intval($row->notanov);
                                    echo '<th style="text-align: right">'.number_format($row->notanov).'</th>';
                                    $subtot11=$subtot11+$row->notanov;
                                    $totarea11=$totarea11+$row->notanov;
                                    $grandtot11=$grandtot11+$row->notanov;
                                    $totkota=$totkota+$row->notanov;
                                    $totarea=$totarea+$row->notanov;
                                    break;
                                    case '12' :
                                    $total=$total+$row->notades;
                                    $totalnota=$total*intval($row->notades);
                                    echo '<th style="text-align: right">'.number_format($row->notades).'</th>';
                                    $subtot12=$subtot12+$row->notades;
                                    $totarea12=$totarea12+$row->notades;
                                    $grandtot12=$grandtot12+$row->notades;
                                    $totkota=$totkota+$row->notades;
                                    $totarea=$totarea+$row->notades;
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                        }elseif( $kode!=$row->iarea ){
                            echo "<tr>
                            <td style='background-color:#F2F2F2;' colspan=10 align=center>T o t a l   K o t a</td>";
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot01).'</th>';
                                    break;
                                    case '2' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot02).'</th>';
                                    break;
                                    case '3' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot03).'</th>';
                                    break;
                                    case '4' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot04).'</th>';
                                    break;
                                    case '5' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot05).'</th>';
                                    break;
                                    case '6' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot06).'</th>';
                                    break;
                                    case '7' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot07).'</th>';
                                    break;
                                    case '8' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot08).'</th>';
                                    break;
                                    case '9' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot09).'</th>';
                                    break;
                                    case '10' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot10).'</th>';
                                    break;
                                    case '11' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot11).'</th>';
                                    break;
                                    case '12' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot12).'</th>';
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totkota).'</th></tr>';
                            $grandtotkota=$grandtotkota+$totkota;
                            echo "<tr>
                            <td style='background-color:#F2F2F2;' colspan=10 align=center>T o t a l   A r e a</td>";
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea01).'</th>';
                                    break;
                                    case '2' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea02).'</th>';
                                    break;
                                    case '3' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea03).'</th>';
                                    break;
                                    case '4' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea04).'</th>';
                                    break;
                                    case '5' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea05).'</th>';
                                    break;
                                    case '6' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea06).'</th>';
                                    break;
                                    case '7' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea07).'</th>';
                                    break;
                                    case '8' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea08).'</th>';
                                    break;
                                    case '9' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea09).'</th>';
                                    break;
                                    case '10' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea10).'</th>';
                                    break;
                                    case '11' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea11).'</th>';
                                    break;
                                    case '12' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea12).'</th>';
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea).'</th></tr>';
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
                            $vtotarea01=0;
                            $vtotarea02=0;
                            $vtotarea03=0;
                            $vtotarea04=0;
                            $vtotarea05=0;
                            $vtotarea06=0;
                            $vtotarea07=0;
                            $vtotarea08=0;
                            $vtotarea09=0;
                            $vtotarea10=0;
                            $vtotarea11=0;
                            $vtotarea12=0;
                            $totarea=0;
                            $vtotarea=0;
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
                            $totkota=0;
                            echo "<tr>
                            <td>".$row->iarea."-".$row->area."</td>
                            <td>$row->kode</td>
                            <td>$row->kota</td>
                            <td>$row->group</td>
                            <td>$row->jenis</td>
                            <td>$row->nama</td>
                            <td>$row->alamat</td>
                            <td>$row->product</td>
                            <td>$row->productname</td>
                            <td>$row->supplier</td>";
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    $total=$total+$row->notajan;
                                    $totalnota=$total*intval($row->notajan);
                                    echo '<th style="text-align: right">'.number_format($row->notajan).'</th>';
                                    $subtot01=$subtot01+$row->notajan;
                                    $totarea01=$totarea01+$row->notajan;
                                    $grandtot01=$grandtot01+$row->notajan;
                                    $totkota=$totkota+$row->notajan;
                                    $totarea=$totarea+$row->notajan;
                                    break;
                                    case '2' :
                                    $total=$total+$row->notafeb;
                                    $totalnota=$total*intval($row->notafeb);
                                    echo '<th style="text-align: right">'.number_format($row->notafeb).'</th>';
                                    $subtot02=$subtot02+$row->notafeb;
                                    $totarea02=$totarea02+$row->notafeb;
                                    $grandtot02=$grandtot02+$row->notafeb;
                                    $totkota=$totkota+$row->notafeb;
                                    $totarea=$totarea+$row->notafeb;
                                    break;
                                    case '3' :
                                    $total=$total+$row->notamar;
                                    $totalnota=$total*intval($row->notamar);
                                    echo '<th style="text-align: right">'.number_format($row->notamar).'</th>';
                                    $subtot03=$subtot03+$row->notamar;
                                    $totarea03=$totarea03+$row->notamar;
                                    $grandtot03=$grandtot03+$row->notamar;
                                    $totkota=$totkota+$row->notamar;
                                    $totarea=$totarea+$row->notamar;
                                    break;
                                    case '4' :
                                    $total=$total+$row->notaapr;
                                    $totalnota=$total*intval($row->notaapr);
                                    echo '<th style="text-align: right">'.number_format($row->notaapr).'</th>';
                                    $subtot04=$subtot04+$row->notaapr;
                                    $totarea04=$totarea04+$row->notaapr;
                                    $grandtot04=$grandtot04+$row->notaapr;
                                    $totkota=$totkota+$row->notaapr;
                                    $totarea=$totarea+$row->notaapr;
                                    break;
                                    case '5' :
                                    $total=$total+$row->notamay;
                                    $totalnota=$total*intval($row->notamay);
                                    echo '<th style="text-align: right">'.number_format($row->notamay).'</th>';
                                    $subtot05=$subtot05+$row->notamay;
                                    $totarea05=$totarea05+$row->notamay;
                                    $grandtot05=$grandtot05+$row->notamay;
                                    $totkota=$totkota+$row->notamay;
                                    $totarea=$totarea+$row->notamay;
                                    break;
                                    case '6' :
                                    $total=$total+$row->notajun;
                                    $totalnota=$total*intval($row->notajun);
                                    echo '<th style="text-align: right">'.number_format($row->notajun).'</th>';
                                    $subtot06=$subtot06+$row->notajun;
                                    $totarea06=$totarea06+$row->notajun;
                                    $grandtot06=$grandtot06+$row->notajun;
                                    $totkota=$totkota+$row->notajun;
                                    $totarea=$totarea+$row->notajun;
                                    break;
                                    case '7' :
                                    $total=$total+$row->notajul;
                                    $totalnota=$total*intval($row->notajul);
                                    echo '<th style="text-align: right">'.number_format($row->notajul).'</th>';
                                    $subtot07=$subtot07+$row->notajul;
                                    $totarea07=$totarea07+$row->notajul;
                                    $grandtot07=$grandtot07+$row->notajul;
                                    $totkota=$totkota+$row->notajul;
                                    $totarea=$totarea+$row->notajul;
                                    break;
                                    case '8' :
                                    $total=$total+$row->notaaug;
                                    $totalnota=$total*intval($row->notaaug);
                                    echo '<th style="text-align: right">'.number_format($row->notaaug).'</th>';
                                    $subtot08=$subtot08+$row->notaaug;
                                    $totarea08=$totarea08+$row->notaaug;
                                    $grandtot08=$grandtot08+$row->notaaug;
                                    $totkota=$totkota+$row->notaaug;
                                    $totarea=$totarea+$row->notaaug;
                                    break;
                                    case '9' :
                                    $total=$total+$row->notasep;
                                    $totalnota=$total*intval($row->notasep);
                                    echo '<th style="text-align: right">'.number_format($row->notasep).'</th>';
                                    $subtot09=$subtot09+$row->notasep;
                                    $totarea09=$totarea09+$row->notasep;
                                    $grandtot09=$grandtot09+$row->notasep;
                                    $totkota=$totkota+$row->notasep;
                                    $totarea=$totarea+$row->notasep;
                                    break;
                                    case '10' :
                                    $total=$total+$row->notaokt;
                                    $totalnota=$total*intval($row->notaokt);
                                    echo '<th style="text-align: right">'.number_format($row->notaokt).'</th>';
                                    $subtot10=$subtot10+$row->notaokt;
                                    $totarea10=$totarea10+$row->notaokt;
                                    $grandtot10=$grandtot10+$row->notaokt;
                                    $totkota=$totkota+$row->notaokt;
                                    $totarea=$totarea+$row->notaokt;
                                    break;
                                    case '11' :
                                    $total=$total+$row->notanov;
                                    $totalnota=$total*intval($row->notanov);
                                    echo '<th style="text-align: right">'.number_format($row->notanov).'</th>';
                                    $subtot11=$subtot11+$row->notanov;
                                    $totarea11=$totarea11+$row->notanov;
                                    $grandtot11=$grandtot11+$row->notanov;
                                    $totkota=$totkota+$row->notanov;
                                    $totarea=$totarea+$row->notanov;
                                    break;
                                    case '12' :
                                    $total=$total+$row->notades;
                                    $totalnota=$total*intval($row->notades);
                                    echo '<th style="text-align: right">'.number_format($row->notades).'</th>';
                                    $subtot12=$subtot12+$row->notades;
                                    $totarea12=$totarea12+$row->notades;
                                    $grandtot12=$grandtot12+$row->notades;
                                    $totkota=$totkota+$row->notades;
                                    $totarea=$totarea+$row->notades;
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                        }elseif( ($icity!='' && $icity!=$row->icity) ){
                            echo "<tr>
                            <td style='background-color:#F2F2F2;' colspan=10 align=center>T o t a l   K o t a</td>";
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot01).'</th>';
                                    break;
                                    case '2' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot02).'</th>';
                                    break;
                                    case '3' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot03).'</th>';
                                    break;
                                    case '4' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot04).'</th>';
                                    break;
                                    case '5' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot05).'</th>';
                                    break;
                                    case '6' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot06).'</th>';
                                    break;
                                    case '7' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot07).'</th>';
                                    break;
                                    case '8' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot08).'</th>';
                                    break;
                                    case '9' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot09).'</th>';
                                    break;
                                    case '10' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot10).'</th>';
                                    break;
                                    case '11' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot11).'</th>';
                                    break;
                                    case '12' :
                                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot12).'</th>';
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totkota).'</th></tr>';
                            $grandtotkota=$grandtotkota+$totkota;
                            if($kode!=$row->iarea){
                                echo "<tr>
                                <td style='background-color:#F2F2F2;' colspan=10 align=center>T o t a l   A r e a</td>";
                                $bl=$blasal;
                                for($i=1;$i<=$interval;$i++){
                                    switch ($bl){
                                        case '1' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea01).'</th>';
                                        break;
                                        case '2' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea02).'</th>';
                                        break;
                                        case '3' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea03).'</th>';
                                        break;
                                        case '4' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea04).'</th>';
                                        break;
                                        case '5' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea05).'</th>';
                                        break;
                                        case '6' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea06).'</th>';
                                        break;
                                        case '7' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea07).'</th>';
                                        break;
                                        case '8' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea08).'</th>';
                                        break;
                                        case '9' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea09).'</th>';
                                        break;
                                        case '10' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea10).'</th>';
                                        break;
                                        case '11' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea11).'</th>';
                                        break;
                                        case '12' :
                                        echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea12).'</th>';
                                        break;
                                    }
                                    $bl++;
                                    if($bl==13)$bl=1;
                                }
                                echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea).'</th></tr>';
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
                                $totarea=0;
                            }
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
                            $totkota=0;
                            echo "<tr>
                            <td>".$row->iarea."-".$row->area."</td>
                            <td>$row->kode</td>
                            <td>$row->kota</td>
                            <td>$row->group</td>
                            <td>$row->jenis</td>
                            <td>$row->nama</td>
                            <td>$row->alamat</td>
                            <td>$row->product</td>
                            <td>$row->productname</td>
                            <td>$row->supplier</td>";
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    $total=$total+$row->notajan;
                                    $totalnota=$total*intval($row->notajan);
                                    echo '<th style="text-align: right">'.number_format($row->notajan).'</th>';
                                    $subtot01=$subtot01+$row->notajan;
                                    $totarea01=$totarea01+$row->notajan;
                                    $grandtot01=$grandtot01+$row->notajan;
                                    $totkota=$totkota+$row->notajan;
                                    $totarea=$totarea+$row->notajan;
                                    break;
                                    case '2' :
                                    $total=$total+$row->notafeb;
                                    $totalnota=$total*intval($row->notafeb);
                                    echo '<th style="text-align: right">'.number_format($row->notafeb).'</th>';
                                    $subtot02=$subtot02+$row->notafeb;
                                    $totarea02=$totarea02+$row->notafeb;
                                    $grandtot02=$grandtot02+$row->notafeb;
                                    $totkota=$totkota+$row->notafeb;
                                    $totarea=$totarea+$row->notafeb;
                                    break;
                                    case '3' :
                                    $total=$total+$row->notamar;
                                    $totalnota=$total*intval($row->notamar);
                                    echo '<th style="text-align: right">'.number_format($row->notamar).'</th>';
                                    $subtot03=$subtot03+$row->notamar;
                                    $totarea03=$totarea03+$row->notamar;
                                    $grandtot03=$grandtot03+$row->notamar;
                                    $totkota=$totkota+$row->notamar;
                                    $totarea=$totarea+$row->notamar;
                                    break;
                                    case '4' :
                                    $total=$total+$row->notaapr;
                                    $totalnota=$total*intval($row->notaapr);
                                    echo '<th style="text-align: right">'.number_format($row->notaapr).'</th>';
                                    $subtot04=$subtot04+$row->notaapr;
                                    $totarea04=$totarea04+$row->notaapr;
                                    $grandtot04=$grandtot04+$row->notaapr;
                                    $totkota=$totkota+$row->notaapr;
                                    $totarea=$totarea+$row->notaapr;
                                    break;
                                    case '5' :
                                    $total=$total+$row->notamay;
                                    $totalnota=$total*intval($row->notamay);
                                    echo '<th style="text-align: right">'.number_format($row->notamay).'</th>';
                                    $subtot05=$subtot05+$row->notamay;
                                    $totarea05=$totarea05+$row->notamay;
                                    $grandtot05=$grandtot05+$row->notamay;
                                    $totkota=$totkota+$row->notamay;
                                    $totarea=$totarea+$row->notamay;
                                    break;
                                    case '6' :
                                    $total=$total+$row->notajun;
                                    $totalnota=$total*intval($row->notajun);
                                    echo '<th style="text-align: right">'.number_format($row->notajun).'</th>';
                                    $subtot06=$subtot06+$row->notajun;
                                    $totarea06=$totarea06+$row->notajun;
                                    $grandtot06=$grandtot06+$row->notajun;
                                    $totkota=$totkota+$row->notajun;
                                    $totarea=$totarea+$row->notajun;
                                    break;
                                    case '7' :
                                    $total=$total+$row->notajul;
                                    $totalnota=$total*intval($row->notajul);
                                    echo '<th style="text-align: right">'.number_format($row->notajul).'</th>';
                                    $subtot07=$subtot07+$row->notajul;
                                    $totarea07=$totarea07+$row->notajul;
                                    $grandtot07=$grandtot07+$row->notajul;
                                    $totkota=$totkota+$row->notajul;
                                    $totarea=$totarea+$row->notajul;
                                    break;
                                    case '8' :
                                    $total=$total+$row->notaaug;
                                    $totalnota=$total*intval($row->notaaug);
                                    echo '<th style="text-align: right">'.number_format($row->notaaug).'</th>';
                                    $subtot08=$subtot08+$row->notaaug;
                                    $totarea08=$totarea08+$row->notaaug;
                                    $grandtot08=$grandtot08+$row->notaaug;
                                    $totkota=$totkota+$row->notaaug;
                                    $totarea=$totarea+$row->notaaug;
                                    break;
                                    case '9' :
                                    $total=$total+$row->notasep;
                                    $totalnota=$total*intval($row->notasep);
                                    echo '<th style="text-align: right">'.number_format($row->notasep).'</th>';
                                    $subtot09=$subtot09+$row->notasep;
                                    $totarea09=$totarea09+$row->notasep;
                                    $grandtot09=$grandtot09+$row->notasep;
                                    $totkota=$totkota+$row->notasep;
                                    $totarea=$totarea+$row->notasep;
                                    break;
                                    case '10' :
                                    $total=$total+$row->notaokt;
                                    $totalnota=$total*intval($row->notaokt);
                                    echo '<th style="text-align: right">'.number_format($row->notaokt).'</th>';
                                    $subtot10=$subtot10+$row->notaokt;
                                    $totarea10=$totarea10+$row->notaokt;
                                    $grandtot10=$grandtot10+$row->notaokt;
                                    $totkota=$totkota+$row->notaokt;
                                    $totarea=$totarea+$row->notaokt;
                                    break;
                                    case '11' :
                                    $total=$total+$row->notanov;
                                    $totalnota=$total*intval($row->notanov);
                                    echo '<th style="text-align: right">'.number_format($row->notanov).'</th>';
                                    $subtot11=$subtot11+$row->notanov;
                                    $totarea11=$totarea11+$row->notanov;
                                    $grandtot11=$grandtot11+$row->notanov;
                                    $totkota=$totkota+$row->notanov;
                                    $totarea=$totarea+$row->notanov;
                                    break;
                                    case '12' :
                                    $total=$total+$row->notades;
                                    $totalnota=$total*intval($row->notades);
                                    echo '<th style="text-align: right">'.number_format($row->notades).'</th>';
                                    $subtot12=$subtot12+$row->notades;
                                    $totarea12=$totarea12+$row->notades;
                                    $grandtot12=$grandtot12+$row->notades;
                                    $totkota=$totkota+$row->notades;
                                    $totarea=$totarea+$row->notades;
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                        }
                        echo '<th style="text-align: right">'.number_format($total).'</th>';
                        echo "</tr>";
                        $icity=$row->icity;
                        $kode=$row->iarea;
                    }
                    echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=10 align=center>T o t a l   K o t a</td>";
                    $bl=$blasal;
                    for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                            case '1' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot01).'</th>';
                            break;
                            case '2' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot02).'</th>';
                            break;
                            case '3' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot03).'</th>';
                            break;
                            case '4' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot04).'</th>';
                            break;
                            case '5' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot05).'</th>';
                            break;
                            case '6' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot06).'</th>';
                            break;
                            case '7' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot07).'</th>';
                            break;
                            case '8' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot08).'</th>';
                            break;
                            case '9' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot09).'</th>';
                            break;
                            case '10' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot10).'</th>';
                            break;
                            case '11' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot11).'</th>';
                            break;
                            case '12' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($subtot12).'</th>';
                            break;
                        }
                        $bl++;
                        if($bl==13)$bl=1;
                    }
                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totkota).'</th></tr>';
                    $grandtotkota=$grandtotkota+$totkota;
                    echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=10 align=center>T o t a l   A r e a</td>";
                    $bl=$blasal;
                    for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                            case '1' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea01).'</th>';
                            break;
                            case '2' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea02).'</th>';
                            break;
                            case '3' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea03).'</th>';
                            break;
                            case '4' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea04).'</th>';
                            break;
                            case '5' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea05).'</th>';
                            break;
                            case '6' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea06).'</th>';
                            break;
                            case '7' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea07).'</th>';
                            break;
                            case '8' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea08).'</th>';
                            break;
                            case '9' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea09).'</th>';
                            break;
                            case '10' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea10).'</th>';
                            break;
                            case '11' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea11).'</th>';
                            break;
                            case '12' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea12).'</th>';
                            break;
                        }
                        $bl++;
                        if($bl==13)$bl=1;
                    }
                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea).'</th></tr>';
                    echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=10 align=center>G r a n d   T o t a l</td>";
                    $bl=$blasal;
                    for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                            case '1' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot01).'</th>';
                            break;
                            case '2' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot02).'</th>';
                            break;
                            case '3' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot03).'</th>';
                            break;
                            case '4' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot04).'</th>';
                            break;
                            case '5' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot05).'</th>';
                            break;
                            case '6' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot06).'</th>';
                            break;
                            case '7' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot07).'</th>';
                            break;
                            case '8' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot08).'</th>';
                            break;
                            case '9' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot09).'</th>';
                            break;
                            case '10' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot10).'</th>';
                            break;
                            case '11' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot11).'</th>';
                            break;
                            case '12' :
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot12).'</th>';
                            break;
                        }
                        $bl++;
                        if($bl==13)$bl=1;
                    }
                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtotkota).'</th></tr>';
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
