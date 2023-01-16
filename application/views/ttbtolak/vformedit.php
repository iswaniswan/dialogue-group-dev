<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="row">
                        <label class="col-md-12">TTB</label>
                        <div class="col-sm-6">
                            <input type="text" id = "ittb" name="ittb" id = "ittb" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id = "dttb" name="dttb" class="form-control date" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Terima Sales</label>
                        <div class="col-sm-7">
                            <input type="text" id = "dreceive1" name="dreceive1" class="form-control date"  readonly value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                        <input type="hidden" name="iarea" id="iarea" value="<?= $data->i_area; ?>">
                        <input type="text" name="eareaname" class="form-control" required="" maxlength="30" onkeyup="gede(this)" value="<?= $data->e_area_name; ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pelanggan</label>
                        <div class="col-sm-12">
                        <input type="hidden" name="icustomer" id="icustomer" value="<?= $data->i_customer; ?>">
                        <input type="text" id = "ecustomername" name="ecustomername" class="form-control"  value="<?= $data->e_customer_name; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">NPWP</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ecustomerpkpnpwp "name="ecustomerpkpnpwp" class="form-control" maxlength="30" 
                            value="<?= $data->e_customer_pkpnpwp; ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                        <input type="hidden" name="isalesman" id="isalesman" value="<?= $data->i_salesman; ?>">
                            <input type="text" id="esalesmanname" name="esalesmanname" class="form-control" required="" value="<?= $data->e_salesman_name; ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;</button>
                            
                        </div>
                    </div> -->
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="row">
                            <label class="col-md-12">SJ</label>
                            <div class="col-sm-6">
                                <input id ="isj" name="isj" class="form-control" required=""
                                value="<?= $data->i_sj; ?>"readonly>
                            </div>
                            <div class="col-sm-6">
                                <input id = "dsj" name="dsj" class="form-control" required=""
                                value="<?= $data->d_sj; ?>"readonly>
                            </div>
                    </div>
                    <div class="row">
                            <label class="col-md-12">Nota</label>
                            <div class="col-sm-6">
                                <input id ="inota" name="inota" class="form-control" required=""
                                value="<?= $data->i_nota; ?>"readonly>
                            </div>
                            <div class="col-sm-6">
                                <input id = "dnota" name="dnota" class="form-control" required=""
                                value="<?= $data->d_nota; ?>"readonly>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-12">
                            <input type="text" id = "vttbgross" name="vttbgross" class="form-control" required="" readonly
                                value="0">
                        </div>
                    </div>
                    <div class="row">
                            <label class="col-md-12">Discount 1</label>
                            <div class="col-sm-6">
                                <input id ="nttbdiscount1" name="nttbdiscount1" class="form-control" required=""
                                value="<?= $data->n_nota_discount1; ?>"readonly>
                            </div>
                            <div class="col-sm-6">
                                <input id = "vttbdiscount1" name="vttbdiscount1" class="form-control" required=""
                                value="0"readonly>
                            </div>
                    </div>
                    <div class="row">
                            <label class="col-md-12">Discount 2</label>
                            <div class="col-sm-6">
                                <input id ="nttbdiscount2" name="nttbdiscount2" class="form-control" required=""
                                value="<?= $data->n_nota_discount2; ?>"readonly>
                            </div>
                            <div class="col-sm-6">
                                <input id = "vttbdiscount2" name="vttbdiscount2" class="form-control" required=""
                                value="0"readonly>
                            </div>
                    </div>
                    <div class="row">
                            <label class="col-md-12">Discount 3</label>
                            <div class="col-sm-6">
                                <input id ="nttbdiscount3" name="nttbdiscount3" class="form-control" required=""
                                value="<?= $data->n_nota_discount3; ?>"readonly>
                            </div>
                            <div class="col-sm-6">
                                <input id = "vttbdiscount3" name="vttbdiscount3" class="form-control" required=""
                                value="0"readonly>
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Discount Total</label>
                        <div class="col-sm-12">
                        <input id="vttbdiscounttotal" name="vttbdiscounttotal" class="form-control" 
                                value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Bersih</label>
                        <div class="col-sm-12">
                        <input id="vttbnetto" name="vttbnetto" class="form-control" required="" 
                                readonly value="0">
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
                                            <th>Harga</th>
                                            <th>Jumlah SJ</th>
                                            <th>Jumlah Tolak</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?$i = 0;
                                        foreach ($data2 as $row) {
                                        $i++;?>
                                        <tr>
                                        <td class="col-sm-1" >
                                            <input type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" >
                                        </td>
                                        <td class="col-sm-1">
                                            <input type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" >
                                        </td>
                                        <td class="col-sm-1">
                                            <input type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" >
                                        </td>
                                        <td class="col-sm-1">
                                            <input type="text" id="vproductretail<?=$i;?>" name="vproductretail<?=$i;?>"value="<?= $row->v_unit_price; ?>" >
                                        </td>
                                        <td class="col-sm-1">
                                            <input type="text" id="ndeliver<?=$i;?>" name="ndeliver<?=$i;?>"value="<?= $row->n_deliver; ?>" >
                                        </td>
                                        <td class="col-sm-1">
                                        
                                            <input type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" onkeyup="cekval(this.value);" >
                                            
                                        </td>
                                        <td class="col-sm-1">
                                    
                                            <input type="text" id="eremark<?=$i;?>" name="e_remark<?=$i;?>"value="" >
                                        </td>
                                        </tr>
                                        
                                        <?}?>
                                        <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
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
    
     var jml   = parseFloat(document.getElementById("jml").value);
     var num = input.replace(/\,/g,'');
     if(!isNaN(num)){
        for(j=1;j<=jml;j++){
            
           if(document.getElementById("nquantity"+j).value==''){
              document.getElementById("nquantity"+j).value='0';}
             var jml    = parseFloat(document.getElementById("jml").value);
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
 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
 });
$(document).ready(function () {
    var counter = 0;

    

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
    });
});
    $(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
    });
</script>