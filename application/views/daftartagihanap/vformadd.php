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
                        <label class="col-md-12">No</label>
                            <div class="col-sm-6">
                                <input type="text" id="idtap" name="idtap" class="form-control"  placeholder="No Hutang Dagang" value="">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="ddtap" name="ddtap" class="form-control date"   placeholder="Tanggal Referensi" value="">
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-6">
                            <select id="isupplier" name="isupplier" class="form-control select2" value=""  onchange="get(this.value);tglpajak();"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input readonly type='text' id="esupplieraddress" name="esupplieraddress"  placeholder="Alamat Supplier" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input readonly type='text' id="esuppliercity" name="esuppliercity"  placeholder="Kota Supplier" class="form-control" value="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Faktur Pajak</label>
                        <div class="col-sm-6">
                            <input type="text" id="ipajak" name="ipajak" class="form-control" value="" placeholder="No Faktur Pajak" onfocus="tglpajak();">
                        </div>
                        <div class="col-sm-4">
                            <input readonly id="dpajak" name="dpajak" class="form-control"  placeholder="Tanggal Faktur Pajak" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <select id="iarea" name="iarea" class="form-control select2" value=""></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jatuh Tempo</label>
                        <div class="col-sm-4">
                            <input readonly type="text" id="dduedate" name="dduedate"  placeholder="Tanggal Jatuh Tempo" class="form-control"  value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                            <input readonly type="text" id="vgross" name="vgross" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Potongan</label>
                        <div class="col-sm-2">
                            <input type="text" id="ndiscount" name="ndiscount" class="form-control" value="0" onkeyup="diskon();"> 
                        </div>
                        <div class="col-sm-5">
                            <input readonly type="text" id="vdiscount" name="vdiscount" class="form-control" value="0" onkeyup="diskon();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">PPN 10%</label>
                        <div class="col-sm-6">
                            <input readonly type="hidden" id="fsupplierpkp" name="fsupplierpkp" class="form-control" value="0">
                            <input readonly type="text" id="vppn" name="vppn" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jumlah Bayar</label>
                        <div class="col-sm-6">
                            <input readonly type="text" id="vnetto" name="vnetto" class="form-control" value="0">
                        </div>
                    </div>
                    </div>
                    <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>DO</th>
                                        <th>Tanggal DO</th>
                                        <th>Supplier</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
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
        $('#isupplier').select2({
        placeholder: 'Pilih Supplier',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_supplier'); ?>',
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
        var kode = $('#isupplier').text();
     });
    });

    $(document).ready(function () {
        $('#iarea').select2({
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
     });
    });

    function get(id) {
        /*alert(iarea);*/
        $.ajax({
            type: "post",
            data: {
                'i_supplier': id
            },
            url: '<?= base_url($folder.'/cform/getsup'); ?>',
            dataType: "json",
            success: function (data) {
                $('#isupplier').val(data[0].i_supplier);
                $('#esuppliername').val(data[0].e_supplier_name);
                $('#esupplieraddress').val(data[0].e_supplier_address);
                $('#esuppliercity').val(data[0].e_supplier_city);
                $('#fsupplierpkp').val(data[0].f_supplier_pkp);
                $('#nsuppliertoplength').val(data[0].n_supplier_toplength);
                ddtap=document.getElementById("ddtap").value;
                if(ddtap.length>0 && data[0].n_supplier_toplength!='' && data[0].n_supplier_toplength!='0' && data[0].n_supplier_toplength!=0){
                  tmp=ddtap.split('-');
                  ddtap=tmp[2]+'-'+tmp[1]+'-'+tmp[0];
                  var today = new Date(ddtap);
                  today.setDate(today.getDate() + parseInt(data[0].n_supplier_toplength));
                  document.getElementById("dduedate").value=formatDate(today);
                }else{
                  document.getElementById("dduedate").value=document.getElementById("ddtap").value;
                }
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        var isupplier = $("#isupplier").val();
        var iarea     = $("#iarea").val();
        var ddtap     = $("#ddtap").val();
        
        var cols = "";
        
        cols += '<td><input readonly style=width:40px; id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
        cols += '<td><select style=width:150px; type="text" id="ido'+ counter + '" class="form-control" name="ido'+ counter + '" onchange="getdo('+ counter + ');"><input readonly type="hidden" id="iop'+ counter + '" class="form-control" name="iop'+ counter + '"/></td>';
        cols += '<td><input readonly type="text" id="ddo'+ counter + '" class="form-control" name="ddo'+ counter + '"/></td>';
        cols += '<td><input readonly style=width:200px; type="text" id="esuppliername'+ counter + '" class="form-control" name="esuppliername'+ counter + '"/></td>';
        cols += '<td><input readonly type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct' + counter + '"/></td>';
        cols += '<td><input readonly style=width:200px; type="text" id="eproductname'+ counter + '" class="form-control" name="eproductname' + counter + '"/></td>';
        cols += '<td><input readonly type="text" id="ndeliver'+ counter + '" class="form-control" name="ndeliver' + counter + '"/></td>';
        cols += '<td><input readonly type="text" id="vunitprice'+ counter + '" class="form-control" name="vunitprice' + counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';

        newRow.append(cols);
        $("#tabledata").append(newRow);

        $("#tabledata").on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();       
            counter -= 1
            document.getElementById("jml").value = counter;
        });
       
        $('#ido'+ counter).select2({
        placeholder: 'Pilih Kode DO',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datado/'); ?>'+isupplier +'/' +iarea +'/' +ddtap,
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

    function getdo(id){
        var ido = $('#ido'+id).val();
        var isupplier = $("#isupplier").val();
        var ddtap     = $("#ddtap").val();
        $.ajax({
        type: "post",
        data: {
            'i_do': ido
        },
        url: '<?= base_url($folder.'/cform/getdoitem/'); ?>'+isupplier +'/' +ddtap,
        dataType: "json",
        success: function (data) {
            $('#ido'+id).val(data[0].i_do);
            $('#ddo'+id).val(data[0].d_do);
            $('#isupplier'+id).val(data[0].i_supplier);
            $('#esuppliername'+id).val(data[0].e_supplier_name);
            $('#fsupplierpkp'+id).val(data[0].f_supplier_pkp);
            $('#iproduct'+id).val(data[0].i_product);
            $('#eproductname'+id).val(data[0].e_product_name);
            $('#ndeliver'+id).val(data[0].n_deliver);
            $('#vunitprice'+id).val(data[0].v_product_mill);
            $('#iop'+id).val(data[0].i_op);
            hitung();
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function hitung(){
        jml=document.getElementById("jml").value;
        gross=0;
        for(i=1;i<=jml;i++){
          qty=formatulang(document.getElementById("ndeliver"+i).value);
          val=formatulang(document.getElementById("vunitprice"+i).value);
          sub=qty*val;
          gross=gross+sub;
        }
	    document.getElementById("vgross").value = formatcemua(gross);
	    persen = formatulang(document.getElementById("ndiscount").value);
	    nilai  = formatulang(document.getElementById("vdiscount").value);
	    if ( (isNaN(parseFloat(persen))) || (isNaN(parseFloat(nilai))) ){
	    	alert("Input harus numerik");
	    }else{
	    	persen = parseFloat(persen);
	    	nilai  = parseFloat(nilai);
	    	gross  = parseFloat(gross);
	    	bersih = 0;
	    	if(persen!=0){
	    	  nilai  = Math.round(((gross/100)*persen)*1)/1;
              alert(nilai);
	    	  document.getElementById("vdiscount").value = formatcemua(nilai);
	    	}else{
	    	  persen = Math.round((nilai/(gross/100))*1)/1;
	    	}
	    	if(document.getElementById("fsupplierpkp").value!='f'){
	    		ppn = Math.round(((gross-nilai)*0.1)*1)/1;
	    	}else{
	    		ppn = 0;
	    	}
	    	bersih = gross-nilai+ppn;
	    	document.getElementById("vppn").value      = formatcemua(ppn);
	    	document.getElementById("vnetto").value    = formatcemua(bersih);
	    }
    }

    function diskon(){
	  gross  = formatulang(document.getElementById("vgross").value);
	  persen = formatulang(document.getElementById("ndiscount").value);
	  nilai  = formatulang(document.getElementById("vdiscount").value);
	    if ((isNaN(parseFloat(persen))) || (isNaN(parseFloat(nilai)))){
		  alert("Input harus numerik");
	    }else{
		  persen = parseFloat(persen);
		  nilai  = parseFloat(nilai);
		  gross  = parseFloat(gross);
		  bersih = 0;
		    if(persen!=0){
			  nilai  = Math.round(((gross/100)*persen)*1)/1;
			  document.getElementById("vdiscount").value = formatcemua(nilai);
		    }else{
			  persen = Math.round((nilai/(gross/100))*1)/1;
            }
		    if(document.getElementById("fsupplierpkp").value!='f'){
		    	ppn = Math.round(((gross-nilai)*0.1)*1)/1;
		    }else{
		    	ppn = 0;
		    }
		    bersih = gross-nilai+ppn;
            bersih = Math.round(bersih);
		    document.getElementById("vppn").value      = formatcemua(ppn);
		    document.getElementById("vnetto").value    = formatcemua(bersih);
	    }
    }

  function tglpajak(){
    document.getElementById("dpajak").value=document.getElementById("ddtap").value;
  }

  function dipales(a){
  	cek='false';
  	if((document.getElementById("idtap").value!='') &&
  		(document.getElementById("ddtap").value!='') &&
  		(document.getElementById("iarea").value!='') &&
		  (document.getElementById("isupplier").value!='') &&
			(document.getElementById("dduedate").value!='') &&
			(document.getElementById("vnetto").value!='0')) {
  	  if(a==0){
  	 		alert('Isi data item minimal 1 !!!');
  	 	}else{
    		for(i=1;i<=a;i++){
				  if((document.getElementById("ido"+i).value==''))
				  {
					  alert('Data item masih ada yang salah !!!');
					  cek='false';
					  exit();
				  }else{
					  cek='true';
				  }
			  }
		  }
		  if(cek=='true'){
    		document.getElementById("login").disabled=true;
    		document.getElementById("cmdtambahitem").disabled=true;
		  }
    }else{
   		alert('Data header masih ada yang salah !!!');
    }
  }
</script>