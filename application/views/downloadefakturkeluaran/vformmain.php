<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i ></i> <?= $title_list; ?>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-8">
                    <div class="form-group row">
                        <label class="col-md-2">Dari Tanggal</label><label class="col-md-2">Sampai Tanggal</label><label class="col-md-8">Pelanggan</label>
                        <div class="col-sm-2">
                              <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>" >
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>" >
                        </div>
                        <div class="col-sm-6">
                            <select name="partner" id="partner" class="form-control select2">
                                <option value="semua" selected> Semua Pelanggan</option>
                            </select>
                        </div>
                         <div class="col-sm-2">
                             <a id="href" onclick="return validasi();"><button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-download"></i>&nbsp;&nbsp;Download</button></a>
                        </div>
                    </div>
                </div>
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

    
    function validasi() {
        var dfrom = $('#dfrom').val();
        var dto = $('#dto').val();
        var partner = $('#partner').val();
        //alert(gudang + dso);
        if ( dto == '') {
            swal('Data header Belum Lengkap');
            return false;
        } else {
            $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+dfrom+"/"+dto+"/"+partner);
            return true;
        }
    }
</script>