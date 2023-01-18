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
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
        <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <h3>&nbsp;&nbsp;&nbsp;<?php echo 'Periode : '.substr($dfrom,0,2).' '.mbulan(substr($dfrom,3,2)).' '.substr($dfrom,6,4).' s/d '.substr($dto,0,2).' '.mbulan(substr($dto,3,2)).' '.substr($dto,6,4); ?></h3>
            <table class="tablesaw table-bordered table-hover table" id="sitabel">
                <thead>
                <?php
                if($isi){?>
                    <tr>
                      <th rowspan=2>AREA</th>
	                    <th rowspan=2>K-LANG</th>
	                    <th rowspan=2>KOTA/KAB</th>
	                    <th rowspan=2>JENIS</th>
		                  <th rowspan=2>NAMA LANG</th>
		                  <th rowspan=2>Tgl Daftar</th>
		                  <th rowspan=2>TOP</th>
		                  <th rowspan=2>PELUNASAN</th>
                      <?php 
                        if($dfrom!=''){
		                      $tmp=explode("-",$dfrom);
		                      $blasal=$tmp[1];
                          settype($bl,'integer');
	                      }
                        $bl=$blasal;
                      ?>
                      <th colspan=<?php echo $interval; ?> align=center>SPB</th>
                      <th colspan=<?php echo $interval; ?> align=center>Nota</th>
                      <?php 
                          echo '<th rowspan=2>Rata2 SPB</th>';
                          echo '<th rowspan=2>Rata2 Nota</th>';
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
                          $bl=$blasal;
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
			foreach($isi as $row){
        $pl=0;
        $this->db->select(" sum(v_jumlah) as v_jumlah from tm_pelunasan where i_area = substring('$row->kode',1,2) and f_pelunasan_cancel='f'
                            AND d_bukti >= to_date('$dfrom','dd-mm-yyyy') AND d_bukti <= to_date('$dto','dd-mm-yyyy')
                            and i_customer='$row->kode' and f_giro_batal='f' and i_jenis_bayar<>'04' and i_jenis_bayar<>'05'", false);
        $query = $this->db->get();
	      if ($query->num_rows() > 0){
		      foreach($query->result() as $xx){
		        $pl=$xx->v_jumlah;
		      }
	      }
#echo $pl.'<br>';
        $rata=0;
        $ratapb=0;
        $tmp=explode('-',$row->tgldaftar);
        $th=$tmp[0];
        $bl=$tmp[1];
        $hr=$tmp[2];
        $row->tgldaftar=$hr.'-'.$bl.'-'.$th;
  	    echo "<tr>
                <td>".substr($row->kode,0,2)."-".$row->area."</td>
                <td>$row->kode</td>
                <td>$row->kota</td>
            	  <td>$row->jenis</td>
                <td>$row->nama</td>
                <td>$row->tgldaftar</td>
                <td>$row->top</td>";
        echo "  <td>".number_format($pl,0)."</td>";
        $bl=$blasal;
        for($i=1;$i<=$interval;$i++){
          switch ($bl){
          case '1' :
            $ratapb=$ratapb+$row->spbjan;
            echo '<td align=right>'.number_format($row->spbjan).'</td>';
            break;
          case '2' :
            $ratapb=$ratapb+$row->spbfeb;
            echo '<td align=right>'.number_format($row->spbfeb).'</td>';
            break;
          case '3' :
            $ratapb=$ratapb+$row->spbmar;
            echo '<td align=right>'.number_format($row->spbmar).'</td>';
            break;
          case '4' :
            $ratapb=$ratapb+$row->spbapr;
            echo '<td align=right>'.number_format($row->spbapr).'</td>';
            break;
          case '5' :
            $ratapb=$ratapb+$row->spbmay;
            echo '<td align=right>'.number_format($row->spbmay).'</td>';
            break;
          case '6' :
            $ratapb=$ratapb+$row->spbjun;
            echo '<td align=right>'.number_format($row->spbjun).'</td>';
            break;
          case '7' :
            $ratapb=$ratapb+$row->spbjul;
            echo '<td align=right>'.number_format($row->spbjul).'</td>';
            break;
          case '8' :
            $ratapb=$ratapb+$row->spbaug;
            echo '<td align=right>'.number_format($row->spbaug).'</td>';
            break;
          case '9' :
            $ratapb=$ratapb+$row->spbsep;
            echo '<td align=right>'.number_format($row->spbsep).'</td>';
            break;
          case '10' :
            $ratapb=$ratapb+$row->spbokt;
            echo '<td align=right>'.number_format($row->spbokt).'</td>';
            break;
          case '11' :
            $ratapb=$ratapb+$row->spbnov;
            echo '<td align=right>'.number_format($row->spbnov).'</td>';
            break;
          case '12' :
            $ratapb=$ratapb+$row->spbdes;
            echo '<td align=right>'.number_format($row->spbdes).'</td>';
            break;
          }
          $bl++;
          if($bl==13)$bl=1;
        }

        $bl=$blasal;
        for($i=1;$i<=$interval;$i++){
          switch ($bl){
          case '1' :
            $rata=$rata+$row->notajan;
            echo '<td align=right>'.number_format($row->notajan).'</td>';
            break;
          case '2' :
            $rata=$rata+$row->notafeb;
            echo '<td align=right>'.number_format($row->notafeb).'</td>';
            break;
          case '3' :
            $rata=$rata+$row->notamar;
            echo '<td align=right>'.number_format($row->notamar).'</td>';
            break;
          case '4' :
            $rata=$rata+$row->notaapr;
            echo '<td align=right>'.number_format($row->notaapr).'</td>';
            break;
          case '5' :
            $rata=$rata+$row->notamay;
            echo '<td align=right>'.number_format($row->notamay).'</td>';
            break;
          case '6' :
            $rata=$rata+$row->notajun;
            echo '<td align=right>'.number_format($row->notajun).'</td>';
            break;
          case '7' :
            $rata=$rata+$row->notajul;
            echo '<td align=right>'.number_format($row->notajul).'</td>';
            break;
          case '8' :
            $rata=$rata+$row->notaaug;
            echo '<td align=right>'.number_format($row->notaaug).'</td>';
            break;
          case '9' :
            $rata=$rata+$row->notasep;
            echo '<td align=right>'.number_format($row->notasep).'</td>';
            break;
          case '10' :
            $rata=$rata+$row->notaokt;
            echo '<td align=right>'.number_format($row->notaokt).'</td>';
            break;
          case '11' :
            $rata=$rata+$row->notanov;
            echo '<td align=right>'.number_format($row->notanov).'</td>';
            break;
          case '12' :
            $rata=$rata+$row->notades;
            echo '<td align=right>'.number_format($row->notades).'</td>';
            break;
          }
          $bl++;
          if($bl==13)$bl=1;
        }
        $ratapb=$ratapb/$interval;
        $rata=$rata/$interval;
        echo '<td align=right>'.number_format($ratapb).'</td>';
        echo '<td align=right>'.number_format($rata).'</td>';
        echo "</tr>";
			}
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
