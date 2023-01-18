<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">Tanggal SPB</label>
                        <label class="col-md-6">Batas Kirim</label>
                        <div class="col-sm-6">
                                <input type="text" id= "dspb" name="dspb" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required="" readonly>
                                <?php foreach ($bagian as $ibagian):?>
                                <input type="hidden" id= "ibagian" name="ibagian" class="form-control date" value="<?php echo $ibagian->i_departement; ?>" readonly>
                                <?php endforeach; ?>
                        </div>
                        <div class="col-sm-6">
                                <input type="text" id= "dbatas" name="dbatas" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">PO Refferensi</label>
                        <label class="col-md-6">Promo</label>
                        <div class="col-sm-6">
                            <input type="text" id= "iporeff" name="iporeff" class="form-control" value="" readonly>
                        </div>
                        <div class="col-sm-6">
                            <select name="ipromo" id="ipromo" class="form-control select2" >
                                <option value="" selected>-- Pilih Promo --</option>
                                <?php foreach ($promo as $ipromo):?>
                                <option value="<?php echo $ipromo->i_promo;?>"><?= $ipromo->i_promo;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                                <input type="text" id= "eremark" name="eremark" class="form-control" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">  
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <!-- <button type="button" hidden id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button> -->
                            <button type="button" hidden id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Proses</button>
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">Alamat</label>
                        <div class="col-sm-6">          
                            <select name="icustomer" id="icustomer" class="form-control select2" onchange="ketabel(this.value);">
                                <option value="" selected>-- Pilih Customer --</option>
                                <?php foreach ($customer as $icustomer):?>
                                <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->e_customer_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">                            
                            <input type="text" id= "ecustomeraddress" name="ecustomeraddress" class="form-control" value="" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4">Nilai Kotor</label>
                        <label class="col-md-4">Persen Disc(%)</label>
                        <label class="col-md-4">Nilai Diskon</label>
                        <div class="col-sm-4">
                            <input type="text" id= "vgross" name="vgross" class="form-control" value="0">
                        </div>
                        <div class="col-sm-4">
                            <!-- <input type="text" id= "vdiskon" name="vdiskon" class="form-control" value="0"> -->
                            <input type="text" id= "ndiskon" name="ndiskon" class="form-control" value="0" onkeyup="hitungnilaidiskon(this.value)">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "vdiskon" name="vdiskon" class="form-control" value="0" onkeyup="hitungpersendiskon(this.value)">
                            <!-- <input type="text" id= "vnetto" name="vnetto" class="form-control" value="0"> -->
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Nilai Bersih</label>
                        <div class="col-sm-4">
                            <input type="text" id= "vnetto" name="vnetto" class="form-control" value="0">
                            <!-- <input type="text" id= "vgross" name="vgross" class="form-control" value="0"> -->
                        </div>
                        <!-- <div class="col-sm-4">
                            <input type="text" id= "vdiskon" name="vdiskon" class="form-control" value="0">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "vnetto" name="vnetto" class="form-control" value="0">
                        </div> -->
                    </div>
                </div>
                 
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="30%">Nama Barang Jadi</th>
                                    <th>Harga</th>
                                    <th>Quantity </th>
                                    <th>Disc</th>
                                    <th>Total</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="jml" id="jml" value="0">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function ketabel(id) {
    var cust =  $("#icustomer").val();

    if (cust == "") {
        $("#addrow").attr("hidden", true);
    } else {
        $("#addrow").attr("hidden", false);
    }

    $.ajax({
        type: "post",
        data: {
            'icustomer': cust
        },
        url: '<?= base_url($folder.'/cform/getcustaddress'); ?>',
        dataType: "json",
        success: function (data) {
            var ecustomeraddress = data['head']['e_customer_address'];
            $('#ecustomeraddress').val(ecustomeraddress);
            var iopreff = data['head']['i_fc'];
            $('#iporeff').val(iopreff);
        },
        error: function () {
            swal('Error :)');
        }
    });
 
}


