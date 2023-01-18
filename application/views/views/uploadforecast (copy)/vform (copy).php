<div class="panel panel-info">
    <div class="panel-heading">
        <i class="fa fa-upload"></i> &nbsp; <?= $title; ?> <a href="#"
        onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
        class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 ol-md-12 col-xs-12">
        <form id="submit">
            <div class="white-box">
                 <div class="form-group">
                        <label class="col-md-6">Distributor</label>
                        <div class="col-sm-6">
                            <select name="customer" id="customer" class="form-control select2">
                                <option value="">-- Pilih Distributor --</option>
                                <?php foreach ($distributor as $dis):?>
                                <option value="<?php echo $dis->i_customer;?>">
                                    <?= $dis->e_customer_name." - ".$dis->i_customer_code;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                </div>

                <div class="form-group row col-sm-6">
                        <div class="col-sm-3">
                            <label class="col-md-3">Bulan</label>
                            <select name="bulan" id="bulan" class="form-control select2">
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>

                        
                        <div class="col-sm-2">
                            <label class="col-md-2">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control select2">
                                <option value="2020" selected>2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                            </select>
                        </div>
                </div>

                <h3 class="box-title">Pilih File Yang Akan Diupload!</h3>
                <label for="input-file-now">Extention nya harus .xls</label>
                <input type="file" id="input-file-now" name="userfile" class="dropify" required=""/><br>
                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-upload"></i>&nbsp;&nbsp;Upload</button>
                <button type="button" class="btn btn-secondary btn-rounded btn-sm" onclick="download()"><i class="fa fa-download"></i>&nbsp; Download Template</button>
                <a href="<?php echo base_url();?>import/forecast/Template Forecast.xls" style="display:none;" id="master"></a>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#submit').submit(function(e){
            e.preventDefault(); 
            $.ajax({
                url: '<?= base_url($folder.'/cform/upload'); ?>',
                type:"post",
                data:new FormData(this),
                processData:false,
                contentType:false,
                cache:false,
                async:false,
                success: function(data){
                    if (data=='berhasil') {
                        swal({
                            title: "Berhasil!",
                            text: "File Berhasil Diupload :)",
                            type: "success",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        show('<?= $folderx;?>/cform/','#main');   
                    }else{
                        swal({
                            title: "Gagal!",
                            text: "File Gagal Diupload :)",
                            type: "error",
                            showConfirmButton: false,
                            timer: 1500
                        });                
                        /*show('<?= $folder;?>/cform/','#main'); */  
                    }
                }
            });
        });
        $('.dropify').dropify();
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function download(){
        document.getElementById('master').click();
    }
    
</script>