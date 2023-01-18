<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal</label>
                        <div class="col-sm-12">
                            <input type="text" required="" readonly id= "dtunai" name="dtunai" class="form-control date" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input id="xeremark" class="form-control" name="xeremark">
                        </div>
                    </div>            
                    <div class="form-group row">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-12">
                            <input type="text" required="" readonly id= "vjumlah" name="vjumlah" class="form-control" value="0">
                        </div>
                    </div>                         
                    <div class="form-group row"></div>   
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" disabled=""><i class="fa fa-plus"></i>&nbsp;&nbsp;Detail
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" id="iarea" required="" class="form-control select2" onchange="getarea(this.value);">
                                <option value=""></option>
                                <?php if ($area) {                                 
                                    foreach ($area as $key) { ?>
                                        <option value="<?php echo $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <select name="icustomer" id="icustomer" required="" class="form-control select2" disabled="" onchange="getpelanggan(this.value);"></select>
                            <input type="hidden" name="ecustomername" id="ecustomername">
                            <input type="hidden" name="icustomergroupar" id="icustomergroupar">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                            <select name="isalesman" id="isalesman" class="form-control select2" disabled="" onchange="getsalesman(this.value);"></select>
                            <input type="hidden" name="xsalesman" id="xsalesman">
                        </div>
                    </div> 
                    <div class="col-md-12">
                        <div class="form-check">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="lebihbayar" name="lebihbayar" class="custom-control-input">
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Lebih Bayar</span>
                            </label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 15%;">No Nota</th>
                                <th style="text-align: center; width: 10%;">Tanggal Nota</th>
                                <th style="text-align: center;">Area</th>
                                <th style="text-align: center;">Jumlah</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center; width: 5%;">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    var xx = 0;
    $("#addrow").on("click", function () {
        xx++;
        if(xx<=30){
            $("#tabledata").attr("hidden", false);
            $('#jml').val(xx);
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="barisp'+xx+'" class="form-control" name="barisp'+xx+'" value="'+xx+'"></td>';
            cols += '<td><select id="inota'+xx+ '" class="form-control" name="inota'+xx+'" onchange="getdetailnota('+xx+');"></td>';
            cols += '<td><input type="hidden" id="dnota'+xx+'" class="form-control" name="dnota'+xx+'" readonly><input id="dnotax'+xx+'" class="form-control" name="dnotax'+xx+'" readonly></td>';
            cols += '<td><input type="hidden" id="iarea'+xx+'" name="iarea'+xx+'"><input id="eareaname'+xx+'" class="form-control" name="eareaname'+xx+'" readonly></td>';
            cols += '<td><input id="vjumlah'+xx+'" class="form-control" name="vjumlah'+xx+'" onkeypress="return hanyaAngka(event);" onblur="hitungnilai();" onkeyup="hitungnilai(); reformat(this);" onpaste="hitungnilai();" autocomplete="off" value="0" style="text-align: right;"/></td>';
            cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+'" readonly></td>';
            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#inota'+xx).select2({
                placeholder: 'Cari Nota',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/nota/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var iarea       = $('#iarea').val();
                        var dtunai      = $('#dtunai').val();
                        var icustomer   = $('#icustomer').val();
                        var query       = {
                            q : params.term,
                            iarea : iarea,
                            dtunai : dtunai,
                            icustomer : icustomer
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
        }else{
            swal("Maksimal 30 item");
            return false;
        }
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jml').val(xx);
    });

    function getdetailnota(id){
        ada=false;
        var iarea     = $('#iarea').val();
        var dtunai    = $('#dtunai').val();
        var icustomer = $('#icustomer').val();
        var a = $('#inota'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#inota'+i).val()) && (i!=x)){
                alert ("No Nota : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'inota'     : a,
                    'iarea'     : iarea,
                    'dtunai'    : dtunai,
                    'icustomer' : icustomer
                },
                url: '<?= base_url($folder.'/cform/getdetailnota'); ?>',
                dataType: "json",
                success: function (data) {
                    var zz = formatulang($('#vjumlah').val());
                    $('#iarea'+id).val(data[0].i_area);
                    $('#dnota'+id).val(data[0].d_nota);
                    $('#dnotax'+id).val(data[0].dnota);
                    $('#eareaname'+id).val(data[0].e_area_name);
                    $('#vjumlah'+id).val(formatcemua(data[0].v_sisa));
                    $('#eremark'+id).val(data[0].e_remark);
                    $('#vjumlah').val(formatcemua(parseFloat(zz)+parseFloat(formatulang(data[0].v_sisa))));
                    hitungnilai();
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#inota'+id).html('');
            $('#inota'+id).val('');
        }
    }

    function hitungnilai(){
        jml=document.getElementById("jml").value;
        if (jml<=0){
        }else{
            salah=false;
            x=0;
            for(i=1;i<=jml;i++){
                y=parseFloat(formatulang(document.getElementById("vjumlah"+i).value));
                if(!isNaN(y)){
                    x=x+y;
                }else{
                    swal('Input harus numerik');
                    document.getElementById("vjumlah"+i).value=0;
                }
            }
            document.getElementById("vjumlah").value=formatcemua(x);
        }
    }

    function getarea(kode) {
        if (kode!='') {
            $("#addrow").attr("disabled", false);
            $("#icustomer").attr("disabled", false);
            $("#isalesman").attr("disabled", false);
        }else{
            $("#addrow").attr("disabled", true);
            $("#icustomer").attr("disabled", true);
            $("#isalesman").attr("disabled", true);
        }
        $("#tabledata tr:gt(0)").remove();       
        $("#tabledata").attr("hidden", true);
        $("#jml").val(0);
        xx = 0;
    }

    function getpelanggan(kode) {
        if (kode!='') {
            $("#addrow").attr("disabled", false);
        }else{
            $("#addrow").attr("disabled", true);
        }
        $("#tabledata tr:gt(0)").remove();       
        $("#tabledata").attr("hidden", true);
        $("#jml").val(0);
        xx = 0;

        var dtunai = $('#dtunai').val();
        var iarea  = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer': kode,
                'dtunai'   : dtunai,
                'iarea'    : iarea
            },
            url: '<?= base_url($folder.'/cform/getdetailcus'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ecustomername').val(data[0].e_customer_name); 
                $('#icustomergroupar').val(data[0].i_customer_groupar);
                if (data[0].i_salesman!=null) {
                    $('#select2-isalesman-container').html(data[0].i_salesman+'-'+data[0].e_salesman_name);
                }
                $('#xsalesman').val(data[0].i_salesman);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function getsalesman(isalesman) {
        $("#xsalesman").val(isalesman);
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#addpel").attr("disabled", true);
        $("#addpelgr").attr("disabled", true);
        $("#addar").attr("disabled", true);
    });

    function hanyaAngka(evt) {      
        var charCode = (evt.which) ? evt.which : event.keyCode      
        if (charCode > 31 && (charCode < 48 || charCode > 57))        
            return false;    
        return true;
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama'
        });

        $('#icustomer').select2({
            placeholder: 'Cari Berdasarkan Kodelang / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getcustomer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var dtunai = $('#dtunai').val();
                    var iarea  = $('#iarea').val();
                    var query = {
                        q: params.term,
                        dtunai: dtunai,
                        iarea: iarea
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

        $('#isalesman').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsalesman/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var dtunai = $('#dtunai').val();
                    var iarea  = $('#iarea').val();
                    var query = {
                        q: params.term,
                        dtunai: dtunai,
                        iarea: iarea
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

    function dipales(){
        if((document.getElementById("dtunai").value=='')||(document.getElementById("iarea").value=='')||(document.getElementById("vjumlah").value=='')||(document.getElementById("vjumlah").value=='0')||(document.getElementById("ibank").value=='')){
            swal("Data Header belum lengkap !!!");
            return false;
        }else{          
            return true;
        }
    }
</script>