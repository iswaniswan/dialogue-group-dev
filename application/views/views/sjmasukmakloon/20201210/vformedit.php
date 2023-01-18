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
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-5">Bagian</label>
                        <label class="col-md-7">Nomor SJ Masuk</label>                        
                        <div class="col-sm-5">
                            <select name="ibagian" id="ibagian" class="form-control select2">
                                <?php foreach($kodemaster as $ibagian): ?>
                                <option value="<?php echo $ibagian->i_departement;?>" 
                                <?php if($ibagian->i_departement==$data->i_kode_master) { ?> selected="selected" <?php } ?>>
                                <?php echo $ibagian->e_departement_name;?></option>
                            <?php endforeach; ?> 
                        </select>
                        </div>
                        <div class="col-sm-6">
                         <input type="text" id="isjkm" name="isjkm" class="form-control date" value="<?=$data->i_sj;?>" readonly>
                        </div>
                        
                    </div>
                    <div class="form-group row">
                    <label class="col-md-12">Nomor Referensi</label>
                        <div class="col-sm-11">
                            <?php if ($ireferensi) {
                                $ireff = '';
                                foreach ($ireferensi as $kuy) {
                                    $ireff = $ireff."".$kuy->i_sj_reff." - ";
                                }
                            }?>
                            <textarea readonly=""  class="form-control text-left" required><?php if($ireff!=''){ echo substr($ireff, 0, -2);} ?></textarea>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-11">
                            <input type="text" id= "eremark "name="eremark" class="form-control" value="<?= $data->e_remark;?>">
                        </div>
                    </div>  
                    <div class="form-group">
                    <?if($data->i_status =='1'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                        <?}else if($data->i_status =='2'){?>
                         <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                        <?}else if($data->i_status =='3'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                        <?}else if($data->i_status =='7'){?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" disabled class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd"  class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                        <?}else{?>
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="cancel" disabled class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>                       
                        <?}?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal SJ Masuk</label>
                        <label class="col-md-8">Supplier</label>
                        <div class="col-sm-4">
                            <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?=$data->d_sj;?>" readonly>
                        </div>
                        <div class="col-sm-8">
                            <input type="hidden" id="esupplier" name="esupplier" class="form-control" value="<?=$data->i_supplier;?>" readonly>
                            <input type="text" id="supplier" name="supplier" class="form-control" readonly value="<?=$data->e_supplier_name;?>">
                            <input type="hidden" id="inodoksup" name="inodoksup" class="form-control" value="<?= $data->e_no_dok_supplier;?>">
                        </div>
                        
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Type Makloon</label>
                        <div class="col-sm-6">
                            <input type="hidden" id="itypemakloon" name="itypemakloon" class="form-control" readonly value="<?=$data->i_type_makloon;?>">
                            <input type="text" id="etypemakloon" name="etypemakloon" class="form-control" readonly value="<?=$data->e_type_makloon;?>"> 
                        </div>
                    </div>                  
                </div>
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <tr>
                                    <th>No</th>
                                    <th>Nomor Referensi</th>
                                    <th>Kode Barang ( Keluar )</th>
                                    <th>Satuan</th>
                                    <th>Qty</th>
                                    <th>Kode Barang ( Masuk )</th>
                                    <th>Satuan</th>
                                    <th>Qty</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                                $counter = 0; 
                                if ($detail){
                                    foreach ($detail as $row) {
                                        $counter++;?>
                                        <tr>
                                            <td style="text-align: center;"><?= $counter;?>
                                                <input type="hidden" class="form-control" readonly id="baris<?= $counter;?>" name="baris<?= $counter;?>" value="<?= $counter;?>">
                                            </td>
                                            <td class="text-center">
                                                <input style ="width:250px" type="text" class="form-control" readonly id="ireferensi<?= $counter;?>" name="ireferensi<?= $counter;?>" value="<?= $row->i_reff;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->material_keluar;?>" readonly="" type="hidden" id="imaterial1<?= $counter;?>" class="form-control" name="imaterial1<?= $counter;?>">
                                                <input style ="width:350px" value="<?= $row->nama_material_keluar;?>" readonly="" type="text" readonly id="ematerial1<?= $counter;?>" class="form-control" name="ematerial1<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->satuan_keluar;?>" readonly="" type="hidden" readonly id="isatuan1<?= $counter;?>" class="form-control" name="isatuan1<?=$counter;?>">
                                                <input style ="width:150px" value="<?= $row->nama_satuan_keluar;?>" readonly="" type="text" readonly id="esatuan1<?= $counter;?>" class="form-control" name="esatuan1<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input style ="width:100px" value="<?= $row->qty_keluar;?>" readonly="" type="text" readonly id="qty1<?= $counter;?>" class="form-control" name="qty1<?= $counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->material_masuk;?>" readonly="" type="hidden" id="imaterial2<?= $counter;?>" class="form-control" name="imaterial2<?= $counter;?>">
                                                <input style ="width:350px" value="<?= $row->nama_material_masuk;?>" readonly="" type="text" readonly id="ematerial2<?= $counter;?>" class="form-control" name="ematerial2<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->satuan_masuk;?>" readonly="" type="hidden" readonly id="isatuan2<?= $counter;?>" class="form-control" name="isatuan2<?=$counter;?>">
                                                <input value="<?= $row->nama_satuan_masuk;?>" readonly="" type="text" style ="width:100px" readonly id="esatuan2<?= $counter;?>" class="form-control" name="esatuan2<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input style ="width:100px" value="<?= $row->qty_masuk;?>" type="text" id="qty2<?= $counter;?>" class="form-control" name="qty2<?= $counter;?>">
                                            </td>
                                            <td>
                                                <input style ="width:350px" value="<?= $row->e_remark;?>" type="text" id="edesc<?= $counter;?>" class="form-control" name="edesc<?=$counter;?>">
                                            </td>
                                            <td style="text-align: center;">
                                                <input type="checkbox" id="chk<?= $counter;?>" name="chk<?= $counter;?>" onclick="setaction(<?= $counter;?>);" checked>
                                            </td>
                                        </td>
                                    </tr>
                                <?php }  
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="jml" id="jml" readonly value="<?= $counter;?>">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        del();
    });

    function getenabledcancel() {
        swal("Berhasil", "Cancel Dokumen", "success");
        $('#sendd').attr("disabled", true);
        $('#cancel').attr("disabled", true);
    }

    function getenabledsend() {
        swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
        $('#sendd').attr("disabled", true);
        $('#cancel').attr("disabled", true);
        $('#submit').attr("disabled", true);
    }

    $(document).ready(function(){
        $("#cancel").on("click", function () {
            var isjkm = $("#isjkm").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder.'/cform/cancel'); ?>",
                data: {
                         'isjkm'  : isjkm,
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
    });

    $(document).ready(function(){
        $("#sendd").on("click", function () {
            var isjkm = $("#isjkm").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder.'/cform/sendd'); ?>",
                data: {
                         'isjkm'  : isjkm,
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
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');

        $('#ireff').select2({
            placeholder: 'Cari No. SJ Keluar Makloon',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsjkm/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        gudang: $('#istore').val(),
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

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    // SJ Masuk Makloon

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function getstore() {
        var gudang = $('#ikodemaster').val();

        if (gudang == "") {
            $("#ireff").attr("disabled", true);
        } else {
            $('#istore').val(gudang);
            $("#ireff").attr("disabled", false);
        }
        
        $('#ireff').html('');
        $('#ireff').val('');
    }

    $("#ireff").change(function() {
        $('#ireff').val($(this).val());
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        var gudang = $('#istore').val();
        $.ajax({
            type: "post",
            data: {
                //'isjkm': sjkm,
                'gudang': gudang,
                'isjkm'  :  $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/getdetailsjkm'); ?>',
            dataType: "json",
            success: function (data) {
                $('#tabledata').attr('hidden', false);
                $('#jml').val(data['detail'].length);
                var isupplier = data['head']['i_supplier'];
                var esupplier = data['head']['e_supplier_name'];
                var ijeniskeluar = data['head']['i_jenis_keluar'];
                var ejeniskeluar = data['head']['e_jenis_keluar'];
                $('#supplier').val(isupplier);
                $('#esupplier').val(esupplier);
                $('#jnskeluar').val(ijeniskeluar);
                $('#ejnskeluar').val(ejeniskeluar);
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var isjkm          = data['detail'][a]['i_sj'];
                    var i_material     = data['detail'][a]['i_material'];
                    var e_material     = data['detail'][a]['e_material_name'];
                    var n_qty          = data['detail'][a]['n_qty'];
                    var i_satuan       = data['detail'][a]['i_satuan'];
                    var e_satuan       = data['detail'][a]['e_satuan'];
                    var namabarang     = i_material + ' - ' + e_material;
                    var cols   = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"><input type="hidden" readonly id="i_material'+zz+'" name="i_material'+zz+'" value="'+i_material+'"></td>';
                    cols += '<td style="text-align: center"><input type="text" class="form-control" readonly id="ireferensi'+zz+'" name="ireferensi'+zz+'" value="'+isjkm+'"></td>';
                    cols += '<td><input class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+namabarang+'" value="'+namabarang+'"></td>';
                    cols += '<td><input readonly class="form-control" style="text-align:left;" id="n_qty'+zz+'" name="n_qty'+zz+'" value="'+n_qty+'"></td>';
                    cols += '<td><input type="hidden" class="form-control" style="text-align:left;" id="i_satuan'+zz+'" name="i_satuan'+zz+'" value="'+i_satuan+'"><input readonly class="form-control" style="text-align:left;" id="e_satuan'+zz+'" name="e_satuan'+zz+'" value="'+e_satuan+'"></td>';
                    cols += '<td><select type="text" style=width:320px id="i_2material'+zz+'" class="form-control" name="i_2material'+ zz + '" value="" onchange="getmaterial('+ zz + ');"></td>';
                    cols += '<td><input type="text" id="n_2qty'+ zz + '" class="form-control" placeholder="0" name="n_2qty'+ zz + '" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
                    cols += '<td><input type="hidden" class="form-control" style="text-align:left;" id="i_2satuan'+zz+'" name="i_2satuan'+zz+'" value=""><input readonly class="form-control" style="text-align:left;" id="e_2satuan'+zz+'" name="e_2satuan'+zz+'" value=""></td>';
                    cols += '<td><input type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value=""/></td>';
                    cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" onclick="setaction('+zz+');"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    $('#i_2material'+zz).select2({
                        placeholder: 'Pilih Material',
                        allowClear: true,
                        ajax: {
                            url: '<?= base_url($folder);?>/cform/datamaterial/'+gudang,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                var query   = {
                                    q       : params.term
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
                }
            },
            error: function () {
                swal('Data Kosong :)');
            }
        });
        xx = $('#jml').val();
    }); 

    function getmaterial(id){
        var imaterial = $('#i_2material'+id).val();
        $.ajax({
            type: "post",
            data: {
                'i_material': imaterial
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                $('#e_2satuan'+id).val(data[0].e_satuan);
                $('#i_2satuan'+id).val(data[0].i_satuan_code);
                $('#chk'+id).prop("checked", true);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function setaction(id) {
        if($('#chk'+id).prop('checked')) {

        } else {
            $('#i_2satuan'+id).val("");
            $('#e_2satuan'+id).val("");
            $('#i_2material'+id).val("").change();
            $('#n_2qty'+id).val("");
        }
    }


    function validasi(){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qtypp   =document.getElementById("nquantity"+i).value;
            qtypm =document.getElementById("pemenuhan"+i).value;
            if(parseFloat(qtypm)>parseFloat(qtypp)){
                swal('Jumlah Pemenuhan Melebihi Permintaan');
                document.getElementById("pemenuhan"+i).value='';
                break;
            }else if(parseFloat(qtypm)=='0'){
                swal('Jumlah Pemenuhan tidak boleh kosong')
                document.getElementById("pemenuhan"+i).value='';
                break;
            }
        }
    }

    function cek() {
        var dsjk = $('#dsjk').val();
        var isjkm = $('#isjkm').val();
        var istore = $('#istore').val();

        if (dsjk == '' || isjkm == null || istore == '') {
            swal('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }
</script>