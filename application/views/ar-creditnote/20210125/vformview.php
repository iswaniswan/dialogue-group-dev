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
                        <label class="col-md-2">Nomor Faktur</label>
                        <label class="col-md-3">Partner Customer</label>
                        <label class="col-sm-3">No. Faktur</label>
                        <label class="col-md-4">Tanggal Kredit Nota</label>
                        <div class="col-sm-2">
                            <input type="text" id="ikn" name="ikn" class="form-control" value="<?php echo $data->i_kn; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                           <select name="ecustomer" id="ecustomer" class="form-control select2" onchange="getnota(this.value);" disabled>
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
                            <input type="hidden" id="customer" name="customer" class="form-control" value="<?php echo $data->i_customer; ?>" readonly>
                        </div> 

                        <div class="col-sm-3">
                            <input type="text" id="nota" name="nota" class="form-control" value="<?php echo $data->i_nota; ?>" readonly>
                            <input type="hidden" id="pkp" name="pkp" class="form-control" value="<?php echo $data->pkp; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="dkn" name="dkn" class="form-control date" value="<?php echo $data->d_kn; ?>" disabled>
                        </div>
                       

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-2">Gross</label>
                        <label class="col-sm-1">% Discount</label>
                        <label class="col-sm-2">Discount</label>
                        <label class="col-sm-2">Netto</label>
                        <label class="col-md-5">Keterangan</label>
                        <div class="col-sm-2">
                            <input type="text" id="gross" name="gross" class="form-control" value="<?php echo 'Rp. '.number_format($data->v_gross); ?>" readonly>
                        </div>
                        <div class="col-sm-1">
                            <input type="text" id="ndiscount" name="ndiscount" class="form-control" value="<?php echo $data->n_discount; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="discount" name="discount" class="form-control" value="<?php echo 'Rp. '.number_format($data->v_discount); ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="netto" name="netto" class="form-control" value="<?php echo 'Rp. '.number_format($data->v_netto); ?>" readonly>
                        </div>
                        <div class="col-sm-5">
                            <textarea id= "eremark" name="eremark" class="form-control" readonly><?php echo $data->e_remark; ?></textarea>
                        </div>
                        
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-2">DPP</label>
                        <label class="col-sm-2">PPN</label>
                        <label class="col-sm-2">Total</label>
                        <label class="col-sm-2"></label>
                        <label class="col-sm-4"></label>
                        <div class="col-sm-2">
                            <input type="text" id="dpp" name="dpp" class="form-control" value="<?php echo 'Rp. '.number_format($data->v_dpp); ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="ppn" name="ppn" class="form-control" value="<?php echo 'Rp. '.number_format($data->v_ppn); ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="total" name="total" class="form-control" value="<?php echo 'Rp. '.number_format($data->v_total); ?>" readonly>
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
                                    <th class="text-center">No. BBM</th>
                                    <th class="text-center">Kode Barang</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Warna</th>
                                    <th class="text-center">Qty KN</th>
                                    <th class="text-center">Harga Satuan</th>
                                    <th class="text-center">Harga Total</th>
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
            var kode = $("#ikn").val();
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
            var kode = $("#ikn").val();
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


    function getnota(customer) {
        if (customer == "") {
            $("#nota").attr("disabled", true);
        } else {
            $("#nota").attr("disabled", false);
        }
        $("#gross").val("");
        $("#discount").val("");
        $("#netto").val("");
        $("#dpp").val("");
        $("#ppn").val("");
        $("#total").val("");
        $("#nota").val("");
        $("#nota").html("");
        removeBody();
    }
    function getdataedit() {
        var ikn = $("#ikn").val();
        var customer = $("#customer").val();
        var nota = $("#nota").val();
        //swal(sj, partner);
        removeBody();

        $.ajax({
        type: "post",
        data: {
            'ikn': ikn,
            'customer': customer,
            'nota': nota
        },
        url: '<?= base_url($folder.'/cform/getdetailknapprove'); ?>',
        dataType: "json",
        success: function (data) {
            
                // var i_memo = data['head']['n_discount'];
                // var d_memo = data['head']['d_memo'];
                // var d_bonk = data['head']['d_bonk'];
                var n_discount = data['head']['n_discount'];
                var pkp = data['head']['pkp'];
                //var ilokasi = data['head']['i_kode_lokasi'];
                $('#pkp').val(pkp);
                $('#ndiscount').val(n_discount+" %");
                if (n_discount == 0) {
                    n_discount = 0;
                } else {
                    n_discount = n_discount /100;
                }
                // $('#lokasi').val(ilokasi);
                // $('#netto').val(d_memo);
                // $('#dbonk').val(d_bonk);

                // if (tujuan_keluar == "external") {
                //     $('#diveks').show();
                // } else {
                //     $('#diveks').hide();
                // }
                var v_discount = 0;
                var v_gross = 0;

                var totgross = 0;
                var totdisc = 0;
                var total = 0;

                $('#jml').val(data['detail'].length);
                //var gudang = $('#istore').val();
                var lastsj = '';
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var i_bbm           = data['detail'][a]['i_bbm'];
                    var i_product       = data['detail'][a]['i_product'];
                    var e_product_basename = data['detail'][a]['e_product_basename'];
                    var i_color         = data['detail'][a]['i_color'];
                    var e_color_name    = data['detail'][a]['e_color_name'];
                    var sisa            = data['detail'][a]['sisa'];
                    var qty             = data['detail'][a]['qty'];
                    var v_price         = data['detail'][a]['v_price'];
                    var e_remark         = data['detail'][a]['e_remark'];
                
                    v_gross = qty * v_price;
                    v_discount  = v_gross*n_discount;
                    total = total + (v_gross-v_discount);
                    totgross = totgross + v_gross;
                    totdisc = totdisc + v_discount;

                    v_price = formatcemua(v_price);
                    v_gross = formatcemua(v_gross);
                    
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                    cols += '<td><input style="width:200px" class="form-control" readonly id="i_bbm'+zz+'" name="i_bbm'+zz+'" value="'+i_bbm+'"></td>';
                    cols += '<td><input style="width:120px" class="form-control" readonly id="i_product'+zz+'" name="i_product'+zz+'" value="'+i_product+'"></td>';
                    cols += '<td><input style="width:400px" class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+e_product_basename+'" value="'+e_product_basename+'"></td>';
                    cols += '<td><input style="width:150px" type="hidden" class="form-control" style="text-align:right;" id="i_color'+zz+'" name="i_color'+zz+'" value="'+i_color+'"><input readonly style="width:150px"  class="form-control" style="text-align;" id="e_color'+zz+'" name="e_color'+zz+'" value="'+e_color_name+'"></td>';
                    cols += '<td><input style="width:90px" readonly class="form-control" style="text-align:right;" id="qty'+zz+'" name="qty'+zz+'" value="'+qty+'"></td>';
                    cols += '<td><input style="width:150px" class="form-control" readonly id="v_price'+zz+'" name="v_price'+zz+'" value="'+v_price+'"><input style="width:150px" type="hidden" class="form-control" readonly id="v_discount'+zz+'" name="v_discount'+zz+'" value="'+v_discount+'"></td>';
                    cols += '<td><input style="width:150px" class="form-control" readonly id="v_gross'+zz+'" name="v_gross'+zz+'" value="'+v_gross+'"></td>';
                    cols += '<td><input style="width:200px" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value="'+e_remark+'" readonly/></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    
                    // $("#chk"+zz).click(function () {
                    //     // var clas = $(this).attr('class');
                    //     // $('.'+clas).prop("checked",$(this).prop("checked"));
                    //     ngetang();
                    // });
      
                }    

                $('#gross').val("Rp. "+formatcemua(totgross));
                $('#netto').val("Rp. "+formatcemua(total));
                $('#discount').val("Rp. "+formatcemua(totdisc));
                if (pkp == 'f') {
                    var dpp = 0;
                    var ppn = 0;
                    $('#dpp').val("Rp. "+formatcemua(dpp));
                    $('#ppn').val("Rp. "+formatcemua(ppn));
                } else {
                    var dpp = total/1.1;
                    var ppn = dpp*0.1;
                    $('#dpp').val("Rp. "+formatcemua(dpp));
                    $('#ppn').val("Rp. "+formatcemua(ppn));
                }
                var totalsemua =  total + ppn;
                $('#total').val("Rp. "+formatcemua(totalsemua));
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        var pkp = $('#pkp').val();
        /*var tot = 0;*/
        var gross2 = 0;
        var discount2 = 0;
        var total2 = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#qty"+brs).val();
            hrg  = formatulang($("#v_price"+brs).val());
            qty  = formatulang(ord);
           
            vhrg = parseFloat(hrg)*parseFloat(qty)-parseFloat(formatulang($("#v_discount"+brs).val()));
            //$("#hargatotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                total2+=parseFloat(vhrg);
                discount2 += parseFloat(formatulang($("#v_discount"+brs).val()));
                gross2  += parseFloat(hrg)*parseFloat(qty);
            }
        }
        $('#gross').val("Rp. "+formatcemua(gross2));
        $('#discount').val("Rp. "+formatcemua(discount2));
        $("#netto").val("Rp. "+formatcemua(total2));

        if (pkp == 'f') {
            var dpp = 0;
            var ppn = 0;
            $('#dpp').val("Rp. "+formatcemua(dpp));
            $('#ppn').val("Rp. "+formatcemua(ppn));
        } else {
            var dpp = total2/1.1;
            var ppn = dpp*0.1;
            $('#dpp').val("Rp. "+formatcemua(dpp));
            $('#ppn').val("Rp. "+formatcemua(ppn));
        }
        var totalsemua =  total2 + ppn;
        $('#total').val("Rp. "+formatcemua(totalsemua));
    }

    function removeBody(){
        var tbl = document.getElementById("tabledata");   // Get the table
        tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
        $('#tabledata').append("<tbody></tbody>");
    }

    function konfirm() {
        var jml = $('#jml').val();
        var myString = Number($("#netto").val().replace(/\D/g,''));
        if ($('#partner').val()!='' && $('#isjkeluar').val()!='' && $('#datefaktur').val()!='' && $('#nopajak').val()!=''
            && $('#datepajak').val()!='' ) {
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

    function getselisih() {
        var jml = $('#jml').val();
        var qty1 = 0;
        var qty2 = 0;
        var qty = []; 
        var jumlah = 0;
        for (i=1; i<=jml; i++){
            qty1 = parseInt($('#sisa'+i).val());
            qty2 = parseInt($('#qty'+i).val());

            if (qty2 > qty1) {
                qty.push("lebih");
            } else {
                qty.push("ok");
            }
            jumlah = jumlah + qty2;
        }
        var found = qty.find(element => element == "lebih");
             
        if (found == "lebih") {
            swal("Jumlah Barang Sudah Dibuat KN Sebelumnya");
            return false;
        } else if (jumlah == 0) {
            swal("Barang Masuk Minimal 1");
            return false;
        } else {
            return true;
        }    
    }
</script>