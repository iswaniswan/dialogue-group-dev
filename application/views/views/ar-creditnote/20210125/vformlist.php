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
                            <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>" onchange="gantidata();">
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>" onchange="gantidata();">
                        </div>
                        
                    </div>
            </div>
        </form>
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Nomor Kredit Nota</th>
                    <th>Tanggal KN</th>
                    <th>Customer</th>
                    <th>Nomor Refferensi</th>
                    <th>Gross</th>
                    <th>Discount</th>
                    <th>Netto</th>
                    <th>DPP</th>
                    <th>PPN</th>
                    <th>Total</th>
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

<script type="text/javascript">

     $(document).ready(function () {
        showCalendar('.date',1830,0);
        $('#tabledata').DataTable().clear().destroy();

        var groupColumn = 2;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto; ?>",
                "type": "POST"
            },
            "displayLength": 10,
        });
    });

    function gantidata() {
       $('#tabledata').DataTable().clear().destroy();

        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/"+dfrom+"/"+dto,
                "type": "POST"
            },
            "displayLength": 10,
        });
    } 

    function cancel(i_nota,partner) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!" ,   
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
                    type: "POST",
                    data: {
                        'i_nota' : i_nota,
                        'partner': partner
                    },
                    url: '<?= base_url($folder.'/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform','#main');     
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

    function batalkirim(ibonk,ibagian,istatus) {
        swal({   
            title: "Tarik Draft Dari Atasan?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, Tarik!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'ibonk'  : ibonk,
                        'istatus'  : istatus,
                        'ibagian'  : ibagian,
                    },
                    url: '<?= base_url($folder.'/cform/updatestatus'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Ditarik!", "Draft berhasil Ditarik Kemabali :)", "success");
                        show('<?= $folder;?>/cform/index/<?= $dfrom.'/'.$dto;?>','#main');
                    },
                    error: function () {
                        swal("Maaf", "Draft gagal ditarik :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penarikan :)", "error");
            } 
        });
    }

    function printx(b,c){
        var lebar =1024;
        var tinggi=768;
        eval('window.open("<?php echo site_url($folder); ?>"+"/cform/cetak/"+b+"/"+c,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,menubar=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
    }
</script>