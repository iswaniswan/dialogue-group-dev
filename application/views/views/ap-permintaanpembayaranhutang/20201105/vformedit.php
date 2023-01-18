<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>

                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Nomor Permintaan</label>
                        <label class="col-md-3">Partner</label>
                        <label class="col-sm-3">Jenis Faktur</label>
                        <label class="col-md-2">Tanggal Dokumen Permintaan</label>
                        <label class="col-md-2">Tanggal Permintaan Dibayar</label>
                        <div class="col-sm-2">
                            <input type="text" id="i_pembayaran" name="i_pembayaran" class="form-control" value="<?php echo $data->i_pembayaran; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                           <select name="partner" id="partner" class="form-control select2" onchange="getdata();">
                                <option value=""></option>
                                <?php if ($partner) {
                                    foreach ($partner->result() as $key) { ?>
                                        <?php if ($key->i_supplier == $data->partner) { ?>
                                        <option value="<?= $key->i_supplier;?>" selected><?= $key->e_supplier_name;?></option> 
                                        <?php } else { ?>
                                        <option value="<?= $key->i_supplier;?>"><?= $key->e_supplier_name;?></option>     
                                        <?php } ?>
                                    <?php }
                                } ?>  
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <select name="jenis" id="jenis" class="form-control select2" onchange="getdata();">
                                <option value="semua" selected> Semua Faktur</option>
                                  <?php if ($jenis) {
                                    foreach ($jenis->result() as $key) { ?>
                                        <option value="<?= $key->i_jenis;?>"><?= $key->i_jenis." - ".$key->e_jenis_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="dpermintaan" name="dpermintaan" class="form-control date" value="<?php echo $data->tanggal; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbayar" name="dbayar" class="form-control date" value="<?php echo $data->permintaan_pembayaran; ?>" readonly>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-2">(Filter) Tempo Awal</label>
                        <label class="col-sm-2">(Filter) Jatuh Tempo Akhir</label>
                        <label class="col-sm-2">Jumlah</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-2">
                            <input type="text" id="jtawal" name="jtawal" class="form-control date" value="<?php echo $dfrom; ?>" readonly >
                        </div>
                        <div class="col-sm-2"> 
                            <input type="text" id="jtakhir" name="jtakhir" class="form-control date" value="<?php echo $dto; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="jumlah" name="jumlah" class="form-control" value="<?php echo $data->v_total; ?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <textarea id= "eremark" name="eremark" class="form-control"><?php echo $data->e_remark; ?></textarea>
                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0">
                <div class="col-md-12">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%"> 
                            <thead>
                                <tr>
                                    <th class="text-center" >No</th>
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
            </form>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        //
        showCalendar('#dpermintaan',1830,0);
        showCalendar('.date');
        // showCalendar('#dback',0,1830);


        $('#partner').select2({
            placeholder: 'Pilih Partner Makloon',
        })

        

        $("#send").attr("disabled", true);
        $("#send").on("click", function () {
            var kode = $("#kode").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder.'/cform/send'); ?>",
                data: {
                         'kode'  : kode,
                        },
                dataType: 'json',
                delay: 250, 
                success: function(data) {
                    return {
                    results: data
                    };
                },
                 cache: true
            });
        });

        getdataedit();
    });

    $("#jtawal").change(function(){
       getdata();
    });

    $("#jtakhir").change(function(){
       getdata();
    }); 

    function getdata() {
        var partner  = $("#partner").val();
        var jenis    = $("#jenis").val(); 
        var jtawal   = $("#jtawal").val();
        var jtakhir  = $("#jtakhir").val();
        //swal(sj, partner);
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
                //var gudang = $('#istore').val();
                var lastsj = '';
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = data['detail'][a]['no'];
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
                    cols += '<td><input style="width:180px" class="form-control" readonly id="i_nota'+zz+'" name="i_nota'+zz+'" value="'+i_nota+'"></td>';
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
                swal('Error :)');
            }
        });
    }

    function getdataedit() {
        var partner  = $("#partner").val();
        var jenis    = $("#jenis").val(); 
        var i_pembayaran   = $("#i_pembayaran").val();
        //swal(sj, partner);
        removeBody();

        $.ajax({
        type: "post",
        data: {
            'partner': partner,
            'jenis'  : jenis,
            'i_pembayaran' : i_pembayaran,
        },
        url: '<?= base_url($folder.'/cform/getdetailedit'); ?>',
        dataType: "json",
        success: function (data) {
               
                var total = 0;

                $('#jml').val(data['detail'].length);
                //var gudang = $('#istore').val();
                var lastsj = '';
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = data['detail'][a]['no'];
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
                    cols += '<td><input style="width:180px" class="form-control" readonly id="i_nota'+zz+'" name="i_nota'+zz+'" value="'+i_nota+'"></td>';
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
                swal('Error :)');
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);

    });

    function getenabledsend() {
        $('#send').attr("disabled", true);
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        swal('Berhasil Di Send');
    }
</script>