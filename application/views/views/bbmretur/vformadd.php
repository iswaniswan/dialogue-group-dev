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
                        <label class="col-md-12">No TTB</label>
                        <div class="col-sm-6">
                            <select name="ittb" id="ittb" class="form-control select2" onchange="get(this.value);">
                            </select>
                            <input type="hidden" id= "dttb" name="dttb" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input readonly id="eareaname" type="text" name="eareaname" value="">
                            <input id="iarea" name="iarea" type="hidden" value="">
                            <input readonly id="ibbm" name="ibbm" type="hidden" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input readonly id="ecustomername" type="text" name="ecustomername" value="">
                            <input id="icustomer" name="icustomer" type="hidden" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                            <input readonly id="esalesmanname" type="text" name="esalesmanname" value="">
                            <input id="isalesman" name="isalesman" type="hidden" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;View</button>
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
        $('#ittb').select2({
        placeholder: 'Pilih TTB',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_ttb'); ?>',
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
        var kode = $('#ittb').text();
     });
    });

    function get(id) {
        /*alert(iarea);*/
        $.ajax({
            type: "post",
            data: {
                'i_ttb': id
            },
            url: '<?= base_url($folder.'/cform/get_ttb'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iarea').val(data[0].i_area);
                $('#eareaname').val(data[0].e_area_name);
                $('#icustomer').val(data[0].i_customer);
                $('#ecustomername').val(data[0].e_customer_name);
                $('#isalesman').val(data[0].i_salesman);
                $('#esalesmanname').val(data[0].e_salesman_name);
                $('#dttb').val(data[0].d_ttb);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function validasi(){
        var op = $('#ittb').val();
        var tidakadaop = $('#tidakadattb').is(':checked');
        if(tidakadaop)
        {
            return true;
        } else {
            if(op==''){
                alert('Pilih Terlebih Dahulu TTB');
                return false;
            }
        }
        
    }
</script>