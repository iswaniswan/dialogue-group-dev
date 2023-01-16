<div class="row">
    <div class="col-lg-12">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">

            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/simpan','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>                
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>   
                    <div class="form-group">
                        <label class="col-md-12">Tanggal SO</label>
                        <div class="col-sm-12">
                            <input class="form-control" readonly="true" type="text" name="dso" id="dso" value="<?= $dso; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Unit Packing</label>
                        <div class="col-sm-12">
                            <input class="form-control" readonly="true" type="text" name="iunitpacking" id="iunitpacking" value="<?= $packing->i_unit_packing; ?>">
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-12">Gudang</label>
                        <div class="col-sm-12">
                            <input class="form-control" readonly="true" type="text" name="igudang" id="igudang" value="<?= $gudang->i_kode_master; ?>">
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
                            <th>Saldo Awal</th>
                            <th>Masuk Packing</th>
                            <th>Keluar Packing</th>
                            <th>Saldo Akhir</th>   
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                        $i=0;   
                        if($data){
                        foreach($data as $row){
                            $i++;
                        ?>
                        <tr>
                           <td class="col-sm-1">
                                <?= $i; ?>
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:160px"type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product_wip; ?>" readonly >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:250px"type="text" id="eproduct<?=$i;?>" name="eproduct<?=$i;?>"value="<?= $row->e_product_namewip; ?>" readonly >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:80px" type="text" id="saldoawal<?=$i;?>" name="saldoawal<?=$i;?>"value="<?= $row->sawal; ?>" readonly >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:80px" type="text" id="masukpacking<?=$i;?>" name="masukpacking<?=$i;?>"value="<?= $row->masukpacking; ?>" readonly >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:70px"type="text" id="keluarpacking<?=$i;?>" name="keluarpacking<?=$i;?>"value="<?= $row->keluarpacking; ?>"readonly >
                            </td>
                            <td class="col-sm-1">
                                <input style ="width:70px"type="text" id="salhir<?=$i;?>" name="salhir<?=$i;?>"value="<?= $row->salhir; ?>"readonly >
                            </td>
                        </tr>
                        <?}
                        }?> 
                        <label class="col-md-12">Jumlah Data</label>
                        <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">            
                    </tbody>
                </table>
            </div>            
        </form>
    </div>
</div>
<script>

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
});

$(document).ready(function () {
    $(".select").select();
    showCalendar('.date');
});

$(document).ready(function () {
    $(".select2").select2();
});

$(document).ready(function () {
    $('#isupplier').select2({
    placeholder: 'Pilih Supplier',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/supplier'); ?>',
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

function cekval(x){
    num=document.getElementById("vbayar"+x);
    
    if(!isNaN(num)){        
        vjmlbyr     = parseFloat(formatulang(document.getElementById("vjumlah1").value));
        vlebihitem  = vjmlbyr;
        vsisadt     = parseFloat(formatulang(document.getElementById("vsisa1").value));
        jml         = document.getElementById("jml").value;
        
        for(a=1;a<=jml;a++){           
            vnota   = parseFloat(formatulang(document.getElementById("vnilai"+a).value));       
            vjmlitem= parseFloat(formatulang(document.getElementById("vbayar"+a).value));
            vsisaitem =vnota-vjmlitem;
           
            if(vsisaitem<0){
                alert("jumlah bayar tidak bisa lebih besar dari nilai notaaa !!!!!");
                document.getElementById("vbayar"+a).value=0;
                vjmlitem  = parseFloat(formatulang(document.getElementById("vbayar"+a).value));
                vsisaitem = parseFloat(formatulang(document.getElementById("vsisa"+a).value));
            }
            vlebihitem=vjmlbyr-vjmlitem;
            // alert(vlebihitem);    
            if(vlebihitem<0){
                vlebihitem=vlebihitem+vjmlitem;
                vsisaitem =vnota-vlebihitem;
                /*alert("jumlah item tidak bisa lebih besar dari nilai bayar !!!!!");*/
                document.getElementById("vbayar"+a).value=formatcemua(vlebihitem);
                vjmlitem  = parseFloat(formatulang(document.getElementById("vbayar"+a).value));
                vlebihitem=0;
            }
            document.getElementById("vsesa"+a).value=formatcemua(vsisaitem);
            document.getElementById("vlebih"+a).value=formatcemua(vlebihitem);
        }
        document.getElementById("vlebih1").value=formatcemua(vlebihitem);
    }else{ 
        alert('input harus numerik !!!');
        document.getElementById("vbayar"+x).value=0;
    }
}
</script>