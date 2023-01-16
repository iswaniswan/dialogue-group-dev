<style type="text/css">
    .font{
        font-size: 16px;
        background-color: #e1f1e4;
    }
</style>
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
                        <label class="col-sm-2">Bagian Pembuat</label>
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Pengeluaran ke</label>
                        <label class="col-md-3">Jenis Pengeluaran</label>                        
                        <div class="col-sm-2">
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
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="itujuan" id="itujuan" class="form-control select2" required="">
                                <option value=""></option>
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row):?>
                                        <option value="<?= $row->id;?>">
                                            <?= $row->e_tujuan_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="ijeniskeluar" id="ijeniskeluar" class="form-control select2" required="">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3">Nomor Memo (Optional)</label>
                        <label class="col-md-2">Tanggal Memo</label>
                        <label class="col-sm-4">Partner</label>
                        <label class="col-md-3">PIC Internal</label>
                        <div class="col-sm-3">
                            <input type="text" id="imemo" name="imemo" class="form-control input-sm" onkeyup="gede(this);" maxlength="30" placeholder="No Memo boleh diisi boleh tidak..">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dmemo" name="dmemo" class="form-control input-sm tgl" value="" placeholder="Tanggal (Optional)" readonly>
                        </div>
                        <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="picinternal" id="picinternal" class="form-control select2" required="">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-8">Keterangan</label>
                        <label class="col-md-4" id="lpicek">PIC Eksternal</label>
                        <div class="col-sm-8">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"></textarea>
                        </div>
                        <div class="col-sm-4" id="dpicek">
                            <input type="text" id="piceksternal" name="piceksternal" class="form-control input-sm" onkeyup="gede(this);" placeholder="Nama PIC">
                            <span class="notekode"> * Harus Diisi Untuk Tujuan Eksternal!</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden="true"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                            <button type="button" id="addrowlist" class="btn btn-info btn-rounded btn-sm" hidden="true"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
