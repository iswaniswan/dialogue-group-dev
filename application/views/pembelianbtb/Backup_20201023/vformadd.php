<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/prosesacuanop'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                    <label class="col-md-3">Date From</label>
                    <label class="col-md-3">Date To</label>
                    <label class="col-md-6">Supplier</label>
                        <div class="col-sm-3">
                            <input type="text" id= "dfrom" name="dfrom" class="form-control date" required value="" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dto" name="dto" class="form-control date" required value="" readonly onchange="getsup(this.value);">
                        </div>
                         <div class="col-sm-6">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="gett(this.value);" disabled="true">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-spinner"></i>&nbsp;&nbsp;Proses</button>
                        </div>
                    </div>
                    </div> 
                    <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-6">No OP</label>
                        <div class="col-sm-6">
                            <select name="iop" id="iop" class="form-control select2" onchange="get(this.value);" disabled="true">
                            </select>
                        </div>                        
                    </div>                    
                    </div>
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No PP</th>
                                    <th>Tanggal PP</th>
                                    <th>Tanggal OP</th>
                                    <th>Gudang</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Qty</th>
                                    <th>Satuan</th>
                                    <!-- <th>Jenis Pembayaran</th> -->
                                    <th>Action</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>   
                    <input type="hidden" name="jml" id="jml">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function getsup(dto) {
    $("#isupplier").attr("disabled", false);
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getsup');?>",
        data:{
            'dfrom': dfrom,
            'dto':dto
        },
        dataType: 'json',
        success: function(data){
            $("#isupplier").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
} 

function gett(isupplier) {
    $("#iop").attr("disabled", false);
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    var isupplier = $('#isupplier').val();
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getiop');?>",
        data:{
            'isupplier': isupplier,
            'dfrom': dfrom,
            'dto':dto
        },
        dataType: 'json',
        success: function(data){
            $("#iop").html(data.kop);
            /*$("#icustomer").val(data.sok);*/
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }

    })
}   

function get(iop) {
    var dfrom     = $('#dfrom').val();
    var dto       = $('#dto').val();
    var isupplier = $('#isupplier').val();
    var iop       = $('#iop').val();
    $.ajax({
        type: "post",
        data: {
            'isupplier': isupplier,
            'dfrom'    : dfrom,
            'dto'      : dto,
            'iop'      : iop,
           
        },
        url: '<?= base_url($folder.'/cform/getop'); ?>',
        dataType: "json",
        success: function (data) {  
            // $('#dop').val(data[0].d_op);
            $('#jml').val(data['jmlitem']);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['jmlitem']; a++) {
                var no = a+1;
                var isupplier   = data['brgop'][a]['i_supplier'];
                var ipp         = data['brgop'][a]['i_pp'];
                var iop         = data['brgop'][a]['i_op'];
                var dpp         = data['brgop'][a]['d_pp'];
                var dop         = data['brgop'][a]['d_op'];
                var igudang     = data['brgop'][a]['i_kode_master'];
                var egudang     = data['brgop'][a]['e_nama_master'];
                var imaterial   = data['brgop'][a]['i_material'];
                var ematerial   = data['brgop'][a]['e_material_name'];
                var isatuan     = data['brgop'][a]['i_satuan'];
                var esatuan     = data['brgop'][a]['e_satuan'];
                var nquantity   = data['brgop'][a]['n_quantity'];
                var ipayment    = data['brgop'][a]['ipayment'];
                var epayment    = data['brgop'][a]['e_payment_typename'];
                var npemenuhan  = data['brgop'][a]['n_pemenuhan'];
               
                var cols        = "";
                var newRow = $("<tr>");
              
                cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input style="width:100px;" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+isupplier+'"></td>';                
                cols += '<td><input style="width:150px;" readonly class="form-control" type="text" id="ipp'+a+'" name="ipp'+a+'" value="'+ipp+'"></td>';
                cols += '<td><input style="width:110px;" readonly class="form-control" type="text" id="dpp'+a+'" name="dpp'+a+'" value="'+dpp+'"></td>';
                cols += '<td><input style="width:110px;" readonly class="form-control" type="text" id="dop'+a+'" name="dop'+a+'" value="'+dop+'"></td>';
                cols += '<td><input style="width:200px;" readonly class="form-control" type="hidden" id="igudang'+a+'" name="igudang'+a+'" value="'+igudang+'"><input style="width:200px;" readonly class="form-control" type="text" id="egudang'+a+'" name="egudang'+a+'" value="'+egudang+'"></td>';               
                cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                cols += '<td><input readonly style="width:400px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+ematerial+'"></td>';
                cols += '<td><input style="width:150px;" class="form-control" readonly type="hidden" id="nquantity'+a+'" name="nquantity'+a+'" value="'+nquantity+'"><input style="width:150px;" readonly class="form-control" type="text" id="npemenuhan'+a+'" name="npemenuhan'+a+'" value="'+npemenuhan+'"></td>';
                cols += '<td><input style="width:40px;"  type="hidden" id="isatuan'+a+'" name="isatuan'+a+'" value="'+isatuan+'"><input style="width:150px;" class="form-control" type="text" id="esatuan'+a+'" readonly name="esatuan'+a+'" value="'+esatuan+'"></td>';
                cols += '<td><input type="checkbox" name="cek'+a+'" value="cek" id="cek'+a+'"></td';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}

function validasi(){
var s=0;
    var maxpil = 1;
    var jml = $("input[type=checkbox]:checked").length;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
    if (textinputs.length == empty.length) {
        swal("PP Masih kosong!");
        return false;
    }else{
        return true;
    }
}  
</script>
