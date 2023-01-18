<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i ></i> <?= $title_list; ?>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-8">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Opname</label><label class="col-md-5">Jenis Faktur</label><label class="col-md-4">Partner</label>
                        <div class="col-sm-3">
                              <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>" onchange="gantidata();">
                        </div>
                        <div class="col-sm-5">
                            <select name="jenis" id="jenis" class="form-control select2" onchange="gantidata();">
                                <option value="semua" selected> Semua Faktur</option>
                                <option value="JNM0002"> Faktur Jasa Makloon Bis Bisan</option>
                                <option value="JNM0006"> Faktur Jasa Makloon Jahit </option>
                                <option value="JNM0007"> Faktur Jasa Makloon Packing </option>
                                <option value="KTG0001"> Faktur Pembelian </option>
                            </select>
                           
                        </div>
                        <div class="col-sm-4">
                            <select name="partner" id="partner" class="form-control select2" onchange="gantidata();">
                                <option value="semua" selected> Semua Partner</option>
                            </select>
                        </div>
                      
                    </div>
                </div>
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Supplier</th>
                                <th>Alamat</th>
                                <th>TOP</th>
                                <th>Jenis Faktur</th>
                                <th>Nota</th>
                                <th>Tgl Nota</th>
                                <th>Jatuh Tempo</th>
                                <th>Jumlah Saldo</th>
                                <th>Umur</th>
                                <th>Telat</th>
                                <th>Status</th>
                                <th>Keterangan</th>
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
        $('#partner').select2({
            placeholder: 'Pilih Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/partner'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        jenis : $('#jenis').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
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
        var jenis = $('#jenis').val();
        var partner = $('#partner').val();
        $('#tabledata').DataTable().clear().destroy();
        if (jenis!= "" && partner != "") {
            var dto = $('#dto').val();
            $('#tabledata').DataTable({
                serverSide: true,
                processing: true,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "ajax": {
                    "url": "<?= site_url($folder); ?>/Cform/data/"+dto+"/"+jenis+"/"+partner,
                    "type": "POST"
                },
                "displayLength": 10,
                //"paging" : false,
            });
        }
        
        
    }

     function partner(partner) {
        if (partner == "") {
            $("#partner").attr("disabled", true);
        } else {
            $("#partner").attr("disabled", false);
        }
        $("#partner").val("");
        $("#partner").html("");
    }

</script>