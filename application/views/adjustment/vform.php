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
                        <label class="col-md-6">Gudang</label><label class="col-md-6">Stockopname</label>
                        <div class="col-sm-6">
                            <select id="istore" name="istore" class="form-control select2" onchange="cekarea(this.value);">
                                <option></option>
                                <?php if ($area) {
                                    foreach ($area as $key) { ?>
                                        <option value="<?= $key->i_store;?>"><?= $key->i_store." - ".$key->e_store_name." - ".$key->i_store_location;?></option>
                                    <?php }
                                } ?>
                            </select>
                            <input type="hidden" name="iarea" id="iarea">
                            <input type="hidden" name="istorelocation" id="istorelocation">
                        </div>
                        <div class="col-sm-6">
                            <select id="istockopname" name="istockopname" class="form-control select2" disabled="true"></select>
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal Adjustment</label><label class="col-md-8">Keterangan</label>
                        <div class="col-sm-4">
                            <input required="" readonly id= "dadj" name="dadj" class="form-control date" value="<?= date('d-m-Y');?>">
                            <input id="iadj" name="iadj" type="hidden">
                        </div>
                        <div class="col-sm-8">
                            <input id= "eremark" name="eremark" class="form-control">
                        </div>
                    </div>               
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button hidden="true" type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;&nbsp;                                
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
                                <th style="text-align: center;">Motif</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center; width: 20%;">Keterangan</th>
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
        cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="grade'+xx+'" name="grade'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";></select></td>';
        cols += '<td><input readonly id="eproductname'+xx+ '" class="form-control" name="eproductname'+xx+'"></td>';
        cols += '<td><input readonly id="emotifname'+xx+ '" class="form-control" name="emotifname'+xx+'"></td>';
        cols += '<td><input style="text-align: right;" id="nquantity'+xx+ '" class="form-control" name="nquantity'+xx+'" autocomplete="off" onkeypress="return hanyaAngka(event);" value="0"></td>';
        cols += '<td><input id="eremark'+xx+ '" class="form-control" name="eremark'+xx+'"></td>';
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
                        q       : params.term,
                        istore      : $('#istore').val(),
                        istorelocation  : $('#istorelocation').val()
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

        $('#istore').select2({
            placeholder: 'Pilih Gudang'
        });

        $('#istockopname').select2({
            placeholder: 'Cari Stockopname',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getso/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea = $('#iarea').val();
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
                    'istore'       : $('#istore').val(),
                    'istorelocation'  : $('#istorelocation').val()
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#motif'+id).val(data[0].motif);
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#grade'+id).val(data[0].grade);
                    $('#nquantity'+id).focus();
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

    function dipales(a){
        if( (document.getElementById("dadj").value!='')||(document.getElementById("iarea").value!='')||(document.getElementById("eremark").value!='')||(document.getElementById("istockopname").value!='') ) {
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
</script>