<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i ></i> <?= $title_list; ?>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Dari Tanggal</label><label class="col-md-4">Sampai</label><label class="col-md-4">Partner Jahit</label>
                        <div class="col-sm-4">
                            <!-- <select name="pbulan" id="pbulan" class="form-control select2" onchange="gantidata()">
                                <?php //foreach (getBulan() as $key => $value) { ?>
                                 <option value="<?php //echo $key;?>" 
                                    <?php //if ($key == $bnow){ ?> selected <?php //} ?> > <?= $value;?>
                                <?php //} ?>
                            </select> -->
                            <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>" onchange="gantidata();">
                        </div>
                        <div class="col-sm-4">
                          <!-- <select name="ptahun" id="ptahun" class="form-control select2" onchange="gantidata()">
                                <?php //foreach (getTahun() as $value) { ?>
                                 <option value="<?php //echo $value;?>" 
                                    <?php //if ($value == $tnow){ ?> selected <?php //} ?> > <?= $value;?>
                                <?php //} ?>
                            </select> -->
                              <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>" onchange="gantidata();">
                        </div>
                        <div class="col-sm-4">
                            <select name="partner" id="partner" class="form-control select2" onchange="gantidata();">
                                <?php foreach ($partner as $ipartner):?>
                                <option value="<?php echo $ipartner->i_unit_jahit;?>"> <?= $ipartner->e_unitjahit_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                      
                    </div>
                </div>
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Warna</th>
                                <th>Saldo <br> Awal</th>
                                <th>SJ Masuk</th>
                                <th>SJ Masuk <br> Retur</th>
                                <th>SJ Keluar</th>
                                <th>SJ Keluar <br> Retur</th>
                                <th>Saldo <br> Akhir</th>
                               <!--  <th>GIT</th> -->
                                <th>SO</th>
                                <th>Selisih</th> 
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
        $('.select2').select2();
        showCalendar('.date');
    });

    $(document).ready(function () {
        $('#tabledata').DataTable().clear().destroy();
        var partner = $('#partner').val();
        var groupColumn = 2;
        var table = $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            // "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto."/"; ?>"+partner,
                "type": "POST"
            },
            //"displayLength": 10,
            "paging" : false,
        });
    });

    function hapus(i_bonk) {
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
                        'i_bonk'  : i_bonk
                    },
                    url: '<?= base_url($folder.'/cform/delete'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/','#main');   
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

    function gantidata() {
       $('#tabledata').DataTable().clear().destroy();

        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        var partner = $('#partner').val();
        $('#tabledata').DataTable({
            serverSide: true,
            processing: true,
            // "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "ajax": {
                "url": "<?= site_url($folder); ?>/Cform/data/"+dfrom+"/"+dto+"/"+partner,
                "type": "POST"
            },
            //"displayLength": 10,
            "paging" : false,
        });
     } 
</script>