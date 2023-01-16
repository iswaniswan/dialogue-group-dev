<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></i>&nbsp; <?= $title_list; ?></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Nomor Memo</label>
                        <label class="col-md-2">Tanggal Permintaan</label>
                        <label class="col-md-3">Tujuan Keluar</label>
                        <label class="col-md-4">Tujuan Departemen/Partner</label>
                       
                        <div class="col-sm-3">
                            <input type="text" id="imemo" name="imemo" class="form-control" value="<?= $data->i_memo; ?>" readonly>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dmemo" name="dmemo" class="form-control date" value="<?= $data->d_memo; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="itujuan" id="itujuan" class="form-control select2" onchange="departemen(this.value);">
                                <option value=""></option>
                                <?php if ($tujuan) {
                                    foreach ($tujuan->result() as $key) { ?>
                                        <option value="<?= $key->i_tujuan;?>" <?php if ($data->i_tujuan_keluar==$key->i_tujuan) {
                                            echo "selected";
                                        }?>><?= $key->e_tujuan_name;?></option> 
                                    <?php }
                                } ?>  
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="idepartemen" id="idepartemen" class="form-control select2">
                                <option value="<?= $data->i_departement;?>"><?= $data->dep;?></option>
                            </select>
                        </div>
                    </div>
                    <?php if ($data->pic_eks=='' || $data->pic_eks==null) {
                        $hidden = 'hidden="true"';
                    }else{
                        $hidden = '';
                    }?>
                    <div class="form-group row">
                        <label class="col-sm-4">Permintaan Ke Gudang</label>
                        <label class="col-sm-2">Perkiraan Pengembalian</label>
                        <label class="col-sm-3">PIC Internal</label>
                        <label <?= $hidden;?> id="lepic" class="col-sm-3">PIC Eksternal</label>
                        <div class="col-sm-4">
                            <input type="text" id="kodelokasi" name="kodelokasi" class="form-control" value="<?= $data->e_lokasi_name; ?>" readonly>
                            <input type="hidden" id="ikodelokasi" name="ikodelokasi" class="form-control" value="<?= $data->i_kode_lokasi; ?>">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="dback" name="dback" class="form-control date" value="<?= $data->d_back; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <select name="ppic" id="ppic" class="form-control select2">
                                <option value="<?= $data->pic;?>"><?= $data->e_nama_karyawan;?></option>
                            </select>
                        </div>
                        <div <?= $hidden;?> id="pice" class="col-sm-3">
                            <input type="text" id="epic" name="epic" class="form-control" placeholder="Nama PIC" value="<?= $data->pic_eks; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-12">Keterangan</label>
                        <div class="col-sm-12">
                            <textarea id= "eremark" name="eremark" class="form-control"><?= $data->e_remark; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-12">
                        
                                    <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return konfirm();"><i class="fa fa-save" ></i>&nbsp;&nbsp;Update</button>&nbsp;&nbsp;
                               
                                <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick="show('<?= $folder; ?>/cform/','#main'); return false;"><i class="ti-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>&nbsp;&nbsp;
                
                                    <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>&nbsp;&nbsp;
                         
                        </div>
                    </div>
                </div>
                <?php 
                $counter = 0; 
                if ($detail) {?>
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                    <th class="text-center" style="width: 3%;">No</th>
                                    <th class="text-center" style="width: 10%;">Kode Barang</th>
                                    <th class="text-center" style="width: 37%;">Nama Barang Jadi</th>
                                    <th class="text-center">Warna</th>
                                    <th class="text-center" style="width: 10%;">Jumlah Stok Tersedia</th>
                                    <th class="text-center" style="width: 10%;">Qty Permintaan</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center" style="width: 5%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    foreach ($detail as $row) {
                                        $counter++;?>
                                        <tr>
                                            <td class="text-center">
                                                <spanx id="snum<?= $counter;?>"><?= $counter;?></spanx>
                                                
                                            </td>
                                            <td>
                                                <input value="<?= $row->i_product_motif;?>" readonly="" type="text" id="xproduct<?= $counter;?>" class="form-control" name="xproduct<?= $counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->e_product_basename;?>" readonly="" type="text" readonly id="eproductname<?= $counter;?>" class="form-control" name="eproductname<?=$counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->i_color;?>" type="hidden" id="icolorproduct<?= $counter;?>" class="form-control" name="icolorproduct<?= $counter;?>">
                                                <input value="<?= $row->e_color_name;?>" readonly="" type="text" readonly id="ecolorproduct<?= $counter;?>" class="form-control" name="ecolorproduct<?= $counter;?>">
                                            </td>
                                            <td>
                                                <input value="<?= $row->qty_reserved;?>" type="text" id="nstok<?= $counter;?>" class="form-control text-right" name="nstok<?= $counter;?>" readonly>
                                            </td>
                                            <td>
                                                <input value="<?= $row->n_qty;?>" type="text" id="nquantity<?= $counter;?>" class="form-control text-right" name="nquantity<?= $counter;?>" onkeypress="return hanyaAngka(event);">
                                            </td>
                                            <td>
                                                <input value="<?= $row->e_remark;?>" type="text" id="edesc<?= $counter;?>" class="form-control" name="edesc<?=$counter;?>">
                                            </td>
                                            <td>
                                                <button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="ti-trash"></i></button>
                                            </td>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
            <input type="hidden" name="jml" id="jml" readonly value="<?= $counter;?>">
            </form>
        </div>
    </div>
</div>
</div>

<script>
    $(document).ready(function () {
        $('.select2').select2();
        //showCalendar('.date');
        //showCalendar('.date',1830,0);
        showCalendar('#dmemo',1830,0);
        showCalendar('#dback',0,1830);

        $('#ppic').select2({
            placeholder: 'Pilih PIC',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/ppic'); ?>',
                dataType: 'json',
                delay: 250,          
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        })

        $('#itujuan').select2({
            placeholder: 'Pilih Tujuan Keluar',
        })

        $('#idepartemen').select2({
            placeholder: 'Pilih Departement/Partner',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/departemen'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query = {
                        q: params.term,
                        itujuan : $('#itujuan').val(),
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

    function departemen(itujuan) {
        if (itujuan == "") {
            $("#idepartemen").attr("disabled", true);
        } else {
            $("#idepartemen").attr("disabled", false);
        }

        if (itujuan=='1') {
            $("#pice").attr("hidden", true);
            $("#lepic").attr("hidden", true);
            //$("#lepicx").attr("hidden", false);
        }else{
            $("#pice").attr("hidden", false);
            $("#lepic").attr("hidden", false);
            //$("#lepicx").attr("hidden", true);
        }
        $("#idepartemen").val("");
        $("#idepartemen").html("");
    }

    var counter = $('#jml').val();
    var counterx = counter-1;
    $("#addrow").on("click", function () {
        counter++;
        counterx++;
        $("#tabledata").attr("hidden", false);
        //var iproduct = $('#iproduct'+counterx).val();
        count=$('#tabledata tr').length;
        // if ((iproduct==''||iproduct==null)&&(count>1)) {
        //     swal('Isi dulu yang masih kosong!!');
        //     counter = counter-1;
        //     counterx = counterx-1;
        //     return false;
        // }
        $('#jml').val(counter);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="text-center"><spanx id="snum'+counter+'">'+count+'</spanx></td>';
        cols += '<td><input type="text" readonly id="xproduct'+ counter + '" class="form-control" name="xproduct' + counter + '"><input type="hidden" readonly id="eproductname'+ counter + '" class="form-control" name="eproductname' + counter + '"></td>';
        cols += '<td><select type="text" id="iproduct'+ counter + '" class="form-control" name="iproduct'+ counter + '" onchange="getproduct('+ counter + ');"</td>';
        cols += '<td><input type="hidden" id="icolorproduct'+ counter + '" class="form-control" name="icolorproduct'+ counter + '"><input type="text" readonly id="ecolorproduct'+ counter + '" class="form-control" name="ecolorproduct'+ counter + '"></td>';
        cols += '<td><input type="text" id="nstok'+ counter + '" class="form-control text-right" name="nstok'+ counter + '" value="0" readonly></td>';
        cols += '<td><input type="text" id="nquantity'+ counter + '" class="form-control text-right" name="nquantity'+ counter + '" value="0" onkeypress="return hanyaAngka(event);"></td>';
        cols += '<td><input type="text" id="edesc'+ counter + '" class="form-control" name="edesc' + counter + '"></td>';
        cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="ti-trash"></i></button></td>';

        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#iproduct'+ counter).select2({
            placeholder: 'Cari Berdasarkan Nama / Kode',
            templateSelection: formatSelection,
            allowClear: true,
            type: "POST",
            ajax: {          
              url: '<?= base_url($folder.'/cform/dataproduct/'); ?>',
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

    function formatSelection(val) {
            return val.name;
    }

    function getproduct(id){
        ada=false;
        var iproduct = $('#iproduct'+id).val();
        var fields = iproduct.split('|');
        var a = fields[0];
        var icolor = fields[1];
        var ikodelokasi = $('#ikodelokasi').val();
        //var a = $('#iproduct'+id).val();
        //swal(a + icolor + ikodelokasi);
        var x = $('#jml').val();
            //var iproduct = $('#iproduct'+id).val();
            $.ajax({
                type: "post",
                data: {
                    'iproduct'  : a,
                    'icolor'  : icolor,
                    'ikodelokasi' : ikodelokasi,
                },
                url: '<?= base_url($folder.'/cform/getproduct'); ?>',
                dataType: "json",
                success: function (data) {
                    $('#xproduct'+id).val(formatcemua(data[0].i_product_motif));
                    $('#icolorproduct'+id).val(formatcemua(data[0].i_color));
                    $('#ecolorproduct'+id).val(formatcemua(data[0].e_color_name));
                    $('#eproductname'+id).val(formatcemua(data[0].e_product_basename));
                    $('#nstok'+id).val((data[0].qty_reserved));
                    $('#nquantity'+id).focus();
                    for(i=1;i<=x;i++){
                        if((a == $('#xproduct'+i).val()) && (i!=id)){
                            swal ("kode Barang : "+a+" sudah ada !!!!!");            
                            ada=true;            
                            break;        
                        }else{            
                            ada=false;             
                        }
                    }
                    if(!ada){

                    }else{
                        $('#iproduct'+id).html('');
                        $('#iproduct'+id).val('');
                        $('#xproduct'+id).val('');
                        $('#icolorproduct'+id).val('');
                        $('#ecolorproduct'+id).val('');
                        $('#nstok'+id).val('');
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
</script>