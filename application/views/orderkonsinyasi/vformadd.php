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
                        <label class="col-md-12">Tanggal</label>
                            <div class="col-sm-3">
                                <input readonly class="form-control date" id="dorderpb" name="dorderpb" value="<?php echo $tgl; ?>">
		                        <input type="hidden" class="form-control" id="dakhir" name="dakhir" value="">
		                        <input type="hidden" class="form-control" id="dnotapbx" name="dnotapbx" value="<?php echo $tgl; ?>"></td>
		                        <input type="hidden" class="form-control" id="iorderpb" name="iorderpb" value="<?php if($iorderpb) echo $iorderpb; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" class="form-control" name="eareaname" value="<?= $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" class="form-control" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">SPG</label>
                        <div class="col-sm-6">
                            <input readonly id="espgname" name="espgname" class="form-control" value="<?= $isi->e_spg_name; ?>">
                            <input id="ispg" name="ispg" type="hidden" class="form-control" value="<?= $isi->i_spg; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-6">
                            <input readonly id="ecustomername" name="ecustomername" class="form-control" value="<?= $isi->e_customer_name; ?>">
                            <input id="icustomer" name="icustomer" type="hidden" class="form-control" value="<?= $isi->i_customer; ?>">
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
                                <th>Order</th>
                                <th>Stock</th>
                                <th>Keterangan</th>
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

    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        var icustomer = $("#icustomer").val();
        
        var cols = "";
        
        cols+='<td><input readonly style="width:40px;" type="text" id="baris'+counter+'" name="baris'+counter+'"  class="form-control" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""></td>';
        cols+='<td><select style="width:200px;" readonly type="text" id="iproduct'+counter+'" name="iproduct'+counter+'"  class="form-control select2" value="" onchange="getproduct('+counter+')"></select></td>';
        cols+='<td><input readonly style="width:300px;" readonly type="text" id="eproductname'+counter+'" name="eproductname'+counter+'"  class="form-control" value=""></td>';
        cols+='<td><input style="text-align:right; width:100px;" type="text" id="nquantityorder'+counter+'" name="nquantityorder'+counter+'"  class="form-control" value="" onkeyup="hitungnilaiorder();"></td>';
        cols+='<td><input style="text-align:right; width:100px;" type="text" id="nquantitystock'+counter+'" name="nquantitystock'+counter+'"  class="form-control" value="" onkeyup="hitungnilaistock();"></td>';
        cols+='<td><input style="text-align:right; width:100px;" type="text" id="eremark'+counter+'" name="eremark'+counter+'"  class="form-control" value=""></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger " value="Delete"></td>';

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
          url: '<?= base_url($folder.'/cform/databarang/'); ?>'+icustomer,
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

    function getproduct(id){
        var iproduct = $('#iproduct'+id).val();
        var icustomer = $("#icustomer").val();
        $.ajax({
        type: "post",
        data: {
            'i_product': iproduct
        },
        url: '<?= base_url($folder.'/cform/getproduct/'); ?>'+icustomer,
        dataType: "json",
        success: function (data) {
            $('#iproduct'+id).val(data[0].i_product);
            $('#eproductname'+id).val(data[0].e_product_name);
            $('#motif'+id).val(data[0].i_product_motif);
            //hitungnilai();
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

    function hitungnilaiorder(){
        jml=document.getElementById("jml").value;
		for(i=1;i<=jml;i++){
            qty=document.getElementById("nquantityorder"+i).value;
            if (isNaN(parseFloat(qty))){
              alert("Input harus numerik");
              document.getElementById("nquantityorder"+i).value='0';
              break;
            }
        }
  }
  function hitungnilaistock(){
    jml=document.getElementById("jml").value;
	for(i=1;i<=jml;i++){
        qty=document.getElementById("nquantitystock"+i).value;
        if (isNaN(parseFloat(qty))){
          alert("Input harus numerik");
          document.getElementById("nquantitystock"+i).value='0';
          break;
        }
    }
  }

    function dipales(a){
        cek='false';
	      if((document.getElementById("dorderpb").value!='')) {
 	     	  if(a==0){
  	     		alert('Isi data item minimal 1 !!!');
	     	  }else{
   	    		for(i=1;i<=a;i++){
	    	      if((document.getElementById("iproduct"+i).value=='') ||
	    		      (document.getElementById("eproductname"+i).value=='') ||
	    		      (document.getElementById("nquantityorder"+i).value=='') ||
                (document.getElementById("nquantitystock"+i).value=='')){
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
</script>