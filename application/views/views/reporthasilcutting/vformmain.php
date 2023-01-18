<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"></a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses1'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
             <div class="col-md-6">
                <div id="pesan"></div>
                <div class="form-group row">
                <label class="col-md-3">Date From</label>
                <label class="col-md-9">Date To</label>
                    <div class="col-sm-3">
                        <input type="text" id= "dfrom" name="dfrom" class="form-control date" required value="" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" id= "dto" name="dto" class="form-control date" required value="" readonly >
                    </div>                 
                </div> 
                 <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="button" id="proses" class="btn btn-inverse btn-rounded btn-sm" onclick="return get();"><i class="fa fa-spinner"></i>&nbsp;&nbsp;View</button>                    
                    </div>
                </div> 
            </div>     
                <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%" hidden="true">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="15%">Kode Barang WIP</th>
                            <th width="40%">Nama Barang WIP</th>
                            <th>Qty WIP</th>
                            <th width="15%">Kode Barang Raw Material</th>
                            <th width="40%">Nama Barang Raw Material</th>
                            <th>Qty Raw Material</th>
                        <tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>    
         <input type="hidden" name="jml" id="jml" value="0"> 
            </div>
        </div>
        </form>
    </div>
</div>
<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#proses").attr("disabled", true);
});

function get() {
    var dfrom = $('#dfrom').val();
    var dto = $('#dto').val();
    $.ajax({
        type: "post",
        data: {
            'dfrom': dfrom,
            'dto':dto,           
        },
        url: '<?= base_url($folder.'/cform/getcutting'); ?>',
        dataType: "json",
        success: function (data) {  
            $('#jml').val(data['jmlitem']);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['jmlitem']; a++) {
                var no = a+1;
                var iproduct       = data['cutting'][a]['i_product'];
                var eproductname   = data['cutting'][a]['e_product_name'];
                var qtyp           = data['cutting'][a]['quantity_product'];
                var imaterial      = data['cutting'][a]['i_material'];
                var ematerial      = data['cutting'][a]['e_material_name'];
                var qtym           = data['cutting'][a]['quantity_material'];
               
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';                
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+iproduct+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+eproductname+'"></td>';
                cols += '<td><input style="width:80px;" readonly class="form-control" type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+qtyp+'"></td>';           
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+ematerial+'"></td>';
                cols += '<td><input style="width:80px;" readonly class="form-control" type="text" id="qtym'+a+'" name="qtym'+a+'" value="'+qtym+'"></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            }
        },
        error: function () {
            alert('Error :)');
        }
    });
}
</script>