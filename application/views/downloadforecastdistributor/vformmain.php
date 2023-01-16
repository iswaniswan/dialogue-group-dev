<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i ></i> <?= $title_list; ?>
            </div>
            <div class="panel-body table-responsive">
                <div class="col-md-8">
                    <div class="form-group row">
                        <label class="col-md-2">Bulan</label><label class="col-md-10">Tahun</label>
                        <div class="col-sm-2">
                        <select name="bulan" id="bulan" class="form-control select2">
                                <option> Pilih Bulan</option>
                                <option value="01"> Januari</option>
                                <option value="02"> Februari</option>
                                <option value="03"> Maret</option>
                                <option value="04"> April</option>
                                <option value="05"> Mei</option>
                                <option value="06"> Juni</option>
                                <option value="07"> Juli</option>
                                <option value="08"> Agustus</option>
                                <option value="09"> September</option>
                                <option value="10"> Oktober</option>
                                <option value="11"> November</option>
                                <option value="12"> Desember</option>
                            </select>
                                
                        </div>
                        <div class="col-sm-2">
                            <input class="form-control input-sm date" readonly="" type="text" name="tahun" id="tahun"
                                >
                        </div>
                        <!-- <div class="col-sm-4">
                            <select name="partner" id="partner" class="form-control select2">
                                <option value="semua" selected> Semua Pelanggan</option>
                            </select>
                        </div> -->
                         <div class="col-sm-4">
                         <a id="href"><button type="button" class="btn btn-info"> <i class="fa fa-download"></i>&nbsp;&nbsp;Download</button></a>
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

        $('#tahun').datepicker({
        format: "yyyy",
        viewMode: "years", 
        minViewMode: "years",
        autoclose:true
        });
    });

    $('#href').click(function(event) {
        /* Act on the event */
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        if (bulan != '' && tahun != '') {
            $('#href').attr('href','<?= site_url($folder.'/cform/export_excel/');?>'+bulan+'/'+tahun);
        }else{
            swal('Maaf :(','Tanggal dan Supplier tidak boleh kosong!','error');
            return false;
        }
    });

    $(document).ready(function () {
        $('#partner').select2(
        //     {
        //     placeholder: 'Pilih Partner',
        //     allowClear: true,
        //     ajax: {
        //         url: '<?= base_url($folder.'/cform/partner'); ?>',
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             var query = {
        //                 q: params.term,
        //                 jenis : $('#jenis').val(),
        //             }
        //             return query;
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: data
        //             };
        //         },
        //         cache: false
        //     }
        // }
        );
    });

    
    // function validasi() {
    //     var dfrom = $('#dfrom').val();
    //     var dto = $('#dto').val();
    //     var partner = $('#partner').val();
    //     //alert(gudang + dso);
    //     if ( dto == '') {
    //         swal('Data header Belum Lengkap');
    //         return false;
    //     } else {
    //         $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+dfrom+"/"+dto+"/"+partner);
    //         return true;
    //     }
    // }
</script>