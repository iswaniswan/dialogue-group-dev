<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-sm-4">Partner</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();" disabled="true">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="iretur" required="" readonly="" class="form-control input-sm" value="<?=$data->i_document;?>">
                            </div>
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?=$data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="itujuan" id="itujuan" class="form-control select2" required="" disabled="true">
                                <option value="<?=$data->i_tujuan;?>"><?=$data->e_supplier_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                        
                        <label class="col-md-12">Keterangan</label>                        
                        <div class="col-sm-6">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250" readonly><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">No Dokumen Masuk</th>
                        <th class="text-center" style="width: 35%;">Nama Barang</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Qty Terima</th>
                        <th class="text-center" style="width: 10%;">Qty Retur</th>
                        <th class="text-center" style="width: 15%;">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $key) {
                        $i++;
                        ?>
                    <tr>
                         <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $i ;?></spanx></td>
                         <td style="text-align: center">
                           <?=  $key->i_document_reff ;?>                        
                        </td>
                        <td>
                          <?= $key->i_product_base. ' - '.$key->e_product_basename ;?>
                        </td>
                        <td>
                           <?= $key->e_color_name ;?>
                        </td>
                        <td>
                           <?= $key->n_masuk ;?>
                        </td>
                        <td>
                           <?= $key->n_retur ;?>
                        </td>
                        <td>
                            <?= $key->e_remark ;?>                            
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>
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