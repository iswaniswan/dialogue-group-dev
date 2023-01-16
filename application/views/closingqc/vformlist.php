<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?></div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-8">
                    <div class="form-group row">
                        <label class="col-md-3">Bagian</label><label class="col-md-3">Bulan</label><label class="col-md-6">Tahun</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" id="bulan" name="bulan">
                                <?php
                                $bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","Nopember","Desember");
                                $jlh_bln=count($bulan);
                                for($c=0; $c<$jlh_bln; $c+=1){
                                    $sel = "";
                                    $i = $c+1;
                                    if ($i<=9){ $i = '0'.$i; }
                                    if ($i == date('m')) $sel = "selected";
                                    echo "<option value=$i $sel> $bulan[$c] </option>";
                                }?>
                            </select> 
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control select2" name="tahun" id="tahun"></select>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-info btn-sm" onclick="return closing($('#bulan').val(), $('#tahun').val(), $('#ibagian').val())"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Update Saldo</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <span class="notekode"><b>Note : </b></span><br>
                        <span class="notekode">* Bulan yang dipilih, sama dengan bulan yang akan diclosing untuk saldo awal bulan selanjutnya..</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            width : '100%',
        });
    });

    $(document).ready(function () {
        var min = new Date().getFullYear()-1,
        max = min + 2,
        select = document.getElementById('tahun');

        for (var i = min; i<=max; i++){
            var opt = document.createElement('option');
            if (i == new Date().getFullYear()) {opt.selected = true;}
            opt.value = i;
            opt.innerHTML = i;
            select.appendChild(opt);
        }
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#unclosing").attr("disabled", true);
    });

    function closing(bulan,tahun,ibagian) {
        swal({   
            title: "Apakah anda yakin?",   
            text: "Update Saldo Awal QC Bulan "+bulan+" Tahun "+tahun,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, Update Saldo Awal!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "POST",
                    data: {
                        'bulan' : bulan,
                        'tahun' : tahun,
                        'ibagian': ibagian,
                    },
                    url: '<?= base_url($folder.'/cform/closing'); ?>',
                    dataType: "json",
                    success: function (data) {
                        if(data == 1) {
                            swal("Update Saldo Awal QC!", "Data berhasil di update :)", "success");
                            show('<?= $folder;?>/cform/index','#main');    
                        }
                        
                    },
                    error: function () {
                        swal("Maaf", "Data gagal di update :(", "error");
                    }
                });
            }else {     
                swal("Dibatalkan", "Anda membatalkan update saldo awal :)", "error");
                return false;
            } 
        });
    }
</script>