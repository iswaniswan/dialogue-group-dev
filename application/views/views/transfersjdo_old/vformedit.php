<div class="row">
    <div class="col-lg-12">
        <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
        <div class="panel panel-info">

            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>                
            <div class="panel-body table-responsive">
            <div class="col-md-6">
                <div id="pesan"></div>   
                    <div class="form-group">
                        <div class="row">
                        <label class="col-md-12">Nomor Bank / Tanggal Bank</label>
                        <div class="col-sm-5">
                            <input class="form-control" readonly="true" type="text" name="ikbank" id="ikbank" value="<?= $bank->i_kbank; ?>">
                        </div>
                        <div class="col-sm-5">
                          <input class="form-control" readonly="true" type="text" name="dbank" id="dbank" value="<?= $bank->d_bank; ?>">
                            <input type="hidden" readonly id="icoabank" name="icoabank" value="<?= $bank->i_coa_bank; ?>">
                        </div>
                    </div>
                    </div>  
                    <div class="form-group">
                    <div class="row">
                        <label class="col-md-12">Tanggal Alokasi / Bank</label>
                        <div class="col-sm-5">
                            <input class="form-control" readonly="true" type="text" name="dalokasi" id="dalokasi" value="<?= $bank->d_bank; ?>">
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control" readonly="true" type="text" name="ebank" id="ebank" value="<?= $ebank->e_bank_name; ?>">
                            <input type="hidden" readonly id="ibank" name="ibank" value="<?= $ebank->i_bank; ?>">
                        </div>
                    </div> 
                    </div> 
                </div>
                <div class="col-md-6">
                <div id="pesan"></div>           
                    <div class="form-group">
                        <label class="col-md-12">Supplier</label>
                        <div class="col-sm-12">
                            <select name="isupplier" id="isupplier" class="form-control select2" onchange="getenabled(this.value);"> 
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Jumlah</label>
                        <div class="col-sm-12">
                            <input type="text" id = "vjumlah" name="vjumlah" class="form-control"  value="<?= $bank->v_sisa; ?>">
                            <!--<input type="text" id = "vjumlahx" name="vjumlahx" class="form-control>-->
                            <!--<input class="form-control" readonly="true" type="text" name="vjumlah1" id="vjumlah1" value="<?= $bank->v_sisa; ?>">
                            --><input type="hidden" id="vlebihh" name="vlebihh" value="0" >
                            <!--<input type="hidden" id="vsisaa" name="vsisaa" value="<?= $bank->v_sisa; ?>" >-->
                        </div>
                    </div> 
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            
                        <button type="button" id="addrow" align="left" class="btn btn-info btn-rounded btn-sm" disabled=""><i class="fa fa-plus" ></i>&nbsp;&nbsp;Tambah</button>
                    </div>               
                </div>
            <div class="panel-body table-responsive">
                <table id="tabledata" class="table table-bordered" cellspacing="0" width="100%"  hidden="true">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th style="width: 18%;">Kode Nota</th>
                            <th style="width: 14%;">Tgl Nota</th>
                            <th style="width: 12%;">Nilai</th>
                            <th style="width: 12%;">Bayar</th>
                            <th style="width: 12%;">Sisa</th>
                            <th style="width: 12%;">Lebih</th>
                            <th style="width: 12%;">Jumlah</th>
                            <th style="width: 15%;">Keterangan</th>
                            <th style="width: 5%;">Action</th>              
                        </tr>
                    </thead>
                    <tbody>                                       
                    </tbody>
                        <input type="hidden" name="jml" id="jml" value="">
                </table>
            </div>            
        </form>
    </div>
