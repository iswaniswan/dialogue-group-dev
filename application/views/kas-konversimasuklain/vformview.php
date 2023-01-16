<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
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
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled>
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_bagian_name;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" readonly="" class="form-control" value="<?=$data->id;?>"> 
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" class="form-control" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control" value="<?=$data->d_document;?>" required="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ikasbank" id="ikasbank" class="form-control select2" disabled> 
                                <option value="<?=$data->id_kas_bank;?>"><?=$data->e_kas_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">                     
                        <label class="col-md-3">Partner</label>                        
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <label class="col-md-2">Sisa Nilai Referensi</label> 
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2" disabled> 
                            <?php if ($partner) {
                                foreach ($partner as $kuy) {?>
                                    <option value="<?= $kuy->id_partner.'|'.$kuy->e_partner_type;?>" selected><?= $kuy->e_partner_name;?></option>
                                <?php }
                            }?>
                            </select>
                        </div>   
                       <div class="col-sm-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2" disabled> 
                                <option value="<?= $data->id_document_reff.'|'.$data->dreferensi.'|'.$data->nilai_ref;?>" selected><?= $data->referensi;?></option>
                            </select>
                        </div>   
                        <div class="col-sm-2">
                            <input class="form-control" name="dreferensi" id="dreferensi" value="<?=$data->dreferensi;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control" name="vnilai" id="vnilai" value="<?=$data->n_sisa;?>" readonly>
                            <input type="hidden" class="form-control" name="vnilai_a" id="vnilai_a" value="<?=$data->n_nilai;?>"readonly>
                            <input type="hidden" class="form-control" name="vnilaiold" id="vnilaiold" value="<?=$data->n_nilai_old;?>" readonly>
                        </div>                     
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Customer</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-6">
                            <select name="icustomer" id="icustomer" class="form-control select2" multiple="multiple" onchange="return getitemcustomer(this.value);" disabled>
                            <?php if ($customer) {
                                foreach ($customer as $ada) {?>
                                    <option value="<?= $ada->id_customer;?>" selected><?= $ada->e_customer_name;?></option>
                                <?php }
                            }?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!" readonly><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">                      
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;                       
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
            <table id="tabledatax" class="table color-table inverse-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th style="text-align:center;">Kode Customer</th>
                        <th style="text-align:center;">Nama Customer</th>
                        <th style="text-align:center;">Nilai</th>
                        <th style="text-align:center;">Keterangan</th>
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
                                    <?= $row->i_customer;?>
                                </td>
                                <td>
                                    <?= $row->e_customer_name;?>
                                </td>
                                <td>
                                    <?= $row->n_nilai;?>
                                </td>
                                <td>
                                    <?= $row->e_remark;?>
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
        max();

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
        });

        $('#ipartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/partner'); ?>',
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
            $("#ireferensi").attr("disabled", false);
            $('#ireferensi').val("");
            $('#ireferensi').html("");
        });

        $('#ireferensi').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ipartner : $('#ipartner').val(),
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
            var ireferensi  = $('#ireferensi').val();
            var referen     = ireferensi.split("|");
            var referensi   = referen[0];
            var dreferen    = referen[1];
            var nilairef    = referen[2];
            $('#dreferensi').val(dreferen);
            $('#vnilai').val(nilairef);
            $('#vnilaiold').val(nilairef);
            $("#icustomer").attr("disabled", false);
            for(var i=1; i<= $('#jml').val();i++){ 
                $('#v_nilai'+i).val("0");
                $('#v_nilai'+i).attr("readonly", false); 
                $("#cek"+i).attr("checked", false);
            }
            $("#vnilai_a").val("0");
            max();
        });

        $('#icustomer').select2({
            placeholder: 'Pilih Customer',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer'); ?>',
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
            var ireferensi  = $('#ireferensi').val();
            var referen     = ireferensi.split("|");
            var referensi   = referen[0];
            var dreferen    = referen[1];
            var nilairef    = referen[2];
            $('#dreferensi').val(dreferen);
            $('#vnilai').val(nilairef);
            $('#vnilaiold').val(nilairef);
            $('#vnilai_a').val("0");
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

    function max(){
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dreferensi').value,
        });
    }

    $('#ddocument').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dreferensi').value,
    });

    function getitemcustomer(icustomer) {   
        var icustomer     = $('#icustomer').val();
        if(icustomer != '' && icustomer != null){
            $.ajax({
                type: "post",
                data: {
                    'icustomer'    : icustomer,
                },
                url: '<?= base_url($folder.'/cform/getitemcustomer'); ?>',
                dataType: "json",
                success: function (data) {  
                    $('#jml').val(data['dataitem'].length);
                    $("#tabledatax tbody").remove();

                    for (let a = 0; a < data['dataitem'].length; a++) {
                        var no = a+1;
                        var id          = data['dataitem'][a]['id'];
                        var icustomer   = data['dataitem'][a]['i_customer'];
                        var ecustomer   = data['dataitem'][a]['e_customer_name'];
                        
                        var cols       = "";
                        var newRow = $("<tr>");
                        
                        cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';
                        cols += '<td><input readonly class="form-control" type="hidden" id="idcustomer'+no+'" name="idcustomer'+no+'" value="'+id+'"><input readonly style="width:150px;" class="form-control" type="text" id="icustomer'+no+'" name="icustomer'+no+'" value="'+icustomer+'"></td>';
                        cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="ecustomer'+no+'" name="ecustomer'+no+'" value="'+ecustomer+'"></td>'; 
                        cols += '<td><input style="width:200px;" class="form-control" type="text" id="v_nilai'+no+'" name="v_nilai'+no+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>'; 
                        cols += '<td><input style="width:400px;" class="form-control" type="text" id="edesc'+no+'" name="edesc'+no+'" value=""></td>';
                        cols +='<td><input type="checkbox" name="cek'+no+'" value="checked" id="cek'+no+'" onchange="cek_value('+no+');"></td>';
                       
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
        if (icustomer != null) {
            if (icustomer.indexOf('ALL') !== -1) {
                $("#icustomer option:selected").select2().removeAttr("selected");
                $("#icustomer option[value='ALL']").select2().attr("selected","selected");
                $('#icustomer').val('ALL');
           }
       }
        
    } 

    function cek_value(i){
        var vtotal    = formatulang(document.getElementById('vnilai').value);
        var vtotal_a = formatulang(document.getElementById('vnilai_a').value);
        var vtotalold = formatulang(document.getElementById('vnilaiold').value);
        if (document.getElementById('cek' + i).checked == true) {
            $('#v_nilai'+i).attr("readonly", true);               
            var v_nilai = formatulang($('#v_nilai' + i).val());
            if(v_nilai == 0){
                swal('Nilai Tidak Boleh 0');
                $("#cek"+i).attr("checked", false);
                $('#v_nilai'+i).attr("readonly", false);
            }
            totakhir = parseFloat(vtotal) - parseFloat(v_nilai);
            totakhira = parseFloat(vtotal_a) + parseFloat(v_nilai);
        } else {
            $('#v_nilai'+i).attr("readonly", false);
            var v_nilai = formatulang($('#v_nilai' + i).val());
            totakhir = parseFloat(vtotal) + parseFloat(v_nilai);
            totakhira = parseFloat(vtotal_a) - parseFloat(v_nilai);
        }
        document.getElementById('vnilai').value = formatcemua(totakhir);
        document.getElementById('vnilai_a').value = formatcemua(totakhira);
        
        if(totakhira > vtotalold){
            //alert(totakhira);
            swal('Nilai Tidak Boleh Lebih dari Sisa Nilai Referensi');
            $("#cek"+i).attr("checked", false);
            var vtotal_z = formatulang(document.getElementById('vnilai').value);
            var vtotal_x = formatulang(document.getElementById('vnilai_a').value);
            if (document.getElementById('cek' + i).checked == false) {
                $('#v_nilai'+i).attr("readonly", false);
                var v_nilai_2 = formatulang($('#v_nilai' + i).val());
                //alert(vtotal_z);
                totakhir_a = parseFloat(vtotal_x) - parseFloat(v_nilai_2);
                totakhir_b = parseFloat(vtotal_z) + parseFloat(v_nilai_2);
                document.getElementById('vnilai').value = formatcemua(totakhir_b);
                document.getElementById('vnilai_a').value = formatcemua(totakhir_a);
                $('#v_nilai'+i).val("0");
            }
            $("#submit").attr("disabled", true);
        }else{
            $("#submit").attr("disabled", false);
        }
    }

    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#ikasbank').val()!='' || $('#ikasbank').val()) && ($('#ipartner').val()!='' || $('#ipartner').val()) && ($('#ireferensi').val()!='' || $('#ireferensi').val()) && ($('#icustomer').val()!='' || $('#icustomer').val())) {
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