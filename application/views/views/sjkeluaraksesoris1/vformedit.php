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
                <div class="col-md-6">
                <div id="pesan"></div>                      
                <div class="form-group">
                    <label class="col-md-12">Tanggal Bon M Keluar</label>
                    <div class="col-sm-12">
                        <input type="text" name="ibonk" class="form-control" value="<?= $data->i_bonk;?>" readonly>
                        <input type="text" name="dbonk" class="form-control date" value="<?= $data->d_bonk;?>" >
                    </div>
                </div>  
                <div class="form-group">
                    <label class="col-md-12">No Schedule</label>
                    <div class="col-sm-12">
                        <input type="text" name="ischedule" class="form-control" value="<?= $dataschedule->i_schedule;?>" readonly>
                    </div>
                </div> 
                </div>
                    <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <input type="hidden" name="igudang" class="form-control" value="<?= $data->i_gudang;?>">
                             <input type="text" name="igudangfake" class="form-control" value="<?= $data->e_gudang_name;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" name="eremarkh" class="form-control" maxlength="60"  value="<?= $data->e_remark;?>" >
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
                            <th>Warna</th>
                            <th>Qty Schedule</th>
                            <th>Qty Bon M</th>
                            <th>Saldo</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                     <?$i = 0;
                        foreach ($datadetail as $row) {
                        $i++;

                        $saldo=$row->n_quantity-$row->n_pemenuhan;
                    ?>
                    <tr>
                        <td class="col-sm-1">
                            <?php echo $i;?>
                        </td>              
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly>
                             <input style ="width:200px" type="text" id="fitemcancel<?=$i;?>" name="fitemcancel<?=$i;?>"value="<?= $row->f_item_cancel; ?>" readonly>
                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:300px" class="form-control" type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly>
                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:100px" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly>
                            <input style ="width:100px" class="form-control" type="text" id="warna<?=$i;?>" name="warna<?=$i;?>"value="<?= $row->warna; ?>" readonly>   
                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly>                       
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="npemenuhan<?=$i;?>" name="npemenuhan<?=$i;?>"value="<?= $row->n_pemenuhan; ?>" onkeyup="pembandingnilai(<?=$i;?>)">
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:100px" class="form-control" type="text" id="nsaldo<?=$i;?>" name="nsaldo<?=$i;?>"value="<?= $saldo; ?>" readonly>
                        </td> 
                        <td class="col-sm-1">
                            <input style ="width:200px" class="form-control" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>"value="<?= $row->e_remark; ?>">
                        </td> 
                        <td class="col-sm-1">
                            <input style ="width:40px" type="checkbox" id="cek<?=$i;?>" name="cek<?=$i;?>"value="cek">
                        </td>   
                        </tr>
                        <?}?>
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