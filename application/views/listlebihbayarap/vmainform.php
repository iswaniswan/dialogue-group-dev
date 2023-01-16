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
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-6">
                            <select name="isupplier" id="isupplier" class="form-control select2" required="">
                                <option value=""></option>
                                <?php if ($supplier) {
                                    foreach ($supplier as $key) { ?>
                                        <option value="<?php echo $key->i_supplier;?>"><?php echo $key->i_supplier." - ".$key->e_supplier_name;?></option> 
                                    <?php }
                                } ?>   
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
        $('.select2').select2({
            placeholder: 'Cari Berdasarkan Kode / Nama'
        });
        showCalendar('.date');
    });
</script>
