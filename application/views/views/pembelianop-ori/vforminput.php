<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
        font-size: 14px;
        background-color: #e1f1e4;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal', 'id' => 'cekinputan')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/index/<?=$dfrom;?>/<?=$dto;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i><?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>               
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3">Bagian Pembuat</label>
                        <label class="col-md-3">No Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-2">Batasan Pemenuhan</label>
                        <label class="col-md-2">Jenis Pembelian</label>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" required="">
                                <?php if ($bagian) {
                                    foreach ($bagian as $row):?>
                                        <option value="<?= $row->i_bagian;?>">
                                            <?= $row->e_bagian_name;?>
                                        </option>
                                    <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" name="iop" id="iop" required="" readonly="" autocomplete="off" onkeyup="gede(this);" placeholder="PP-2010-000001" maxlength="15" class="form-control input-sm" value="<?= $number;?>" aria-label="Text input with dropdown button">
                                <span class="input-group-addon">
                                    <input type="checkbox" id="ceklis" aria-label="Checkbox for following text input">
                                </span>
                            </div>
                            <span class="notekode">Format : (<?= $number;?>)</span><br>
                            <span class="notekode" hidden="true"><b> * No. Sudah Ada!</b></span>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dop" name="dop" class="form-control input-sm date" required="" readonly value="<?= date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dbp" name="dbp" class="form-control input-sm date"  required="" readonly value="<?= date("d-m-Y", strtotime('+1 month', strtotime(date('d-m-Y')))); ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="jenis" name="jenis" class="form-control input-sm" required="" readonly value="<?= $datasup->jenis_pembelian; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-3">Importance Status</label>
                        <label class="col-md-6">Keterangan</label>
                        <div class="col-sm-3">
                            <input type="hidden" id="isupplier" name="isupplier" class="form-control" required="" onkeyup="gede(this)" value="<?= $data->i_supplier;?>" readonly> 
                            <input type="text" id="esupplier" name="esupplier" class="form-control input-sm" required="" onkeyup="gede(this)" value="<?= $data->i_supplier." - ".$data->e_supplier_name;?>" readonly>
                            <input type="hidden" id="esuppliername" name="esuppliername" value="<?=$data->e_supplier_name;?>">
                            <input type="hidden" id="ntop" name="ntop" value="<?=$data->n_supplier_toplength;?>">
                            <?php if($data->i_type_pajak == 'I'){
                                $fppn = 't';
                            }else if($data->i_type_pajak == 'E'){
                                $fppn = 'f';
                            }else if($data->i_type_pajak == null){
                                $fppn = '';
                            }
                            ?>
                            <input type="hidden" id="itypepajak" name="itypepajak" value="<?=$data->i_type_pajak;?>">
                            <input type="hidden" id="fppn" name="fppn" value="<?=$fppn;?>">
                            <input type="hidden" id="ndiskon" name="ndiskon" value="<?=$data->n_diskon;?>">
                            <input type="hidden" id="fpkp" name="fpkp" value="<?=$data->f_pkp;?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="importantstatus" id="importantstatus" required="" class="form-control select2"> 
                            </select>    
                        </div>
                        <div class="col-sm-6">
                            <textarea type="text" name="eremarkh" id="eremark" class="form-control" value="" placeholder="Keterangan"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/tambah/<?= $dfrom.'/'.$dto;?>","#main")'><i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" hidden="true" id="send" class="btn btn-primary btn-rounded btn-sm"> <i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>       
                    </div>               
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Barang</h3>
    </div>
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                <thead>
                    <tr>
                        <th style="text-align:center;width:3%">No</th>
                        <th style="text-align:center;width:10%">Kode</th>
                        <th style="text-align:center;width:30%">Nama Barang</th>
                        <th style="text-align:center;width:9%">Qty</th>  
                        <th style="text-align:center;width:11%">Satuan</th>
                        <th style="text-align:center;width:10%">Harga</th>
                        <th style="text-align:center;width:10%">Total</th>
                        <th style="text-align:center;width:22%">Keterangan</th>                           
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($data2){
                        $i = 0;
                        $group = "";
                        $no = 0;
                        foreach($data2 as $row){
                            $i++;
                            $no++;
                            $v_harga_konversi = $row->v_harga_konversi==0?$row->hrgpp:$row->v_harga_konversi;
                            $total = $row->n_quantity*$v_harga_konversi;
                            if($group==""){ ?>
                                <tr class="pudding">
                                    <td colspan="8">Nomor PP : <b><?= $row->i_pp;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp;?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name;?> )
                                    </td>
                                </tr>
                            <?php } else {
                                if($group!=$row->id_pp){ ?>
                                    <tr class="pudding">
                                        <td colspan="8">Nomor PP : <b><?= $row->i_pp;?></b> &nbsp;&nbsp;-&nbsp;&nbsp; Tanggal PP : <b><?= $row->d_pp;?></b> &nbsp;&nbsp; (<b><?= $row->e_bagian_name;?> )
                                        </td>
                                    </tr>
                                    <?php $no = 1; }
                                }
                                $group = $row->id_pp
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?= $i;?>
                                        <input type="hidden" class="form-control" readonly id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                        <input type="hidden" id="ipp<?=$i;?>" name="ipp<?=$i;?>"value="<?= $row->i_pp; ?>" readonly>
                                        <input type="hidden" name="idpp<?=$i;?>" id="idpp<?=$i;?>" value="<?=$row->id_pp;?>">
                                        <input type="hidden" class="form-control" id="ibagian<?=$i;?>" name="ibagian<?=$i;?>"value="<?= $row->i_bagian; ?>" readonly> 
                                    </td> 
                                    <td>  
                                        <input type="text" class="form-control input-sm" id="imaterial<?=$i;?>" name="imaterial<?=$i;?>"value="<?= $row->i_material; ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm" id="ematerialname<?=$i;?>" name="ematerialname<?=$i;?>"value="<?= $row->e_material_name; ?>" readonly>
                                    </td>                            
                                    <td>
                                        <input type="text" class="form-control input-sm text-right" id="nquantity<?=$i;?>" name="nquantity<?=$i;?>" value="<?= $row->n_sisa; ?>" autocomplete="off" onkeyup="valstock(<?=$i;?>); hitung(this.value); angkahungkul(this);"> 
                                        <input type="hidden" class="form-control" id="npemenuhan<?=$i;?>" name="npemenuhan<?=$i;?>" value="<?= $row->n_sisa; ?>" readonly> 
                                    </td>
                                    <td>  
                                        <input type="hidden" id="isatuan<?=$i;?>" name="isatuan<?=$i;?>"value="<?= $row->i_satuan_code; ?>" readonly>
                                        <input type="text" class="form-control input-sm" id="isatuan1<?=$i;?>" name="isatuan1<?=$i;?>"value="<?= $row->e_satuan_name; ?>" readonly>
                                    </td>
                                    <td>  
                                        <input type="text" class="form-control input-sm text-right" id="vprice<?=$i;?>" name="vprice<?=$i;?>" value="<?= $row->v_harga_konversi; ?>" autocomplete="off" onkeyup="hitung(this.value); angkahungkul(this); reformat(this);" onblur="if(this.value==''){this.value='0';}" onfocus="if(this.value=='0'){this.value='';}" <?php if ($jenis=='credit') {?> readonly <?php } ;?>>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm text-right" id="vtotal<?=$i;?>" name="vtotal<?=$i;?>"value="<?= number_format($total,0); ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm" placeholder="Isi Keterangan Jika Ada!" id="eremark<?=$i;?>" name="eremark<?=$i;?>"value="">
                                    </td>                        
                                </tr> 
                                <?php 
                            }
                        }else{
                            $i=0;
                            $read = "disabled";
                            echo "<table class=\"table table-striped bottom\" style=\"width:100%;\"><tr><td colspan=\"16\" style=\"text-align:center;\">Maaf Tidak Ada PP!</td></tr></table>"; 
                        }?>      
                        <input type="hidden" name="jml" id="jml" value="<?= $i; ?>"> 
                    </tbody>                         
                </table>
            </div> 
        </div>
    </div>
