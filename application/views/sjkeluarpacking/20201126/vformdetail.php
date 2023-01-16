<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i>&nbsp;<?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform'), 'update' => '#main', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form group row">
                        <label class="col-md-12">NO Permintaan</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ibonk" name="ibonk" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $datheader->i_bonk;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Permintaan</label>
                        <div class="col-sm-12">
                        <input type="text" name="dbonk" id="dbonk" class="form-control" value="<?= $datheader->d_bonk; ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kode Product</label>
                        <div class="col-sm-12">
                        <input type="text" name="iproduct" id="iproduct" class="form-control" value="<?= $datheader->i_product; ?>"readonly>
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
                            <input type="text" id = "ikodemaster2" name="ikodemaster2" class="form-control" value="<?= $datheader->e_nama_master;?>"readonly>
                            <input type="hidden" id = "ikodemaster" name="ikodemaster" class="form-control" value="<?= $datheader->i_gudang;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Warna</label>
                        <div class="col-sm-12">
                        <input type="text" id = "ecolorname" name="eremark" class="form-control"  value="<?= $datheader->e_color_name; ?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                        <input type="text" id = "eremark" name="eremark" class="form-control"  value="<?= $datheader->e_remark; ?>"readonly>
                        </div>
                    </div>
                </div>
                    
                </div>
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; width: 5%;">No</th>
                                            <th style="text-align: center; width: 15%;">Kode Barang</th>
                                            <th style="text-align: center; width: 30%;">Nama Barang</th>
                                            <th style="text-align: center; width: 20%;">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?=$i = 0;
                                        foreach ($datdetail as $row) {
                                        $i++;?>
                                        <tr>
                                        <td>
                                            <input type="text" class="form-control" id="no<?=$i;?>" name="no<?=$i;?>"value="<?= $i; ?>" readonly >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="nqty<?=$i;?>" name="nqty<?=$i;?>"value="<?= $row->n_quantity; ?>" readonly>
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