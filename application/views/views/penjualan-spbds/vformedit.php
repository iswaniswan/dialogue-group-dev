<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Bagian Pembuat</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Tanggal Batas Kirim</label>
                        <label class="col-md-3">Area</label>
                        <div class="col-md-2">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" onchange="number();">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                         <option value="<?= $row->i_bagian;?>" <?php if ($row->i_bagian == $data->i_bagian) {?> selected <?php } ?>>
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                            <input type="hidden" name="ibagianold" id="ibagianold" value="<?= $data->i_bagian;?>">
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="hidden" name="id" id="id" value="<?= $id;?>">
                                <input type="hidden" name="idocumentold" id="idocumentold" value="<?= $data->i_document;?>">
                                <input type="text" name="idocument" id="idocument" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="<?= $number;?>" maxlength="25" class="form-control input-sm" value="<?= $data->i_document;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" id="ada" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-md-2">
                            <input type="text" id="ddocument" name="ddocument" class="form-control input-sm date" required="" readonly value="<?= $data->d_document;?>" onchange="max_tgl();">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dsend" name="dsend" class="form-control input-sm date" required="" readonly value="<?= $data->d_estimate;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="iarea" id="iarea" class="form-control select2" required="" >
                                <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Customer</label>
                        <label class="col-md-3">Kelompok Harga</label>
                        <label class="col-md-3">Salesman</label>
                        <label class="col-md-3">Referensi OP</label>                        
                        <div class="col-sm-3">
                            <select name="icustomer" id="icustomer" class="form-control select2" required="" onchange="return getdiskon(this.value);">
                                <option value="<?=$data->id_customer;?>"><?=$data->e_customer_name;?></option>
                            </select>
                            <input type="hidden" id="1ndiskonitem" name="1ndiskonitem" class="form-control" readonly>
                            <input type="hidden" id="2ndiskonitem" name="2ndiskonitem" class="form-control" readonly>
                            <input type="hidden" id="3ndiskonitem" name="3ndiskonitem" class="form-control" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" id="kodeharga" name="kodeharga" class="form-control" value="<?=$data->id_harga_kode;?>" readonly>
                            <input type="text" id="ekodeharga" name="ekodeharga" class="form-control" value="<?=$data->e_harga;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="isales" id="isales" class="form-control select2" required="">
                                <option value="<?=$data->id_sales;?>"><?=$data->e_sales;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="ireferensiop" name="ireferensiop" class="form-control" value="<?= $data->i_referensi_op;?>">                           
                        </div>                                            
                    </div>     
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-md-12">
                            <textarea id="eremarkh" name="eremarkh" class="form-control"><?= $data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                             <?php if ($data->i_status == '1' || $data->i_status == '3' || $data->i_status == '7') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>                            
                            <?php if ($data->i_status == '1') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='2') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-6">
        <h3 class="box-title m-b-0">Detail Barang</h3>
        <div class="m-b-0">
            <?php if ($data->i_status == '1'){?>
            <div class="form-group row">
                <label class="col-md-5">Kategori Barang</label>
                <label class="col-md-6">Jenis Barang</label>
                <label class="col-md-1"></label>
                <div class="col-sm-5">
                    <select class="form-control select2" name="ikategori" id="ikategori">
                        <option value="all">Semua Kategori</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <select class="form-control select2" name="ijenis" id="ijenis">
                        <option value="all">Semua Jenis</option>
                    </select>
                    <input type="hidden" id="ibrand" name="ibrand" class="form-control" value="<?= $data->i_brand;?>" readonly>
                </div>
                <div class="col-sm-1">
                    <button type="button" id="addrow" class="btn btn-info btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                </div>
            </div>
            <?}?>
        </div>
    </div>
   <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Diskon 1 (%)</th>
                        <th class="text-center">Diskon 2 (%)</th>
                        <th class="text-center">Diskon 3 (%)</th>
                        <th class="text-center">Diskon Tambahan (Rp.)</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center" >Act</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $i = 0;
                    if ($datadetail) {
                        foreach ($datadetail as $row) {
                            $totalnet = $row->v_total - $row->v_total_discount;
                            $i++;?>
                            <tr>
                                <td class="text-center"><spanx id="snum<?=$i;?>"><?= $i;?></spanx></td>
                                <td>
                                    <input style="width:150px;" type="text" readonly  id="iproduct<?= $i;?>" class="form-control" name="iproduct[]" value="<?= $row->i_product_base;?>">
                                    <input style="width:150px;" readonly id="idproduct<?= $i;?>" type="hidden" class="form-control" name="idproduct[]" value="<?= $row->id_product;?>">
                                </td>
                                <td>
                                    <select style="width:350px;" id="eproduct<?=$i;?>" class="form-control select2" name="eproduct[]" onchange="getproduct(<?=$i;?>);">
                                        <option value="<?= $row->id_product;?>"><?= $row->e_product_basename;?></option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" value="<?= $row->n_quantity;?>" id="nquantity<?=$i;?>" class="form-control text-right" autocomplete="off" name="nquantity[]" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" value="0" onkeyup="hitungtotal(this);">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" readonly  id="vharga<?=$i;?>" class="form-control" name="vharga[]" value="<?=  ($row->v_price);?>">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" readonly  id="ndiskon<?=$i;?>" class="form-control" name="ndiskon[]" value="<?= $row->n_diskon1;?>">
                                    <input style="width:100px;" readonly  id="vdiskon<?=$i;?>" type="hidden" class="form-control" name="vdiskon[]" value="<?= $row->v_diskon1;?>">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" readonly  id="ndiskonn<?=$i;?>" class="form-control" name="ndiskonn[]" value="<?= $row->n_diskon2;?>">
                                    <input style="width:100px;" readonly  id="vdiskonn<?=$i;?>" type="hidden" class="form-control" name="vdiskonn[]" value="<?= $row->v_diskon2;?>">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" readonly  id="ndiskonnn<?=$i;?>" class="form-control" name="ndiskonnn[]" value="<?= $row->n_diskon3;?>">
                                    <input style="width:100px;" type="hidden" readonly  id="vdiskonnn<?=$i;?>" class="form-control" name="vdiskonnn[]" value="<?= $row->v_diskon3;?>">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" id="adddiskon<?=$i;?>" class="form-control" name="adddiskon[]" value="<?= $row->v_diskontambahan;?>" onkeyup="hitungtotal(this);">
                                </td>
                                <td>
                                    <input style="width:100px;" type="text" id="vtotal<?=$i;?>" class="form-control" name="vtotal[]" value="<?= $row->v_total;?>" readonly>
                                    <input style="width:100px;" type="hidden" id="vtotaldiskon<?=$i;?>" class="form-control" name="vtotaldiskon[]" value="<?= $row->v_total_discount;?>" readonly>
                                    <input style="width:100px;" type="hidden" id="vtotalnet<?=$i;?>" class="form-control" name="vtotalnet[]" value="<?=$totalnet?>" readonly>
                                </td>
                                <td>
                                    <input style="width:300px;" type="text" id="eremark<?=$i;?>" class="form-control input-sm" value="<?= $row->e_remark;?>" name="eremark[]">
                                </td>
                                <td>
                                    <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button>
                                </td>
                            </tr>
                        <?php } 
                    }?>
                    <input type="hidden" name="jml" id="jml" value ="<?= $i;?>">
                </tbody>
                <tfoot>
                    <?php 
                        $grandtotal = $data->v_dpp + $data->v_ppn;
                    ?>
                    <tr>
                        <td class="text-right" colspan="8">Total</td>
                        <td>:</td>
                        <td><input type="text" id="nkotor" name="nkotor" class="form-control input-sm" readonly value=<?= $data->v_kotor;?> ></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">Diskon</td>
                        <td>:</td>
                        <td><input type="text" id="ndiskontotal" name="ndiskontotal" class="form-control input-sm" readonly value=<?= $data->v_diskon;?> ></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">DPP</td>
                        <td>:</td>
                        <td><input type="text" id="vdpp" name="vdpp" class="form-control input-sm" readonly value=<?= $data->v_dpp;?> ></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">PPN (10%)</td>
                        <td>:</td>
                        <td><input type="text" id="vppn" name="vppn" class="form-control input-sm" readonly value=<?= $data->v_ppn;?> ></td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="8">Grand Total</td>
                        <td>:</td>
                        <td><input type="text" id="nbersih" name="nbersih" class="form-control input-sm" readonly value=<?= $grandtotal; ?> ></td>
                    </tr>
                    </tfoot>
            </table>
        </div>
    </div>
