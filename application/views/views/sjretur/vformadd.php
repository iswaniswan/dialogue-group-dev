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
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal SJ / No SJ</label>
                        <div class="col-sm-3">
                            <input readonly type="text" id= "dsj" name="dsj" class="form-control date" required="" maxlength="7" value="">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "isjold" name="isjold" class="form-control" value="">
                            <input type="hidden" id= "isj" name="isj" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <select name="istore" id="istore" class="form-control select2" onchange="get(this.value);">
                            </select>
                            <input type="hidden" name="eareaname" id="eareaname" class="form-control" value="">
                            <input type="hidden" name="iarea" id="iarea" class="form-control" value="">
                            <input type="hidden" name="istorelocation" id="istorelocation" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai</label>
                        <div class="col-sm-6">
                            <input readonly name="vsj" id="vsj" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            &nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
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
                                        <th>Jumlah Retur</th>
                                        <th>Jumlah Terima</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="pesan"></div>
                        <input type="hidden" name="jml" id="jml">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var counter = 0;

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        var istore = $("#istore").val();
        var istorelocation = $("#istorelocation").val();
        
        var cols = "";
        cols += '<td><input style="width:40px;" readonly type="text" id="baris'+counter+'" name="baris'+counter+'" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""></td>';
        cols += '<td><select style="width:200px;" type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getharga('+ counter + ');"></td>';
        cols += '<td><input readonly select style="width:300px;" type="text" id="eproductname'+ counter + '" type="text" class="form-control" name="eproductname' + counter + '"><input type="hidden" id="emotifname'+counter+'" name="emotifname'+counter+'" value=""></td>';
        cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark'+ counter + '"/><input type="hidden" id="vproductmill'+counter+'" name="vproductmill'+counter+'" value=""></td>';
        cols += '<td><input style="width:90px;" type="text" id="nretur'+ counter + '" class="form-control" name="nretur'+ counter + '" onkeyup="hitungnilai('+counter+'); pembandingstok('+counter+');" onblur="hitungnilai('+counter+'); pembandingstok('+counter+');" onpaste="hitungnilai('+counter+'); pembandingstok('+counter+');" onchange="hitungnilai('+counter+'); pembandingstok('+counter+');"><input type="hidden" id="stok'+counter+'" name="stok'+counter+'" value="stok'+counter+'"><input type="hidden" id="vtotal'+counter+'" name="vtotal'+counter+'" value="0"></td>';
        cols += '<td><input readonly style="width:90px;" type="text" id="nreceive'+ counter + '" class="form-control" name="nreceive' + counter + '"/></td>';
        cols += '<td><input type="checkbox" name="chk'+counter+'" id="chk'+counter+'" value="on" checked onclick="pilihan(this.value,'+counter+')"</td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });
       
        $('#iproduct'+ counter).select2({
        placeholder: 'Pilih Kode Barang',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/databrg/'); ?>'+istore +'/' +istorelocation,
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
      
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });
    
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    $(document).ready(function () {
        $('#istore').select2({
        placeholder: 'Pilih Area',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_area'); ?>',
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
        var kode = $('#iarea').text();
        kode = kode.split("-");
        $('#eareaname').val(kode[1]);
     });
    });

    function getharga(id){
        var iproduct = $('#iproduct'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_product': iproduct
        },
        url: '<?= base_url($folder.'/cform/getharga'); ?>',
        dataType: "json",
        success: function (data) {
            $('#eproductname'+id).val(data[0].e_product_name);
            $('#nretur'+id).val("0");
            $('#nreceive'+id).val("0");
            $('#vproductmill'+id).val(data[0].v_product_mill);
            $('#motif'+id).val(data[0].i_product_motif);
            $('#stok'+id).val(data[0].n_quantity_stock);

            ada=false;
            var a = $('#iproduct'+id).val();
            var e = $('#motif'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
	            if((a == $('#iproduct'+i).val()) && (i!=jml)){
	            	swal ("kode : "+a+" sudah ada !!!!!");
	            	ada=true;
	            	break;
	            }else{
	            	ada=false;	   
	            }
            }
            if(!ada){
                var iproduct    = $('#iproduct'+id).val();
                var istore      = $('#istore').val();
                $.ajax({
                    type: "post",
                    data: {
                        'iproduct'  : iproduct,
                        'istore'    : istore
                    },
                    url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#eproductname'+id).val(data[0].e_product_name);
                        $('#nretur'+id).val("0");
                        $('#nreceive'+id).val("0");
                        $('#vproductmill'+id).val(data[0].v_product_mill);
                        $('#motif'+id).val(data[0].i_product_motif);
                        $('#stok'+id).val(data[0].n_quantity_stock);
                    },
                });
            }else{
                $('#iproduct'+id).html('');
                $('#iproduct'+id).val('');
                $('#eproductname'+id).val('');
                $('#nretur'+id).val('');
                $('#nreceive'+id).val('');
                $('#chk'+id).val('');
            }
        },
        error: function () {
            swal('Error :)');
        }
    });
    }

    function get(id) {
        /*alert(iarea);*/
        $.ajax({
            type: "post",
            data: {
                'i_store': id
            },
            url: '<?= base_url($folder.'/cform/getarea'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iarea').val(data[0].i_area);
                $('#eareaname').val(data[0].e_area_name);
                $('#istore').val(data[0].i_store);
                $('#istorelocation').val(data[0].i_store_location);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function pembandingstok(){
        jml=document.getElementById("jml").value;
        gud=document.getElementById("istore").value;
        for(i=1;i<=jml;i++){
          stock  =formatulang(document.getElementById("stok"+i).value);
          retur  =formatulang(document.getElementById("nretur"+i).value);
          if(parseFloat(stock)<0)
            stock=0;
          if(gud!='PB'){
            if(parseFloat(retur)>parseFloat(stock)){
              alert('Jumlah Retur melebihi jumlah Stock');
              document.getElementById("nretur"+i).value=0;
              break;
            }
          }
        }
    }

    function hitungnilai(brs){
        var tot=0;
        var nretur = $("#nretur"+brs).val();
	    if (isNaN(parseFloat(nretur))){
	    	  alert("Input harus numerik");
	    }else{
            var hrg = formatulang($("#vproductmill"+brs).val());
	    	qty=formatulang(nretur);
	    	vhrg=parseFloat(hrg)*parseFloat(qty);
            $('#vtotal'+brs).val(formatcemua(vhrg));
            var jml = parseFloat(document.getElementById("jml").value);
            for(i=1;i<=jml;i++){
              if(document.getElementById("chk"+i).value=='on'){
                tot+=parseFloat(formatulang($("#vtotal"+i).val()));
              }
            }
            $('#vsj').val(formatcemua(tot));
	    }
    }

    function dipales(a){
  	    cek='false';
  	    if((document.getElementById("iarea").value!='')) {
  	 	    if(a==0){
  	 	    	alert('Isi data item minimal 1 !!!');
  	 	    }else{
   		    	for(i=1;i<=a;i++){
		    		  if((document.getElementById("iproduct"+i).value=='') ||
		    			  (document.getElementById("eproductname"+i).value=='') ||
		    			  (document.getElementById("nretur"+i).value=='')){
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
    	    }else{
		       	document.getElementById("login").disabled=false;
		    }
        }else{
   		alert('Data header masih ada yang salah !!!');
        }
    }

    function pilihan(a,b){
      var a = $('#iproduct'+id).val();
      var b = $('#jml').val();
	  if(a==''){
		  document.getElementById("chk"+b).value='on';
	  }else{
		  document.getElementById("chk"+b).value='';
	  }
     hitungnilai(b);
    }
    
</script>
