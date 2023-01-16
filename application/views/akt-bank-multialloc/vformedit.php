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
                    <div class="form-group row">
                        <label class="col-md-12">No Bank / Tanggal Bank</label>
                        <div class="col-sm-3">
                            <input type="text" name="ikbank" id="ikbank" class="form-control" onkeyup="gede(this)" value="<?= $data->i_kbank; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="dbank" id="dbank" class="form-control" value="<?= $data->d_bank; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Bank / Tanggal Alokasi</label>
                        <div class="col-sm-5">
                            <input type="text" name="ebankname" id="ebankname" class="form-control" value="<?= $data->e_bank_name; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="dalokasi" id="dalokasi" class="form-control" value="<?= $data->d_bank; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" ><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-6">
                        <select name="isupplier" id="isupplier" class="form-control select2">
                        <input type="hidden" readonly id="icoabank" name="icoabank" class="form-control" value="<?= $data->i_coa_bank; ?>">
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-6">
                        <input type="text" name="vjumlah" id="vjumlah" class="form-control" value="<?= $data->v_sisa; ?>" onKeyUp="reformat(this);hitung();" readonly>
                        <input type="hidden" id="vlebih" name="vlebih" class="form-control" value="0" >
			            <input type="hidden" id="vsisa" name="vsisa" class="form-control" value="<?= $data->v_sisa; ?>" >
			            <input type="hidden" name="jml" id="jml" class="form-control" value="0">
			            <input type="hidden" name="icoabank" id="icoabank" class="form-control" value="<?= $data->i_coa_bank; ?>">
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nota</th>
                                    <th>Tanggal Nota</th>
                                    <th>Nilai</th>
                                    <th>Bayar</th>
                                    <th>Sisa</th>
                                    <th>Lebih</th>
                                    <th>Ket2</th>
                                </tr>
                            </thead>
                    </table>
                </div>
            </form>
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
    $(".select2").select2();
    showCalendar('.date');
 });
 $(document).ready(function () {
    $('#isupplier').select2({
    placeholder: 'Pilih Supplier',
    allowClear: true,
    ajax: {
      url: '<?= base_url($folder.'/cform/data_supplier'); ?>',
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
        var kode = $('#isupplier').text();
    });
});
var counter = 0;
$("#addrow").on("click", function () {
    counter++;
    document.getElementById("jml").value = counter;
    var newRow = $("<tr>");
    var isupplier = $("#isupplier").val();
    
    var cols = "";
    
    cols += '<td><input readonly style=width:40px; id="baris'+counter+'" type="text" class="form-control" name="baris'+counter+'" value="'+counter+'"></td>';
    cols += '<td><select style=width:150px; type="text" id="idtap'+ counter + '" class="form-control" name="idtap'+ counter + '" onchange="getnota('+ counter + ');"></td>';
    cols += '<td><input readonly type="text" id="ddtap'+ counter + '" class="form-control" name="ddtap'+ counter + '"/></td>';
    cols += '<td><input readonly style=width:200px; type="text" id="vnota'+ counter + '" class="form-control" name="vnota'+ counter + '"/></td>';
    cols += '<td><input readonly type="text" id="vjumlah'+ counter + '" class="form-control" name="vjumlah' + counter + '" onKeyUp="hetang('+counter+');"/></td>';
    cols += '<td><input readonly style=width:200px; type="text" id="vsesa'+ counter + '" class="form-control" name="vsesa' + counter + '" value="0"/><input type="hidden" id="vsisa'+counter+'" name="vsisa'+counter+'" value="0"/></td>';
    cols += '<td><input readonly type="text" id="vlebih'+ counter + '" class="form-control" name="vlebih' + counter + '" value="0"/></td>';
    cols += '<td><input readonly type="text" id="eremark'+ counter + '" class="form-control" name="eremark' + counter + '"/></td>';
    cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
    newRow.append(cols);
    $("#tabledata").append(newRow);
    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        counter -= 1
        document.getElementById("jml").value = counter;
    });
    $('#idtap'+ counter).select2({
        placeholder: 'Pilih No Nota',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/datanota/'); ?>'+isupplier,
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
function getnota(id){
    ada=false;
    var a = $('#idtap'+id).val();
    var x = $('#jml').val();
    for(i=1;i<=x;i++){
        if((a == $('#idtap'+i).val()) && (i!=x)){
            swal ("kode : "+a+" sudah ada !!!!!");            
            ada=true;            
            break;        
        }else{            
            ada=false;             
        }
    }
    if(!ada){
        var idtap = $('#idtap'+id).val();
        var isupplier = $("#isupplier").val();
    
        $.ajax({
        type: "post",
        data: {
            'i_dtap': idtap
        },
        url: '<?= base_url($folder.'/cform/getdtapitem/'); ?>'+isupplier,
        dataType: "json",
        success: function (data) {
            $('#idtap'+id).val(data[0].i_dtap);
            $('#ddtap'+id).val(data[0].d_dtap);
            $('#isupplier'+id).val(data[0].i_supplier);
            $('#vnota'+id).val(formatcemua(data[0].v_sisa));
            $('#vsisa'+id).val(formatcemua(data[0].v_sisa));
            var tmp=formatulang($('#vjumlah').val());
            var jml=$('#jml').val();
            if(tmp>0){
                tmp=parseFloat(tmp);
                sisa=0;
                jumasal=tmp;
                jumall=jumasal;
                bay=0;
                for(x=1;x<=jml;x++){
                    if($('#vjumlah'+x).val()==''){
                         jum     = parseFloat(formatulang($('#vsisa'+x).val()));
                         alert(jum);
                    }else{
                         jum     = parseFloat(formatulang($('#vjumlah'+x).val()));
                    }
                    jumall= jumall-jum;
                    if(jumall>0){
                        $('#vlebih').val(formatcemua(jumall));
                        if(x==id){
                            $('#vjumlah'+id).val(formatcemua(data[0].v_sisa));
                            by  = parseFloat(formatulang($('#vjumlah'+id).val()));
                            bay = jumasal-by;
                            sis = parseFloat(formatulang($('#vsisa'+id).val()));
                            $('#vlebih'+id).val(formatcemua(bay));
                        }
                        sisa=sisa+jum;
                    }else{
                        $('#vlebih').val('0');
                        $('#vlebih'+id).val('0');
                        $('#vjumlah'+id).val(formatcemua(jumasal-sisa));
                    }
                }
            }
            hetang(id);
        },
        error: function () {
            alert('Error :)');
        }
    });
    }else{
        $('#idtap'+id).html('');
        $('#idtap'+id).val('');
    }
}
    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest('tr').find('input').val(0);
        $(this).closest('tr').find('input').attr("disabled", true);
        $(this).closest('tr').find('select').attr("disabled", true);
        $(this).closest("tr").hide();       
        alert('xx');
        $(this).closest("tr input").attr('disabled', true);       
        $('#jml').val(xx);
        del();
        hetang(xx);
    });

  function hetang(x){	
    num=document.getElementById("vjumlah"+x).value.replace(/\,/g, '');
    if(!isNaN(num)){
		vjmlbyr	    = parseFloat(formatulang(document.getElementById("vjumlah").value));
        vlebihitem  = vjmlbyr;
		vsisadt     = parseFloat(formatulang(document.getElementById("vsisa").value));
		jml		    = document.getElementById("jml").value;
      for(a=1;a<=jml;a++){
        vnota   = parseFloat(formatulang(document.getElementById("vsisa"+a).value));
     	vjmlitem= parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
        if(vjmlitem==0){
        }
        vsisaitem =vnota-vjmlitem;
        if(vsisaitem<0){
          swal("jumlah bayar tidak bisa lebih besar dari nilai notaaa !!!!!");
          document.getElementById("vjumlah"+a).value=0;
          vjmlitem  = parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
          vsisaitem = parseFloat(formatulang(document.getElementById("vsisa"+a).value));
        }
        vlebihitem=vlebihitem-vjmlitem;
        if(vlebihitem<0){
            vlebihitem=vlebihitem+vjmlitem;
            vsisaitem =vnota-vlebihitem;
            swal("jumlah item tidak bisa lebih besar dari nilai bayar !!!!!");
            document.getElementById("vjumlah"+a).value=formatcemua(vlebihitem);
            vjmlitem  = parseFloat(formatulang(document.getElementById("vjumlah"+a).value));
            vlebihitem=0;
        }
       document.getElementById("vsesa"+a).value=formatcemua(vsisaitem);
       document.getElementById("vlebih"+a).value=formatcemua(vlebihitem);
      }
      document.getElementById("vlebih").value=formatcemua(vlebihitem);
    }else{ 
		swal('input harus numerik !!!');
        document.getElementById("vjumlah"+x).value=0;
	}
  }
</script>
