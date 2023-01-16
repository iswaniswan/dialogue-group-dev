<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>                      
                <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-9">No SJ</label>
                    <label class="col-md-3">Tanggal SJ</label>
                    <div class="col-sm-9">
                        <input type="text" name="isj" class="form-control" value="<?= $data->i_sj;?>" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="dsj" class="form-control date" value="<?= $data->d_sj;?>" readonly>
                    </div>
                </div>   
                <div class="form-group row">
                <label class="col-md-9">No SJ</label>
                <label class="col-md-3">Tanggal SJ</label>
                    <div class="col-sm-9">
                        <input type="text" name="imemo" class="form-control" value="<?= $data->i_memo;?>" readonly>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" name="dmemo" class="form-control date" value="<?= $data->d_memo;?>" readonly>
                    </div>
                </div>  
                </div>
                    <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Customer</label>
                        <div class="col-sm-12">
                            <input type="text" name="icustomer" class="form-control" value="<?= $data->e_customer_name;?>"readonly>
                             <!-- <input type="text" name="igudangfake" class="form-control" value="<#?= $data->e_gudang_name;?>" readonly> -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eremark" id="eremark" class="form-control" maxlength="60"  value="<?= $data->e_remark;?>" >
                        </div>
                    </div> 
                </div>  
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                    </div>               
                </div>

            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Qty Memo</th>
                            <th>Qty SJ</th>
                            <th>Keterangan</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                     <? $i = 0;
                        $z = 0;
                        foreach ($datadetail as $row) {
                        $i++;
                            foreach ($datadetail2 as $rowi) {
                            $z++;
                            if($row->i_product == $rowi->i_product){
                            
                        // $saldo=$row->n_quantity-$row->n_pemenuhan;
                    ?>
                    <tr>
                        <td class="col-sm-1">
                            <?php echo $i;?>
                        </td>              
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly>
                            <input style ="width:100px" class="form-control" type="hidden" id="iproductz<?=$z;?>" name="iproductz<?=$z;?>"value="<?= $rowi->i_product; ?>" readonly>

                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:300px" class="form-control" type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly>
                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:100px" type="text" id="esatuanname<?=$i;?>" name="esatuanname<?=$i;?>"value="<?= $row->e_satuan; ?>" readonly>
                            <input style ="width:100px" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan; ?>" readonly>
                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:100px" class="form-control" type="hidden" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>">                       
                            <input style ="width:100px" class="form-control" type="text" id="nquantityz<?=$z;?>" name="nquantityz<?=$z;?>"value="<?= $rowi->n_delivery; ?>" readonly>
                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:100px" class="form-control" type="text" id="npemenuhan<?=$i;?>" name="npemenuhan<?=$i;?>"value="<?= $row->n_quantity; ?>"onkeyup="validasi(this.value);>                       
                        </td>
                        
                        <td class="col-sm-1">
                            <input style ="width:200px" class="form-control" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>"value="<?= $row->e_remark; ?>">
                        </td> 
                        <!-- <td class="col-sm-1">
                            <input style ="width:40px" type="checkbox" id="cek<?=$i;?>" name="cek<?=$i;?>"value="cek">
                        </td>    -->
                        </tr>
                            <?}
                            }
                        }?>
                        <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
                    </tbody>
                </table>
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

function pembandingnilai(a){
    var n_pemenuhan =  $("#npemenuhan"+a).val();
    var n_qty =  $("#nquantity"+a).val();
    //var n_pemenuhan   = document.getElementById('npemenuhan'+a).value;
    //var n_qty = document.getElementById('nquantity'+a).value;
    if(parseInt(n_pemenuhan) > parseInt(n_qty)) {
        swal('Jml kirim ( '+n_pemenuhan+' item ) tdk dpt melebihi Order ('+n_qty+' item)');
        document.getElementById('npemenuhan'+a).value   = n_qty;
        document.getElementById('npemenuhan'+a).focus();
        return false;   
        }
}


function validasi(id){
    swal(id);
        jml=document.getElementById("jml").value;
        for(i=1;i<=jml;i++){
            qtysj   =document.getElementById("nquantityz"+i).value;
            qtyretur=document.getElementById("npemenuhan"+i).value;
            if(parseFloat(qtyretur)>parseFloat(qtysj)){
                swal('Jumlah Retur Tidak Boleh Lebih dari Jumlah SJ');
                document.getElementById("npemenuhan"+i).value=0;
                break;
          }
        }
    }

// function hitungnilai(isi,jml){   
// jml=document.getElementById("jml").value;
//   vtot =0;  
//   for(i=1;i<=jml;i++){
//     npemenuhan      =formatulang(document.getElementById("npemenuhan"+i).value);
//     noutpemenuhan   =formatulang(document.getElementById("npemenuhanout"+i).value);
//     nsaldo          =formatulang(document.getElementById("nsaldohidden"+i).value);
//     nquantity       =formatulang(document.getElementById("nquantity"+i).value);
        
//         if(npemenuhan=='')npemenuhan=0;
//         if(noutpemenuhan=='')noutpemenuhan=0;
    
//         saldo=(parseFloat(nsaldo)-parseFloat(noutpemenuhan))+parseFloat(npemenuhan);

//     //alert(saldo);
//     vtot=vtot+saldo;
//     document.getElementById("nsaldo"+i).value=(saldo);
//     if( parseInt(saldo) > parseInt(nquantity)){
//       //alert("testbos");
//       document.getElementById("npemenuhan"+i).value=0;
//     }
//   }
// } 

// function pembandingnilai(a){
//       var n_qty         = formatulang(document.getElementById('nquantity'+a).value);
//       var n_pemenuhan   = formatulang(document.getElementById('npemenuhan'+a).value);
//       var nsaldo        = formatulang(document.getElementById('nsaldo'+a).value);
    
//       if(parseInt(n_pemenuhan) > parseInt(n_qty)) {
//           swal('Jumlah kirim ( '+n_pemenuhan+') tidak dapat melebihi Qty Schedule ( '+n_qty+' item )');
//           document.getElementById('npemenuhan'+a).value = 0;
//           document.getElementById('npemenuhan'+a).focus();
//           return false;
//       }else if((parseInt(n_pemenuhan)+ parseInt(nsaldo)) > parseInt(n_qty)) {
//           var totsaldo = (parseInt(n_pemenuhan)+ parseInt(nsaldo));
//             swal('Total Qty + Saldo = ( '+totsaldo+' ) tidak dapat melebihi qty schedule ( '+n_qty+' )');
//             document.getElementById('npemenuhan'+a).value=0;
//             document.getElementById('npemenuhan'+a).focus();
//             return false;
//         }
//         if (isNaN(parseFloat(n_pemenuhan))){
//         swal("Input harus numerik");
//         document.getElementById('npemenuhan'+a).value=0;
//             }
//   } 
</script>