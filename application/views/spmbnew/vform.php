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
                        <label class="col-md-6">Tanggal SPMB</label><label class="col-md-6">No. SPMB</label>
                        <div class="col-sm-6">
                            <input type="text" required="" readonly id= "dspmb" name="dspmb" class="form-control date" readonly value="<?= date('d-m-Y');?>">
                            <input id="d_now" name="d_now" type="hidden" value="<?php echo date('Y-m-d');?>">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" maxlength="2" id= "ispmbold" name="ispmbold" class="form-control" value="">
                            <input id="ispmb" name="ispmb" type="hidden">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <select name="istore" id="istore" required="" class="form-control select2" onchange="getdetailbarang(this.value);">
                                <option value=""></option>
                                <?php if ($store) {                                 
                                    foreach ($store as $key) { ?>
                                        <option value="<?php echo $key->i_store;?>"><?= $key->i_store_location." - ".$key->e_store_name." - ".$key->e_store_locationname;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                            <input id="iarea" name="iarea" type="hidden">
                            <input id="istorelocation" name="istorelocation" type="hidden">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input name="eremark" id="eremark" class="form-control">
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
                    <input type="hidden" name="jml" id="jml" value="0">
                    <div class="col-md-12">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 10%;">Kode</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center; width: 6%;">Motif</th>
                                    <th style="text-align: center;">Jumlah Rata2</th>
                                    <th style="text-align: center;">Nilai Rata2</th>
                                    <th style="text-align: center;">Qty Pesan</th>
                                    <th style="text-align: center;">Qty Acc</th>
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
    function getdetailbarang(kode) {
        var istore = $('#istore option:selected').text();
        var istorelocation = istore.substr(0,2);
        if (kode!='') {
            $("#iarea").val(kode);
            $("#tabledata").attr("hidden", false);
        }else{
            $("#tabledata").attr("hidden", true);
        }
        var dspmb = $('#dspmb').val();
        $.ajax({
            type: "post",
            data: {
                'istore': kode,
                'dspmb' : dspmb,
                'istorelocation' : istorelocation
            },
            url: '<?= base_url($folder.'/cform/getdetailitem'); ?>',
            dataType: "json",
            success: function (data) {
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {                  
                    var xx = a+1;
                    var imotif      = data['detail'][a]['i_product_motif'];
                    var produk      = data['detail'][a]['i_product'];
                    var namaproduk  = data['detail'][a]['e_product_name'];
                    var motif       = data['detail'][a]['e_product_motifname'];
                    var harga       = data['detail'][a]['v_product_mill'];
                    var vrata       = data['detail'][a]['vrata'];
                    var nrata       = data['detail'][a]['nrata'];
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+xx+'<input type="hidden" id="baris'+xx+'" name="baris'+xx+'" value="'+xx+'"><input type="hidden" name="motif'+xx+'" id="motif'+xx+'" value="'+imotif+'"></td>';
                    cols += '<td><input class="form-control" readonly id="iproduct'+xx+'" name="iproduct'+xx+'" value="'+produk+'"></td>';
                    cols += '<td><input class="form-control" readonly id="eproductname'+xx+'" name="eproductname'+xx+'" value="'+namaproduk+'"></td>';
                    cols += '<td><input readonly class="form-control" id="emotifname'+xx+'" name="emotifname'+xx+'" value="'+motif+'"><input readonly type="hidden" id="vproductmill'+xx+'" name="vproductmill'+xx+'" value="'+harga+'"></td>';
                    cols += '<td><input readonly class="form-control" style="text-align:right;" id="jmlrata'+xx+'" name="jmlrata'+xx+'" value="'+nrata+'"></td>';
                    cols += '<td><input readonly class="form-control" style="text-align:right;" id="nilairata'+xx+'" name="nilairata'+xx+'" value="'+vrata+'"></td>';
                    cols += '<td><input class="form-control" style="text-align:right;" id="norder'+xx+'" name="norder'+xx+'" value="0" onkeypress="return hanyaAngka(event);" onkeyup="hitungnilai('+xx+');"></td>';
                    cols += '<td><input class="form-control" readonly style="text-align:right;" id="nacc'+xx+'" name="nacc'+xx+'" value="0"><input type="hidden" id="vtotal'+xx+'" name="vtotal'+xx+'" value="0"></td>';
                    cols += '<td><input class="form-control" id="eremark'+xx+'" name="eremark'+xx+'" value=""></td>';
                    newRow.append(cols);

                    $("#tabledata").append(newRow);
                }
            },
            error: function () {
                swal('Data kosong :)');
            }
        });
    }

    function hitungnilai(brs){
        ord=document.getElementById("norder"+brs).value;
        hrg=formatulang(document.getElementById("vproductmill"+brs).value);
        qty=formatulang(ord);
        vhrg=parseFloat(hrg)*parseFloat(qty);
        document.getElementById("vtotal"+brs).value=formatcemua(vhrg);
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date',0);
        $('#istore').select2({
            placeholder: 'Pilih Lokasi Gudang'
        });
    });

    function dipales(){
        var a = $('#jml').val();
        if((document.getElementById("dspmb").value!='') && (document.getElementById("iarea").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("norder"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }
</script>