<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">No Kas</label>
                        <div class="col-sm-12">
                            <input type="text" id = "ikb" name="ikb" class="form-control" required="" maxlength="6"
                            onkeyup="gede(this)" value="<?= $data->i_kb;?>"readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Tanggal Kas</label>
                        <div class="col-sm-12">
                        <input type="text" name="dkb" id="dkb" class="form-control date" value="<?= $data->d_kb; ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-12">
                        <input type="text" id = "vjumlah" name="vjumlah" class="form-control"  value="<?= $data->v_kb; ?>">
                        <!--<input type="text" id = "vjumlahx" name="vjumlahx" class="form-control>-->
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                            <select name="isupplier" id="isupplier" class="form-control select2">
                                <option value="">-- Pilih Supplier --</option>
                                <?php foreach ($supplier as $isupplier):?>
                                <option value="<?php echo $isupplier->i_supplier;?>">
                                    <?= $isupplier->i_supplier." - ".$isupplier->e_supplier_name;?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                    <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            
                        <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus" ></i>&nbsp;&nbsp;Tambah</button>
                    </div>               
                    </div>
                    </div>
                            <div class="panel-body table-responsive">
                                <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                                    <thead>
                                        <tr>
                                            <th style="width: 18%;">Kode Nota</th>
                                            <th style="width: 14%;">Tgl Nota</th>
                                            <th style="width: 12%;">Nilai</th>
                                            <th style="width: 12%;">Bayar</th>
                                            <th style="width: 12%;">Sisa</th>
                                            <th style="width: 12%;">Lebih</th>
                                            <th style="width: 5%;">Action</th>  
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <label class="col-md-12">Jumlah Data</label>
                                        <input style ="width:50px"type="text" name="jml" id="jml" value="0">
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
  var counter = document.getElementById("jml").value;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        $("#tabledata").attr("hidden", false);
        var newRow = $("<tr>");
        var isupplier = $("#isupplier").val();

        var cols = "";
        if(cols =! ""){       
            document.getElementById("jml").value = counter;  
            
            cols += '<td><select type="text" id="inota'+ counter + '" class="form-control" name="inota'+ counter + '" onchange="getnota('+ counter +');"></td>';
            cols += '<td><input type="text" id="dnota'+ counter + '" type="text" class="form-control" name="dnota' + counter + '" readonly></td>';
            cols += '<td><input type="text" id="vnilai'+ counter + '" class="form-control" name="vnilai'+ counter + '" onkeyup="cekval(this.value); reformat(this);" readonly/></td>';
            cols += '<td><input type="text" id="vbayar'+ counter + '" class="form-control" name="vbayar'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
            cols += '<td><input type="text" id="vsisa'+ counter + '" class="form-control" name="vsisa'+ counter + '" readonly /></td>';
            cols += '<td><input type="text" id="vlebih'+ counter + '" class="form-control" name="vlebih' + counter + '" readonly/></td>';
            cols += '<td><input type="hidden" id="vjumlah'+ counter + '" class="form-control" name="vjumlah'+ counter + '"readonly/></td>';

        }
        newRow.append(cols);
        $("#tabledata").append(newRow);
        // alert(isupplier);
        $('#inota'+ counter).select2({
        placeholder: 'Pilih nota',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datanota/');?>'+isupplier,
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
 $("form").submit(function(event) {
     event.preventDefault();
     $("input").attr("disabled", true);
     $("select").attr("disabled", true);
     $("#submit").attr("disabled", true);
 });
// $(document).ready(function () {
    // var counter = 0;

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
        document.getElementById("jml").value = counter;
    });

    // $("#tabledata").on("click", ".ibtnDel", function (event) {
    //     $(this).closest("tr").remove();       
    //     counter -= 1
    // });
});

    $(document).ready(function () {
        $(".select").select();
        showCalendar('.date');
    });

    function getnota(id){
        var inota = $('#inota'+id).val();
        var vjumlah = $('#vjumlah').val();
        var vjumlahx = $('#vjumlahx').val();
        var jml = $('#jml').val();
        var vkb2 = 0;

        // var isupplier = $('#isupplier').val();
        $.ajax({
        type: "post",
        data: {
            'i_nota': inota
        },
        url: '<?= base_url($folder.'/cform/getnota');?>',
        dataType: "json",
        success: function (data) {
            //$('#vjumlahx').val(vjumlah);
            $('#dnota'+id).val(data[0].d_nota);
            $('#vnilai'+id).val(data[0].v_total);
            $('#vbayar'+id).val(data[0].v_sisa); 
            // document.getElementById("vjumlah").value = vjumlah;
            //hetang(id);
            var jmltr = $('#tabledata tr').length;
            for(n = 0; n < jmltr; n++){
                if(n == 1){
                    $('#vjumlah'+id).val(vjumlah);

                    if(vjumlah-data[0].v_sisa <= 0){
                        $('#vsisa'+id).val((vjumlah-data[0].v_sisa)*-1);
                    }else{
                        $('#vsisa'+id).val(vjumlah-data[0].v_sisa);
                    }

                    $('#vlebih'+id).val(vjumlah-data[0].v_sisa);
                    if(vjumlah-data[0].v_sisa <= 0){
                        $('#vlebih'+id).val((vjumlah-data[0].v_sisa)*0);
                    }
                }else{
                    var x = n-1;
                    $('#vjumlah'+id).val($('#vlebih'+x).val());

                    if(vjumlah-data[0].v_sisa <= 0){
                        $('#vsisa'+id).val(($('#vjumlah'+id).val()-$('#vnilai'+n).val())*-1);
                        //$('#vsisa'+id).val((vjumlah-data[0].v_sisa)*-1);
                    }else{
                        //$('#vsisa'+id).val($('#vjumlah'+n).val()-$('#vnilai'+n).val());
                        $('#vsisa'+id).val($('#vjumlah'+id).val()-$('#vnilai'+n).val());
                    }

                    $('#vlebih'+id).val(vjumlah-data[0].v_sisa);
                    if(vjumlah-data[0].v_sisa <= 0){
                        $('#vlebih'+id).val((vjumlah-data[0].v_sisa)*0);
                    }
                }
            }
            //  vkb2 = $('#vlebih'+id).val((vkb-data[0].v_sisa)*0);
            }
              
        })
    };


    function hetang(x)
    {
        
    num=document.getElementById("vjumlah"+counter).value;
    alert(num);	
    if(!isNaN(num)){
		  vjmlbyr	    = parseFloat(formatulang(document.getElementById("vjumlah").value));
      vlebihitem  = vjmlbyr;
		  vsisadt     = parseFloat(formatulang(document.getElementById("vsisa").value));
		  jml		= document.getElementById("jml").value;
      for(a=1;a<=jml;a++){
        vnota   = parseFloat(formatulang(document.getElementById("vsisa"+a).value));
     		vjmlitem= parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
        if(vjmlitem==0){
          bbotol();
        }
        vsisaitem =vnota-vjmlitem;
        if(vsisaitem<0){
          alert("jumlah bayar tidak bisa lebih besar dari nilai notaaa !!!!!");
          document.getElementById("vjumlah"+a).value=0;
          vjmlitem  = parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
          vsisaitem = parseFloat(formatulang(document.getElementById("vsisa"+a).value));
        }
        vlebihitem=vlebihitem-vjmlitem;
        if(vlebihitem<0){
          vlebihitem=vlebihitem+vjmlitem;
          vsisaitem =vnota-vlebihitem;
          alert("jumlah item tidak bisa lebih besar dari nilai bayar !!!!!");
          document.getElementById("vjumlah"+a).value=formatcemua(vlebihitem);
         vjmlitem  = parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
          vlebihitem=0;
        }
       document.getElementById("vsesa"+a).value=formatcemua(vsisaitem);
       document.getElementById("vlebih"+a).value=formatcemua(vlebihitem);
      }
      document.getElementById("vlebih").value=formatcemua(vlebihitem);
    }else{ 
		  alert('input harus numerik !!!');
      document.getElementById("vjumlah"+x).value=0;
	  }
  }
    
</script>