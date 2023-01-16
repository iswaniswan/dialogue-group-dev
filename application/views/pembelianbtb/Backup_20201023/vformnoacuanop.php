<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/save'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-7">
                        <input type="hidden" id= "isupplier" name="isupplier" class="form-control" required=""
                            onkeyup="gede(this)" value="<?=$getpkp->i_supplier;?>">
                        <input type="text" id= "esuppliername" name="esuppliername" class="form-control" required=""
                        onkeyup="gede(this)" value="<?=$getpkp->e_supplier_name;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">PKP</label>
                        <div class="col-sm-12">
                        <!-- <?php 
		  					if($getpkp->f_supplier_pkp=='t'){
                                $check = "checked";
                            } else {
                                $check = "";
                            }
                        ?> -->
                            <input type="checkbox" name="pkp" id="pkp" value="<?=$getpkp->f_supplier_pkp;?>" class="tinggi" style="position: relative;bottom: 2px;" 
                            onclick="hitungnilai();">&nbsp;&nbsp;&nbsp;<span id="topnya"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Include</label>
                        <div class="col-sm-12">
                        <select name="tipepajak" id="tipepajak" class="form-control select2" onchange="hitungnilai();"readonly>
                        <option value="I" <?php if ($getpkp->f_tipe_pajak == 'I' ) echo 'selected' ; ?>>Include</option>
		  					<option value="E" <?php if ($getpkp->f_tipe_pajak == 'E' ) echo 'selected' ; ?>>Exclude</option>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis pembayaran</label>
                        <div class="col-sm-12">
                        <select name="paymenttype" id="paymenttype" class="form-control"readonly>
                        <option value='0' <?php if($jenisp=='0') echo "Selected"?>>Cash</option>
                        <option value='1' <?php if($jenisp=='1') echo "Selected"?>>Kredit</option>
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id="eremark" name="eremark" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;</button>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">NO SJ</label>
                        <div class="col-sm-12">
                            <input type="text" id="isj" name="isj" class="form-control" required=""
                                value="0">
                        </div>
                    </div>
                    <div class="row">
                            <label class="col-md-12">Tanggal SJ</label>
                            <div class="col-sm-12">
                                <input id="dsj" name="dsj" class="form-control date" required="">
                            </div>
                    </div>
                    <div class="row">
                            <label class="col-md-12">DPP</label>
                            <div class="col-sm-12">
                                <input id="totdpp" name="totdpp" class="form-control" required=""
                                 readonly value="0"readonly>
                            </div>
                    </div>
                    <div class="row">
                            <label class="col-md-12">PPN</label>
                            <div class="col-sm-12">
                                <input id="totppn" name="totppn" class="form-control" required=""
                                 readonly value="0"readonly>
                            </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-md-12">Grand Total OP</label>
                        <div class="col-sm-12">
                        <input  id="grandtotop" name="grandtotop" class="form-control" required="" 
                                value="0"readonly>
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label class="col-md-12">Grand Total</label>
                        <div class="col-sm-12">
                        <input name="grandtot" id="grandtot" class="form-control" required="" 
                                readonly value="0">
                        </div>
                    </div>
                    </div>
                    <label class="col-md-12">Jumlah Data</label>
                    <input type="hidden" name="jml" id="jml"readonly>
                    
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Gudang</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>Total</th>
                                            <th>PPN</th>
                                            <th>Selisih</th>
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

