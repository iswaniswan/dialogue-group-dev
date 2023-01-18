<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-2">Nomor Retur</label>
                        <label class="col-md-2">Gudang</label>
                        <label class="col-md-3">Customer</label>
                        <label class="col-sm-3">No Dok. Permintaan Retur</label>
                        <label class="col-md-2">Tanggal Retur</label>
                         <div class="col-sm-2">
                            <input type="text" id="ibbm" name="ibbm" class="form-control" value="<?php echo $data->i_bbm; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <select name="kodelokasi" id="kodelokasi" class="form-control select2" disabled>
                                <option value="kosong">--Pilih Gudang Jadi--</option>
                                <?php if ($gudangjadi) {
                                    foreach ($gudangjadi->result() as $key) { ?>
                                        <?php if ($key->i_kode_lokasi == $data->i_kode_lokasi) { ?>
                                        <option value="<?= $key->i_kode_lokasi;?>" selected><?= $key->e_lokasi_name;?></option> 
                                        <?php } else { ?>
                                        <option value="<?= $key->i_kode_lokasi;?>"><?= $key->e_lokasi_name;?></option>     
                                        <?php } ?>

                                    <?php }
                                } ?>  
                            </select> 
                             <input type="hidden" id="ikodelokasi" name="ikodelokasi" class="form-control" value="<?php echo $data->i_kode_lokasi; ?>" placeholder="">
                        </div>
                        <div class="col-sm-3">
                           <select name="customer" id="customer" class="form-control select2" disabled>
                                <option value=""></option>
                                <?php if ($customer) {
                                    foreach ($customer->result() as $key) { ?>
                                        <?php if ($key->i_customer == $data->i_customer) { ?>
                                        <option value="<?= $key->i_customer;?>" selected><?= $key->e_customer_name;?></option> 
                                        <?php } else { ?>
                                        <option value="<?= $key->i_customer;?>"><?= $key->e_customer_name;?></option>     
                                        <?php } ?>

                                    <?php }
                                } ?>  
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <input type="text" id="ttb" name="ttb" class="form-control" value="<?php echo $data->i_ttb; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="dbbm" name="dbbm" class="form-control date" value="<?php echo $data->d_bbm; ?>" disabled>
                        </div>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control" disabled><?php echo $data->e_remark; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                          
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value ="0">
                <div class="col-md-12">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%"> 
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode Barang</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Warna</th>
                                    <th class="text-center">Qty Permintaan</th>
                                    <th class="text-center">Qty Retur</th>
                                    <th class="text-center">Keterangan</th>
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
        showCalendar('.date',1830,0);
        // showCalendar('#dbonk',1830,0);
        // showCalendar('#dback',0,1830);

        $('#change').attr("disabled", false);
        $("#change").on("click", function () {
            var kode = $("#ibbm").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder.'/cform/change'); ?>",
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

       $("#reject").on("click", function () {
            var kode = $("#ibbm").val();
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder.'/cform/reject'); ?>",
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


        $("form").submit(function(event) {
            event.preventDefault();
            $('#change').attr("disabled", true);
            $('#reject').attr("disabled", true);
            $('#submit').attr("disabled", true);
        });

        getdataedit();
    });

    function getenabledchange() {
        $('#change').attr("disabled", true);
        $('#reject').attr("disabled", true);
        $('#submit').attr("disabled", true);
        swal('Data Berhasil Di Change Request');
    }

    function getenabledreject() {
        $('#change').attr("disabled", true);
        $('#reject').attr("disabled", true);
        $('#submit').attr("disabled", true);
        swal('Data Berhasil Di Reject');
    }

    function setkodelokasi(value) {
        var ikodelokasi = $('#ikodelokasi').val(value);
    }

    function getCustomer(cust) {
        if (cust == "") {
            $("#ttb").attr("disabled", true);
        } else {
            $("#ttb").attr("disabled", false);
        }
        $("#ttb").val("");
        $("#ttb").html("");
        removeBody();
    }

    function getdataedit() {
        var ibbm = $("#ibbm").val();
        var customer = $("#customer").val();
        var ttb = $("#ttb").val();
        //swal(sj, partner);
        removeBody();

        $.ajax({
        type: "post",
        data: {
            'ibbm': ibbm,
            'ttb': ttb,
            'customer': customer
        },
        url: '<?= base_url($folder.'/cform/getdetailttbedit'); ?>',
        dataType: "json",
        success: function (data) {
                $('#jml').val(data['detail'].length);
                //var gudang = $('#istore').val();
                var lastsj = '';
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var i_product       = data['detail'][a]['i_product'];
                    var e_product_basename = data['detail'][a]['e_product_basename'];
                    var i_color         = data['detail'][a]['i_color'];
                    var e_color_name    = data['detail'][a]['e_color_name'];
                    var qty_permintaan  = data['detail'][a]['qty_permintaan'];
                    //var qty_retur       = data['detail'][a]['qty_retur'];

                    var e_remark      = data['detail'][a]['e_remark'];
                    var qty_retur       = data['detail'][a]['qty_retur'];
                    
 
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                    cols += '<td><input style="width:120px" class="form-control" readonly id="i_product'+zz+'" name="i_product'+zz+'" value="'+i_product+'"></td>';
                    cols += '<td><input style="width:400px" class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+e_product_basename+'" value="'+e_product_basename+'"></td>';
                    cols += '<td><input style="width:150px" type="hidden" class="form-control" style="text-align:right;" id="i_color'+zz+'" name="i_color'+zz+'" value="'+i_color+'"><input readonly style="width:150px"  class="form-control" style="text-align;" id="e_color'+zz+'" name="e_color'+zz+'" value="'+e_color_name+'"></td>';
                    cols += '<td><input style="width:90px" readonly class="form-control" style="text-align:right;" id="qty_permintaan'+zz+'" name="qty_permintaan'+zz+'" value="'+qty_permintaan+'"></td>';
                    cols += '<td><input style="width:90px" class="form-control" style="text-align:right;" id="qty_retur'+zz+'" name="qty_retur'+zz+'" value="'+qty_retur+'" readonly></td>';
                    cols += '<td><input style="width:200px" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value="'+e_remark+'" readonly/></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }    
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function removeBody(){
        var tbl = document.getElementById("tabledata");   // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
        $('#tabledata').append("<tbody></tbody>");
    }

    function getselisih() {
        var jml = $('#jml').val();
        var qty1 = 0;
        var qty2 = 0;
        var qty = []; 
        var jumlah = 0;
        for (i=1; i<=jml; i++){
            qty1 = parseInt($('#qty_retur'+i).val());
            qty2 = parseInt($('#qty_permintaan'+i).val());

            if (qty1 > qty2) {
                qty.push("lebih");
            } else {
                qty.push("ok");
            }
            jumlah = jumlah + qty1;
        }
        var found = qty.find(element => element == "lebih");
             
        if (found == "lebih") {
            swal("Jumlah Barang Masuk Melebihi Jumlah Sisa Barang Keluar");
            return false;
        } else if (jumlah == 0) {
            swal("Barang Masuk Minimal 1");
            return false;
        } else {
            return true;
        }    
    }
</script>