<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-12">
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-4">Kode Group Barang</label>
                        <label class="col-md-8">Nama Group Barang</label>
                        <div class="col-sm-4">
                            <input type="text" name="igroupbrg" id="igroupbrg" class="form-control" placeholder="Kode Group Barang" autocomplete="off" required="Kolom wajib diisi" onkeyup="gede(this);" value="">
                            <span id="cek" hidden="true"> 
                                <font size="3" face="Courier New" color="red">Kode Sudah Ada!</font> 
                            </span>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="egroupname" id="egroupname" class="form-control" placeholder="Nama Group Barang" onkeyup="gede(this)" value="" required="Kolom wajib diisi">
                        </div>
                    </div>                                                                 
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>    
                        </div>
                    </div>
                    <div class="form-group">
                        <span style="color: #8B0000"><b>NOTE :</b></span><br>
                        <span style="color: #8B0000">* Standar Kode Group Barang terdiri dari 7 (tujuh) kombinasi huruf dan angka</span><br>
                        <span style="color: #8B0000">* Susunan huruf dapat diambil dari singkatan Nama Group Barang</span><br>
                        <span style="color: #8B0000">* Susunan angka dapat dikombinasikan antara angka 0 (nol) dengan nomor urutan terakhir pada Group Barang sebelumnya</span><br><br>
                        <span style="color: #8B0000"><b>* Contoh : GRB0001, GRB0002, GRB0003, dst</span>
                    </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
 $( "#igroupbrg" ).keyup(function() {
        var kode = $('#igroupbrg').val();;
            $.ajax({
                type: "post",
                data: {
                    'kode' : kode,
                },
                url: '<?= base_url($folder.'/cform/cekkode'); ?>',
                dataType: "json",
                success: function (data) {
                    if (data==1) {
                        $("#cek").attr("hidden", false);
                        $("#submit").attr("disabled", true);
                    }else{
                        $("#cek").attr("hidden", true);
                        $("#submit").attr("disabled", false);
                    }
                },
                error: function () {
                    swal('Error :)');
                }
            });
    });

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $( "#igroupbrg" ).focus();
    });
</script>
