<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                        <input type="hidden" name="eareaname" id="eareaname" readonly>
                        <select name="iarea" id="iarea" class="form-control">
                            <option></option>
                            <?php foreach ($area as $iarea):?>
                                <option value="<?php echo $iarea->i_area;?>"><?php echo $iarea->e_area_name;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                        <select name="icustomer" id="icustomer" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                        <input type="hidden" name="esalesman" id="esalesman" readonly>
                        <select name="isalesman" id="isalesman" class="form-control">
                            <?php foreach ($salesman as $isalesman):?>
                                <option></option>
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
                                <input type="hidden" id="iperiode" name="iperiode" value="">
                                <select name="bulan">
                                    <option></option>
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
                        </div>

                        <div class="col-sm-2">
                            <div class="input-group">
                                <select name="tahun">
                                    <option></option>
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
                        </div>
                    </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                    </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('#iarea').select2({
            placeholder: "Pilih Area",
            allowClear: true
        }).on("change", function (e) {
            var kode = $('#iarea option:selected').text();
            //kode = kode.split("-");
            $('#eareaname').val(kode);
            // var i_area = $('#iarea').val();
        });

        $('#icustomer').select2({
            placeholder: 'Pilih Pelanggan',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/data_pelanggan/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var i_area = $('#iarea').val();
                        var query = {
                            q: params.term,
                            i_area: i_area
                        }
                        return query;
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
        });

        //   $('#isalesman').select2({
        //     //var iarea = $('#iarea').val();
        //     placeholder: 'Pilih Salesman',
        //     allowClear: true,
        //     ajax: {
        //       url: '<#?= base_url($folder.'/cform/data_salesman/'); ?>',
        //       dataType: 'json',
        //       delay: 250,
        //       processResults: function (data) {
        //         return {
        //           results: data
        //         };
        //       },
        //       cache: true
        //     }
        //   });

        $('#isalesman').select2({
            //var iarea = $('#iarea').val();
            placeholder: 'Pilih Salesman',
            allowClear: true,
        }).on("change", function (e) {
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