<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                            <div class="col-sm-5">
                                <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
                <table class="display nowrap" cellspacing="0" width="100%" id="tabledata">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No SPBB</th>
                            <th>Tanggal SPBB</th>
                            <th>Kode Product</th>
                            <th>Nama Product</th>
                            <th>No Bon Keluar</th>
                            <th>Warna</th>
                            <th>Quantity SPBB</th>
                            <th>Quantity Pengeluaran ke Produksi</th>
                            <th>Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <br>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-8">
                        <a id="downloadLink" onclick="exportF(this);"><button type="button" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button> </a>
                    </div>
                </div>
                <div>
                    <input type="hidden" id ="dfrom" name="dfrom" value="<?php echo $dfrom ?>" >
                    <input type="hidden" id="dto" name="dto" value="<?php echo $dto ?>" >
                </div>

            </div>
        </div>
<!-- ---------------------------------------------------------------------------------------------------------------------- -->
<table hidden id="tableexport" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th style="text-align:center;font-size:8px;" colspan=14>REPORT OUTSTANDING SPBB</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;font-size:8px;" colspan=14>PERIODE : <?=$dfrom;?> s/d <?=$dto;?></th>
                        </tr>
                        <tr>
                            <th style="text-align:right;" colspan=8></th>
                        </tr>
                        <tr>
                            <th>No</th>
                            <th>Kode Product</th>
                            <th>Nama Product</th>
                            <th>No SPBB</th>
                            <th>No Bon Keluar</th>
                            <th>Warna</th>
                            <th>Quantity SPBB</th>
                            <th>Quantity Bon K</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                            foreach($isi as $row){
                                echo "<tr>
                                        <td>$row->i</td>
                                        <td>$row->i_product</td>
                                        <td>$row->e_product_name</td>
                                        <td>$row->i_spbb</td>
                                        <td>$row->i_bonk</td>
                                        <td>$row->e_color_name</td>
                                        <td>$row->qtyspbb</td>
                                        <td>$row->qtybonk</td>
                                    </tr>";
                            }
                        ?>
                    </tbody>
                </table>
<!-- ---------------------------------------------------------------------------------------------------------------------- -->
    </div>
</div>

<script>
    $(document).ready(function () {
        showCalendar('.date',1830,0);
        var groupColumn = 2;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
    });

    $( "#dfrom" ).change(function() {
        var dfrom   = splitdate($(this).val());
        var dto     = splitdate($('#dto').val());
        if (dfrom!=null&& dto!=null) {
            if (dfrom>dto) {
                swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $( "#dto" ).change(function() {
        var dto   = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom!=null && dto!=null) {   
            if (dfrom>dto) {
                swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                $('#dto').val('');
            }
        }
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });

    $("#href").click(function() {
        var dfrom = $('#dfrom').val();
        var dto   = $('#dto').val();
        var iarea = $('#iarea').val();
        if (dfrom=='' || dto==''||iarea=='') {
            swal('Isi form yang masih kosong!');
            return false;
        }
        //var abc = "<?= site_url($folder.'/cform/export/'); ?>"+dfrom+'/'+dto+'/'+iarea;
        var abc = "<?= site_url($folder.'/cform/export/'); ?>"+dfrom+'/'+dto;
        $("#href").attr("href",abc);
    });

    function exportF(elem) {
        var dfrom       = $('#dfrom').val();
        var dto         = $('#dto').val();
        // var ikategori   = $('#ekategori').val();
        // var ijenis      = $('#ejenis').val();
        var table       = document.getElementById("tableexport");
        var html        = table.outerHTML;
        var url         = 'data:application/vnd.ms-Excel,' + escape(html); // Set your html table into url 
        elem.setAttribute("href", url);
        elem.setAttribute("download", "Report Outstanding SPBB Periode "+dfrom+"-sd-"+dto+".xls"); // Choose the file name
        return false;
    }
</script>