<?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-3">Pengirim</label>    
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2">
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
                                <input type="text" name="iretur" id="iretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dretur" name="dretur" class="form-control date"  required="" readonly value="<? echo date("d-m-Y");?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2">
                                <?php if ($tujuan) {
                                    foreach ($tujuan as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>  
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-md-3">
                            <select name="ireferensi" id="ireferensi" class="form-control select2" onchange="getitem();">
                            </select>
                        </div>
                        <div class="col-sm-8">
                            <textarea id= "eremarkh" name="eremarkh" class="form-control"></textarea>
                        </div>
                    </div>                   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <!-- <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button> -->
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;              
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value ="0">
                    <input type="hidden" name="jml_item" id="jml_item" value ="0">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="row">
        <div class="col-sm-11">
            <h3 class="box-title m-b-0">Detail Barang</h3>
        </div>
        <div class="col-sm-1" style="text-align: right;">
            <?= $doc; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 3%;">No</th>
                            <th class="text-center" style="width: 25%;">Kode</th>
                            <th class="text-center" style="width: 35%;">Barang</th>
                            <th class="text-center" style="width: 10%;">Qty Retur</th>
                            <th class="text-center" style="width: 10%;">Qty Terima</th>
                            <th class="text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function () {
        $('#iretur').mask('SSSS-0000-0000S');
        $('.select2').select2();
        showCalendar('.date');
        number();

        $('#ireferensi').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/referensi'); ?>',
            dataType: 'json',
            delay: 250,  
            data: function (params) {
                    var query = {
                        q: params.term,
                        pengirim : $('#itujuan').val(),
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

    $('#ibagian, #dretur').change(function(event) {
        number();
    });
    
    $( "#idocument" ).keyup(function() {
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

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dretur').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iretur').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });
    
    var i = $('#jml').val();
    function samadenganqty(qtyset,i){
        // iih++;
        // alert(qtywip+ '-'+i);
        $('.qtyset'+i).val(qtyset);
    }
    
    function samadenganmark(erset,i){
        // i++;
        $('.erset'+i).val(erset);
    }

    function getitem(){
        
        var idreff = $('#ireferensi').val();
        var ipengirim = $('#ibagian').val();

            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                    'ipengirim' : ipengirim,
                },
                url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
                dataType: "json",
                success: function (data) {  

                    //$('#ijenis').val(data['datahead']['id_jenis_barang_keluar']);
                    
                    $('#jml').val(data['jmlitem']);
                    $("#tabledatax tbody").remove();
                    $("#detail").attr("hidden", false);

                    group = "";

                    var dref =  data['datahead']['d_document'];
                    $("#dreferensi").val(dref);
                    i = 0;
                    for (let a = 0; a < data['jmlitem']; a++) {
                        i++;
                        //var no = a+1;
                        //count=$('#tabledatax tr').length;   
                        
                        var idproduct   = data['dataitem'][a]['id_product_wip'];
                        var newRow      = $("<tr>");
                        var cols        = "";
                        var cols1       = "";
                        if(group == ""){
                            cols1 += '<td colspan="6"><input type="text" id="iproduct'+a+'" class="form-control input-sm" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly></td>';
                            //cols1 += '<td><input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" value="'+data['dataitem'][a]['n_quantity_retur']+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="validasi('+a+');" ></td>';
                        } else {
                            if(group != idproduct){
                                cols1 += '<td colspan="5"><input type="text" id="iproduct'+a+'" class="form-control input-sm" name="iproduct'+a+'" value="'+data['dataitem'][a]['i_product_wip']+' - '+data['dataitem'][a]['e_product_wipname']+' - '+data['dataitem'][a]['e_color_name']+'" readonly><input type="hidden" id="idproduct'+a+'" class="form-control" name="idproduct'+a+'" value="'+data['dataitem'][a]['id_product_wip']+'" readonly></td>';
                                //cols1 += '<td><input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantitywip'+a+'" name="nquantitywip'+a+'" value="'+data['dataitem'][a]['n_quantity_retur']+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="validasi('+a+');" ></td>';
                            }
                        }
                        newRow.append(cols1);
                        $("#tabledatax").append(newRow);
                        group = idproduct;       
                        
                        var material = htmlentity(data['dataitem'][a]['e_material_name']);
                        
                        var newRow = $("<tr>");
                        cols += '<td class="text-center">'+i+'<input type="hidden" name="idproductwip[]" value="'+data['dataitem'][a]['id_product_wip']+'"><input type="hidden" name="iditem[]" id="iditem'+i+'" value="'+data['dataitem'][a]['id']+'" ></td>';
                        cols += '<td><input type="text" id="epanel'+i+'" class="form-control input-sm" value="'+data['dataitem'][a]['i_panel']+' - '+data['dataitem'][a]['bagian']+'" readonly></td>';
                        cols += '<td><input type="text" id="imaterial'+i+'" class="form-control input-sm" name="imaterial'+i+'" value="'+data['dataitem'][a]['i_material']+' - '+material+'" readonly>';
                        cols += '<input type="hidden" class="idmaterial" name="idmaterial[]" id="idmaterial'+i+'" value="'+data['dataitem'][a]['id_material']+'">';
                        cols += '<input type="hidden" class="idpanel" name="idpanel[]" id="idpanel'+i+'" value="'+data['dataitem'][a]['id_panel_item']+'"></td>';
                        cols += '<td><input readonly class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantityretur'+i+'" name="nquantityretur[]" value="'+data['dataitem'][a]['n_qty_retur']+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' onkeyup="validasi('+i+');" ></td>';
                        cols += '<td><input class="form-control qty input-sm text-right" autocomplete="off" type="text"  id="nquantityterima'+i+'" name="nquantityterima[]" value="'+data['dataitem'][a]['n_qty_retur']+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="validasi('+i+');" ></td>';
                        cols += '<td><input class="form-control input-sm" type="text" name="edesc[]" id="edesc'+i+'" value="" placeholder="Isi keterangan jika ada!"></td></tr>';
                        newRow.append(cols);
                        $("#tabledatax").append(newRow);
                    }

                function formatSelection(val) {
                    return val.name;
                }

                $("#tabledatax").on("click", ".ibtnDel", function (event) {
                    $(this).closest("tr").remove();       
                });
                //max_tgl();
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function hetang(x, idproductwip) {

        var n_max = 0;

        var n_qty_retur = parseFloat($('#n_qty_retur_'+idproductwip+'_'+x).val());
        var n_qty_penyusun = parseFloat($('#n_qty_penyusun_'+idproductwip+'_'+x).val());

        var n_quantity_akhir = n_qty_retur / n_qty_penyusun;

        if (isFinite(n_quantity_akhir)  ) {
            $('#nquantity_'+idproductwip+'_'+x).val(n_quantity_akhir);
            $('#nquantity_tmp_'+idproductwip+'_'+x).val(n_quantity_akhir);
        }else{
            $('#nquantity_'+idproductwip+'_'+x).val(0);
            $('#nquantity_tmp_'+idproductwip+'_'+x).val(0);
        }

        n_max = Math.ceil(Math.max.apply(Math,$("input[name='nquantity_tmp_"+idproductwip+"[]']").map(function(){return parseFloat($(this).val());}).get()));
        $('#n_qty_kekurangan_'+idproductwip+'_'+x).val(n_max * n_qty_penyusun - n_qty_retur );


        for(var i = 1; i<= $("input[name='nquantity_tmp_"+idproductwip+"[]']").length ; i++) {
            var n_qty_retur = parseFloat($('#n_qty_retur_'+idproductwip+'_'+i).val());
            var n_qty_penyusun = parseFloat($('#n_qty_penyusun_'+idproductwip+'_'+i).val());

            var n_quantity_akhir = n_qty_retur / n_qty_penyusun;

            if (isFinite(n_quantity_akhir)  ) {
                $('#nquantity_'+idproductwip+'_'+i).val(n_quantity_akhir);
                $('#nquantity_tmp_'+idproductwip+'_'+i).val(n_quantity_akhir);
            }else{
                $('#nquantity_'+idproductwip+'_'+i).val(0);
                $('#nquantity_tmp_'+idproductwip+'_'+i).val(0);
            }

            n_max = Math.ceil(Math.max.apply(Math,$("input[name='nquantity_tmp_"+idproductwip+"[]']").map(function(){return parseFloat($(this).val());}).get()));
            $('#n_qty_kekurangan_'+idproductwip+'_'+i).val(n_max * n_qty_penyusun - n_qty_retur );
        }

        console.log(n_max + " " + n_qty_penyusun + " " + n_qty_retur + " " + $("input[name='nquantity_tmp_"+idproductwip+"[]']").length );
    }

    /**
     * Hapus Detail Item
     */
    
    function hapusdetail(x) {
        $("#tabledatax tbody").each(function() {
            $("tr.del"+x).remove();
        });
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
    });

    function validasi(id){

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
                $(this).find("td nquantityset").each(function() {
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