</form>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $(".select2").select2();
        showCalendar('.date');
        cekharga();

        $('#cekinputan').validate({
            errorClass: "my-error-class",
            validClass: "my-valid-class"
        });
        $('#iop').mask('SS-0000-000000S');

        $('#importantstatus').select2({
            placeholder:"Pilih Importance Status",
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/importancestatus'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });

    $('#send').click(function(event) {
        statuschange('<?= $folder;?>',$('#id').val(),'2','<?= $dfrom."','".$dto;?>');
    });

    function maxi(){
        var dop     = $('#dop').val();
        var da      = dop.substr(0, 2);
        var ma      = dop.substr(3, 2);
        var ya      = dop.substr(6, 4);
        var today   = new Date(ya, ma, da);
        var year    = today.getFullYear();
        var month   = today.getMonth();
        var date    = today.getDate();
        var day     = new Date(year, month, date+30);

        mnth = ("0" + (day.getMonth())).slice(-2),
        dath = ("0" + day.getDate()).slice(-2);
        jam  = [dath, mnth, today.getFullYear()].join("-");

        $('#dbp').val(jam);
    }

    $( "#iop" ).keyup(function() {
        $.ajax({
            type: "post",
            data: {
                'kode' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/cekkode'); ?>',
            dataType: "json",
            success: function (data) {
                if (data==1) {
                    $(".notekode").attr("hidden", false);
                    $("#submit").attr("disabled", true);
                }else{
                    $(".notekode").attr("hidden", true);
                    $("#submit").attr("disabled", false);
                }
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    $('#ceklis').click(function(event) {
        if($('#ceklis').is(':checked')){
            $("#iop").attr("readonly", false);
        }else{
            $("#iop").attr("readonly", true);
            $("#iop").val("<?= $number;?>");
        }
    });

    $( "#dop" ).change(function() {
        maxi();
        $.ajax({
            type: "post",
            data: {
                'tgl' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/number'); ?>',
            dataType: "json",
            success: function (data) {
                $('#iop').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    function cekharga(){
        var i = $('#jml').val();
        var harga = $('#vprice'+i).val();
        var jenis = '<?= $jenis; ?>';
        if(harga == '' && jenis=='credit'){
            swal("Harga masih kosong, Input Harga terlebih dahulu di Master Harga Per Supplier");
            $("#submit").attr("disabled", true);
            $("#send").attr("disabled", true);
        }
    }

    $("form").submit(function (event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#addrow").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("hidden", false);
    });

    function hitung(){
        var jml = $('#jml').val();
        for(var i=1; i<=jml; i++){
            var qty = $('#nquantity'+i).val()==''?$('#nquantity'+i).val(0):qty;
            qty = $('#nquantity'+i).val() || 0;

            var price = formatulang($('#vprice'+i).val())==''?$('#vprice'+i).val(0):price;
            price   = formatulang($('#vprice'+i).val()) || 0; 

            total = (parseFloat(qty)*parseFloat(price));
            $('#vtotal'+i).val(formatcemua(total));
        }
    }

    function valstock(id){
        var stock         = $('#nquantity'+id).val();
        var noutstanding  = $('#npemenuhan'+id).val();
        if (stock == ''){
            stock = 0;
        }
        if(parseFloat(noutstanding)<parseFloat(stock)){
            swal ("Jumlah quantity melebihi stock");
            $('#nquantity'+id).val(noutstanding);
        }
    }

    /*function max_tgl(val) {
        $('#dop').datepicker('destroy');
        $('#dop').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dpp').value,
        });
    }*/

    $('#dop').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dbp').value,
    });

    /*function max_tglkirim(val) {
        $('#ddeliv').datepicker('destroy');
        $('#ddeliv').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            daysOfWeekDisabled: [0],
            startDate: document.getElementById('dop').value,
        });
    }
    $('#ddeliv').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: "dd-mm-yyyy",
        todayBtn: "linked",
        daysOfWeekDisabled: [0],
        startDate: document.getElementById('dop').value,
    });*/

    $( "#submit" ).click(function(event) {
        var valid = $("#cekinputan").valid();
        if (valid) {
            for (var i = 1; i <= $('#jml').val(); i++) {
                if ($('#vprice'+i).val()=='' || $('#vprice'+i).val()==null || $('#vprice'+i).val()==0) {
                    swal('Maaf :(','Harga tidak boleh kosong! Silahkan input manual atau input di master harga terlebih dahulu!','error');
                    return false;
                }   
            }
            /*var s=0;
            var i = $('#jml').val();
            var harga = $('#vprice'+i).val();
            var textinputs = document.querySelectorAll('input[type=input]'); 
            var empty = [].filter.call( textinputs, function( el ) {
                return !el.checked
            });

            if(document.getElementById('dop').value==''){
                swal("Tanggal OP Masih Kosong!");
                return false;
            }else if(document.getElementById('importantstatus').value=='' || document.getElementById('importantstatus').value== null){
                swal("Importance Status Masih Kosong");
                return false;
            }else if(document.getElementById('vprice').value==''){
                swal("Harga masih kosong, Input Harga terlebih dahulu di Master Harga Per Supplier");
                return false;
            }else{
                return true
            }*/
        }else{            
            return false;
        }
    });

    /*function validasi(){
        var s=0;
        var i = $('#jml').val();
        var harga = $('#vprice'+i).val();
        var textinputs = document.querySelectorAll('input[type=input]'); 
        var empty = [].filter.call( textinputs, function( el ) {
            return !el.checked
        });

        if(document.getElementById('dop').value==''){
            swal("Tanggal OP Masih Kosong!");
            return false;
        }else if(document.getElementById('importantstatus').value=='' || document.getElementById('importantstatus').value== null){
            swal("Importance Status Masih Kosong");
            return false;
        }else if(document.getElementById('vprice').value==''){
            swal("Harga masih kosong, Input Harga terlebih dahulu di Master Harga Per Supplier");
            return false;
        }else{
            return true
        }
    }*/    
</script>