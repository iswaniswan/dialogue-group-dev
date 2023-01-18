<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <!-- <div class="panel-body table-responsive">                
                <input type="hidden" id="id" name="id" value="">
                <input type="hidden" id="iop" name="iop" value="">
                <input type="hidden" id="isupplier" name="isupplier" value="">
                <input type="hidden" id="ibtbtmp" name="ibtbtmp" value="IBTB">

            </div> -->
            <!-- Modal -->
            <!-- <div class="modal" id="myModal" role="dialog">
                <div class="modal-dialog"> -->
            <!-- Modal content-->
            <!-- <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" style="text-align:center;"><b>Pilih BTB</b></h4>
                        </div>
                        <div class="modal-body">
                            <select name="ibtb" id="ibtb" style="width:100%;" class="form-control select2" onchange="return btbtmp(this.value);">
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="prosessupplier" class="btn btn-info btn-sm" data-dismiss="modal">Proses</button>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="panel-body">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder . '/cform/proses2'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <input type="hidden" id="dfrom" name="dfrom" value="<?= $dfrom ?>">
                <input type="hidden" id="dto" name="dto" value="<?= $dto ?>">
                <div class="table-responsive">
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="2%;">No</th>
                                <th>Supplier</th>
                                <th>Nomor OP</th>
                                <th>Tanggal OP</th>
                                <th>Nomor BTB</th>
                                <th>Tanggal BTB</th>
                                <th>SJ Supplier</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
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
    $(document).ready(function() {
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/awalnext');
        showCalendar('.date');
        $(".select2").select2();
        $('#ibtb').select2({
            placeholder: "Pilih BTB",
        });
    });

    $(document).ready(function() {
        var table = $('#tabledata').DataTable();
        table.buttons('.dt-buttons').remove();
    });

    function refreshview() {
        show('<?= $folder; ?>/cform', '#main');
    }

    function callswal(id, isupplier, iop) {
        $('#myModal').modal('show');
        $('#id').val(id);
        $('#iop').val(iop);
        $('#isupplier').val(isupplier);
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder . '/Cform/getibtb'); ?>",
            data: {
                'id': id,
                'iop': iop,
                'isupplier': isupplier,
            },
            dataType: 'json',
            success: function(data) {
                $("#ibtb").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                    //$("#prosessupplier").attr("disabled", true);
                    swal("Data BTB Kosong");
                    $('#myModal').modal('hide');
                } else {
                    $("#submit").attr("disabled", false);

                }
            }
        });
    }

    function btbtmp(id) {
        $('#ibtbtmp').val(id);
    }

    $('#prosessupplier').click(function() {
        if ($("#ibtbtmp").val() != '') {
            $.ajax({
                type: "post",
                data: {
                    'isupplier': $("#isupplier").val(),
                    'iop': $("#iop").val(),
                    'id': $("#id").val(),
                    'ibtb': $("#ibtbtmp").val(),
                },
                url: '<?= base_url($folder . '/cform/proses'); ?>',
                dataType: "html",
                success: function(data) {
                    $('#main').html(data);
                },
                error: function(data) {
                    swal("Maaf", "Data kosong", "error");
                }
            });
        } else {
            $.ajax({
                success: function(data) {
                    swal("Maaf", "Data kosong, Supplier Tidak Terdaftar dalam Sistem", "error");
                }
            });
        }
    });

    $("#checkAll").click(function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>