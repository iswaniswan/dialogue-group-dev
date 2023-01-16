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
                        <label class="col-md-4">Tanggal SPMB</label><label class="col-md-8">No. SPMB</label>
                        <div class="col-sm-4">
                            <input required="" readonly id= "dspmb" name="dspmb" class="form-control date" value="<?= date('d-m-Y');?>">
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" id="ispmbold" name="ispmbold">
                            <input id="ispmb" name="ispmb" type="hidden">
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-4">Lokasi Gudang</label>
                        <div class="col-sm-8">
                            <select name="iarea" id="iarea" required="" class="form-control select2">
                                <option value=""></option>
                                <?php if ($store) {                                 
                                    foreach ($store as $key) { ?>
                                        <option value="<?php echo $key->i_store;?>"><?= $key->i_store_location." - ".$key->e_store_name." - ".$key->e_store_locationname;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-4">Total</label><label class="col-md-8">Keterangan</label>
                        <div class="col-sm-4">
                            <input readonly="" value="0" id= "vtotal" name="vtotal" class="form-control">
                        </div>
                        <div class="col-sm-8">
                            <input id= "eremark" name="eremark" class="form-control">
                        </div>
                    </div>               
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;&nbsp;                                
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>                               
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
                                <th style="text-align: center; width: 5%;">Act</th>
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
    var xx = $('#jml').val();
    var uu = xx-1;
    $("#addrow").on("click", function () {
        xx++;
        uu++;
        $("#tabledata").attr("hidden", false);
        var iproduct = $('#iproduct'+uu).val();
        count=$('#tabledata tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            xx = xx-1;
            uu = uu-1;
            return false;
        }
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
        cols += '<td><select class="form-control" id="iproduct'+xx+'" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+');"></select></td>';
        cols += '<td><input class="form-control" readonly id="eproductname'+xx+'" name="eproductname'+xx+'"></td>';
        cols += '<td><input readonly class="form-control" id="emotifname'+xx+'" name="emotifname'+xx+'"><input readonly type="hidden" id="vproductmill'+xx+'" name="vproductmill'+xx+'"></td>';
        cols += '<td><input readonly class="form-control" style="text-align:right;" id="jmlrata'+xx+'" name="jmlrata'+xx+'"></td>';
        cols += '<td><input readonly class="form-control" style="text-align:right;" id="nilairata'+xx+'" name="nilairata'+xx+'"></td>';
        cols += '<td><input class="form-control" style="text-align:right;" id="norder'+xx+'" name="norder'+xx+'" value="0" onkeypress="return hanyaAngka(event);" onkeyup="ngetang('+xx+');"></td>';
        cols += '<td><input class="form-control" readonly style="text-align:right;" id="nacc'+xx+'" name="nacc'+xx+'" value="0"><input type="hidden" id="vtotal'+xx+'" name="vtotal'+xx+'" value="0"></td>';
        cols += '<td><input class="form-control" id="eremark'+xx+'" name="eremark'+xx+'" value=""></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
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
        $('#jml').val(xx);
        del();
    });

    function del() {
        obj=$('#tabledata tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');

        $('#iarea').select2({
            placeholder: 'Pilih Lokasi Gudang'
        });
    });

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
                    'iproduct'  : iproduct,
                    'iarea'     : $('#iarea').val(),
                    'dspmb'     : $('#dspmb').val()
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#vproductmill'+id).val(data[0].harga);
                    $('#jmlrata'+id).val(formatcemua(data[0].vrata));
                    $('#nilairata'+id).val(formatcemua(data[0].nrata));
                    $('#norder'+id).focus();
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

    function ngetang(brs){
        jml = $("#norder"+brs).val();
        brs = $("#jml").val();
        tot = 0;
        for(i=1;i<=brs;i++){
            hrg=formatulang($("#vproductmill"+i).val());
            qty=formatulang($("#norder"+i).val());
            tot=tot+parseFloat(parseFloat(hrg)*parseFloat(qty));
        }
        $("#vtotal").val(formatcemua(tot));
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function dipales(a){
        if(($("#dspmb").val()!='') && ($("#iarea").val()!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if(($("#iproduct"+i).val()=='') || ($("#eproductname"+i).val()=='') || ($("#norder"+i).val()=='')){
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