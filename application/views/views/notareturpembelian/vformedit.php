<style type="text/css">
    .select2-results__options{
        font-size:14px !important;
    }
    .select2-selection__rendered {
      font-size: 12px;
    }
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }
</style>
<form id="cekinputan">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
                </div>
                <div class="panel-body table-responsive">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-md-3">Bagian Pembuat</label>
                            <label class="col-md-3">Nomor Dokumen</label>
                            <label class="col-md-2">Tanggal Dokumen</label>
                            <label class="col-md-4">Supplier</label>
                            <div class="col-sm-3">
                                <select name="ibagian" id="ibagian" class="form-control select2" required="">
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
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="hidden" name="id" id="id" value="<?= $data->id;?>">
                                    <input type="hidden" name="idocumentold" id="inotareturold" value="<?= $data->i_document;?>">   
                                    <input type="text" name="idocument" required="" id="inotaretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $number;?>)</span><br>
                                <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document;?>">
                            </div>
                            <div class="col-sm-4">
                                <input type="hidden" id="idsupplier" name="idsupplier" required="" value="<?= $data->id_supplier; ?>">
                                <input type="text" id="esupplier" name="esupplier" class="form-control input-sm" required="" readonly value="<?= $data->e_supplier_name; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                    <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>N O T E : </b></span><br>
                            <span class="notekode">* Item yang disimpan hanya qty retur yang lebih besar dari 0.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $i = 0; if ($datadetail) {?>   
    <div class="white-box" id="detail">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0">Detail Item</h3>
        </div>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="3%;">No</th>
                            <th class="text-center" width="12%;">No. SJ</th>
                            <th class="text-center" width="11%;">Kode</th>
                            <th class="text-center" width="25%;">Nama Barang</th>
                            <th class="text-center" width="10%;">Satuan</th>
                            <th class="text-center" width="9%;">Harga</th>
                            <th class="text-center" width="8%;">Jml Retur Gudang</th>
                            <th class="text-center" width="9%;">Jml Retur</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 0; $group = ""; foreach ($datadetail as $key) { 
                            $i++; $no++; 
                            if($group==""){ ?>
                                <tr class="pudding">
                                    <td colspan="9">Nomor Referensi : <b><?= $key->i_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal Referensi : <b><?= $key->d_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; <b>(<?= $key->e_bagian_name;?>)</b></td>
                                </tr>
                                <?php 
                            }else{
                                if($group!=$key->i_referensi){?>
                                    <tr class="pudding">
                                        <td colspan="9">Nomor Referensi : <b><?= $key->i_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal Referensi : <b><?= $key->d_referensi;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; <b>(<?= $key->e_bagian_name;?>)</b></td>
                                    </tr>
                                    <?php $no = 1; 
                                }
                            }
                            $group = $key->i_referensi;
                            ?>
                            <tr>
                                <td class="text-center"><?=$no;?></td>
                                <td>
                                    <input readonly type="text" class="form-control input-sm" value="<?= $key->i_sj_supplier;?>">
                                </td>
                                <td>
                                    <input readonly type="hidden" id="id_referensi<?=$i;?>" name="id_referensi<?=$i;?>" value="<?= $key->id_referensi;?>">
                                    <input readonly type="hidden" id="id_retur_gudang<?=$i;?>" name="id_retur_gudang<?=$i;?>" value="<?= $key->id_retur_gudang;?>">
                                    <input readonly type="hidden" id="id_material<?=$i;?>" name="id_material<?=$i;?>" value="<?= $key->id_material;?>">
                                    <input readonly type="text" class="form-control input-sm" value="<?= $key->i_material;?>">
                                </td>
                                <td><input readonly type="text" class="form-control input-sm" value="<?= $key->e_material_name;?>"></td>
                                <td><input readonly type="text" class="form-control input-sm" value="<?= $key->e_satuan_name;?>"></td>
                                <td><input type="text" readonly class="form-control input-sm text-right" name="v_price<?=$i;?>" id="v_price<?=$i;?>" value="<?= number_format($key->v_price);?>"/></td>
                                <td><input type="text" readonly class="form-control input-sm text-right" name="n_qty<?=$i;?>" id="n_qty<?=$i;?>" value="<?= $key->n_referensi;?>"/></td>
                                <td><input type="text" class="form-control input-sm text-right" name="n_qty_retur<?=$i;?>" id="n_qty_retur<?=$i;?>" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}"  value="<?= $key->n_retur;?>" onkeyup="angkahungkul(this); cekqty(<?=$i;?>);"/></td>
                                <td><input type="text" class="form-control input-sm" name="eremark<?=$i;?>" id="eremark<?=$i;?>" placeholder="Jika Ada!" value="<?= $key->e_remark;?>"></td>
                            </tr>
                            <?php 
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php }else{ ?>
        <div class="white-box">
            <div class="card card-outline-danger text-center text-dark">
                <div class="card-block">
                    <footer>
                        <cite title="Source Title"><b>Item Tidak Ada</b></cite>
                    </footer>
                </div>
            </div>
        </div>
    <?php } ?>
    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN DIBUKA  ----------*/    
    $(document).ready(function () {
        /*----------  Load Form Validation  ----------*/        
        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });

        $('#inotaretur').mask('SSS-0000-000000S');        
        $('.select2').select2();
        /*----------  Tanggal tidak boleh kurang dari hari ini!  ----------*/
        showCalendar('.date',0);  
    });

    /*----------  RUBAH NO DOKUMEN (GANTI TANGGAL & BAGIAN)  ----------*/    
    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    /*----------  RUNNING NUMBER DOKUMEN  ----------*/    
    function number() {
        if (($('#ibagian').val() == $('#ibagianold').val())) {
            $('#inotaretur').val($('#inotareturold').val());
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
                    $('#inotaretur').val(data);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }
    }   

    /*----------  UPDATE STATUS DOKUMEN  ----------*/
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    /*----------  CEKLIS NO DOKUMEN (MANUAL)  ----------*/    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#inotaretur").attr("readonly", false);
        }else{
            $("#inotaretur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN  ----------*/    
    $( "#inotaretur" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1 && ($('#inotaretur').val() != $('#inotareturold').val())) {
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

    function cekqty(i) {
        if (parseInt($('#n_qty_retur'+i).val()) > parseInt($('#n_qty'+i).val())) {
            swal("Maaf :(","Jumlah Retur tidak boleh lebih dari Jumlah SJ = "+$('#n_qty'+i).val()+" !","error");
            $('#n_qty_retur'+i).val('');
        }
    }

    /*----------  VALIDASI UPDATE DATA  ----------*/    
    $( "#submit" ).click(function(event) {
        var valid = $("#cekinputan").valid();
        if (valid) {
            ada = false;
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                var x = 0;
                for (var i = 1; i <= $('#jml').val(); i++) {
                    x += parseInt($('#n_qty_retur'+i).val());
                }
                if (x > 0) {
                    swal({
                        title: "Update Data Ini?",   
                        text: "Anda Dapat Membatalkannya Nanti",
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonColor: 'LightSeaGreen',
                        confirmButtonText: "Ya, Update!",   
                        closeOnConfirm: false 
                    }, function(){
                        $.ajax({
                            type: "POST",
                            data: $("form").serialize(),
                            url: '<?= base_url($folder.'/cform/update/'); ?>',
                            dataType: "json",
                            success: function (data) {
                                if (data.sukses==true) {
                                    swal("Sukses!", "No Dokumen : "+data.kode+", Berhasil Diupdate :)", "success"); 
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                }else if (data.sukses=='ada') {
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Sudah Ada :(", "error");   
                                }else{
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Gagal Diupdate :(", "error"); 
                                }
                            },
                            error: function () {
                                swal("Maaf", "Data Gagal Diupdate :(", "error");
                            }
                        });
                    });
                }else{
                    swal('Maaf :(','Total Jumlah Retur harus lebih besar dari 0 !','error');
                    return false;
                }
            }
        }
        return false;     
    })
</script>