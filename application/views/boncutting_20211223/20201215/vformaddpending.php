<style type="text/css">
    .tdna{
        font-size:16px; background-color: #ddd; font-weight: bold;
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
                        <label class="col-md-2">Bagian Pembuat</label>
                        <label class="col-md-3">No. Dokumen</label>
                        <label class="col-md-2">Tgl. Dokumen</label>
                        <label class="col-md-3">No. Dokumen Referensi</label>
                        <label class="col-md-2">Tgl. Dokumen Referensi</label>
                        <div class="col-sm-2">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>"><?= $key->e_bagian_name;?></option>
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="xidocument" readonly="" autocomplete="off" placeholder="<?= $number;?>" maxlength="20" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm date" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="irefference" id="irefference" class="form-control select2"></select>
                            <input type="hidden" name="ireferensi" id="ireferensi">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dreferensi" id="dreferensi" class="form-control input-sm" value="" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-2">Bagian Tujuan</label>
                        <label class="col-md-10">Keterangan</label>
                        <div class="col-sm-2">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <?php if ($tujuan) {
                                    foreach ($tujuan->result() as $key) { ?>
                                        <option value="<?= trim($key->i_bagian);?>"><?= $key->e_bagian_name;?></option>
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-10">
                            <textarea type="text" id= "eremark" name="eremark" maxlength="250" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
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
    <h3 class="box-title m-b-0">Detail Barang</h3>
    <div class="table-responsive">
        <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%" hidden="true">
            <thead>
                <tr>
                    <th class="text-center" style="width: 3%;">No</th>
                    <th class="text-center" style="width: 10%;">Kode</th>
                    <th class="text-center" style="width: 40%;">Nama Material</th>
                    <th class="text-center" style="width: 10%;">Satuan</th>
                    <th class="text-center" style="width: 10%;">Jml Set</th>
                    <th class="text-center" style="width: 12%;">Jml Lembar</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#submit').click(function(event) {
        if ($('#irefference').val()!='' || $('#irefference').val()!=null) {
            if($('#jml').val() == 0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for (var i = 0; i < $('#jml').val(); i++) {
                    if($("#jmllembar"+i).val()=='' || $("#jmllembar"+i).val()==null || $("#jmllembar"+i).val()==0){
                        swal('Jumlah Lembar Harus Lebih Besar Dari 0!');
                        return false;
                    }
                }
            }
        }else{
            swal('Referensi Tidak Boleh Kosong!!!');
            return false;
        }
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date',0);

        $('#irefference').select2({
            placeholder: 'Cari No Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensip/'); ?>',
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
        }).change(function() {
            $("#tabledatax").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id': $(this).val()
                },
                url: '<?= base_url($folder.'/cform/referensidetailp'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['head']!=null && data['detail']!=null) {
                        $('#xidocument').val(data['number']/*['number']*/);
                        $('#dreferensi').val(data['head']['d_document']);
                        $('#ireferensi').val(data['head']['id_reff']);
                        $('#jml').val(data['detail'].length);
                        var group = '';
                        for (let x = 0; x < data['detail'].length; x++) {
                            var cols        = "";
                            var cols1       = "";
                            var newRow      = $("<tr>");
                            if(group==""){
                                cols1 += '<td class="tdna" colspan="4"><input type="text" readonly class="form-control input-sm" value="'+data['detail'][x]['i_product_wip']+' - '+data['detail'][x]['e_product_wipname']+' '+data['detail'][x]['e_color_name']+'"/></td>';
                                cols1 += '<td class="tdna text-right"><input readonly type = "text" class="form-control input-sm text-right" value="Jml WIP"></td>';
                                cols1 += '<td class="tdna"><input type="text" name="qtywip" id="'+data['detail'][x]['id_product_wip']+'" readonly class="form-control input-sm text-right" maxlength="12" value="'+data['detail'][x]['qty']+'"></td>';
                                cols1 += '<td class="tdna"></td>';
                            }else{
                                if(group!=data['detail'][x]['id_product_wip']){
                                    cols1 += '<td class="tdna" colspan="4"><input type="text" readonly class="form-control input-sm" value="'+data['detail'][x]['i_product_wip']+' - '+data['detail'][x]['e_product_wipname']+' '+data['detail'][x]['e_color_name']+'"/></td>';
                                    cols1 += '<td class="tdna text-right"><input readonly type = "text" class="form-control input-sm text-right" value="Jml WIP"></td>';
                                    cols1 += '<td class="tdna"><input type="text" name="qtywip" id="'+data['detail'][x]['id_product_wip']+'" readonly class="form-control input-sm text-right" maxlength="12" value="'+data['detail'][x]['qty']+'"></td>';
                                    cols1 += '<td class="tdna"></td>';
                                }
                            }
                            newRow.append(cols1);
                            $("#tabledatax").append(newRow);
                            group = data['detail'][x]['id_product_wip'];
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">'+(x+1)+'</td>';
                            cols += '<td><input type="hidden" id="idproduct'+x+'" name="idproduct'+x+'" value="'+data['detail'][x]['id_product_wip']+'">';
                            cols += '<input type="hidden" id="idmaterial'+x+'" name="idmaterial'+x+'" value="'+data['detail'][x]['id_material']+'">';
                            cols += '<input class="form-control input-sm" readonly type="text" id="imaterial'+x+'" name="imaterial'+x+'" value="'+data['detail'][x]['i_material']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="ematerialname'+x+'" name="ematerialname'+x+'" value="'+data['detail'][x]['e_material_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm" type="text" id="satuan'+x+'" name="satuan'+x+'" value="'+data['detail'][x]['e_satuan_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="jmlset'+x+'" name="jmlset'+x+'" value="'+data['detail'][x]['jmlset']+'"></td>';
                            cols += '<td><input class="form-control input-sm text-right" type="text" id="jmllembar'+x+'" name="jmllembar'+x+'" placeholder="0" onkeyup="angkahungkul(this); cekjml('+x+');" value="'+data['detail'][x]['jmlset']+'"></td>';
                            cols += '<td><input class="form-control input-sm" type="text" id="eremark'+x+'" name="eremark'+x+'" value="" placeholder="Isi keterangan jika ada!">';
                            cols += '<input type="hidden" id="vset'+x+'" name="vset'+x+'" value="'+data['detail'][x]['v_set']+'">';
                            cols += '<input type="hidden" id="vtoset'+x+'" name="vtoset'+x+'" value="'+data['detail'][x]['v_toset']+'">';
                            cols += '<input type="hidden" id="qty'+x+'" name="qty'+x+'" value="'+data['detail'][x]['qty']+'">';
                            cols += '<input type="hidden" id="qtysc'+x+'" name="qtysc'+x+'" value="'+data['detail'][x]['qty']+'"></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                        }
                    }
                },
                error: function () {
                    swal('Data kosong : (');
                }
            });
        })
    });

    function cekjml(i) {
        if (parseInt($('#jmllembar'+i).val()) > parseInt($('#jmlset'+i).val())) {
            swal('Jumlah lembar tidak boleh lebih dari jumlah set!');
            $('#jmllembar'+i).val($('#jmlset'+i).val());
        }
    }

    function hetang(qty,idwip){
        for(var i = 0; i < $('#jml').val(); i++){
            if(idwip == $("#idproduct"+i).val()){           
                if(qty==''){
                    qty = 0;
                }

                $('#qty'+i).val(qty);
                if (parseInt($('#qty'+i).val()) > parseInt($('#qtysc'+i).val())) {
                    swal('Jumlah WIP tidak boleh lebih dari jumlah Schedule!');
                    $('#qty'+i).val($('#qtysc'+i).val());
                    $('#'+idwip).val($('#qtysc'+i).val());
                }

                var jmllembar = (parseInt($('#qty'+i).val()) / parseInt($('#vset'+i).val())) * parseInt($('#vtoset'+i).val());
                $('#jmlset'+i+',#jmllembar'+i).val(jmllembar.toFixed(2));
            }
        }
    } 

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });
</script>