<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>            
                     <div class="form-group">
                        <label class="col-md-12">No OP</label>
                        <div class="col-sm-6">
                            <select name="iop" id="iop" class="form-control select2" onchange="get(this.value);">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No DO</label>
                        <div class="col-sm-6">
                            <input id="ido" name="ido" type="text" maxlength="6" class="form-control" onkeyup="gede(this);" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pemasok</label>
                        <div class="col-sm-6">
                            <input readonly id="esuppliername" class="form-control" name="esuppliername" value=""readonly>
                            <input id="isupplier" name="isupplier" class="form-control" type="hidden" value="">
                            <input id="nsupplierdiscount" name="nsupplierdiscount" class="form-control" type="hidden" value="">
                            <input id="nsupplierdiscount2" name="nsupplierdiscount2" class="form-control" type="hidden" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="dipales(parseFloat(document.getElementById('jml').value));"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="" readonly>
                            <input id="iarea" name="iarea" type="hidden" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                            <label class="col-md-12">Tanggal DO</label>
                            <div class="col-sm-6">
                                <input readonly id="ddo" name="ddo" class="form-control date" value="<?= date('d-m-Y') ?>">
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                            <input readonly id="vdogross" class="form-control" name="vdogross" value="0">
                        </div>
                    </div>
                </div>                  
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Keterangan</th>
                                    <th>Harga</th>
                                    <th>Jumlah OP</th>
                                    <th>Jumlah Kirim</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="pesan"></div>
                    <input type="hidden" name="jml" id="jml" value="0">
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
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'i_op': id
            },
            url: '<?= base_url($folder.'/cform/getop'); ?>',
            dataType: "json",
            success: function (data) {
                $('#isupplier').val(data['data'][0].i_supplier);
                $('#nsupplierdiscount').val(data['data'][0].n_supplier_discount);
                $('#nsupplierdiscount2').val(data['data'][0].n_supplier_discount2);
                $('#esuppliername').val(data['data'][0].e_supplier_name);
                $('#iarea').val(data['data'][0].i_area);
                $('#eareaname').val(data['data'][0].e_area_name);
                $('#jml').val(data['jmlitem']);
                
               
                for (let a = 0; a < data['jmlitem']; a++) {
                    var no = a+1;
                    var total = 0;
                    var jumdo = 0;
                    var order = data['brgop'][a]['n_order'];
                    var deliver = data['brgop'][a]['n_delivery'];
                    var produk      = data['brgop'][a]['i_product'];
                    var namaproduk  = data['brgop'][a]['e_product_name'];
                    var imotif      = data['brgop'][a]['i_product_motif'];
                    var motif       = data['brgop'][a]['e_product_motifname'];
                    var harga       = data['brgop'][a]['v_product_mill'];
                    var igrade      = data['brgop'][a]['i_product_grade'];
                    var cols        = "";
                    var newRow = $("<tr>");
                    var sisa = parseInt(order) +parseInt(deliver);
                    cols += '<td><input style="width:35px;" readonly type="text" id="baris'+a+'" name="baris'+a+'" class="form-control" value="'+no+'"><input type="hidden" id="motif'+a+'" name="motif'+a+'" value="'+imotif+'"><input type="hidden" id="nitemno'+a+'" name="nitemno'+a+'" value="'+no+'"><input type="hidden" id="iproductgrade'+a+'" name="iproductgrade'+a+'" value="'+igrade+'"></td>';
                    cols += '<td><input style="width:100px;" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" class="form-control" value="'+produk+'"></td>';
                    cols += '<td><input style="width:268px;" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" class="form-control" value="'+namaproduk+'"></td>';
                    cols += '<td><input readonly style="width:91px;"  type="text" id="emotifname'+a+'" name="emotifname'+a+'" class="form-control" value="'+motif+'"></td>';
                    cols += '<td><input readonly style="text-align:right; width:100px;" type="text" id="vproductmill'+a+'" name="vproductmill'+a+'" class="form-control" value="'+harga+'"></td>';
                    cols += '<td><input style="text-align:right; width:82px;" readonly type="text" id="ndeliverhidden'+a+'" name="ndeliverhidden'+a+'" class="form-control" value="'+sisa+'"><input style="text-align:right; width:82px;" type="hidden" id="ntmp'+a+'" name="ntmp'+a+'" value="'+jumdo+'"></td>';
                    cols += '<td><input style="text-align:right; width:82px;" type="text" id="ndeliver'+a+'" name="ndeliver'+a+'" value ="'+jumdo+'"class="form-control" onkeyup="hitungnilai(this.value);pembandingnilai(this.value)"></td>';
                    cols += '<td><input readonly style="text-align:right; width:100px;" readonly type="text" id="vtotal'+a+'" class="form-control" name="vtotal'+a+'" value="'+total+'"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function pembandingnilai(a){
     var n_deliver   = document.getElementById('ndeliver'+a).value;
     var deliverasal = document.getElementById('ndeliverhidden'+a).value;

     if(parseFloat(n_deliver) > parseFloat(deliverasal)) {
        alert('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Order ( '+deliverasal+' item )');
        document.getElementById('ndeliver'+a).value   = deliverasal;
        document.getElementById('ndeliver'+a).focus();
        return false;
     }
  }

  function hitungnilai(isi,jml){
   if (isNaN(parseFloat(isi))){
      alert("Input harus numerik");
   }else{
      var vtot=0;
      var dis1=parseFloat(document.getElementById("nsupplierdiscount").value);
      //alert(dis1);
      var dis2=parseFloat(document.getElementById("nsupplierdiscount2").value);
      jml=document.getElementById("jml").value;
     // alert(jml);
      for(i=0;i<jml;i++){
         var vhrg=formatulang(document.getElementById("vproductmill"+i).value);
         var nqty=formatulang(document.getElementById("ndeliver"+i).value);
         vhrg=parseFloat(vhrg)*parseFloat(nqty);
         vtot=vtot+vhrg;
         //document.getElementById("vtotal"+i).value=formatcemua(vhrg);
         $('#vtotal'+i).val(formatcemua(vhrg));
      }
      var xx=(parseFloat(vtot)*dis1)/100;
      alert(xx);
      var tmp=parseFloat(vtot-xx);
      var yy=(tmp*dis2)/100;
      vtot=vtot-(xx+yy);
      //document.getElementById("vdogross").value=formatcemua(vtot);
      $('#vdogross').val(formatcemua(vtot));
      alert(formatcemua(vtot));
      //document.getElementById("ntmp").value=nqty;
   }
  }

  function sisa(a){
    var sisa = parseFloat(document.getElementById('sisa'+a).value);
    var ndeliver = parseFloat(document.getElementById('ndeliver'+a).value);

    if(ndeliver > sisa){
      alert('Sisa Jumlah Kirim Adalah : '+sisa);
      document.getElementById('ndeliver'+a).value   = 0;
       document.getElementById('ndeliver'+a).focus();
    }
  }

  function dipales(a){
    cek='false';
    if((document.getElementById("ddo").value!='') &&
      (document.getElementById("isupplier").value!='') &&
      (document.getElementById("ido").value!='') &&
      (document.getElementById("iop").value!='')) {
      if(a==0){
         alert('Isi data item minimal 1 !!!');
      }else{
            for(i=1;i<=a;i++){
            if((document.getElementById("iproduct"+i).value=='') ||
               (document.getElementById("eproductname"+i).value=='') ||
               (document.getElementById("ndeliver"+i).value=='')){
               alert('Data item masih ada yang salah !!!');
               exit();
               cek='false';
            }else{
               cek='true';
            }
         }
      }
      if(cek=='true'){
         document.getElementById("login").disabled=true;
         document.getElementById("cmdtambahitem").disabled=true;
         document.getElementById("submit").disabled=true;
      }else{
            document.getElementById("login").disabled=false;
         document.getElementById("cmdtambahitem").disabled=false;
      }
    }else{
         alert('Data header masih ada yang salah !!!');
    }
  }

</script>