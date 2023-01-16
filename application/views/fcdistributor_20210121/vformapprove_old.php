<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-list"></i> &nbsp; <?= $title; ?>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/view'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4">Gudang</label>
                        <div class="col-sm-4">
                            <select name="ikodemaster" id="ikodemaster" class="form-control select2">
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Periode</label>
                        <label class="col-md-8">Tahun</label>
                        <div class="col-sm-4">
                           <select name="iperiodebl" id="iperiodebl" class="form-control select2">
                            <option value="">Pilih Bulan</option>
                                <option value='01'>Januari</option>
                                <option value='02'>Februari</option>
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
                         <div class="col-sm-4">
                             <select name="iperiodeth" id="iperiodeth" class="form-control select2" required="">
                                <option value=""></option>
                                <?php 
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                    <option value="<?= $i;?>" <?php if ($tahun==$i) {
                                    echo "selected";} ?>><?= $i;?></option>
                                <?php } ?>
                            </select>  
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-inverse btn-rounded btn-sm" onclick="return validasi();"> <i class="fa fa-search"></i>&nbsp;&nbsp;View</button>                   
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
    $('.select2').select2();
    showCalendar('.date');
});

$(document).ready(function () {
//var ikodebarang = $('#ikodebarang').val();
$('#ikodemaster').select2({
    placeholder: 'Pilih Gudang',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/gudang'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data

        };
      },
      cache: true
    }
  })
 });
</script>