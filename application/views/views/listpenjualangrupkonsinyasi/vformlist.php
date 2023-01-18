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
                        <th>K-LANG GRUP</th>
                        <th>NAMA LANG GRUP</th>
                        <?php 
                        if($dfrom!=''){
                            $tmp=explode("-",$dfrom);
                            $blasal=$tmp[1];
                            settype($bl,'integer');
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
                        echo '<th>Rata2</th>';
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach($isi as $row){
                        $rata=0;
                        echo "<tr>
                        <td>$row->kode</td>
                        <td>$row->nama</td>";
                        $bl=$blasal;
                        for($i=1;$i<=$interval;$i++){
                            switch ($bl){
                                case '1' :
                                $rata=$rata+$row->notajan;
                                echo '<th style="text-align: right">'.number_format($row->notajan).'</th>';
                                break;
                                case '2' :
                                $rata=$rata+$row->notafeb;
                                echo '<th style="text-align: right">'.number_format($row->notafeb).'</th>';
                                break;
                                case '3' :
                                $rata=$rata+$row->notamar;
                                echo '<th style="text-align: right">'.number_format($row->notamar).'</th>';
                                break;
                                case '4' :
                                $rata=$rata+$row->notaapr;
                                echo '<th style="text-align: right">'.number_format($row->notaapr).'</th>';
                                break;
                                case '5' :
                                $rata=$rata+$row->notamay;
                                echo '<th style="text-align: right">'.number_format($row->notamay).'</th>';
                                break;
                                case '6' :
                                $rata=$rata+$row->notajun;
                                echo '<th style="text-align: right">'.number_format($row->notajun).'</th>';
                                break;
                                case '7' :
                                $rata=$rata+$row->notajul;
                                echo '<th style="text-align: right">'.number_format($row->notajul).'</th>';
                                break;
                                case '8' :
                                $rata=$rata+$row->notaaug;
                                echo '<th style="text-align: right">'.number_format($row->notaaug).'</th>';
                                break;
                                case '9' :
                                $rata=$rata+$row->notasep;
                                echo '<th style="text-align: right">'.number_format($row->notasep).'</th>';
                                break;
                                case '10' :
                                $rata=$rata+$row->notaoct;
                                echo '<th style="text-align: right">'.number_format($row->notaoct).'</th>';
                                break;
                                case '11' :
                                $rata=$rata+$row->notanov;
                                echo '<th style="text-align: right">'.number_format($row->notanov).'</th>';
                                break;
                                case '12' :
                                $rata=$rata+$row->notades;
                                echo '<th style="text-align: right">'.number_format($row->notades).'</th>';
                                break;
                            }
                            $bl++;
                            if($bl==13)$bl=1;
                        }
                        $rata=$rata/$interval;
                        echo '<th style="text-align: right">'.number_format($rata).'</th>';
                        echo "</tr>";
                    }
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
