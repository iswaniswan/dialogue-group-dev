<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if($row->i_bagian == $data->i_bagian){ echo "selected";} ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" id="id" name="id" class="form-control" value="<?= $data->id;?>">
                            <input type="hidden" id="idocumentold" name="idocumentold" class="form-control" value="<?=$data->i_document;?>">
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BONM-2010-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $data->i_document;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                             <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?= $data->d_document; ?>" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ipengirim" id="ipengirim" class="form-control select2">
                                <option value="<?= $data->i_bagian_pengirim; ?>"><?= $data->e_bagian_pengirim; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);"> 
                                <option value="<?= $data->id_reff; ?>"><?= $data->i_reff.' - '.$data->e_jenis_name; ?></option>
                            </select>
                            <input type="hidden" id="ijenis" name="ijenis" value="<?= $data->id_jenis_barang_keluar; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="<?= $data->d_reff; ?>" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7' || $data->i_status == '6') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th style="text-align:center;">No</th>
                        <th colspan="2" style="text-align:center;">Barang</th>
                        <th style="text-align:center; width:10%;">Qty Kirim</th>
                        <th style="text-align:center; width:10%;">Qty Terima</th>
                        <th style="text-align:center;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($datadetail as $key) { $i++; 
                       
                        ?>
                            
                                    <tr id="tr<?= $i;?>">
                                        <td class="text-center"><?= $i; ?></td>
                                        <td colspan="2">
                                            <input readonly data-nourut="<?= $i ;?>" id="iproduct<?= $i ;?>" type="text" class="form-control input-sm" name="iproduct<?= $i ;?>" value="<?= $key->i_product_wip.' - '.$key->e_product_wipname.' - '.$key->e_color_name;?>">
                                            <input type="hidden" name="idproduct<?=$i;?>" id="idproduct<?=$i;?>" class="form-control" value="<?= $key->id_product_wip;?>">
                                            <input type="hidden" name="idreff<?=$i;?>" id="idreff<?=$i;?>" class="form-control" value="<?= $key->id_reff;?>">
                                        </td>
                                        <td>
                                            <input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' name="nquantitywip<?= $i ;?>" id="nquantitywip<?= $i ;?>" value="<?= $key->n_quantity_wip;?>" onkeyup="validasi(<?= $i; ?>);">
                                        </td>
                                        <td>
                                            <input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantityterima<?= $i ;?>" name="nquantityterima<?= $i; ?>" onblur='if(this.value==""){this.value="0";}' onfocus='if(this.value=="0"){this.value="";}' value="<?= $key->n_quantity_terima;?>" onkeyup="angkahungkul(this);validasi(<?= $i; ?>);" >
                                        </td>
                                        <td colspan="2">
                                            <input type="text" class="form-control input-sm" name="edesc<?=$i;?>" id="edesc<?=$i;?>" value="<?= $key->e_remark;?>" placeholder="Isi keterangan jika ada!"/>
                                        </td>
                                    </tr>
                                <?php
                            }?>
                </tbody>         
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
</form>
<?php } ?>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        max_tgl();
        /**
        * Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun) dari hari ini
        */
        showCalendar('.date',1830,0);
        
        //$('#idocument').mask('SSS-0000-0000S');
        //memanggil function untuk penomoran dokumen
        number();
        $('#ipengirim').select2({
            placeholder: 'Pilih Bagian Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bagianpengirim'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#ibagian').val(),
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

        $('#ireff').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iasal : $('#ipengirim').val(),
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

    $( "#idocument" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $("#ibagian").val(),
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
    $("#ipengirim").change(function(){
        $('#ireff').attr("disabled", false);
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
        }
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

    function getdataitem(ireff) {
        var idreff = $('#ireff').val();
        var ipengirim = $('#ipengirim').val();

            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                    'ipengirim' : ipengirim,
                },
                url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
                dataType: "json",
                success: function (data) {  
                    
                    $('#jml').val(data['jmlitem']);
                    $("#tabledatax tbody").remove();
                    $("#detail").attr("hidden", false);

                    var dref =  data['datahead']['d_document'];
                    $("#dreferensi").val(dref);
                    group = "";
                    i = 0;
                    for (let a = 0; a < data['jmlitem']; a++) {
                        //var no = a+1;
                        //count=$('#tabledatax tr').length;   
                        var idproduct   = data['dataitem'][a]['id_product_wip'];
                        var newRow      = $("<tr>");
                        var cols        = "";
                        var cols1       = "";
                            cols1 += '<td class="text-center">'+a+'</td>';
                            cols1 += '<td colspan="2"><input type="text" id="iproduct'+a+'" class="form-control input-sm" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly><input type="hidden" name="idreff'+a+'" value="'+data['dataitem'][a]['id']+'" ></td>';
                            cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" value="'+data['dataitem'][a]['n_quantity_wip']+'" ></td>';
                            cols1 += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantityterima'+a+'" name="nquantityterima'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);validasi('+a+');" ></td>';
                            // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywipsisa'+a+'" name="nquantitywipsisa'+a+'" value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" readonly></td>';
                            // cols1 += '<td><input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" id="nquantitywipmasuk'+a+'" name="nquantitywipmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_wip_sisa']+'" onkeyup="validasi('+a+');"></td>';
                            cols1 += '<td colspan="2"><input class="form-control input-sm" type="text" name="edesc'+a+'" id="edesc'+a+'" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                        
                        newRow.append(cols1);
                        $("#tabledatax").append(newRow);
                        group = idproduct;
                        // var newRow = $("<tr>");
                        // cols += '<td class="text-center">'+(a+1)+'</td>';
                        // cols += '<td><input type="hidden" name="idproductwip[]" id="idproductwip'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'">';
                        // cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial'+a+'" value="'+data['dataitem'][a]['id_material']+'">';
                        // cols += '<input style="width:120px;" class="form-control input-sm" readonly type="text" value="'+data['dataitem'][a]['i_material']+'"></td>';
                        // cols += '<td><input style="width:350px;" class="form-control input-sm" readonly type="text " value="'+data['dataitem'][a]['e_material_name']+'"></td>';
                        // cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahan[]" id="nquantitybahan'+a+'" readonly value="'+data['dataitem'][a]['n_quantity']+'"></td>';
                        // cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right" autocomplete="off" type="text" name="nquantitybahansisa[]" id="nquantitybahansisa'+a+'" readonly value="'+data['dataitem'][a]['n_quantity_sisa']+'"></td>';
                        // cols += '<td><input style="width:100px;" class="form-control qty input-sm text-right inputitem" autocomplete="off" type="text" name="nquantitybahanmasuk[]" id="nquantitybahanmasuk'+a+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="'+data['dataitem'][a]['n_quantity_sisa']+'" onkeyup="validasi('+a+');"></td>';
                        // cols += '<td colspan="2"><input style="width:250px;" class="form-control input-sm" type="text" name="edesc[]" id="edesc'+a+'" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                        // newRow.append(cols);
                        // $("#tabledatax").append(newRow);
                }

                function formatSelection(val) {
                    return val.name;
                }

                $("#tabledatax").on("click", ".ibtnDel", function (event) {
                    $(this).closest("tr").remove();       
                });
                max_tgl();
            },
            error: function () {
                alert('Error :)');
            }
        });
    } 

    function max_tgl() {
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

    function validasi(id){
        var nquantitywip           = $("#nquantitywip"+id).val();
        var nquantityterima        = $("#nquantityterima"+id).val();
        // nquantitywip           = $("#nquantitywip"+id).val();
        // nquantityterima        = $("#nquantityterima"+id).val();
        // nquantityma            = $("#nquantitywipsisa"+id).val();
        // nquantitymasuk         = $("#nquantitywipmasuk"+id).val();
        // nquantitymaterial      = $("#nquantitybahansisa"+id).val();
        // nquantitymasukmaterial = $("#nquantitybahanmasuk"+id).val();

        if(parseInt(nquantityterima) > parseInt(nquantitywip)){
            swal('QTY Terima Tidak boleh lebih besar dari QTY Kirim '+ nquantitywip );
            $("#nquantityterima"+id).val(nquantitywip);
        } 

        // if(parseFloat(nquantitywip)< 0){
        //     swal('Quantity Masuk Tidak Boleh Kurang Dari 0');
        //     $("#nquantitywip"+id).val(0);
        // }
        // if(parseFloat(nquantityterima)< 0){
        //     swal('Quantity Masuk Tidak Boleh Kurang Dari 0');
        //     $("#nquantityterima"+id).val(0);
        // }
        // if(parseFloat(nquantitymasuk)>parseFloat(nquantityma)){
        //     swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
        //     $("#nquantitywipmasuk"+id).val(nquantityma);
        // }
        // if (parseFloat(nquantitymasukmaterial)>parseFloat(nquantitymaterial)){
        //     swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Keluar');
        //     $("#nquantitybahanmasuk"+id).val(nquantitymaterial);
        // }

        // if(parseFloat(nquantitymasuk) == '0'){
        //     swal('Quantity Tidak Boleh 0 atau Kosong');
        //     $("#nquantitywipmasuk"+id).val(nquantityma);
        // } 
        // if(parseFloat(nquantitymasukmaterial) == '0'){
        //     swal('Quantity Tidak Boleh 0 atau Kosong');
        //     $("#nquantitybahanmasuk"+id).val(nquantitymaterial);
        // }
    }

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
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
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
    }
</script>
