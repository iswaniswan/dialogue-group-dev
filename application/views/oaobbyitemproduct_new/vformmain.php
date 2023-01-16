<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-bar-chart-o"></i> <?= $title; ?>
        </div>
        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-3">
                <div class="form-group row">
                    <label class="col-md-6">Date From</label><label class="col-md-6">Date To</label>
                    <div class="col-sm-6">
                        <input readonly name="dfrom" id="dfrom" class="form-control date" required="">
                    </div>
                    <div class="col-sm-6">
                        <input readonly name="dto" id="dto" class="form-control date" required="" value="<?= date('d-m-Y');?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Product Group</label>
                    <div class="col-sm-12">
                        <select id="iproductgroup" name="iproductgroup" class="form-control">
                            <option value="NA">Nasional</option>
                            <option value="MO">Modern Outlet</option>
                            <option value="01">Dialogue Baby Bedding</option>
                            <option value="02">Dialogue Baby Non Bedding</option>
                            <option value="00">Dialogue Home</option>
                            <option value="06">Dialogue Fashion</option>
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
     $(".select2").select2();
     showCalendar('.date');
 });
</script>
