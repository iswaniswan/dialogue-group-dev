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
                            <th>Kode Barang WIP</th>
                            <th>Nama Barang WIP</th>
                            <th>Warna</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <!-- <th>Satuan</th> -->
                            <th>Saldo Awal</th>
                            <th>Masuk</th>
                            <th>Masuk Lain</th>
                            <th>Keluar</th>
                            <th>Keluar Lain</th>
                            <th>Saldo Akhir</th>
                            <th>GIT</th>
                            <th>SO</th>
                            <th>Selisih</th>
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
        url: '<?= base_url($folder.'/cform/getqcset'); ?>',
        dataType: "json",
        success: function (data) {  
            $('#jml').val(data['jmlitem']);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['jmlitem']; a++) {
                var no = a+1;
                var iproductwip       = data['qcset'][a]['kodewip'];
                var eproductnamewip   = data['qcset'][a]['barangwip'];
                var iproduct          = data['qcset'][a]['kode'];
                var eproductname      = data['qcset'][a]['barang'];
                var satuan            = data['qcset'][a]['satuan'];
                var saldoawal         = data['qcset'][a]['saldoawal'];
                var bonmasuk          = data['qcset'][a]['bonmasuk1'];
                var bonmasuklain      = data['qcset'][a]['bonmasuklain'];
                var bonkeluar         = data['qcset'][a]['bonkeluar'];
                var bonkeluarlain     = data['qcset'][a]['bonkeluarlain'];
                var saldoakhir        = data['qcset'][a]['saldoakhir'];
                var git               = data['qcset'][a]['git'];
                var so                = data['qcset'][a]['so'];
                var selisih           = data['qcset'][a]['selisih'];
                var icolor            = data['qcset'][a]['icolor'];
                var ecolor            = data['qcset'][a]['ecolor'];
               
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';                
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="iproductwip'+a+'" name="iproductwip'+a+'" value="'+iproductwip+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="eproductnamewip'+a+'" name="eproductnamewip'+a+'" value="'+eproductnamewip+'"></td>';
                 cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="ecolor'+a+'" name="ecolor'+a+'" value="'+ecolor+'"></td>';    
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+iproduct+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+eproductname+'"></td>';
                // cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="satuan'+a+'" name="satuan'+a+'" value="'+satuan+'"></td>';    
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="saldoawal'+a+'" name="saldoawal'+a+'" value="'+saldoawal+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="bonmasuk'+a+'" name="bonmasuk'+a+'" value="'+bonmasuk+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="bonmasuklain'+a+'" name="bonmasuklain'+a+'" value="'+bonmasuklain+'"></td>';
                cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="bonkeluar'+a+'" name="bonkeluar'+a+'" value="'+bonkeluar+'"></td>';
                cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="bonkeluarlain'+a+'" name="bonkeluarlain'+a+'" value="'+bonkeluarlain+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="saldoakhir'+a+'" name="saldoakhir'+a+'" value="'+saldoakhir+'"></td>';
                cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="git'+a+'" name="git'+a+'" value="'+git+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="so'+a+'" name="so'+a+'" value="'+so+'"></td>';
                cols += '<td><input style="width:80px;" readonly class="form-control" type="text" id="selisih'+a+'" name="selisih'+a+'" value="'+selisih+'"></td>';
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