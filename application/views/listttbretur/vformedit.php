<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-pencil"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i></a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-md-3">Nomor TTB</label>
                        <label class="col-md-3">Tanggal TTB</label>
                        <label class="col-md-3">Tanggal Terima Sales</label>
                        <label class="col-md-3">Nilai Kotor</label>
                        <div class="col-sm-3">
                            <input id="ittb" name="ittb" class="form-control" required="" readonly value="<?= $isi->i_ttb;?>">
                            <input type="hidden" id="ibbm" name="ibbm" value="<?= $isi->i_bbm; ?>">
                            <input type='hidden' id="dfrom" name="dfrom" value="<?= $dfrom; ?>">
                            <input type='hidden' id="dto" name="dto" value="<?= $dto; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "dttb" name="dttb" class="form-control date" required="" onchange="cektanggal();" readonly value="<?= $isi->d_ttb;?>">
                            <input hidden id="bttb" name="bttb" value="<?= date('m', strtotime($isi->d_ttb)); ?>">
                            <input hidden id="tglttb" name="tglttb" value="<?= $isi->d_ttb;?>">
                            <input type="hidden" id="nttbyear" name="nttbyear" value="<?= $tahun;?>">
                        </div>
                        <div class="col-sm-3">
                            <input id= "dreceive1" name="dreceive1" class="form-control date" onchange="testes()" readonly value="<?= $isi->d_receive1; ?>">
                            <input type="hidden" id="tglreceive" name="tglreceive" value="<?= $isi->d_receive1; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id="vttbgross" readonly name="vttbgross" class="form-control" value="<?= number_format($isi->v_ttb_gross); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-6">Pelanggan</label>
                        <label class="col-md-3">Discount 1</label>
                        <label class="col-md-3">Nilai Discount 1</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" id="icustomer" name="icustomer" onchange="getdetailcus(this.value);">
                                <option value="<?= $isi->i_customer; ?>"><?= $isi->e_customer_name; ?></option>
                            </select>
                            <input type="hidden" id="ipricegroup" name="ipricegroup" value="<?= $isi->i_price_group; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" id="nttbdiscount1" name="nttbdiscount1" value="<?= $isi->n_ttb_discount1; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vttbdiscount1" name="vttbdiscount1" value="<?= number_format($isi->v_ttb_discount1); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">NPWP</label>
                        <label class="col-md-3">Tahun TTB</label>
                        <label class="col-md-3">Discount 2</label>
                        <label class="col-md-3">Nilai Discount 2</label>
                        <div class="col-sm-3">
                            <input class="form-control" id="ecustomerpkpnpwp" name="ecustomerpkpnpwp" value="<?= $isi->e_customer_pkpnpwp; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly="" type="text" id="nttbyear" name="nttbyear" value="<?= $tahun; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" id="nttbdiscount2" name="nttbdiscount2" value="<?= $isi->n_ttb_discount2; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vttbdiscount2" name="vttbdiscount2" value="<?= number_format($isi->v_ttb_discount2); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Area</label>                        
                        <label class="col-md-3">Keterangan</label>
                        <label class="col-md-3">Discount 3</label>
                        <label class="col-md-3">Nilai Discount 3</label>
                        <div class="col-sm-3">
                            <input readonly id="eareaname" name="eareaname" class="form-control" value="<?= $isi->e_area_name; ?>">
                            <input id="iarea" name="iarea" type="hidden" value="<?= $isi->i_area; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input id="fttbplusppn" name="fttbplusppn" type="hidden" value="<?= $isi->f_ttb_plusppn; ?>">
                            <input id="fttbplusdiscount" name="fttbplusdiscount" type="hidden" value="<?= $isi->f_ttb_plusdiscount; ?>">
                            <input id="fttbpkp" name="fttbpkp" type="hidden" value="<?= $isi->f_ttb_pkp; ?>">
                            <input class="form-control" id="eremark" name="eremark" value="<?= $isi->e_ttb_remark; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" id="nttbdiscount3" name="nttbdiscount3" value="<?= $isi->n_ttb_discount3; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vttbdiscount3" name="vttbdiscount3" value="<?= number_format($isi->v_ttb_discount3); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3">Salesman</label>
                        <label class="col-md-3">Alasan Retur</label>
                        <label class="col-md-3">Discount Total</label>
                        <label class="col-md-3">Nilai Bersih</label>
                        <div class="col-sm-3">
                            <select class="form-control select2" id="isalesman" name="isalesman" onchange="setsales(this.value);">
                                <option value="<?= $isi->i_salesman; ?>"><?= $isi->e_salesman_name; ?></option>
                            </select>
                            <input type="hidden" class="form-control" id="isalesmanx" name="isalesmanx" value="<?= $isi->i_salesman; ?>">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control select2" id="ialasanretur" name="ialasanretur">
                                <option value="<?= $isi->i_alasan_retur; ?>"><?= $isi->e_alasan_returname; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" id="vttbdiscounttotal" name="vttbdiscounttotal" value="<?= $isi->v_ttb_discounttotal; ?>">
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" readonly id="vttbnetto" name="vttbnetto" value="<?= number_format($isi->v_ttb_netto); ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-5 col-sm-8">
                            <?php if(check_role($i_menu, 3) && ($isi->ibbm == '') && ($isi->f_bbm_cancel != 'f') && ($isi->f_ttb_cancel != 'f')){?>
                                <button type="submit" id="submit" class="btn btn-success btn-rounded btn-sm" onclick="return dipales(parseFloat(document.getElementById('jml').value));"> <i class="fa fa-save"></i>&nbsp;&nbsp;Update</button>
                                &nbsp;&nbsp;
                            <?php } ?>
                            <button type="button" class="btn btn-inverse btn-rounded btn-sm" onclick='show("<?= $folder;?>/cform/view/<?= $xarea."/".$dfrom."/".$dto;?>","#main")'> <i class="fa fa-arrow-circle-left"></i>&nbsp;&nbsp;Kembali</button>
                            <?php if(check_role($i_menu, 3) && ($isi->ibbm == '') && ($isi->f_bbm_cancel != 'f') && ($isi->f_ttb_cancel != 'f')){?>
                                &nbsp;&nbsp;
                                <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Item</button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tabledata" class="table color-table inverse-table table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 4%;">No</th>
                                    <th class="text-center" style="width: 10%;">Kode</th>
                                    <th class="text-center" style="width: 30%;">Nama Barang</th>
                                    <th class="text-center">Motif</th>
                                    <th class="text-center">Qty Nota</th>
                                    <th class="text-center">Qty Retur</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php               
                                $i=0;
                                if($detail){
                                    foreach($detail as $row){ 
                                        $i++;
                                        $harga      = number_format($row->v_unit_price);
                                        $nquantity  = number_format($row->n_quantity,0);
                                        $nqtyrec    = number_format($row->n_quantity_receive,0);
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?= $i;?>
                                                <input type="hidden" class="form-control" readonly type="text" id="baris<?= $i;?>" name="baris<?= $i;?>" value="<?= $i;?>">
                                                <input class="form-control" type="hidden" id="motif<?= $i;?>" name="motif<?= $i;?>" value="<?= $row->i_product1_motif;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="iproduct<?= $i;?>" name="iproduct<?= $i;?>" value="<?= $row->i_product1;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="eproductname<?= $i;?>" name="eproductname<?= $i;?>" value="<?= $row->e_product_name;?>">
                                            </td>
                                            <td>
                                                <input class="form-control" readonly type="text" id="emotifname<?= $i;?>" name="emotifname<?= $i;?>" value="<?= $row->e_product_motifname;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" readonly type="text" id="ndeliver<?= $i;?>" name="ndeliver<?= $i;?>" value="<?= $nquantity;?>">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" onkeypress="return hanyaAngka(event);" text-right type="text" id="nquantity<?= $i;?>" name="nquantity<?= $i;?>" value="<?= $nquantity;?>" onkeyup="cekval(this.value); reformat(this)">
                                            </td>
                                            <td>
                                                <input class="form-control text-right" onkeypress="return hanyaAngka(event);" type="text" id="vunitprice<?= $i;?>" name="vunitprice<?= $i;?>" value="<?= $harga;?>" onkeyup="cekval(this.value); reformat(this)">
                                            </td>
                                            <td>
                                                <input class="form-control" type="text" id="eremark<?= $i;?>" name="eremark<?= $i;?>" value="<?= $row->e_ttb_remark;?>">
                                            </td>
                                            <td class="text-center">
                                                <?php if(check_role($i_menu,4) && ($isi->ibbm == '') && ($isi->f_bbm_cancel != 'f') && ($isi->f_ttb_cancel != 'f')){ ?>
                                                    <button type="button" onclick="hapusitem('<?= $i;?>'); return false;" title="Delete" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                <input type="hidden" name="jml" id="jml" value="<?= $i;?>">
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
<script>
    function hapusitem(x) {
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
                        'product'           : $('#iproduct'+x).val(),
                        'motif'             : $('#motif'+x).val(),
                        'iarea'             : $('#iarea').val(),
                        'ittb'              : $('#ittb').val(),
                        'nttbyear'          : $('#nttbyear').val(),
                        'nttbdiscount1'     : parseFloat(formatulang($("#nttbdiscount1").val())),
                        'nttbdiscount2'     : parseFloat(formatulang($("#nttbdiscount2").val())),
                        'nttbdiscount3'     : parseFloat(formatulang($("#nttbdiscount3").val())),
                        'vttbdiscount1'     : parseFloat(formatulang($("#vttbdiscount1").val())),
                        'vttbdiscount2'     : parseFloat(formatulang($("#vttbdiscount2").val())),
                        'vttbdiscount3'     : parseFloat(formatulang($("#vttbdiscount3").val())),
                        'vttbdiscounttotal' : parseFloat(formatulang($("#vttbdiscounttotal").val())),
                        'vttbnetto'         : parseFloat(formatulang($("#vttbnetto").val())),
                        'vttbgross'         : parseFloat(formatulang($("#vttbgross").val())),
                        'jml'               : $("#jml").val(),
                        'dttb'              : $("#dttb").val(),
                        'cust'              : $("#icustomer").val(),
                        'dfrom'             : $("#dfrom").val(),
                        'dto'               : $("#dto").val(),
                        'dreceive1'         : $("#dreceive1").val(),
                        'ettbremark'        : $("#eremark").val(),
                        'ecustomerpkpnpwp'  : $("#ecustomerpkpnpwp").val(),
                        'dreceive1'         : $("#dreceive1").val(),
                        'ibbm'              : $("#ibbm").val(),
                    },
                    url: '<?= base_url($folder.'/cform/deleteitem'); ?>',
                    dataType: "json",
                    success: function (data) {
                        swal("Dihapus!", "Data berhasil dihapus :)", "success");
                        show('<?= $folder;?>/cform/edit/<?= $id.'/'.$iarea.'/'.$dfrom.'/'.$dto.'/'.$xarea.'/'.$tahun;?>','#main');     
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

    var xx = $('#jml').val();
    $("#addrow").on("click", function () {
        xx++;
        /*document.getElementById("jml").value = xx;*/
        $('#jml').val(xx);
        var newRow = $("<tr>");
        var cols = "";
        cols += '<td class="text-center">'+xx+'<input type="hidden" id="baris'+xx+'" class="form-control" name="baris'+xx+'" value="'+xx+'"><input type="hidden" id="motif'+xx+'" name="motif'+xx+'" value=""><input type="hidden" id="dnota'+xx+'" name="dnota'+xx+'" value=""></td>';
        cols += '<td><select id="iproduct'+xx+ '" class="form-control" name="iproduct'+xx+'" onchange="detail('+xx+');" value=""></td>';
        cols += '<td><input id="eproductname'+xx+'" class="form-control" name="eproductname'+xx+'" value="" readonly></td>';
        cols += '<td><input class="form-control" type="text" id="emotifname'+xx+'" class="form-control" name="emotifname'+xx+'" value="" readonly></td>';
        cols += '<td><input class="form-control text-right" readonly type="text" id="ndeliver'+xx+'" name="ndeliver'+xx+'" value="0"></td>';
        cols += '<td><input class="form-control text-right" onkeypress="return hanyaAngka(event);" type="text" id="nquantity'+xx+'" name="nquantity'+xx+'" value="0" onkeyup="cekval(this.value); reformat(this);"></td>';
        cols += '<td><input class="form-control text-right" onkeypress="return hanyaAngka(event);" type="text" id="vunitprice'+xx+'" name="vunitprice'+xx+'" value="0" onkeyup="cekval(this.value); reformat(this);"></td>';
        cols += '<td><input class="form-control" type="text" id="eremark'+xx+'" name="eremark'+xx+'" value=""></td>';
        cols += '<td></td>';
        /*cols += '<td><button type="button" id="addrow" title="Delete" class="ibtnDel btn btn btn-danger"><i class="fa fa-trash"></i></button></td>';*/
        newRow.append(cols);
        $("#tabledata").append(newRow);
        $('#iproduct'+xx).select2({
            placeholder: 'Cari Kode / Nama',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/product/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term,
                        icustomer : $('#icustomer').val(),
                        ipricegroup : $('#ipricegroup').val(),
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

    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();       
        xx -= 1
        document.getElementById("jml").value = xx;
    });

    $(document).ready(function () {
        ceko();
        /*cekval(0);*/
        showCalendar('.date');
        $('.select2').select2();
        $('#icustomer').select2({
            placeholder: 'Cari Customer',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/customer/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term,
                        iarea   : $('#iarea').val(),
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

        $('#isalesman').select2({
            placeholder: 'Cari Salesman',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/salesman/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term,
                        iarea   : $('#iarea').val(),
                        dttb    : $('#dttb').val(),
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

        $('#ialasanretur').select2({
            placeholder: 'Cari Alasan',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/alasan/'); ?>',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    var query   = {
                        q       : params.term,
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

    function setsales(id) {
        $('#isalesmanx').val(id);
    }

    function getdetailcus(id){
        $.ajax({
            type: "post",
            data: {
                'icustomer' : id,
                'iarea'     : $('#iarea').val(),
            },
            url: '<?= base_url($folder.'/cform/detailcustomer'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ipricegroup').val(data[0].i_price_group);
                $('#ecustomerpkpnpwp').val(data[0].e_customer_pkpnpwp);
                if (data[0].e_customer_pkpnpwp!='') {
                    $('#fttbpkp').val('t');
                }else{
                    $('#fttbpkp').val('f');
                }
                $('#select2-isalesman-container').html(data[0].e_salesman_name);
                $('#isalesmanx').val(data[0].i_salesman);
                $("#nttbdiscount1").val(data[0].n_customer_discount1);
                $("#nttbdiscount2").val(data[0].n_customer_discount2);
                $("#nttbdiscount3").val(data[0].n_customer_discount3);
                $("#fttbplusppn").val(data[0].f_customer_plusppn);
                $("#fttbplusdiscount").val(data[0].f_customer_plusdiscount);
                ceko();
            },
            error: function () {
                swal('Error :)');
            }
        });
    }

    function cekval(input){
        var jml   = parseFloat(document.getElementById("jml").value); 
        var num = input.replace(/\,/g,'');
        if(!isNaN(num)){
            for(j=1;j<=jml;j++){
                if(document.getElementById("nquantity"+j).value=='')
                  document.getElementById("nquantity"+j).value='0';
              var jml   = parseFloat(document.getElementById("jml").value);
              var totdis    = 0;
              var totnil    = 0;
              var hrg       = 0;
              var ndis1 = parseFloat(formatulang(document.getElementById("nttbdiscount1").value));
              var ndis2 = parseFloat(formatulang(document.getElementById("nttbdiscount2").value));
              var ndis3 = parseFloat(formatulang(document.getElementById("nttbdiscount3").value));
              var vdis1 = 0;
              var vdis2 = 0;
              var vdis3 = 0;
              for(i=1;i<=jml;i++){
                  document.getElementById("ndeliver"+i).value=document.getElementById("nquantity"+i).value;
                  var hrgtmp  = parseFloat(formatulang(document.getElementById("vunitprice"+i).value))*parseFloat(formatulang(document.getElementById("nquantity"+i).value));
                  hrg           = hrg+hrgtmp;
              }
              vdis1=vdis1+((hrg*ndis1)/100);
              vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
              vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
              vdistot   = vdis1+vdis2+vdis3;
              vhrgreal= hrg-vdistot;
              document.getElementById("vttbdiscount1").value=formatcemua(vdis1);
              document.getElementById("vttbdiscount2").value=formatcemua(vdis2);
              document.getElementById("vttbdiscount3").value=formatcemua(vdis3);
              document.getElementById("vttbdiscounttotal").value=formatcemua(vdistot);
              document.getElementById("vttbnetto").value=formatcemua(vhrgreal);
              document.getElementById("vttbgross").value=formatcemua(hrg);
          }
      }else{ 
          alert('input harus numerik !!!');
          input = input.substring(0,input.length-1);
      }
  }

  function ceko(){
    var jml   = parseFloat($("#jml").val()); 
    for(j=1;j<=jml;j++){
        if(document.getElementById("nquantity"+j).value=='')
          document.getElementById("nquantity"+j).value='0';
      var jml   = parseFloat(document.getElementById("jml").value);
      var totdis    = 0;
      var totnil    = 0;
      var hrg       = 0;
      var ndis1 = parseFloat(formatulang(document.getElementById("nttbdiscount1").value));
      var ndis2 = parseFloat(formatulang(document.getElementById("nttbdiscount2").value));
      var ndis3 = parseFloat(formatulang(document.getElementById("nttbdiscount3").value));
      var vdis1 = 0;
      var vdis2 = 0;
      var vdis3 = 0;
      for(i=1;i<=jml;i++){
        document.getElementById("ndeliver"+i).value=document.getElementById("nquantity"+i).value;
        var hrgtmp  = parseFloat(formatulang(document.getElementById("vunitprice"+i).value))*parseFloat(formatulang(document.getElementById("nquantity"+i).value));
        hrg           = hrg+hrgtmp;
    }
    vdis1=vdis1+((hrg*ndis1)/100);
    vdis2=vdis2+(((hrg-vdis1)*ndis2)/100);
    vdis3=vdis3+(((hrg-(vdis1+vdis2))*ndis3)/100);
    vdistot   = vdis1+vdis2+vdis3;
    vhrgreal= hrg-vdistot;
    document.getElementById("vttbdiscount1").value=formatcemua(vdis1);
    document.getElementById("vttbdiscount2").value=formatcemua(vdis2);
    document.getElementById("vttbdiscount3").value=formatcemua(vdis3);
    document.getElementById("vttbdiscounttotal").value=formatcemua(vdistot);
    document.getElementById("vttbnetto").value=formatcemua(vhrgreal);
    document.getElementById("vttbgross").value=formatcemua(hrg);
}
}

function detail(id){
    ada=false;
    var a = $('#iproduct'+id).val();
    var x = $('#jml').val();
    for(i=1;i<=x;i++){            
        if((a == $('#iproduct'+i).val()) && (i!=x)){
            swal ("kode : "+a+" sudah ada !!!!!");            
            ada=true;            
            break;        
        }else{            
            ada=false;             
        }
    }
    if(!ada){
        var iproduct    = $('#iproduct'+id).val();
        $.ajax({
            type: "post",
            data: {
                'iproduct'  : iproduct,
                'icustomer' : $('#icustomer').val(),
                'ipricegroup' : $('#ipricegroup').val(),
            },
            url: '<?= base_url($folder.'/cform/detailproduct'); ?>',
            dataType: "json",
            success: function (data) {
                $('#eproductname'+id).val(data[0].nama);
                $('#vunitprice'+id).val(formatcemua(data[0].harga));
                $('#emotifname'+id).val(data[0].namamotif);
                $('#motif'+id).val(data[0].motif);
                $('#nquantity'+id).focus();
            },
            error: function () {
                swal('Error :)');
            }
        });
    }else{
        $('#iproduct'+id).html('');
        $('#iproduct'+id).val('');
    }
}

function dipales(a){
    if((document.getElementById("dreceive").value!='') &&
        (document.getElementById("iarea").value!='')) {
        if(a==0){
            swal('Isi data item minimal 1 !!!');
            return false;
        }else{
            for(i=1;i<=a;i++){
                if((document.getElementById("iproduct"+i).value=='') || (document.getElementById("eproductname"+i).value=='') || (document.getElementById("nreceive"+i).value=='')){
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

$("form").submit(function(event) {
    event.preventDefault();
    $("input").attr("disabled", true);
    $("select").attr("disabled", true);
    $("#submit").attr("disabled", true);
    $("#addrow").attr("disabled", true);
    $("#refresh").attr("disabled", true);
});

function cektanggal(){
    dspb = $('#dttb').val();
    bspb = $('#bttb').val();
    dtmp = dspb.split('-');
    per  = dtmp[2]+dtmp[1]+dtmp[0];
    bln  = dtmp[1];
    if( (bspb!='') && (dspb!='') ){
        if(bspb != bln){
            swal("Tanggal TTB tidak boleh dalam bulan yang berbeda !!!");
            $("#dttb").val('');
        }
    }

    dsj     = $('#dttb').val();
    dsjrec  = $('#dreceive1').val();
    sj      = dsj.split('-');
    tglsj   = sj[2]+sj[1]+sj[0];
    rc      = dsjrec.split('-');
    tglrec  = rc[2]+rc[1]+rc[0];
    if (tglrec<tglsj) {
        swal("Tanggal TTB Receive tidak boleh lebih kecil dari tanggal SJR !!!");
        $("#dreceive1").val('');
    }
}

function testes(){
    var a=$("#dttb").val();
    var b=$("#dreceive1").val();
    if(a!=''){
        a=a.split('-');
        a1=a[0];
        a2=a[1];
        a3=a[2];
        a=a3+a2+a1;
        a=parseFloat(a);
        if(b!=''){
            b=b.split('-');
            b1=b[0];
            b2=b[1];
            b3=b[2];
            b=b3+b2+b1;
            b=parseFloat(b);
            if(b<a){
                swal("Tanggal terima sales harus lebih besar dari tanggal TTB !!!!!");
                $("#dreceive1").val('');
            }
        }
    }
}
</script>