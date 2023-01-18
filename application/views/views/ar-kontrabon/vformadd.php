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
                        <label class="col-sm-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-sm-3">Jenis Faktur</label>
                        <div class="col-sm-3">
                        <select class="form-control select2" name="ibagian" id="ibagian">
                            <?php foreach ($bagian as $ibagian):?>
                            <option value="<?php echo $ibagian->i_bagian;?>">
                                <?= $ibagian->e_bagian_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control date" onchange="number();" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ijenis" id="ijenis" class="form-control select2">
                                <?php if ($jenis) {
                                    foreach ($jenis->result() as $key) { ?>
                                        <option value="<?= $key->i_jenis_faktur;?>"><?= $key->e_jenis_faktur_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>  
                        <label class="col-sm-2">Jumlah</label> 
                        <label class="col-sm-1"></label>                      
                        <label class="col-sm-2">Jatuh Tempo Awal</label>
                        <label class="col-sm-4">Jatuh Tempo Akhir</label>
                        <div class="col-sm-3">
                            <select name="ipartner" id="ipartner" class="form-control select2">
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="jumlah" name="jumlah" class="form-control" value="0" readonly>
                            <input type="hidden" id="sisa" name="sisa" class="form-control" value="0" readonly>
                        </div>
                        <div class="col-sm-1"></div>             
                        <div class="col-sm-2">
                            <input type="text" id="jtawal" name="jtawal" class="form-control date" value="<?= $dfrom?>" readonly>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" id="jtakhir" name="jtakhir" class="form-control date" value="<?= $dto?>" readonly>
                        </div>                        
                        <div class="col-sm-2">
                            <button type="button" id="cari" class="btn btn-info btn-sm" onclick="getdata();"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                    <div class="form-group row">                       
                        <label class="col-md-12">Keterangan</label>                    
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control"></textarea>
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
                        <th class="text-center">Nomor Faktur Penjualan</th>
                        <th class="text-center">Tanggal Faktur Penjualan</th>
                        <th class="text-center">Nomor Faktur Pajak Penjualan</th>
                        <th class="text-center">Tanggal Faktur Pajak Penjualan</th>
                        <th class="text-center">Tanggal Jatuh Tempo</th>
                        <th class="text-center">Nilai Faktur</th>
                        <th class="text-center">Sisa</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Action</th>
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
        $('#idocument').mask('SSS-0000-000000S');

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
                        ijenis : $('#ijenis').val(),
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
            removeBody();
            $("#jumlah").val(0);
            $("#sisa").val(0);
            $("#jml").val(0);
        });

        $('#send').click(function(event) {
            statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $ldfrom."','".$ldto;?>');
        });

    });

    function getdata() {
        var ipartner   = $("#ipartner").val();
        var ijenis     = $("#ijenis").val(); 
        var jtawal     = $("#jtawal").val();
        var jtakhir    = $("#jtakhir").val();
//alert(ipartner);
        if (ipartner == null) {
            swal("Partner Harus Di Isi");
        } else {
            removeBody();
            $.ajax({
            type: "post",
            data: {
                    'ipartner'  : ipartner,
                    'ijenis'    : ijenis,
                    'jtawal'    : jtawal,
                    'jtakhir'   : jtakhir
            },
            url: '<?= base_url($folder.'/cform/getdetail'); ?>',
            dataType: "json",
            success: function (data) {
                   
                    var total = 0;

                    $('#jml').val(data['detail'].length);

                    if (data['detail'].length == 0) {
                        swal("Tidak Ada Faktur Pada Tanggal Jatuh Tempo Yang Dipilih");
                    }
                    //var gudang = $('#istore').val();
                    var lastsj = '';
                    for (let a = 0; a < data['detail'].length; a++) {
                        var zz = a+1;
                        //var zz = data['detail'][a]['no'];
                        var id          = data['detail'][a]['id'];
                        var ifaktur     = data['detail'][a]['i_document'];
                        var dfaktur     = data['detail'][a]['d_document'];
                        var ipajak      = data['detail'][a]['i_pajak'];
                        var dpajak      = data['detail'][a]['d_pajak'];
                        var djatuhtempo = data['detail'][a]['d_jatuh_tempo'];
                        var vtotal      = data['detail'][a]['v_bersih'];
                        var vsisa       = data['detail'][a]['v_sisa'];
                    
                        vtotal = formatcemua(vtotal);
                        vsisa  = formatcemua(vsisa);
                        if(dpajak == null || ipajak == null){
                            dpajak = "";
                            ipajak = "";
                        }

                        var cols   = "";
                        var newRow = $("<tr>");
                        cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                        cols += '<td><input type="hidden" class="form-control" id="idfaktur'+zz+'" name="idfaktur'+zz+'" value="'+id+'"><input style="width:180px" class="form-control" readonly id="ifaktur'+zz+'" name="ifaktur'+zz+'" value="'+ifaktur+'"></td>';
                        cols += '<td><input style="width:120px" class="form-control" readonly id="dfaktur'+zz+'" name="dfaktur'+zz+'" value="'+dfaktur+'"></td>';
                        cols += '<td><input style="width:250px" class="form-control" readonly id="ipajak'+zz+'" name="ipajak'+zz+'" value="'+ipajak+'"></td>';
                        cols += '<td><input style="width:250px" class="form-control" readonly id="dpajak'+zz+'" name="dpajak'+zz+'" value="'+dpajak+'"></td>';
                        cols += '<td><input style="width:120px" class="form-control" readonly id="djatuhtempo'+zz+'" name="djatuhtempo'+zz+'" value="'+djatuhtempo+'"></td>';
                        cols += '<td><input style="width:150px" readonly class="form-control" style="text-align:right;" id="vtotal'+zz+'" name="vtotal'+zz+'" value="'+vtotal+'">'
                        cols += '<td><input style="width:150px" readonly class="form-control" style="text-align:right;" id="vsisa'+zz+'" name="vsisa'+zz+'" value="'+vsisa+'">'
                        cols += '<td><input style="width:200px" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value=""/></td>';
                        cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" value="cek" checked/></td>';
                        newRow.append(cols);
                        $("#tabledata").append(newRow);
                        
                        $("#chk"+zz).click(function () {
                            ngetang();
                        });          
                    }    
                    ngetang();
                },
                error: function () {
                    swal('Error');
                }
            });
        }
    }

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        var total2 = 0;
        var total3 = 0;
        for(brs=1;brs<=jml;brs++){  
        //alert(jml);  
            vtotal  = formatulang($("#vtotal"+brs).val());
            vsisa   = formatulang($("#vsisa"+brs).val());
            if($("#chk"+brs).is(':checked')){
                total2+=parseFloat(vtotal);
                total3+=parseFloat(vsisa);
            }
        }
        $('#jumlah').val(formatcemua(total2));
        $('#sisa').val(formatcemua(total3));
    }

    function removeBody(){
        var tbl = document.getElementById("tabledata");   // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
        $('#tabledata').append("<tbody></tbody>");
    }

    $( "#ijenis" ).change(function() {
        removeBody();
    });
    
    //new script
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl'     : $('#ddocument').val(),
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $( "#submit" ).click(function(event) {
        //ada = false;
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#ipartner').val()!='' || $('#ipartner').val())) {
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