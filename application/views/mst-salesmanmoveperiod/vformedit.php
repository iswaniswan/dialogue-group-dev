<?php 
    $ab = array(
        "1Januari","2Februari","3Maret","4April","5Mei","6Juni","7Juli","8Agustus","9September","10Oktober","11November","12Desember",
    );
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/pindah'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="form-group row">
                    <label class="col-md-6">Periode Lama</label>
                    <label class="col-md-6">Periode Baru</label>
                    <div class="col-sm-4">
                        <select name="blama" id="blama" class="form-control select2">
                            <option value='01'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='01'){?> selected <?php } ?>>Januari</option>
                            <option value='02'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='02'){?> selected <?php } ?>>Pebruari</option>
                            <option value='03'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='03'){?> selected <?php } ?>>Maret</option>
                            <option value='04'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='04'){?> selected <?php } ?>>April</option>
                            <option value='05'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='05'){?> selected <?php } ?>>Mei</option>
                            <option value='06'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='06'){?> selected <?php } ?>>Juni</option>
                            <option value='07'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='07'){?> selected <?php } ?>>Juli</option>
                            <option value='08'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='08'){?> selected <?php } ?>>Agustus</option>
                            <option value='09'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='09'){?> selected <?php } ?>>September</option>
                            <option value='10'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='10'){?> selected <?php } ?>>Oktober</option>
                            <option value='11'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='11'){?> selected <?php } ?>>November</option>
                            <option value='12'<?php if(date('m', strtotime('-1 month', strtotime(date('m'))))=='12'){?> selected <?php } ?>>Desember</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control select2" name="tlama">
                            <?php for($x=2021; $x<=date('Y'); $x++){ ?>
                                <option value='<?= $x ?>' <?php if(date('Y', strtotime('-1 month', strtotime(date('m'))))==$x){?> selected <?php } ?>><?= $x ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="bbaru" id="bbaru" class="form-control select2">
                            <option value='01'<?php if(date('m')=='01'){?> selected <?php } ?>>Januari</option>
                            <option value='02'<?php if(date('m')=='02'){?> selected <?php } ?>>Pebruari</option>
                            <option value='03'<?php if(date('m')=='03'){?> selected <?php } ?>>Maret</option>
                            <option value='04'<?php if(date('m')=='04'){?> selected <?php } ?>>April</option>
                            <option value='05'<?php if(date('m')=='05'){?> selected <?php } ?>>Mei</option>
                            <option value='06'<?php if(date('m')=='06'){?> selected <?php } ?>>Juni</option>
                            <option value='07'<?php if(date('m')=='07'){?> selected <?php } ?>>Juli</option>
                            <option value='08'<?php if(date('m')=='08'){?> selected <?php } ?>>Agustus</option>
                            <option value='09'<?php if(date('m')=='09'){?> selected <?php } ?>>September</option>
                            <option value='10'<?php if(date('m')=='10'){?> selected <?php } ?>>Oktober</option>
                            <option value='11'<?php if(date('m')=='11'){?> selected <?php } ?>>November</option>
                            <option value='12'<?php if(date('m')=='12'){?> selected <?php } ?>>Desember</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control select2" name="tbaru">
                            <?php for($x=2021; $x<=date('Y'); $x++){ ?>
                                <option value='<?= $x ?>' <?php if(date('Y')==$x){?> selected <?php } ?>><?= $x ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-success btn-rounded btn-sm"> <i
                                class="ti-arrows-horizontal"></i>&nbsp;&nbsp;Pindah</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2();
    });
</script>