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
                <div class="col-md-2">
                    <div class="form-group row">
                        <label class="col-md-8">Tanggal</label>
                        <div class="col-sm-8">
                            <input readonly name="dfrom" id="dfrom" class="form-control date required" value="<?= date('d-m-Y');?>" required="">
                        </div>
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="bulan" id="bulan" class="form-control" required="">
                                <?php if ($area) {
                                    foreach ($area as $key) {?>
                                        <option value="<?= $key->i_area;?>"><?= $key->e_area_name;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>
                    </div> -->
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
            placeholder: 'Pilih Area'
        });
    });
</script>