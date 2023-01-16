<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>

            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Tanggal</label>
                        <div class="col-sm-12">
                            <input type="text" name="dop" id="dop" required="" class="form-control date" value="" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm" onclick="dipales(parseFloat(document.getElementById('jml').value));"> <i
                                class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;</button> 
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="20%">Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Motif</th>
                                <th>Jml Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script language="javascript" type="text/javascript">
$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
});

var counter = 0;

$("#addrow").on("click", function () {
    counter++;
    document.getElementById("jml").value = counter;
    var newRow = $("<tr>");
    
    var cols = "";
    
    cols += '<td style="text-align: center;">'+counter+'<input type="hidden" id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"><input type="hidden" id="motif'+counter+'" name="motif'+counter+'" value=""></td>';
    cols += '<td><select type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getmotif('+ counter + ');"></td>';
    cols += '<td><input type="text" id="eproductname'+ counter + '" type="text" class="form-control" name="eproductname' + counter + '" value=""></td>';
    cols += '<td><input type="text" id="emotifname'+ counter + '" class="form-control" name="emotifname'+ counter + '" value=""></td>';
    cols += '<td><input type="text" id="norder'+ counter + '" class="form-control" name="norder' + counter + '" value=""></td>';
    cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
    newRow.append(cols);
    $("#tabledata").append(newRow);

    $("#tabledata").on("click", ".ibtnDel", function (event) {
    $(this).closest("tr").remove();       
    counter -= 1
    document.getElementById("jml").value = counter;

});
   
$('#iproduct'+ counter).select2({
    placeholder: 'Pilih Kode Barang',
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
  });
  
});

$("#tabledata").on("click", ".ibtnDel", function (event) {
    $(this).closest("tr").remove();       
    counter -= 1
    document.getElementById("jml").value = counter;

});
$(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function dipales(a){
	cek='false';
	if((document.getElementById("iopstatus").value!='') && (document.getElementById("dop").value!='')){
  	 	if(a==0){
  	 		alert('Isi data item minimal 1 !!!');
  	 	}else{
            for(i=1;i<=a;i++){
                if((document.getElementById("norder"+i).value=='')){
                    alert('Data item masih ada yang salah !!!');
                    cek='false';
                }else{
                    cek='true';	
                } 
            }
		}
		if(cek=='true'){
  	        document.getElementById("login").disabled=true;
            document.getElementById("cmdtambahitem").disabled=true;
  	    }else{
	        document.getElementById("login").disabled=false;
		}
	}else{
  		alert('Data header masih ada yang salah !!!');
	}
}

function getmotif(id){
        var iproduct = $('#iproduct'+id).val();
        $.ajax({
        type: "post",
        data: {
            'i_product': iproduct
        },
        url: '<?= base_url($folder.'/cform/getmotif'); ?>',
        dataType: "json",
        success: function (data) {
            $('#eproductname'+id).val(data[0].e_product_name);
            $('#motif'+id).val(data[0].i_product_motif);
            $('#emotifname'+id).val(data[0].e_product_motifname);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }
</script>
