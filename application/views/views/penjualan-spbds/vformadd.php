<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
<form>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Tanggal Batas Kirim</label>
                        <label class="col-md-3">Area</label>
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
                                <input type="hidden" name="id" id="id" class="form-control">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" onchange="number(); max_tgl();" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dsend" name="dsend" class="form-control input-sm date" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2" required="" >
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Customer</label>
                        <label class="col-md-3">Kelompok Harga</label>
                        <label class="col-md-3">Salesman</label>
                        <label class="col-md-3">Referensi OP</label>                        
                        <div class="col-sm-3">
                            <select name="icustomer" id="icustomer" class="form-control select2" required="" disabled onchange="return getdiskon(this.value);">
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" id="kodeharga" name="kodeharga" class="form-control" readonly>
                            <input type="text" id="ekodeharga" name="ekodeharga" class="form-control" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="isales" id="isales" class="form-control select2" required="" disabled>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ireferensiop" name="ireferensiop" class="form-control">
                            <input type="hidden" id="1ndiskonitem" name="1ndiskonitem" class="form-control" readonly>
                            <input type="hidden" id="2ndiskonitem" name="2ndiskonitem" class="form-control" readonly>
                            <input type="hidden" id="3ndiskonitem" name="3ndiskonitem" class="form-control" readonly>
                        </div>                                            
                    </div>                       
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremarkh" name="eremarkh" class="form-control" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <span style="color: #8B0000"><b>NOTE :</b></span><br>
                    <span style="color: #8B0000">* Harga barang jadi yang digunakan adalah harga exclude</span><br>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0">
            </div>
        </div>
    </div>
</div>

