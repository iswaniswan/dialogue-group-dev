<style>
    .form-group {
        margin-bottom: 10px !important;
    }

    .table>thead>tr>th {
        padding: 6px 6px;
    }

    .dropify-wrapper {
        height: 174px !important;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i>  <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-4">Area</label>
                    <label class="col-md-4">Salesman</label>
                    <!-- <label class="col-md-4">Brand</label> -->
                    <label class="col-md-4">Periode</label>
                    <div class="col-sm-4">
                        <select name="iarea" id="iarea" class="form-control" required>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="id_salesman" id="id_salesman" class="form-control" required>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="bulan" id="bulan" class="form-control select2" required>
                            <option value='01' <?php if(date('m')=='01'){?> selected <?php } ?>>Januari</option>
                            <option value='02' <?php if(date('m')=='02'){?> selected <?php } ?>>Pebruari</option>
                            <option value='03' <?php if(date('m')=='03'){?> selected <?php } ?>>Maret</option>
                            <option value='04' <?php if(date('m')=='04'){?> selected <?php } ?>>April</option>
                            <option value='05' <?php if(date('m')=='05'){?> selected <?php } ?>>Mei</option>
                            <option value='06' <?php if(date('m')=='06'){?> selected <?php } ?>>Juni</option>
                            <option value='07' <?php if(date('m')=='07'){?> selected <?php } ?>>Juli</option>
                            <option value='08' <?php if(date('m')=='08'){?> selected <?php } ?>>Agustus</option>
                            <option value='09' <?php if(date('m')=='09'){?> selected <?php } ?>>September</option>
                            <option value='10' <?php if(date('m')=='10'){?> selected <?php } ?>>Oktober</option>
                            <option value='11' <?php if(date('m')=='11'){?> selected <?php } ?>>November</option>
                            <option value='12' <?php if(date('m')=='12'){?> selected <?php } ?>>Desember</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <select name="tahun" id="tahun" class="form-control select2" required>
                                <?php 
                                    $tahun1 = date('Y')-3;
                                    $tahun2 = date('Y');
                                    for($i=$tahun1;$i<=$tahun2;$i++){?>
                                        <option value='<?= $i;?>' <?php if($i==$tahun2){?> selected <?php } ?>><?= $i;?></option>
                                    <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Customer</label>
                    <!-- <label class="col-md-4">Periode</label> -->
                    <div class="col-sm-12">
                        <select name="icustomer[]" multiple id="icustomer" class="form-control">
                        </select>
                    </div>
                </div>
                <div class="form-group mt-5">
                    <div class="col-md-6">
                        <button type="submit" id="submit" class="btn btn-success btn-block btn-sm mr-2" onclick="return validasi();"> <i class="fa fa-save mr-2"></i>Simpan</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-inverse btn-block btn-sm mr-2" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left mr-2"></i>Kembali</button>
                    </div>
                </div>                
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                Upload Customer Salesman
            </div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-6">Upload File (Optional)</label>
                        <label class="col-md-6 text-right notekode">Formatnya .xls</label>
                        <div class="col-sm-12">
                            <input type="file" id="input-file-now" name="userfile" class="dropify" style="height: auto;"/>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" id="upload" class="btn btn-success btn-block btn-sm"><i class="fa fa-upload mr-1 mr-2"></i>Upload</button>
                        </div>
                        <div class="col-md-6">
                            <a id="href" onclick="return export_data();"><button type="button" class="btn btn-primary btn-block btn-sm"><i class="fa fa-download mr-2"></i>Download Template</button> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="white-box mt-4 d-none" id="detail">    
    <div class="table-responsive">
        <table id="sitabel" class="table color-table success-table table-bordered class" cellspacing="0" width="100%">   
        </table>
    </div>
</div>
</form>

<script>
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();

        $('#iarea').select2({
            placeholder: 'Pilih Area',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/area'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
            }
        });

        $('#icustomer').select2({
            placeholder: 'Pilih Customer',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/customer'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
            }
        });

        $('#id_salesman').select2({
            placeholder: 'Pilih Salesman',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/salesman'); ?>',
            dataType: 'json',
            delay: 250,          
            processResults: function (data) {
                return {
                results: data
                };
            },
            cache: true
            }
        });

        $('.dropify').dropify();

        $("#upload").on("click", function() {       
            const id_salesman = $('#id_salesman').val();

            if (id_salesman == null || id_salesman == '') {
                swal("Sales belum dipilih");
                return;
            }
            

            var formData = new FormData();
            formData.append('userfile', $('input[type=file]')[0].files[0]);
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder . '/cform/load_view'); ?>",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                success: function(result) {
                    var json = JSON.parse(result);
                    if (json.status == 'berhasil') {
                        createShowTableCustomer(json.data);
                    } else {
                        swal({
                            title: "Gagal!",
                            text: "File Gagal Diupload :)",
                            type: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
            });            
        });

        function reindexNumber () {
            $('.table-index').each(function(index) {
                $(this).html(index+1);
            });
        }

        function initButtonDelete() {
            $('.ibtnDel').each(function() {
                $(this).on('click', function() {
                    $(this).closest('tr').remove();
                    reindexNumber();
                })
            })
        }

        reindexNumber();
        initButtonDelete();

        function createShowTableCustomer(params) {
            const thead = `<thead>
                                <tr>
                                    <th class="text-center" width="35px">No</th>
                                    <th width="auto">Kode</th>
                                    <th width="auto">Customer</th>
                                    <th width="auto">Area</th>
                                    <th width="auto">Kota</th>
                                    <th width="auto">Alamat</th>
                                    <th class="text-center" width="35px">Act</th>
                                </tr>
                            </thead>`;
            let rows;      
            let number = 1;
            for(let row of params) {
                rows += `<tr>
                            <td class="table-index">${number}</td>
                            <td><span>${row?.i_customer}</span></td>
                            <td>
                                <input type="hidden" name="items[${number}][id_customer]" value="${row?.id_customer}">
                                <span>${row?.e_customer_name}</span>
                            </td>
                            <td><span>${row?.e_area}</span></td>
                            <td><span>${row?.e_city_name}</span></td>
                            <td><span>${row?.e_customer_address}</span></td>
                            <td class="text-center">
                                <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger">
                                    <i class="ti-close"></i>
                                </button>
                            </td>
                        </tr>`;
                number++;
            }

            const tbody = `<tbody>${rows}</tbody>`;
            const table = `${thead} ${tbody}`;

            $('#detail').removeClass('d-none');
            $('#sitabel').empty()
                .append(table);

            reindexNumber();
            initButtonDelete();
            /** clear selected customer */
            $('#icustomer').select2().val('').trigger('change');
        }
    });

    function validasi() {
        if ($('#iarea').val() == '' || $('#iarea').val() == null && 
                $('#id_customer').val() == '' || $('#id_customer').val() == null &&
                $('#id_salesman').val() == '' || $('#id_salesman').val() == null && 
                $('#bulan').val() == '' || $('#bulan').val() == null && 
                $('#tahun').val() == '' || $('#tahun').val() == null) {
            swal("Data Masih Belum Lengkap");
            return false;
        } 
        return true;
    }

    function export_data() {
        const tahun = $('#tahun').val();
        const bulan = $('#bulan').val();
        const e_periode = tahun.toString() + bulan.toString();
        const id_salesman = $('#id_salesman').val();

        if (id_salesman == null || id_salesman == '') {
            swal("Sales belum dipilih");
            return;
        }
        $('#href').attr('href', '<?php echo site_url($folder . '/cform/export?id_salesman='); ?>' + id_salesman + '&e_periode=' + e_periode);
    }
    
</script>