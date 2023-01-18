<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-3">
                    <div class="form-group row">
                    <label class="col-md-12">Periode</label>
                        <div class="col-sm-7">
                            <select name="bulan" id="bulan" class="form-control" required="">
                                <option value="">-- Pilih Bulan --</option>
                                <option value='01' <?php if (date('m')=='01') { echo "selected";}?>>Januari</option>
                                <option value='02' <?php if (date('m')=='02') { echo "selected";}?>>Pebruari</option>
                                <option value='03' <?php if (date('m')=='03') { echo "selected";}?>>Maret</option>
                                <option value='04' <?php if (date('m')=='04') { echo "selected";}?>>April</option>
                                <option value='05' <?php if (date('m')=='05') { echo "selected";}?>>Mei</option>
                                <option value='06' <?php if (date('m')=='06') { echo "selected";}?>>Juni</option>
                                <option value='07' <?php if (date('m')=='07') { echo "selected";}?>>Juli</option>
                                <option value='08' <?php if (date('m')=='08') { echo "selected";}?>>Agustus</option>
                                <option value='09' <?php if (date('m')=='09') { echo "selected";}?>>September</option>
                                <option value='10' <?php if (date('m')=='10') { echo "selected";}?>>Oktober</option>
                                <option value='11' <?php if (date('m')=='11') { echo "selected";}?>>November</option>
                                <option value='12' <?php if (date('m')=='12') { echo "selected";}?>>Desember</option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select name="tahun" id="tahun" class="form-control" required="">
                                <option value="">-- Pilih Tahun --</option>
                                <?php 
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                    <option value="<?= $i;?>" <?php if ($i==$tahun2) { echo "selected";}?>><?= $i;?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-table"></i>&nbsp;&nbsp;View</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function () {
        showCalendar('.date');
        $('#bulan').select2({
            placeholder: 'Pilih Bulan'
        });
        $('#tahun').select2({
            placeholder: 'Pilih Tahun'
        });
    });
</script>