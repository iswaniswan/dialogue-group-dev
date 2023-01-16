<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-info">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?=$title;?>
      <?php  if(check_role($this->i_menu, 1)){ ?>
        <a href="#" onclick="show('<?= $folder; ?>/cform/tambah/','#main'); return false;" class="btn btn-info btn-sm pull-right">
        <i class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
        </div>
      <div class="panel-body table-responsive">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
              <!-- PENCARIAN TANGGAL -->
              <div class="col-md-6">
                  <div class="form-group row">
                      <label class="col-md-4">Date From</label><label class="col-md-4">Date To</label><label class="col-md-4">Kas/Bank</label>
                      <div class="col-sm-4">
                          <input class="form-control date" readonly="" type="text" name="dfrom" id="dfrom" value="<?= $dfrom;?>">
                      </div>
                      <div class="col-sm-4">
                          <input class="form-control date" readonly="" type="text" name="dto" id="dto" value="<?= $dto;?>">
                      </div>
                      <div class="col-sm-2">
                        <select name="status" id="status" class="form-control select2">
                          <option value='Belum' <?php if($status=='Belum') {echo "selected";}?>>Belum</option>
                          <option value='Sudah' <?php if($status=='Sudah') {echo "selected";}?>>Sudah</option>
                        </select>
                      </div>
                      <div class="col-sm-2">
                          <button type="submit" id="submit" class="btn btn-info" onsubmit = "check()"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                      </div>
                  </div>
              </div>
              <!-- --------------------------------------------------------------------------------------------------------------- -->
              <!-- PENCARIAN PERIODE -->
              <!-- <div class="col-md-4">
                  <div class="form-group row">
                      <label class="col-md-5">Bulan</label><label class="col-md-5">Tahun</label>
                      <div class="col-sm-5">
                        <select name="bulan" id="bulan" class="form-control select2" required="">
                            <option value=''></option>
                            <option value='01' <?php if($bulan=='01') {echo "selected";}?>>Januari</option>
                            <option value='02' <?php if($bulan=='02') {echo "selected";}?>>Februari</option>
                            <option value='03' <?php if($bulan=='03') {echo "selected";}?>>Maret</option>
                            <option value='04' <?php if($bulan=='04') {echo "selected";}?>>April</option>
                            <option value='05' <?php if($bulan=='05') {echo "selected";}?>>Mei</option>
                            <option value='06' <?php if($bulan=='06') {echo "selected";}?>>Juni</option>
                            <option value='07' <?php if($bulan=='07') {echo "selected";}?>>Juli</option>
                            <option value='08' <?php if($bulan=='08') {echo "selected";}?>>Agustus</option>
                            <option value='09' <?php if($bulan=='09') {echo "selected";}?>>September</option>
                            <option value='10' <?php if($bulan=='00') {echo "selected";}?>>Oktober</option>
                            <option value='11' <?php if($bulan=='01') {echo "selected";}?>>November</option>
                            <option value='12' <?php if($bulan=='02') {echo "selected";}?>>Desember</option>
                        </select>
                      </div>
                      <div class="col-sm-5">
                        <select name="tahun" id="tahun" class="form-control select2" required="">
                            <?php 
                            $tahun1 = date('Y')-3;
                            $tahun2 = date('Y');
                            for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                <option value=""></option>
                                <option value="<?= $i;?>" <?php if ($tahun==$tahun2) {echo "selected";}?>><?= $i;?></option>
                            <?php } ?>
                        </select>
                      </div>
                      <div class="col-sm-2">
                          <button type="submit" id="submit" class="btn btn-info"> <i class="fa fa-search"></i>&nbsp;&nbsp;Cari</button>
                      </div>
                  </div>
              </div> -->
              <!-- --------------------------------------------------------------------------------------------------------------- -->
          </form>
        <table id="tabledata" class="display nowrap" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>NO</th>
              <th>NO DOKUMEN KAS/BANK</th>
              <th>TGL KAS/BANK</th>
              <th>KAS/BANK</th>
              <th>NO DOKUMEN PERMINTAAN</th>
              <th>TGL PERMINTAAN</th>
              <th>JML PERMINTAAN</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
  function check(){
    if((document.getElementById(dfrom)=='')||(document.getElementById(dto)=='')){
      alert("Silahkan pilih tanggal terlebih dahulu");
      return false;
    }
  }
    $(document).ready(function () {
      showCalendar2('.date',1835,30);

      var t = $('#tabledata').DataTable( {
              "ajax": {
                  "url": "<?= site_url($folder); ?>/Cform/data/<?= $dfrom."/".$dto."/".$status; ?>",
                  "type": "POST"
              },
              "columnDefs": [ {
                  "searchable": false,
                  "orderable": false,
                  "targets": 0
              } ],
              "order": [[ 1, 'asc' ]]
          } );
      
      t.on( 'order.dt search.dt', function () {
          t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
              cell.innerHTML = i+1;
          } );
      } ).draw();

      $('.select2').select2();
      // $('#status').date({
      //    placeholder: 'Pilih Status',
      // });
      
      /* AKTIFKAN KALAU PENGEN PERIODE */
      //$('.select2').select2();
      // $('#bulan').select2({
      //   placeholder: 'Pilih Bulan',
      // });
      // $('#tahun').select2({
      //   placeholder: 'Pilih Tahun',
      // });
      /* ****************************** */
    });
</script>