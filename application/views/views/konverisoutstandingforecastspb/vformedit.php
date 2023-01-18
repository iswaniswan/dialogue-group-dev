<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group row">
                        <div class="col-sm-6">
                            <label >Nomor SPB</label>
                            <input type="text" id="ispb" name="ispb" class="form-control" readonly maxlength="" value="<?php echo $data->i_op_code;?>">
                            <?php foreach ($bagian as $ibagian):?>
                                <input type="hidden" id= "ibagian" name="ibagian" class="form-control date" value="<?php echo $ibagian->i_departement; ?>" readonly>
                                <?php endforeach; ?>
                        </div>
                </div>
                <div class="form-group row">
                        <label class="col-md-6">Tanggal SPB</label>
                        <label class="col-md-6">Batas Kirim</label>
                        <div class="col-sm-6">
                                <input type="text" id= "dspb" name="dspb" class="form-control date" value="<?php echo $data->d_op; ?>" required="" readonly>
                        </div>
                        <div class="col-sm-6">
                                <input type="text" id= "dbatas" name="dbatas" class="form-control date" value="<?php echo $data->d_delivery_limit; ?>" required="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">PO Refferensi</label>
                        <label class="col-md-6">Promo</label>
                        <div class="col-sm-6">
                            <input type="text" id= "iporeff" name="iporeff" class="form-control" value="<?= $data->i_op_reff ?>">
                        </div>
                        <div class="col-sm-6">
                            <select name="ipromo" id="ipromo" class="form-control select2" disabled="">
                                <option value="">-- Pilih promo --</option>
                                <?php foreach ($promo as $ipromo):?>
                                    <?php if ($ipromo->i_promo == $data->i_promo) { ?>
                                         <option value="<?php echo $ipromo->i_promo;?>" selected><?= $ipromo->i_promo;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $ipromo->i_promo;?>"><?= $ipromo->i_promo;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                                <input type="text" id= "eremark" name="eremark" class="form-control" value="<?= $data->e_op_remark?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" ><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                            <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                     <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-6">Alamat</label>
                        <div class="col-sm-6">          
                            <select name="icustomer" id="icustomer" class="form-control select2" >
                                <option value="">-- Pilih Customer --</option>
                                <?php foreach ($customer as $icustomer):?>
                                    <?php if ($icustomer->i_customer == $data->i_customer) { ?>
                                         <option value="<?php echo $icustomer->i_customer;?>" selected><?= $icustomer->e_customer_name;?></option>
                                    <?php } else { ?>
                                         <option value="<?php echo $icustomer->i_customer;?>"><?= $icustomer->e_customer_name;?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-6">                            
                            <input type="text" id= "ecustomeraddress" name="ecustomeraddress" class="form-control" value="<?= $data->e_customer_address; ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4">Nilai Kotor</label>
                        <label class="col-md-4">Nilai Diskon</label>
                        <label class="col-md-4">Nilai Bersih</label>
                        <div class="col-sm-4">
                            <input type="text" id= "vgross" name="vgross" class="form-control" value="<?= $data->v_total_gross; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "vdiskon" name="vdiskon" class="form-control" value="<?= $data->v_total_discount; ?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id= "vnetto" name="vnetto" class="form-control" value="<?= $data->v_total_netto; ?>">
                        </div>
                    </div>
                </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledataa" class="table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th width="45%">Nama Barang Jadi</th>
                                    <th>Harga</th>
                                    <th>Quantity </th>
                                    <th>Disc</th>
                                    <th>Total</th>
                                    <th>Keterangan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                 <?php $i = 0;
                                    foreach ($datadetail as $row) {
                                    $i++;
                                ?>
                                <tr>
                                <td style="text-align: center;"><?=$i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="iproduct<?=$i;?>" name="iproduct[]" value="<?= $row->i_product; ?>" readonly >    
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px" class="form-control" type="text" id="eproductname<?=$i;?>" name="eproductname[]" value="<?= $row->e_product_basename; ?>" readonly >    
                                </td>
                                <td class="col-sm-1">     
                                    <input style ="width:80px" class="form-control" type="text" id="vharga<?=$i;?>" name="vharga[]" value="<?= $row->v_price; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]" value="<?= $row->n_count; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="ndisc<?=$i;?>" name="ndisc[]" value="<?= $row->n_disc; ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" class="form-control" type="text" id="vsubtotal<?=$i;?>" name="vsubtotal[]" value="<?= (($row->v_price*$row->n_count)-($row->v_price*$row->n_count)*$row->n_disc/100); ?>" >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:200px" class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]"value="<?= $row->e_remark; ?>" >
                                </td>
                                <td align="center">
                                    <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button>
                                </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

$("#sendd").on("click", function () {
        var ispb = $("#ispb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'ispb'  : ispb,
                    },
            dataType: 'json',
            delay: 250, 
            success: function(data) {
                return {
                results: data
                };
            },
             cache: true
        });
    });

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function getenabledsend() {
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
    $('#addrow').attr("disabled", true);
}

    var a = $('#jml').val();
    $("#addrow").on("click", function () {
        a++;
        var ireff = $('#ireff').val();
        $('#jml').val(a);
        count=$('#tabledataa tr').length;
        $("#tabledataa").attr("hidden", false);
        var newRow = $("<tr>");
        
        var cols = "";

        cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+count+'</spanx><input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><input style="width:100px;" type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
        cols += '<td><select type="text" id="eproductname'+ counter + '" class="form-control" name="eproductname[]" onchange="getmaterial('+ counter +');"></td>';
        cols += '<td><input type="text" id="vharga'+ counter + '" class="form-control" name="vharga[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" onkeyup="total('+ counter +'); cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="ndisc'+ counter + '" class="form-control" name="ndisc[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="vsubtotal'+ counter + '" class="form-control" name="vsubtotal[]" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]"/></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';

        newRow.append(cols);
        $("#tabledataa").append(newRow);

        $('#eproductname'+ counter).select2({
            placeholder: 'Pilih Material',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {
              url: '<?= base_url($folder.'/cform/datamaterial/'); ?>',
              
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                return {
                  results: data
                };
              },
              cache: true
            }
        });

    });


    function formatSelection(val) {
        return val.name;
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
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

</script>