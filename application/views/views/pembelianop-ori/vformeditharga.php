<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/updateharga'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Batasan Pemenuhan</label>
                        <label class="col-md-2">Jenis Pembelian</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="" disabled="true">
                                <option value="<?=$data->i_bagian;?>"><?=$data->bagian_pembuat;?></option>
                            </select>
                            <input type="hidden" id="id" name="id" value="<?=$data->id?>">
                            <input type="hidden" id="ibagian" name="ibagian" value="<?=$data->i_bagian;?>">
                            <input type="hidden" id="istatus" name="istatus" value="<?=$data->i_status;?>">
                        </div>
                        <?php if($data->i_status != '6'){?>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="iop" id="iop" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_op;?>" aria-label="Text input with dropdown button">
                                    <span class="input-group-addon">
                                        <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                    </span>
                                </div>
                                <span class="notekode">Format : (<?= $data->i_op;?>)</span><br>
                                <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                            </div>
                        <?php }else{ ?>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" name="iop" id="iop" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?=$data->i_op;?>" aria-label="Text input with dropdown button">
                                </div>
                            </div>
                            <?php
                        }?>
                        <?php if($data->i_status != '6'){?>
                            <div class="col-sm-2">
                                <input type="text" id="dop" name="dop" class="form-control input-sm date" required="" readonly value="<?= $data->d_op; ?>">
                            </div>
                        <?php }else{?>
                            <div class="col-sm-2">
                                <input type="text" id="dop" name="dop" class="form-control input-sm" required="" readonly value="<?= $data->d_op; ?>">
                            </div>
                        <?php }?>
                        <?php if($data->i_status != '6'){?>
                            <div class="col-sm-2">
                                <input type="text" id="dbp" name="dbp" class="form-control input-sm date"  required="" readonly value="<?= date('d-m-Y', strtotime($data->d_deliv)); ?>">
                            </div>
                        <?php }else{ ?>
                            <div class="col-sm-2">
                                <input type="text" id="dbp" name="dbp" class="form-control input-sm"  required="" readonly value="<?= date('d-m-Y', strtotime($data->d_deliv)); ?>">
                            </div>
                        <?php }?>
                        <div class="col-sm-2">
                            <input type="text" id="jenis" name="jenis" class="form-control input-sm" required="" readonly value="<?= $data->jenis_pembelian; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <!-- <label class="col-md-3">Gudang</label> -->
                        <!-- <label class="col-md-3">No Referensi</label> -->
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-3">Importance Status</label>
                        <label class="col-md-6">Keterangan</label>
                        <!-- <div class="col-sm-3"> -->
                            <?php $e_bagian_name = str_replace('"','', str_replace("}", "", str_replace("{", "", str_replace(",", ",", $data->e_bagian_name)))); ?>
                            <!-- <input type="text" name="egudang" id="egudang" class="form-control input-sm" value="<?= $e_bagian_name ?>" readonly required> -->
                        <!-- </div> -->
                        <!-- <div class="col-sm-3">
                            <input type="text" name="ipp" id="ipp" class="form-control input-sm" value="<?= $data->i_pp ?>" readonly required>
                        </div> -->
                        <div class="col-sm-3">
                            <input type="hidden" id="isupplier" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier;?>" readonly> 
                            <input type="text" id="esupplier" name="esupplier" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_supplier." - ".$data->e_supplier_name;?>" readonly>
                            <input type="hidden" id="esuppliername" name="esuppliername" value="<?=$data->e_supplier_name;?>">
                            <input type="hidden" id="ntop" name="ntop" value="<?=$data->n_top;?>">
                            <?php if($data->i_type_pajak == 'I'){
                                $fppn = 't';
                            }else if($data->i_type_pajak == 'E'){
                                $fppn = 'f';
                            }
                            ?>
                            <input type="hidden" id="itypepajak" name="itypepajak" value="<?=$data->i_type_pajak;?>">
                            <input type="hidden" id="fppn" name="fppn" value="<?=$fppn;?>">
                            <input type="hidden" id="ndiskon" name="ndiskon" value="<?=$data->n_diskon;?>">
                            <input type="hidden" id="fpkp" name="fpkp" value="<?=$data->f_pkp;?>">
                        </div>
                        <div class="col-sm-3">
                            <?php if($data->i_status != '6'){?>
                                <select name="importantstatus" id="importantstatus" class="form-control select2"> 
                                    <option value="<?=$data->i_status_op;?>"><?=$data->e_status_op;?></option>
                                </select>    
                            <?php }else{?>
                                <input type="hidden" name="importantstatusharga" id="importantstatusharga" value="<?=$data->i_status_op;?>">  
                                <input type="text" name="emportantstatus" class="form-control" id="emportantstatus" value="<?=$data->e_status_op;?>" readonly> 
                            <?php }?>
                        </div>
                        <?php if($data->i_status != '6'){?>
                            <div class="col-sm-6">
                                <textarea type="text" name="eremarkh" id="eremark" class="form-control" value="" placeholder="Keterangan"><?=$data->e_remark;?></textarea>
                            </div>
                        <?php }else{?>
                            <div class="col-sm-6">
                                <textarea type="text" name="eremarkh" id="eremark" class="form-control" value="" placeholder="Keterangan" readonly><?=$data->e_remark;?></textarea>
                            </div>
                        <?php }?>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <?php if ($data->i_status == '14') {?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"  onclick="return validasi();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <?php if ($data->i_status == '14') {?>
                                <button type="button" id="send" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>&nbsp;
                                <button type="button" id="hapus" class="btn btn-danger btn-rounded btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</button>&nbsp;
                            <?php }elseif($data->i_status=='8') {?>
                                <button type="button" id="cancel" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Cancel</button>&nbsp;
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="notekode"><b>Note : </b></span><br>
                            <span class="notekode">* Form ini hanya untuk edit harga dan kirim Dokumen!</span><br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center;width:3%">No</th>
                        <th style="text-align:center;width:10%">Kode</th>
                        <th style="text-align:center;width:30%">Nama Barang</th>
                        <th style="text-align:center;width:9%">Qty</th>  
                        <th style="text-align:center;width:11%">Satuan</th>
                        <th style="text-align:center;width:10%">Harga</th>
                        <th style="text-align:center;width:10%">Total</th>
                        <th style="text-align:center;width:22%">Keterangan</th>                           
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($data2){
                        $i = 0;
                        $group = "";
                        $no = 0;
                        foreach($data2 as $row){
                            $i++;
                            $no++;
                            $total = $row->n_quantity*$row->v_price;
                            if($group==""){ ?>
                                <tr class="pudding">
                                <td colspan="8">Nomor PP : <b><?= $row->i_pp;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp;?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name;?> )</td>
                                </tr>
                            <?php } else {
                                    if($group!=$row->id_pp){ ?>
                                    <tr class="pudding">
                                        <td colspan="8">Nomor PP : <b><?= $row->i_pp;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp;?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name;?> )</td>
                                    </tr>
                                    <?php $no = 1; }
                                }
                                $group = $row->id_pp                            
                            
                            ?>
                            <tr>
                                <td class="text-center">
                                    <?= $i;?>
                                    <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                    <input type="hidden" id="ipp<?=$i;?>" name="ipp<?=$i;?>"value="<?= $row->i_pp; ?>" readonly>
                                    <input type="hidden" name="idpp<?=$i;?>" id="idpp<?=$i;?>" value="<?=$row->id_pp;?>">
                                    <input type="hidden" class="form-control" id="ibagian<?=$i;?>" name="ibagian<?=$i;?>"value="<?= $row->i_bagian; ?>" readonly> 
                                </td> 

                                <td>  
                                    <input type="text" class="form-control input-sm" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control input-sm" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly>
                                </td>                            
                                <td>
                                    <input type="text" class="form-control input-sm text-right" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_quantity; ?>" onkeyup="valstock(<?=$i;?>); hitung(this.value); angkahungkul(this);"> 
                                    <input type="hidden" class="form-control" id="npemenuhan<?=$i;?>" name="npemenuhan<?=$i;?>" value="<?= $row->n_sisa; ?>" readonly>
                                    <input type="hidden" class="form-control" id="nquantity_now<?=$i;?>" name="nquantity_now<?=$i;?>" value="<?= $row->n_quantity; ?>" readonly> 
                                </td>
                                <td>  
                                    <input type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>" value="<?= $row->i_satuan_code; ?>" readonly>
                                    <input type="text" class="form-control input-sm" id="isatuan1<?=$i;?>" name="isatuan1<?=$i;?>"value="<?= $row->e_satuan_name; ?>" readonly>
                                </td>
                                <td>  
                                    <input type="text" class="form-control input-sm text-right inputitem" id="vprice<?=$i;?>" name="vprice<?=$i;?>"value="<?= number_format($row->v_price); ?>" onkeyup="hitung(this.value); angkahungkul(this); reformat(this);">
                                </td>
                                <td>
                                    <input type="text" class="form-control input-sm text-right" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>"value="<?= number_format($total,2); ?>" readonly>
                                </td>
                                <td>
                                    <?php if($row->i_status != '6'){?>
                                        <input type="text" class="form-control input-sm" id="eremark<?=$i;?>" name="eremark<?=$i;?>"value="<?=$row->remark;?>" placeholder="Isi Keterangan Jika Ada!">
                                    <?php }else{?>
                                        <input type="text" class="form-control input-sm" id="eremark<?=$i;?>" name="eremark<?=$i;?>"value="<?=$row->remark;?>" placeholder="Isi Keterangan Jika Ada!">
                                    <?php }?>
                                </td>                        
                            </tr>    
                           
                            <?}
                        }else{
                            $i=0;
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"16\" style=\"text-align:center;\">Maaf Tidak Ada PP!</td></tr></table>"; 
                        }?>     
                         <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">  
                    </tbody>                         
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</form>
<!-- <script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script> -->
<script>
    $(document).ready(function () {
        $('.select2').select2();
        /*showCalendar('.date');*/

        cekharga();

        /*$('#iop').mask('SS-0000-000000S');*/

        /*$('#importantstatus').select2({
            placeholder:"Pilih Importance Status",
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/importancestatus'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });*/
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'8','<?= $dfrom."','".$dto;?>');
    });

    $('#cancel').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'14','<?= $dfrom."','".$dto;?>');
    });

    $('#hapus').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'5','<?= $dfrom."','".$dto;?>');
    });

    /*function maxi(){
        var dop     = $('#dop').val();
        var da      = dop.substr(0, 2);
        var ma      = dop.substr(3, 2);
        var ya      = dop.substr(6, 4);
        var today   = new Date(ya, ma, da);
        var year    = today.getFullYear();
        var month   = today.getMonth();
        var date    = today.getDate();
        var day     = new Date(year, month, date+30);

        mnth = ("0" + (day.getMonth())).slice(-2),
        dath = ("0" + day.getDate()).slice(-2);
        jam  = [dath, mnth, today.getFullYear()].join("-");

        $('#dbp').val(jam);
    }*/

    function validasi(){
        var s=0;
        var jml = $('#jml').val();
        var harga = $('#vprice'+i).val();

        var textinputs = document.querySelectorAll('input[type=input]'); 
        var empty = [].filter.call( textinputs, function( el ) {
             return !el.checked
        });

        if(document.getElementById('dop').value==''){
            swal("Tanggal OP Masih Kosong!");
            return false;
        }else if(document.getElementById('importantstatus').value=='' || document.getElementById('importantstatus').value== null){
            swal("Importance Status Masih Kosong");
            return false;
        }else{
             $("#tabledatax tbody tr").each(function() {
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Harga Tidak Boleh Kosong Atau 0!');
                        ada = true;
                    }
                });
            });
            return true
        }
    }    

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#hapus").attr("disabled", true);
        $("#cancel").attr("disabled", true);
    });

   /* $( "#iop" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#iop").attr("readonly", false);
        }else{
            $("#iop").attr("readonly", true);
            $("#iop").val("<?= $number;?>");
        }
    });*/

    f/*unction cekharga(){

       var i = $('#jml').val();
       var harga = $('#vprice'+i).val();

       if(harga == ''){
           swal("Harga masih kosong, Input Harga terlebih dahulu di Master Harga Per Supplier");
       }
   }

   $( "#dop" ).change(function() {
    maxi();
    $.ajax({
        type: "post",
        data: {
            'tgl' : $(this).val(),
        },
        url: '<?= base_url($folder.'/cform/number'); ?>',
        dataType: "json",
        success: function (data) {
            $('#iop').val(data);
        },
        error: function () {
            swal('Error :)');
        }
    });
});*/

   /*function getenabledcancel() {
    swal("Berhasil", "Cancel Dokumen", "success");
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledsend() {
    swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
    $('#sendd').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}*/

