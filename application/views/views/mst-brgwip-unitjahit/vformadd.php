<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                    onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                    class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/simpan'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
            <div class="panel-body table-responsive">
                <div id="pesan"></div>
                <div class="form-group">
                    <label class="col-md-12">Unit Jahit</label>
                    <div class="col-sm-12">
                        <select name="ikodeunit"  id="ikodeunit" class="form-control select2" oonchange="getid(this.value);">
                            <option value="">-- Pilih Kelompok Barang --</option>
                            <?php foreach ($kodeunit as $r):?>
                            <option value="<?php echo $r->id.'||'.$r->kode_unit;?>"><?php echo $r->kode_unit." - ".$r->nama;?>
                            </option>
                            <?php endforeach; ?>
                            <input type="hidden" id="idunitjahit" name="idunitjahit" class="form-control" required= "" maxlength="30">
                        </select>
                        
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Kelompok Barang</label>
                    <div class="col-sm-12">
                        <select name="ikode" class="form-control select2">
                            <option value="">-- Pilih Kelompok Barang --</option>
                            <?php foreach ($kode as $r):?>

                            <option value="<?php echo $r->kode;?>"><?php echo $r->kode." - ".$r->nama;?></option>
                            <?php endforeach; ?>
                        </select>
                         <!-- <input type="hidden" name="iproductgrade" class="form-control" required="" maxlength="30" value="00"> -->
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Jenis Barang</label>
                    <div class="col-sm-12">
                        <select name="ikode2" class="form-control select2" onchange="getjenis(this.value);">
                            <option value="">-- Pilih Jenis Barang --</option>
                            <?php foreach ($kode2 as $r):?>

                            <option value="<?php echo $r->kode;?>"><?php echo $r->kode." - ".$r->nama;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i
                                class="fa fa-plus"></i>&nbsp;&nbsp;Simpan</button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="button" id="addrow" class="btn btn-info btn-rounded btn-sm"><i
                                class="fa fa-plus"></i>&nbsp;&nbsp;</button>

                    </div>
                </div>
                <input type="text" name="jml" id="jml">
            </div>

            <div class="panel-body table-responsive">
                <table id="tabledata" class="display table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Kode Barang</th>
                            <th width="20%">Nama Barang</th>
                            <th>Harga</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>
            </div>
            
        </div>
        </form>
    </div>
</div>
<script>
    // $(document).ready(function () {
    //     $('.select2').select2();
    //     $('#iproduct').select2({
    //     placeholder: 'Pilih Barang',
    //     allowClear: true,
    //     ajax: {
    //       url: '<#?= base_url($folder.'/cform/databrg'); ?>',
    //       dataType: 'json',
    //       delay: 250,
    //       processResults: function (data) {
    //         return {
    //           results: data
    //         };
    //       },
    //       cache: true
    //     }
    //   });
    // });
    $("form").submit(function (event) {
        event.preventDefault();
        // $("input").attr("disabled", true);
        // $("select").attr("disabled", true);
        // $("#submit").attr("disabled", true);
    });
    var counter = 0;
    $("#addrow").on("click", function () {
        counter++;
        document.getElementById("jml").value = counter;
        var newRow = $("<tr>");

        var cols = "";

        cols += '<td><select id="iproduct' + counter + '" class="form-control" name="iproduct' +counter + '" onchange="getproductname(' + counter + ');"></td>';
        cols += '<td><input type="text" id="eproductbasename' + counter +'" type="text" class="form-control" name="eproductbasename' + counter + '"></td>';
        cols += '<td><input type="text" id="harga' + counter + '" class="form-control" name="harga' + counter +'"/></td>';
        cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
        cols += '<td><input type="hidden" id="idprod' + counter +'" type="hidden" class="form-control" name="idprod' + counter + '"></td>';
        newRow.append(cols);
        $("#tabledata").append(newRow);

        $('#iproduct' + counter).select2({
            placeholder: 'Pilih Product',
            allowClear: true,
            ajax: {
                url: '<?= base_url($folder.'/cform/databrg'); ?>',
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
    $("#tabledata").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
        counter -= 1
        document.getElementById("jml").value = counter;

    });

    function getjenis(ikodeunit) {
        /*alert(iarea);*/
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getid');?>",
            data: "ikodeunit=" + ikodeunit,
            dataType: 'json',
            success: function (data) {
                $("#idunitjahit").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }

        })
    }
    function getid(ikode) {
        /*alert(iarea);*/
        $.ajax({
            type: "POST",
            url: "<?php echo site_url($folder.'/Cform/getid');?>",
            data: "ikode2=" + ikode2,
            dataType: 'json',
            success: function (data) {
                $("#ikode2").html(data.kop);
                /*$("#icustomer").val(data.sok);*/
                if (data.kosong == 'kopong') {
                    $("#submit").attr("disabled", true);
                } else {
                    $("#submit").attr("disabled", false);
                }
            },

            error: function (XMLHttpRequest) {
                alert(XMLHttpRequest.responseText);
            }

        })
    }

    function getproductname(id) {
        var iproduct = $('#iproduct' + id).val();
        $.ajax({
            type: "post",
            data: {
                'iproduct': iproduct
            },
            url: '<?= base_url($folder.'/cform/getproductname'); ?>',
            dataType: "json",
            success: function (data) {
                $('#eproductbasename' + id).val(data[0].nama_brg);
                $('#idprod'+id).val(data[0].id);
            },
            error: function () {
                alert('Error :)');
            }
        });
    }

    $('#ikodeunit').on('change',function(){
        var id = $("#ikodeunit").val();
        var res = id.split("||");
        $('#idunitjahit').val(res[0]);
    })
</script>