<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
            <div class="form-group row">
                <label class="col-md-6">Kategori Barang</label>
                <label class="col-md-5">Jenis Barang</label>
                <label class="col-md-1"></label> 
                <div class="col-sm-6">
                    <select class="form-control select2" name="ikategori" id="ikategori">
                        <option value="all">Semua Kategori</option>
                    </select>
                </div>
                <div class="col-sm-5">
                    <select class="form-control select2" name="ijenis" id="ijenis">
                        <option value="all">Semua Jenis</option>
                    </select>
                    <input type="hidden" id="ibrand" name="ibrand" class="form-control" readonly> 
                </div>
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm" disabled><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                </div>
            </div>
        </div>
    </div>
   <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Diskon 1 (%)</th>
                        <th class="text-center">Diskon 2 (%)</th>
                        <th class="text-center">Diskon 3 (%)</th>
                        <th class="text-center">Diskon Tambahan (Rp.)</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" >Act</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right" colspan="8">Total</td>
                        <td>:</td>
                        <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm" readonly></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">Diskon</td>
                        <td>:</td>
                        <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm" readonly value="0"></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">DPP</td>
                        <td>:</td>
                        <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">PPN (10%)</td>
                        <td>:</td>
                        <td><input type="text" id="vppn" name="vppn" class="form-control input-sm" value="0" readonly></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">Grand Total</td>
                        <td>:</td>
                        <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm" readonly></td>
                    </tr>
                    </tfoot>
            </table>
        </div>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        number();
        max_tgl();

        $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/area/'); ?>',
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
            $("#icustomer").attr("disabled", false);
            /*$("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);*/
            $("#icustomer").val("");
            $("#icustomer").html("");
        });

        $('#icustomer').select2({
            placeholder: 'Pilih Customer',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iarea : $('#iarea').val(),
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
            $("#addrow").attr('hidden', false);
            $("#tabledatax > tbody").remove();
            $("#jml").val(0);

            $("#isales").attr("disabled", false);
            $("#isales").val("");
            $("#isales").html("");
        });

        $('#isales').select2({
            placeholder: 'Pilih Sales',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/sales'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iarea : $('#iarea').val(),
                        icustomer : $('#icustomer').val(),
                        ddocument : $('#ddocument').val(),
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
            $("#addrow").attr("disabled", false);
        });

        $('#ikategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenis').val('');
            $('#ijenis').html('');
        });

        $('#ijenis').select2({
            placeholder: 'Pilih Jenis',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikategori : $('#ikategori').val(),
                        ibagian   : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    function getdiskon(){
        var icustomer = $('#icustomer').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer'  : icustomer
            },
            url: '<?= base_url($folder.'/cform/getdiskon'); ?>',
            dataType: "json",
            success: function (data) {
                $('#1ndiskonitem').val(formatcemua(data[0].v_customer_discount));
                $('#2ndiskonitem').val(formatcemua(data[0].v_customer_discount2));
                $('#3ndiskonitem').val(formatcemua(data[0].v_customer_discount3));
                $('#kodeharga').val(formatcemua(data[0].id_harga_kode));
                $('#ekodeharga').val(formatcemua(data[0].e_harga));
            },
            error: function () {
                swal('Error :)');
            }
        });
    }
     
      /**
     * Tambah Item
     */

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {
        var no     = $('#tabledatax tbody tr').length+1;
        counter++;
        counterx++;
        $("#tabledatax").attr("hidden", false);
        var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledatax tr').length;
        // if ((iproduct==''||iproduct==null)&&(count>1)) {
        //     swal('Isi dulu yang masih kosong!!');
        //     counter = counter-1;
        //     counterx = counterx-1;
        //     return false;
        // }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+no+'</spanx></td>';
        cols += '<td><input style="width:150px;" type="text" readonly  id="iproduct'+ counter + '" class="form-control" name="iproduct[]" value=""><input style="width:150px;" readonly id="idproduct'+ counter + '" type="hidden" class="form-control" name="idproduct[]" value=""></td>';
        cols += '<td><select style="width:300px;" id="eproduct'+counter+ '" class="form-control select2" name="eproduct[]"  onchange="getproduct('+ counter + ');"></select></td>';
        cols += '<td><input style="width:100px;" type="text" id="nquantity'+counter+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" value="0" onkeyup="hitungtotal(); reformat(this);"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="vharga'+ counter + '" class="form-control" name="vharga[]" value=""></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="ndiskon'+ counter + '" class="form-control" name="ndiskon[]" value=""><input style="width:100px;" readonly  id="vdiskon'+ counter + '" type="hidden" class="form-control" name="vdiskon[]" value=""></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="ndiskonn'+ counter + '" class="form-control" name="ndiskonn[]" value=""><input style="width:100px;" readonly  id="vdiskonn'+ counter + '" type="hidden" class="form-control" name="vdiskonn[]" value=""></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="ndiskonnn'+ counter + '" class="form-control" name="ndiskonnn[]" value=""><input style="width:100px;" type="hidden" readonly  id="vdiskonnn'+ counter + '" class="form-control" name="vdiskonnn[]" value=""></td>';
        cols += '<td><input style="width:100px;" type="text" id="adddiskon'+ counter + '" class="form-control" name="adddiskon[]" value="0" onkeyup="hitungtotal(); reformat(this);"></td>';
        cols += '<td><input style="width:100px;" type="text" id="vtotal'+ counter + '" class="form-control" name="vtotal[]" value="" readonly><input style="width:100px;" type="hidden" id="vtotaldiskon'+ counter + '" class="form-control" name="vtotaldiskon[]" value="" readonly><input style="width:100px;" type="hidden" id="vtotalnet'+ counter + '" class="form-control" name="vtotalnet[]" value="" readonly></td>';
        cols += '<td><input style="width:300px;" type="text" id="eremark'+counter+'" class="form-control input-sm" name="eremark[]"></td>';
        cols += '<td><button type="button" oncilick="hitungtotal();" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#eproduct'+ counter).select2({
            placeholder: 'Cari Kode / Nama Product',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ikategori  : $('#ikategori').val(),
                        ijenis     : $('#ijenis').val(),
                        ibagian    : $('#ibagian').val(),
                        ibrand     : $('#ibrand').val(),
                        kodeharga  : $('#kodeharga').val(),
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
        function formatSelection(val) {
            return val.name;
        }
    });  

    function getproduct(id){
        var dis1_ = $('#1ndiskonitem').val();
        var dis2_ = $('#2ndiskonitem').val();
        var dis3_ = $('#3ndiskonitem').val();
        $.ajax({
            type: "post",
            data: {
                'eproduct' : $('#eproduct'+id).val(),
                'tgl'      : $('#ddocument').val(),
                'kodeharga': $('#kodeharga').val(),
            },
            url: '<?= base_url($folder.'/cform/getproduct'); ?>',
            dataType: "json",
            success: function (data) {
                if (parseInt(data.length) < 1) {
                    swal('Maaf :(','Harga Barang Jadi Masih Kosong, Silahkan Input di Master Harga Barang Jadi!','error');
                    $('#eproduct'+id).html('');
                    $('#eproduct'+id).val('');
                    return false;
                }
                ada = false;
                for(var i = 1; i <=$('#jml').val(); i++){
                    if(($('#eproduct'+id).val() == $('#eproduct'+i).val()) && (i!=id)){
                        swal ("kode : "+$('#eproduct'+id).val()+" sudah ada !!!!!");
                        ada = true;
                        break;
                    }else{
                        ada = false;     
                    }
                }
                 if(!ada){
                    $('#idproduct'+id).val(formatcemua(data[0].id_product));
                    $('#iproduct'+id).val(formatcemua(data[0].i_product_base));  
                    $('#vharga'+id).val(formatcemua(data[0].v_price)); 
                    $('#ibrand').val(formatcemua(data[0].i_brand));                   
                    $('#nquantity'+id).focus();
                    $('#ndiskon'+id).val(dis1_);
                    $('#ndiskonn'+id).val(dis2_);
                    $('#ndiskonnn'+id).val(dis3_);
                    hitungtotal(id);
                }else{
                    $('#idproduct'+id).html('');
                    $('#iproduct'+id).html('');
                    $('#eproduct'+id).html('');
                    $('#vharga'+id).html('');
                    $('#ndiskon'+id).html('');
                    $('#ndiskonn'+id).html('');
                    $('#ndiskonnn'+id).html('');
                    $('#idproduct'+id).val('');
                    $('#iproduct'+id).val('');
                    $('#eproduct'+id).val('');
                    $('#vharga'+id).val('');
                    $('#ndiskon'+id).val('');
                    $('#ndiskonn'+id).val('');
                    $('#ndiskonnn'+id).val('');
                }
            },
            error: function () {
                swal('Ada kesalahan :(');
            }
        });
    }

    function max_tgl(val) {
        $('#dsend').datepicker('destroy');
        $('#dsend').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('ddocument').value,
        });
    }
    $('#dsend').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('ddocument').value,
    });

    function hitungtotal(){
        var id = $('#jml').val();
        var total = 0;
        var totaldis = 0;
        var vjumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand = 0;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if(typeof $('#idproduct'+i).val() != 'undefined'){
                var jumlah = formatulang($('#vharga'+i).val()) * parseFloat($('#nquantity'+i).val());
                var disc1 = formatulang($('#ndiskon'+i).val());
                var disc2 = formatulang($('#ndiskonn'+i).val());
                var disc3 = formatulang($('#ndiskonnn'+i).val());
                var disc4 = formatulang($('#adddiskon'+i).val());
               // alert(disc4);
                var ndisc1 = jumlah * (disc1/100);
                var ndisc2 = (jumlah - ndisc1) * (disc2/100);
                var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3/100);

                var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + parseInt(disc4));
                   
                var vtotal  = jumlah - vtotaldis;
                //alert(jumlah+' - '+vtotaldis);
                //alert(vtotaldis+'|'+vtotal);
               
                $('#vdiskon'+i).val(ndisc1);
                $('#vdiskonn'+i).val(ndisc2);
                $('#vdiskonnn'+i).val(ndisc3);
                $('#vtotaldiskon'+i).val(vtotaldis);
                $('#vtotal'+i).val(jumlah);
                $('#vtotalnet'+i).val(vtotal);

                totaldis += vtotaldis;
                vjumlah += jumlah;
                total += vtotal;
            }
        }
        $('#nkotor').val(vjumlah);
        $('#ndiskontotal').val(totaldis);
 
        dpp = vjumlah - totaldis;
        ppn = dpp * 0.1;
        grand = dpp + ppn;

        $('#nbersih').val(grand);
        $('#vdpp').val(dpp);
        $('#vppn').val(ppn);
        //alert(dpp);
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        del();
        hitungtotal();

        var jum = $('#tabledatax tbody tr').length;
        if(jum == 0){
            $('#ibrand').val('');
        }
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });        
    }

    //new script
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

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#idocument").attr("readonly", false);
        }else{
            $("#idocument").attr("readonly", true);
            $("#ada").attr("hidden", true);
            number();
        }
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

    $( "#submit" ).click(function(event) {
        ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#iarea').val()!='' || $('#iarea').val()) && ($('#icustomer').val()!='' || $('#icustomer').val())  && ($('#isales').val()!='' || $('#isales').val())) {
            if ($('#jml').val()==0) {
                swal('Isi item minimal 1!');
                return false;
            }else{
                $("#tabledatax tbody tr").each(function() {
                    $(this).find("td select").each(function() {
                        if ($(this).val()== '' || $(this).val()== null) {
                            swal('Barang tidak boleh kosong!');
                            ada = true;
                        }
                    });
                    $(this).find("td .inputitem").each(function() {
                        if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                            swal('Quantity Tidak Boleh Kosong Atau 0!');
                            ada = true;
                        }
                    });
                });
                if (!ada) {
                    swal({   
                        title: "Simpan Data Ini?",   
                        text: "Anda Dapat Membatalkannya Nanti",
                        type: "warning",   
                        showCancelButton: true,   
                        confirmButtonColor: "#DD6B55",   
                        confirmButtonColor: 'LightSeaGreen',
                        confirmButtonText: "Ya, Simpan!",   
                        closeOnConfirm: false 
                    }, function(){
                        $.ajax({
                            type: "POST",
                            data: $("form").serialize(),
                            url: '<?= base_url($folder.'/cform/simpan/'); ?>',
                            dataType: "json",
                            success: function (data) {
                                if (data.sukses==true) {
                                    $('#id').val(data.id)
                                    swal("Sukses!", "No Dokumen : "+data.kode+", Berhasil Disimpan :)", "success"); 
                                    $("input").attr("disabled", true);
                                    $("select").attr("disabled", true);
                                    $("#submit").attr("disabled", true);
                                    $("#addrow").attr("disabled", true);
                                    $("#send").attr("hidden", false);
                                }else if (data.sukses=='ada') {
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Sudah Ada :(", "error");   
                                }else{
                                    swal("Maaf :(", "No Dokumen : "+data.kode+", Gagal Disimpan :(", "error"); 
                                }
                            },
                            error: function () {
                                swal("Maaf", "Data Gagal Disimpan :(", "error");
                            }
                        });
                    });
                }else{
                    return false;
                }
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }     
    });
</script>