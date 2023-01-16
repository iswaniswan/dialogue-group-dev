<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-7">
                    <div class="form-group row">
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-4">No Retur</label>
                        <label class="col-md-4">Tanggal Retur</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <option value="" selected>Pilih Gudang</option>
                                <?php foreach ($bagian as $ibagian):?>
                                    <?php if ($ibagian->i_sub_bagian == $data->i_bagian) { ?>
                                    <option value="<?php echo $ibagian->i_sub_bagian;?>" selected><?= $ibagian->e_sub_bagian;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ibagian->i_sub_bagian;?>"><?= $ibagian->e_sub_bagian;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "iretur" name="iretur" class="form-control" value="<?php echo $data->i_retur;?>" readonly> 
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dretur" name="dretur" class="form-control date" value="<?php echo $data->d_retur;?>" readonly> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" value="<?php echo $data->e_remark;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden="true"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>                            
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group row">
                        <label class="col-md-1"></label>
                        <label class="col-md-5">Tujuan</label>
                        <label class="col-md-6">Referensi</label>
                        <div class="col-sm-1">
                        </div>
                        <div class="col-sm-5">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <option value="" selected>Pilih Tujuan</option>
                                <?php foreach ($tujuan as $itujuan):?>
                                    <?php if ($itujuan->i_supplier == $data->i_tujuan) { ?>
                                    <option value="<?php echo $itujuan->i_supplier;?>" selected><?= $itujuan->e_supplier_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $itujuan->i_supplier;?>"><?= $itujuan->e_supplier_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>    
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="ireff" name="ireff" class="form-control" value="<?php echo $data->i_referensi;?>" readonly>
                        </div>
                    </div>   
                </div>      
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang WIP</th>
                                    <th>Nama barang WIP</th>
                                    <th>Warna</th>
                                    <th>Kode Barang Jadi</th>
                                    <th>Quantity Retur</th>
                                    <th>Keterangan</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?$i = 0;
                        foreach ($datadetail as $row) {
                        $i++;?>
                        <tr>
                            <td style="text-align: center;"><?= $i;?>
                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:100px" type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>"  readonly >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:400px"type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_namabrg; ?>" class="form-control" readonly >
                            </td> 
                            <td class="col-sm-1">
                                <input style ="width:80px" class="form-control" type="hidden" id="icolorproduct<?=$i;?>" name="icolorproduct<?=$i;?>"value="<?= $row->i_color; ?>" >
                                <input style ="width:140px" class="form-control" type="text" id="ecolorproduct<?=$i;?>" name="ecolorproduct<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly>
                            </td>                  
                            <td class="col-sm-1">
                                <input style ="width:150px" type="text" class="form-control" id="brgjadi<?=$i;?>" name="brgjadi<?=$i;?>"value="<?= $row->i_product_jadi; ?>"  readonly >
                            </td>
                             <td class="col-sm-1">
                                <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity_retur; ?>" >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:200px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>" >
                            </td>
                           <!--  <td>
                                <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                            </td> -->
                        </tr>
                        <?}?>
                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>"> 
                            </tbody>
                        </table>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
});


