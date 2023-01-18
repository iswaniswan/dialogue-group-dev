<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                <div id="pesan"></div> 
                    <div class="form-group row">
                        <label class="col-sm-6">Gudang</label>
                        <label class="col-sm-6">Material</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="gudang" name="gudang" class="form-control" value="<?php echo $data->i_kode_master?>">
                            <input type="text" id="gudangname" name="gudangname" class="form-control" value="<?php echo $data->e_nama_master?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" id="material" name="material" class="form-control" value="<?php echo $data->i_material?>">   
                            <input type="text" id="materialname" name="materialname" class="form-control" value="<?php echo $data->e_material_name?>" readonly>
                        </div>
                    </div>        
                    <!-- <div class="form-group row">
                        <div class="col-sm-8">
                            <label >Nomor Memo</label>
                            <input type="text" id="imemo" name="imemo" class="form-control" maxlength="" value="">
                        </div>
                        <div class="col-sm-4">
                            <label >Tanggal Memo</label>
                            <input type="text" id="dmemo" name="dmemo" class="form-control date" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="">
                        </div> -->
                    <!-- </div> -->
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <!-- <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>   -->
                            <!-- <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button> -->
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Dari Tanggal</label>
                        <label class="col-md-6">Sampai Tanggal</label>
                        <div class="col-sm-6">
                            <input type="text" id="dfrom" name="dfrom" class="form-control" readonly maxlength="" value="<?php echo $dfrom?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id="dto" name="dto" class="form-control" readonly maxlength="" value="<?php echo $dto?>" readonly>
                        </div>
                    </div>
                    
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Nama PIC</th>
                                    <th width="10%">Bon keluar</th>
                                    <th width="15%">Tanggal Bon Keluar</th>
                                    <th width="10%">Bon Masuk</th>
                                    <th width="15%">Tanggal Bon Masuk</th>
                                    <th width="10%">Qty Bon Keluar</th>
                                    <th width="15%">Qty Bon Masuk</th>
                                    <!-- <th width="10%">Satuan</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    foreach ($data2 as $row) {
                                    $i++;
                                ?>
                                <tr>
                                 <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150" class="form-control" type="text" id="pic<?=$i;?>" name="pic" value="<?= $row->e_nama_karyawan; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:200px" class="form-control" type="text" id="bonk<?=$i;?>" name="bonk"value="<?= $row->i_bonmk; ?>" readonly >
                                </td>                                 
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="dbonmk<?=$i;?>" name="dbonmk" value="<?= $row->d_bonmk; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:200px" class="form-control" type="text" id="ibonmm<?=$i;?>" name="ibonmm" value="<?= $row->i_bonmm; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="dbonmm<?=$i;?>" name="dbonmm" value="<?= $row->d_bonmm; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_qty; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:150px" class="form-control" type="text" id="qtydeliver<?=$i;?>" name="qtydeliver"value="<?= $row->n_deliver; ?>" readonly>
                                </td>
                                
                                <!-- <td align="center">
                                    <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td> -->
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
function getpic(tujuankeluar){
    //alert(tujuankeluar);
    $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getpic');?>",
            data:"tujuankeluar="+tujuankeluar,
            dataType: 'json',
            success: function(data){
                $("#edept").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#edept").attr("disabled", true);
                }else{
                    $("#edept").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
        if(tujuankeluar == 'external'){
            $("#diveks").attr("hidden", false);
        }else{
            $("#diveks").attr("hidden", true);
        }
}
$(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date');
});

$(document).ready(function () {
    // var counter = 0;
var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        // var ikodemaster     = $("#ikodemaster").val();
        $('#jml').val(counter);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        var cols = "";
        if(cols =! ""){

            cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
            cols += '<td><input style="width:200px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]"></td>';
            cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
            cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" placeholder="0" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
            cols += '<td><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan[]" onkeyup="cekval(this.value);"/></td>';
            cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
            cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        }

        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#ikodemaster').attr("disabled", true);
        var gudang = $('#istore').val();

        $('#ematerialname'+ counter).select2({
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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
        // $('#jml').val(counter);
        // del();
         // counter -= 1
         // document.getElementById("jml").value = counter;
    });

    // function del() {
    //     obj=$('#tabledata tr').find('spanx');
    //     $.each( obj, function( key, value ) {
    //         id=value.id;
    //         $('#'+id).html(key+1);
    //     });
    // }
});

function getmaterial(id){
        var ematerialname = $('#ematerialname'+id).val();
        $.ajax({
            type: "post",
            data: {
                'ematerialname': ematerialname
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                $('#imaterial'+id).val(data[0].i_material);
                $('#esatuan'+id).val(data[0].e_satuan);
                $('#isatuan'+id).val(data[0].i_satuan_code);
           ada=false;
            var a = $('#imaterial'+id).val();
            var e = $('#ematerialname'+id).val();
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
                var ematerialname = $('#ematerialname'+id).val();
                $.ajax({
                    type: "post",
                    data: {
                         'ematerialname': ematerialname,
                    },
                    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#ematerialname'+id).val(data[0].e_material_name);
                        $('#esatuan'+id).val(data[0].e_satuan);
                        $('#isatuan'+id).val(data[0].i_satuan_code);
                    },
                });
            }else{
                $('#imaterial'+id).html('');
                $('#imaterial'+id).val('');
                $('#ematerialname'+id).html('');
                $('#ematerialname'+id).val('');
                $('#esatuan'+id).val('');
                $('#isatuan'+id).val('');
                // $('#esatuan'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#addrow").attr("disabled", true);
     $(".ibtnDel").attr("disabled", true);
 });

 function getstore() {
        var gudang = $('#ikodemaster').val();
        //alert(gudang);
        $('#istore').val(gudang);

        if (gudang == "") {
            $("#addrow").attr("hidden", true);
        } else {
            $("#addrow").attr("hidden", false);
        }
        
    }
</script>