<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
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
                        <label class="col-md-4">Tanggal Permintaan Dibayar</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian==$data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="ijenis" id="ijenis" value="<?= $ijenis; ?>">
                                <input type="hidden" name="ippapold" id="ippapold" value="<?= $data->i_ppap;?>">
                                <input type="text" name="ippap" id="ippap" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="<?= $data->i_ppap;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="dppap" name="dppap" class="form-control date" required="" readonly value="<?=  $data->d_ppap ?>">
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="drppap" name="drppap" class="form-control date" required="" readonly value="<?= $data->d_req_ppap ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Partner</label>
                        <label class="col-sm-3">Jenis Faktur</label>
                        <label class="col-sm-2">Jatuh Tempo Awal</label>
                        <label class="col-sm-4">Jatuh Tempo Akhir</label>
                        <div class="col-sm-3">
                           <select name="partner" id="partner" class="form-control select2">
                                <option value=""></option>
                                <?php if ($partner) {
                                    foreach ($partner->result() as $key) { ?>
                                        <option value="<?= $key->i_supplier;?>"
                                            <?php if ($key->i_supplier==$data->i_supplier) {?> selected <?php } ?>>
                                            <?=$key->e_supplier_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <select name="jenis" id="jenis" class="form-control select2">
                                <?php if($jenis){
                                    foreach($jenis->result() as $key){?>
                                        <option value="<?=$key->i_jenis_faktur;?>"
                                            <?php if($key->i_jenis_faktur == $data->i_jenis_faktur){?>
                                            selected
                                            <?}?>>
                                            <?=$key->e_jenis_faktur_name;?>
                                        </option>
                                    <?}
                                }?>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="jtawal" name="jtawal" class="form-control date" value="" readonly>
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" id="jtakhir" name="jtakhir" class="form-control date" value="" readonly>
                        </div>
                        
                        <div class="col-sm-2">
                            <button type="button" id="cari" class="btn btn-info btn-sm" onclick="getdata();"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Jumlah</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" id="jumlah" name="jumlah" class="form-control" value="<?php echo $data->v_total; ?>" 
                            readonly>
                        </div>
                        <div class="col-sm-7">
                            <textarea id= "eremark" name="eremark" class="form-control"><?php echo $data->e_remark; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                         <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $ldfrom."/".$ldto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0">
            
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
            <table id="tabledata" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%"> 
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nomor Faktur</th>
                        <th class="text-center">Tanggal Faktur</th>
                        <th class="text-center">Jenis Faktur</th>
                        <th class="text-center">Jatuh Tempo</th>
                        <th class="text-center">Jumlah</th>
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
        $('#ippap').mask('SSSS-0000-000000S');
        showCalendar('.date');
        // showCalendar('#dback',0,1830);

        $('#partner').select2({
            placeholder: 'Pilih Partner Supplier',
        })

        $('#send').click(function(event) {
            statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $ldfrom."','".$ldto;?>');
        });

        $('#cancel').click(function(event) {
            statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $ldfrom."','".$ldto;?>');
        });

        $('#hapus').click(function(event) {
            statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $ldfrom."','".$ldto;?>');
        });

        getdataedit();

    });

    function getdata() {
        var partner  = $("#partner").val();
        var jenis    = $("#jenis").val(); 
        var jtawal   = $("#jtawal").val();
        var jtakhir  = $("#jtakhir").val();
        //swal(sj, partner);

        if (jtawal == "" || jtakhir == "" ) {
            swal("Tanggal Jatuh Tempo Harus Di isi");
        } else {
            removeBody();
            $.ajax({
            type: "post",
            data: {
                'partner': partner,
                'jenis'  : jenis,
                'jtawal' : jtawal,
                'jtakhir': jtakhir
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
                        var zz = data['detail'][a]['no'];
                        var id      = data['detail'][a]['id'];
                        var i_nota      = data['detail'][a]['i_nota'];
                        var d_nota      = data['detail'][a]['d_nota'];
                        var jenis       = data['detail'][a]['jenis'];
                        var ijenis      = data['detail'][a]['i_jenis'];
                        var jatuh_tempo = data['detail'][a]['jatuh_tempo'];
                        var saldo       = data['detail'][a]['saldo'];
                    
                        total = total + parseFloat(saldo);
                        v_saldo = formatcemua(saldo);

                        var cols        = "";
                        var newRow = $("<tr>");
                        cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                        cols += '<td><input type="hidden" class="form-control" id="id_nota'+zz+'" name="id_nota'+zz+'" value="'+id+'"><input style="width:180px" class="form-control" readonly id="i_nota'+zz+'" name="i_nota'+zz+'" value="'+i_nota+'"></td>';
                        cols += '<td><input style="width:120px" class="form-control" readonly id="d_nota'+zz+'" name="d_nota'+zz+'" value="'+d_nota+'"></td>';
                        cols += '<td><input style="width:250px" class="form-control" readonly id="jenis'+zz+'" name="jenis'+zz+'" title="'+jenis+'" value="'+jenis+'"><input style="width:200px" class="form-control" type="hidden" readonly id="ijenis'+zz+'" name="ijenis'+zz+'" title="'+ijenis+'" value="'+ijenis+'"></td>';
                        cols += '<td><input style="width:120px" class="form-control" readonly id="jatuh_tempo'+zz+'" name="jatuh_tempo'+zz+'" value="'+jatuh_tempo+'"></td>';
                        cols += '<td><input style="width:150px" readonly class="form-control" style="text-align:right;" id="v_saldo'+zz+'" name="v_saldo'+zz+'" value="'+v_saldo+'">'
                        cols += '<td><input style="width:200px" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value=""/></td>';
                        cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" checked/></td>';
                        newRow.append(cols);
                        $("#tabledata").append(newRow);
                        
                        $("#chk"+zz).click(function () {
                            // var clas = $(this).attr('class');
                            // $('.'+clas).prop("checked",$(this).prop("checked"));
                            ngetang();
                        });
          
                    }    
                    $('#jumlah').val("Rp. "+formatcemua(total));
                },
                error: function () {
                    swal('Error');
                }
            });
        }
    }

    function getdataedit() {
        var id  = $("#id").val();
        var ijenis = $("#ijenis").val();
        //swal(sj, partner);
        removeBody();
        $.ajax({
        type: "post",
        data: {
            'id': id,
            'ijenis' : ijenis,
        },
        url: '<?= base_url($folder.'/cform/getdetailedit'); ?>',
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
                    var zz = data['detail'][a]['no'];
                    var id      = data['detail'][a]['id'];
                    var i_nota      = data['detail'][a]['i_nota'];
                    var d_nota      = data['detail'][a]['d_nota'];
                    var jenis       = data['detail'][a]['jenis'];
                    var ijenis      = data['detail'][a]['i_jenis'];
                    var jatuh_tempo = data['detail'][a]['jatuh_tempo'];
                    var saldo       = data['detail'][a]['saldo'];
                    var eremark       = data['detail'][a]['e_remark'];
                
                    total = total + parseFloat(saldo);
                    v_saldo = formatcemua(saldo);

                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                    cols += '<td><input type="hidden" class="form-control" id="id_nota'+zz+'" name="id_nota'+zz+'" value="'+id+'"><input style="width:180px" class="form-control" readonly id="i_nota'+zz+'" name="i_nota'+zz+'" value="'+i_nota+'"></td>';
                    cols += '<td><input style="width:120px" class="form-control" readonly id="d_nota'+zz+'" name="d_nota'+zz+'" value="'+d_nota+'"></td>';
                    cols += '<td><input style="width:250px" class="form-control" readonly id="jenis'+zz+'" name="jenis'+zz+'" title="'+jenis+'" value="'+jenis+'"><input style="width:200px" class="form-control" type="hidden" readonly id="ijenis'+zz+'" name="ijenis'+zz+'" title="'+ijenis+'" value="'+ijenis+'"></td>';
                    cols += '<td><input style="width:120px" class="form-control" readonly id="jatuh_tempo'+zz+'" name="jatuh_tempo'+zz+'" value="'+jatuh_tempo+'"></td>';
                    cols += '<td><input style="width:150px" readonly class="form-control" style="text-align:right;" id="v_saldo'+zz+'" name="v_saldo'+zz+'" value="'+v_saldo+'">'
                    cols += '<td><input style="width:200px" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value="'+eremark+'"/></td>';
                    cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" checked/></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    
                    $("#chk"+zz).click(function () {
                        // var clas = $(this).attr('class');
                        // $('.'+clas).prop("checked",$(this).prop("checked"));
                        ngetang();
                    });
      
                }    
                $('#jumlah').val("Rp. "+formatcemua(total));
            },
            error: function () {
                swal('Error');
            }
        });
    }
    

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        /*var tot = 0;*/
        var total2 = 0;
        for(brs=1;brs<=jml;brs++){    
            // ord = $("#qty_belumnota"+brs).val();
            v_saldo  = formatulang($("#v_saldo"+brs).val());
            // qty  = formatulang(ord);
           
            // vhrg = parseFloat(hrg)*parseFloat(qty)-parseFloat(formatulang($("#discount"+brs).val()));
            //$("#hargatotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                total2+=parseFloat(v_saldo);
                // discount2 += parseFloat(formatulang($("#discount"+brs).val()));
                // gross2  += parseFloat(hrg)*parseFloat(qty);
            }
        }

        $('#jumlah').val("Rp. "+formatcemua(total2));
    }

    function removeBody(){
        var tbl = document.getElementById("tabledata");   // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
        $('#tabledata').append("<tbody></tbody>");
    }

    function konfirm() {
        var jml = $('#jml').val();
        var myString = Number($("#jumlah").val().replace(/\D/g,''));
        if ($('#partner').val()!='') {
            if(jml==0 || myString == 0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                return true;
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }
    }

    
    //new script

    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#dppap').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ippap').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#ippap").attr("readonly", false);
        }else{
            $("#ippap").attr("readonly", true);
            $("#ippap").val($("#ippapold").val());

        }
    });

    $( "#ippap" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
                'kodeold' : $('#ippapold').val(),
                'ibagian' : $('#ibagian').val(),
            },
            url: '<?= base_url($folder.'/cform/cekkodeedit'); ?>',
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

    $('#ibagian').change(function(event) {
        number();
    });
</script>