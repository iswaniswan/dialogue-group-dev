<style type="text/css">
    .font{
        font-size: 16px;
        background-color: #e1f1e4;
    }
</style>
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
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Perkiraan Kembali</label>
                        <label class="col-md-2">Tipe Makloon</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" required="" class="form-control select2">
                                <?php if ($bagian) {
                                    foreach ($bagian->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>" <?php if ($key->i_bagian == $data->i_bagian) {?> selected <?php } ?>><?= $key->e_bagian_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="isjold" id="isjold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" required="" id="isj" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="15" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="note">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" required="" id="ddocument" class="form-control input-sm date" value="<?= $data->d_document;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="destimate" required="" id="destimate" class="form-control input-sm tgl" value="<?= $data->d_estimate;?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="idtype" required="" id="idtype" class="form-control select2" data-placeholder="Pilih Tipe Makloon">
                                <?php if ($type) {
                                    foreach ($type->result() as $key) { ?>
                                        <option value="<?= $key->id;?>" <?php if ($key->id == $data->id_type_makloon) {?> selected <?php } ?>><?= $key->e_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-md-3">No. Dokumen Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <label class="col-md-4">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="idpartner" id="idpartner" required="" class="form-control input-sm select2">
                                <option value="<?= $data->id_supplier;?>"><?= $data->e_supplier_name;?></option>
                            </select>
                            <input type="hidden" name="itypepajak" id="itypepajak" value="<?= $data->i_type_pajak;?>">
                            <input type="hidden" name="ndiskon" id="ndiskon" value="<?= $data->n_diskon;?>">
                        </div>
                        <div class="col-sm-3">
                            <select type="text" name="idreff" required="" id="idreff" class="form-control input-sm select2">
                                <option value="<?= $data->id_document_reff;?>"><?= $data->i_referensi;?></option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreff" required="" id="dreff" class="form-control input-sm" placeholder="Tanggal Referensi" value="<?= $data->d_referensi;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <textarea type="text" id="eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
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
                     <span class="notekode"><b>Note : Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!</b></span>
                </div>           
            </div>
        </div>
    </div>
</div>
<?php $i = 0; if ($datadetail) {?>
<div class="white-box" id="detail">
    <h3 class="box-title m-b-0">Detail Barang</h3>
    <div class="table-responsive">
        <table id="tabledatay" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
            <thead>
                <tr>
                    <th class="text-center" width="3%">No</th>
                    <th class="text-center" width="8%">Kode</th>
                    <th class="text-center" width="25%">Nama Barang</th>
                    <th class="text-center" width="10%">Satuan</th>
                    <th class="text-center" width="12%">Jml Permintaan</th>
                    <th class="text-center" width="8%">Jml Sisa</th>
                    <th class="text-center" width="8%">Jml Kirim</th>
                    <th class="text-center" width="18%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $z = 0; $group = "";
                        foreach ($datadetail as $key) {
                            if($group!=$key->id_material){
                                $z++;
                            } 
                            if($group==""){ ?>
                                <tr class='tdna'>
                                    <td class="text-center"><b><?=$z;?></b></td>
                                    <td><input class="form-control input-sm" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $key->i_material;?>"></td>
                                    <td><input class="form-control input-sm" readonly type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>" value="<?= $key->e_material_name;?>"></td>
                                    <td><input readonly class="form-control input-sm" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>" value="<?= $key->e_satuan_name;?>"></td>
                                    <td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysemua<?=$i;?>" name="nquantitysisasemua<?=$i;?>" value="<?= $key->n_quantity_reff;?>"></td>
                                    <td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa<?=$i;?>" name="nquantitysisa<?=$i;?>" value="<?= $key->n_quantity_sisa_reff;?>"></td>
                                    <td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $key->n_quantity;?>" placeholder="0" onkeyup="angkahungkul(this); cekqty(<?=$i;?>); hetang(this.value,<?= $key->id_material;?>)"></td>
                                    <td></td>
                                </tr>
                                <tr class="font"><td colspan="8"><b>List Detail Barang</b></td></tr>
                                <?php 
                            }else{
                                if($group!=$key->id_material){?>
                                    <tr class='tdna'>
                                        <td class="text-center"><b><?=$z;?></b></td>
                                        <td><input class="form-control input-sm" readonly type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $key->i_material;?>"></td>
                                        <td><input class="form-control input-sm" readonly type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>" value="<?= $key->e_material_name;?>"></td>
                                        <td><input readonly class="form-control input-sm" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>" value="<?= $key->e_satuan_name;?>"></td>
                                        <td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysemua<?=$i;?>" name="nquantitysisasemua<?=$i;?>" value="<?= $key->n_quantity_reff;?>"></td>
                                        <td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa<?=$i;?>" name="nquantitysisa<?=$i;?>" value="<?= $key->n_quantity_sisa_reff;?>"></td>
                                        <td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $key->n_quantity;?>" placeholder="0" onkeyup="angkahungkul(this); cekqty(<?=$i;?>); hetang(this.value,<?= $key->id_material;?>)"></td>
                                        <td></td>
                                    </tr>
                                    <tr class="font"><td colspan="8"><b>List Detail Barang</b></td></tr>
                                <?php }
                            }
                            $group = $key->id_material;?>
                            <tr>
                                <td class="text-center">#</td>
                                <td>
                                    <input type="hidden" id="idmaterial<?=$i;?>" name="idmaterial<?=$i;?>" value="<?= $key->id_material;?>">
                                    <input type="hidden" id="nqty<?=$i;?>" name="nqty<?=$i;?>" value="<?= $key->n_quantity;?>">
                                    <input type="hidden" id="idmateriallist<?=$i;?>" name="idmateriallist<?=$i;?>" value="<?= $key->id_material_list;?>">
                                    <input type="hidden" id="vunitprice<?=$i;?>" name="vunitprice<?=$i;?>" value="<?= $key->v_unitprice;?>">
                                    <input type="hidden" id="vunitpricelist<?=$i;?>" name="vunitpricelist<?=$i;?>" value="<?= $key->v_unitprice_list;?>">
                                    <input class="form-control input-sm" readonly type="text" id="imateriallist<?=$i;?>" name="imateriallist<?=$i;?>" value="<?= $key->i_material_list;?>">
                                </td>
                                <td><input class="form-control input-sm" readonly type="text" id="emateriallist<?=$i;?>" name="emateriallist<?=$i;?>" value="<?= $key->e_material_list;?>"></td>
                                <td><input readonly class="form-control input-sm" type="text" id="esatuanlist<?=$i;?>" name="esatuanlist<?=$i;?>" value="<?= $key->e_satuan_list;?>"></td>
                                <td><input class="form-control input-sm text-right" type="text" readonly id="nqtylistsemua<?=$i;?>" name="nqtylistsemua<?=$i;?>" value="<?= $key->n_quantity_list_reff;?>"></td>
                                <td><input class="form-control input-sm text-right" type="text" readonly id="nqtylistsisa<?=$i;?>" name="nqtylistsisa<?=$i;?>" value="<?= $key->n_quantity_list_sisa_reff;?>"></td>
                                <td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nqtylist<?=$i;?>" name="nqtylist<?=$i;?>" onkeyup="angkahungkul(this); cekjml(<?=$i;?>);" value="<?= $key->n_quantity_list;?>"></td>
                                <td><input class="form-control input-sm" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>" value="<?= $key->e_remark;?>" placeholder="Isi keterangan jika ada!"></td>
                            </tr>
                        <?php
                    $i++; } ?>
            </tbody>
        </table>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="<?= $i ;?>">
<?php } ?>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>

    /*----------  LOAD SAAT DOKUMEN READY  ----------*/
    
    $(document).ready(function () {
        $('#isj').mask('SS-0000-000000S');
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
            $.ajax({
                type: "post",
                data: {
                    'idsupplier' : $(this).val(),
                },
                url: '<?= base_url($folder.'/cform/detailsupplier'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#itypepajak').val(data.i_type_pajak);
                    $('#ndiskon').val(data.n_diskon);
                },
                error: function () {
                    swal('Error :(');
                }
            });
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
            
            $("#tabledatay").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#tabledatay tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id' : $(this).val(),
                },
                url: '<?= base_url($folder.'/cform/detailreferensi'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['detail']!=null && data['data']!=null) {
                        $('#dreff').val(data['data']['d_document']);
                        $('#jml').val(data['detail'].length);
                        var no     = 1;
                        var group  = "";
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols        = "";
                            var cols        = "";
                            var cols1       = "";
                            var cols2       = "";
                            var newRow      = $("<tr class='tdna'>");
                            var newRow1     = $("<tr class='font'>");
                            if(group==""){
                                cols += '<td class="text-center">'+(no)+'</td>';
                                cols += '<td><input class="form-control input-sm" readonly type="text" id="iproduct'+x+'" name="iproduct'+x+'" value="'+data['detail'][x]['i_material']+'"></td>';
                                cols += '<td><input class="form-control input-sm" readonly type="text" id="eproduct'+x+'" name="eproduct'+x+'" value="'+data['detail'][x]['e_material_name']+'"></td>';
                                cols += '<td><input readonly class="form-control input-sm" type="text" id="ecolor'+x+'" name="ecolor'+x+'" value="'+data['detail'][x]['e_satuan_name']+'"></td>';
                                cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysemua'+x+'" name="nquantitysisasemua'+x+'" value="'+data['detail'][x]['n_quantity']+'"></td>';
                                cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa'+x+'" name="nquantitysisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                                cols += '<td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nquantity'+x+'" name="nquantity'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'" placeholder="0" onkeyup="angkahungkul(this); cekqty('+x+'); hetang(this.value,'+data['detail'][x]['id_material']+')"><input type="hidden" id="nqtysisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                                cols += '<td></td></tr>';
                                cols2 += '<td colspan="8"><b>List Detail Barang</b></td></tr>';
                            }else{
                                if(group!=data['detail'][x]['id_material']){
                                    var newRow      = $("<tr class='tdna'>");
                                    no++;
                                    cols += '<td class="text-center">'+(no)+'</td>';
                                    cols += '<td><input class="form-control input-sm" readonly type="text" id="iproduct'+x+'" name="iproduct'+x+'" value="'+data['detail'][x]['i_material']+'"></td>';
                                    cols += '<td><input class="form-control input-sm" readonly type="text" id="eproduct'+x+'" name="eproduct'+x+'" value="'+data['detail'][x]['e_material_name']+'"></td>';
                                    cols += '<td><input readonly class="form-control input-sm" type="text" id="ecolor'+x+'" name="ecolor'+x+'" value="'+data['detail'][x]['e_satuan_name']+'"></td>';
                                    cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysemua'+x+'" name="nquantitysisasemua'+x+'" value="'+data['detail'][x]['n_quantity']+'"></td>';
                                    cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa'+x+'" name="nquantitysisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                                    cols += '<td><input class="form-control input-sm text-right" autocomplete="off" type="text" id="nquantity'+x+'" name="nquantity'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'" placeholder="0" onkeyup="angkahungkul(this); cekqty('+x+'); hetang(this.value,'+data['detail'][x]['id_material']+')"><input type="hidden" id="nqtysisa'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                                    cols += '<td></td></tr>';
                                    cols2 += '<td colspan="8"><b>List Detail Barang</b></td></tr>';
                                }
                            }
                            newRow.append(cols);
                            newRow1.append(cols2);
                            $("#tabledatay").append(newRow);
                            $("#tabledatay").append(newRow1);
                            group = data['detail'][x]['id_material'];
                            var newRow2 = $("<tr>");
                            cols1 += '<td class="text-center">#</td>';
                            cols1 += '<td><input type="hidden" id="idmaterial'+x+'" name="idmaterial'+x+'" value="'+data['detail'][x]['id_material']+'">';
                            cols1 += '<input type="hidden" id="nqty'+x+'" name="nqty'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'">';
                            cols1 += '<input type="hidden" id="idmateriallist'+x+'" name="idmateriallist'+x+'" value="'+data['detail'][x]['id_material_list']+'">';
                            cols1 += '<input type="hidden" id="vunitprice'+x+'" name="vunitprice'+x+'" value="'+data['detail'][x]['v_unitprice']+'">';
                            cols1 += '<input type="hidden" id="vunitpricelist'+x+'" name="vunitpricelist'+x+'" value="'+data['detail'][x]['v_unitprice_list']+'">';
                            cols1 += '<input class="form-control input-sm" readonly type="text" id="imateriallist'+x+'" name="imateriallist'+x+'" value="'+data['detail'][x]['i_material_list']+'"></td>';
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
                    swal('Ada kesalahan :(');
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

    function hetang(qty,id){
        for(var i = 0; i < $('#jml').val(); i++){
            if(id == $("#idmaterial"+i).val()){
                if(qty==''){
                    qty = 0;
                }
                $('#nqty'+i).val(qty);
            }
        }
    }     

    /*----------  NOMOR DOKUMEN  ----------*/    

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
                    $('#isj').val(data);
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
                if (data==1 && ($('#isj').val() != $('#isjold').val())) {
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

    /*----------  UPDATE STATUS DOKUMEN ----------*/    

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    /*----------  UPDATE NO DOKUMEN SAAT TANGGAL DOKUMEN DAN BAGIAN PEMBUAT DIRUBAH  ----------*/
    
    $('#ddocument, #ibagian').change(function(event) {
        number();
    });

    /*----------  VALIDASI SAAT MENEKAN TOMBOL SIMPAN  ----------*/
    
    $('#submit').click(function(event) {
        var d1 = splitdate($('#ddocument').val());
        var d2 = splitdate($('#dreff').val());
        if ((d1!=null || d1!='') && (d2!=null || d2!='')) {
            if (d1<d2) {
                swal('Maaf','Tanggal Dokumen Tidak Boleh Kurang Dari Tanggal Referensi!!!','error');
                $('#ddocument').val('');
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
                alert($("#nqtylist"+i).val()+'|'+$("#nqty"+i).val());
                if(($("#nqtylist"+i).val()=='' || $("#nqtylist"+i).val()==null || $("#nqtylist"+i).val()==0) && $("#nqty"+i).val()=='' || $("#nqty"+i).val()==null || $("#nqty"+i).val()==0){
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