<link href="<?= base_url(); ?>assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/colors/green.css" id="theme" rel="stylesheet">
<div class="col-sm-12">
    <!-- div awal -->
    <?php 
        $periode=$iperiode;
        $a=substr($periode,0,4);
        $b=substr($periode,4,2);
        $iperiode= $this->fungsi->mbulan($b)." - ".$a;
    ?>
    <h3 class="box-title" style="text-align: center;"><?= $title; ?></h3>
    <p class="text-muted" style="text-align: center;">Periode <?= $iperiode;?></p>
    <div class="panel-body table-responsive">
        
        <div class="col-md-12 text-center"> 
            <button type = "button" name = "cmdreset" id = "cmdreset" class="btn btn-success btn-rounded btn-success btn-med">Export</button>
        </div>

        <table class="table color-bordered-table info-bordered-table" id="sitabel" cellpadding="0" cellspacing="0" border=1>
            <thead>
                <input name="iperiode" id="iperiode" value="<?php echo $iperiode; ?>" type="hidden" readonly>
                <?php if($isi){ ?>
                <tr>
                    <th style="font-size: 16px;text-align: center;">No</th>
                    <th style="font-size: 16px;text-align: center;">Kode Produk</th>
                    <th style="font-size: 16px;text-align: center;">Nama Produk</th>
                    <th style="font-size: 16px;text-align: center;">Motif</th>
                    <th style="font-size: 16px;text-align: center;">Grade</th>
                    <th style="font-size: 16px;text-align: center;">Jumlah</th>
                </tr>
            </thead>

            <tbody>
            <?php 
                    if($isi){
                        $i=1;
                        foreach($isi as $row){
                            if($row->f_ic_convertion=='t'){
                                echo "<tr>
                                        <td style='font-size: 16px;text-align: center;'>$i</td>
                                        <td style='font-size: 16px;text-align: center;'>$row->ic_product</td>
                                        <td style='font-size: 16px;text-align: left;'>$row->ic_product_name</td>
                                        <td style='font-size: 16px;text-align: center;'>$row->ic_product_motif</td>
                                        <td style='font-size: 16px;text-align: center;'>$row->ic_product_grade</td>
                                        <td style='font-size: 16px;text-align: center;'>$row->ic_n_convertion</td>
                                      </tr>";
                              }elseif($row->f_ic_convertion=='f'){
                                echo "<tr>
                                        <td style='font-size: 16px;text-align: center;'>$i</td>
                                        <td style='font-size: 16px;text-align: center;'>$row->item_product</td>
                                        <td style='font-size: 16px;text-align: left;'>$row->item_product_name</td>
                                        <td style='font-size: 16px;text-align: center;'>$row->item_product_motif</td>
                                        <td style='font-size: 16px;text-align: center;'>$row->item_product_grade</td>
                                        <td style='font-size: 16px;text-align: center;'>$row->item_n_convertion</td>
                                      </tr>";	
                                }
                        $i++;
                        }
                    }
            ?>
            </tbody>
            <?php }?>
            <!-- end if isi -->
        </table>
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