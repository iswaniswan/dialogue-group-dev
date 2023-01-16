<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">No SJ</label><label class="col-md-6">Tanggal SJ</label>
                        <div class="col-sm-6">
                            <input class="form-control" readonly id="isj" name="isj" value="<?= $isi->i_sjpbr;?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly id= "dsj" name="dsj" class="form-control date" value="<?= $isi->d_sjpbr;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="hidden" readonly id= "iarea" name="iarea" class="form-control" value="<?= $isi->i_area;?>">
                            <input type="text" readonly id= "eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if (check_role($i_menu, 3) && $isi->d_sjpbr_receive == '') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                            <?php } ?>
                            &nbsp;&nbsp;
                            <?php if (check_role($i_menu, 3) && $isi->d_sjpbr_receive == '') {?>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            <?php } ?>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $iarea.'/'.$dfrom.'/'.$dto;?>","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="hidden" readonly id= "icustomer" name="icustomer" class="form-control" value="<?= $isi->i_customer;?>">
                            <input type="text" readonly id= "ecustomername" name="ecustomername" class="form-control" value="<?= $isi->e_customer_name;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">SPG</label>
                        <div class="col-sm-12">
                            <input id="ispg" type="hidden" name="ispg" class="form-control" required="" readonly value="<?= $isi->i_spg; ?>">
                            <input id="espgname" type="text" name="espgname" class="form-control" required="" readonly value="<?= $isi->e_spg_name; ?>">
                            <input id="vsjpbr" type="hidden" name="vsjpbr" class="form-control" required="" readonly value="<?= $isi->v_sjpbr; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 4%;">No</th>
                                    <th style="text-align: center; width: 15%;">Kode Barang</th>
                                    <th style="text-align: center; width: 30%;">Nama Barang</th>
                                    <th style="text-align: center; width: 30%;">keterangan</th>
                                    <th style="text-align: center; width: 10%;">Jumlah Retur</th>
                                    <th style="text-align: center;  width: 10%;">Jumlah Terima</th>
                                    <th style="text-align: center; width: 5%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                 $i = 0;
                                 if ($detail) { 
                                    foreach ($detail as $row) {
                                        $vtotal = $row->v_unit_price * $row->n_quantity_retur;
                                        $i++;?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $i;?>
                                                <input type="hidden" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input type="hidden" readonly id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                                <input class="form-control" readonly type="hidden" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                                <input class="form-control" readonly type="hidden" id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" type="text" id="nretur<?= $i;?>" name="nretur<?= $i;?>" value="<?= $row->n_quantity_retur;?>" onkeyup="ngetang();">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" readonly type="text" id="nreceive<?= $i;?>" name="nreceive<?= $i;?>" value="<?= $row->n_quantity_receive;?>"
                                                <input class="form-control" readonly type="hidden" id="nasal<?= $i;?>" name="nasal<?= $i;?>" value="<?= $row->n_quantity_retur;?>">
                                                <input class="form-control" readonly type="hidden" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                            </td>
                                            <td style="text-align: center;">
                                                    <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick="ngetang()">
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        $('#jml').val(xx);
        var newRow = $("<tr>");
        count=$('#tabledata tr').length;
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
        cols += '<td><select id="iproduct'+xx+'" class="form-control select2" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+');"></select><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><input readonly type="text" class="form-control" id="eproductname'+xx+'" name="eproductname'+xx+'" value=""><input readonly type="hidden" class="form-control" id="emotifname'+xx+'" name="emotifname'+xx+'" value=""></td>';
        cols += '<td><input readonly type="text" class="form-control" id="eremark'+xx+'" name="eremark'+xx+'" value=""><input readonly type="hidden" class="form-control" id="vproductmill'+xx+'" name="vproductmill'+xx+'" value=""></td>';
        cols += '<td><input style="text-align: right;" type="text" class="form-control" id="nretur'+xx+'" name="nretur'+xx+'" class="form-control" value="" onkeyup="ngetang();"></td>';
        cols += '<td><input style="text-align: right;" type="text" class="form-control" id="nreceive'+xx+'" name="nreceive'+xx+'" class="form-control" value=""><input readonly type="hidden" class="form-control" id="nasal'+xx+'" name="nasal'+xx+'" value=""><input readonly type="hidden" class="form-control" id="vtotal'+xx+'" name="vtotal'+xx+'" value=""></td>';
        cols += '<td style="text-align: center;"><input type="checkbox" name="chk'+xx+'" id="chk'+xx+'" checked onclick="ngetang()"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode/Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getproduct/'); ?>',
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jml').val(xx);
    });

    function del() {
        obj=$('#tabledata tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getdetailproduct(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode Barang : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            var iproduct = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#vproductmill'+id).val(data[0].harga);
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#nretur'+id).focus();
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

    function ngetang(){
        var jml = parseFloat($('#jml').val());
        var tot = 0;
        for(brs=1;brs<=jml;brs++){    
            ord = $("#nretur"+brs).val();
            hrg  = formatulang($("#vproductmill"+brs).val());
            qty  = formatulang(ord);
            vhrg = parseFloat(hrg)*parseFloat(qty);
            $("#vtotal"+brs).val(formatcemua(vhrg));
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
            if($("#chk"+brs).is(':checked')){
                tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
            }
        }
        $("#vsjpbr").val(formatcemua(tot));
    }

    function dipales(a){ 
        cek = 'false';
        if ((document.getElementById("iarea").value != '')) {
            if (a == 0) {
                alert('Isi data item minimal 1 !!!');
            }else{
                for (i = 1; i <= a; i++) {
                    if ((document.getElementById("iproduct" + i).value == '') ||
                        (document.getElementById("eproductname" + i).value == '') ||
                        (document.getElementById("nretur" + i).value == '')) {
                        alert('Data item masih ada yang salah !!!');
                        exit();
                        cek = 'false';
                    }else{
                        cek = 'true';
                    }
                }
            }
            if (cek == 'true') {
                document.getElementById("login").disabled = true;
                document.getElementById("cmdtambahitem").disabled = true;
            }else{
                document.getElementById("login").disabled = false;
            }
        } else {
            alert('Data header masih ada yang salah !!!');
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#addex").attr("disabled", true);
    });
</script>