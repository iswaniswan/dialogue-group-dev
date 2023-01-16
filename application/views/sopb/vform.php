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
                <div class="col-md-7">
                    <div class="form-group row">
                        <label class="col-md-3">Tanggal Akhir Periode</label>
                        <label class="col-md-5">Toko</label>
                        <label class="col-md-4">SPG</label>
                        <div class="col-sm-3">
                            <input required="" readonly id= "dstockopname" name="dstockopname" class="form-control date" readonly value="<?= date('d-m-Y');?>">
                            <input type="hidden" id="istockopname" name="istockopname" value="">
                            <input type="hidden" id="periode" name="periode" value="<?= $iperiode; ?>">
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control" readonly id="ecustomername" name="ecustomername" value="<?= $ecustomername; ?>">
                            <input type="hidden" id="icustomer" name="icustomer" value="<?= $icustomer; ?>">
                            <input type="hidden" id="iarea" name="iarea" value="<?= $iarea; ?>"></td>
                        </div>
                        <div class="col-sm-4">
                            <input class="form-control" readonly id="espgname" name="espgname" value="<?= $espgname; ?>">
                            <input type="hidden" id="ispg" name="ispg" value="<?= $ispg; ?>">
                        </div>
                    </div>                 
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales();"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan
                            </button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            <button id="load" type="button" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-refresh"></i>&nbsp;&nbsp;Load Item</button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 20%;">Kode</th>
                                <th style="text-align: center; width: 10%;">Grade</th>
                                <th style="text-align: center;">Nama Barang</th>
                                <th style="text-align: center; width: 10%;">Motif</th>
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
    $("#load").click(function(event) {
        $("#tabledata").attr("hidden", false);
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'icustomer'   : '<?= $icustomer;?>'
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
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td style="text-align: center">'+xx+'<input type="hidden" id="no'+xx+'" name="no'+xx+'" value="'+xx+'"><input type="hidden" id="iproductmotif'+xx+'" name="iproductmotif'+xx+'" value="'+imotif+'"></td>';
                    cols += '<td><input class="form-control" readonly id="iproduct'+xx+'" name="iproduct'+xx+'" value="'+produk+'"></td>';
                    cols += '<td><input readonly class="form-control" id="iproductgrade'+xx+'" name="iproductgrade'+xx+'" value="'+grade+'"></td>';
                    cols += '<td><input class="form-control" readonly id="eproductname'+xx+'" name="eproductname'+xx+'" value="'+namaproduk+'"></td>';
                    cols += '<td><input readonly class="form-control" id="emotifname'+xx+'" name="emotifname'+xx+'" value="'+motif+'"></td>';
                    cols += '<td><input class="form-control" style="text-align:right;" id="nstockopname'+xx+'" name="nstockopname'+xx+'" value="0" onkeypress="return hanyaAngka(event);" onkeyup="reformat(this);"></td>';
                    newRow.append(cols);
                    $("#tabledata").append(newRow);
                }
            },
            error: function () {
                swal('Data ada yang salah :)');
            }
        });
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#load").attr("disabled", true);
    });

    $(document).ready(function () {
        showCalendar('.date');
    });

    function cekperiode() {
        var iperiode    = "<?= $iperiode; ?>";
        var dso         = $('#dstockopname').val();
        var tgl         = dso.split('-');
        var iperiodeso  = tgl[2]+''+tgl[1];
        if(iperiode>iperiodeso){
            swal("Periode aktif = "+iperiode);
            $('#dstockopname').val('');
            return false;
        }
    }

    $("#submit").click(function(event) {
        cekperiode();
        if ($('#dstockopname').val()=='') {
            swal('Tanggal SO harus Diisi!');
            return false;
        }
        var a = $('#jml').val();
        if(($("#dstockopname").val()!='') && ($("#icustomer").val()!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if(($("#iproduct"+i).val()=='') || ($("#iproductgrade"+i).val()=='') || ($("#eproductname"+i).val()=='') || $("#nstockopname"+i).val()==''){
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
    });
</script>