<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
               <div class="col-md-6">
                 <div class="form-group row">
                    <label class="col-md-5">Nomor Dokumen</label>
                    <label class="col-md-3">Bagian</label>
                    <label class="col-md-4">Tanggal</label>
                    <div class="col-sm-5">
                        <input class="form-control" name="ikasmasuk" id="ikasmasuk" readonly="" value="<?= $data->i_kas_masuk?>">
                    </div>
                    <div class="col-sm-3">
                            <select class="form-control select2" name="ibagian" id="ibagian" disabled="">
                                <?php foreach ($area as $ibagian):?>
                                <option value="<?php echo $ibagian->i_departement;?>">
                                    <?= $ibagian->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" name="dmasuk" id="dmasuk" readonly="" value="<?= $data->d_kas_masuk?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-5">Customer</label>
                    <label class="col-md-7">Nilai</label>
                    <div class="col-sm-5">
                        <select name="icustomer" id="icustomer" class="form-control select2" onchange="return getcustomer(this.value);" disabled="">
                             <option value="" selected>Pilih Customer</option>
                            <?php foreach ($customer as $icustomer):?>
                            <?php if ($icustomer->i_customer == $data->i_customer) { ?>
                                <option value="<?php echo $icustomer->i_customer;?>" selected><?= $icustomer->e_customer_name;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->e_customer_name;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-7">
                        <input type="text" name="vnilai" id="vnilai" class="form-control" value="<?=$data->n_nilai?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12">
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>  
                </div> 
                <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-5">Kas/Bank</label>
                    <label class="col-md-6">Bank</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-5">
                        <select name="ikasbank" id="ikasbank" class="form-control select2" onchange="return customer(this.value);" disabled="">
                            <option value="" selected>Pilih Tujuan</option>
                            <?php foreach ($kasbank as $ikasbank):?>
                            <?php if ($ikasbank->i_kode_kas == $data->i_kas_bank) { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>" selected><?= $ikasbank->e_nama_kas;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>"><?= $ikasbank->e_nama_kas;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?if($data->i_bank == null){?>
                    <div class="col-sm-5">
                        <select name="ibank" id="ibank" class="form-control select2"  disabled="true">
                        </select>
                    </div>
                    <?}else{?>
                    <div class="col-md-6">
                        <select name="ibank" id="ibank" class="form-control select2" disabled="">
                            <option value="" selected>Pilih Bank</option>
                            <?php foreach ($bank as $ibank):?>
                            <?php if ($ibank->i_bank == $data->i_bank) { ?>
                                <option value="<?php echo $ibank->i_bank;?>" selected><?= $ibank->e_bank_name;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ibank->i_bank;?>"><?= $ibank->e_bank_name;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?}?>
                </div>
                <div class="from-group row">
                    <label class="col-md-1"></label>
                    <label class="col-md-11">Keterangan</label>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-11">
                        <input type="text" name="eremark" id="eremark" class="form-control" value="<?=$data->e_remark?>">
                    </div>
                </div>
            </div>
                <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Customer</th>
                                    <th>Nama Customer</th>
                                    <th>Nilai</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                foreach ($datadetail as $row) {
                                $i++;
                                $checked = !empty($row->kasmasuk)?"checked":"";?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td>
                                    <input style ="width:150px" type="text" class="form-control" id="icustomer<?=$i;?>" name="icustomer<?=$i?>" value="<?= $row->i_customer; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:400px" class="form-control" type="text" id="ecustomer<?=$i;?>" name="ecustomer<?=$i;?>" value="<?= $row->e_customer_name; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:100px" class="form-control" type="text" id="vnilai<?=$i;?>" name="vnilai<?=$i;?>" value="<?= number_format($row->n_nilai,0); ?>" readonly>
                                </td>
                                <td>
                                    <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>" value="<?= $row->e_remark; ?>" readonly>
                                </td>
                                <td class="col-sm-1">
                                    <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>" <?php echo $checked ?> readonly>
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

function getenabledcancel() {
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledsend() {
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
    $('#addrow').attr("disabled", true);
}

function cekqty(counter){
    var vjumlah = $('#nquantitykonv'+counter).val();
    // $('#vjumlah'+id).val(vjumlah);
    $('#nquantity'+counter).val(vjumlah);

}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var isj = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'isj'  : isj,
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
});

$(document).ready(function(){
    $("#sendd").on("click", function () {
        var isj = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'isj'  : isj,
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
});

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
            $('#esatuankonv'+id).val(data[0].i_convertion);
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

    function cek() {
        var dsjk = $('#dsjk').val();
        var icustomer = $('#icustomer').val();
        var imemo = $('#imemo').val();
        var istore = $('#istore').val();

        if (dsjk == '' || icustomer == '' || istore == '') {
            swla('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }
</script>