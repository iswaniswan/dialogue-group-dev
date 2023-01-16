<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $ldfrom."/".$ldto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-sm-3">Jenis Faktur</label>
                        <label class="col-sm-3">No Faktur Awal</label>
                        <label class="col-sm-3">Seri Pajak Awal</label>
                        <div class="col-sm-3">
                        <select class="form-control select2" name="ibagian" id="ibagian">
                            <?php foreach ($bagian as $ibagian):?>
                            <option value="<?php echo $ibagian->i_bagian;?>">
                                <?= $ibagian->e_bagian_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>  
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2" aonchange="getiseri(this.value);">
                                <option value="">Pilih Jenis Faktur</option>
                                <option value="all">Semua Jenis Faktur</option>
                                <?php if ($jenis) {
                                    foreach ($jenis->result() as $key) { ?>
                                        <option value="<?= $key->i_jenis_faktur;?>"><?= $key->e_jenis_faktur_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>                     
                        <div class="col-sm-3">
                            <input type="text" id="ipajakawal" name="ipajakawal" class="form-control" value="">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="iseripajak" name="iseripajak" class="form-control" value="<?=$seripajak->i_seri_pajak_awal;?>"> 
                        </div>
                    </div>
                    <div class="form-group row">                                         
                        <label class="col-sm-2">Date from</label>
                        <label class="col-sm-10">Date to</label>   
                        <!-- <label class="col-sm-3">Nomor Nota from</label>  
                        <label class="col-sm-5">Nomor Nota to</label>   -->
                        <div class="col-sm-2">
                            <input type="text" id="jtawal" name="jtawal" class="form-control date" value="<?= $dfrom?>" readonly>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" id="jtakhir" name="jtakhir" class="form-control date" value="<?= $dto?>" readonly>
                        </div>       
                        <!-- <div class="col-sm-3">
                            <select name="inotafrom" id="inotafrom" disabled class="form-control select2">
                            </select>
                        </div>                   
                         <div class="col-sm-3">
                            <select name="inotato" id="inotato" disabled class="form-control select2">
                            </select>
                        </div>  -->
                        <div class="col-sm-2">
                            <button type="button" id="cari" class="btn btn-info btn-sm" onclick="getdata();"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="send" hidden="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $ldfrom."/".$ldto;?>','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            
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
        <h3 class="box-title m-b-0">Detail</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledata" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%"> 
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Jenis Faktur</th>
                        <th class="text-center">Nomor Faktur Penjualan</th>
                        <th class="text-center">Tanggal Faktur Penjualan</th>
                        <th class="text-center">Tanggal Jatuh Tempo</th>
                        <th class="text-center">Nilai Faktur</th>
                        <th class="text-center">Faktur Pajak Penjualan</th>
                        <th class="text-center">Nomor Faktur Pajak</th>
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
        $('.select2').select2();
        showCalendar('.date');
        $("#submit").attr("disabled", true);
        $("#cari").attr("disabled", true);

        $('#ijenis').change(function(event) {
            $("#inotafrom").attr("disabled", false);
            $("#cari").attr("disabled", false);
        });        

        $('#inotafrom').select2({
            placeholder: 'Pilih Nota',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getnota/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                        ijenis : $('#ijenis').val(),
                        jtawal : $('#jtawal').val(),
                        jtakhir : $('#jtakhir').val(),
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
            $("#inotato").attr("disabled", false);
        });

        $('#inotato').select2({
            placeholder: 'Pilih Nota',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getnota/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
                        ijenis : $('#ijenis').val(),
                        jtawal : $('#jtawal').val(),
                        jtakhir : $('#jtakhir').val(),
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
            $("#cari").attr("disabled", false);
        });
    });

    function getiseri(ijenis){
        $.ajax({
            type: "post",
            data: {
                'ijenis'     : $('#ijenis').val(),
            },
            url: '<?= base_url($folder.'/cform/getiseri'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iseripajak').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function getdata() {
        $("#submit").attr("disabled", false);
        var ijenis     = $("#ijenis").val(); 
        var jtawal     = $("#jtawal").val();
        var jtakhir    = $("#jtakhir").val();
        // var inotafrom  = $("#inotafrom").val();
        // var inotato    = $("#inotato").val();
//alert(ipartner);
        if (ijenis == "") {
            swal("Jenis Faktur Harus Di Pilih");
        } else {
            removeBody();
            $.ajax({
            type: "post",
            data: {
                    'ijenis'    : ijenis,
                    'jtawal'    : jtawal,
                    'jtakhir'   : jtakhir,
                    // 'inotafrom' : inotafrom,
                    // 'inotato'   : inotato
            },
            url: '<?= base_url($folder.'/cform/getdetail'); ?>',
            dataType: "json",
            success: function (data) {
                   
                    var total = 0;

                    $('#jml').val(data['detail'].length);

                    if (data['detail'].length == 0) {
                        swal("Tidak Ada Faktur Pada Tanggal Faktur Yang Dipilih");
                    }

                    var ipajakn = $('#iseripajak').val();
                    ipajakn     = ipajakn.split(".");
                    if(ipajakn[2].slice(0,5)== "00000"){
                        var aa = "00001";
                    }else if(ipajakn[2].slice(0,4)== "0000"){
                        var no = parseInt(ipajakn[2])+1;
                        var aa = "0000"+no;
                    }else if(ipajakn[2].slice(0,3)== "000"){
                        var no = parseInt(ipajakn[2])+1;
                        var aa = "000"+no;
                    }else if(ipajakn[2].slice(0,2)== "00"){
                        var no = parseInt(ipajakn[2])+1;
                        var aa = "00"+no;
                    }else if(ipajakn[2].slice(0,1)== "0"){
                        var no = parseInt(ipajakn[2])+1;
                        var aa = "0"+no;
                    }else{
                         var no = parseInt(ipajakn[2])+1;
                        var aa = no;
                    }
                    var pajak = ipajakn[0]+"."+ipajakn[1]+"."+aa;

                    for (let a = 0; a < data['detail'].length; a++) {
                        var zz = a+1;
                        //var zz = data['detail'][a]['no'];
                        var id          = data['detail'][a]['id'];
                        var ifaktur     = data['detail'][a]['i_document'];
                        var dfaktur     = data['detail'][a]['d_document'];
                        var djatuhtempo = data['detail'][a]['d_jatuh_tempo'];
                        var ifakturpjk  = data['detail'][a]['i_pajak'];
                        var vtotal      = data['detail'][a]['v_bersih'];
                        var jenisfaktur = data['detail'][a]['jenis_faktur'];
                        var ijenisfaktur = data['detail'][a]['i_jenis_faktur'];

                        if(ifakturpjk == null){
                            ifakturpjk = '';
                        }
                        var cols   = "";
                        var newRow = $("<tr>");
                        cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                        cols += '<td><input style="width:300px" class="form-control" readonly name="ejenisfaktur[]" value="'+jenisfaktur+'"><input type="hidden" class="form-control" readonly name="jenisfaktur[]" value="'+ijenisfaktur+'"></td>';
                        cols += '<td><input type="hidden" class="form-control" id="idfaktur'+zz+'" name="idfaktur[]" value="'+id+'"><input style="width:250px" class="form-control" readonly id="ifaktur'+zz+'" name="ifaktur[]" value="'+ifaktur+'"></td>';
                        cols += '<td><input style="width:150px" class="form-control" readonly id="dfaktur'+zz+'" name="dfaktur'+zz+'" value="'+dfaktur+'"></td>';
                        cols += '<td><input style="width:150px" class="form-control" readonly id="djatuhtempo'+zz+'" name="djatuhtempo'+zz+'" value="'+djatuhtempo+'"></td>';
                        cols += '<td><input style="width:200px" readonly class="form-control" style="text-align:right;" id="vtotal'+zz+'" name="vtotal'+zz+'" value="'+vtotal+'">'
                        cols += '<td><input style="width:200px" readonly class="form-control" style="text-align:right;" id="ifakturpjk'+zz+'" name="ifakturpjk'+zz+'" value="'+ifakturpjk+'">'
                        cols += '<td><input style="width:300px" type="text" id="ipajak'+ zz + '" class="form-control" name="ipajak[]" value="'+pajak+'"></td>';

                        newRow.append(cols);
                        $("#tabledata").append(newRow);  

                        if(zz > 1){
                            var ipajakn     = $('#ipajak'+zz).val();
                        }else{
                            var ipajakn     = $('#iseripajak').val();
                        }
                        ipajakn    = ipajakn.split(".");
                        var no = parseInt(ipajakn[2])+1;
                        
                        if(ipajakn[2].slice(0,5)== "00000"){
                            var aa = "00001";
                        }else if(ipajakn[2].slice(0,4)== "0000"){
                            var no = parseInt(ipajakn[2])+1;
                            var aa = "0000"+no;
                        }else if(ipajakn[2].slice(0,3)== "000"){
                            var no = parseInt(ipajakn[2])+1;
                            var aa = "000"+no;
                        }else if(ipajakn[2].slice(0,2)== "00"){
                            var no = parseInt(ipajakn[2])+1;
                            var aa = "00"+no;
                        }else if(ipajakn[2].slice(0,1)== "0"){
                            var no = parseInt(ipajakn[2])+1;
                            var aa = "0"+no;
                        }else{
                            var no = parseInt(ipajakn[2])+1;
                            var aa = no;
                        }

                        var pajak = ipajakn[0]+"."+ipajakn[1]+"."+aa;                            
                        $('#ipajak'+zz).val(pajak);

                    //lastpajak = parseInt(pajak)+1; 
                    } 
                },
                error: function () {
                    swal('Error');
                }
            });
        }
    }

    function removeBody(){
        var tbl = document.getElementById("tabledata");   // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
        $('#tabledata').append("<tbody></tbody>");
    }

    $( "#ijenis" ).change(function() {
        removeBody();
    });
    /*
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#cari").attr("disabled", true);
    });*/

    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ijenis').val()!='' && $('#ijenis').val()!= null || $('#iseripajak').val() == '')) {
            if ($('#jml').val()==0) {
                swal('Data Item Masih Kosong!');
                return false;
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }     
    });  
</script>