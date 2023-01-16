<style>
        td {
            white-space: nowrap;
        }
        .withscroll {
            width: 1800px;
            overflow-x: scroll;
            white-space: nowrap;
        }
    </style>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                <div id="pesan"></div> 
                    <div class="form-group row">
                        <label class="col-sm-4">Permintaan ke Gudang</label>
                        <label class="col-sm-4">Nomor Permintaan</label>
                        <label class="col-sm-4">Tanggal Permintaan</label>
                        <div class="col-sm-4">
                             <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="">
                                <option value="<?=$head->i_kode_master;?>" selected><?=$head->e_nama_master;?></option>
                                <?php foreach ($kodemaster as $ikodemaster):?>
                                    <?php if ($ikodemaster->i_kode_master == $head->i_kode_master) { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>" selected><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $ikodemaster->i_kode_master;?>"><?= $ikodemaster->e_nama_master;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="istore" name="istore" class="form-control" value="<?php echo $head->i_kode_master?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="i_bonmk" name="i_bonmk" class="form-control" readonly maxlength="" value="<?php echo $head->i_permintaan;?>">
                        </div>
                        <div class="col-sm-4">
                             <input type="text" id="dbonk" name="dbonk" class="form-control date" value="<?php echo $head->d_pp;?>" readonly>
                        </div>
                    </div>        
                    <div class="form-group row">
                        <label class="col-md-8">Jenis Pengeluaran</label>
                        <label class="col-md-4">Tanggal Pengembalian</label>
                        <div class="col-sm-8">
                            <select name="jenispengeluaran" id="jenispengeluaran" class="form-control select2" disabled="">
                                <option value="" selected>-- Pilih Jenis Pengeluaran  --</option>
                                <?php foreach ($jeniskeluar as $jeniskeluar):?>
                                    <?php if ($jeniskeluar->i_jenis == $head->jenis_pengeluaran) { ?>
                                    <option value="<?php echo $jeniskeluar->i_jenis;?>" selected><?= $jeniskeluar->e_nama_jenis?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $jeniskeluar->i_jenis;?>"><?= $jeniskeluar->e_nama_jenis;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                             <input type="text" id="dback" name="dback" class="form-control" value="<?php echo $head->d_back;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark;?>" disabled>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tujuan Keluar</label>
                        <label class="col-md-6">Departemen</label>
                        <div class="col-sm-6">
                            <select name="tujuankeluar" id="tujuankeluar" class="form-control select2" onchange="getpic(this.value)" disabled="">
                                <option value="internal" <?php if($head->tujuan_keluar =='internal') { ?> selected <?php } ?> >Internal</option>
                                <option value="external" <?php if($head->tujuan_keluar =='external') { ?> selected <?php } ?> >Eksternal</option> 
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="edept" id="edept" class="form-control select2" disabled="">
                                <option value="<?=$head->partner;?>"><?=$head->partner_name;?></option>
                                <?php foreach ($partner as $partner):?>
                                    <?php if ($partner->id == $head->partner) { ?>
                                    <option value="<?php echo $partner->id;?>" selected><?= $partner->partner;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $partner->id;?>"><?= $partner->partner;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">PIC</label>
                        <label class="col-md-6">Nama Peminjam</label>
                        <div class="col-sm-6">
                            <select name="ppic" id="ppic" class="form-control select2" disabled="">
                                <option value="<?=$head->pic?>"><?=$head->epic;?></option>
                                <?php foreach ($karyawan as $karyawan):?>
                                    <?php if ($karyawan->id == $head->pic) { ?>
                                    <option value="<?php echo $karyawan->id;?>" selected><?= $karyawan->nama;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $karyawan->id;?>"><?= $karyawan->nama;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                         <div class="col-sm-6">
                            <input type="text" id="epic" name="epic" class="form-control" value="<?php echo $head->pic_eks;?>" disabled>
                        </div>
                    </div>
                </div>
                <?php 
                    if($head->jenis_pengeluaran == 'JK00001' || $head->jenis_pengeluaran == 'JK00003'){?>
                        <div class="panel-body table-responsive withscroll">
                            <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;" width="4%">No</th>
                                        <th style="text-align:center;" width="12%">Kode Barang</th>
                                        <th style="text-align:center;" width="25%">Nama Barang</th>
                                        <th style="text-align:center;" width="10%">Qty</th>
                                        <th style="text-align:center;" width="10%">Satuan</th>
                                        <th style="text-align:center;" width="20%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <input style ="width:50px"type="hidden" name="jml" id="jml" value="0">
                        </div>
                    <?}else if($head->jenis_pengeluaran == 'JK00002'){?>
                        <div class="panel-body table-responsive withscroll">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align:center;" width="4%">No</th>
                                    <th style="text-align:center;" width="7%">Kode Barang</th>
                                    <th style="text-align:center;" width="20%">Nama Barang</th>
                                    <th style="text-align:center;" width="5%">Qty</th>
                                    <th style="text-align:center;" width="7%">Satuan</th>
                                    <th style="text-align:center;" width="7%">Kode Barang</th>
                                    <th style="text-align:center;" width="20%">List Nama Barang</th>
                                    <th style="text-align:center;" width="5%">Qty</th>
                                    <th style="text-align:center;" width="7%">Satuan</th>
                                    <th style="text-align:center;" width="10%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="0">
                    </div>
                    <?}
                ?>
                    
                </div>
            </form>
        </div>
    </div>
</div>


<script>
getdetailpermintaan();
function getpic(tujuankeluar){
    //alert(tujuankeluar);
    $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getpic');?>",
            data:"tujuankeluar="+tujuankeluar,
            dataType: 'json',
            success: function(data){
                $("#edept").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong=='kopong') {
                    $("#edept").attr("disabled", true);
                }else{
                    $("#edept").attr("disabled", false);
                }
            },

            error:function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

        })
        if(tujuankeluar == 'external'){
            $("#diveks").attr("hidden", false);
        }else{
            $("#diveks").attr("hidden", true);
        }
}
$(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date');
});

