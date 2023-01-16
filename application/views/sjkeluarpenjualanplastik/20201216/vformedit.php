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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Gudang</label>
                        <label class="col-md-4">No SJ</label>
                        <label class="col-md-4">Tanggal SJ</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();">
                                <option value="" selected>-- Pilih Gudang --</option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                    <?php if ($ikodemaster->i_kode_master == $head->i_kode_master) { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>" selected><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>"><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_kode_master?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="isj" name="isj" class="form-control" value="<?php echo $head->i_sj?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo $head->d_sj?>" readonly>
                        </div>
                    </div>
                     <div class="form-group row">
                        <div class="col-sm-8">
                        <label >Nomor Memo</label>
                            <input type="text" id="imemo" name="imemo" class="form-control" maxlength="" value="<?php echo $head->i_memo?>">
                        </div>
                        <div class="col-sm-4">
                        <label >Tanggal Memo</label>
                            <input type="text" id="dmemo" name="dmemo" class="form-control date" value="<?php echo $head->d_memo?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                       <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>                 
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Customer</label>
                        <div class="col-sm-6">
                            <select name="icustomer" id="icustomer" class="form-control select2" >
                                <option value="" selected>-- Pilih Customer --</option>
                                 <?php foreach ($customer as $icustomer):?>
                                    <?php if ($icustomer->i_customer == $head->i_customer) { ?>
                                    <option value="<?php echo $icustomer->i_customer;?>" selected><?= $icustomer->e_customer_name;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->e_customer_name;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="35%">Nama Barang</th>  
                                    <th>Qty</th>
                                    <th>Keterangan</th>
                                    <th >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                foreach ($detail as $row) {
                                $i++;?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td>
                                    <input style ="width:150px" type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial[]"value="<?= $row->i_product; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:400px" class="form-control" type="text" id="ematerialname<?=$i;?>" name="ematerialname[]" value="<?= $row->e_material_name; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:150px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= number_format($row->n_quantity,0); ?>">
                                </td>
                                <td>
                                    <input style ="width:200px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]" value="<?= $row->e_remark; ?>" >
                                     <input class="form-control" type="hidden" id="isatuan<?=$i;?>" name="isatuan[]" value="<?= $row->i_satuan; ?>" >
                                </td>
                                <td align="center">
                                    <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                                </tr>
                                <?}?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </tbody>
                        </table>
                    </div>
                </form>
            <div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});
function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

  }
$(document).ready(function () {
    // var counter = 0;
  var counter = document.getElementById("jml").value;
  //var counter = $('#jml').val();
  $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");        
        var cols = "";

        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input style="width:150px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="form-control" name="imaterial[]"></td>';
        cols += '<td><select type="text" id="ematerialname'+ counter + '" class="form-control select2" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" placeholder="0" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        //cols += '<td><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan[]" value="" onkeyup="cekval(this.value); reformat(this);"/>></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/><input type="hidden" id="fkonv'+ counter + '" class="form-control" name="fkonv[]" value = "0";><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan[]" onkeyup="cekval(this.value);"/</td>';
        //cols += '<td><input type="checkbox" checked id="bisbisan'+ counter + '" name="bisbisan[]" onclick="return false;"></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ikodemaster').attr("disabled", true);
        var gudang = $('#istore').val();

        $('#ematerialname'+counter).select2({
        placeholder: 'Pilih Material',
        templateSelection: formatSelection,
        allowClear: true,
        ajax: {
            url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
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
        // counter -= 1
        // document.getElementById("jml").value = counter;
        //del();
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
 });

});

    $(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
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
                //$('#esatuan'+id).val(data[0].e_satuan);
                $('#isatuan'+id).val(data[0].i_satuan_code);
                $('#esatuankonv'+id).val(data[0].i_convertion);
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
                var imaterial    = $('#imaterial'+id).val();
                $.ajax({
                    type: "post",
                    data: {
                        'imaterial'  : imaterial,
                    },
                    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#ematerialname'+id).val(data[0].e_material_name);
                        //$('#esatuan'+id).val(data[0].e_satuan);
                        $('#isatuan'+id).val(data[0].i_satuan_code);
                    },
                });
            }else{
                $('#imaterial'+id).html('');
                $('#imaterial'+id).val('');
                $('#ematerialname'+id).html('');
                $('#ematerialname'+id).val('');
                $('#isatuan'+id).val('');
                // $('#esatuan'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

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