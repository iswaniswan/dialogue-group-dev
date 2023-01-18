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
                        <label class="col-md-4">Supplier</label>    
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" onchange="number();" required="">
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
                                <input type="text" name="inoteretur" id="inoteretur" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="17" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>     
                        <div class="col-sm-2">
                            <input id="dnoteretur" name="dnoteretur" class="form-control input-sm date" onchange="number();" value="<?= date("d-m-Y"); ?>" readonly>
                        </div>  
                         <div class="col-sm-4">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="return getreferensi();"> 
                            </select>
                        </div>      
                    </div> 
                    <div class="form-group row">  
                        <label class="col-md-6">Nomor Referensi</label> 
                        <label class="col-md-6">Total Retur</label>    
                        <div class="col-sm-6">
                            <select name="ireferensi" multiple="multiple" id="ireferensi" class="form-control select2" disabled="true"> 
                            </select>
                        </div>    
                        <div class="col-sm-2">                           
                            <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="0" readonly>
                        </div>                   
                    </div> 
                    <!--<div class="form-group row">
                        <label class="col-md-3">No Faktur Supplier</label> 
                        <label class="col-md-2">No Faktur Pembelian</label> 
                        <label class="col-md-2">Tanggal Faktur Pembelian</label> 
                        <label class="col-md-2">Total PPN</label>
                        <label class="col-md-2">Total DPP</label>
                        <div class="col-sm-3">
                            <input type="text" name="ifaksup" id="ifaksup" class="form-control" value="" readonly>   
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="ifakpajak" id="ifakpajak" class="form-control" value="" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" name="dfakpajak" id="dfakpajak" class="form-control" value="" readonly>   
                        </div>                         
                        <div class="col-sm-2">                           
                            <input type="text" name="vtotalppn" id="vtotalppn" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-2">                           
                            <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control" value="0" readonly>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-sm-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control input-sm" name="eremark" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>   
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0"> 
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 3%;">No</th>
                        <th class="text-center">Nomor Nota Retur</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Harga Total</th>
                        <!-- <th class="text-center">DPP</th>
                        <th class="text-center">PPN</th> -->
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
    $(document).ready(function () {
        $('#inoteretur').mask('SS-0000-000000S');
        $('.select2').select2();
        showCalendar('.date');
        number();
    });

    $( "#inoteretur" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode'      : $(this).val(),
                'ibagian'   : $('#ibagian').val(),
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
                'tgl'     : $('#dnoteretur').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#inoteretur').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#inoteretur").attr("readonly", false);
        }else{
            $("#inoteretur").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
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
        $("#send").attr("hidden", false);
    });

    $(document).ready(function () {
        $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
        allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/supplier'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                      results: data
                    };
                },
                cache: true
            }
        })
    });

    function getreferensi(){
        $("#ireferensi").attr("disabled", false);
        var isupplier = $('#isupplier').val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getreferensi');?>",
            data:{
                    'isupplier': isupplier,
            },
            dataType: 'json',
            success: function(data){
                $("#ireferensi").html(data.kop);
                if (data.kosong=='kopong') {
                    $("#submit").attr("disabled", true);
                }else{
                    $("#submit").attr("disabled", false);
                }
            },
            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }
        })
    }

    $("#ireferensi").change(function() {
        var ireferensi = $('#ireferensi').val();
        var isupplier = $('#isupplier').val();
        $("#ireferensi").val($(this).val());
        $("#tabledatax tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'id'        : $(this).val(),
                'ireferensi': ireferensi,
                'isupplier' : isupplier
            },
            url: '<?= base_url($folder.'/cform/getdetailreff'); ?>',
            dataType: "json",
            success: function (data) {
                $('#tabledatax').attr('hidden', false);
                $('#jml').val(data['dataitem'].length);
                for (let a = 0; a < data['dataitem'].length; a++) {
                    var no = a+1;
                    var cols = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+no+'</td>';
                    cols += '<td style="text-align: center"><input type="hidden" class="form-control" readonly id="idnotaretur'+no+'" name="idnotaretur'+no+'" value="'+data['dataitem'][a]['id_document']+'"><input type="text" style="width:250px;" class="form-control" readonly id="inotaretur'+no+'" name="inotaretur'+no+'" value="'+data['dataitem'][a]['i_document']+'"></td>';
                    cols += '<td><input type="hidden" class="form-control" readonly id="idmaterial'+no+'" name="idmaterial'+no+'" value="'+data['dataitem'][a]['id_material']+'"><input style="width:150px;" class="form-control" readonly id="imaterial'+no+'" name="imaterial'+no+'" value="'+data['dataitem'][a]['i_material']+'"></td>';
                    cols += '<td><input style="width:400px;" readonly class="form-control" id="ematerial'+no+'" name="ematerial'+no+'" value="'+data['dataitem'][a]['e_material_name']+'"></td>';
                    cols += '<td><input style="width:100px;" class="form-control text-right" readonly id="nquantity'+no+'" name="nquantity'+no+'" value="'+data['dataitem'][a]['n_retur']+'"></td>';
                    cols += '<td><input style="width:150px;" class="form-control text-right" id="vprice'+no+'" name="vprice'+no+'" value="'+data['dataitem'][a]['v_price']+'" readonly></td>';
                    cols += '<td><input style="width:150px;" class="form-control" id="vpricetotal'+no+'" name="vpricetotal'+no+'" value="" readonly></td>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
                hitung();
            },
            error: function () {
                swal('Data kosong :)');
            }
        });
    });

    function hitung() {
        var jml = $('#jml').val();
        // var tot = 0;
        jumlah = 0;

        for (var i = 1; i <= jml; i++) {
            var hrg = $('#vprice' + i).val();
            var qty = $('#nquantity' + i).val();
            total= qty*hrg;
            $('#vpricetotal' + i).val(formatcemua(total));

            jum = formatulang($('#vpricetotal' + i).val());
            jumlah = parseFloat(jumlah) + parseFloat(jum);
            $('#vtotalfa').val(formatcemua(jumlah));

           /* dpp = (100/110 * parseFloat(total));
            $('#dpp' + i).val(formatcemua(dpp));
            ppn = (0.1 * dpp);
            $('#ppn' + i).val(formatcemua(ppn));*/
        }
    }

    function konfirm() {
        var jml = $('#jml').val();
        ada = false;
        if(jml==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }        
    }
</script>
