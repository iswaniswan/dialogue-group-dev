<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/approve2'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="row">
                        <label class="col-md-12">NO PP</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ipp" name="ipp" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_pp;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-7">
                            <input type="text" id = "ikodemaster" name="ikodemaster" class="form-control" value="<?= $data->i_kode_master;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal PP</label>
                        <div class="col-sm-12">
                        <input type="text" name="dpp" id="dpp" class="form-control date" value="<?= $data->d_pp; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id = "eremark" name="eremark" class="form-control"  value="<?= $data->e_remark; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Batas Pemenuhan</label>
                        <div class="col-sm-12">
                            <input type="text" id = "dpemenuhan "name="dpemenuhan" class="form-control date" 
                            value="<?= $data->d_pp; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class=""></i>&nbsp;&nbsp;Approve</button>
                        </div>
                    </div>

                     <!-- <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i
                                    class="fa fa-plus"></i>&nbsp;&nbsp;</button>
                            
                        </div>
                    </div>  -->
                </div>
                
                    </div>
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th>Qty</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?$i = 0;
                                        foreach ($data2 as $row) {
                                        $i++;?>
                                        <tr>
                                         <td>
                                            <input style ="width:40px"; type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly>
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:160px"type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:250px"type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:150px" type="text" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:70px"type="text" id="qty<?=$i;?>" name="qty<?=$i;?>"value="<?= $row->n_quantity; ?>" >
                                        <td class="col-sm-1">
                                            <input style ="width:250px"type="text" id="eremark<?=$i;?>" name="e_remark<?=$i;?>"value="<?= $row->e_remark; ?>" >
                                        </td>
                                        </tr>
                                        
                                        <?}?>
                                        <label class="col-md-12">Jumlah Data</label>
                                        <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
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