<link href="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.css" rel="stylesheet" />
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
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-3">Tanggal</label>
                        <label class="col-md-6">Bagian Pembuat</label>
                        <div class="col-sm-3">
                            <input type="text" readonly class="form-control input-sm" name="idocument" id="idocument" value="<?= $format ?>">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control input-sm date" name="ddocument" id="ddocument" value="<?php echo date("d-m-Y"); ?>">
                        </div>
                        <div class="col-sm-3">
                            <select name="ibagian" id="ibagian" class="form-control select2" >
                                <?php if ($bagian) {
                                foreach ($bagian as $row):?>
                                    <option value="<?= $row->i_bagian;?>">
                                        <?= $row->e_bagian_name;?>
                                    </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Periode Referensi</label>
                        <label class="col-md-9">Keterangan</label>
                        <div class="col-sm-3">
                            <select name="ireff" id="ireff" class="form-control select2" onchange="getdataitem(this.value);" >
                                <option></option>
                                <?php if ($periode) {
                                foreach ($periode as $row):?>
                                    <option value="<?= $row->i_periode;?>">
                                        <?= $row->i_periode;?>
                                    </option>
                                <?php endforeach; 
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <textarea id="keterangan" name="keterangan" class="form-control input-sm" placeholder="Isi keterangan jika ada!"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder;?>/cform','#main')"> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;
                            <button type="button" onclick="tambah($('#jml').val());" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="white-box" id="detail">
    <div class="col-sm-5">
        <h3 class="box-title m-b-0">Detail Material</h3>
    </div>
    <div class="col-sm-12">
            <div class="table-responsive" style="width:1200px; ">
                <table id="tabledatax" class="table color-table success-table table-bordered class" cellpadding="8" cellspacing="1" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center" >No</th>
                            <th class="text-center" width="10%">Tanggal Pengerjaan</th>
                            <th class="text-center" width="20%">Nama Barang</th>
                            <!-- <th class="text-center" width="5%">Kategori Barang</th> -->
                        <!-- <th class="text-center" width="15%">Konversi ke Set</th>-->
                            <th class="text-center" >Progress</th>
                            <th class="text-center" >FC Cutting</th>
                            <!-- <th class="text-center" >FC Produksi</th>
                            <th class="text-center" >Kondisi Stock Persiapan Cutting</th> -->
                            <th class="text-center" >Keterangan</th>
        
                            <th class="text-center" >Act</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
</form>
<script src="<?= base_url();?>assets/plugins/bower_components/switchery/dist/switchery.min.js"></script>
<script>
    $(document).ready(function () {
        
        $('#ibagian').select2({
            // placeholder: 'Cari Bagian',
            // allowClear: true,
            // ajax: {
            //     url: '<?= base_url($folder.'/cform/getbagian'); ?>',
            //     dataType: 'json',
            //     delay: 250,          
            //     processResults: function (data) {
            //         return {
            //             results: data
            //         };
            //     },
            //     cache: true
            // }
        });

        $('#ireff').select2({});
    })
    
        $("#ddocument").datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $("#periode").datepicker({
            autoclose: true,
            todayHighlight: true,
            minViewMode: "months",
            format: "yyyymm",
            todayBtn: "linked",
        });

        function getdataitem(ireff) {
            
        var idreff = $('#ireff').val();

        var cek =  $('#jml').val();
        if(cek > 0){
        $("#tabledatax tbody").remove();
        }
        // var ipengirim = $('#ipengirim').val();
        if (idreff) {
            $.ajax({
                type: "post",
                data: {
                    'idreff': idreff,
                },
                url: '<?= base_url($folder . '/cform/getdataitem'); ?>',
                dataType: "json",
                success: function(data) {

                    $('#jml').val(data['jmlitem']);
                    console.log(data);

                    i = 0;
                    for (let a = 0; a < data['jmlitem']; a++) {
                        i++;
                        //var no = a+1;
                        //count=$('#tabledatax tr').length;

                        var no     = $('#tabledatax tr').length;
                        var newRow = $("<tr>");
                        var cols   = "";
                        cols += '<td class="text-center"><spanx id="snum'+ a +'">'+ no +'</spanx></td>';
                        cols += `<td><input type="text" id="tanggal${a}" name="tanggal${a}" class="form-control input-sm date" placeholder="isi tanggal" value="<?php echo date("d-m-Y"); ?>"></td>`;
                        cols += '<td><select data-nourut="'+ a +'" id="ibarang'+ a +'" class="form-control input-sm id" name="ibarang'+ a +'" ><option value="' + data['dataitem'][a]['id_product_wip'] + '">[' + data['dataitem'][a]['i_product_wip'] + '] - ' + data['dataitem'][a]['e_product_wipname'] + ' - [' + data['dataitem'][a]['e_color_name'] + ']</option></select></td>';
                        cols += '<td><input type="text" id="progress'+ a +'" name="progress'+ a +'" value="' + data['dataitem'][a]['progress'] + '"></td>';
                        cols += '<td><input type="text" id="nfccutting'+ a +'" name="nfccutting'+ a +'" size="2" value="' + data['dataitem'][a]['n_fc_cutting'] + '"></td>';
                        // cols += '<td><input type="text" id="nfcproduksi'+ a +'" name="nfcproduksi'+ a +'" size="2" value="' + data['dataitem'][a]['n_fc_perhitungan'] + '"></td>';
                        // cols += '<td><input type="text" id="nkondisi'+ a +'" name="nkondisi'+ a +'" size="2" value="' + data['dataitem'][a]['n_kondisi_stock'] + '"></td>';
                        cols += '<td><input type="text" id="eremark'+ a +'" name="eremark'+ a +'" ></td>';
                        cols += `<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;

                        newRow.append(cols);
                        $("#tabledatax").append(newRow);

                        $("#tanggal"+a).datepicker({
                            autoclose: true,
                            todayHighlight: true,
                            format: "dd-mm-yyyy",
                            todayBtn: "linked",
                        });

                        $('#ibarang'+ a).select2({
                            placeholder: 'Cari Kode / Nama Produk',
                            allowClear: true,
                            width: "100%",
                            type: "POST",
                            ajax: {
                                url: '<?= base_url($folder.'/cform/productwip/'); ?>',
                                dataType: 'json',
                                delay: 250,
                                processResults: function (data) {
                                    return {
                                        results: data
                                    };
                                },
                                cache: true
                            }
                        }).change(function(){
                            var data = $(this).select2('data');
                            data = data[0];
                            // document.getElementById('ikategori'+i).value = data.ikelompok;
                            // document.getElementById('kategori'+i).value = data.kelompok;
                            document.getElementById('progress'+i).value = data.progress;
                            document.getElementById('nfccutting'+i).value = data.cutting;
                            document.getElementById('nfcproduksi'+i).value = data.perhitungan;
                            document.getElementById('nkondisi'+i).value = data.kondisi;
                            document.getElementById('npersiapan'+i).value = data.persiapan;
                        });
                    }

                    function formatSelection(val) {
                        return val.name;
                    }

                    $("#tabledatax").on("click", ".ibtnDel", function(event) {
                        $(this).closest("tr").remove();
                    });
                },
                error: function() {
                    alert('Error :)');
                }
            });
        }
    }

    

    var i = $("#jml").val();/*
    $("#addrow").on("click", function () {
        i++; */
    /* function tambah(jml) {
        let i = parseInt(jml)+1;
        $("#jml").val(i);

        var no     = $('#tabledatax tr').length;
        var newRow = $("<tr>");
        var cols   = "";
        cols += `<td class="text-center"><spanx id="snum${i}">${no}</spanx></td>`;
        cols += `<td><input type="text" id="tanggal${i}" name="tanggal${i}" class="form-control input-sm date" value="<?php echo date("d-m-Y"); ?>" placeholder="<?= date('d-m-Y'); ?>"></td>`;
        cols += `<td><select data-nourut="${i}" id="ibarang${i}" class="form-control input-sm id" name="ibarang${i}" ></select></td>`;
        cols += `<td><input type="text" id="progress${i}" name="progress${i}" ></td>`;
        cols += `<td><input type="text" id="nfccutting${i}" name="nfccutting${i}" size="2" ></td>`;
        cols += `<td><input type="text" id="nfcproduksi${i}" name="nfcproduksi${i}" size="2" ></td>`;
        cols += `<td><input type="text" id="nkondisi${i}" name="nkondisi${i}" size="2" ></td>`;
        cols += `<td><input type="text" id="npersiapan${i}" name="npersiapan${i}" size="2" ></td>`;
        cols += `<td><input type="text" id="eremark${i}" name="eremark${i}"></td>`;
        cols += `<td><button type="button" title="Delete" class="ibtnDel btn btn-circle btn-danger"><i class="ti-close"></i></button></td>`;
        newRow.append(cols);
        $("#tabledatax").append(newRow);

        $("#tanggal"+i).datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd-mm-yyyy",
            todayBtn: "linked",
        });

        $('#ibarang'+ i).select2({
            placeholder: 'Cari Kode / Nama Produk',
            allowClear: true,
            width: "100%",
            type: "POST",
            ajax: {
                url: '<?= base_url($folder.'/cform/productwip/'); ?>',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).change(function(){
            var data = $(this).select2('data');
            data = data[0];
            // document.getElementById('ikategori'+i).value = data.ikelompok;
            // document.getElementById('kategori'+i).value = data.kelompok;
            document.getElementById('progress'+i).value = data.progress;
            document.getElementById('nfccutting'+i).value = data.cutting;
            document.getElementById('nfcproduksi'+i).value = data.perhitungan;
            document.getElementById('nkondisi'+i).value = data.kondisi;
            document.getElementById('npersiapan'+i).value = data.persiapan;
        });
        
    } */

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

    $( "#submit" ).click(function(event) {
        ada = false;
        if ($('#jml').val()==0) {
            swal('Isi item minimal 1!');
            return false;
        }else{
            $("#tabledatax tbody tr").each(function() {
                $(this).find("td select .id").each(function() {
                    if ($(this).val()=='' || $(this).val()==null) {
                        swal('Kode barang tidak boleh kosong!');
                        ada = true;
                    }
                });
                $(this).find("td .inputitem").each(function() {
                    if ($(this).val()=='' || $(this).val()==null || $(this).val()==0) {
                        swal('Jml harus lebih besar dari 0 !');
                        ada = true;
                    }
                });
            });
            if (!ada) {
                return true;
            }else{
                return false;
            }
        }
    }) 

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });
</script>