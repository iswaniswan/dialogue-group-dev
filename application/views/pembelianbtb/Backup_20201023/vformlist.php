<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i
                        class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>            
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
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
                                <button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                            </div>
                        </div>
                    </div>
                </form>
                <table id="tabledata" class="display nowrap" cellspacing="0" width="100%" >
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor BTB</th>
                            <th>Tanggal BTB</th>
                            <th>No Dok Supp</th>                            
                            <th>Nomor PP</th>
                            <th>Nomor OP</th>
                            <th>Tanggal PP</th>
                            <th>Supplier</th>
                            <th>Keterangan</th>
                            <th>Status Dokumen</th>                      
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        showCalendar('.date',null,0);
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

    function hapus(i_sj) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'i_sj'  : i_sj
                    },
                    url: '<?= base_url($folder.'/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/index/','#main');   
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dihapus :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }
</script>