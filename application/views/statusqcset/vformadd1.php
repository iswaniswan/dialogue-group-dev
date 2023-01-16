<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
            <div id="pesan"></div>
                <div class="col-md-6">
                <div class="form-group row">
                        <label class="col-md-6">No Schedule</label><label class="col-md-6">Gudang</label>
                        <div class="col-sm-6">
                            <select name="ischedule" id="ischedule" class="form-control select2" onchange="get(this.value);"> 
                            <input type="hidden" name="dschedule" id="dschedule" class="form-control" value="" >
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <select name="igudang" id="igudang" class="form-control select2"> 
                            </select>
                        </div>
                    </div>    
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                    </div>               
                </div>   
            </div> 
            <div class="col-md-6">
                <!-- <div class="form-group">
                        <label class="col-md-12">Tanggal Permintaan</label>
                        <div class="col-sm-12">
                            <input type="text" name="dspbb" class="form-control date" value="" >
                            <input type="text" name="ispbb" id="ispbb" class="form-control" value="" >
                        </div>
                    </div>   -->
                
                    
                    <!-- <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <select name="igudang" id="igudang" class="form-control select2"> 
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Permintaan</label><label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="text" name="dspbb" class="form-control date" value="" readonly>
                            <input type="hidden" name="ispbb" id="ispbb" class="form-control" value="" >
                        </div>
                        <div class="col-sm-9">
                            <input type="text" name="eremarkh" class="form-control" maxlength="60"  value="" >
                        </div>
                    </div> 
                </div>  
                

            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th>Kode Bahan Baku</th>
                            <th>Nama Bahan Baku</th>
                            <th>Qty</th>
                            <th>Gelar</th>
                            <th>Set</th>
                            <th>Jml Gelar</th>
                            <th>Panjang Kain</th>
                        </tr>
                    </thead>
                </table>
                <input type="text" name="jml" id="jml" value="0">
            </div>    
          
        </form>
    </div>
</div>
<script>
    
$("form").submit(function (event) {
    event.preventDefault();
});

$(document).ready(function () {
$(".select2").select2();
});

$(document).ready(function () {
  $('.select2').select2();
  showCalendar('.date');
});

// function removeBody(){
//     var tbl = document.getElementById("tabledata");   // Get the table
//     tbl.removeChild(tbl.getElementsByTagName("tbody")[0]);
//  }

