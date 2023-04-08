<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-lg fa-list mr-2"></i><?= $title; ?>
                <?php if (check_role($this->i_menu, 1)) { ?><a href="#" onclick="show('<?= $folder; ?>/cform/tambah/<?= $dfrom; ?>/<?= $dto; ?>','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-5">Date From</label><label class="col-md-5">Date To</label>
                        <div class="col-sm-5">
                            <input class="form-control input-sm date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control input-sm date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="submit" class="btn btn-info btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group pull-right row">
                        <!-- <label class="col-md-12">&nbsp;</label> -->
                        <div class="col-sm-12 mt-5 pull-right">
                            <button type="button" class="btn btn-rounded btn-primary btn-sm" onclick="show('<?= $folder; ?>/cform/index/<?= $dfrom . "/" . $dto; ?>','#main'); return false;"> <i class="fa fa-spin fa fa-refresh fa-lg mr-2"></i>Reload Page</button>
                        </div>
                    </div>
                </div>
            </form>
                <div class="table-responsive">
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Dokumen</th>
                                <th>Tgl. Dokumen</th>
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
</div>
<script>
    $(document).ready(function() {
        showCalendar2('.date', null, 0);
        datatablemod('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?= $dfrom . '/' . $dto; ?>');
    });

    $(document).ready(function() {
        var table = $('#tabledata').DataTable();
        table.buttons('.dt-buttons').remove();
    });

    $("#dfrom").change(function() {
        var dfrom = splitdate($(this).val());
        var dto = splitdate($('#dto').val());
        if (dfrom != null && dto != null) {
            if (dfrom > dto) {
                swal('Tanggal Mulai Tidak Boleh Lebih Besar Dari Tanggal Sampai!!!');
                $('#dfrom').val('');
            }
        }
    });

    $("#dto").change(function() {
        var dto = splitdate($(this).val());
        var dfrom = splitdate($('#dfrom').val());
        if (dfrom != null && dto != null) {
            if (dfrom > dto) {
                swal('Tanggal Sampai Tidak Boleh Lebih Kecil Dari Tanggal Mulai!!!');
                $('#dto').val('');
            }
        }
    });

    /*function hapus(iop) {
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
                        'iop'  : iop
                    },
                    url: '<?= base_url($folder . '/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder; ?>/cform/index/','#main');   
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dihapus :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }*/

    function printx(b, c, d) {
        var lebar = 1024;
        var tinggi = 768;
        eval('window.open("<?php echo site_url($folder); ?>"+"/cform/cetak/"+b+"/"+c+"/"+d,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top=' + (screen.height - tinggi) / 2 + ',left=' + (screen.width - lebar) / 2 + '")');
    }

    function printnonharga(b, c, d) {
        var lebar = 1024;
        var tinggi = 768;
        eval('window.open("<?php echo site_url($folder); ?>"+"/cform/cetaknonharga/"+b+"/"+c+"/"+d,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top=' + (screen.height - tinggi) / 2 + ',left=' + (screen.width - lebar) / 2 + '")');
    }

    function refreshview() {
        show('<?= $folder; ?>/cform/index/<?= $dfrom . '/' . $dto; ?>', '#main');
    }

    function cetak(id, dfrom, dto, ibagian) {
        var lebar = 1024;
        var tinggi = 768;
        eval('window.open("<?= site_url($folder); ?>"+"/cform/cetak/"+id+"/"+dfrom+"/"+dto+"/"+ibagian,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>