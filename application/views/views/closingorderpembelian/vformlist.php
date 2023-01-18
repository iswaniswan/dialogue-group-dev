<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-4">
                        <div class="form-group row">
                            <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                            <div class="col-sm-5">
                                <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                            </div>
                            <div class="col-sm-5">
                                <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/closing'), 'update' => '#pesan', 'type' => 'post', 'id' => 'formclose', 'class' => 'form-horizontal')); ?>
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No OP</th>
                                <th>Tanggal OP</th>
                                <th>Supplier</th>
                                <th>No SJ</th>
                                <th>Tanggal SJ</th>
                                <th>No BTB</th>
                                <th>Tanggal BTB</th>                         
                                <th>Status Closing</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12" style="text-align: center;">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Closing</button>
                            &nbsp;&nbsp;
                            <label class="custom-control custom-checkbox">
                            <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input">
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Check All</span>
                            </label>
                            &nbsp;&nbsp;<button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>","#main")'> <i class="fa fa-spin fa-refresh"></i>&nbsp;&nbsp;Refresh</button>
                        </div>
                     </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        showCalendar2('.date',null,0);
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom.'/'.$dto;?>');
    });

    $( "#dfrom" ).change(function() {
        var dfrom   = splitdate($(this).val());
        var dto     = splitdate($('#dto').val());
        if (dfrom!=null&& dto!=null) {
            if (dfrom>dto) {
                swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $( "#dto" ).change(function() {
        var dto   = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom!=null && dto!=null) {   
            if (dfrom>dto) {
                swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                $('#dto').val('');
            }
        }
    });

    $("#submit").click(function(event) {
        if ($("#formclose input:checkbox:checked").length > 0){
            return true;
        }else{
            swal('Pilih data minimal satu!');
            return false;
        }
    });
     
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#unclosing").attr("disabled", true);
    });

    function unclosing(id,iop) {
        swal({   
            title: "Apakah anda yakin?",   
            text: "Unclosing OP!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, Unclosing!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "POST",
                    data: {
                        'id'  : id,
                        'iop' : iop,
                    },
                    url: '<?= base_url($folder.'/cform/unclosing'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Unclosing OP!", "Data berhasil di unclosing :)", "success");
                        show('<?= $folder;?>/cform/index/<?= $dfrom."/".$dto;?>','#main');
                    },
                    error: function () {
                        swal("Maaf", "Data gagal di unclosing :(", "error");
                    }
                });
            }else {     
                swal("Dibatalkan", "Anda membatalkan unclosing OP :)", "error");
            } 
        });
    }
</script>