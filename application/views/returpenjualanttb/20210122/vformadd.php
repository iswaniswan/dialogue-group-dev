<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Gudang</label>
                        <label class="col-md-3">Customer</label>
                        <label class="col-sm-3">No Dok. Permintaan Retur</label>
                        <label class="col-md-3">Tanggal Retur</label>
                        <div class="col-sm-3">
                            <select name="kodelokasi" id="kodelokasi" class="form-control select2" onchange="setkodelokasi(this.value);">
                                <option value="kosong">--Pilih Gudang Jadi--</option>
                                <?php if ($gudangjadi) {
                                    foreach ($gudangjadi->result() as $key) { ?>
                                        <option value="<?= $key->i_kode_lokasi;?>"><?= $key->e_lokasi_name;?></option> 
                                    <?php }
                                } ?> 
                            </select> 
                             <input type="hidden" id="ikodelokasi" name="ikodelokasi" class="form-control" value="kosong" placeholder="">
                        </div>
                        <div class="col-sm-3">
                           <select name="customer" id="customer" class="form-control select2" onchange="getCustomer(this.value);">
                                <option value=""></option>
                                <?php if ($customer) {
                                    foreach ($customer->result() as $key) { ?>
                                        <option value="<?= $key->i_customer;?>"><?= $key->i_customer. " - ".$key->e_customer_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>

                        <div class="col-sm-3">
                            <select name="ttb" id="ttb" class="form-control select2" disabled="" onchange="getdata(this.value);">
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="dbbm" name="dbbm" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id="eremark" name="eremark" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" ><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
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
                                    <th class="text-center">No</th>
                                    <th class="text-center">Kode Barang</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Warna</th>
                                    <th class="text-center">Qty Permintaan</th>
                                    <th class="text-center">Qty Retur</th>
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
        showCalendar('.date',1830,0);
        // showCalendar('#dbonk',1830,0);
        // showCalendar('#dback',0,1830);


        $('#customer').select2({
            placeholder: 'Pilih Partner Customer',
        })

        $('#ttb').select2({
            placeholder: 'No Permintaan Retur',
        })

        $('#ttb').select2({
            placeholder: 'Pilih No Surat Jalan Keluar',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/ttb'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        customer : $('#customer').val(),
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
    });

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

    function getdata(ttb) {
        var customer = $("#customer").val();
        //swal(sj, partner);
        removeBody();

        $.ajax({
        type: "post",
        data: {
            'ttb': ttb,
            'customer': customer
        },
        url: '<?= base_url($folder.'/cform/getdetailttb'); ?>',
        dataType: "json",
        success: function (data) {
            
                // var i_memo = data['head']['n_discount'];
                // var d_memo = data['head']['d_memo'];
                // var d_bonk = data['head']['d_bonk'];
                // var n_discount = data['head']['n_discount'];
                // var pkp = data['head']['pkp'];
                // $('#pkp').val(pkp);
                // $('#ndiscount').val(n_discount+" %");
                // if (n_discount == 0) {
                //     n_discount = 1;
                // } else {
                //     n_discount = n_discount /100;
                // }
                // $('#gross').val(i_memo);
                // $('#netto').val(d_memo);
                // $('#dbonk').val(d_bonk);

                // if (tujuan_keluar == "external") {
                //     $('#diveks').show();
                // } else {
                //     $('#diveks').hide();
                // }
                // var v_discount = 0;
                // var v_gross = 0;

                // var totgross = 0;
                // var totdisc = 0;
                // var total = 0;

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
                    var qty_retur       = data['detail'][a]['qty_retur'];
                    
 
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                    cols += '<td><input style="width:120px" class="form-control" readonly id="i_product'+zz+'" name="i_product'+zz+'" value="'+i_product+'"></td>';
                    cols += '<td><input style="width:400px" class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+e_product_basename+'" value="'+e_product_basename+'"></td>';
                    cols += '<td><input style="width:150px" type="hidden" class="form-control" style="text-align:right;" id="i_color'+zz+'" name="i_color'+zz+'" value="'+i_color+'"><input readonly style="width:150px"  class="form-control" style="text-align;" id="e_color'+zz+'" name="e_color'+zz+'" value="'+e_color_name+'"></td>';
                    cols += '<td><input style="width:90px" readonly class="form-control" style="text-align:right;" id="qty_permintaan'+zz+'" name="qty_permintaan'+zz+'" value="'+qty_permintaan+'"></td>';
                    cols += '<td><input style="width:90px" class="form-control" style="text-align:right;" id="qty_retur'+zz+'" name="qty_retur'+zz+'" value="" onkeyup="validasi('+zz+');"></td>';
                    cols += '<td><input style="width:200px" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value=""/></td>';
                    cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" checked/></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }    
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function validasi(id){
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qty_permintaan  =document.getElementById("qty_permintaan"+i).value;
            qty_retur =document.getElementById("qty_retur"+i).value;
            if(parseFloat(qty_retur)>parseFloat(qty_permintaan)){
                swal('Jumlah Retur Tidak Boleh Lebih dari Permintaan');
                document.getElementById("qty_retur"+i).value=0;
                break;
          }
        }
    }

    function removeBody(){
        var tbl = document.getElementById("tabledata");   // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
        $('#tabledata').append("<tbody></tbody>");
    }

    function konfirm() {
        var jml = $('#jml').val();
        var ikodelokasi = $('#ikodelokasi').val();
        var customer = $('#customer').val();
        var ttb = $('#ttb').val();
        //swal(ikodelokasi + "" + customer + "" + ttb);
        var count = 0;
        if (ikodelokasi !='kosong' && customer !='' && ttb !='') {
            if(jml==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=jml;i++){
                    if($("#chk"+i).is(':checked') && ($('#qty_retur'+i).val() != "" && $('#qty_retur'+i).val() != "0") ){
                        count += i;
                    }
                }

                if (count > 0) {
                    return true;
                } else {
                    swal('Harap Centang Dan isi Qty Retur'+count);
                    return false;
                }
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
</script>