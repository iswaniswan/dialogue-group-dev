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
        <div class="table-responsive">
            <table class="tablesaw table-bordered table-hover table" id="sitabel">
                <thead>
                <?php
                if($isi){?>
                    <tr>
                      <th class="text-center" rowspan=2>AREA</th>
	                    <th class="text-center" rowspan=2>K-LANG</th>
	                    <th class="text-center" rowspan=2>KOTA/KAB</th>
		                  <th class="text-center" rowspan=2>NAMA LANG</th>
		                  <th class="text-center" rowspan=2>ALAMAT</th>
                      <th class="text-center" rowspan=2>KD PRODUK</th>
                      <th class="text-center" rowspan=2>PRODUK</th>
                      <?php 
                        if($dfrom!=''){
		                      $tmp=explode("-",$dfrom);
		                      $blasal=$tmp[1];
                          settype($bl,'integer');
	                      }
                        $bl=$blasal;
                      ?>
                      <th colspan=<?php echo $interval; ?> class="text-center">Nota</th>
                      <?php 
                          echo '<th rowspan=2>Total Nota</th>';
                      ?>
                    </tr>
                    <tr>
                    <?php 
                      for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                          case '1' :
                            echo '<th class="text-center">Jan</th>';
                            break;
                          case '2' :
                            echo '<th class="text-center">Feb</th>';
                            break;
                          case '3' :
                            echo '<th class="text-center">Mar</th>';
                            break;
                          case '4' :
                            echo '<th class="text-center">Apr</th>';
                            break;
                          case '5' :
                            echo '<th class="text-center">Mei</th>';
                            break;
                          case '6' :
                            echo '<th class="text-center">Jun</th>';
                            break;
                          case '7' :
                            echo '<th class="text-center">Jul</th>';
                            break;
                          case '8' :
                            echo '<th class="text-center">Agu</th>';
                            break;
                          case '9' :
                            echo '<th class="text-center">Sep</th>';
                            break;
                          case '10' :
                            echo '<th class="text-center">Okt</th>';
                            break;
                          case '11' :
                            echo '<th class="text-center">Nov</th>';
                            break;
                          case '12' :
                            echo '<th class="text-center">Des</th>';
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
                  $nama='';

		              foreach($isi as $row){
                    $total=0;
                    if($icity=='' || ($icity==$row->icity && $kode==$row->kode) ){
  	                  echo "<tr>
                              <td>".substr($row->kode,0,2)."-".$row->area."</td>
                              <td>$row->kode</td>
                              <td>$row->kota</td>
                              <td>$row->nama</td>
                              <td>$row->alamat</td>
                              <td>$row->iproduct</td>
                              <td>$row->eproductname</td>";
                      $bl=$blasal;
                      for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                        case '1' :
                          $total=$total+$row->notajan;
                          echo '<td align=right>'.number_format($row->notajan).'</td>';
                          $subtot01=$subtot01+$row->notajan;
                          $totarea01=$totarea01+$row->notajan;
                          $grandtot01=$grandtot01+$row->notajan;
                          $totkota=$totkota+$row->notajan;
                          $totarea=$totarea+$row->notajan;
                          break;
                        case '2' :
                          $total=$total+$row->notafeb;
                          echo '<td align=right>'.number_format($row->notafeb).'</td>';
                          $subtot02=$subtot02+$row->notafeb;
                          $totarea02=$totarea02+$row->notafeb;
                          $grandtot02=$grandtot02+$row->notafeb;
                          $totkota=$totkota+$row->notafeb;
                          $totarea=$totarea+$row->notafeb;
                          break;
                        case '3' :
                          $total=$total+$row->notamar;
                          echo '<td align=right>'.number_format($row->notamar).'</td>';
                          $subtot03=$subtot03+$row->notamar;
                          $totarea03=$totarea03+$row->notamar;
                          $grandtot03=$grandtot03+$row->notamar;
                          $totkota=$totkota+$row->notamar;
                          $totarea=$totarea+$row->notamar;
                          break;
                        case '4' :
                          $total=$total+$row->notaapr;
                          echo '<td align=right>'.number_format($row->notaapr).'</td>';
                          $subtot04=$subtot04+$row->notaapr;
                          $totarea04=$totarea04+$row->notaapr;
                          $grandtot04=$grandtot04+$row->notaapr;
                          $totkota=$totkota+$row->notaapr;
                          $totarea=$totarea+$row->notaapr;
                          break;
                        case '5' :
                          $total=$total+$row->notamay;
                          echo '<td align=right>'.number_format($row->notamay).'</td>';
                          $subtot05=$subtot05+$row->notamay;
                          $totarea05=$totarea05+$row->notamay;
                          $grandtot05=$grandtot05+$row->notamay;
                          $totkota=$totkota+$row->notamay;
                          $totarea=$totarea+$row->notamay;
                          break;
                        case '6' :
                          $total=$total+$row->notajun;
                          echo '<td align=right>'.number_format($row->notajun).'</td>';
                          $subtot06=$subtot06+$row->notajun;
                          $totarea06=$totarea06+$row->notajun;
                          $grandtot06=$grandtot06+$row->notajun;
                          $totkota=$totkota+$row->notajun;
                          $totarea=$totarea+$row->notajun;
                          break;
                        case '7' :
                          $total=$total+$row->notajul;
                          echo '<td align=right>'.number_format($row->notajul).'</td>';
                          $subtot07=$subtot07+$row->notajul;
                          $totarea07=$totarea07+$row->notajul;
                          $grandtot07=$grandtot07+$row->notajul;
                          $totkota=$totkota+$row->notajul;
                          $totarea=$totarea+$row->notajul;
                          break;
                        case '8' :
                          $total=$total+$row->notaaug;
                          echo '<td align=right>'.number_format($row->notaaug).'</td>';
                          $subtot08=$subtot08+$row->notaaug;
                          $totarea08=$totarea08+$row->notaaug;
                          $grandtot08=$grandtot08+$row->notaaug;
                          $totkota=$totkota+$row->notaaug;
                          $totarea=$totarea+$row->notaaug;
                          break;
                        case '9' :
                          $total=$total+$row->notasep;
                          echo '<td align=right>'.number_format($row->notasep).'</td>';
                          $subtot09=$subtot09+$row->notasep;
                          $totarea09=$totarea09+$row->notasep;
                          $grandtot09=$grandtot09+$row->notasep;
                          $totkota=$totkota+$row->notasep;
                          $totarea=$totarea+$row->notasep;
                          break;
                        case '10' :
                          $total=$total+$row->notaoct;
                          echo '<td align=right>'.number_format($row->notaoct).'</td>';
                          $subtot10=$subtot10+$row->notaoct;
                          $totarea10=$totarea10+$row->notaoct;
                          $grandtot10=$grandtot10+$row->notaoct;
                          $totkota=$totkota+$row->notaoct;
                          $totarea=$totarea+$row->notaoct;
                          break;
                        case '11' :
                          $total=$total+$row->notanov;
                          echo '<td align=right>'.number_format($row->notanov).'</td>';
                          $subtot11=$subtot11+$row->notanov;
                          $totarea11=$totarea11+$row->notanov;
                          $grandtot11=$grandtot11+$row->notanov;
                          $totkota=$totkota+$row->notanov;
                          $totarea=$totarea+$row->notanov;
                          break;
                        case '12' :
                          $total=$total+$row->notades;
                          echo '<td align=right>'.number_format($row->notades).'</td>';
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
                    }elseif( $kode!=$row->kode ){
  	                  echo "<tr>
                              <td colspan=7 align=center>T o t a l   ".$nama."</td>";
                      $bl=$blasal;
                      for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                          case '1' :
                            echo '<td align=right>'.number_format($subtot01).'</td>';
                            break;
                          case '2' :
                            echo '<td align=right>'.number_format($subtot02).'</td>';
                            break;
                          case '3' :
                            echo '<td align=right>'.number_format($subtot03).'</td>';
                            break;
                          case '4' :
                            echo '<td align=right>'.number_format($subtot04).'</td>';
                            break;
                          case '5' :
                            echo '<td align=right>'.number_format($subtot05).'</td>';
                            break;
                          case '6' :
                            echo '<td align=right>'.number_format($subtot06).'</td>';
                            break;
                          case '7' :
                            echo '<td align=right>'.number_format($subtot07).'</td>';
                            break;
                          case '8' :
                            echo '<td align=right>'.number_format($subtot08).'</td>';
                            break;
                          case '9' :
                            echo '<td align=right>'.number_format($subtot09).'</td>';
                            break;
                          case '10' :
                            echo '<td align=right>'.number_format($subtot10).'</td>';
                            break;
                          case '11' :
                            echo '<td align=right>'.number_format($subtot11).'</td>';
                            break;
                          case '12' :
                            echo '<td align=right>'.number_format($subtot12).'</td>';
                            break;
                        }
                        $bl++;
                        if($bl==13)$bl=1;
                      }
                      echo '<td align=right>'.number_format($totkota).'</td></tr>';
                      $grandtotkota=$grandtotkota+$totkota;
                      
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
                      $totkota=0;
  	                  echo "<tr>
                              <td>".substr($row->kode,0,2)."-".$row->area."</td>
                              <td>$row->kode</td>
                              <td>$row->kota</td>
                              <td>$row->nama</td>
                              <td>$row->alamat</td>
                              <td>$row->iproduct</td>
                              <td>$row->eproductname</td>";
                      $bl=$blasal;
                      for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                          case '1' :
                            $total=$total+$row->notajan;
                            echo '<td align=right>'.number_format($row->notajan).'</td>';
                            $subtot01=$subtot01+$row->notajan;
                            $totarea01=$totarea01+$row->notajan;
                            $grandtot01=$grandtot01+$row->notajan;
                            $totkota=$totkota+$row->notajan;
                            $totarea=$totarea+$row->notajan;
                            break;
                          case '2' :
                            $total=$total+$row->notafeb;
                            echo '<td align=right>'.number_format($row->notafeb).'</td>';
                            $subtot02=$subtot02+$row->notafeb;
                            $totarea02=$totarea02+$row->notafeb;
                            $grandtot02=$grandtot02+$row->notafeb;
                            $totkota=$totkota+$row->notafeb;
                            $totarea=$totarea+$row->notafeb;
                            break;
                          case '3' :
                            $total=$total+$row->notamar;
                            echo '<td align=right>'.number_format($row->notamar).'</td>';
                            $subtot03=$subtot03+$row->notamar;
                            $totarea03=$totarea03+$row->notamar;
                            $grandtot03=$grandtot03+$row->notamar;
                            $totkota=$totkota+$row->notamar;
                            $totarea=$totarea+$row->notamar;
                            break;
                          case '4' :
                            $total=$total+$row->notaapr;
                            echo '<td align=right>'.number_format($row->notaapr).'</td>';
                            $subtot04=$subtot04+$row->notaapr;
                            $totarea04=$totarea04+$row->notaapr;
                            $grandtot04=$grandtot04+$row->notaapr;
                            $totkota=$totkota+$row->notaapr;
                            $totarea=$totarea+$row->notaapr;
                            break;
                          case '5' :
                            $total=$total+$row->notamay;
                            echo '<td align=right>'.number_format($row->notamay).'</td>';
                            $subtot05=$subtot05+$row->notamay;
                            $totarea05=$totarea05+$row->notamay;
                            $grandtot05=$grandtot05+$row->notamay;
                            $totkota=$totkota+$row->notamay;
                            $totarea=$totarea+$row->notamay;
                            break;
                          case '6' :
                            $total=$total+$row->notajun;
                            echo '<td align=right>'.number_format($row->notajun).'</td>';
                            $subtot06=$subtot06+$row->notajun;
                            $totarea06=$totarea06+$row->notajun;
                            $grandtot06=$grandtot06+$row->notajun;
                            $totkota=$totkota+$row->notajun;
                            $totarea=$totarea+$row->notajun;
                            break;
                          case '7' :
                            $total=$total+$row->notajul;
                            echo '<td align=right>'.number_format($row->notajul).'</td>';
                            $subtot07=$subtot07+$row->notajul;
                            $totarea07=$totarea07+$row->notajul;
                            $grandtot07=$grandtot07+$row->notajul;
                            $totkota=$totkota+$row->notajul;
                            $totarea=$totarea+$row->notajul;
                            break;
                          case '8' :
                            $total=$total+$row->notaaug;
                            echo '<td align=right>'.number_format($row->notaaug).'</td>';
                            $subtot08=$subtot08+$row->notaaug;
                            $totarea08=$totarea08+$row->notaaug;
                            $grandtot08=$grandtot08+$row->notaaug;
                            $totkota=$totkota+$row->notaaug;
                            $totarea=$totarea+$row->notaaug;
                            break;
                          case '9' :
                            $total=$total+$row->notasep;
                            echo '<td align=right>'.number_format($row->notasep).'</td>';
                            $subtot09=$subtot09+$row->notasep;
                            $totarea09=$totarea09+$row->notasep;
                            $grandtot09=$grandtot09+$row->notasep;
                            $totkota=$totkota+$row->notasep;
                            $totarea=$totarea+$row->notasep;
                            break;
                          case '10' :
                            $total=$total+$row->notaoct;
                            echo '<td align=right>'.number_format($row->notaoct).'</td>';
                            $subtot10=$subtot10+$row->notaoct;
                            $totarea10=$totarea10+$row->notaoct;
                            $grandtot10=$grandtot10+$row->notaoct;
                            $totkota=$totkota+$row->notaoct;
                            $totarea=$totarea+$row->notaoct;
                            break;
                          case '11' :
                            $total=$total+$row->notanov;
                            echo '<td align=right>'.number_format($row->notanov).'</td>';
                            $subtot11=$subtot11+$row->notanov;
                            $totarea11=$totarea11+$row->notanov;
                            $grandtot11=$grandtot11+$row->notanov;
                            $totkota=$totkota+$row->notanov;
                            $totarea=$totarea+$row->notanov;
                            break;
                          case '12' :
                            $total=$total+$row->notades;
                            echo '<td align=right>'.number_format($row->notades).'</td>';
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
                              <td colspan=7 align=center>T o t a l   ".$nama."</td>";
                      $bl=$blasal;
                      for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                          case '1' :
                            echo '<td align=right>'.number_format($subtot01).'</td>';
                            break;
                          case '2' :
                            echo '<td align=right>'.number_format($subtot02).'</td>';
                            break;
                          case '3' :
                            echo '<td align=right>'.number_format($subtot03).'</td>';
                            break;
                          case '4' :
                            echo '<td align=right>'.number_format($subtot04).'</td>';
                            break;
                          case '5' :
                            echo '<td align=right>'.number_format($subtot05).'</td>';
                            break;
                          case '6' :
                            echo '<td align=right>'.number_format($subtot06).'</td>';
                            break;
                          case '7' :
                            echo '<td align=right>'.number_format($subtot07).'</td>';
                            break;
                          case '8' :
                            echo '<td align=right>'.number_format($subtot08).'</td>';
                            break;
                          case '9' :
                            echo '<td align=right>'.number_format($subtot09).'</td>';
                            break;
                          case '10' :
                            echo '<td align=right>'.number_format($subtot10).'</td>';
                            break;
                          case '11' :
                            echo '<td align=right>'.number_format($subtot11).'</td>';
                            break;
                          case '12' :
                            echo '<td align=right>'.number_format($subtot12).'</td>';
                            break;
                        }
                        $bl++;
                        if($bl==13)$bl=1;
                      }
                      echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</td></tr>';
                      $grandtotkota=$grandtotkota+$totkota;
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
                              <td>".substr($row->kode,0,2)."-".$row->area."</td>
                              <td>$row->kode</td>
                              <td>$row->kota</td>
                              <td>$row->nama</td>
                              <td>$row->alamat</td>
                              <td>$row->iproduct</td>
                              <td>$row->eproductname</td>";
                      $bl=$blasal;
                      for($i=1;$i<=$interval;$i++){
                        switch ($bl){
                          case '1' :
                            $total=$total+$row->notajan;
                            echo '<td align=right>'.number_format($row->notajan).'</td>';
                            $subtot01=$subtot01+$row->notajan;
                            $totarea01=$totarea01+$row->notajan;
                            $grandtot01=$grandtot01+$row->notajan;
                            $totkota=$totkota+$row->notajan;
                            $totarea=$totarea+$row->notajan;
                            break;
                          case '2' :
                            $total=$total+$row->notafeb;
                            echo '<td align=right>'.number_format($row->notafeb).'</td>';
                            $subtot02=$subtot02+$row->notafeb;
                            $totarea02=$totarea02+$row->notafeb;
                            $grandtot02=$grandtot02+$row->notafeb;
                            $totkota=$totkota+$row->notafeb;
                            $totarea=$totarea+$row->notafeb;
                            break;
                          case '3' :
                            $total=$total+$row->notamar;
                            echo '<td align=right>'.number_format($row->notamar).'</td>';
                            $subtot03=$subtot03+$row->notamar;
                            $totarea03=$totarea03+$row->notamar;
                            $grandtot03=$grandtot03+$row->notamar;
                            $totkota=$totkota+$row->notamar;
                            $totarea=$totarea+$row->notamar;
                            break;
                          case '4' :
                            $total=$total+$row->notaapr;
                            echo '<td align=right>'.number_format($row->notaapr).'</td>';
                            $subtot04=$subtot04+$row->notaapr;
                            $totarea04=$totarea04+$row->notaapr;
                            $grandtot04=$grandtot04+$row->notaapr;
                            $totkota=$totkota+$row->notaapr;
                            $totarea=$totarea+$row->notaapr;
                            break;
                          case '5' :
                            $total=$total+$row->notamay;
                            echo '<td align=right>'.number_format($row->notamay).'</td>';
                            $subtot05=$subtot05+$row->notamay;
                            $totarea05=$totarea05+$row->notamay;
                            $grandtot05=$grandtot05+$row->notamay;
                            $totkota=$totkota+$row->notamay;
                            $totarea=$totarea+$row->notamay;
                            break;
                          case '6' :
                            $total=$total+$row->notajun;
                            echo '<td align=right>'.number_format($row->notajun).'</td>';
                            $subtot06=$subtot06+$row->notajun;
                            $totarea06=$totarea06+$row->notajun;
                            $grandtot06=$grandtot06+$row->notajun;
                            $totkota=$totkota+$row->notajun;
                            $totarea=$totarea+$row->notajun;
                            break;
                          case '7' :
                            $total=$total+$row->notajul;
                            echo '<td align=right>'.number_format($row->notajul).'</td>';
                            $subtot07=$subtot07+$row->notajul;
                            $totarea07=$totarea07+$row->notajul;
                            $grandtot07=$grandtot07+$row->notajul;
                            $totkota=$totkota+$row->notajul;
                            $totarea=$totarea+$row->notajul;
                            break;
                          case '8' :
                            $total=$total+$row->notaaug;
                            echo '<td align=right>'.number_format($row->notaaug).'</td>';
                            $subtot08=$subtot08+$row->notaaug;
                            $totarea08=$totarea08+$row->notaaug;
                            $grandtot08=$grandtot08+$row->notaaug;
                            $totkota=$totkota+$row->notaaug;
                            $totarea=$totarea+$row->notaaug;
                            break;
                          case '9' :
                            $total=$total+$row->notasep;
                            echo '<td align=right>'.number_format($row->notasep).'</td>';
                            $subtot09=$subtot09+$row->notasep;
                            $totarea09=$totarea09+$row->notasep;
                            $grandtot09=$grandtot09+$row->notasep;
                            $totkota=$totkota+$row->notasep;
                            $totarea=$totarea+$row->notasep;
                            break;
                          case '10' :
                            $total=$total+$row->notaoct;
                            echo '<td align=right>'.number_format($row->notaoct).'</td>';
                            $subtot10=$subtot10+$row->notaoct;
                            $totarea10=$totarea10+$row->notaoct;
                            $grandtot10=$grandtot10+$row->notaoct;
                            $totkota=$totkota+$row->notaoct;
                            $totarea=$totarea+$row->notaoct;
                            break;
                          case '11' :
                            $total=$total+$row->notanov;
                            echo '<td align=right>'.number_format($row->notanov).'</td>';
                            $subtot11=$subtot11+$row->notanov;
                            $totarea11=$totarea11+$row->notanov;
                            $grandtot11=$grandtot11+$row->notanov;
                            $totkota=$totkota+$row->notanov;
                            $totarea=$totarea+$row->notanov;
                            break;
                          case '12' :
                            $total=$total+$row->notades;
                            echo '<td align=right>'.number_format($row->notades).'</td>';
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
                echo '<td align=right>'.number_format($total).'</td>';
                echo "</tr>";
                $icity=$row->icity;
                $kode=$row->kode;
                $nama=$row->nama;
		          }
              echo "<tr>
                    <td colspan=7 align=center>T o t a l   ".$nama."</td>";
              $bl=$blasal;
              for($i=1;$i<=$interval;$i++){
                switch ($bl){
                  case '1' :
                    echo '<td align=right>'.number_format($subtot01).'</td>';
                    break;
                  case '2' :
                    echo '<td align=right>'.number_format($subtot02).'</td>';
                    break;
                  case '3' :
                    echo '<td align=right>'.number_format($subtot03).'</td>';
                    break;
                  case '4' :
                    echo '<td align=right>'.number_format($subtot04).'</td>';
                    break;
                  case '5' :
                    echo '<td align=right>'.number_format($subtot05).'</td>';
                    break;
                  case '6' :
                    echo '<td align=right>'.number_format($subtot06).'</td>';
                    break;
                  case '7' :
                    echo '<td align=right>'.number_format($subtot07).'</td>';
                    break;
                  case '8' :
                    echo '<td align=right>'.number_format($subtot08).'</td>';
                    break;
                  case '9' :
                    echo '<td align=right>'.number_format($subtot09).'</td>';
                    break;
                  case '10' :
                    echo '<td align=right>'.number_format($subtot10).'</td>';
                    break;
                  case '11' :
                    echo '<td align=right>'.number_format($subtot11).'</td>';
                    break;
                  case '12' :
                    echo '<td align=right>'.number_format($subtot12).'</td>';
                    break;
                }
                $bl++;
                if($bl==13)$bl=1;
              }
              echo '<td align=right>'.number_format($totkota).'</td></tr>';
              $grandtotkota=$grandtotkota+$totkota;

              echo "<tr>
                    <th colspan=7 align=center>G r a n d   T o t a l</th>";
              $bl=$blasal;
              for($i=1;$i<=$interval;$i++){
                switch ($bl){
                  case '1' :
                    echo '<td align=right>'.number_format($grandtot01).'</td>';
                    break;
                  case '2' :
                    echo '<td lign=right>'.number_format($grandtot02).'</td>';
                    break;
                  case '3' :
                    echo '<td align=right>'.number_format($grandtot03).'</td>';
                    break;
                  case '4' :
                    echo '<td  align=right>'.number_format($grandtot04).'</td>';
                    break;
                  case '5' :
                    echo '<td align=right>'.number_format($grandtot05).'</td>';
                    break;
                  case '6' :
                    echo '<td align=right>'.number_format($grandtot06).'</td>';
                    break;
                  case '7' :
                    echo '<td align=right>'.number_format($grandtot07).'</td>';
                    break;
                  case '8' :
                    echo '<td align=right>'.number_format($grandtot08).'</td>';
                    break;
                  case '9' :
                    echo '<td align=right>'.number_format($grandtot09).'</td>';
                    break;
                  case '10' :
                    echo '<td align=right>'.number_format($grandtot10).'</td>';
                    break;
                  case '11' :
                    echo '<td align=right>'.number_format($grandtot11).'</td>';
                    break;
                  case '12' :
                    echo '<td align=right>'.number_format($grandtot12).'</td>';
                    break;
                }
                $bl++;
                if($bl==13)$bl=1;
              }
              echo '<td align=right>'.number_format($grandtotkota).'</td></tr>';
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
