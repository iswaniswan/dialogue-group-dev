<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/index'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label> 
                        <label class="col-md-3">Date To</label> 
                        <label class="col-md-4">Supplier</label>
                        <div class="col-sm-3">
                            <input type="text" id="dfrom" name="dfrom" class="form-control date" readonly value="<?=$dfrom;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dto" name="dto" class="form-control date" readonly value="<?=$dto;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="isupplier" id="isupplier" class="form-control select2">
                            <option value="ALL">All Supplier</option>
                                <?php 
                                    if($isupplier == 'ALL'){?>
                                        <option value="<?=$isupplier;?>" selected="true">All Supplier</option>
                                        <?php foreach($supplier as $key){?>
                                            <option value="<?=$key->i_supplier;?>"><?=$key->e_supplier_name;?></option>
                                        <?}?>
                                    <?}else{?>
                                        <option value="<?=$isupplier;?>" selected="true"><?=$esupplier;?></option>
                                        <?php foreach($supplier as $key){?>
                                            <option value="<?=$key->i_supplier;?>"><?=$key->e_supplier_name;?></option>
                                        <?}?>
                                    <?}
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-md"> <i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>&nbsp;&nbsp;&nbsp;&nbsp;
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-1">
                            <a id="downloadLink" onclick="exportF(this);"><button type="button" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button> </a>
                        </div>

                        <div class="col-sm-1">
                            <a id="href" onclick="return validasi();"><button type="button" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;Export Detail</button></a>
                        </div>
                    </div>
                </div>
                </form>
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
                            <th>Qty OP</th>
                            <th>Qty BTB</th>
                            <th>Qty O/S</th>
                            <th>Qty Hangus</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
            <input type="hidden" id="supplier" name="supplier" value="<?= $isupplier ?>" >
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
                            <th style="text-align:center;font-size:11px;" colspan=11>SUPPLIER : <?=$isupplier;?> - <?=$esupplier;?></th>
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
</div>

<script type="text/javascript">
    $(document).ready(function () {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $isupplier."/".$dfrom."/".$dto;?>');
        $('.select2').select2();
        showCalendar('.date');
    });

    $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
    });

    $( "#cmdreset" ).click(function() {  
        var Contents = $('#tabledata').html();    
        window.open('data:application/vnd.ms-excel, ' +  '<table>'+encodeURIComponent($('#tabledata').html()) +  '</table>' );
    });

    function exportF(elem) {
        var dfrom       = $('#dfrom').val();
        var dto         = $('#dto').val();
        var supplier    = $('#supplier').val();
        var table       = document.getElementById("tableexport");
        var html        = table.outerHTML;
        var url         = 'data:application/vnd.ms-Excel,' + escape(html); // Set your html table into url 
        elem.setAttribute("href", url);
        elem.setAttribute("download", "Report Rincian Pembelian "+dfrom+"-sd-"+dto+" Supplier "+supplier+".xls"); // Choose the file name
        return false;
    }

    function validasi() {
        var supplier = $('#isupplier').val();
        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+supplier+'/'+dfrom+'/'+dto);
    }
</script>