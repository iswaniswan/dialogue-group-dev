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
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <table class="tablesaw table-bordered table-hover table" id="sitabel">
                <thead>
                <?php
                if($isi){?>
                    <tr>
                      <th rowspan=2>AREA</th>
	                    <th rowspan=2>K-LANG</th>
	                    <th rowspan=2>KOTA/KAB</th>
                      <th rowspan=2>DIVISI</th>
	                    <th rowspan=2>SALESMAN</th>
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
                          <th colspan=<?php echo $interval; ?> align=center>SPB</th>
                          <?php 
                              echo '<th rowspan=2>Total SPB</th>';
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
          if($icity=='' || ($icity==$row->icity && $kode==substr($row->kode,0,2)) ){
	        echo "<tr>
                  <td>".substr($row->kode,0,2)."-".$row->area."</td>
                  <td>$row->kode</td>
                  <td>$row->kota</td>
                  <td>$row->group</td>
                  <td>$row->sales</td>
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
                $total=$total+$row->spbjan;
                echo '<td align=right>'.number_format($row->spbjan).'</td>';
                $subtot01=$subtot01+$row->spbjan;
                $totarea01=$totarea01+$row->spbjan;
                $grandtot01=$grandtot01+$row->spbjan;
                $totkota=$totkota+$row->spbjan;
                $totarea=$totarea+$row->spbjan;
                break;
              case '2' :
                $total=$total+$row->spbfeb;
                echo '<td align=right>'.number_format($row->spbfeb).'</td>';
                $subtot02=$subtot02+$row->spbfeb;
                $totarea02=$totarea02+$row->spbfeb;
                $grandtot02=$grandtot02+$row->spbfeb;
                $totkota=$totkota+$row->spbfeb;
                $totarea=$totarea+$row->spbfeb;
                break;
              case '3' :
                $total=$total+$row->spbmar;
                echo '<td align=right>'.number_format($row->spbmar).'</td>';
                $subtot03=$subtot03+$row->spbmar;
                $totarea03=$totarea03+$row->spbmar;
                $grandtot03=$grandtot03+$row->spbmar;
                $totkota=$totkota+$row->spbmar;
                $totarea=$totarea+$row->spbmar;
                break;
              case '4' :
                $total=$total+$row->spbapr;
                echo '<td align=right>'.number_format($row->spbapr).'</td>';
                $subtot04=$subtot04+$row->spbapr;
                $totarea04=$totarea04+$row->spbapr;
                $grandtot04=$grandtot04+$row->spbapr;
                $totkota=$totkota+$row->spbapr;
                $totarea=$totarea+$row->spbapr;
                break;
              case '5' :
                $total=$total+$row->spbmay;
                echo '<td align=right>'.number_format($row->spbmay).'</td>';
                $subtot05=$subtot05+$row->spbmay;
                $totarea05=$totarea05+$row->spbmay;
                $grandtot05=$grandtot05+$row->spbmay;
                $totkota=$totkota+$row->spbmay;
                $totarea=$totarea+$row->spbmay;
                break;
              case '6' :
                $total=$total+$row->spbjun;
                echo '<td align=right>'.number_format($row->spbjun).'</td>';
                $subtot06=$subtot06+$row->spbjun;
                $totarea06=$totarea06+$row->spbjun;
                $grandtot06=$grandtot06+$row->spbjun;
                $totkota=$totkota+$row->spbjun;
                $totarea=$totarea+$row->spbjun;
                break;
              case '7' :
                $total=$total+$row->spbjul;
                echo '<td align=right>'.number_format($row->spbjul).'</td>';
                $subtot07=$subtot07+$row->spbjul;
                $totarea07=$totarea07+$row->spbjul;
                $grandtot07=$grandtot07+$row->spbjul;
                $totkota=$totkota+$row->spbjul;
                $totarea=$totarea+$row->spbjul;
                break;
              case '8' :
                $total=$total+$row->spbaug;
                echo '<td align=right>'.number_format($row->spbaug).'</td>';
                $subtot08=$subtot08+$row->spbaug;
                $totarea08=$totarea08+$row->spbaug;
                $grandtot08=$grandtot08+$row->spbaug;
                $totkota=$totkota+$row->spbaug;
                $totarea=$totarea+$row->spbaug;
                break;
              case '9' :
                $total=$total+$row->spbsep;
                echo '<td align=right>'.number_format($row->spbsep).'</td>';
                $subtot09=$subtot09+$row->spbsep;
                $totarea09=$totarea09+$row->spbsep;
                $grandtot09=$grandtot09+$row->spbsep;
                $totkota=$totkota+$row->spbsep;
                $totarea=$totarea+$row->spbsep;
                break;
              case '10' :
                $total=$total+$row->spbokt;
                echo '<td align=right>'.number_format($row->spbokt).'</td>';
                $subtot10=$subtot10+$row->spbokt;
                $totarea10=$totarea10+$row->spbokt;
                $grandtot10=$grandtot10+$row->spbokt;
                $totkota=$totkota+$row->spbokt;
                $totarea=$totarea+$row->spbokt;
                break;
              case '11' :
                $total=$total+$row->spbnov;
                echo '<td align=right>'.number_format($row->spbnov).'</td>';
                $subtot11=$subtot11+$row->spbnov;
                $totarea11=$totarea11+$row->spbnov;
                $grandtot11=$grandtot11+$row->spbnov;
                $totkota=$totkota+$row->spbnov;
                $totarea=$totarea+$row->spbnov;
                break;
              case '12' :
                $total=$total+$row->spbdes;
                echo '<td align=right>'.number_format($row->spbdes).'</td>';
                $subtot12=$subtot12+$row->spbdes;
                $totarea12=$totarea12+$row->spbdes;
                $grandtot12=$grandtot12+$row->spbdes;
                $totkota=$totkota+$row->spbdes;
                $totarea=$totarea+$row->spbdes;
                break;
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }elseif( $kode!=substr($row->kode,0,2) ){
  	        echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=11 align=center>T o t a l   K o t a</td>";
            $bl=$blasal;
            for($i=1;$i<=$interval;$i++){
              switch ($bl){
              case '1' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</td>';
                break;
              case '2' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</th>';
                break;
              case '3' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</td>';
                break;
              case '4' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</td>';
                break;
              case '5' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</td>';
                break;
              case '6' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</td>';
                break;
              case '7' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</td>';
                break;
              case '8' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</td>';
                break;
              case '9' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</td>';
                break;
              case '10' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</td>';
                break;
              case '11' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</td>';
                break;
              case '12' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</td>';
                break;
              }
              $bl++;
              if($bl==13)$bl=1;
            }
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</td></tr>';
            $grandtotkota=$grandtotkota+$totkota;
              echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=11 align=center>T o t a l   A r e a</td>";
              $bl=$blasal;
              for($i=1;$i<=$interval;$i++){
                switch ($bl){
                case '1' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</td>';
                  break;
                case '2' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</td>';
                  break;
                case '3' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</td>';
                  break;
                case '4' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</td>';
                  break;
                case '5' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</td>';
                  break;
                case '6' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</td>';
                  break;
                case '7' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</td>';
                  break;
                case '8' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</td>';
                  break;
                case '9' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</td>';
                  break;
                case '10' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</td>';
                  break;
                case '11' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</td>';
                  break;
                case '12' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</td>';
                  break;
                }
                $bl++;
                if($bl==13)$bl=1;
              }
              echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</td></tr>';
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
                    <td>".substr($row->kode,0,2)."-".$row->area."</td>
                    <td>$row->kode</td>
                    <td>$row->kota</td>
                    <td>$row->sales</td>
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
                $total=$total+$row->spbjan;
                echo '<td align=right>'.number_format($row->spbjan).'</td>';
                $subtot01=$subtot01+$row->spbjan;
                $totarea01=$totarea01+$row->spbjan;
                $grandtot01=$grandtot01+$row->spbjan;
                $totkota=$totkota+$row->spbjan;
                $totarea=$totarea+$row->spbjan;
                break;
              case '2' :
                $total=$total+$row->spbfeb;
                echo '<td align=right>'.number_format($row->spbfeb).'</td>';
                $subtot02=$subtot02+$row->spbfeb;
                $totarea02=$totarea02+$row->spbfeb;
                $grandtot02=$grandtot02+$row->spbfeb;
                $totkota=$totkota+$row->spbfeb;
                $totarea=$totarea+$row->spbfeb;
                break;
              case '3' :
                $total=$total+$row->spbmar;
                echo '<td align=right>'.number_format($row->spbmar).'</td>';
                $subtot03=$subtot03+$row->spbmar;
                $totarea03=$totarea03+$row->spbmar;
                $grandtot03=$grandtot03+$row->spbmar;
                $totkota=$totkota+$row->spbmar;
                $totarea=$totarea+$row->spbmar;
                break;
              case '4' :
                $total=$total+$row->spbapr;
                echo '<td align=right>'.number_format($row->spbapr).'</td>';
                $subtot04=$subtot04+$row->spbapr;
                $totarea04=$totarea04+$row->spbapr;
                $grandtot04=$grandtot04+$row->spbapr;
                $totkota=$totkota+$row->spbapr;
                $totarea=$totarea+$row->spbapr;
                break;
              case '5' :
                $total=$total+$row->spbmay;
                echo '<td align=right>'.number_format($row->spbmay).'</td>';
                $subtot05=$subtot05+$row->spbmay;
                $totarea05=$totarea05+$row->spbmay;
                $grandtot05=$grandtot05+$row->spbmay;
                $totkota=$totkota+$row->spbmay;
                $totarea=$totarea+$row->spbmay;
                break;
              case '6' :
                $total=$total+$row->spbjun;
                echo '<td align=right>'.number_format($row->spbjun).'</td>';
                $subtot06=$subtot06+$row->spbjun;
                $totarea06=$totarea06+$row->spbjun;
                $grandtot06=$grandtot06+$row->spbjun;
                $totkota=$totkota+$row->spbjun;
                $totarea=$totarea+$row->spbjun;
                break;
              case '7' :
                $total=$total+$row->spbjul;
                echo '<td align=right>'.number_format($row->spbjul).'</td>';
                $subtot07=$subtot07+$row->spbjul;
                $totarea07=$totarea07+$row->spbjul;
                $grandtot07=$grandtot07+$row->spbjul;
                $totkota=$totkota+$row->spbjul;
                $totarea=$totarea+$row->spbjul;
                break;
              case '8' :
                $total=$total+$row->spbaug;
                echo '<td align=right>'.number_format($row->spbaug).'</td>';
                $subtot08=$subtot08+$row->spbaug;
                $totarea08=$totarea08+$row->spbaug;
                $grandtot08=$grandtot08+$row->spbaug;
                $totkota=$totkota+$row->spbaug;
                $totarea=$totarea+$row->spbaug;
                break;
              case '9' :
                $total=$total+$row->spbsep;
                echo '<td align=right>'.number_format($row->spbsep).'</td>';
                $subtot09=$subtot09+$row->spbsep;
                $totarea09=$totarea09+$row->spbsep;
                $grandtot09=$grandtot09+$row->spbsep;
                $totkota=$totkota+$row->spbsep;
                $totarea=$totarea+$row->spbsep;
                break;
              case '10' :
                $total=$total+$row->spbokt;
                echo '<td align=right>'.number_format($row->spbokt).'</td>';
                $subtot10=$subtot10+$row->spbokt;
                $totarea10=$totarea10+$row->spbokt;
                $grandtot10=$grandtot10+$row->spbokt;
                $totkota=$totkota+$row->spbokt;
                $totarea=$totarea+$row->spbokt;
                break;
              case '11' :
                $total=$total+$row->spbnov;
                echo '<td align=right>'.number_format($row->spbnov).'</td>';
                $subtot11=$subtot11+$row->spbnov;
                $totarea11=$totarea11+$row->spbnov;
                $grandtot11=$grandtot11+$row->spbnov;
                $totkota=$totkota+$row->spbnov;
                $totarea=$totarea+$row->spbnov;
                break;
              case '12' :
                $total=$total+$row->spbdes;
                echo '<td align=right>'.number_format($row->spbdes).'</td>';
                $subtot12=$subtot12+$row->spbdes;
                $totarea12=$totarea12+$row->spbdes;
                $grandtot12=$grandtot12+$row->spbdes;
                $totkota=$totkota+$row->spbdes;
                $totarea=$totarea+$row->spbdes;
                break;
              }
              $bl++;
              if($bl==13)$bl=1;
            }
