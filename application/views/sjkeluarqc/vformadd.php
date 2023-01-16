<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-sm-3">Tujuan Makloon</label>
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
                                <input type="text" name="idocument" id="isj" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="itype" id="itype" class="form-control select2" required="">
                                <?php if ($type) {
                                    foreach ($type as $row):?>
                                        <option value="<?= $row->id;?>">
                                            <?= $row->e_type_makloon_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4">Partner</label>
                        <label class="col-sm-2">Nomor Forecast</label>
                        <label class="col-md-2">Tanggal Forecast</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="iforecast" name="iforecast" class="form-control input-sm" onkeyup="gede(this);" maxlength="18" required="" placeholder="No Forecast Harus Diisi!">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dforecast" required="" name="dforecast" class="form-control input-sm tgl" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" > <i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
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
                        <th class="text-center" style="width: 45%;">Nama Barang</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" style="width: 5%;">Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

    /**
     * Load Saat Document Ready
     */

    $(document).ready(function () {
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.date',0);
        showCalendar('.tgl',null,0);
        number();

        $('#itype').select2({
            placeholder: 'Type Makloon',
        }).change(function() {
            $('#ipartner').val("");
            $('#ipartner').html("");
        });

        $('#ipartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/partner/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                        itype : $('#itype').val(),
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

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $( "#ddocument" ).change(function() {
        number();
    });

    /**
     * Cek Kode Sudah Ada
     */

    $( "#isj" ).keyup(function() {
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

    /**
     * Input Kode Manual
     */

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#isj").attr("readonly", false);
        }else{
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /**
     * Running Number
     */

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
                $('#isj').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    /**
     * Tambah Item
     */

    var i = $('#jml').val();
    $("#addrow").on("click", function () {
        i++;
        $("#jml").val(i);
        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><select data-nourut="${i}" id="idproduct${i}" class="form-control input-sm" name="idproduct${i}" ></select></td>`;
        cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#idproduct'+ i).select2({
            placeholder: 'Cari Kode / Nama Barang Jadi',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q         : params.term,
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
            var z = $(this).data('nourut');
            var ada = true;
            for(var x = 1; x <= $('#jml').val(); x++){
                if ($(this).val()!=null) {
                    if((($(this).val()) == $('#idproduct'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }else{
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

    /**
     * Validasi Simpan Data
     */

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
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()=='0') {
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
</script>