</div>
</form>

<script>
$(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        getdiskon();
        max_tgl();
        //hitungtotal();
       // number();

        $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/area/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
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
            $("#icustomer").attr("disabled", false);
            /*$("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);*/
            $("#icustomer").val("");
            $("#icustomer").html("");
        });

        $('#icustomer').select2({
            placeholder: 'Pilih Customer',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iarea : $('#iarea').val(),
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
            $("#isales").attr("disabled", false);
            /*$("#tabledatax tr:gt(0)").remove();
            $("#jml").val(0);*/
            $("#isales").val("");
            $("#isales").html("");
        });

        $('#isales').select2({
            placeholder: 'Pilih Sales',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/sales'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        iarea : $('#iarea').val(),
                        icustomer : $('#icustomer').val(),
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
            $("#addrow").attr("disabled", false);
        });

        $('#ikategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kelompok'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ibagian : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenis').val('');
            $('#ijenis').html('');
        });

        $('#ijenis').select2({
            placeholder: 'Pilih Jenis',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/jenis'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikategori : $('#ikategori').val(),
                        ibagian   : $('#ibagian').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'1','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    function max_tgl(val) {
        $('#dsend').datepicker('destroy');
        $('#dsend').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('ddocument').value,
        });
    }
    $('#dsend').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('ddocument').value,
    });

    function getdiskon(){
        var icustomer = $('#icustomer').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer'  : icustomer
            },
            url: '<?= base_url($folder.'/cform/getdiskon'); ?>',
            dataType: "json",
            success: function (data) {
                $('#1ndiskonitem').val(formatcemua(data[0].v_customer_discount));
                $('#2ndiskonitem').val(formatcemua(data[0].v_customer_discount2));
                $('#3ndiskonitem').val(formatcemua(data[0].v_customer_discount3));
                $('#kodeharga').val(formatcemua(data[0].id_harga_kode));
                $('#ekodeharga').val(formatcemua(data[0].e_harga));
            },
            error: function () {
                swal('Error :)');
            }
        });
    }
     
      /**
     * Tambah Item
     */

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {
        var no     = $('#tabledatax tbody tr').length+1;
        counter++;
        counterx++;
        $("#tabledatax").attr("hidden", false);
        var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledatax tr tbody').length;
        // if ((iproduct==''||iproduct==null)&&(count>1)) {
        //     swal('Isi dulu yang masih kosong!!');
        //     counter = counter-1;
        //     counterx = counterx-1;
        //     return false;
        // }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+no+'</spanx></td>';
        cols += '<td><input style="width:150px;" type="text" readonly  id="iproduct'+ counter + '" class="form-control" name="iproduct[]" value=""><input style="width:150px;" readonly id="idproduct'+ counter + '" type="hidden" class="form-control" name="idproduct[]" value=""></td>';
        cols += '<td><select style="width:350px;" id="eproduct'+counter+ '" class="form-control select2" name="eproduct[]"  onchange="getproduct('+ counter + ');"></select></td>';
        cols += '<td><input style="width:100px;" type="text" id="nquantity'+counter+'" class="form-control text-right input-sm inputitem" autocomplete="off" name="nquantity[]" value="0" onkeyup="hitungtotal(this);"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="vharga'+ counter + '" class="form-control" name="vharga[]" value=""></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="ndiskon'+ counter + '" class="form-control" name="ndiskon[]" value=""><input style="width:100px;" readonly  id="vdiskon'+ counter + '" type="hidden" class="form-control" name="vdiskon[]" value=""></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="ndiskonn'+ counter + '" class="form-control" name="ndiskonn[]" value=""><input style="width:100px;" readonly  id="vdiskonn'+ counter + '" type="hidden" class="form-control" name="vdiskonn[]" value=""></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="ndiskonnn'+ counter + '" class="form-control" name="ndiskonnn[]" value=""><input style="width:100px;" type="hidden" readonly  id="vdiskonnn'+ counter + '" class="form-control" name="vdiskonnn[]" value=""></td>';
        cols += '<td><input style="width:100px;" type="text" id="adddiskon'+ counter + '" class="form-control" name="adddiskon[]" value="0" onkeyup="hitungtotal(this);"></td>';
        cols += '<td><input style="width:100px;" type="text" id="vtotal'+ counter + '" class="form-control" name="vtotal[]" value="" readonly><input style="width:100px;" type="hidden" id="vtotaldiskon'+ counter + '" class="form-control" name="vtotaldiskon[]" value="" readonly><input style="width:100px;" type="hidden" id="vtotalnet'+ counter + '" class="form-control" name="vtotalnet[]" value="" readonly></td>';
        cols += '<td><input style="width:300px;" type="text" id="eremark'+counter+'" class="form-control input-sm" name="eremark[]"></td>';
        cols += '<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $('#eproduct'+ counter).select2({
            placeholder: 'Cari Kode / Nama Product',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q          : params.term,
                        ikategori  : $('#ikategori').val(),
                        ijenis     : $('#ijenis').val(),
                        ibagian    : $('#ibagian').val(),
                        ibrand     : $('#ibrand').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        function formatSelection(val) {
            return val.name;
        }
    });  

    function getproduct(id){
        var dis1_ = $('#1ndiskonitem').val();
        var dis2_ = $('#2ndiskonitem').val();
        var dis3_ = $('#3ndiskonitem').val();
        $.ajax({
            type: "post",
            data: {
                'eproduct'  : $('#eproduct'+id).val(),
                'tgl'       : $('#ddocument').val(),
                'kodeharga' : $('#kodeharga').val(),
            },
            url: '<?= base_url($folder.'/cform/getproduct'); ?>',
            dataType: "json",
            success: function (data) {
                if (parseInt(data.length) < 1) {
                    swal('Maaf :(','Harga Barang Jadi Masih Kosong, Silahkan Input di Master Harga Barang Jadi!','error');
                    $('#eproduct'+id).html('');
                    $('#eproduct'+id).val('');
                    return false;
                }
                ada = false;
                for(var i = 1; i <=$('#jml').val(); i++){
                    if(($('#eproduct'+id).val() == $('#eproduct'+i).val()) && (i!=id)){
                        swal ("kode : "+$('#eproduct'+id).val()+" sudah ada !!!!!");
                        ada = true;
                        break;
                    }else{
                        ada = false;     
                    }
                }
                if(!ada){
                    $('#idproduct'+id).val(formatcemua(data[0].id_product));
                    $('#iproduct'+id).val(formatcemua(data[0].i_product_base));  
                    $('#vharga'+id).val(formatcemua(data[0].v_price));                   
                    $('#nquantity'+id).focus();
                    $('#ndiskon'+id).val(dis1_);
                    $('#ndiskonn'+id).val(dis2_);
                    $('#ndiskonnn'+id).val(dis3_);
                    hitungtotal(id);
                }else{
                    $('#idproduct'+id).html('');
                    $('#iproduct'+id).html('');
                    $('#eproduct'+id).html('');
                    $('#vharga'+id).html('');
                    $('#ndiskon'+id).html('');
                    $('#ndiskonn'+id).html('');
                    $('#ndiskonnn'+id).html('');
                    $('#idproduct'+id).val('');
                    $('#iproduct'+id).val('');
                    $('#eproduct'+id).val('');
                    $('#vharga'+id).val('');
                    $('#ndiskon'+id).val('');
                    $('#ndiskonn'+id).val('');
                    $('#ndiskonnn'+id).val('');
                }
            },
            error: function () {
                swal('Ada kesalahan :(');
            }
        });
    }

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        del();
        hitungtotal();  

        var jum = $('#tabledatax tbody tr').length;
        if(jum == 0){
            $('#ibrand').val('');
        }
    });

    function del() {
        obj=$('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    }

     function hitungtotal(id){
         var id = $('#jml').val();
        var total = 0;
        var totaldis = 0;
        var vjumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand = 0;
        for (var i = 1; i <= $('#jml').val(); i++) {
            if(typeof $('#idproduct'+i).val() != 'undefined'){
                var jumlah = formatulang($('#vharga'+i).val()) * parseFloat($('#nquantity'+i).val());
                var disc1 = formatulang($('#ndiskon'+i).val());
                var disc2 = formatulang($('#ndiskonn'+i).val());
                var disc3 = formatulang($('#ndiskonnn'+i).val());
                var disc4 = formatulang($('#adddiskon'+i).val());
               // alert(disc4);
                var ndisc1 = jumlah * (disc1/100);
                var ndisc2 = (jumlah - ndisc1) * (disc2/100);
                var ndisc3 = (jumlah - ndisc1 - ndisc2) * (disc3/100);

                var vtotaldis = (ndisc1 + ndisc2 + ndisc3 + parseInt(disc4));
                   
                var vtotal  = jumlah - vtotaldis;
                //alert(jumlah+' - '+vtotaldis);
                //alert(vtotaldis+'|'+vtotal);
               
                $('#vdiskon'+i).val(ndisc1);
                $('#vdiskonn'+i).val(ndisc2);
                $('#vdiskonnn'+i).val(ndisc3);
                $('#vtotaldiskon'+i).val(vtotaldis);
                $('#vtotal'+i).val(jumlah);
                $('#vtotalnet'+i).val(vtotal);

                totaldis += vtotaldis;
                vjumlah += jumlah;
                total += vtotal;
            }
        }
        $('#nkotor').val(vjumlah);
        $('#ndiskontotal').val(totaldis);
 
        dpp = vjumlah - totaldis;
        ppn = dpp * 0.1;
        grand = dpp + ppn;

        $('#nbersih').val(grand);
        $('#vdpp').val(dpp);
        $('#vppn').val(ppn);
        //alert(dpp);
    }

    //new script
    function number() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $('#ddocument').val(),
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

   function konfirm() {
        var jml = $('#jml').val();
        if (($('#ibagian').val()!='' || $('#ibagian').val()) && ($('#iarea').val()!='' || $('#iarea').val()) && ($('#icustomer').val()!='' || $('#icustomer').val())  && ($('#isales').val()!='' || $('#isales').val())) {
            if(jml==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=jml;i++){
                    if($("#iproduct"+i).val()=='' || $("#eproduct"+i).val()=='' || $("#eproduct"+i).val()== null || $("#nquantity"+i).val()=='' || $("#nquantity"+i).val()==0){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            swal('Data Header Masih Ada yang Kosong!');
            return false;
        }        
    }
</script>