#*#*    #*#*#*
          }elseif( ($icity!='' && $icity!=$row->icity) ){
  	        echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=11 align=center>T o t a l   K o t a</td>";
            $bl=$blasal;
            for($i=1;$i<=$interval;$i++){
              switch ($bl){
              case '1' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</td>';
                break;
              case '2' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</td>';
                break;
              case '3' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</td>';
                break;
              case '4' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</td>';
                break;
              case '5' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</td>';
                break;
              case '6' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</td>';
                break;
              case '7' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</td>';
                break;
              case '8' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</td>';
                break;
              case '9' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</td>';
                break;
              case '10' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</td>';
                break;
              case '11' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</td>';
                break;
              case '12' :
                echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</td>';
                break;
              }
              $bl++;
              if($bl==13)$bl=1;
            }
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</td></tr>';
            $grandtotkota=$grandtotkota+$totkota;
            if($kode!=substr($row->kode,0,2)){
              echo "<tr>
                    <td style='background-color:#F2F2F2;' colspan=11 align=center>T o t a l   A r e a</td>";
              $bl=$blasal;
              for($i=1;$i<=$interval;$i++){
                switch ($bl){
                case '1' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</td>';
                  break;
                case '2' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</td>';
                  break;
                case '3' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</td>';
                  break;
                case '4' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</td>';
                  break;
                case '5' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</td>';
                  break;
                case '6' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</td>';
                  break;
                case '7' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</td>';
                  break;
                case '8' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</td>';
                  break;
                case '9' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</td>';
                  break;
                case '10' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</td>';
                  break;
                case '11' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</td>';
                  break;
                case '12' :
                  echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</td>';
                  break;
                }
                $bl++;
                if($bl==13)$bl=1;
              }
              echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</td></tr>';
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
                    <td>".substr($row->kode,0,2)."-".$row->area."</td>
                    <td>$row->kode</td>
                    <td>$row->kota</td>
                    <td>$row->group</td>
                    <td>$row->sales</td>
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
                $total=$total+$row->spbjan;
                echo '<td align=right>'.number_format($row->spbjan).'</td>';
                $subtot01=$subtot01+$row->spbjan;
                $totarea01=$totarea01+$row->spbjan;
                $grandtot01=$grandtot01+$row->spbjan;
                $totkota=$totkota+$row->spbjan;
                $totarea=$totarea+$row->spbjan;
                break;
              case '2' :
                $total=$total+$row->spbfeb;
                echo '<td align=right>'.number_format($row->spbfeb).'</td>';
                $subtot02=$subtot02+$row->spbfeb;
                $totarea02=$totarea02+$row->spbfeb;
                $grandtot02=$grandtot02+$row->spbfeb;
                $totkota=$totkota+$row->spbfeb;
                $totarea=$totarea+$row->spbfeb;
                break;
              case '3' :
                $total=$total+$row->spbmar;
                echo '<td align=right>'.number_format($row->spbmar).'</td>';
                $subtot03=$subtot03+$row->spbmar;
                $totarea03=$totarea03+$row->spbmar;
                $grandtot03=$grandtot03+$row->spbmar;
                $totkota=$totkota+$row->spbmar;
                $totarea=$totarea+$row->spbmar;
                break;
              case '4' :
                $total=$total+$row->spbapr;
                echo '<td align=right>'.number_format($row->spbapr).'</td>';
                $subtot04=$subtot04+$row->spbapr;
                $totarea04=$totarea04+$row->spbapr;
                $grandtot04=$grandtot04+$row->spbapr;
                $totkota=$totkota+$row->spbapr;
                $totarea=$totarea+$row->spbapr;
                break;
              case '5' :
                $total=$total+$row->spbmay;
                echo '<td align=right>'.number_format($row->spbmay).'</td>';
                $subtot05=$subtot05+$row->spbmay;
                $totarea05=$totarea05+$row->spbmay;
                $grandtot05=$grandtot05+$row->spbmay;
                $totkota=$totkota+$row->spbmay;
                $totarea=$totarea+$row->spbmay;
                break;
              case '6' :
                $total=$total+$row->spbjun;
                echo '<td align=right>'.number_format($row->spbjun).'</td>';
                $subtot06=$subtot06+$row->spbjun;
                $totarea06=$totarea06+$row->spbjun;
                $grandtot06=$grandtot06+$row->spbjun;
                $totkota=$totkota+$row->spbjun;
                $totarea=$totarea+$row->spbjun;
                break;
              case '7' :
                $total=$total+$row->spbjul;
                echo '<td align=right>'.number_format($row->spbjul).'</td>';
                $subtot07=$subtot07+$row->spbjul;
                $totarea07=$totarea07+$row->spbjul;
                $grandtot07=$grandtot07+$row->spbjul;
                $totkota=$totkota+$row->spbjul;
                $totarea=$totarea+$row->spbjul;
                break;
              case '8' :
                $total=$total+$row->spbaug;
                echo '<td align=right>'.number_format($row->spbaug).'</td>';
                $subtot08=$subtot08+$row->spbaug;
                $totarea08=$totarea08+$row->spbaug;
                $grandtot08=$grandtot08+$row->spbaug;
                $totkota=$totkota+$row->spbaug;
                $totarea=$totarea+$row->spbaug;
                break;
              case '9' :
                $total=$total+$row->spbsep;
                echo '<td align=right>'.number_format($row->spbsep).'</td>';
                $subtot09=$subtot09+$row->spbsep;
                $totarea09=$totarea09+$row->spbsep;
                $grandtot09=$grandtot09+$row->spbsep;
                $totkota=$totkota+$row->spbsep;
                $totarea=$totarea+$row->spbsep;
                break;
              case '10' :
                $total=$total+$row->spbokt;
                echo '<td align=right>'.number_format($row->spbokt).'</td>';
                $subtot10=$subtot10+$row->spbokt;
                $totarea10=$totarea10+$row->spbokt;
                $grandtot10=$grandtot10+$row->spbokt;
                $totkota=$totkota+$row->spbokt;
                $totarea=$totarea+$row->spbokt;
                break;
              case '11' :
                $total=$total+$row->spbnov;
                echo '<td align=right>'.number_format($row->spbnov).'</td>';
                $subtot11=$subtot11+$row->spbnov;
                $totarea11=$totarea11+$row->spbnov;
                $grandtot11=$grandtot11+$row->spbnov;
                $totkota=$totkota+$row->spbnov;
                $totarea=$totarea+$row->spbnov;
                break;
              case '12' :
                $total=$total+$row->spbdes;
                echo '<td align=right>'.number_format($row->spbdes).'</td>';
                $subtot12=$subtot12+$row->spbdes;
                $totarea12=$totarea12+$row->spbdes;
                $grandtot12=$grandtot12+$row->spbdes;
                $totkota=$totkota+$row->spbdes;
                $totarea=$totarea+$row->spbdes;
                break;
              }
              $bl++;
              if($bl==13)$bl=1;
            }
          }
          echo '<td align=right>'.number_format($total).'</td>';
          echo "</tr>";
          $icity=$row->icity;
          $kode=substr($row->kode,0,2);
		    }
        echo "<tr>
              <td style='background-color:#F2F2F2;' colspan=11 align=center>T o t a l   K o t a</td>";
        $bl=$blasal;
        for($i=1;$i<=$interval;$i++){
          switch ($bl){
          case '1' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot01).'</td>';
            break;
          case '2' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot02).'</td>';
            break;
          case '3' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot03).'</td>';
            break;
          case '4' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot04).'</td>';
            break;
          case '5' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot05).'</td>';
            break;
          case '6' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot06).'</td>';
            break;
          case '7' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot07).'</td>';
            break;
          case '8' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot08).'</td>';
            break;
          case '9' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot09).'</td>';
            break;
          case '10' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot10).'</td>';
            break;
          case '11' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot11).'</td>';
            break;
          case '12' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($subtot12).'</td>';
            break;
          }
          $bl++;
          if($bl==13)$bl=1;
        }
        echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totkota).'</td></tr>';
        $grandtotkota=$grandtotkota+$totkota;
        echo "<tr>
              <td style='background-color:#F2F2F2;' colspan=11 align=center>T o t a l   A r e a</td>";
        $bl=$blasal;
        for($i=1;$i<=$interval;$i++){
          switch ($bl){
          case '1' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea01).'</td>';
            break;
          case '2' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea02).'</td>';
            break;
          case '3' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea03).'</td>';
            break;
          case '4' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea04).'</td>';
            break;
          case '5' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea05).'</td>';
            break;
          case '6' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea06).'</td>';
            break;
          case '7' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea07).'</td>';
            break;
          case '8' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea08).'</td>';
            break;
          case '9' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea09).'</td>';
            break;
          case '10' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea10).'</td>';
            break;
          case '11' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea11).'</td>';
            break;
          case '12' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea12).'</td>';
            break;
          }
          $bl++;
          if($bl==13)$bl=1;
        }
        echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($totarea).'</td></tr>';
        echo "<tr>
              <td style='background-color:#F2F2F2;' colspan=11 align=center>G r a n d   T o t a l</td>";
        $bl=$blasal;
        for($i=1;$i<=$interval;$i++){
          switch ($bl){
          case '1' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot01).'</td>';
            break;
          case '2' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot02).'</td>';
            break;
          case '3' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot03).'</td>';
            break;
          case '4' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot04).'</td>';
            break;
          case '5' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot05).'</td>';
            break;
          case '6' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot06).'</td>';
            break;
          case '7' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot07).'</td>';
            break;
          case '8' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot08).'</td>';
            break;
          case '9' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot09).'</td>';
            break;
          case '10' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot10).'</td>';
            break;
          case '11' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot11).'</td>';
            break;
          case '12' :
            echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtot12).'</td>';
            break;
          }
          $bl++;
          if($bl==13)$bl=1;
        }
        echo '<td style="background-color:#F2F2F2;" align=right>'.number_format($grandtotkota).'</td></tr>';
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
</form>
</div>
</div>

<script>
    $( "#cmdreset" ).click(function() {  
    	var Contents = $('#sitabel').html();    
    	window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#sitabel').html()) +  '</table>' );
  	});
</script>
