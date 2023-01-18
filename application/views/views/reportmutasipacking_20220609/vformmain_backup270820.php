<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
          <!--   <div class="panel-heading">
                <i class="fa fa-eye"></i> &nbsp; <#?= $title; ?> <a href="#"
                    onclick="show('<#?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"></a>
            </div> -->

            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?><a href="#" onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-arrow-circle-o-left"></i> &nbsp;Kembali</a>
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
                            <th>Saldo Awal</th>
                            <th>Masuk</th>
                            <th>Masuk Makloon</th>
                            <th>Keluar</th>
                            <th>Keluar Makloon</th>
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
        url: '<?= base_url($folder.'/cform/getpacking'); ?>',
        dataType: "json",
        success: function (data) {  
            $('#jml').val(data['jmlitem']);
            $("#tabledata tbody").remove();
            $("#tabledata").attr("hidden", false);
            for (let a = 0; a < data['jmlitem']; a++) {
                var no = a+1;
                var iproduct          = data['packing'][a]['kode'];
                var eproductname      = data['packing'][a]['barang'];
                var kodegudang        = data['packing'][a]['kodegudang'];
                var gudang            = data['packing'][a]['gudang'];
                var saldoawal         = data['packing'][a]['saldoawal'];
                var bonmasuk          = data['packing'][a]['bonmasuk'];
                var bonmasukmakloon   = data['packing'][a]['bonmasukmakloon'];
                var bonkeluar         = data['packing'][a]['bonkeluar'];
                var bonkeluarmakloon  = data['packing'][a]['bonkeluarmakloon'];
                var saldoakhir        = data['packing'][a]['saldoakhir'];
                var so                = data['packing'][a]['so'];
                var selisih           = data['packing'][a]['selisih'];
               
                var cols        = "";
                var newRow = $("<tr>");
                
                cols += '<td style="text-align:center;">'+no+'<input class="form-control" readonly type="hidden" id="baris'+a+'" name="baris'+a+'" value="'+no+'"></td>';                
                cols += '<td><input readonly style="width:90px;" class="form-control" type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+iproduct+'"></td>';
                cols += '<td><input readonly style="width:350px;" class="form-control" type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+eproductname+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="saldoawal'+a+'" name="saldoawal'+a+'" value="'+saldoawal+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="bonmasuk'+a+'" name="bonmasuk'+a+'" value="'+bonmasuk+'"></td>';
                cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="bonmasukmakloon'+a+'" name="bonmasukmakloon'+a+'" value="'+bonmasukmakloon+'"></td>';
                cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="bonkeluar'+a+'" name="bonkeluar'+a+'" value="'+bonkeluar+'"></td>';
                cols += '<td><input style="width:100px;" readonly class="form-control" type="text" id="bonkeluarmakloon'+a+'" name="bonkeluarmakloon'+a+'" value="'+bonkeluarmakloon+'"></td>';
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