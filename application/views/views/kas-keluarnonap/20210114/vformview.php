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
                        <input class="form-control" name="ikasbankkeluar" id="ikasbankkeluar" readonly="" value="<?= $data->i_kasbank_keluar_nonap?>">
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
                        <input class="form-control date" name="dkasbankkeluar" id="dkasbankkeluar" readonly="" value="<?= $data->d_kasbank_keluar?>">
                    </div>
                </div>
                <div class="form-group row">
                    <!-- <label class="col-md-4">Sisa Hutang </label>
                    <label class="col-md-4">Jumlah Bayar</label> -->
                    <label class="col-md-4">Keterangan</label>
                    <!-- <div class="col-sm-4">
                        <input type="text" name="vsisa" id="vsisa" class="form-control" value="<?=$data->v_sisa?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vbayar" id="vbayar" class="form-control" value="<?=$data->v_bayar?>" readonly>
                    </div> -->
                    <div class="col-sm-4">
                        <input type="text" name="eremark" id="eremark" class="form-control" value="<?=$data->e_remark?>" readonly>
                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="">
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
                    <label class="col-md-3">Jenis Keluar</label>
                    <label class="col-md-3">No Ref</label>
                    <label class="col-md-3">Kas/Bank</label>
                    <label class="col-md-3">Bank</label>
                    <div class="col-sm-3">
                    <select name="jeniskeluar" id="jeniskeluar" class="form-control select2" onchange="getjenis(this.value)" disabled="">
                        <option value="">-- Jenis Keluar --</option>
                        <?php if ($data->i_jenis_keluar == 'kasbon') { ?>
                            <option value="kasbon" selected>Kas Bon</option>
                            <option value="kaskeluar">Permintaan Kas Keluar</option>
                        <?php }else { ?>
                            <option value="kasbon">Kas Bon</option>
                            <option value="kaskeluar" selected>Permintaan Kas Keluar</option>
                        <?php }?>
                    </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="irefferensi" id="irefferensi" class="form-control select2"onchange="return getrefferensi(this.value);" disabled="">
                            <option value="" selected>Pilih Nomor Refferensi</option>
                            <?php foreach ($refferensi as $irefferensi):?>
                            <?php if ($irefferensi->i_refferensi == $data->i_refferensi) { ?>
                                <option value="<?php echo $irefferensi->i_refferensi;?>" selected><?= $irefferensi->i_refferensi;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $irefferensi->i_refferensi;?>"><?= $irefferensi->i_refferensi;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="ikasbank" id="ikasbank" class="form-control select2" disabled="">
                            <option value="" selected>Pilih Tujuan</option>
                            <?php foreach ($kasbank as $ikasbank):?>
                            <?php if ($ikasbank->i_kode_kas == $data->i_kasbank) { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>" selected><?= $ikasbank->e_nama_kas;?></option>
                            <?php }else { ?>
                                <option value="<?php echo $ikasbank->i_kode_kas;?>"><?= $ikasbank->e_nama_kas;?></option>
                                    <?php }?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?if($data->i_bank == null){?>
                    <div class="col-sm-3">
                        <select name="ibank" id="ibank" class="form-control select2"  disabled="true">
                        </select>
                    </div>
                    <?}else{?>
                    <div class="col-md-3">
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
            </div>
                <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Kas</th>
                                    <th>Tanggal Kas</th>
                                    <th>Nilai Kas</th>
                                    <th>Keterangan Kas</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                foreach ($datadetail as $row) {
                                $i++;
                                // $checked = !empty($row->kasmasuk)?"checked":"";?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td>
                                    <input style ="width:150px" type="text" class="form-control" id="irefferensi<?=$i;?>" name="irefferensi<?=$i?>" value="<?= $row->i_refferensi; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:400px" class="form-control" type="text" id="drefferensi<?=$i;?>" name="drefferensi<?=$i;?>" value="<?= $row->d_refferensi; ?>" readonly >
                                </td>
                                <td>
                                    <input style ="width:100px" class="form-control" type="text" id="vnilai<?=$i;?>" name="vnilai<?=$i;?>" value="<?= number_format($row->v_nilai,0); ?>" readonly>
                                </td>
                                <td>
                                    <input style ="width:300px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc<?=$i;?>" value="<?= $row->e_desc; ?>">
                                </td>
                                <td class="col-sm-1">
                                    <!-- <input type="checkbox" name="cek<?php echo $i; ?>" value="cek" id="cek<?php echo $i; ?>" <?php echo $checked ?> readonly> -->
                                </td>
                                </tr>
                                <?}?>
                                <!-- <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>"> -->
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

    // function getmaterial(id){
    //     var ematerialname = $('#ematerialname'+id).val();
    //     $.ajax({
    //     type: "post",
    //     data: {
    //         'ematerialname': ematerialname
    //     },
    //     url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
    //     dataType: "json",
    //     success: function (data) {
    //         $('#imaterial'+id).val(data[0].i_material);
    //         $('#esatuan'+id).val(data[0].e_satuan);
    //         $('#isatuan'+id).val(data[0].i_satuan_code);
    //         $('#esatuankonv'+id).val(data[0].i_convertion);
    //     },
    //     error: function () {
    //         alert('Error :)');
    //     }
    // });
    // }

    // function getstore() {
    //     var gudang = $('#ikodemaster').val();
    //     //alert(gudang);
    //     $('#istore').val(gudang);

    //     if (gudang == "") {
    //         $("#addrow").attr("hidden", true);
    //     } else {
    //         $("#addrow").attr("hidden", false);
    //     }
    // }

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