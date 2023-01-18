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
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th>Saldo Awal</th>
                            <th>Masuk</th>
                            <th>Terima Retur</th>
                            <th>Keluar</th>
                            <th>Saldo Akhir</th>
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
        url: '<?= base_url($folder.'/cform/getmakpacking'); ?>',
        dataType: "json",
        success: function (data) {  
            $('#jml').val(data['jmlitem']);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['jmlitem']; a++) {
                var no = a+1;
                var kodebarang        = data['makpack'][a]['kodebarang'];
                var namabarang        = data['makpack'][a]['namabarang'];
                var saldoawal         = data['makpack'][a]['saldoawal'];
                var masuk             = data['makpack'][a]['masuk'];
                var masukretur        = data['makpack'][a]['masukretur'];
                var keluar            = data['makpack'][a]['keluar'];
                var saldoakhir        = data['makpack'][a]['saldoakhir'];
                var so                = data['makpack'][a]['so'];
                var selisih           = data['makpack'][a]['selisih'];
                var icolor            = data['makpack'][a]['icolor'];
                var ecolor            = data['makpack'][a]['ecolor'];
               
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';                
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="iproductwip'+a+'" name="iproductwip'+a+'" value="'+kodebarang+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="eproductnamewip'+a+'" name="eproductnamewip'+a+'" value="'+namabarang+'"></td>';
                 cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="ecolor'+a+'" name="ecolor'+a+'" value="'+ecolor+'"></td>';    
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="saldoawal'+a+'" name="saldoawal'+a+'" value="'+saldoawal+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="masuk'+a+'" name="masuk'+a+'" value="'+masuk+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="terimaretur'+a+'" name="terimaretur'+a+'" value="'+masukretur+'"></td>';
                cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="keluar'+a+'" name="keluar'+a+'" value="'+keluar+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="saldoakhir'+a+'" name="saldoakhir'+a+'" value="'+saldoakhir+'"></td>';
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