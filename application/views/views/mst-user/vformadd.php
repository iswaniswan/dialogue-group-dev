<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?= $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                    <div id="pesan"></div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-4">*Username</label>
                            <label class="col-md-4">*Nama Karyawan / User</label>
                            <label class="col-md-4">*Password</label>
                            <div class="col-sm-4">
                                <input type="text" name="iuser" id="iuser" class="form-control input-sm" value="" onkeyup="clearcode(this);" autocomplete="off" maxlength="30">
                                <span class="notekode" hidden="true">* Username Sudah Ada!</span>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="eusername" class="form-control input-sm" onkeyup="clearname(this);" required="" value="" autocomplete="off" maxlength="50">
                            </div>
                            <div class="col-sm-4">
                                <input type="password" name="epass" class="form-control input-sm" autocomplete="off" value="" maxlength="50">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-offset-8 col-sm-12">
                                <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Departement</label>
                        <label class="col-md-6">Level</label>
                        <div class="col-sm-6">
                            <select name="idept" id="idept" class="form-control select2">
                                <option value="">Pilih Departement</option>
                                <?php foreach ($dept as $idept):?>
                                    <option value="<?php echo $idept->i_departement;?>"><?= $idept->e_departement_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                        <div class="col-sm-6">
                            <select name="ilevel" id="ilevel" class="form-control select2">
                                <option value="">Pilih Level</option>
                                <?php foreach ($level as $ilevel):?>
                                    <option value="<?php echo $ilevel->i_level;?>"><?= $ilevel->e_level_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                        <br>
                        <br>
                        <p style = 'margin-top:20px' align="justify"><font face="Courier New" color="red" size="3">*Note : Tanda * Wajib dipilih/diisi  </font></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select2").select2();
    });

    $( "#iuser" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });
</script>
