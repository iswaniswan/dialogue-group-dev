<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-sm-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Supplier</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
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
                                <input type="hidden" name="inotereturold" id="inotereturold" value="<?= $data->i_document;?>">
                                <input type="text" name="inoteretur" id="inoteretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b>* No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dnoteretur" name="dnoteretur" class="form-control input-sm date" required="" readonly value="<?= date('d-m-Y', strtotime($data->d_document)); ?>"  >
                        </div>
                        <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="return getreferensi();" disabled="">
                                <option value="<?php echo $data->id_supplier;?>" selected><?= $data->e_supplier_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Nomor Referensi</label> 
                        <label class="col-md-6">Total Retur</label>
                        <div class="col-sm-6">  
                            <select name="ireferensi" id="ireferensi" class="form-control select2" multiple="multiple"> 
                                <option value="<?php echo $data->id_document_reff;?>" selected><?= $data->i_document_reff.' || '.$data->d_document_reff;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">                           
                            <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="<?php echo $data->v_total?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea> 
                        </div>   
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
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
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center">Nomor Nota Retur</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Harga Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0;
                        foreach ($datadetail as $row) {
                        $i++;
                    ?>
                        <tr>
                        <td style="text-align: center;"><?=$i;?>
                            <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                        </td>
                        <td class="col-sm-1">
                            <input class="form-control" type="hidden" id="idnotaretur<?=$i;?>" name="idnotaretur<?=$i;?>" value="<?= $row->id_document_reff; ?>" readonly>
                            <input style ="width:250px" class="form-control" type="text" id="inotaretur<?=$i;?>" name="inotaretur<?=$i;?>" value="<?= $row->i_document_ref; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input class="form-control" type="hidden" id="idmaterial<?=$i;?>" name="idmaterial<?=$i;?>" value="<?= $row->id_material; ?>" readonly>
                            <input style ="width:150px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>" value="<?= $row->i_material; ?>" readonly>
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:400px" class="form-control" type="text" id="ematerial<?=$i;?>" name="ematerial<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly>
                        </td>   
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_quantity; ?>" readonly>
                        </td> 
                        <td class="col-sm-1">
                            <input style ="width:150px" class="form-control" type="text" id="vprice<?=$i;?>" name="vprice<?=$i;?>" value="<?= $row->v_price; ?>" readonly>
                        </td>                              
                        <td class="col-sm-1">
                            <input style ="width:150px" class="form-control" type="text" id="vpricetotal<?=$i;?>" name="vpricetotal<?=$i;?>"value="<?= $row->v_price_total; ?>" readonly>
                        </td>
                        </tr>
                    <?php } ?>  
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
            </table>
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('#inoteretur').mask('SS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
    });

    $( "#inoteretur" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#inoteretur').val()!=$('#inotereturold').val())) {
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
                'tgl' : $('#dnoteretur').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#inoteretur').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ibagian, #dnoteretur').change(function(event) {
        number();
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#inoteretur").attr("readonly", false);
        }else{
            $("#inoteretur").attr("readonly", true);
            $("#inoteretur").val("<?= $id;?>");
            number();
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
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

   $(document).ready(function () {
        $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
        allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/supplier'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                      results: data
                    };
                },
                cache: true
            }
        })
    });

    function getreferensi(){
        $("#ireferensi").attr("disabled", false);
        var isupplier = $('#isupplier').val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
            data:{
                    'isupplier': isupplier,
            },
            dataType: 'json',
            success: function(data){
                $("#ireferensi").html(data.kop);
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },
            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
    }

    $("#ireferensi").change(function() {
        var ireferensi = $('#ireferensi').val();
        var isupplier = $('#isupplier').val();
        $("#ireferensi").val($(this).val());
        $("#tabledatax tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'id'        : $(this).val(),
                'ireferensi': ireferensi,
                'isupplier' : isupplier
            },
            url: '<?= base_url($folder.'/cform/getdetailreff'); ?>',
            dataType: "json",
            success: function (data) {
                $('#tabledatax').attr('hidden', false);
                $('#jml').val(data['dataitem'].length);
                for (let a = 0; a < data['dataitem'].length; a++) {
                    var no = a+1;
                    var cols = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+no+'</td>';
                    cols += '<td style="text-align: center"><input type="hidden" class="form-control" readonly id="idnotaretur'+no+'" name="idnotaretur'+no+'" value="'+data['dataitem'][a]['id_document']+'"><input type="text" style="width:250px;" class="form-control" readonly id="inotaretur'+no+'" name="inotaretur'+no+'" value="'+data['dataitem'][a]['i_document']+'"></td>';
                    cols += '<td><input type="hidden" class="form-control" readonly id="idmaterial'+no+'" name="idmaterial'+no+'" value="'+data['dataitem'][a]['id_material']+'"><input style="width:150px;" class="form-control" readonly id="imaterial'+no+'" name="imaterial'+no+'" value="'+data['dataitem'][a]['i_material']+'"></td>';
                    cols += '<td><input style="width:400px;" readonly class="form-control" id="ematerial'+no+'" name="ematerial'+no+'" value="'+data['dataitem'][a]['e_material_name']+'"></td>';
                    cols += '<td><input style="width:100px;" class="form-control text-right" readonly id="nquantity'+no+'" name="nquantity'+no+'" value="'+data['dataitem'][a]['n_retur']+'"></td>';
                    cols += '<td><input style="width:150px;" class="form-control text-right" id="vprice'+no+'" name="vprice'+no+'" value="'+data['dataitem'][a]['v_price']+'" readonly></td>';
                    cols += '<td><input style="width:150px;" class="form-control" id="vpricetotal'+no+'" name="vpricetotal'+no+'" value="" readonly></td>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
                hitung();
            },
            error: function () {
                swal('Data kosong :)');
            }
        });
    });

    function hitung() {
        var jml = $('#jml').val();
        // var tot = 0;
        jumlah = 0;

        for (var i = 1; i <= jml; i++) {
            var hrg = $('#vprice' + i).val();
            var qty = $('#nquantity' + i).val();
            total= qty*hrg;
            $('#vpricetotal' + i).val(formatcemua(total));

            jum = formatulang($('#vpricetotal' + i).val());
            jumlah = parseFloat(jumlah) + parseFloat(jum);
            $('#vtotalfa').val(formatcemua(jumlah));

           /* dpp = (100/110 * parseFloat(total));
            $('#dpp' + i).val(formatcemua(dpp));
            ppn = (0.1 * dpp);
            $('#ppn' + i).val(formatcemua(ppn));*/
        }
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }        
    }
</script>