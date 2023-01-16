<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Kredit Nota</label><label class="col-md-6">Tanggal Kredit Nota</label>
                        <div class="col-sm-6">
                            <input type="text" maxlength="2" required="" id= "ikn" name="ikn" class="form-control" value="" placeholder="No KN Auto" readonly="">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dkn" name="dkn" class="form-control date" value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">No. Refferensi</label><label class="col-md-6">Tanggal Referensi</label>
                        <div class="col-sm-6">
                            <select required="" id= "irefference" name="irefference" class="form-control" disabled="" onchange="getdetailref(this.value);"></select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "drefference" name="drefference" class="form-control" value="">
                        </div>
                    </div>          
                    <div class="form-group row">
                        <label class="col-md-6">Nilai Kotor</label><label class="col-md-6">Nilai Potongan</label>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" id= "vgross" name="vgross" class="form-control" value="0" readonly="">
                        </div>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" id= "vdiscount" name="vdiscount" class="form-control" value="0"  readonly="">
                            <input type="hidden" name="nttbdiscount1" id="nttbdiscount1" value="0">
                            <input type="hidden" name="nttbdiscount2" id="nttbdiscount2" value="0">
                            <input type="hidden" name="nttbdiscount3" id="nttbdiscount3" value="0">
                            <input type="hidden" name="vttbdiscount1" id="vttbdiscount1" value="0">
                            <input type="hidden" name="vttbdiscount2" id="vttbdiscount2" value="0">
                            <input type="hidden" name="vttbdiscount3" id="vttbdiscount3" value="0">
                            <input type="hidden" name="vttbdiscounttotal" id="vttbdiscounttotal" value="0">
                            <input type="hidden" name="vttbnetto" id="vttbnetto" value="0">
                            <input type="hidden" name="vttbgross" id="vttbgross" value="0">
                        </div>
                    </div>          
                    <div class="form-group row">
                        <label class="col-md-6">Nilai Bersih</label><label class="col-md-6">Nilai Sisa</label>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" readonly id= "vnetto" name="vnetto" class="form-control" value="0">
                        </div>
                        <div class="col-sm-6">
                            <input style="text-align: right;" required="" readonly id= "vsisa" name="vsisa" class="form-control" value="0">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-6">No. Pajak</label><label class="col-md-6">Tanggal Pajaks</label>
                        <div class="col-sm-6">
                            <select type="text" readonly id= "ipajak" name="ipajak" class="form-control" disabled="" onchange="getdetailpajak(this.value);"></select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dpajak" name="dpajak" class="form-control">
                        </div>
                    </div>                            
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-12">Area</label>
                            <div class="col-sm-12">
                                <select name="iarea" id="iarea" required="" class="form-control select2" onchange="getarea(this.value);">
                                    <option value=""></option>
                                    <?php if ($area) {                                 
                                        foreach ($area as $key) { ?>
                                            <option value="<?php echo $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option>
                                        <?php }; 
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Pelanggan</label>
                            <div class="col-sm-12">
                                <input name="ecustomername" id="ecustomername" readonly="" class="form-control">
                                <input type="hidden" name="icustomer" id="icustomer">
                                <input type="hidden" name="icustomergroupar" id="icustomergroupar">
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-12">Alamat</label>
                            <div class="col-sm-12">
                                <input  name="ecustomeraddress" id="ecustomeraddress" class="form-control" readonly>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <label class="col-md-12">Salesman</label>
                            <div class="col-sm-12">
                                <input name="esalesmanname" id="esalesmanname" class="form-control" readonly=""></select>
                                <input type="hidden" name="isalesman" id="isalesman">
                            </div>
                        </div>                   
                        <div class="form-group row"> 
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" id="finsentif" name="finsentif" class="custom-control-input" checked>
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Insentif</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" id="fmasalah" name="fmasalah" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Masalah</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <input name="eremark" id="eremark" class="form-control">
                            </div>
                        </div>  
                    </div>
                    <input type="hidden" name="jml" id="jml" value="0">
                    <div class="col-md-12">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 10%;">Kode</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center; width: 6%;">Motif</th>
                                    <th style="text-align: center;">Jumlah</th>
                                    <th style="text-align: center;">Harga</th>
                                    <th style="text-align: center;">Keterangan</th>
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
</div>
<script>
    function getarea(kode) {
        if (kode!='') {
            $("#irefference").attr("disabled", false);
        }else{
            $("#irefference").attr("disabled", true);
        }
        $('#irefference').html('');
        $('#irefference').val('');
    }

    function getdetailref(kode) {
        if (kode!='') {
            $("#ipajak").attr("disabled", false);
            $("#tabledata").attr("hidden", false);
        }else{
            $("#ipajak").attr("disabled", true);
            $("#tabledata").attr("hidden", true);
        }
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        var iarea  = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'ibbm': kode,
                'iarea': iarea
            },
            url: '<?= base_url($folder.'/cform/getdetailref'); ?>',
            dataType: "json",
            success: function (data) {
                $('#drefference').val(data['data'][0].d_bbm); 
                $('#icustomer').val(data['data'][0].i_customer); 
                $('#icustomergroupar').val(data['data'][0].i_customer_groupar); 
                $('#ecustomername').val(data['data'][0].e_customer_name); 
                $('#isalesman').val(data['data'][0].i_salesman); 
                $('#esalesmanname').val(data['data'][0].e_salesman_name);
                $('#ecustomeraddress').val(data['data'][0].e_customer_address); 
                $('#vgross').val(formatcemua(data['data'][0].v_ttb_gross)); 
                $('#vttbgross').val(data['data'][0].v_ttb_gross); 
                $('#vsisa').val(formatcemua(data['data'][0].v_ttb_sisa)); 
                $('#vnetto').val(formatcemua(data['data'][0].v_ttb_netto)); 
                $('#vttbnetto').val(data['data'][0].v_ttb_netto);
                $('#vdiscount').val(formatcemua(data['data'][0].v_ttb_discounttotal)); 
                $('#vttbdiscounttotal').val(data['data'][0].v_ttb_discounttotal); 
                $('#nttbdiscount1').val(data['data'][0].n_ttb_discount1);
                $('#nttbdiscount2').val(data['data'][0].n_ttb_discount2);
                $('#nttbdiscount3').val(data['data'][0].n_ttb_discount3);
                $('#vttbdiscount1').val(data['data'][0].v_ttb_discount1);
                $('#vttbdiscount2').val(data['data'][0].v_ttb_discount2);
                $('#vttbdiscount3').val(data['data'][0].v_ttb_discount3);
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {
                    var no = a+1;
                    var produk      = data['detail'][a]['i_product'];
                    var namaproduk  = data['detail'][a]['e_product_name'];
                    var imotif      = data['detail'][a]['i_product_motif'];
                    var igrade      = data['detail'][a]['i_product_grade'];
                    var motif       = data['detail'][a]['e_product_motifname'];
                    var qty         = data['detail'][a]['n_quantity'];
                    var harga       = data['detail'][a]['v_unit_price'];
                    var ket         = data['detail'][a]['e_remark'];
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+no+'<input type="hidden" id="baris'+no+'" name="baris'+no+'" value="'+no+'"></td>';
                    cols += '<td><input class="form-control" readonly id="iproduct'+no+'" name="iproduct'+no+'" value="'+produk+'"></td>';
                    cols += '<td><input class="form-control" readonly id="eproductname'+no+'" name="eproductname'+no+'" value="'+namaproduk+'"><input type="hidden" readonly id="iproductmotif'+no+'" name="iproductmotif'+no+'" value="'+imotif+'"><input type="hidden" readonly id="iproductgrade'+no+'" name="iproductgrade'+no+'" value="'+igrade+'"></td>';
                    cols += '<td><input readonly class="form-control" id="emotifname'+no+'" name="emotifname'+no+'" value="'+motif+'"></td>';
                    cols += '<td><input readonly class="form-control" style="text-align:right;" id="nquantity'+no+'" name="nquantity'+no+'" value="'+qty+'"></td>';
                    cols += '<td><input class="form-control" style="text-align:right;" id="vunitprice'+no+'" name="vunitprice'+no+'" value="'+harga+'" onkeyup="ngetang(this.value);" onkeypress="return hanyaAngka(event);"></td>';
                    cols += '<td><input class="form-control" id="eremark'+no+'" name="eremark'+no+'" value="'+ket+'"></td>';;
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function getdetailpajak(kode) {
        var iproduct  = $('#iproduct1').val();
        var icustomer = $('#icustomer').val();
        $.ajax({
            type: "post",
            data: {
                'inota': kode,
                'iproduct': iproduct,
                'icustomer': icustomer
            },
            url: '<?= base_url($folder.'/cform/getdetailpajak'); ?>',
            dataType: "json",
            success: function (data) {
                $('#dpajak').val(data[0].d_pajak);
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
    });

    $(document).ready(function () {
        /*ngetang(0);*/
        $('.select2').select2();
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Pilih Area'
        });

        $('#irefference').select2({
            placeholder: 'Cari Berdasarkan BBM',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getreferensi/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea  = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iarea: iarea
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

        $('#ipajak').select2({
            placeholder: 'Cari Berdasarkan Nota',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getpajak/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var icustomer = $('#icustomer').val();
                    var iproduct  = $('#iproduct1').val();
                    var query = {
                        q: params.term,
                        iproduct: iproduct,
                        icustomer: icustomer
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

    function ngetang(input){
        var jml   = parseFloat(document.getElementById("jml").value);
        var num   = input;
        if(!isNaN(num)){
            for(j=1;j<=jml;j++){
                if(document.getElementById("nquantity"+j).value=='') {
                    document.getElementById("nquantity"+j).value='0';
                }
                var jml     = parseFloat(document.getElementById("jml").value);
                var totdis  = 0;
                var totnil  = 0;
                var hrg     = 0;
                var ndis1   = parseFloat(formatulang(document.getElementById("nttbdiscount1").value));
                var ndis2   = parseFloat(formatulang(document.getElementById("nttbdiscount2").value));
                var ndis3   = parseFloat(formatulang(document.getElementById("nttbdiscount3").value));
                var vdis1   = 0;
                var vdis2   = 0;
                var vdis3   = 0;
                for(i=1;i<=jml;i++){
                    var vprod=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
                    var nquan=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
                    var hrgtmp  = vprod*nquan;
                    hrg         = hrg+hrgtmp;
                }
                console.log(num);
                alert(num);
                vdis1=vdis1+((hrg*ndis1)/100);
                vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
                vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
                vdistot = Math.round(vdis1+vdis2+vdis3);
                vhrgreal= hrg-vdistot;
                document.getElementById("vttbdiscount1").value=formatcemua(vdis1);
                document.getElementById("vttbdiscount2").value=formatcemua(vdis2);
                document.getElementById("vttbdiscount3").value=formatcemua(vdis3);
                document.getElementById("vttbdiscounttotal").value=formatcemua(vdistot);
                document.getElementById("vttbnetto").value=formatcemua(vhrgreal);
                document.getElementById("vttbgross").value=formatcemua(hrg);
                document.getElementById("vdiscount").value=formatcemua(vdistot);
                document.getElementById("vnetto").value=formatcemua(vhrgreal);
                document.getElementById("vsisa").value=formatcemua(vhrgreal);
                document.getElementById("vgross").value=formatcemua(hrg);

            }
        }else{ 
            /*swal('input harus numerik !!!');*/
            input = input.substring(0,input.length-1);
        }
    }

    function dipales(){
        if((document.getElementById("dkn").value=='') || (document.getElementById("iarea").value=='') || (document.getElementById("irefference").value=='') || (document.getElementById("finsentif").checked==false)){
            swal("Data Header belum lengkap !!!");
            return false;
        }else{          
            return true;
        }
    }
</script>