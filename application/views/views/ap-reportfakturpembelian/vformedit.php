<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> 
                <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?> </a>
            </div>
        <div class="panel-body table-responsive">
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="col-md-6">
                <div id="pesan"></div>  
                    <div class="form-group row">
                        <label class="col-md-4">No Faktur</label> 
                        <label class="col-md-4">Supplier</label>
                        <label class="col-md-4">Tanggal Nota</label>
                        <div class="col-sm-4">
                            <input type="text" name="inota" id="inota" class="form-control" value="<?= $data->i_nota;?>" readonly>
                            <input type="hidden" name="ibtb" id="ibtb" class="form-control" value="<?= $data->i_sj_masuk;?>" readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="hidden" name="isupplier" class="form-control" value="<?= $data->i_supplier;?>" readonly>
                            <input type="text" name="esupplier" class="form-control" value="<?= $data->e_supplier_name;?>"
                            readonly>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="dnota" class="form-control" value="<?= $data->d_nota;?>"
                            readonly>
                        </div>
                  </div>
                  <div class="form-group row">                    
                    <label class="col-md-8">Keterangan</label>
                    <label class="col-md-4">Diskon</label>
                    <div class="col-sm-8">
                      <input type="text" name="eremark" id="eremark" class="form-control date" value="<?= $data->e_remark;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="discount" id="discount" class="form-control" value="<?= $data->n_discount;?>"
                      onkeyup = "hitungnilai2(this.value)">
                    </div>
                  </div>
                  <!--- <div class="form-group">
                    <label class="col-md-9">Keterangan</label>
                    <div class="col-sm-9">
                      <input type="text" name="eremark" id="eremark" class="form-control" value="<#?= $data->e_remark;?>">
                    </div>
                  </div> -->
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="sendd" class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                         <!-- <div class="col-sm-offset-5 col-sm-10">
                            <button type="submit" id="submit" disabled class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>
                            <button type="button" id="sendd" disabled class="btn btn-success btn-rounded btn-sm" onclick="return getenabledsend();"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>                    -->
                  </div>
            </div>
                <div class="col-md-6">  
                  <div class="form-group row">
                    <label class="col-md-4">Nilai Kotor</label>
                    <label class="col-md-4">Total Discount</label>
                    <label class="col-md-4">Nilai Bersih</label>
                    <div class="col-sm-4">
                       <input type="text" name="vspb" id="vspb" class="form-control" value="<?= $data->v_gross;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="vspbdiscounttotal" id="vspbdiscounttotal" class="form-control" value="<?= $data->v_discount;?>" readonly>
                    </div>
                    <div class="col-sm-4">
                       <input type="text" name="vspbbersih" id="vspbbersih" class="form-control" value="<?= $data->v_netto;?>" readonly>
                    </div>
                  </div>                  
                </div>        
                <div class="panel-body table-responsive">      
                <table id="tabledata" class="table color-table success-table table-bordered" cellspacing="0" width="100%" >
                        <thead>
                            <tr>
                                <th style width="5%">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Jumlah Total (Rp.)</th>                      
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($data1){
                                $i = 0;
                                foreach($data1 as $row){
                                // $checked = !empty($row->sjnota)?"checked":"";
                                    $i++;
                            ?>
                            <tr>   
                            <td>
                               <!--  -->
                               <input style="width:40px" class="form-control" type="text" id="no<?=$i;?>" name="no<?=$i;?>" value="<?php echo $i; ?>" readonly>
                            </td>                      
                             <td >  
                                <input style="width:100px" class="form-control" type="text" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>" value="<?= $row->i_material; ?>" readonly>
                            </td>
                            <td >  
                                <input style="width:400px" class="form-control" type="text" id="ematerial<?=$i;?>" name="ematerial<?=$i;?>" value="<?= $row->e_material_name; ?>" readonly>
                            </td>
                            <td>
                                <input style="width:100px;" type="hidden" id="isatuan<?=$i;?>" class="form-control" name="isatuan<?=$i;?>" value="<?=$row->i_satuan_code;?>" readonly>
                                <input style="width:100px;" type="text" id="esatuaneks<?=$i;?>" class="form-control" name="esatuaneks<?=$i;?>" value="<?=$row->e_satuan;?>" readonly>
                            </td>
                            <td >  
                                <input style="width:100px" class="form-control" type="text" id="nquantity<?=$i;?>"name="nquantity<?=$i;?>" value="<?php echo number_format($row->n_quantity,2); ?>" readonly>
                            </td>
                            <td>
                                <input style="width:100px" class="form-control" type="text" id="vprice<?=$i;?>" name="vprice<?=$i;?>" value="<?= $row->v_price; ?>" onkeyup="hitungnilai(this.value)">
                            </td>
                            <td>
                                <input style="width:100px" class="form-control" type="text" id="total<?=$i;?>" name="total<?=$i;?>" value="<?= $row->v_tot?>" readonly>
                            </td>                
                            </tr>    
                            
                            <?}
                            }?>
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">           
                        </tbody>                         
                    </table>
                </form>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>

