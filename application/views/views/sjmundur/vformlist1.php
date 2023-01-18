<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading"> <i class="fa fa-list"></i> <?= $title; ?>
                <?php  if(check_role($this->i_menu, 1)){ ?><a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-plus"></i> &nbsp;<?= $title; ?></a>
                <?php } ?>
            </div>
            <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="form-group">
                        <label class="col-md-12">Kode Product</label>
                        <div class="col-sm-12">
                            <select name="iproduct" id="iproduct" class="form-control">
                            </select>
                        </div>
                </div>
                <div class="form-group">
                        <label class="col-md-12">Nama product</label>
                        <div class="col-sm-12">
                            <input type="text" id="eproductname" name="eproductname" class="form-control" required="" readonly>
                        </div>
                </div>
                <div class="form-group">
                        <label class="col-md-12">Grade</label>
                        <div class="col-sm-12">
                            <select name="iproductgrade" class="form-control select2">
                            <?php foreach ($productgrade as $r):?>
                                <option value="<?php echo $r->i_product_grade;?>"><?php echo $r->i_product_grade." - ".$r->e_product_gradename;?></option>
                            <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="iproductgrade" class="form-control" required="" maxlength="30" value="00">
                        </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode harga</th>
                            <th>Harga Pabrik</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
        if($isi){
          $i=1;
          foreach($isi as $row){ ?>
                        <tr>
                            <td><input class="form-control" type="text" value="<?= $row->i_price_group; ?>" name="ipricegroup<?= $i; ?>" id="ipricegroup<?= $i; ?>"readonly>
            </div>
            </td>
            <td>
                <input class="form-control" type="text" name="vproductmill<?= $i; ?>" id="vproductmill<?= $i; ?>" 
                value="" onkeyup="reformat(this);samain(<?php echo $i;?>);"></td>
                <!-- <td width=\"16%\"><input name=\"vproductmill$i\" id=\"vproductmill$i\" value=\"\" 
                onkeyup=\"reformat(this);samain($i);\"></td> -->
        </div>
        </td>
        <td><input class="form-control" type="text" value="" name="vproductretail<?= $i; ?>"  
        onkeyup=reformat(this) id="vproductretail<?= $i; ?>" >
    </div>
    </td>
    </tr>

    <?php  $i++;
        }
      }
      $jml=$i-1;
      echo "<input type=hidden name=\"jmlitem\" id=\"jmlitem\" value=\"$jml\">";
    ?>
    </tbody>
    </table>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-5">
            <button type="submit" id="submit" class="btn btn-info btn-sm pull-right"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
            <!-- <button type="submit" class="btn btn-info btn-sm pull-right"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button> -->
        </div>
    </div>
</div>
</div>
</div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        $('#iproduct').select2({
        placeholder: 'Pilih Barang',
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
      }).on("change", function(e) {
        var kode = $('#iproduct').text();
        kode = kode.split("-");
        $('#eproductname').val(kode[1]);
      });
    });
  function samain(i){
    
    for(x=1;x<=10;x++){
      document.getElementById("vproductmill"+x).value=document.getElementById("vproductmill"+i).value;
    }
  }
 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
 });
</script>
