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
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-6">Area</label>
                    <label class="col-md-6">Periode</label>

                    <div class="col-sm-6">
                        <input type="hidden" id="id" name="id" value="<?php echo $data->id;?>">
                        <select name="iarea" id="iarea" class="form-control">
                            <option value="<?=$data->id_area;?>"><?=$data->e_area;?></option>
                        </select>
                    </div>

                    <div class="col-sm-4">
                        <input type="hidden" id="iperiode" name="iperiode" value="<?php echo $data->e_periode;?>">
                        <?php 
                            $thn = substr($periode,0,4);
                            $bln = substr($periode,4,2);
                        ?>
                        <select name="bulan" id="bulan" class="form-control select2">
                            <option value="01"<?php if($bln=='01') echo ' selected'; ?>>Januari</option>
                            <option value="02"<?php if($bln=='02') echo ' selected'; ?>>Pebruari</option>
                            <option value="03"<?php if($bln=='03') echo ' selected'; ?>>Maret</option>
                            <option value="04"<?php if($bln=='04') echo ' selected'; ?>>April</option>
                            <option value="05"<?php if($bln=='05') echo ' selected'; ?>>Mei</option>
                            <option value="06"<?php if($bln=='06') echo ' selected'; ?>>Juni</option>
                            <option value="07"<?php if($bln=='07') echo ' selected'; ?>>Juli</option>
                            <option value="08"<?php if($bln=='08') echo ' selected'; ?>>Agustus</option>
                            <option value="09"<?php if($bln=='09') echo ' selected'; ?>>September</option>
                            <option value="10"<?php if($bln=='10') echo ' selected'; ?>>Oktober</option>
                            <option value="11"<?php if($bln=='11') echo ' selected'; ?>>November</option>
                            <option value="12"<?php if($bln=='12') echo ' selected'; ?>>Desember</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <select name="tahun" id="tahun" class="form-control select2">
                                <?php 
                                    echo "<option value='$thn'>$thn</option>";
                                ?>
                                <?php 
                                    $tahun1 = date('Y')-3;
                                    $tahun2 = date('Y');
                                    for($i=$tahun1;$i<=$tahun2;$i++)
                                    {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-12">Salesman</label>                        
                    <div class="col-sm-12">
                        <select name="id_salesman" id="id_salesman" class="form-control" readonly>
                            <option value="<?=$data->id_salesman;?>"><?=$data->e_sales;?></option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group row mt-5">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-blcok btn-sm" onclick="return validasi();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                        <button type="button" class="btn btn-inverse btn-blcok btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
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

<div class="white-box mt-4" id="detail">    
    <div class="row">
        <div class="col-sm-6">
            <h3 class="box-title m-b-0 ml-1">Daftar Customer</h3>
        </div>
    </div>
    <div class="table-responsive">
        <table id="sitabel" class="table color-table success-table table-bordered class" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center" width="35px">No</th>
                    <th width="auto">Kode</th>
                    <th width="auto">Customer</th>
                    <th width="auto">Area</th>
                    <th width="auto">Kota</th>
                    <th width="auto">Alamat</th>
                    <th class="text-center" width="35px">Act</th>
                </tr>
            </thead>     
            <tbody>                
                <?php $number = 1; foreach ($detail->result() as $row) { ?>
                <tr>
                    <td class="table-index"><?= $number ?></td>
                    <td><span><?= $row->i_customer ?></span></td>
                    <td>
                        <input type="hidden" name="items[<?= $number ?>][id_customer]" value="<?= $row->id_customer ?>">
                        <span><?= $row->e_customer_name ?></span>
                    </td>
                    <td><span><?= $row->e_area ?></span></td>
                    <td><span><?= $row->e_city_name ?></span></td>
                    <td><span><?= $row->e_customer_address ?></span></td>
                    <td class="text-center">
                        <button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger">
                            <i class="ti-close"></i>
                        </button>
                    </td>
                </tr>
                <?php $number++; } ?>                
            </tbody>
        </table>
    </div>
</div>
</from>

<script>
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
    
    function validasi() {
        if ($('#iarea').val() == '' || $('#iarea').val() == null && $('#icustomer').val() == '' || $('#icustomer').val() == null && $('#id_salesman').val() == '' || $('#id_salesman').val() == null && $('#ibrand').val() == '' || $('#ibrand').val() == null && $('#bulan').val() == '' || $('#bulan').val() == null && $('#tahun').val() == '' || $('#tahun').val() == null) {
            swal("Data Masih Belum Lengkap");
            return false;
        } else{ 
            return true;
        }
    }

    function export_data() {
        const id_salesman = $('#id_salesman').val();
        $('#href').attr('href', '<?php echo site_url($folder . '/cform/export?id_salesman='); ?>' + id_salesman);
    }

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

        // $('#id_salesman').select2({
        //     allowClear: false,
        //     ajax: {
        //     url: '<?= base_url($folder.'/cform/salesman'); ?>',
        //     dataType: 'json',
        //     delay: 250,          
        //     processResults: function (data) {
        //         return {
        //         results: data
        //         };
        //     },
        //     cache: true
        //     }
        // });

        $('#ibrand').select2({
            placeholder: 'Pilih Brand',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/brand'); ?>',
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
                                    <th width="auto">Customer</th>
                                    <th class="text-center" width="35px">Act</th>
                                </tr>
                            </thead>`;
            let rows;      
            let number = 1;
            for(let row of params) {
                rows += `<tr>
                            <td class="table-index">${number}</td>
                            <td>
                                <input type="hidden" name="items[${number}][id_customer]" value="${row?.id_customer}">
                                <span>${row?.e_customer_name}</span>
                            </td>
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
        }

    });

</script>