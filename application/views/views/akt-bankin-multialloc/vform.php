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
                        <label class="col-md-4">Tanggal Alokasi</label><label class="col-md-4">No. Bank</label><label class="col-md-4">Tanggal Bank</label>
                        <div class="col-sm-4">
                            <input required="" placeholder="Pilih Tanggal" readonly id= "dalokasi" name="dalokasi" class="form-control" value="<?= $isi->d_bank;?>">
                        </div>
                        <div class="col-sm-4">
                            <input readonly id= "ikbank" name="ikbank" class="form-control" value="<?= $isi->i_kbank;?>">
                        </div>
                        <div class="col-sm-4">
                            <input required="" placeholder="Pilih Tanggal" readonly id="dbank" name="dbank" class="form-control" value="<?= $isi->d_bank;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Ku/Giro/Tunai</label><label class="col-md-6">Bank</label>
                        <div class="col-sm-6">
                            <input readonly id="igiro" name="igiro" value="<?= $isi->i_giro; ?>" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id="ebankname" name="ebankname" value="<?= $isi->e_bank_name; ?>" class="form-control">
                            <input type="hidden" id="ebanknameicoabank" name="icoabank" value="<?= $isi->i_coa_bank; ?>" class="form-control">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-12">
                            <input readonly id= "vjumlah" name="vjumlah" class="form-control" value="<?= number_format($isi->v_sisa);?>">
                            <input type="hidden" id="vlebih" name="vlebih" value="0">
                            <input type="hidden" id="vsisa" name="vsisa" value="<?= $isi->v_sisa; ?>">
                        </div>
                    </div>              
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button hidden="true" type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button hidden="true" type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Detail</button>&nbsp;&nbsp;                                
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom;?>/<?= $dto;?>/<?= $iarea;?>/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                             
                        </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group row">
                        <label class="col-md-6">No. DT</label><label class="col-md-6">Debitur</label>
                        <div class="col-sm-6">
                            <select name="idt" id="idt" required="" class="form-control select2" onchange="cekdt(this.value);"></select>
                            <input type="hidden" id="iareadt" name="iareadt">
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control" id="icustomer" name="icustomer" disabled="true" onchange="getdetailcustomer(this.value);"></select>
                            <input type="hidden" id= "ecustomername" name="ecustomername" class="form-control" value="">
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">Kota</label>
                        <div class="col-sm-6">
                            <input type="hidden" id= "iarea" name="iarea" class="form-control" value="<?= $isi->i_area;?>">
                            <input readonly id= "eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name;?>">
                        </div>
                        <div class="col-sm-6">
                            <input readonly id= "ecustomercity" name="ecustomercity" class="form-control" value="">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-12">Alamat</label>
                        <div class="col-sm-12">
                            <input readonly id= "ecustomeraddress" name="ecustomeraddress" class="form-control" value="">
                        </div>
                    </div>  
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="col-md-12">
                    <table hidden="true" id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 15%;">Nota</th>
                                <th style="text-align: center; width: 10%;">Tanggal Nota</th>
                                <th style="text-align: center; width: 11%;">Nilai</th>
                                <th style="text-align: center; width: 11%;">Bayar</th>
                                <th style="text-align: center; width: 11%;">Sisa</th>
                                <th style="text-align: center; width: 11%;">Lebih</th>
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
    function cekdt(idt) {
        if (idt != '') {
            $("#icustomer").attr("disabled", false);
        }else{
            $("#icustomer").attr("disabled", true);
        }
        var iareadt = $('#idt option:selected').text();
        var areadt  = iareadt.substr(-2);
        $('#iareadt').val(areadt);        
    }

    var xx = $('#jml').val();
    var uu = xx-1;
    $("#addrow").on("click", function () {
        xx++;
        uu++;
        $("#tabledata").attr("hidden", false);
        var inota = $('#inota'+uu).val()
        count=$('#tabledata tr:visible').length;
        if ((inota==''||inota==null)&&(count>1)) {
            swal('Isi dulu nota sebelumnya!!');
            return false;
        }
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
        cols += '<td><select id="inota'+xx+'" class="form-control" name="inota'+xx+'" onchange="getdetailnota('+xx+');"></select></td>';
        cols += '<td><input id="dnota'+xx+'" readonly class="form-control" name="dnota'+xx+'" ></td>';
        cols += '<td><input style="text-align: right;" id="vnota'+xx+'" class="form-control" name="vnota'+xx+'" readonly value="0"></td>';
        cols += '<td><input style="text-align: right;" value="0" id="vjumlah'+xx+'" class="form-control" name="vjumlah'+xx+'" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this); hetang('+xx+');" onpaste="return false;" maxlength="17"></td>';
        cols += '<td><input style="text-align: right;" id="vsesa'+xx+'" class="form-control" name="vsesa'+xx+'" readonly value="0"><input type="hidden" id="vsisa'+xx+'" name="vsisa'+xx+'" value="0"></td>';
        cols += '<td><input style="text-align: right;" id="vlebih'+xx+'" class="form-control" name="vlebih'+xx+'" value="0" readonly></td>';
        cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+'"></td>';
        cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#inota'+xx).select2({
            placeholder: 'Cari Nota',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getnota/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var icustomer = $('#icustomer').val();
                    var idt       = $('#idt').val();
                    var query   = {
                        q       : params.term,
                        icustomer   : icustomer,
                        idt     : idt
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

    function getdetailnota(id){
        ada=false;
        var a = $('#inota'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#inota'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var inota = $('#inota'+id).val();
            var icustomer = $('#icustomer').val();
            var idt = $('#idt').val();
            $.ajax({
                type: "post",
                data: {
                    'idt'  : idt,
                    'inota'  : inota,
                    'icustomer'  : icustomer
                },
                url: '<?= base_url($folder.'/cform/getdetailnota'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#dnota'+id).val(data[0].d_nota);
                    $('#vsesa'+id).val(formatcemua(data[0].v_sisa));
                    $('#vsisa'+id).val(formatcemua(data[0].v_sisa));
                    $('#vnota'+id).val(formatcemua(data[0].v_sisa));
                    var tmp = formatulang($('#vjumlah').val());
                    var jml = $('#jml').val();
                    if (tmp>0) {
                        tmp     = parseFloat(tmp);
                        sisa    = 0;
                        jumasal = tmp;
                        jumall  = jumasal;
                        bay     = 0;
                        for(x=1;x<=jml;x++){
                            if($('#vjumlah'+x).val()==''){
                                jum     = parseFloat(formatulang($('#vsisa'+x).val()));
                            }else{
                                jum     = parseFloat(formatulang($('#vjumlah'+x).val()));
                            }
                            jumall = jumall-jum;
                            if(jumall>0){
                                $('#vlebih').val(formatcemua(jumall));
                                if(x==id){
                                    $('#vjumlah'+id).val(formatcemua(data[0].v_sisa));
                                    by  = parseFloat(formatulang($('#vjumlah'+id).val()));
                                    bay = jumasal-by;
                                    sis = parseFloat(formatulang($('#vsisa'+id).val()));
                                    $('#vlebih'+id).val(formatcemua(bay));
                                }
                                sisa=sisa+jum;
                            }else{
                                $('#vlebih').val('0');
                                $('#vlebih'+id).val('0');
                                $('#vjumlah'+id).val(formatcemua(jumasal-sisa));
                            }
                        }
                    }
                    hetang(id);
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#inota'+id).html('');
            $('#inota'+id).val('');
        }
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        /*$(this).closest("tr").remove(); */
        /*$(this).closest("tr").find("td:eq(0)").text();*/
        $(this).closest('tr').find('input').val(0);
        $(this).closest('tr').find('input').attr("disabled", true);
        $(this).closest('tr').find('select').attr("disabled", true);
        $(this).closest("tr").hide();       
        /*alert('xx');*/
        $(this).closest("tr input").attr('disabled', true);       
        /*$(this).closest("tr").find('td').val(0);*/    
        /*xx -= 1;*/
        $('#jml').val(xx);
        del();
        hetang(xx);
    });

    function del() {
        obj=$('#tabledata tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function hetang(x) {
        num = document.getElementById("vjumlah"+x).value.replace(/\,/g, '');
        if (!isNaN(num)) {
            vjmlbyr = parseFloat(formatulang(document.getElementById("vjumlah").value));
            vlebihitem = vjmlbyr;
            vsisadt = parseFloat(formatulang(document.getElementById("vsisa").value));
            jml = document.getElementById("jml").value;
            for (a = 1; a <= jml; a++) {
                vnota = parseFloat(formatulang(document.getElementById("vsisa"+a).value));
                vjmlitem = parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
                if (vjmlitem == 0) {
                    /*bbotol();*/
                }
                vsisaitem = vnota - vjmlitem;
                if (vsisaitem < 0) {
                    swal("jumlah bayar tidak bisa lebih besar dari nilai nota !!!!!");
                    document.getElementById("vjumlah"+a).value = 0;
                    vjmlitem = parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
                    vsisaitem = parseFloat(formatulang(document.getElementById("vsisa"+a).value));
                }
                vlebihitem = vlebihitem - vjmlitem;
                if (vlebihitem < 0) {
                    vlebihitem = vlebihitem + vjmlitem;
                    vsisaitem = vnota - vlebihitem;
                    swal("jumlah item tidak bisa lebih besar dari nilai bayar !!!!!");
                    document.getElementById("vjumlah"+a).value = formatcemua(vlebihitem);
                    vjmlitem = parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
                    vlebihitem = 0;
                }
                document.getElementById("vsesa"+a).value = formatcemua(vsisaitem);
                document.getElementById("vlebih"+a).value = formatcemua(vlebihitem);
            }
            document.getElementById("vlebih").value = formatcemua(vlebihitem);
        } else {
            /*swal('input harus numerik !!!');*/
            document.getElementById("vjumlah"+x).value = 0;
        }
    }

    $(document).ready(function () {
        showCalendar('.date'); 
        $('#icustomer').select2({
            placeholder: 'Cari Customer Berdasarkan Kodelang/Nama',
            ajax: {
                url: '<?= base_url($folder.'/cform/getcustomer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var idt = $('#idt').val();
                    var iarea = $('#iareadt').val();
                    var query   = {
                        q       : params.term,
                        idt     : idt,
                        iarea   : iarea
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

        $('#idt').select2({
            placeholder: 'Cari DT',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getdt/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var dbank = $('#dbank').val();
                    var query   = {
                        q       : params.term,
                        dbank   : dbank
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

    function getdetailcustomer(id){
        if (id != '') {
            $("#addrow").attr("hidden", false);
            $("#submit").attr("hidden", false);
        }else{
            $("#addrow").attr("hidden", true);
            $("#submit").attr("hidden", true);
        }
        $("#tabledata").attr("hidden", true);
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        xx = 0;
        var iarea = $('#iareadt').val();
        var icustomer = $('#icustomer').val();
        var idt = $('#idt').val();
        $.ajax({
            type: "post",
            data: {
                'idt'  : idt,
                'iarea'  : iarea,
                'icustomer'  : icustomer
            },
            url: '<?= base_url($folder.'/cform/getdetailcustomer'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ecustomername').val(data[0].e_customer_name);
                $('#ecustomeraddress').val(formatcemua(data[0].e_customer_address));
                $('#ecustomercity').val(formatcemua(data[0].e_customer_city));
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function dipales() {
        cek = 'false';
        cok = 'false';
        if ((document.getElementById("ikn").value != '') &&
            (document.getElementById("dkn").value != '') &&
            (document.getElementById("dalokasi").value != '') &&
            (document.getElementById("vjumlah").value != '') &&
            (document.getElementById("vjumlah").value != '0') &&
            (document.getElementById("icustomer").value != '')) {
            var a = parseFloat(document.getElementById("jml").value);
        for (i = 1; i <= a; i++) {
            if (document.getElementById("vjumlah" + i).value != '0') {
                sisa = parseFloat(formatulang(document.getElementById("vsisa" + i).value));
                awal = parseFloat(formatulang(document.getElementById("vjumlah" + i).value));
                cok = 'true';
                cek = 'true';
            } else {
                cek = 'false';
            }
        }
        if (cek == 'true') {
            return true;
        } else if (cok == 'false') {} else {
            swal('Isi jumlah detail pelunasan minimal 1 item !!!');
            return false;
        }
    } else {
        swal('Data header masih ada yang salah !!!');
        return false;
    }
}
</script>