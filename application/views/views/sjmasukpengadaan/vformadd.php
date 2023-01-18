<?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Penerima</label>
                        <label class="col-md-3">Nomor Dokumen</label>
                        <label class="col-md-2">Tanggal Dokumen</label>
                        <label class="col-md-4 form-control-label" for="pengirim">Pengirim</label>
                        <div class="col-sm-3">
                            <select name="idepartemen" id="idepartemen" class="form-control">
                                <?php if ($gudang) {
                                    foreach ($gudang->result() as $key) { ?>
                                        <option value="<?= trim($key->i_departement);?>"<?php if ($key->i_departement==$this->session->userdata('i_departement')) {
                                            echo "selected";
                                        }?>><?= $key->e_departement_name;?></option> 
                                    <?php }
                                } ?> 
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="id" name="id" class="form-control" value="<?= $no; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="tgl" name="tgl" class="form-control date" value="<?php echo date("d-m-Y"); ?>" readonly>
                        </div>
                        <div class="col-sm-4 has-danger">
                            <input type="text" id="pengirim" name="pengirim" class="form-control form-control-danger" placeholder="Pengirim Harus Disi!">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                            <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Simpan</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                            <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;&nbsp;
                            <button type="button" hidden="true" id="send" onclick="changestatus('<?= $folder;?>',$('#kode').val(),'2');" class="btn btn-primary btn-rounded btn-sm"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="jml" id="jml" value ="0">
<div class="white-box" id="detail" hidden="true">
    <h3 class="box-title m-b-0">Detail Barang</h3>
    <div class="table-responsive">
        <table id="tabledata" class="table color-table success-table table-bordered class tablex" cellpadding="8" cellspacing="1" width="100%" hidden="true">
            <thead>
                <tr>
                    <th class="text-center" style="width: 3%;">No</th>
                    <th class="text-center" style="width: 12%;">Kode</th>
                    <th class="text-center" style="width: 35%;">Nama Barang WIP</th>
                    <th class="text-center" style="width: 12%;">Warna</th>
                    <th class="text-center" style="width: 10%;">Qty</th>
                    <th class="text-center">Keterangan</th>
                    <th class="text-center" style="width: 3%;">Act</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
</form>
<script>
    $(document).ready(function () {
        $('.select2').select2();
        /*Tidak boleh lebih dari hari ini, dan maksimal mundur 1830 hari (5 tahun)*/
        showCalendar('.date',1830,0);
    });

    $( "#tgl" ).change(function() {
        $.ajax({
            type: "post",
            data: {
                'tgl' : $(this).val(),
            },
            url: '<?= base_url($folder.'/cform/getid'); ?>',
            dataType: "json",
            success: function (data) {
                $('#id').val(data);
            },
            error: function () {
                swal('Error :)');
            }
        });
    });

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {
        counter++;
        counterx++;
        $("#tabledata").attr("hidden", false);
        $("#detail").attr("hidden", false);
        var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledata tr').length;
        if ((iproduct==''||iproduct==null)&&(count>1)) {
            swal('Isi dulu yang masih kosong!!');
            counter = counter-1;
            counterx = counterx-1;
            return false;
        }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="text-center"><spanx id="snum'+counter+'">'+count+'</spanx></td>';
        cols += '<td><input type="text" readonly id="xproduct'+ counter + '" class="form-control" name="xproduct' + counter + '"><input type="hidden" readonly id="eproductname'+ counter + '" class="form-control" name="eproductname' + counter + '"></td>';
        cols += '<td><select type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="hidden" id="icolor'+ counter + '" class="form-control" name="icolor'+ counter + '"><input type="text" readonly id="ecolor'+ counter + '" class="form-control" name="ecolor'+ counter + '"></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control text-right" name="nquantity'+ counter + '" placeholder="0" value="" onkeypress="return hanyaAngka(event);"></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '"></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="ti-trash"></i></button></td>';

        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#iproduct'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/dataproduct'); ?>',
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

    function getproduct(id){
        ada = false;
        var x = $('#jml').val();
        var iproduct = $('#iproduct'+id).val();
        $.ajax({
            type: "post",
            data: {
                'iproduct'  : iproduct
            },
            url: '<?= base_url($folder.'/cform/getproduct'); ?>',
            dataType: "json",
            success: function (data) {
                for(i=1;i<=x;i++){
                    if((data[0].id == $('#iproduct'+i).val()) && (data[0].i_color == $('#icolor'+i).val()) && (i!=x)){
                        swal ("Kode Barang : "+data[0].id+" Warna : "+data[0].e_color_name+" sudah ada !!!!!");            
                        ada=true;
                        $('#iproduct'+id).html('');
                        $('#iproduct'+id).val('');
                        $('#xproduct'+id).val(''); 
                        $('#xproduct'+id).val('');
                        $('#icolor'+id).val('');
                        $('#ecolor'+id).val('');
                        $('#eproductname'+id).val('');      
                        break;        
                    }else{            
                        ada=false;             
                    }
                    if(!ada){
                        $('#xproduct'+id).val(data[0].id);
                        $('#icolor'+id).val(data[0].i_color);
                        $('#ecolor'+id).val(data[0].e_color_name);
                        $('#eproductname'+id).val(data[0].nama);
                        $('#nquantity'+id).focus();                            
                    }else{
                        $('#iproduct'+id).html('');
                        $('#iproduct'+id).val('');
                        $('#xproduct'+id).val('');
                    }
                }

            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    $("#tabledata").on("click", ".ibtnDel", function (event) {    
        $(this).closest("tr").remove();
        $('#jml').val(counter);
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
        $("#send").attr("hidden", false);
    });

    function konfirm() {
        var jml = $('#jml').val();
        if (($('#pengirim').val()!='' || $('#pengirim').val())) {
            if(jml==0){
                swal('Isi data item minimal 1 !!!');
                return false;
            }else{
                for(i=1;i<=jml;i++){
                    if($("#iproduct"+i).val()=='' || $("#eproductname"+i).val()=='' || $("#nquantity"+i).val()==''){
                        swal('Data item masih ada yang salah !!!');
                        return false;
                    }else{
                        return true;
                    } 
                }
            }
        }else{
            swal('Pengirim Harus Diisi!');
            return false;
        }
    }
</script>