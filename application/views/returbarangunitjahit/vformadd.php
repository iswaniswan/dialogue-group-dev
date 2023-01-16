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
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4">Partner</label>
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
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                         <div class="col-sm-4">
                            <select name="ipartner" id="ipartner" class="form-control select2" required="">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-md-3">Nomor Referensi</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select id="ireff" required="" class="form-control select2">
                            </select>
                            <input type="hidden" name="ireff" id="reff">
                            <input type="hidden" name="dreff" id="dreff">
                        </div>
                        <div class="col-sm-9">
                            <textarea type="text" name="eremarkh" placeholder="Isi keterangan jika ada!!!" class="form-control input-sm" maxlength="250"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-3">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="10%">Kode</th>
                        <th class="text-center" width="35%">Nama Barang</th>
                        <th class="text-center" width="12%">Warna</th>
                        <th class="text-center" width="10%">Jml</th>
                        <th class="text-center" width="10%">Jml Retur</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>

<script>
    $(document).ready(function () {
        $('#isj').mask('SS-0000-000000S');
        $('.select2').select2();
        number();
        /*Tidak boleh lebih dari hari ini*/
        showCalendar('.date',null,0);

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
            $("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);
            $("#ireff").val("");
            $("#ireff").html("");
        });;

        $('#ireff').select2({
            placeholder: 'Cari No Referensi',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ipartner : $('#ipartner').val()
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
            var ireff  = $(this).val().split("|")[0];
            var dreff  = $(this).val().split("|")[1];
            $('#reff').val(ireff);
            $('#dreff').val(dreff);
            var dto   = splitdate(dreff);
            var dfrom = splitdate($('#ddocument').val());
            if (dfrom!=null && dto!=null) {   
                if (dfrom<dto) {
                    swal('Yaah :(','Tgl Dok tidak boleh lebih kecil dari tgl Ref '+dreff+'!!!','error');
                    $('#ddocument').val(dreff);
                    number();
                }
            }

            $("#tabledatax tr:gt(0)").remove();       
            $("#jml").val(0);
            $.ajax({
                type: "post",
                data: {
                    'id'  : ireff,
                },
                url: '<?= base_url($folder.'/cform/getdetailreff'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data['detail']!=null) {
                        $('#tabledatax').attr('hidden', false);
                        $('#jml').val(data['detail'].length);
                        for (let i = 0; i < data['detail'].length; i++) {
                            var cols = "";
                            var newRow = $("<tr>");
                            cols += '<td class="text-center">'+(i+1)+'</td>';
                            cols += '<td><input type="text" class="form-control input-sm" readonly value="'+data['detail'][i]['i_product_wip']+'"><input hidden class="form-control input-sm" readonly name="idproduct'+i+'" value="'+data['detail'][i]['id_product_wip']+'"></td>';
                            cols += '<td><input class="form-control input-sm" readonly value="'+data['detail'][i]['e_product_wipname']+'"></td>';
                            cols += '<td><input readonly class="form-control input-sm" value="'+data['detail'][i]['e_color_name']+'"></td>';
                            cols += '<td><input class="form-control input-sm text-right" id="quantity'+i+'" readonly value="'+data['detail'][i]['n_quantity']+'"></td>';
                            cols += '<td><input class="form-control input-sm text-right" autocomplete="off" id="nquantity'+i+'" name="nquantity'+i+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' value="0" onkeyup="angkahungkul(this); ceksaldo('+i+');"></td>';
                            cols += '<td><input class="form-control input-sm" placeholder="Isi keterangan jika ada!" name="eremark'+i+'" value=""></td>';
                            newRow.append(cols);
                            $("#tabledatax").append(newRow);
                        }
                    }
                },
                error: function () {
                    swal('Data kosong :)');
                }
            });            
        });
    });

    /**
     * Rubah Tanggal Dokumen
     */
    
    $('#ddocument').change(function(event) {
        var dto   = splitdate($(this).val());
        var dfrom = splitdate($('#dreff').val());
        var dreff = $('#dreff').val();
        if (dfrom!=null && dto!=null) {   
            if (dfrom>dto) {
                swal("Yaah :(","Tgl Dok tidak boleh lebih kecil dari tgl Ref "+$('#dreff').val()+"!!!","error");
                $('#ddocument').val('');
            }
        }
        number();
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
     * Update Status Kirim ke Atasan
     */

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    /**
     * Cek Retur Tidak Melebihi Jml
     */

    function ceksaldo(i) {
        if (parseFloat($('#nquantity'+i).val()) > parseFloat($('#quantity'+i).val())) {
            swal(':(','Jml retur tidak boleh lebih dari jml yang ada!!!','error');
            $('#nquantity'+i).val($('#quantity'+i).val());
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);

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
     * Cek Submit
     */
    
    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            var qty = 0;
            for (var i = 0; i < $('#jml').val(); i++) {
                qty += parseInt($('#nquantity'+i).val());
            }
            if (qty <= 0) {
                swal(':(','Jumlah retur tidak boleh 0 semua :(','error');
                return false;
            }else{
                return true;
            }
        }
    })
</script>