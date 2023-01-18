<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Gudang</label>
                        <label class="col-md-2">Tanggal</label>
                        <label class="col-md-3">No Permintaan</label>
                        <label class="col-md-2">Tanggal Permintaan</label>
                        <label class="col-md-3">Departement</label>
                        <div class="col-sm-2">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2" onchange="getstore();">
                                <?php foreach ($kodemaster->result() as $row):?>
                                    <option value="<?php echo $row->i_departement;?>"> <?= $row->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbonk" name="dbonk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select required="" id="imemo" name="imemo" class="form-control" onchange="getdetailmemo();"></select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dmemo" name="dmemo" class="form-control" value="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select required="" id="idepartement" name="idepartement" class="form-control"></select>
                        </div>
                    </div>                
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark "name="eremark" class="form-control"></textarea>
                        </div>
                    </div>   
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp; 
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0">
                <div class="table-responsive">
                    <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Kode Barang</th>
                                <th class="text-center" width="40%">Nama Barang</th>
                                <th class="text-center">Qty Outstanding</th>
                                <th class="text-center">Qty Keluar</th>
                                <th class="text-center">Keterangan</th>
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
<script>
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');

        $('#imemo').select2({
            placeholder: 'Cari No. Permintaan',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getmemo/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var gudang = $('#istore').val();
                    var query = {
                        q: params.term,
                        gudang: gudang
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

        $('#idepartement').select2({
            placeholder: 'Pilih Departement',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getdepartemen/'); ?>',
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
    });

    function getstore() {
        var gudang = $('#ikodemaster').val();

        if (gudang == "") {
            $("#imemo").attr("disabled", true);
        } else {
            $('#istore').val(gudang);
            $("#imemo").attr("disabled", false);
        }

        $('#imemo').html('');
        $('#imemo').val('');
    }

    function getdetailmemo() {
        var imemo = $('#imemo').val();
        var gudang = $('#istore').val();
        if (imemo!='') {

        }else{

        }
        $.ajax({
            type: "post",
            data: {
                'imemo': imemo,
                'gudang': gudang
            },
            url: '<?= base_url($folder.'/cform/getdetailmemo'); ?>',
            dataType: "json",
            success: function (data) {
                $("#tabledata tr:gt(0)").remove();       
                $("#jml").val(0);
                var iop = data['head']['i_op_code'];
                var d_op = data['head']['d_op'];
                $('#dmemo').val(d_op);
                $('#jml').val(data['detail'].length);
                var gudang = $('#istore').val();
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var i_material    = data['detail'][a]['i_product'];
                    var e_material    = data['detail'][a]['e_material_name'];
                    var n_qty         = data['detail'][a]['n_order'];

                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                    cols += '<td><input type="text" class="form-control" readonly id="i_material'+zz+'" name="i_material'+zz+'" value="'+i_material+'"></td>';
                    cols += '<td><input class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+e_material+'" value="'+e_material+'"></td>';
                    cols += '<td><input readonly class="form-control text-right" style="text-align:left;" id="n_qtyout'+zz+'" name="n_qtyout'+zz+'" value="'+n_qty+'"></td>';
                    cols += '<td><input type="text" class="form-control text-right" onkeypress="return hanyaAngka(event);" id="n_qtykeluar'+zz+'" name="n_qtykeluar'+zz+'" value=""></td>';
                    cols += '<td><input type="text" class="form-control" style="text-align:left;" id="edesc'+zz+'" name="edesc'+zz+'" value=""></td>';

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
                swal('Error :)');
            }
        });
    }

    function cek() {
        var dbonk = $('#dbonk').val();
        var imemo = $('#imemo').val();
        var istore = $('#istore').val();

        if (dbonk == '' || imemo == null || istore == '') {
            swal('Data Header Belum Lengkap !!');
            return false;
        } else {
            return true;
        }
    }
</script>