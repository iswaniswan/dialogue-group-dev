<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">  
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Bagian Pengirim</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>" <?php if ($key->i_bagian == $data->i_bagian) {?> selected <?php } ?>><?= $key->e_bagian_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                            <input type="hidden" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" id="id" name="id" value="<?= $data->id;?>">
                            <input type="hidden" id="ibbmold" value="<?= $data->i_document;?>">  
                            <input type="text" name="idocument" required="" id="ibbm" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="16" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                            <!-- <div class="input-group">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div> -->
                            <!-- <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span> -->
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ipengirim" required="" id="ipengirim" class="form-control select2" data-placeholder="Pilih Pengirim">
                                <option value="<?= $data->id_bagian_pengirim;?>"><?= $data->e_bagian_pengirim;?></option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Dokumen Reff</label>
                        <label class="col-md-3">Jenis Barang</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ireff" id="ireff" required="" class="form-control input-sm select2">
                                <option value="<?= $data->id_document_reff;?>"><?= $data->i_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                <?php if ($jenisbarang) {
                                    foreach ($jenisbarang as $row):?>
                                        <option value="<?= $row->id;?>" <?php if ($row->id==$data->id_jenis_barang_keluar) {?> selected <?php } ?>>
                                            <?= $row->e_jenis_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                            <div class="col">
                                <button type="submit" id="submit" class="btn btn-success btn-block btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            </div>
                        <?php } ?>
                        <div class="col">
                            <button type="button" class="btn btn-inverse btn-block btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                        </div>
                        <?php if ($data->i_status == '1') { ?>
                            <div class="col">
                                <button type="button" id="send" class="btn btn-primary btn-block btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                            </div>
                            <div class="col">
                                <button type="button" id="hapus" class="btn btn-danger btn-block btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            </div>
                        <?php } elseif ($data->i_status=='2') { ?>
                            <div class="col-sm-6">
                                <button type="button" id="cancel" class="btn btn-primary btn-block btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            </div>
                        <?php } ?>
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
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="10%">Kode</th>
                        <th class="text-center" width="30%">Nama Barang</th>
                        <th class="text-center" width="12%">Warna</th>
                        <th class="text-center" width="8%">Qty Kirim</th>
                        <th class="text-center" width="10%">Qty Terima</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datadetail as $result) {
                        $product = $result['product'];
                        ?>

                        <?php if ($i >= 1) { ?>
                            <tr class="table-info">
                                <td colspan="8">
                                    <hr class="mb-0 mt-0">
                                </td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td class="text-center"><?=$i+1;?></td>
                            <td>
                                <input class="form-control input-sm" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $product->i_product;?>">
                                <input type="hidden" id="idproduct<?=$i;?>" name="idproduct<?=$i;?>" value="<?= $product->id_product;?>">
                            </td>
                            <td><input class="form-control input-sm" readonly type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>" value="<?= $product->e_product;?>"></td>
                            <td><input readonly class="form-control input-sm" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>" value="<?= $product->e_color_name;?>"></td>
                            <td><input readonly class="form-control input-sm text-right" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $product->n_quantity_reff;?>"><input type="hidden" name="nquantitysisa<?=$i;?>" id="nquantitysisa<?=$i;?>" value="<?= $product->n_quantity_sisa;?>"></td>
                            <td><input class="form-control input-sm text-right" type="text" id="npemenuhan<?=$i;?>" name="npemenuhan<?=$i;?>" value="<?= $product->n_quantity;?>" placeholder="0" onkeyup="angkahungkul(this); cekqty(<?=$i;?>);"></td>
                            <td><input class="form-control input-sm" placeholder="Isi keterangan jika ada!" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $product->e_remark;?>"></td>
                        </tr>

                        <?php if (!empty($result['bundling'])) { ?>
                            <tr class="th<?= $i; ?> bold table-active">
                                <td class="text-center"><i class="fa fa-hashtag fa-lg"></i></a></td>
                                <td colspan="7"><b>Bundling Produk</b></td>
                            </tr>

                            <?php $o = 1; ?>
                            <?php foreach($result['bundling'] as $bundling) { ?>

                            <tr>
                                <td class="text-center"><spanx id="snum<?= $i; ?>"><?= $o; ?></spanx></td>
                                <td><?= $bundling->i_product_base; ?></td>
                                <td  class="d-flex justify-content-between"><span><?= $bundling->e_product_basename; ?></span></td>
                                <td><?= $bundling->e_color_name; ?></td>
                                <td class="text-right"><?= $bundling->n_quantity_bundling; ?></td>
                                <td colspan="2"><?= $bundling->e_remark; ?></td>
                            </tr>

                            <?php $o++; ?>                            
                            <?php } ?>
                            
                        <?php } ?>

                    <?php $i++; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    
    $(document).ready(function () {
        $('#ibbm').mask('SSS-0000-0000S');
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',null,0);

        $('#ipengirim').select2({
            placeholder: 'Pilih Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/pengirim'); ?>',
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
            $('#ireff').val('');
            $('#ireff').html('');
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#ireff').select2({
            placeholder: 'Cari Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ipengirim  : $('#ipengirim').val(),
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
        }).change(function() {

            /*----------  GET DATA DETAIL AFTER CHANGE REFERENSI  ----------*/
            
            $("#tabledatax").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id' : $(this).val(),
                    'ibagian' : $('#ibagian').val(),
                    'ipengirim' : $('#ipengirim').val(),
                },
                url: '<?= base_url($folder.'/cform/detailreferensi'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['detail']!=null) {
                        $('#jml').val(data['detail'].length);
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols   = "";
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">'+(x+1)+'</td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="iproduct'+x+'" name="iproduct'+x+'" value="'+data['detail'][x]['i_product']+'"><input type="hidden" id="idproduct'+x+'" name="idproduct'+x+'" value="'+data['detail'][x]['id_product']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="eproduct'+x+'" name="eproduct'+x+'" value="'+data['detail'][x]['e_product']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm" type="text" id="ecolor'+x+'" name="ecolor'+x+'" value="'+data['detail'][x]['e_color_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantity'+x+'" name="nquantity'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"><input type="hidden" id="nquantitysisa'+x+'" name="nquantitysisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                            cols += '<td><input class="form-control input-sm text-right" type="text" id="npemenuhan'+x+'" name="npemenuhan'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'" placeholder="0" onkeyup="angkahungkul(this);cekqty('+x+');"></td>';
                            cols += '<td><input class="form-control input-sm" placeholder="Isi keterangan jika ada!" type="text" id="eremark'+x+'" name="eremark'+x+'" value=""></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                        }
                    }
                },
                error: function () {
                    swal('Ada kesalahan :(');
                }
            })
        });
    });

    /*----------  CEK QTY  ----------*/

    function cekqty(x) {
        if (parseInt($('#npemenuhan'+x).val()) > parseInt($('#nquantitysisa'+x).val())) {
            swal('Yaah :(','Qty Terima Tidak Boleh Lebih Dari Qty Kirim = '+$('#nquantitysisa'+x).val()+'!','error');
            $('#npemenuhan'+x).val($('#nquantitysisa'+x).val());
        }
    }

    /*----------  NOMOR DOKUMEN  ----------*/    

    function number() {
        if ($('#ibagian').val() == $('#ibagianold').val()) {
            $('#ibbm').val($('#ibbmold').val());
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
                    $('#ibbm').val(data);
                },
                error: function () {
                    swal('Error :(');
                }
            });
        }
    }

    /*----------  KONDISI PAS CHECKBOX DI NO DOKUMEN DIKLIK  ----------*/
    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ibbm").attr("readonly", false);
        }else{
            $("#ibbm").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN SAAT DIKETIK  ----------*/    

    $( "#ibbm" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#ibbm').val() != $('#ibbmold').val())) {
                    $("#ada").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $("#ada").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :(');
            }
        });
    });

    /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/    

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    /*----------  UPDATE NO DOKUMEN SAAT BAGIAN PEMBUAT DAN TANGGAL DOKUMEN DIRUBAH  ----------*/
    
    $('#ddocument, #ibagian').change(function(event) {
        number();
    });

    /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
    
    $('#submit').click(function(event) {
        if($("#jml").val()==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            for (var i = 0; i <= $("#jml").val(); i++) {
                if($("#npemenuhan"+i).val()=='' || $("#npemenuhan"+i).val()==null || $("#npemenuhan"+i).val()==0){
                    swal('Yaah :(','Jumlah Pemenuhan Harus Lebih Besar Dari 0!','error');
                    return false;
                }else if (parseInt($("#npemenuhan"+i).val()) > parseInt($("#nquantity"+i).val())) {
                    swal('Yaah :(','Jumlah Pemenuhan tidak boleh melebihi jumlah kirim !','error');
                    return false;
                }else{
                    return true;
                }
            }
        }
    });

    /*----------  KONDISI SETELAH MENEKAN TOMBOL SIMPAN  ----------*/    

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });
</script>