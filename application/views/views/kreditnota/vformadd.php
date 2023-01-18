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
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="row">
                        <label class="col-md-12">No Retur || Tanggal Retur</label>
                        <div class="col-sm-6">
                            <input type="text" id= "ittb" name="ittb" class="form-control" required="" maxlength="7"
                            onkeyup="gede(this)" value=""> 
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "dttb" name="dttb" class="form-control date" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Customer</label>
                        <div class="col-sm-12">
                            <select name="icustomer" id="icustomer" class="form-control select2" onchange="getbranch(this.value);">
                                <option value="">-- Pilih Customer --</option>
                                <?php foreach ($customer as $icustomer):?>
                                <option value="<?php echo $icustomer->i_customer;?>">
                                    <?= $icustomer->i_customer." - ".$icustomer->e_customer_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Cabang</label>
                        <div class="col-sm-12">
                            <select name="ibranch" id="ibranch" class="form-control select2"onchange="getfaktur(this.value);">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No Referensi</label>
                        <div class="col-sm-12">
                            <select name="inota" id="inota" class="form-control select2" onchange = "displayddrow();">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" maxlength="30" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                           <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" disabled ><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>  

                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" disabled ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>                 
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <select name="ikodelokasi" class="form-control select2">
                                <option value="">-- Pilih Gudang --</option>
                                <?php foreach ($gudang as $ikodelokasi):?>
                                <option value="<?php echo $ikodelokasi->i_kode_lokasi;?>">
                                    <?php echo $ikodelokasi->e_nama_master;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-12">
                            <input type="text" id="vspb" name="vspb" class="form-control" required="" readonly
                                value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount Total</label>
                        <div class="col-sm-12">
                        <input  id="vspbdiscounttotal" name="vspbdiscounttotal" class="form-control" required="" 
                                value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Bersih</label>
                        <div class="col-sm-12">
                        <input id="vspbbersih" name="vspbbersih" class="form-control" required="" 
                                readonly value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Alasan Retur</label>
                        <div class="col-sm-12">
                            <select name="ialasanretur" class="form-control select2">
                                <option value="">-- Pilih Alasan Retur --</option>
                                <?php foreach ($alasan as $ialasanretur):?>
                                <option value="<?php echo $ialasanretur->i_alasan_retur;?>">
                                    <?php echo $ialasanretur->e_alasan_returname;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    </div>
                    <input type="hidden" name="jml" id="jml">
                    
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="10%">Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Warna</th>
                                            <th>Jumlah Nota</th>
                                            <th>Jumlah Retur</th>
                                            <th>Discount 1</th>
                                            <th>Discount 2</th>
                                            <th>Discount 3</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th>Action</th>
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

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        var inota = $('#inota').val();
        
        cols += '<td><select  type="text" id="iproduct'+ counter + '" class="form-control" readonly  name="iproduct'+ counter + '" onchange="getharga('+ counter + ');"></td>';
        cols += '<td><input type="text" id="eproductname'+ counter + '" type="text" class="form-control" readonly  name="eproductname' + counter + '"></td>';
        cols += '<td><input type="text" id="ecolorname'+ counter + '" type="text" class="form-control" readonly  name="ecolorname' + counter + '"></td>';
        cols += '<td><input type="text" id="nquantitystock'+ counter + '" class="form-control"  readonly  name="nquantitystock'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="ndeliver'+ counter + '" class="form-control" name="ndeliver'+ counter + '" onkeyup="hitungnilai(this.value,'+ counter +'); reformat(this);"/></td>';
        cols += '<td><input type="text" id="ncustomerdiscount1'+ counter + '" class="form-control"  readonly name="ncustomerdiscount1' + counter + '"/></td>';
        cols += '<td><input type="text" id="ncustomerdiscount2'+ counter + '" class="form-control"  readonly name="ncustomerdiscount2' + counter + '"/></td>';
        cols += '<td><input type="text" id="ncustomerdiscount3'+ counter + '" class="form-control"  readonly name="ncustomerdiscount3' + counter + '"/></td>';
        cols += '<td><input type="text" id="vproductretail'+ counter + '" class="form-control"  readonly name="vproductretail' + counter + '"/></td>';
        cols += '<td><input type="text" id="vtotal'+ counter + '" class="form-control"  readonly name="vtotal' + counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        cols += '<td><input type="hidden" id="icolor'+ counter + '" type="text" class="form-control" name="icolor' + counter + '"></td>';
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
          url: '<?= base_url($folder.'/cform/databrg/'); ?>'+inota,
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

    function displayddrow(){
        $("#addrow").attr("disabled", false);
        $("#submit").attr("disabled", false);
    }

    function getfaktur(ibranch) {
        /*alert(iarea);*/
        
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getfaktur');?>",
            data:"i_branch="+ibranch,
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
    function getbranch(icustomer) {
        /*alert(iarea);*/
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getbranch');?>",
            data:"i_customer="+icustomer,
            dataType: 'json',
            success: function(data){
                $("#ibranch").html(data.kop);
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
    function getxxx(id){
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
        var iproduct    = $('#iproduct'+id).val();
        var inota       = $('#inota').val();
        $.ajax({
        type: "post",
        data: {
            'i_product': iproduct
        },
        url: '<?= base_url($folder.'/cform/getharga/'); ?>'+inota,
        dataType: "json",
        success: function (data) {
            $('#eproductname'+id).val(data[0].e_product_name);
            $('#vproductretail'+id).val(data[0].v_unit_price);
            $('#icolor'+id).val(data[0].i_color);
            $('#ncustomerdiscount1'+id).val(data[0].n_customer_discount1);
            $('#ncustomerdiscount2'+id).val(data[0].n_customer_discount2);
            $('#ncustomerdiscount3'+id).val(data[0].n_customer_discount3);
            $('#ecolorname'+id).val(data[0].e_color_name);
            $('#nquantitystock'+id).val(data[0].n_quantity);

            ada=false;
            var a = $('#iproduct'+id).val();
            var c = $('#eproductname'+id).val();
            var e = $('#icolor'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
	            if((a == $('#iproduct'+i).val()) && (c == $('#eproductname'+i).val()) && (e == $('#icolor'+i).val()) && (i!=jml)){
	            	swal ("kode : "+a+" sudah ada !!!!!");
	            	ada=true;
	            	break;
	            }else{
	            	ada=false;	   
	            }
            }
            // if(!ada){
            //     var iproduct    = $('#iproduct'+id).val();
            //     $.ajax({
            //         type: "post",
            //         data: {
            //             'iproduct'  : iproduct,
            //         },
            //         url: '<?= base_url($folder.'/cform/getharga/'); ?>'+inota,
            //         dataType: "json",
            //         success: function (data) {
            //             $('#eproductname'+id).val(data[0].e_product_name);
            //             $('#vproductretail'+id).val(data[0].v_unit_price);
            //             $('#icolor'+id).val(data[0].i_color);
            //             $('#ncustomerdiscount1'+id).val(data[0].n_customer_discount1);
            //             $('#ncustomerdiscount2'+id).val(data[0].n_customer_discount2);
            //             $('#ncustomerdiscount3'+id).val(data[0].n_customer_discount3);
            //             $('#ecolorname'+id).val(data[0].e_color_name);
            //             $('#nquantitystock'+id).val(data[0].n_quantity);

            //         },
            //     });
            // }
            // else{
            if(ada == true){
                $('#iproduct'+id).html('');
                $('#eproductname'+id).val('');
                $('#vproductretail'+id).val('');
                $('#icolor'+id).val('');
                $('#ncustomerdiscount1'+id).val('');
                $('#ncustomerdiscount2'+id).val('');
                $('#ncustomerdiscount3'+id).val('');
                $('#ecolorname'+id).val('');
                $('#nquantitystock'+id).val('');
            }
            // }
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
    function hitungnilai(isi, counter) {
    //   jml = document.getElementById("jml").value;
      if (isNaN(parseFloat(isi))) {
        swal("Input harus numerik");
      } else {

        dtmp1 = parseFloat(formatulang(document.getElementById("ncustomerdiscount1" + counter).value));
        dtmp2 = parseFloat(formatulang(document.getElementById("ncustomerdiscount2" + counter).value));
        dtmp3 = parseFloat(formatulang(document.getElementById("ncustomerdiscount3" + counter).value));
        vdis1 = 0;
        vdis2 = 0;
        vdis3 = 0;
        vtot = 0;
        vtotdis = 0;


        for (i = 1; i <= counter; i++) {

          vhrg = formatulang(document.getElementById("vproductretail" + i).value);


          if (isNaN(parseFloat(document.getElementById("ndeliver" + i).value))) {
            nqty = 0;


          } else {
            // if((document.getElementById("fstock").value=='f')){
            //     nqty=formatulang(document.getElementById("ndeliver"+i).value);
            //     vhrg=parseFloat(vhrg)*parseFloat(nqty);
            //     vtot=vtot+vhrg;
            //     document.getElementById("vtotal"+i).value=formatcemua(vhrg);
            //}
            // else{

            if (parseFloat(document.getElementById("nquantitystock" + i).value) < parseFloat(document.getElementById(
                "ndeliver" + i).value)) {

              swal("Lebih Dari Stock / Melebihi Qty Yang Belum Terkirim!!!");
              nqty = 0;
              vhrg = parseFloat(vhrg) * parseFloat(nqty);
              vtot = vtot + vhrg;
              document.getElementById("ndeliver" + i).value = formatcemua(vhrg);
              document.getElementById("vtotal" + i).value = formatcemua(vhrg);

            } else {

              nqty = formatulang(document.getElementById("ndeliver" + i).value);
              vhrg = parseFloat(vhrg) * parseFloat(nqty);
              vtot = vtot + vhrg;
              document.getElementById("vtotal" + i).value = formatcemua(vhrg);
              

            }
            

            // }
          } ////

        }
        
        vdis1 = vdis1 + ((vtot * dtmp1) / 100);
        // alert("asasa");
        vdis2 = vdis2 + (((vtot - vdis1) * dtmp2) / 100);
        vdis3 = vdis3 + (((vtot - (vdis1 + vdis2)) * dtmp3) / 100);
        
        // document.getElementById("vcustomerdiscount1"+jml).value=formatcemua(Math.round(vdis1));
        // document.getElementById("vcustomerdiscount2"+jml).value=formatcemua(Math.round(vdis2));
        // document.getElementById("vcustomerdiscount3"+jml).value=formatcemua(Math.round(vdis3));
        vdis1 = parseFloat(vdis1);
        vdis2 = parseFloat(vdis2);
        vdis3 = parseFloat(vdis3);
        vtotdis = vdis1 + vdis2 + vdis3;

        document.getElementById("vspbdiscounttotal").value = formatcemua(Math.round(vtotdis));
        // alert(vtotdis);
        document.getElementById("vspb").value = formatcemua(vtot);
        vtotbersih = parseFloat(formatulang(formatcemua(vtot))) - parseFloat(formatulang(formatcemua(Math.round(
          vtotdis))));
        document.getElementById("vspbbersih").value = formatcemua(vtotbersih);
      }
    }
    
</script>