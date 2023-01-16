<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Nomor Forecast</label><label class="col-md-4">Date From</label><label class="col-md-4">Date To</label>
                        <div class="col-sm-4">
                            <select name="ifc" id="ifc" class="form-control select2" onchange="getdetailfcedit();">             
                                <?php if($yearmonthfrom == $yearmonthto){?>
                                    <option value="ALL">Forecast Produksi <?=$yearmonthfrom;?></option>
                                        <?php foreach ($fc as $ifc) { ?>
                                            <option value="<?php echo $ifc->i_fc;?>"><?php echo $ifc->i_fc;?></option> 
                                        <?php }?>
                                <?}else{?>
                                    <?php foreach ($fc as $ifc) { ?>
                                        <option value="<?php echo $ifc->i_fc;?>"><?php echo $ifc->i_fc;?></option> 
                                    <?php }?>
                                <?}?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dfrom" id="dfrom" class="form-control" value="<?=date('d-m-Y', strtotime($dfrom));?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dto" id="dto" class="form-control" value="<?=date('d-m-Y',strtotime($dto));?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="col-sm-offset-5 col-sm-10">  
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>   
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
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
                                    <th style="text-align:center;width:16%">Jumlah Forecast</th>
                                    <th style="text-align:center;width:4%">Action</th>
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
                                        <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris[]" value="<?= $i;?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]" value="<?= $row->i_product; ?>" readonly >
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="eproductname<?=$i;?>" name="eproductname[]" value="<?= $row->e_product_basename;?>" readonly >
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" id="icolor<?=$i;?>" name="icolor[]" value="<?= $row->i_color; ?>"readonly >
                                    </td>
                                        
                                    <td>
                                        <input type="text" class="form-control" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->jumlah; ?>" readonly >
                                    </td>
                                    <!-- <td style="text-align: center;">
                                        <button type="button" onclick="hapusdetail('<?= $isi->i_fc."','".$row->i_product; ?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </td> -->
                                </tr>
                                <?php } ?> 
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $jmlitem; ?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#addrow").attr("disabled", true);
     $("#download").attr("disabled", true);
     $("#upload").attr("disabled", true);
});

var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");

        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct'+counter+'"></td>';
        cols += '<td><select id="eproductname'+ counter + '" class="form-control" name="eproductname'+counter+'" onchange="getdetail('+ counter + ');"></select></td>';
        cols += '<td><input type="text" readonly id="icolor'+ counter + '" class="form-control" name="icolor'+counter+'"/></td>';    
        cols += '<td><input type="text"  id="nquantity'+ counter + '" class="form-control" name="nquantity'+counter+'" value="" reformat(this);"/></td>';         
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#eproductname'+ counter).select2({
        
        placeholder: 'Pilih Produk',
        templateSelection: formatSelection,
        allowClear: true,
        type: "POST",
        ajax: {          
            url: '<?= base_url($folder.'/cform/dataproduct/'); ?>',
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

function hapusdetail(ifc,iproduct) {
    swal({   
        title: "Apakah anda yakin ?",   
        text: "Anda tidak akan dapat memulihkan data ini!",   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: "Ya, hapus!",   
        cancelButtonText: "Tidak, batalkan!",   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, function(isConfirm){   
        if (isConfirm) { 
            $.ajax({
                type: "post",
                data: {
                    'ifc'        : ifc,
                    'iproduct'   : iproduct
                },
                url: '<?= base_url($folder.'/cform/deletedetailinput'); ?>',
                dataType: "json",
                success: function (data) {
                    swal("Dihapus!", "Data berhasil dihapus :)", "success");
                    show('<?= $folder;?>/cform/tambah/<?=$dfrom;?>/<?=$dto;?>','#main');     
                },
                error: function () {
                    swal("Maaf", "Data gagal dihapus :(", "error");
                }
            });
        } else {     
            swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
        } 
    });
}

function getdetail(id){
        var iproduct   = $('#eproductname'+id).val();
        $.ajax({
        type: "post",
        data: {
            'iproduct'    : iproduct
        },
        url: '<?= base_url($folder.'/cform/getproduct'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_product_motif);
            ada=false;
            var a = $('#iproduct'+id).val();
            var e = $('#eproductname'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
                if((a == $('#iproduct'+i).val()) && (i!=id)){
                    swal ("kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }
            if(!ada){
                $('#iproduct'+id).val(data[0].i_product_motif);
                $('#eproductname'+id).val(data[0].e_product_basename);
                $('#icolor'+id).val(data[0].i_color);
            }else{
                $('#iproduct'+id).html('');
                $('#iproduct'+id).val('');
                $('#icolor'+id).val('');
                $('#icolor'+id).val('');
                $('#eproductname'+id).html('');
                $('#eproductname'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});
    
function getdetailfcedit() {
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

                    cols += '<td>'+counter+'<input type="hidden" readonly  id="baris'+ counter + '" type="text" class="form-control" name="baris[]" value="'+counter+'"></td>';
                    cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]" value="'+i_product+'"></td>';
                    cols += '<td><input type="text" readonly id="eproductname'+ counter + '" class="form-control" name="eproductname[]" value="'+e_product_name+'"></td>';
                    cols += '<td><input type="text"  id="icolor'+ counter + '" class="form-control" name="icolor[]" value="'+i_color+'" readonly></td>';
                    cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" onfocus="if(this.value==\'0\'){this.value=\'\';}" reformat(this); " value="'+n_quantity+'"/></td>';    
                    cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
      
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
        jml = counter;
}

function removeBody(){
    var tbl = document.getElementById("tabledata");   // Get the table
    tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
}
</script>