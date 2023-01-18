<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <div class="white-box">
        <h3 class="box-title">Daftar Bayar Hutang Dagang</h3>
        <div class="table-responsive">
            <table id="tabledata" class="table color-bordered-table info-bordered-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No KN</th>
                        <th>Tgl KN</th>
                        <th>Jumlah</th>
                        <th>No Bukti</th>
                        <th>Tgl Bukti</th>
                        <th>Jml Bayar</th>
                        <th>Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($isi){
                        foreach($isi as $row){
                            if($row->d_kn!=''){
                                $tmp=explode('-',$row->d_kn);
                                $tgl=$tmp[2];
                                $bln=$tmp[1];
                                $thn=$tmp[0];
                                $row->d_kn=$tgl.'-'.$bln.'-'.$thn;
                            }
                            if($row->d_alokasi!=''){
                                $tmp=explode('-',$row->d_alokasi);
                                $tgl=$tmp[2];
                                $bln=$tmp[1];
                                $thn=$tmp[0];
                                $row->d_alokasi=$tgl.'-'.$bln.'-'.$thn;
                            }
                            if(!isset($saldo)){
                                $saldo=$row->v_netto;
                            }else{
                            $saldo=$saldo-$jmltmp;
                            }
                            echo "<tr> 
                            <td style='font-size: 12px;'>$row->i_kn</td>
                            <td style='font-size: 12px;'>$row->d_kn</td>
                            <td style='font-size: 12px;' align=right>".number_format($row->v_netto)."</td>
                            <td style='font-size: 12px;'>$row->i_alokasi</td>
                            <td style='font-size: 12px;'>$row->d_alokasi</td>
                            <td style='font-size: 12px;' align=right>".number_format($row->v_jumlah)."</td>";
                            $jmltmp=$row->v_jumlah;
                            $sisa=$saldo-$jmltmp;
                            echo "
                            <td style='font-size: 12px;' align=right>".number_format($sisa)."</td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="dipales();"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Keluar</button>&nbsp;
            <button type="button" name="cmdreset" id="cmdreset" class="btn btn-succes btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
    function dipales() {
        this.close();
    }

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });
</script>