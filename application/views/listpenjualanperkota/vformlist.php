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
                        <th rowspan=2>KOTA/KAB</th>
                        <?php if($dfrom!=''){
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
                    $totkota=0;
                    $icity='';
                    $iarea='';
                    $totarea=0;
                    $grandtotarea=0;
                    $grandtot=0;
                    foreach($isi as $row){
                        $riario=substr($row->kode,0,2);
                        $isiti=substr($row->kode,2,7);
                        $query=$this->db->query("
                            SELECT
                                a.e_area_name,
                                b.e_city_name
                            FROM
                                tr_area a,
                                tr_city b
                            WHERE
                                a.i_area = '$riario'
                                AND b.i_area = '$riario'
                                AND b.i_city = '$isiti'
                        ",false);
                        foreach($query->result() as $tx){
                            $area=$tx->e_area_name;
                            $kota=$tx->e_city_name;
                        }
                        $total=0;
                        if($icity=='' || ($icity==$isiti && $iarea==$riario) ){
                            echo "<tr>
                            <td>".$riario."-".$area."</td>
                            <td>".$isiti."-"."$kota</td>";
                            $bl=$blasal;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    $total=$total+$row->notajan;
                                    echo '<th style="text-align: right">'.number_format($row->notajan).'</th>';
                                    $subtot01=$subtot01+$row->notajan;
                                    $grandtot01=$grandtot01+$row->notajan;
                                    $totkota=$totkota+$row->notajan;
                                    $totarea=$totarea+$row->notajan;
                                    $grandtot=$grandtot+$row->notajan;
                                    break;
                                    case '2' :
                                    $total=$total+$row->notafeb;
                                    echo '<th style="text-align: right">'.number_format($row->notafeb).'</th>';
                                    $subtot02=$subtot02+$row->notafeb;
                                    $grandtot02=$grandtot02+$row->notafeb;
                                    $totkota=$totkota+$row->notafeb;
                                    $totarea=$totarea+$row->notafeb;
                                    $grandtot=$grandtot+$row->notafeb;
                                    break;
                                    case '3' :
                                    $total=$total+$row->notamar;
                                    echo '<th style="text-align: right">'.number_format($row->notamar).'</th>';
                                    $subtot03=$subtot03+$row->notamar;
                                    $grandtot03=$grandtot03+$row->notamar;
                                    $totkota=$totkota+$row->notamar;
                                    $totarea=$totarea+$row->notamar;
                                    $grandtot=$grandtot+$row->notamar;
                                    break;
                                    case '4' :
                                    $total=$total+$row->notaapr;
                                    echo '<th style="text-align: right">'.number_format($row->notaapr).'</th>';
                                    $subtot04=$subtot04+$row->notaapr;
                                    $grandtot04=$grandtot04+$row->notaapr;
                                    $totkota=$totkota+$row->notaapr;
                                    $totarea=$totarea+$row->notaapr;
                                    $grandtot=$grandtot+$row->notaapr;
                                    break;
                                    case '5' :
                                    $total=$total+$row->notamay;
                                    echo '<th style="text-align: right">'.number_format($row->notamay).'</th>';
                                    $subtot05=$subtot05+$row->notamay;
                                    $grandtot05=$grandtot05+$row->notamay;
                                    $totkota=$totkota+$row->notamay;
                                    $totarea=$totarea+$row->notamay;
                                    $grandtot=$grandtot+$row->notamay;
                                    break;
                                    case '6' :
                                    $total=$total+$row->notajun;
                                    echo '<th style="text-align: right">'.number_format($row->notajun).'</th>';
                                    $subtot06=$subtot06+$row->notajun;
                                    $grandtot06=$grandtot06+$row->notajun;
                                    $totkota=$totkota+$row->notajun;
                                    $totarea=$totarea+$row->notajun;
                                    $grandtot=$grandtot+$row->notajun;
                                    break;
                                    case '7' :
                                    $total=$total+$row->notajul;
                                    echo '<th style="text-align: right">'.number_format($row->notajul).'</th>';
                                    $subtot07=$subtot07+$row->notajul;
                                    $grandtot07=$grandtot07+$row->notajul;
                                    $totkota=$totkota+$row->notajul;
                                    $totarea=$totarea+$row->notajul;
                                    $grandtot=$grandtot+$row->notajul;
                                    break;
                                    case '8' :
                                    $total=$total+$row->notaaug;
                                    echo '<th style="text-align: right">'.number_format($row->notaaug).'</th>';
                                    $subtot08=$subtot08+$row->notaaug;
                                    $grandtot08=$grandtot08+$row->notaaug;
                                    $totkota=$totkota+$row->notaaug;
                                    $totarea=$totarea+$row->notaaug;
                                    $grandtot=$grandtot+$row->notaaug;
                                    break;
                                    case '9' :
                                    $total=$total+$row->notasep;
                                    echo '<th style="text-align: right">'.number_format($row->notasep).'</th>';
                                    $subtot09=$subtot09+$row->notasep;
                                    $grandtot09=$grandtot09+$row->notasep;
                                    $totkota=$totkota+$row->notasep;
                                    $totarea=$totarea+$row->notasep;
                                    $grandtot=$grandtot+$row->notasep;
                                    break;
                                    case '10' :
                                    $total=$total+$row->notaoct;
                                    echo '<th style="text-align: right">'.number_format($row->notaoct).'</th>';
                                    $subtot10=$subtot10+$row->notaoct;
                                    $grandtot10=$grandtot10+$row->notaoct;
                                    $totkota=$totkota+$row->notaoct;
                                    $totarea=$totarea+$row->notaoct;
                                    $grandtot=$grandtot+$row->notaoct;
                                    break;
                                    case '11' :
                                    $total=$total+$row->notanov;
                                    echo '<th style="text-align: right">'.number_format($row->notanov).'</th>';
                                    $subtot11=$subtot11+$row->notanov;
                                    $grandtot11=$grandtot11+$row->notanov;
                                    $totkota=$totkota+$row->notanov;
                                    $totarea=$totarea+$row->notanov;
                                    $grandtot=$grandtot+$row->notanov;
                                    break;
                                    case '12' :
                                    $total=$total+$row->notades;
                                    echo '<th style="text-align: right">'.number_format($row->notades).'</th>';
                                    $subtot12=$subtot12+$row->notades;
                                    $grandtot12=$grandtot12+$row->notades;
                                    $totkota=$totkota+$row->notades;
                                    $totarea=$totarea+$row->notades;
                                    $grandtot=$grandtot+$row->notades;
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totkota).'</th></tr>';
                        }elseif($iarea!=$riario){
                            if($bl==13)$bl=1;
                            echo "<tr>
                            <td style='background-color:#F2F2F2;' colspan=2 align=center>T o t a l   Area</td>";
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
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea).'</th></tr>';
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
                            echo "<tr>
                            <td>".$riario."-".$area."</td>
                            <td>".$isiti."-"."$kota</td>";
                            $bl=$blasal;
                            $totkota=0;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    $total=$total+$row->notajan;
                                    echo '<th style="text-align: right">'.number_format($row->notajan).'</th>';
                                    $subtot01=$subtot01+$row->notajan;
                                    $grandtot01=$grandtot01+$row->notajan;
                                    $totkota=$totkota+$row->notajan;
                                    $totarea=$totarea+$row->notajan;
                                    $grandtot=$grandtot+$row->notajan;
                                    break;
                                    case '2' :
                                    $total=$total+$row->notafeb;
                                    echo '<th style="text-align: right">'.number_format($row->notafeb).'</th>';
                                    $subtot02=$subtot02+$row->notafeb;
                                    $grandtot02=$grandtot02+$row->notafeb;
                                    $totkota=$totkota+$row->notafeb;
                                    $totarea=$totarea+$row->notafeb;
                                    $grandtot=$grandtot+$row->notafeb;
                                    break;
                                    case '3' :
                                    $total=$total+$row->notamar;
                                    echo '<th style="text-align: right">'.number_format($row->notamar).'</th>';
                                    $subtot03=$subtot03+$row->notamar;
                                    $grandtot03=$grandtot03+$row->notamar;
                                    $totkota=$totkota+$row->notamar;
                                    $totarea=$totarea+$row->notamar;
                                    $grandtot=$grandtot+$row->notamar;
                                    break;
                                    case '4' :
                                    $total=$total+$row->notaapr;
                                    echo '<th style="text-align: right">'.number_format($row->notaapr).'</th>';
                                    $subtot04=$subtot04+$row->notaapr;
                                    $grandtot04=$grandtot04+$row->notaapr;
                                    $totkota=$totkota+$row->notaapr;
                                    $totarea=$totarea+$row->notaapr;
                                    $grandtot=$grandtot+$row->notaapr;
                                    break;
                                    case '5' :
                                    $total=$total+$row->notamay;
                                    echo '<th style="text-align: right">'.number_format($row->notamay).'</th>';
                                    $subtot05=$subtot05+$row->notamay;
                                    $grandtot05=$grandtot05+$row->notamay;
                                    $totkota=$totkota+$row->notamay;
                                    $totarea=$totarea+$row->notamay;
                                    $grandtot=$grandtot+$row->notamay;
                                    break;
                                    case '6' :
                                    $total=$total+$row->notajun;
                                    echo '<th style="text-align: right">'.number_format($row->notajun).'</th>';
                                    $subtot06=$subtot06+$row->notajun;
                                    $grandtot06=$grandtot06+$row->notajun;
                                    $totkota=$totkota+$row->notajun;
                                    $totarea=$totarea+$row->notajun;
                                    $grandtot=$grandtot+$row->notajun;
                                    break;
                                    case '7' :
                                    $total=$total+$row->notajul;
                                    echo '<th style="text-align: right">'.number_format($row->notajul).'</th>';
                                    $subtot07=$subtot07+$row->notajul;
                                    $grandtot07=$grandtot07+$row->notajul;
                                    $totkota=$totkota+$row->notajul;
                                    $totarea=$totarea+$row->notajul;
                                    $grandtot=$grandtot+$row->notajul;
                                    break;
                                    case '8' :
                                    $total=$total+$row->notaaug;
                                    echo '<th style="text-align: right">'.number_format($row->notaaug).'</th>';
                                    $subtot08=$subtot08+$row->notaaug;
                                    $grandtot08=$grandtot08+$row->notaaug;
                                    $totkota=$totkota+$row->notaaug;
                                    $totarea=$totarea+$row->notaaug;
                                    $grandtot=$grandtot+$row->notaaug;
                                    break;
                                    case '9' :
                                    $total=$total+$row->notasep;
                                    echo '<th style="text-align: right">'.number_format($row->notasep).'</th>';
                                    $subtot09=$subtot09+$row->notasep;
                                    $grandtot09=$grandtot09+$row->notasep;
                                    $totkota=$totkota+$row->notasep;
                                    $totarea=$totarea+$row->notasep;
                                    $grandtot=$grandtot+$row->notasep;
                                    break;
                                    case '10' :
                                    $total=$total+$row->notaoct;
                                    echo '<th style="text-align: right">'.number_format($row->notaoct).'</th>';
                                    $subtot10=$subtot10+$row->notaoct;
                                    $grandtot10=$grandtot10+$row->notaoct;
                                    $totkota=$totkota+$row->notaoct;
                                    $totarea=$totarea+$row->notaoct;
                                    $grandtot=$grandtot+$row->notaoct;
                                    break;
                                    case '11' :
                                    $total=$total+$row->notanov;
                                    echo '<th style="text-align: right">'.number_format($row->notanov).'</th>';
                                    $subtot11=$subtot11+$row->notanov;
                                    $grandtot11=$grandtot11+$row->notanov;
                                    $totkota=$totkota+$row->notanov;
                                    $totarea=$totarea+$row->notanov;
                                    $grandtot=$grandtot+$row->notanov;
                                    break;
                                    case '12' :
                                    $total=$total+$row->notades;
                                    echo '<th style="text-align: right">'.number_format($row->notades).'</th>';
                                    $subtot12=$subtot12+$row->notades;
                                    $grandtot12=$grandtot12+$row->notades;
                                    $totkota=$totkota+$row->notades;
                                    $totarea=$totarea+$row->notades;
                                    $grandtot=$grandtot+$row->notades;
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totkota).'</th></tr>';
                        }elseif($icity!=$isiti && $iarea==$riario){
                            echo "<tr>
                            <td></td>
                            <td>".$isiti."-"."$kota</td>";
                            $bl=$blasal;
                            $totkota=0;
                            for($i=1;$i<=$interval;$i++){
                                switch ($bl){
                                    case '1' :
                                    $total=$total+$row->notajan;
                                    echo '<th style="text-align: right">'.number_format($row->notajan).'</th>';
                                    $subtot01=$subtot01+$row->notajan;
                                    $grandtot01=$grandtot01+$row->notajan;
                                    $totkota=$totkota+$row->notajan;
                                    $totarea=$totarea+$row->notajan;
                                    $grandtot=$grandtot+$row->notajan;
                                    break;
                                    case '2' :
                                    $total=$total+$row->notafeb;
                                    echo '<th style="text-align: right">'.number_format($row->notafeb).'</th>';
                                    $subtot02=$subtot02+$row->notafeb;
                                    $grandtot02=$grandtot02+$row->notafeb;
                                    $totkota=$totkota+$row->notafeb;
                                    $totarea=$totarea+$row->notafeb;
                                    $grandtot=$grandtot+$row->notafeb;
                                    break;
                                    case '3' :
                                    $total=$total+$row->notamar;
                                    echo '<th style="text-align: right">'.number_format($row->notamar).'</th>';
                                    $subtot03=$subtot03+$row->notamar;
                                    $grandtot03=$grandtot03+$row->notamar;
                                    $totkota=$totkota+$row->notamar;
                                    $totarea=$totarea+$row->notamar;
                                    $grandtot=$grandtot+$row->notamar;
                                    break;
                                    case '4' :
                                    $total=$total+$row->notaapr;
                                    echo '<th style="text-align: right">'.number_format($row->notaapr).'</th>';
                                    $subtot04=$subtot04+$row->notaapr;
                                    $grandtot04=$grandtot04+$row->notaapr;
                                    $totkota=$totkota+$row->notaapr;
                                    $totarea=$totarea+$row->notaapr;
                                    $grandtot=$grandtot+$row->notaapr;
                                    break;
                                    case '5' :
                                    $total=$total+$row->notamay;
                                    echo '<th style="text-align: right">'.number_format($row->notamay).'</th>';
                                    $subtot05=$subtot05+$row->notamay;
                                    $grandtot05=$grandtot05+$row->notamay;
                                    $totkota=$totkota+$row->notamay;
                                    $totarea=$totarea+$row->notamay;
                                    $grandtot=$grandtot+$row->notamay;
                                    break;
                                    case '6' :
                                    $total=$total+$row->notajun;
                                    echo '<th style="text-align: right">'.number_format($row->notajun).'</th>';
                                    $subtot06=$subtot06+$row->notajun;
                                    $grandtot06=$grandtot06+$row->notajun;
                                    $totkota=$totkota+$row->notajun;
                                    $totarea=$totarea+$row->notajun;
                                    $grandtot=$grandtot+$row->notajun;
                                    break;
                                    case '7' :
                                    $total=$total+$row->notajul;
                                    echo '<th style="text-align: right">'.number_format($row->notajul).'</th>';
                                    $subtot07=$subtot07+$row->notajul;
                                    $grandtot07=$grandtot07+$row->notajul;
                                    $totkota=$totkota+$row->notajul;
                                    $totarea=$totarea+$row->notajul;
                                    $grandtot=$grandtot+$row->notajul;
                                    break;
                                    case '8' :
                                    $total=$total+$row->notaaug;
                                    echo '<th style="text-align: right">'.number_format($row->notaaug).'</th>';
                                    $subtot08=$subtot08+$row->notaaug;
                                    $grandtot08=$grandtot08+$row->notaaug;
                                    $totkota=$totkota+$row->notaaug;
                                    $totarea=$totarea+$row->notaaug;
                                    $grandtot=$grandtot+$row->notaaug;
                                    break;
                                    case '9' :
                                    $total=$total+$row->notasep;
                                    echo '<th style="text-align: right">'.number_format($row->notasep).'</th>';
                                    $subtot09=$subtot09+$row->notasep;
                                    $grandtot09=$grandtot09+$row->notasep;
                                    $totkota=$totkota+$row->notasep;
                                    $totarea=$totarea+$row->notasep;
                                    $grandtot=$grandtot+$row->notasep;
                                    break;
                                    case '10' :
                                    $total=$total+$row->notaoct;
                                    echo '<th style="text-align: right">'.number_format($row->notaoct).'</th>';
                                    $subtot10=$subtot10+$row->notaoct;
                                    $grandtot10=$grandtot10+$row->notaoct;
                                    $totkota=$totkota+$row->notaoct;
                                    $totarea=$totarea+$row->notaoct;
                                    $grandtot=$grandtot+$row->notaoct;
                                    break;
                                    case '11' :
                                    $total=$total+$row->notanov;
                                    echo '<th style="text-align: right">'.number_format($row->notanov).'</th>';
                                    $subtot11=$subtot11+$row->notanov;
                                    $grandtot11=$grandtot11+$row->notanov;
                                    $totkota=$totkota+$row->notanov;
                                    $totarea=$totarea+$row->notanov;
                                    $grandtot=$grandtot+$row->notanov;
                                    break;
                                    case '12' :
                                    $total=$total+$row->notades;
                                    echo '<th style="text-align: right">'.number_format($row->notades).'</th>';
                                    $subtot12=$subtot12+$row->notades;
                                    $grandtot12=$grandtot12+$row->notades;
                                    $totkota=$totkota+$row->notades;
                                    $totarea=$totarea+$row->notades;
                                    $grandtot=$grandtot+$row->notades;
                                    break;
                                }
                                $bl++;
                                if($bl==13)$bl=1;
                            }
                            echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totkota).'</th></tr>';
                        }
                        $icity=$isiti;
                        $iarea=$riario;
                    }
                    echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=2 align=center>T o t a l   Area</td>";
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
                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($totarea).'</th></tr>';


                    echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=2 align=center>G r a n d    T o t a l</td>";
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
                    echo '<th style="background-color:#F2F2F2; text-align: right;">'.number_format($grandtot).'</th></tr>';
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
