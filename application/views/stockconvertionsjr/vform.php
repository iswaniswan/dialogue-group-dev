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
                        <label class="col-md-3">Tanggal Konversi</label>
                        <div class="col-sm-3">
                            <input required="" readonly id= "dicconvertion" name="dicconvertion" class="form-control date" readonly value="<?= date('d-m-Y');?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Area - No. SJR - Tanggal SJR</label>
                        <div class="col-sm-9">
                            <select name="irefference" id="irefference" required="" class="form-control select2" onchange="getdetailbarang(this.value);"></select>
                            <input id="drefference" name="drefference" type="hidden">
                            <input id="iarea" name="iarea" type="hidden">
                            <input id="iicconvertion" name="iicconvertion" type="hidden">
                        </div>
                    </div>                 
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i
                                class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="jml" id="jml" value="0">
                    <div class="col-md-12">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                            <thead>
                                <tr>
                                    <th style="width: 4%;">No</th>
                                    <th style="width: 20%;">Kode</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 10%;">Motif</th>
                                    <th style="text-align: center; width: 15%">Qty</th>
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
    function getdetailbarang(kode) {
        var isjr  = $('#irefference option:selected').text();
        var iarea = isjr.substr(0,2);
        var dsjr  = isjr.substr(-10);
        $("#iarea").val(iarea);
        $("#drefference").val(dsjr);
        if (kode!='') {
            $("#tabledata").attr("hidden", false);
        }else{
            $("#tabledata").attr("hidden", true);
        }
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'isjr': kode
            },
            url: '<?= base_url($folder.'/cform/getdetailitem'); ?>',
            dataType: "json",
            success: function (data) {
                $('#jml').val(data['detail'].length);
                for (let a = 0; a < data['detail'].length; a++) {                  
                    var xx = a+1;
                    var imotif      = data['detail'][a]['i_product_motif'];
                    var produk      = data['detail'][a]['i_product'];
                    var grade       = data['detail'][a]['i_product_grade'];
                    var namaproduk  = data['detail'][a]['e_product_name'];
                    var motif       = data['detail'][a]['e_product_motifname'];
                    var harga       = data['detail'][a]['v_unit_price'];
                    var qty         = data['detail'][a]['n_quantity_receive'];
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+xx+'<input type="hidden" id="baris'+xx+'" name="baris'+xx+'" value="'+xx+'"></td>';
                    cols += '<td><input class="form-control" readonly id="iproduct'+xx+'" name="iproduct'+xx+'" value="'+produk+'"></td>';
                    cols += '<td><input class="form-control" readonly id="eproductname'+xx+'" name="eproductname'+xx+'" value="'+namaproduk+'"><input type="hidden" id="iproductmotif'+xx+'" name="iproductmotif'+xx+'" value="'+imotif+'"><input type="hidden" id="iproductgrade'+xx+'" name="iproductgrade'+xx+'" value="'+grade+'"><input readonly type="hidden" id="vunitprice'+xx+'" name="vunitprice'+xx+'" value="'+harga+'"></td>';
                    cols += '<td><input readonly class="form-control" id="emotifname'+xx+'" name="emotifname'+xx+'" value="'+motif+'"></td>';
                    cols += '<td><input readonly class="form-control" style="text-align:right;" id="nicconvertion'+xx+'" name="nicconvertion'+xx+'" value="'+qty+'" onkeypress="return hanyaAngka(event);"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }
            },
            error: function () {
                swal('Data ada yang salah :)');
            }
        });
    }

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });

    $(document).ready(function () {
        $('.select2').select2();
        showCalendar('.date');
        $('#irefference').select2({
            placeholder: 'Cari No. SJR',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsjr/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term
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

    function dipales(){
        var a = $('#jml').val();
        if(($("#dicconvertion").val()!='') && ($("#irefference").val()!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if(($("#iproduct"+i).val()=='') || ($("#eproductname"+i).val()=='') || (($("#nicconvertion"+i).val()=='') || $("#nicconvertion"+i).val()=='0')){
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