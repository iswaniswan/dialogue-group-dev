<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tujuan</label>    
                        <div class="col-sm-3">
                            <input type="hidden" name="xbagian" id="xbagian" value="<?= $data->i_bagian;?>">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="ibonkold" id="ibonkold" value="<?= $data->i_document;?>">
                                <input type="text" name="ibonk" id="ireturwip" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span class="notekode">Format : (<?= $number;?>)</span><br> -->
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbonk" name="dbonk" class="form-control input-sm date"  required="" readonly value="<?= $data->d_document;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <!-- <?php if ($tujuan) {
                                    foreach ($tujuan as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_tujuan) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?> -->
                                <?php if ($tujuan) {
                                    $group = "";
                                    foreach ($tujuan as $row) : ?>
                                    <?php if ($group!=$row->name) {?>
                                        </optgroup>
                                        <optgroup label="<?= strtoupper(str_replace(".","",$row->name));?>">
                                    <?php }
                                    $group = $row->name;
                                    ?>
                                        <option value="<?= "$row->id_company|$row->i_bagian"; ?>" <?php if ($row->id_company.'|'.$row->i_bagian == $data->id_bagian_company.'|'.$data->i_tujuan) { ?> selected <?php } ?>>
                                            <?= $row->e_bagian_name; ?>
                                        </option>
                                <?php endforeach;
                                } ?>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>                   
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm mr-2" onclick="return konfirm();"><i class="fa fa-save mr-2" ></i>Update</button>
                            <?php } ?>

                            <?php if($data->i_status == '2'){?>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2" hidden="true"><i class="fa fa-plus mr-2"></i>Item</button>
                            <?}else{?>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm mr-2"><i class="fa fa-plus mr-2"></i>Item</button>
                            <?}?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left mr-2"></i>Kembali</button>
                            <?php if ($data->i_status == '1' || $data->i_status == '3') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm mr-2"><i class="fa fa-trash mr-2"></i>Delete</button>
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm mr-2"><i class="fa fa-refresh mr-2"></i>Cancel</button>
                            <?php } ?>
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
                </div>
            </div>
        </div> -->
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 15%;">Kode Barang</th>
                        <th class="text-center" style="width: 27%;">Nama Barang Jadi</th>
                        <th class="text-center" style="width: 15%;">Warna</th>
                        <th class="text-center" style="width: 10%;">Quantity</th>
                        <th class="text-center" style="width: 10%;">Alasan Repair</th>
                        <th class="text-center" style="width: 20%;">Keterangan</th>
                        <th class="text-center" style="width: 5%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 0;
                    if ($detail) {
                        foreach ($detail as $row) {
                            $i++;?>
                            <tr>
                                <td class="text-center">
                                    <spanx id="snum<?=$i;?>"><?= $i;?></spanx>
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->id_product;?>" id="idproduct<?=$i;?>" name="idproduct[]">
                                    <input type="text" value="<?= $row->i_product_base;?>" readonly id="iproduct<?=$i;?>" name="iproduct[]" class="form-control input-sm">
                                </td>
                                <td>
                                    <select id="eproduct<?=$i;?>" class="form-control select2" name="eproduct[]" onchange="getproduct(<?=$i;?>);">
                                        <option value="<?= $row->id_product;?>"><?= $row->e_product_basename;?></option>
                                    </select>
                                    <input type="hidden" id="stok<?=$i;?>" name="stok<?=$i;?>" value="<?= $row->saldo_akhir; ?>">
                                </td>
                                <td>
                                    <input type="hidden" value="<?= $row->id_color;?>" id="idcolorproduct<?=$i;?>" name="idcolorproduct[]">
                                    <input type="text" value="<?= $row->e_color_name;?>" readonly id="ecolorproduct<?=$i;?>" name="ecolorproduct[]" class="form-control input-sm">
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity_product;?>" id="nquantity<?=$i;?>" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" onkeyup="angkahungkul(this); validasi(<?=$i;?>);">
                                </td>
                                <td>
                                    <select id="ialasan<?=$i;?>" class="form-control select2" name="ialasan[]">
                                        <?php if ($alasan) {
                                            foreach ($alasan as $rowalasan):?>
                                                <option value="<?= $rowalasan->id;?>" <?php if($rowalasan->id == $row->id_alasan_retur_repair){ echo "selected";} ?>>
                                                    <?= $rowalasan->e_alasan_name;?>
                                                </option>
                                            <?php endforeach; 
                                        } ?>
                                    </select>
                                </td>
                                <td><input type="text" id="edesc<?=$i;?>" class="form-control input-sm" name="edesc[]" value="<?= $row->e_remark;?>"></td>
                                <td class="text-center">
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
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
   $(document).ready(function () {
        $('#ireturwip').mask('SSS-0000-000000S');
        $('.select2').select2({
            width : '100%',
        });
        showCalendar('.date', 1830, 0);
        $('#ibagian').select2({
            placeholder: 'Pilih Bagian',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bagian'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian : $('#xbagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });
    
    $( "#ireturwip" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#ireturwip').val()!=$('#ireturwipold').val())) {
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

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ireturwip").attr("readonly", false);
        }else{
            $("#ireturwip").attr("readonly", true);
            $("#ada").attr("hidden", true);
            $("#ireturwip").val($("#ireturwipold").val());
            /*number();*/
        }
    });

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dbonk').val(),
                'ibagian' : $('#itujuan').val(),
                'id' : $('#id').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ireturwip').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#itujuan, #dbonk').change(function(event) {
        number();
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
        //$("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    var counter = $('#jml').val();
    //var counterx = counter-1;
    $("#addrow").on("click", function () {
        counter++;
        $("#tabledatax").attr("hidden", false);
      //  var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledatax tr').length;
        // if ((iproduct==''||iproduct==null)&&(count>1)) {
        //     swal('Isi dulu yang masih kosong!!');
        //     counter = counter-1;
        //     counterx = counterx-1;
        //     return false;
        // }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td class="text-center"><spanx id="snum'+counter+'">'+count+'</spanx></td>';
        cols += '<td><input type="hidden" readonly id="idproduct'+ counter + '" class="form-control" name="idproduct[]"><input type="text" readonly id="iproduct'+ counter + '" class="form-control input-sm" name="iproduct' + counter + '"></td>';
        cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control" name="eproduct'+ counter + '" onchange="getproduct('+ counter + '); getstok('+ counter +');"></select><input type="hidden" id="stok'+ counter +'" name="stok'+ counter +'"></td>';
        cols += '<td><input type="hidden" id="idcolorproduct'+ counter + '" class="form-control" name="idcolorproduct[]"><input type="text" readonly id="ecolorproduct'+ counter + '" class="form-control input-sm" name="ecolorproduct'+ counter + '"></td>';
        /* cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control text-right inputitem" name="nquantity[]" value="0" onkeypress="return hanyaAngka(event);"></td>'; */
        cols += '<td><input type="text" data-nourut="'+ counter +'" id="nquantity'+ counter + '" class="form-control input-sm text-right inputitem" name="nquantity[]" value="0" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="validasi('+ counter +');"></td>';
        cols += '<td><select type="text" id="ialasan'+ counter + '" class="form-control select2" name="ialasan[]"</td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control input-sm" name="edesc[]"></td>';
        cols += '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';

        newRow.append(cols);
        $("#tabledatax").append(newRow);

        $('#eproduct'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/dataproduct'); ?>',
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

        $('#ialasan'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            allowClear: true,
            width:"100%",
            ajax: {
                url: '<?= base_url($folder.'/cform/alasanrepair'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
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
                swal ("kode Barang : "+a+" sudah ada !!!!!");            
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
                    'eproduct'  : eproduct
                },
                url: '<?= base_url($folder.'/cform/getproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#idproduct'+id).val(data[0].id_product);
                    $('#iproduct'+id).val(data[0].i_product_base);
                    $('#idcolorproduct'+id).val(data[0].id_color);
                    $('#ecolorproduct'+id).val(data[0].e_color_name);
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

    function getstok(id){
        var idproduct = $('#eproduct'+id).val();
        var ibagian = $('#ibagian').val();
        $.ajax({
            type: "post",
            data: {
                'idproduct'  : idproduct,
                'ibagian'    : ibagian,
            },
            url: '<?= base_url($folder.'/cform/getstok'); ?>',
            dataType: "json",
            success: function (data) {
                $('#stok'+id).val(data.saldo_akhir);
            },
            error: function () {
                swal('Error :)');
            }
        });
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

    function validasi(id){
        var jml=document.getElementById("jml").value;
        if(id === undefined ){
            for(i=1;i<=jml;i++){
                var nquantity    =document.getElementById("nquantity"+i).value;
                var stok         =document.getElementById("stok"+i).value;
                if(parseFloat(nquantity)>parseFloat(stok)){
                    swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                    document.getElementById("nquantity"+i).value=stok;
                    return true;
                    break;    
                }
                if(parseFloat(nquantity) == 0 && parseFloat(nquantity) == ''){
                    swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                    document.getElementById("nquantity"+i).value=stok;
                    return true;
                    break;
                }
            }
            return false;
        }else{
            var nquantity    =document.getElementById("nquantity"+id).value;
            var stok         =document.getElementById("stok"+id).value;
            if(parseFloat(nquantity)>parseFloat(stok)){
                swal('Quantity Kirim Tidak Boleh Melebihi \nSaldo akhir ' + stok);
                document.getElementById("nquantity"+id).value=stok;
                
            }
            if(parseFloat(nquantity) == 0 && parseFloat(nquantity) == ''){
                swal('Quantity Kirim Tidak Boleh 0 atau Kosong');
                document.getElementById("nquantity"+id).value=stok;
                
            }
        }
    }

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