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
                            <input required="" readonly id= "dspmb" name="dspmb" class="form-control date" value="<?= date('d-m-Y');?>">
                        </div>
                        <div class="col-sm-6">
                            <input id= "ispmbold" name="ispmbold" class="form-control">
                            <input id="ispmb" name="ispmb" type="hidden"></td>
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Total</label>
                        <div class="col-sm-12">
                            <input id="vtotal" name="vtotal" class="form-control" readonly="" style="text-align: right;" value="0"></td>
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
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <select required="" id="iarea" name="iarea" class="form-control">
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
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input id="eremark" name="eremark" class="form-control"></td>
                        </div>
                    </div>       
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 20%;">Kode</th>
                                <th style="text-align: center; width: 25%;">Nama Barang</th>
                                <th style="text-align: center; width: 7%;">Motif</th>
                                <th style="text-align: center;">Jml Rata2</th>
                                <th style="text-align: center;">Nilai Rata2</th>
                                <th style="text-align: center; width: 7%;">Qty Pesan</th>
                                <th style="text-align: center; width: 7%;">Qty Acc</th>
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
        cols += '<td><select id="iproduct'+xx+'" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";></select></td>';
        cols += '<td><input readonly id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'"></td>';
        cols += '<td><input readonly id="emotifname'+xx+'" class="form-control" name="emotifname'+xx+'"><input type="hidden" id="vproductmill'+xx+'" value="0" class="form-control" name="vproductmill'+xx+'"></td>';
        cols += '<td><input style="text-align: right;" id="jmlrata'+xx+'" class="form-control" name="jmlrata'+xx+'" readonly value="0"></td>';
        cols += '<td><input style="text-align: right;" id="nilairata'+xx+'" class="form-control" name="nilairata'+xx+'" readonly value="0"></td>';
        cols += '<td><input style="text-align: right;" id="norder'+xx+'" class="form-control" name="norder'+xx+'" onkeypress="return hanyaAngka(event);" onkeyup="hitungnilai('+xx+');" disabled></td>';
        cols += '<td><input style="text-align: right;" id="nacc'+xx+'" class="form-control" name="nacc'+xx+'" readonly value="0"><input type="hidden" id="vtotal'+xx+'" class="form-control" name="vtotal'+xx+'" value="0"></td>';
        cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+'"></td>';
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

    /*$("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jml').val(xx);
    });*/

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        /*$(this).closest('tr').find('input').val(0);
        $(this).closest('tr').find('input').attr("disabled", true);
        $(this).closest('tr').find('select').attr("disabled", true);
        $(this).closest("tr").hide();       
        $(this).closest("tr input").attr('disabled', true);  */     
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
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Pilih Gudang',
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
                    'iproduct'  : iproduct
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#vproductmill'+id).val(data[0].harga);
                    $('#jmlrata'+id).val(data[0].nrata);
                    $('#nilairata'+id).val(data[0].vrata);
                    $('#norder'+id).attr('disabled', false);
                    /*hitungnilai(id);*/
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function hitungnilai(brs){
        ord=document.getElementById("norder"+brs).value;
        hrg=formatulang(document.getElementById("vproductmill"+brs).value);
        qty=formatulang(ord);
        vhrg=parseFloat(hrg)*parseFloat(qty);
        document.getElementById("vtotal"+brs).value=formatcemua(vhrg);
        jml=document.getElementById("jml").value;
        tot=0;
        for(i=1;i<=jml;i++){
            tot=tot+parseFloat(formatulang(document.getElementById("vtotal"+i).value));
        }
        document.getElementById("vtotal").value=formatcemua(tot);
    }

    function dipales(a){
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