<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-check"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve2'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-5">NO BTB</label>
                        <label class="col-md-3">Tanggal BTB</label>
                        <label class="col-md-4">NO PP</label>
                        
                        <div class="col-sm-5">
                            <input id="ibtb" name="ibtb" class="form-control" required="" value = "<?= $data->i_btb; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="dbtb" name="dbtb" class="form-control date" required="" value="<?= $data->d_btb; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input id="ipp" name="ipp" class="form-control" required="" value = "<?= $data->i_pp; ?>" readonly>
                        </div>
                    </div>
                   <!--  <div class="form-group">
                        <label class="col-md-12">PKP</label>
                        <div class="col-sm-12">
                          <?php 
                            if($data->f_supplier_pkp = 't'){
                              $check = "checked";
                            } else {
                              $check = "";
                            }
                          ?>
                           <input type="checkbox" name="pkp" id="pkp" value="<?=$data->f_supplier_pkp?>" 
                           class="tinggi" style="position: relative;bottom: 2px;" <?php echo $check; ?>
                           onclick="hitungnilai();">&nbsp;&nbsp;&nbsp;<span id="topnya"></span>
                        </div>
                    </div> -->
                   <!--  <div class="form-group">
                        <label class="col-md-12">Include</label>
                        <div class="col-sm-12">
                        <select name="tipepajak" id="tipepajak" class="form-control select2" onchange="hitungnilai();">
                            <option value="I" <?php if ($data->f_tipe_pajak == 'I' ) echo 'selected' ; ?>>Include</option>
                            <option value="E" <?php if ($data->f_tipe_pajak == 'E' ) echo 'selected' ; ?>>Exclude</option>
                        </select>
                        </div>
                    </div> -->
                   <!--  <div class="form-group">
                        <label class="col-md-12">Jenis pembayaran</label>
                        <div class="col-sm-12">
                        <select name="paymenttype" id="paymenttype" class="form-control">
                        <option value='0' <?php if($data=='0') echo "Selected"?>>Cash</option>
                        <option value='1' <?php if($data=='1') echo "Selected"?>>Kredit</option>
                        </select>
                        </div>
                    </div> -->
                    <div class="form-group row">
                        <label class="col-md-8">NO Dokumen Supplier</label>
                        <label class="col-md-4">Tanggal Dok Supp</label>
                         <div class="col-sm-8">
                            <input id="ibtb" name="isj" class="form-control" required="" value = "<?= $data->i_sj; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dsj" name="dsj" class="form-control date" required="" value="<?= $data->d_sj; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id="eremark" name="eremark" class="form-control" required="" value = "<?= $data->e_remark; ?>" readonly>
                        </div>
                    </div>
                     <div class="form-group">
                        <?if($data->e_approval =='7'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>                           
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->e_approval =='3'){?>
                       <div class="col-sm-offset-5 col-sm-10">   
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->e_approval =='4'){?>
                        <div class="col-sm-offset-5 col-sm-10">    
                            <button type="button" disabled id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->e_approval =='6'){?>
                        <div class="col-sm-offset-5 col-sm-10">   
                            <button type="button"  id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}else if($data->e_approval =='8'){?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" disabled id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" disabled id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" disabled id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                            <button type="button" id="submit2" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledappr2();"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;&nbsp;Approve 2</button>
                        </div>
                        <?}else{?>
                        <div class="col-sm-offset-5 col-sm-10">  
                            <button type="button" id="cancel" class="btn btn-inverse btn-rounded btn-sm" onclick="return getenabledcancel();"> <i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Cancel</button>
                            <button type="button" id="change" class="btn btn-warning btn-rounded btn-sm" onclick="return getenabledchange();"> <i class="fa fa-pencil-square-o"></i>&nbsp;&nbsp;&nbsp;Change Requested</button>
                            <button type="button" id="reject" class="btn btn-danger btn-rounded btn-sm" onclick="return getenabledreject();"> <i class="fa fa-times"></i>&nbsp;&nbsp;&nbsp;Reject</button>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
                        </div>
                        <?}?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal PP</label>
                        <label class="col-md-4">NO OP</label>
                        <label class="col-md-4">Tanggal OP</label>
                        <div class="col-sm-4">
                            <input type="text" id="dpp" name="dpp" class="form-control" required="" value="<?= $data->d_pp; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input id="iop" name="iop" class="form-control" required="" value = "<?= $data->i_op; ?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" id="dop" name="dop" class="form-control" required="" value="<?= $data->d_op; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">Gudang Penerima</label>
                        <label class="col-md-4">Status Dokumen</label>
                        <div class="col-sm-4">
                            <input type="hidden" id= "isupplier" name="isupplier" class="form-control" required=""
                                onkeyup="gede(this)" value="<?= $data->i_supplier;?>">
                            <input type="text" id= "esuppliername" name="esuppliername" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_supplier_name;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                             <select name="igudang" class="form-control select2" disabled="">
                            <option value="">Pilih Gudang Penerima</option>
                            <?php foreach ($gudang as $igudang):?>
                                <option value="<?php echo $igudang->i_kode_master;?>"
                                    <?php if($igudang->i_kode_master==$data->i_kode_master) { ?> selected="selected" <?php } ?>>
                                    <?php echo $igudang->e_nama_master;?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        <div class="col-sm-4">                       
                            <input type="text" id= "istatus" name="istatus" class="form-control"  required="" readonly value="<?= $data->e_status;?>">  
                        </div>
                    </div>

                    <!-- <div class="row">
                            <label class="col-md-12">DPP</label>
                            <div class="col-sm-12">
                                <input id="totdpp" name="totdpp" class="form-control" required=""
                                 readonly value="0">
                            </div>
                    </div> -->
                    <!-- <div class="row">
                            <label class="col-md-12">PPN</label>
                            <div class="col-sm-12">
                                <input id="totppn" name="totppn" class="form-control" required=""
                                 readonly value="0">
                            </div>
                    </div> -->
                    <!-- <div class="form-group">
                        <label class="col-md-12">Grand Total OP</label>
                        <div class="col-sm-12">
                        <input  id="grandtotop" name="grandtotop" class="form-control" required="" 
                                value="0"readonly>
                        </div>
                    </div> -->
                   <!--  <div class="form-group">
                        <label class="col-md-12">Grand Total</label>
                        <div class="col-sm-12">
                        <input name="grandtot" id="grandtot" class="form-control" required="" 
                                readonly value="0">
                        </div>
                    </div> -->
                    </div>
                    <!-- <input type="hidden" name="jml" id="jml"> -->
                    
                            <div class="panel-body table-responsive">
                               <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Gudang</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Qty Eks</th>
                                            <th>Satuan Eks</th>
                                            <th>Qty In</th>
                                            <th>Satuan In</th>
                                            <!-- <th>Harga</th>
                                            <th>Diskon</th>
                                            <th>Total OP</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th>PPN</th>
                                            <th>Selisih</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?$i=0;
                            $grandtot=0;
                            $grandtotop=0;
                                    $i = 0;
                                        foreach ($data2 as $row) {
                                        $i++;?>
                                        <tr>
                                        <td class="col-sm-1">
                                            <input style ="width:40px" class="form-control" type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:250px" class="form-control" type="text" id="ekodemaster<?=$i;?>" name="ekodemaster<?=$i;?>"value="<?= $row->e_nama_master; ?>" readonly >
                                            <input style ="width:150px"type="hidden" id="ikodemaster<?=$i;?>" name="ikodemaster<?=$i;?>"value="<?= $row->i_kode_master; ?>" readonly >
                                        </td> 
                                        <td class="col-sm-1">
                                        <input style ="width:100px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                        <input style ="width:400px" class="form-control" type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:70px"type="text" id="nquantityeks<?=$i;?>" name="nquantityeks<?=$i;?>" value="<?=$row->n_qty_eks;?>" class="form-control" readonly>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style="width:100px;" type="hidden" id="isatuaneks<?=$i;?>" class="form-control" name="isatuaneks<?=$i;?>" value="<?=$row->i_satuan_eks;?>" readonly>
                                            <input style="width:100px;" type="text" id="esatuaneks<?=$i;?>" class="form-control" name="esatuaneks<?=$i;?>" value="<?=$row->satuaneks;?>" readonly>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:80px" readonly class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_qty; ?>"onkeyup= hitungnilai(this.value);>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:100px" class="form-control" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                            <input style ="width:70px" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan; ?>" readonly >
                                        </td>
                                        <!-- <td class="col-sm-1">
                                            <input style ="width:60px"type="text" id="vpriceop<?=$i;?>" name="vpriceop<?=$i;?>"value="<?= $row->v_unit_price_op; ?>"readonly>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:60px"type="text" id="diskon<?=$i;?>" name="diskon<?=$i;?>"value="0" onkeyup= hitungnilai(this.value);>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:60px"type="text" id="vtotalop<?=$i;?>" name="vtotalop<?=$i;?>"value="<?= $row->v_total_op; ?>" readonly>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:60px"type="text" id="vprice<?=$i;?>" name="vprice<?=$i;?>"value="<?= $row->v_unit_price; ?>" onkeyup= hitungnilai(this.value);>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:60px"type="text" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>"value="<?= $row->v_total; ?>" readonly>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:60px"type="text" id="ppn<?=$i;?>" name="ppn<?=$i;?>"value="0" readonly>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:60px"type="text" id="selisih<?=$i;?>" name="selisih<?=$i;?>"value="0"readonly >
                                        </td> -->
                                        <input style ="width:60px"type="hidden" id="iop<?=$i;?>" name="iop<?=$i;?>"value="<?= $row->i_op; ?>"readonly >
                                        </tr>
                                        <?}?>
                                        
                                        <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>">
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
        //hitungnilai();
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
        
        var hrgop = formatulang($('#vpriceop'+i).val());
        
        var hrg = formatulang($('#vprice'+i).val())==''?$('#vprice'+i).val(0):hrg;
        hrg   = formatulang($('#vprice'+i).val()) || 0; 
        
        var diskon = $('#diskon'+i).val()==''?$('#diskon'+i).val(0):diskon;
        diskon = $('#diskon'+i).val() || 0;
                
        if(tipepajak=='I')
        {
                  //alert("dfsf");
                  
          totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
          $('#vtotalop'+i).val(formatcemua(totop));
          tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
          $('#vtotal'+i).val(formatcemua(tot));
          selisih = totop-tot;
                  
          $('#selisih'+i).val(formatcemua(selisih));
                
          var pi = tot/1.1;
                  
          ppn = tot-pi;
                
          $('#ppn'+i).val(formatMoney(ppn,2,',','.'));
          gtotppn += ppn;
          gtotop += totop;
          gtot += tot;
        } else {
                
          totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
          $('#vtotalop'+i).val(formatcemua(totop));
          tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
          $('#vtotal'+i).val(formatcemua(tot));
          selisih = totop-tot;
          $('#selisih'+i).val(formatcemua(selisih));
                  
          // pe=pajak exclude
          var pe = tot*0.1;
          $('#ppn'+i).val(formatMoney(pe,2,',','.'));
          var newtot = parseFloat(pe)+parseFloat(tot);

          // peop=pajak exclude op
          var peop = totop*0.1;
          var newtotop = parseFloat(peop)+parseFloat(totop);

          gtotppn += pe;
          gtotop += newtotop;
          gtot += newtot;
        }

        $('#grandtotop').val(formatcemua(gtotop));
        $('#grandtot').val(formatcemua(gtot));
        $('#totppn').val(formatMoney(gtotppn,2,',','.'));
        dpp = gtot/1.1;
        $('#totdpp').val(formatMoney(dpp,2,',','.'));
        gtotselisih = gtotop-gtot;
        $('#grandselisih').val(formatcemua(gtotselisih));
      }
    } else {
      for(var i=1; i<=jml; i++)
      {
        var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
        qty = $('#nquantity'+i).val() || 0;
        
        var hrgop = formatulang($('#vpriceop'+i).val());

        var hrg = formatulang($('#vprice'+i).val())==''?$('#vprice'+i).val(0):hrg;
        hrg   = formatulang($('#vprice'+i).val()) || 0; 
        
        var diskon = $('#diskon'+i).val()==''?$('#diskon'+i).val(0):diskon;
        diskon = $('#diskon'+i).val() || 0;
        $('#ppn'+i).val(0);
        totop = (parseFloat(hrgop)*parseFloat(qty))-parseFloat(diskon);
        $('#vtotalop'+i).val(formatcemua(totop));
        tot = (parseFloat(hrg)*parseFloat(qty))-parseFloat(diskon);
        $('#vtotal'+i).val(formatcemua(tot));
        selisih = totop-tot;
        $('#selisih'+i).val(formatcemua(selisih));

        gtotop += totop;
        gtot += tot;
      }
      $('#grandtotop').val(formatcemua(gtotop));
      $('#grandtot').val(formatcemua(gtot));
      $('#totppn').val(0);
      $('#totdpp').val(0);
      gtotselisih = gtotop-gtot;
      $('#grandselisih').val(formatcemua(gtotselisih));
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

    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");
        
        var cols = "";
        
        cols += '<td><select  type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getharga('+ counter + ');"></td>';
        cols += '<td><input type="text" id="eproductname'+ counter + '" type="text" class="form-control" name="eproductname' + counter + '"></td>';
        cols += '<td><input type="text" id="ndeliver'+ counter + '" class="form-control" name="ndeliver'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control" name="nquantity'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
        cols += '<td><input type="text" id="vunitprice'+ counter + '" class="form-control" name="vunitprice' + counter + '"/></td>';
        cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark' + counter + '"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;

    });
       
        $('#iproduct'+ counter).select2({
        placeholder: 'Pilih Nota',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/databrg'); ?>',
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
            $('#nttbdiscount1').val(data.n_nota_discount1);
            $('#nttbdiscount2').val(data.n_nota_discount2);
            $('#nttbdiscount3').val(data.n_nota_discount3);
            $('#isalesman').val(data.i_salesman);
            // $('#vunitprice'+id).val(data.v_product_mill);
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
            $('#eproductname'+id).val(data.e_product_name);
            $('#vunitprice'+id).val(data.v_product_mill);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }

$("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
     $("#reject").attr("disabled", true);
     $("#change").attr("disabled", true);
     $("#cancel").attr("disabled", false);
});

function getenabledcancel() {
    swal("Berhasil", "Cancel Dokumen", "success");
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledchange() {
    swal("Berhasil", "Change Requested Dokumen", "success");
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledreject() {
    swal("Berhasil", "Reject Dokumen", "success");
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

function getenabledappr2() {
    swal("Berhasil", "Approve Dokumen", "success");
    $('#change').attr("disabled", true);
    $('#reject').attr("disabled", true);
    $('#cancel').attr("disabled", true);
    $('#submit').attr("disabled", true);
    $('#submit2').attr("disabled", true);
}

$(document).ready(function(){
    $("#cancel").on("click", function () {
        var ibtb = $("#ibtb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/cancel'); ?>",
            data: {
                     'ibtb'  : ibtb,
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
    $("#change").on("click", function () {
       var ibtb = $("#ibtb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/change'); ?>",
            data: {
                     'ibtb'  : ibtb,
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
    $("#reject").on("click", function () {
    var ibtb = $("#ibtb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/reject'); ?>",
            data: {
                     'ibtb'  : ibtb,
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
    $("#submit2").on("click", function () {
    var ibtb = $("#ibtb").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/appr2'); ?>",
            data: {
                     'ibtb'  : ibtb,
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