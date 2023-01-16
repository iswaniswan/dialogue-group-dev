<style type="text/css">
    .pudding{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>
<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Supplier</label>
                        <label class="col-md-9">PPN</label>
                        <div class="col-sm-3">
                            <select name="isupplier" id="isupplier" class="form-control select2" required="" >
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm" name="ppn" id="ppn" >
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button"  id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                            <button type="button" id="send" disabled="true" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o fa-lg mr-2"></i>Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">

<div class="col-sm-12">
    <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Kategori</label>
                        <label class="col-md-9">Sub Kategori</label>
                        
                        <div class="col-sm-3">
                            <select name="kategori" id="kategori" class="form-control select2">
                                <option value="all">Semua Kategori</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="subkategori" id="subkategori" class="form-control select2">
                                <option value="all">Semua Sub Kategori</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br><br>
        <h3 class="box-title m-b-0">Detail Material</h3>
        <div class="col-sm-12">
            <div style="height:auto; overflow:auto; max-height: 400px;">
                <div class="table-responsive" style="width:100%; ">
                    <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="95%">
                        <thead>
                            <tr>
                                <th style="text-align:center;">No</th>                          
                                <th style="text-align:center;">Barang</th>
                                <th style="text-align:center;">Satuan dari Supplier</th>                           
                                <th style="text-align:center;">Harga Exclude</th>                         
                                <th style="text-align:center;">Minimal Order</th>
                                <th style="text-align:center; width:10%">Tgl Berlaku</th>
                                <th style="text-align:center;">Kode Material Supplier</th>
                                <th style="text-align:center; width:5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
</form>
<script src="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.mask.min.js"></script>
<script src="<?= base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {

        $('#isupplier').select2({
            placeholder: 'Pilih Supplier',
            allowClear: true,
            ajax: {
            url: '<?= base_url($folder.'/cform/supplier'); ?>',
            dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q : params.term,
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
        }).change(function(){
            var ppn = '';
            var data = $(this).select2('data');
            data = data[0];
            if(data.ppn == 'I'){
                ppn = 'Include';
            }
            else if(data.ppn == 'E'){
                ppn = 'Exclude';
            }
            document.getElementById('ppn').value = ppn;
            $("#tabledatax tbody").remove();
        });

        $('#kategori').select2({
            placeholder: 'Pilih Kategori',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/kategori'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        isupplier : $('#isupplier').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        }).change(function(event) {
            $('#ijenis').val('');
            $('#ijenis').html('');
        });

        $('#subkategori').select2({
            placeholder: 'Pilih Jenis',
            width: '100%',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/subkategori'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        ikategori : $('#kategori').val(),
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data,
                    };
                },
                cache: false
            }
        });
    });
     
      /**
     * Tambah Item
     */

    var isubkategori = "";
    var ikategori = "";
    var isupplier = "";

        $("#subkategori").change(function(){
            isubkategori = $('#subkategori').val();
        });

        $("#kategori").change(function(){
            ikategori = $('#kategori').val();
        });
        
        $("#isupplier").change(function(){
            isupplier = $('#isupplier').val();
        }); 

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {
        var no     = $('#tabledatax tbody tr').length+1;
        counter++;
        counterx++;
        $("#tabledatax").attr("hidden", false);
        var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledatax tr').length;
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";

        cols += '<td style="text-align: center;">'+ no +'<input type="hidden" class="form-control input-sm" readonly id="baris'+ counter +'" name="baris'+ counter +'" value="'+ counter +'"></td>';
        cols += '<td size="6"><select id="namabrg'+ counter +'" class="form-control input-sm select2" name="namabrg'+ counter +'" ></select></td>';
        cols += '<td size="12"><select class="form-control input-sm" name="isatuansupplier'+ counter +'" id="isatuansupplier'+ counter +'" ></select></td>';
        cols += '<td class="col-sm-1"><input style="width:200px;"  type="text" id="hargakonversi'+ counter +'" class="form-control input-sm" name="hargakonversi'+ counter +'" onkeyup="angkahungkul(this);reformat(this)" onblur=\'if(this.value==""){this.value="0";}\' onfocus=\'if(this.value=="0"){this.value="";}\' placeholder="0" ></td>';

        cols += '<td class="col-sm-1"><input style="width:150px;"  type="text" id="norder'+ counter +'" class="form-control input-sm" name="norder'+ counter +'" value="" placeholder="0" ></td>';
        cols += '<td size="1"><input style="width:150px;"  type="text" id="dberlaku'+ counter +'" class="form-control input-sm date" name="dberlaku'+ counter +'"value="" ></td>';
        cols += '<td class="col-sm-1"><input  type="text" id="imaterialsupplier'+ counter +'" class="form-control input-sm" name="imaterialsupplier'+ counter +'" value="" placeholder="kode material dari supplier" ></td>';
        cols += '<td class="text-center"><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>';
        newRow.append(cols);
        $("#tabledatax").append(newRow);
        $("#dberlaku"+ counter).datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $('#namabrg'+ counter).select2({
            placeholder: 'Cari Material',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: "<?php echo site_url($folder.'/Cform/getmaterial/');?>",
                data: function (params){
                    var query   = {
                        q            : params.term,
                        isubkategori : $('#subkategori').val(),
                        ikategori    : $('#kategori').val(),
                        isupplier    : $('#isupplier').val(),
                        }
                        return query;
                    },
                dataType: 'json',
                processResults: function (data) {
                return {
                            results: data
                        };
                },
                cache: false

            }
        }).change(function(){
            var data = $(this).select2('data');
            data = data[0];
            var o = new Option(data.satuanname, data.satuancode);
            /// jquerify the DOM object 'o' so we can use the html method
            $(o).html(data.satuanname);
            $("#isatuansupplier" + no).append(o);
        });

        $('#isatuansupplier'+ counter).select2({
            placeholder: 'Cari Satuan',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/satuan/'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        }).change(function(){
            // var data = $(this).select2('data');
            // data = data[0];
            // document.getElementById('ikategori'+i).value = data.ikelompok;
            // document.getElementById('kategori'+i).value = data.kelompok;
        });

        // $('#hargakonversi'+ no).mask('#.##0', {reverse: true});
    });  

    $("#tabledatax").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        /* alert(i); */
        /* $('#jml').val(i); */
        var obj = $('#tabledatax tr:visible').find('spanx');
        $.each( obj, function( key, value ) {
            id = value.id;
            $('#'+id).html(key+1);
        });
    });

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#send").attr("disabled", false);
        $("#addrow").attr("disabled", true);
    });
    $('#send').click(function(event) {
        statuschangearray('<?= $folder;?>',$('#id').val(),'2','','');
        $("#send").attr("disabled", true);
    });

</script>