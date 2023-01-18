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
                        <label class="col-md-5">Kode Area - Area</label><label class="col-md-7">Customer</label>
                        <div class="col-sm-5">
                            <select id="iarea" name="iarea" class="form-control select2" onchange="cekarea(this.value);">
                                <option></option>
                                <?php if ($area) {
                                    foreach ($area as $key) { ?>
                                        <option value="<?= $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name." - ".$key->i_store;?></option>
                                    <?php }
                                } ?>
                            </select>
                            <input type="hidden" name="istore" id="istore">
                            <input type="hidden" name="istorelocation" id="istorelocation" value="00">
                            <input type="hidden" name="istorelocationbin" id="istorelocationbin" value="00">
                        </div>
                        <div class="col-sm-7">
                            <select id="icustomer" name="icustomer" class="form-control select2" disabled="true" onchange="getspg();"></select>
                            <input type="hidden" name="ispg" id="ispg">
                        </div>
                    </div>    
                    <div class="form-group row">
                        <label class="col-md-5">Tanggal SJ</label><label class="col-md-7">Nilai</label>
                        <div class="col-sm-5">
                            <input required="" readonly id= "dsj" name="dsj" class="form-control date" value="<?= date('d-m-Y');?>">
                            <input id="isj" name="isj" type="hidden">
                        </div>
                        <div class="col-sm-7">
                            <input required="" readonly id= "nilai" name="nilai" class="form-control" value="0">
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
                                <th style="text-align: center; width: 40%;">Nama Barang</th>
                                <th style="text-align: center;">Motif</th>
                                <th style="text-align: center;">Grade</th>
                                <th style="text-align: center;">Qty</th>
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
        var iarea  = $('#iarea option:selected').text();
        var istore = iarea.substr(-2);
        $("#istore").val(istore);
        if (kode!='') {
            $('#icustomer').attr('disabled', false);
        }else{
            $('#icustomer').attr('disabled', true);
        }
        $('#icustomer').val('');
        $('#icustomer').html('');
    }

    function getspg() {
        var ispg  = $('#icustomer option:selected').text();
        var spg   = ispg.substr(-4);
        $('#ispg').val(spg);
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
        cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="getdetailproduct('+xx+')";><input type="hidden" id="productprice'+xx+'" name="productprice'+xx+'" value=""></select></td>';
        cols += '<td><input readonly id="eproductname'+xx+ '" class="form-control" name="eproductname'+xx+'"></td>';
        cols += '<td><input readonly id="emotifname'+xx+ '" class="form-control" name="emotifname'+xx+'"></td>';
        cols += '<td><input id="iproductgrade'+xx+ '" class="form-control" name="iproductgrade'+xx+'"></td>';
        cols += '<td><input style="text-align: right;" id="ndeliver'+xx+ '" class="form-control" name="ndeliver'+xx+'" autocomplete="off" onkeypress="return hanyaAngka(event);" onkeyup="hitungnilai(this.value,'+xx+')"></td>';
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
                        istore   : $('#istore').val(),
                        icustomer  : $('#icustomer').val()
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

    function hitungnilai(){
        jml=document.getElementById("jml").value;
        gros=0;
        if (jml>0){
            for(i=1;i<=jml;i++){
                hrg=formatulang(document.getElementById("productprice"+i).value);
                qty=formatulang(document.getElementById("ndeliver"+i).value);
                nilai= parseFloat(hrg)*parseFloat(qty);
                gros=gros+nilai;
            }
            document.getElementById("nilai").value=formatcemua(gros);
        }
    }

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
            placeholder: 'Pilih Area'
        });

        $('#icustomer').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getcustomer/'); ?>',
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
                    'istore'    : $('#istore').val(),
                    'icustomer' : $('#icustomer').val()
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#motif'+id).val(data[0].i_product_motif);
                    $('#emotifname'+id).val(data[0].e_product_motifname);
                    $('#productprice'+id).val(formatcemua(data[0].v_product_retail));
                    $('#iproductgrade'+id).val(data[0].i_product_grade);
                    $('#ndeliver'+id).val(0);
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
        if((document.getElementById("dsj").value!='') && (document.getElementById("iarea").value!='') && (document.getElementById("isjp").value!='') && (document.getElementById("icustomer").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("ndeliver"+i).value=='')){
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