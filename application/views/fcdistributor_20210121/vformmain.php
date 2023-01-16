
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/tambah'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="white-box">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-sm-4">Bagian Pembuat</label>
                        <div class="col-sm-6">
                             <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>"><?= $row->e_bagian_name;?></option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>          

                    <div class="form-group">
                        <label class="col-sm-3">Customer</label>
                        <div class="col-sm-6">
                             <select name="idcustomer" id="idcustomer" class="form-control select2" required="">
                                <?php if ($customer) {
                                    foreach ($customer as $row):?>
                                        <option value="<?= $row->id;?>">
                                            <?= $row->e_customer_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>        

                     <div class="form-group row">
                            <label class="col-md-4">Bulan</label><label class="col-md-8">Tahun</label>
                            <div class="col-sm-4">
                                <select class="form-control select2" id="bulan" name="bulan">
                                    <?php
                                    $bulan=array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                                   
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
                    </div>  
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">  
                            <!-- <a id="href" onclick="return validasi();"><button type="button" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</button> </a> -->
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-search"></i>&nbsp;&nbsp;Proses</button>
                            <!-- <button type="button" id="approve" class="btn btn-success btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/approval/"+$("#dso").val(),"#main")' ><i class="fa fa-check"></i>&nbsp;&nbsp;Approve</button>   -->
                           <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button> 
                        </div>
                    </div>
                </div>
                <div class="col-md-6" >
                    <div class="form-group" hidden>
                        <label class="col-md-6">Barang</label>
                        <div class="col-sm-6">
                            <select name="ikodebarang" id="ikodebarang" class="form-control select2">
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-6">Upload File (Optional)</label>
                        <label for="input-file-now">Extention nya harus .xls</label>
                        <input type="file" id="input-file-now" name="userfile" class="dropify" /><br>
                        <button type="button" id="upload" class="btn btn-success btn-rounded btn-sm" onclick="return validasi();"><i class="fa fa-upload mr-1"></i>&nbsp;&nbsp;Upload</button>
                        <a id="href" onclick="return validasi();"><button type="button" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</button> </a>
                    </div>
                </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

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
function validasi() {
    var ibagian = $('#ibagian').val();
    var bulan = $('#bulan').val();
    var tahun = $('#tahun').val();
    //alert(gudang + dso);
    if ( ibagian == '') {
        swal('Data header Belum Lengkap');
        return false;
    } else {
        $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+bulan+'/'+tahun);
        return true;
    }
}
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');

    $("#upload").on("click", function () {
           
        var ibagian    = $('#ibagian').val();
        var idcustomer = $('#idcustomer').val();
        var bulan      = $('#bulan').val();
        var tahun      = $('#tahun').val();

        var formData = new FormData();
        formData.append('userfile', $('input[type=file]')[0].files[0]);
        formData.append('ibagian',ibagian);
        formData.append('idcustomer',idcustomer);
        formData.append('bulan',bulan);
        formData.append('tahun',tahun);
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/load'); ?>",
            data:formData,
            processData:false,
            contentType:false,
            cache:false,
            async:false,
            success: function(data){
                //console.log($('input[type=file]')[0].files[0]);
                var json = JSON.parse(data);
                var status = json.status;
                if (status=='berhasil') {
                    var idcustomer = json.idcustomer;
                    var tahun = json.tahun;
                    var bulan = json.bulan;
                    var ibagian = json.ibagian;
                    // swal({
                    //     title: "Berhasil!",
                    //     text: "File Berhasil Diupload :)",
                    //     type: "success",
                    //     showConfirmButton: false,
                    //     timer: 1500
                    // });
                    show('<?= $folder;?>/cform/loadview/'+idcustomer+'/'+tahun+'/'+bulan+'/'+ibagian,'#main');   
                }else{
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

    $('.dropify').dropify();
});

function getbarang(ikodemaster) {
    $("#approve").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: "<?php echo site_url($folder.'/Cform/getbarang');?>",
        data:"ikodemaster="+ikodemaster,
        dataType: 'json',
        success: function(data){
            $("#ikodebarang").html(data.kop);
            if (data.kosong=='kopong') {
                $("#submit").attr("disabled", true);
 
            }else{
                $("#submit").attr("disabled", false);
            }
        },

        error:function(XMLHttpRequest){
            alert(XMLHttpRequest.responseText);
        }
    })
}

</script>