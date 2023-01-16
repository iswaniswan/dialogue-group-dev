<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-edit"></i> &nbsp;UPDATE <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/view/<?= $dfrom.'/'.$dto.'/'.$icustomer;?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-rotate-left"></i> Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal BON</label><label class="col-md-6">Nomor BON</label>
                        <div class="col-sm-6">
                            <input readonly id="dnotapb" required="" name="dnotapb" class="form-control date" onchange="cektanggal();" value="<?= date('d-m-Y', strtotime($isi->d_notapb)); ?>">
                            <input type=hidden id="dnotapbx" name="dnotapbx" value="<?= date('d-m-Y', strtotime($isi->d_notapb)); ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="inotapb" name="inotapb" readonly="" class="form-control" value="<?php if($inotapb) echo $inotapb; ?>" maxlength="7">
                            <input type="hidden" id="xinotapb" name="xinotapb" value="<?php if($inotapb) echo $inotapb; ?>" maxlength="7">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">SPG</label>
                        <div class="col-sm-6">
                            <input id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>" readonly>
                            <input type="hidden" id="iarea" name="iarea" class="form-control" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input id="espgname" name="espgname" class="form-control" value="<?= $isi->e_spg_name; ?>" readonly>
                            <input type="hidden" id="ispg" name="ispg" class="form-control" value="<?= $isi->i_spg; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?= $isi->e_customer_name; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?= $isi->i_customer; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Potongan</label><label class="col-md-6">Total</label>
                        <div class="col-sm-2">
                            <input style="text-align:right;" id="nnotapbdiscount" name="nnotapbdiscount" class="form-control" value="<?= number_format($isi->n_notapb_discount); ?>" onkeyup="hitungnilai();">
                        </div>
                        <div class="col-sm-1">
                            <span><b>%</b></span>
                        </div>
                        <div class="col-sm-3">
                            <input style="text-align:right;" readonly id="vnotapbdiscount" name="vnotapbdiscount" class="form-control" value="<?= number_format($isi->v_notapb_discount); ?>" onkeyup="diskonrupiah();">
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" id="vnotapbgross" name="vnotapbgross" value="<?= number_format($isi->v_notapb_gross); ?>">
                            <input style="text-align:right;" class="form-control" readonly id="vnotapbnetto" name="vnotapbnetto" value="<?= number_format($isi->v_notapb_gross-$isi->v_notapb_discount); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-sm-offset-5 col-sm-12">
                        <?php $user = $this->session->userdata('username'); if( $user == 'admin' || $user == 'spvbaby' && $isi->f_spb_rekap=='f' && ($isi->i_spb=='' || $isi->i_spb==null)){ ?>
                            <button type="button" id="bcek" class="btn btn-danger btn-rounded btn-sm"> <i class="fa fa-times"></i>&nbsp;&nbsp;Batal Cek</button>&nbsp;&nbsp;
                        <?php } ?>
                        <?php if ($isi->f_spb_rekap=='f' && ($isi->i_spb=='' || $isi->i_spb==null) && ($isi->i_cek=='' || $isi->i_cek==null)){ ?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">No</th>
                                    <th style="text-align: center; width: 12%;">Kode Barang</th>
                                    <th style="text-align: center; width: 35%;">Nama Barang</th>
                                    <th style="text-align: center;">Jumlah</th>
                                    <th style="text-align: center;">Harga</th>
                                    <th style="text-align: center;">Total</th>
                                    <th style="text-align: center;">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($detail) {
                                    $i = 0;
                                    foreach ($detail as $row) {
                                        $i++;
                                        $totall=$row->n_quantity*$row->v_unit_price;
                                        ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?= $i;?>
                                                <input readonly type="hidden" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                                <input type="hidden" id="ipricegroupco<?= $i;?>" name="ipricegroupco<?= $i;?>" value="<?= $row->i_price_groupco;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" style="text-align:right;" onkeypress="return hanyaAngka(event);" id="nquantity<?= $i;?>" name="nquantity<?= $i;?>" value="<?= $row->n_quantity;?>" onkeyup="hitungnilai(<?= $i;?>);">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly style="text-align:right;" type="text" id="vunitprice<?= $i;?>" name="vunitprice<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" style="text-align:right;" readonly="" type="text" id="total<?= $i;?>" name="total<?= $i;?>" value="<?= $totall;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
            </form>
        </div>
    </div>
</div>
</div>
</div>

<script>
    $( "#bcek" ).click(function() {
        swal({   
            title: "Batal Cek Bon Ini ?",   
            text: "Anda harus yakin!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya!",   
            cancelButtonText: "Tidak!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'i_notapb'  : $('#i_notapb').val(),
                        'icustomer' : $('#icustomer').val(),
                    },
                    url: '<?= base_url($folder.'/cform/batalcek'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dibatalkan!", "Data berhasil dibatalkan :)", "success");
                        show('<?= $folder;?>/cform/view/<?= $dfrom.'/'.$dto.'/'.$icustomer;?>','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dibatalkan :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan pembatalan :)", "error");
            } 
        });
    });

    function cektanggal() {
        var dnotapb = document.getElementById('dnotapb').value;
        var periode = '<?= $periode->i_periode; ?>';
        var dtmp    = dnotapb.split('-');
        thnnotapb   = dtmp[2];
        blnnotapb   = dtmp[1];
        hrnotapb    = dtmp[0];
        thnbln      = thnnotapb+blnnotapb;
        if(thnbln < periode){
            swal('Tanggal Bon tidak boleh kurang dari periode : '+periode);
            document.getElementById('dnotapb').value = '';
        }
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    var counter = $('#jml').val();
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        var icustomer = $("#icustomer").val();

        var cols = "";

        cols+='<td style="text-align: center">'+counter+'<input type="hidden" readonly style="width:40px;" type="text" id="baris'+counter+'" name="baris'+counter+'"  class="form-control" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""><input type="hidden" id="ipricegroupco'+counter+'" name="ipricegroupco'+counter+'" value=""></td>';
        cols+='<td><select readonly type="text" id="iproduct'+counter+'" name="iproduct'+counter+'"  class="form-control select2" value="" onchange="getproduct('+counter+')"></select></td>';
        cols+='<td><input readonly readonly type="text" id="eproductname'+counter+'" name="eproductname'+counter+'"  class="form-control" value=""></td>';
        cols+='<td><input style="text-align:right;" onkeypress="return hanyaAngka(event);" type="text" id="nquantity'+counter+'" name="nquantity'+counter+'"  class="form-control" value="" onkeyup="hitungnilai('+counter+');"></td>';
        cols+='<td><input readonly style="text-align:right;" type="text" id="vunitprice'+counter+'" name="vunitprice'+counter+'"  class="form-control" value=""></td>';
        cols+='<td><input readonly style="text-align:right;" onkeypress="return hanyaAngka(event);" type="text" id="total'+counter+'" name="total'+counter+'"  class="form-control" value=""></td>';
        cols+='<td><input style="text-align:right;" type="text" id="eremark'+counter+'" name="eremark'+counter+'"  class="form-control" value=""></td>';
        /*cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger " value="Delete"></td>';*/

        newRow.append(cols);
        $("#tabledata").append(newRow);

        $("#tabledata").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();       
            counter -= 1
            document.getElementById("jml").value = counter;
        });

        $('#iproduct'+ counter).select2({
            placeholder: 'Pilih Kode Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databarang/'); ?>'+icustomer,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });

    function getproduct(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var iproduct = $('#iproduct'+id).val();
            var icustomer = $("#icustomer").val();
            $.ajax({
                type: "post",
                data: {
                    'i_product': iproduct
                },
                url: '<?= base_url($folder.'/cform/getproduct/'); ?>'+icustomer,
                dataType: "json",
                success: function (data) {
                    $('#iproduct'+id).val(data[0].i_product);
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#vunitprice'+id).val(data[0].v_product_retail);
                    $('#motif'+id).val(data[0].i_product_motif);
                    $('#ipricegroupco'+id).val(data[0].i_price_groupco);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
        }
    }

    function hitungnilai(){
        jml=document.getElementById("jml").value;
        bener=true;
        for(i=1;i<=jml;i++){
            qty=document.getElementById("nquantity"+i).value;
            if (isNaN(parseFloat(qty))){
                swal("Input harus numerik");
                bener=false;
                break;
            }
        }
        if(bener){
            tot=0;
            subtot=0;
            for(i=1;i<=jml;i++){
                qty=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
                hrg=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
                subtot=qty*hrg;
                document.getElementById("total"+i).value=formatcemua(subtot);
                tot=tot+subtot;
            }
            document.getElementById("vnotapbgross").value=formatcemua(tot);
            dis=parseFloat(formatulang(document.getElementById("nnotapbdiscount").value));
            vdis=(tot*dis)/100;
            document.getElementById("vnotapbdiscount").value=formatcemua(vdis);
            document.getElementById("vnotapbnetto").value=formatcemua(tot-vdis);
        }
    }

    function diskonrupiah(){
        jml=document.getElementById("jml").value;
        bener=true;
        for(i=1;i<=jml;i++){
            qty=document.getElementById("nquantity"+i).value;
            if (isNaN(parseFloat(qty))){
                swal("Input harus numerik");
                bener=false;
                break;
            }
        }
        if(bener){
            tot=0;
            subtot=0;
            for(i=1;i<=jml;i++){
                qty=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
                hrg=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
                subtot=qty*hrg;
                document.getElementById("total"+i).value=formatcemua(subtot);
                tot=tot+subtot;
            }
            document.getElementById("vnotapbgross").value=formatcemua(tot);
            vdis=parseFloat(formatulang(document.getElementById("vnotapbdiscount").value));
            dis=roundNumber((vdis*100)/tot,2);
            document.getElementById("nnotapbdiscount").value=dis;
            document.getElementById("vnotapbnetto").value=formatcemua(tot-vdis);
        }
    }

    function dipales(a){
        if((document.getElementById("dnotapb").value!='') && (document.getElementById("inotapb").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nquantity"+i).value=='')){
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#bcek").attr("disabled", true);
    });
</script>