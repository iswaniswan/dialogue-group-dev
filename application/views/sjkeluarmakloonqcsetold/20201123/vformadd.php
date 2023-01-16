<div class="row">
   <div class="col-md-12">
      <div class="white-box">
         <div id="pesan"></div>
         <section>
            <div class="sttabs tabs-style-bar">
               <nav>
                  <ul>
                     <li><a href="#section-bar-1" class="fa fa-plus" onclick="cekdok(1);"><span>&nbsp;&nbsp;SJ Keluar Makloon Print</span></a></li>
                     <li><a href="#section-bar-2" class="fa fa-plus" onclick="cekdok(2);"><span>&nbsp;&nbsp;SJ Keluar Makloon Bordir</span></a></li>
                     <li><a href="#section-bar-3" class="fa fa-plus" onclick="cekdok(3);"><span>&nbsp;&nbsp;SJ Keluar Makloon Embosh</span></a></li>
                  </ul>
               </nav>
               <div class="content-wrap">
                  <section id="section-bar-1">
                     <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                     <input type="hidden" id="ddok" name="ddok" value="1">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="panel panel-info">
                              <div class="panel-heading"><i class="fa fa-plus" ></i> &nbsp; <?= "Tambah SJ Keluar Makloon Print"; ?> <a href="#"
                                 onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
                              </div>
                              <div class="panel-wrapper collapse in" aria-expanded="true">
                                 <div class="panel-body">
                                    <div class="form-body">
                                       <div class="form-group row">
                                          <div id="pesan"></div>
                                          <div class="col-md-6">
                                             <div class="form-group row">
                                                <label class="col-md-7">Bagian</label>
                                                <label class="col-md-5">Tanggal SJ</label>
                                                <div class="col-sm-7">
                                                   <select name="ibagian" id="ibagian" class="form-control select2">
                                                      <?php foreach ($area as $ibagian):?>
                                                      <option value="<?php echo $ibagian->i_departement;?>">
                                                         <?= $ibagian->e_departement_name;?>
                                                      </option>
                                                      <?php endforeach; ?>
                                                   </select>
                                                </div>
                                                <div class="col-sm-4">
                                                   <input type="text" id="dsjk" name="dsjk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required readonly onchange="return max_back();">
                                                </div>
                                             </div>
                                             <div class="form-group row">
                                                <label class="col-md-7">No Permintaan</label>
                                                <label class="col-md-5">Tanggal Permintaan</label>
                                                <div class="col-sm-7">
                                                   <input type="text" id= "ipermintaan" name="ipermintaan" class="form-control" maxlength="18" required value="">
                                                   <input type="hidden" id= "fpkp" name="fpkp" class="form-control" value="">
                                                   <input type="hidden" id= "vdiskon" name="vdiskon" class="form-control" value="">
                                                </div>
                                                <div class="col-sm-4">
                                                   <input readonly type="text" id= "dpermintaan" name="dpermintaan" class="form-control date" required value="<?php echo date("d-m-Y"); ?>">
                                                </div>
                                             </div>
                                             <div class="form-group">
                                                <div class="col-sm-offset-5 col-sm-8">
                                                   <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" ><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                                                   <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                                                   <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button> 
                                                   <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>            
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group row">
                                                <label class="col-md-4">Perkiraan Kembali</label>
                                                <label class="col-md-4">Partner</label>
                                                <label class="col-md-4">Type Makloon</label>
                                                <div class="col-sm-4">
                                                   <input type="text" id="dback" name="dback" class="form-control date" required value="<?php echo date("d-m-Y"); ?>" readonly>
                                                </div>
                                                <div class="col-sm-4">
                                                   <select name="ipartner" id="ipartner" class="form-control select2" onchange="return gettypemakloon(this.value);">
                                                   </select>
                                                </div>
                                                <div class="col-sm-4">
                                                   <select name="itypemakloon" id="itypemakloon" class="form-control select2" disabled="true" onchange="getstore();">
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="form-group">
                                                <label class="col-md-12">Keterangan</label>
                                                <div class="col-sm-12">
                                                   <input type="text" id= "eremark" name="eremark" class="form-control" value="">
                                                </div>
                                             </div>
                                             <input type="hidden" name="jml" id="jml" value ="0">
                                          </div>
                                          <div class="panel-body table-responsive">
                                             <table id="tabledata" class="table color-table info-table table-bordered" cellspacing="0" width="100%" hidden="true">
                                                <thead>
                                                   <tr>
                                                      <th style="text-align: center; width: 5%;">No</th>
                                                      <th style="text-align: center; width: 13%;">Kode Barang</th>
                                                      <th style="text-align: center; width: 35%;">Nama Barang</th>
                                                      <th style="text-align: center; width: 10%;">Quantity</th>
                                                      <th style="text-align: center; width: 30%;">Keterangan</th>
                                                      <th style="text-align: center;">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                             </table>
                                          </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <section id="section-bar-2">
                     <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                     <input type="hidden" id="ddok" name="ddok" value="2">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="panel panel-info">
                              <div class="panel-heading"> <i class="fa fa-plus"></i> &nbsp; <?= "Tambah SJ Keluar Makloon Bordir"; ?> <a href="#"
                                 onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
                              </div>
                              <div class="panel-wrapper collapse in" aria-expanded="true">
                                 <div class="panel-body">
                                    <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                                    <div class="form-body">
                                       <div class="form-group row">
                                          <div class="col-md-6">
                                             <div class="form-group row">
                                                <label class="col-md-7">Bagian</label>
                                                <label class="col-md-5">Tanggal SJ</label>
                                                <div class="col-sm-7">
                                                   <select name="i_bagian" id="i_bagian" class="form-control select2">
                                                      <?php foreach ($area as $ibagian):?>
                                                      <option value="<?php echo $ibagian->i_departement;?>">
                                                         <?= $ibagian->e_departement_name;?>
                                                      </option>
                                                      <?php endforeach; ?>
                                                   </select>
                                                </div>
                                                <div class="col-sm-4">
                                                   <input type="text" id="d_sjk" name="d_sjk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required readonly onchange="return max_back();">
                                                </div>
                                             </div>
                                             <div class="form-group row">
                                                <label class="col-md-7">No Permintaan</label>
                                                <label class="col-md-5">Tanggal Permintaan</label>
                                                <div class="col-sm-7">
                                                   <input type="text" id= "i_permintaan" name="i_permintaan" class="form-control" maxlength="18" required value="">
                                                   <input type="hidden" id= "f_pkp" name="f_pkp" class="form-control" value="">
                                                   <input type="hidden" id= "v_diskon" name="v_diskon" class="form-control" value="">
                                                </div>
                                                <div class="col-sm-4">
                                                   <input readonly type="text" id= "d_permintaan" name="d_permintaan" class="form-control date" required value="<?php echo date("d-m-Y"); ?>">
                                                </div>
                                             </div>
                                             <div class="form-group">
                                                <div class="col-sm-offset-5 col-sm-8">
                                                   <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek2();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                                                   <button type="button" id="s_send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                                                   <button type="button" id="add_row" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>     
                                                   <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>         
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group row">
                                                <label class="col-md-4">Perkiraan Kembali</label>
                                                <label class="col-md-4">Partner</label>
                                                <label class="col-md-4">Type Makloon</label>
                                                <div class="col-sm-4">
                                                   <input type="text" id="d_back" name="d_back" class="form-control date" required value="<?php echo date("d-m-Y"); ?>" readonly>
                                                </div>
                                                <div class="col-sm-4">
                                                   <select name="i_partner" id="i_partner" class="form-control select2" onchange="return gettypemakloon2(this.value);">
                                                   </select>
                                                </div>
                                                <div class="col-sm-4">
                                                   <select name="i_typemakloon" id="i_typemakloon" class="form-control select2" disabled="true" onchange="getstore();">
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="form-group">
                                                <label class="col-md-12">Keterangan</label>
                                                <div class="col-sm-12">
                                                   <input type="text" id= "e_remark" name="e_remark" class="form-control" value="">
                                                </div>
                                             </div>
                                             <input type="hidden" name="j_jml" id="j_jml" value ="0">
                                          </div>
                                          <div class="panel-body table-responsive">
                                             <table id="table_data" class="table color-table info-table table-bordered" cellspacing="0" width="100%" hidden="true">
                                                <thead>
                                                   <tr>
                                                      <th style="text-align: center; width: 5%;">No</th>
                                                      <th style="text-align: center; width: 13%;">Kode Barang</th>
                                                      <th style="text-align: center; width: 35%;">Nama Barang</th>
                                                      <th style="text-align: center; width: 10%;">Quantity</th>
                                                      <th style="text-align: center; width: 30%;">Keterangan</th>
                                                      <th style="text-align: center;">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                             </table>
                                          </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <section id="section-bar-3">
                     <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                     <input type="hidden" id="ddok" name="ddok" value="3">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="panel panel-info">
                              <div class="panel-heading"> <i class="fa fa-plus"></i> &nbsp; <?= "Tambah SJ Keluar Makloon Embosh"; ?> <a href="#"
                                 onclick="show('<?= $folder; ?>/cform/','#main'); return false;" class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
                              </div>
                              <div class="panel-wrapper collapse in" aria-expanded="true">
                                 <div class="panel-body">
                                    <div class="form-body">
                                       <div class="form-group row">
                                          <div class="col-md-6">
                                             <div class="form-group row">
                                                <label class="col-md-7">Bagian</label>
                                                <label class="col-md-5">Tanggal SJ</label>
                                                <div class="col-sm-7">
                                                   <select name="ibagiann" id="ibagiann" class="form-control select2">
                                                      <?php foreach ($area as $ibagian):?>
                                                      <option value="<?php echo $ibagian->i_departement;?>">
                                                         <?= $ibagian->e_departement_name;?>
                                                      </option>
                                                      <?php endforeach; ?>
                                                   </select>
                                                </div>
                                                <div class="col-sm-4">
                                                   <input type="text" id="dsjkk" name="dsjkk" class="form-control date" value="<?php echo date("d-m-Y"); ?>" required readonly onchange="return max_back();">
                                                </div>
                                             </div>
                                             <div class="form-group row">
                                                <label class="col-md-7">No Permintaan</label>
                                                <label class="col-md-5">Tanggal Permintaan</label>
                                                <div class="col-sm-7">
                                                   <input type="text" id= "ipermintaann" name="ipermintaann" class="form-control" maxlength="18" required value="">
                                                   <input type="hidden" id= "fpkpp" name="fpkpp" class="form-control" value="">
                                                   <input type="hidden" id= "vdiskonn" name="vdiskonn" class="form-control" value="">
                                                </div>
                                                <div class="col-sm-4">
                                                   <input readonly type="text" id= "dpermintaann" name="dpermintaann" class="form-control date" required value="<?php echo date("d-m-Y"); ?>">
                                                </div>
                                             </div>
                                             <div class="form-group">
                                                <div class="col-sm-offset-5 col-sm-8">
                                                   <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return cek3();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  
                                                   <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                                                   <button type="button" id="addroww" class="btn btn-info btn-rounded btn-sm" hidden><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                                   <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>               
                                                </div>
                                             </div>
                                          </div>
                                          <div class="col-md-6">
                                             <div class="form-group row">
                                                <label class="col-md-4">Perkiraan Kembali</label>
                                                <label class="col-md-4">Partner</label>
                                                <label class="col-md-4">Type Makloon</label>
                                                <div class="col-sm-4">
                                                   <input type="text" id="dbackk" name="dbackk" class="form-control date" required value="<?php echo date("d-m-Y"); ?>" readonly>
                                                </div>
                                                <div class="col-sm-4">
                                                   <select name="ipartnerr" id="ipartnerr" class="form-control select2" onchange="return gettypemakloon3(this.value);">
                                                   </select>
                                                </div>
                                                <div class="col-sm-4">
                                                   <select name="itypemakloonn" id="itypemakloonn" class="form-control select2" disabled="true" onchange="getstore();">
                                                   </select>
                                                </div>
                                             </div>
                                             <div class="form-group">
                                                <label class="col-md-12">Keterangan</label>
                                                <div class="col-sm-12">
                                                   <input type="text" id= "eremarkk" name="eremarkk" class="form-control" value="">
                                                </div>
                                             </div>
                                             <input type="hidden" name="jmll" id="jmll" value ="0">
                                          </div>
                                          <div class="panel-body table-responsive">
                                             <table id="tabledataa" class="table color-table info-table table-bordered" cellspacing="0" width="100%" hidden="true">
                                                <thead>
                                                   <tr>
                                                      <th style="text-align: center; width: 5%;">No</th>
                                                      <th style="text-align: center; width: 13%;">Kode Barang</th>
                                                      <th style="text-align: center; width: 35%;">Nama Barang</th>
                                                      <th style="text-align: center; width: 10%;">Quantity</th>
                                                      <th style="text-align: center; width: 30%;">Keterangan</th>
                                                      <th style="text-align: center;">Action</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                             </table>
                                          </div>
                                          </form>
                                       </div>
                                    </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
               </div>
            </div>
         </section>
      </div>
   </div>