</div>
<script>
var counter =0;
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
            // cols += '<td><select  type="text" id="inota'+ counter + '" class="form-control" name="inota'+ counter + '" onchange="getnota('+ counter +');"></td>';
            // cols += '<td><input type="text" id="dnota'+ counter + '" type="text" class="form-control" name="dnota' + counter + '" readonly></td>';
            // cols += '<td><input type="text" id="vnilai'+ counter + '" class="form-control" name="vnilai'+ counter + '" readonly/></td>';
            // cols += '<td><input type="text" id="vbayar'+ counter + '" class="form-control" name="vbayar'+ counter + '" onkeyup="cekval(this.value);"/></td>';
            // cols += '<td><input type="text" id="vsisa'+ counter + '" class="form-control" name="vsisa'+ counter + '" readonly/><input type="hidden" id="vsesa'+ counter + '" class="form-control" name="vsesa'+ counter + '" value="0" readonly/></td>';
            // cols += '<td><input type="text" id="vlebih'+ counter + '" class="form-control" name="vlebih' + counter + '" readonly/></td>';
            // cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark' + counter + '"/></td>';
            // cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
            cols += '<td><input style="width:40px;" readonly type="text" id="baris'+counter+'" name="baris'+counter+'" value="'+counter+'"></td>';
            cols += '<td><select type="text" id="inota'+ counter + '" class="form-control" name="inota'+ counter + '" onchange="getnota('+ counter +');"></td>';
            cols += '<td><input type="text" id="dnota'+ counter + '" type="text" class="form-control" name="dnota' + counter + '" readonly></td>';
            cols += '<td><input type="text" id="vnilai'+ counter + '" class="form-control" name="vnilai'+ counter + '" onkeyup="cekval(this.value); reformat(this);" readonly/></td>';
            cols += '<td><input type="text" id="vbayar'+ counter + '" class="form-control" name="vbayar'+ counter + '" onkeyup="cekval(this.value); reformat(this);"/></td>';
            cols += '<td><input type="text" id="vsisa'+ counter + '" class="form-control" name="vsisa'+ counter + '" readonly /></td>';
            cols += '<td><input type="text" id="vlebih'+ counter + '" class="form-control" name="vlebih' + counter + '" readonly/></td>';
            cols += '<td><input type="text" id="vjumlah'+ counter + '" class="form-control" name="vjumlah'+ counter + '"readonly/></td>';
            cols += '<td><input type="text" id="eremark'+ counter + '" class="form-control" name="eremark' + counter + '"/></td>';
             cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
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
     $("#addrow").attr("disabled", true);
 });

$("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
        document.getElementById("jml").value = counter;
    });
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

function getnota(id){
        var inota = $('#inota'+id).val();
        //var vjumlah1 = $('#vjumlah1').val();
        
        var vjumlah = $('#vjumlah').val();
        var vjumlahx = $('#vjumlahx').val();
        var jml = $('#jml').val();
        var vkb2 = 0;
        $.ajax({
        type: "post",
        data: {
            'i_nota': inota
        },
        url: '<?= base_url($folder.'/cform/getnota');?>',
        dataType: "json",
        success: function (data) {
            // $('#dnota'+id).val(data[0].d_nota);
            // $('#vnilai'+id).val(data[0].v_total);
            // $('#vbayar'+id).val(data[0].v_total); 
            // //$('#vsesa'+id).val(data[0].v_sisa);
            // //$('#vlebih'+id).val(data[0].v_total); 
            // $('#vsesa'+id).val(vjumlah1-data[0].v_total);
            // //$('#vsesa'+id).val(vjumlah1-data[0].v_total);
            // // // $('#vsisa'+id).val(vkb-data[0].v_total);
            // if(vjumlah1-data[0].v_total <= 0){
            //     $('#vsesa'+id).val((vjumlah1-data[0].v_total)*-1);
            // }
            // $('#vlebih'+id).val(vjumlah1-data[0].v_sisa);
            // if(vjumlah1-data[0].v_sisa <= 0){
            //     $('#vlebih'+id).val((vjumlah1-data[0].v_sisa)*0);
            // }

            $('#dnota'+id).val(data[0].d_nota);
            $('#vnilai'+id).val(data[0].v_total);
            $('#vbayar'+id).val(data[0].v_sisa);
            
            ada=false;
            var a = $('#inota'+id).val();
            var jml = $('#jml').val();
            for(i=1;i<=jml;i++){
                if((a == $('#inota'+i).val()) && (i!=jml)){
                    swal ("Kode : "+a+" sudah ada !!!!!");
                    ada=true;
                    break;
                }else{
                    ada=false;     
                }
            }

            if(!ada){
                var inota    = $('#inota'+id).val();
                $.ajax({
                    type: "post",
                    data: {
                        'i_nota'  : inota,
                    },
                    url: '<?= base_url($folder.'/cform/getnota'); ?>',
                    dataType: "json",
                    success: function (data) {
                        $('#dnota'+id).val(data[0].d_nota);
                        $('#vnilai'+id).val(data[0].v_total);
                        $('#vbayar'+id).val(data[0].v_sisa);
                    },
                });
            }else{
                $('#inota'+id).html('');
                $('#dnota'+id).val('');
                $('#vnilai'+id).val('');
                $('#vbayar'+id).val('');
            }
           // if(jml = 1){
                // $('#vsesa'+id).val(data[0].v_total-data[0].v_sisa);
                // if(vjumlah1-data[0].v_total <= 0){
                //     $('#vsesa'+id).val((vjumlah1-data[0].v_total)*-1);
                // }
                // $('#vlebih'+id).val(vjumlah1-data[0].v_sisa);
                // if(vjumlah1-data[0].v_sisa <= 0){
                //     $('#vlebih'+id).val((vjumlah1-data[0].v_sisa)*0);
                // }
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
                        $('#vsisa'+id).val(($('#vjumlah'+id).val()-$('#vnilai'+n).val())*1);
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
        },
        error: function () {
            alert('Error :)');
        }
    });
}

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

 function getenabled(kode) {
        $('#addrow').attr("disabled", false);
        $('#isupplier').attr("disabled", true);
    }
</script>