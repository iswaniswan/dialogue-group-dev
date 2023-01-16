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
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Nomor Referensi</label>
                        <label class="col-md-2">Tanggal Referensi</label>
                        <!-- <label class="col-md-2">No. Schedule</label> -->
                        <div class="col-sm-3">
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
                                <input type="text" name="idocument" id="ibonm" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="16" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ddocument" id="ddocument" class="form-control input-sm date" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="ispbb" id="ispbb" required="" class="form-control input-sm select2"></select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dspbb" id="dspbb" class="form-control input-sm" value="" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-3">Permintaan Dari</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="hidden" name="itujuan" id="itujuan" class="form-control input-sm" value="" readonly>
                            <input type="text" name="etujuan" id="etujuan" class="form-control input-sm" value="" readonly>
                        </div>
                        <div class="col-sm-9">
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
                    <th class="text-center" style="width: 30%;">Nama Material</th>
                    <th class="text-center" style="width: 8%;">Satuan</th>
                    <th class="text-center" style="width: 8%;">Jml SPBB</th>
                    <th class="text-center" style="width: 10%;">Panjang Kain</th>
                    <th class="text-center" style="width: 10%;">Sisa Panjang Kain</th>
                    <th class="text-center" style="width: 8%;">Jml Keluar</th>
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
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

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
                $('#ibonm').val(data);
            },
            error: function () {
                swal('Error :(');
            }
        });
    }

    $( "#ibonm" ).keyup(function() {
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

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#submit').click(function(event) {
        if($("#jml").val()==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            for (var i = 0; i < $("#jml").val(); i++) {
                if($("#npemenuhan"+i).val()=='' || $("#npemenuhan"+i).val()==null || $("#npemenuhan"+i).val()==0){
                    swal('Jumlah Pemenuhan Harus Lebih Besar Dari 0!');
                    return false;
                }
            }
        }
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ibonm").attr("readonly", false);
        }else{
            $("#ibonm").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $('#ddocument, #ibagian').change(function(event) {
        number();
    });

    $(document).ready(function () {
        $('#ibonm').mask('SSS-0000-000000S');
        number();
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',0);
        $('#ispbb').select2({
            placeholder: 'Pilih Nomor Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/dataspbb'); ?>',
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
        }).change(function() {
            $("#tabledatax").attr("hidden", false);
            $("#detail").attr("hidden", false);
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'ispbb': $(this).val()
                },
                url: '<?= base_url($folder.'/cform/dataspbbdetail'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['head']!=null && data['detail']!=null) {
                        $('#dspbb').val(data['head']['dspbb']);
                        $('#itujuan').val(data['head']['i_bagian']);
                        $('#etujuan').val(data['head']['e_bagian_name']);
                        $('#jml').val(data['detail'].length);
                        var group = '';
                        var no = 0;
                        for (let x = 0; x < data['detail'].length; x++) {
                            no++;
                            var cols   = "";
                            var cols1  = "";
                            var newRow      = $("<tr class='tdna'>");
                            if(group==""){
                                cols1 += '<td colspan="9">'+data['detail'][x]['e_product_wipname']+' - '+data['detail'][x]['e_color_name']+'</td>';
                            }else{
                                if(group!=data['detail'][x]['id_product']){
                                    cols1 += '<td colspan="9">'+data['detail'][x]['e_product_wipname']+' - '+data['detail'][x]['e_color_name']+'</td>'
                                no = 1; }
                            }
                            newRow.append(cols1);
                            $("#tabledatax").append(newRow);
                            group = data['detail'][x]['id_product'];
                            var newRow = $("<tr>");

                            cols += '<td class="text-center">'+no+'</td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="imaterial'+x+'" name="imaterial'+x+'" value="'+data['detail'][x]['i_material']+'"><input type="hidden" id="idmaterial'+x+'" name="idmaterial'+x+'" value="'+data['detail'][x]['id_material']+'"><input type="hidden" id="idproduct'+x+'" name="idproduct'+x+'" value="'+data['detail'][x]['id_product']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly type="text" id="ematerialname'+x+'" name="ematerialname'+x+'" value="'+data['detail'][x]['e_material_name']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm" type="text" id="satuan'+x+'" name="satuan'+x+'" value="'+data['detail'][x]['e_satuan']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantity'+x+'" name="nquantity'+x+'" value="'+data['detail'][x]['n_quantity_sisa']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="npanjangkain'+x+'" name="npanjangkain'+x+'" value="'+data['detail'][x]['n_panjang_kain_sisa']+'"></td>';
                            cols += '<td><input class="form-control input-sm text-right" type="text" id="nsisa'+x+'" name="nsisa'+x+'" value="'+data['detail'][x]['n_panjang_kain_sisa']+'" placeholder="0" readonly></td>';
                            cols += '<td><input class="form-control input-sm text-right" type="text" id="npemenuhan'+x+'" name="npemenuhan'+x+'" value="'+data['detail'][x]['n_panjang_kain_sisa']+'" placeholder="0" onkeyup="angkahungkul(this); cekvalidasi('+x+');" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'></td>';
                            cols += '<td><input class="form-control input-sm" type="text" id="eremark'+x+'" name="eremark'+x+'" value=""></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                        }
                    }
                    max_tgl();
                },
                error: function () {
                    swal('Ada kesalahan :(');
                }
            })
        });
    });

    function cekvalidasi(i){
        nquantity = $("#npemenuhan"+i).val();
        nsisa     = $("#nsisa"+i).val();
        //alert("cek");
        if(parseFloat(nquantity)>parseFloat(nsisa)){
            swal('Jumlah Keluar Tidak Boleh Lebih Dari Sisa');
            $("#npemenuhan"+i).val(nsisa);
        }
        if(parseFloat(nquantity) == '0' || parseFloat(nquantity) == '' || parseFloat(nquantity) == null){
            swal('Jumlah Keluar Tidak Boleh Kosong atau 0');
            $("#npemenuhan"+i).val(nsisa);
        }
    }

    function max_tgl(val) {
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dspbb').value,
        });
    }

    $('#ddocument').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dspbb').value,
    });
</script>