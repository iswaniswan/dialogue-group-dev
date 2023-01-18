<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal BTB</label>
                        <label class="col-md-6">No PP</label>
                        <label class="col-md-3">Tanggal PP</label>      
                        <div class="col-sm-3">
                            <input id="dbtb" name="dbtb" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                        <div class="col-sm-6">
                            <input type="text" id= "ipp" name="ipp" class="form-control" required="" value="<?= $data->i_pp;?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id= "dpp" name="dpp" class="form-control" required="" value="<?= $data->d_pp;?>" readonly>
                        </div>
                    </div> 
                    <div class="form-group row">
                        <label class="col-md-9">No Dokumen Supplier</label>
                        <label class="col-md-3">Tanggal SJ</label>
                        <div class="col-sm-9">
                            <input type="text" id="isj" name="isj" class="form-control" required="" value="" required="">
                        </div>
                        <div class="col-sm-3">
                            <input id="dsj" name="dsj" class="form-control date"  readonly value="<?php echo date("d-m-Y"); ?>">
                        </div>
                    </div>       
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-9">
                            <input type="text" id="eremark" name="eremark" class="form-control" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="send" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No OP</label>
                        <label class="col-md-6">Tanggal OP</label>
                        <div class="col-sm-6">
                            <input type="text" id= "iop" name="iop" class="form-control" required="" value="<?= $data->i_op;?>" readonly>
                        </div>
                         <div class="col-sm-6">
                            <input type="text" id= "dop" name="dop" class="form-control date" required="" value="<?= date("d-m-Y",strtotime($data->d_op));?>" readonly onchange="max_tgl(this.value);">
                        </div>
                    </div>
                     <div class="form-group row">
                        <label class="col-md-6">Supplier</label>
                        <label class="col-md-6">Gudang Penerima</label>
                        <div class="col-sm-6">
                            <input type="hidden" id= "isupplier" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier;?>">
                            <input type="text" id= "esuppliername" name="esuppliername" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->e_supplier_name;?>" readonly>
                            <input type="hidden" id="ikode" name="ikode"value="<?= $data->i_kode_master; ?>" readonly >
                        </div>
                        <div class="col-sm-6">
                            <select name="igudang" class="form-control select2">
                            <option value="">Pilih Gudang</option>
                                <?php foreach($gudang as $igudang): ?>
                                <option value="<?php echo $igudang->i_kode_master;?>" 
                                <?php if($igudang->i_kode_master==$data->i_kode_master) { ?> selected="selected" <?php } ?>>
                                <?php echo $igudang->e_nama_master;?></option>
                                <?php endforeach; ?> 
                            </select>
                        </div>
                    </div>                    
                </div>
                    <!-- <input type="hidden" name="jml" id="jml"> -->                    
                    <div class="panel-body table-responsive">
                        <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
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
                                </tr>
                            </thead>
                            <tbody>
                            <?
                               if($data2){
                                $i = 0;                                   
                                foreach ($data2 as $row) {
                                    // var_dump($row);
                                  $i++;
                            ?>
                                <tr>
                                <td class="col-sm-1">
                                    <input style ="width:40px"type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" class="form-control"  readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:250px"type="text" id="ekodemaster<?=$i;?>" name="ekodemaster<?=$i;?>"value="<?= $row->e_nama_master; ?>" class="form-control" readonly >
                                    <input style ="width:350px"type="hidden" name="ikodemaster<?=$i;?>"value="<?= $row->i_kode_master; ?>" readonly >
                                </td>
                                <td class="col-sm-1">
                                <input style ="width:150px" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" class="form-control" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:400px" type="text" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" class="form-control" readonly >
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="nquantityeks<?=$i;?>" name="nquantityeks<?=$i;?>"value="" class="form-control">
                                </td>
                                <td class="col-sm-1">
                                    <select style="width:100px;" type="text" id="isatuaneks<?=$i;?>" class="form-control select2" name="isatuaneks<?=$i;?>" >
                                    </select>
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:70px"type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_pemenuhan; ?>" class="form-control" onkeyup=valstock(this.value);>
                                    <input type="hidden" id="hrgop<?=$i;?>" name="hrgop<?=$i;?>"value="<?= $row->hrgop; ?>" class="form-control">
                                    <input style ="width:90px" type="hidden" class="form-control" id="npemenuhan<?=$i;?>" name="npemenuhan<?=$i;?>" value="<?= $row->n_pemenuhan; ?>" readonly> 
                                </td>
                                <td class="col-sm-1">
                                    <input style ="width:100px" type="text" id="esatuan<?=$i;?>" name="esatuan<?=$i;?>"value="<?= $row->e_satuan; ?>" class="form-control" readonly >
                                    <input style ="width:70px" type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan; ?>" readonly >
                                </td>
                                <input style ="width:60px"type="hidden" id="iop<?=$i;?>" name="iop<?=$i;?>"value="<?= $row->i_op; ?>"readonly >
                                </tr>
                                <?}}?>
                                <input style ="width:50px"type="hidden" name="jml" id="jml" value="<?= $i; ?>" readonly>
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
    $("#send").attr("disabled", true);
    //hitungnilai();
});

$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function valstock(){ 
    var jml = $('#jml').val();
    for(var i=1; i<=jml; i++){        
       var stock         = $('#nquantity'+i).val();
       var noutstanding  = $('#npemenuhan'+i).val();
        if (stock == ''){
            stock = 0;
        }
        // if(parseFloat(noutstanding)>parseFloat(stock) ){
        //      swal ("Jumlah stock kurang");
        //         document.getElementById("nquantity"+id).value=0;
        //         break;
        // }else
         if(parseFloat(noutstanding)<parseFloat(stock) ){
            swal ("Jumlah stock lebih");
            document.getElementById("nquantity"+id).value=0;
                break;
        }
    }
}

$("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#send").attr("disabled", false);
});

function getenabledsend() {
    $('#send').attr("disabled", true);
}

$(document).ready(function(){
    $("#send").on("click", function () {
        var kode = $("#kode").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/send'); ?>",
            data: {
                     'kode'  : kode,
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

function max_tgl(val) {
  $('#dbtb').datepicker('destroy');
  $('#dbtb').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dop').value,
  });
}
$('#dbtb').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dop').value,
});

$(document).ready(function () {
    var i = $('#jml').val();
    $('#isatuaneks'+i).select2({

    placeholder: 'Pilih Satuan',
    allowClear: true,
    type: "POST",
    ajax: {
      url: '<?= base_url($folder.'/cform/satuan'); ?>',
      dataType: 'json',
      delay: 250,          
      processResults: function (data) {
        return {
          results: data
        };
      },
      cache: true
    }
  })
});

</script>