function valstock(id){ 
    // var jml = $('#jml').val();
    // for(var i=1; i<=jml; i++){        
    //        var stock         = $('#nquantity'+i).val();
    //        var noutstanding  = $('#npemenuhan'+i).val();
    //        var qty_now       = $('#nquantity_now'+i).val();
    //        if (stock == ''){
    //         stock = 0;
    //     }
    //     if(parseFloat(stock) > parseFloat(noutstanding)){
    //         swal ("Jumlah quantity melebihi stock");
    //         document.getElementById("nquantity"+id).value=0;
    //         break;
    //     }
    // }
    var jml = $('#jml').val();
   // for(var i=1; i<=jml; i++){        
    var stock         = $('#nquantity'+id).val();
    var noutstanding  = $('#npemenuhan'+id).val();
     if (stock == ''){
        stock = 0;
    }
    if(parseFloat(noutstanding)<parseFloat(stock)){
        swal ("Jumlah quantity melebihi stock");
        $('#nquantity'+id).val(noutstanding);
           // break;
    }
}

function hitung(){
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){
        var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
        qty = $('#nquantity'+i).val() || 0;

        var price = formatulang($('#vprice'+i).val())==''?$('#vprice'+i).val(0):price;
        price   = formatulang($('#vprice'+i).val()) || 0; 

        total = (parseFloat(qty)*parseFloat(price));
        $('#vtotal'+i).val(formatcemua(total));
    }
}

