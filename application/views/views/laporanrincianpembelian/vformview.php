<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
            <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                        <div class="col-sm-5">
                            <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>" onchange="gantidata();">
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>" onchange="gantidata();">
                        </div>
                        
                    </div>
                </div>
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Material</th>
                            <th>Nama Material</th>
                            <th>Nomor OP</th>
                            <th>Tanggal OP</th>
                            <th>Supplier</th>
                            <th>No.BTB</th>
                            <!-- <th>Tanggal BTB</th> -->
                            <th>Qty OP</th>
                            <th>Qty BTB</th>
                            <th>Qty O/S</th>
                            <th>Qty Hangus</th>
                            
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
        <input type="hidden" id="supplier" name="supplier" value="<?php echo $supplier ?>" >
<!-- ------------------------------------------------------------------------------------------------------------------------ -->
<table hidden id="tableexport" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align:center;font-size:11px;" colspan=11>REPORT OUTSTANDING SPBB</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;font-size:11px;" colspan=11>PERIODE : <?=$dfrom;?> s/d <?=$dto;?></th>
                        </tr>
                        <tr>
                            <th style="text-align:right;" colspan=11></th>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>Kode Material</th>
                            <th>Nama Material</th>
                            <th>Nomor OP</th>
                            <th>Tanggal OP</th>
                            <th>Supplier</th>
                            <th>No.BTB</th>
                            <th>Qty OP</th>
                            <th>Qty BTB</th>
                            <th>Qty O/S</th>
                            <th>Qty Hangus</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                            foreach($isi as $row){
                                echo "<tr>
                                        <td>$row->i</td>
                                        <td>$row->i_material</td>
                                        <td>$row->e_material_name</td>
                                        <td>$row->i_op</td>
                                        <td>$row->d_op</td>
                                        <td>$row->supplier</td>
                                        <td>$row->i_btb</td>
                                        <td>$row->qtyop</td>
                                        <td>$row->qtybtb</td>
                                        <td>$row->qtyos</td>
                                        <td>$row->qtyhangus</td>
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
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $supplier."/".$dfrom."/".$dto; ?>",
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
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $supplier."/".$dfrom."/".$dto; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
     } 

    function exportF(elem) {
        var dfrom       = $('#dfrom').val();
        var dto         = $('#dto').val();
        var supplier         = $('#supplier').val();
        // var ikategori   = $('#ekategori').val();
        // var ijenis      = $('#ejenis').val();
        var table       = document.getElementById("tableexport");
        var html        = table.outerHTML;
        var url         = 'data:application/vnd.ms-Excel,' + escape(html); // Set your html table into url 
        elem.setAttribute("href", url);
        elem.setAttribute("download", "Report Rincian Pembelian "+dfrom+"-sd-"+dto+" Supplier "+supplier+".xls"); // Choose the file name
        return false;
    }
</script>