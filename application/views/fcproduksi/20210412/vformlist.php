<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Forecast</label><label class="col-md-3">Date From</label><label class="col-md-3">Date To</label>
                        <div class="col-sm-6">
                            <select name="ifc" id="ifc" class="form-control select2" onchange="getdetailfc();">             
                                <?php if($yearmonthfrom == $yearmonthto){?>
                                    <option value="ALL">Forecast Produksi <?=$yearmonthfrom;?></option>
                                        <?php foreach ($fc as $ifc) { ?>
                                            <option value="<?php echo $ifc->i_fc;?>"><?php echo $ifc->i_fc;?></option> 
                                        <?php }?>
                                <?}else{?>
                                    <option value="ALL">All Forecast</option>
                                    <?php foreach ($fc as $ifc) { ?>
                                        <option value="<?php echo $ifc->i_fc;?>"><?php echo $ifc->i_fc;?></option> 
                                    <?php }?>
                                <?}?>
                            </select>
                            <!-- <input type="text" name="ifc" id="ifc" class="form-control" value="<?=$isi->i_fc;?>" readonly> -->
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="dfrom" id="dfrom" class="form-control" value="<?=date('d-m-Y', strtotime($dfrom));?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="dto" id="dto" class="form-control" value="<?=date('d-m-Y',strtotime($dto));?>" readonly>
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center;width:3%">No</th>
                                    <th style="text-align:center;width:25%">Kode Barang</th>
                                    <th style="text-align:center;width:37%">Nama Barang</th>
                                    <th style="text-align:center;width:15%">Warna</th>
                                    <th style="text-align:center;width:20%">Jumlah Forecast</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                $gudang = '';
                                    foreach ($detail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?= $i;?>
                                        <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>" readonly >
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_basename;?>" readonly >
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="icolor<?=$i;?>" name="icolor<?=$i;?>" value="<?= $row->i_color; ?>"readonly >
                                    </td>
                                        
                                    <td>
                                        <input type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->jumlah; ?>"readonly>
                                    </td>
                                </tr>
                                <?php } ?> 
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
   $(document).ready(function () {
        $('.select2').select2();
        showCalendar2('.date');
    });

    function getdetailfc() {
        removeBody();
        var ifc = $('#ifc').val();
        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        $.ajax({
            type: "post",
            data: {
                'ifc': ifc,
                'dfrom': dfrom,
                'dto'  : dto
            },
            url: '<?= base_url($folder.'/cform/getdetailfc'); ?>',
            dataType: "json",
            success: function (data) {
                $('#jml').val(data['detail'].length);
                var lastproduct ='';
                var lastcolor ='';
                for (let a = 0; a < data['detail'].length; a++) {
                    var counter = a+1;
                    count=$('#tabledata tr').length;                   
                    var i_product           = data['detail'][a]['i_product'];
                    var e_product_name      = data['detail'][a]['e_product_basename'];
                    var i_color             = data['detail'][a]['i_color'];
                    var n_quantity          = data['detail'][a]['n_quantity'];
                    var cols        = "";
                    var newRow = $("<tr>");

                    cols += '<td>'+counter+'<input type="hidden" readonly  id="baris'+ counter + '" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
                    cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct'+counter+'" value="'+i_product+'"></td>';
                    cols += '<td><input type="text" readonly id="eproductname'+ counter + '" class="form-control" name="eproductname'+counter+'" value="'+e_product_name+'"></td>';
                    cols += '<td><input type="text"  id="icolor'+ counter + '" class="form-control" name="icolor'+counter+'" value="'+i_color+'" readonly></td>';
                    cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+counter+'" readonly onfocus="if(this.value==\'0\'){this.value=\'\';}" reformat(this); " value="'+n_quantity+'"/></td>';    

                    newRow.append(cols);
                    $("#tabledata").append(newRow);
      
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
        xx = $('#jml').val();
    }

    function removeBody(){
    var tbl = document.getElementById("tabledata");   // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
    }
</script>
