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
                        <label class="col-md-4">Tanggal SJPB</label><label class="col-md-8">Pelanggan</label>
                        <div class="col-sm-4">
                            <input id="dsj" name="dsj" readonly="" class="form-control date" value="<?= date('d-m-Y');?>">
                            <input id="d_now" name="d_now" type="hidden" value="<?= date('Y-m-d');?>">
                            <input id="isj" name="isj" type="hidden">
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" readonly id="ecustomername" name="ecustomername" value="<?= $ecustomername; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" value="<?= $icustomer; ?>">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">SPG</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" value="<?= $eareaname; ?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $iarea; ?>">
                        </div>
                        <div class="col-sm-6">
                            <input class="form-control" readonly id="espgname" name="espgname" value="<?= $espgname; ?>">
                            <input id="ispg" name="ispg" type="hidden" value="<?= $ispg; ?>">
                            <input id="vsj" name="vsj" type="hidden" value="0">
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
                                <th style="text-align: center; width: 20%;">Kode</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center;width: 10%;">Qty Retur</th>
                                <th style="text-align: center;width: 10%;">Qty Terima</th>
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
    function cekarea(kode) {
        var istore = $('#istore option:selected').text();
        var istorelocation = istore.substr(-2);
        if (kode=='AA') {
            var area = '00';
        }else{
            var area = kode;
        }
        $("#iarea").val(area);
        $("#istorelocation").val(istorelocation);
        if (kode!='') {
            $('#istockopname').attr('disabled', false);
            $('#addrow').attr('hidden', false);
        }else{
            $('#istockopname').attr('disabled', true);
            $('#addrow').attr('hidden', true);
        }
        $('#istockopname').val('');
        $('#istockopname').html('');
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        xx = 0;
    }

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
        cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";></select></td>';
        cols += '<td><input readonly id="eproductname'+xx+ '" class="form-control" name="eproductname'+xx+'"><input readonly id="emotifname'+xx+ '" type="hidden" name="emotifname'+xx+'"></td>';
        cols += '<td><input id="eremark'+xx+ '" class="form-control" name="eremark'+xx+'"></td>';
        cols += '<td><input style="text-align: right;" id="nretur'+xx+ '" class="form-control" name="nretur'+xx+'" autocomplete="off" onkeypress="return hanyaAngka(event);" value="0" onkeyup="ngetang();"><input type="hidden" id="vproductmill'+xx+'" name="vproductmill'+xx+'" value="0"><input type="hidden" id="vtotal'+xx+'" name="vtotal'+xx+'" value="0"></td>';
        cols += '<td><input style="text-align: right;" id="nreceive'+xx+ '" class="form-control" name="nreceive'+xx+'" readonly value="0"></td>';
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
        showCalendar('.date',0);
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

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
        }
        $("#vsj").val(formatcemua(tot));
    }

    function dipales(a){
        if(a==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            for(i=1;i<=a;i++){
                if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nretur"+i).value=='')){
                    swal('Data item masih ada yang salah !!!');
                    return false;
                }else{
                    return true;
                }
            }
        }
    }
</script>