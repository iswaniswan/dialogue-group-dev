<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-md-4">Bagian Pembuat</label>
                    <label class="col-md-4">Nomor Dokumen</label>
                    <label class="col-md-2">Tgl Dokumen</label>
                    <label class="col-md-2">Tgl Terima Faktur</label> 
                    <div class="col-sm-4">
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
                            <input type="text" name="inota" id="inota" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="FP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                            <span class="input-group-addon">
                                <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                            </span>
                        </div>
                        <span class="notekode">Format : (<?= $number;?>)</span><br>
                        <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                    </div> 
                        <div class="col-sm-2">
                            <input type="text" name="dnota" id="dnota" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly="" onchange="max_tgl(this.value);">
                    </div>       
                    <div class="col-sm-2">
                        <input type="text" name="dreceivefaktur" id="dreceivefaktur" class="form-control date" value="<?php echo date("d-m-Y"); ?>" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4">Partner</label>
                    <label class="col-md-4">Nomor Referensi</label>
                    <label class="col-md-2">Tgl Referensi</label>
                    <label class="col-md-2">Tgl Jatuh Tempo</label>
                    <div class="col-sm-4">
                        <select class="form-control select2" id="ipartner" name="ipartner"></select>
                        <input type="hidden" class="form-control" id="itypepajak" name="itypepajak" value="" readonly>
                        <input type="hidden" class="form-control" id="fpkp" name="fpkp" value="" readonly>
                        <input type="hidden" class="form-control" id="suptop" name="suptop" value="" readonly>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control select2" id="ireferensi" name="ireferensi" disabled="true" onchange="getdataitem(this.value);"></select>
                        <input type="hidden" id="ibagianreff" name="ibagianreff" class="form-control" value="">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="dreferensi" name="dreferensi" value="" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="djatuhtempo" name="djatuhtempo" value="" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3">No Faktur Supplier</label>
                    <label class="col-md-2">Tgl Faktur</label>
                    <label class="col-md-3">No Pajak</label>
                    <label class="col-md-4">Tgl Pajak</label>
                    <div class="col-sm-3">
                        <input type="text" name="ifaktursupp" id="ifaktursupp" class="form-control" value="" placeholder="Nomor Faktur Supplier">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="dfaktursup" id="dfaktursup" class="form-control date" value="<?php echo date("d-m-Y"); ?>" placeholder="<?php echo date("d-m-Y"); ?>" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="ipajak" id="ipajak" class="form-control" value="" placeholder="Nomor Pajak">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" name="dpajak" id="dpajak" class="form-control date" value="<?php echo date("d-m-Y"); ?>" placeholder="<?php echo date("d-m-Y"); ?>" onchange="return tgl_jatuhtempo();" readonly>
                    </div>
                </div>
                <div class="form-group row"> 
                    <label class="col-md-3">Diskon (Rp.)</label>     
                    <label class="col-md-3">Jml Dis Reg</label>
                    <label class="col-md-3">Nilai Total DPP</label>
                    <label class="col-md-3">Nilai Total PPN</label>   
                    <div class="col-sm-3">
                        <input type="text" name="vdiskon" id="vdiskon" class="form-control" placeholder="0" value="0"
                        onkeyup="hitungdiskon()">
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="vtotaldis" id="vtotaldis" class="form-control" value="0" readonly>
                        <input type="hidden" name="diskonsup" id="diskonsup" class="form-control" value="" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="vtotaldpp" id="vtotaldpp" class="form-control" value="0" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="vtotalppn" id="vtotalppn" class="form-control" value="0" readonly>
                    </div>   
                </div>
                <div class="form-group row">
                    <label class="col-md-3">Jumlah Nilai Bruto</label>
                    <label class="col-md-3">Jumlah Nilai Netto</label>
                    <label class="col-md-6">Jumlah Total</label> 
                    <div class="col-sm-3">
                        <input type="text" name="vtotalbruto" id="vtotalbruto" class="form-control" value="0" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="vtotalnetto" id="vtotalnetto" class="form-control" value="0" readonly>
                        <input type="hidden" name="vtotalneto" id="vtotalneto" class="form-control" value="0" readonly>
                    </div>
                    <div class="col-sm-3">
                       <input type="text" name="vtotalfa" id="vtotalfa" class="form-control" value="0" readonly>
                    </div>         
                </div>
                <div class="form-group">
                    <label class="col-md-12">Keterangan</label>
                    <div class="col-sm-12">
                        <textarea class="form-control input-sm" id="eremark" name="eremark" placeholder="Isi keterangan jika ada!"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">No SJ Masuk</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <input type="hidden" name="jml" id="jml" value="0">
            </table>
        </div>
    </div>