<div class="white-box" id="detail" hidden="true">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%" hidden="true">
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
            <table id="tabledatalistx" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%" hidden="true">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center" style="width: 45%;">Nama Barang</th>
                        <th class="text-center" style="width: 10%;">Qty</th>
                        <th class="text-center">Keterangan</th>
                        <th colspan="2" class="text-center" style="width: 5%;">Act</th>
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
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.tgl',null,0);
        number();

        $('#itujuan').select2({
            placeholder: 'Eksternal / Internal',
        }).change(function(event) {
            $('#ijeniskeluar').val("");
            $('#ijeniskeluar').html("");
            $('#ipartner').val("");
            $('#ipartner').html("");
            if ($(this).val()==2) {
                $('#lpicek').attr('hidden', true);
                $('#dpicek').attr('hidden', true);
                $('#dpicek').attr('disabled', true);
            }else{
                $('#lpicek').attr('hidden', false);
                $('#dpicek').attr('hidden', false);
                $('#dpicek').attr('disabled', false);
            }
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
                        idtujuan : $('#itujuan').val(),
                        idjenis : $('#ijeniskeluar').val(),
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

        $('#ijeniskeluar').select2({
            placeholder: 'Pilih Jenis Pengeluaran',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/jenis/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                        idtujuan : $('#itujuan').val(),
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
            $('#ipartner').val("");
            $('#ipartner').html("");
            $("#tabledatalistx tr:gt(0)").remove();
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#jmllist").val(0);
            $('#detail').attr('hidden', false);
            if ($(this).val() == 1) {
                var space = " ";
                var spacex = "";
                $("#addrow").attr("hidden", true);
                $("#addrowlist").attr("hidden", false);
                $(space).insertAfter('#addrowlist');
                $(spacex).insertAfter('#addrow');
                $("#tabledatax").attr("hidden", true);
                $("#tabledatalistx").attr("hidden", false);
            }else{
                var space = " ";
                var spacex = "";
                $("#addrow").attr("hidden", false);
                $("#addrowlist").attr("hidden", true);
                $(space).insertAfter('#addrow');
                $(spacex).insertAfter('#addrowlist');
                $("#tabledatax").attr("hidden", false);
                $("#tabledatalistx").attr("hidden", true);
            }
        });

        $('#picinternal').select2({
            placeholder: 'Pilih PIC',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/pic/'); ?>',
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
        cols += `<td><select data-nourut="${i}" id="xidmaterial${i}" class="form-control input-sm" name="idmaterial${i}" ></select></td>`;
        cols += `<td><input type="text" id="nquantity${i}" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity${i}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"></td>`;
        cols += `<td><input type="text" class="form-control input-sm" name="eremark${i}" id="eremark${i}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#xidmaterial'+ i).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term,
                        ibagian : $('#ibagian').val(),
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
                    if((($(this).val()) == $('#xidmaterial'+x).val()) && (z!=x)){
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
     * Tambah Item Khusus Makloon
     */

    var iter = $('#jml').val();
    $("#addrowlist").on("click", function () {
        iter++;
        $("#jml").val(iter);
        var no     = $('#tabledatalistx .tr').length;
        var newRow = $('<tr class="tdna">');
        var cols   = "";
        var col    = "";
        cols += `<td class="text-center"><spanlistx id="snum${iter}"><b>${(no+1)}</b></spanlistx></td>`;
        cols += `<td><select data-nourut="${iter}" id="idmaterial${iter}" class="form-control input-sm" name="idmaterial${iter}" ></select></td>`;
        cols += `<td><input type="text" id="nquantity${iter}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantity${iter}" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);hetang(${iter});"></td>`;
        cols += `<td><input type="hidden" class="form-control input-sm" name="eremark${iter}" id="eremark${iter}" placeholder="Isi keterangan jika ada!"/></td>`;
        cols += `<td class="text-center"><button data-urut="${iter}" type="button" id="addlist${iter}" title="Tambah List" class="btn btn-sm btn-circle btn-info"><i class="ti-plus"></i></button></td>`;
        cols += `<td class="text-center"><button type="button" onclick="hapusdetail(${iter});" title="Delete" class="ibtnDel btn btn-sm btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatalistx").append(newRow);
        var newRow1 = $('<tr class="tr tdna del'+iter+'" id="tr'+iter+'"><td colspan="6" class="font"><b>LIST BARANG</b></td></tr>');
        $("#tabledatalistx").append(newRow1);
        $('#idmaterial'+ iter).select2({
            placeholder: 'Cari Kode / Nama Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term,
                        ibagian : $('#ibagian').val(),
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
                    if((($(this).val()) == $('#idmaterial'+x).val()) && (z!=x)){
                        swal ("kode barang tersebut sudah ada !!!!!");
                        ada = false;
                        break;
                    }
                }
                $('#idmaterialhead'+z+x).val($('#idmaterial'+z).val());
            }
            if (!ada) {                
                $(this).val('');
                $(this).html('');
            }
        });
        var nox = 0;
        $("#addlist"+ iter).on("click", function () {
            var u = $(this).data('urut');
            nox++;
            var newRow1 = $('<tr id="trdetail'+u+nox+'" class="add'+u+' del'+u+'">');
            var nomer = $('#tabledatalistx .add'+u).length;
            col += `<td class="text-right"><spanlist${u} id="snum${u}"></spanlist${u}></td>`;
            col += `<td><select data-urutan="${u}${nox}" id="idmateriallist${u}${nox}" class="form-control input-sm" name="idmateriallist[]"></select><input type="hidden" id="idmaterialhead${u}${nox}" name="idmaterialhead[]"></td>`;
            col += `<td><input type="text" id="nquantitylist${u}${nox}" class="form-control text-right input-sm inputqty" autocomplete="off" name="nquantitylist[]" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this);"><input type="hidden" id="nquantityhead${u}${nox}" name="nquantityhead[]"></td>`;
            col += `<td><input type="text" class="form-control input-sm" name="eremarklist[]" id="eremarklist${u}${nox}" placeholder="Isi keterangan jika ada!"/></td>`;
            col += `<td colspan="2" class="text-center"><button type="button" title="Delete" data-b = "${u}" class="ibtnDel btn-sm btn btn-circle btn-warning"><i class="ti-close"></i></button></td></tr>`;
            newRow1.append(col);
            if (nox>1) {
                var v = nox-1;
                if(typeof $('#idmateriallist'+u+v).val() == 'undefined'){
                    $(newRow1).insertAfter("#tabledatalistx #tr"+u);
                }else{
                    $(newRow1).insertAfter("#tabledatalistx #trdetail"+u+v)
                }
            }else{
                $(newRow1).insertAfter("#tabledatalistx #tr"+u);
            }
            $('#idmaterialhead'+u+nox).val($('#idmaterial'+u).val());
            $('#nquantityhead'+u+nox).val($('#nquantity'+u).val());

            $('#idmateriallist'+u+nox).select2({
                placeholder: 'Cari Kode / Nama Material',
                allowClear: true,
                width: "100%",
                type: "POST",
                ajax: {
                    url: '<?= base_url($folder.'/cform/product/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var query   = {
                            q       : params.term,
                            ibagian : $('#ibagian').val(),
                        }
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true
                }
            }).change(function(event) {
                var z = $(this).data('urutan');
                var ada = true;
                for(var x = 1; x <= $('#tabledatalistx .add'+u).length; x++){
                    y = String(u)+x;
                    if ($(this).val()!=null) {
                        if((($(this).val()) == $('#idmateriallist'+u+x).val()) && (z!=y)){
                            swal ("kode barang sudah ada !!!!!");
                            ada = false;
                            break;
                        }
                    }
                }
                if (!ada) {                
                    $(this).val('');
                    $(this).html('');
                }
            });
        });
    });

    function hetang(i) {
        for(var x = 1; x <= $('#tabledatalistx .add'+i).length; x++){
            $('#nquantityhead'+i+x).val($('#nquantity'+i).val());
        }
    }

    /**
     * Hapus Detail Item
     */
    
    function hapusdetail(x) {
        $("#tabledatalistx tbody").each(function() {
            $("tr.del"+x).remove();
        });
    }

    $("#tabledatalistx").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        var obj = $('#tabledatalistx tr:visible').find('spanlistx');
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
            if ($('#ijeniskeluar').val()==1) {
                $("#tabledatalistx tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val()=='' || $(this).val()==null) {
                            swal('Kode barang tidak boleh kosong!');
                            ada = true;
                        }
                    });
                    $(this).find("td .inputqty").each(function() {
                        if ($(this).val()=='' || $(this).val()==null || $(this).val()=='0') {
                            swal('Quantity Tidak Boleh Kosong Atau 0!');
                            ada = true;
                        }
                    });
                });
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
            }
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