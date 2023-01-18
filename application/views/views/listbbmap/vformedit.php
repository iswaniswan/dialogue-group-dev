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
                
                <div id="pesan"></div>

                <div class="col-md-6">
                    <?php 
                            if($isi->d_ap!=''){       
                                $tmp=explode("-",$isi->d_ap);
                                $th=$tmp[0];
                                $bl=$tmp[1];
                                $hr=$tmp[2];
                                $dap=$hr."-".$bl."-".$th;
                            }
                    ?>
                    <?php if($isi){
                    ?>              
                    <div class="form-group">
                        <label class="col-md-12">No OP</label>
                        <div class="col-sm-6">
                            <input type="text" name="iop" class="form-control" value="<?= $isi->i_op;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No AP</label>
                        <div class="col-sm-6">
                            <input id="iap" name="iap" type="text" maxlength="6" class="form-control" onkeyup="gede(this);" value="<?= $isi->i_ap;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pemasok</label>
                        <div class="col-sm-6">
                            <input readonly id="esuppliername" class="form-control" name="esuppliername" value="<?= $isi->e_supplier_name;?>"readonly>
                            <input id="isupplier" name="isupplier" class="form-control" type="hidden" value="<?= $isi->i_supplier;?>">
                            <input id="nsupplierdiscount" name="nsupplierdiscount" class="form-control" type="hidden" value="<?=$isi->n_supplier_discount; ?>">
                            <input id="nsupplierdiscount2" name="nsupplierdiscount2" class="form-control" type="hidden" value="<?=$isi->n_supplier_discount2; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-8">
                        
                        <?php if(check_role($i_menu, 3) && $isi->f_ap_cancel == 'f' && $inota=='t'){?>                            
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="dipales(parseFloat(document.getElementById('jml').value));"> <i
                                    class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                        <?php }?>    

                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$allsupp;?>","#main")'> 
                                    <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                       
                        <?php if(check_role($i_menu, 3) && $isi->f_ap_cancel == 'f' && $inota=='t'){?>
                            <button type="button" id="addrow1" class="btn btn-primary btn-rounded btn-sm">
                                    <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                        <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-6">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name;?>" readonly>
                            <input id="iarea" name="iarea" type="hidden" class="form-control" value="<?= $isi->i_area;?>">
                        </div>
                    </div>
                    <div class="form-group">
                            <label class="col-md-12">Tanggal AP</label>
                            <div class="col-sm-6">
                                <input readonly id="dap" name="dap" class="form-control date" value="<?= $dap; ?>">
                                <input type = "hidden" id="iapold" name="iapold" readonly value="<?= $isi->i_ap_old; ?>">
                            </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                            <input readonly id="vapgross" class="form-control" name="vapgross" value="<?= number_format($isi->v_ap_gross); ?>">
                        </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                    <?php
                    }else{                           
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"6\" style=\"text-align:center;\">Maaf Tidak Ada isi!</td></tr></table>";
                    }?> 
                    </div>                    
                            <div class="panel-body table-responsive">
                                <table id="tableisi" class="display table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Motif</th>
                                            <th>Harga</th>
                                            <th>Jml Terima</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <?php $i=0; ?>
                                    <?php if($detail!='') {
                                        $query 		= $this->db->query("select * from tm_ap_item where i_ap = '$isi->i_ap' ");//and i_supplier='$isupplier'");
                                        $jmlitem 	= $query->num_rows(); 	
                                        ?>
                                    <tbody>
                                    <? 
                                        $i=0;
                                        foreach($detail as $row){
                                        $i++;
                                        $total=0;
                                        $jumdo=0;
                                        $pangaos=number_format($row->v_product_mill);
                                        $toti=$row->v_product_mill*$row->n_receive;
                                        $total=number_format($toti);
                                    ?>
                                    <tr>
                                        <td class="col-sm-1"> 
                                            <input style="width:40px;" readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                            <input type="hidden"  class="form-control" id="motif<?=$i; ?>" name="motif<?=$i; ?>" value="<?php echo $row->i_product_motif; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input style="width:100px;" readonly type="text" class="form-control" id="iproduct<?=$i; ?>" name="iproduct<?=$i; ?>" value="<?php echo $row->i_product; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input style="width:272px;" readonly type="text" class="form-control" id="eproductname<?=$i; ?>" name="eproductname<?=$i; ?>" value="<?php echo $row->e_product_name; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input readonly style="width:93px;"  type="text" class="form-control" id="emotifname<?=$i; ?>" name="emotifname<?=$i; ?>" value="<?php echo $row->e_product_motifname; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input readonly style="width:85px;"  type="text" class="form-control" id="vproductmill<?=$i; ?>" name="vproductmill<?=$i; ?>" value="<?php echo $pangaos; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input style="width:85px;" type="text" class="form-control" id="nreceive<?=$i; ?>" name="nreceive<?=$i; ?>" value="<?= $row->n_receive; ?>" onkeyup="hitungnilai(this.value,<?=$jmlitem; ?>); ">
                                            <input type="hidden" class="form-control" id="ntmp<?=$i; ?>" name="ntmp<?=$i; ?>" value="<?php echo $row->n_receive; ?>">
                                        </td>
                                        <td class="col-sm-1"> 
                                            <input readonly style="width:88px;" type="text" class="form-control" id="vtotal<?=$i; ?>" name="vtotal<?=$i; ?>" value="<?= $total;?>">
                                        </td>
                                        <td class="col-sm-1">
                                            <?php if($inota=='t' && check_role($i_menu, 4)){?>
                                                <button type="button" onclick="hapusitem('<?= $row->i_product."','".$row->i_product_motif."','".$row->v_product_mill."','".$row->i_product_grade."','".$row->n_receive."','".$row->i_ap;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                        <?}?>
                                    </tbody>
                                </table>
                                <?}?>
                            </div>
                            <div id="pesan"></div>
                            <input type="hidden" name="jml" id="jml" value="<?= $i; ?>">
                            </form>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
    });

    function hapusitem(i_product,i_product_motif,v_product_mill,i_product_grade,n_receive,i_ap) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                       'i_product'       : i_product,
                       'i_product_motif' : i_product_motif,
                       'v_product_mill'  : v_product_mill,
                       'i_product_grade' : i_product_grade,
                       'n_receive'       : n_receive,
                       'i_ap'            : i_ap,
                   },
                   url: '<?= base_url($folder.'/cform/deleteitem'); ?>',
                   dataType: "json",
                   success: function (data) {
                    swal("Dihapus!", "Data berhasil dihapus :)", "success");
                    show('<?= $folder;?>/cform/edit/<?= $inota.'/'.$iap.'/'.$isupp.'/'.$dfrom.'/'.$dto.'/'.$allsupp;?>','#main');     
                },
                error: function () {
                    swal("Maaf", "Data gagal dihapus :(", "error");
                }
            });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }

    function get(id) {
        /*alert(iarea);*/
        $.ajax({
            type: "post",
            isi: {
                'i_op': id
            },
            url: '<?= base_url($folder.'/cform/getop'); ?>',
            isiType: "json",
            success: function (isi) {
                $('#isupplier').val(isi[0].i_supplier);
                $('#esuppliername').val(isi[0].e_supplier_name);
                $('#iarea').val(isi[0].i_area);
                $('#eareaname').val(isi[0].e_area_name);
                $('#nsupplierdiscount').val(isi[0].n_supplier_discount);
                $('#nsupplierdiscount2').val(isi[0].n_supplier_discount2);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function pembandingnilai(a){
     var n_deliver   = document.getElementById('ndeliver'+a).value;
     var deliverasal = document.getElementById('ndeliverhidden'+a).value;

     if(parseFloat(n_deliver) > parseFloat(deliverasal)) {
        alert('Jml kirim ( '+n_deliver+' item ) tdk dpt melebihi Order ( '+deliverasal+' item )');
        document.getElementById('ndeliver'+a).value   = deliverasal;
        document.getElementById('ndeliver'+a).focus();
        return false;
     }
  }

  function hitungnilai(isi,jml){
   if (isNaN(parseFloat(isi))){
      alert("Input harus numerik");
   }else{
      var vtot=0;
      for(i=1;i<=jml;i++){
			vhrg=formatulang(document.getElementById("vproductmill"+i).value);
			nqty=formatulang(document.getElementById("nreceive"+i).value);
			vhrg=parseFloat(vhrg)*parseFloat(nqty);
			vtot=vtot+vhrg;
			document.getElementById("vtotal"+i).value=formatcemua(vhrg);
		}
        document.getElementById("vapgross").value=formatcemua(vtot);
   }
  }

  function sisa(a){
    var sisa = parseFloat(document.getElementById('sisa'+a).value);
    var ndeliver = parseFloat(document.getElementById('ndeliver'+a).value);

    if(ndeliver > sisa){
      alert('Sisa Jumlah Kirim Adalah : '+sisa);
      document.getElementById('ndeliver'+a).value   = 0;
       document.getElementById('ndeliver'+a).focus();
    }
  }

  function dipales(a){
    cek='false';
    if((document.getElementById("ddo").value!='') &&
      (document.getElementById("isupplier").value!='') &&
      (document.getElementById("ido").value!='') &&
      (document.getElementById("iop").value!='')) {
      if(a==0){
         alert('Isi isi item minimal 1 !!!');
      }else{
            for(i=1;i<=a;i++){
            if((document.getElementById("iproduct"+i).value=='') ||
               (document.getElementById("eproductname"+i).value=='') ||
               (document.getElementById("ndeliver"+i).value=='')){
               alert('isi item masih ada yang salah !!!');
               exit();
               cek='false';
            }else{
               cek='true';
            }
         }
      }
      if(cek=='true'){
         document.getElementById("login").disabled=true;
         document.getElementById("cmdtambahitem").disabled=true;
         document.getElementById("submit").disabled=true;
      }else{
            document.getElementById("login").disabled=false;
         document.getElementById("cmdtambahitem").disabled=false;
      }
    }else{
         alert('isi header masih ada yang salah !!!');
    }
  }

</script>