var counter = 0;

    $("#addrow").on("click", function () {
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        var icustomer = $('#icustomer').val();
        var ipromo = $('#ipromo').val();
        var dspb = $('#dspb').val();
        $.ajax({
            type: "post",
            data: {
                'icustomer':icustomer, 
                'dspb':dspb,
                'ipromo':ipromo,
            },
            url: '<?= base_url($folder.'/cform/get_outstanding'); ?>',
            dataType: "json",
            success: function (data) {
                $('#tabledata').attr('hidden', false);
                $('#jml').val(data['detail'].length);

                var vgross       = data['head']['v_total_gross'];
// var vgrossasal       = data['head']['v_total_gross'];
                var vdiskon       = data['head']['v_total_discount'];
                var vnetto    = data['head']['v_total_netto'];
                $('#vgross').val(vgross);
// $('#vgrossasal').val(vgrossasal);
                $('#vdiskon').val(vdiskon);
                $('#vnetto').val(vnetto);

                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;

                    var iproduct           = data['detail'][a]['i_product'];
                    var eproductbasename   = data['detail'][a]['e_product_basename'];
                    var vunitprice         = data['detail'][a]['v_unitprice'];
                    var nquantityfc        = data['detail'][a]['n_outstanding'];
                    var ndisc              = data['detail'][a]['n_disc'];
                    var vsubtotaldisc      = data['detail'][a]['v_subtotaldisc'];

                    var cols   = "";
                    var newRow = $("<tr>");

                    cols += '<td style="text-align: center;"><spanx id="snum'+zz+'">'+zz+'</spanx><input type="hidden" id="baris'+zz+'" type="text" class="form-control" name="baris'+zz+'" value="'+zz+'"></td>';
                    cols += '<td><input style="width:100px;" type="text" readonly  id="iproduct'+ zz + '" type="text" class="form-control" name="iproduct[]" value="'+iproduct+'"></td>';
                    // cols += '<td><select type="text" id="eproductname'+ zz + '" class="form-control" name="eproductname[]" onchange="getmaterial('+ zz +');" value="'+eproductbasename+'"></td>';
                    cols += '<td><input type="text" readonly  id="eproductname'+ zz + '" type="text" class="form-control" name="ipreproductnameoduct[]" value="'+eproductbasename+'"></td>';
                    cols += '<td><input type="text" id="vharga'+ zz + '" class="form-control" name="vharga[]" onkeyup="cekval(this.value); reformat(this);"/ value="'+vunitprice+'" readonly></td>';
                    // cols += '<td><input type="text" id="nquantity'+ zz + '" class="form-control" name="nquantity[]" onkeyup="total('+ zz +'); cekval(this.value); reformat(this);"/ value="'+nquantityfc+'"></td>';
                    cols += '<td><input type="text" id="nquantity'+ zz + '" class="form-control" name="nquantity[]" onkeyup="hitungnilai(this.value,'+ zz +'); cekval(this.value); reformat(this);"/ value="'+nquantityfc+'"></td>';
                    cols += '<td><input type="text" id="ndisc'+ zz + '" class="form-control" name="ndisc[]" onkeyup="cekval(this.value); reformat(this);"/ value="'+ndisc+'" readonly></td>';
                    cols += '<td><input type="text" id="vsubtotal'+ zz + '" class="form-control" name="vsubtotal[]" onkeyup="cekval(this.value); reformat(this);"/ value="'+vsubtotaldisc+'" readonly></td>';
                    // cols += '<input type="hidden" id="vsubtotalasal'+ zz + '" class="form-control" name="vsubtotalasal[]" onkeyup="cekval(this.value); reformat(this);"/ value="'+vsubtotaldisc+'">';
                    cols += '<td><input type="text" id="edesc'+ zz + '" class="form-control" name="edesc[]"/></td>';
                    // cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

                    // cols += '<td style="text-align: center;"><input type="checkbox" id="chk'+zz+'" name="chk'+zz+'" onclick="setaction('+zz+');"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                   

                }
            },
            error: function () {
                swal('Data Kosong :)');
            }
        });
        xx = $('#jml').val();
    });

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function getmaterial(id){
        var eproductname = $('#eproductname'+id).val();
        var ipromo = $('#ipromo').val();
        var dspb = $('#dspb').val();
        $.ajax({
        type: "post",
        data: {
            'eproductname': eproductname,
            'ipromo': ipromo,
            'dspb': dspb
        },
        url: '<?= base_url($folder.'/cform/getmaterial'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_product_motif);
            $('#vharga'+id).val(data[0].v_unitprice);
            $('#ndisc'+id).val(data[0].n_disc);
            
            ada=false;
            var a = $('#iproduct'+id).val();
            var e = $('#eproductname'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
	            if((a == $('#iproduct'+i).val()) && (i!=id)){
	            	swal ("kode : "+a+" sudah ada !!!!!");
	            	ada=true;
	            	break;
	            }else{
	            	ada=false;	   
	            }
            }
            if(!ada){
                $('#iproduct'+id).val(data[0].i_product_motif);
                $('#vharga'+id).val(data[0].v_unitprice);
                $('#ndisc'+id).val(data[0].n_disc);
            }else{
                $('#iproduct'+id).html('');
                $('#iproduct'+id).val('');
                $('#eproductname'+id).val('');
                $('#eproductname'+id).html('');
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

//     function total(id){
//         var vgross = $('#vgross').val();
// // var vgrossasal = $('#vgrossasal').val();
//         var vdiskon = $('#vdiskon').val();
//         var vnetto = $('#vnetto').val();

//         var vharga = $('#vharga'+id).val();
//         var nquantity = $('#nquantity'+id).val();
//         var ndisc = $('#ndisc'+id).val();

//         var subtotal = parseInt(vharga)*parseInt(nquantity);
// // var vsubtotalasal = $('#vsubtotalasal'+id).val();
//         var subdiskon = subtotal*parseInt(ndisc)/100;
//         var subtotaldisc = (subtotal-subdiskon);
//         $('#vsubtotal'+id).val(subtotaldisc);

//         if(parseInt(vgross)>0){
//             var totalvgross = (parseInt(vgross) - parseInt(vsubtotalasal)) + subtotal;
//             var totalvdiskon = parseInt(vdiskon) + subdiskon;
//             var totalnetto = totalvgross - totalvdiskon;
//         }else{
//             var totalvgross = parseInt(vgross) + subtotal;
//             var totalvdiskon = parseInt(vdiskon) + subdiskon;
//             var totalnetto = totalvgross - totalvdiskon;
//         }
        

//         $('#vgross').val(totalvgross);
//         $('#vdiskon').val(totalvdiskon);
//         $('#vnetto').val(totalnetto);
// }


function hitungnilai(isi,jml){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{

            vtot =0;
            vdisctot = 0;
            vall = 0;
            
            for(i=1;i<=jml;i++){
                vhrg=formatulang(document.getElementById("vharga"+i).value);
                vhrgdisc=formatulang(document.getElementById("vharga"+i).value);
                ndisc=formatulang(document.getElementById("ndisc"+i).value);
                
                
                if (isNaN(parseFloat(document.getElementById("nquantity"+i).value))){
                    nqty=0;
                    
                }else{
                        // if(parseFloat(document.getElementById("nquantitystock"+i).value)<parseFloat(document.getElementById("norder"+i).value)){
                        //     swal("Lebih Dari Stock!!!");
                        //     nqty=0;
                        //     vhrg=parseFloat(vhrg)*parseFloat(nqty);
                        //     vtot=vtot+vhrg;
                        //     document.getElementById("norder"+i).value=formatcemua(vhrg);
                        //     document.getElementById("vtotal"+i).value=formatcemua(vhrg);

                        // }else{

                            nqty=formatulang(document.getElementById("nquantity"+i).value);
                            
                            vhrg=(parseFloat(vhrg)*parseFloat(nqty));
                            vdisc=(parseFloat(vhrgdisc)*parseFloat(nqty))*parseFloat(ndisc)/100;
                            // document.getElementById("vsubtotal"+i).value=formatcemua(vhrg-vdisc);
                            document.getElementById("vsubtotal"+i).value=(vhrg-vdisc);

                            vtot=vtot+vhrg;
                            vdisctot=vdisctot+vdisc;

                        // }
                }
                
            }

            // document.getElementById("vgross").value=formatcemua(vtot);
            // document.getElementById("vdiskon").value=formatcemua(vdisctot);
            // document.getElementById("vnetto").value=formatcemua(vtot-vdisctot);
            document.getElementById("vgross").value=vtot;
            document.getElementById("vdiskon").value=vdisctot;
            document.getElementById("vnetto").value=(vtot-vdisctot);
        }
    }

    function formatSelection(val) {
            return val.name;
        }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
    });

    function hitungnilaidiskon(isi){
        // jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
            vdisctot = 0;

            vtot = document.getElementById("vgross").value;
            ndisctot = document.getElementById("ndiskon").value;
            vdisctot = (vtot*ndisctot)/100;
            document.getElementById("vdiskon").value=vdisctot;
            document.getElementById("vnetto").value=(vtot-vdisctot);
        }
    }

    function hitungpersendiskon(isi){
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
            vdisctot = 0;

            vtot = document.getElementById("vgross").value;
            // ndisctot = document.getElementById("ndiskon").value;
            vdisctot = document.getElementById("vdiskon").value;
            ndisctot = (vdisctot*100)/vtot;
            document.getElementById("ndiskon").value=ndisctot.toFixed(2);
            document.getElementById("vnetto").value=(vtot-vdisctot);
        }
    }
</script>