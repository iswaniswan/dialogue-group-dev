<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-3">
                            <select name="istore" id="istore" class="form-control select2" required="" onchange="get(this.value);">
                                <option value=""></option>
                                <?php if ($area) {
                                    foreach ($area as $key) { ?>
                                        <option value="<?php echo $key->i_store;?>"><?php echo $key->i_store." - ".$key->e_store_name;?></option> 
                                    <?php }
                                } ?>   
                            </select>
                            <input type="hidden" name="istorelocation" id="istorelocation" class="form-control" value="">
                            <input type="hidden" name="iarea" id="iarea" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-table"></i>&nbsp;&nbsp;View</button>
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
    $('.select2').select2({
        placeholder: 'Cari Berdasarkan Kode / Nama'
    });
});

function get(id) {   
        
        $.ajax({
            type: "post",
            data: {
                'istore' : id,
            },
            url: '<?= base_url($folder.'/cform/getstore'); ?>',
            dataType: "json",
            success: function (data) {
                var istorelocation   = data['isi']['i_store_location'];
                $('#istorelocation').val(istorelocation);
                var iarea   = data['isi']['i_area'];
                $('#iarea').val(iarea);
            },
            error: function () {
                swal('Error :)');
            }
        });
        xx = $('#jml').val();
    }
</script>