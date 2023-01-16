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
            <?php if($isi){ ?>
                <tr>
                    <th rowspan=2>AREA</th>
                    <th rowspan=2>K-LANG</th>
                    <th rowspan=2>KOTA/KAB</th>
                    <th rowspan=2>JENIS</th>
                    <th rowspan=2>NAMA LANG</th>
                    <th rowspan=2>ALAMAT</th>
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
                    echo '<th rowspan=2>Total Nota(GROSS)</th>';
                    ?>
                </tr>
                <tr>
                    <?php 
                    for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                            case '1' :
                            echo '<th>Jan(gross)</th>';
                            break;
                            case '2' :
                            echo '<th>Feb(gross)</th>';
                            break;
                            case '3' :
                            echo '<th>Mar(gross)</th>';
                            break;
                            case '4' :
                            echo '<th>Apr(gross)</th>';
                            break;
                            case '5' :
                            echo '<th>Mei(gross)</th>';
                            break;
                            case '6' :
                            echo '<th>Jun(gross)</th>';
                            break;
                            case '7' :
                            echo '<th>Jul(gross)</th>';
                            break;
                            case '8' :
                            echo '<th>Agu(gross)</th>';
                            break;
                            case '9' :
                            echo '<th>Sep(gross)</th>';
                            break;
                            case '10' :
                            echo '<th>Okt(gross)</th>';
                            break;
                            case '11' :
                            echo '<th>Nov(gross)</th>';
                            break;
                            case '12' :
                            echo '<th>Des(gross)</th>';
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
  //-----------------NETTO-----------------
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
                                $total=$total+$row->notajangross;
                                echo '<th align=right>'.number_format($row->notajangross).'</th>';
                                $subtot01=$subtot01+$row->notajangross;
                                $totarea01=$totarea01+$row->notajangross;
                                $grandtot01=$grandtot01+$row->notajangross;
                                $totkota=$totkota+$row->notajangross;
                                $totarea=$totarea+$row->notajangross;
            //--------------NETTO---------------------//
                                break;
                                case '2' :
                                $total=$total+$row->notafebgross;
                                echo '<th align=right>'.number_format($row->notafebgross).'</th>';
                                $subtot02=$subtot02+$row->notafebgross;
                                $totarea02=$totarea02+$row->notafebgross;
                                $grandtot02=$grandtot02+$row->notafebgross;
                                $totkota=$totkota+$row->notafebgross;
                                $totarea=$totarea+$row->notafebgross;
            //---------------NETTO------------------//
                                break;
                                case '3' :
                                $total=$total+$row->notamargross;
                                echo '<th align=right>'.number_format($row->notamargross).'</th>';
                                $subtot03=$subtot03+$row->notamargross;
                                $totarea03=$totarea03+$row->notamargross;
                                $grandtot03=$grandtot03+$row->notamargross;
                                $totkota=$totkota+$row->notamargross;
                                $totarea=$totarea+$row->notamargross;
            //---------------NETTO------------------//
                                break;
                                case '4' :
                                $total=$total+$row->notaaprgross;
                                echo '<th align=right>'.number_format($row->notaaprgross).'</th>';
                                $subtot04=$subtot04+$row->notaaprgross;
                                $totarea04=$totarea04+$row->notaaprgross;
                                $grandtot04=$grandtot04+$row->notaaprgross;
                                $totkota=$totkota+$row->notaaprgross;
                                $totarea=$totarea+$row->notaaprgross;
            //---------------NETTO------------------//
                                break;
                                case '5' :
                                $total=$total+$row->notamaygross;
                                echo '<th align=right>'.number_format($row->notamaygross).'</th>';
                                $subtot05=$subtot05+$row->notamaygross;
                                $totarea05=$totarea05+$row->notamaygross;
                                $grandtot05=$grandtot05+$row->notamaygross;
                                $totkota=$totkota+$row->notamaygross;
                                $totarea=$totarea+$row->notamaygross;
            //---------------NETTO------------------//
                                break;
                                case '6' :
                                $total=$total+$row->notajungross;
                                echo '<th align=right>'.number_format($row->notajungross).'</th>';
                                $subtot06=$subtot06+$row->notajungross;
                                $totarea06=$totarea06+$row->notajungross;
                                $grandtot06=$grandtot06+$row->notajungross;
                                $totkota=$totkota+$row->notajungross;
                                $totarea=$totarea+$row->notajungross;
            //---------------NETTO------------------//
                                break;
                                case '7' :
                                $total=$total+$row->notajulgross;
                                echo '<th align=right>'.number_format($row->notajulgross).'</th>';
                                $subtot07=$subtot07+$row->notajulgross;
                                $totarea07=$totarea07+$row->notajulgross;
                                $grandtot07=$grandtot07+$row->notajulgross;
                                $totkota=$totkota+$row->notajulgross;
                                $totarea=$totarea+$row->notajulgross;
            //---------------NETTO------------------//
                                break;
                                case '8' :
                                $total=$total+$row->notaauggross;
                                echo '<th align=right>'.number_format($row->notaauggross).'</th>';
                                $subtot08=$subtot08+$row->notaauggross;
                                $totarea08=$totarea08+$row->notaauggross;
                                $grandtot08=$grandtot08+$row->notaauggross;
                                $totkota=$totkota+$row->notaauggross;
                                $totarea=$totarea+$row->notaauggross;
            //---------------NETTO------------------//
                                break;
                                case '9' :
                                $total=$total+$row->notasepgross;
                                echo '<th align=right>'.number_format($row->notasepgross).'</th>';
                                $subtot09=$subtot09+$row->notasepgross;
                                $totarea09=$totarea09+$row->notasepgross;
                                $grandtot09=$grandtot09+$row->notasepgross;
                                $totkota=$totkota+$row->notasepgross;
                                $totarea=$totarea+$row->notasepgross;
            //---------------NETTO------------------//
                                break;
                                case '10' :
                                $total=$total+$row->notaoctgross;
                                echo '<th align=right>'.number_format($row->notaoctgross).'</th>';
                                $subtot10=$subtot10+$row->notaoctgross;
                                $totarea10=$totarea10+$row->notaoctgross;
                                $grandtot10=$grandtot10+$row->notaoctgross;
                                $totkota=$totkota+$row->notaoctgross;
                                $totarea=$totarea+$row->notaoctgross;
            //---------------NETTO------------------//
                                break;
                                case '11' :
                                $total=$total+$row->notanovgross;
                                echo '<th align=right>'.number_format($row->notanovgross).'</th>';
                                $subtot11=$subtot11+$row->notanovgross;
                                $totarea11=$totarea11+$row->notanovgross;
                                $grandtot11=$grandtot11+$row->notanovgross;
                                $totkota=$totkota+$row->notanovgross;
                                $totarea=$totarea+$row->notanovgross;
            //---------------NETTO------------------//
                                break;
                                case '12' :
                                $total=$total+$row->notadesgross;
                                echo '<th align=right>'.number_format($row->notadesgross).'</th>';
                                $subtot12=$subtot12+$row->notadesgross;
                                $totarea12=$totarea12+$row->notadesgross;
                                $grandtot12=$grandtot12+$row->notadesgross;
                                $totkota=$totkota+$row->notadesgross;
                                $totarea=$totarea+$row->notadesgross;
            //---------------NETTO------------------//
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
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</th>';
                                break;
                                case '2' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</th>';
                                break;
                                case '3' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</th>';
                                break;
                                case '4' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</th>';
                                break;
                                case '5' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</th>';
                                break;
                                case '6' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</th>';
                                break;
                                case '7' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</th>';
                                break;
                                case '8' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</th>';
                                break;
                                case '9' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</th>';
                                break;
                                case '10' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</th>';
                                break;
                                case '11' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</th>';
                                break;
                                case '12' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</th>';
                                break;
                            }
                            $bl++;
                            if($bl==13)$bl=1;
                        }
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</th>';
                        $grandtotkota=$grandtotkota+$totkota;
                        echo "<tr>
                        <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
                        $bl=$blasal;
                        for($i=1;$i<=$interval;$i++){
                            switch ($bl){
                                case '1' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</th>';
                                break;
                                case '2' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</th>';
                                break;
                                case '3' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</th>';
                                break;
                                case '4' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</th>';
                                break;
                                case '5' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</th>';
                                break;
                                case '6' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</th>';
                                break;
                                case '7' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</th>';
                                break;
                                case '8' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</th>';
                                break;
                                case '9' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</th>';
                                break;
                                case '10' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</th>';
                                break;
                                case '11' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</th>';
                                break;
                                case '12' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</th>';
                                break;
                            }
                            $bl++;
                            if($bl==13)$bl=1;
                        }
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</th>';
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
      //---------NETTO-----------------//
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
                                $total=$total+$row->notajangross;
                                echo '<th align=right>'.number_format($row->notajangross).'</th>';
                                $subtot01=$subtot01+$row->notajangross;
                                $totarea01=$totarea01+$row->notajangross;
                                $grandtot01=$grandtot01+$row->notajangross;
                                $totkota=$totkota+$row->notajangross;
                                $totarea=$totarea+$row->notajangross;
            //--------------NETTO---------------------//
                                break;
                                case '2' :
                                $total=$total+$row->notafebgross;
                                echo '<th align=right>'.number_format($row->notafebgross).'</th>';
                                $subtot02=$subtot02+$row->notafebgross;
                                $totarea02=$totarea02+$row->notafebgross;
                                $grandtot02=$grandtot02+$row->notafebgross;
                                $totkota=$totkota+$row->notafebgross;
                                $totarea=$totarea+$row->notafebgross;
            //---------------NETTO------------------//
                                break;
                                case '3' :
                                $total=$total+$row->notamargross;
                                echo '<th align=right>'.number_format($row->notamargross).'</th>';
                                $subtot03=$subtot03+$row->notamargross;
                                $totarea03=$totarea03+$row->notamargross;
                                $grandtot03=$grandtot03+$row->notamargross;
                                $totkota=$totkota+$row->notamargross;
                                $totarea=$totarea+$row->notamargross;
            //---------------NETTO------------------//
                                break;
                                case '4' :
                                $total=$total+$row->notaaprgross;
                                echo '<th align=right>'.number_format($row->notaaprgross).'</th>';
                                $subtot04=$subtot04+$row->notaaprgross;
                                $totarea04=$totarea04+$row->notaaprgross;
                                $grandtot04=$grandtot04+$row->notaaprgross;
                                $totkota=$totkota+$row->notaaprgross;
                                $totarea=$totarea+$row->notaaprgross;
            //---------------NETTO------------------//
                                break;
                                case '5' :
                                $total=$total+$row->notamaygross;
                                echo '<th align=right>'.number_format($row->notamaygross).'</th>';
                                $subtot05=$subtot05+$row->notamaygross;
                                $totarea05=$totarea05+$row->notamaygross;
                                $grandtot05=$grandtot05+$row->notamaygross;
                                $totkota=$totkota+$row->notamaygross;
                                $totarea=$totarea+$row->notamaygross;
            //---------------NETTO------------------//
                                break;
                                case '6' :
                                $total=$total+$row->notajungross;
                                echo '<th align=right>'.number_format($row->notajungross).'</th>';
                                $subtot06=$subtot06+$row->notajungross;
                                $totarea06=$totarea06+$row->notajungross;
                                $grandtot06=$grandtot06+$row->notajungross;
                                $totkota=$totkota+$row->notajungross;
                                $totarea=$totarea+$row->notajungross;
            //---------------NETTO------------------//
                                break;
                                case '7' :
                                $total=$total+$row->notajulgross;
                                echo '<th align=right>'.number_format($row->notajulgross).'</th>';
                                $subtot07=$subtot07+$row->notajulgross;
                                $totarea07=$totarea07+$row->notajulgross;
                                $grandtot07=$grandtot07+$row->notajulgross;
                                $totkota=$totkota+$row->notajulgross;
                                $totarea=$totarea+$row->notajulgross;
            //---------------NETTO------------------//
                                break;
                                case '8' :
                                $total=$total+$row->notaauggross;
                                echo '<th align=right>'.number_format($row->notaauggross).'</th>';
                                $subtot08=$subtot08+$row->notaauggross;
                                $totarea08=$totarea08+$row->notaauggross;
                                $grandtot08=$grandtot08+$row->notaauggross;
                                $totkota=$totkota+$row->notaauggross;
                                $totarea=$totarea+$row->notaauggross;
            //---------------NETTO------------------//
                                break;
                                case '9' :
                                $total=$total+$row->notasepgross;
                                echo '<th align=right>'.number_format($row->notasepgross).'</th>';
                                $subtot09=$subtot09+$row->notasepgross;
                                $totarea09=$totarea09+$row->notasepgross;
                                $grandtot09=$grandtot09+$row->notasepgross;
                                $totkota=$totkota+$row->notasepgross;
                                $totarea=$totarea+$row->notasepgross;
            //---------------NETTO------------------//
                                break;
                                case '10' :
                                $total=$total+$row->notaoctgross;
                                echo '<th align=right>'.number_format($row->notaoctgross).'</th>';
                                $subtot10=$subtot10+$row->notaoctgross;
                                $totarea10=$totarea10+$row->notaoctgross;
                                $grandtot10=$grandtot10+$row->notaoctgross;
                                $totkota=$totkota+$row->notaoctgross;
                                $totarea=$totarea+$row->notaoctgross;
            //---------------NETTO------------------//
                                break;
                                case '11' :
                                $total=$total+$row->notanovgross;
                                echo '<th align=right>'.number_format($row->notanovgross).'</th>';
                                $subtot11=$subtot11+$row->notanovgross;
                                $totarea11=$totarea11+$row->notanovgross;
                                $grandtot11=$grandtot11+$row->notanovgross;
                                $totkota=$totkota+$row->notanovgross;
                                $totarea=$totarea+$row->notanovgross;
            //---------------NETTO------------------//
                                break;
                                case '12' :
                                $total=$total+$row->notadesgross;
                                echo '<th align=right>'.number_format($row->notadesgross).'</th>';
                                $subtot12=$subtot12+$row->notadesgross;
                                $totarea12=$totarea12+$row->notadesgross;
                                $grandtot12=$grandtot12+$row->notadesgross;
                                $totkota=$totkota+$row->notadesgross;
                                $totarea=$totarea+$row->notadesgross;
            //---------------NETTO------------------//
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
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</th>';
                                break;
                                case '2' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</th>';
                                break;
                                case '3' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</th>';
                                break;
                                case '4' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</th>';
                                break;
                                case '5' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</th>';
                                break;
                                case '6' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</th>';
                                break;
                                case '7' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</th>';
                                break;
                                case '8' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</th>';
                                break;
                                case '9' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</th>';
                                break;
                                case '10' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</th>';
                                break;
                                case '11' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</th>';
                                break;
                                case '12' :
                                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</th>';
                                break;
                            }
                            $bl++;
                            if($bl==13)$bl=1;
                        }
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</th>';
                        $grandtotkota=$grandtotkota+$totkota;
                        if($kode!=substr($row->kode,0,2)){
                            echo "<tr>
                            <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</th>';
                                    break;
                                    case '2' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</th>';
                                    break;
                                    case '3' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</th>';
                                    break;
                                    case '4' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</th>';
                                    break;
                                    case '5' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</th>';
                                    break;
                                    case '6' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</th>';
                                    break;
                                    case '7' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</th>';
                                    break;
                                    case '8' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</th>';
                                    break;
                                    case '9' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</th>';
                                    break;
                                    case '10' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</th>';
                                    break;
                                    case '11' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</th>';
                                    break;
                                    case '12' :
                                    echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</th>';
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                            echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</th></tr>';
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
        //---------NETTO-----------------//
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
                                $total=$total+$row->notajangross;
                                echo '<th align=right>'.number_format($row->notajangross).'</th>';
                                $subtot01=$subtot01+$row->notajangross;
                                $totarea01=$totarea01+$row->notajangross;
                                $grandtot01=$grandtot01+$row->notajangross;
                                $totkota=$totkota+$row->notajangross;
                                $totarea=$totarea+$row->notajangross;
            //--------------NETTO---------------------//
                                break;
                                case '2' :
                                $total=$total+$row->notafebgross;
                                echo '<th align=right>'.number_format($row->notafebgross).'</th>';
                                $subtot02=$subtot02+$row->notafebgross;
                                $totarea02=$totarea02+$row->notafebgross;
                                $grandtot02=$grandtot02+$row->notafebgross;
                                $totkota=$totkota+$row->notafebgross;
                                $totarea=$totarea+$row->notafebgross;
            //---------------NETTO------------------//
                                break;
                                case '3' :
                                $total=$total+$row->notamargross;
                                echo '<th align=right>'.number_format($row->notamargross).'</th>';
                                $subtot03=$subtot03+$row->notamargross;
                                $totarea03=$totarea03+$row->notamargross;
                                $grandtot03=$grandtot03+$row->notamargross;
                                $totkota=$totkota+$row->notamargross;
                                $totarea=$totarea+$row->notamargross;
            //---------------NETTO------------------//
                                break;
                                case '4' :
                                $total=$total+$row->notaaprgross;
                                echo '<th align=right>'.number_format($row->notaaprgross).'</th>';
                                $subtot04=$subtot04+$row->notaaprgross;
                                $totarea04=$totarea04+$row->notaaprgross;
                                $grandtot04=$grandtot04+$row->notaaprgross;
                                $totkota=$totkota+$row->notaaprgross;
                                $totarea=$totarea+$row->notaaprgross;
            //---------------NETTO------------------//
                                break;
                                case '5' :
                                $total=$total+$row->notamaygross;
                                echo '<th align=right>'.number_format($row->notamaygross).'</th>';
                                $subtot05=$subtot05+$row->notamaygross;
                                $totarea05=$totarea05+$row->notamaygross;
                                $grandtot05=$grandtot05+$row->notamaygross;
                                $totkota=$totkota+$row->notamaygross;
                                $totarea=$totarea+$row->notamaygross;
            //---------------NETTO------------------//
                                break;
                                case '6' :
                                $total=$total+$row->notajungross;
                                echo '<th align=right>'.number_format($row->notajungross).'</th>';
                                $subtot06=$subtot06+$row->notajungross;
                                $totarea06=$totarea06+$row->notajungross;
                                $grandtot06=$grandtot06+$row->notajungross;
                                $totkota=$totkota+$row->notajungross;
                                $totarea=$totarea+$row->notajungross;
            //---------------NETTO------------------//
                                break;
                                case '7' :
                                $total=$total+$row->notajulgross;
                                echo '<th align=right>'.number_format($row->notajulgross).'</th>';
                                $subtot07=$subtot07+$row->notajulgross;
                                $totarea07=$totarea07+$row->notajulgross;
                                $grandtot07=$grandtot07+$row->notajulgross;
                                $totkota=$totkota+$row->notajulgross;
                                $totarea=$totarea+$row->notajulgross;
            //---------------NETTO------------------//
                                break;
                                case '8' :
                                $total=$total+$row->notaauggross;
                                echo '<th align=right>'.number_format($row->notaauggross).'</th>';
                                $subtot08=$subtot08+$row->notaauggross;
                                $totarea08=$totarea08+$row->notaauggross;
                                $grandtot08=$grandtot08+$row->notaauggross;
                                $totkota=$totkota+$row->notaauggross;
                                $totarea=$totarea+$row->notaauggross;
            //---------------NETTO------------------//
                                break;
                                case '9' :
                                $total=$total+$row->notasepgross;
                                echo '<th align=right>'.number_format($row->notasepgross).'</th>';
                                $subtot09=$subtot09+$row->notasepgross;
                                $totarea09=$totarea09+$row->notasepgross;
                                $grandtot09=$grandtot09+$row->notasepgross;
                                $totkota=$totkota+$row->notasepgross;
                                $totarea=$totarea+$row->notasepgross;
            //---------------NETTO------------------//
                                break;
                                case '10' :
                                $total=$total+$row->notaoctgross;
                                echo '<th align=right>'.number_format($row->notaoctgross).'</th>';
                                $subtot10=$subtot10+$row->notaoctgross;
                                $totarea10=$totarea10+$row->notaoctgross;
                                $grandtot10=$grandtot10+$row->notaoctgross;
                                $totkota=$totkota+$row->notaoctgross;
                                $totarea=$totarea+$row->notaoctgross;
            //---------------NETTO------------------//
                                break;
                                case '11' :
                                $total=$total+$row->notanovgross;
                                echo '<th align=right>'.number_format($row->notanovgross).'</th>';
                                $subtot11=$subtot11+$row->notanovgross;
                                $totarea11=$totarea11+$row->notanovgross;
                                $grandtot11=$grandtot11+$row->notanovgross;
                                $totkota=$totkota+$row->notanovgross;
                                $totarea=$totarea+$row->notanovgross;
            //---------------NETTO------------------//
                                break;
                                case '12' :
                                $total=$total+$row->notadesgross;
                                echo '<th align=right>'.number_format($row->notadesgross).'</th>';
                                $subtot12=$subtot12+$row->notadesgross;
                                $totarea12=$totarea12+$row->notadesgross;
                                $grandtot12=$grandtot12+$row->notadesgross;
                                $totkota=$totkota+$row->notadesgross;
                                $totarea=$totarea+$row->notadesgross;
            //---------------NETTO------------------//
                                break;
                            }
                            $bl++;
                            if($bl==13)$bl=1;
                        }
                    }
                    echo '<th align=right>'.number_format($total).'</th>';
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
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</th>';
                        break;
                        case '2' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</th>';
                        break;
                        case '3' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</th>';
                        break;
                        case '4' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</th>';
                        break;
                        case '5' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</th>';
                        break;
                        case '6' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</th>';
                        break;
                        case '7' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</th>';
                        break;
                        case '8' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</th>';
                        break;
                        case '9' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</th>';
                        break;
                        case '10' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</th>';
                        break;
                        case '11' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</th>';
                        break;
                        case '12' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</th>';
                        break;
                    }
                    $bl++;
                    if($bl==13)$bl=1;
                }
                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</th></tr>';
                $grandtotkota=$grandtotkota+$totkota;
                echo "<tr>
                <td style='background-color:#F2F2F2;' colspan=6 align=center>T o t a l   A r e a</td>";
                $bl=$blasal;
                for($i=1;$i<=$interval;$i++){
                    switch ($bl){
                        case '1' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</th>';
                        break;
                        case '2' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</th>';
                        break;
                        case '3' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</th>';
                        break;
                        case '4' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</th>';
                        break;
                        case '5' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</th>';
                        break;
                        case '6' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</th>';
                        break;
                        case '7' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</th>';
                        break;
                        case '8' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</th>';
                        break;
                        case '9' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</th>';
                        break;
                        case '10' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</th>';
                        break;
                        case '11' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</th>';
                        break;
                        case '12' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</th>';
                        break;
                    }
                    $bl++;
                    if($bl==13)$bl=1;
                }
                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</th></tr>';

                echo "<tr>
                <td style='background-color:#F2F2F2;' colspan=6 align=center>G r a n d   T o t a l</td>";
                $bl=$blasal;
                for($i=1;$i<=$interval;$i++){
                    switch ($bl){
                        case '1' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot01).'</th>';
                        break;
                        case '2' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot02).'</th>';
                        break;
                        case '3' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot03).'</th>';
                        break;
                        case '4' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot04).'</th>';
                        break;
                        case '5' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot05).'</th>';
                        break;
                        case '6' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot06).'</th>';
                        break;
                        case '7' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot07).'</th>';
                        break;
                        case '8' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot08).'</th>';
                        break;
                        case '9' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot09).'</th>';
                        break;
                        case '10' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot10).'</th>';
                        break;
                        case '11' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot11).'</th>';
                        break;
                        case '12' :
                        echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtot12).'</th>';
                        break;
                    }
                    $bl++;
                    if($bl==13)$bl=1;
                }
                echo '<th style="background-color:#F2F2F2;" align=right>'.number_format($grandtotkota).'</th></tr>';
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
