<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>         
                <div class="col-md-6">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label >Gudang</label>
                            <input type="text" readonly id="gudang" name="gudang" class="form-control" maxlength="" value="<?php echo $head->e_nama_master;?>">
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_kode_master;?>">
                        </div>
                        <div class="col-sm-4">
                            <label >Nomor Adjusment</label>
                            <input type="text" readonly id="i_adjus" name="i_adjus" class="form-control" maxlength="" value="<?php echo $head->i_adjustment;?>">
                        </div>
                        <div class="col-sm-4">
                             <label >Tanggal</label>
                             <input type="text" id="dadjus" name="dadjus" class="form-control date" value="<?php echo $head->tanggal;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>      
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="40%">Nama barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($detail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td class="col-sm-1">
                                    <spanx id="snum<?=$i;?>"><?=$i;?></spanx>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>" value="<?= $row->i_material; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:450px" class="form-control" type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                </td>
                                <?php if ($head->i_status == '1' || $head->i_status == '3') { ?>
                                        <td class="col-sm-1">
                                           <input type="text" class="form-control" class="form-control" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_qty; ?>" readonly>
                                        </td>
                                <?php } else { ?>
                                        <td class="col-sm-1">
                                           <input style ="width:50px" class="form-control" type="text"  id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_qty; ?>" readonly >
                                        </td>
                                <?php } ?>       
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan_code; ?>" readonly >
                                    <input style ="width:100px" class="form-control" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                      <input style ="width:200px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>
                                </td>
                                </tr>
                                <?php } ?>
                                <!-- <label class="col-md-12">Jumlah Data</label> -->
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
    showCalendar('.date');
});

function setjenisbarang() {
    var ejenisbarang = $('#ejenisbarang').val();
    $('#ijenisbarang').val(ejenisbarang);     
}

function cek() {
    var dadjus = $('#dadjus').val();

    if (dadjus == '') {
        alert('Data Header Belum Lengkap !!');
        return false;
    } else {
        return true;
    }
}

function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

  }

function getenabledsend() {
    $('#send').attr("disabled", true);
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $(".ibtnDel").attr("disabled", true);
}

$(document).ready(function () {
    // var counter = 0;
   $('#send').attr("disabled", false);
   $("#send").on("click", function () {
        var kode = $("#i_adjus").val();
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

  var counter = document.getElementById("jml").value;
  var ijenisbarang = $('#ijenisbarang').val();
  var istore = $('#istore').val();
  $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        cols += '<td class="col-sm-1"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input type="text" readonly id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial' + counter + '" value=""></td>';
        cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname'+ counter + '" value="" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="number" id="nquantity'+ counter + '" class="form-control" placeholder="0" name="nquantity'+ counter + '" value="0"/></td>';
        cols += '<td><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '" value=""/><input type="hidden" id="namabarang'+ counter + '" class="form-control" name="namabarang'+ counter + '" /></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#ematerialname'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/datamaterial/'); ?>'+istore+'/'+ijenisbarang,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });
  
    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        del();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

     $("form").submit(function(event) {
         event.preventDefault();
         $("input").attr("disabled", true);
         $("select").attr("disabled", true);
         $("#submit").attr("disabled", true);
         $("#addrow").attr("disabled", true);
         $(".ibtnDel").attr("disabled", true);
         $('#send').attr("disabled", true);
     });
});

    $(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
    });

    function getmaterial(id){
        var imaterial = $('#ematerialname'+id).val();
        $.ajax({
                type: "post",
                data: {
                    'i_material': imaterial
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    ada=false;
                    var a = $('#ematerialname'+id).val();
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
                        $('#imaterial'+id).val(data[0].i_material);
                        $('#namabarang'+id).val(data[0].e_material_name);
                        $('#esatuan'+id).val(data[0].e_satuan);
                        $('#isatuan'+id).val(data[0].i_satuan_code);
                    }else{
                        $('#imaterial'+id).html('');
                        $('#ematerialname'+id).html('');
                        $('#ematerialname'+id).val('');
                        $('#namabarang'+id).val('');
                        $('#isatuan'+id).val('');
                        $('#esatuan'+id).val('');
                        // $('#esatuan'+id).val('');
                    }
                },
                error: function () {
                    alert('Error :)');
                }
            });  
    }
</script>