/*$(document).ready(function(){
    $("#cancel").on("click", function () {
        var iop = $("#iop").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
             'iop'  : iop,
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
});

$(document).ready(function(){
    $("#sendd").on("click", function () {
        var iop = $("#iop").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
             'iop'  : iop,
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
});*/

// function max_tgl(val) {
//   $('#dop').datepicker('destroy');
//   $('#dop').datepicker({
//     autoclose: true,
//     todayHighlight: true,
//     format: "dd-mm-yyyy",
//     todayBtn: "linked",
//     daysOfWeekDisabled: [0],
//     startDate: document.getElementById('dpp').value,
// });
// }
// $('#dop').datepicker({
//   autoclose: true,
//   todayHighlight: true,
//   format: "dd-mm-yyyy",
//   todayBtn: "linked",
//   daysOfWeekDisabled: [0],
//   startDate: document.getElementById('dpp').value,
// });

// function max_tglkirim(val) {
//   $('#ddelivery').datepicker('destroy');
//   $('#ddelivery').datepicker({
//     autoclose: true,
//     todayHighlight: true,
//     format: "dd-mm-yyyy",
//     todayBtn: "linked",
//     daysOfWeekDisabled: [0],
//     startDate: document.getElementById('dop').value,
// });
// }
// $('#ddelivery').datepicker({
//   autoclose: true,
//   todayHighlight: true,
//   format: "dd-mm-yyyy",
//   todayBtn: "linked",
//   daysOfWeekDisabled: [0],
//   startDate: document.getElementById('dop').value,
// });
</script>
