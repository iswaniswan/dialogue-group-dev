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
                        <label class="col-md-6">Tanggal RRKH</label><label class="col-md-6">Hari</label>
                        <div class="col-sm-6">
                            <input type="text" required="" placeholder="Pilih Tanggal" readonly id= "drrkh" name="drrkh" class="form-control date" value="" onchange="carihari();">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" required="" placeholder="Pilih Hari" readonly id= "hari" name="hari" class="form-control" value="" onclick="carihari()">
                        </div>
                    </div>                           
                    <div class="form-group row">
                        <label class="col-md-12">Tanggal Terima Sales</label>
                        <div class="col-sm-12">
                            <input type="text" readonly id= "dreceive1" name="dreceive1" class="form-control date" value="">
                        </div>
                    </div>             
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm" disabled=""><i class="fa fa-plus"></i>&nbsp;&nbsp;Pelanggan</button>&nbsp;&nbsp;                                
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Batal</button>                               
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12">Area</label>
                        <div class="col-sm-12">
                            <select name="iarea" id="iarea" required="" class="form-control" onchange="cekarea(this.value);">
                                <option value=""></option>
                                <?php if ($area) {                                 
                                    foreach ($area as $key) { ?>
                                        <option value="<?php echo $key->i_area;?>"><?= $key->i_area." - ".$key->e_area_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Salesman</label>
                        <div class="col-sm-12">
                            <select name="isalesman" id="isalesman" class="form-control" disabled="">
                                <option value=""></option>
                            </select>
                        </div>
                    </div> 
                </div>
                <input type="hidden" name="jml" id="jml" value="0">
                <div class="col-md-12">
                    <table id="tabledata" class="display table" cellspacing="0" width="100%" hidden="true">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 4%;">No</th>
                                <th style="text-align: center; width: 5%;">Bukti</th>
                                <th style="text-align: center; width: 25%;">Pelanggan</th>
                                <!-- <th style="text-align: center; width: 10%;">Waktu</th> -->
                                <th style="text-align: center; width: 15%;">Area Kota</th>
                                <th style="text-align: center; width: 15%;">Rencana</th>
                                <th style="text-align: center; width: 5%;">Real</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center; width: 5%;">Act</th>
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
    function carihari(){      
        if(document.getElementById("drrkh").value!=''){         
            var tanggallengkap = document.getElementById("drrkh").value;
            var namahari = ("Minggu Senin Selasa Rabu Kamis Jumat Sabtu");
            namahari = namahari.split(" ");
            var namabulan = ("Januari Februari Maret April Mei Juni Juli Agustus September Oktober Nopember Desember");
            namabulan = namabulan.split(" ");
            tanggallengkap=tanggallengkap.split("-");
            tanggallengkap[1]=tanggallengkap[1]-1;
            var tgl = new Date(tanggallengkap[2], tanggallengkap[1], tanggallengkap[0], 0,0,0,0)
            var hari = tgl.getDay();
            var tanggal = tgl.getDate();
            var bulan = tgl.getMonth();
            var tahun = tgl.getFullYear();
            tanggallengkap = namahari[hari] + ", " +tanggal + " " + namabulan[bulan] + " " + tahun;
            document.getElementById("hari").value=namahari[hari];
        }
    }

    function cekarea(iarea) {
        if (iarea != '') {
            $("#isalesman").attr("disabled", false);
            $("#addrow").attr("disabled", false);
        }else{
            $("#isalesman").attr("disabled", true);
            $("#addrow").attr("disabled", true);
        }
        $('#isalesman').html('');
        $('#isalesman').val('');
        $("#tabledata").attr("hidden", true);
        $("#tabledata tr:gt(0)").remove();       
        $("#jml").val(0);
        xx = 0;
    }

    var xx = 0;
    $("#addrow").on("click", function () {
        xx++;
        if(xx<=30){
            $("#tabledata").attr("hidden", false);
            $('#jml').val(xx);
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td style="text-align: center;">'+xx+'<input type="hidden" id="baris'+xx+'" type="text" class="form-control" name="baris'+xx+'" value="'+xx+'"></td>';
            cols += '<td style="text-align: center;"><input type="checkbox" id="fkunjunganvalid'+xx+'" name="fkunjunganvalid'+xx+'"></td>';
            cols += '<td><select id="icustomer'+xx+ '" class="form-control" name="icustomer'+xx+'"></select></td>';
            /*cols += '<td><input type="text" id="waktu'+xx+'" type="text" class="form-control" name="waktu'+xx+'"></td>';*/
            cols += '<td><select id="icity'+xx+ '" class="form-control" name="icity'+xx+'"></select></td>';
            cols += '<td><select id="ikunjungantype'+xx+ '" class="form-control" name="ikunjungantype'+xx+'"><option value=""></option><?php if ($kunjungan) {foreach ($kunjungan as $key) { ?><option value="<?= $key->i_kunjungan_type;?>"><?= $key->e_kunjungan_typename;?></option><?php }; } ?></select></td>';
            cols += '<td style="text-align: center;"><input type="checkbox" id="fkunjunganrealisasi'+xx+'" name="fkunjunganrealisasi'+xx+'"></td>';
            cols += '<td><input type="text" id="eremark'+xx+'" class="form-control" name="eremark'+xx+'" ></td>';
            cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);
            $("#tabledata").append(newRow);
            $('#icustomer'+xx).select2({
                placeholder: 'Cari Pelanggan',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/getcustomer/'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var iarea   = $('#iarea').val();
                        var query   = {
                            q       : params.term,
                            iarea   : iarea
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
            $('#icity'+xx).select2({
                placeholder: 'Cari Kota',
                allowClear: true,
                ajax: {
                    url: '<?= base_url($folder.'/cform/getcity'); ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        var iarea   = $('#iarea').val();
                        var query   = {
                            q       : params.term,
                            iarea   : iarea
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
            $('#ikunjungantype'+xx).select2({
                placeholder: 'Cari Kunjungan'
            });
        }else{
            swal("Maksimal 30 item");
        }
    });

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        $('#jml').val(xx);
    });

    $(document).ready(function () {
        showCalendar('.date');
        $('#iarea').select2({
            placeholder: 'Cari Area Berdasarkan Kode / Nama'
        });

        $('#isalesman').select2({
            placeholder: 'Cari Salesman Berdasarkan Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/getsalesman/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var iarea    = $('#iarea').val();
                    var query = {
                        q: params.term,
                        iarea:iarea
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

    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
        $("#addrow").attr("disabled", true);
    });

    function dipales(a){
        if((document.getElementById("drrkh").value!='') && (document.getElementById("iarea").value!='')) {
            if(a==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=a;i++){
                    if((document.getElementById("icustomer"+i).value=='') || (document.getElementById("ikunjungantype"+i).value=='') || (document.getElementById("icity"+i).value=='')){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true
                    }
                }
            }
        }else{
            swal('Data header masih ada yang salah !!!');
            return false;
        }
    }
</script>