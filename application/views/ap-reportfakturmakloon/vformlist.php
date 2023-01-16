<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-12">Periode (Bulan / Tahun)</label> 
                        <div class="col-sm-6">
                            <?php if($periode == ''){?>
                                <input type="hidden" id="iperiode" name="iperiode" value="">
                            <?}else{?>
                                <input type="hidden" id="iperiode" name="iperiode" value="<?=$periode;?>">
                            <?}?>
                            <select name="bulan" id="bulan" class="form-control select2" required="" onmouseup="buatperiode()">
                            <?php if($bulan == ''){?>
                                <option value="">-- Pilih Bulan --</option>
                            <?}else{?>
                                <option value="<?=$bulan;?>"><?=$namabulan;?></option>
                            <?}?>
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
                        <div class="col-sm-6">
                        <select name="tahun" id="tahun" class="form-control select2" required="" onmouseup="buatperiode()">
                            <?php if($tahun == ''){?>
                                <option value="">-- Pilih Tahun --</option>
                            <?}else{?>
                                <option value="<?=$tahun?>"><?=$tahun;?></option>
                            <?}?>
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
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a id="href" onclick="return validasi();"><button type="button" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Download</button> </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-md-6">Tipe Makloon</label>
                        <label class="col-md-6">Supplier</label>
                        <div class="col-sm-6">
                            <select name="itypemakloon" id="itypemakloon" class="form-control select2" onchange="getsupplier(this.value);buatperiode();"> 
                               <?php if($itypemakloon == 'ALL'){?>
                                    <option value="ALL" selected>Semua Makloon</option>
                                    <?php foreach ($typemakloon as $key):?>
                                    <option value="<?php echo $key->i_type_makloon;?>"><?=$key->e_type_makloon;?></option>
                                    <?php endforeach; ?>
                                <?}else{
                                    if($itypemakloon == ''){?>
                                        <option value="">-- Pilih Tipe Makloon--</option>
                                        <option value="ALL">Semua Makloon</option>
                                        <?php foreach ($typemakloon as $key):?>
                                        <option value="<?php echo $key->i_type_makloon;?>"><?=$key->e_type_makloon;?></option>
                                        <?php endforeach; ?>
                                    <?}else{?>
                                        <option value="<?=$itypemakloon;?>" selected><?=$getmakloon->e_type_makloon;?></option>
                                        <?php foreach ($typemakloon as $key):?>
                                        <option value="<?php echo $key->i_type_makloon;?>"><?=$key->e_type_makloon;?></option>
                                        <?php endforeach; 
                                    }?>
                                    
                                <?}?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="isupplier" id="isupplier" class="form-control select2" disabled onchange="getfaktur(this.value);">
                                <?php if($isupplier == ''){?>
                                    <option value="">-- Pilih Supplier --</option>
                                    <option value="ALL">Semua Supplier</option>
                                <?}else{?>
                                    <?php if($isupplier == 'ALL'){?>
                                        <option value="ALL">Semua Supplier</option>
                                    <?}else{?>
                                        <option value="<?=$isupplier?>"><?=$getsuppliername->e_supplier_name;?></option>
                                    <?}?>
                                <?}?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-md-12">No Faktur</label> 
                        <div class="col-sm-12">
                            <select name="inota" id="inota" class="form-control select2">
                                <?php if($ifaktur == ''){?>
                                    <option value="">-- Pilih Faktur --</option>
                                    <option value="ALL">Semua Faktur</option>
                                <?}else{?>
                                    <?php if($ifaktur == 'ALL'){?>
                                        <option value="ALL">Semua Faktur</option>
                                    <?}else{?>
                                        <option value="<?=$ifaktur;?>"><?=$getfaktur->no_faktur;?></option>
                                    <?}?>
                                <?}?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No Faktur</th>
                                <th>Tanggal Faktur</th> 
                                <th>Supplier</th>
                                <th>Tipe Makloon</th>
                                <th>Gross</th>
                                <th>Discount</th>            
                                <th>Netto</th>
                                <th>DPP</th>
                                <th>PPN</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align: center;" colspan="4">TOTAL</th>
                                <th><?= number_format($total->total_gross);?></th>
                                <th><?= number_format($total->total_discount);?></th>
                                <th><?= number_format($total->total_netto);?></th>
                                <th><?= number_format($total->total_dpp);?></th>
                                <th><?= number_format($total->total_ppn);?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        datatable('#tabledata', base_url + '<?= $folder; ?>/Cform/data/<?=$bulan;?>/<?=$tahun;?>/<?=$isupplier;?>/<?=$itypemakloon;?>/<?=$ifaktur;?>');
        $('.select2').select2();

        $('#inota').select2({
            placeholder: 'Cari No Faktur',
        });
    });

    function getsupplier(id){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getsupplier');?>",
            data: "id=" + id,
            dataType: 'json',
            success: function (data) {
                $("#isupplier").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                    $("#isupplier").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }
        });
    } 

    function getfaktur(id){
        iperiode = $('#iperiode').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getfaktur');?>",
            data: {
                'id' : id, 
                'periode' : iperiode
                },
            dataType: 'json',
            success: function (data) {
                $("#inota").html(data.kop);
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                    $("#inota").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }
        });
    } 

    function validasi() {
        var bulan        = $('#bulan').val();
        var tahun        = $('#tahun').val();
        var itypemakloon = $('#itypemakloon').val();
        var isupplier    = $('#isupplier').val();
        var ifaktur      = $('#inota').val();

        if ( (bulan == '' && tahun == '') || itypemakloon == '' || isupplier == '' || ifaktur == '') {
            swal('Data header Belum Lengkap');
            return false;
        } else {
            $('#href').attr('href','<?php echo site_url($folder.'/cform/export/');?>'+bulan+'/'+tahun+'/'+isupplier+'/'+itypemakloon+'/'+ifaktur);
            return true;
        }
    }

    function buatperiode(){
        var tahun =  $('#tahun').val();
        var bulan = $('#bulan').val();
        periode=tahun+bulan;
        $('#iperiode').val(periode);
    }
</script>