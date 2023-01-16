<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <label class="col-md-4">Nomor Dokumen</label>
                        <label class="col-md-4">Tanggal Dokumen</label>
                        <div class="col-sm-4">
                            <input type="hidden" id="imenu" name="imenu" value="<?= $imenu;?>">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="BBM-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                                <!-- <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span> -->
                            </div>
                            <!-- <span id="notekode" class="notekode">Format : (<?= $number;?>)</span><br> -->
                            <span id="notekode" class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "ddocument" name="ddocument" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" required="" placeholder="<?=date('d-m-Y');?>"readonly >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Pengirim</label>
                        <label class="col-md-4">Nomor Referensi</label>
                        <label class="col-md-4">Tanggal Referensi</label>
                        <div class="col-sm-4">
                            <select name="ipengirim" id="ipengirim" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" disabled> 
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" id= "jreferensi" name="jreferensi">
                            <input type="text" id= "dreferensi" name="dreferensi" class="form-control input-sm" value="" required="" placeholder="<?=date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2"><i class="fa fa-save mr-2" ></i>Simpan</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
                        </div>
                        <div class="col-sm-4">
                            <button type="button" disabled="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-block btn-sm mr-2"><i class="fa fa-paper-plane-o mr-2"></i>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<input type="hidden" name="jml" id="jml" value="0">
<div class="white-box" id="detail" hidden="true">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead> 
                    <tr>
                        <th class="text-center" width="3%;">No</th>
                        <th class="text-center" width="12%;">Kode Barang</th>
                        <th class="text-center" width="25%;">Nama Barang</th>
                        <th class="text-center" width="12%;">Warna</th>
                        <th class="text-center" width="12%;">QTY Kirim</th>
                        <th class="text-center" width="12%;" hidden>Quantity Sisa</th>
                        <th class="text-center" width="12%;">QTY Terima</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        
        // $('#idocument').mask('SSS-0000-000000S');
        //memanggil function untuk penomoran dokumen
        number();

    });

    $('#ibagian, #ddocument').change(function(event) {
        number();
    });

    $(document).ready(function () {
        $('#ipengirim').select2({
            placeholder: 'Pilih Bagian Pengirim',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/bagianpengirim'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian: $('#ibagian').val(),
                        imenu: $('#imenu').val()
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
        });

        $('#ireff').select2({
            placeholder: 'Pilih Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/referensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iasal : $('#ipengirim').val(),
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
        });
    });

    //untuk me-generate running number
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
                $('#idocument').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

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
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });
    
    $("#ipengirim").change(function(){
        $('#ireff').attr("disabled", false);
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
        }
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
    });

    function getdataitem(ireff) {
        var idreff = $('#ireff').val();
        var ipengirim = $('#ipengirim').val();
            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                    'ipengirim' : ipengirim,
                },
                url: '<?= base_url($folder.'/cform/getdataitem'); ?>',
                dataType: "json",
                success: function (data) {  
                    $('#jml').val(data['jmlitem']);
                    $("#tabledatax tbody").remove();
                    $("#detail").attr("hidden", false);

                    var dref =  data['datahead']['d_document'];
                    var jref =  data['datahead']['id_jenis_barang_keluar'];
                    $("#dreferensi").val(dref);
                    $("#jreferensi").val(jref);
                    for (let a = 0; a < data['jmlitem']; a++) {
                        var no = a+1;
                        var idproduct         = data['dataitem'][a]['id_product'];
                        var iproduct          = data['dataitem'][a]['i_product_base'];
                        var eproduct          = data['dataitem'][a]['e_product_basename'];
                        var nquantity         = data['dataitem'][a]['n_quantity_product'];
                        var nquantitysisa     = data['dataitem'][a]['n_sisa'];
                        var idcolor           = data['dataitem'][a]['id_color'];
                        var icolor            = data['dataitem'][a]['i_color'];
                        var ecolor            = data['dataitem'][a]['e_color_name'];

                var cols        = "";
                var newRow = $("<tr>");
                    cols += '<td style="text-align:center;">'+no+'<input readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';     
                    cols += '<td><input readonly class="form-control input-sm" type="text" id="iproduct'+a+'" name="iproduct[]" value="'+iproduct+'"><input readonly type="hidden" id="idproduct'+a+'" name="idproduct[]" value="'+idproduct+'"></td>';
                    cols += '<td><input readonly class="form-control input-sm" type="text" id="eproduct'+a+'" name="eproduct'+a+'" value="'+eproduct+'"></td>'; 
                    cols += '<td><input type="hidden" id="idcolor'+a+'" name="idcolor[]" value="'+idcolor+'"><input type="hidden" id="icolorpro'+a+'" name="icolorpro[]" value="'+icolor+'"><input class="form-control input-sm" type="text" id="ecolor'+a+'" readonly name="ecolor'+a+'" value="'+ecolor+'"></td>';
                    cols += '<td><input readonly class="form-control input-sm text-right" type="text" id="nquantity'+a+'" name="nquantity[]" value="'+nquantity+'"></td>';
                    cols += '<td hidden><input readonly class="form-control input-sm text-right" type="text" id="nquantitysisa'+a+'" name="nquantitysisa[]" value="'+nquantitysisa+'"></td>';
                    cols += '<td><input class="form-control input-sm inputitem text-right" type="text" id="nquantitymasuk'+a+'" name="nquantitymasuk[]" value="'+nquantitysisa+'" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\'  onkeyup="validasi('+a+')"></td>';
                    cols += '<td><input class="form-control input-sm" type="text" id="edesc'+a+'" name="edesc[]" value="" placeholder="Isi keterangan jika ada!"></td>';

                newRow.append(cols);
                $("#tabledatax").append(newRow);
                }
                max();
                //var a = $('#jml').val();

                function formatSelection(val) {
                    return val.name;
                }

                $("#tabledatax").on("click", ".ibtnDel", function (event) {
                    $(this).closest("tr").remove();       
                });

            },
            error: function () {
                alert('Error :)');
            }
        });            
    }

    function validasi(id){
        jml=document.getElementById("jml").value;
        for(i=0;i<=jml-1;i++){
            nquantityma    =document.getElementById("nquantitysisa"+i).value;
            nquantitymasuk =document.getElementById("nquantitymasuk"+i).value;
            if(parseFloat(nquantitymasuk)>parseFloat(nquantityma)){
                swal('Quantity Masuk Tidak Boleh Lebih Dari Quantity Sisa');
                document.getElementById("nquantitymasuk"+i).value=nquantityma;
                //break;
            }
            if(/*parseFloat(nquantitymasuk) == 0 &&*/  parseFloat(nquantitymasuk) == ''){
                //swal('Quantity Tidak Boleh Kosong');
                document.getElementById("nquantitymasuk"+i).value=0;
                //break;
            }
        }
    }

   function max(){
        $('#ddocument').datepicker('destroy');
        $('#ddocument').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dreferensi').value,
        });
    }

    $('#ddocument').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dreferensi').value,
    });

    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Quantity Tidak Boleh Kosong !');
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
</script>