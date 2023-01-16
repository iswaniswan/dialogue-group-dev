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
                <div class="col-md-6">
                    <div class="form-group row">
                    <label class="col-md-12">Periode (Bulan / Tahun)</label>
                        <div class="col-sm-3">
                            <input type="hidden" id="iperiode" name="iperiode">
                            <select name="bulan" id="bulan" class="form-control select2" required="">
                            <option>--Pilih Bulan--</option>
							<option value='01'>Januari</option>
							<option value='02'>Pebruari</option>
							<option value='03'>Maret</option>
							<option value='04'>April</option>
							<option value='05'>Mei</option>
							<option value='06'>Juni</option>
							<option value='07'>Juli</option>
							<option value='08'>Agustus</option>
							<option value='09'>September</option>
							<option value='10'>Oktober</option>
							<option value='11'>November</option>
							<option value='12'>Desember</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                        <select name="tahun" id="tahun" class="form-control select2" required="">
                            <option>--Pilih Tahun--</option>
                            <?php 
                               $tahun1 = date('Y')-3;
                               $tahun2 = date('Y');
                               for($i=$tahun1;$i<=$tahun2;$i++)
                               {
                                  echo "<option value='$i'>$i</option>";
                               }
                            ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select style="display:none" name="itypemakloon" id="itypemakloon">
                                <option value="ALL" selected>Semua Makloon</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select style="display:none" name="isupplier" id="isupplier">
                                <option value="ALL" selected>Semua Supplier</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select style="display:none" name="inota" id="inota">
                                <option value="ALL" selected>Semua Faktur</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>
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
        $("#itypemakloon").on("click", function () {
            $.ajax({
                type: "POST",
                url: "<?= base_url($folder.'/cform/typemakloon'); ?>",
                dataType: 'json',
                delay: 250, 
                success: function(data) {
                    return {
                    results: data
                    };
                },
                 cache: true
            });
        });
    });
</script>