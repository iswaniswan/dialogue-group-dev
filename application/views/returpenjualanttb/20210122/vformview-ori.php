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
                        <label class="col-md-2">Partner Packing</label>
                        <label class="col-sm-2">SJ Keluar</label>
                        <label class="col-md-2">Tanggal Faktur</label>
                        <label class="col-md-2">No Pajak Makloon</label>
                        <label class="col-md-2">Tanggal Pajak Makloon</label>
                        <div class="col-sm-2">
                            <input type="text" id="inota" name="inota" class="form-control" value="<?php echo $data->i_nota; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                           <select name="partner" id="partner" class="form-control select2" disabled="">
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
                            <input type="hidden" id="ipartner" name="ipartner" class="form-control" value="<?php echo $data->partner; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="isjkeluar" name="isjkeluar" class="form-control" value="<?php echo $data->i_sj_keluar; ?>" readonly>
                             <input type="hidden" id="pkp" name="pkp" class="form-control" value="<?php echo $data->pkp; ?>" readonly>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="dfaktur" name="dfaktur" class="form-control date" value="<?php echo $data->d_nota; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                             <input type="text" id="nopajak" name="nopajak" class="form-control" value="<?php echo $data->no_pajakmakloon; ?>" readonly>
                        </div> 
                        <div class="col-sm-2">
                            <input type="text" id="dpajak" name="dpajak" class="form-control date" value="<?php echo $data->d_pajak; ?>" readonly>
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
                                    <th class="text-center" >No</th>
                                    <th class="text-center">SJ Masuk</th>
                                    <th class="text-center">Kode Barang</th>
                                    <th class="text-center">Nama Barang</th>
                                    <th class="text-center">Warna</th>
                                    <!-- <th class="text-center">Qty Keluar</th>
                                    <th class="text-center">Qty Masuk</th>
                                    <th class="text-center">Qty Sudah Nota</th> -->
                                    <th class="text-center">Qty Belum Nota</th>
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
getdataedit($("#isjkeluar").val(),$("#inota").val());
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date',1830,0);
        // showCalendar('#dbonk',1830,0);
        // showCalendar('#dback',0,1830);

        $('#change').attr("disabled", false);
        $("#change").on("click", function () {
            var kode = $("#inota").val();
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
            var kode = $("#inota").val();
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


    function sjkeluar(sj) {
        if (sj == "") {
            $("#isjkeluar").attr("disabled", true);
        } else {
            $("#isjkeluar").attr("disabled", false);
        }
        $("#gross").val("");
        $("#discount").val("");
        $("#netto").val("");
        $("#isjkeluar").val("");
        $("#isjkeluar").html("");
        removeBody();
    }

    function getdata(sj) {
        var partner = $("#partner").val();
        //swal(sj, partner);
        removeBody();

        $.ajax({
        type: "post",
        data: {
            'sj': sj,
            'partner': partner
        },
        url: '<?= base_url($folder.'/cform/getdetailsj'); ?>',
        dataType: "json",
        success: function (data) {
            
                // var i_memo = data['head']['n_discount'];
                // var d_memo = data['head']['d_memo'];
                // var d_bonk = data['head']['d_bonk'];
                var n_discount = data['head']['n_discount'];
                $('#ndiscount').val(n_discount+" %");
                if (n_discount == 0) {
                    n_discount = 1;
                } else {
                    n_discount = n_discount /100;
                }
                // $('#gross').val(i_memo);
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
                    var i_sj    = data['detail'][a]['i_sj'];
                    var i_wip    = data['detail'][a]['i_wip'];
                    var e_namabrg       = data['detail'][a]['e_namabrg'];
                    var i_color    = data['detail'][a]['i_color'];
                    var e_color_name    = data['detail'][a]['e_color_name'];
                    // var qty_keluar       = data['detail'][a]['qty_keluar'];
                    var v_price       = data['detail'][a]['v_price'];
                    // var qty_masuk       = data['detail'][a]['qty_masuk'];
                    // var qty_nota       = data['detail'][a]['qty_nota'];
                    var qty_belumnota       = data['detail'][a]['qty_belumnota'];
                
                    v_gross = qty_belumnota * v_price;
                    v_discount  = v_gross*n_discount;
                    total = total + (v_gross-v_discount);
                    totgross = totgross + v_gross;
                    totdisc = totdisc + v_discount;

                    v_price = formatcemua(v_price);
                    v_gross = formatcemua(v_gross);

                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                    cols += '<td><input style="width:200px" class="form-control" readonly id="i_sj'+zz+'" name="i_sj'+zz+'" value="'+i_sj+'"></td>';
                    cols += '<td><input style="width:120px" class="form-control" readonly id="i_wip'+zz+'" name="i_wip'+zz+'" value="'+i_wip+'"></td>';
                    cols += '<td><input style="width:400px" class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+e_namabrg+'" value="'+e_namabrg+'"></td>';
                    cols += '<td><input style="width:150px" type="hidden" class="form-control" style="text-align:right;" id="i_color'+zz+'" name="i_color'+zz+'" value="'+i_color+'"><input readonly style="width:150px"  class="form-control" style="text-align;" id="e_color'+zz+'" name="e_color'+zz+'" value="'+e_color_name+'"></td>';
                    // cols += '<td><input style="width:80px" readonly class="form-control" style="text-align:right;" id="qty_keluar'+zz+'" name="qty_keluar'+zz+'" value="'+qty_keluar+'"></td>';
                    // cols += '<td><input style="width:80px" readonly class="form-control" style="text-align:right;" id="qty_masuk'+zz+'" name="qty_masuk'+zz+'" value="'+qty_masuk+'"></td>';
                    // cols += '<td><input style="width:80px" readonly class="form-control" style="text-align:right;" id="qty_keluar'+zz+'" name="qty_keluar'+zz+'" value="'+qty_nota+'"></td>';
                    cols += '<td><input style="width:80px" readonly class="form-control" style="text-align:right;" id="qty_belumnota'+zz+'" name="qty_belumnota'+zz+'" value="'+qty_belumnota+'"></td>';
                    cols += '<td><input style="width:100px" readonly class="form-control" style="text-align:right;" id="hargasatuan'+zz+'" name="hargasatuan'+zz+'" value="'+v_price+'"></td>';
                    cols += '<td><input style="width:150px" readonly class="form-control" style="text-align:right;" id="hargatotal'+zz+'" name="hargatotal'+zz+'" value="'+v_gross+'"><input style="width:80px" type="hidden" class="form-control" style="text-align:right;" id="discount'+zz+'" name="discount'+zz+'" value="'+v_discount+'"></td>';
                    // cols += '<td><input style="width:80px" type="text" id="nquantity'+ zz + '" class="form-control text-right" name="nquantity'+ zz + '" value="0" onkeypress="return hanyaAngka(event);"></td>';
                    cols += '<td><input style="width:200px" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value=""/></td>';
                    cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" class="'+i_sj+'" checked/></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                    
                    $("#chk"+zz).click(function () {
                        var clas = $(this).attr('class');
                        $('.'+clas).prop("checked",$(this).prop("checked"));
                        ngetang();
                    });
      
                }

                                 

                $('#gross').val("Rp. "+formatcemua(totgross));
                $('#netto').val("Rp. "+formatcemua(total));
                $('#discount').val("Rp. "+formatcemua(totdisc));
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function getdataedit(sj, nota) {
        var partner = $("#partner").val();
        //swal(sj, partner);
        removeBody();

        $.ajax({
        type: "post",
        data: {
            'sj': sj,
            'partner': partner,
            'nota': nota
        },
        url: '<?= base_url($folder.'/cform/getdetailsjview'); ?>',
        dataType: "json",
        success: function (data) {
            
                // var i_memo = data['head']['n_discount'];
                // var d_memo = data['head']['d_memo'];
                // var d_bonk = data['head']['d_bonk'];
                var n_discount = data['head']['n_discount'];
                var pkp = data['head']['pkp'];
                $('#pkp').val(pkp);
                $('#ndiscount').val(n_discount+" %");
                if (n_discount == 0) {
                    n_discount = 1;
                } else {
                    n_discount = n_discount /100;
                }
                // $('#gross').val(i_memo);
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
                var zz = 0;
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var i_sj    = data['detail'][a]['i_sj'];
                    var i_wip    = data['detail'][a]['i_wip'];
                    var e_namabrg       = data['detail'][a]['e_namabrg'];
                    var i_color    = data['detail'][a]['i_color'];
                    var e_color_name    = data['detail'][a]['e_color_name'];
                    //var qty_keluar       = data['detail'][a]['qty_keluar'];
                    var v_price       = data['detail'][a]['v_price'];
                    //var qty_masuk       = data['detail'][a]['qty_masuk'];
                    //var qty_nota       = data['detail'][a]['qty_nota'];
                    var qty_belumnota       = data['detail'][a]['qty_sudahnota'];
                    //var edit       = data['detail'][a]['edit'];
                    
                    var checked = '';
                    v_gross = qty_belumnota * v_price;
                    v_discount  = v_gross*n_discount;

                    var cols        = "";
                    var newRow = $("<tr>");

                        checked = 'checked="checked"';
                        total = total + (v_gross-v_discount);
                        totgross = totgross + v_gross;
                        totdisc = totdisc + v_discount;

                        v_price = formatcemua(v_price);
                        v_gross = formatcemua(v_gross);

                        cols += '<td style="text-align: left">'+zz+'<input type="hidden" id="baris'+zz+'" name="baris'+zz+'" value="'+zz+'"></td>';
                        cols += '<td><input style="width:200px" class="form-control" readonly id="i_sj'+zz+'" name="i_sj'+zz+'" value="'+i_sj+'"></td>';
                        cols += '<td><input style="width:120px" class="form-control" readonly id="i_wip'+zz+'" name="i_wip'+zz+'" value="'+i_wip+'"></td>';
                        cols += '<td><input style="width:400px" class="form-control" readonly id="namabarang'+zz+'" name="namabarang'+zz+'" title="'+e_namabrg+'" value="'+e_namabrg+'"></td>';
                        cols += '<td><input style="width:150px" type="hidden" class="form-control" style="text-align:right;" id="i_color'+zz+'" name="i_color'+zz+'" value="'+i_color+'"><input readonly style="width:150px"  class="form-control" style="text-align;" id="e_color'+zz+'" name="e_color'+zz+'" value="'+e_color_name+'"></td>';
                        // cols += '<td><input style="width:80px" readonly class="form-control" style="text-align:right;" id="qty_keluar'+zz+'" name="qty_keluar'+zz+'" value="'+qty_keluar+'"></td>';
                        // cols += '<td><input style="width:80px" readonly class="form-control" style="text-align:right;" id="qty_masuk'+zz+'" name="qty_masuk'+zz+'" value="'+qty_masuk+'"></td>';
                        // cols += '<td><input style="width:80px" readonly class="form-control" style="text-align:right;" id="qty_keluar'+zz+'" name="qty_keluar'+zz+'" value="'+qty_nota+'"></td>';
                        cols += '<td><input style="width:80px" readonly class="form-control" style="text-align:right;" id="qty_belumnota'+zz+'" name="qty_belumnota'+zz+'" value="'+qty_belumnota+'"></td>';
                        cols += '<td><input style="width:100px" readonly class="form-control" style="text-align:right;" id="hargasatuan'+zz+'" name="hargasatuan'+zz+'" value="'+v_price+'"></td>';
                        cols += '<td><input style="width:150px" readonly class="form-control" style="text-align:right;" id="hargatotal'+zz+'" name="hargatotal'+zz+'" value="'+v_gross+'"><input style="width:80px" type="hidden" class="form-control" style="text-align:right;" id="discount'+zz+'" name="discount'+zz+'" value="'+v_discount+'"></td>';
                        // cols += '<td><input style="width:80px" type="text" id="nquantity'+ zz + '" class="form-control text-right" name="nquantity'+ zz + '" value="0" onkeypress="return hanyaAngka(event);"></td>';
                        cols += '<td><input style="width:200px" type="text" id="edesc'+ zz + '" class="form-control" name="edesc' + zz + '" value="" readonly/></td>';
                    
                    
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
      
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
        /*var tot = 0;*/
        var gross2 = 0;
        var discount2 = 0;
        var total2 = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#qty_belumnota"+brs).val();
            hrg  = formatulang($("#hargasatuan"+brs).val());
            qty  = formatulang(ord);
           
            vhrg = parseFloat(hrg)*parseFloat(qty)-parseFloat(formatulang($("#discount"+brs).val()));
            //$("#hargatotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                total2+=parseFloat(vhrg);
                discount2 += parseFloat(formatulang($("#discount"+brs).val()));
                gross2  += parseFloat(hrg)*parseFloat(qty);
            }
        }
        $('#gross').val("Rp. "+formatcemua(gross2));
        $('#discount').val("Rp. "+formatcemua(discount2));
        $("#netto").val("Rp. "+formatcemua(total2));
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