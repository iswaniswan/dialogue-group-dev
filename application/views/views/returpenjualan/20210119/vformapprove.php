<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Bagian Pembuat</label>
                        <label class="col-md-4">No TTB</label>
                        <label class="col-md-4">Tanggal Retur</label>
                        <div class="col-sm-4">
                            <input type="text" name="idept" id="idept" class="form-control" value="<?php echo $data->i_ttb?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="ittb" id="ittb" class="form-control" value="<?php echo $data->i_ttb?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dttb" name="dttb" class="form-control date" value="<?php echo $data->d_ttb; ?>" required="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">No Pajak</label>
                        <label class="col-md-6">Alasan Retur</label>
                        <div class="col-sm-6">
                            <input type="text" id= "ipajak "name="ipajak" class="form-control" value="<?php echo $data->i_pajak ?>">
                        </div>
                        <div class="col-sm-6">
                            <select name="ialasanretur" id="ialasanretur" class="form-control select2">
                                <?php foreach ($getalasan as $ialasan):?>
                                    <?php if ($ialasan->i_alasan_retur == $data->i_alasan_retur) { ?>
                                    <option value="<?php echo $ialasan->i_alasan_retur;?>" selected><?= $ialasan->e_alasan_returname;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ialasan->i_alasan_retur;?>"><?= $ialasan->e_alasan_returname;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" value="<?php echo $data->e_ttb_remark?>">
                        </div>
                    </div>
                    <div class="form-group">
                    <div class="col-sm-offset-6 col-sm-12">
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return getselisih();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                    
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">No Nota Penjualan</label>
                        <div class="col-sm-6">          
                            <select name="epelanggan" id="epelanggan" class="form-control select2" onchange="getnotapenjualan(this.value);"> 
                            </select>
                            <input type="hidden" name="ipelanggan" id="ipelanggan" class="form-control" value="">
                        </div>
                        <div class="col-sm-6">                            
                            <select name="i_nota" id="i_nota" class="form-control select2" onchange="getdataitem(this.value);" disabled="true"> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Discount Pelanggan</label>
                        <label class="col-md-4">DPP</label>
                        <label class="col-md-4">PPN</label>
                        <div class="col-sm-4">          
                            <input type="text" name="discount" id="discount" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <input type="text" name="dpp" id="dpp" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <input type="text" name="ppn" id="ppn" class="form-control" value="0" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Nilai Kotor</label>
                        <label class="col-md-4">Total Discount</label>
                        <label class="col-md-4">Nilai Bersih</label>
                        <div class="col-sm-4">          
                            <input type="text" name="vspb" id="vspb" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <input type="text" name="vspbdiscounttotal" id="vspbdiscounttotal" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-4">                            
                            <input type="text" name="vspbbersih" id="vspbbersih" class="form-control" value="0" readonly>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-md-6">Asal Kirim</label>
                        <label class="col-md-6">No Referensi</label>
                        <div class="col-sm-6">          
                            <select name="epelanggan" id="epelanggan" class="form-control select2"  >
                            <option value="" selected></option>
                                <?php foreach ($getpelanggan as $epelanggan):?>
                                    <?php if ($epelanggan->i_sub_bagian == $data->i_asal) { ?>
                                    <option value="<?php echo $epelanggan->i_sub_bagian;?>" selected><?= $epelanggan->e_sub_bagian;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $epelanggan->i_sub_bagian;?>"><?= $epelanggan->e_sub_bagian;?></option>
                                    <?php }?>
                                <?php endforeach; ?> 
                            </select>
                            <input type="hidden" name="ipelanggan" id="ipelanggan" class="form-control" value="<?php echo $data->i_asal?>">
                        </div>
                        <div class="col-sm-6">                            
                            <select name="inota" id="inota" class="form-control select2" onchange="getdataitem(this.value);" > 
                                <option value="" selected></option>
                                <?php foreach ($referensi as $inota):?>
                                    <?php if ($inota->referensi == $data->i_referensi) { ?>
                                    <option value="<?php echo $inota->referensi;?>" selected><?= $inota->referensi;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $inota->referensi;?>"><?= $inota->referensi;?></option>
                                    <?php }?>
                                <?php endforeach; ?> 
                            </select>
                            </select>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">No Nota Penjualan</label>
                        <div class="col-sm-6">          
                            <select name="epelanggan" id="epelanggan" class="form-control select2" onchange="getnotapenjualan(this.value);"> 
                                <?php foreach ($getpelanggan as $epelanggan):?>
                                    <?php if ($epelanggan->i_customer == $data->i_customer) { ?>
                                    <option value="<?php echo $epelanggan->i_customer;?>" selected><?= $epelanggan->e_customer_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $epelanggan->i_customer;?>"><?= $epelanggan->e_customer_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?> 
                            </select>
                            <input type="hidden" name="ipelanggan" id="ipelanggan" class="form-control" value="<?php echo $data->i_customer ?>">
                        </div>
                        <div class="col-sm-6">                            
                            <select name="i_nota" id="i_nota" class="form-control select2" onchange="getdataitem(this.value);" > 
                                <?php foreach ($getnota as $inota):?>
                                    <?php if ($inota->i_faktur_code == $data->i_nota) { ?>
                                    <option value="<?php echo $inota->i_faktur_code;?>" selected><?= $inota->i_faktur_code;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $inota->i_faktur_code;?>"><?= $inota->i_faktur_code;?></option>
                                    <?php }?>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                    </div>
                </div> -->
                    <div class="panel-body table-responsive">
                    <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%" >
                            <thead>
                            <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode Barang</th>
                                    <th width="20%">Nama Barang</th>
                                    <th width="10%">Harga</th>
                                    <th width="8%">Quantity Nota</th>
                                    <th width="9%">Quantity Retur</th>
                                    <th width="15%">Total</th>
                                    <th width="20%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php $i = 0;
                                    foreach ($datadetail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td>
                                    <input style ="width:150px" class="form-control" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:300" class="form-control" type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:100px" class="form-control" type="text" id="vprice<?=$i;?>" name="vprice<?=$i;?>" value="<?= $row->v_unit_price; ?>" readonly>
                                </td>
                                <td>
                                    <input style ="width:80px" class="form-control" type="text" id="nquantityfaktur<?=$i;?>" name="nquantityfaktur<?=$i;?>" value="<?= $row->n_quantity_faktur; ?>" readonly>
                                    <input style ="width:80px" class="form-control" type="hidden" id="nquantitysisa<?=$i;?>" name="nquantitysisa<?=$i;?>" value="<?= $row->n_quantity_sisa; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:80px" class="form-control" type="text" id="nquantityretur<?=$i;?>" name="nquantityretur<?=$i;?>"value="<?= $row->n_quantity_retur; ?>" onkeyup="hitungnilai(this.value)";>
                                </td>
                                <td>
                                    <input style ="width:80px" class="form-control" type="text" id="total<?=$i;?>" name="total<?=$i;?>"value="<?= $row->n_quantity_retur; ?>" onkeyup="hitungnilai(this.value)";>
                                </td>
                                <td>
                                    <input style ="width:150px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_ttb_remark; ?>" >
                                </td>
                                <!-- <td align="center">
                                    <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td> -->
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

function getenabledchange() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal('Data Berhasil Di Change Request');
}

function getenabledreject() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal('Data Berhasil Di Reject');
}

function getenabledsend() {
    $('#send').attr("disabled", true);
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    swal('Berhasil Di Send');
}

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$("#send").on("click", function () {
        var kode = $("#ittb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });

    $("#reject").on("click", function () {
        var kode = $("#ittb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });

    $('#change').attr("disabled", false);
    $("#change").on("click", function () {
        var kode = $("#ittb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'kode'  : kode,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });

var ipelanggan = $('#ipelanggan').val();
//---------------------------------------------------------
var a = $('#jml').val();
    $("#addrow").on("click", function () {
        a++;
        var inota = $('#inota').val();
        $('#jml').val(a);
        count=$('#tabledataa tr').length;
        $("#tabledataa").attr("hidden", false);
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+a+'">'+count+'</spanx><input type="hidden" id="baris'+a+'" type="text" class="form-control" name="baris'+a+'" value="'+a+'"></td>';
cols += '<td><input style="width:100px;" type="text" readonly id="iproduct'+ a + '" type="text" class="form-control" name="iproduct' + a + '"></td>';
cols += '<td><select style="width:300px;" type="text" id="eproduct'+ a + '" class="form-control" name="eproduct'+ a + '" onchange="getproduct('+ a + ');"</td>';
cols += '<td><input type="hidden" id="icolorpro'+ a + '" class="form-control" name="icolorpro'+ a + '"><input style="width:100px;" type="text" readonly id="ecolorproduct'+ a + '" class="form-control" name="ecolorproduct'+ a + '"></td>';
cols += '<td><input style="width:100px;" type="text" readonly id="imaterial'+ a + '" class="form-control" name="imaterial'+ a + '"><input readonly style="width:100px;" class="form-control" type="hidden" id="icolorma'+a+'" name="icolorma'+ a + '" value=""></td>';
cols += '<td><select style="width:300px;" type="text" id="ematerial'+ a + '" class="form-control" name="ematerial'+ a + '"onchange="getmaterial('+ a + ');"</td>';
cols += '<td><input type="text" id="nquantitymasuk'+ a + '" style="width:100px;"class="form-control" name="nquantitymasuk'+ a + '" value="0"></td>';
cols += '<td><input style="width:200px;" type="text" id="edesc'+ a + '" class="form-control" name="edesc' + a + '"></td>';
//cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

        newRow.append(cols);
        $("#tabledataa").append(newRow);

    $('#eproduct'+ a).select2({
        placeholder: 'Pilih Kode Barang',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/dataproduct'); ?>',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
    });

        $('#ematerial'+ a).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
              url: '<?= base_url($folder.'/cform/datamaterial/'); ?>',
              
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                return {
                  results: data
                };
              },
              cache: true
            }
        });
    });

    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
    });


function getmaterial(id){
    var ematerial = $('#ematerial'+id).val();
    $.ajax({
    type: "post",
    data: {
        'ematerial': ematerial
    },
    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
    dataType: "json",
    success: function (data) {
        $('#imaterial'+id).val(data[0].i_material);
        $('#icolorma'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);
        //$('#iproduct'+id).val(data[0].i_material);
        $('#icolorpro'+id).val(data[0].i_color);

        ada=false;
        var a = $('#imaterial'+id).val();
        var e = $('#ematerial'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
            if((a == $('#imaterial'+i).val()) && (i!=jml)){
                swal ("kode : "+a+" sudah ada !!!!!");
                ada=true;
                break;
            }else{
                ada=false;     
            }
        }
        if(!ada){
            var ematerial    = $('#ematerial'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'ematerial'  : ematerial,
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#imaterial'+id).val(data[0].i_material);
                    $('#icolorma'+id).val(data[0].i_color);
                    $('#ecolor'+id).val(data[0].e_color_name);
                    //$('#iproduct'+id).val(data[0].i_material);
                    $('#icolorpro'+id).val(data[0].i_color);
                },
            });
        }else{
            $('#imaterial'+id).html('');
            //$('#iproduct'+id).val('');
           // $('#eproduct'+id).html('');
            $('#ematerial'+id).val('');
            $('#icolorma'+id).val('');
            $('#ecolor'+id).val('');
            //$('#iproduct'+id).val('');
            $('#icolorpro'+id).val('');
        }
    },
    error: function () {
        alert('Error :)');
    }
});
}

function getproduct(id){
var eproduct = $('#eproduct'+id).val();
    $('#ematerial'+ a).select2({
        placeholder: 'Pilih Kode Barang',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datamaterial/'); ?>'+eproduct,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
    });
        
    $.ajax({
        type: "post",
        data: {
            'eproduct': eproduct
        },
        url: '<?= base_url($folder.'/cform/getproduct'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_product);
            $('#icolorproduct'+id).val(data[0].i_color);
            $('#ecolorproduct'+id).val(data[0].e_color_name);
        },
        error: function () {
            alert('Error :)');
        }
    });
}

 function getmaterialaks(id){
    var ematerial = $('#ematerial'+id).val();
    //alert(eproduct);
    $.ajax({
    type: "post",
    data: {
        'ematerial': ematerial
    },
    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
    dataType: "json",
    success: function (data) {
        $('#imaterial'+id).val(data[0].i_material);
        $('#isatuan'+id).val(data[0].i_satuan_code);
        $('#esatuan'+id).val(data[0].e_satuan);
        $('#icolorma'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);
        $('#iproduct'+id).val(data[0].i_material);
        $('#icolorpro'+id).val(data[0].i_color);

        ada=false;
        var a = $('#imaterial'+id).val();
        var e = $('#ematerial'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
            if((a == $('#imaterial'+i).val()) && (i!=jml)){
                swal ("kode : "+a+" sudah ada !!!!!");
                ada=true;
                break;
            }else{
                ada=false;     
            }
        }
        if(!ada){
            var ematerial    = $('#ematerial'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'ematerial'  : ematerial,
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#imaterial'+id).val(data[0].i_material);
                    $('#icolorma'+id).val(data[0].i_color);
                    $('#ecolor'+id).val(data[0].e_color_name);
                    $('#iproduct'+id).val(data[0].i_material);
                    $('#icolorpro'+id).val(data[0].i_color);
                },
            });
        }else{
            $('#imaterial'+id).html('');
            //$('#iproduct'+id).val('');
           // $('#eproduct'+id).html('');
            $('#ematerial'+id).val('');
            $('#icolorma'+id).val('');
            $('#ecolor'+id).val('');
            $('#iproduct'+id).val('');
            $('#icolorpro'+id).val('');
        }
    },
    error: function () {
        alert('Error :)');
    }
});
}
</script>