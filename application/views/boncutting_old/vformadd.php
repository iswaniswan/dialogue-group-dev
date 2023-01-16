<style>
    .font-11{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 11px;  
    height: 20px;  
}
.font-12{
    padding-left: 3px;
    padding-right: 3px;
    font-size: 12px;    
}
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>  <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-sm-2">Tanggal Dokumen</label>
                        <label class="col-sm-4">Keterangan</label>
                       
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="istb_cutting" id="istb_cutting" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dstb_cutting" name="dstb_cutting" class="form-control input-sm date" onchange="tanggal(this.value); number();" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                         <div class="col-sm-4">
                            <textarea class="form-control input-sm" name="remark" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2"><i class="fa fa-save mr-2" ></i>Simpan</button>
                            <button type="button" id="addrow" class="btn btn-rounded btn-info btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Item</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <!-- <div class="m-b-0">
            <div class="form-group row">
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>Item</button>
                </div>
            </div>
        </div> -->
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table font-11 success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 30%;">Material (Kode + Nama + Tglschedule)</th>
                        <th class="text-center" style="width: 10%;">Kode WIP</th>
                        <th class="text-center" style="width: 20%;">Nama WIP</th>
                        <th class="text-center" style="width: 10%;">Tgl Schedule</th>
                        <th class="text-center" style="width: 10%;">Jml Lembar</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" style="width: 3%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
<input type="hidden" name="budgeting" id="budgeting" value ="f">
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('#istb_cutting').mask('SSS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        number();
    });

    $("#istb_cutting").keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dstb_cutting').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#istb_cutting').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#istb_cutting").attr("readonly", false);
        }else{
            $("#istb_cutting").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        // $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    var i = 0;
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+i+'">'+no+'</spanx></td>';
        cols += '<td><select data-urut="'+i+'" id="idscheduleitem'+i+ '" class="form-control input-sm" name="idscheduleitem[]"></td>';
        cols += '<td><input type="text" readonly id="i_wip'+i+'" class="form-control input-sm" name="i_wip[]"></td>';
        cols += '<td><input type="text" readonly id="e_wip_name'+i+'" class="form-control input-sm" name="e_wip_name[]"></td>';
        cols += '<td><input type="text" readonly id="d_schedule'+i+'" class="form-control input-sm" name="d_schedule[]"></td>';
        cols += '<td><input type="text" id="nquantity_kirim'+i+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity_kirim[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);cek('+i+')"></td>';
        cols += '<td><input type="text" id="eremark'+i+'" placeholder="Keterangan Detail" class="form-control input-sm" name="eremark[]"/></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idscheduleitem'+ i).select2({
            placeholder: 'Cari Kode / Nama Material / Tgl Schedule',
            allowClear: true,
            width: '100%',
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/material/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ibagian    : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(event) {
            let id = $(this).data('urut');
            $.ajax({
                type: "post",
                data: {
                    'idscheduleitem': $(this).val(),
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                    ada = false;
                    for(var i = 1; i <=$('#jml').val(); i++){
                        if(($('#idscheduleitem'+id).val() == $('#idscheduleitem'+i).val()) && (i!=id)){
                            swal ("kode sudah ada !!!!!");
                            ada = true;
                            break;
                        }else{
                            ada = false;     
                        }
                    }

                    if(!ada){
                        $('#d_schedule'+id).val(data[0]['d_schedule']);
                        $('#i_wip'+id).val(data[0]['i_product_wip']);
                        $('#e_wip_name'+id).val(data[0]['e_product_wipname']);
                        $('#nquantity_kirim'+id).val(data[0]['n_quantity']);
                        $('#nquantity_kirim'+id).focus();
                    }else{
                        $('#idscheduleitem'+id).html('');
                        $('#idscheduleitem'+id).val('');
                        $('#d_schedule'+id).val('');
                        $('#i_wip'+id).val('');
                        $('#e_wip_name'+id).val('');
                        $('#nquantity_kirim'+id).val('');
                    }
                },
                error: function () {
                    swal('Ada kesalahan :(');
                }
            });
        });
    });  

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();

        $('#jml').val(i);
        del();
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    }


    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Quantity Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
    }) 

    function cek(i) {
        if (parseFloat($('#nquantity_kirim' + i).val()) > parseFloat($('#nquantity' + i).val())) {
            swal('Maaf Qty Kirim = ' + $('#nquantity_kirim' + i).val() + ', tidak boleh lebih dari Qty Permintaan = ' + $('#nquantity' + i).val());
            $('#nquantity_kirim' + i).val($('#nquantity' + i).val());
        }
    }
    function tanggal(d) {
        $('#dbp').val(maxDate(d));
    }
</script>