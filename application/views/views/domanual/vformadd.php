<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">No OP</label>
                        <div class="col-sm-6">
                            <select name="iop" id="iop" class="form-control select2" onchange="get(this.value);">
                            </select>
                            <input type='hidden' id="dop" name="dop" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pemasok</label>
                        <div class="col-sm-12">
                            <input readonly id="esuppliername" name="esuppliername" value="">
                            <input id="isupplier" name="isupplier" type="hidden" value="">
                            <input id="nsupplierdiscount" name="nsupplierdiscount" type="hidden" value="">
                            <input id="nsupplierdiscount2" name="nsupplierdiscount2" type="hidden" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus" onclick="return validasi();"></i>&nbsp;&nbsp;View</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input readonly id="eareaname" name="eareaname" value="">
                            <input id="iarea" name="iarea" type="hidden" value="">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                            <input readonly id="vdogross" name="vdogross" value="<?php if(isset($total)) echo number_format($total); else echo '0'; ?>">
                        </div>
                    </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    $(document).ready(function () {
        $('#iop').select2({
        placeholder: 'Pilih OP',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_op'); ?>',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      }).on("change", function(e) {
        var kode = $('#iop').text();
     });
    });

    function get(id) {
        /*alert(iarea);*/
        $.ajax({
            type: "post",
            data: {
                'i_op': id
            },
            url: '<?= base_url($folder.'/cform/getop'); ?>',
            dataType: "json",
            success: function (data) {
                $('#isupplier').val(data[0].i_supplier);
                $('#esuppliername').val(data[0].e_supplier_name);
                $('#iarea').val(data[0].i_area);
                $('#eareaname').val(data[0].e_area_name);
                $('#nsupplierdiscount').val(data[0].n_supplier_discount);
                $('#nsupplierdiscount2').val(data[0].n_supplier_discount2);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function validasi(){
        var op = $('#iop').val();
        var tidakadaop = $('#tidakadaop').is(':checked');
        if(tidakadaop)
        {
            return true;
        } else {
            if(op==''){
                alert('Pilih Terlebih Dahulu OP');
                return false;
            }
        }
        
    }
</script>