<script>

    $(document).ready(function () {
        hitungnilai();
    });



    var counter = 0;

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        
        cols += '<td><select type="text" id="ikodemaster'+ counter + '" type="text" class="form-control" name="ikodemaster' + counter + '"></td>';
        cols += '<td><select  type="text" id="imaterial'+ counter + '"type="text" class="form-control" name="imaterial'+ counter + '" onchange="getmaterial('+ counter + ');"></td>';
        cols += '<td><input type="text" id="ematerialname'+ counter + '" class="form-control" name="ematerialname'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/readonly></td>';
        cols += '<td><input type="text" id="esatuan'+ counter + '" class="form-control" name="esatuan'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity' + counter + '"onkeyup=\'hitungnilai();\' value = "0"></td>';
        cols += '<td><input type="text" id="vprice'+ counter + '" class="form-control" name="vprice' + counter + '"onkeyup=\'hitungnilai();\' value = "0"></td>';
        cols += '<td><input type="text" id="diskon'+ counter + '" class="form-control" name="diskon' + counter + '"onkeyup=\'hitungnilai();\' value = "0"></td>';
        cols += '<td><input type="text" id="vtotal'+ counter + '" class="form-control" name="vtotal' + counter + '"/readonly></td>';
        cols += '<td><input type="text" id="ppn'+ counter + '" class="form-control" name="ppn' + counter + '"/readonly></td>';
        cols += '<td><input type="text" id="selisih'+ counter + '" class="form-control" name="selisih' + counter + '"/readonly></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        cols += '<td><input type="hidden" id="isatuan'+ counter + '" class="form-control" name="isatuan'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });
       
        $('#ikodemaster'+ counter).select2({
        placeholder: 'Pilih',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datagudang'); ?>',
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
      $('#imaterial'+ counter).select2({
        placeholder: 'Pilih',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datamaterial'); ?>',
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
function formatMoney(angka, decPlaces, thouSeparator, decSeparator) {
    var n = angka,
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSeparator = decSeparator == undefined ? "." : decSeparator,
    thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
    sign = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
  }
  function hitungnilai()
  {
  	var jml = $('#jml').val();
  	var pkp = $('#pkp').is(':checked');
  	var tipepajak = $('#tipepajak').val();
  	var totop=0;
  	var tot=0;
  	var selisih=0;
  	var dpp=0;
  	var ppn=0;
  	var gtotppn=0;
  	var gtotop=0;
  	var gtot=0;
  	var gtotselisih=0;

  	if(pkp)
  	{
  		for(var i=1; i<=jml; i++)
  		{
  			var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
  			qty = $('#nquantity'+i).val() || 0;
  			
  			// var hrgop = formatulang($('#vpriceop'+i).val());
  			
  			var hrg = formatulang($('#vprice'+i).val())==''?$('#vprice'+i).val(0):hrg;
  			hrg   = formatulang($('#vprice'+i).val()) || 0;	
  			
  			var diskon = $('#diskon'+i).val()==''?$('#diskon'+i).val(0):diskon;
  			diskon = $('#diskon'+i).val() || 0;

  			if(tipepajak=='I')
  			{
  				// totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
	  			// $('#vtotalop'+i).val(formatcemua(totop));
	  			tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
	  			$('#vtotal'+i).val(formatcemua(tot));
	  			// selisih = totop-tot;
	  			// $('#selisih'+i).val(formatcemua(selisih));

	  			var pi = tot/1.1;
	  			ppn = tot-pi;
	  			$('#ppn'+i).val(formatMoney(ppn,2,',','.'));

	  			gtotppn += ppn;
	  			// gtotop += totop;
	  			gtot += tot;
  			} else {
  				// totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
	  			// $('#vtotalop'+i).val(formatcemua(totop));
	  			tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
	  			$('#vtotal'+i).val(formatcemua(tot));
	  			// selisih = totop-tot;
	  			// $('#selisih'+i).val(formatcemua(selisih));

	  			// pe=pajak exclude
	  			var pe = tot*0.1;
	  			$('#ppn'+i).val(formatMoney(pe,2,',','.'));
	  			var newtot = parseFloat(pe)+parseFloat(tot);

	  			// peop=pajak exclude op
	  			// var peop = totop*0.1;
	  			// var newtotop = parseFloat(peop)+parseFloat(totop);

	  			gtotppn += pe;
	  			// gtotop += newtotop;
	  			gtot += newtot;
  			}

  			// $('#grandtotop').val(formatcemua(gtotop));
	  		$('#grandtot').val(formatcemua(gtot));
	  		$('#totppn').val(formatMoney(gtotppn,2,',','.'));
	  		dpp = gtot/1.1;
	  		$('#totdpp').val(formatMoney(dpp,2,',','.'));
	  		// gtotselisih = gtotop-gtot;
	  		// $('#grandselisih').val(formatcemua(gtotselisih));
  		}
  	} else {
  		for(var i=1; i<=jml; i++)
  		{
  			var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
  			qty = $('#nquantity'+i).val() || 0;
  			
  			// var hrgop = formatulang($('#vpriceop'+i).val());

  			var hrg = formatulang($('#vprice'+i).val())==''?$('#vprice'+i).val(0):hrg;
  			hrg   = formatulang($('#vprice'+i).val()) || 0;	
  			
  			var diskon = $('#diskon'+i).val()==''?$('#diskon'+i).val(0):diskon;
  			diskon = $('#diskon'+i).val() || 0;
  			$('#ppn'+i).val(0);
  			// totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
  			// $('#vtotalop'+i).val(formatcemua(totop));
  			tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
  			$('#vtotal'+i).val(formatcemua(tot));
  			// selisih = totop-tot;
  			// $('#selisih'+i).val(formatcemua(selisih));

  			// gtotop += totop;
  			gtot += tot;
  		}
  		// $('#grandtotop').val(formatcemua(gtotop));
  		$('#grandtot').val(formatcemua(gtot));
  		$('#totppn').val(0);
	  	$('#totdpp').val(0);
  		// gtotselisih = gtotop-gtot;
  		// $('#grandselisih').val(formatcemua(gtotselisih));
  	}
  }
function cekval(input){
     var jml   = counter;
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        for(j=1;j<=jml;j++){
           if(document.getElementById("nquantity"+j).value=='')
              document.getElementById("nquantity"+j).value='0';
             var jml    = counter;
             var totdis    = 0;
             var totnil = 0;
             var hrg    = 0;
             var ndis1  = parseFloat(formatulang(document.getElementById("nttbdiscount1").value));
             var ndis2  = parseFloat(formatulang(document.getElementById("nttbdiscount2").value));
             var ndis3  = parseFloat(formatulang(document.getElementById("nttbdiscount3").value));
             
             var vdis1  = 0;
             var vdis2  = 0;
             var vdis3  = 0;
             for(i=1;i<=jml;i++){
            document.getElementById("ndeliver"+i).value=document.getElementById("nquantity"+i).value;
                vprod=parseFloat(formatulang(document.getElementById("vunitprice"+i).value));
                nquan=parseFloat(formatulang(document.getElementById("nquantity"+i).value));
               var hrgtmp  = vprod*nquan;
                hrg        = hrg+hrgtmp;
             }
             
             vdis1=vdis1+((hrg*ndis1)/100);
             vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
             vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
             vdistot = vdis1+vdis2+vdis3;
             vhrgreal= hrg-vdistot;
             
             document.getElementById("vttbdiscount1").value=formatcemua(vdis1);
             
             document.getElementById("vttbdiscount2").value=formatcemua(vdis2);
             
             document.getElementById("vttbdiscount3").value=formatcemua(vdis3);
             document.getElementById("vttbdiscounttotal").value=formatcemua(vdistot);
             document.getElementById("vttbnetto").value=formatcemua(vhrgreal);
             document.getElementById("vttbgross").value=formatcemua(hrg);
          }
    }else{
        alert('input harus numerik !!!');
      input = input.substring(0,input.length-1);
     }
  }
    var counter = 0;

    // $("#addrow").on("click", function () {
    //     counter++;
    //     document.getElementById("jml").value = counter;
    //     var newRow = $("<tr>");
        
    //     var cols = "";
        
    //     cols += '<td><select  type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getharga('+ counter + ');"></td>';
    //     cols += '<td><input type="text" id="eproductname'+ counter + '" type="text" class="form-control" name="eproductname' + counter + '"></td>';
    //     cols += '<td><input type="text" id="ndeliver'+ counter + '" class="form-control" name="ndeliver'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
    //     cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
    //     cols += '<td><input type="text" id="vunitprice'+ counter + '" class="form-control" name="vunitprice' + counter + '"/></td>';
    //     cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark' + counter + '"/></td>';
    //     cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
    //     newRow.append(cols);
    //     $("#tabledata").append(newRow);

    //     $("#tabledata").on("click", ".ibtnDel", function (event) {
    //     $(this).closest("tr").remove();       
    //     counter -= 1
    //     document.getElementById("jml").value = counter;

    // });
       
    //     $('#iproduct'+ counter).select2({
    //     placeholder: 'Pilih Nota',
    //     allowClear: true,
    //     ajax: {
    //       url: '<?= base_url($folder.'/cform/databrg'); ?>',
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
      
    // });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function get(iarea) {
        /*alert(iarea);*/
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getcust');?>",
            data:"iarea="+iarea,
            dataType: 'json',
            success: function(data){
                $("#icustomer").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
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
    function getfaktur(icustomer) {
        /*alert(iarea);*/
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getfaktur');?>",
            data:"icustomer="+icustomer,
            dataType: 'json',
            success: function(data){
                $("#inota").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
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
    function getfaktur2(id){
        // alert(id);
        // var inota = $('#inota'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_nota': id
        },
        url: '<?= base_url($folder.'/cform/getharga2'); ?>',
        dataType: "json",
        success: function (data) {
            $('#nttbdiscount1').val(data[0].n_nota_discount1);
            $('#nttbdiscount2').val(data[0].n_nota_discount2);
            $('#nttbdiscount3').val(data[0].n_nota_discount3);
            $('#isalesman').val(data[0].i_salesman);
            // $('#vunitprice'+id).val(data[0].v_product_mill);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

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
            $('#vunitprice'+id).val(data[0].v_product_mill);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
    
</script>