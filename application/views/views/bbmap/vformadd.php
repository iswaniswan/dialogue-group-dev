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
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">No OP</label>
                        <div class="col-sm-6">
                            <select name="iop" id="iop" class="form-control select2" onchange="get(this.value);">
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">No BBM-AP</label>
                        <div class="col-sm-6">
                            <input type='text' id="iap" name="iap" value="" maxlength="14">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Pemasok</label>
                        <div class="col-sm-12">
                            <input readonly id="esuppliername" name="esuppliername" value="">
                            <input id="isupplier" name="isupplier" type="hidden" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                    class="fa fa-plus" onclick="return validasi();"></i>&nbsp;&nbsp;Simpan</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="pesan"></div>
                    <div class="form-group">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <input readonly id="eareaname" name="eareaname" value="">
                            <input id="iarea" name="iarea" type="hidden" value="">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12">Tanggal BBM-AP</label>
                        <div class="col-sm-3">
                            <input readonly id="dap" name="dap" class="form-control date"  value="">
                        </div>
                        <div class="col-sm-6">
                            <input id="iapold" name="iapold" type="text" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Nilai Kotor</label>
                        <div class="col-sm-6">
                            <input readonly id="vapgross" name="vapgross" value="0">
                        </div>
                    </div>
                </div>
                <div class="panel-body table-responsive">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Motif</th>
                                    <th>Harga</th>
                                    <th>Jumlah Terima</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div id="pesan"></div>
                    <input type="hidden" name="jml" id="jml" value="0">
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

    $(document).ready(function () {
        $('#iop').select2({
        placeholder: 'Pilih OP',
        allowClear: true,
        ajax: {
          url: '<?= base_url($folder.'/cform/data_op'); ?>',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      }).on("change", function(e) {
        var kode = $('#iop').text();
     });
    });

    function get(id) {
        /*alert(iarea);*/
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        $.ajax({
            type: "post",
            data: {
                'i_op': id
            },
            url: '<?= base_url($folder.'/cform/getop'); ?>',
            dataType: "json",
            success: function (data) {
                $('#isupplier').val(data['data'][0].i_supplier);
                $('#esuppliername').val(data['data'][0].e_supplier_name);
                $('#iarea').val(data['data'][0].i_area);
                $('#eareaname').val(data['data'][0].e_area_name);
                $('#jml').val(data['jmlitem']);
               
                for (let a = 0; a <= data['jmlitem']; a++) {
                    var no = a+1;
                    var produk      = data['brgop'][a]['i_product'];
                    var namaproduk  = data['brgop'][a]['e_product_name'];
                    var imotif      = data['brgop'][a]['i_product_motif'];
                    var motif       = data['brgop'][a]['e_product_motifname'];
                    var harga       = data['brgop'][a]['v_product_mill'];
                    var igrade      = data['brgop'][a]['i_product_grade'];
                    var cols        = "";
                    var newRow = $("<tr>");
                    cols += '<td><input style="width:35px;" readonly type="text" id="baris'+a+'" name="baris'+a+'" value="'+no+'"><input type="hidden" id="motif'+a+'" name="motif'+a+'" value="'+imotif+'"><input type="hidden" id="nitemno'+a+'" name="nitemno'+a+'" value="'+no+'"><input type="hidden" id="iproductgrade'+a+'" name="iproductgrade'+a+'" value="'+igrade+'"></td>';
                    cols += '<td><input style="width:100px;" readonly type="text" id="iproduct'+a+'" name="iproduct'+a+'" value="'+produk+'"></td>';
                    cols += '<td><input style="width:268px;" readonly type="text" id="eproductname'+a+'" name="eproductname'+a+'" value="'+namaproduk+'"></td>';
                    cols += '<td><input readonly style="width:91px;"  type="text" id="emotifname'+a+'" name="emotifname'+a+'" value="'+motif+'"></td>';
                    //cols += '<td>'+harga+'</td>';
                    cols += '<td><input readonly style="text-align:right; width:100px;" type="text" id="vproductmill'+a+'" name="vproductmill'+a+'" value="'+harga+'"></td>';
                    cols += '<td><input style="text-align:right; width:82px;" type="text" id="nreceive'+a+'" name="nreceive'+a+'" onkeyup="hitungnilai(this.value)"></td>';
                    cols += '<td><input readonly style="text-align:right; width:100px;" type="text" id="vtotal'+a+'" name="vtotal'+a+'" value="0"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);
                }
              
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    function validasi(){
        var op = $('#iop').val();
        var tidakadaop = $('#tidakadaop').is(':checked');
        if(tidakadaop)
        {
            return true;
        } else {
            if(op==''){
                alert('Pilih Terlebih Dahulu OP');
                return false;
            }
        }
        
    }

    function hitungnilai(isi){
        jml=document.getElementById("jml").value;
        if (isNaN(parseFloat(isi))){
           alert("Input harus numerik");
        }else{
           vtot =0;
           for(i=0;i<jml;i++){
              vhrg=formatulang(document.getElementById("vproductmill"+i).value);
              nqty=formatulang(document.getElementById("nreceive"+i).value);
           if(document.getElementById("nreceive"+i).value=='') 
              nqty=0;
              vhrg=parseFloat(vhrg)*parseFloat(nqty);
              vtot=vtot+vhrg;
              document.getElementById("vtotal"+i).value=formatcemua(vhrg);
           }
           document.getElementById("vapgross").value=formatcemua(vtot);
        }
  }
</script>
