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
                        <label class="col-md-12">Tanggal Setor</label>
                        <div class="col-sm-12">
                            <input type="text" required readonly id= "drtunai" name="drtunai" class="form-control date" value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input id="eremark" class="form-control" name="eremark">
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
                        <label class="col-md-12">Bank</label>
                        <div class="col-sm-12">
                            <select name="ibank" id="ibank" required="" class="form-control select2">
                                <option value=""></option>
                                <?php if ($bank) {                                 
                                    foreach ($bank as $key) { ?>
                                        <option value="<?= $key->i_bank;?>"><?= $key->i_coa." - ".$key->e_bank_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                    </div>           
                    <div class="form-group row">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-12">
                            <input type="text" required="" readonly id= "vjumlah" name="vjumlah" class="form-control" value="0">
                        </div>
                    </div> 
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 15%;">No Tunai</th>
                                <th style="text-align: center; width: 10%;">Tanggal Tunai</th>
                                <th style="text-align: center;">Area</th>
                                <th style="text-align: center;">Pelanggan</th>
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
            cols += '<td><select id="itunai'+xx+ '" class="form-control" name="itunai'+xx+'" onchange="getdetailtunai('+xx+');"></td>';
            cols += '<td><input type="hidden" id="dtunai'+xx+'" class="form-control" name="dtunai'+xx+'" readonly><input id="dtunaix'+xx+'" class="form-control" name="dtunaix'+xx+'" readonly></td>';
            cols += '<td><input type="hidden" id="icustomer'+xx+'" name="icustomer'+xx+'"><input id="ecustomername'+xx+'" class="form-control" name="ecustomername'+xx+'" readonly></td>';
            cols += '<td><input type="hidden" id="iarea'+xx+'" name="iarea'+xx+'"><input id="eareaname'+xx+'" class="form-control" name="eareaname'+xx+'" readonly></td>';
            cols += '<td><input type="hidden" id="vjumlahasal'+xx+'" name="vjumlahasal'+xx+'" value=""><input type="hidden" id="vjumlahasallagi'+xx+'" name="vjumlahasallagi'+xx+'" value=""><input id="vjumlah'+xx+'" class="form-control" name="vjumlah'+xx+'" onkeypress="return hanyaAngka(event);" onblur="hitungnilai('+xx+');" onkeyup="hitungnilai('+xx+'); reformat(this);" onpaste="hitungnilai('+xx+');" autocomplete="off" value="0" style="text-align: right;"/></td>';
            cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+'" readonly></td>';
            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#itunai'+xx).select2({
                placeholder: 'Cari Tunai',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/tunai/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var iarea       = $('#iarea').val();
                        var drtunai     = $('#drtunai').val();
                        var query       = {
                            q : params.term,
                            iarea : iarea,
                            drtunai : drtunai
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

    function getdetailtunai(id){
        ada=false;
        var iarea     = $('#iarea').val();
        var drtunai   = $('#drtunai').val();
        var a = $('#itunai'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#itunai'+i).val()) && (i!=x)){
                alert ("No Tunai : "+a+" sudah ada !!!!!");            
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
                    'itunai'     : a,
                    'iarea'     : iarea,
                    'drtunai'    : drtunai
                },
                url: '<?= base_url($folder.'/cform/getdetailtunai'); ?>',
                dataType: "json",
                success: function (data) {
                    var zz = formatulang($('#vjumlah').val());
                    $('#iarea'+id).val(data[0].i_area);
                    $('#icustomer'+id).val(data[0].i_customer);
                    $('#ecustomername'+id).val(data[0].e_customer_name);
                    $('#dtunai'+id).val(data[0].d_tunai);
                    $('#dtunaix'+id).val(data[0].dtunai);
                    $('#eareaname'+id).val(data[0].e_area_name);
                    $('#vjumlah'+id).val(formatcemua(data[0].v_sisa));
                    $('#vjumlahasal'+id).val(data[0].v_sisa);
                    $('#vjumlahasallagi'+id).val(data[0].v_sisa);
                    $('#eremark'+id).val(data[0].e_remark);
                    $('#vjumlah').val(formatcemua(parseFloat(zz)+parseFloat(formatulang(data[0].v_sisa))));
                    hitungnilai();
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#itunai'+id).html('');
            $('#itunai'+id).val('');
        }
    }

    function hitungnilai(x){
        num=document.getElementById("vjumlah"+x).value.replace(/\,/g,'');
        numasal=document.getElementById("vjumlahasal"+x).value.replace(/\,/g,'');
        numasli=document.getElementById("vjumlahasallagi"+x).value.replace(/\,/g,'');
        if(!isNaN(num)){
            xx=document.getElementById("vjumlah").value.replace(/\,/g,'');
            if(parseFloat(num)>parseFloat(numasli)){
                swal('Jumlah tidak boleh lebih dari Rp. '+numasli+' !!!');
                document.getElementById("vjumlah"+x).value=document.getElementById("vjumlahasal"+x).value;
                yy=(parseFloat(xx)+parseFloat(numasal));
                document.getElementById("vjumlah").value=formatcemua(parseFloat(yy)-parseFloat(num));
                xx=document.getElementById("vjumlah").value.replace(/\,/g,'');
            }
            if(parseFloat(num)<0){
                swal('Jumlah tidak boleh kurang dari 0 !!!');
                document.getElementById("vjumlah"+x).value=document.getElementById("vjumlahasal"+x).value;
                yy=(parseFloat(xx)+parseFloat(numasal));
                document.getElementById("vjumlah").value=formatcemua(parseFloat(yy)-parseFloat(num));
                xx=document.getElementById("vjumlah").value.replace(/\,/g,'');
            }
            if(document.getElementById("vjumlah"+x).value==''){
                yy=(parseFloat(xx)-parseFloat(numasal));
                document.getElementById("vjumlah").value=formatcemua(parseFloat(yy));
                document.getElementById("vjumlahasal"+x).value='0';
            }else{
                yy=(parseFloat(xx)-parseFloat(numasal));
                document.getElementById("vjumlah").value=formatcemua(parseFloat(yy)+parseFloat(num));
                document.getElementById("vjumlahasal"+x).value=document.getElementById("vjumlah"+x).value;
                if(parseFloat(num)<=parseFloat(numasli)){
                    document.getElementById("vjumlah"+x).value=formatcemua(parseFloat(num));
                }
            }
        }else{
            swal('input harus numerik !!!');
            document.getElementById("vjumlah"+x).value=0;
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

        var drtunai = $('#drtunai').val();
        var iarea  = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer': kode,
                'drtunai'   : drtunai,
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

        $('#ibank').select2({
            placeholder: 'Pilih Bank'
        });
    });

    function dipales(){
        if((document.getElementById("drtunai").value=='')||(document.getElementById("iarea").value=='')||(document.getElementById("vjumlah").value=='')||(document.getElementById("vjumlah").value=='0')||(document.getElementById("ibank").value=='')){
            swal("Data Header belum lengkap !!!");
            return false;
        }else{          
            return true;
        }
    }
</script>