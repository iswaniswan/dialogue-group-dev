<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-md-12">Nomor Stock Opname</label>
                    <div class="col-sm-6">
                       <input readonly class="form-control" name="istockopname" id="istockopname" value="<?= $isi->i_stockopname; ?>">
                   </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-12">Tanggal Akhir Periode</label>
                    <?php 
		                $tmp=explode('-',$isi->d_stockopname);
		                $yy=$tmp[0];
		                $mm=$tmp[1];
                        $dd=$tmp[2];
                        $thbl=$yy.$mm;
		                $isi->d_stockopname=$dd.'-'.$mm.'-'.$yy;
		            ?>
                    <div class="col-sm-3">
                        <input class="form-control date" readonly name="dstockopname" id="dstockopname" value="<?= $isi->d_stockopname; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-offset-5 col-sm-8">
                    <?php 
                        if($bisaedit){?>
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-info btn-rounded btn-sm" id="addrow""> <i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>
                            &nbsp;&nbsp;
                        <?php
                            }?>
                        <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea."/"; ?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6"> 
                <div class="form-group row">
                    <label class="col-md-12">Gudang</label>
                    <div class="col-sm-6">
                        <input readonly class="form-control" name="estorename" id="estorename" value="<?php echo $isi->e_store_name; ?>">
                        <input type="hidden" id="istore" name="istore" value="<?php echo $isi->i_store; ?>">
						<input type="hidden" id="iarea" name="iarea" value="<?php echo $isi->i_area; ?>">
                    </div>
                </div>                   
                <div class="form-group row">
                    <label class="col-md-12">Lokasi Gudang</label>
                    <div class="col-sm-6">
                        <input readonly type="text" name="estorelocationname" id="estorelocationname" class="form-control" value="<?php echo ($isi->e_store_locationname);?>">
                        <input type="hidden" id="istorelocation" name="istorelocation" value="<?php echo $isi->i_store_location?>">
                   </div>
                </div>   
            </div>
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 13%;">Kode Barang</th>
                                <th style="text-align: center; width: 5%;">Grade</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center; width: 10%;">Motif</th>
                                <th style="text-align: center; width: 10%;">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($detail) {
                                $i = 0;
                                $x = 0;
                                foreach ($detail as $row) { 
                                    $i++; 
                                    ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <spanx id="snum<?= $i;?>"><?=$i;?></spanx>
                                            <input style="text-align: center;" readonly type="hidden" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        </td>
                                        <td>
                                            <input readonly type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                            <input readonly type="hidden" class="form-control" id="iproductmotif<?=$i;?>" name="iproductmotif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                        </td>
                                        <td>
                                        <input readonly type="text" class="form-control" id="iproductgrade<?=$i;?>" name="iproductgrade<?=$i;?>" value="<?= $row->i_product_grade;?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly type="text" id="eproductmotifname<?=$i;?>" name="eproductmotifname<?=$i;?>" value="<?= $row->e_product_motifname; ?>">
                                        </td>
                                        <td>
                                            <input  style="text-align: right;"  type="text" class="form-control" id="nstockopname<?=$i;?>" name="nstockopname<?=$i;?>" value="<?= $row->n_stockopname; ?>">
                                        </td>
                                        <?php
                                        $x = $x+$row->n_stockopname;?>
                                    </tr>
                                <?php  }?>
                            <?} ?>
                            
                        </tbody>
                    </table>
                    <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    var xx = $('#jml').val(); 
    $("#addrow").on("click", function () {
        xx++;
            $("#tabledata").attr("hidden", false);
            $('#jml').val(xx);
            var istore = $('#istore').val();
            var istorelocation = $('#istorelocation').val();
            count=$('#tabledata tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
            cols += '<td><select id="iproduct'+xx+'" class="form-control" name="iproduct'+xx+'" onchange="getproduct('+xx+');"></select><input type="hidden" id="iproductmotif'+xx+'" name="iproductmotif'+xx+'" value=""></td>';
            cols += '<td><input readonly type="text" class="form-control" id="iproductgrade'+xx+'" name="iproductgrade'+xx+'" value=""></td>';
            cols += '<td><input readonly type="text" class="form-control" id="eproductname'+xx+'" name="eproductname'+xx+'" value=""></td>';
            cols += '<td><input readonly type="text" class="form-control" id="eproductmotifname'+xx+'" name="eproductmotifname'+xx+'" value=""></td>';
            cols += '<td><input style="text-align: right;" type="text" class="form-control" id="nstockopname'+xx+'" name="nstockopname'+xx+'" class="form-control" value=""></td>';
            cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#iproduct'+xx).select2({
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

    function getproduct(id){
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
            var iproduct = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : iproduct,
                },
                url: '<?= base_url($folder.'/cform/getdetailproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#eproductname'+id).val(data[0].e_product_name);
                    $('#iproductmotif'+id).val(data[0].i_product_motif);
                    $('#eproductmotifname'+id).val(data[0].e_product_motifname);
                    $('#iproductgrade'+id).val(data[0].i_product_grade);
                    
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        /*xx -= 1;*/
        $('#jml').val(xx);
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $(".ibtnDel").attr("disabled", true);
    });
</script>