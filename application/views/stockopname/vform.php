<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal Akhir Periode</label><label class="col-md-8">Gudang - Lokasi Gudang</label>
                        <div class="col-sm-4">
                            <input type="text" required="" readonly id= "dstockopname" name="dstockopname" class="form-control date" onchange="cektanggal(this.value);">
                            <input type="hidden" id= "periode" name="periode" class="form-control" value="<?= $iperiode;?>">
                        </div>
                        <div class="col-sm-8">
                            <select name="istore" id="istore" disabled="" required="" class="form-control select2" onchange="getdetailstore(this.value);">
                                <option value=""></option>
                                <?php if ($store) {                                 
                                    foreach ($store as $key) { ?>
                                        <option value="<?php echo $key->i_store;?>"><?= $key->i_store." - ".$key->e_store_name." - ".$key->e_store_locationname." - ".$key->i_store_location;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                            <input type="hidden" name="istorelocation" id="istorelocation" class="form-control">
                            <input type="hidden" name="iarea" id="iarea" class="form-control">
                        </div>
                    </div>                        
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 15%;">Kode</th>
                                <th style="text-align: center; width: 10%;">Grade</th>
                                <th style="text-align: center; width: 40%;">Nama Barang</th>
                                <th style="text-align: center;">Motif</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: center;">Action</th>
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
<script>
    function cektanggal(tgl) {
        if (tgl!='') {
            $("#istore").attr("disabled", false);
        }else{
            $("#istore").attr("disabled", true);
        }
    }

    function getdetailstore(kode) {
        var istore = $('#istore option:selected').text();
        var dso    = $('#dstockopname').val();
        var istorelocation = istore.substr(-2);
        if (kode!='') {
            if (kode=='AA') {
                iarea = '00';
            }else{
                iarea = kode;
            }
            $("#istorelocation").val(istorelocation);
            $("#iarea").val(iarea);
            $("#addrow").attr("hidden", false);
            $("#tabledata").attr("hidden", false);
        }else{
            $("#addrow").attr("hidden", true);
            $("#tabledata").attr("hidden", true);
        }
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'dso' : dso,
                'istore': kode,
                'istorelocation': istorelocation
            },
            url: '<?= base_url($folder.'/cform/getdetail'); ?>',
            dataType: "json",
            success: function (data) {
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {
                    var zz = a+1;
                    var i_product_motif     = data['detail'][a]['i_product_motif'];
                    var i_product           = data['detail'][a]['i_product'];
                    var i_product_grade     = data['detail'][a]['i_product_grade'];
                    var e_product_name      = data['detail'][a]['e_product_name'];
                    var e_product_motifname = data['detail'][a]['e_product_motifname'];
                    var count = $('#tabledata tr').length;
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center"><spanx id="snum'+zz+'">'+count+'<input type="hidden" id="no'+zz+'" name="no'+zz+'" value="'+zz+'"><input type="hidden" readonly id="iproductmotif'+zz+'" name="iproductmotif'+zz+'" value="'+i_product_motif+'"></td>';
                    cols += '<td><input class="form-control" readonly id="iproduct'+zz+'" name="iproduct'+zz+'" value="'+i_product+'"></td>';
                    cols += '<td><input class="form-control" readonly id="iproductgrade'+zz+'" name="iproductgrade'+zz+'" value="'+i_product_grade+'"></td>';
                    cols += '<td><input class="form-control" readonly id="eproductname'+zz+'" name="eproductname'+zz+'" value="'+e_product_name+'"></td>';
                    cols += '<td><input class="form-control" readonly id="eproductmotifname'+zz+'" name="eproductmotifname'+zz+'" value="'+e_product_motifname+'"></td>';
                    cols += '<td><input class="form-control" id="nstockopname'+zz+'" name="nstockopname'+zz+'" value="0" style="text-align: right;" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);"></td>';
                    cols += '<td style="text-align: center;"><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }
            },
            error: function () {
                swal('Ada kesalahan :)');
            }
        });
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        del();
    });

    function del() {
        obj=$('#tabledata tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id=value.id;
            $('#'+id).html(key+1);
        });
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    
    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $('#istore').select2({
            placeholder: 'Pilih Gudang'
        });
    });

    function dipales(a){
        var a = $('#jml').val();
        if((document.getElementById("dstockopname").value!='') &&(document.getElementById("istore").value!='') && (document.getElementById("istorelocation").value!='')) {
            if(a==1){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<a;i++){
                    if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("iproductgrade"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nstockopname"+i).value=='')){
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
</script>