<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <dir id="pesan"></dir>
                <?php 
                if($isi->d_sjp){
                    if($isi->d_sjp!=''){
                        $tmp=explode("-",$isi->d_sjp);
                        $hr=$tmp[2];
                        $bl=$tmp[1];
                        $th=$tmp[0];
                        $isi->d_sjp=$hr."-".$bl."-".$th;
                    }
                }
                if($isi->d_spmb){
                  if($isi->d_spmb!=''){
                      $tmp=explode("-",$isi->d_spmb);
                      $hr=$tmp[2];
                      $bl=$tmp[1];
                      $th=$tmp[0];
                      $isi->d_spmb=$hr."-".$bl."-".$th;
                  }
               }
                ?>
                <input hidden id="bsj" name="bsj" value="<?php echo $bl; ?>">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Nomor SJP</label>
                        <label class="col-md-3">Tanggal SJP</label>
                        <label class="col-md-3">SPmB</label>
                        <label class="col-md-3">Tgl SPmB</label>
                        <div class="col-sm-3">
                            <input id="isj" name="isj" class="form-control" required="" readonly value="<?= $isi->i_sjp;?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "dsj" name="dsj" class="form-control" required="" readonly value="<?= $isi->d_sjp;?>">
                        </div>
                        <div class="col-sm-3">
                            <input readonly id="ispmb" class="form-control" name="ispmb" value="<?php if($isi->i_spmb) echo $isi->i_spmb; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "dspmb" name="dspmb" class="form-control" required="" readonly value="<?= $isi->d_spmb;?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-3">SJ Lama</label><label class="col-md-3">Nilai Kirim</label>
                        <div class="col-sm-6">
                            <input id= "eareaname" name="eareaname" class="form-control" required="" readonly value="<?= $isi->e_area_name;?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?php if($isi->i_area) echo $isi->i_area; ?>">
                            <input id="istore" name="istore" type="hidden" value="<?php if($isi->i_store) echo $isi->i_store; ?>">
                            <input id="istorelocation" name="istorelocation" type="hidden" value="<?php if($isi->i_store_location) echo $isi->i_store_location; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id="isjold" readonly name="isjold" class="form-control" value="<?= $isi->i_sjp_old; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id="vsj" name="vsj" readonly class="form-control" value="<?= number_format($isi->v_sjp); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if(check_role($i_menu, 3) && ($i_level == 1 || $i_level == 5 || $i_level == 6) && ($isi->f_sjp_cancel == 'f') && ($isi->d_sjp_receive == '')){?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                                &nbsp;&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            
                            <!-- <label class="custom-control custom-checkbox">
                                <input type="checkbox" id="checkAll" name="checkAll" class="custom-control-input" checked>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">&nbsp;&nbsp;Check All</span>
                            </label> -->
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 10%;">Kode Barang</th>
                                <th style="text-align: center; width: 35%;">Nama Barang</th>
                                <th style="text-align: center; width: 30%;">Ket</th>
                                <th style="text-align: center;">Qty Kirim</th>
                                <th style="text-align: center;">Qty Terima</th>
                                <th style="text-align: center;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php               
                            $i=0;
                            if($detail){
                                foreach($detail as $row){ 
                                    $i++;
                                    $vtotal=$row->v_unit_price*$row->n_quantity_deliver;
                                    ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <?= $i;?>
                                            <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                            <input type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product_motif;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            <input type="hidden" readonly class="form-control" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_remark;?>">
                                            <input type="hidden" style="text-align: right;" readonly class="form-control" width:85px; id="vproductmill<?= $i;?>" name="vproductmill<?= $i;?>" value="<?= $row->v_unit_price;?>">
                                        </td>
                                        <td>
                                            <input style="text-align: right;" class="form-control" id="norder<?= $i;?>" name="norder<?= $i;?>" value="<?= $row->n_quantity_order;?>" readonly>
                                        </td> 
                                        <td>
                                            <input style="text-align: right;" class="form-control" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $row->n_quantity_deliver;?>" onkeypress="return hanyaAngka(event);" name="nasal<?= $i;?>" value="<?= $row->n_quantity_deliver;?>" onkeyup="hitungnilai(<?= $i;?>)">
                                            <input type="hidden" style="text-align: right;" readonly class="form-control" width:85px; id="ntmp<?= $i;?>" name="ntmp<?= $i;?>" value="<?= $row->n_quantity_deliver;?>">
                                            <input style="text-align: right;" type="hidden" readonly class="form-control" id="vtotal<?= $i;?>" name="vtotal<?= $i;?>" value="<?= $vtotal;?>">
                                        </td> 
                                        <td style="text-align: center;">
                                            <input type='checkbox' name="chk<?=$i;?>" id="chk<?=$i;?>" value='on' checked onclick="pilihan(this.value,<?= $i;?>)">
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                            <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
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
    var xx = $('#jml').val();
    // $("#addrow").on("click", function () {
    //     xx++;
    //     /*document.getElementById("jml").value = xx;*/
    //     $('#jml').val(xx);
    //     var newRow = $("<tr>");
    //     var cols = "";
    //     cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""></td>';
    //     cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="detail('+xx+');" value=""></td>';
    //     cols += '<td><input id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'" value="" readonly><input type="hidden" id="emotifname'+xx+'" class="form-control" name="emotifname'+xx+'" value="" readonly></td>';
    //     cols += '<td><input id="eremark'+xx+'" class="form-control" name="eremark'+xx+ '"/><input type="hidden" id="vproductmill'+xx+'" class="form-control" name="vproductmill'+xx+'"/ readonly></td>';
    //     cols += '<td><input class="form-control" id="ndeliver'+xx+'" style="text-align: right;" name="ndeliver'+xx+'" required value="" onkeypress="return hanyaAngka(event);"></td>';
    //     cols += '<td><input class="form-control" id="nreceive'+xx+'" name="nreceive'+xx+'" required value="" onkeypress="return hanyaAngka(event);" style="text-align: right;" onkeyup="ngetang();"><input type="hidden" id="vtotal'+xx+'" class="form-control" name="vtotal'+xx+'" readonly></td>';
    //     cols += '<td style="text-align: center;"><input type="checkbox" name="chk'+xx+'" id="chk'+xx+'" value="on" checked onclick="pilihan(this.value,".$i.")></td>';
    //     /*cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
    //     newRow.append(cols);
    //     $("#tabledata").append(newRow);
    //     $('#iproduct'+xx).select2({
    //         placeholder: 'Cari Kode / Nama',
    //         allowClear: true,
    //         ajax: {
    //             url: '<?= base_url($folder.'/cform/product/'); ?>',
    //             dataType: 'json',
    //             delay: 250,
    //             data: function (params) {
    //                 var query   = {
    //                     q       : params.term,
    //                 }
    //                 return query;
    //             },
    //             processResults: function (data) {
    //                 return {
    //                     results: data
    //                 };
    //             },
    //             cache: false
    //         }
    //     });
    // });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        document.getElementById("jml").value = xx;
    });

    $(document).ready(function () {
        //hitungnilai();
        /*$('.select2').select2();*/
        showCalendar('.date');
    });

    function detail(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){            
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode : "+a+" sudah ada !!!!!");            
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
                    'iproduct'  : iproduct,
                },
                url: '<?= base_url($folder.'/cform/detailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].nama);
                    $('#vproductmill'+id).val(formatcemua(data[0].harga));
                    $('#emotifname'+id).val(data[0].namamotif);
                    $('#motif'+id).val(data[0].motif);
                    $('#ndeliver'+id).focus();
                },
                error: function () {
                    swal('Error :)');
                }
            });
        }else{
            $('#iproduct'+id).html('');
            $('#iproduct'+id).val('');
        }
    }

    function dipales(a){
        if((document.getElementById("dreceive").value!='') &&
            (document.getElementById("iarea").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nreceive"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#refresh").attr("disabled", true);
    });

    // $("#checkAll").click(function(){
    //     $('input:checkbox').not(this).prop('checked', this.checked);
    //     ngetang();
    // });

    // function ngetang(){
    //     var jml = parseFloat($('#jml').val());
    //     var tot = 0;
    //     for(brs=1;brs<=jml;brs++){
    //         ord = $("#norder"+brs).val();
    //         hrg  = formatulang($("#vproductmill"+brs).val());
    //         qty  = formatulang(ord);
    //         vhrg = parseFloat(hrg)*parseFloat(qty);
    //         $("#vtotal"+brs).val(formatcemua(vhrg));
    //         if($("#chk"+brs).is(':checked')){
    //             tot+=parseFloat(formatulang($("#vtotal"+brs).val()));
    //         }
    //     }
    //     $("#vsj").val(formatcemua(tot));
    // }

    function pilihan(a,b){
      if(a==''){
          document.getElementById("chk"+b).value='on';
      }else{
          document.getElementById("chk"+b).value='';
      }
     hitungnilai(b);
  }

    function hitungnilai(brs){
        var tot=0;
          ord=document.getElementById("ndeliver"+brs).value;
        psn=document.getElementById("norder"+brs).value;
          if (isNaN(parseFloat(ord))){
              alert("Input harus numerik");
          }else{
          if((parseFloat(psn))<(parseFloat(ord))){
            alert('Jumlah kirim tidak boleh lebih besar dari jumlah pesan !!!');
            document.getElementById("ndeliver"+brs).value='0';
          }else{
                hrg=formatulang(document.getElementById("vproductmill"+brs).value);
                qty=formatulang(ord);
                vhrg=parseFloat(hrg)*parseFloat(qty);
                document.getElementById("vtotal"+brs).value=formatcemua(vhrg);
            jml=parseFloat(document.getElementById("jml").value);
            for(i=1;i<=jml;i++){
              if(document.getElementById("chk"+i).value=='on'){
                tot+=parseFloat(formatulang(document.getElementById("vtotal"+i).value));
              }
            }
            document.getElementById("vsj").value=formatcemua(tot);
          }
          }
      }

    function cektanggal(){
        dspb = $('#dreceive').val();
        bspb = $('#breceive').val();
        dtmp = dspb.split('-');
        per  = dtmp[2]+dtmp[1]+dtmp[0];
        bln  = dtmp[1];
        if( (bspb!='') && (dspb!='') ){
            if(bspb != bln){
                swal("Tanggal Terima SJP tidak boleh dalam bulan yang berbeda !!!");
                $("#dreceive").val('');
            }
        }

        dsj     = $('#dsj').val();
        dsjpec  = $('#dreceive').val();
        sj      = dsj.split('-');
        tglsj   = sj[2]+sj[1]+sj[0];
        rc      = dsjpec.split('-');
        tglrec  = rc[2]+rc[1]+rc[0];
        if (tglrec<tglsj) {
            swal("Tanggal SJP Receive tidak boleh lebih kecil dari tanggal SJP !!!");
            $("#dreceive").val('');
        }
    }
</script>