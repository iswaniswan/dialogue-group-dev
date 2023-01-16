<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <div class="white-box">
        <h3 class="box-title">Daftar Nota</h3>
        <div class="table-responsive">
            <table id="tabledata" class="table color-bordered-table info-bordered-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Kode Lang</th>
                        <th>Kode Sales</th>
                        <th>No Nota</th>
                        <th>Tgl Nota</th>
                        <th>Jatuh Tempo</th>
                        <th>Nilai Bersih</th>
                        <th>Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($isi){
                        $tbersih=0;
                        $tsisa=0;
                        foreach($isi as $row){
                            if($row->d_nota!=''){
                                $tmp=explode('-',$row->d_nota);
                                $tgl=$tmp[2];
                                $bln=$tmp[1];
                                $thn=$tmp[0];
                                $row->d_nota=$tgl.'-'.$bln.'-'.$thn;
                            }
                            echo "<tr> 
                            <td style='font-size: 12px;'>$row->i_customer</td>
                            <td style='font-size: 12px;'>$row->i_salesman</td>
                            <td style='font-size: 12px;'>$row->i_nota</td>
                            <td style='font-size: 12px;'>$row->d_nota</td>
                            <td style='font-size: 12px;'>$row->d_jatuh_tempo</td>
                            <td style='font-size: 12px;' align=right>".number_format($row->v_nota_netto)."</td>
                            <td style='font-size: 12px;' align=right>".number_format($row->v_sisa)."</td>
                            </tr>";
                            $tbersih=$tbersih+$row->v_nota_netto;
                            $tsisa=$tsisa+$row->v_sisa;
                        }
                        echo "<tr> 
                            <td style='font-size: 12px;' align=right colspan=5>Total</td>
                            <td style='font-size: 12px;' align=right>".number_format($tbersih)."</td>
                            <td style='font-size: 12px;' align=right>".number_format($tsisa)."</td>
                            </tr>";
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
