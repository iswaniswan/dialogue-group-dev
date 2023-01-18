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
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div> 
                <div class="col-md-7">
                    <div class="form-group row">
                        <label class="col-sm-5">Permintaan ke Gudang</label>
                        <label class="col-sm-6">Nomor Permintaan</label>                       
                        <div class="col-sm-5">
                             <select name="ikodemaster" id="ikodemaster" class="form-control select2" disabled="">
                                <option value="" selected>-- Pilih Gudang --</option>
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
                        <div class="col-sm-6">
                            <input type="text" id="i_bonmk" name="i_bonmk" class="form-control" readonly maxlength="" value="<?php echo $head->i_permintaan;?>">
                        </div>
                        
                    </div>        
                    <div class="form-group row">
                        <label class="col-md-5">Tujuan Keluar</label>
                        <label class="col-md-6">Departemen</label>
                        <div class="col-sm-5">
                            <select name="tujuankeluar" id="tujuankeluar" class="form-control select2" onchange="getpic(this.value)" disabled="">
                                <?php if($head->jenis_pengeluaran != 'JK00002') { ?><option value="internal" <?php if($head->tujuan_keluar =='internal') { ?> selected <?php } ?> >Internal</option> <?php }?>
                                <option value="external" <?php if($head->tujuan_keluar =='external') { ?> selected <?php } ?> >Eksternal</option> 
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="edept" id="edept" class="form-control select2" disabled="">
                                <option value="">-- Pilih Departemen --</option>
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
                    <?php if ($head->jenis_pengeluaran =="JK00001") {?>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label >PIC</label>
                            <select name="ppic" id="ppic" class="form-control select2" disabled="">
                                <option value="">-- Pilih Karyawan --</option>
                                <?php foreach ($karyawan as $karyawan):?>
                                    <?php if ($karyawan->id == $head->pic) { ?>
                                    <option value="<?php echo $karyawan->id;?>" selected><?= $karyawan->nama;?></option>
                                    <?php }else { ?>
                                    <option value="<?php echo $karyawan->id;?>"><?= $karyawan->nama;?></option>
                                    <?php }?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if($head->tujuan_keluar =='external'){?>
                         <div class="col-sm-6">
                            <label >PIC Eks</label>
                            <input type="text" id="epic" name="epic" class="form-control" value="<?php echo $head->pic_eks;?>" readonly>
                        </div>
                        <? }else{?>
                        <div class="col-sm-6" id="diveks" hidden="">
                            <label >PIC Eks</label>
                            <input type="text" id="epic" name="epic" class="form-control" placeholder="Nama PIC" value="" readonly>
                        </div>
                        <? }?>
                    </div>
                <?php } else { ?>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <input type="hidden" id="ppic" name="ppic" class="form-control" value="">
                            <input type="hidden" id="epic" name="epic" class="form-control" value="">
                        </div>
                    </div>
                <?php } ?>                     
                     <div class="form-group row">
                        <div class="col-sm-5">
                            <label>Status Dokumen</label>
                            <input type="text" id="istatus" name="istatus" class="form-control" value="<?php echo $head->e_status;?>" readonly>
                        </div>
                     </div>
                     <div class="form-group row">
                     </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                             <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                                <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>           
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group row">
                        <label class="col-sm-4">Tgl Permintaan</label>
                        <label class="col-sm-8">Jenis Pengeluaran</label>
                        <div class="col-sm-4">
                             <input type="text" id="dbonk" name="dbonk" class="form-control" value="<?php echo $head->d_pp;?>" readonly>
                        </div>
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
                    
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-8">
                        <label >Nomor Memo (Optional)</label>
                            <input type="text" id="memo" name="memo" class="form-control" maxlength="" value="<?php echo $head->memo;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                        <label >Tgl Memo (Opt)</label>
                            <input type="text" id="dmemo" name="dmemo" class="form-control" value="<?php echo $head->d_memo;?>" readonly>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id= "eremark" name="eremark" class="form-control" maxlength="30" value="<?php echo $head->e_remark;?>" disabled>
                        </div>
                    </div>
                </div> 
                <input style ="width:50px"type="hidden" name="jml" id="jml" value="0">
                <?php if ($head->jenis_pengeluaran =="JK00002") {?>
                    <div class="panel-body table-responsive withscroll">
                         <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="7%">Kode Barang</th>
                                    <th width="20%">Nama Barang</th>
                                    <th width="5%">Qty</th>
                                    <th width="7%">Satuan</th>
                                    <th width="7%">Kode Barang</th>
                                    <th width="20%">List Nama Barang</th>
                                    <th width="5%">Qty</th>
                                    <th width="7%">Satuan</th>
                                    <th width="10%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <?php } else {?>
                     <div class="panel-body table-responsive">
                        <table id="tabledata2" class="table color-table info-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="4%">No</th>
                                    <th width="7%">Kode Barang</th>
                                    <th width="20%">Nama Barang</th>
                                    <th width="5%">Qty</th>
                                    <th width="6%">Satuan</th>
                                    <th width="10%">Keterangan</th>
                                    <th width="5%" colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                <?php }?>
                </div>
                        
                    </div>
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

    $("#change").on("click", function () {
        var kode = $("#i_bonmk").val();
        var gudang = $("#istore").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'kode'  : kode,
                     'gudang'  : gudang
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

   $("#reject").on("click", function () {
        var kode = $("#i_bonmk").val();
        var gudang = $("#istore").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'kode'  : kode,
                     'gudang'  : gudang
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

   $("#cancel").on("click", function () {
        var kode = $("#i_bonmk").val();
        var gudang = $("#istore").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'kode'  : kode,
                     'gudang'  : gudang
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
    // var counter = 0;
    $("#addrow").on("click", function () {
        var counter = $('#jml').val();
        counter++;
        //document.getElementById("jml").value = counter;
        // var ikodemaster     = $("#ikodemaster").val();
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
        // $('#jml').val(counter);
        // del();
         // counter -= 1
         // document.getElementById("jml").value = counter;
    });

    // function del() {
    //     obj=$('#tabledata tr').find('spanx');
    //     $.each( obj, function( key, value ) {
    //         id=value.id;
    //         $('#'+id).html(key+1);
    //     });
    // }

    $("#tabledata").on("click", "#addrow2", function (event) {
        //$(this).closest('td').find('td').attr('rowspan','2');
        var row = $(this).closest("tr");
        var material = $(this).closest('tr').find('.imaterial').val();
        var nquantity = $(this).closest('tr').find('.nquantity').val();
        var isatuan = $(this).closest('tr').find('.isatuan').val();

        var counter = $('#jml').val();
        counter++;
        //document.getElementById("jml").value = counter;
        // var ikodemaster     = $("#ikodemaster").val();
        $('#jml').val(counter);
        count=$('#tabledata tr').length;
        //alert(count);
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
        //$("#tabledata").append(newRow);
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
    $('#cancel').attr("disabled", true);
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
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

function getenabledcancel() {
    swal("Berhasil", "Cancel Dokumen", "success");
    $('#cancel').attr("disabled", true);
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledchange() {
    swal("Berhasil", "Change Request Dokumen", "success");
    $('#cancel').attr("disabled", true);
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledreject() {
    swal("Berhasil", "Reject Dokumen", "success");
    $('#cancel').attr("disabled", true);
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
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
            // var isupplier = data['head']['i_supplier'];
            // var esupplier = data['head']['e_supplier_name'];
            // var ijeniskeluar = data['head']['i_jenis_keluar'];
            // var ejeniskeluar = data['head']['e_jenis_keluar'];
            // $('#supplier').val(isupplier);
            // $('#esupplier').val(esupplier);
            // $('#jnskeluar').val(ijeniskeluar);
            // $('#ejnskeluar').val(ejeniskeluar);
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
                var i_material2    = data['detail'][a]['i_material2'];
                var e_material2    = data['detail'][a]['e_material_name2'];
                var n_qty2         = data['detail'][a]['n_qty2'];
                var i_satuan2      = data['detail'][a]['i_satuan_code2'];
                var e_satuan2      = data['detail'][a]['e_satuan2'];
                var e_remark       = data['detail'][a]['e_remark'];

                var cols        = "";
                var newRow = $("<tr>");
                if (jenispengeluaran == "JK00002") {
                    if (lastmaterial == i_material) {
                        cols += '<td colspan="5"><input style="width:100px;" type="hidden" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+lastmaterial+'"><input type="hidden" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/></td>';

                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]" value="'+i_material2+'" required></td>';
                        cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');" disabled><option value="'+i_material2+'" selected>'+e_material2+'</option></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" id="nquantity2'+ counter + '" value="'+n_qty2+'" class="form-control" placeholder="0" name="nquantity2[]" onkeyup="cekval(this.value); reformat(this);" readonly></td>';
                        cols += '<td rowspan="1"><input  style="width:100px;" type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="'+e_satuan2+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" value="'+i_satuan2+'"/></td>';
                        cols += '<td><input style="width:200px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'" readonly></td>';
                    } else {
                        cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+i_material+'" required></td>';
                        cols += '<td><select style="width:400px;" type="text" id="ematerialname'+ counter + '"  class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');" disabled><option value="'+i_material+'" selected>'+e_material+'</option></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);" readonly></td>';
                        cols += '<td rowspan="1"><input  style="width:100px;" type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="'+e_satuan+'" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial2'+ counter + '" type="text" class="form-control" name="imaterial2[]" value="'+i_material2+'" required></td>';
                        cols += '<td><select type="text" style="width:400px;" id="ematerialname2'+ counter + '" class="form-control" name="ematerialname2[]" onchange="getmaterial2('+ counter + ');" disabled><option value="'+i_material2+'" selected>'+e_material2+'</option></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" id="nquantity2'+ counter + '" value="'+n_qty2+'" class="form-control" placeholder="0" name="nquantity2[]" onkeyup="cekval(this.value); reformat(this);" readonly></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly id="esatuan2'+ counter + '" class="form-control" name="esatuan2'+ counter + '" value="'+e_satuan2+'" onkeyup="cekval(this.value); reformat(this);"/><input type="hidden" id="isatuan2'+ counter + '" class="form-control" name="isatuan2[]" value="'+i_satuan2+'" onkeyup="cekval(this.value);"/></td>';
                        cols += '<td><input style="width:200px;" type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'" readonly></td>';
                    }
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    
                    //$('#ikodemaster').attr("disabled", true);
                    var gudang = $('#istore').val();

                    $('#ematerialname'+counter).select2({
                        placeholder: 'Pilih Material',
                        //templateSelection: formatSelection,
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
                        //templateSelection: formatSelection,
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
                } else {
                    cols += '<td rowspan="1" style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
                        cols += '<td rowspan="1"><input style="width:100px;" type="text" readonly  id="imaterial'+ counter + '" type="text" class="imaterial" name="imaterial[]" value="'+i_material+'" required></td>';
                        cols += '<td><select style="width:400px;" type="text" id="ematerialname'+ counter + '" disabled class="form-control" name="ematerialname[]" onchange="getmaterial('+ counter + ');"><option value="'+i_material+'" selected>'+e_material+'</option></td>';
                        cols += '<td rowspan="1"><input type="text" size="2" id="nquantity'+ counter + '" class="nquantity" placeholder="0" name="nquantity[]" value="'+n_qty+'" onkeyup="cekval(this.value); reformat(this);" readonly></td>';
                        cols += '<td rowspan="1"><input  style="width:100px;" type="text" readonly id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" value="'+e_satuan+'" /><input type="hidden" id="isatuan'+ counter + '" class="isatuan" name="isatuan[]" value="'+i_satuan+'"/></td>';
                        cols += '<td><input type="text"  style="width:200px;" id="edesc'+ counter + '" class="form-control" name="edesc[]" value="'+e_remark+'" readonly></td>';
                        cols += '<td style="text-align: center;"><button type="button" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
                    newRow.append(cols);
                    $("#tabledata2").append(newRow);

                    $('#ematerialname'+counter).select2({
                        placeholder: 'Pilih Material',
                        //templateSelection: formatSelection,
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
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
}
</script>