$(document).ready(function () {
    $("#addrow").on("click", function () {
        var counter = $('#jml').val();
        counter++;
        $('#jml').val(counter);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]"></td>';
        cols += '<td rowspan="1"><select style="width:400px;" type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td rowspan="1"><input type="text" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td rowspan="1"><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]"/></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]"></td>';
        cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');"></td>';
        cols += '<td rowspan="1"><input type="text" id="nquantity2'+ counter + '" class="form-control" placeholder="0" name="nquantity2[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td rowspan="1"><input type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
        cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i></button></td>';
        cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        
        $('#ikodemaster').attr("disabled", true);
        var gudang = $('#istore').val();

        $('#ematerialname'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
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

        $('#ematerialname2'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
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
    });

    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
    });

    $("#tabledata").on("click", "#addrow2", function (event) {
        //$(this).closest('td').find('td').attr('rowspan','2');
        var row = $(this).closest("tr");
        var material = $(this).closest('tr').find('.imaterial').val();
        var nquantity = $(this).closest('tr').find('.nquantity').val();
        var isatuan = $(this).closest('tr').find('.isatuan').val();

        var counter = $('#jml').val();
        counter++;
        $('#jml').val(counter);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td colspan="5"><input style="width:100px;" type="hidden" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+material+'"><input type="hidden" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+nquantity+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+isatuan+'"/></td>';
        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]"></td>';
        cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');"></td>';
        cols += '<td rowspan="1"><input type="text" id="nquantity2'+ counter + '" class="form-control" placeholder="0" name="nquantity2[]" value="" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td rowspan="1"><input type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value=""/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" onkeyup="cekval(this.value);"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/></td>';
        cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Tambah Item" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i></button></td>';
        cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        newRow.insertAfter(row);
        var gudang = $('#istore').val();
        $('#ematerialname2'+counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
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
            

    });

});

