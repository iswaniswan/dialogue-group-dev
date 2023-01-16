<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <!-- <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?> -->
                <div id="pesan"></div>
                <div class="col-md-5">
                    <div class="form-group row">
                        <label class="col-md-6">Date From</label><label class="col-md-6">Date To</label>
                        <div class="col-sm-6">
                            <input type="text" id= "dfrom" name="dfrom" class="form-control date" required="" value="" readonly value="">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "dto" name="dto" class="form-control date" required="" value="<?= date('d-m-Y');?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">Product Group</label>
                        <div class="col-sm-6">
                            <select name="iarea" id="iarea" class="form-control select2" required="">
                                <option value=""></option>
                                <?php if ($xarea=='00') { ?>
                                    <option value="NA">NA - Nasional</option>
                                <?php } ?>
                                <?php if ($area) {
                                    foreach ($area as $key) { ?>
                                        <option value="<?= $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="iproductgroup" id="iproductgroup" class="form-control select2" required="">
                                <option value=""></option>
                                <option value="NA">NA - Nasional</option>
                                <?php if ($group) {
                                    foreach ($group as $key) { ?>
                                        <option value="<?= $key->i_product_group;?>"><?= $key->e_product_groupname;?></option> 
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
        showCalendar('.date');

        $('#iarea').select2({
            placeholder: 'Pilih Area'
        });
        $('#iproductgroup').select2({
            placeholder: 'Pilih Group'
        });
    });

    $("#submit").click(function() {
        var lebar  = 1366;
        var tinggi = 768;
        var dfrom  = $('#dfrom').val();
        var dto    = $('#dto').val();
        var iarea  = $('#iarea').val();
        var group  = $('#iproductgroup').val();
        if (dfrom == '' || dto == '' || iarea == '' || group == '') {
            swal('Isi dulu form yang masih kosong!!!');
            return false;
        }else{   
            eval('window.open("<?= site_url(); ?>"+"/<?=$folder;?>/cform/view/"+iarea+"/"+dfrom+"/"+dto+"/"+group,"","width="+lebar+"px,height="+tinggi+"px,resizable=1,scrollbars=1,top='+(screen.height-tinggi)/2+',left='+(screen.width-lebar)/2+'")');
        }
    });
</script>