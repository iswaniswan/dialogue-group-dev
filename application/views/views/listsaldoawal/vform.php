<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-info-circle"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 10%;">Periode</th>
                                <th style="text-align: center; width: 13%;">Kode Barang</th>
                                <th style="text-align: center; width: 30%;">Nama Barang</th>
                                <th style="text-align: center; width: 10%;">Saldo Awal</th>
                                <th style="text-align: center; width: 10%;">Sisa Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                        <input type="hidden" readonly id="e_periode" name="e_periode" value="<?php echo $e_periode; ?>">
                            <?php if ($isi) {
                                $i = 0;
                                foreach ($isi as $row) { 
                                    $i++; 
                                    ?>
                                    <tr>
                                        <td style="text-align: center;">
                                            <spanx id="snum<?= $i;?>"><?=$i;?></spanx>
                                            <input style="text-align: center;" readonly type="hidden" class="form-control" id="baris<?=$i;?>" name="baris<?=$i;?>" value="<?= $i;?>">
                                        </td>
                                        <td>
                                            <input readonly type="text" class="form-control" id="eperiode<?=$i;?>" name="eperiode<?=$i;?>" value="<?= $row->e_periode; ?>">
                                        </td>
                                        <td>
                                            <input readonly type="text" class="form-control" id="iproduct<?=$i;?>" name="iproduct<?=$i;?>" value="<?= $row->i_product; ?>">
                                            <input readonly type="hidden" class="form-control" id="iproductmotif<?=$i;?>" name="iproductmotif<?=$i;?>" value="<?= $row->i_product_motif; ?>">
                                            <input readonly type="hidden" class="form-control" id="iproductgrade<?=$i;?>" name="iproductgrade<?=$i;?>" value="A">
                                        </td>
                                        <td>
                                            <input class="form-control" readonly type="text" id="eproductname<?=$i;?>" name="eproductname<?=$i;?>" value="<?= $row->e_product_name; ?>">
                                        </td>
                                        <td>
                                            <input type="hidden" class="form-control" id="e_saldo_awal<?=$i;?>" name="e_saldo_awal<?=$i;?>" value="<?= $row->n_saldo_awal; ?>">
                                            <input  style="text-align: right;"  type="text" class="form-control" id="n_saldo_awal<?=$i;?>" name="n_saldo_awal<?=$i;?>" value="<?= $row->n_saldo_awal; ?>" onkeyup="getsaldo('<?=$i;?>')">
                                        </td>
                                        <td>
                                            <input  style="text-align: right;"  class="form-control" readonly type="text" id="n_sisa<?=$i;?>" name="n_sisa<?=$i;?>" value="<?= $sisa = $row->n_saldo_awal - $row->n_sisa; ?>">
                                            <input type="hidden" id="e_sisa<?=$i;?>" name="e_sisa<?=$i;?>" value="<?= $sisa = $row->n_saldo_awal - $row->n_sisa;?>">
                                        </td>
                                    </tr>
                                <?php  }?>
                            <?} ?>
                            
                        </tbody>
                    </table>
                                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                </div>
                
                <div class="col-md-12">          
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12" style="text-align: center;">
                            <button  type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button  type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Tambah Item</button>&nbsp;&nbsp;
                            <a href="#" id="href" onclick = "exportexcel();"><button type="button" class="btn btn-secondary btn-rounded btn-sm"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>&nbsp;&nbsp;
                            <button  type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                             
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
			                <input type="hidden" name="xdfrom" value= "<?php echo $dfrom; ?>">
			                <input type="hidden" name="xdto" value= "<?php echo $dto; ?>">
			                <input type="hidden" name="xiarea" value= "<?php echo $iarea; ?>">
                        </div>
                    </div>
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
            count=$('#tabledata tr').length;
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;"><spanx id="snum'+xx+'">'+count+'</spanx><input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
            cols += '<td><input readonly type="text" id="eperiode'+xx+'" class="form-control" name="eperiode'+xx+'" value="<?= $e_periode; ?>"></td>';
            cols += '<td><select id="iproduct'+xx+'" class="form-control" name="iproduct'+xx+'" onchange="getproduct('+xx+');"></select><input type="hidden" id="iproductmotif'+xx+'" name="iproductmotif'+xx+'" value=""><input type="hidden" id="iproductgrade'+xx+'" name="iproductgrade'+xx+'" value="A"></td>';
            cols += '<td><input type="text" id="eproductname'+xx+'" name="eproductname'+xx+'" value=""></td>';
            cols += '<td><input style="text-align: right;" type="text" id="n_saldo_awal'+xx+'" name="n_saldo_awal'+xx+'" class="form-control" value="" onkeyup="getsisatambah('+xx+');"><input style="text-align: right;" type="hidden" id="e_saldo_awal'+xx+'" class="form-control" name="e_saldo_awal'+xx+'" value=""></td>';
            cols += '<td><input style="text-align: right;" type="text" id="n_sisa'+xx+'" class="form-control" name="n_sisa'+xx+'" value=""><input style="text-align: right;" type="hidden" id="e_sisa'+xx+'" class="form-control" name="e_sisa'+xx+'" value=""></td>';
            cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#iproduct'+xx).select2({
                placeholder: 'Cari Product / Barang',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/getproduct/'); ?>',
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

    function getsisatambah(id){
        var n_saldo_awal = parseInt($('#n_saldo_awal'+id).val());
        $('#e_saldo_awal'+id).val(n_saldo_awal);
        $('#n_sisa'+id).val(n_saldo_awal);
        $('#e_sisa'+id).val(n_saldo_awal);
    }

    function getsaldo(id){
        var n_saldo_awal = parseInt($('#n_saldo_awal'+id).val());
        var e_saldo_awal = parseInt($('#e_saldo_awal'+id).val());
        var e_sisa = parseInt($('#e_sisa'+id).val());
        if(isNaN(parseFloat(n_saldo_awal))){
            swal("INPUT HARUS NUMERIK");
            $('#n_saldo_awal'+id).val(0);
        }else{
            var total = ((n_saldo_awal -  e_saldo_awal) + e_sisa);
            $('#n_sisa'+id).val(total);
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

    function exportexcel(){
        var abc = "<?php echo site_url($folder.'/cform/export/'.$dfrom.'/'.$dto.'/'.$iarea); ?>";
        $("#href").attr("href",abc);
    }
</script>