<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form group row">
                        <label class="col-md-12">NO Permintaan</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ibonk" name="ibonk" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_bonk;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Permintaan</label>
                        <div class="col-sm-12">
                        <input type="text" name="dbonk" id="dbonk" class="form-control" value="<?= $data->d_bonk; ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class=""></i>&nbsp;&nbsp;keluar</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label class="col-md-12">tujuan</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ikodemaster2" name="ikodemaster2" class="form-control" value="<?= $data->e_nama_master;?>"readonly>
                            <input type="hidden" id = "ikodemaster" name="ikodemaster" class="form-control" value="<?= $data->i_gudang;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id = "eremark" name="eremark" class="form-control"  value="<?= $data->e_remark; ?>"readonly>
                        </div>
                    </div>
                </div>
                    
                </div>
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; width: 7%;">No</th>
                                            <!-- <th>No</th> -->
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Warna</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?=$i = 0;
                                        foreach ($data2 as $row) {
                                        $i++;?>
                                        <tr>
                                        <td class="col-sm-1">
                                            <input style ="width:50px"type="text" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:150px"type="text" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>"value="<?= $row->i_product; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:400px"type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>"value="<?= $row->e_product_name; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:130px"type="hidden" id="icolor<?=$i;?>" name="icolor<?=$i;?>"value="<?= $row->i_color; ?>" readonly >
                                            <input style ="width:120px"type="text" id="ecolorname<?=$i;?>" name="ecolorname<?=$i;?>"value="<?= $row->e_color_name; ?>" readonly >
                                        </td>
                                        <td class="col-sm-1">
                                            <input style ="width:100px"type="text" id="nqty<?=$i;?>" name="nqty<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly>
                                        </td>
                                        
                                        <td class="col-sm-1">
                                        <!-- <input type="text" class="ibtnDel btn btn-md btn-danger "  value="Edit"> -->
                                        <?= $this->pquery->link_to_remote('<i class="glyphicon glyphicon-pencil"></i>',array('url'=>site_url().'/bonkkeluarqc/cform/detail/'.$row->i_bonk.'/'.$row->i_product.'/'.$row->i_color.'/','update'=>'#main'))?>
                                        <?= $this->pquery->link_to_remote('<i class="fa fa-trash"></i>',array('url'=>site_url().'/bonkkeluarqc/cform/deletedetail/'.$row->i_bonk.'/'.$row->i_product.'/'.$row->i_color.'/','update'=>'#main'))?>
                                        </td>
                                        </tr>
                                        <?}?>
                                        <label class="col-md-12">Jumlah Data</label>
                                        <input style ="width:50px"type="text" name="jml" id="jml" value="<?= $i; ?>">
                                    </tbody>
                                </table>
                            </div>
                </form>
            <div>
        </div>
    </div>
</div>

<script>

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

    $('#imaterial'+ counter).select2({
        placeholder: 'Pilih Material',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datamaterial/'); ?>'+ikodemaster,
          
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
</script>