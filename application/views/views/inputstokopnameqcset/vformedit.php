<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Pembuat Dokumen</label>
                        <label class="col-md-2">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-6">Keterangan</label>                        
                        <div class="col-sm-2">
                            <input type="hidden" name="ibagian" id="ibagian" class="form-control" value="<?= $datahead->i_bagian;?>" readonly>   
                            <input type="text" name="e_bagian_name" id="e_bagian_name" class="form-control" value="<?= $datahead->e_bagian_name;?>" readonly>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="hidden" name="id" id="id" class="form-control" value="<?= $datahead->id;?>" readonly="">
                            <input type="text" name="idocument" id="idocument" class="form-control" value="<?= $datahead->i_document;?>" readonly>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" name="ddocument" id="ddocument" class="form-control" value="<?= $datahead->d_document;?>" readonly>   
                        </div>
                        <div class="col-sm-6"> 
                            <textarea name="eremarkh" id="eremarkh" class="form-control"><?= $datahead->e_remark;?></textarea>   
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($datahead->i_status == '1' || $datahead->i_status == '3' || $datahead->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;

                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button> 
                            <?php } ?>
                            <?php if ($datahead->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($datahead->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledatax" class="table color-table success-table table-bordered class" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th width="30%">Kode Panel</th>
                                    <th width="37%">Nama Barang</th>
                                    <th width="10%">Jumlah SO</th>
                                    <th width="20%">Keterangan</th>
                                    <th width="10%">Action</th> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
                                    if($datadetail){
                                        $group = "";
                                        $z = 0;
                                        $x = 0;
                                        foreach ($datadetail as $key) {
                                        $i++;
                                        $x++;

                                if($group !== $key['id_product_wip']){
                                    $group = "";
                                    $x = 1;
                                }

                                if($group == ""){
                                $z++;    
                                ?>
                                <tr id="tr<?= $z;?>">
                                    <td  colspan="5">
                                        <select class="form-control select2 input-sm" id="product<?= $z ;?>" data-nourut="<?= $z; ?>">
                                            <option value="<?= $key['id_product_wip']; ?>"><?= $key['i_product_wip'].' - '.$key['e_product_wipname'].' - '.$key['e_color_name']; ?></option>
                                        </select>
                                    </td>
                                    <td class="text-center"><button type="button" title="Delete" onclick="hapusdetail(<?= $z;?>);" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>
                                </tr>
                                
                                <?php
                                }
                                ?>
                                <tr class="del<?= $z;?>">
                                    <td class="text-center"><spanx id="snum<?= $i ;?>"><?= $x ;?></spanx></td>
                                    <td>
                                        <input type="text"  class="form-control input-sm inputitem" value="<?= $key['i_panel'].' - '.$key['bagian'];?>" readonly>
                                        <input type="hidden" id="idpanel<?= $i ;?>" name="idpanel[]" value="<?= $key['id_panel_item'] ;?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm inputitem" value="<?= $key["i_material"].' - '.$key["e_material_name"].' ('.$key["e_satuan_name"].')';?>" readonly>
                                    </td>
                                    <td><input type="text" id="nquantity<?= $i ;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="<?= $key["n_quantity"];?>" onkeyup="angkahungkul(this);"></td>
                                    <td colspan="2"><input type="text" class="form-control input-sm" name="eremark[]" id="eremark<?= $i ;?>" value="<?= $key["e_remark"];?>" placeholder="Isi keterangan jika ada!"/></td>
                                    
                                </tr>
                                <?php 
                                    $group = $key['id_product_wip'];
                                        }
                                    } 
                                ?> 
                                <input style ="width:50px"type="hidden" name="jmlh" id="jmlh" value="<?= $z; ?>">
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
        for (var i = 1; i <= $('#jml').val(); i++) {
            $('#product'+ i).select2({
                    placeholder: 'Cari Kode / Nama Barang',
                    allowClear: true,
                    width: "100%",
                    type: "POST",
                    ajax: {
                        url: '<?= base_url($folder.'/cform/barang/'); ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            var query   = {
                                q         : params.term,
                                ibagian   : $('#ibagian').val(),
                                ddocument : $('#ddocument').val(),
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
                    var z = $(this).data('nourut');
                    hapusdetail(z);
                /**
                 * Cek Barang Sudah Ada
                 * Get Harga Barang
                 */
                    var kode = $(this).val();
                    var ada = true;
                    for(var x = 1; x <= $('#jmlh').val(); x++){
                        if ($(this).val()!=null) {
                            if(($(this).val() == $('#product'+x).val()) && (z!=x)){
                                swal ("kode barang tersebut sudah ada !!!!!");
                                ada = false;
                                break;
                            } else {
                                
                            }
                        }
                    }
                    if (!ada) {                
                        $(this).val('');
                        $(this).html('');
                    }else{
                        $.ajax({
                        type: "post",
                        data: {
                            'id'         :kode,
                            'ibagian'    : $('#ibagian').val(),
                            'ddocument'  : $('#ddocument').val(),
                        },
                        url: '<?= base_url($folder.'/cform/detailbarang'); ?>',
                        dataType: "json",
                        success: function (data) {
                            var xx = 0;
                            var netr = "";
                            var cols1   = "";
                            var idproduct = kode;
                            for (let x = data['detail'].length; x > 0 ; x--) {
                                var newRow1 = $('<tr class="del'+z+'">');
                                cols1 += '<td class="text-center">'+x+'<input type="hidden" name="idpanel[]" value="'+data['detail'][xx]['id_panel']+'"></td>';
                                cols1 += '<td><input type="hidden" name="idproductwip[]" value="'+data['detail'][xx]['id_product_wip']+'">';
                                cols1 += '<input type="hidden" class="idmaterial" name="idmaterial[]" value="'+data['detail'][xx]['id_material']+'">';
                                cols1 += '<input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_panel']+' - '+data['detail'][xx]['bagian']+'"></td>';
                                cols1 += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_material']+' - '+data['detail'][xx]['e_material_name']+'"></td>';
                                cols1 += '<td><input class="form-control qty input-sm text-right inputqty_'+idproduct+' material_'+idproduct+'_'+x+'" data-noqty="'+x+'"  autocomplete="off" type="text" id="nquantity'+x+'" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
                                cols1 += '<td colspan="2"><input class="form-control input-sm" type="text" name="eremark[]" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                                newRow1.append(cols1);
                                $('#nquantity'+z).focus();
                                /*$("#tabledatax #tr"+z).insertAfter(newRow1);*/
                                $(newRow1).insertAfter("#tabledatax #tr"+z);
                                xx ++;
                            }
                        },
                        error: function () {
                            swal('Data kosong : (');
                        }
                    });
                        $('#nquantity'+z).focus();
                    }
                });
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
    });

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
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
    })

    /**
     * After Submit
     */

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    var z = $('#jmlh').val();
    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        z++;
        i++; 
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow =  $('<tr id="tr'+z+'">');
        var cols   = "";
        cols+= `<td  colspan="5"><select class="form-control select2 input-sm" id="product${z}" data-nourut="${z}"><option></option></select></td>`;
        cols+= `<td class="text-center"><button type="button" title="Delete" onclick="hapusdetail(${z});" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td></tr>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#product'+ z).select2({
            placeholder: 'Cari Kode / Nama Bahan Baku',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/barang/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q         : params.term,
                        ibagian   : $('#ibagian').val(),
                        ddocument : $('#ddocument').val(),
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
            /**
             * Cek Barang Sudah Ada
             * Get Harga Barang
             */
            var kode = $(this).val();

            var z = $(this).data('nourut');
            var ada = true;
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if(($(this).val() == $('#idmaterial'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    } else {
                        $('#e_satuan'+z).val(kode);
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
                $.ajax({
                        type: "post",
                        data: {
                            'id'         :kode,
                            'ibagian'    : $('#ibagian').val(),
                            'ddocument'  : $('#ddocument').val(),
                        },
                        url: '<?= base_url($folder.'/cform/detailbarang'); ?>',
                        dataType: "json",
                        success: function (data) {
                            var xx = 0;
                            var netr = "";
                            var cols1   = "";
                            var idproduct = kode;
                            for (let x = data['detail'].length; x > 0 ; x--) {
                                var newRow1 = $('<tr class="del'+z+'">');
                                cols1 += '<td class="text-center">'+x+'<input type="hidden" name="idpanel[]" value="'+data['detail'][xx]['id_panel']+'"></td>';
                                cols1 += '<td><input type="hidden" name="idproductwip[]" value="'+data['detail'][xx]['id_product_wip']+'">';
                                cols1 += '<input type="hidden" class="idmaterial" name="idmaterial[]" value="'+data['detail'][xx]['id_material']+'">';
                                cols1 += '<input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_panel']+' - '+data['detail'][xx]['bagian']+'"></td>';
                                cols1 += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][xx]['i_material']+' - '+data['detail'][xx]['e_material_name']+'"></td>';
                                cols1 += '<td><input class="form-control qty input-sm text-right inputqty_'+idproduct+' material_'+idproduct+'_'+x+'" data-noqty="'+x+'"  autocomplete="off" type="text" id="nquantity'+x+'" name="nquantity[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>';
                                cols1 += '<td colspan="2"><input class="form-control input-sm" type="text" name="eremark[]" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                                newRow1.append(cols1);
                                $('#nquantity'+z).focus();
                                /*$("#tabledatax #tr"+z).insertAfter(newRow1);*/
                                $(newRow1).insertAfter("#tabledatax #tr"+z);
                                xx ++;
                            }
                        },
                        error: function () {
                            swal('Data kosong : (');
                        }
                    });
                $('#nquantity'+z).focus();
            }
        });
    });

    /**
     * Hapus Detail Item
     */

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();

        $('#jml').val(i);
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    });
</script>