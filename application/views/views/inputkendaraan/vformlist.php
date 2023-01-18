<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> 
                <?= $title; ?>                
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="form-group">
                    <label class="col-md-12">Plat Nomor</label>
                    <div class="col-md-12">
                        <input type="text" name="ikendaraan" maxlength="9" class="form-control" onkeyup="gede(this)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Periode</label>
                    <div class="col-sm-12">
                        <select class="form-control select2" name="iperiodea">
                            <option value='01'>01</option>
                            <option value='02'>02</option>
                            <option value='03'>03</option>
                            <option value='04'>04</option>
                            <option value='05'>05</option>
                            <option value='06'>06</option>
                            <option value='07'>07</option>
                            <option value='08'>08</option>
                            <option value='09'>09</option>
                            <option value='10'>10</option>
                            <option value='11'>11</option>
                            <option value='12'>12</option>
                        </select>
                        <input type="number" name="iperiodeb" maxlength="4" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Area</label>
                    <div class="col-md-12">
                        <select class="form-control select2" name="iarea">
                            <?php foreach($area as $r){ ?>
                                <option value='<?= $r->i_area ?>'><?= $r->i_area.' - '.$r->e_area_name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Jenis Kendaraan</label>
                    <div class="col-md-12">
                        <select class="form-control select2" name="ikendaraanjenis">
                            <?php foreach($jeniskendaraan as $r){ ?>
                                <option value='<?= $r->i_kendaraan_jenis ?>'><?= $r->i_kendaraan_jenis.' - '.$r->e_kendaraan_jenis ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Jenis BBM</label>
                    <div class="col-md-12">
                        <select class="form-control select2" name="ikendaraanbbm">
                            <?php foreach($jenisbbm as $r){ ?>
                                <option value='<?= $r->i_kendaraan_bbm ?>'><?= $r->i_kendaraan_bbm.' - '.$r->e_kendaraan_bbm ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Pengguna</label>
                    <div class="col-md-12">
                        <input type="text" name="epengguna" maxlength="30" class="form-control" onkeyup="gede(this)">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Tanggal Pajak</label>
                    <div class="col-md-12">
                        <input type="date" name="dpajak" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                class="fa fa-eye"></i>&nbsp;&nbsp;Simpan</button>
                    </div>
                </div>                
                </form>
            </div>
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
        showCalendar2('.date');
    });
</script>