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
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal Dokumen</label>
                        <label class="col-md-3">Tipe Makloon</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>"><?= $key->e_bagian_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" required="" id="isj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="note">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="idtype" required="" id="idtype" class="form-control select2" data-placeholder="Pilih Tipe Makloon">
                                <option value=""></option>
                                <?php if ($type) {
                                    foreach ($type->result() as $key) { ?>
                                        <option value="<?= $key->id;?>"><?= $key->e_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-4">Dokumen Referensi</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="idpartner" id="idpartner" required="" class="form-control input-sm select2"></select>
                        </div>
                        <div class="col-sm-4">
                            <select type="text" multiple="multiple" name="idreff[]" required="" id="idreff" class="form-control input-sm select2"></select>
                            <input type="hidden" name="dreff" id="dreff" class="form-control input-sm">
                        </div>
                        <div class="col-sm-5">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                     <span class="notekode"><b>Note : Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!</b></span>
                </div>           
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
<div class="white-box" id="detail" hidden="true">
    <div class="col-sm-12">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%" hidden="true">
                <thead>
                    <tr>
                        <th class="text-center" width="3%">No</th>
                        <th class="text-center" width="12%">Referensi</th>
                        <th class="text-center" width="9%">Kode</th>
                        <th class="text-center" width="25%">Nama Barang</th>
                        <th class="text-center" width="10%">Satuan</th>
                        <th class="text-center" width="7%">Jml</th>
                        <th class="text-center" width="7%">Sisa</th>
                        <th class="text-center" width="7%">Terima</th>
                        <th class="text-center" width="15%">Keterangan</th>
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

    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    
    $(document).ready(function () {
        $('#isj').mask('SS-0000-000000S');
        number();
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',null,0);
        /*Tidak boleh kurang dari hari ini*/
        showCalendar('.tgl',0);

        $('#idtype').change(function(event) {
            $('#idpartner').val('');
            $('#idpartner').html('');
            $('#idreff').val('');
            $('#idreff').html('');
            $("#tabledatay tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#idpartner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/partner'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q      : params.term,
                        idtype : $('#idtype').val(),
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
            $('#idreff').val('');
            $('#idreff').html('');
            $("#tabledatay tr:gt(0)").remove();
            $("#jml").val(0);
        });

        $('#idreff').select2({
            placeholder: 'Cari Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        idpartner  : $('#idpartner').val(),
                        idtype     : $('#idtype').val(),
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
            
            $.ajax({
                type: "post",
                data: {
                    'id' : $(this).val(),
                },
                url: '<?= base_url($folder.'/cform/detailreferensi'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['detail']!=null && data['data']!=null) {
                        $("#tabledatay").attr("hidden", false);
                        $("#detail").attr("hidden", false);
                        $("#tabledatay tr:gt(0)").remove();
                        $('#dreff').val(data['data']['d_date']);
                        $('#jml').val(data['detail'].length);
                        var no     = 1;
                        var group  = "";
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols    = "";
                            var cols    = "";
                            var cols1   = "";
                            var cols2   = "";
                            var newRow  = $("<tr class='tdna'>");
                            var newRow1 = $("<tr class='font'>");
                            if(group==""){
                                cols += '<td class="text-center">'+(no)+'</td>';
                                cols += '<td><input class="form-control input-sm" readonly type="text" id="idocument'+x+'" name="idocument'+x+'" value="'+data['detail'][x]['i_document']+'"></td>';
                                cols += '<td><input class="form-control input-sm" readonly type="text" id="iproduct'+x+'" name="iproduct'+x+'" value="'+data['detail'][x]['i_material']+'"></td>';
                                cols += '<td><input class="form-control input-sm" readonly type="text" value="'+data['detail'][x]['e_material_name']+'"></td>';
                                cols += '<td><input readonly class="form-control input-sm" type="text" value="'+data['detail'][x]['e_satuan_name']+'"></td>';
                                cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysemua'+x+'" name="nquantitysisasemua'+x+'" value="'+data['detail'][x]['n_quantity']+'"></td>';
                                cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa'+x+'" name="nquantitysisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                                cols += '<td><input readonly class="form-control input-sm text-right" autocomplete="off" type="text" id="nquantity'+x+'" name="nquantity'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'" placeholder="0" onkeyup="angkahungkul(this); cekqty('+x+'); hetang(this.value,'+data['detail'][x]['id_material']+','+data['detail'][x]['id_document']+')"></td>';
                                cols += '<td></td></tr>';
                                cols2 += '<td colspan="9"><b>List Detail Barang</b></td></tr>';
                            }else{
                                if(group!=data['detail'][x]['id_document']+data['detail'][x]['id_material']){
                                    var newRow = $("<tr class='tdna'>");
                                    no++;
                                    cols += '<td class="text-center">'+(no)+'</td>';
                                    cols += '<td><input class="form-control input-sm" readonly type="text" id="idocument'+x+'" name="idocument'+x+'" value="'+data['detail'][x]['i_document']+'"></td>';
                                    cols += '<td><input class="form-control input-sm" readonly type="text" id="iproduct'+x+'" name="iproduct'+x+'" value="'+data['detail'][x]['i_material']+'"></td>';
                                    cols += '<td><input class="form-control input-sm" readonly type="text" id="eproduct'+x+'" name="eproduct'+x+'" value="'+data['detail'][x]['e_material_name']+'"></td>';
                                    cols += '<td><input readonly class="form-control input-sm" type="text" id="ecolor'+x+'" name="ecolor'+x+'" value="'+data['detail'][x]['e_satuan_name']+'"></td>';
                                    cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysemua'+x+'" name="nquantitysisasemua'+x+'" value="'+data['detail'][x]['n_quantity']+'"></td>';
                                    cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa'+x+'" name="nquantitysisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                                    cols += '<td><input readonly class="form-control input-sm text-right" autocomplete="off" type="text" id="nquantity'+x+'" name="nquantity'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'" placeholder="0" onkeyup="angkahungkul(this); cekqty('+x+'); hetang(this.value,'+data['detail'][x]['id_material']+','+data['detail'][x]['id_document']+')"></td>';
                                    cols += '<td></td></tr>';
                                    cols2 += '<td colspan="9"><b>List Detail Barang</b></td></tr>';
                                }
                            }
                            newRow.append(cols);
                            newRow1.append(cols2);
                            $("#tabledatay").append(newRow);
                            $("#tabledatay").append(newRow1);
                            group = data['detail'][x]['id_document']+data['detail'][x]['id_material'];
                            var newRow2 = $("<tr>");
                            cols1 += '<td class="text-center">#</td>';
                            cols1 += '<td><input type="hidden" id="idmaterial'+x+'" name="idmaterial'+x+'" value="'+data['detail'][x]['id_material']+'">';
                            cols1 += '<input type="hidden" id="nqty'+x+'" name="nqty'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'">';
                            cols1 += '<input type="hidden" id="iddocument'+x+'" name="iddocument'+x+'" value="'+data['detail'][x]['id_document']+'">';
                            cols1 += '<input type="hidden" id="idmateriallist'+x+'" name="idmateriallist'+x+'" value="'+data['detail'][x]['id_material_list']+'">';
                            cols1 += '<input class="form-control input-sm" readonly type="text" id="idocument'+x+'" name="idocument'+x+'" value="'+data['detail'][x]['i_document']+'"></td>';
                            cols1 += '<td><input class="form-control input-sm" readonly type="text" id="imateriallist'+x+'" name="imateriallist'+x+'" value="'+data['detail'][x]['i_material_list']+'"></td>';
                            cols1 += '<td><input class="form-control input-sm" readonly type="text" id="emateriallist'+x+'" name="emateriallist'+x+'" value="'+data['detail'][x]['e_material_list']+'"></td>';
                            cols1 += '<td><input readonly class="form-control input-sm" type="text" id="esatuanlist'+x+'" name="esatuanlist'+x+'" value="'+data['detail'][x]['e_satuan_list']+'"></td>';
                            cols1 += '<td><input class="form-control input-sm text-right" type="text" readonly id="nqtylistsemua'+x+'" name="nqtylistsemua'+x+'" value="'+data['detail'][x]['n_quantity_list']+'"></td>';
                            cols1 += '<td><input class="form-control input-sm text-right" type="text" readonly id="nqtylistsisa'+x+'" name="nqtylistsisa'+x+'" value="'+data['detail'][x]['n_quantity_list_sisa']+'"></td>';
                            cols1 += '<td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nqtylist'+x+'" name="nqtylist'+x+'" onkeyup="angkahungkul(this); cekjml('+x+');" value="'+data['detail'][x]['n_quantity_list_sisa']+'"></td>';
                            cols1 += '<td><input class="form-control input-sm" type="text" id="eremark'+x+'" name="eremark'+x+'" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                            newRow2.append(cols1);
                            $("#tabledatay").append(newRow2);
                        }
                    }
                },
                error: function () {
                    swal('Ada Kesalahan :(');
                    $("#tabledatay tr:gt(0)").remove();
                    $("#jml").val(0);
                }
            })
        });
    });

    /*----------  CEK QTY HEADER  ----------*/

    function cekqty(i) {
        if (parseInt($('#nquantity'+i).val()) > parseInt($('#nquantitysisa'+i).val())) {
            swal('Maaf','Jumlah Kirim Tidak Boleh Lebih Dari Jumlah Sisa = '+$('#nquantitysisa'+i).val()+'!','error');
            $('#nquantity'+i).val($('#nquantitysisa'+i).val());
        }
    }  

    /*----------  CEK QTY ITEM  ----------*/

    function cekjml(i) {
        if (parseInt($('#nqtylist'+i).val()) > parseInt($('#nqtylistsisa'+i).val())) {
            swal('Maaf','Jumlah Kirim Tidak Boleh Lebih Dari Jumlah Sisa = '+$('#nqtylistsisa'+i).val()+'!','error');
            $('#nqtylist'+i).val($('#nqtylistsisa'+i).val());
        }
    }

    /*----------  SET VALUE DETAIL  ----------*/

    function hetang(qty,id,iddoc){
        for(var i = 0; i < $('#jml').val(); i++){
            if(id == $("#idmaterial"+i).val() && iddoc == $("#iddocument"+i).val()){
                if(qty==''){
                    qty = 0;
                }
                $('#nqty'+i).val(qty);
            }
        }
    }    

    /*----------  NOMOR DOKUMEN  ----------*/

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
                swal('Error :(');
            }
        });
    }

    /*----------  KONDISI PAS CHECKBOX DI NO DOKUMEN DIKLIK  ----------*/
    
    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#isj").attr("readonly", false);
        }else{
            $("#isj").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    /*----------  CEK NO DOKUMEN SAAT DIKETIK  ----------*/    

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
                swal('Error :(');
            }
        });
    });

    /*----------  UPDATE STATUS DOKUMEN KE WAIT APPROVE ----------*/    

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    /*----------  UPDATE NO DOKUMEN SAAT TANGGAL DOKUMEN DAN BAGIAN PEMBUAT DIRUBAH  ----------*/
    
    $('#ddocument, #ibagian').change(function(event) {
        number();
    });

    /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
    
    $('#submit').click(function(event) {
        var d1 = splitdate($('#ddocument').val());
        var d2 = splitdate($('#dreffhide').val());
        if ((d1!=null || d1!='') && (d2!=null || d2!='')) {
            if (d1<d2) {
                swal('Maaf','Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!!!','error');
                $('#ddocument').val($('#dreffhide').val());
                return false;
            }
        }else{
            swal('Maaf','Tanggal Dokumen Tidak Boleh Kosong!!!','error');
            return false;
        }
        if($("#jml").val()==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            for (var i = 0; i < $("#jml").val(); i++) {
                if($("#nqty"+i).val()=='' || $("#nqty"+i).val()==null || $("#nqty"+i).val()==0){
                    swal('Maaf :(','Jumlah Pemenuhan Harus Lebih Besar Dari 0!','error');
                    return false;
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