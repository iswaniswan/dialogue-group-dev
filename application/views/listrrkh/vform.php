<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-edit"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/view/<?= $dfrom."/".$dto."/".$iarea;?>','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-rotate-left"></i>&nbsp;Kembali</a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-5">
                    <div class="form-group row">
                        <label class="col-md-4">Tanggal RRKH</label><label class="col-md-4">Hari</label><label class="col-md-4">Tanggal Terima Sales</label>
                        <div class="col-sm-4">
                            <input type="text" required="" placeholder="Pilih Tanggal" readonly id= "drrkh" name="drrkh" class="form-control date" value="<?= $drrkh;?>" onchange="carihari();">
                            <input type="hidden" required="" placeholder="Pilih Tanggal" readonly id= "drrkhasal" name="drrkhasal" class="form-control date" value="<?= $isi->d_rrkh;?>">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" required="" placeholder="Pilih Hari" readonly id= "hari" name="hari" class="form-control" value="<?= $hari;?>" onclick="carihari()">
                        </div>
                        <?php 
                        if(!empty($isi->d_receive1) || $isi->d_receive1!=''){
                            $dreceive = date('d-m-Y', strtotime($isi->d_receive1));
                        }else{
                            $dreceive = '';
                        }
                        ?>
                        <div class="col-sm-4">
                            <input type="text" readonly id= "dreceive1" name="dreceive1" class="form-control date" value="<?= $dreceive;?>">
                        </div>
                    </div>  
                    <div class="form-group row">
                        <label class="col-md-6">Area</label><label class="col-md-6">Salesman</label>
                        <div class="col-sm-6">
                            <select id="iarea" required="" class="form-control" onchange="cekarea(this.value);" disabled="">
                                <option value=""></option>
                                <?php if ($area) {                                 
                                    foreach ($area as $key) { ?>
                                        <option value="<?php echo $key->i_area;?>" <?php if ($isi->i_area==$key->i_area) {
                                            echo "selected";
                                        }?>><?= $key->i_area." - ".$key->e_area_name;?></option>
                                    <?php }; 
                                } ?>
                            </select>
                            <input type="hidden" name="iarea" id="xarea" value="<?= $isi->i_area;?>">
                        </div>
                        <div class="col-sm-6">
                            <select id="isalesman" class="form-control" disabled="">
                                <option value="<?= $isi->i_salesman;?>"><?= $isi->e_salesman_name;?></option>
                            </select>
                            <input type="hidden" name="isalesman" id="xsalesman" value="<?= $isi->i_salesman;?>">
                        </div>
                    </div>        
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-12">
                            <?php if($isi->f_rrkh_cancel=='f' && $isi->i_approve==''){ ?>
                                <button type="button" id="approve" class="btn btn-primary btn-rounded btn-sm"> <i class="fa fa-check-square-o"></i>&nbsp;&nbsp;Approve</button>&nbsp;&nbsp;
                            <?php } ?>
                            <?php $user = $this->session->userdata('username'); if($isi->f_rrkh_cancel=='f' && $isi->d_receive1 == '' && $isi->i_approve !='' && ( $user == 'admin' || $user == 'spvpusat' || $user == 'sales3' || $user == 'sales4' || $user == 'sales5' )){ ?>
                                <button type="button" id="bapprove" class="btn btn-danger btn-rounded btn-sm"> <i class="fa fa-times"></i>&nbsp;&nbsp;Batal Approve</button>&nbsp;&nbsp;
                            <?php } ?>
                            <?php if($isi->f_rrkh_cancel=='f' && $isi->i_approve!=''){ ?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <?php } ?>
                            <?php if($isi->f_rrkh_cancel=='f' && $isi->i_approve==''){ ?>
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Pelanggan</button>&nbsp;&nbsp;              
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $dfrom."/".$dto."/".$iarea;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>                               
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-lg-3 col-sm-6 col-xs-12">
                        <!-- Start an Alert -->
                        <div id="alerttopright" class="myadmin-alert myadmin-alert-img alert-success myadmin-alert-top-right"> <img src="<?= base_url();?>assets/images/x.png" class="img" alt="img"><a href="#" class="closed">&times;</a>
                            <strong><i class="fa fa-info-circle"></i> Informasi!</strong><br>
                            <!-- <h4>You have a Message!</h4> -->
                            <b>Apabila tombol simpan tidak ada, berarti harus di Approve terlebih dahulu ! <br>Sebelum klik approve, pastikan daftar pelanggannya sudah benar !</b>
                        </div>
                        <!-- <div class="myadmin-alert myadmin-alert-icon myadmin-alert-click alert-info myadmin-alert-bottom alertbottom"> <strong><i class="fa fa-info-circle"></i> Informasi!</strong><br>
                            <b>Apabila tidak ada tombol simpan harus di Approve dulu ! Sebelum klik approve pastikan daftar pelanggannya sudah benar !</b> <a href="#" class="closed">&times;</a> </div> -->
                        </div>
                    </div>
                    <div class="col-md-12">
                        <table id="tabledata" class="display table" cellspacing="0" width="100%">
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
                                <?php if ($detail) {
                                    $xx = 0;
                                    foreach ($detail as $row) {
                                        $xx++;
                                        if($row->f_kunjungan_valid=='t'){
                                            $fkunjunganvalid='on';
                                            $cekkunjungan = 'checked';
                                        }else{
                                            $fkunjunganvalid='';
                                            $cekkunjungan = '';
                                        }
                                        if($row->f_kunjungan_realisasi=='t'){
                                            $fkunjunganrealisasi='on';
                                            $cekreal = 'checked';
                                        }else{
                                            $fkunjunganrealisasi='';
                                            $cekreal = '';
                                        }
                                        ?>
                                        <tr>
                                            <td style="text-align: center;">
                                                <?= $xx;?>
                                                <input type="hidden" id="baris<?= $xx;?>" type="text" class="form-control" name="baris<?= $xx;?>" value="<?= $xx;?>">
                                            </td>
                                            <td style="text-align: center;"><input type="checkbox" id="fkunjunganvalid<?= $xx;?>" name="fkunjunganvalid<?= $xx;?>" <?= $cekkunjungan;?>></td>
                                            <td>
                                                <select id="icustomer<?= $xx;?>" class="form-control select2" name="icustomer<?= $xx;?>">
                                                    <option value="<?= $row->i_customer;?>"><?= $row->i_customer." - ".$row->e_customer_name;?></option>
                                                </select>
                                            </td>
                                            <td>
                                                <select id="icity<?= $xx;?>" class="form-control select2" name="icity<?= $xx;?>">
                                                    <option value="<?= $row->i_city;?>"><?= $row->e_city_name;?></option>
                                                </select>
                                            </td>
                                            <td>
                                                <select id="ikunjungantype<?= $xx;?>" class="form-control select2" name="ikunjungantype<?= $xx;?>">
                                                    <option value=""></option>
                                                    <?php if ($kunjungan) {
                                                        foreach ($kunjungan as $key) { ?>
                                                            <option value="<?= $key->i_kunjungan_type;?>" <?php if ($row->i_kunjungan_type==$key->i_kunjungan_type) {
                                                                echo "selected";
                                                            }?>><?= $key->e_kunjungan_typename;?></option>
                                                        <?php }; 
                                                    } ?>
                                                </select>
                                            </td>
                                            <td style="text-align: center;"><input type="checkbox" id="fkunjunganrealisasi<?= $xx;?>" name="fkunjunganrealisasi<?= $xx;?>" <?= $cekkunjungan;?>></td>
                                            <td><input type="text" id="eremark<?= $xx;?>" class="form-control" name="eremark<?= $xx;?>" value="<?= $row->e_remark;?>"></td>
                                            <?php if($isi->f_rrkh_cancel=='f' && $isi->i_approve==''){ ?>
                                                <td><button type="button" onclick="hapusitem('<?= $drrkh."','".$isi->i_salesman."','".$isi->i_area."','".$row->i_customer;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    <input type="hidden" name="jml" id="jml" value="<?= $xx;?>">
                                <?php } ?>
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
    $( "#approve" ).click(function() {
        swal({   
            title: "Approve RRKH Ini ?",   
            text: "Anda harus yakin!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, Approve!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'isalesman' : $('#xsalesman').val(),
                        'drrkh' : $('#drrkh').val(),
                        'iarea' : $('#xarea').val()
                    },
                    url: '<?= base_url($folder.'/cform/approve'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Approve!", "Data berhasil diapprove :)", "success");
                        show('<?= $folder;?>/cform/view/<?= $dfrom.'/'.$dto.'/'.$iarea;?>','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal diapprove :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan pengapprovan :)", "error");
            } 
        });
    });

    $( "#bapprove" ).click(function() {
        swal({   
            title: "Batal Approve RRKH Ini ?",   
            text: "Anda harus yakin!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya!",   
            cancelButtonText: "Tidak!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'isalesman' : $('#xsalesman').val(),
                        'drrkh' : $('#drrkh').val(),
                        'iarea' : $('#xarea').val()
                    },
                    url: '<?= base_url($folder.'/cform/batalapprove'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dibatalkan!", "Data berhasil dibatalkan :)", "success");
                        show('<?= $folder;?>/cform/view/<?= $dfrom.'/'.$dto.'/'.$iarea;?>','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dibatalkan :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan pembatalan :)", "error");
            } 
        });
    });

    function hapusitem(drrkh,isalesman,iarea,icustomer) {
        swal({   
            title: "Apakah anda yakin ?",   
            text: "Anda tidak akan dapat memulihkan data ini!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Ya, hapus!",   
            cancelButtonText: "Tidak, batalkan!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) { 
                $.ajax({
                    type: "post",
                    data: {
                        'drrkh' : drrkh,
                        'isalesman' : isalesman,
                        'iarea' : iarea,
                        'icustomer' : icustomer,
                    },
                    url: '<?= base_url($folder.'/cform/deleteitem'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/'+drrkh+'/'+isalesman+'/'+iarea+'/<?= $dfrom.'/'.$dto;?>','#main');     
                    },
                    error: function () {
                        swal("Maaf", "Data gagal dihapus :(", "error");
                    }
                });
            } else {     
                swal("Dibatalkan", "Anda membatalkan penghapusan :)", "error");
            } 
        });
    }

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

    var xx = $('#jml').val();
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
            cols += '<td></td>';
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
        $("#alerttopright").fadeToggle(350);
        $(".myadmin-alert .closed").click(function(event) {
            $(this).parents(".myadmin-alert").fadeToggle(350);
            return false;
        });
        /*$(".alertbottom").fadeToggle(350);
        $(".alertbottom2").fadeToggle(350);*/

        showCalendar('.date');
        $('.select2').select2();
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