function getmaterial(id){
        var ematerialname = $('#ematerialname'+id).val();
        $.ajax({
            type: "post",
            data: {
                'ematerialname': ematerialname
            },
            url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
            dataType: "json",
            success: function (data) {
                $('#imaterial'+id).val(data[0].i_material);
                $('#esatuan'+id).val(data[0].e_satuan);
                $('#isatuan'+id).val(data[0].i_satuan_code);
           ada=false;
            var a = $('#imaterial'+id).val();
            var e = $('#ematerialname'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
                if((a == $('#imaterial'+i).val()) && (i!=jml)){
                    swal ("kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }
            if(!ada){
                var ematerialname = $('#ematerialname'+id).val();
                $.ajax({
                    type: "post",
                    data: {
                         'ematerialname': ematerialname,
                    },
                    url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#ematerialname'+id).val(data[0].e_material_name);
                        $('#esatuan'+id).val(data[0].e_satuan);
                        $('#isatuan'+id).val(data[0].i_satuan_code);
                    },
                });
            }else{
                $('#imaterial'+id).html('');
                $('#imaterial'+id).val('');
                $('#ematerialname'+id).html('');
                $('#ematerialname'+id).val('');
                $('#esatuan'+id).val('');
                $('#isatuan'+id).val('');
                // $('#esatuan'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function getmaterial2(id){
        var ematerialname = $('#ematerialname2'+id).val();
        $.ajax({
                type: "post",
                data: {
                    'ematerialname': ematerialname
                },
                url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
                dataType: "json",
                success: function (data) {
                $('#imaterial2'+id).val(data[0].i_material);
                $('#ematerialname2'+id).val(data[0].e_material_name);
                $('#esatuan2'+id).val(data[0].e_satuan);
                $('#isatuan2'+id).val(data[0].i_satuan_code);
                    
            },
            error: function () {
                alert('Error :)');
            }
        });
}

 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#addrow").attr("disabled", true);
     $(".ibtnDel").attr("disabled", true);
 });

function getstore() {
        var gudang = $('#ikodemaster').val();
        //alert(gudang);
        $('#istore').val(gudang);

        if (gudang == "") {
            $("#addrow").attr("hidden", true);
        } else {
            $("#addrow").attr("hidden", false);
        }
        
}

function formatSelection(val) {
        return val.name;
}

function getdetailpermintaan() {
    var i_bonmk = $('#i_bonmk').val();
    var gudang = $('#istore').val();
    var jenispengeluaran = $('#jenispengeluaran').val();

    $.ajax({
        type: "post",
        data: {
            'ipermintaan': i_bonmk,
            'gudang': gudang
        },
        url: '<?= base_url($folder.'/cform/getdetailpermintaan'); ?>',
        dataType: "json",
        success: function (data) {
            $('#jml').val(data['detail'].length);
            var gudang = $('#istore').val();
            var lastmaterial;
            for (let a = 0; a < data['detail'].length; a++) {
                var counter = a+1;
                var i_material    = data['detail'][a]['i_material'];
                var e_material    = data['detail'][a]['e_material_name'];
                var n_qty         = data['detail'][a]['n_qty'];
                var i_satuan      = data['detail'][a]['i_satuan_code'];
                var e_satuan      = data['detail'][a]['e_satuan'];
                var i_material2   = data['detail'][a]['i_material2'];
                var e_material2   = data['detail'][a]['e_material_name2'];
                var n_qty2        = data['detail'][a]['n_qty2'];
                var i_satuan2     = data['detail'][a]['i_satuan_code2'];
                var e_satuan2     = data['detail'][a]['e_satuan2'];
                var e_remark      = data['detail'][a]['e_remark'];
                var cols          = "";
                var newRow        = $("<tr>");

                if(jenispengeluaran == 'JK00001' || jenispengeluaran == 'JK00003'){
                    if (lastmaterial == i_material) {
                        cols += '<td colspan="5"><input type="hidden" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+lastmaterial+'"><input type="hidden" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/></td>';
                        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" disabled name="edesc[]" value="'+e_remark+'"/></td>';
                    } else {
                        cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
                        cols += '<td rowspan="1"><input type="text" readonly class="form-control" id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+i_material+'" required></td>';
                        cols += '<td><select disabled class="form-control" type="text" id="ematerialname'+ counter + '" disabled class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"><option value="'+i_material+'" selected>'+e_material+'</option></td>';
                        cols += '<td rowspan="1"><input type="text" readonly class="form-control" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);"/></td>';
                        cols += '<td rowspan="1"><input type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="'+e_satuan+'" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/></td>';
                        cols += '<td><input type="text" disabled id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'"/></td>';
                    }
                }else if(jenispengeluaran == 'JK00002'){
                    if (lastmaterial == i_material) {
                        cols += '<td colspan="5"><input style="width:100px;" type="hidden" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+lastmaterial+'"><input type="hidden" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/></td>';

                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]" value="'+i_material2+'" required></td>';
                        cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" disabled name="ematerialname2[]" onchange="getmaterial2('+ counter + ');"><option value="'+i_material2+'" selected>'+e_material2+'</option></td>';
                        cols += '<td rowspan="1"><input type="text" id="nquantity2'+ counter + '" value="'+n_qty2+'" disabled class="form-control" placeholder="0" name="nquantity2[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
                        cols += '<td rowspan="1"><input type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="'+e_satuan2+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" value="'+i_satuan2+'"/></td>';
                        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" disabled name="edesc[]" value="'+e_remark+'"/></td>';
                    } else {
                        cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+i_material+'" required></td>';
                        cols += '<td><select style="width:400px;" disabled type="text" id="ematerialname'+ counter + '" disabled class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"><option value="'+i_material+'" selected>'+e_material+'</option></td>';
                        cols += '<td rowspan="1"><input type="text" disabled size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);"/></td>';
                        cols += '<td rowspan="1"><input type="text" disabled readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="'+e_satuan+'" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]" value="'+i_material2+'" required></td>';
                        cols += '<td><select type="text" style="width:400px;" disabled id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');"><option value="'+i_material2+'" selected>'+e_material2+'</option></td>';
                        cols += '<td rowspan="1"><input type="text" disabled id="nquantity2'+ counter + '" value="'+n_qty2+'" class="form-control" placeholder="0" name="nquantity2[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
                        cols += '<td rowspan="1"><input type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="'+e_satuan2+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" value="'+i_satuan2+'" onkeyup="cekval(this.value);"/></td>';
                        cols += '<td><input type="text" disabled id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'"/></td>';
                    }
                }
                newRow.append(cols);
                $("#tabledata").append(newRow);
                
                var gudang = $('#istore').val();

                $('#ematerialname'+counter).select2({
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

                $('#ematerialname2'+counter).select2({
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

                lastmaterial = i_material;
  
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
}
</script>