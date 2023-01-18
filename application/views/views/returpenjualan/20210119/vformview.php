<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-8">Sub Bagian</label>
                        <label class="col-md-4">Tanggal Retur</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="ittb" id="ittb" class="form-control" value="<?php echo $data->i_ttb?>">
                            <select name="ikodebagian" id="ikodebagian" class="form-control select2">
                                <?php foreach ($kodebagian as $ikodebagian):?>
                                    <?php if ($ikodebagian->i_sub_bagian == $data->i_kode_bagian) { ?>
                                    <option value="<?php echo $ikodebagian->i_sub_bagian;?>" selected><?= $ikodebagian->e_sub_bagian;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodebagian->i_sub_bagian;?>"><?= $ikodebagian->e_sub_bagian;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dttb" name="dttb" class="form-control date" value="<?php echo $data->d_ttb; ?>" required="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-8">No Nota Retur Pelanggan</label>
                        <div class="col-sm-4">
                            <input type="text" id= "noreturpelanggan "name="noreturpelanggan" class="form-control" value="<?php echo $data->i_nota_retur_customer ?>">
                        </div>
                        <label class="col-md-8">Alasan Retur</label>
                        <div class="col-sm-4">
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
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">  
                            
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
                <div class="col-md-6">
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
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledataa" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <!-- <th>No</th>
                                    <th>Kode Barang WIP</th>
                                    <th>Nama Barang WIP</th>
                                    <th>Warna</th>
                                    <th>Kode Barang BB</th>
                                    <th>Nama Barang BB</th>
                                    <th>Quantity Masuk</th>
                                    <th>Keterangan</th> -->
                                    <th>No</th>
                                    <th width="20%">Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Quantity Nota</th>
                                    <th>Quantity Sisa</th>
                                    <th>Quantity Retur</th>
                                    <th>Keterangan</th>
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
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px" class="form-control" type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" class="form-control" type="text" id="nquantityfaktur<?=$i;?>" name="nquantityfaktur<?=$i;?>" value="<?= $row->n_quantity_faktur; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" class="form-control" type="text" id="nquantitysisa<?=$i;?>" name="nquantitysisa<?=$i;?>" value="<?= $row->n_quantity_sisa; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:80px" class="form-control" type="text" id="nquantityretur<?=$i;?>" name="nquantityretur<?=$i;?>"value="<?= $row->n_quantity_retur; ?>" >
                                </td>
                                <td class="col-sm-1">
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

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
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
cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

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