$(document).ready(function () {
    $('#ibagian').select2({
    placeholder: 'Pilih Gudang',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/gudang'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  })
});

$(document).ready(function () {
    $('#itujuan').select2({
    placeholder: 'Pilih Tujuan',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/tujuan'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  })
});

$(document).ready(function () {
    $('#ireffo').select2({
    placeholder: 'Pilih Referensi',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/getreferensi'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  })
});

function changereferensi(){
    var adareferensi   = $('#noreff').val();

    if(adareferensi == '1'){
        $("#ireffo").attr("disabled", false);
        $("#ireffm").attr("disabled", true);
        $("#addrow").attr("hidden", true);
    }else{
        $("#ireffm").attr("disabled", false);
        $("#ireffo").attr("disabled", true);
    }
}

function change(){
     $("#addrow").attr("hidden", false);
}

var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        
        var cols = "";
         cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct' + counter + '"></td>';
        cols += '<td><select style="width:300px;" type="text" id="eproduct'+ counter + '" class="form-control" name="eproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="text" id="icolorproduct'+ counter + '" class="form-control" name="icolorproduct'+ counter + '"><input style="width:100px;" type="text" readonly id="ecolorproduct'+ counter + '" class="form-control" name="ecolorproduct'+ counter + '"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly id="imaterial'+ counter + '" class="form-control" name="imaterial'+ counter + '"></td>';
        cols += '<td><select style="width:300px;" type="text" id="ematerial'+ counter + '" class="form-control" name="ematerial'+ counter + '"onchange="getmaterial('+ counter + ');"</td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" style="width:100px;"class="form-control" name="nquantity'+ counter + '" value="0"></td>';
        cols += '<td><input style="width:200px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '" required></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
       
        newRow.append(cols);
        $("#tabledata").append(newRow);
       
        $('#eproduct'+ counter).select2({
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
    });

function formatSelection(val) {
    return val.name;
}

$("#tabledata").on("click", ".ibtnDel", function (event) {
    $(this).closest("tr").remove();       
    // counter -= 1
    // document.getElementById("jml").value = counter;
});

function getproduct(id){
var eproduct = $('#eproduct'+id).val();
    $('#ematerial'+ counter).select2({
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
            var imaterial    = $('#imaterial'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'imaterial'  : imaterial,
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#ematerial'+id).val(data[0].e_material_name);
                },
            });
        }else{
            $('#imaterial'+id).html('');
            $('#imaterial'+id).val('');
            $('#ematerial'+id).html('');
            $('#ematerial'+id).val('');
        }
    },
    error: function () {
        alert('Error :)');
    }
});
}

function getdatareferensi(){
    var referensi   = $('#ireffo').val();
    $.ajax({
        type: "post",
        data: {
            'referensi': referensi,
        },
        url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
        dataType: "json",
        success: function (data) {  
            $('#jml').val(data['dataitem'].length);
            $("#tabledata tbody").remove();
            for (let no = 0; no < data['dataitem'].length; no++) {
                var a = no+1;
                var ibonk        = data['dataitem'][no]['i_bonk'];
                var iproduct     = data['dataitem'][no]['i_product'];
                var eproduct     = data['dataitem'][no]['e_namabrg'];
                var imaterial    = data['dataitem'][no]['i_material'];
                var ematerial    = data['dataitem'][no]['e_material_name'];
                var icolor       = data['dataitem'][no]['i_color'];
                var ecolor       = data['dataitem'][no]['e_color_name'];
                var cols         = "";

                 var x = $('#jml').val();
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+a+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+a+'"></td>';     
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+iproduct+'"></td>';
                cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="eproduct'+a+'" name="eproduct'+a+'" value="'+eproduct+'"></td>'; 
                cols += '<td><input style="width:40px;"  type="hidden" id="icolorproduct'+a+'" name="icolorproduct'+a+'" value="'+icolor+'"><input style="width:90px;" class="form-control" type="text" id="ecolor'+a+'" readonly name="ecolor'+a+'" value="'+ecolor+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+ematerial+'"></td>';
                cols += '<td><input type="text" id="nquantity'+a+ '" style="width:100px;"class="form-control" name="nquantity'+a+ '" value="0"></td>';
                cols += '<td><input style="width:200px;" type="text" id="edesc'+a+ '" class="form-control" name="edesc' + a + '" required></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }
        }
    });
     xx = $('#jml').val();
}  

function validasi(){
    var gudang   = $('#ibagian').val();
    var itujuan  = $('#itujuan').val();

    if (gudang == '' || gudang == null || itujuan == '' || itujuan == null) {
        swal('Data header Belum Lengkap');
        return false;
    }else {
        return true;
    }
}
</script>