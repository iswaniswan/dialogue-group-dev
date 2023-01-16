<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                    <label class="col-md-3">Gudang</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-3">Tanggal</label>
                        <label class="col-md-3">Tujuan</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" disabled="">
                                <option value="<?=$data->i_bagian;?>"><?=$data->e_nama_master;?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "isj" name="isj" class="form-control"  value="<?=$data->i_sj;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dsj" name="dsj" class="form-control"  value="<?=$data->d_sj;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" disabled="">
                                <option value="<?=$data->i_tujuan;?>"><?=$data->e_departement_name;?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-12">  
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                                
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return getselisih();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                    </div>  
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea type="text" id= "eremark "name="eremark" class="form-control" readonly><?=$data->e_remark;?></textarea>
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align:center; width:5%">No</th>
                                <th style="text-align:center; width:12%">Kode Barang</th>
                                <th style="text-align:center; width:30%">Nama barang</th>
                                <th style="text-align:center; width:12%">Warna</th>
                                <th style="text-align:center; width:8%">Quantity</th>
                                <th style="text-align:center; width:30%">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?$i = 0;
                        foreach ($datadetail as $row) {
                        $i++;?>
                        <tr>
                            <td style="text-align: center;"><?= $i;?>
                                <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct[]"value="<?= $row->i_product; ?>"  readonly >
                            </td>
                            <td>
                                <input type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_product_basename; ?>" class="form-control" readonly >
                            </td> 
                            <td>
                                <input class="form-control" type="hidden" id="icolor<?=$i;?>" name="icolor[]"value="<?= $row->i_color; ?>" >
                                <input class="form-control" type="text" id="ecolor<?=$i;?>" name="ecolor<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly>
                            </td>   
                            <td>
                                <input class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity[]"value="<?= $row->n_quantity; ?>" readonly>
                            </td>
                            <td>
                                <input class="form-control" type="text" id="edesc<?=$i;?>" name="edesc[]" value="<?= $row->e_remark; ?>" readonly>
                            </td>
                        </tr>
                        <?}?>
                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>"> 
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $('#change').attr("disabled", false);
    $("#change").on("click", function () {
        var kode = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'kode'  : kode,
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

    $("#reject").on("click", function () {
        var kode = $("#isj").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'kode'  : kode,
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

    $("form").submit(function(event) {
        event.preventDefault();
        $('#change').attr("disabled", true);
        $('#reject').attr("disabled", true);
        $('#submit').attr("disabled", true);
    });
});


$("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#reject").attr("disabled", true);
     $("#change").attr("disabled", true);
     $("#cancel").attr("disabled", false);
});

function getenabledchange() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal('Data Berhasil Di Change Request');
}
function getenabledreject() {
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#submit').attr("disabled", true);
    swal('Data Berhasil Di Reject');
}
function getenabledsend() {
    $('#send').attr("disabled", true);
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    swal('Berhasil Di Send');
}
</script>