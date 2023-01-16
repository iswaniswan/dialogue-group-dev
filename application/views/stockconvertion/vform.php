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
                        <label class="control-label col-md-2">Tanggal</label>
                        <div class="col-sm-3">
                            <input readonly id= "dicconvertion" name="dicconvertion" class="form-control date" value="<?= date('d-m-Y');?>">
                            <input id="iicconvertion" name="iicconvertion" type="hidden">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml1').value),parseFloat(document.getElementById('jml2').value)"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" id="addrow1" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Product Asal
                            </button>&nbsp;&nbsp;                                
                            <button type="button" id="addrow2" class="btn btn-inverse btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Product Jadi
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml1" id="jml1" value="0">
                <input type="hidden" name="jml2" id="jml2" value="0">
                <div class="col-md-12">
                    <table id="tabeldata1" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 25%;">Kode Barang</th>
                                <th style="text-align: center; width: 10%;">Grade</th>
                                <th style="text-align: center;">Nama Barang</th>
                                <th style="text-align: center; width: 10%;">Qty</th>
                                <!-- <th style="text-align: center; width: 5%;">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <table id="tabeldata2" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 25%;">Kode Barang</th>
                                <th style="text-align: center; width: 10%;">Grade</th>
                                <th style="text-align: center;">Nama Barang</th>
                                <th style="text-align: center; width: 10%;">Qty</th>
                                <!-- <th style="text-align: center; width: 5%;">Action</th> -->
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
    var xx  = parseFloat($('#jml1').val());
    var xxx = parseFloat($('#jml2').val());
    var uu  = xx-1;
    $("#addrow1").on("click", function () {
        if (((parseFloat($('#jml2').val())<=1) && (parseFloat($('#jml1').val())<1))||
            ((parseFloat($('#jml2').val())>=1) && (parseFloat($('#jml1').val())<1))||
            ((parseFloat($('#jml2').val())<=1) && (parseFloat($('#jml1').val())>=1))) {
            xx++;
        uu++;
        $("#tabeldata1").attr("hidden", false);
        var iproduct = $('#iproduct'+uu).val();
        count=$('#tabeldata1 tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            xx = xx-1;
            uu = uu-1;
            return false;
        }
        $('#jml1').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="iproductmotif'+xx+'" name="iproductmotif'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+'" class="form-control" name="iproduct'+xx+'" onchange="getdetail1('+xx+');"></td>';
        cols += '<td><input id="iproductgrade'+xx+'" class="form-control" name="iproductgrade'+xx+'" readonly><input type="hidden" id="vproductretail'+xx+'" name="vproductretail'+xx+'" value=""></td>';
        cols += '<td><input id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'" readonly></td>';
        cols += '<td><input id="nicconvertion'+xx+'" class="form-control" name="nicconvertion'+xx+'" onkeypress="return hanyaAngka(event);" value="0" style="text-align: right;"/></td>';
        /*cols += '<td style="text-align: center;"><button type="button" id="addrow1" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
        newRow.append(cols);
        $("#tabeldata1").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Product',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
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
    }
});

    $("#tabeldata1").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        $('#jml1').val(xx);
        del();
    });

    function del() {
        obj=$('#tabeldata1 tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getdetail1(id){
        ada=false;
        var iproductgrade = $('#iproduct'+id+' option:selected').text();
        var grade = iproductgrade.substr(-1);
        var a = $('#iproduct'+id).val();
        var x = $('#jml1').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iproduct'+i).val()) && (grade == $('#iproductgrade'+i).val()) && (i!=x)){
                swal ("Kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            for(i=1;i<=x;i++){            
                if((a == $('#2iproduct'+i).val()) && (grade == $('#2iproductgrade'+i).val())){
                    swal ("Kode : "+a+" sudah ada !!!!!");            
                    ada=true;            
                    break;        
                }else{            
                    ada=false;             
                }
            }
        }

        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'iproduct' : a,
                    'grade'    : grade
                },
                url: '<?= base_url($folder.'/cform/getdetail'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#iproductgrade'+id).val(data[0].i_product_grade);
                    $('#vproductretail'+id).val(data[0].v_product_retail);
                    $('#iproductmotif'+id).val(data[0].i_product_motif);
                    $('#nicconvertion'+id).focus();
                },
                error: function () {
                    swal('Data ada yang salah :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
        }
    }

    var zzz = parseFloat($('#jml1').val());
    var zz  = parseFloat($('#jml2').val());
    var ww  = zz-1;
    $("#addrow2").on("click", function () {
        if (((parseFloat($('#jml2').val())<1) && (parseFloat($('#jml1').val())<=1))||
            ((parseFloat($('#jml2').val())>=1) && (parseFloat($('#jml1').val())<=1))||
            ((parseFloat($('#jml2').val())<1) && (parseFloat($('#jml1').val())>=1))) {
            zz++;
        ww++;
        $("#tabeldata2").attr("hidden", false);
        var iproduct = $('#2iproduct'+ww).val();
        count=$('#tabeldata2 tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            zz = zz-1;
            ww = ww-1;
            return false;
        }
        $('#jml2').val(zz);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;"><spanz id="snum'+zz+'">'+count+'</spanz><input type="hidden" id="2iproductmotif'+zz+'" name="2iproductmotif'+zz+'" value=""></td>';
        cols += '<td><select id="2iproduct'+zz+'" class="form-control" name="2iproduct'+zz+'" onchange="getdetail2('+zz+');"></td>';
        cols += '<td><input id="2iproductgrade'+zz+'" class="form-control" name="2iproductgrade'+zz+'" readonly><input type="hidden" id="2vproductretail'+zz+'" name="2vproductretail'+zz+'" value=""></td>';
        cols += '<td><input id="2eproductname'+zz+'" class="form-control" name="2eproductname'+zz+'" readonly></td>';
        cols += '<td><input id="2nicconvertion'+zz+'" class="form-control" name="2nicconvertion'+zz+'" onkeypress="return hanyaAngka(event);" value="0" style="text-align: right;"/></td>';
        /*cols += '<td style="text-align: center;"><button type="button" id="addrow2" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
        newRow.append(cols);
        $("#tabeldata2").append(newRow);
        $('#2iproduct'+zz).select2({
            placeholder: 'Cari Product',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
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
    }
});

    $("#tabeldata2").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        $('#jml2').val(zz);
        dell();
    });

    function dell() {
        obj=$('#tabeldata2 tr:visible').find('spanz');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function getdetail2(id){
        ada=false;
        var iproductgrade = $('#2iproduct'+id+' option:selected').text();
        var grade = iproductgrade.substr(-1);
        var a = $('#2iproduct'+id).val();
        var x = $('#jml2').val();
        for(i=1;i<=x;i++){            
            if((a == $('#2iproduct'+i).val()) && (grade == $('#2iproductgrade'+i).val()) && (i!=x)){
                swal ("Kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }

        if(!ada){
            for(i=1;i<=x;i++){            
                if((a == $('#iproduct'+i).val()) && (grade == $('#iproductgrade'+i).val())){
                    swal ("Kode : "+a+" sudah ada !!!!!");            
                    ada=true;            
                    break;        
                }else{            
                    ada=false;             
                }
            }
        }

        if(!ada){
            $.ajax({
                type: "post",
                data: {
                    'iproduct' : a,
                    'grade'    : grade
                },
                url: '<?= base_url($folder.'/cform/getdetail'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#2eproductname'+id).val(data[0].e_product_name);
                    $('#2iproductgrade'+id).val(data[0].i_product_grade);
                    $('#2vproductretail'+id).val(data[0].v_product_retail);
                    $('#2iproductmotif'+id).val(data[0].i_product_motif);
                    $('#2nicconvertion'+id).focus();
                },
                error: function () {
                    swal('Data ada yang salah :)');
                }
            });
        }else{
            $('#2iproduct'+id).html('');
            $('#2iproduct'+id).val('');
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow1").attr("disabled", true);
        $("#addrow2").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function dipales(a,b){
        if(($("#dicconvertion").val()!='')) {
            if( (a=='0') || (b=='0')){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if(($("#iproduct"+i).val()=='') || ($("#iproductgrade"+i).val()=='') || ($("#eproductname"+i).val()=='') || ($("#nicconvertion"+i).val()=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
                for(i=1;i<=b;i++){
                    if(($("#2iproduct"+i).val()=='') || ($("#2iproductgrade"+i).val()=='') || ($("#2eproductname"+i).val()=='') || ($("#2nicconvertion"+i).val()=='')){
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