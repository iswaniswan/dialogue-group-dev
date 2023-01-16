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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-6">No Adjustment</label><label class="col-md-6">Tanggal Adjustment</label>
                            <div class="col-sm-6">
                                <input readonly id="iadj" name="iadj" class="form-control" value="<?php echo $isi->i_adj; ?>">
                            </div>
                            <div class="col-sm-3">
                            <?php 
				                $tmp=explode("-",$isi->d_adj);
				                $th =$tmp[0];
				                $bl =$tmp[1];
				                $hr =$tmp[2];
				                $isi->d_adj=$hr."-".$bl."-".$th;
			                ?>
                                <input readonly id="dadj" name="dadj" class="form-control date" value="<?php echo $isi->d_adj; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Gudang</label>
                            <div class="col-sm-6">
                                <input readonly id="iarea" name="iarea" class="form-control" type="hidden" value="<?php echo $isi->i_area; ?>">
                                <input readonly id="eareaname" name="eareaname" class="form-control" type="text" value="<?php echo $isi->e_area_name; ?>">
                                <input readonly id="istore" name="istore" class="form-control" type="hidden" value="<?php echo $isi->i_store; ?>">
                                <input readonly id="istorelocation" name="istorelocation" class="form-control" type="hidden" value="<?php echo $isi->i_store_location; ?>">
                            </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-3 col-sm-8">
		                        <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                                &nbsp;&nbsp; 
                                <button type="button" id="addrow" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                                &nbsp;&nbsp;
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?=$dfrom;?>/<?=$dto;?>/<?=$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group row">
                        <label class="col-md-12">Stock Opname</label>
                        <div class="col-sm-6">
                            <input readonly id="istockopname" name="istockopname" class="form-control" type="text" value="<?php echo $isi->i_stockopname; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-6">
                            <input readonly id="eremark" name="eremark" class="form-control" type="text" value="<?php echo $isi->e_remark; ?>">
                        </div>
                    </div>
                </div>
                    <div class="table-responsive">
                    <table class="table table-bordered" id="tabledata" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; width: 7%;">No</th>
                                        <th style="text-align: center; width: 12%;">Kode Barang</th>
                                        <th style="text-align: center; width: 35%;">Nama Barang</th>
                                        <th style="text-align: center; width: 10%;">Motif</th>
                                        <th style="text-align: center; width: 10%;">Jumlah</th>
                                        <th style="text-align: center; width: 3 0%;">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php               
                                    if($detail){
                                        $i=0;
                                        foreach($detail as $row){
                                                $i++;?>
                                                    <tr>
                                                        <td> 
                                                            <input readonly type="text" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?=$i;?>">
                                                            <input type="hidden" class="form-control" id="motif<?=$i; ?>" name="motif<?=$i; ?>" value="<?php echo $row->i_product_motif; ?>">
                                                            <input type="hidden" class="form-control" id="grade<?=$i; ?>" name="grade<?=$i; ?>" value="<?php echo $row->i_product_grade; ?>">
                                                        </td>
                                                        <td> 
                                                            <input readonly type="text" class="form-control" id="iproduct<?=$i; ?>" name="iproduct<?=$i; ?>" value="<?php echo $row->i_product; ?>">
                                                        </td>
                                                        <td> 
                                                            <input readonly type="text" class="form-control" id="eproductname<?=$i; ?>" name="eproductname<?=$i; ?>" value="<?php echo $row->e_product_name.' ('.$row->i_product_grade.')'; ?>">
                                                        </td>
                                                        <td> 
                                                            <input readonly type="text" class="form-control" id="emotifname<?=$i; ?>" name="emotifname<?=$i; ?>" value="<?php echo $row->e_product_motifname; ?>">
                                                        </td>
                                                        <td> 
                                                            <input type="text" class="form-control" id="nquantity<?=$i; ?>" name="nquantity<?=$i; ?>" value="<?php echo $row->n_quantity; ?>">
                                                        </td>
                                                        <td> 
                                                            <input type="text" class="form-control" id="eremark<?=$i; ?>" name="eremark<?=$i; ?>" value="<?php echo $row->e_remark; ?>">
                                                        </td>
                                                    </tr>
                                           <? }
                                        }
                                    ?>
                                    </div>
                                    <input type="hidden" name="jml" id="jml" value="<?= $jmlitem;?>">
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
        $('.select2').select2();
        showCalendar('.date');
    });

    var counter = $('#jml').val(); 
    var istore = $('#istore').val();
    var istorelocation = $('#istorelocation').val();
    $("#addrow").on("click", function () {
        counter++;
        $("#tabledata").attr("hidden", false);
        $('#jml').val(counter);
        count=$('#tabledata tr').length;
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td><input readonly type="text" class="form-control" id="baris'+ counter + '" name="baris'+ counter + '" value="'+counter+'"/><input readonly type="hidden" class="form-control" id="motif'+ counter + '" class="form-control" name="motif'+ counter + '" value=""/><input readonly type="hidden" class="form-control" id="grade'+ counter + '" class="form-control" name="grade'+ counter + '" value=""/></td>';
        cols += '<td><select readonly class="form-control select2" id="iproduct'+ counter + '" name="iproduct' + counter + '" onchange="getgrade('+counter+');"/></select></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="eproductname'+ counter + '" name="eproductname'+ counter + '"/></td>';
        cols += '<td><input readonly class="form-control" input type="text" id="emotifname'+ counter + '" name="emotifname'+ counter + '"/></td>';
        cols += '<td><input class="form-control" type="text" id="nquantity'+ counter + '" name="nquantity'+ counter + '" value="0"/></td>';
        cols += '<td><input class="form-control" type="text" id="eremark'+ counter + '" name="eremark' + counter + '" value=""/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger"  value="Delete"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow); 
        $('#iproduct'+counter).select2({
            placeholder: 'Cari Product / Barang',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getproduct/'); ?>'+istore+'/'+istorelocation,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iproduct   = $('#iproduct').val();

                    var query   = {
                        q           : params.term,
                        iproduct    : iproduct
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
    });

    function getdetailproduct(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode Barang : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var iproduct = $('#iproduct'+id).val();
            var iproductgrade = $('#grade'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct': iproduct,
                    'iproductgrade' : iproductgrade
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",

                success: function (data) {
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#motif'+id).val(data[0].i_product_motif);
                    $('#emotifname'+id).val(data[0].e_product_motifname);
                    // $('#grade'+id).val(data[0].i_product_grade);
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#eproductname'+id).html('');
            $('#eproductname'+id).val('');
            $('#emotif'+id).html('');
            $('#emotif'+id).val('');
            $('#emotifname'+id).html('');
            $('#emotifname'+id).val('');
        }
    }

    function getgrade(id){
        ada=false;
        var a = $('#iproduct'+id).val();
        var x = $('#jml').val();
        for(i=1;i<=x;i++){
            if((a == $('#iproduct'+i).val()) && (i!=x)){
                swal ("kode Barang : "+a+" sudah ada !!!!!");            
                ada=true;            
                break;        
            }else{            
                ada=false;             
            }
        }
        if(!ada){
            var iproduct = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct': iproduct,
                },
                url: '<?= base_url($folder.'/cform/getdetailproductgrade'); ?>',
                dataType: "json",

                success: function (data) {
                    $('#grade'+id).val(data[0].i_product_grade);
                    getdetailproduct(id);
                },
                error: function () {
                    alert('Error :)');
                }
            });
        }else{
            $('#grade'+id).html('');
            $('#grade'+id).val('');
        }
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        /*xx -= 1;*/
        $('#jml').val(xx);
        del();
    });

    function del() {
        obj=$('#tabledata tr').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    function dipales(a){
    cek='false';
    if( (document.getElementById("dadj").value!='')||(document.getElementById("iarea").value!='')||(document.getElementById("eremark").value!='')||(document.getElementById("istockopname").value!='') ) {
      if(a==0){
        alert('Isi data item minimal 1 !!!');
      }else{
        for(i=1;i<=a;i++){
          if((document.getElementById("iproduct"+i).value=='') ||
             (document.getElementById("eproductname"+i).value=='') ||
             (document.getElementById("nquantity"+i).value=='')){
            alert('Data item masih ada yang salah !!!');
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
      }else{
        document.getElementById("login").disabled=false;
      }
    }else{
      alert('Data header masih ada yang salah !!!');
    }
  }
  
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });
</script>
