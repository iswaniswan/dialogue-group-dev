<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom."/".$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?=$title_list;?></a>
            </div>
            <div class="panel-body table-responsive">                
                <input type="hidden" id="id" name="id" value="">
                <input type="hidden" id="iop" name="iop" value="">
                <input type="hidden" id="isupplier" name="isupplier" value="">
                <input type="hidden" id="ibtbtmp" name="ibtbtmp" value="IBTB">

            </div>

            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/proses2'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <input type="hidden" id="dfrom" name="dfrom" value="<?= $dfrom?>">
                <input type="hidden" id="dto" name="dto" value="<?= $dto?>">
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Partner</th>
                            <th>Jenis Partner</th>
                            <th>Nomor SJ</th>  
                            <th>Tanggal SJ</th>   
                            <th>Jenis SJ</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-12" style="text-align: center;">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Proses</button>
                        &nbsp;&nbsp;
                        <label class="custom-control custom-checkbox">
                        <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Check All</span>
                        </label>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/awalnext');
         showCalendar('.date');
    });

    $(document).ready(function () {
        var table = $('#tabledata').DataTable();         
        table.buttons( '.dt-buttons' ).remove();
    });    

    function refreshview() {
        show('<?= $folder;?>/cform','#main');
    }


    function btbtmp(id){
        $('#ibtbtmp').val(id);
    }

    $('#prosessupplier').click(function(){
        if($("#ibtbtmp").val() != ''){
            $.ajax({
                type: "post",
                data: {
                    'isupplier'  : $("#isupplier").val(),
                    'iop'        : $("#iop").val(),
                    'id'         : $("#id").val(),
                    'ibtb'       : $("#ibtbtmp").val(),
                },
                url: '<?= base_url($folder.'/cform/proses'); ?>',
                dataType: "html",
                success: function (data) {
                    $('#main').html(data);
                },
                error: function (data) {
                    swal("Maaf", "Data kosong", "error");
                }
            });
        }else{
            $.ajax({
                success: function (data) {
                    swal("Maaf", "Data kosong, Supplier Tidak Terdaftar dalam Sistem", "error");
                }
            });
        }
    });

    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>