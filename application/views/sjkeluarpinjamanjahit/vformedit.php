 <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Perkiraan Kembali</label>  
                        <div class="col-md-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                         <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>

                        <div class="col-md-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" onchange="number();" required="" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="dback" name="dback" class="form-control input-sm date" onchange="number();" required="" readonly value="<?= $data->d_back;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="">
                                <option value="<?= $data->id_partner.'|'.$data->i_partner; ?>"><?= $data->epartner; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ireff" id="ireff" class="form-control select2">
                                <?php if($data->id_reff != '0'){?>
                                    <option value="<?= $data->id_reff; ?>"><?= $data->i_reff; ?></option>
                                <?}?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <textarea id="eremarkh" name="eremarkh" class="form-control"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                             <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" > <i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;
                            <?php } ?>                            
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">Kode</th>
                        <th class="text-center" style="width: 30%;">Nama Barang</th>
                        <th class="text-center" style="width: 13%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" style="width: 5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->id_product;?>" id="idproduct<?=$i;?>" name="idproduct[]">
                                    <input type="text" value="<?= $row->i_product;?>" readonly id="iproduct<?=$i;?>" name="iproduct[]" class="form-control">
                                </td>
                                <td>
                                    <select id="eproduct<?=$i;?>" class="form-control select2" name="eproduct[]" onchange="getproduct(<?=$i;?>);">
                                        <option value="<?= $row->id_product;?>"><?= $row->e_product;?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->id_color;?>" id="idcolorproduct<?=$i;?>" name="idcolorproduct[]">
                                    <input type="text" value="<?= $row->e_color_name;?>" readonly id="ecolorproduct<?=$i;?>" name="ecolorproduct[]" class="form-control">
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity;?>" id="nquantity<?=$i;?>" class="form-control text-right inputitem" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="angkahungkul(this);">
                                </td>
                                <td>
                                    <input type="text" id="edesc<?=$i;?>" class="form-control" value="<?= $row->e_remark;?>" name="edesc[]">
                                </td>
                                <td>
                                    <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>
</form>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $(document).ready(function () {
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
        });
    });
     
      /**
     * Tambah Item
     */

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {
        counter++;
        counterx++;
        $("#tabledatax").attr("hidden", false);
        var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledatax tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            counter = counter-1;
            counterx = counterx-1;
            return false;
        }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum'+counter+'">'+count+'</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct'+ counter + '" class="form-control" name="idproduct[]"><input type="text" readonly id="iproduct'+ counter + '" class="form-control" name="iproduct' + counter + '"></td>';
        cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control" name="eproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="hidden" id="idcolorproduct'+ counter + '" class="form-control" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct'+ counter + '" class="form-control" name="ecolorproduct'+ counter + '"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control text-right inputitem" name="nquantity[]" value="0" onkeypress="return hanyaAngka(event);" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0"></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]"></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

        newRow.append(cols);
        $("#tabledatax").append(newRow);

        $('#eproduct'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product'); ?>',
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

    function getproduct(id){
        ada=false;
        var a = $('#eproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#eproduct'+i).val()) && (i!=x)){
                swal ("kode Barang sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            var eproduct = $('#eproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'id'  : eproduct
                },
                url: '<?= base_url($folder.'/cform/detailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#idproduct'+id).val(formatcemua(data[0].id_product_wip));
                    $('#iproduct'+id).val(formatcemua(data[0].i_product_wip));
                    $('#idcolorproduct'+id).val(formatcemua(data[0].id_color));
                    $('#ecolorproduct'+id).val(formatcemua(data[0].e_color_name));
                    $('#nquantity'+id).focus();
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#idproduct'+id).html('');
            $('#iproduct'+id).html('');
            $('#eproduct'+id).html('');
            $('#idcolorproduct'+id).html('');
            $('#ecolorproduct'+id).html('');
            $('#idproduct'+id).val('');
            $('#iproduct'+id).val('');
            $('#eproduct'+id).val('');
            $('#idcolorproduct'+id).val('');
            $('#ecolorproduct'+id).val('');
        }
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        $('#jml').val(counter);
        del();
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

     //new script
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#isj').val($('#isjold').val());
        }else{
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
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $( "#idocument" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'kodeold' : $('#idocumentold').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkodeedit'); ?>',
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

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });
    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });
    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
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