</div>
<script>
   function cekdok(va) {
      $("#ddok").val(va);
   }
   
   $(document).ready(function () {
       $('.select2').select2();
       showCalendar('.date');
       $("#send").attr("disabled", true);
       $('#s_send').attr("disabled", true);
       $('#sendd').attr("disabled", true);
   });
   
   (function() {
       [].slice.call(document.querySelectorAll('.sttabs')).forEach(function(el) {
           new CBPFWTabs(el);
       });
   })();
   
   max_tgl();
   max_back();
   
   $(document).ready(function () {
      $('#ipartner').select2({
           placeholder: 'Pilih Partner',
           allowClear: true,
           ajax: {
               url: '<?= base_url($folder.'/cform/getpartner/'); ?>',
               dataType: 'json',
               delay: 250,
               data: function (params) {
                   var query = {
                       q: params.term,
                   }
                   return query;
               },
               processResults: function (data) {
                   return {
                       results: data
                   };
               },
               cache: false
           }
      });
   
      $('#i_partner').select2({
           placeholder: 'Pilih Partner',
           allowClear: true,
           ajax: {
               url: '<?= base_url($folder.'/cform/getpartner2/'); ?>',
               dataType: 'json',
               delay: 250,
               data: function (params) {
                   var query = {
                       q: params.term,
                   }
                   return query;
               },
               processResults: function (data) {
                   return {
                       results: data
                   };
               },
               cache: false
           }
      });
   
      $('#ipartnerr').select2({
           placeholder: 'Pilih Partner',
           allowClear: true,
           ajax: {
               url: '<?= base_url($folder.'/cform/getpartner3/'); ?>',
               dataType: 'json',
               delay: 250,
               data: function (params) {
                   var query = {
                       q: params.term,
                   }
                   return query;
               },
               processResults: function (data) {
                   return {
                       results: data
                   };
               },
               cache: false
           }
       });
   });
   
   function gettypemakloon(ipartner){
       $("#itypemakloon").attr("disabled", false);
       var ipartner     = $('#ipartner').val();
       $.ajax({
           type: "POST",
           url: "<?php echo site_url($folder.'/Cform/gettypemakloon');?>",
           data:{
               'ipartner': ipartner
           },
           dataType: 'json',
           success: function(data){
               $("#itypemakloon").html(data.kop);
               if (data.kosong=='kopong') {
                   $("#submit").attr("disabled", true);
               }else{
                   $("#submit").attr("disabled", false);
               }
           },
   
           error:function(XMLHttpRequest){
               alert(XMLHttpRequest.responseText);
           }
       })
   }
   
   function gettypemakloon2(i_partner){
       $("#i_typemakloon").attr("disabled", false);
       var i_partner     = $('#i_partner').val();
       $.ajax({
           type: "POST",
           url: "<?php echo site_url($folder.'/Cform/gettypemakloon2');?>",
           data:{
               'i_partner': i_partner
           },
           dataType: 'json',
           success: function(data){
               $("#i_typemakloon").html(data.kop);
               if (data.kosong=='kopong') {
                   $("#submit").attr("disabled", true);
               }else{
                   $("#submit").attr("disabled", false);
               }
           },
   
           error:function(XMLHttpRequest){
               alert(XMLHttpRequest.responseText);
           }
       })
   }

   function gettypemakloon3(ipartnerr){
       $("#itypemakloonn").attr("disabled", false);
       var ipartnerr     = $('#ipartnerr').val();
       $.ajax({
           type: "POST",
           url: "<?php echo site_url($folder.'/Cform/gettypemakloon3');?>",
           data:{
               'ipartnerr': ipartnerr
           },
           dataType: 'json',
           success: function(data){
               $("#itypemakloonn").html(data.kop);
               if (data.kosong=='kopong') {
                   $("#submit").attr("disabled", true);
               }else{
                   $("#submit").attr("disabled", false);
               }
           },
   
           error:function(XMLHttpRequest){
               alert(XMLHttpRequest.responseText);
           }
       })
   }
   
   function max_tgl() {
     $('#dsjk').datepicker('destroy');
     $('#dsjk').datepicker({
       autoclose: true,
       todayHighlight: true,
       format: "dd-mm-yyyy",
       todayBtn: "linked",
       daysOfWeekDisabled: [0],
       startDate: document.getElementById('dpermintaan').value,
     });
   }
   $('#dsjk').datepicker({
     autoclose: true,
     todayHighlight: true,
     format: "dd-mm-yyyy",
     todayBtn: "linked",
     daysOfWeekDisabled: [0],
     startDate: document.getElementById('dpermintaan').value,
   });
   
   function max_back() {
     $('#dback').datepicker('destroy');
     $('#dback').datepicker({
       autoclose: true,
       todayHighlight: true,
       format: "dd-mm-yyyy",
       todayBtn: "linked",
       daysOfWeekDisabled: [0],
       startDate: document.getElementById('dsjk').value,
     });
   }
   $('#dback').datepicker({
     autoclose: true,
     todayHighlight: true,
     format: "dd-mm-yyyy",
     todayBtn: "linked",
     daysOfWeekDisabled: [0],
     startDate: document.getElementById('dsjk').value,
   });

   function getstore() {
   
       if (addrow == "") {
           $("#addrow").attr("hidden", true);
           $("#add_row").attr("hidden", true);
           $("#addroww").attr("hidden", true);
       } else {
           $("#addrow").attr("hidden", false);
           $("#add_row").attr("hidden", false);
           $("#addroww").attr("hidden", false);
       }
   }
   
   var counter = 0;
   
   $("#addrow").on("click", function () {
       var counter = $('#jml').val();
       counter++;
       $("#tabledata").attr("hidden", false);
       document.getElementById("jml").value = counter;
       count=$('#tabledata tr').length;
       var newRow = $("<tr>");
       
       var cols = "";
       cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" class="form-control" name="baris[]" value="'+counter+'"></td>';
       cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
       cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control select2" name="eproduct[]" onchange="get('+ counter + ');"></td>';
       cols += '<td><input style="text-align: right;" type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" value="0"onkeyup="validasi('+ counter +'); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}" ></td>';
       cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/><input type="hidden" id="vharga'+ counter + '" class="form-control" name="vharga[]" value=""/></td>';
       cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
       newRow.append(cols);
       $("#tabledata").append(newRow);
       
       var dsjk   = $("#dsjk").val();
      // alert(dsjk);
       $('#eproduct'+counter).select2({
       placeholder: 'Pilih Product',
       templateSelection: formatSelection,
       allowClear: true,
       ajax: {          
          url: '<?= base_url($folder.'/cform/getproduct/'); ?>'+dsjk,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
     });
   
   function formatSelection(val) {
       return val.name;
   }
   
   $("#tabledata").on("click", ".ibtnDel", function (event) {
       $(this).closest("tr").remove();       
   });
   });
   
   var counter = 0;
   
   $("#add_row").on("click", function () {
       var counter = $('#j_jml').val();
       counter++;
       $("#table_data").attr("hidden", false);
       document.getElementById("j_jml").value = counter;
       count=$('#table_data tr').length;
       var newRow = $("<tr>");
       
       var cols = "";
       cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" class="form-control" name="baris[]" value="'+counter+'"></td>';
       cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
       cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control select2" name="eproduct[]" onchange="get_get('+ counter + ');"></td>';
       cols += '<td><input style="text-align: right;" type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" value="0"onkeyup="validasi('+ counter +'); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}" ></td>';
       cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/><input type="hidden" id="vharga'+ counter + '" class="form-control" name="vharga[]" value=""/></td>';
       cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
       newRow.append(cols);
       $("#table_data").append(newRow);
       
       var dsjk   = $("#d_sjk").val();
      // alert(dsjk);
       $('#eproduct'+counter).select2({
       placeholder: 'Pilih Product',
       templateSelection: formatSelection,
       allowClear: true,
       ajax: {          
          url: '<?= base_url($folder.'/cform/get_product/'); ?>'+dsjk,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
     });
   
   function formatSelection(val) {
       return val.name;
   }
   
   $("#table_data").on("click", ".ibtnDel", function (event) {
       $(this).closest("tr").remove();       
   });
   });

   var counter = 0;
   
   $("#addroww").on("click", function () {
       var counter = $('#jmll').val();
       counter++;
       $("#tabledataa").attr("hidden", false);
       document.getElementById("jmll").value = counter;
       count=$('#tabledataa tr').length;
       var newRow = $("<tr>");
       
       var cols = "";
       cols += '<td style="text-align: center;"><spanx id="snum'+counter+'">'+counter+'</spanx><input type="hidden" id="baris'+counter+'" class="form-control" name="baris[]" value="'+counter+'"></td>';
       cols += '<td><input type="text" readonly  id="iproduct'+ counter + '" type="text" class="form-control" name="iproduct[]"></td>';
       cols += '<td><select type="text" id="eproduct'+ counter + '" class="form-control select2" name="eproduct[]" onchange="gett('+ counter + ');"></td>';
       cols += '<td><input style="text-align: right;" type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity[]" value="0"onkeyup="validasi('+ counter +'); reformat(this); " onfocus="if(this.value==\'0\'){this.value=\'\';}" ></td>';
       cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc[]" value=""/><input type="hidden" id="vharga'+ counter + '" class="form-control" name="vharga[]" value=""/></td>';
       cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
       newRow.append(cols);
       $("#tabledataa").append(newRow);
       
       var dsjk   = $("#dsjkk").val();
      // alert(dsjk);
       $('#eproduct'+counter).select2({
       placeholder: 'Pilih Product',
       templateSelection: formatSelection,
       allowClear: true,
       ajax: {          
          url: '<?= base_url($folder.'/cform/get_productt/'); ?>'+dsjk,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
     });
   
   function formatSelection(val) {
       return val.name;
   }
   
   $("#tabledataa").on("click", ".ibtnDel", function (event) {
       $(this).closest("tr").remove();       
   });
   });
   
   function get(id){
       var eproduct     = $('#eproduct'+id).val();
       var ipartner     = $('#ipartner').val();
       var itypemakloon = $('#itypemakloon').val();
       var dsjk         = $('#dsjk').val();
       $.ajax({
       type: "post",
       data: {
               'eproduct'     : eproduct,
               'ipartner'     : ipartner,
               'itypemakloon' : itypemakloon,
               'dsjk'         : dsjk
       },
       url: '<?= base_url($folder.'/cform/get'); ?>',
       dataType: "json",
       success: function (data) {
           $('#iproduct'+id).val(data[0].i_material);
           $('#vdiskon').val(data[0].v_diskon);
           $('#vharga'+id).val(data[0].v_price);
           $('#fpkp').val(data[0].f_supplier_pkp);
   
           ada=false;
           var a = $('#iproduct'+id).val();
           var e = $('#eproduct'+id).val();
           var jml = $('#jml').val();
           for(i=1;i<=jml;i++){
               if((a == $('#iproduct'+i).val()) && (i!=id)){
                   swal ("kode : "+a+" sudah ada !!!!!");
                   ada=true;
                   break;
               }else{
                   ada=false;     
               }
           }
           if(!ada){
               $('#iproduct'+id).val(data[0].i_material);
           }else{
               $('#iproduct'+id).html('');
               $('#eproduct'+id).val('');
               $('#iproduct'+id).val('');
               $('#eproduct'+id).html('');
               $('#vharga'+id).val('');
               $('#vharga'+id).html('');
   
           }
       },
       error: function () {
           alert('Error :)');
       }
   });
   }

   function get_get(id){
       var eproduct     = $('#eproduct'+id).val();
       var ipartner     = $('#i_partner').val();
       var itypemakloon = $('#i_typemakloon').val();
       var dsjk         = $('#d_sjk').val();
       $.ajax({
       type: "post",
       data: {
               'eproduct'     : eproduct,
               'ipartner'     : ipartner,
               'itypemakloon' : itypemakloon,
               'dsjk'         : dsjk
       },
       url: '<?= base_url($folder.'/cform/get_get'); ?>',
       dataType: "json",
       success: function (data) {
           $('#iproduct'+id).val(data[0].i_material);
           $('#v_diskon').val(data[0].v_diskon);
           $('#vharga'+id).val(data[0].v_price);
           $('#f_pkp').val(data[0].f_supplier_pkp);
   
           ada=false;
           var a = $('#iproduct'+id).val();
           var e = $('#eproduct'+id).val();
           var jml = $('#j_jml').val();
           for(i=1;i<=jml;i++){
               if((a == $('#iproduct'+i).val()) && (i!=id)){
                   swal ("kode : "+a+" sudah ada !!!!!");
                   ada=true;
                   break;
               }else{
                   ada=false;     
               }
           }
           if(!ada){
               $('#iproduct'+id).val(data[0].i_material);
           }else{
               $('#iproduct'+id).html('');
               $('#eproduct'+id).val('');
               $('#iproduct'+id).val('');
               $('#eproduct'+id).html('');
               $('#vharga'+id).val('');
               $('#vharga'+id).html('');
   
           }
       },
       error: function () {
           alert('Error :)');
       }
   });
   }

    function gett(id){
       var eproduct     = $('#eproduct'+id).val();
       var ipartner     = $('#ipartnerr').val();
       var itypemakloon = $('#itypemakloonn').val();
       var dsjk         = $('#dsjkk').val();
       $.ajax({
       type: "post",
       data: {
               'eproduct'     : eproduct,
               'ipartner'     : ipartner,
               'itypemakloon' : itypemakloon,
               'dsjk'         : dsjk
       },
       url: '<?= base_url($folder.'/cform/gett'); ?>',
       dataType: "json",
       success: function (data) {
           $('#iproduct'+id).val(data[0].i_material);
           $('#vdiskonn').val(data[0].v_diskon);
           $('#vharga'+id).val(data[0].v_price);
           $('#fpkpp').val(data[0].f_supplier_pkp);
   
           ada=false;
           var a = $('#iproduct'+id).val();
           var e = $('#eproduct'+id).val();
           var jml = $('#jmll').val();
           for(i=1;i<=jml;i++){
               if((a == $('#iproduct'+i).val()) && (i!=id)){
                   swal ("kode : "+a+" sudah ada !!!!!");
                   ada=true;
                   break;
               }else{
                   ada=false;     
               }
           }
           if(!ada){
               $('#iproduct'+id).val(data[0].i_material);
           }else{
               $('#iproduct'+id).html('');
               $('#eproduct'+id).val('');
               $('#iproduct'+id).val('');
               $('#eproduct'+id).html('');
               $('#vharga'+id).val('');
               $('#vharga'+id).html('');
   
           }
       },
       error: function () {
           alert('Error :)');
       }
   });
   }
   
   function cekval(input){
        var jml   = counter;
        var num = input.replace(/\,/g,'');
        if(!isNaN(num)){
       }else{
           alert('input harus numerik !!!');
         input = input.substring(0,input.length-1);
        }
   }
   
   function validasi(id){
       jml=document.getElementById("jml").value;
       for(i=1;i<=jml;i++){
           qty          =document.getElementById("nquantity"+i).value;
           if(qty == '0'){
               swal("Jumlah Retur Tidak boleh kosong");
               $('#nquantity'+i).val("");
               break;
           }
       }
   }
   
   function cek() {
       var dsjk          = $('#dsjk').val();
       var ipermintaan  = $('#ipermintaan').val();
       var dpermintaan   = $('#dpermintaan').val();
       var dback         = $('#dback').val();
       var ibagian      = $('#ibagian').val();
   
       if (dsjk == '' || ipermintaan == '' || dpermintaan == '' || dback == '' || ibagian == '') {
           swal('Data Header Belum Lengkap !!');
           return false;
       } else {
           return true;
       }
   }

    function cek2() {
       var dsjk          = $('#d_sjk').val();
       var ipermintaan  = $('#i_permintaan').val();
       var dpermintaan   = $('#d_permintaan').val();
       var dback         = $('#d_back').val();
       var ibagian      = $('#i_bagian').val();
   
       if (dsjk == '' || ipermintaan == '' || dpermintaan == '' || dback == '' || ibagian == '') {
           swal('Data Header Belum Lengkap !!');
           return false;
       } else {
           $("#submit").attr("disabled", true);
           return true;
       }
   }

   function cek3() {
       var dsjk          = $('#dsjkk').val();
       var ipermintaan  = $('#ipermintaann').val();
       var dpermintaan   = $('#dpermintaann').val();
       var dback         = $('#dbackk').val();
       var ibagian      = $('#ibagiann').val();
   
       if (dsjk == '' || ipermintaan == '' || dpermintaan == '' || dback == '' || ibagian == '') {
           swal('Data Header Belum Lengkap !!');
           return false;
       } else {
           $("#submit").attr("disabled", true);
           return true;
       }
   }
   
   $("form").submit(function(event) {
       event.preventDefault();
       $("input").attr("disabled", true);
       $("select").attr("disabled", true);
       $("#submit").attr("disabled", true);
       $("#addrow").attr("disabled", true);
       $("#add_row").attr("disabled", true);
       $("#addroww").attr("disabled", true);
       $("#send").attr("disabled", false);
       $('#s_send').attr("disabled", false);
       $('#sendd').attr("disabled", false);
   });

   function getenabledsend() {
      swal("Berhasil", "Dokumen Terkirim ke Atasan", "success");
      $('#send').attr("disabled", true);
      $('#s_send').attr("disabled", true);
      $('#sendd').attr("disabled", true);
   }

   $(document).ready(function(){
       $("#send").on("click", function () {
           var kode = $("#kode").val();
           $.ajax({
               type: "POST",
               url: "<?= base_url($folder.'/cform/send'); ?>",
               data: {
                        'kode'  : kode,
                       },
               dataType: 'json',
               delay: 250, 
               success: function(data) {
                   return {
                   results: data
                   };
               },
                cache: true
           });
       });
   });

   $(document).ready(function(){
       $("#s_send").on("click", function () {
           var kode = $("#kode").val();
           $.ajax({
               type: "POST",
               url: "<?= base_url($folder.'/cform/s_send'); ?>",
               data: {
                        'kode'  : kode,
                       },
               dataType: 'json',
               delay: 250, 
               success: function(data) {
                   return {
                   results: data
                   };
               },
                cache: true
           });
       });
   });

   $(document).ready(function(){
       $("#sendd").on("click", function () {
           var kode = $("#kode").val();
           $.ajax({
               type: "POST",
               url: "<?= base_url($folder.'/cform/sendd'); ?>",
               data: {
                        'kode'  : kode,
                       },
               dataType: 'json',
               delay: 250, 
               success: function(data) {
                   return {
                   results: data
                   };
               },
                cache: true
           });
       });
   });
</script>

