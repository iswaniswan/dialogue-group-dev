<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
            <div class="col-md-4">
                    <div class="form-group row">
                        <!-- <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label> -->
                        <div class="col-sm-5">
                            <!-- <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>" onchange="gantidata();"> -->
                        </div>
                        <div class="col-sm-5">
                            <!-- <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>" onchange="gantidata();"> -->
                        </div>
                    </div>
                </div>
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Forcast</th>
                            <th>Kode Product</th>
                            <th>Nama Product</th>
                            <th>warna</th>
                            <th>Qty Forcast</th>
                            <th>Qty OP</th>
                            <th>Sisa Forcast</th>
                            <th>Fc vs OP(%)</th>
                            <th>Qty Forcast</th>
                            <th>Qty DO</th>
                            <th>Sisa Forcast</th>
                            <th>Fc vs DO(%)</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <!-- <th colspan="4" style="text-align: center;">Total :</th>
                        <th><?= $total->op;?></th>
                        <th><?= $total->btb;?></th>
                        <th><?= $total->sisa;?></th> -->
                    </tfoot>
                </table>
                <br>
                <!-- <button type="button" name="cmdreset" id="cmdreset" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-download"></i>&nbsp;&nbsp;Export</button> -->
                <a id="downloadLink" onclick="exportF(this);"><button type="button" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button> </a>
            </div>
        </div>
        <!-- <input type="hidden" id ="dfrom" name="dfrom" value="<?php echo $dfrom ?>" >
        <input type="hidden" id="dto" name="dto" value="<?php echo $dto ?>" > -->
        <input type="hidden" id="periode" name="periode" value="<?php echo $periode ?>" >
<!-- ------------------------------------------------------------------------------------------------------------------------ -->
<table hidden id="tableexport" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align:center;font-size:13px;" colspan=13>REPORT REALISASI FORECAT</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;font-size:13px;" colspan=13>PERIODE : <?=$periode?> dari Tanggal <?=$dfrom;?> s/d <?=$dto;?></th>
                        </tr>
                        <tr>
                            <th style="text-align:right;" colspan=13></th>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>Nomor Forcast</th>
                            <th>Kode Product</th>
                            <th>Nama Product</th>
                            <th>warna</th>
                            <th>Qty Forcast</th>
                            <th>Qty OP</th>
                            <th>Sisa Forcast</th>
                            <th>Fc vs OP(%)</th>
                            <th>Qty Forcast</th>
                            <th>Qty DO</th>
                            <th>Sisa Forcast</th>
                            <th>Fc vs DO(%)</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                            foreach($isi as $row){
                                echo "<tr>
                                        <td>$row->i</td>
                                        <td>$row->i_fc</td>
                                        <td>$row->i_product</td>
                                        <td>$row->e_product_basename</td>
                                        <td>$row->e_color_name</td>
                                        <td>$row->qtyforecast</td>
                                        <td>$row->qtyop</td>
                                        <td>$row->sisafc</td>
                                        <td>$row->fcvsop</td>
                                        <td>$row->qtyforecast1</td>
                                        <td>$row->qtydo</td>
                                        <td>$row->sisafc1</td>
                                        <td>$row->fcvsdo</td>
                                    </tr>";
                            }
                        ?>
                    </tbody>
                </table>
<!-- ------------------------------------------------------------------------------------------------------------------------ -->
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tabledata').DataTable().clear().destroy();
        $('.select2').select2();
        showCalendar('.date');
        var groupColumn = 2;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $periode."/".$dfrom."/".$dto; ?>",
                "type": "POST"
            },
            "order": [[ 2, 'asc' ],[ 9, 'asc' ]],
            "displayLength": 10,
        });
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });

    function gantidata() {
       $('#tabledata').DataTable().clear().destroy();

        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        var supplier = $('#supplier').val();
        $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                // "url": "<?= site_url($folder); ?>/Cform/data/"+dfrom+"/"+dto,
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $periode."/".$dfrom."/".$dto; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
     } 

    function exportF(elem) {
        var dfrom       = $('#dfrom').val();
        var dto         = $('#dto').val();
        var periode         = $('#periode').val();
        // var ikategori   = $('#ekategori').val();
        // var ijenis      = $('#ejenis').val();
        var table       = document.getElementById("tableexport");
        var html        = table.outerHTML;
        var url         = 'data:application/vnd.ms-Excel,' + escape(html); // Set your html table into url 
        elem.setAttribute("href", url);
        elem.setAttribute("download", "Report Realisasi Forecast "+dfrom+"-sd-"+dto+" Periode Forecast "+periode+".xls"); // Choose the file name
        return false;
    }
</script>