<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label>
                        <label class="col-md-3">Date To</label>
                        <label class="col-md-3">Kategori Barang</label>
                        <label class="col-md-3">Jenis Barang</label>     
                        <div class="col-sm-3">
                            <input type="text" id="dfrom" name="dfrom" class="form-control date"  readonly value="">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dto" name="dto" class="form-control date"  readonly value="">
                        </div>
                        <div class="col-sm-3">
                            <select id="kategori" name="kategori" class="form-control select2" onchange="getjenisbarang(this.value);">
                                <option value="">--Pilih Kategori Barang--</option>
                                <option value="ALL">Semua Kategori Barang</option>
                                <?php foreach ($kategori as $key):?>
                                    <option value="<?php echo $key->i_kode_kelompok;?>"><?=$key->i_kode_kelompok.' - '.$key->e_nama;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select id="jenis" name="jenis" class="form-control select2" disabled>
                                <option value="">--Pilih Jenis Barang--</option>
                                <option value="ALL">Semua Jenis Barang</option>
                            </select>
                        </div>
                        <div style="display:none;" class="col-sm-4">
                            <select id="produk" name="produk" class="form-control select2" hidden></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="validasi();"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function getjenisbarang(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getjenisbarang');?>",
            data: "ikategori=" + id,
            dataType: 'json',
            success: function (data) {
                $("#jenis").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#jenis").attr("disabled", false);
                } else {
                    $("#jenis").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }
        });
    } 

    function validasi() {
        var dfrom        = $('#dfrom').val();
        var dto          = $('#dto').val();
        var ikategori    = $('#kategori').val();
        var ijenis       = $('#jenis').val();

        if ( (dfrom == '' && dto == '') || ikategori == '' || ijenis == '') {
            swal('Data header Belum Lengkap');
            show('<?= $folder;?>/cform/','#main'); 
        }
    }
</script>