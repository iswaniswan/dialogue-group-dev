<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Username</label>
                        <div class="col-sm-12">
                            <input type="text" name="username" class="form-control" required="" maxlength="30" value="<?php echo $this->session->userdata('username') ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nama Pengguna</label>
                        <div class="col-sm-12">
                            <input type="text" name="ename" class="form-control" required="" maxlength="50" value="<?= $data->e_name; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Password Lama</label>
                        <div class="col-sm-12">
                            <input type="password" name="epasswordold" class="form-control" required="" maxlength="40" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Password Baru</label>
                        <div class="col-sm-12">
                            <input type="password" name="epasswordnew1" class="form-control" required="" maxlength="40" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Konfirmasi Password Baru</label>
                        <div class="col-sm-12">
                            <input type="password" name="epasswordnew2" class="form-control" required="" maxlength="40" value="" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});
 $(document).ready(function () {
    $(".select2").select2();
    showCalendar('.date');
 });
</script>