function get(id) {
    // removeBody(); 
        $.ajax({
            type: "post",
            data: {
                'ischedule': id
            },
            url: '<?= base_url($folder.'/cform/getschedule'); ?>',
            dataType: "json",
            success: function (data) {   
                $('#dschedule').val(data['data'][0].d_schedule);
                $('#ispbb').val(data['data'][0].i_spbb);
                
               // alert($('#jml').val(data['jmlitem']));
                $("#tabledata").attr("hidden", false);
                for (let a = 0; a <= data['jmlitem']; a++) {
                    var no = a+1;
                    var produk      = data['brgop'][a]['i_product'];
                    var namaproduk  = data['brgop'][a]['e_product_name'];
                    var warna       = data['brgop'][a]['warna'];
                    var color       = data['brgop'][a]['i_color'];
                    var material    = data['brgop'][a]['material_name'];
                    var imaterial   = data['brgop'][a]['i_material'];
                    var qty         = data['brgop'][a]['n_quantity'];
                    var gelar       = data['brgop'][a]['v_gelar'];
                    var set         = data['brgop'][a]['v_set'];
                    var totalgelar  = data['brgop'][a]['total_gelar'];
                    var pgelar      = data['brgop'][a]['panjang_kain'];
                    var fbisbisan   = data['brgop'][a]['f_bisbisan'];
                    var cols        = "";

                    var newRow = $("<tr>");

                    if(fbisbisan =='t'){
                        cols += '<td><input style="width:30px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input readonly style="width:60px;"  type="text" id="fbisbisan'+a+'" name="fbisbisan'+a+'" value="'+fbisbisan+'"></td>';
                        cols += '<td><input style="width:80px;" class="form-control" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+produk+'"></td>';
                        cols += '<td><input style="width:250px;" class="form-control" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+namaproduk+'"></td>';
                        cols += '<td><input readonly style="width:70px;" class="form-control" type="text" id="warna'+a+'" name="warna'+a+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+a+'" name="icolor'+a+'" value="'+color+'"></td>';
                        cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"><input readonly style="width:150px;"  type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+material+'"></td>';
                        cols += '<td><input style="width:40px;" class="form-control" type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+qty+'" onkeyup="hitungbisbisan(this.value,'+a+');"></td>';
                        cols += '<td><input readonly style="width:60px;" class="form-control" type="text" id="vgelar'+a+'" name="vgelar'+a+'" value="'+gelar+'"></td>';
                        cols += '<td><input readonly style="width:60px;" class="form-control" type="text" id="vset'+a+'" name="vset'+a+'" value="'+set+'"></td>';
                        cols += '<td><input readonly style="width:60px;" class="form-control" type="text" id="jumgelar'+a+'" name="jumgelar'+a+'" value="number_format('+totalgelar+',2)" onkeyup="reformat(this);"</td>';
                        cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="pjgkain'+a+'" name="pjgkain'+a+'" value="'+pgelar+'" onkeyup="hitungbisbisan2(this.value,'+a+');"></td>';

                    }else{
                        cols += '<td><input style="width:40px;" class="form-control" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input readonly style="width:60px;"  type="hidden" id="fbisbisan'+a+'" name="fbisbisan'+a+'" value="'+fbisbisan+'"></td>';
                        cols += '<td><input style="width:100px;" class="form-control" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+produk+'"></td>';
                        cols += '<td><input style="width:250px;" class="form-control" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+namaproduk+'"></td>';
                        cols += '<td><input readonly style="width:100px;" class="form-control" type="text" id="warna'+a+'" name="warna'+a+'" value="'+warna+'"><input readonly style="width:70px;"  type="hidden" id="icolor'+a+'" name="icolor'+a+'" value="'+color+'"></td>';
                        cols += '<td><input readonly style="width:150px;" class="form-control" type="text" id="imaterial'+a+'" name="imaterial'+a+'" value="'+imaterial+'"></td>';
                        cols +='<td><input readonly style="width:350px;" class="form-control" type="text" id="ematerial'+a+'" name="ematerial'+a+'" value="'+material+'"></td>';
                        cols += '<td><input style="width:70px;" class="form-control" type="text" id="nquantity'+a+'" name="nquantity'+a+'" value="'+qty+'" onkeyup="hitungnilai3(this.value,'+a+');"></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control" type="text" id="vgelar'+a+'" name="vgelar'+a+'" value="'+gelar+'"></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control" type="text" id="vset'+a+'" name="vset'+a+'" value="'+set+'"></td>';
                        cols += '<td><input readonly style="width:80px;" class="form-control" type="text" id="jumgelar'+a+'" name="jumgelar'+a+'" value="'+totalgelar+'" onkeyup="hitungnilai2(this.value,'+a+'); reformat(this);"></td>';
                        cols += '<td><input readonly style="width:120px;" class="form-control" type="text" id="pjgkain'+a+'" name="pjgkain'+a+'" value="'+pgelar+'" onkeyup="hitungnilai(this.value,'+a+');"></td>';
                    }

                    console.log(produk);
                                        
        newRow.append(cols);
        $("#tabledata").append(newRow);
                }
                $('#jml').val(no);
              
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

function hitungbisbisan(isi,jml){       
      i=jml;  
      qty       =formatulang(document.getElementById("nquantity"+i).value);
      vgelar    =formatulang(document.getElementById("vgelar"+i).value);
      vset      =formatulang(document.getElementById("vset"+i).value);
      bagibis   =formatulang(document.getElementById("jumgelar"+i).value);
      if(qty=='')qty=0;
      pjngkain  =(parseFloat(qty)*parseFloat(vgelar)*parseFloat(vset))/parseFloat(bagibis);
      document.getElementById("pjgkain"+i).value=(pjngkain).toFixed(2);
} 

function hitungbisbisan2(isi,jml){    
    i=jml;
    pjgkain=formatulang(document.getElementById("pjgkain"+i).value);
    ngelar=formatulang(document.getElementById("vgelar"+i).value);
    nset=formatulang(document.getElementById("vset"+i).value);
    bagibis=formatulang(document.getElementById("jumgelar"+i).value);
    if(pjgkain=='')pjgkain=0;
    hasil=((parseFloat(pjgkain)*parseFloat(bagibis))/parseFloat(nset))/parseFloat(ngelar);
    document.getElementById("nquantity"+i).value=(hasil);
 } 

 function hitungnilai(isi,jml){   
        i=jml;
        pjgkain=formatulang(document.getElementById("pjgkain"+i).value);
        ngelar=formatulang(document.getElementById("vgelar"+i).value);
        nset=formatulang(document.getElementById("vset"+i).value);
        if(pjgkain=='')pjgkain=0;
        gelar=((parseFloat(pjgkain)*100)/parseFloat(ngelar))/100;
        hasil=(((parseFloat(pjgkain)*100)/parseFloat(ngelar))*parseFloat(nset))/100;
        document.getElementById("jumgelar"+i).value=(gelar).toFixed(2);
        document.getElementById("nquantity"+i).value=(hasil);
  } 

  function hitungnilai2(isi,jml){   
        i=jml;
        jumgelar=formatulang(document.getElementById("jumgelar"+i).value);
        vgelar=formatulang(document.getElementById("vgelar"+i).value);
        vset=formatulang(document.getElementById("vset"+i).value);
        if(jumgelar=='')jumgelar=0;
        pjgkain=parseFloat(jumgelar)*parseFloat(vgelar);
        hasil=parseFloat(jumgelar)*parseFloat(vset);
        document.getElementById("pjgkain"+i).value=(pjgkain).toFixed(2);
        document.getElementById("nquantity"+i).value=(hasil);
  } 

  function hitungnilai3(isi,jml){   
      i=jml;     
      qty=formatulang(document.getElementById("nquantity"+i).value);
      vgelar=formatulang(document.getElementById("vgelar"+i).value);
      vset=formatulang(document.getElementById("vset"+i).value);
      if(qty=='')qty=0;
      jmlgelar=parseFloat(qty)/parseFloat(vset);
      pjngkain=(parseFloat(qty)/parseFloat(vset))*parseFloat(vgelar);
      document.getElementById("jumgelar"+i).value=(jmlgelar).toFixed(2);
      document.getElementById("pjgkain"+i).value=(pjngkain).toFixed(2);
  } 

$(document).ready(function () {
    $('#ischedule').select2({
    placeholder: 'Pilih Schedule',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/schedule'); ?>',
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

$(document).ready(function () {
    $('#igudang').select2({
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