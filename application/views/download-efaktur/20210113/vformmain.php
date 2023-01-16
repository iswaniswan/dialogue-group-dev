<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
           <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Date From</label><label class="col-md-3">Date To</label><label class="col-md-4">Supplier</label>
                        <div class="col-sm-3">
                            <input readonly name="dfrom" id="dfrom" class="form-control date" required="">
                        </div>
                        <div class="col-sm-3">
                            <input readonly name="dto" id="dto" class="form-control date" required="" value="<?= date('d-m-Y');?>">
                        </div>
                        <div class="col-sm-4">
                        <select id="isupplier" name="isupplier" class="form-control select2" onchange="getsupplier(this.value);">
                            <option value="">-- Pilih Supplier --</option>
                            <option value="NA">All Supplier</option>
                                <?php if ($supplier) {
                                    foreach ($supplier as $key) { ?>
                                        <option value="<?= $key->i_supplier;?>"><?= $key->i_supplier." - ".$key->e_supplier_name;?></option> 
                                    <?php }
                                } ?>
                            </select>
                            <input readonly type = "hidden" name="esuppliername" id="esuppliername" class="form-control date" required="" value="">
                        </div>
                        <div class="col-sm-2">
                            <button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Search</button>
                        </div>
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
       showCalendar('.date');
    });

    function getsupplier(id){
        var kode = $('#isupplier').val();
        $.ajax({
            type: "post",
            data: {
                'kode'  : kode
            },
            url: '<?= base_url($folder.'/cform/supplier'); ?>',
            dataType: "json",
            success: function (data) {
                $('#esuppliername').val(data[0].e_supplier_name);
            },
            error: function () {
                swal('Error :)');
            }
        });
    }
</script>