<script>
 $(document).ready(function () {
    $(".select2").select2();
 });

 $("form").submit(function (event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#sendd").attr("disabled", false);
});

function getenabledsend() {
    $('#sendd').attr("disabled", true);
    $('#submit').attr("disabled", true);
}

$(document).ready(function(){
    $("#sendd").on("click", function () {
        var inota = $("#inota").val();
        $.ajax({
            type: "POST",
            url: "<?= base_url($folder.'/cform/sendd'); ?>",
            data: {
                     'inota'  : inota,
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

 $(document).ready(function () {
    $('.select2').select2();
    showCalendar('.date');
});

function max_tgl(val) {
  $('#dpajak').datepicker('destroy');
  $('#dpajak').datepicker({
    autoclose: true,
    todayHighlight: true,
    format: "dd-mm-yyyy",
    todayBtn: "linked",
    daysOfWeekDisabled: [0],
    startDate: document.getElementById('dnota').value,
  });
}
$('#dpajak').datepicker({
  autoclose: true,
  todayHighlight: true,
  format: "dd-mm-yyyy",
  todayBtn: "linked",
  daysOfWeekDisabled: [0],
  startDate: document.getElementById('dnota').value,
});

function hitungnilai(isi,jml){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
            dtmp1=parseFloat(formatulang(document.getElementById("discount").value));
            vdis1   =0;
            vtot    =0;
            
            for(i=1;i<=jml;i++){
                vhrg=formatulang(document.getElementById("vprice"+i).value);
                if (isNaN(parseFloat(document.getElementById("nquantity"+i).value))){
                    nqty=0;
                }else{
                    nqty=formatulang(document.getElementById("nquantity"+i).value);
                    vhrg=parseFloat(vhrg)*parseFloat(nqty);
                    vtot=vtot+vhrg;
                    document.getElementById("total"+i).value=formatcemua(vhrg);
                    // alert(vtot);
                }    
            }
            vdis1=vdis1+((vtot*dtmp1)/100);
            // alert("asasa");
            // vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
            // vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
            vdis1=parseFloat(vdis1);
            // vdis2=parseFloat(vdis2);
            // vdis3=parseFloat(vdis3);
            vtotdis=vdis1
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
        }
    }

    function hitungnilai2(isi){
        jml=document.getElementById("jml").value;
        // alert("oke");
        if (isNaN(parseFloat(isi))){
            swal("Input harus numerik");
        }else{
            // alert(isi);
            dtmp1=parseFloat(formatulang(document.getElementById("discount").value));
            vdis1   =0;
            vtot    =0;
            
            for(i=1;i<=jml;i++){
                vhrg=formatulang(document.getElementById("vprice"+i).value);
                if (isNaN(parseFloat(document.getElementById("nquantity"+i).value))){
                    nqty=0;
                }else{
                    nqty=formatulang(document.getElementById("nquantity"+i).value);
                    vhrg=parseFloat(vhrg)*parseFloat(nqty);
                    vtot=vtot+vhrg;
                    // alert(vhrg);
                    document.getElementById("total"+i).value=formatcemua(vhrg);
                    
                }    
            }
            vdis1=vdis1+((vtot*dtmp1)/100);
            // alert("asasa");
            // vdis2=vdis2+(((vtot-vdis1)*dtmp2)/100);
            // vdis3=vdis3+(((vtot-(vdis1+vdis2))*dtmp3)/100);
            vdis1=parseFloat(vdis1);
            // vdis2=parseFloat(vdis2);
            // vdis3=parseFloat(vdis3);
            vtotdis=vdis1
            document.getElementById("vspbdiscounttotal").value=formatcemua(Math.round(vtotdis));
            document.getElementById("vspb").value=formatcemua(vtot);
            vtotbersih=parseFloat(formatulang(formatcemua(vtot)))-parseFloat(formatulang(formatcemua(Math.round(vtotdis))));
            document.getElementById("vspbbersih").value=formatcemua(vtotbersih);
        }
    }

function validasi(){
    var s=0;
    var textinputs = document.querySelectorAll('input[type=checkbox]'); 
    var empty = [].filter.call( textinputs, function( el ) {
       return !el.checked
    });
    if (textinputs.length == empty.length) {
        alert("Maaf Tolong Pilih Minimal 1 SJ!");
        return false;
    } else if(document.getElementById('dnota').value==''){
        alert("Maaf Tolong Pilih Tanggal Faktur");
        return false;
    } else if(document.getElementById('dpajak').value==''){
        alert("Maaf Tolong Pilih Tanggal Pajak");
        return false;
    }else {
        return true
    }
  }
</script>
