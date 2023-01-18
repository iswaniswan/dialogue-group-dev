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
                    <div class="form-group">
                        <label class="col-md-12">No SJ</label>
                        <div class="col-sm-12">
                            <input type="text" id= "isj "name="isj" class="form-control" value="<?= $data->i_sj;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal SJ</label>
                        <div class="col-sm-12">
                            <input type="text" id= "dsj "name="dsj" class="form-control" value="<?= $data->d_sj;?>"readonly>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12">Periode Forecast</label>
                        <div class="col-sm-6">
                            <input type="hidden" id= "iperiode "name="iperiode" class="form-control" value="<?= $data->i_periode_forcast;?>"readonly>
                            <?php $th=substr($data->i_periode_forcast,0,4);
                                  $bl=substr($data->i_periode_forcast,4,2); ?>
                            <select id= "blnforecast" name="blnforecast" class="form-control select2" readonly disabled="">
                                <option></option>
                                <option value='01' <?php if($bl =='01') { ?> selected <?php } ?> >Januari</option>
                                <option value='02' <?php if($bl =='02') { ?> selected <?php } ?> >Pebruari</option>
                                <option value='03' <?php if($bl =='03') { ?> selected <?php } ?> >Maret</option>
                                <option value='04' <?php if($bl =='04') { ?> selected <?php } ?> >April</option>
                                <option value='05' <?php if($bl =='05') { ?> selected <?php } ?> >Mei</option>
                                <option value='06' <?php if($bl =='06') { ?> selected <?php } ?> >Juni</option>
                                <option value='07' <?php if($bl =='07') { ?> selected <?php } ?> >Juli</option>
                                <option value='08' <?php if($bl =='08') { ?> selected <?php } ?> >Agustus</option>
                                <option value='09' <?php if($bl =='09') { ?> selected <?php } ?> >September</option>
                                <option value='10' <?php if($bl =='10') { ?> selected <?php } ?> >Oktober</option>
                                <option value='11' <?php if($bl =='11') { ?> selected <?php } ?> >November</option>
                                <option value='12' <?php if($bl =='12') { ?> selected <?php } ?> >Desember</option>
                            </select>
                        </div>
                        
                        <div class="col-sm-6">
                            <select id= "thnforecast" name="thnforecast" class="form-control select2" readonly disabled="">
                            <option></option>
                            <?php
                                $tahun1 = date('Y')-3;
                                $tahun2 = date('Y');
                                    
                                for($i=$tahun1;$i<=$tahun2;$i++){ ?>
                                    <option value='$i' <?php if($i == $th) { ?> selected <?php } ?> ><?php echo $i ?></option>
                              <?php  } ?>                             

                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jenis Keluar</label>
                        <div class="col-sm-12">
                            <input type="text" readonly id="ejenisout" name="ejenisout" value="<?= $data->e_jenis_keluar;?>" class="form-control">
                            <input readonly id="ijenis" name="ijenis" type="hidden" value="<?= $data->i_jenis;?>">
                            <input readonly id="isumber" name="isumber" type="hidden" value="<?= $data->i_sumber;?>">
                        </div>
                    </div>                                  
                    <div class="form-group">
                        <?
                            if($data->f_receive !='t'){
                            ?>
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>

                                <!-- <button type="button" class="btn btn-inverse btn-rounded btn-sm" 
                                onclick='show("<?= $folder;?>/cform/edit","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>    -->
                                <i>* Belum di Approve </i>
                            </div>   
                            <?
                            }else{
                            ?>
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>
<!-- 
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" 
                                onclick='show("<?= $folder;?>/cform/edit","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>    -->
                                 <i>* Sudah di Approve </i>
                            </div> 
                            <?}?>   
                    </div>
                </div>
                <div class="col-md-6">
                <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Unit</label>
                        <div class="col-sm-12">
                            <input type="text" readonly id="eunitjahit" name="eunitjahit" value="<?= $data->e_unit_name;?>" class="form-control">
                            <input readonly id="iunitjahit" name="iunitjahit" type="hidden" value="<?= $data->i_unit;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <input type="text" id= "eremark "name="eremark" class="form-control" value="<?= $data->e_remark;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Terima</label>
                        <div class="col-sm-12">
                            <input type="text" id= "dreceive "name="dreceive" class="form-control date" value="<?= $data->d_receive;?>" readonly>
                        </div>
                    </div>
                </div>    
                <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Warna</th>
                            <th>Qty</th>
                            <th>Keterangan</th>
                    </thead>
                    <tbody>
                        <?$i = 0;
                        foreach ($datadetail as $row) {
                        $i++;?>
                        <tr>
                        <td class="col-sm-1">
                            <?php echo $i;?>
                        </td>              
                        <td class="col-sm-1">
                            <input style ="width:100px" type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly>
                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:200px" type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly> 
                        </td>
                        <td class="col-sm-1" >  
                            <input style ="width:100px" type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly>
                            <input style ="width:100px" type="text" id="ecolorname<?=$i;?>" name="ecolorname<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly>                       
                        </td>
                        <td class="col-sm-1">
                            <input style ="width:150px" type="text" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly>
                        </td>                                       
                        <td>
                            <input style ="width:200px" type="text" id="eremark<?=$i;?>" name="eremark<?=$i;?>"value="<?= $row->e_remark; ?>" readonly>                       
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
    </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $(".select2").select2();
 });

 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function getproduct(id){
    var iproduct = $('#iproduct'+id).val();
    $.ajax({
    type: "post",
    data: {
        'i_product': iproduct
    },
    url: '<?= base_url($folder.'/cform/getproduct'); ?>',
    dataType: "json",
    success: function (data) {
        $('#eproduct'+id).val(data[0].e_product_namewip);
        $('#icolor'+id).val(data[0].i_color);
        $('#ecolor'+id).val(data[0].e_color_name);

        ada=false;
        var a = $('#iproduct'+id).val();
        var e = $('#eproduct'+id).val();
        var jml = $('#jml').val();
        for(i=1;i<=jml;i++){
            if((a == $('#iproduct'+i).val()) && (i!=jml)){
                swal ("Kode : "+a+" sudah ada !!!!!");
                ada=true;
                break;
            }else{
                ada=false;     
            }
        }
        if(!ada){
            var iproduct    = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'i_product'  : iproduct,
                },
                url: '<?= base_url($folder.'/cform/getdetailbar'); ?>',
                dataType: "json",
                success: function (data) {
                     $('#eproduct'+id).val(data[0].e_product_namewip);
                     $('#icolor'+id).val(data[0].i_color);
                     $('#ecolor'+id).val(data[0].e_color_name);
                },
            });
        }else{
            $('#iproduct'+id).html('');
            $('#eproduct'+id).val('');
            $('#icolor'+id).val('');
            $('#ecolor'+id).val('');
        }
    },
    error: function () {
        alert('Error :)');
    }
});
}
</script>