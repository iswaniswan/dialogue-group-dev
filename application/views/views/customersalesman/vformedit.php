<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input type="hidden" name="eareaname" id="eareaname" value="<?= $data->e_area_name; ?>" readonly>
                            <select name="iarea" id="iarea" class="form-control">
                                <option value="<?php echo $data->i_area;?>"><?php echo $data->e_area_name;?></option>
                                <?php foreach ($area as $iarea):?>
                                    <option value="<?php echo $iarea->i_area;?>"><?php echo $iarea->e_area_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                            <input type="hidden" name="ecustomer" id="ecustomer" value="<?= $data->e_customer_name; ?>" readonly>
                            <select name="icustomer" id="icustomer" class="form-control">
                                    <option value="<?php echo $data->i_customer; ?>"><?php echo $data->i_customer." - ".$data->e_customer_name;?></option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                        <input type="hidden" name="esalesman" id="esalesman" value="<?php echo $data->e_salesman_name;?>" readonly>
                        <select name="isalesman" id="isalesman" class="form-control">
                            <option value="<?php echo $data->i_salesman;?>"><?php echo $data->e_salesman_name;?></option>
                            <?php foreach ($salesman as $isalesman):?>
                                <option value="<?php echo $isalesman->i_salesman;?>"><?php echo $isalesman->e_salesman_name;?></option>
                            <?php endforeach; ?>  
                        </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Jenis</label>
                        <div class="col-sm-12">
                        <select name="iproductgroup" id="iproductgroup" class="form-control">
                            <?php foreach ($jenis as $ijenis):?>
                                <option value="<?php echo $ijenis->i_product_group;?>"><?php echo $ijenis->e_product_groupname;?></option>
                            <?php endforeach; ?>  
                        </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Periode</label>
                    <div class="row col-md-12">
                        <div class="col-sm-1">
                            <div class="input-group">
                                <input type="hidden" id="iperiode" name="iperiode" value="<?php echo $data->e_periode;?>">
                                <?php 
                                    $thn = substr($periode,0,4);
                                    $bln = substr($periode,4,2);
                                ?>
                                <select name="bulan">
                                    <option value="01"<?php if($bln=='01') echo ' selected'; ?>>Januari</option>
                                    <option value="02"<?php if($bln=='02') echo ' selected'; ?>>Pebruari</option>
                                    <option value="03"<?php if($bln=='03') echo ' selected'; ?>>Maret</option>
                                    <option value="04"<?php if($bln=='04') echo ' selected'; ?>>April</option>
                                    <option value="05"<?php if($bln=='05') echo ' selected'; ?>>Mei</option>
                                    <option value="06"<?php if($bln=='06') echo ' selected'; ?>>Juni</option>
                                    <option value="07"<?php if($bln=='07') echo ' selected'; ?>>Juli</option>
                                    <option value="08"<?php if($bln=='08') echo ' selected'; ?>>Agustus</option>
                                    <option value="09"<?php if($bln=='09') echo ' selected'; ?>>September</option>
                                    <option value="10"<?php if($bln=='10') echo ' selected'; ?>>Oktober</option>
                                    <option value="11"<?php if($bln=='11') echo ' selected'; ?>>November</option>
                                    <option value="12"<?php if($bln=='12') echo ' selected'; ?>>Desember</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <select name="tahun">
                                    <option></option>
                                    <?php 
                                        echo "<option value='$thn'>$thn</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
            </div>
            </form>
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
        $('#iarea').select2({
            placeholder: "Pilih Area",
            allowClear: true
        }).on("change", function(e) {
        var kode = $('#iarea option:selected').text();
        //kode = kode.split("-");
        $('#eareaname').val(kode);
     });

        $('#icustomer').select2({
        placeholder: 'Pilih Pelanggan',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_pelanggan/'); ?>',
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
        var kode = $('#icustomer option:selected').text();
        kode = kode.split("-");
        $('#ecustomer').val(kode[1]);
        });

      $('#isalesman').select2({
        //var iarea = $('#iarea').val();
        placeholder: 'Pilih Salesman',
        allowClear: true,
      }).on("change", function(e) {
        var kode = $('#isalesman option:selected').text();
        //kode = kode.split("-");
        $('#esalesman').val(kode);
      });

      $('#iproductgroup').select2({
        placeholder: 'Pilih Jenis',
        allowClear: true,
      });
    });
</script>