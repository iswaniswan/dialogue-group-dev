<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <i class="fa fa-plus"></i> &nbsp; <?= $title; ?> <a href="#"
                onclick="show('<?= $folder; ?>/cform/','#main'); return false;"
                class="btn btn-info btn-sm pull-right"><i class="fa fa-list"></i> <?= $title_list; ?> </a>
            </div>
            <div class="panel-body table-responsive">
                <?php echo $this->pquery->form_remote_tag(array('url' => site_url($folder.'/cform/update'), 'update' => '#pesan', 'type' => 'post', 'class' => 'form-horizontal')); ?>
                <div id="pesan"></div>
                <div class="form-group">
                    <label class="col-md-12">Kode Barang</label>
                    <div class="col-sm-12">
                         <input type="text" id="iproduct" name="iproduct" class="form-control" required="" readonly value="<?php if($isi->i_product) echo $isi->i_product; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Nama Barang</label>
                    <div class="col-sm-12">
                        <input type="text" id="eproductname" name="eproductname" class="form-control" required="" readonly value="<?php if($isi->e_product_name) echo $isi->e_product_name; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-12">Kode Grade</label>
                    <div class="col-sm-12">
                        <input type="hidden" name="iproductgrade" id="iproductgrade" value="<?php if($isi->i_product_grade) echo $isi->i_product_grade; ?>">             
                        <input readonly class="form-control select2" name="eproductgradename" id="eproductgradename" value="<?php if($isi->e_product_gradename) echo $isi->e_product_gradename; ?>">
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kode Harga</th>
                            <th>Harga Netto</th>
                            <th>Harga Counter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input class="form-control" name="ipricegroup" id="ipricegroup" type="text" maxlength='2' value="<?php if($isi->i_price_group) echo $isi->i_price_group; ?>" readonly required></td>
                            <td><input class="form-control" name="vproductmill" id="vproductmill" value="" onkeyup="reformat(this);" required="">
                            <td><input class="form-control" name="vproductretail" id="vproductretail" value="<?php if($isi->v_product_retail) echo $isi->v_product_retail; ?>" autocomplete="off" type="text" onkeyup="reformat(this);" required></td>
                                <input type='hidden' name="nmargin" id="nmargin" value="">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" id="submit" class="btn btn-info btn-rounded btn-sm"> <i class="fa fa-plus"></i>&nbsp;&nbsp;Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    $("form").submit(function(event) {
        event.preventDefault();
        $("input").attr("disabled", true);
        $("select").attr("disabled", true);
        $("#submit").attr("disabled", true);
    });
    $(document).ready(function () {
        $('.select2').select2();
        $('#iproduct').select2({
            placeholder: 'Pilih Barang',
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
        }).on("change", function(e) {
            var kode = $('#iproduct').text();
            kode = kode.split("-");
            $('#eproductname').val(kode[1]);
        });
    });

    function hitung(){        
        if(document.getElementById("vproductretail").value!='' && document.getElementById("nmargin").value!=''){          
            retail=document.getElementById("vproductretail").value;          
            margin=document.getElementById("nmargin").value;          
            x=(retail*margin)/100;          
            z=retail-x;      
            document.getElementById("vproductmill").value=z;//formatcemua(z);
        }
    }

    function getharga(igroup){
        $.ajax({
            type: "post",
            data: {
                'igroup': igroup
            },
            url: '<?= base_url($folder.'/cform/getharga'); ?>',
            dataType: "json",
            success: function (data) {
                $('#ipricegroupco').val(data[0].i_price_groupco);
                $('#nmargin').val(data[0].n_margin);
                hitung();
            },
            error: function () {
                alert('Error :)');
            }
        });
    }
</script>