<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Kas/Bank</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly="" class="form-control" value="<?=$data->id;?>"> 
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PKK-2012-000001" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_document;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control date" value="<?=$data->d_document;?>" required="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikasbank" id="ikasbank" class="form-control select2"> 
                                <option value="<?=$data->id_kas_bank.'|'.$data->i_bank;?>"><?=$data->e_kas_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                     
                        <label class="col-md-3">Bank</label>
                        <label class="col-md-3">Jenis Partner</label>
                        <label class="col-md-4">Partner</label>
                        <label class="col-md-2">Total Nilai</label>    
                        <div class="col-sm-3">
                            <?php if($data->id_bank == 0 || $data->id_bank == null){?>
                                <select name="ibank" id="ibank" class="form-control select2" disabled> 
                                </select>
                            <?}else{?>
                                <select name="ibank" id="ibank" class="form-control select2"> 
                                    <option value="<?=$data->id_bank;?>"><?=$data->e_bank_name;?></option>
                                </select>
                            <?}?>
                        </div>   
                        <div class="col-sm-3">
                            <select name="epartnertype" id="epartnertype" class="form-control select2"> 
                                <?php if ($data->e_partner_type == 'customer') { ?>
                                    <option value="<?php echo $data->e_partner_type;?>" selected>Customer</option>
                                    <option value="pic">PIC</option>
                                <?php } else { ?>
                                    <option value="<?php echo $data->e_partner_type;?>" selected>PIC</option>
                                    <option value="customer">Customer</option>
                                <?php } ?>
                            </select>
                            <input type="hidden" id= "epartnertypeold" name="epartnertypeold" class="form-control" value="<?=$data->e_partner_type;?>" readonly>
                        </div>  
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" multiple class="form-control select2" onchange="return getitempartner(this.value);">
                            <?php if ($partner) {
                                foreach ($partner as $kuy) {?>
                                    <option value="<?= $kuy->id_partner;?>" selected><?= $kuy->e_partner_name;?></option>
                                <?php }
                            }?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="vnilai" id="vnilai" readonly value="<?=$data->n_nilai;?>">
                        </div>                     
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                        <?php } ?>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        <?php if ($data->i_status == '1') {?>
                        <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                        <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                        <?php }elseif($data->i_status=='2') {?>
                            <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                        <?php } ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="white-box" id="detail" >
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Partner</th>
                        <th style="text-align:center;">Nama Partner</th>
                        <th style="text-align:center;">Nilai</th>
                        <th style="text-align:center;">Keterangan</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td>
                                    <input type="hidden" readonly id="idpartner<?=$i;?>" class="form-control input-sm" name="idpartner<?=$i;?>" value="<?= $row->id_partner;?>">
                                    <input type="text" readonly id="ipartner<?=$i;?>" class="form-control" name="ipartner<?=$i;?>"style="width:150px;" value="<?= $row->i_partner;?>">
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="epartnername<?=$i;?>" name="epartnername<?=$i;?>" readonly style="width:350px;" value="<?= $row->e_partner_name;?>">
                                </td>
                                <td>
                                    <input type="text" id="v_nilai<?=$i;?>" class="form-control" name="v_nilai<?=$i;?>" style="width:200px;" value="<?= $row->n_nilai;?>" onkeyup="reformat(this);" readonly>
                                </td>
                                <td>
                                    <input type="text" id="edesc<?=$i;?>" class="form-control" name="edesc<?=$i;?>" style="width:400px;" value="<?= $row->e_remark;?>" >
                                </td>
                                <td>
                                    <input type="checkbox" name="cek<?=$i;?>" value="checked" id="cek<?=$i;?>" checked onchange="cek_value(<?=$i;?>);">
                                </td>
                            </tr>
                        <?php } 
                    }?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i;?>" readonly>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        /**
        * Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
        */
        showCalendar('.date',1830,0);
        
        $('#idocument').mask('SSS-0000-000000S');

        $('#ikasbank').select2({
            placeholder: 'Pilih Kas/Bank',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kasbank'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
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
        }).change(function(event) {
            //customer();
            $("#ibank").attr("disabled", false);
            $("#ibank").val("");
            $("#ibank").html("");
        });

        $('#epartnertype').change(function(event){
            //var eptype = $('#epartnertype').val();
            $("#ipartner").val("");
            $("#ipartner").html("");
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            //$("#epartnertypeold").val(eptype);
        });

        $('#ibank').select2({
            placeholder: 'Pilih Bank',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bank'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikasbank : $('#ikasbank').val(),
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

        $('#ipartner').select2({
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getpartneredit'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        epartnertype : $('#epartnertype').val(),
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
        }).change(function(event) {
            $("#vnilai").val("0");
        });
    });

    $( "#idocument" ).keyup(function() {
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
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    //menyesuaikan periode di running number sesuai dengan tanggal dokumen
    $( "#ddocument" ).change(function() {
        number();
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
        }
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    //untuk me-generate running number
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#ddocument').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#idocument').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function getitempartner(ipartner) { 
        
        var ipartner     = $('#ipartner').val();
        //alert(ipartner);
        var epartnertype    = $('#epartnertype').val();
        var epartnertypeold = $('#epartnertypeold').val();
        var id              = $('#id').val();
        if(ipartner != '' && ipartner != null){
            $.ajax({
                type: "post",
                data: {
                    'ipartner'    : ipartner,
                    'epartnertype': epartnertype,
                    'id'          : id,
                },
                url: '<?= base_url($folder.'/cform/getitempartner_edit'); ?>',
                dataType: "json",
                success: function (data) {  
                    $('#jml').val(data['data'].length);
                    $("#tabledatax tbody").remove();

                    for (let a = 0; a < data['dataitem'].length; a++) {
                        no++;

                        var no = a+1;
                        var id         = data['dataitem'][a]['id'];
                        var ipartner   = data['dataitem'][a]['i_partner'];
                        var epartner   = data['dataitem'][a]['e_partner_name'];
                        var nilai      = data['dataitem'][a]['nilai'];
                        
                        var cols       = "";
                        var newRow = $("<tr>");
                        
                        cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+no+'" value="'+no+'"></td>';
                        cols += '<td><input readonly class="form-control" type="hidden" id="idpartner'+no+'" name="idpartner'+no+'" value="'+id+'"><input readonly style="width:150px;" class="form-control" type="text" id="ipartner'+no+'" name="ipartner'+no+'" value="'+ipartner+'"></td>';
                        cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="epartner'+no+'" name="epartner'+no+'" value="'+epartner+'"></td>'; 
                        cols += '<td><input style="width:200px;" class="form-control" type="text" id="v_nilai'+no+'" name="v_nilai'+no+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+nilai+'" onkeyup="reformat(this);"></td>'; 
                        cols += '<td><input style="width:400px;" class="form-control" type="text" id="edesc'+no+'" name="edesc'+no+'" value=""></td>';
                        cols +='<td><input type="checkbox" name="cek'+no+'" value="checked" id="cek'+no+'" onchange="cek_value('+no+')"></td>';
                       
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                    }
                    // $('#tabledatax').DataTable({
                    //     "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    //     "displayLength": 10,
                    //     "paging" : false,
                    //     destroy: true,
                    // });                          
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
             $("#tabledatax tbody").remove();
        }
        if (ipartner != null) {
            if (ipartner.indexOf('ALL') !== -1) {
                $("#ipartner option:selected").select2().removeAttr("selected");
                $("#ipartner option[value='ALL']").select2().attr("selected","selected");
                $('#ipartner').val('ALL');
           }
       }
        
    } 

    function cek_value(i){
        var vtotal = formatulang(document.getElementById('vnilai').value);
        if (document.getElementById('cek' + i).checked == true) {
            $('#v_nilai'+i).attr("readonly", true);
            var v_nilai = formatulang($('#v_nilai' + i).val());
//alert(v_nilai);
            totakhir = parseFloat(vtotal) + parseFloat(v_nilai);
        } else {
            $('#v_nilai'+i).attr("readonly", false);
            var v_nilai = formatulang($('#v_nilai' + i).val());
            totakhir = parseFloat(vtotal) - parseFloat(v_nilai);
        }
        document.getElementById('vnilai').value = formatcemua(totakhir);
    }

    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#ikasbank').val()!='' || $('#ikasbank').val()) && ($('#ipartner').val()!='' || $('#ipartner').val()) ) {
            if ($('#jml').val()==0) {
                swal('Data Item Masih Kosong!');
                return false;
            }else{
                if ($("#tabledatax input:checkbox:checked").length > 0){
                    return true;
                }else{
                    swal('Pilih data minimal satu!');
                    return false;
                }
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }     
    });      
</script>