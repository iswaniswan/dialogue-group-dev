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
                        <label class="col-md-6">Gudang</label>
                        <label class="col-md-6">Tanggal Memo</label>
                        <div class="col-sm-6">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2">
                                <option value="">-- Pilih Gudang --</option>
                                <?php if ($gudang) {                 
                                    foreach ($gudang as $igudang) { ?>
                                        <option value="<?php echo $igudang->i_kode_master;?>"><?= $igudang->e_nama_master;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "dspb" name="dspb" class="form-control date" readonly value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">Alamat</label>
                        <div class="col-sm-6">
                        <select name="customer" id="customer" class="form-control select2"onchange="getdetailpel(this.value);">
                                <option value="">-- Pilih pelanggan --</option>
                                <?php if ($customer) {                                   
                                    foreach ($customer as $icustomer) { ?>
                                        <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->e_customer_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input readonly type="text" id="ecumstomeraddress" name="ecumstomeraddress" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                                &nbsp;&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" disabled=""><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6"> 
                    <div class="form-group row">
                        <label class="col-md-12">No Memo</label>
                        <div class="col-sm-12">
                            <input type="text" id="imemo" name="imemo" class="form-control">
                        </div>
                    </div>
                        <div class="form-group row">
                            <label class="col-md-12">Keterangan</label>
                            <div class="col-sm-12">
                                <input id="eremarkx" name="eremarkx" maxlength="100" class="form-control">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value="0">
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th style="text-align: center; width: 5%;">No</th>
                                    <th style="text-align: center; width: 10%;">Kode Barang</th>
                                    <th style="text-align: center; width: 25%;">Nama Barang</th>
                                    <th style="text-align: center; width: 10%;">Quantity</th>
                                    <th style="text-align: width : 20% center;">Keterangan</th>
                                    <th style="text-align: center;">Action</th>
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
    var xx = 0;
    $("#addrow").on("click", function () {
        $("#tabledata").attr("hidden", false);
        xx++;
        /*document.getElementById("jml").value = xx;*/
        var kodemaster  = $('#ikodemaster').val();
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="iproductstatus'+xx+'" name="iproductstatus'+xx+'" value=""></td>';
        cols += '<td><input type="text" id="iproduct'+xx+'" type="text" class="form-control" name="iproduct'+xx+'" readonly></td>';
        cols += '<td><select  type="text" id="eproductname'+xx+ '" class="form-control" name="eproductname'+xx+'" onchange="getharga('+xx+');"></td>';
        cols += '<td><input style : 100px type="text" id="norder'+xx+'" class="form-control" name="norder'+xx+'" onkeypress="return hanyaAngka(event)"  onkeyup="hitungnilai(this.value,'+xx+')" autocomplete="off"><input type="hidden" id="nquantitystock'+xx+'" name="nquantitystock'+xx+'" value=""></td>';
        cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#eproductname'+xx).select2({
            
            placeholder: 'Cari Kode / Nama',
            templateSelection: formatSelection,
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databrg/'); ?>'+"/"+kodemaster,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var kdharga     = $('#ipricegroup').val();
                    var groupbarang = $('#productgroup').val();
                    var istore      = $('#istore').val();
                    var fstock      = $('#fstock').val();
                    var query   = {
                        q       : params.term,
                        kdharga : kdharga,
                        group   : groupbarang,
                        istore  : istore,
                        fstock  : fstock
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
        function formatSelection(val) {
            return val.name;
        }
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        document.getElementById("jml").value = xx;
    });

    $(document).ready(function () {
        var iarea = $('#iarea').val();
        $('.select2').select2();
        showCalendar('.date', 0, 5);

        $('#icustomer').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getpelanggan/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var i_area = $('#iarea').val();
                    var query = {
                        q: params.term,
                        i_area: i_area
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

    function getdetailpel(icustomer){
        var dspb  = $('#dspb').val();
        $('#addrow').attr("disabled", false);
        // var iarea = $('#iarea').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer': icustomer,
            },
            url: '<?= base_url($folder.'/cform/getdetailpel'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ecumstomeraddress').val(data[0].e_customer_address);
                $('#ndiscc').val(data[0].v_customer_discount);   
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function getdiscount(id){
        var ibranch = $('#ibranch').val();
        $.ajax({
        type: "post",
        data: {
            'i_branch': ibranch
        },
        url: '<?= base_url($folder.'/cform/getdiscount'); ?>',
        dataType: "json",
        success: function (data) {
            $('#ncustomerdiscount1'+id).val(data[0].n_customer_discount1);
            $('#ncustomerdiscount2'+id).val(data[0].n_customer_discount2);
            $('#ncustomerdiscount3'+id).val(data[0].n_customer_discount3);

        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function getharga(id){
        var eproductname = $('#eproductname'+id).val();
        $.ajax({
        type: "post",
        data: {
            'eproductname': eproductname
        },
        url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_material);
            $('#vprice'+id).val(data[0].v_price);

            ada=false;
            var a = $('#iproduct'+id).val();
            var c = $('#icolor'+id).val();
            var e = $('#eproductname'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
	            if((a == $('#iproduct'+i).val()) && (c == $('#icolor'+i).val()) && (i!=jml)){
	            	swal ("kode : "+a+" sudah ada y !!!!!");
	            	ada=true;
	            	break;
	            }else{
	            	ada=false;	   
	            }
            }
            if(!ada){
                $('#iproduct'+id).val(data[0].i_material);
                $('#vprice'+id).val(data[0].v_price);
            }else{
                $('#iproduct'+id).html('');
                $('#iproduct'+id).val('');
                $('#eproductname'+id).html('');
                $('#eproductname'+id).val('');
                $('#vproductretail'+id).val('');
                $('#ecolorname'+id).val('');
                $('#icolor'+id).val('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function hitungnilai(isi,jml){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{;
            var dtmp1 = $('#ndiscc').val();
            vdis1=0;
            vtot =0;
            
            for(i=1;i<=jml;i++){
                
                vhrg=formatulang(document.getElementById("vprice"+i).value);               
                if (isNaN(parseFloat(document.getElementById("norder"+i).value))){
                    nqty=0;
                    
                }else{
                            nqty=formatulang(document.getElementById("norder"+i).value);
                            vhrg=parseFloat(vhrg)*parseFloat(nqty);
                            vtot=vtot+vhrg;
                            document.getElementById("vtotal"+i).value=formatcemua(vhrg);
                }
                
            }            
            vdis1=vdis1+((vtot*dtmp1)/100);
            vdis1=parseFloat(vdis1);
            vtotdis=vdis1+vdis2+vdis3;
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });
</script>