</div>
</from>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('#inota').mask('SS-0000-000000S');
        $(".select2").select2();
        showCalendar('.date');
        number();
        $("#ipartner").select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getpartner'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
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

        $('#ireferensi').select2({
            placeholder: 'Pilih SJ Keluar Makloon / Referensi',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getreferensi'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ipartner : $('#ipartner').val(),
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

    //untuk me-generate running number
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dnota').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#inota').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $("#ipartner").change(function(){
        $('#ireferensi').attr("disabled", false);
    });

    function getdataitem(ireff){
        $("#tabledatax tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'id'  : ireff,
                'partner' : $('#ipartner').val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailref'); ?>',
            dataType: "json",
            success: function (data) {
                $('#tabledatax').attr('hidden', false);
                $('#jml').val(data['detail'].length);
                $('#dreferensi').val(data['head']['d_document']);
                $('#itypepajak').val(data['head']['i_type_pajak']);
                $('#fpkp').val(data['head']['f_pkp']);
                $('#suptop').val(data['head']['n_top']);
                $('#diskonsup').val(data['head']['n_diskon']);
                $('#ibagianreff').val(data['head']['i_bagian']);
                for (let a = 0; a < data['detail'].length; a++) {
                    var no = a+1;
                    var cols = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+no+'</td>';
                    cols += '<td style="text-align: center"><input type="text" style="width:200px;" class="form-control" readonly id="ireferensiitem'+no+'" name="ireferensiitem'+no+'" value="'+data['detail'][a]['dokumen_masuk']+'"><input hidden class="form-control" readonly id="idreffitem'+no+'" name="idreffitem'+no+'" value="'+data['detail'][a]['id_document']+'"><input hidden class="form-control" readonly id="idproductwip'+no+'" name="idproductwip'+no+'" value="'+data['detail'][a]['produk_masuk']+'"></td>';
                    cols += '<td><input style="width:300px;" class="form-control" readonly id="iproductwip'+no+'" name="iproductwip'+no+'" value="'+data['detail'][a]['i_product_wip']+ ' - ' +data['detail'][a]['e_product_wipname']+'"></td>';
                    cols += '<td><input style="width:120px;" type="text" class="form-control text-right" readonly id="nquantity'+no+'" name="nquantity'+no+'" value="'+data['detail'][a]['n_quantity']+'"><input type="hidden" class="form-control text-right" readonly id="nsisa'+no+'" name="nsisa'+no+'" value="'+data['detail'][a]['n_sisa']+'"></td>';
                    cols += '<td><input style="width:150px;" type="text" class="form-control text-right" id="vprice'+no+'" name="vprice'+no+'" value="'+data['detail'][a]['v_price']+'"readonly>';
                    cols += '<input type="hidden" class="form-control" id="vgross'+no+'" name="vgross'+no+'" value="" readonly>';
                    cols += '<input type="hidden" class="form-control" id="vnetto'+no+'" name="vnetto'+no+'" value="" readonly></td>';
                    cols += '<td><input style="width:150px;" type="text" class="form-control" id="vtotalitem'+no+'" name="vtotalitem'+no+'" value="" readonly>';
                    cols += '<input type="hidden" class="form-control" id="vdpp'+no+'" name="vdpp'+no+'" value="" readonly>';
                    cols += '<input type="hidden" class="form-control" id="vppn'+no+'" name="vppn'+no+'" value="" readonly></td>';
                    cols += '<td><input style="width:250px;" type="text" class="form-control" id="edesc'+no+'" name="edesc'+no+'" value="" placeholder="Isi keterangan jika ada!"></td>';
                    newRow.append(cols);
                    $("#tabledatax").append(newRow);
                }
                hitung();
                tgl_jatuhtempo();
            },
            error: function () {
                swal('Data kosong :)');
            }
        });
    }

    //HITUNG TOTOTALAN
    function hitung() {
        var jml = $('#jml').val();
        var tot = 0;
        var dpp = 0;
        var ppn = 0;
         //alert(jml);    
        for (var i = 1; i <= jml; i++) {
            var hrg = parseFloat($('#vprice' + i).val());
            var qty = parseFloat($('#nquantity' + i).val());
            var tipe= $('#itypepajak').val();

            //TOTAL HARGA
            vharga = (qty)*(hrg);
            if(tipe == 'I'){//include
                //DPP
                dpp = (parseFloat(vharga) / 1.1);
                $('#vdpp' + i).val(dpp);
                //PPN
                ppn = (dpp * 0.1);
                $('#vppn' + i).val(ppn);
                //JUMLAH TOTAL
                tot = (parseFloat(dpp) + parseFloat(ppn));
                tot = tot.toFixed(2);
                $('#vtotalitem' + i).val(tot);
            }else if(tipe == 'E'){//Exclude
                //DPP
                dpp =  parseFloat(vharga);
                $('#vdpp' + i).val(dpp);
                //PPN
                ppn = (dpp * 0.1);
                $('#vppn' + i).val(ppn);
                //JUMLAH TOTAL
                tot = (parseFloat(dpp) + parseFloat(ppn));
                tot = tot.toFixed(2);
                $('#vtotalitem' + i).val(tot);                 
            }
            hitungnilai(i);
        }
    }

    function hitungnilai(i) {
        var totfak = formatulang(document.getElementById('vtotalfa').value);
        var totdpp = formatulang(document.getElementById('vtotaldpp').value);
        var totppn = formatulang(document.getElementById('vtotalppn').value);
        var totfak = formatulang(document.getElementById('vtotalfa').value);
        var tipe   = formatulang(document.getElementById('itypepajak').value);

        var nilaisj = formatulang($('#vtotalitem' + i).val());
        totakhir = parseFloat(totfak) + parseFloat(nilaisj);
        totakhir = totakhir.toFixed(2);
        document.getElementById('vtotalfa').value = (totakhir);

        var total = formatulang(document.getElementById('vtotalfa').value);
        var diskon = formatulang(document.getElementById('diskonsup').value);
        var diskon2 = formatulang(document.getElementById('vdiskon').value);

        if(tipe == 'I'){
            bruto   = total;
            vdis    = diskon/100;
            vdiskon = parseFloat(bruto)*parseFloat(vdis);
            vnet    = bruto - vdiskon - diskon2;
            dpp     = parseFloat(vnet)/1.1;
            ppn     = dpp*0.1;
            vnetto  = vnet;
        }else if(tipe == 'E'){
            bruto   = total*1.1;
            vdis    = diskon/100;
            vdiskon = parseFloat(bruto)*parseFloat(vdis);
            vnet    = bruto - vdiskon;
            dpp     = vnet/1.1;
            ppn     = dpp*0.1;
            vdis2   = diskon2/100;
            vdiskon2= parseFloat(vnet)*parseFloat(vdis2);
            vnetto  = vnet - vdiskon2;
        }

        document.getElementById('vtotaldpp').value   = (dpp.toFixed(2));
        document.getElementById('vtotalppn').value   = (ppn.toFixed(2));
        document.getElementById('vtotalbruto').value = (bruto);
        document.getElementById('vtotaldis').value   = (vdiskon.toFixed(2));
        document.getElementById('vtotalnetto').value = (vnetto.toFixed(2));
        document.getElementById('vtotalneto').value  = (vnetto.toFixed(2));
    }

    function hitungdiskon() {
        var vnetto  = formatulang($('#vtotalneto').val());    
        var vnetto2 = formatulang($('#vtotalneto').val());    
        var vdiskon = formatulang($('#vdiskon').val());
        if(vdiskon == ''){
            vdiskon = 0;
        }
        totalnet = parseFloat(vnetto2) - parseFloat(vdiskon);
        dpp      = parseFloat(totalnet)/1.1;
        ppn      = dpp*0.1;
        document.getElementById('vtotalnetto').value = (totalnet.toFixed(2));
        document.getElementById('vtotaldpp').value   = (dpp.toFixed(2));
        document.getElementById('vtotalppn').value   = (ppn.toFixed(2));
    }

    $( "#inota" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
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

    //menyesuaikan periode di running number sesuai dengan tanggal dokumen
    $( "#dnota" ).change(function() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $(this).val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#inota').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#inota").attr("readonly", false);
        }else{
            $("#inota").attr("readonly", true);
            $("#inota").val("<?= $number;?>");
        }
    });

    function tgl_jatuhtempo(){
        var dpajak  = $('#dpajak').val(); 
        var suptop  = $('#suptop').val(); 
  
        var a       = parseInt(suptop);
        var arr = dpajak.split("-");
        var d  = arr[0];
        var m  = arr[1];

        var y  = arr[2];
        var x = y+" "+m+" "+d;
        var date = new Date(x);

        date.setDate(date.getDate() + a); // add 30 days 
        var year    = date.getFullYear();
        var month   = date.getMonth();
        var ndate   = date.getDate();
        var day     = new Date(year, month, ndate);  
        var year1=day.getFullYear();
        var month1=day.getMonth()+1; //getMonth is zero based;
        var mm = ("0"+month1).slice(-2);
        var day1=("0" + day.getDate()).slice(-2);
        dnew= day1 + "-" + mm + "-" + year1;

        $('#djatuhtempo').val(dnew);
    }

    function max_tgl(val) {
        $('#dpajak').datepicker('destroy');
        $('#dpajak').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dnota').value,
        });
    }

    $('#dpajak').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dnota').value,
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'11','<?= $dfrom."','".$dto;?>');
    });
    
    function validasi() {
        var s = 0;
        var pkp = $('#fpkp').val();

        if ($('#jml').val()==0) {
            swal('Nomor Refrensi masih kosong!');
            return false;
        }else{            
            if (pkp == 't') {
                if (document.getElementById('ipajak').value == '') {
                    swal("No Pajak Masih Kosong");
                    return false;
                } else {
                    return true;
                }
            }
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
    }   
</script>